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

function upgrade_module_2_1_0($module)
{
    $return = true;
    foreach ($module->getNewTableNames() as $old_name => $new_name) {
        if (tableExists($old_name)) {
            $return &= renameTable($old_name, $new_name);
        }
    }

    return $return;
}

function renameTable($old_name, $new_name)
{
    return (bool) Db::getInstance()->execute('ALTER TABLE '._DB_PREFIX_.pSQL($old_name).' RENAME '._DB_PREFIX_.pSQL($new_name));
}

function tableExists($table)
{
    return (bool) Db::getInstance()->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.pSQL($table).'\'');
}
