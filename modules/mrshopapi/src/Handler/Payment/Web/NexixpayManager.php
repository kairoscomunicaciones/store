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

namespace MrAPPs\MrShopApi\Handler\Payment\Web;

use Configuration;
use Context;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use Tools;

class NexixpayManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay securely by credit and debit card or other payment methods via Nexi.', 'nexixpaymanager');
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay by credit/debit card', 'nexixpaymanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        $config = $this->getConfiguration();
        $alias = $config['alias'];
        $macKey = $config['macKey'];

        return parent::isValidPaymentMethod($cart) &&
            Tools::strlen($alias) > 0 &&
            Tools::strlen($macKey) > 0;
    }

    private function getConfiguration()
    {
        $sandbox = (bool) Configuration::get('NEXIXPAY_TEST', null, null, null, true);
        $alias = Configuration::get('NEXIXPAY_ALIAS');
        $macKey = Configuration::get('NEXIXPAY_MAC_KEY');

        return [
            'sandbox' => $sandbox,
            'alias' => $alias,
            'macKey' => $macKey,
        ];
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = (bool) Configuration::get('NEXIXPAY_TEST', null, null, null, true);

        return $structure;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();
        $config = $this->moduleInstance->getConfiguration();

        $urlRedirect = $context->link->getModuleLink('nexixpay', 'pay', [], true);
        $urlBack = version_compare($this->getModuleInstance()->version, '5', '<') ? $config->url_back : $config->urlBack;

        switch ($this->dataHandler->getApiVersion()) {
            case 1:
                $urlReturn = $context->link->getPageLink('order-confirmation');

                $errorReturn = [
                    $context->link->getPageLink('order&step=1'),
                    $context->link->getPageLink('history', true),
                ];

                $cancelUrl = [urldecode($urlBack)];

                break;
            default:
                $urlReturn = $this->getRegEx($context->link->getPageLink('order-confirmation'));

                $errorReturn = [
                    $this->getRegEx(
                        $context->link->getModuleLink(
                            'nexixpay',
                            'ritorno',
                            [],
                            true
                        )
                        .'?\S*esito=KO'
                    ),
                ];

                $cancelUrl = [$this->getRegEx(urldecode($urlBack))];

                break;
        }

        return [
            'method' => 'GET',
            'gateway' => $urlRedirect,
            'body' => null,
            'returnUrlSuccess' => $urlReturn,
            'returnUrlFailure' => $errorReturn,
            'returnUrlCancel' => $cancelUrl,
            'amount' => $totalAmount,
        ];
    }
}
