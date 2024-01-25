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

function upgrade_module_2_0_2($module)
{
    $sql = [];
    $return = true;
    if (columnExist('default_value')) {
        $return = true;
    } else {
        $return = Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'fmm_custom_fields_lang`
            ADD `default_value` varchar(255) default NULL'
        );
    }

    if (!columnExistNot('default_value')) {
        $return = true;
    } else {
        $return = Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'fmm_custom_fields`
            DROP `default_value`'
        );
    }

    return $return;
}

function columnExist($column_name)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "'._DB_NAME_.'" AND table_name = "'._DB_PREFIX_.'fmm_custom_fields_lang"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }

    return false;
}

function columnExistNot($column_name)
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
