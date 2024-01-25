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

function upgrade_module_2_2_0($module)
{
    $sql = [];
    $return = true;
    $sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_headings(
        `id_custom_field_headings` int(11) NOT NULL auto_increment,
        PRIMARY KEY (`id_custom_field_headings`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    $sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_headings_lang(
        `id_custom_field_headings` int(11) NOT NULL,
        `id_lang` int(11) NOT NULL,
        `title` varchar(255) default NULL,
        PRIMARY KEY (`id_custom_field_headings`,`id_lang`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    foreach ($sql as $query) {
        if (Db::getInstance()->execute($query) == false) {
            $return = false;
        } else {
            $return = true;
        }
    }
    if (!columnExist('id_heading')) {
        Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'custom_field`
            ADD `id_heading` int(11) DEFAULT 0'
        );
    }
    $tab = new Tab();
    $tab->class_name = 'AdminCustomFieldsHeadings';
    $tab->id_parent = Tab::getIdFromClassName('AdminCustomFields');
    $tab->module = 'customfields';
    $tab->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $module->l('Custom Fields Headings');
    if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
        $tab->icon = 'content_paste';
    }
    $tab->add();

    return $return;
}

function columnExist($column_name)
{
    $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
        WHERE table_schema = "'._DB_NAME_.'" AND table_name = "'._DB_PREFIX_.'custom_field"');
    if (isset($columns) && $columns) {
        foreach ($columns as $column) {
            if ($column['COLUMN_NAME'] == $column_name) {
                return true;
            }
        }
    }

    return false;
}
