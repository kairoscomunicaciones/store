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

namespace MrAPPs\MrShopApi\Api\Front;

use Address;
use Context;
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\BookingHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use Product;
use Tools;

class CartWS extends BaseWS
{
    /** @var CartHandler $handler */
    private $handler;

    /** @var BookingHandler */
    private $bookingHandler;

    private $externalModuleService;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->handler = new CartHandler($dataHandler->getGuestId());
        $this->bookingHandler = new BookingHandler();
        $this->externalModuleService = new ExternalModuleService();
    }

    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!$params || count($params) === 0) {
            $this->response(false, $this->module->l('Invalid parameters', 'cartws'), null);
        } else {
            if (array_key_exists('id_carrier', $params) || array_key_exists(
                'id_address_delivery',
                $params
            ) || array_key_exists('id_address_invoice', $params)) {
                $cart = $this->handler->getCart($id_customer, true, $this->getCurrencyId());
                $checkPsVersion = false;

                // Aggiornamento indirizzi
                if (array_key_exists('id_address_delivery', $params) || array_key_exists(
                    'id_address_invoice',
                    $params
                )) {
                    $id_address_delivery = array_key_exists(
                        'id_address_delivery',
                        $params
                    ) ? (int) $params['id_address_delivery'] : null;
                    $id_address_invoice = array_key_exists(
                        'id_address_invoice',
                        $params
                    ) ? (int) $params['id_address_invoice'] : null;

                    if ($cart !== null) {
                        if ($id_address_delivery) {
                            $cart->id_address_delivery = $id_address_delivery;
                            Db::getInstance()->execute(
                                'UPDATE `'._DB_PREFIX_.'cart_product`
                                SET `id_address_delivery` = '.(int) $id_address_delivery.'
                                WHERE `id_cart` = '.(int) $cart->id
                            );
                        }
                        if ($id_address_invoice !== null) {
                            $cart->id_address_invoice = $id_address_invoice;
                        }

                        $cart->update();

                        /**
                         * RpBlockByCountry
                         * Check if product is allowed for country
                         */
                        $products = $cart->getProducts();
                        $response = $this->handler->areProductsAllowedForCountry($products);
                        if (!$response['success']) {
                            $this->response(false, $response['message'], null);
                        }
                    } else {
                        $this->response(false, $this->module->l('Error while updating cart', 'cartws'), null);
                    }
                }

                // Aggiornamento corriere
                if (array_key_exists('id_carrier', $params)) {
                    $id_carrier = (int) $params['id_carrier'];

                    if ($cart !== null) {
                        $cart->setDeliveryOption([$cart->id_address_delivery => $id_carrier.',']);
                        $cart->update();
                        $checkPsVersion = true;
                    } else {
                        $this->response(false, $this->module->l('Error while updating cart', 'cartws'), null);
                    }
                }

                $this->getList(null, $id_customer, $cart, null, $checkPsVersion);
            } else {
                $id_product = array_key_exists('id_product', $params) ? (int) $params['id_product'] : null;
                $quantity = array_key_exists('quantity', $params) ? (float) $params['quantity'] : null;

                if (array_key_exists('id_product_attribute', $params)) {
                    $id_product_attribute = (int) $params['id_product_attribute'];
                } else {
                    $id_product_attribute = (int) Db::getInstance()->getValue('SELECT id_product_attribute 
                      FROM `'._DB_PREFIX_.'product_attribute`
                      WHERE id_product = '.(int) $id_product.'
                      AND default_on = 1');
                }

                if ($id_product === null || $quantity === null || $id_product_attribute === null) {
                    $this->response(false, $this->module->l('Invalid parameters', 'cartws'), null);
                }

                $cart = $this->handler->getCart($id_customer, true, $this->getCurrencyId());

                if ($cart !== null) {
                    if ($this->bookingHandler->isModuleActive() && $this->bookingHandler->isBookingProduct($id_product)) {
                        if (!array_key_exists('booking_date_from', $params) || !array_key_exists('booking_date_to', $params)) {
                            $this->response(false, $this->module->l('Please specify a range of dates', 'cartws'), null);
                        }

                        if ($this->bookingHandler->updateOrCreate($params, $cart)) {
                            $message = $this->module->l('Product added to cart', 'cartws');
                            $this->getList(null, $id_customer, $cart, $message);
                        } else {
                            $this->response(false, $this->module->l('Error while updating cart', 'cartws'), null);
                        }
                    } else {
                        $product = new Product($id_product, $id_product_attribute);
                        if ($this->dataHandler->isModuleActive('egbuycentimeter') && (isset($product->unity) && !empty($product->unity))) {
                            if ($this->dataHandler->getApiVersion() > 11) {
                                $quantity = $quantity * 100;
                            }
                        }
                        $quantity = (int) $quantity;
                        
                        $productDeleted = false;

                        if ($quantity != 0) {
                            $purchasable = $this->checkIfQuantityIsPurchasable(
                                $cart,
                                $quantity,
                                $id_product,
                                $id_product_attribute
                            );

                            if (!$purchasable['isPurchasable']) {
                                $this->response(false, $purchasable['message'], null);
                            }
                        } else {
                            $productDeleted = Db::getInstance()->execute(
                                'DELETE FROM `'._DB_PREFIX_.'cart_product`
                                WHERE `id_product` = '.(int) $id_product
                                .' AND `id_product_attribute` = '.(int) $id_product_attribute
                            );
                        }

                        $idCustomization = empty($params['id_customization']) ? false : (int) $params['id_customization'];
                        if ($productDeleted || $cart->updateQty(
                            abs($quantity),
                            $id_product,
                            $id_product_attribute,
                            $idCustomization,
                            $quantity > 0 ? 'up' : 'down',
                            0,
                            null,
                            true,
                            true
                        )) {
                            if ($quantity > 0) {
                                $message = $this->module->l('Product added to cart', 'cartws');
                            } else {
                                $message = null;
                            }

                            if ($quantity == 0) {
                                $this->handler->afterDeleteCartProduct($cart->id, $id_product, $idCustomization);
                            }

                            $this->getList(null, $id_customer, $cart, $message);
                        } else {
                            $this->response(false, $this->module->l('Error while updating cart', 'cartws'), null);
                        }
                    }
                } else {
                    $this->response(false, $this->module->l('Error while updating cart', 'cartws'), null);
                }
            }
        }
    }

    /**
     * @param \Cart $cart
     * @param $quantityToChange
     * @param $id_product
     * @param $id_product_attribute
     * @return array
     */
    private function checkIfQuantityIsPurchasable($cart, $quantityToChange, $id_product, $id_product_attribute)
    {
        $shop = Context::getContext()->shop;
        $productInstance = new Product($id_product, false, $this->context->language->id);
        /**
         * RpBlockByCountry
         * If cart shipping address check if product is allowed for country
         */
        $shippingAddress = new Address((int) $cart->id_address_delivery);
        if ($shippingAddress) {
            $product = (array) $productInstance;
            $product['id_product'] = $id_product;
            $response = $this->handler->areProductsAllowedForCountry([$product], 'add');
            if (!$response['success']) {
                return [
                    'isPurchasable' => false,
                    'message' => $response['message'],
                ];
            }
        }

        $productQuantity = Product::getQuantity($id_product, $id_product_attribute);
        $newQuantity = (int) $productQuantity - (int) $quantityToChange;
        $cartQuantity = $cart->containsProduct($id_product, $id_product_attribute);

        if ($cartQuantity) {
            $newQuantity -= (int) $cartQuantity['quantity'];
        }
        
        /*
         *  MINPURCHASE
         */
        if ($this->dataHandler->isModuleActive('minpurchase')) {
            $minpurchaseError = $this->externalModuleService->minpurchaseCheckQuantity($id_product, $quantityToChange, (int) $cartQuantity['quantity']);
            if (!empty($minpurchaseError)) {
                return [
                    'isPurchasable' => false,
                    'message' => $minpurchaseError
                ];
            }
        }

        if ($newQuantity >= 0) {
            return ['isPurchasable' => true, 'message' => null];
        }

        $sql = 'SELECT stock.out_of_stock FROM '._DB_PREFIX_.'product p'.
            Product::sqlStock('p', $id_product_attribute, true, $shop).
            'WHERE p.id_product = '.(int) $id_product;

        $result = Db::getInstance()->getRow($sql);

        if (!Product::isAvailableWhenOutOfStock((int) $result['out_of_stock'])) {
            return [
                'isPurchasable' => false,
                'message' => $this->module->l('There are not enough products in stock.', 'cartws'),
            ];
        }

        return ['isPurchasable' => true, 'message' => null];
    }

    public function getList($params, $id_customer = null, $cart = null, $message = null, $checkPsVersion = false)
    {
        if ($cart === null) {
            $cart = $this->handler->getCart($id_customer, true, $this->getCurrencyId());
        }

        if ($cart !== null) {
            $paymentMethod = is_array($params) && array_key_exists(
                'payment_method',
                $params
            ) ? trim($params['payment_method']) : null;
            $response = $this->handler->composeCart($cart, $id_customer, $message, $checkPsVersion, $paymentMethod);
            $this->response(true, null, $response);
        } else {
            ResponseHandler::notFound($this->module->l('Cart not found', 'cartws'));
        }
    }

    public function applyCoupon($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();
        $couponCode = array_key_exists('coupon_code', $bodyParams) ? $bodyParams['coupon_code'] : Tools::getValue('coupon_code');
        $cart = $this->handler->applyCoupon($customerId, $couponCode);
        $this->getList(null, $customerId, $cart);
    }

    public function removeCoupon($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();
        $couponCode = array_key_exists('coupon_code', $bodyParams) ? $bodyParams['coupon_code'] : Tools::getValue('coupon_code');
        $cart = $this->handler->removeCoupon($customerId, $couponCode);
        $this->getList(null, $customerId, $cart);
    }
}
