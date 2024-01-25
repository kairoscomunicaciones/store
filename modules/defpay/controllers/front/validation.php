<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2022 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

require_once(dirname(__FILE__) . '../../../models/defpayuser.php');

use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;

class defpayValidationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function getCustomerGroups()
    {
        $customer_groups = array();
        if (isset($this->context->cart->id_customer)) {
            if ($this->context->cart->id_customer == 0) {
                $customer_groups[1] = 1;
            } else {
                foreach (Customer::getGroupsStatic($this->context->cart->id_customer) as $group) {
                    $customer_groups[$group] = 1;
                }
            }
        } elseif ($this->context->customer->is_guest == 1) {
            $customer_groups[1] = 2;
        } else {
            $customer_groups[1] = 1;
        }
        if (count($customer_groups) > 0) {
            return $customer_groups;
        } else {
            return false;
        }
    }
    
    public function postProcess()
    {
        if ($this->context->cart->id_customer == 0 || $this->context->cart->id_address_delivery == 0 || $this->context->cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirectLink(__PS_BASE_URI__ . 'order.php?step=1');
        }
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'defpay') {
                $authorized = true;
                break;
            }
        }
        $defpayuser = defpayuser::getByCustomer($this->context->cart->id_customer, $this->getCustomerGroups());
        if (Context::getContext()->customer->id != 0) {
            if ($defpayuser == false) {
                $authorized = false;
            }
        }

        if (!$authorized) {
            die(Tools::displayError('This payment method is not available.'));
        }

        $customer = new Customer($this->context->cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirectLink(__PS_BASE_URI__ . 'order.php?step=1');
        }

        $cart = $this->context->cart;
        $currency = $this->context->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $mailVars = array();
        $this->module->validateOrder($cart->id, Configuration::get('DEFPAY_OS_PLACED'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
        $this->adddefpay($this->module->currentOrder, $defpayuser);
        if (Configuration::get('DEFPAY_MAIL_CONF') == true) {
            $message = Configuration::get('DEFPAY_MESSAGE', Context::getContext()->language->id);
            $order = new Order($this->module->currentOrder);
            $currency_order = new Currency($order->id_currency, Context::getContext()->currency->id);
            $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
            $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime($order->date_add)));
            $templatevars['{customer_firstname}'] = $customer->firstname;
            $templatevars['{customer_lastname}'] = $customer->lastname;
            $templatevars['{order_id}'] = $order->id;
            $templatevars['{order_reference}'] = $order->reference;
            $templatevars['{defpay_deadline}'] = Tools::displayDate($defpayuser_date);
            $templatevars['{defpay_days}'] = $days;
            $templatevars['{defpay_message}'] = $message;
            $templatevars['{order_total}'] = Tools::displayPrice($order->total_paid, $currency_order->id);
            Mail::Send(Context::getContext()->language->id, 'defpay-confirmation', Configuration::get('DEFPAY_TITLE_MAIL', Context::getContext()->language->id), $templatevars, (string)($customer->email), null, (string)Configuration::get('PS_SHOP_EMAIL', null, null, Context::getContext()->shop->id), (string)Configuration::get('PS_SHOP_NAME', null, null, Context::getContext()->shop->id), null, null, dirname(__file__) . '/mails/', false, Context::getContext()->shop->id);
        }
        Tools::redirectLink(__PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int)$this->context->cart->id . '&id_module=' . (int)$this->module->id . '&id_order=' . (int)$this->module->currentOrder);

    }

    protected function adddefpay($id_order, $defpayuser)
    {
        $order = new Order($id_order);
        $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
        $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime($order->date_add)));

        Db::getInstance()->update('orders', array('defpayday' => $days), 'id_order = ' . $id_order);
        Db::getInstance()->update('orders', array('defpayid' => $defpayuser[0]['id_defpayuser']), 'id_order = ' . $id_order);
        Db::getInstance()->update('orders', array('defpaydate' => $defpayuser_date), 'id_order = ' . $id_order);
    }
}
