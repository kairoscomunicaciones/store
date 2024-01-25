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

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_3_1($module)
{
    $installer = new MrAPPs\MrShopApi\Service\Installer($module);
    $installer->alterTables('alter_create_2.3.1.sql');

    return true;
}
