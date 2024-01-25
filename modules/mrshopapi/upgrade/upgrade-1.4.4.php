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
 * @copyright Mr. APPs 2019
 * @license Mr. APPs
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param Module $module
 * @return bool result
 * @throws PrestaShopDatabaseException
 *
 */
function upgrade_module_1_4_4($module)
{
    Configuration::updateValue('MRSHOP_FIREBASE_SERVICE_ACCOUNT', '');

    mkdir(
        _PS_CONFIG_DIR_.'modules/mrshopapi/',
        0755,
        true
    );

    return true;
}
