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

class MrShopIGFSPaymentManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay securely by credit and debit card or alternative payment methods through IGFS.', 'mrshopigfspaymentmanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with credit/debit card', 'mrshopigfspaymentmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        $mrShopIGFS_SURLS = Configuration::get('MRSHOP_IGFS_PAYMENT_SERVER_URLS', null);
        $mrShopIGFS_TID = Configuration::get('MRSHOP_IGFS_PAYMENT_TERMINAL_ID', null);
        $mrShopIGFS_KSIG = Configuration::get('MRSHOP_IGFS_PAYMENT_KSIG', null);

        return parent::isValidPaymentMethod($cart) &&
            Tools::strlen($mrShopIGFS_SURLS) > 0 &&
            Tools::strlen($mrShopIGFS_TID) > 0 &&
            Tools::strlen($mrShopIGFS_KSIG) > 0;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();

        switch ($this->dataHandler->getApiVersion()) {
            case 1:
                $urlReturn = $context->link->getPageLink('order-confirmation');
                $errorReturn = [
                    $context->link->getModuleLink('mrshopigfspayment', 'error'),
                    $context->link->getPageLink('order'),
                ];
                $cancelUrl = [
                    $context->link->getPageLink('order'),
                ];

                break;
            default:
                $urlReturn = $this->getRegEx($context->link->getPageLink('order-confirmation'));
                $errorReturn = [$this->getRegEx($context->link->getModuleLink('mrshopigfspayment', 'error'))];
                $cancelUrl = [$this->getRegEx($context->link->getPageLink('order'))];

                break;
        }

        $gateway = $context->link->getModuleLink(
            'mrshopapi',
            'mobileigfspayment',
            [
                'auth_token' => $this->dataHandler->getToken(true),
                'amount' => $totalAmount,
            ]
        );

        return [
            'method' => 'GET',
            'gateway' => $gateway,
            'body' => null,
            'returnUrlSuccess' => $urlReturn,
            'returnUrlFailure' => $errorReturn,
            'returnUrlCancel' => $cancelUrl,
            'amount' => $totalAmount,
        ];
    }
}
