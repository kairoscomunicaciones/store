<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_.'mrshopapi/classes/CmsPage.php');

function migrateStoreMultilangConfiguration($configurationName)
{
    $sql = '
    SELECT cl.`id_lang`, cl.`value`
    FROM `'._DB_PREFIX_.'configuration_lang` cl
    LEFT JOIN `'._DB_PREFIX_.'configuration` co ON cl.`id_configuration` = co.`id_configuration`
    WHERE co.`name` = "'.pSQL($configurationName).'"
    ';

    $recordset = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    $languagesAlreadyIn = [];
    foreach ($recordset as $row) {
        if (Tools::strlen(trim($row['value'])) > 0) {
            $languagesAlreadyIn[] = (int) $row['id_lang'];
        }
    }

    $baseValue = trim(Configuration::get($configurationName));
    $values = [];
    foreach (Language::getLanguages(true) as $lang) {
        $idLang = (int) $lang['id_lang'];
        if (!in_array($idLang, $languagesAlreadyIn)) {
            $values[$idLang] = $baseValue;
        }
    }

    if (count($values) > 0) {
        Configuration::updateValue($configurationName, null);
        Configuration::updateValue($configurationName, $values);
    }
}

function upgrade_module_1_3_0($module)
{
    $installer = new MrAPPs\MrShopApi\Service\Installer($module);

    $installer->createTables();
    $installer->deleteTabs();
    $installer->createTabs();

    // Age verification
    if (!Configuration::hasKey('MRSHOP_ENABLE_AGE_VERIFICATION')) {
        Configuration::updateValue('MRSHOP_ENABLE_AGE_VERIFICATION', false);
    }
    if (!Configuration::hasKey('MRSHOP_AGE_VERIFICATION_MIN_AGE')) {
        Configuration::updateValue('MRSHOP_AGE_VERIFICATION_MIN_AGE', 18);
    }

    // Default manufacturer products order
    if (!Configuration::hasKey('MRSHOP_DEFAULT_ORDER_MANUFACTURER_PRODUCTS')) {
        Configuration::updateValue('MRSHOP_DEFAULT_ORDER_MANUFACTURER_PRODUCTS', "product.date_add.desc");
    }

    // Store MultiLang configuration
    migrateStoreMultilangConfiguration('MRSHOP_APP_DESCRIPTION');
    migrateStoreMultilangConfiguration('MRSHOP_APP_SHORT_DESCRIPTION');
    migrateStoreMultilangConfiguration('MRSHOP_APP_KEYWORDS');

    return true;
}
