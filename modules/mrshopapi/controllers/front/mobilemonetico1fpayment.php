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
require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php';

use MrAPPs\MrShopApi\Handler\Payment\Web\MoneticoManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class mrshopapimobilemonetico1fpaymentModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    private $moduleInstance;

    public function initContent()
    {
        $this->moduleInstance = Module::getInstanceByName('monetico1f');

        if (false == $this->moduleInstance->active) {
            throw new \Exception('Monetico module is not active yet or configured');
        }

        $this->ajax = false;

        $this->renderInitView();
    }

    protected function renderInitView()
    {
        $path = 'modules/mrshopapi/views';
        $assetPath = ApiUtils::getFullUrl($path);

        $data = [
            'style'        => $assetPath.'/css/front.css',
            'templatePath' => $this->getTemplateFullPath(),
            'monetico_form' => $this->moduleInstance->Payment_Monetico([]),
            'error_return_url' => MoneticoManager::getMonetico1fRedirectUrl()
        ];

        $this->context->smarty->assign($data);
        $this->setTemplateRelativePath('monetico1f_init.tpl');
    }
}
