<?php
/**
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

if (!defined('_PS_VERSION_'))
	exit;
function upgrade_module_2_3_8($module)
{
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_opc_login_social` ( 
    `id_customer` INT(11) NOT NULL , 
    `registered_network` TINYINT(1) NOT NULL , 
    `last_login_network` TINYINT(1) NOT NULL , 
    PRIMARY KEY (`id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    $module->registerHook('actionAuthentication');
    $module->registerHook('actionCustomerGridQueryBuilderModifier');
    return true;
}