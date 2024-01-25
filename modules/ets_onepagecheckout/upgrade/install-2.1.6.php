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
function upgrade_module_2_1_6()
{
    $address_fields = Configuration::get('ETS_OPC_ADDRESS_DISPLAY_FIELD');
    $address_required_fields = Configuration::get('ETS_OPC_ADDRESS_DISPLAY_FIELD_REQUIRED');
    if($address_fields)
        $address_fields = explode(',',$address_fields);
    else
        $address_fields = array();
    if($address_required_fields)
        $address_required_fields = explode(',',$address_required_fields);
    else
        $address_required_fields = array();
    $required_fields = array('firstname','lastname','country','state','post_code','city','address');
    foreach($required_fields as $field)
    {
        if(!in_array($field,$address_fields))
            $address_fields[] = $field;
        if(!in_array($field,$address_required_fields))
            $address_required_fields[] = $field;
    }
    Configuration::updateGlobalValue('ETS_OPC_ADDRESS_DISPLAY_FIELD',implode(',',$address_fields));
    Configuration::updateGlobalValue('ETS_OPC_ADDRESS_DISPLAY_FIELD_REQUIRED',implode(',',$address_required_fields));
    return true;
}