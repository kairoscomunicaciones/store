<?php
/**
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
* @author    FMM Modules
* @copyright 2022 FMM Modules All right reserved
* @license   FMM Modules
*/

$sql = array();
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_lang';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_values';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_userdata';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_shop';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_group';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_categories';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_headings';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'custom_field_headings_lang';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
