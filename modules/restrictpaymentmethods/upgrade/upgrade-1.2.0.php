<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FMM Modules
*  @copyright FME Modules 2020
*  @license   Single domain
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_0($module)
{
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_group` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_group` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_group`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_brands` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_brand` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_brand`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_suppliers` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_supplier` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_supplier`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    $module->registerHook('displayProductButtons');
    $module->registerHook('displayHeader');
    return $module->registerHook('actionAdminControllerSetMedia');
}
