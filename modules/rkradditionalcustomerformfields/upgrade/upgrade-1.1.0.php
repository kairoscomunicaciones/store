<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'rkradditionalcustomerformfields/classes/RkrCampos.php';

function upgrade_module_1_1_0($module)
{
    $sql = 'ALTER TABLE ' . _DB_PREFIX_ . "rkr_additional_form_fields 
        ADD COLUMN required_address tinyint(1) NOT NULL DEFAULT '0';";
    $res1 = (bool) Db::getInstance()->execute($sql);

    $module->registerHook('actionFrontControllerSetMedia');
    Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS', 0);
    Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT', 0);
    Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS_ALIAS', 'Address alias');
    Configuration::deleteByName('RKR_ADDITIONAL_PUT_DEFAULT');

    $sql = 'UPDATE ' . _DB_PREFIX_ . "rkr_additional_form_fields SET required_address = 1 
        where name IN ('address1', 'id_country', 'city')";
    $res2 = (bool) Db::getInstance()->query($sql);

    $campo = new RkrCampos();
    $campo->name = 'id_state';
    $campo->label = $module->transMultiLangField('State');
    $campo->type = 'select';
    $campo->required = false;
    $campo->required_address = false;
    $campo->maxLength = null;
    $campo->constraint = 'isUnsignedId';
    $campo->active = false;
    $campo->position = 11;
    $res3 = $campo->save(true);

    return $res1 && $res2 && $res3;
}
