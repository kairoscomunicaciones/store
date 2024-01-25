<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FMM Modules
*  @copyright FME Modules 2021
*  @license   Single domain
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_0($module)
{
    if (!columnExistRpm('rpm_has_categories')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_categories` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_categories')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_products` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_brands')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_brands` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_suppliers')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_suppliers` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_range')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_range` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_groupBox')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_groupBox` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_cities')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_cities` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_zips')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_zips` int(11) DEFAULT 0'
        );
    }
    if (!columnExistRpm('rpm_has_days')) {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'restrictpaymentmethods`
            ADD `rpm_has_days` int(11) DEFAULT 0'
        );
    }
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_cities` (
        `id_restrictpaymentmethods_cities` int(11) NOT NULL auto_increment,
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `name` varchar(255) default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods_cities`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_zips` (
        `id_restrictpaymentmethods_zips` int(11) NOT NULL auto_increment,
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `code` varchar(255) default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods_zips`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_days` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_day` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_day`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    $module->registerHook('actionAdminControllerSetMedia');
    return $module->registerHook('displayPaymentTop');
}

function columnExistRpm($column_name)
{
    $columns = Db::getInstance()->executeS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "'._DB_NAME_.'" AND table_name = "'._DB_PREFIX_.'restrictpaymentmethods"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }
    return false;
}