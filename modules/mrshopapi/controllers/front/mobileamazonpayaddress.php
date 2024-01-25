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
 *
 */

use MrAPPs\MrShopApi\Handler\Api\JwtHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Handler\Payment\Web\AmazonPayManager;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class mrshopapimobileamazonpayaddressModuleFrontController extends ModuleFrontController
{
    public $internal_id_address = 0;

    public $address_form;

    public $ssl = true;

    public function __construct()
    {
        parent::__construct();
        $this->ajax = false;
    }

    public function init()
    {
        parent::init();
        $this->address_form = $this->makeAddressForm();
        $this->context->smarty->assign(['address_form'=> $this->address_form->getProxy(), 'editing' => false]);
    }

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('customer/address', ['entity' => 'address', 'id' => $this->internal_id_address > 0 ? $this->internal_id_address : Tools::getValue('id_address')]);
    }

    public function postProcess()
    {
        $a = 'AmazonPayCheckoutSession';
        $amazonPayCheckoutSession = new $a(false);
        if ($amazonPayCheckoutSession->checkStatus()) {
            $coData = $amazonPayCheckoutSession->assocReturn();
        } else {
            Tools::redirect(AmazonPayManager::getErrorUrl());
        }
        $this->context->smarty->assign('editing', false);
        $this->address_form->setTemplate('module:mrshopapi/views/templates/front/address-form.tpl');
        $this->address_form->fillWith(Tools::getAllValues());
        if (Tools::isSubmit('submitAddress')) {
            if (!$this->address_form->submit()) {
                $this->errors[] = $this->module->l('Please fix the error below.', 'mobileamazonpayaddress');
            } else {
                call_user_func(
                    'AmazonPayAddress::saveAddressAmazonReference',
                    $this->address_form->getAddress(),
                    Context::getContext()->cookie->amazon_pay_checkout_session_id,
                    $this->context->customer->id,
                    $coData['shippingAddress']
                );

                $address_invoice = call_user_func(
                    'AmazonPayAddress::findByAmazonOrderReferenceIdOrNew',
                    Context::getContext()->cookie->amazon_pay_checkout_session_id.'-invoice',
                    $coData['billingAddress'],
                    $this->context->customer->id,
                    false
                );

                $address_invoice->processFromArray($coData['billingAddress']);

                try {
                    $address_invoice->save();
                } catch (\Exception $e) {
                    $fields_to_set = call_user_func('AmazonPayAddress::fetchInvalidInput', $address_invoice);
                    foreach ($fields_to_set as $field_to_set) {
                        $address_invoice->$field_to_set = isset($this->address_form->getAddress()->$field_to_set) ? $this->address_form->getAddress()->$field_to_set : '';
                    }
                    $address_invoice->save();
                }

                $fields_to_set = call_user_func('AmazonPayAddress::fetchInvalidInput', $address_invoice);
                foreach ($fields_to_set as $field_to_set) {
                    $address_invoice->$field_to_set = isset($this->address_form->getAddress()->$field_to_set) ? $this->address_form->getAddress()->$field_to_set : '';
                }
                $address_invoice->save();

                call_user_func(
                    'AmazonPayAddress::saveAddressAmazonReference',
                    $address_invoice,
                    Context::getContext()->cookie->amazon_pay_checkout_session_id.'-invoice',
                    $this->context->customer->id,
                    $coData['billingAddress']
                );

                $this->context->cart->id_address_delivery = $this->address_form->getAddress()->id;
                $this->context->cart->id_address_invoice = $address_invoice->id;
                $sql = 'UPDATE `'._DB_PREFIX_.'cart_product`
                           SET `id_address_delivery` = '.(int) $this->address_form->getAddress()->id.'
                         WHERE `id_cart` = '.(int) $this->context->cart->id.'';
                Db::getInstance()->execute($sql);

                $sql = 'UPDATE `'._DB_PREFIX_.'customization`
                           SET `id_address_delivery` = '.(int) $this->address_form->getAddress()->id.'
                         WHERE `id_cart` = '.(int) $this->context->cart->id.'';
                Db::getInstance()->execute($sql);
                $this->context->cart->save();

                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);
                Tools::redirect(AmazonPayManager::getContinueToAppUrl());
            }
        } else {
            $address_delivery = call_user_func(
                'AmazonPayAddress::findByAmazonOrderReferenceIdOrNew',
                Context::getContext()->cookie->amazon_pay_checkout_session_id,
                $coData['shippingAddress'],
                $this->context->customer->id,
                false
            );
            $address_delivery->processFromArray($coData['shippingAddress']);
            if ((int) $address_delivery->id > 0) {
                $this->address_form->loadAddressById((int) $address_delivery->id);
            }
            $this->address_form->fillWith(get_object_vars($address_delivery));
            $this->internal_id_address = $address_delivery->id;
        }
    }
}
