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

class mrshopapimobilepayonwebpaymentModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        $jwt = $this->requestHandler->getJwtHandler();
        $customerId = $jwt->getCustomerId();

        $this->setupContext();

        $paymentHandler = new PaymentHandler($this->dataHandler);
        $cart = $paymentHandler->getCustomerCart($customerId);

        Context::getContext()->cart = $cart;

        $this->ajax = false;
        $this->disableColumns();

        $invoice = new Address((int) ($cart->id_address_invoice));
        $customer = new Customer((int) ($cart->id_customer));

        $payonweb_url = 'https://ssl.moneynet.it/newpow/pay.asp';
        $mid = Configuration::get('PAYONWEB_MID');
        $amount = number_format($cart->getOrderTotal(true, 3), 2, ',', '');
        $cart_order_id = $cart->id;
        $codice_acquisto = Tools::substr(hash('sha1', microtime(true)), 0, 14).$cart_order_id;

        $firstname = $invoice->firstname;
        $lastname = $invoice->lastname;
        $address = $invoice->address1;
        $address2 = $invoice->address2;
        $phone = $invoice->phone;
        $city = $invoice->city;
        $state = (Validate::isLoadedObject($invoice) and $invoice->id_state) ? new State((int) ($invoice->id_state)) : false;
        $state_name = $state ? $state->name : 'XX';
        $email = $customer->email;
        $text = $this->module->l('You will be redirected to the PayOnWeb page to complete the payment.', 'payonweb');

        $this->context->smarty->assign([
            'payonweb_url' => $payonweb_url,
            'mid' => $mid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'amount' => $amount,
            'address' => $address,
            'address2' => $address2,
            'city' => $city,
            'state_name' => $state_name,
            'phone' => $phone,
            'email' => $email,
            'codice_acquisto' => $codice_acquisto,
            'text' => $text,
        ]);

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->setTemplate('module:mrshopapi/views/templates/front/payonweb_confirm_payment.tpl');
        } else {
            $this->setTemplate('payonweb_confirm_payment.tpl');
        }

        parent::initContent();
    }
}
