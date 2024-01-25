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

require_once(_PS_MODULE_DIR_.'mrshopapi/classes/AppHome.php');

function upgrade_module_1_1_0($module)
{
    try {
        if (!Configuration::hasKey('MRSHOP_ENABLE_LOGO')) {
            Configuration::updateValue('MRSHOP_ENABLE_LOGO', true);
        }

        $installer = new MrAPPs\MrShopApi\Service\Installer($module);

        $installer->createTables();
        $installer->alterTables();
        $installer->createTabs();

        // SHOWCASE
        if (0 == (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'app_home` WHERE `type` = "'.pSQL(AppHome::$TYPE_SHOWCASE_PRODUCTS).'"')) {
            $showcase = new AppHome();
            $showcase->type = AppHome::$TYPE_SHOWCASE_PRODUCTS;
            $showcase->active = Configuration::get('MRSHOP_ENABLE_SHOWCASE_PRODUCTS');
            $showcase->layout = (int) Configuration::get('MRSHOP_SHOWCASE_LAYOUT');
            $showcase->order_by = Configuration::get('MRSHOP_DEFAULT_ORDER_SHOWCASE_PRODUCTS');
            $showcase->title = 'Prodotti in evidenza';
            $showcase->position = 1;
            $showcase->save();
        }

        // SPECIAL_OFFERS
        if (0 == (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'app_home` WHERE `type` = "'.pSQL(AppHome::$TYPE_SPECIAL_OFFERS).'"')) {
            $specialOffers = new AppHome();
            $specialOffers->type = AppHome::$TYPE_SPECIAL_OFFERS;
            $specialOffers->active = Configuration::get('MRSHOP_SPECIAL_OFFERS');
            $specialOffers->layout = (int) Configuration::get('MRSHOP_SPECIAL_OFFERS_LAYOUT');
            $specialOffers->order_by = Configuration::get('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS');
            $specialOffers->title = 'Offerte speciali';
            $specialOffers->position = 2;
            $specialOffers->save();
        }

        // NEW_PRODUCTS
        if (0 == (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'app_home` WHERE `type` = "'.pSQL(AppHome::$TYPE_NEW_PRODUCTS).'"')) {
            $newProducts = new AppHome();
            $newProducts->type = AppHome::$TYPE_NEW_PRODUCTS;
            $newProducts->active = Configuration::get('MRSHOP_NEW_PRODUCTS');
            $newProducts->layout = (int) Configuration::get('MRSHOP_NEW_PRODUCTS_LAYOUT');
            $newProducts->order_by = Configuration::get('MRSHOP_DEFAULT_ORDER_NEW_PRODUCTS');
            $newProducts->title = 'Nuovi prodotti';
            $newProducts->position = 3;
            $newProducts->save();
        }
    } catch (Exception $exception) {
        return false;
    }

    return true;
}
