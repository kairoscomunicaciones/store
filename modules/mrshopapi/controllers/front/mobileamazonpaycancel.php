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
require_once(_PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php');

class mrshopapimobileamazonpaycancelModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        $data = [
            'backMessage' => $this->module->l('Back to the app', 'amazonpaymanager')
        ];

        $this->context->smarty->assign($data);
        $this->setTemplateRelativePath('amazonpay_back.tpl');
    }
}
