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
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Service\CodFeeHelper;
use Order;
use Validate;

class CashOnDeliveryManager extends PaymentManager
{
    protected $moduleInstance;

    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_PAYMENT_DELAYED'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay cash upon the delivery of your order', 'cashondeliverymanager');
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay by cash on delivery', 'cashondeliverymanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($this->moduleName == 'codfee' &&
            version_compare($this->moduleInstance->version, '3.0.0', '<')) {
            return false;
        }

        return parent::isValidPaymentMethod($cart);
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        try {
            switch ($this->moduleName) {
                case 'mrshopcashondelivery':
                    return $this->handleMrShopCashOnDelivery($customer, $cart, $note, $shop, $totalAmount);
                case 'codfee':
                    return $this->handleCodFee($customer, $cart, $note, $shop, $totalAmount);
                case 'ps_cashondelivery':
                case 'cashondelivery':
                    return $this->handleCashOnDelivery($customer, $cart, $note, $shop, $totalAmount);
                default:
                    throw new \Exception('Invalid module');
            }
        } catch (\Exception $e) {
            error_log($e);
            ResponseHandler::badRequest($this->getApiModule()->l('Payment Error', 'cashondeliverymanager'));
        }
    }

    private function handleMrShopCashOnDelivery($customer, $cart, $note, $shop, $totalAmount)
    {
        $module = $this->getApiModule();
        require_once _PS_MODULE_DIR_.'mrshopcashondelivery/classes/Fee.php';

        /** @var \Fee $fee */
        $fee = call_user_func('\Fee::getFee', $cart);

        if ($fee && !($fee instanceof \Fee)) {
            $fee = new \Fee((int) $fee['id_fees']);
        }

        $fee_amount = call_user_func('\Fee::getFeeAmount', $cart, $fee);
        if ($this->moduleInstance->_validateOrder(
            (int) ($cart->id),
            $fee->initial_status, // Order status
            $fee_amount,
            $totalAmount,
            $this->moduleInstance->displayName,
            $note,
            [],
            null,
            false,
            $customer->secure_key,
            $shop
        )) {
            $orderId = (int) ($this->moduleInstance->currentOrder);

            $order = new Order($orderId);
            if (!Validate::isLoadedObject($order)) {
                throw new \Exception('Order not created. Maybe the cart has no products?');
            }

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $text = sprintf($module->l('Your order on %s is complete.\n\nYou will make the payment directly upon delivery of your order.', 'cashondeliverymanager'), $shop->name);

            $this->setOrderIdForCustomFields($cart->id, $orderId);

            return [
                'text' => $text,
                'reference' => $order->reference,
                'amount' => $totalAmount,
            ];
        } else {
            throw new \Exception('Problems during order creation');
        }
    }

    /* ---------------------------------------------------------------- */
    /* --------------------------- codfee ----------------------------- */
    /* ---------------------------------------------------------------- */

    private function handleCodFee($customer, $cart, $note, $shop, $totalAmount)
    {
        $module = $this->getApiModule();
        $codFee = CodFeeHelper::getCodFee($customer, $cart);

        $response = false;

        $validateOrderCodPath = _PS_MODULE_DIR_.'codfee/classes/ValidateOrderCod.php';

        if (file_exists($validateOrderCodPath)) {
            require_once $validateOrderCodPath;

            $classToCall = new \ValidateOrderCod();

            $arguments = [
                (int) ($cart->id),
                $codFee['fee']['initial_status'],
                $totalAmount,
                $codFee['amount'],
                $codFee['fee']['id_codfee_configuration'],
                $this->moduleInstance->displayName,
                $note,
                null,
                null,
                false,
                $customer->secure_key
            ];

            if (version_compare(_PS_VERSION_, '1.7.7', '>=')) {
                $call = 'validateOrder177';
            } elseif (version_compare(_PS_VERSION_, '1.7.6.1', '>=')) {
                $call = 'validateOrder176';
            } elseif (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
                $call = 'validateOrder174';
            } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
                $call = 'validateOrder17';
            } else {
                $call = 'validateOrder16';
            }
        } else {
            $classToCall = $this->moduleInstance;

            $arguments = [
                (int) ($cart->id),
                $codFee['fee']['initial_status'],
                $totalAmount,
                $codFee['amount'],
                $this->moduleInstance->displayName,
                $note,
                null,
                null,
                false,
                $customer->secure_key
            ];

            if (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
                $call = 'validateOrder174';
            } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
                $call = 'validateOrder17';
            } else {
                $call = 'validateOrder16';
            }
        }

        if (is_callable([$classToCall, $call])) {
            $response = call_user_func_array([$classToCall, $call], $arguments);
        }

        if ($response) {
            $orderId = (int) $classToCall->currentOrder;

            $order = new Order($orderId);
            if (!Validate::isLoadedObject($order)) {
                ResponseHandler::badRequest($module->l('Order not created', 'cashondeliverymanager'));
            }

            $this->setOrderIdForCustomFields($cart->id, $orderId);

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $text = sprintf($module->l('Your order on %s is complete.\n\nYou will make the payment directly upon delivery of your order.', 'cashondeliverymanager'), $shop->name);

            return [
                'text' => $text,
                'reference' => $order->reference,
                'amount' => $totalAmount,
            ];
        } else {
            throw new \Exception('Problems during order creation');
        }
    }

    /* ---------------------------------------------------------------- */
    /* ----------------------- cashondelivery ------------------------- */
    /* ---------------------------------------------------------------- */

    private function handleCashOnDelivery($customer, $cart, $note, $shop, $totalAmount)
    {
        $this->cartHandler->validateCart();

        $module = $this->getApiModule();

        // Order creation
        if ($this->moduleInstance->validateOrder(
            (int) ($cart->id),
            Configuration::get('PS_OS_PREPARATION'),    // Order status
            $totalAmount,
            $this->moduleInstance->displayName,
            $note,
            [],
            null,
            false,
            $customer->secure_key
        )) {
            $orderId = (int) ($this->moduleInstance->currentOrder);
            $order = new Order($orderId);

            if (!Validate::isLoadedObject($order)) {
                ResponseHandler::badRequest($module->l('Order not created', 'cashondeliverymanager'));
            }

            $this->setOrderIdForCustomFields($cart->id, $orderId);

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $text = sprintf($module->l('Your order on %s is complete.\n\nYou will make the payment directly upon delivery of your order.', 'cashondeliverymanager'), $shop->name);

            return [
                'text' => $text,
                'reference' => $order->reference,
                'amount' => $totalAmount,
            ];
        } else {
            throw new \Exception('Problems during order creation');
        }
    }
}
