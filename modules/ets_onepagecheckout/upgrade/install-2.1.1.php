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
function upgrade_module_2_1_1($module)
{
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_opc_additionalinfo_field` ( 
    `id_ets_opc_additionalinfo_field` INT(11) NOT NULL AUTO_INCREMENT , 
    `type` VARCHAR(222) NOT NULL , 
    `sort` INT(11) NOT NULL , 
    `required` TINYINT(1) NOT NULL , 
    `enable` TINYINT(1) NOT NULL , 
    `deleted` INT(1) NOT NULL , PRIMARY KEY (`id_ets_opc_additionalinfo_field`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_opc_additionalinfo_field_lang` ( 
    `id_ets_opc_additionalinfo_field` INT(11) NOT NULL , 
    `id_lang` INT(11) NOT NULL ,
    `title` VARCHAR(222) NOT NULL , 
    `description` TEXT NOT NULL , 
    `options` TEXT NOT NULL ,
    PRIMARY KEY (`id_ets_opc_additionalinfo_field`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_opc_additionalinfo_field_value` ( 
    `id_ets_opc_additionalinfo_field_value` INT(11) NOT NULL AUTO_INCREMENT , 
    `id_ets_opc_additionalinfo_field` INT(11) NOT NULL , 
    `id_cart` INT(1) NOT NULL , 
    `value` text, 
    `file_name` VARCHAR(222) NOT NULL , PRIMARY KEY (`id_ets_opc_additionalinfo_field_value`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    $module->registerHook('displayOrderDetail');
    $module->registerHook('displayAdminOrderLeft');
    return true;
}