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
require_once _PS_MODULE_DIR_.'mrshopapi/vendor/autoload.php';

use MrAPPs\ModuleCleaner\Cleaner;
use MrAPPs\MrShopApi\Exceptions\HttpException;
use MrAPPs\MrShopApi\Handler\Api\AppConfigurationHandler;
use MrAPPs\MrShopApi\Service\MrShopCustomer\MrShopCustomerClient;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\UpdateCleanerSubscriber;

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_0($module)
{
    $path = realpath(_PS_MODULE_DIR_.'/'.$module->name);
    $sub = new UpdateCleanerSubscriber('Update 2.0.0');
    $sub->log('cleaning '.$path);
    if (false == file_exists($path.'/packing-list.json')) {
        $sub->log('packinglist is not present');
    } else {
        $file = Tools::file_get_contents($path.'/packing-list.json');

        if (false == $file) {
            $sub->log('Unable to read package list');

            return false;
        }

        $list = json_decode($file, true);

        if (empty($list)) {
            $sub->log('Package list is empty');
        } else {
            Cleaner::create($path, false)
                ->setSubscriber($sub)
                ->clean($list);
        }
    }
    
    // rimuovo le tab legacy dal db
    $sql = "SELECT id_tab FROM "._DB_PREFIX_."tab WHERE class_name = 'AdminMrShopApiAppHome'";
    $id = (int) Db::getInstance()->getValue($sql);
    if (false == empty($id)) {
        $sql = "DELETE FROM "._DB_PREFIX_."tab WHERE id_tab = ".(int) $id;
        $res = Db::getInstance()->execute($sql);

        if (false == empty($res)) {
            $sql =  "DELETE FROM "._DB_PREFIX_."tab_lang WHERE id_tab = ".(int) $id;
            Db::getInstance()->execute($sql);
        }
    }

    $installer = new MrAPPs\MrShopApi\Service\Installer($module);

    $installer->alterTables('alter_2.0.0.sql');

    if (ApiUtils::isAppRequested()) {
        Configuration::updateValue('MRSHOP_LAST_ALLOWED_STEP', AppConfigurationHandler::MAX_STEPS);
    }

    $logo = Configuration::get('MRSHOP_LOGO');
    if ($logo) {
        $path = explode('/', $logo);
        if (!empty($path)) {
            Configuration::updateValue('MRSHOP_LOGO', $path[count($path) - 1]);
        }
    }

    $icon = Configuration::get('MRSHOP_ICON');
    if ($icon) {
        $path = explode('/', $icon);
        if (!empty($path)) {
            Configuration::updateValue('MRSHOP_ICON', $path[count($path) - 1]);
        }
    }

    $splash = Configuration::get('MRSHOP_SPLASH');
    if ($splash) {
        $path = explode('/', $splash);
        if (!empty($path)) {
            Configuration::updateValue('MRSHOP_SPLASH', $path[count($path) - 1]);
        }
    }

    if (!Configuration::hasKey('MRSHOP_PAYPAL_APP_ENABLED')) {
        $paypalEnabled = !empty(Configuration::get('MRSHOP_PAYPAL_LIVE_BRAINTREE_ACCESS_TOKEN'));
        Configuration::updateValue('MRSHOP_PAYPAL_APP_ENABLED', (string) $paypalEnabled);
    }

    $module->unregisterHook('actionObjectLanguageAddAfter');
    $module->unregisterHook('actionObjectLanguageUpdateAfter');
    $module->unregisterHook('actionObjectLanguageDeleteAfter');
    $module->unregisterHook('actionCategoryAdd');
    $module->unregisterHook('actionCategoryUpdate');
    $module->unregisterHook('actionCategoryDelete');
    $module->unregisterHook('actionProductAdd');
    $module->unregisterHook('actionProductDelete');
    $module->unregisterHook('actionProductSave');
    $module->unregisterHook('actionProductUpdate');
    $module->unregisterHook('actionProductAttributeDelete');
    $module->unregisterHook('actionProductAttributeUpdate');
    $module->unregisterHook('actionProductCoverage');

    $client = new MrShopCustomerClient();

    try {
        $response = $client->getModulesList();
        ApiUtils::updateModulesList($response);
    } catch (HttpException $ex) {
    }

    return true;
}
