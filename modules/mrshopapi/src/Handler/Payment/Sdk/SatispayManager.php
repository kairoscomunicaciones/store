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

namespace  MrAPPs\MrShopApi\Handler\Payment\Sdk;

use Cart;
use Configuration;
use Currency;
use Customer;
use Exception;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Order;
use Tools;
use Validate;

class SatispayManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_SDK'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay with Satispay', 'satispaymanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Satispay', 'satispaymanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($this->dataHandler->getApiVersion() < 3) {
            return false;
        }

        return parent::isValidPaymentMethod($cart) && $this->getConfiguration() != null;
    }

    public function getConfiguration()
    {
        if (!$this->moduleInstance) {
            return null;
        }

        if (version_compare($this->moduleInstance->version, '1.5.0', '<')) {
            return [
                'sandbox' => Configuration::get('SATISPAY_STAGING', null, null, null, true),
                'security_bearer' => trim(Configuration::get('SATISPAY_SECURITY_BEARER')),
            ];
        } else {
            return [
                'sandbox' => Configuration::get('SATISPAY_SANDBOX', false),
            ];
        }
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = (bool) $this->getConfiguration()['sandbox'];

        return $structure;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $config = $this->getConfiguration();

        $currency = new Currency($cart->id_currency);

        if (!$config) {
            throw new Exception('Payment method not found or inactive');
        }

        $phoneNumber = array_key_exists(
            'phone_number',
            $this->extraParameters
        ) ? trim($this->extraParameters['phone_number']) : '';
        if (Tools::strlen($phoneNumber) == 0) {
            throw new Exception('Phone number is required.');
        }

        $chargeId = null;

        $amount = (int) ($totalAmount * 100);
        $metadata = [
            'cart_id' => $cart->id,
            'phone_number' => $phoneNumber,
            'note' => Tools::substr(trim($note), 0, 500),
        ];

        if (version_compare($this->moduleInstance->version, '1.5.0', '<')) {
            try {
                $user = call_user_func('\SatispayOnline\User::create', [
                    'phone_number' => $phoneNumber,
                ]);
            } catch (Exception $e) {
                error_log($e);
                switch ($e->getCode()) {
                    case 404:
                        throw new Exception('This User is not subscribed to Satispay service.');
                    default:
                        throw $e;
                }
            }

            $module = $this->getApiModule();

            $callbackUrl = ApiUtils::getFullDomain().'client_mrshop/cart/validate_satispay?charge_id={uuid}';
            $charge = call_user_func('\SatispayOnline\Charge::create', [
                'user_id' => $user->id,
                'description' => $module->l('Payment', 'statispaymanager').' #'.$cart->id,
                'currency' => $currency->iso_code,
                'amount' => $amount,
                'callback_url' => $callbackUrl,
                'metadata' => $metadata,
            ]);
            $chargeId = $charge->id;
        } else {
            $callbackUrl = ApiUtils::getFullDomain().'client_mrshop/cart/validate_satispay?payment_id={uuid}';
            $payment = call_user_func('\SatispayGBusiness\Payment::create', [
                'flow' => 'MATCH_CODE',
                'amount_unit' => $amount,
                'currency' => $currency->iso_code,
                'callback_url' => $callbackUrl,
                'metadata' => $metadata,
            ]);

            $chargeId = $payment->id;
        }

        return [
            'gateway' => $chargeId,
            'amount' => $amount,
            'method' => null,
        ];
    }

    public function handlePostPayment($params)
    {
        $module = $this->getApiModule();
        $chargeId = $params['transaction_id'];
        $cartId = null;

        if (version_compare($this->moduleInstance->version, '1.5.0', '<')) {
            $charge = call_user_func('\SatispayOnline\Charge::get', $chargeId);
            if ($charge->status === 'SUCCESS') {
                $cartId = $charge->metadata->cart_id;
            }
        } else {
            $payment = call_user_func('\SatispayGBusiness\Payment::get', $chargeId);

            if ($payment->status == 'ACCEPTED') {
                $cartId = $payment->metadata->cart_id;
            }
        }

        if ($cartId === null) {
            $message = $module->l('Payment Error', 'statispaymanager');
            ResponseHandler::badRequest($message);
        }

        for ($i = 0; $i < 6; ++$i) {
            $orderId = Order::getOrderByCartId($cartId);
            $order = new Order($orderId);

            if (!empty($order->id)) {
                ResponseHandler::success([
                    'reference' => (Validate::isLoadedObject($order)) ? $order->reference : '',
                    'text' => $module->l('Payment complete', 'statispaymanager'),
                ]);
            } else {
                sleep(2);
            }
        }

        ResponseHandler::notFound($module->l('Order not found', 'statispaymanager'));
    }

    public function handleCallback()
    {
        $module = $this->getApiModule();

        try {
            $cartId = null;
            $note = null;
            $amount = null;
            $chargeId = null;

            if (version_compare($this->moduleInstance->version, '1.5.0', '<')) {
                $bodyParams = $this->dataHandler->getBodyParams();
                $chargeId = array_key_exists('charge_id', $bodyParams) ? $bodyParams['charge_id'] : Tools::getValue('charge_id');

                $charge = call_user_func('\SatispayOnline\Charge::get', $chargeId);
                if ($charge->status === 'SUCCESS') {
                    $cartId = isset($charge->metadata->cart_id) ? (int) $charge->metadata->cart_id : 0;
                    $note = isset($charge->metadata->note) ? $charge->metadata->note : '';
                    $amount = $charge->amount / 100;
                } else {
                    $message = sprintf(
                        '%s (%s): %s',
                        $module->l('Payment Error', 'statispaymanager'),
                        $charge->status,
                        $charge->status_details
                    );
                    error_log($message);
                    ResponseHandler::badRequest($message);
                }
            } else {
                $bodyParams = $this->dataHandler->getBodyParams();
                $chargeId = array_key_exists('payment_id', $bodyParams) ? $bodyParams['payment_id'] : Tools::getValue('payment_id');
                $payment = call_user_func('\SatispayGBusiness\Payment::get', $chargeId);
                if ($payment->status == 'ACCEPTED') {
                    $cartId = isset($payment->metadata->cart_id) ? (int) $payment->metadata->cart_id : 0;
                    $note = isset($payment->metadata->note) ? $payment->metadata->note : '';
                    $amount = $payment->amount_unit / 100;
                } else {
                    $message = $module->l('Payment Error', 'statispaymanager');
                    error_log('[SATISPAY] '.json_encode((array) $payment));
                    ResponseHandler::badRequest($message);
                }
            }

            $cart = new Cart((int) $cartId);
            if (!Validate::isLoadedObject($cart)) {
                ResponseHandler::notFound($module->l('Cart not found', 'statispaymanager'));
            }

            $customer = new Customer($cart->id_customer);
            if (!Validate::isLoadedObject($customer)) {
                ResponseHandler::notFound($module->l('Customer not found', 'statispaymanager'));
            }

            if ($cart->secure_key != $customer->secure_key) {
                $cart->secure_key = $customer->secure_key;
                $cart->save();
            }

            $currency = new Currency($cart->id_currency);

            $this->moduleInstance->validateOrder(
                $cart->id,
                2,
                $amount,
                $this->moduleInstance->displayName,
                $note,
                [
                    'transaction_id' => $chargeId,
                ],
                $currency->id,
                null,
                $customer->secure_key
            );

            $order = new Order(Order::getOrderByCartId($cartId));
            if (empty($order->id)) {
                ResponseHandler::notFound($module->l('Order not found', 'statispaymanager'));
            } else {
                $this->cartHandler->generateNewCartFromCustomer($cart->id_customer);
                ResponseHandler::success();
            }
        } catch (Exception $e) {
            error_log($e);
            ResponseHandler::badRequest($this->getApiModule()->l('Order not created', 'satispaymanager'));
        }
    }
}
