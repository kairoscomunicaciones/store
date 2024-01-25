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

use Context;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;

class FlowPayManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay with Flow - Webpay.', 'flowpaymanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Webpay', 'flowpaymanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        return parent::isValidPaymentMethod($cart);
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        return parent::getStructure($customerId, $cartId);
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();

        $urlRedirect = $context->link->getModuleLink('flowpaymentwp', 'create', [], true);

        $urlReturn = $this->getRegEx($context->link->getPageLink('order-confirmation'));

        $errorReturn = [
            /* $this->getRegEx(
                $context->link->getModuleLink(
                    'flowpaymentwp',
                    'paymentfailure',
                    array(),
                    true
                )
            ),
           $this->getRegEx(
                $context->link->getModuleLink(
                    'flowpaymentwp',
                    'error',
                    array(),
                    true
                )
            )*/
        ];

        $cancelUrl = [
            $this->getRegEx($context->link->getPageLink('order')),
        ];

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
