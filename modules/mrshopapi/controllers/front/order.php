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

use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class mrshopapiorderModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        $this->ajax = false;

        $bodyParams = $this->dataHandler->getBodyParams();
        $paymentMethod = array_key_exists('payment_method', $bodyParams)
            ? $bodyParams['payment_method']
            : Tools::getValue('payment_method');
        $extra = array_key_exists('extra', $bodyParams)
            ? $bodyParams['extra']
            : Tools::getValue('extra');

        if (empty($extra)) {
            $extra = [];
        }

        if (strpos($paymentMethod, '?') > 0) {
            $paymentMethodData = explode('?', $paymentMethod);
            $paymentMethod = $paymentMethodData[0];
            parse_str($paymentMethodData[1], $parsedParams);
            $extra = array_merge($extra, $parsedParams);
        }

        $customerId = Context::getContext()->customer->id;
        $paymentHandler = new PaymentHandler($this->dataHandler);
        $cart = $paymentHandler->getCustomerCart($customerId);
        $manager = $paymentHandler->getPaymentManager($customerId, $paymentMethod, $extra, false, true);

        $error = false;

        if (!$manager) {
            $error = true;
        } else {
            $form = $manager->getWebPaymentForm($cart);
            if (!$form) {
                $error = true;
            }
        }

        if ($error) {
            throw new \Exception('Payment module is not active yet or configured');
        }

        $this->renderInitView($form, $manager);
    }

    protected function renderInitView($form, $manager)
    {
        $path = 'modules/mrshopapi/views';
        $assetPath = ApiUtils::getFullUrl($path);
        $isHookModule = $manager->getHookModule();
        $templateName = $isHookModule ? 'module_payment_init.tpl' : 'web_payment_init.tpl';
        $dataKey = $isHookModule ? 'option' : 'form';

        $paymentModule = $manager->getModuleInstance();
        $this->php_self = 'order';
        Hook::exec('actionFrontControllerSetMedia', [], $paymentModule->id);

        $paymentOptions = Hook::exec('paymentOptions', [], $paymentModule->id, true);

        $paymentOption = $paymentOptions[$paymentModule->name][(int) $manager->getOptionIndex()];

        $data = [
            'style'        => $assetPath.'/css/front.css',
            'templatePath' => $this->getTemplateFullPath(),
            $dataKey => $form,
            'buttonId' => !empty($form['id']) ? 'pay-with-'.$form['id'] : '',
            'buttonText' => $this->module->l('Send order', 'order'),
            'moduleName' => $paymentModule->name,
            'moduleDisplayName' => $paymentOption->getCallToActionText()
        ];

        parent::initContent();
        parent::setMedia();
        $this->context->smarty->assign($data);
        $this->setTemplateRelativePath($templateName);
    }
}
