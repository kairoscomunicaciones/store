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

function upgrade_module_1_2_1($module)
{
	Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gwaicnrandom` (
        	`id_gwaicnrandom` INT(10) UNSIGNED AUTO_INCREMENT NOT NULL,
            `type` varchar(2) NULL,
        	`id_object` INT(10) UNSIGNED NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `random` varchar(255) NULL,
            `random_number` varchar(255) NULL,
        	PRIMARY KEY (`id_gwaicnrandom`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;');
	return true;
}
