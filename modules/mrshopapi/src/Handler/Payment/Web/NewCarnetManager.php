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
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Order;
use Tools;
use Validate;

class NewCarnetManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay with credit card by CardNET', 'newcarnetmanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with CardNET', 'newcarnetmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($cart === null) {
            return true;
        }

        if (!parent::isValidPaymentMethod($cart)) {
            return false;
        }

        $operatingSystem = Tools::getValue('system');
        $systemVersion = Tools::getValue('system_version');

        // verifico che la versione di iOS sia maggiore di 10.x.x
        if ($operatingSystem && $operatingSystem === 'ios' && $systemVersion) {
            $systemVersionArray = explode('.', trim($systemVersion));

            return count($systemVersionArray) === 0 || (int) $systemVersionArray[0] > 10;
        }

        return true;
    }

    public function getStructure($customerId, $cartId = null)
    {
        $structure = parent::getStructure($customerId, $cartId);
        $structure['is_sandbox'] = Configuration::get('CARNET_PAYMENT_GATEWAY') != 2;

        return $structure;
    }

    public function getClientIp()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        if ((int) Configuration::get('CARNET_PAYMENT_GATEWAY') == 2) {
            // Production
            $merchantName = Configuration::get('CARNET_PAYMENT_MNA');
            $merchantNumber = Configuration::get('CARNET_PAYMENT_MN');
            $merchantTerminal = Configuration::get('CARNET_PAYMENT_MT');
            $merchantType = Configuration::get('CARNET_PAYMENT_MTP');
            $paymentUrl = Configuration::get('CARNET_PAYMENT_URL');
        } else {
            // Sandbox
            $merchantName = Configuration::get('CARNET_PAYMENT_SANDBOX_MNA');
            $merchantNumber = Configuration::get('CARNET_PAYMENT_SANDBOX_MN');
            $merchantTerminal = Configuration::get('CARNET_PAYMENT_SANDBOX_MT');
            $merchantType = Configuration::get('CARNET_PAYMENT_SANDBOX_MTP');
            $paymentUrl = Configuration::get('CARNET_PAYMENT_SANDBOX_URL');
        }

        $total = str_replace('.', '', number_format($totalAmount, 2));
        $total = str_replace(',', '', $total);

        $baseReturnUrl = ApiUtils::getFullDomain().'client_mrshop/cart/validate_newcarnet/';

        $returnUrlProcessing = $baseReturnUrl.'processing';
        $returnUrlCancel = $baseReturnUrl.'cancel';
        $returnUrlSuccess = $baseReturnUrl.'success';
        $returnUrlFailure = $baseReturnUrl.'failure';

        return [
            'method' => 'POST',
            'gateway' => $paymentUrl,
            'body' => http_build_query([
                'TransactionType' => '0200',
                'CurrencyCode' => '214',
                'AcquiringInstitutionCode' => '349',
                'MerchantType' => $merchantType,
                'MerchantNumber' => $merchantNumber,
                'MerchantTerminal' => $merchantTerminal,
                'ReturnUrl' => $returnUrlProcessing,
                'CancelUrl' => $returnUrlCancel,
                'PageLanguaje' => 'ESP',
                'OrdenId' => str_pad($cart->id, 6, '0', STR_PAD_LEFT),
                'TransactionId' => '000001',
                'Amount' => $total,
                'Tax' => '000000000000',
                'MerchantName' => $merchantName,
                'KeyEncriptionKey' => '1fc500b127d04b532f759d183944b057',
                'Ipclient' => $this->getClientIp(),
                'loteid' => '001',
                'seqid' => '009',
                'currency_payement' => $currency->id,
            ]),
            'returnUrlSuccess' => $this->getRegEx($returnUrlSuccess),
            'returnUrlFailure' => $this->getRegEx($returnUrlFailure),
            'returnUrlCancel' => $this->getRegEx($returnUrlCancel),
            'amount' => $totalAmount,
        ];
    }

    public function handleValidatePaymentS2S($cart, $note)
    {
        $baseRedirectUrl = ApiUtils::getFullDomain().'client_mrshop/cart/validate_newcarnet/';
        $redirectUrlSuccess = $baseRedirectUrl.'success';
        $redirectUrlFailure = $baseRedirectUrl.'failure';

        try {
            if (!Validate::isLoadedObject($cart)) {
                error_log('Cart not found');
                Tools::redirect($redirectUrlFailure);
            }
            $customer = new Customer($cart->id_customer);
            if (!Validate::isLoadedObject($customer)) {
                error_log('Customer not found');
                Tools::redirect($redirectUrlFailure);
            }

            $total = (float) $cart->getOrderTotal(true, Cart::BOTH);

            $params = Tools::getAllValues();

            if ($params['ResponseCode'] == '00') {
                // If payment was approved
                $this->moduleInstance->validateOrder(
                    (int) $cart->id,
                    Configuration::get('PS_OS_PAYMENT'),
                    $total,
                    'Tarjeta',
                    $note,
                    null,
                    (int) $cart->id_currency,
                    false,
                    $customer->secure_key
                );

                $order = new Order(Order::getOrderByCartId($cart->id));
                if (empty($order->id)) {
                    error_log('Order not found');
                    Tools::redirect($redirectUrlFailure);
                } else {
                    $this->cartHandler->generateNewCartFromCustomer($cart->id_customer);
                    Tools::redirect($redirectUrlSuccess);
                }
            } else {
                $message = '';

                switch ($params['ResponseCode']) {
                    case '51':
                        $message = 'Su tarjeta ha sido rechazada por fondos insuficientes.';

                        break;
                    case '39':
                        $message = 'Tarjeta Invalida. Por favor verifique la información proporsionada e intente nuevamente.';

                        break;
                    case '03':
                        $message = 'Comercio Inválido. Por favor contactar al administrador de la tienda.';

                        break;
                }

                if (Tools::strlen($message) == 0) {
                    $message = sprintf(
                        '%s (ResponseCode: %s)',
                        $this->getApiModule()->l('Payment Error', 'newcarnetmanager'),
                        $params['ResponseCode']
                    );
                }

                error_log($message);
                Tools::redirect($redirectUrlFailure);
            }
        } catch (\Exception $e) {
            error_log($e);
            Tools::redirect($redirectUrlFailure);
        }
    }
}
