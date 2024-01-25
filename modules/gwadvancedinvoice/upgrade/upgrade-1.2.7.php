<?php

/**

* This is main class of module. 

* 

* @author    Globo Software Solution JSC <contact@globosoftware.net>

* @copyright 2017 Globo ., Jsc

* @license   please read license in file license.txt

* @link	     http://www.globosoftware.net

*/



if (!defined('_PS_VERSION_'))

	exit;
function upgrade_module_1_2_7($module)
{
	Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gwadvancedinvoicetemplate` ADD COLUMN `hidden_discount` TINYINT(1) NULL DEFAULT  "0"');
	return true;
}