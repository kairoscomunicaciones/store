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

use MrAPPs\MrShopApi\Handler\Api\JwtHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;

class mrshopapimobileigfspaymentModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        $jwt = $this->requestHandler->getJwtHandler();
        $customerId = $jwt->getCustomerId();

        $this->setupContext();

        $this->ajax = false;
        $this->disableColumns();

        if (!$this->isModuleActive()) {
            ResponseHandler::badRequest($this->module->l('Module not installed', 'mobileigfspayment'));
        }

        $paymentHandler = new PaymentHandler($this->dataHandler);
        $cart = $paymentHandler->getCustomerCart($customerId);

        Context::getContext()->cart = $cart;

        $igfsModule = ModuleCore::getInstanceByName('mrshopigfspayment');
        $output = $igfsModule->initPayment(true, $cart);

        if (!$output['success']) {
            $this->renderView(['message' => $output['error']], 'display_message.tpl');
        } else {
            $this->renderView(['url' => $output['redirect_url']], 'redirect_to.tpl');
        }
    }

    private function isModuleActive()
    {
        return Module::isEnabled('mrshopigfspayment');
    }

    public function renderView($smartyParams, $relativeTplPath)
    {
        $this->context->smarty->assign(
            $smartyParams
        );

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->setTemplate('module:mrshopapi/views/templates/front/'.$relativeTplPath);
        } else {
            $this->setTemplate($relativeTplPath);
        }
    }
}
