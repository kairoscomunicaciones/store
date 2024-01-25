<?php
/**
 *  DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by FME Modules.
 *
 *  @author    FME Modules
 *  @copyright 2022 FME Modules
 *  @license   Comerical Licence
*/

class AdminCustomFieldsSettingController extends ModuleAdminController
{
    public function init()
    {
        parent::init();
        Tools::redirectAdmin(
            Context::getContext()->link->getAdminLink('AdminModules').'&configure='.$this->module->name
        );
    }
}
