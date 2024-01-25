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

class ConstrivManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];
        $module = $this->getApiModule();
        $this->description = $module->l('Pay securely by credit card through Consorzio Triveneto.', 'constrivmanager');
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Consorzio Triveneto - Pay by credit card', 'constrivmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        $liveMode = (bool) Configuration::get('CONSTRIV_LIVE_MODE');

        return parent::isValidPaymentMethod($cart) &&
            Tools::strlen(Configuration::get('CONSTRIV_ACCOUNT_ID')) > 0 &&
            Tools::strlen(Configuration::get('CONSTRIV_ACCOUNT_PASSWORD')) > 0 &&
            $liveMode ? Tools::strlen(Configuration::get('CONSTRIV_PRODUCTION_SERVER')) > 0 : Tools::strlen(Configuration::get('CONSTRIV_TEST_SERVER')) > 0;
    }

    public function getConfiguration()
    {
        if (!$this->moduleInstance) {
            return null;
        }

        return [
            'live_mode'         => Configuration::get('CONSTRIV_LIVE_MODE'),
            'account_id'        => Configuration::get('CONSTRIV_ACCOUNT_ID'),
            'account_password'  => Configuration::get('CONSTRIV_ACCOUNT_PASSWORD'),
            'order_track'       => Configuration::get('CONSTRIV_ORDER_TRACK'),
            'tipo_trans'        => Configuration::get('CONSTRIV_TIPO_TRANS'),
            'confirm_page'      => Configuration::get('CONSTRIV_CONFIRM_PAGE'),
            'fee'               => Configuration::get('CONSTRIV_FEE'),
            'fee_type'          => Configuration::get('CONSTRIV_FEE_TYPE'),
            'test_server'       => Configuration::get('CONSTRIV_TEST_SERVER'),
            'production_server' => Configuration::get('CONSTRIV_PRODUCTION_SERVER'),
        ];
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }
        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = (int) $this->getConfiguration()['live_mode'] === 0;

        return $structure;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();
        $urlRedirect = $context->link->getModuleLink('constriv', 'redirect', [], true);
        $urlSuccess = $this->getRegEx($context->link->getPageLink('order-confirmation'));
        $urlFailure = [$this->getRegEx($context->link->getModuleLink('constriv', 'error'))];
        $urlCancel = [$this->getRegEx($context->link->getPageLink('order'))];

        return [
            'method'           => 'GET',
            'gateway'          => $urlRedirect,
            'body'             => null,
            'returnUrlSuccess' => $urlSuccess,
            'returnUrlFailure' => $urlFailure,
            'returnUrlCancel'  => $urlCancel,
            'amount'           => $totalAmount,
        ];
    }
}
