<?php
/**
* DISCLAIMER.
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FMM Modules
*  @copyright FME Modules 2022
*  @license   Single domain
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_2_4($module)
{
    Configuration::updateGlobalValue('FMM_CUSTOMFIELDS_SECURE_KEY', Tools::passwdGen(32, 'RANDOM'));
    return true;
}
