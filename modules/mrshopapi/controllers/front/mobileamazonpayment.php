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
require_once(_PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php');

use MrAPPs\MrShopApi\Handler\Api\JwtHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Handler\Payment\Web\AmazonPayManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class mrshopapimobileamazonpaymentModuleFrontController extends mrshopapibaseModuleFrontController
{
    public $ssl = true;

    const ACQUIRE_MODE  = 'acquire';

    public function initContent()
    {
        if (false == AmazonPayManager::isAmazonPayActive()) {
            throw new \Exception('Amazon Pay V2 module is not active yet or configured');
        }

        $this->ajax = false;

        $jwt = $this->requestHandler->getJwtHandler();

        /*
         * A mode could be provided, this is a way to prevent too much controllers
         * for a single purpose
         */
        if (false == empty(Tools::getValue(AmazonPayManager::MODE_PARAM))) {
            switch (Tools::getValue(AmazonPayManager::MODE_PARAM)) {
                case self::ACQUIRE_MODE:
                    /*
                     * should be an ajax call that triggers the address acquisition.
                     * Is ment to be an ajax call to show a loader while the acquisition is
                     * in progress
                     */
                    $this->acquireAmazonPayAddresses();

                    break;
                case AmazonPayManager::REVIEW_MODE:
                    /*
                     * This is the page that triggers the ajax call to acquire
                     * addresses wihle shows a loader
                     */
                    $this->showAddressAquirePage();

                    break;
                case AmazonPayManager::CONTINUE_MODE:
                    /*
                     * Page where the client is redirected after the addresses are sussessfully
                     * acquired
                     */
                    $this->showContinuePage();

                    break;
            }
            /*
             * If a mode is not provided but the request has a token, is intended as an init call
             */
        } elseif ($jwt->hasToken()) {
            $this->initAmazonPaySession();
            /*
             * Otherwise is intended as an error
             */
        } else {
            Tools::redirect(AmazonPayManager::getBackUrl());
        }
    }

    /**
     * URL of the continue page, that's where the user is redirected after the site
     * has correctly registered the delivery address and the paying card. A page
     * with a loader is displayed
     * @return string
     */
    public static function getAcquireUrl()
    {
        $qs = [];
        $qs[AmazonPayManager::MODE_PARAM] = self::ACQUIRE_MODE;

        return Context::getContext()->link
                ->getModuleLink(ApiUtils::MODULE_NAME, basename(__FILE__, '.php'), $qs, true);
    }

    protected function initAmazonPaySession()
    {
        $jwt = $this->requestHandler->getJwtHandler();
        $customerId = $jwt->getCustomerId();
        $paymentHandler = new PaymentHandler($this->dataHandler);
        $cart = $paymentHandler->getCustomerCart($customerId);

        Context::getContext()->cart = $cart;

        ApiUtils::startCustomerSession($customerId);
        $this->renderInitView();
    }

    protected function showContinuePage()
    {
        $data = [
            'mode' => 2
        ];

        Context::getContext()->cookie->force_cookie_write = rand(0, 200);
        Context::getContext()->cookie->write();
        $this->context->smarty->assign(array_merge($data, $this->getCommonData()));
        $this->setTemplateRelativePath('amazonpay_init.tpl');
    }

    protected function showAddressAquirePage()
    {
        $data = [
            'ajax_url'    => self::getAcquireUrl(),
            'success_url' => AmazonPayManager::getContinueToAppUrl(),
            'error_url'   => AmazonPayManager::getErrorUrl(),
            'address_url' => AmazonPayManager::getAddressUrl(),
            'mode'        => 1
        ];

        $this->context->smarty->assign(array_merge($data, $this->getCommonData()));
        $this->setTemplateRelativePath('amazonpay_init.tpl');
    }

    protected function acquireAmazonPayAddresses()
    {
        try {
            $data = $this->dataHandler->getBodyParams();
            if (empty($data['amazonCheckoutSessionId'])) {
                ResponseHandler::badRequest('amazonCheckoutSessionId is required');
            }

            $amazonCheckoutSessionId = $data['amazonCheckoutSessionId'];

            // work around to avoid prestashp validation error on inexistent class
            $sessionClass = 'AmazonPayCheckoutSession';

            /** @var AmazonPayCheckoutSession $amazonPayCheckoutSession */
            $amazonPayCheckoutSession = new $sessionClass(false, $amazonCheckoutSessionId);

            if ($amazonPayCheckoutSession->isOpen()) {
                $amazonPayCheckoutSession->saveSession();
            }

            $amazonPayCheckoutSession = new $sessionClass(false);
            if (!$amazonPayCheckoutSession->checkStatus()) {
                ResponseHandler::notFound('Not found session');

                return;
            }

            $coData = $amazonPayCheckoutSession->assocReturn();
            $customer = new Customer((int) $this->context->customer->id);

            // ---- PERSISTING SHIPPING ADDRESS
            // save the old delivery address id

            $old_delivery_address_id = Context::getContext()->cart->id_address_delivery;

            $amazon_address_to_use_for_shipping = $coData['shippingAddress'];
            if ($coData['shippingAddress'] == '' && Context::getContext()->cart->isVirtualCart()) {
                $amazon_address_to_use_for_shipping = $coData['billingAddress'];
            }

            // create a new address only if is not already present in the db
            $address_delivery = $this->findAddressByAmazonOrderReferenceIdOrNew(
                Context::getContext()->cookie->amazon_pay_checkout_session_id,
                $amazon_address_to_use_for_shipping,
                $customer->id,
                false
            );

            try {
                // fill the db address with data from amazon
                $address_delivery->processFromArray($amazon_address_to_use_for_shipping);
                $address_delivery->save();
            } catch (Exception $ex) {
                ResponseHandler::response(412);
            }

            // saves the used address in the db
            $this->saveAddressAmazonReference(
                $address_delivery,
                Context::getContext()->cookie->amazon_pay_checkout_session_id,
                $customer->id,
                $amazon_address_to_use_for_shipping
            );
            // --- END OF PERSISTING SHIPPING ADDRESS

            // --- PERSISTING INVOICE ADDRESS
            if (is_null($coData['billingAddress'])) {
                if (Context::getContext()->cart->id_address_delivery !== Context::getContext()->cart->id_address_invoice) {
                    $address_invoice = new Address(Context::getContext()->cart->id_address_invoice);
                } else {
                    $address_invoice = $address_delivery;
                }
            } else {
                $address_invoice = $this->findAddressByAmazonOrderReferenceIdOrNew(
                    Context::getContext()->cookie->amazon_pay_checkout_session_id.'-invoice',
                    $coData['billingAddress'],
                    $customer->id,
                    false
                );
                $address_invoice->processFromArray($coData['billingAddress']);

                try {
                    $address_invoice->save();
                } catch (\Exception $e) {
                    $fields_to_set = call_user_func('AmazonPayAddress::fetchInvalidInput', $address_invoice);
                    foreach ($fields_to_set as $field_to_set) {
                        $address_invoice->$field_to_set = isset($address_delivery->$field_to_set) ? $address_delivery->$field_to_set : '';
                    }
                    $address_invoice->save();
                }
            }

            $this->saveAddressAmazonReference(
                $address_invoice,
                Context::getContext()->cookie->amazon_pay_checkout_session_id.'-invoice',
                $customer->id,
                $coData['billingAddress']
            );
            // --- END OF PERSISTING INVOICE ADDRESS

            // ending

            Context::getContext()->cart->id_address_delivery = $address_delivery->id;
            Context::getContext()->cart->id_address_invoice = $address_invoice->id;
            Context::getContext()->cart->updateAddressId($old_delivery_address_id, $address_delivery->id);
            Context::getContext()->cart->save();

            CartRule::autoRemoveFromCart(Context::getContext());
            CartRule::autoAddToCart(Context::getContext());
            Context::getContext()->cookie->write();
            ResponseHandler::success();
        } catch (Exception $ex) {
            ResponseHandler::error();
        }
    }

    protected function getJsDefs()
    {
        $paysign = call_user_func('AmazonPayHelper::getButtonPayloadAndSignature');

        $payload = json_decode($paysign['payload']);
        $payload->webCheckoutDetails->checkoutReviewReturnUrl = AmazonPayManager::getReviewUrl();

        $client = call_user_func('AmazonPayHelper::getClient');
        $signature = $client->generateButtonSignature(json_encode($payload));

        $def = [
            'merchant_id' => Configuration::get('AMAZONPAY_MERCHANT_ID'),
            'public_key_id' => Configuration::get('AMAZONPAY_PUBLIC_KEY_ID'),
            'amazonCheckoutSessionId' => false,
            'sandbox' => Configuration::get('AMAZONPAY_LIVEMODE') ? false : true,
            'ledgerCurrency' => call_user_func('AmazonPayHelper::getLedgerCurrency'),
            'checkoutType' => Context::getContext()->cart->isVirtualCart() ? 'PayOnly' : 'PayAndShip',
            'checkoutLanguage' => call_user_func('AmazonPayHelper::getCheckoutLanguage'),
            'button_payload' => $payload,
            'button_signature' => $signature
        ];

        return $def;
    }

    protected function renderInitView()
    {
        $data = [
            'amazonpay' => $this->getJsDefs(),
            'sdkUrl'    => call_user_func('AmazonPayHelper::getCheckoutJSURL'),
            'mode'      => 0
        ];

        $this->context->smarty->assign(array_merge($data, $this->getCommonData()));
        $this->setTemplateRelativePath('amazonpay_init.tpl');
    }

    protected function getCommonData()
    {
        $path = 'modules/mrshopapi/views';
        $assetPath = ApiUtils::getFullUrl($path);

        return [
            'style'        => $assetPath.'/css/front.css',
            'templatePath' => $this->getTemplateFullPath()
        ];
    }

    protected function findAddressByAmazonOrderReferenceIdOrNew($orderref, $amazon_address, $id_customer, $boolean = false)
    {
        return call_user_func('AmazonPayAddress::findByAmazonOrderReferenceIdOrNew', $orderref, $amazon_address, $id_customer, $boolean);
    }

    protected function saveAddressAmazonReference(Address $address, $orderref, $id_customer, $amazon_address = false)
    {
        return call_user_func('AmazonPayAddress::saveAddressAmazonReference', $address, $orderref, $id_customer, $amazon_address);
    }
}
