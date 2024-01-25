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

class PayonwebManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay with credit card by Payonweb', 'payonwebmanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with PayonWeb', 'payonwebmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        return parent::isValidPaymentMethod($cart) && Tools::strlen(trim(Configuration::get('PAYONWEB_MID'))) > 0;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();
        switch ($this->dataHandler->getApiVersion()) {
            case 1:
                $urlReturn = $context->link->getModuleLink('payonweb', 'validation');

                break;
            default:
                $urlReturn = $this->getRegEx($context->link->getModuleLink('payonweb', 'validation'));

                break;
        }

        $gateway = $context->link->getModuleLink(
            'mrshopapi',
            'mobilepayonwebpayment',
            ['auth_token' => $this->dataHandler->getToken(true)]
        );

        return [
            'method' => 'GET',
            'gateway' => $gateway,
            'body' => null,
            'returnUrlSuccess' => $urlReturn,
            'returnUrlFailure' => $urlReturn,
            'returnUrlCancel' => '',
            'amount' => $totalAmount,
        ];
    }
}
