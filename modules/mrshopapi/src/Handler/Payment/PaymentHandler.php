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

namespace MrAPPs\MrShopApi\Handler\Payment;

use Address;
use Cart;
use Context;
use Country;
use Currency;
use Customer;
use Hook;
use Message;
use Module;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\Payment\Delayed\BankwireManager;
use MrAPPs\MrShopApi\Handler\Payment\Delayed\CashOnDeliveryManager;
use MrAPPs\MrShopApi\Handler\Payment\Delayed\ChequeManager;
use MrAPPs\MrShopApi\Handler\Payment\Delayed\InvoicePaymentManager;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\PaypalBraintreeManager;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\SatispayManager;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\StripeManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\AmazonPayManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\CicManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\ConstrivManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\FlowPayManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\MoneticoManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\MrShopIGFSPaymentManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\NewCarnetManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\NexixpayManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\PayonwebManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\PayPlugManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\PsCheckoutManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\RedSysManager;
use MrAPPs\MrShopApi\Handler\Payment\Web\WebManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrShopPaymentModule;
use Order;
use PaymentModule;
use Shop;
use Tools;
use Validate;

class PaymentHandler
{
    private $context;

    private $module;

    /** @var DataHandler $dataHandler */
    private $dataHandler;

    /** @var CartHandler $cartHandler */
    private $cartHandler;

    private $customerCart = null;

    /*
     * moduleName => managerClassName
     */
    private $modules = [
        'ps_wirepayment'       => BankwireManager::class,
        'bankwire'             => BankwireManager::class,
        'bankwirediscount'     => BankwireManager::class,
        'ps_checkpayment'      => ChequeManager::class,
        'cheque'               => ChequeManager::class,
        'ps_cashondelivery'    => CashOnDeliveryManager::class,
        'cashondelivery'       => CashOnDeliveryManager::class,
        'mrshopcashondelivery' => CashOnDeliveryManager::class,
        'codfee'               => CashOnDeliveryManager::class,
        'paypal'               => PaypalBraintreeManager::class,
        'braintreeofficial'    => PaypalBraintreeManager::class,
        'paypalwithfee'        => PaypalBraintreeManager::class,
        'paypalusa'            => PaypalBraintreeManager::class,
        'payonweb'             => PayonwebManager::class,
        'nexixpay'             => NexixpayManager::class,
        'mrshopigfspayment'    => MrShopIGFSPaymentManager::class,
        'satispay'             => SatispayManager::class,
        'newcarnetpayment'     => NewCarnetManager::class,
        'invoicepayment'       => InvoicePaymentManager::class,
        'stripe_official'      => StripeManager::class,
        'redsysoficial'        => RedSysManager::class,
        'sps2006_cmcic30'      => CicManager::class,
        'constriv'             => ConstrivManager::class,
        'flowpaymentwp'        => FlowPayManager::class,
        'payplug'              => PayPlugManager::class,
        'amazonpay'            => AmazonPayManager::class,
        'monetico1f'           => MoneticoManager::class,
        'sdevmonetico'         => MoneticoManager::class,
        'ps_checkout'          => PsCheckoutManager::class,
        'general_web'          => WebManager::class
    ];

    private $acceptedModules = [];

    public function __construct($dataHandler)
    {
        $this->dataHandler    = $dataHandler;
        $this->context        = Context::getContext();
        $this->module         = ApiUtils::getModule();
        $this->cartHandler    = new CartHandler($this->dataHandler->getGuestId(), $dataHandler);
    }

    private function validatePaymentModules($customerId = null, $cart = null)
    {
        if (is_null($cart)) {
            $cart = $this->getCustomerCart($customerId);
        }
        
        $acceptedMethodsIDs = $this->getPaymentModulesId();

        foreach ($this->modules as $key => $value) {
            if (in_array(Module::getModuleIdByName($key), $acceptedMethodsIDs)) {
                /** @var PaymentManager $manager */
                $manager = new $value($key, $this->dataHandler);
                if ($manager->getModuleInstance() != false && $manager->isValidPaymentMethod($cart)) {
                    $this->acceptedModules[] = $manager->getModuleName();
                }
            }
        }
    }
    
    public function getAllManagerInstances()
    {
        $retval = [];
        
        foreach ($this->modules as $key => $value) {
            if ((bool) Module::getModuleIdByName($key)) {
                $retval[] = new $value($key, $this->dataHandler);
            }
        }

        return $retval;
    }

    public function getModuleList()
    {
        $managers = $this->getAllManagerInstances();
        $modules = [];

        foreach ($managers as $manager) {
            $m = [
                'moduleName' => $manager->getModuleName(),
                'status'     => $manager->isValidPaymentMethod(),
                'text'       => $manager->getDisplayName(),
                'supported'  => true,
                'id_module'  => $manager->getModuleInstance()->id
            ];
            
            $modules[] = $m;
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $paymentMethods = PaymentModule::getInstalledPaymentModules();
            foreach ($paymentMethods as $paymentModule) {
                $idModule = (int) $paymentModule['id_module'];
                $moduleName = $paymentModule['name'];
                $found = false;
                foreach ($modules as &$m) {
                    if ($m['moduleName'] == $moduleName) {
                        $found = true;
                        $m['id_module'] = $idModule;

                        break;
                    }
                }
    
                if (!$found) {
                    $modules[] = [
                        'id_module' => $idModule,
                        'moduleName' => $moduleName,
                        'status'     => Module::isEnabled($moduleName),
                        'text'       => $moduleName,
                        'supported'  => false
                    ];
                }
            }
        }

        return $modules;
    }

    public function getFullModulesList()
    {
        $modulesList = [];
        foreach ($this->modules as $key => $value) {
            if ((bool) Module::getModuleIdByName($key)) {
                /** @var PaymentManager $manager */
                $manager = new $value($key, $this->dataHandler);
                $modulesList[] = [
                    'name' => $manager->getDisplayModuleName(),
                    'visible' => $manager->isValidPaymentMethod(),
                ];
            }
        }

        return $modulesList;
    }

    public function getList($customerId)
    {
        $data = [];
        $modulesArray = $this->getValidPaymentModulesName($customerId);
        $cart = $this->getCustomerCart($customerId);

        foreach ($modulesArray as $moduleName) {
            try {
                /** @var PaymentManager $manager */
                $manager = $this->getModuleManager($moduleName);
                $structure = $manager->getStructure($customerId, (int) $cart->id);

                if (!empty($structure)) {
                    if (ApiUtils::isAssociative($structure)) {
                        $modulePaymentMethods = [$structure];
                    } else {
                        $modulePaymentMethods = $structure;
                    }

                    foreach ($modulePaymentMethods as $modulePaymentMethod) {
                        // amazon pay has not to be shown in the list since it has a completely different flow
                        if ($modulePaymentMethod['name'] == 'amazonpay') {
                            continue;
                        }
                        $data[] = array_merge($modulePaymentMethod, ['id' => count($data) + 1]);
                    }
                }
            } catch (\Exception $e) {
            }
        }

        return $data;
    }

    /**
     * @param Cart $cart
     * @return void
     */
    private function setCheckoutData($cart)
    {
        $bothAddresses = (int) $cart->id_address_invoice != 0 && (int) $cart->id_address_delivery != 0;
        $isCarrierSetted = (int) $cart->id_carrier != 0;

        $cartChecksum = new \CartChecksum(new \AddressChecksum());
        $checksum = $cartChecksum->generateChecksum($cart);

        $data = [
            "checkout-personal-information-step" => [
                "step_is_reachable" => true,
                "step_is_complete" => true,
            ],
            "checkout-addresses-step" => [
                "step_is_reachable" => true,
                "step_is_complete" => $bothAddresses,
                "use_same_address" => $bothAddresses && $cart->id_address_delivery == $cart->id_address_invoice
            ],
            "checkout-delivery-step" => [
                "step_is_reachable" => $bothAddresses,
                "step_is_complete" => $isCarrierSetted,
            ],
            "checkout-payment-step" => [
                "step_is_reachable" => $isCarrierSetted,
                "step_is_complete" => false,
            ],
            'checksum' => $checksum
        ];

        \Db::getInstance()->execute(
            'UPDATE '._DB_PREFIX_.'cart SET checkout_session_data = "'.pSQL(json_encode($data)).'"
                WHERE id_cart = '.(int) $cart->id
        );
    }

    public function findPaymentOptions()
    {
        $paymentOptions = Hook::exec('displayPaymentEU', [], null, true);

        $paymentOptions = array_merge($paymentOptions, Hook::exec('advancedPaymentOptions', [], null, true));

        $paymentOptions = array_merge($paymentOptions,Hook::exec('paymentOptions', [], null, true));

        // Safety check
        foreach ($paymentOptions as $moduleName => $paymentOption) {
            if (!is_array($paymentOption)) {
                unset($paymentOptions[$moduleName]);
            }
        }

        return $paymentOptions;
    }

    public function getValidPaymentModulesName($customerId, $cart = null)
    {
        $this->validatePaymentModules($customerId, $cart);

        // Valuta
        $currency = new Currency((int) $this->dataHandler->getCurrencyId());
        if (!Validate::isLoadedObject($currency)) {
            // La prima valuta attiva
            $currencyId = ApiUtils::getFirstCurrency();
            $currency = new Currency($currencyId);
        }
        $this->context->currency = $currency;
        // -----------------------------------------------------------------

        $modulesArray = [];

        /* fix for ps 1.7.6 not setting smarty link variable */
        if (!$this->context->smarty->getTemplateVars('link')) {
            $this->context->smarty->assign('link', $this->context->link);
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '<')) {
            try {
                foreach (Hook::exec('displayPayment', [], null, true) as $moduleName => $paymentHtml) {
                    if (!empty($paymentHtml) && !in_array($moduleName, $modulesArray) && in_array($moduleName, $this->acceptedModules)) {
                        $modulesArray[] = $moduleName;
                    }
                }
            } catch (\Exception $exception) {
                error_log($exception->getMessage());
            }
        } else {
            if ($cart != null) {
                $this->setCheckoutData($cart);
            }

            try {
                $paymentMethods = $this->findPaymentOptions();
                $allowedPaymentMethods = MrShopPaymentModule::getAllowedPaymentModules();
                $allowedModuleNames = array_map(function ($item) {
                    return $item['name'];
                }, $allowedPaymentMethods);

                foreach ($paymentMethods as $moduleName => $paymentMethod) {
                    if (in_array($moduleName, $allowedModuleNames)) {
                        $modulesArray[] = $moduleName;
                    }
                }
            } catch (\Exception $exception) {
                error_log($exception->getMessage());
            }
        }

        return $modulesArray;
    }
    
    public function isModuleValidFor($moduleName, $customerId, $cart = null)
    {
        return in_array($moduleName, $this->getValidPaymentModulesName($customerId, $cart));
    }

    public function getCustomerCart($customerId)
    {
        if ($this->customerCart) {
            return $this->customerCart;
        }

        $cart = $this->cartHandler->getCart($customerId, false, $this->dataHandler->getCurrencyId());
        if ($cart == null) {
            ResponseHandler::badRequest($this->module->l('Cart not found', 'paymenthandler'));
        }

        $infoMessages = $this->cartHandler->validateCart();

        if (is_array($infoMessages) && !empty($infoMessages)) {
            ResponseHandler::badRequest(
                implode("\n", $infoMessages)
            );
        }

        $this->customerCart = $cart;

        return $cart;
    }

    private function getPaymentModulesId()
    {
        $modulesArray = call_user_func('PaymentModule::getPaymentModules');

        $modulesId = [];

        foreach ($modulesArray as $module) {
            $modulesId[] = (int) $module['id_module'];
        }

        return $modulesId;
    }

    public function getModuleManager($moduleName)
    {
        if (array_key_exists($moduleName, $this->modules)) {
            return new $this->modules[$moduleName]($moduleName, $this->dataHandler);
        } else {
            return new WebManager($moduleName, $this->dataHandler);
        }
    }

    public function getPaymentManager($customerId, $paymentMethodName, $extraParameters = [], $skipValidation = false, $nullifyIfError = false)
    {
        $paymentMethods = $this->findPaymentOptions();
        $isHookModule = array_key_exists($paymentMethodName, $paymentMethods);

        $acceptedMethodsIDs = $this->getPaymentModulesId();
        if (!array_key_exists($paymentMethodName, $this->modules) && !$isHookModule) {
            ResponseHandler::badRequest($this->module->l('Payment method not found or inactive', 'paymenthandler'));
        }

        /** @var PaymentManager $manager */
        $manager = $this->getModuleManager($paymentMethodName);
        $manager->setExtraParameters($extraParameters);
        $manager->setHookModule($isHookModule);

        if ($skipValidation) {
            return $manager;
        }

        $moduleInstance = $manager->getModuleInstance();
        $cart = $this->getCustomerCart($customerId);
        if (!$moduleInstance ||
            !$manager->isValidPaymentMethod($cart) ||
            !in_array((int) $moduleInstance->id, $acceptedMethodsIDs)) {
            if ($nullifyIfError) {
                return null;
            } else {
                ResponseHandler::badRequest($this->module->l('Payment method not found or inactive', 'paymenthandler'));
            }
        }

        return $manager;
    }

    private function composeNotes($noteUser)
    {
        return 'Order from app'.((Tools::strlen($noteUser) > 0) ? ' - '.$noteUser : '');
    }

    public function initPayment($customerId, $paymentMethodName, $noteUser, $extraParameters = [])
    {
        $manager = $this->getPaymentManager($customerId, $paymentMethodName, $extraParameters, false, false);

        $cart = $this->getCustomerCart($customerId);
        $shop = new Shop((int) $cart->id_shop);

        $customer = new Customer((int) $cart->id_customer);
        $billingAddress = new Address((int) $cart->id_address_invoice);
        $shippingAddress = new Address((int) $cart->id_address_delivery);
        // $manager = $this->getPaymentManager($customerId, $paymentMethodName, $extraParameters);

        $this->validateCartObjects($customer, $billingAddress, $shippingAddress);

        $this->context->cart = $cart;
        $this->context->customer = $customer;
        $this->context->shop = $shop;
        $this->context->country = new Country((int) $shippingAddress->id_country);

        if ($cart->secure_key != $customer->secure_key) {
            $cart->secure_key = $customer->secure_key;
            $cart->save();
        }

        // ------------------------------------

        $includeTaxes = ApiUtils::includeTaxes($customer->id);
        $this->cartHandler->validateCart();

        $cartTotals = $this->cartHandler->getCartTotals($cart, $includeTaxes, false, $paymentMethodName);

        $totalAmount = $cartTotals['total_inc_fee'];

        try {
            /** Add message before order  **/
            $notes = $this->composeNotes($noteUser);
            $oldMessage = Message::getMessageByCartId((int) $cart->id);
            $message = new Message($oldMessage && !$oldMessage['private'] ? $oldMessage['id_message'] : null);
            $message->message = $notes;
            $message->id_customer = $customer->id;
            $message->id_cart = $cart->id;
            $message->private = false;
            $message->save();

            $response = $manager->handleInitPayment(
                $customer,
                $cart,
                null,
                $shop,
                $this->context->currency,
                $totalAmount
            );

            $this->response($manager->getPaymentType(), $response);
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            ResponseHandler::badRequest($this->module->l($exception->getMessage(), 'paymenthandler'));
        }

        // Modulo di pagamento non accettato
        ResponseHandler::badRequest($this->module->l('Payment method not found or inactive', 'paymenthandler'));
    }

    private function getCartAfterValidateObjects($customerId, $extraParameters)
    {
        if (isset($extraParameters['cart_id'])) {
            $cart = new Cart((int) $extraParameters['cart_id']);
        } else {
            $cart = $this->getCustomerCart($customerId);
        }

        $customer = new Customer((int) $cart->id_customer);
        $billingAddress = new Address((int) $cart->id_address_invoice);
        $shippingAddress = new Address((int) $cart->id_address_delivery);

        $this->validateCartObjects($customer, $billingAddress, $shippingAddress);

        return $cart;
    }

    public function validatePayment($customerId, $paymentMethodName, $noteUser, $extraParameters = [], $serverSide = false, $skipValidation = false)
    {
        try {
            $manager = $this->getPaymentManager($customerId, $paymentMethodName, $extraParameters, $skipValidation);
            $cart = $this->getCartAfterValidateObjects($customerId, $extraParameters);

            if ($serverSide) {
                $manager->handleValidatePaymentS2S(
                    $cart,
                    null
                );
            } else {
                $customer = new Customer((int) $cart->id_customer);
                $manager->handleValidatePayment(
                    $customer,
                    $cart,
                    null,
                    $extraParameters
                );
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            ResponseHandler::badRequest($this->module->l($exception->getMessage(), 'paymenthandler'));
        }
        // ------------------------------------

        // Modulo di pagamento non accettato
        ResponseHandler::badRequest($this->module->l('Payment method not found or inactive', 'paymenthandler'));
    }

    public function confirmPayment($customerId, $paymentMethodName, $noteUser, $extraParameters = [])
    {
        try {
            $manager = $this->getPaymentManager($customerId, $paymentMethodName, $extraParameters);
            $cart = $this->getCartAfterValidateObjects($customerId, $extraParameters);
            $customer = new Customer((int) $cart->id_customer);

            $manager->handleConfirmPayment(
                $customer,
                $cart,
                null,
                $extraParameters
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            ResponseHandler::badRequest($this->module->l($exception->getMessage(), 'paymenthandler'));
        }
        // ------------------------------------

        // Modulo di pagamento non accettato
        ResponseHandler::badRequest($this->module->l('Payment method not found or inactive', 'paymenthandler'));
    }

    public function validateWebGateway($cartId)
    {
        $cart = new Cart((int) $cartId);
        if (!Validate::isLoadedObject($cart)) {
            ResponseHandler::notFound($this->module->l('Cart not found', 'paymenthandler'));
        }

        $orderId = (int) Order::getOrderByCartId((int) $cartId);
        $order = new Order($orderId);
        if ($orderId == 0 || !Validate::isLoadedObject($order)) {
            ResponseHandler::notFound($this->module->l('Order not found', 'paymenthandler'));
        }

        $this->cartHandler->generateNewCartFromCustomer($cart->id_customer);

        ResponseHandler::success($order);
    }

    public function postPayment($customerId, $paymentMethodName, $transactionId)
    {
        $manager = $this->getPaymentManager($customerId, $paymentMethodName);
        $customer = new Customer((int) $customerId);

        $params = [
            'customer' => $customer,
            'transaction_id' => $transactionId,
        ];

        $manager->handlePostPayment($params);
    }

    private function response($type, $params)
    {
        switch ($type) {
            case PaymentManager::$paymentTypes['TYPE_SDK']:
                ResponseHandler::success($this->sdkResponse($params));

                break;

            case PaymentManager::$paymentTypes['TYPE_WEB']:
                ResponseHandler::success($this->webResponse($params));

                break;

            case PaymentManager::$paymentTypes['TYPE_PAYMENT_DELAYED']:
                ResponseHandler::success($this->paymentDelayedResponse($params));

                break;
        }
    }

    private function sdkResponse($params)
    {
        $data = array_merge([
           'type'    => PaymentManager::$paymentTypes['TYPE_SDK'],
       ], $params);

        return array_merge($this->getDataResponse(), $data);
    }

    private function validateUrlsContent($urls)
    {
        return empty($urls) ? [] : (
            ApiUtils::isArray($urls) ? $urls : [$urls]
        );
    }

    private function webResponse($params)
    {
        $values = [
            'type' => PaymentManager::$paymentTypes['TYPE_WEB'],
            'gateway' => $params['gateway'],
            'web_method' => $params['method'],
            'web_body' => $params['body'],
            'return_url_success' => $this->validateUrlsContent($params['returnUrlSuccess']),
            'return_url_failure' => $this->validateUrlsContent($params['returnUrlFailure']),
            'return_url_cancel' => isset($params['returnUrlCancel']) ? $this->validateUrlsContent($params['returnUrlCancel']) : [],
            'amount' => $params['amount'],
        ];

        return array_merge($this->getDataResponse(), $values);
    }

    private function getDataResponse()
    {
        return [
            'type' => PaymentManager::$paymentTypes['TYPE_UNKNOWN'],
            'gateway' => '',
            'web_method' => '',
            'web_body' => '',
            'return_url_success' => [],
            'return_url_failure' => [],
            'return_url_cancel' => [],
            'text' => '',
            'reference' => '',
            'currency_code' => $this->context->currency->iso_code,
        ];
    }

    private function paymentDelayedResponse($params)
    {
        return array_merge($this->getDataResponse(), [
            'type' => PaymentManager::$paymentTypes['TYPE_PAYMENT_DELAYED'],
            'text' => $params['text'],
            'reference' => $params['reference'],
            'amount' => array_key_exists('amount', $params) ? $params['amount'] : null,
        ]);
    }

    private function validateCartObjects($customer, $billingAddress, $shippingAddress)
    {
        $missingObjects = [];

        if (!Validate::isLoadedObject($customer)) {
            $missingObjects[] = $this->module->l('Customer', 'paymenthandler');
        }

        if (!Validate::isLoadedObject($billingAddress)) {
            $missingObjects[] = $this->module->l('Billing Address', 'paymenthandler');
        }

        if (!Validate::isLoadedObject($shippingAddress)) {
            $missingObjects[] = $this->module->l('Shipping Address', 'paymenthandler');
        }

        if (count($missingObjects) > 0) {
            ResponseHandler::badRequest($this->module->l('The following cart parameters are not correct', 'paymenthandler').': '.implode(', ', $missingObjects));
        }
    }

    /**
     * Only used for OrderWS.php
     *
     * @param $moduleName
     * @param $customerId
     * @param null $cartId
     *
     * @return array|null
     */
    public function getSinglePaymentMethod($moduleName)
    {
        $manager = $this->getPaymentMethodManager($moduleName);

        return isset($manager)
            ? $manager->getOrderStructure()
            : null;
    }
    
    public function getPaymentMethodManager($moduleName)
    {
        if (empty($this->modules[$moduleName])) {
            return null;
        }

        /** @var PaymentManager $manager */
        $manager = new $this->modules[$moduleName]($moduleName, $this->dataHandler);

        return $manager->getModuleInstance()
                ? $manager
                : null;
    }
}
