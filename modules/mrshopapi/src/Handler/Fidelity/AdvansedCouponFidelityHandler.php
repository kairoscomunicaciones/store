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
 *
 * WARNING: name typo is inherited from module name typo
 */

namespace MrAPPs\MrShopApi\Handler\Fidelity;

use CartRule;
use Configuration;
use Context;
use Db;
use Language;
use Module;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Product;
use Tools;

class AdvansedCouponFidelityHandler extends AbstractFidelityHandler implements FidelityHandlerInterface
{
    protected $restrictCategoryId;

    protected $module = null;

    protected $dataHandler;

    protected $fidelityModule;

    protected $context;

    public function __construct()
    {
        $this->module  = ApiUtils::getModule();
        $this->context = Context::getContext();
        if ($this->isModuleActive()) {
            $this->fidelityModule = Module::getInstanceByName('advansedcoupons');
            require_once($this->fidelityModule->dir_loyalty_state);
            require_once($this->fidelityModule->dir_loyalty);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCartFidelityData($cart, $id_customer, $currency)
    {
        $points = $this->getCartFidelityPoints($cart);
        $displayCredits = $points > 0;
        $value = Tools::displayPrice($this->getVoucherValue($points, $currency->id));

        return $this->presentFidelityCredits($value, $displayCredits);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductFidelityData($idProduct, $idProductAttribute)
    {
        $product                       = new Product((int) $idProduct);
        $product->id_product_attribute = $idProductAttribute;

        $credits            = $this->getProductCredits($product).' '.$this->module->l('points', 'advansedcouponfidelityhandler');
        $totalCredits       = ($this->getCartFidelityPoints() + (int) $credits).' '.$this->module->l('points', 'advansedcouponfidelityhandler');
        $displayCredits     = false == empty($credits);
        $minimum            = Configuration::get('ADVANCEDLOYALTY_MINIMAL');
        $discountedNoPoints = is_null($credits);

        $productRewardData = $this->presentProductCredits($displayCredits, $credits, $totalCredits, $minimum, $discountedNoPoints);
        // fixed, just to adapt the response interface to the common one
        $productGiftData   = $this->presentProductGift(false, 0);

        return array_merge($productRewardData, $productGiftData);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsGiftsList($page, $limit, $orderBy, $orderWay)
    {
        // unsupported feature
        return [
            'total' => 0,
            'result' => []
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getRewardsAccount($idCustomer, $actions = true)
    {
        $rewardsAccount = $this->getTotalsByCustomerId($idCustomer);

        // If merchandise returns are allowed, the voucher musn't be usable before this max return date
        if (Configuration::get('PS_ORDER_RETURN') && 0 < (int) Configuration::get('PS_ORDER_RETURN_NB_DAYS')) {
            $rewardsAccount['return_days'] = (int) Configuration::get('PS_ORDER_RETURN_NB_DAYS');
        } else {
            $rewardsAccount['return_days'] = null;
        }
        // unsupported data
        $rewardsAccount['general_txt'] = null;
        $totalAvailableVoucher = $this->getVoucherValue($rewardsAccount['total_available'], $this->context->cart->id_currency);

        if ($actions) {
            $rewardsAccount['rewards_actions'] = [
                $this->presentTransformCreditsAction(Tools::displayPrice($totalAvailableVoucher), $rewardsAccount['total_available'] > 0)
            ];
        }

        return $rewardsAccount;
    }

    /**
     * {@inheritDoc}
     */
    public function getRewardsHistory($customerId, $page, $limit)
    {
        $offset = $limit * max($page - 1, 0);

        $sql = "SELECT count(*)
            FROM "._DB_PREFIX_."ws_loyalty_history h
            LEFT JOIN "._DB_PREFIX_."ws_loyalty l ON l.id_loyalty = h.id_loyalty
            WHERE l.id_customer = ".(int) $customerId;

        $total = (int) Db::getInstance()->getValue($sql);

        $maxPage = ceil($total / $limit);

        if ($page <= $maxPage) {
            $sql = "SELECT h.*, lsl.name as state, l.id_order, l.date_end 
            FROM "._DB_PREFIX_."ws_loyalty_history h
            LEFT JOIN "._DB_PREFIX_."ws_loyalty l ON l.id_loyalty = h.id_loyalty
            LEFT JOIN "._DB_PREFIX_."ws_loyalty_state_lang lsl ON lsl.id_loyalty_state = h.id_loyalty_state AND lsl.id_lang = ".(int) $this->context->language->id."
            WHERE l.id_customer = ".(int) $customerId."
            ORDER BY h.id_loyalty_history DESC
            LIMIT ".(int) $offset.",".(int) $limit;

            $rows = Db::getInstance()->executeS($sql);
        } else {
            $rows = [];
        }

        $retval = [
            'count'   => $total,
            'rewards' => []
        ];

        if (false == empty($rows) && is_array($rows)) {
            foreach ($rows as $row) {
                $retval['rewards'][] = [
                    'detail' => sprintf($this->module->l('Order #%s', 'advansedcouponfidelityhandler'), $row['id_order']),
                    'date'   => \DateTime::createFromFormat('Y-m-d H:i:s', $row['date_add'])->format('d-m-Y H:i:s'),
                    'credits' => Tools::displayPrice($this->getVoucherValue($row['points'], $this->context->cart->id_currency), $this->context->currency),
                    'state'  => $row['state'],
                    'validity' => isset($row['date_end']) && '0000-00-00 00:00:00' == $row['date_end']
                        ? \DateTime::createFromFormat('Y-m-d H:i:s', $row['date_end'])->format('d-m-Y H:i:s')
                        : '-'
                ];
            }
        }

        return $retval;
    }

    /**
     * {@inheritDoc}
     */
    public function isFidelityProgramActive()
    {
        return $this->isModuleActive() && false == empty(Configuration::get('COUPON_LOYALTY'));
    }

    /**
     * {@inheritDoc}
     */
    public function isModuleActive()
    {
        return self::isActive();
    }

    /**
     * {@inheritDoc}
     */
    public static function isActive()
    {
        return Module::isEnabled('advansedcoupons');
    }

    /**
     * {@inheritDoc}
     */
    public function isFidelityAvailable()
    {
        return $this->isFidelityProgramActive();
    }

    /**
     * {@inheritDoc}
     */
    public function purchaseWithRewards($idProduct, $idProductAttribute)
    {
        throw new UnsupportedFidelityActionException('Method not allowed');
    }

    /**
     * {@inheritDoc}
     * @throws UnsupportedFidelityActionException
     */
    public function getAskForPaymentUrl($customerId)
    {
        throw new UnsupportedFidelityActionException('Method not allowed');
    }

    /**
     * {@inheritDoc}
     */
    public function transformCredits($customerId, $params)
    {
        $customer_id     = (int) $customerId;
        $customer_points = (int) call_user_func_array('WsAdvLoyaltyModule::getPointsByCustomer', [$customer_id]);

        if ($customer_points <= 0) {
            throw new FidelityHandlerException($this->module->l('You don\'t have credits to be converted', 'advansedcouponfidelityhandler'));
        }

        $categories = $this->getCategoryRestriction();
        if (false == $categories) {
            return false;
        }

        try {
            $cart_rule = $this->generateCartRuleFor($customer_id, $customer_points);
        } catch (UnsupportedFidelityActionException $ex) {
            return false;
        }

        $this->context->cart->addCartRule($cart_rule->id);

        // Register order(s) which contributed to create this voucher
        if (false == call_user_func_array('WsAdvLoyaltyModule::registerDiscount', [$cart_rule])) {
            $cart_rule->delete();

            return false;
        }

        return true;
    }

    /**
     * Generates the cart rule (voucher) for the passed user with the passed points
     * @param int $customer_id
     * @param int $customer_points
     * @return \CartRule
     */
    protected function generateCartRuleFor($customer_id, $customer_points)
    {
        /* Generate a voucher code */
        $voucher_code = null;
        do {
            $voucher_code = 'FID'.rand(1000, 100000);
        } while (CartRule::cartRuleExists($voucher_code));

        $currency_id = (int) $this->context->currency->id;

        // Voucher creation and affectation to the customer
        $cart_rule = new CartRule();
        $cart_rule->code                  = $voucher_code;
        $cart_rule->id_customer           = (int) $customer_id;
        $cart_rule->reduction_currency    = $currency_id;
        $cart_rule->reduction_amount      = $this->getVoucherValue((int) $customer_points);
        $cart_rule->quantity              = 1;
        $cart_rule->highlight             = 1;
        $cart_rule->partial_use           = 0;
        $cart_rule->quantity_per_user     = 1;
        $cart_rule->cart_rule_restriction = 1;
        $cart_rule->reduction_tax         = (bool) Configuration::get('ADVANCEDLOYALTY_TAX');

        $sql = 'SELECT UNIX_TIMESTAMP(date_add) n
                FROM '._DB_PREFIX_.'ws_loyalty
                WHERE
                    id_cart_rule = 0
                    AND id_customer = '.(int) $customer_id.'
                ORDER BY date_add DESC';

        $date_from = Db::getInstance()->getValue($sql);

        // If merchandise returns are allowed, the voucher musn't be usable before this max return date
        if (Configuration::get('PS_ORDER_RETURN')) {
            $date_from += 60 * 60 * 24 * (int) Configuration::get('PS_ORDER_RETURN_NB_DAYS');
        }

        $date_to = $date_from + (int) Configuration::get('ADVANCEDLOYALTY_VALIDITY_PERIOD') * 24 * 60 * 60;

        $cart_rule->date_from = date('Y-m-d H:i:s', $date_from);
        $cart_rule->date_to   = date('Y-m-d H:i:s', $date_to);

        $cart_rule->minimum_amount          = (float) Configuration::get('ADVANCEDLOYALTY_MINIMAL');
        $cart_rule->minimum_amount_currency = $currency_id;
        $cart_rule->active = 1;

        $categories = $this->getCategoryRestriction();

        if (false === $categories) {
            throw new UnsupportedFidelityActionException("Can't create voucher without restrictions");
        }

        $languages    = Language::getLanguages(true);
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $default_text = Configuration::get('ADVANCEDLOYALTY_VOUCHER_DETAILS', $default_lang);

        foreach ($languages as $language) {
            $text = Configuration::get('ADVANCEDLOYALTY_VOUCHER_DETAILS', (int) $language['id_lang']);
            $cart_rule->name[(int) $language['id_lang']] = $text ? $text : $default_text;
        }

        $contains_categories = is_array($categories) && count($categories);
        if ($contains_categories) {
            $cart_rule->product_restriction = 1;
        }
        $cart_rule->add();

        if ($contains_categories) {
            $this->saveCategoryRestrictionOn($cart_rule->id);
        }

        return $cart_rule;
    }

    /**
     * Restrict cart rule with categories
     * @param int $cart_rule_id
     */
    protected function saveCategoryRestrictionOn($cart_rule_id)
    {
        $categories = $this->getCategoryRestriction();

        $id_cart_rule = (int) $cart_rule_id;

        // Creating rule group
        $sql = "INSERT INTO "._DB_PREFIX_."cart_rule_product_rule_group (id_cart_rule, quantity)
            VALUES ('$id_cart_rule', 1)";
        Db::getInstance()->execute($sql);
        $id_group = (int) Db::getInstance()->Insert_ID();

        // Creating product rule
        $sql = "INSERT INTO "._DB_PREFIX_."cart_rule_product_rule (id_product_rule_group, type)
            VALUES ('$id_group', 'categories')";
        Db::getInstance()->execute($sql);
        $id_product_rule = (int) Db::getInstance()->Insert_ID();

        // Creating restrictions
        $values = [];
        foreach ($categories as $category) {
            $category = (int) $category;
            $values[] = "('$id_product_rule', '$category')";
        }
        $values = implode(',', $values);
        $sql = "INSERT INTO "._DB_PREFIX_."cart_rule_product_rule_value (id_product_rule, id_item)
            VALUES $values";
        Db::getInstance()->execute($sql);
    }

    protected function getCategoryRestriction()
    {
        if (is_null($this->restrictCategoryId)) {
            $categories = Configuration::get('ADVANCEDLOYALTY_VOUCHER_CATEGORY');
            if ($categories != '' && $categories != 0) {
                $this->restrictCategoryId = explode(',', $categories);
            } else {
                $this->restrictCategoryId = false;
            }
        }

        return $this->restrictCategoryId;
    }

    protected function getVoucherValue($points, $currency_id = null)
    {
        return call_user_func_array('WsAdvLoyaltyModule::getVoucherValue', [$points, $currency_id]);
    }

    protected function getCartFidelityPoints($cart = null)
    {
        if (is_null($cart)) {
            $cart = $this->context->cart;
        }

        return call_user_func_array('WsAdvLoyaltyModule::getCartNbPoints', [$cart]);
    }

    protected function getNbCreditsByPrice($price)
    {
        return call_user_func_array('WsAdvLoyaltyModule::getNbPointsByPrice', [$price]);
    }

    /**
     * Gets credits generated by product
     * @param Product $product
     * @return int
     */
    protected function getProductCredits($product)
    {
        if (!(int) (Configuration::get('ADVANCEDLOYALTY_NONE_AWARD')) && Product::isDiscounted((int) $product->id)) {
            return null;
        }
        $taxesEnabled = Product::getTaxCalculationMethod();
        $price = $taxesEnabled == PS_TAX_EXC
                ? $product->price
                : $product->getPrice();

        return $this->getNbCreditsByPrice($price);
    }

    protected function getTotalsByCustomerId($customerId)
    {
        $retval = [
            'total_global'          => 0,
            'total_converted'       => 0,
            'total_paid'            => '',
            'total_available'       => 0,
            'total_pending'         => 0,
            'total_waiting_payment' => ''
        ];

        // lo so che questa è brutta ma non c'era altro modo per farla
        $sql = "SELECT id_loyalty_state, sum(points) as total
                FROM "._DB_PREFIX_."ws_loyalty
                WHERE id_customer = ".(int) $customerId."
                GROUP BY id_loyalty_state";

        $results = Db::getInstance()->executeS($sql);

        if (false == empty($results) && is_array($results)) {
            $list = array_reduce($results, function ($acc, $row) {
                $acc[$row['id_loyalty_state']] = (int) $row['total'];

                return $acc;
            }, []);

            $retval['total_global'] = array_sum($list).' '.$this->module->l('points', 'advansedcouponfidelityhandler');
            $map = $this->getTotalToStatesMap();

            foreach ($map as $id_loyalty_state => $key) {
                $retval[$key] = (isset($list[$id_loyalty_state])
                        ? $list[$id_loyalty_state]
                        : 0).' '.$this->module->l('points', 'advansedcouponfidelityhandler');
            }
        }

        return $retval;
    }

    protected function getTotalToStatesMap()
    {
        $retval = [];
        $retval[call_user_func('WsAdvLoyaltyStateModule::getConvertId')]    = 'total_converted';
        $retval[call_user_func('WsAdvLoyaltyStateModule::getValidationId')] = 'total_available';
        $retval[call_user_func('WsAdvLoyaltyStateModule::getDefaultId')]    = 'total_pending';

        return $retval;
    }
}
