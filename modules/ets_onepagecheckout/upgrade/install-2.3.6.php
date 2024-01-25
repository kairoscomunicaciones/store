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
function upgrade_module_2_3_6($module)
{
    $ETS_OPC_ADDRESS_DISPLAY_FIELD = Configuration::get('ETS_OPC_ADDRESS_DISPLAY_FIELD');
    if(Tools::strpos($ETS_OPC_ADDRESS_DISPLAY_FIELD,'use_address')===false)
        $ETS_OPC_ADDRESS_DISPLAY_FIELD = 'use_address,'.$ETS_OPC_ADDRESS_DISPLAY_FIELD; 
    if(Tools::strpos($ETS_OPC_ADDRESS_DISPLAY_FIELD,'use_address_invoice')===false)
        $ETS_OPC_ADDRESS_DISPLAY_FIELD .=',use_address_invoice';
    Configuration::updateValue('ETS_OPC_ADDRESS_DISPLAY_FIELD',$ETS_OPC_ADDRESS_DISPLAY_FIELD);
    Configuration::updateGlobalValue('ETS_OPC_PAGE_ENABLED_SOCIAL','register_page,login_page,checkout_page');
    Configuration::updateGlobalValue('ETS_OPC_USE_NAME_ACCOUNT',1);
    $module->registerHook('displayCustomerLoginFormAfter');
    $module->registerHook('displayCustomerAccountForm');
    return true;
}