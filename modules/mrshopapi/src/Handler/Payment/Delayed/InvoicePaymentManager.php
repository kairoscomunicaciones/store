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

namespace MrAPPs\MrShopApi\Handler\Payment\Delayed;

use Configuration;
use Db;
use DbQuery;
use Module;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use Order;
use Validate;

class InvoicePaymentManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_PAYMENT_DELAYED'];

        $module = $this->getApiModule();
        $this->description = '';
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay by Invoice Payment', 'invoicepaymentmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($cart === null) {
            return true;
        }

        if (!$cart) {
            return false;
        }

        // Copied from: invoicepayment.php --> getUserPermissions (private function)
        $query = new DbQuery();
        $query->select('COUNT(*)');
        $query->from('invoice_payment_permissions', 'ipp');
        $query->leftJoin('customer_group', 'cg', 'cg.id_group = ipp.id_group');
        $query->innerJoin('address', 'adr', 'adr.id_customer = cg.id_customer AND adr.id_country=ipp.id_country');
        $query->where('cg.id_customer = '.(int) $cart->id_customer);
        $query->where('ipp.id_shop = '.(int) $cart->id_shop);
        $query->where('adr.id_address = '.(int) $cart->id_address_invoice);

        if (!parent::isValidPaymentMethod($cart)
            || !Validate::isLoadedObject($cart)
            || !$this->moduleInstance->checkCart($cart)
            || !(int) Db::getInstance()->getValue($query)
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $module = $this->getApiModule();

        if ($cart->id_customer == 0
            || $cart->id_address_delivery == 0
            || $cart->id_address_invoice == 0
            || !$this->moduleInstance->active) {
            ResponseHandler::badRequest($this->getApiModule()->l('Order not created', 'invoicepaymentmanager'));
        }

        // Check that this payment option is still available in case the customer
        // changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $m) {
            if ($m['name'] == 'invoicepayment') {
                $authorized = true;

                break;
            }
        }

        if (!$authorized) {
            ResponseHandler::badRequest($this->getApiModule()->l('This payment method is not available.', 'invoicepaymentmanager'));
        }

        if ($this->moduleInstance->validateOrder(
            (int) $cart->id,
            (Configuration::get('PS_DEFAULT_STATUS_ORDER_IP')
                ? (int) Configuration::get('PS_DEFAULT_STATUS_ORDER_IP')
                : (int) Configuration::get('PS_OS_PREPARATION')),
            $totalAmount,
            $this->moduleInstance->displayName,
            $note,
            [],
            (int) $currency->id,
            false,
            $customer->secure_key
        )) {
            $order = new Order((int) Order::getIdByCartId((int) $cart->id));

            if (!$order || !Validate::isLoadedObject($order)) {
                ResponseHandler::badRequest($this->getApiModule()->l('Order not created', 'invoicepaymentmanager'));
            }

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $paymentInfo = Configuration::get('INVOICE_PAYMENT_INFO', $order->id_lang);

            $text = sprintf($module->l('Your order on %s is complete.\n\nYou will make the payment directly upon delivery of your order.', 'cashondeliverymanager'), $shop->name);

            $text = sprintf(
                "%s\n\n%s: %s",
                $text,
                $module->l('Payment detail', 'invoicepaymentmanager'),
                $paymentInfo
            );

            $this->setOrderIdForCustomFields($cart->id, $order->id);

            return [
                'text' => $text,
                'reference' => $order->reference,
                'amount' => $totalAmount,
            ];
        } else {
            ResponseHandler::badRequest($this->getApiModule()->l('Order not created', 'invoicepaymentmanager'));
        }
    }
}
