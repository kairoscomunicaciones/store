<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Handler;

use Address;
use Carrier;
use Cart;
use CartRule;
use Configuration;
use Context;
use Country;
use Currency;
use Customer;
use Db;
use Group;
use Guest;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CustomizationHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Handler\Payment\Web\AmazonPayManager;
use MrAPPs\MrShopApi\Service\CodFeeHelper;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use MrAPPs\MrShopApi\Service\PriceFormatter;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\FermopointUtils;
use Product;
use Shop;
use Smarty_Variable;
use StockAvailable;
use Tools;
use Validate;

class CartHandler
{
    private $context;

    private $module;

    private $idGuest;

    public $priceFormatter;

    public $dataHandler;

    private $bookingHandler;

    private $fidelityHandler;

    public function __construct($idGuest = null, $dataHandler = null)
    {
        $this->dataHandler     = !$dataHandler ? new DataHandler() : $dataHandler;
        $this->module          = ApiUtils::getModule();
        $this->idGuest         = $idGuest;
        $this->context         = Context::getContext();
        $this->priceFormatter  = new PriceFormatter();
        $this->bookingHandler  = new BookingHandler();
        $this->fidelityHandler = ApiUtils::getFidelityHandler();
        $this->customizationHandler = new CustomizationHandler();
        $this->externalModuleService = new ExternalModuleService();
    }

    private function validateCoupon($couponCode)
    {
        if (empty($couponCode)) {
            ResponseHandler::badRequest($this->module->l('Invalid coupon', 'carthandler'));
        }
    }

    public function applyCoupon($customerId, $couponCode)
    {
        $this->validateCoupon($couponCode);

        $cart = $this->getCart($customerId, false);
        if ($cart == null) {
            ResponseHandler::badRequest($this->module->l('Cart not found', 'carthandler'));
        }

        $cartRules = CartRule::getCartsRuleByCode($couponCode, $this->context->language->id);
        if (count($cartRules) > 0) {
            $cartRuleId = $cartRules[0]['id_cart_rule'];
            $cartRule = new CartRule($cartRuleId);
            $this->context->cart = $cart;
            $errorMessage = $cartRule->checkValidity($this->context);

            if (empty($errorMessage)) {
                if (!$cart->addCartRule($cartRuleId)) {
                    ResponseHandler::badRequest($this->module->l('You have already used this coupon', 'carthandler'));
                }

                return $cart;
            } else {
                ResponseHandler::badRequest($errorMessage);
            }
        } else {
            ResponseHandler::badRequest($this->module->l('Coupon not found', 'carthandler'));
        }
    }

    public function removeCoupon($customerId, $couponCode)
    {
        $this->validateCoupon($couponCode);

        $cart = $this->getCart($customerId, false);
        if ($cart == null) {
            ResponseHandler::badRequest($this->module->l('Cart not found', 'carthandler'));
        }

        $cartRules = CartRule::getCartsRuleByCode($couponCode, $this->context->language->id);

        if (count($cartRules) > 0) {
            $cartRuleId = (int) $cartRules[0]['id_cart_rule'];

            $actualCartRules = $cart->getCartRules();

            $found = false;
            /** @var CartRule $actualCartRule */
            foreach ($actualCartRules as $actualCartRule) {
                if ((int) $actualCartRule['id_cart_rule'] === $cartRuleId) {
                    $found = true;
                }
            }

            if ($found) {
                if (!$cart->removeCartRule($cartRuleId)) {
                    ResponseHandler::badRequest($this->module->l('Coupon removal failed', 'carthandler'));
                }

                return $cart;
            } else {
                ResponseHandler::notFound($this->module->l('Coupon not found', 'carthandler'));
            }
        }
    }

    public function getCart($id_customer, $createCartIfNeeded = true, $_currencyId = null)
    {
        $cart = null;

        // If customer is authenticated
        if ($id_customer != null) {
            if ($this->idGuest == null) {
                // If no id_guest is set, just retrieve customer last cart
                $cart = new Cart((int) Cart::lastNoneOrderedCart((int) $id_customer));

                if (!Validate::isLoadedObject($cart) && $createCartIfNeeded) {
                    $this->idGuest = $this->createGuestIfMissing($this->idGuest, $id_customer);
                    $cart = $this->generateNewCart($this->idGuest, $id_customer);
                }
            } else {
                $useGuestCart = true;

                // If id_guest is set, we have to set the id_customer to the guest cart
                $id_cart = $this->getCartIdByGuest($id_customer);
                $cart = new Cart((int) $id_cart);

                if ($id_cart === false || !Validate::isLoadedObject($cart) || $cart->nbProducts() == 0) {
                    $customerCart = new Cart((int) Cart::lastNoneOrderedCart((int) $id_customer));

                    if (Validate::isLoadedObject($customerCart)) {
                        $cart = $customerCart;
                        $useGuestCart = false;
                    }
                }

                if ($useGuestCart) {
                    $guest = new Guest($this->idGuest);
                    $guest->id_customer = $id_customer;
                    $guest->update();

                    $cart->id_customer = $id_customer;
                    $cart->id_guest = $this->idGuest;
                    $cart->save();
                }
            }
        } else {
            // If customer is not authenticated, retrieve guest cart
            $id_cart = $this->getCartIdByGuest();

            if ($id_cart === false || $id_cart === 0 || $this->idGuest === null) {
                if ($createCartIfNeeded) {
                    // If guest is not set, just create a new empty cart
                    $this->idGuest = $this->createGuestIfMissing(null);
                    $cart = $this->generateNewCart($this->idGuest);
                }
            } else {
                $cart = new Cart($id_cart);
            }
        }

        $this->dataHandler->setGuestId($this->idGuest);

        if ($cart != null) {
            $this->context->cart = $cart;
            $cartId = (int) $cart->id;
            $currencyId = $_currencyId != null ? (int) $_currencyId : (int) $cart->id_currency;
            $this->context->currency = Currency::getCurrencyInstance($currencyId);

            if ($this->context->cart->id_currency != $currencyId) {
                $this->context->cart->id_currency = $currencyId;
                $this->context->cart->save();
            }
        } else {
            $cartId = 0;
            $this->context->cart = new Cart();
            $currencyId = $_currencyId != null ? (int) $_currencyId : (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $this->context->currency = Currency::getCurrencyInstance($currencyId);
            $this->context->cart->id_currency = $currencyId;
            $this->context->cart->id_lang = $this->context->language->id;
        }

        if ($cartId != $this->context->cookie->id_cart ||
            $currencyId != $this->context->cookie->id_currency ||
            $id_customer != $this->context->cookie->id_customer) {
            $this->context->cookie->id_cart = $cartId;
            $this->context->cookie->id_currency = $currencyId;
            $this->context->cookie->id_customer = $id_customer;
            $this->context->cookie->write();
        }

        return $cart;
    }

    private function createGuestIfMissing($id_guest, $id_customer = null)
    {
        if (empty($id_guest) && !empty($id_customer)) {
            $id_guest = Guest::getFromCustomer($id_customer);
            $id_guest = !$id_guest ? null : (int) $id_guest;
        }

        if ($id_guest == null) {
            $guest = new Guest();
            $guest->id_customer = (int) $id_customer;
            $guest->userAgent();
            $guest->save();

            $id_guest = (int) $guest->id;
        }

        $this->dataHandler->setGuestId((int) $id_guest);

        return $this->dataHandler->getGuestId();
    }

    public function generateNewCartFromCustomer($id_customer)
    {
        $guestId = $this->createGuestIfMissing(null, $id_customer);

        return $this->generateNewCart($guestId, $id_customer);
    }

    private function generateNewCart($id_guest, $id_customer = null)
    {
        $cart = new Cart();
        $cart->id_shop_group = 1;
        $cart->id_currency = Configuration::get('PS_CURRENCY_DEFAULT');
        $cart->id_lang = $this->context->language->id;
        $cart->id_customer = $id_customer != null ? (int) $id_customer : 0;
        $cart->id_guest = (int) $id_guest;

        $idAddress = (int) Address::getFirstCustomerAddressId($id_customer);
        $cart->id_address_delivery = $idAddress;
        $cart->id_address_invoice = $idAddress;

        $cart->save();

        return $cart;
    }

    private function getCartIdByGuest($idCustomer = 0)
    {
        if ((int) $this->idGuest == 0) {
            return false;
        }

        $query = 'SELECT c.`id_cart`
                  FROM '._DB_PREFIX_.'cart c
                  WHERE id_guest = '.(int) $this->idGuest.'.
                  AND (id_customer = '.(int) $idCustomer.'. OR id_customer = 0 OR id_customer IS NULL)
                  '.Shop::addSqlRestriction(Shop::SHARE_ORDER, 'c').'
				  ORDER BY c.`date_upd` DESC';

        if (!$id_cart = Db::getInstance()->getValue($query)) {
            return false;
        }

        return (int) $id_cart;
    }

    public function getCartRules($id_cart = null)
    {
        $cart = $id_cart != null ? new Cart($id_cart) : $this->context->cart;
        if (!$cart || !Validate::isLoadedObject($cart)) {
            return [];
        }

        $compatRules = [];
        $cartRules = $cart->getCartRules();
        foreach ($cartRules as $cartRuleData) {
            /** @var CartRule $cartRule */
            $cartRule = $cartRuleData['obj'];
            $discountAmount = $cartRuleData['value_real'];

            // Disabilito controllo regole carrello se l'ID viene passato come parametro (ovvero se l'ordine è già stato creato)
            $cartRuleError = $id_cart == null ? $cartRule->checkValidity($this->context, true) : null;

            if ($cartRuleError !== null) {
                $cart->removeCartRule($cartRule->id);
            } else {
                $compatRules[] = [
                    'id' => $cartRule->id,
                    'name' => $cartRule->name,
                    'discount_amount' => $discountAmount > 0 ? '- '.$this->priceFormatter->format($discountAmount) : '',
                    'description' => ApiUtils::nullifyIfEmpty($cartRule->description),
                    'coupon_code' => ApiUtils::nullifyIfEmpty($cartRule->code),
                ];
            }
        }

        return ApiUtils::nullifyIfEmpty($compatRules);
    }

    /**
     * Check if the products in the cart are available.
     *
     * @return bool
     * @var Cart $cart
     *
     */
    private function areAllProductsAvailable($cart)
    {
        $products = $cart->getProducts(true);
        $allProductsAvailable = true;

        foreach ($products as $product) {
            $currentProduct = new Product();
            $currentProduct->hydrate($product);

            if ($currentProduct->hasAttributes() && $product['id_product_attribute'] === '0') {
                $cart->updateQty(
                    0,
                    $product['id_product'],
                    $product['id_product_attribute'],
                    $product['id_customization'],
                    'down',
                    $product['id_address_delivery']
                );
                $allProductsAvailable = false;
            }
        }

        do {
            $product = $cart->checkQuantities(true);
            if (is_array($product)) {
                if (
                    !$product['active'] ||
                    !$product['available_for_order'] ||
                    (!$product['allow_oosp'] && $product['stock_quantity'] < $product['cart_quantity'])
                ) {
                    $quantity = 0;
                } else {
                    $quantity = $product['cart_quantity'] - $product['stock_quantity'];
                }

                $cart->updateQty(
                    $quantity,
                    $product['id_product'],
                    $product['id_product_attribute'],
                    $product['id_customization'],
                    'down',
                    $product['id_address_delivery']
                );

                $allProductsAvailable = false;
            }
        } while (is_array($product));

        return $allProductsAvailable;
    }

    public function validateCart()
    {
        $cart = $this->context->cart;
        $areAllProductsAvailable = $this->areAllProductsAvailable($cart);

        CartRule::autoAddToCart($this->context);

        $cartRuleInvalid = [];
        $cartRules = $cart->getCartRules();

        foreach ($cartRules as $cartRuleData) {
            /** @var CartRule $cartRule */
            $cartRule = $cartRuleData['obj'];
            $cartRuleError = $cartRule->checkValidity($this->context, true);

            if ($cartRuleError == null) {
                if (!$cartRule->active) {
                    $cartRuleError = $this->module->l('This voucher is disabled', 'carthandler');
                }
                if (!$cartRule->quantity) {
                    $cartRuleError = $this->module->l('This voucher has already been used', 'carthandler');
                }
                if (strtotime($cartRule->date_to) < time()) {
                    $cartRuleError = $this->module->l('This voucher has expired', 'carthandler');
                }
            }

            if ($cartRuleError !== null) {
                if (!empty($cartRule->code)) {
                    $cartRuleInvalid[] = $cartRule->name.': '.$cartRuleError;
                }

                $cart->removeCartRule($cartRule->id);
            }
        }

        $infoMessages = [];

        if (!$areAllProductsAvailable) {
            $infoMessages[] = $this->module->l('Cart has been updated considering products availability', 'carthandler');
        }

        if (!empty($cartRuleInvalid)) {
            $infoMessages = array_merge($infoMessages, $cartRuleInvalid);
        }

        return ApiUtils::nullifyIfEmpty($infoMessages);
    }

    /**
     * Return shipping total for the cart for Prestashop >= 1.7 and < 1.7.3
     * (static $_total_shipping impedisce il ricalcolo delle spese di spedizione più di una volta nella stessa sessione)
     *
     * @param Cart|null $cart Cart
     * @param array|null $delivery_option Array of the delivery option for each address
     * @param bool $use_tax Use taxes
     * @param Country|null $default_country Default Country
     *
     * @return float Shipping total
     */
    public function getTotalShippingCostForPs172(
        $cart = null,
        $cartRules = null,
        $delivery_option = null,
        $use_tax = true,
        Country $default_country = null
    ) {
        if (!$cart) {
            return 0;
        }
        if (!$cartRules) {
            $cartRules = $this->getCartRules();
        }
        if (!ApiUtils::isArray($cartRules)) {
            $cartRules = [];
        }

        if (isset($this->context->cookie->id_country)) {
            $default_country = new Country($this->context->cookie->id_country);
        }
        if (is_null($delivery_option)) {
            $delivery_option = $cart->getDeliveryOption($default_country, false, false);
        }

        $_total_shipping = [
            'with_tax' => 0,
            'without_tax' => 0,
        ];

        $delivery_option_list = $cart->getDeliveryOptionList($default_country);
        foreach ($delivery_option as $id_address => $key) {
            if (!isset($delivery_option_list[$id_address]) || !isset($delivery_option_list[$id_address][$key])) {
                continue;
            }

            $_total_shipping['with_tax'] += $delivery_option_list[$id_address][$key]['total_price_with_tax'];
            $_total_shipping['without_tax'] += $delivery_option_list[$id_address][$key]['total_price_without_tax'];
        }

        // Check Free Shipping
        $freeShipping = false;
        foreach ($cartRules as $crData) {
            $cartRule = new CartRule(isset($crData['id']) ? (int) $crData['id'] : 0);
            if (Validate::isLoadedObject($cartRule) && (bool) $cartRule->free_shipping) {
                $freeShipping = true;

                break;
            }
        }
        if ($freeShipping) {
            return 0;
        }

        return ($use_tax) ? $_total_shipping['with_tax'] : $_total_shipping['without_tax'];
    }

    private function setSmartyVariableForPaymentFeeModule($paymentMethod)
    {
        $oldSmartyPageName = null;
        $paymentMethod = trim($paymentMethod);

        // Support for module "paymentfee"
        if (Tools::strlen($paymentMethod) > 0) {
            $newSmartyPageName = 'module-'.$paymentMethod.'-express_checkout-payment';

            if (!isset($this->context->smarty->tpl_vars['page_name'])) {
                $this->context->smarty->tpl_vars['page_name'] = new Smarty_Variable($newSmartyPageName);
            } else {
                $oldSmartyPageName = $this->context->smarty->tpl_vars['page_name']->value;
                $this->context->smarty->tpl_vars['page_name']->value = $newSmartyPageName;
            }
        }

        return $oldSmartyPageName;
    }

    private function restoreSmartyVariableForPaymentFeeModule($oldPageName = null)
    {
        // Support for module "paymentfee"
        if ($oldPageName !== null) {
            $this->context->smarty->tpl_vars['page_name']->value = $oldPageName;
        } else {
            unset($this->context->smarty->tpl_vars['page_name']);
        }
    }

    /**
     * @param \Customer $customer Customer
     * @param \Cart $cart Cart
     *
     * @return float Codfee fee amount
     */
    private function getCodFeeAmount($customer, $cart)
    {
        $codFee = CodFeeHelper::getCodFee($customer, $cart);

        return $codFee['amount'];
    }

    /**
     * @param Cart|null $cart Cart
     * @param bool $includeTaxes Use taxes
     * @param bool $checkPsVersion Use taxes
     * @param string|null $paymentMethod
     *
     * @return array cartTotal
     *
     * @throws \Exception
     */
    public function getCartTotals($cart, $includeTaxes, $checkPsVersion = false, $paymentMethod = null)
    {
        $productsAmount = $cart->getOrderTotal($includeTaxes, Cart::BOTH_WITHOUT_SHIPPING);
        $feeAmount = 0;

        if ($checkPsVersion && version_compare(_PS_VERSION_, '1.7.0.0', '>=') && version_compare(
            _PS_VERSION_,
            '1.7.3',
            '<'
        )) {
            $shippingCosts = $cart->id_carrier ? $this->getTotalShippingCostForPs172($cart) : 0;
            $totalAmount = $productsAmount + $shippingCosts;
        } else {
            $totalAmount = $cart->getOrderTotal(true, Cart::BOTH);
            $shippingCosts = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
        }

        if (!empty($paymentMethod)) {
            if (Module::isEnabled('paymentfee')) {
                $paymentfee = Module::getInstanceByName('paymentfee');
                if (is_callable([$paymentfee, 'getFeesByCart'])) {
                    // Paymentfee module support: set correct Smarty variable 'page_name'
                    $oldSmartyPageName = $this->setSmartyVariableForPaymentFeeModule($paymentMethod);

                    $feeAmount = $paymentfee->getFeesByCart(true, $cart->id);

                    // Paymentfee module support: restore original Smarty variable 'page_name'
                    $this->restoreSmartyVariableForPaymentFeeModule($oldSmartyPageName);
                }
            }

            $paymentModule = Module::getInstanceByName($paymentMethod);
            if (Validate::isLoadedObject($paymentModule) && $paymentModule->active) {
                switch ($paymentMethod) {
                    case 'mrshopcashondelivery':
                        $fee = call_user_func('Fee::getFee', $cart);
                        $feeAmount += $fee !== false ? call_user_func('Fee::getFeeAmount', $cart, $fee) : 0;

                        break;
                    case 'codfee':
                        $feeAmount += $this->getCodFeeAmount($this->context->customer, $cart);

                        break;
                    case 'paypalwithfee':
                        if (method_exists($paymentModule, 'getCompleteFee')) {
                            $totalFee = $paymentModule->getCompleteFee($cart);
                            $feeAmount += $totalFee['fee_with_tax'];
                        } elseif (method_exists($paymentModule, 'getFee')) {
                            $totalFee = $paymentModule->getFee($cart);
                            $feeAmount += $totalFee['fee_with_tax'];
                        }

                        break;
                    case 'bankwirediscount':
                        if (method_exists($paymentModule, 'calculateDiscount')) {
                            $discount = $paymentModule->calculateDiscount();
                            $feeAmount -= $discount;
                        }

                        break;
                    default:
                        break;
                }
            }
        }

        $totalAmountIncFee = Tools::ps_round((float) ($totalAmount + $feeAmount), 2);

        return [
            'products' => $productsAmount,
            'shipping' => $shippingCosts,
            'fee_amount' => $feeAmount,
            'total' => $totalAmount,
            'total_inc_fee' => $totalAmountIncFee,
        ];
    }

    public function composeCart($cart, $id_customer, $message, $checkPsVersion = false, $paymentMethod = null)
    {
        $this->context->cart = $cart;

        $includeTaxes = ApiUtils::includeTaxes($id_customer);

        $infoMessages = $this->validateCart();
        $apiVersion = $this->dataHandler->getApiVersion();

        $productsData = $cart->getProducts(true);

        $products = [];
        $giveAwayProducts = [];

        foreach ($productsData as $product) {
            $productId = (int) $product['id_product'];
            // $productInstance = new Product($productId);
            $productAttributeId = (int) $product['id_product_attribute'] > 0 ? (int) $product['id_product_attribute'] : null;
            $productQt = $product['quantity'];
            $allowOosp = (bool) Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock($productId));

            $inCart = (int) $product['cart_quantity'];
            $minimumQuantity = (($product['minimal_quantity'] ? (int) $product['minimal_quantity'] : 1) - $inCart);
            if ($minimumQuantity <= 0) {
                if ($apiVersion <= 10) {
                    $minimumQuantity = 1;
                } else {
                    $minimumQuantity = (int) $product['minimal_quantity'];
                }
            }

            if ($apiVersion <= 10) {
                $quantityAvailable = $this->dataHandler->getMaxProductsShown() > (int) $product['quantity'] && $allowOosp ? $this->dataHandler->getMaxProductsShown() : (int) $product['quantity'];
            } else {
                $quantityAvailable = $allowOosp ? -1 : max((int) $product['quantity_available'], 0);
            }

            /*
             * PSBOOKING
             * A single booking product is explosed into many cart product (one for each date interval)
             */
            if ($this->bookingHandler->isModuleActive() && $this->bookingHandler->isBookingProduct($productId)) {
                $items = $this->bookingHandler->getCartItem($product);
                $products = array_merge($products, $items);
            } else {
                /** 0 tax incl - 1 tax excl */
                $priceDisplayMethods = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                $discountedPrice = $this->priceFormatter->format(
                    Tools::ps_round(
                        $priceDisplayMethods ? $product['price_with_reduction_without_tax'] : $product['price_with_reduction'],
                        2
                    )
                );
                $discountedPriceVal = $this->priceFormatter->getPriceData($priceDisplayMethods ? $product['price_with_reduction_without_tax'] : $product['price_with_reduction']);
                $fullPrice = $this->priceFormatter->format(
                    Tools::ps_round(
                        $product['price_without_reduction'],
                        2
                    )
                );
                $fullPriceVal = $this->priceFormatter->getPriceData($product['price_without_reduction']);

                /*
                *  egbuycentimeter
                */
                $unit_increment = null;
                if ($this->dataHandler->isModuleActive('egbuycentimeter') && (isset($product['unity']) && !empty($product['unity']))) {
                    $quantities = $this->externalModuleService->egbuycentimeterGetQuantities(
                        $productId,
                        $minimumQuantity,
                        (int) $product['quantity'],
                        $quantityAvailable,
                        $unit_increment
                    );
                    $unit_increment = $quantities['unit_increment'];
                    $minimumQuantity = $quantities['minimum_quantity'];
                    $quantityAvailable = $quantities['quantity_available'];
                    $product['quantity'] = $quantities['quantity'];
                }

                /*
                * MINPURCHASE
                * Override quantities and set unit increment
                */
                if ($this->dataHandler->isModuleActive('minpurchase')) {
                    $quantities = $this->externalModuleService->minpurchaseGetQuantities($productId);

                    $minimumQuantity = $quantities['minimum_quantity'] != false ? $quantities['minimum_quantity'] : $minimumQuantity;
                    $quantityAvailable = $quantities['quantity_available'] != false ? $quantities['quantity_available'] : $quantityAvailable;
                    $unit_increment = $quantities['unit_increment'] != false ? $quantities['unit_increment'] : $unit_increment;
                }

                if ($this->dataHandler->getApiVersion() > 11) {
                    $product['quantity'] = (float) $product['quantity'];
                }

                if ($this->dataHandler->getApiVersion() >= 13) {
                    $gifts = $cart->getCartRules(CartRule::FILTER_ACTION_GIFT, false);

                    if (count($gifts) > 0) {
                        foreach ($gifts as $gift) {
                            $giftProductId = (int) $gift['gift_product'];
                            $giftProductAttributeId = $gift['gift_product_attribute'] > 0 ? (int) $gift['gift_product_attribute'] : null;
                            if (
                                $productId == $giftProductId &&
                                $productAttributeId == $giftProductAttributeId
                            ) {
                                $giftQt = 1;
                                $productQt = $productQt - $giftQt;
                                $cartTotalProducts = $productQt + $giftQt;
                                $giveAwayProducts[] = [
                                    'id' => $giftProductId,
                                    'name' => $product['name'],
                                    'attributes_description' => empty($product['attributes_small']) ? null : str_replace(
                                        '-',
                                        ',',
                                        $product['attributes_small']
                                    ),
                                    'minimum_quantity' => $minimumQuantity,
                                    'id_product_attribute' => $giftProductAttributeId,
                                    'quantity' => $cartTotalProducts,
                                    'quantity_available' => $quantityAvailable,
                                    'unit_increment' => $unit_increment,
                                    'total_price' => $this->module->l('Gift'),
                                    'total_price_val' => $this->priceFormatter->getPriceData(
                                        $priceDisplayMethods ? $product['total'] : $product['total_wt']
                                    ),
                                    'full_price' => $this->module->l('Gift'),
                                    'full_price_val' => $fullPriceVal,
                                    'discounted_price' => $this->module->l('Gift'),
                                    'discounted_price_val' => $discountedPriceVal,
                                    'image' => BaseWS::getProductImageUrl(
                                        ['link_rewrite' => $product['link_rewrite']],
                                        (int) explode('-', $product['id_image'])[1],
                                        BaseWS::getImageFormattedName('cart')
                                    ),
                                    'is_gift' => true
                                ];
                            }
                        }
                    }
                }

                if ($productQt > 0) {
                    $idCustomization = !empty($product['id_customization']) ? (int) $product['id_customization'] : null;
                    $productCustomizations = [];

                    if ($idCustomization != false) {
                        $productCustomizations = $this->customizationHandler->getProductCustomizationValues(
                            $cart->id,
                            $productId
                        );
                    }

                    if (!$product['total'] || !$product['total_wt']) {
                        $totalPrice = '-';
                    } else {
                        $totalPrice = $this->priceFormatter->format(
                            Tools::ps_round(
                                $priceDisplayMethods ? $product['total'] : $product['total_wt'],
                                2
                            )
                        );
                    };
                    if ($this->dataHandler->isModuleActive('egbuycentimeter')) {
                        $productQt = $product['quantity'];
                    }

                    $item = [
                        'id' => $productId,
                        'name' => $product['name'],
                        'attributes_description' => empty($product['attributes_small']) ? null : str_replace(
                            '-',
                            ',',
                            $product['attributes_small']
                        ),
                        'minimum_quantity' => $minimumQuantity,
                        'id_product_attribute' => $productAttributeId,
                        'quantity' => $productQt,
                        'quantity_available' => $quantityAvailable,
                        'unit_increment' => $unit_increment,
                        'total_price' => $totalPrice,
                        'total_price_val' => $this->priceFormatter->getPriceData(
                            $priceDisplayMethods ? $product['total'] : $product['total_wt']
                        ),
                        'full_price' => $fullPrice,
                        'full_price_val' => $fullPriceVal,
                        'discounted_price' => $discountedPrice,
                        'discounted_price_val' => $discountedPriceVal,
                        'image' => BaseWS::getProductImageUrl(
                            ['link_rewrite' => $product['link_rewrite']],
                            (int) explode('-', $product['id_image'])[1],
                            BaseWS::getImageFormattedName('cart')
                        ),
                        'is_gift' => false,
                        'id_customization' => $idCustomization,
                        'customized_fields' => $productCustomizations
                    ];

                    $products[] = $item;
                }
            }
        }

        $products = array_merge($products, $giveAwayProducts);
        $cartRules = $this->getCartRules();

        // Totale prodotti, spedizione, totale
        $cartTotals = $this->getCartTotals($cart, $includeTaxes, $checkPsVersion, $paymentMethod);
        $productsAmount = $cartTotals['products'];
        $shippingCosts = Tools::ps_round($cartTotals['shipping'], 2);
        $shippingCostsFormatted = $this->priceFormatter->format($shippingCosts);
        $totalAmount = $cartTotals['total'];

        $minimumPurchase = (float) Configuration::get('PS_PURCHASE_MINIMUM');
        $productsTotalExcludingTax = $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

        $priceFormatter = new PriceFormatter();

        $canProceed = count($products) > 0;

        $payment = [
            'can_proceed' => $canProceed,
            'message' => null,
        ];

        if ($canProceed && $priceFormatter->convertAmount($productsTotalExcludingTax) < $minimumPurchase) {
            $canProceed = false;
            $payment = [
                'can_proceed' => $canProceed,
                'message' => sprintf(
                    $this->module->l('A minimum total of %s (excluding taxes) is required to validate the payment.', 'carthandler'),
                    $priceFormatter->convertAndFormat($minimumPurchase)
                ),
            ];
        }

        /**
         * RPBLOCKBYCOUNTRY
         * Check if products are allowed for country
         */
        if ($canProceed) {
            $response = $this->areProductsAllowedForCountry($productsData);
            $canProceed = (bool) $response['success'];
            if (!$canProceed) {
                $payment = [
                    'can_proceed' => $canProceed,
                    'message' => $response['message'],
                ];
            }
        }

        /**
         * riccionepiadina
         * Check if products are valid
         */
        if ($canProceed) {
            $response = $this->areProductsValidForRiccionePiadina($productsData);
            $canProceed = (bool) $response['success'];
            if (!$canProceed) {
                $payment = [
                    'can_proceed' => $canProceed,
                    'message' => $response['message'],
                ];
            }
        }

        $currency = new Currency($cart->id_currency);

        $base_total_tax_inc = $cart->getOrderTotal(true);
        $base_total_tax_exc = $cart->getOrderTotal(false);
        $total_tax = $base_total_tax_inc - $base_total_tax_exc;
        if ($total_tax < 0) {
            $total_tax = 0;
        }
        $taxEnabled = (bool) Configuration::get('PS_TAX');
        $displayMethod = Group::getPriceDisplayMethod(Customer::getDefaultGroupId($id_customer));

        if (!$total_tax) {
            $amount = '-';
        } else {
            $amount = $this->priceFormatter->format(Tools::ps_round($total_tax, 2));
        }

        $taxes = [
            'enabled' => (bool) Configuration::get('PS_TAX'),
            'display' => (bool) Configuration::get('PS_TAX_DISPLAY'),
            'amount' => $amount,
            'amount_val' => $this->priceFormatter->getPriceData($total_tax),
            'display_method' => !$taxEnabled ? '' : ($displayMethod ? '('.$this->module->l('tax excl.', 'carthandler').')' : '('.$this->module->l('tax incl.', 'carthandler').')'),
            'label' => !$taxEnabled ? '' : ($displayMethod ? $this->module->l('Taxes', 'carthandler') : $this->module->l('Included taxes', 'carthandler')),
        ];

        $paymentHandler = new PaymentHandler($this->dataHandler);

        $amazonPay = null;

        if ($paymentHandler->isModuleValidFor('amazonpay', $id_customer, $cart)) {
            $token = $this->dataHandler->getToken(true);
            $amazonPay = AmazonPayManager::preInitData($token);
        }

        if ($canProceed) {
            if ($this->dataHandler->isModuleActive('minpurchase')) {
                $payment = $this->externalModuleService->minpurchaseCheckAvailability($products);
            }
        }

        $guestCheckout = null;
        if ($id_customer != null && $this->context->customer->isGuest()) {
            $guestCheckout = [
                'email' => $this->context->customer->email,
                'firstname' => $this->context->customer->firstname,
                'lastname' => $this->context->customer->lastname
            ];
        }

        if (!$totalAmount) {
            $total = '-';
        } else {
            $total = $this->priceFormatter->format(Tools::ps_round($totalAmount, 2));
        };

        if (!$productsAmount) {
            $productsTotal = '-';
        } else {
            $productsTotal = $this->priceFormatter->format(Tools::ps_round($productsAmount, 2));
        }

        $response = [
            'id' => $cart->id,
            'id_customer' => $id_customer,
            'id_guest' => (int) $cart->id_guest,
            'guest_checkout' => $guestCheckout,
            'id_address_delivery' => (int) $cart->id_address_delivery,
            'id_address_invoice' => (int) $cart->id_address_invoice,
            'id_carrier' => (int) $cart->id_carrier,
            'cart_rules' => $cartRules,
            'currency_iso' => $currency->iso_code,
            'payment_amount' => Tools::ps_round($totalAmount, 2),
            'payment_amount_val' => $this->priceFormatter->getPriceData($totalAmount),
            'payment_products' => Tools::ps_round($productsAmount, 2),
            'payment_products_val' => $this->priceFormatter->getPriceData($productsAmount),
            'payment_shipping' => Tools::ps_round($shippingCosts, 2),
            'payment_shipping_val' => $this->priceFormatter->getPriceData($shippingCosts),
            'total' => $total,
            'total_val' => $this->priceFormatter->getPriceData($totalAmount),
            'shipping_total' => $shippingCosts == 0 ? $this->module->l('Free', 'carthandler') : $shippingCostsFormatted,
            'shipping_total_val' => $this->priceFormatter->getPriceData($shippingCosts),
            'products_total' => $productsTotal,
            'products_total_val' => $this->priceFormatter->getPriceData($productsAmount),
            'info_messages' => $infoMessages,
            'payment' => $payment,
            'is_virtual_cart' => (bool) $cart->isVirtualCart(),
            'taxes' => $taxes,
            'amazonpay' => $amazonPay
        ];

        $response['products'] = $products;

        /*
         *  AIO_REWARDS
         * Add fidelity data
         */
        if ($this->fidelityHandler->isModuleActive()) {
            $fidelityData = $this->fidelityHandler->getCartFidelityData($cart, $id_customer, $currency);
            if (!empty($fidelityData)) {
                $response['fidelity'] = $fidelityData;
            }
        }

        if ($message != null) {
            $response['message'] = $message;
        }

        return $response;
    }

    protected function getAddressSummary($id_customer, $id_address)
    {
        $query = 'SELECT id_address AS id, id_customer, id_country, id_state, alias, address1, address2, postcode, city, company, lastname, firstname, phone, phone_mobile, vat_number, dni, other
                FROM `'._DB_PREFIX_.'address`
                WHERE active = 1 AND deleted = 0
                AND id_customer = '.(int) $id_customer.' 
                AND id_address = '.(int) $id_address;

        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);

        return !empty($row)
            ? ApiUtils::refactorRow($row, $this->dataHandler->getApiVersion())
            : null;
    }

    public function getCartSummary($cart, $id_customer, $message, $checkPsVersion = false, $paymentMethod = null)
    {
        $paymentMethodName = isset($paymentMethod) ? explode('?', $paymentMethod)[0] : null;

        $hidden = ['amazonpay'];
        $formattedCart = $this->composeCart($cart, $id_customer, $message, $checkPsVersion, $paymentMethodName);

        $retval = [];

        foreach ($formattedCart as $key => $value) {
            if (in_array($key, $hidden)) {
                continue;
            }

            switch ($key) {
                case 'id_address_delivery':
                    if (FermopointUtils::isFermopointEnabled() && $formattedCart['id_carrier'] == FermopointUtils::getFermopointCarrierId()) {
                        $info = FermopointUtils::getPointInfo($cart->id);
                        if (false == empty($info)) {
                            $retval['address_delivery'] = FermopointUtils::refactorPointInfo($info, $id_customer);
                        } else {
                            $retval['address_delivery'] = null;
                        }
                    } else {
                        $retval['address_delivery'] = $this->getAddressSummary($id_customer, $value);
                    }

                    break;
                case 'id_address_invoice':
                    $retval['address_invoice'] = $this->getAddressSummary($id_customer, $value);

                    break;
                case 'id_carrier':
                    $zoneId = Address::getZoneById($cart->id_address_delivery);
                    $customer = new Customer($id_customer);
                    $groups = $customer->getGroups();
                    $carriers = Carrier::getCarriersForOrder($zoneId, $groups, $cart);

                    $row = [];
                    foreach ($carriers as $d) {
                        if ($d['id_carrier'] == $value) {
                            $row = $d;

                            break;
                        }
                    }

                    if (empty($row)) {
                        $retval['carrier'] = null;
                    } else {
                        $data = [];

                        $data["id_carrier"] = (int) $row["id_carrier"];
                        $data["id_reference"] = (int) $row["id_reference"];
                        $data["name"] = $row["name"];
                        $data["delay"] = $row["delay"];
                        $data["img"] = ApiUtils::nullifyIfEmpty($row["img"]);

                        $displayMethod = Group::getPriceDisplayMethod(Customer::getDefaultGroupId($id_customer));
                        $free = $this->module->l('Free', 'carthandler');
                        if ($displayMethod) {
                            $data["price"] = $row['price_tax_exc'] === 0
                                ? $free
                                : $this->priceFormatter->format($row['price_tax_exc'], $this->dataHandler->getCurrencyId()).' ('.$taxLabel = $this->module->l('tax excl.', 'carthandler').')';
                        } else {
                            $data["price"] = $row['price'] === 0
                                ? $free
                                : $this->priceFormatter->format($row['price'], $this->dataHandler->getCurrencyId()).' ('.$this->module->l('tax incl.', 'carthandler').')';
                            ;
                        }

                        $retval['carrier'] = $data;
                    }

                    break;
                default:
                    $retval[$key] = $value;

                    break;
            }
        }

        $retval['payment'] = null;

        if (is_null($paymentMethod)) {
            return $retval;
        }

        $paymentHandler = new PaymentHandler($this->dataHandler);
        $manager = $paymentHandler->getPaymentMethodManager($paymentMethodName);

        if (is_null($manager)) {
            return $retval;
        }

        $structure = $manager->getStructure($id_customer, (int) $cart->id);

        if (empty($structure)) {
            return $retval;
        }

        if (ApiUtils::isAssociative($structure)) {
            $retval['payment'] = array_merge($structure, $formattedCart['payment']);
        } else {
            foreach ($structure as $method) {
                if ($method['name'] == $paymentMethod) {
                    $retval['payment'] = array_merge($method, $formattedCart['payment']);

                    break;
                }
            }
        }

        return $retval;
    }

    /**
     * Check if products are allowed for customer country (module rpblockbycountry)
     * @param $products
     * @param $action
     * @return array
     */
    public function areProductsAllowedForCountry($products, $action = '')
    {
        $response = [
            'success' => true,
            'message' => ""
        ];
        if (Module::isEnabled('rpblockbycountry')) {
            $rpblockbycountry = Module::getInstanceByName('rpblockbycountry');
            $relevant_filters = $rpblockbycountry->getRelevantFilters($rpblockbycountry->getUserCountry());
            $valid = $rpblockbycountry->validateCart($relevant_filters, $products);
            if ($valid !== true) {
                $response['success'] = false;
                if ($action == 'add') {
                    $response['message'] = $this->module->l('This product is not available for your region.', 'carthandler');
                } else {
                    $response['message'] = $this->module->l('These products are not available for your region:', 'carthandler').' '.str_replace('"', '', $valid);
                }
            }
        }

        if (Module::isEnabled('blockproductsbycountry')) {
            $blockproducts = Module::getInstanceByName('blockproductsbycountry');
            $blockproductsClass = get_class($blockproducts);
            $list = [];
            foreach ($products as $product) {
                if ($blockproductsClass::isProductBlocked($product['id_product'])) {
                    $list[] = $product["name"];
                }
            }
            if (!empty($list)) {
                $response['success'] = false;
                if ($action == 'add') {
                    $response['message'] = $this->module->l('This product is not available for your region.', 'carthandler');
                } else {
                    $response['message'] = $this->module->l('These products are not available for your region:', 'carthandler').' '.implode(', ', $list);
                }
            }
        }

        return $response;
    }

    /**
     * Check if products are allowed (module riccionepiadina)
     * @param $products
     * @param $action
     * @return array
     */
    public function areProductsValidForRiccionePiadina($products)
    {
        $response = [
            'success' => true,
            'message' => ""
        ];
        if (Module::isEnabled('riccionepiadina')) {
            $module = Module::getInstanceByName('riccionepiadina');
            $valid = $module->isValid($products);
            if ($valid !== true) {
                $response['success'] = false;
                $response['message'] = $this->module->l('You can\'t buy a fresh product together with a non-fresh one', 'carthandler');
            }
        }

        return $response;
    }

    public function afterDeleteCartProduct(
        $id_cart,
        $id_product,
        $id_customization = null
    ) {
        if ((int) $id_customization > 0) {
            $this->customizationHandler->deleteProductCustomization($id_cart, $id_customization);
        }
    }
}
