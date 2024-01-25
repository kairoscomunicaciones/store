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

namespace MrAPPs\MrShopApi\Service;

use Context;
use Currency;
use Tools;

class PriceFormatter
{
    public function convertAmount($price, $currency = null)
    {
        return (float) Tools::convertPrice($price, $currency);
    }

    public function format($price, $currency = null)
    {
        return Tools::displayPrice($price, $currency);
    }

    public function convertAndFormat($price)
    {
        return $this->format($this->convertAmount($price));
    }
    
    /**
     * Returns raw price data
     * @param float $price
     * @param Currency|int|array $currency
     * @return array
     */
    public function getPriceData($price, $currency = null)
    {
        $context  = Context::getContext();
        $currency = $currency ?: $context->currency;

        if (is_int($currency)) {
            $currency = Currency::getCurrencyInstance($currency);
        }
       
        $currencyCode = is_array($currency)
                ? $currency['iso_code']
                : $currency->iso_code;

        return [
            'value'    => (string) Tools::ps_round((float) $price, 2),
            'currency' => $currencyCode
        ];
    }
}
