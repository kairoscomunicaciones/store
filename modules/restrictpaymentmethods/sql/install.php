<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2019 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods` (
        `id_restrictpaymentmethods` int(11) NOT NULL auto_increment,
        `title` varchar(255) default NULL,
        `active` tinyint(1) default \'0\',
        `rpm_has_categories` tinyint(1) default \'0\',
        `rpm_has_products` tinyint(1) default \'0\',
        `rpm_has_brands` tinyint(1) default \'0\',
        `rpm_has_suppliers` tinyint(1) default \'0\',
        `rpm_has_range` tinyint(1) default \'0\',
        `rpm_has_groupBox` tinyint(1) default \'0\',
        `rpm_has_cities` tinyint(1) default \'0\',
        `rpm_has_zips` tinyint(1) default \'0\',
        `rpm_has_days` tinyint(1) default \'0\',
        `range_min` int(11) default \'0\',
        `range_max` int(11) default \'0\',
        `range_tax` tinyint(1) default \'0\',
        `range_calc` tinyint(1) default \'0\',
        `start_date` datetime default NULL,
        `expiry_date` datetime default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_shop` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_shop` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_shop`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_categories` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_category` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_category`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_products` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_product` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_product`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_payment` (
        `id_restrictpaymentmethods_payment` int(11) NOT NULL auto_increment,
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `name` varchar(255) default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods_payment`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_group` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_group` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_group`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_brands` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_brand` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_brand`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_suppliers` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_supplier` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_supplier`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_cities` (
        `id_restrictpaymentmethods_cities` int(11) NOT NULL auto_increment,
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `name` varchar(255) default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods_cities`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_zips` (
        `id_restrictpaymentmethods_zips` int(11) NOT NULL auto_increment,
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `code` varchar(255) default NULL,
        PRIMARY KEY (`id_restrictpaymentmethods_zips`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_days` (
        `id_restrictpaymentmethods` int(10) NOT NULL,
        `id_day` int(10) NOT NULL,
        PRIMARY KEY (`id_restrictpaymentmethods`, `id_day`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
