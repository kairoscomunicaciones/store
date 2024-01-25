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

namespace MrAPPs\MrShopApi\Handler\Payment\Sdk;

use Address;
use Configuration;
use Country;
use Currency;
use Customer;
use Db;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use PrestaShopException;
use State;

class StripeManager extends PaymentManager
{
    const API_VERSION = '2020-08-27';

    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_SDK'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay with Stripe', 'stripemanager');
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Stripe', 'stripemanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($this->dataHandler->getApiVersion() < 4
        || !class_exists('\Stripe\Stripe')) {
            return false;
        }

        return parent::isValidPaymentMethod($cart) && $this->getConfiguration() != null;
    }

    public function getConfiguration()
    {
        $moduleInstance = $this->getModuleInstance();

        if (!$moduleInstance) {
            return null;
        }

        if (Configuration::hasKey('_PS_STRIPE_mode')) {
            $stripeModeKey = '_PS_STRIPE_mode';
            $stripeTestPublishable = '_PS_STRIPE_test_publishable';
            $stripePublishable = '_PS_STRIPE_publishable';

            $stripeTestKey = '_PS_STRIPE_test_key';
            $stripeKey = '_PS_STRIPE_key';
        } else {
            $stripeModeKey = 'STRIPE_MODE';
            $stripeTestPublishable = 'STRIPE_TEST_PUBLISHABLE';
            $stripePublishable = 'STRIPE_PUBLISHABLE';

            $stripeTestKey = 'STRIPE_TEST_KEY';
            $stripeKey = 'STRIPE_KEY';
        }

        if (is_callable([\Stripe_official::class, 'getShopGroupIdContext'])) {
            $shopGroupId =   \Stripe_official::getShopGroupIdContext();
            $shopId = \Stripe_official::getShopIdContext();
        } else {
            $shopGroupId = null;
            $shopId = null;
        }

        $testMode = Configuration::get($stripeModeKey, null, $shopGroupId, $shopId, true);
        $publishable = trim(Configuration::get($testMode ? $stripeTestPublishable : $stripePublishable, null, $shopGroupId, $shopId));
        $secret = trim(Configuration::get($testMode ? $stripeTestKey : $stripeKey, null, $shopGroupId, $shopId));

        if ((!$publishable || empty($publishable)) || (!$secret || empty($secret))) {
            return null;
        }

        return [
            'sandbox' => (bool) $testMode,
            'publishable' => $publishable,
            'secret' => $secret,
        ];
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = $this->getConfiguration()['sandbox'];

        if ($this->dataHandler->getApiVersion() < 13) {
            return $structure;
        }

        if ($cartId != null) {
            $cart = new \Cart((int) $cartId);
            $currency = new Currency($cart->id_currency);
            $address_invoice = new Address($cart->id_address_invoice);
            $country = Country::getIsoById($address_invoice->id_country);
        } else {
            $currency = null;
            $country = null;
        }

        $structures = [];
        foreach (\Stripe_official::$paymentMethods as $name => $paymentMethod) {
            // Check if the payment method is enabled
            if ($paymentMethod['enable'] !== true && Configuration::get($paymentMethod['enable']) != 'on') {
                continue;
            }

            // Check for country support
            if ($cartId != null && isset($paymentMethod['countries']) && !in_array($country, $paymentMethod['countries'])) {
                continue;
            }

            // Check for currency support
            $currency_iso_code = \Tools::strtolower($currency->iso_code);
            if ($cartId != null && isset($paymentMethod['currencies']) && !in_array($currency_iso_code, $paymentMethod['currencies'])) {
                continue;
            }

            $updatedStructure = array_merge($structure, []);

            $updatedStructure['display_name'] = $paymentMethod['name'];
            $updatedStructure['name'] = $updatedStructure['name'].'?'.http_build_query(['payment' => $name]);

            $structures[] = $updatedStructure;
        }

        return $structures;
    }

    public function getStripeCustomerId(Customer $customer)
    {
        $stripeAccount = \Stripe\Account::retrieve();

        $localStripeCustomer = new \StripeCustomer();
        $localStripeCustomer = $localStripeCustomer->getCustomerById($customer->id, $stripeAccount->id);

        if (empty($localStripeCustomer->id)) {
            $stripeCustomer = \Stripe\Customer::create([
                'description' => 'Customer created from Prestashop MrShop App module',
                'email' => $customer->email,
                'name' => $customer->firstname.' '.$customer->lastname,
            ]);

            $localStripeCustomer->id_customer = $customer->id;
            $localStripeCustomer->stripe_customer_key = $stripeCustomer->id;
            $localStripeCustomer->id_account = $stripeAccount->id;
            $localStripeCustomer->save();
        }

        return $localStripeCustomer->stripe_customer_key;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $config = $this->getConfiguration();

        if (!$config || !class_exists('\Stripe\Stripe')) {
            throw new \Exception('Payment method not found or inactive');
        }

        call_user_func('\Stripe\Stripe::setApiKey', $config['secret']);

        $customerId = $this->getStripeCustomerId($customer);
        $ephemeralKey = \Stripe\EphemeralKey::create(
            ['customer' => $customerId],
            ['stripe_version' => self::API_VERSION]
        );

        if ($this->dataHandler->getApiVersion() < 13) {
            return [
                 'gateway' => json_encode($ephemeralKey->jsonSerialize()),
                 'amount' => $totalAmount,
                 'method' => null,
             ];
        }

        $module = $this->getApiModule();
        // ------------------------------ customer -----------------------------
        $stripeCustomerId = $this->getStripeCustomerId($customer);

        // ------------------------------ amount -----------------------------
        $includeTaxes = ApiUtils::includeTaxes($customer->id);
        $cartTotals = $this->cartHandler->getCartTotals($cart, $includeTaxes);
        $amount = $cartTotals['total'] * 100;

        // ------------------------------ currency -----------------------------
        $currency = new Currency((int) $cart->id_currency);
        $currencyCode = $currency->iso_code;

        // ------------------------------ address -----------------------------
        $address_delivery = new Address($cart->id_address_delivery);
        $state_delivery = State::getNameById($address_delivery->id_state);
        $cardHolderName = $customer->firstname.' '.$customer->lastname;

        $captureMethod = 'automatic';

        $proceed = false;
        if (isset($this->extraParameters['payment']) && isset(\Stripe_official::$paymentMethods[$this->extraParameters['payment']])) {
            $paymentMethodName = $this->extraParameters['payment'];
            $paymentMethod = \Stripe_official::$paymentMethods[$this->extraParameters['payment']];
            if ($paymentMethod['enable'] === true || Configuration::get($paymentMethod['enable']) == 'on') {
                $proceed = true;
                $paymentMethodTypes = [$paymentMethodName];

                if ($paymentMethodName == 'card') {
                    $captureMethod = 'manual';
                }
            }
        }

        if (!$proceed) {
            throw new \Exception('Payment method not found or inactive');
        }

        $paymentIntent = $this->createIdempotencyKey(
            [
            'amount' => $amount,
            'currency' => $currencyCode,
            'payment_method_types' => $paymentMethodTypes,
            'capture_method' => $captureMethod,
            'description' => $module->l('Order from app', 'stripemanager'),
            'customer' => $stripeCustomerId,
            'metadata' => [
                'id_cart' => $cart->id,
                'verification_url' => Configuration::get('PS_SHOP_DOMAIN'),
            ],
            'shipping' => [
                'address' => [
                    'city' => $address_delivery->city,
                    'country' => Country::getIsoById($address_delivery->id_country),
                    'line1' => $address_delivery->address1,
                    'line2' => $address_delivery->address2,
                    'postal_code' => $address_delivery->postcode,
                    'state' => $state_delivery,
                ],
                'name' => $cardHolderName,
            ],
        ]
        );

        return [
            'stripe' => [
                'ephemeral_key' => $ephemeralKey->secret,
                'payment_intent' => $paymentIntent->client_secret,
                'customer' => $customerId
            ],
            'gateway' => null,
            'amount' => $totalAmount,
            'method' => null,
        ];
    }

    private function registerStripeEvent($intent)
    {
        try {
            $stripeEventDate = new \DateTime();
            $stripeEventDate = $stripeEventDate->setTimestamp($intent->created);

            $stripeEvent = new \StripeEvent();
            $stripeEvent->setIdPaymentIntent($intent->id);
            $stripeEvent->setStatus(\StripeEvent::CREATED_STATUS);
            $stripeEvent->setDateAdd($stripeEventDate->format('Y-m-d H:i:s'));
            $stripeEvent->setIsProcessed(1);
            $stripeEvent->setFlowType('direct');

            if (!$stripeEvent->save()) {
                ResponseHandler::badRequest($this->module->l('There was an error while executing operation', 'stripemanager'));
            }
        } catch (PrestaShopException $e) {
            ResponseHandler::badRequest($this->module->l('There was an error while executing operation', 'stripemanager'));
        }
    }

    private function createIdempotencyKey($intentData)
    {
        try {
            $cart = $this->context->cart;
            $stripeIdempotencyKey = new \StripeIdempotencyKey();
            $stripeIdempotencyKey = $stripeIdempotencyKey->getByIdCart($cart->id);

            $paymentIntentStatus = (empty($stripeIdempotencyKey->id) === false) ? \Stripe\PaymentIntent::retrieve($stripeIdempotencyKey->id_payment_intent)->status : null;
            $updatableStatus = ['requires_payment_method', 'requires_confirmation', 'requires_action'];

            if (in_array($paymentIntentStatus, $updatableStatus) === false) {
                $intent = $stripeIdempotencyKey->createNewOne($cart->id, $intentData);
                $this->registerStripeEvent($intent);
            } else {
                $intent = $stripeIdempotencyKey->updateIntentData($intentData);
            }

            return $intent;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            ResponseHandler::badRequest($e->getMessage());
        } catch (PrestaShopException $e) {
            ResponseHandler::badRequest($this->module->l('There was an error while executing operation', 'stripemanager'));
        }
    }

    public function handleValidatePayment($customer, $cart, $note, $extra)
    {
        $token = isset($extra['token']) ? $extra['token'] : null;
        $type = isset($extra['type']) ? $extra['type'] : null;
        $module = $this->getApiModule();

        if (empty($token)
            || empty($type)) {
            ResponseHandler::badRequest($module->l('Params required', 'stripemanager'));
        }

        if ($this->isValidPaymentMethod()) {
            $config = $this->getConfiguration();

            // ------------------------------ customer -----------------------------
            $stripeCustomerId = $this->getStripeCustomerId($customer);

            // ------------------------------ amount -----------------------------
            $includeTaxes = ApiUtils::includeTaxes($customer->id);
            $cartTotals = $this->cartHandler->getCartTotals($cart, $includeTaxes);
            $amount = $cartTotals['total'] * 100;

            if ($amount <= 0) {
                ResponseHandler::badRequest(
                    $module->l('Cart is empty', 'stripemanager')
                );
            }

            // ------------------------------ currency -----------------------------
            $currency = new Currency((int) $cart->id_currency);
            $currencyCode = $currency->iso_code;

            // ------------------------------ address -----------------------------
            $address_delivery = new Address($cart->id_address_delivery);
            $state_delivery = State::getNameById($address_delivery->id_state);
            $cardHolderName = $customer->firstname.' '.$customer->lastname;

            try {
                call_user_func('\Stripe\Stripe::setApiKey', $config['secret']);

                $intent = call_user_func('\Stripe\PaymentIntent::create', [
                    'payment_method' => $token,
                    'use_stripe_sdk' => true,
                    'amount' => $amount,
                    'currency' => $currencyCode,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'description' => $module->l('Order from app', 'stripemanager'),
                    'customer' => $stripeCustomerId,
                    'metadata' => [
                        'cart_id' => $cart->id,
                        'verification_url' => Configuration::get('PS_SHOP_DOMAIN'),
                    ],
                    'shipping' => [
                        'address' => [
                            'city' => $address_delivery->city,
                            'country' => Country::getIsoById($address_delivery->id_country),
                            'line1' => $address_delivery->address1,
                            'line2' => $address_delivery->address2,
                            'postal_code' => $address_delivery->postcode,
                            'state' => $state_delivery,
                        ],
                        'name' => $cardHolderName,
                    ],
                ]);

                if ($intent->status == 'requires_action' &&
                    $intent->next_action->type == 'use_stripe_sdk') {
                    // The client needs to handle the action
                    ResponseHandler::success([
                        'requires_action' => true,
                        'payment_intent_client_secret' => $intent->client_secret,
                    ]);
                } elseif ($intent->status == 'succeeded') {
                    // The payment didn’t need any additional actions and completed!
                    // Handle post-payment fulfillment
                    $this->createStripeOrder($customer, $cart, $intent, $type, $note);
                } else {
                    // Invalid status
                    ResponseHandler::badRequest(
                        $module->l('Payment Error', 'stripemanager')
                    );
                }
            } catch (\Exception $e) {
                $charge = null;
                $refund = $amount;
                $this->addTentative(
                    $e->getMessage(),
                    $cardHolderName,
                    $type,
                    $refund,
                    $refund,
                    $currencyCode,
                    0,
                    $cart->id
                );
                ResponseHandler::badRequest(
                    $module->l('Payment Error', 'stripemanager').': '.$e->getMessage()
                );
            }
        }
    }

    public function handleConfirmPayment($customer, $cart, $note, $extra)
    {
        $config = $this->getConfiguration();

        if (!isset($extra['payment_intent_id'])
            || !isset($extra['type'])) {
            ResponseHandler::badRequest(
                $this->module->l('Payment Error', 'stripemanager')
            );
        }

        call_user_func('\Stripe\Stripe::setApiKey', $config['secret']);

        $paymentIntent = call_user_func('\Stripe\PaymentIntent::retrieve', $extra['payment_intent_id']);
        $paymentIntent->confirm();

        $this->createStripeOrder($customer, $cart, $paymentIntent, $extra['type'], $note);
    }

    private function createStripeOrder($customer, $cart, $paymentIntent, $type, $note)
    {
        /**
         * @var Stripe_official $paymentModule
         */
        $paymentModule = $this->moduleInstance;
        $module = $this->getApiModule();
        $charge = $paymentIntent->charges->getIterator()->current();
        $cardHolderName = $charge->shipping->name;

        if ($charge) {
            if (($charge->status == 'succeeded' && $charge->object == 'charge' && $charge->id)
                || ($charge->status == 'pending' && $charge->object == 'charge' && $charge->id && $type == 'sofort')) {
                // The payment was approved
                $note = 'Stripe Transaction ID: '.$charge->id.' - '.$note;
                $secure_key = $customer->secure_key;

                $validOrder = false;

                try {
                    $paid = $paymentModule->isZeroDecimalCurrency($charge->currency) ? $charge->amount : $charge->amount / 100;

                    // Add transaction on Prestashop back Office (Order)
                    if ($type == 'sofort' && $charge->status == 'pending') {
                        $status = Configuration::get('STRIPE_OS_SOFORT_WAITING');
                    } else {
                        $status = Configuration::get('PS_OS_PAYMENT');
                    }
                    $validOrder = $paymentModule->validateOrder(
                        (int) $charge->metadata->cart_id,
                        (int) $status,
                        $paid,
                        $module->l('Payment by Stripe', 'stripemanager'),
                        $note,
                        [],
                        null,
                        false,
                        $secure_key
                    );
                } catch (\Exception $e) {
                    ResponseHandler::badRequest(
                        $module->l('Payment Error', 'stripemanager').': '.(string) $e->getMessage()
                    );
                }

                // Add transaction on database
                if ($type == 'sofort' && $charge->status == 'pending') {
                    $result = 4;
                } else {
                    $result = 1;
                }
                $this->addTentative(
                    $charge->id,
                    $cardHolderName,
                    $type,
                    $charge->amount,
                    0,
                    $charge->currency,
                    $result,
                    (int) $charge->metadata->cart_id
                );

                $this->cartHandler->generateNewCartFromCustomer($cart->id_customer);

                if ($validOrder) {
                    $ch = call_user_func('\Stripe\Charge::retrieve', $charge->id);
                    $ch->description = 'Order id: '.$paymentModule->currentOrder.' - '.$customer->email;
                    $ch->save();

                    ResponseHandler::success([
                        'reference' => $paymentModule->currentOrderReference,
                        'text' => $module->l('Payment complete', 'stripemanager'),
                    ]);
                } else {
                    ResponseHandler::success([
                        'reference' => '',
                        'text' => $module->l('Payment complete', 'stripemanager'),
                    ]);
                }
            } else {
                $this->addTentative(
                    $charge->id,
                    $cardHolderName,
                    $type,
                    $charge->amount,
                    0,
                    $charge->currency,
                    0,
                    $cart->id
                );
                ResponseHandler::badRequest($module->l('Payment Error - Unknown Error', 'stripemanager'));
            }
        } else {
            ResponseHandler::badRequest($module->l('Payment Error', 'stripemanager'));
        }
    }

    /*
     * Function copied from the official module which has this method but it's private
     */
    public function addTentative($id_stripe, $name, $type, $amount, $refund, $currency, $result, $id_cart, $mode = null)
    {
        if ($type == 'American Express') {
            $type = 'amex';
        } elseif ($type == 'Diners Club') {
            $type = 'diners';
        }

        // @see: https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support
        $zeroDecimalCurrencies = [
            'BIF',
            'CLP',
            'DJF',
            'GNF',
            'JPY',
            'KMF',
            'KRW',
            'MGA',
            'PYG',
            'RWF',
            'VND',
            'VUV',
            'XAF',
            'XOF',
            'XPF',
        ];

        if (!in_array($currency, $zeroDecimalCurrencies)) {
            $amount /= 100;
            $refund /= 100;
        }

        if ($mode === null) {
            if (Configuration::hasKey('_PS_STRIPE_mode')) {
                $stripeModeKey = '_PS_STRIPE_mode';
            } else {
                $stripeModeKey = 'STRIPE_MODE';
            }
            $mode = Configuration::get($stripeModeKey, null, null, null, 1);
        }

        /* Add request on Database */
        Db::getInstance()->Execute(
            'INSERT INTO '._DB_PREFIX_
            .'stripe_payment (id_stripe, name, id_cart, type, amount, refund, currency, result, state, date_add)
            VALUES ("'.pSQL($id_stripe).'", "'.pSQL($name).'", \''.(int) $id_cart.'\', "'.pSQL(\Tools::strtolower($type)).'", "'
            .pSQL($amount).'", "'.pSQL($refund).'", "'.pSQL(\Tools::strtolower($currency)).'", '.(int) $result.', '.(int) $mode.', NOW())'
        );
    }
}
