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

function upgrade_module_1_4_11($module)
{
    $sql = "show tables like '"._DB_PREFIX_."_mrshop_refresh_token'";
    $rows = Db::getInstance()->executeS($sql);

    $installer = new MrAPPs\MrShopApi\Service\Installer($module);
    
    if (count($rows) > 0) {
        $installer->alterTables('alter_1.4.11.sql', false);
    } else {
        $installer->alterTables('alter_create_1.4.11.sql', false);
    }
   
    return true;
}
