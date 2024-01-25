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

namespace MrAPPs\MrShopApi\Handler\Fidelity;

use CartRule;
use Configuration;
use Context;
use Currency;
use Customer;
use Module;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrshopApi\classes\services\PriceFormatter;
use Product;
use Tools;
use Validate;

class AllinoneRewardsFidelityHandler extends AbstractFidelityHandler implements FidelityHandlerInterface
{
    private $context = null;

    protected $module = null;

    /** @var allinone_rewards module  */
    private $fidelityModule = null;

    /** @var bool rewards disabled for discounted product  */
    private $noPtsDiscounted = false;

    /** @var bool fidelity program status */
    private $fidelityProgram = false;

    /**
     * FidelityHandler constructor.
     * @param $dataHandler
     */
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();
        if ($this->isModuleActive()) {
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsTemplateModel.php');
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsProductModel.php');
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsGiftProductAttributeModel.php');
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsModel.php');
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsStateModel.php');
            require_once(_PS_MODULE_DIR_.'allinone_rewards/models/RewardsGiftProductModel.php');
            $this->fidelityModule = Module::getInstanceByName('allinone_rewards');
            $this->fidelityProgram = $this->fidelityModule->loyalty->isActive();
        }
    }

    /**
     * Check if module allinone_rewards is active
     * @return bool|mixed
     */
    public function isModuleActive()
    {
        return self::isActive();
    }

    /**
     * Check if fidelity program is active
     * @return bool|mixed
     */
    public function isFidelityProgramActive()
    {
        return $this->fidelityProgram;
    }

    /**
     * {@inheritDoc}
     */
    public function isFidelityAvailable()
    {
        return $this->fidelityModule->core->isRewardsAccountVisible();
    }

    /**
     * {@inheritDoc}
     */
    public static function isActive()
    {
        return Module::isEnabled('allinone_rewards');
    }

    /**
     * Check if the product is in a category which is allowed to give loyalty rewards or if a reward is defined on that product
     * @param $idProduct
     * @return bool
     */
    public function _isProductAllowed($idProduct)
    {
        $idTemplate = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', $this->context->customer->id);
        if ((int) call_user_func('MyConf::get', null, $idTemplate) == 0 || (int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $idTemplate) == 1) {
            if (call_user_func('MyConf::get', 'RLOYALTY_ALL_CATEGORIES', null, $idTemplate)) {
                return true;
            }

            return Product::idIsOnCategoryId($idProduct, $this->_getAllowedCategories());
        } else {
            return call_user_func('RewardsProductModel::isProductRewarded', $idProduct, $idTemplate, 'loyalty');
        }
    }

    /**
     * Check if customer is in a group which is allowed to get loyalty rewards.
     * If bCheckDefault is true, then return true if the default group is checked (to know if we display the rewards for people not logged in)
     * @param $customer
     * @param bool $bCheckDefault
     * @return bool
     */
    public function _isCustomerAllowed($customer, $bCheckDefault = false)
    {
        $allowed_groups = explode(',', Configuration::get('RLOYALTY_GROUPS'));
        if (Validate::isLoadedObject($customer)) {
            // if the customer is linked to a template, then it overrides the groups setting
            if ((int) call_user_func('MyConf::getIdTemplate', 'loyalty', $customer->id)) {
                return true;
            }
            $customer_groups = $customer->getGroups();

            return sizeof(array_intersect($allowed_groups, $customer_groups)) > 0;
        } elseif ($bCheckDefault && in_array(Configuration::get('PS_UNIDENTIFIED_GROUP'), $allowed_groups)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the product is in a category which is allowed for free gift or if a custom behavior is defined on that product
     * @param $id_template
     * @param $id_product
     * @param $id_product_attribute
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function _isGiftProductAllowed($id_template, $id_product, $id_product_attribute)
    {
        if (Validate::isLoadedObject($product = new Product($id_product)) && !$product->customizable && $product->minimal_quantity <= 1 && (float) $product->getPrice(false, $id_product_attribute) > 0) {
            $gift_allowed = call_user_func('RewardsGiftProductAttributeModel::getGiftProductAttributeAllowed', $id_product, $id_product_attribute);
            switch ($gift_allowed) {
                // product has no custom value defined in the product sheet
                case -1:
                    // all categories
                    if ((int) call_user_func('MyConf::get', 'REWARDS_GIFT_ALL_CATEGORIES', null, $id_template)==1) {
                        return true;
                    } elseif ((int) call_user_func('MyConf::get', 'REWARDS_GIFT_ALL_CATEGORIES', null, $id_template)==-1) { // none
                        return false;
                    } else {
                        $allowed_categories = [];
                        $categories = explode(',', call_user_func('MyConf::get', 'REWARDS_GIFT_CATEGORIES', null, $id_template));
                        foreach ($categories as $category) {
                            $allowed_categories[] = ['id_category' => $category];
                        }

                        return Product::idIsOnCategoryId($id_product, $allowed_categories);
                    }
                    // product is not allowed in product sheet
                    // no break
                case 0:
                    return false;
                    // product is active in product sheet
                case 1:
                    return true;
            }
        }

        return false;
    }

    /**
     * Convert the string into an array of object(array) which have id_category as key
     * @return array|null
     */
    public function _getAllowedCategories()
    {
        $idTemplate=0;
        if (isset($this->context->customer)) {
            $idTemplate = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', $this->context->customer->id);
        }
        if (call_user_func('MyConf::get', 'RLOYALTY_ALL_CATEGORIES', null, $idTemplate)) {
            return null;
        } else {
            $allowedCategories = [];
            $categories = explode(',', call_user_func('MyConf::get', 'RLOYALTY_CATEGORIES', null, $idTemplate));
            foreach ($categories as $category) {
                $allowedCategories[] = ['id_category' => $category];
            }

            return $allowedCategories;
        }
    }

    /**
     * Get product fidelity data
     * @param $idProduct
     * @param $idProductAttribute
     * @return array
     * @throws
     */
    public function getProductFidelityData($idProduct, $idProductAttribute)
    {
        $fidelity = [];
        $productRewardData = [];
        $productGiftData = [];

        $product = new Product($idProduct);

        if ($product->available_for_order
            && $this->_isCustomerAllowed($this->context->customer, true)
            && Validate::isLoadedObject($product)
            && $this->_isProductAllowed($idProduct)
            && $this->fidelityProgram
        ) {
            $productRewardData = $this->getProductReward($idProduct);
        }

        if ($this->_isGiftProductAllowed((int) call_user_func('MyConf::getIdTemplate', 'core', $this->context->customer->id), $idProduct, $idProductAttribute)
            && call_user_func('RewardsModel::isCustomerAllowedForGiftProduct')) {
            $productGiftData = $this->getProductGift($idProduct, $idProductAttribute);
        }

        $fidelity = array_merge($fidelity, $productRewardData, $productGiftData);

        return $fidelity;
    }

    /**
     * Get cart fidelity data
     * @param $cart
     * @param $idCustomer
     * @param $currency
     * @return array
     * @throws
     */
    public function getCartFidelityData($cart, $idCustomer, $currency)
    {
        $customer = new Customer($idCustomer);
        if ($this->_isCustomerAllowed($customer, true)
            && Validate::isLoadedObject($cart)
            && $this->fidelityProgram
        ) {
            $id_template = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', $customer->id);
            if ((int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $id_template) != 2) {
                $total = $this->_getCartTotalForReward();
                $total = call_user_func('RewardsModel::getCurrencyValue', $total, Configuration::get('PS_CURRENCY_DEFAULT'));
                $credits = $this->_getNbCreditsByPrice($customer->id, $total, $currency->id);
            } else {
                $credits = $this->_getCartRewardByProduct($cart);
                // $credits = call_user_func('RewardsModel::getCurrencyValue',$credits, Configuration::get('PS_CURRENCY_DEFAULT'));
            }
            $displayCredits = ((float) $credits > 0) ? true : false;
            $credits = $this->fidelityModule->getRewardReadyForDisplay(
                (float) $credits,
                (int) $currency->id,
                null,
                false
            );

            return $this->presentFidelityCredits($credits, $displayCredits);
        }

        return [];
    }

    /**
     * Get product detail reward data
     * @param $id_product
     * @param int $id_product_attribute
     * @return array
     * @throws
     */
    public function getProductReward($id_product, $id_product_attribute = 0)
    {
        $rewardData = [];
        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', $this->context->customer->id);
        $rewards_on_total = (int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $id_template) == 2 ? false : true;
        $product = new Product((int) $id_product);
        $product->id_product_attribute = $id_product_attribute;
        if (Validate::isLoadedObject($this->context->cart)) {
            if ($rewards_on_total) {
                $total_before = $this->_getCartTotalForReward();
                $total_after = $this->_getCartTotalForReward($product);
                $credits_before = (float) $this->_getNbCreditsByPrice($this->context->customer->id, $total_before, $this->context->currency->id);
                $credits_after = (float) ($this->_getNbCreditsByPrice($this->context->customer->id, $total_after, $this->context->currency->id));
            } else {
                $credits_before = $this->_getCartRewardByProduct($this->context->cart);
                $credits_after = $this->_getCartRewardByProduct($this->context->cart, $product);
            }
            $credits = (float) ($credits_after - $credits_before);
        } else {
            if (!(int) (call_user_func('MyConf::get', 'RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template)) && call_user_func('RewardsModel::isDiscountedProduct', $product->id)) {
                $credits = $credits_before = $credits_after = 0;
                $this->noPtsDiscounted = true;
            } else {
                $credits_before = 0;
                if ($rewards_on_total) {
                    $total_after = $this->_getCartTotalForReward($product);
                    $credits_after = (float) ($this->_getNbCreditsByPrice($this->context->customer->id, $total_after, $this->context->currency->id));
                } else {
                    $credits_after = $this->_getCartRewardByProduct(null, $product);
                }
                $credits = $credits_after;
            }
        }

        if ($credits == 0 && (int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $id_template) != 0 && !$this->noPtsDiscounted) {
            return $rewardData;
        }

        $credits = $this->fidelityModule->getRewardReadyForDisplay((float) $credits, (int) $this->context->currency->id, null, false);
        $displayCredits = $credits > 0 ? true : false;
        $totalCredits = $this->fidelityModule->getRewardReadyForDisplay((float) $credits_after, (int) $this->context->currency->id, null, false);
        $minimum = round(Tools::convertPrice(call_user_func('MyConf::get', 'RLOYALTY_POINT_RATE', null, $id_template), (int) $this->context->currency->id), 2);

        return $this->presentProductCredits($displayCredits, $credits, $totalCredits, $minimum, $this->noPtsDiscounted);
    }

    /**
     * Get product detail gift data
     * @param $idProduct
     * @param $idProductAttribute
     * @return mixed
     */
    public function getProductGift($idProduct, $idProductAttribute)
    {
        $accountInfo    = $this->getRewardsAccount($this->context->customer->id, false);
        $purchaseButton = json_decode($this->fidelityModule->core->displayPurchaseButtonOnProductPage($idProduct, $idProductAttribute));
        $enabled        = !$purchaseButton->has_error && (bool) $purchaseButton->aior_show_buy_button;

        return $this->presentProductGift($enabled, $accountInfo['total_available']);
    }

    /**
     * Get the total of the cart for the reward calculation, in the cart currency
     * @param null $newProduct
     * @return float|int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function _getCartTotalForReward($newProduct = null)
    {
        $benefits = false;
        $total = 0;
        $cartProducts = [];
        $taxesEnabled = Product::getTaxCalculationMethod();
        $cart_currency = $this->context->currency;
        $cart = $this->context->cart;
        $id_template = 0;
        $allowedCategories = $this->_getAllowedCategories();

        if (Validate::isLoadedObject($cart)) {
            $cartProducts = $cart->getProducts();
            $taxesEnabled = Product::getTaxCalculationMethod((int) $cart->id_customer);
            $cart_currency = new Currency((int) $cart->id_currency);
            $id_template = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', (int) $cart->id_customer);
        }

        if (isset($newProduct) && !empty($newProduct->id)) {
            $found = false;
            foreach ($cartProducts as $key => $product) {
                if ($newProduct->id == $product['id_product'] && $newProduct->id_product_attribute == $product['id_product_attribute']) {
                    $found = true;
                    $cartProducts[$key]['cart_quantity']++;
                }
            }
            if (!$found) {
                $cartProductsNew = [];
                $cartProductsNew['id_product'] = (int) $newProduct->id;
                $cartProductsNew['id_product_attribute'] = $newProduct->id_product_attribute ? (int) $newProduct->id_product_attribute : (int) $newProduct->getIdProductAttributeMostExpensive();
                $cartProductsNew['price'] = number_format($newProduct->getPrice(false, $cartProductsNew['id_product_attribute']), 2, '.', '');
                if ($taxesEnabled != PS_TAX_EXC && call_user_func('MyConf::get', 'RLOYALTY_TAX', null, $id_template)) {
                    $cartProductsNew['price_wt'] = number_format($newProduct->getPrice(true, $cartProductsNew['id_product_attribute']), 2, '.', '');
                }
                $cartProductsNew['cart_quantity'] = 1;
                if ($benefits) {
                    $product_attribute = $newProduct->getAttributeCombinationsById($cartProductsNew['id_product_attribute'], (int) (Configuration::get('PS_LANG_DEFAULT')));
                    $cartProductsNew['wholesale_price'] = isset($product_attribute[0]['wholesale_price']) && (float) ($product_attribute[0]['wholesale_price']) > 0 ? (float) $product_attribute[0]['wholesale_price'] : (float) $newProduct->wholesale_price;
                }
                $cartProducts[] = $cartProductsNew;
            }
        }

        $gifts = [];
        if (Validate::isLoadedObject($cart)) {
            foreach ($cart->getCartRules(CartRule::FILTER_ACTION_GIFT) as $rule) {
                $cart_rule = new CartRule($rule['id_cart_rule']);
                $gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute] = isset($gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute]) ? $gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute] + 1 : 1;
            }
        }

        foreach ($cartProducts as $product) {
            if ((!call_user_func('MyConf::get', 'RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template) && call_user_func('RewardsModel::isDiscountedProduct', $product['id_product'], (int) $product['id_product_attribute'])) || (is_array($allowedCategories) && !Product::idIsOnCategoryId($product['id_product'], $allowedCategories))) {
                if (is_object($newProduct) && $product['id_product'] == $newProduct->id && $product['id_product_attribute'] == $newProduct->id_product_attribute) {
                    $this->noPtsDiscounted = true;
                }

                continue;
            }

            $quantity = (int) $product['cart_quantity'] - (isset($gifts[$product['id_product'].'_'.$product['id_product_attribute']]) ? $gifts[$product['id_product'].'_'.$product['id_product_attribute']] : 0);
            if ($benefits) {
                $total += ($product['price'] - ((float) $product['wholesale_price'] * (float) $cart_currency->conversion_rate)) * $quantity;
            } else {
                $total += ($taxesEnabled == PS_TAX_EXC || !call_user_func('MyConf::get', 'RLOYALTY_TAX', null, $id_template) ? $product['price'] : $product['price_wt']) * $quantity;
            }
        }

        if (Validate::isLoadedObject($cart)) {
            foreach ($cart->getCartRules(CartRule::FILTER_ACTION_REDUCTION) as $cart_rule) {
                $total -= $benefits || $taxesEnabled == PS_TAX_EXC || !call_user_func('MyConf::get', 'RLOYALTY_TAX', null, $id_template) ? $cart_rule['value_tax_exc'] : $cart_rule['value_real'];
            }
        }
        if ($total < 0) {
            $total = 0;
        }

        return $total;
    }

    /**
     * Get loyalty reward product by product for a cart, in the cart currency
     * @param $cart
     * @param null $newProduct
     * @return float|int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function _getCartRewardByProduct($cart, $newProduct = null)
    {
        $total = 0;
        $cartProducts = [];
        $taxesEnabled = Product::getTaxCalculationMethod();
        $cart_currency = $this->context->currency;
        $id_template = 0;

        if (Validate::isLoadedObject($cart)) {
            $cartProducts = $cart->getProducts();
            $taxesEnabled = Product::getTaxCalculationMethod((int) $cart->id_customer);
            $cart_currency = new Currency((int) $cart->id_currency);
            $id_template = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', (int) $cart->id_customer);
        }

        if (isset($newProduct) && !empty($newProduct->id)) {
            $found = false;
            foreach ($cartProducts as $key => $product) {
                if ($newProduct->id == $product['id_product'] && $newProduct->id_product_attribute == $product['id_product_attribute']) {
                    $found = true;
                    $cartProducts[$key]['cart_quantity']++;
                }
            }
            if (!$found) {
                $cartProductsNew = [];
                $cartProductsNew['id_product'] = (int) $newProduct->id;
                $cartProductsNew['id_product_attribute'] = $newProduct->id_product_attribute ? (int) $newProduct->id_product_attribute : (int) $newProduct->getIdProductAttributeMostExpensive();
                $cartProductsNew['price'] = number_format($newProduct->getPrice(false, $cartProductsNew['id_product_attribute']), 2, '.', '');
                if ($taxesEnabled != PS_TAX_EXC && call_user_func('MyConf::get', 'RLOYALTY_TAX', null, $id_template)) {
                    $cartProductsNew['price_wt'] = number_format($newProduct->getPrice(true, $cartProductsNew['id_product_attribute']), 2, '.', '');
                }
                $cartProductsNew['cart_quantity'] = 1;
                $cartProducts[] = $cartProductsNew;
            }
        }

        $gifts = [];
        if (Validate::isLoadedObject($cart)) {
            foreach ($cart->getCartRules(CartRule::FILTER_ACTION_GIFT) as $rule) {
                $cart_rule = new CartRule($rule['id_cart_rule']);
                $gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute] = isset($gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute]) ? $gifts[$cart_rule->gift_product.'_'.$cart_rule->gift_product_attribute] + 1 : 1;
            }
        }

        foreach ($cartProducts as $product) {
            if ((!call_user_func('MyConf::get', 'RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template) && call_user_func('RewardsModel::isDiscountedProduct', $product['id_product'], (int) $product['id_product_attribute']))) {
                if (is_object($newProduct) && $product['id_product'] == $newProduct->id && $product['id_product_attribute'] == $newProduct->id_product_attribute) {
                    $this->noPtsDiscounted = true;
                }

                continue;
            }

            $quantity = (int) $product['cart_quantity'] - (isset($gifts[$product['id_product'].'_'.$product['id_product_attribute']]) ? $gifts[$product['id_product'].'_'.$product['id_product_attribute']] : 0);
            $price = $taxesEnabled == PS_TAX_EXC || !call_user_func('MyConf::get', 'RLOYALTY_TAX', null, $id_template) ? $product['price'] : $product['price_wt'];
            $total += (float) call_user_func('RewardsProductModel::getProductReward', (int) $product['id_product'], $price, $quantity, $cart_currency->id, $id_template, 'loyalty');
        }

        if ($total < 0) {
            $total = 0;
        }

        return $total;
    }

    /**
     * Get the reward calculated from a price in a specific currency, and converted in the 2nd currency
     * @param $id_customer
     * @param $price
     * @param $idCurrencyFrom
     * @param null $idCurrencyTo
     * @param array $extraParams
     * @return float
     */
    public function _getNbCreditsByPrice($id_customer, $price, $idCurrencyFrom, $idCurrencyTo = null, $extraParams = [])
    {
        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'loyalty', $id_customer);
        if (!isset($idCurrencyTo)) {
            $idCurrencyTo = $idCurrencyFrom;
        }

        if (Configuration::get('PS_CURRENCY_DEFAULT') != $idCurrencyFrom) {
            // converti de la devise du client vers la devise par défaut
            $price = Tools::convertPrice($price, Currency::getCurrency($idCurrencyFrom), false);
        }
        /* Prevent division by zero */
        $credits = 0;
        if ((int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $id_template) == 0) {
            $credits = floor(number_format($price, 2, '.', '') / (float) call_user_func('MyConf::get', 'RLOYALTY_POINT_RATE', null, $id_template)) * (float) call_user_func('MyConf::get', 'RLOYALTY_POINT_VALUE', null, $id_template);
        } elseif ((int) call_user_func('MyConf::get', 'RLOYALTY_TYPE', null, $id_template) == 1) {
            $credits = number_format($price, 2, '.', '') * (float) call_user_func('MyConf::get', 'RLOYALTY_PERCENTAGE', null, $id_template) / 100;
        }

        return round(Tools::convertPrice($credits, Currency::getCurrency($idCurrencyTo)), 2);
    }

    /**
     * Get customer rewards history
     * @param $idCustomer
     * @param $page
     * @param $limit
     * @return array
     */
    public function getRewardsHistory($idCustomer, $page, $limit)
    {
        $rewardsHistory = [];
        $totalRewards = count(call_user_func('RewardsModel::getAllByIdCustomer', (int) $idCustomer));
        $maxPage = ceil($totalRewards/$limit);
        $totalRewards = call_user_func('RewardsModel::getAllByIdCustomer', (int) $idCustomer, false, false, false, null, null);
        $rewardsHistory['count'] = $totalRewards ? count($totalRewards) : 0;
        $rewardsHistory['rewards'] = [];
        if ($page > $maxPage) {
            $rewards = [];
        } else {
            $rewards = call_user_func('RewardsModel::getAllByIdCustomer', (int) $idCustomer, false, false, true, $limit, $page);
        }
        if ($rewards && count($rewards) > 0) {
            foreach ($rewards as $reward) {
                $rewardsHistory['rewards'][] = [
                    'detail'   => strip_tags($reward['detail']),
                    'date'     => date('d-m-Y H:i:s', strtotime($reward['date_add'])),
                    'credits'  => $this->fidelityModule->getRewardReadyForDisplay($reward['credits'], (int) $this->context->currency->id),
                    'state'    => $reward['state'],
                    'validity' => ($reward['date_end'] !== '0000-00-00 00:00:00') ? date('d-m-Y', strtotime($reward['date_end'])) : '-'
                ];
            }
        }

        return $rewardsHistory;
    }

    /**
     * Get customer rewards account
     * @param $idCustomer
     * @return array
     */
    public function getRewardsAccount($idCustomer, $actions = true)
    {
        $rewardsAccount = [];
        $rewardsData = $this->getRewardsData($idCustomer);

        if ($rewardsData['return_days']) {
            $rewardsAccount['return_days'] = sprintf($this->module->l('Rewards will be available %s days after the validation of each order.', 'allinonerewardsfidelityhandler'), $rewardsData['return_days']);
        } else {
            $rewardsAccount['return_days'] = null;
        }

        if ($rewardsData['general_txt']) {
            $rewardsAccount['general_txt'] = strip_tags($rewardsData['general_txt']);
        } else {
            $rewardsAccount['general_txt'] = null;
        }

        $rewardsAccount['total_global']          = $rewardsData['totalGlobal'];
        $rewardsAccount['total_converted']       = $rewardsData['totalConverted'];
        $rewardsAccount['total_paid']            = $rewardsData['totalPaid'];
        $rewardsAccount['total_available']       = $rewardsData['totalAvailable'];
        $rewardsAccount['total_pending']         = $rewardsData['totalPending'];
        $rewardsAccount['total_waiting_payment'] = $rewardsData['totalWaitingPayment'];

        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'core', $idCustomer);
        if ($actions) {
            $giftListEnabled         = (bool) Configuration::get('REWARDS_GIFT') && call_user_func('MyConf::get', 'REWARDS_GIFT_SHOW_LINK', null, $id_template);
            $askForPaymentEnabled    = (bool) Configuration::get('REWARDS_PAYMENT');
            $transformCreditsEnabled = (bool) Configuration::get('REWARDS_VOUCHER');
            if ($transformCreditsEnabled) {
                $voucherType = (int) Configuration::get('REWARDS_VOUCHER_TYPE');

                /* if ($voucherType == 1) {
                    $presentTransformCreditsAction = $this->presentTransformCreditsAction($rewardsData['totalAvailableCurrency'], $transformCreditsEnabled);
                }
                */
                if ($voucherType == 2) {
                    $getVoucherListValues = Configuration::get('REWARDS_VOUCHER_LIST_VALUES');
                    $voucherListValues = explode(";", $getVoucherListValues);

                    foreach ($voucherListValues as $value) {
                        if ((float) $rewardsData['totalAvailable'] >= (float) $value) {
                            $valueAndCurrency = Tools::displayPrice(call_user_func('RewardsModel::getCurrencyValue', $value, $this->context->currency->id), $this->context->currency->id);
                            $points = Configuration::get('REWARDS_VIRTUAL') ? call_user_func('allinone_rewards::getRewardReadyForDisplay', $value, (int) $this->context->currency->id) : '';
                            $rewards[] = [
                                'value' => $value,
                                'label' => $valueAndCurrency.' ('.$points.')'
                            ];
                        }
                    }

                    $presentTransformCreditsAction = $this->presentTransformCreditsActionDiffValues($transformCreditsEnabled && count($rewards) > 0);

                    if (count($rewards) == 1) {
                        $rewards[0]['label'] = $presentTransformCreditsAction['label'].' '.$rewards[0]['label'];
                    }

                    $rewardsAccount['conversion_values'] = $rewards;
                } else {
                    $presentTransformCreditsAction = $this->presentTransformCreditsAction($rewardsData['totalAvailableCurrency'], $transformCreditsEnabled);
                }
            }

            $rewardsAccount['rewards_actions'] = [
                $this->presentGiftsListAction($giftListEnabled),
                $presentTransformCreditsAction,
                $this->presentAskForPaymentAction($askForPaymentEnabled)
            ];
        }

        return $this->getEnableRewardActions($rewardsAccount);
    }

    public function getEnableRewardActions(&$rewardsAccount)
    {
        $clone = $rewardsAccount['rewards_actions'];
        $rewardsAccount['rewards_actions'] = [];

        foreach ($clone as $rewardAction) {
            if (!$rewardAction['enabled']) {
                continue;
            } else {
                $rewardsAccount['rewards_actions'][] = $rewardAction;
            }
        }

        return $rewardsAccount;
    }

    /**
     * Get customer rewards data
     * @param $idCustomer
     * @param bool $pagination
     * @param int $resultsPerPage
     * @param int $page
     * @return array
     */
    public function getRewardsData($idCustomer, $pagination = false, $resultsPerPage = 10, $page = 1)
    {
        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'core', $idCustomer);
        $payment_ratio = (float) call_user_func('MyConf::get', 'REWARDS_PAYMENT_RATIO', null, $id_template);

        $totals = call_user_func('RewardsModel::getAllTotalsByCustomer', (int) $idCustomer);
        $totalGlobal = isset($totals['total']) ? (float) $totals['total'] : 0;
        $totalConverted = isset($totals[call_user_func('RewardsStateModel::getConvertId')]) ? (float) $totals[call_user_func('RewardsStateModel::getConvertId')] : 0;
        $totalAvailable = isset($totals[call_user_func('RewardsStateModel::getValidationId')]) ? (float) $totals[call_user_func('RewardsStateModel::getValidationId')] : 0;
        $totalPending = (isset($totals[call_user_func('RewardsStateModel::getDefaultId')]) ? (float) $totals[call_user_func('RewardsStateModel::getDefaultId')] : 0) + (isset($totals[call_user_func('RewardsStateModel::getReturnPeriodId')]) ? $totals[call_user_func('RewardsStateModel::getReturnPeriodId')] : 0);
        $totalWaitingPayment = isset($totals[call_user_func('RewardsStateModel::getWaitingPaymentId')]) ? (float) $totals[call_user_func('RewardsStateModel::getWaitingPaymentId')] : 0;
        $totalPaid = isset($totals[call_user_func('RewardsStateModel::getPaidId')]) ? (float) $totals[call_user_func('RewardsStateModel::getPaidId')] : 0;
        $totalForPaymentDefaultCurrency = round($totalAvailable * $payment_ratio / 100, 2);

        $totalAvailableUserCurrency = call_user_func('RewardsModel::getCurrencyValue', $totalAvailable, $this->context->currency->id);
        $voucherMininum = (float) call_user_func('MyConf::get', 'REWARDS_VOUCHER_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template) > 0 ? (float) call_user_func('MyConf::get', 'REWARDS_VOUCHER_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template) : 0;
        $paymentMininum = (float) call_user_func('MyConf::get', 'REWARDS_PAYMENT_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template) > 0 ? (float) call_user_func('MyConf::get', 'REWARDS_PAYMENT_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template) : 0;

        $giftAllowed = call_user_func('RewardsModel::isCustomerAllowedForGiftProduct');
        $voucherAllowed = call_user_func('RewardsModel::isCustomerAllowedForVoucher');
        $paymentAllowed = call_user_func('RewardsModel::isCustomerAllowedForPayment');

        $link = $this->context->link->getModuleLink('allinone_rewards', 'rewards', [], true);

        $rewards = call_user_func('RewardsModel::getAllByIdCustomer', (int) $idCustomer, false, false, $pagination, $resultsPerPage, $page);

        $displayrewards = call_user_func('RewardsModel::getAllByIdCustomer', (int) $idCustomer, false, false, true, $resultsPerPage, (int) Tools::getValue('page') > 0 ? (int) Tools::getValue('page') : 1, $this->context->currency->id, true);

        $rewardsData = [
            'return_days' => (Configuration::get('REWARDS_WAIT_RETURN_PERIOD') && Configuration::get('PS_ORDER_RETURN') && (int) Configuration::get('PS_ORDER_RETURN_NB_DAYS') > 0) ? (int) Configuration::get('PS_ORDER_RETURN_NB_DAYS') : 0,
            'rewards' => $rewards,
            'cart_rules' => call_user_func('RewardsModel::getCartRulesFromRewards', (int) $idCustomer),
            'cart_rules_available' => call_user_func('RewardsModel::getCartRulesFromRewards', (int) $idCustomer, true),
            'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order',
            'rewards_virtual' => (int) call_user_func('MyConf::get', 'REWARDS_VIRTUAL', null, $id_template),
            'show_link' => $giftAllowed && (int) call_user_func('MyConf::get', 'REWARDS_GIFT_SHOW_LINK', null, $id_template),
            'displayrewards' => $displayrewards,
            ' ' => $link.(strpos($link, '?') !== false ? '&' : '?'),
            'totalGlobal' => $this->fidelityModule->getRewardReadyForDisplay($totalGlobal, (int) $this->context->currency->id),
            'totalConverted' => $this->fidelityModule->getRewardReadyForDisplay($totalConverted, (int) $this->context->currency->id),
            'totalAvailable' => $this->fidelityModule->getRewardReadyForDisplay($totalAvailable, (int) $this->context->currency->id),
            'totalAvailableCurrency' => Tools::displayPrice($totalAvailableUserCurrency, $this->context->currency),
            'totalPending' => $this->fidelityModule->getRewardReadyForDisplay($totalPending, (int) $this->context->currency->id),
            'totalWaitingPayment' => $this->fidelityModule->getRewardReadyForDisplay($totalWaitingPayment, (int) $this->context->currency->id),
            'totalPaid' => $this->fidelityModule->getRewardReadyForDisplay($totalPaid, (int) $this->context->currency->id),
            'convertColumns' => ($voucherAllowed || $totalConverted > 0) ? true : false,
            'paymentColumns' => ($paymentAllowed || $totalPaid > 0 || $totalWaitingPayment > 0) ? true : false,
            'totalForPaymentDefaultCurrency' => Tools::displayPrice($totalForPaymentDefaultCurrency, (int) Configuration::get('PS_CURRENCY_DEFAULT')),
            'voucherMinimum' => $this->fidelityModule->getRewardReadyForDisplay($voucherMininum, (int) $this->context->currency->id),
            'voucher_minimum_allowed' => $voucherAllowed && $voucherMininum > 0 ? true : false,
            'voucher_button_allowed' => $voucherAllowed && $totalAvailableUserCurrency >= $voucherMininum && $totalAvailableUserCurrency > 0,
            'paymentMinimum' => $this->fidelityModule->getRewardReadyForDisplay($paymentMininum, (int) $this->context->currency->id),
            'payment_minimum_allowed' => $paymentAllowed && $paymentMininum > 0 ? true : false,
            'payment_button_allowed' => $paymentAllowed && $totalAvailableUserCurrency >= $paymentMininum && $totalForPaymentDefaultCurrency > 0,
            'payment_txt' => call_user_func('MyConf::get', 'REWARDS_PAYMENT_TXT', (int) $this->context->language->id, $id_template),
            'general_txt' => call_user_func('MyConf::get', 'REWARDS_GENERAL_TXT', (int) $this->context->language->id, $id_template),
            'raw' => [
                'totalAvailableUserCurrency' => $totalAvailableUserCurrency,
                'voucherMinimum' => $voucherMininum,
                'paymentMinimum' => $paymentMininum
            ]
        ];

        return $rewardsData;
    }

    /**
     * Get the products gifts list
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderWay
     * @return array
     */
    public function getProductsGiftsList($page, $limit, $orderBy, $orderWay)
    {
        $total   = call_user_func('RewardsGiftProductModel::getGiftsProducts', $this->context->language->id, null, null, null, null, true);
        $maxPage = ceil($total/$limit);
        if ($page > $maxPage) {
            $products = [];
        } else {
            $products = call_user_func('RewardsGiftProductModel::getGiftsProducts', $this->context->language->id, $page, $limit, $orderBy, $orderWay, false);
        }

        return [
            'total'  => $total,
            'result' => $products,
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @throws FidelityHandlerException
     */
    public function transformCredits($customerId, $params)
    {
        $this->_isCustomerAllowedAction('REWARDS_VOUCHER');
        $rewardsData = $this->getRewardsData($customerId);
        if ($rewardsData['voucher_minimum_allowed'] && $rewardsData['raw']['totalAvailableUserCurrency'] < $rewardsData['raw']['voucherMinimum']) {
            throw new FidelityHandlerException($this->module->l('The minimum required to be able to transform your rewards into vouchers is', 'allinonerewardsfidelityhandler').' '.$rewardsData['voucherMinimum']);
        }
        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'core', $customerId);
        $totals = call_user_func('RewardsModel::getAllTotalsByCustomer', (int) $customerId);
        $totalAvailablePoints = isset($totals[call_user_func('RewardsStateModel::getValidationId')]) ? (float) $totals[call_user_func('RewardsStateModel::getValidationId')] : 0;
        $totalAvailable = (float) $params['amount'];
        if (!$totalAvailable) {
            $totalAvailable = isset($totals[call_user_func('RewardsStateModel::getValidationId')]) ? (float) $totals[call_user_func('RewardsStateModel::getValidationId')] : 0;
        }
        $totalAvailableUserCurrency = call_user_func('RewardsModel::getCurrencyValue', $totalAvailable, $this->context->currency->id);
        $voucherMininum = (float) call_user_func('MyConf::get', 'REWARDS_VOUCHER_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template) > 0
            ? (float) call_user_func('MyConf::get', 'REWARDS_VOUCHER_MIN_VALUE_'.(int) $this->context->currency->id, null, $id_template)
            : 0;

        $voucherAllowed = call_user_func('RewardsModel::isCustomerAllowedForVoucher');
        if ($voucherAllowed && $totalAvailableUserCurrency <= $totalAvailablePoints && $totalAvailablePoints >= $voucherMininum) {
            call_user_func('RewardsModel::createDiscount', $totalAvailable);

            return true;
        } else {
            return false;
        }
    }

    public function getAskForPaymentUrl($customerId)
    {
        $this->_isCustomerAllowedAction('REWARDS_PAYMENT');
        $rewardsData = $this->getRewardsData($customerId);

        if ($rewardsData['payment_minimum_allowed'] && $rewardsData['raw']['totalAvailableUserCurrency'] < $rewardsData['raw']['paymentMinimum']) {
            throw new FidelityHandlerException($this->module->l('The minimum required to be able to ask for a payment is', 'allinonerewardsfidelityhandler').' '.$rewardsData['paymentMinimum']);
        }

        return Context::getContext()->link->getModuleLink('allinone_rewards', 'rewards', [], true);
    }

    /**
     * Purchase the product with the current rewards.
     * @param $idProduct
     * @param $idProductAttribute
     * @return array
     */
    public function purchaseWithRewards($idProduct, $idProductAttribute)
    {
        $this->_isCustomerAllowedAction('REWARDS_GIFT');

        return (array) json_decode($this->fidelityModule->core->purchaseProductFromRewards($idProduct, $idProductAttribute));
    }

    /**
     * Generic check for customer action:
     * - fidelity program si enabled
     * - minimum orders required
     * - customer group authorized
     * @param $action
     */
    private function _isCustomerAllowedAction($action)
    {
        if (!$this->isFidelityProgramActive()) {
            throw new FidelityHandlerException($this->module->l('Fidelity program is disabled', 'allinonerewardsfidelityhandler'));
        }
        $context = Context::getContext();
        $id_template = (int) call_user_func('MyConf::getIdTemplate', 'core', $context->customer->id);
        $stats = $context->customer->getStats();
        if (call_user_func('MyConf::get', $action, null, $id_template)) {
            $ordersRequired = (int) call_user_func('MyConf::get', $action.'_NB_ORDERS', null, $id_template);
            if ((int) $stats['nb_orders'] < $ordersRequired) {
                throw new FidelityHandlerException($this->module->l('The numbers of orders required to perform this action is', 'allinonerewardsfidelityhandler').' '.$ordersRequired);
            }
            $allowed_groups = explode(',', Configuration::get($action.'_GROUPS'));
            $customer_groups = $context->customer->getGroups();
            if (sizeof(array_intersect($allowed_groups, $customer_groups)) == 0) {
                throw new FidelityHandlerException($this->module->l('Unauthorized customer group', 'allinonerewardsfidelityhandler'));
            }
        } else {
            throw new UnsupportedFidelityActionException($this->module->l('Invalid action', 'allinonerewardsfidelityhandler'));
        }
    }
}
