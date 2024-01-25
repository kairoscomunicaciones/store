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
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_5($module)
{
    $registrationMsg = trim(Configuration::get('MRSHOP_REGISTRATION_MSG', $module->context->language->id, null, null, ''));
    $profileMsg = trim(Configuration::get('MRSHOP_PROFILE_MSG', $module->context->language->id, null, null, ''));

    if (Tools::strlen($registrationMsg) == 0) {
        Configuration::updateValue('MRSHOP_REGISTRATION_MSG', '');
    }
    if (Tools::strlen($profileMsg) == 0) {
        Configuration::updateValue('MRSHOP_PROFILE_MSG', '');
    }

    if ((int) Configuration::get('MRSHOP_MAX_PRODUCT_SHOWN', null, null, null, 0) == 0) {
        Configuration::updateValue('MRSHOP_MAX_PRODUCT_SHOWN', 10);
    }

    $defaultOrderShowcaseProducts = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_SHOWCASE_PRODUCTS', null, null, null, ''));
    if (Tools::strlen($defaultOrderShowcaseProducts) == 0) {
        Configuration::updateValue('MRSHOP_DEFAULT_ORDER_SHOWCASE_PRODUCTS', "product.date_add.desc");
    }

    $defaultOrderNewProducts = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_NEW_PRODUCTS', null, null, null, ''));
    if (Tools::strlen($defaultOrderNewProducts) == 0) {
        Configuration::updateValue('MRSHOP_DEFAULT_ORDER_NEW_PRODUCTS', "product.date_add.desc");
    }

    $defaultOrderSpecialOffers = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS', null, null, null, ''));
    if (Tools::strlen($defaultOrderSpecialOffers) == 0) {
        Configuration::updateValue('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS', "product.date_add.desc");
    }

    if ((int) Configuration::get('MRSHOP_SPECIAL_OFFERS_LAYOUT', null, null, null, 0) == 0) {
        Configuration::updateValue('MRSHOP_SPECIAL_OFFERS_LAYOUT', 1);
    }
    if ((int) Configuration::get('MRSHOP_NEW_PRODUCTS_LAYOUT', null, null, null, 0) == 0) {
        Configuration::updateValue('MRSHOP_NEW_PRODUCTS_LAYOUT', 1);
    }
    if ((int) Configuration::get('MRSHOP_SHOWCASE_LAYOUT', null, null, null, 0) == 0) {
        Configuration::updateValue('MRSHOP_SHOWCASE_LAYOUT', 1);
    }
    if ((int) Configuration::get('MRSHOP_PRODUCTS_LAYOUT', null, null, null, 0) == 0) {
        Configuration::updateValue('MRSHOP_PRODUCTS_LAYOUT', 1);
    }

    return true;
}
