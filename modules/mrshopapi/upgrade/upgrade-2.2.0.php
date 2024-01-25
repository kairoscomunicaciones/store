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
use MrAPPs\MrShopApi\Utils\UpdateCleanerSubscriber;

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_2_0($module)
{
    $installer = new MrAPPs\MrShopApi\Service\Installer($module);
    $installer->alterTables('alter_2.2.0.sql');
    $installer->deleteTabs();
    $installer->createTabs();
    $installer->syncImages();
    $installer->installPsAccounts();
    $installer->createAnonymousCustomer();

    $path = realpath(_PS_MODULE_DIR_.'/'.$module->name);
    $sub = new UpdateCleanerSubscriber('Update 2.2.0');
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

    return true;
}
