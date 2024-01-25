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

namespace MrAPPs\MrShopApi\Handler\Payment\Web;

use Configuration;
use Context;
use Module;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrShopApi;
use Tools;

class AmazonPayManager extends PaymentManager
{
    const REDIRECT_ROUTE = 'mobileamazonpayment';

    const CANCEL_ROUTE   = 'mobileamazonpaycancel';

    const ADDRESS_ROUTE  = 'mobileamazonpayaddress';

    const MODE_PARAM     = 'mode';

    const REVIEW_MODE    = 'review';

    const CONTINUE_MODE  = 'continue';
    
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
 
        $this->description = $module->l('Pay securely wtih your Amazon Account.', 'amazonpaymanager');
    }
    
    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with AmazonPay', 'amazonpaymanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        return parent::isValidPaymentMethod($cart) && (is_null($cart) || $cart->nbProducts() > 0) && static::isAmazonPayActive() && $this->dataHandler->isApiVersionMin(MrShopApi::AMAZONPAY_MIN_VERSION);
    }
    
    public static function isAmazonPayActive()
    {
        if (false === Module::isEnabled('amazonpay')) {
            return false;
        }
        
        if (Configuration::get('AMAZONPAY_MERCHANT_ID') == '') {
            return false;
        }
        if (Configuration::get('AMAZONPAY_PUBLIC_KEY_ID') == '') {
            return false;
        }
        if (Configuration::get('AMAZONPAY_STORE_ID') == '') {
            return false;
        }
        $config = self::getAmazonPayConfig();
        $key_spec = $config['private_key'];
        if ((strpos($key_spec, 'BEGIN RSA PRIVATE KEY') === false) && (strpos($key_spec, 'BEGIN PRIVATE KEY') === false)) {
            if (!file_exists($key_spec)) {
                return false;
            }
            $contents = Tools::file_get_contents($key_spec);
            if ($contents === false) {
                return false;
            }
        }

        return true;
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = !Configuration::get('AMAZONPAY_LIVEMODE');

        return $structure;
    }
    
    public static function getAmazonPayConfig()
    {
        return call_user_func('AmazonPayHelper::getAmazonPayConfig');
    }
    
    /**
     * URL of the address page
     * @return string
     */
    public static function getAddressUrl()
    {
        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, self::ADDRESS_ROUTE, [], true);
    }
    
    /**
     * URL of the pay method page
     * @return string
     */
    public static function getRedirectUrl()
    {
        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, self::REDIRECT_ROUTE, [], true);
    }
    
    /**
     * URL of the review page, that's the page where the user gets redirected after
     * he has choose the delivery address and the paying card from amazon
     * @return string
     */
    public static function getReviewUrl()
    {
        $qs = [];
        $qs[self::MODE_PARAM] = self::REVIEW_MODE;

        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, self::REDIRECT_ROUTE, $qs, true);
    }
    
    /**
     * URL of the continue page, that's where the user is redirected after the site
     * has correctly registered the delivery address and billing address
     * @return string
     */
    public static function getContinueToAppUrl()
    {
        $qs = [];
        $qs[self::MODE_PARAM] = self::CONTINUE_MODE;

        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, self::REDIRECT_ROUTE, $qs, true);
    }
    
    /**
     * URL where the user is redirected if he goes back from the amazon pay site
     * @return string
     */
    public static function getCancelUrl()
    {
        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, self::CANCEL_ROUTE, [], true);
    }
    
    /**
     * URL that has to be opened in a web view to complete the payment
     * @return string
     */
    public static function getCompletePaymentUrl()
    {
        return Context::getContext()->link
                    ->getModuleLink('amazonpay', 'redirect');
    }
    
    /**
     * URL where the user is redirected when a payment error is occured
     * @return string
     */
    public static function getErrorUrl()
    {
        $name = ApiUtils::isPS16() ? 'order' : 'cart';

        return Context::getContext()->link->getPageLink($name);
    }
    
    /**
     * URL where the user is redirected when the payment is correctly completed
     * @return string
     */
    public static function getSuccessUrl()
    {
        return Context::getContext()->link->getPageLink('order-confirmation');
    }
    
    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        return [
            'method'             => 'GET',
            'gateway'            => self::getCompletePaymentUrl(),
            'body'               => null,
            'returnUrlSuccess'   => $this->getRegEx(self::getSuccessUrl()),
            'returnUrlFailure'   => $this->getRegEx(self::getErrorUrl()),
            'returnUrlCancel'    => $this->getRegEx(self::getCancelUrl()),
            'amount'             => $totalAmount
        ];
    }
    
    public static function preInitData($token)
    {
        return [
            'type'               => PaymentManager::$paymentTypes['TYPE_WEB'],
            'web_method'         => 'POST',
            'gateway'            => self::getRedirectUrl(),
            'web_body'           => http_build_query(['auth_token' => $token]),
            'return_url_cancel'  => [ApiUtils::composeRegEx(self::getCancelUrl())],
            'return_url_failure' => [ApiUtils::composeRegEx(self::getErrorUrl())],
            'return_url_success' => [ApiUtils::composeRegEx(self::getContinueToAppUrl())],
            'external_domains'   => ['/^([a-z]*\.)?amazon\.([a-z])+/i']
        ];
    }
}
