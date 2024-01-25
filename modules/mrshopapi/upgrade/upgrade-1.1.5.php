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

function upgrade_module_1_1_5($module)
{
    $installer = new MrAPPs\MrShopApi\Service\Installer($module);

    return $installer->alterTables('alter_1.1.5.sql', false);
}
