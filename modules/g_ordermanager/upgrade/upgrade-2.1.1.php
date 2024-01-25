<?php
/**
* This is main class of module. 
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_1_1($module)
{
    $module;
    $g_ordermanagerObj = Module::getInstanceByName('g_ordermanager');
    $g_ordermanagerObj->registerHook('actionAdminControllerSetMedia');
    return true;
}