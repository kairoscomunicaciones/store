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

use CartRule;
use Exception;
use MrAPPs\MrShopApi\Api\BaseWS;
use Product;
use Tools;

class VoucherWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getList($params, $id_customer)
    {
        $result = [];

        try {
            $vouchers = CartRule::getCustomerCartRules($this->context->language->id, $id_customer, true, false);
            foreach ($vouchers as $voucher) {
                if (version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                    $voucherDate = Tools::displayDate($voucher['date_to'], false);
                } else {
                    $voucherDate = Tools::displayDate($voucher['date_to'], $this->context->language->id, false);
                }
                $cartRule = [
                    'id_cart_rule' => (int) $voucher['id_cart_rule'],
                    'description' => $voucher['description'],
                    'code' => $voucher['code'],
                    'name' => $voucher['name'],
                    'voucher_date' => $voucherDate,
                    'voucher_minimal' => ($voucher['minimum_amount'] > 0) ? Tools::displayPrice($voucher['minimum_amount'], (int) $voucher['minimum_amount_currency']) : $this->module->l('None', 'voucherws'),
                    'voucher_cumulable' => $this->module->l($voucher['cart_rule_restriction'] ? 'Yes' : 'No', 'voucherws'),
                ];

                $cartRuleValue = $this->accumulateCartRuleValue($voucher);

                if (0 === count($cartRuleValue)) {
                    $cartRule['value'] = '-';
                } else {
                    $cartRule['value'] = implode(' + ', $cartRuleValue);
                }

                $result[] = $cartRule;
            }
        } catch (Exception $exception) {
            $this->response(false);
        }

        $this->response(true, null, $result);
    }

    /**
     * @param $hasTaxIncluded
     * @param $amount
     * @param $currencyId
     *
     * @return string
     */
    protected function formatReductionAmount($hasTaxIncluded, $amount, $currencyId)
    {
        if ($hasTaxIncluded) {
            $taxTranslation = $this->module->l('Tax included', 'voucherws');
        } else {
            $taxTranslation = $this->module->l('Tax excluded', 'voucherws');
        }

        return sprintf(
            '%s '.$taxTranslation,
            Tools::displayPrice($amount, (int) $currencyId)
        );
    }

    /**
     * @param $percentage
     *
     * @return string
     */
    protected function formatReductionInPercentage($percentage)
    {
        return sprintf('%s%%', $percentage);
    }

    /**
     * @param $voucher
     *
     * @return array
     */
    protected function accumulateCartRuleValue($voucher)
    {
        $cartRuleValue = [];

        if ($voucher['reduction_percent'] > 0) {
            $cartRuleValue[] = $this->formatReductionInPercentage($voucher['reduction_percent']);
        }

        if ($voucher['reduction_amount'] > 0) {
            $cartRuleValue[] = $this->formatReductionAmount(
                $voucher['reduction_tax'],
                $voucher['reduction_amount'],
                $voucher['reduction_currency']
            );
        }

        if ($voucher['free_shipping']) {
            $cartRuleValue[] = $this->module->l('Free shipping', 'voucherws');
        }

        if ($voucher['gift_product'] > 0) {
            $cartRuleValue[] = Product::getProductName(
                $voucher['gift_product'],
                $voucher['gift_product_attribute']
            );
        }

        return $cartRuleValue;
    }
}
