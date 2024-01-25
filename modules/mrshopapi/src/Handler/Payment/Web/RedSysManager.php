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

use Address;
use Configuration;
use Context;
use Country;
use Currency;
use Customer;
use Language;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Order;
use Tools;
use Validate;

class RedSysManager extends PaymentManager
{
    private static $ENVIROMENT_URL = [
        'https://sis.redsys.es/sis/realizarPago/utf-8',
        'https://sis-t.redsys.es:25443/sis/realizarPago/utf-8',
        'https://sis-i.redsys.es:25443/sis/realizarPago/utf-8',
        'https://sis-d.redsys.es/sis/realizarPago/utf-8',
    ];

    private static $POS_LANG = [
        'es' => '001',
        'en' => '002',
        'ca' => '003',
        'fr' => '004',
        'de' => '005',
        'nl' => '006',
        'it' => '007',
        'sv' => '008',
        'pt' => '009',
        'pl' => '011',
        'gl' => '012',
        'eu' => '013',
    ];

    private static $ERROR_CODES_START = 101;

    private static $STATUS_CODES = [
        '0000' => 'Payment completed',
        '0101' => 'Expired card',
        '0102' => 'Card in transitory exception or under suspicion of fraud',
        '0106' => 'PIN Attempts Exceeded',
        '0125' => 'Non-effective card',
        '0129' => 'Security code (CVV2 / CVC2) incorrect',
        '0180' => 'Foreign service card',
        '0184' => 'Holder Authentication Error',
        '0190' => 'Refusal of the issuer without specifying reason',
        '0191' => 'Wrong Expiration Date',
        '0202' => 'Card in transitory exception or on suspicion of fraud with card withdrawal',
        '0904' => 'Commerce not registered in FUC',
        '0909' => 'System error',
        '0913' => 'Repeated order',
        '0944' => 'Incorrect Session',
        '0950' => 'Return operation not allowed',
        '0912' => 'Issuer not available',
        '9064' => 'Number of incorrect card positions',
        '9078' => 'Type of operation not allowed for that card',
        '9093' => 'Non-existent card',
        '9094' => 'Reject international servers',
        '9104' => 'Trade with “secure holder” and holder without secure purchase key',
        '9218' => 'The trade does not allow op. safe per entry / operations',
        '9253' => 'Card does not comply with the check-digit',
        '9256' => 'The merchant cannot preauthorize',
        '9257' => 'This card does not allow preauthorization operations',
        '9261' => 'Operation stopped for overcoming the control of restrictions at the entrance to the SIS',
        '9912' => 'Issuer not available',
        '9913' => 'Error in the confirmation that the merchant sends to the Virtual POS (only applicable in the SOAP synchronization option)',
        '9914' => '“KO” confirmation of the merchant (only applicable in the SOAP synchronization option)',
        '9915' => 'At the request of the user the payment has been canceled',
        '9928' => 'Cancellation of deferred authorization by the SIS (batch process)',
        '9929' => 'Cancellation of deferred authorization made by the merchant',
        '9997' => 'Another transaction is being processed in SIS with the same card',
        '9998' => 'Operation in process of requesting card data',
        '9999' => 'Operation that has been redirected to the issuer to authenticate',
    ];

    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];
        $module = $this->getApiModule();
        $this->description = $module->l('Pay with credit card by RedSys', 'redsysmanager');
        if (parent::isValidPaymentMethod()) {
            require_once _PS_MODULE_DIR_.'redsysoficial/apiRedsys/redsysLibrary.php';
        }
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('RedSys', 'redsysmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($cart === null) {
            return true;
        }

        return parent::isValidPaymentMethod($cart) &&
            Tools::strlen(trim(Configuration::get('REDSYS_NOMBRE'))) > 0 &&
            Tools::strlen(trim(Configuration::get('REDSYS_CODIGO'))) > 0 &&
            Tools::strlen(trim(Configuration::get('REDSYS_TERMINAL'))) > 0 &&
            Tools::strlen(trim(Configuration::get('REDSYS_CLAVE256'))) > 0;
    }

    public function getConfiguration()
    {
        if (!$this->moduleInstance) {
            return null;
        }

        // payment_type -> " ":todos "C":Solo con Tarjeta "T":Tarjeta y Iupay
        return [
            /* configuration */
            'enviroment' => (int) Configuration::get('REDSYS_URLTPV'),
            'shop_name' => Configuration::get('REDSYS_NOMBRE'),
            'fuc_code' => (int) Configuration::get('REDSYS_CODIGO'),
            'payment_type' => Configuration::get('REDSYS_TIPOPAGO'),
            'secret_key' => Configuration::get('REDSYS_CLAVE256'),
            'terminal_number' => (int) Configuration::get('REDSYS_TERMINAL'),
            'transaction_type' => (int) Configuration::get('REDSYS_TRANS', null, null, null, 0),
            /* customize */
            'order_status' => (int) Configuration::get('REDSYS_ESTADO_PEDIDO'),
            'payment_retry' => Configuration::get('REDSYS_ERROR_PAGO') === 'no' ? false : true,
            'log' => Configuration::get('REDSYS_LOG') === 'no' ? false : true,
            'pos_lang' => Configuration::get('REDSYS_IDIOMAS_ESTADO') === 'no' ? false : true,
        ];
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = !((int) Configuration::get('REDSYS_URLTPV') === 1);

        return $structure;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $config = $this->getConfiguration();

        /* payment url */
        $paymentUrl = self::$ENVIROMENT_URL[$config['enviroment'] - 1];

        /* pos lang */
        if ($config['pos_lang']) {
            $idLang = Tools::strtolower($this->context->language->iso_code);
            if ($idLang && isset($idLang,self::$POS_LANG)) {
                $lang = self::$POS_LANG[$idLang];
            } else {
                $lang = self::$POS_LANG['en'];
            }
        } else {
            $lang = self::$POS_LANG['en'];
        }

        /* order number */
        $orderNumber = str_pad($cart->id.'z'.-1 .time() % 1000, 12, '0', STR_PAD_LEFT);
        call_user_func('escribirLog', " - ORDER NUMBER: Internal order number: '".$cart->id."'. Order number sent to REDSYS: '".$orderNumber."'", $config['log']);

        /* cart products */
        $cartProducts = $cart->getProducts();
        $products = '';
        foreach ($cartProducts as $cartProduct) {
            $products .= $cartProduct['quantity'].' '.Tools::truncate($cartProduct['name'], 50).' ';
        }
        $products = str_replace('%', '&#37;', $products);

        /* url */
        $baseReturnUrl = ApiUtils::getFullDomain().'client_mrshop/cart/validate_redsys/';
        $returnUrlSuccess = $baseReturnUrl.'success';
        $returnUrlFailure = $baseReturnUrl.'failure';
        $returnUrlProcessing = $baseReturnUrl.'processing';

        /* total */
        $total = (int) ($totalAmount * 100);

        /* currency */
        $currency = new Currency($this->dataHandler->getCurrencyId());
        $currency = $currency->iso_code_num;

        /* build params */
        $rs = new \RedsysAPI();
        $rs->setParameter('DS_MERCHANT_AMOUNT', $total);
        $rs->setParameter('DS_MERCHANT_ORDER', (string) $orderNumber);
        $rs->setParameter('DS_MERCHANT_MERCHANTCODE', $config['fuc_code']);
        $rs->setParameter('DS_MERCHANT_CURRENCY', $currency);
        $rs->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $config['transaction_type']);
        $rs->setParameter('DS_MERCHANT_TERMINAL', $config['terminal_number']);
        $rs->setParameter('DS_MERCHANT_MERCHANTURL', $returnUrlProcessing);
        $rs->setParameter('DS_MERCHANT_URLOK', $returnUrlSuccess);
        $rs->setParameter('DS_MERCHANT_URLKO', $returnUrlFailure);
        $rs->setParameter('Ds_Merchant_ConsumerLanguage', $lang);
        $rs->setParameter('Ds_Merchant_ProductDescription', $products);
        $rs->setParameter('Ds_Merchant_Titular', $customer->firstname.' '.$customer->lastname);
        $rs->setParameter('Ds_Merchant_MerchantData', $note);
        $rs->setParameter('Ds_Merchant_MerchantName', $config['shop_name']);
        $rs->setParameter('Ds_Merchant_PayMethods', $config['payment_type']);
        $rs->setParameter('Ds_Merchant_Module', 'PR_redsys_'.$this->moduleInstance->version);

        $version2 = call_user_func('getVersionClave');
        $paramsBase64 = $rs->createMerchantParameters();
        $signatureMac = $rs->createMerchantSignature($config['secret_key']);

        return [
            'method' => 'POST',
            'gateway' => $paymentUrl,
            'body' => http_build_query([
                'Ds_SignatureVersion' => $version2,
                'Ds_MerchantParameters' => $paramsBase64,
                'Ds_Signature' => $signatureMac,
            ]),
            'returnUrlSuccess' => $this->getRegEx($this->context->link->getPageLink('order-confirmation')),
            'returnUrlFailure' => $this->getRegEx($this->context->link->getPageLink('order')),
            'amount' => $total,
        ];
    }

    public function handleValidatePayment($customer, $cart, $note, $extraParameters)
    {
        $config = $this->getConfiguration();
        $module = $this->getApiModule();
        $logActive = $config['log'];

        $merchantParams = $extraParameters['Ds_MerchantParameters'];
        $remoteSignature = $extraParameters['Ds_Signature'];
        $kc = $config['secret_key'];

        $rs = new \RedsysAPI();
        $rs->decodeMerchantParameters($merchantParams);
        $localSignature = $rs->createMerchantSignatureNotif($kc, $merchantParams);

        $note = str_replace('+', ' ', $rs->getParameter('Ds_MerchantData'));

        $status = $extraParameters['status'];
        if ($status === 'failure' || $status === 'cancel') {
            Tools::redirect($this->context->link->getPageLink('order'));
        } elseif ($status === 'success') {
            Tools::redirect($this->context->link->getPageLink('order-confirmation'));
        } else {
            try {
                $idLog = call_user_func('generateIdLog');
                call_user_func('escribirLog', $idLog.' -- '.'Order validation', $logActive);

                $total = $rs->getParameter('Ds_Amount');
                call_user_func('escribirLog', $idLog.' -- '.'Amount: '.$total, $logActive);

                $order = $rs->getParameter('Ds_Order');
                call_user_func('escribirLog', $idLog.' -- '.'Redsys Order: '.$order, $logActive);

                $order = (int) Tools::substr($order, 0, 11);
                call_user_func('escribirLog', $idLog.' -- '.'Prestashop Order: '.$order, $logActive);

                $remoteCode = $rs->getParameter('Ds_MerchantCode');
                call_user_func('escribirLog', $idLog.' -- '.'Cod: '.$remoteCode, $logActive);

                $currency = $rs->getParameter('Ds_Currency');

                $response = $rs->getParameter('Ds_Response');
                call_user_func('escribirLog', $idLog.' -- '.'Response: '.$response, $logActive);

                $idTrans = $rs->getParameter('Ds_AuthorisationCode');
                call_user_func('escribirLog', $idLog.' -- '.'ID trans: '.$idTrans, $logActive);
                $idTrans = str_replace('+', '', $idTrans);

                $code = $config['fuc_code'];
                $paymentRetry = $config['payment_retry'];

                if ($localSignature === $remoteSignature
                    && call_user_func('checkImporte', $total)
                    && call_user_func('checkPedidoNum', $order)
                    && call_user_func('checkFuc', $remoteCode)
                    && call_user_func('checkMoneda', $currency)
                    && call_user_func('checkRespuesta', $response)) {
                    $validCart = true;
                    if ($cart->id_customer == 0) {
                        call_user_func('escribirLog', $idLog.' -- '.'Validation cart error: empty customer', $logActive);
                        $validCart = false;
                    }
                    if ($cart->id_address_delivery == 0) {
                        call_user_func('escribirLog', $idLog.' -- '.'Validation cart error: empty shipping address', $logActive);
                        $validCart = false;
                    }
                    if ($cart->id_address_invoice == 0) {
                        call_user_func('escribirLog', $idLog.' -- '.'Validation cart error: empty billing address', $logActive);
                        $validCart = false;
                    }
                    if (!$this->moduleInstance->active) {
                        call_user_func('escribirLog', $idLog.' -- '.'Error: disabled module', $logActive);
                        $validCart = false;
                    }

                    if (!$validCart) {
                        Tools::redirect($this->context->link->getPageLink('order'));
                    }

                    $customer = new Customer((int) $cart->id_customer);

                    Context::getContext()->customer = $customer;
                    $address = new Address((int) $cart->id_address_invoice);
                    Context::getContext()->country = new Country((int) $address->id_country);
                    Context::getContext()->customer = new Customer((int) $cart->id_customer);
                    Context::getContext()->language = new Language((int) $cart->id_lang);
                    Context::getContext()->currency = new Currency((int) $cart->id_currency);

                    if (!Validate::isLoadedObject($customer)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Customer validation error', $logActive);
                        Tools::redirect($this->context->link->getPageLink('order'));
                    }

                    $currencyOrig = new Currency($cart->id_currency);
                    $currency_decimals = is_array($currencyOrig) ? (int) $currencyOrig['decimals'] : (int) $currencyOrig->decimals;

                    $currencyOrig = $currencyOrig->iso_code_num;
                    if ($currencyOrig == 0 || $currencyOrig == null) {
                        call_user_func('escribirLog', $idLog.' -- '.'Error loading currency, using the recovered currency', $logActive);
                        $currencyOrig = $currency;
                    }

                    $response = (int) $response;

                    if ($currencyOrig == $currency && (int) $code == (int) $remoteCode && $response < self::$ERROR_CODES_START && call_user_func('checkAutCode', $idTrans)) {
                        $mailvars = [];
                        $mailvars['transaction_id'] = (int) $idTrans;
                        $this->moduleInstance->validateOrder(
                            $cart->id,
                            $config['order_status'],
                            $total / 100,
                            $this->moduleInstance->displayName,
                            $note,
                            $mailvars,
                            (int) $cart->id_currency,
                            false,
                            $customer->secure_key
                        );

                        $order = new Order($this->moduleInstance->currentOrder);

                        $this->cartHandler->generateNewCartFromCustomer($customer->id);

                        call_user_func('escribirLog', $idLog.' -- '.'Order with cart '.$cart->id.' ('.$order->id.') is valid and has been successfully registered', $logActive);
                    } else {
                        if (!($currencyOrig == $currency)) {
                            call_user_func('escribirLog', $idLog.' -- '."The currency does not match ($currencyOrig : $currency)", $logActive);
                        }
                        if (!((int) $code == (int) $remoteCode)) {
                            call_user_func('escribirLog', $idLog.' -- '."Trade code does not match ($code : $remoteCode)", $logActive);
                        }
                        if (!call_user_func('checkAutCode', $idTrans)) {
                            call_user_func('escribirLog', $idLog.' -- '."Ds_AuthorisationCode invalid ($idTrans)", $logActive);
                        }
                        if (!$paymentRetry) {
                            /* se anota el pedido como no pagado * */
                            $this->moduleInstance->validateOrder(
                                $order,
                                _PS_OS_ERROR_,
                                0,
                                $this->moduleInstance->displayName,
                                $note.' ('.$module->l('error', 'redsysmanager').' '.$response.')'
                            );
                        }
                        call_user_func('escribirLog', $idLog.' -- '.'Order with id cart '.$order.' is invalid', $logActive);
                    }
                } else {
                    if (!($localSignature === $remoteSignature)) {
                        call_user_func('escribirLog', $idLog.' -- '.'The signature does not match', $logActive);
                    }
                    if (!call_user_func('checkImporte', $total)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Ds_Amount invalid', $logActive);
                    }
                    if (!call_user_func('checkPedidoNum', $order)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Ds_Order invalid', $logActive);
                    }
                    if (!call_user_func('checkFuc', $remoteCode)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Ds_MerchantCode invalid', $logActive);
                    }
                    if (!call_user_func('checkMoneda', $currency)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Ds_Currency invalid', $logActive);
                    }
                    if (!call_user_func('checkRespuesta', $response)) {
                        call_user_func('escribirLog', $idLog.' -- '.'Ds_Response invalid', $logActive);
                    }
                    if (!$paymentRetry) {
                        /* se anota el pedido como no pagado * */
                        $this->moduleInstance->validateOrder(
                            $order,
                            _PS_OS_ERROR_,
                            0,
                            $this->moduleInstance->displayName,
                            $note.' ('.$module->l('error', 'redsysmanager').' '.$response.')'
                        );
                    }
                    call_user_func('escribirLog', $idLog.' -- '.'Notification: Order with id cart '.$order.' is invalid', $logActive);
                }
            } catch (\Exception $e) {
                $idLogExc = call_user_func('generateIdLog');
                call_user_func('escribirLog', $idLogExc.' -- Validation exception: '.$e->getMessage(), $logActive);
                Tools::redirect($this->context->link->getPageLink('order'));
                // ResponseHandler::badRequest($e->getMessage());
            }
        }
    }
}
