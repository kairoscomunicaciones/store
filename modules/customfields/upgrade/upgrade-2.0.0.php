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

function upgrade_module_2_0_0($module)
{
    $sql = [];
    $return = true;
    if (columnExist('dependant') && columnExist('dependant_field') && columnExist('dependant_value')) {
        $return = true;
    } else {
        $return = Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'fmm_custom_fields`
            ADD `dependant` INT(1) NOT NULL DEFAULT 0,
            ADD `dependant_field` INT(10) NOT NULL DEFAULT 0,
            ADD `dependant_value` INT(10) NOT NULL DEFAULT 0'
        );
    }
    $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_custom_fields_group` (
        `id_custom_field` int(10) NOT NULL,
        `id_group` int(10) NOT NULL,
        PRIMARY KEY (`id_custom_field`, `id_group`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

    $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_custom_fields_categories` (
        `id_custom_field` int(10) NOT NULL,
        `id_category` int(10) NOT NULL,
        PRIMARY KEY (`id_custom_field`, `id_category`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
    foreach ($sql as $query) {
        if (Db::getInstance()->execute($query) == false) {
            $return = false;
        }
    }

    return $return;
}

function columnExist($column_name)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "'._DB_NAME_.'" AND table_name = "'._DB_PREFIX_.'fmm_custom_fields"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }

    return false;
}
