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

use Cart;
use Context;
use Db;
use Module;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Service\PriceFormatter;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Validate;

abstract class PaymentManager
{
    public static $paymentTypes = [
        'TYPE_WEB' => 'web',
        'TYPE_SDK' => 'sdk',
        'TYPE_PAYMENT_DELAYED' => 'paymentdelayed',
        'TYPE_UNKNOWN' => 'unknown',
    ];

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    public $moduleName;

    /**
     * @var Module
     */
    protected $moduleInstance;

    /**
     * @var string
     */
    protected $paymentType;

    /**
     * @var DataHandler
     */
    protected $dataHandler;

    /**
     * @var CartHandler
     */
    protected $cartHandler = null;

    /**
     * @var PriceFormatter
     */
    protected $priceFormatter;

    /**
     * @var array
     */
    protected $extraParameters;

    protected $module;

    protected $context = null;

    protected $isHookModule = false;    

    /**
     * PaymentManager constructor.
     *
     * @param $moduleName
     * @param $dataHandler
     * @param $extraParameters
     *
     * Initialize the module instance and services
     */
    public function __construct($moduleName, $dataHandler)
    {
        $this->context = Context::getContext();
        $this->moduleName = $moduleName;
        $this->moduleInstance = Module::getInstanceByName($this->moduleName);
        $this->extraParameters = [];
        $this->dataHandler = $dataHandler;
        $this->priceFormatter = new PriceFormatter();
        $this->cartHandler = new CartHandler($this->dataHandler->getGuestId());
        $this->displayName = $this->getDisplayName();
    }

    abstract public function getDisplayName($langIsoCode = null);

    /**
     * Return the payment module instance
     *
     * @return Module
     */
    public function getModuleInstance()
    {
        return $this->moduleInstance;
    }

    /*
     * Return the payment type of the method (sdk, web, delayed)
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /*
     * Return the module name
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /*
     * Return the module name
     */
    public function getDisplayModuleName()
    {
        return $this->displayName." ($this->moduleName)";
    }

    /*
     * Validates the payment method, checks if module is active
     */
    public function isValidPaymentMethod($cart = null)
    {
        return $this->dataHandler->isModuleActive($this->moduleName);
    }

    /**
     * Return an array with:
     * - fee_amount : the fee amount
     * - cart_total_inc_fee : the cart total with fee included
     *
     * @param $customerId
     * @param $cartId
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getPaymentFeeData($customerId, $cartId)
    {
        $cart = (int) $cartId > 0 ? new Cart((int) $cartId) : null;
        if (!$cart || !Validate::isLoadedObject($cart)) {
            $cart = $this->cartHandler->getCart($customerId, false);
        }
        if ($cart) {
            // Total WITHOUT fee
            $total = $this->cartHandler->getCartTotals($cart, true, false, $this->moduleName);

            return [
                'fee_amount' => $total['fee_amount'],
                'cart_total_inc_fee' => $total['total_inc_fee'],
            ];
        }

        return [
            'fee_amount' => 0,
            'cart_total_inc_fee' => 0,
        ];
    }

    protected function getFeeDescription($feeAmount)
    {
        $module = $this->getApiModule();
        $feeTrans = $module->l('Fee', 'paymentmanager');

        return "\n".$feeTrans.': '.$this->priceFormatter->format(\Tools::ps_round($feeAmount, 2), $this->dataHandler->getCurrencyId());
    }

    /**
     * Return payment method structure for the getList() of payment methods
     *
     * @param $customerId
     * @param $cartId
     *
     * @return array|null
     */
    public function getStructure($customerId, $cartId)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $description = $this->description;
        $feeData = $this->getPaymentFeeData($customerId, $cartId);
        if ($feeData['fee_amount'] != 0) {
            $description .= $this->getFeeDescription($feeData['fee_amount']);
        }

        return [
            'id' => (int) ($this->moduleInstance->id),
            'version' => $this->moduleInstance->version,
            'name' => $this->moduleName,
            'display_name' => $this->displayName,
            'description' => $description,
            'logo' => $this->getModuleLogo(),
            'is_sandbox' => false,
            'type' => $this->paymentType,
            'cart_total_inc_fee' => $this->priceFormatter->format($feeData['cart_total_inc_fee'], $this->dataHandler->getCurrencyId()),
            'cart_total_inc_fee_val' => $this->priceFormatter->getPriceData($feeData['cart_total_inc_fee'], $this->dataHandler->getCurrencyId())
        ];
    }

    public function getWebPaymentForm($cart)
    {
        return null;
    }

    public function getOrderStructure()
    {
        if (!$this->moduleInstance) {
            return null;
        }

        return [
            'id' => (int) ($this->moduleInstance->id),
            'version' => $this->moduleInstance->version,
            'name' => $this->moduleName,
            'display_name' => $this->displayName,
            'description' => $this->description,
            'logo' => $this->getModuleLogo(),
            'type' => $this->paymentType,
        ];
    }

    /*
     * Return the logo of the module
     *
     * @return string
     */
    public function getModuleLogo()
    {
        $baseModuleUrl = ApiUtils::getFullDomain().'modules/'.$this->moduleName.'/';
        $baseModuleDir = _PS_MODULE_DIR_.$this->moduleName.'/';
        $logoPng = 'logo.png';
        $logoJpg = 'logo.jpg';
        $logoJpeg = 'logo.jpeg';
        $logoGif = 'logo.gif';

        $logo = '';
        if (file_exists($baseModuleDir.$logoPng)) {
            $logo = $baseModuleUrl.$logoPng;
        } elseif (file_exists($baseModuleDir.$logoJpg)) {
            $logo = $baseModuleUrl.$logoJpg;
        } elseif (file_exists($baseModuleDir.$logoJpeg)) {
            $logo = $baseModuleUrl.$logoJpeg;
        } elseif (file_exists($baseModuleDir.$logoGif)) {
            $logo = $baseModuleUrl.$logoGif;
        }

        return $logo;
    }

    /**
     * Handle init payment method
     *
     * @param $customer
     * @param \Cart $cart
     * @param $note
     * @param $shop
     * @param $currency
     * @param $totalAmount
     *
     * @return array
     */
    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        ResponseHandler::badRequest('Method not supported');
    }

    /**
     * Handle validatePayment method
     *
     * @param $customer
     * @param $cart
     * @param string $note
     * @param array $extra
     */
    public function handleValidatePayment($customer, $cart, $note, $extra)
    {
        ResponseHandler::badRequest('Method not supported');
    }

    /**
     * Handle confirmPayment method
     *
     * @param $customer
     * @param $cart
     * @param string $note
     * @param array $extra
     */
    public function handleConfirmPayment($customer, $cart, $note, $extra)
    {
        ResponseHandler::badRequest('Method not supported');
    }

    /**
     * Handle validatePaymentS2S method
     *
     * @param $cart
     * @param string $note
     */
    public function handleValidatePaymentS2S($cart, $note)
    {
        ResponseHandler::badRequest('Method not supported');
    }

    /**
     * Handle post payment method
     */
    public function handlePostPayment($params)
    {
        ResponseHandler::badRequest('Method not supported');
    }

    protected function getRegEx($pattern)
    {
        return ApiUtils::composeRegEx($pattern);
    }

    /**
     * Set the extra parameters
     *
     * @return PaymentManager
     */
    public function setExtraParameters($extraParameters = [])
    {
        if (is_array($extraParameters)) {
            $this->extraParameters = $extraParameters;
        }

        return $this;
    }

    public function getApiModule()
    {
        return ApiUtils::getModule();
    }

    /**
     * @param $cart_id
     * @param $order_id
     *
     * @return bool
     */
    public function setOrderIdForCustomFields($cart_id, $order_id)
    {
        if (!Module::isEnabled('customfields')) {
            return false;
        }

        if (!$cart_id || !$order_id) {
            return false;
        } else {
            return (bool) Db::getInstance()->update(
                'fmm_custom_userdata',
                ['id_order' => (int) $order_id],
                'id_cart = '.(int) $cart_id
            );
        }
    }

    public function setHookModule($isHookModule)
    {
        $this->isHookModule = $isHookModule;
    }

    public function getHookModule()
    {
        return $this->isHookModule;
    }
}
