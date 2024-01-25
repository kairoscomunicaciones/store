<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2021 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_shop`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_categories`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_products`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_payment`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_group`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_brands`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_suppliers`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_cities`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'restrictpaymentmethods_zips`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
