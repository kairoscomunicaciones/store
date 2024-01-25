<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('multiprice.php');

$multiprice = new multiprice();
$multiprices = array();
$current_currency = Context::getContext()->currency;
if (Tools::getValue('getPrice', 'false') != 'false' && Tools::getValue('id_product', 'false') != 'false' && Tools::getValue('id_product_attribute', 'false') != 'false') {
    if (count($multiprice->mp_curr) > 0) {
        $product = new Product(Tools::getValue('id_product'), true);
        foreach ($multiprice->mp_curr AS $currency => $val) {
            if ($currency != $current_currency->id) {
                $currency_to = new Currency($currency);
                $multiprices[$currency]['tex'] = Tools::displayPrice(Tools::convertPrice($product->getPrice(false, Tools::getValue('id_product_attribute'), 6, null, false, true, Tools::getValue('qty')), $currency_to, $current_currency), $currency_to);
                $multiprices[$currency]['tin'] = Tools::displayPrice(Tools::convertPrice($product->getPrice(true, Tools::getValue('id_product_attribute'), 6, null, false, true, Tools::getValue('qty')), $currency_to, $current_currency), $currency_to);
            }
        }
    }

    if (count($multiprices) <= 0) {
        die(0);
    }

    die(json_encode($multiprices));
}

if (Tools::getValue('getReport') == 1) {
    Foreach (Currency::getCurrencies() AS $currency) {
        echo "<pre>";
        var_dump($currency);
        echo "</pre>";
    }
}