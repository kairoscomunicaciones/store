<?php
/**
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

if (!defined('_PS_VERSION_'))
    exit;

class Ets_onepagecheckoutDownloadModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        if($this->context->customer->isLogged())
        {
            if (($file_name = Tools::getValue('file_name', false)) && Validate::isFileName($file_name))
            {
                Ets_opc_additionalinfo_field_value::customerDownloadFile($file_name);
                die($this->module->l('File not found','download'));

            }
            else
                die($this->module->l('Not found!', 'download'));

        }
        else
            Tools::redirect($this->context->link->getPageLink('my-account'));
    }
}