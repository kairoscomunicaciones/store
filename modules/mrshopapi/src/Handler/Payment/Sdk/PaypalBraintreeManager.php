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

use Address;
use Braintree\Gateway as Braintree_Gateway;
use Braintree\MerchantAccountGateway as Braintree_MerchantAccountGateway;
use Configuration;
use Country;
use Currency;
use Db;
use Module;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Order;
use Shop;
use State;
use Tools;
use Validate;

class PaypalBraintreeManager extends PaymentManager
{
    private $configuration;
    
    private $name;

    /*
     * paypal < 5.0.0 = paypal + braintree
     * paypal > 5.0.0 = paypal (braintree separato in braintreeofficial)
     *
     */
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_SDK'];
        $module = $this->getApiModule();
        $this->name = $name;
        
        if ($name === 'braintreeofficial') {
            $this->description = $module->l('Pay safely with your PayPal account or with your credit cards.', 'paypalbraintreemanager');
        } elseif ($name === 'paypalwithfee') {
            $this->description = $module->l('PayPal payment with fee.', 'paypalbraintreemanager');
        } else {
            $this->description = $module->l('Pay safely with your PayPal account.', 'paypalbraintreemanager');
        }

        $this->configuration = [];

        if (!$this->moduleInstance) {
            return;
        }

        $isPaypalOld = (version_compare($this->moduleInstance->version, '5.0.0', '<') && Module::isEnabled('paypal'));

        /*
         * Get paypal method
         * EC = express checkout
         * ECS = express checkout sortcut
         * BT = Braintree
         * PPP = PayPal Plus
         */
        if (Configuration::hasKey('PAYPAL_METHOD') && $isPaypalOld) {
            $method = Configuration::get('PAYPAL_METHOD');
        } else {
            $method = $name === 'braintreeofficial' ? 'BT' : 'EC';
        }
        $this->configuration['method'] = $method;

        /*
         * Setup configuration key
         */
        if ($isPaypalOld
            && Configuration::hasKey('PAYPAL_SANDBOX')
            && Configuration::hasKey('PAYPAL_LIVE_BRAINTREE_ACCESS_TOKEN')) {
            $sandboxKey = 'PAYPAL_SANDBOX';
            $paypalSandboxTokenKey = 'PAYPAL_SANDBOX_BRAINTREE_ACCESS_TOKEN';
            $paypalLiveTokenKey = 'PAYPAL_LIVE_BRAINTREE_ACCESS_TOKEN';
        } elseif (static::isOneOfPaypalModulesEnabled()) {
            $sandboxKey = 'MRSHOP_PAYPAL_SANDBOX';
            $paypalSandboxTokenKey = 'MRSHOP_PAYPAL_SANDBOX_BRAINTREE_ACCESS_TOKEN';
            $paypalLiveTokenKey = 'MRSHOP_PAYPAL_LIVE_BRAINTREE_ACCESS_TOKEN';
        } else {
            // BT
            $sandboxKey = 'BRAINTREEOFFICIAL_SANDBOX';
        }

        /**
         * Check if sandbox enabled
         */
        $sandbox = (bool) Configuration::get($sandboxKey, null, null, null, true);
        $this->configuration['sandbox'] = $sandbox;

        /*
         * Set access token (paypal) or private/public key (braintree)
         */
        if (static::isOneOfPaypalModulesEnabled()) {
            if (Module::isEnabled('paypalwithfee') && version_compare($this->moduleInstance->version, '4.0.0', '<')) {
                $this->configuration = null;
            } else {
                $accessToken = trim(Configuration::get(($sandbox ? $paypalSandboxTokenKey : $paypalLiveTokenKey), null, null, null, ''));
                if ($accessToken && !empty(trim($accessToken))) {
                    $this->configuration['access_token'] = $accessToken;
                } else {
                    $this->configuration = null;
                }
            }
        } else {
            if ($sandbox) {
                $publicKey = Configuration::get('BRAINTREEOFFICIAL_PUBLIC_KEY_SANDBOX');
                $privateKey = Configuration::get('BRAINTREEOFFICIAL_PRIVATE_KEY_SANDBOX');
                $merchantId = Configuration::get('BRAINTREEOFFICIAL_MERCHANT_ID_SANDBOX');
            } else {
                $publicKey = Configuration::get('BRAINTREEOFFICIAL_PUBLIC_KEY_LIVE');
                $privateKey = Configuration::get('BRAINTREEOFFICIAL_PRIVATE_KEY_LIVE');
                $merchantId = Configuration::get('BRAINTREEOFFICIAL_MERCHANT_ID_LIVE');
            }
            if (!empty($publicKey) && !empty($privateKey) && !empty($merchantId)) {
                $this->configuration['public_key'] = $publicKey;
                $this->configuration['private_key'] = $privateKey;
                $this->configuration['merchant_id'] = $merchantId;
            } else {
                $this->configuration = null;
            }
        }
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();
        switch ($this->name) {
            case 'braintreeofficial':
                return $module->l('Pay with BrainTree', 'paypalbraintreemanager', $langIsoCode);

                break;
            default:
                return $module->l('Pay with PayPal', 'paypalbraintreemanager', $langIsoCode);

                break;
        }
    }

    public static function isOneOfPaypalModulesEnabled()
    {
        return Module::isEnabled('paypal') || Module::isEnabled('paypalwithfee') || Module::isEnabled('paypalusa');
    }
    
    public function isValidPaymentMethod($cart = null)
    {
        if (Module::isEnabled('braintreeofficial') && $this->dataHandler->getApiVersion() < 4) {
            return false;
        }

        if (Module::isEnabled('paypalwithfee')) {
            if ($cart === null) {
                return true;
            }

            $blockme = false;
            $categorias_search = [];
            $limited_categories = explode(',', Configuration::get('PPAL_FEE_DISABLECAT'));
            $product_by_category = $cart->getProducts(true);
            $ppal_fee_limit = (int) Configuration::get('PPAL_FEE_LIMIT');

            /* conversion rate o fee limit if currency is not default */
            if ($cart->id_currency != Configuration::get('PS_CURRENCY_DEFAULT')) {
                $current_currency = new Currency($cart->id_currency);
                $conversion_rate = $current_currency->conversion_rate;
                $ppal_fee_limit = round($ppal_fee_limit * $conversion_rate, 6);
            }

            foreach ($product_by_category as $value) {
                $categorias_search[] = $value['id_category_default'];
                $categories = Db::getInstance()->executeS(
                    'SELECT `id_category` FROM `'._DB_PREFIX_.'category_product` WHERE `id_product`='.(int) $value['id_product']
                );
                foreach ($categories as $category) {
                    $categorias_search[] = $category['id_category'];
                }
            }

            foreach ($limited_categories as $value) {
                if (in_array($value, $categorias_search)) {
                    $blockme = true;

                    break;
                }
            }

            if ($blockme || ($cart->getOrderTotal(true) > $ppal_fee_limit && $ppal_fee_limit !== 0)) {
                return false;
            }
        }

        return parent::isValidPaymentMethod($cart) && $this->getConfiguration() != null;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = $this->getConfiguration()['sandbox'];

        return $structure;
    }

    public function getBraintreeGateway()
    {
        if (isset($this->configuration['access_token'])) {
            return new Braintree_Gateway([
                'accessToken' => $this->configuration['access_token'],
            ]);
        } else {
            return new Braintree_Gateway([
                'environment' => $this->configuration['sandbox'] ? 'sandbox' : 'live',
                'merchantId' => $this->configuration['merchant_id'],
                'publicKey' => $this->configuration['public_key'],
                'privateKey' => $this->configuration['private_key'],
            ]);
        }
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $config = $this->getConfiguration();

        if (!$config) {
            throw new \Exception('Payment method not found or inactive');
        }

        $gateway = $this->getBraintreeGateway();
        $clientToken = $gateway->clientToken()->generate();

        return [
            'gateway' => $clientToken,
            'amount' => $totalAmount,
            'method' => $this->moduleName
        ];
    }

    private function getMerchantAccountId($gateway, $currency)
    {
        if (!$gateway || !$currency) {
            return null;
        }
        if (!$gateway || !$currency) {
            return null;
        }

        $merchantAccountGateway = new Braintree_MerchantAccountGateway($gateway);
        $allMerchants = $merchantAccountGateway->all();

        foreach ($allMerchants as $m) {
            $json = $m->jsonSerialize();
            $currencyIsoCode = isset($json['currencyIsoCode']) ? Tools::strtoupper(trim($json['currencyIsoCode'])) : '';
            $merchantAccountId = isset($json['id']) ? trim($json['id']) : '';
            if ($currencyIsoCode == $currency->iso_code) {
                return $merchantAccountId;
            }
        }

        return null;
    }

    public function handleValidatePayment($customer, $cart, $note, $extra)
    {
        $module = $this->getApiModule();
        $cart = $this->context->cart;

        if ($this->isValidPaymentMethod($cart)) {
            $config = $this->getConfiguration();
            $gateway = $this->getBraintreeGateway();

            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            try {
                $shop = new Shop((int) $cart->id_shop);
                $currency = new Currency((int) $cart->id_currency);
                $billingAddress = new Address((int) $cart->id_address_invoice);
                $shippingAddress = new Address((int) $cart->id_address_delivery);

                $billingCountry = new Country((int) $billingAddress->id_country);
                $shippingCountry = new Country((int) $shippingAddress->id_country);

                $includeTaxes = ApiUtils::includeTaxes($customer->id);
                $cartTotals = $this->cartHandler->getCartTotals($cart, $includeTaxes);
                $amount = $cartTotals['total'];

                $this->context->cart = $cart;
                $this->context->customer = $customer;
                $this->context->shop = $shop;
                $this->context->currency = $currency;
                $this->context->country = $billingCountry;

                $billingIsoState = '';
                if ($billingAddress->id_state) {
                    $state = new State((int) $billingAddress->id_state);
                    $billingIsoState = $state->iso_code;
                }

                $shippingIsoState = '';
                if ($shippingAddress->id_state) {
                    $state = new State((int) $shippingAddress->id_state);
                    $shippingIsoState = $state->iso_code;
                }

                $orderId = $cart->secure_key.'_'.$cart->id;

                $data = [
                    'amount' => $amount,
                    'paymentMethodNonce' => $extra['paymentMethodNonce'],
                    'orderId' => $orderId,
                    'billing' => [
                        'firstName' => $billingAddress->firstname,
                        'lastName' => $billingAddress->lastname,
                        'company' => $billingAddress->company,
                        'streetAddress' => $billingAddress->address1,
                        'extendedAddress' => $billingAddress->address2,
                        'locality' => $billingAddress->city,
                        'postalCode' => $billingAddress->postcode,
                        'countryCodeAlpha2' => $billingCountry->iso_code,
                        'region' => $billingIsoState,
                    ],
                    'shipping' => [
                        'firstName' => $shippingAddress->firstname,
                        'lastName' => $shippingAddress->lastname,
                        'company' => $shippingAddress->company,
                        'streetAddress' => $shippingAddress->address1,
                        'extendedAddress' => $shippingAddress->address2,
                        'locality' => $shippingAddress->city,
                        'postalCode' => $shippingAddress->postcode,
                        'countryCodeAlpha2' => $shippingCountry->iso_code,
                        'region' => $shippingIsoState,
                    ],
                    'options' => [
                        'submitForSettlement' => true,
                        'paypal' => [
                            'description' => $module->l('Order from app', 'paypalbraintreemanager'),
                        ],
                    ],
                ];

                $merchantAccountId = $this->getMerchantAccountId($gateway, $currency);
                if ($merchantAccountId != null) {
                    $data['merchantAccountId'] = $merchantAccountId;
                }

                // if Braintree, use vault
                if (array_key_exists('method', $config) &&
                    $config['method'] === 'BT') {
                    $data['options']['storeInVaultOnSuccess'] = true;
                }

                // Pagamento Braintree
                $result = $gateway->transaction()->sale($data);

                if (!$result->success) {
                    error_log($result->message.'[code: '.$result->transaction->processorResponseCode.']');
                    ResponseHandler::badRequest($module->l('Payment Error', 'paypalbraintreemanager').': '.$result->message);
                }

                // Creazione ordine Paypal
                $this->moduleInstance->validateOrder(
                    $cart->id,
                    Configuration::get('PS_OS_PAYMENT'),
                    $amount,
                    $this->moduleInstance->displayName,
                    $note,
                    [
                        'transaction_id' => $result->transaction->id,
                        'payment_method' => $result->transaction->type,
                        'payment_status' => $result->transaction->status,
                        'total_paid' => $result->transaction->amount,
                        'id_payment' => '',
                        'capture' => true,
                        'currency' => $result->transaction->currencyIsoCode,
                        'shipping' => $result->transaction->shippingAmount,
                        'payment_date' => date('Y-m-d H:i:s', $result->transaction->createdAt->getTimestamp()),
                    ],
                    (int) $currency->id,
                    false,
                    $customer->secure_key,
                    $this->context->shop
                );

                $order = new Order($this->moduleInstance->currentOrder);

                $this->cartHandler->generateNewCartFromCustomer($customer->id);

                ResponseHandler::success([
                    'reference' => (Validate::isLoadedObject($order)) ? $order->reference : '',
                    'text' => $module->l('Payment complete', 'paypalbraintreemanager'),
                ]);
            } catch (\Exception $e) {
                error_log($e);
                ResponseHandler::badRequest($module->l('Payment Error', 'paypalbraintreemanager'));
            }
        } else {
            ResponseHandler::badRequest($module->l('Payment Error', 'paypalbraintreemanager'));
        }
    }
}
