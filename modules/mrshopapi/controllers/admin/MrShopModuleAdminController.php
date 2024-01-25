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
class MrShopModuleAdminController extends ModuleAdminController
{
    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return html_entity_decode(parent::l($string, $class, $addslashes, false));
    }
}
