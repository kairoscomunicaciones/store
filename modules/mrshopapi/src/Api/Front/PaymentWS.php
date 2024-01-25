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

namespace MrAPPs\MrShopApi\Api\Front;

use Cart;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\SatispayManager;
use PrestaShopLogger;
use Tools;

class PaymentWS extends BaseWS
{
    /**@var PaymentHandler $paymentHandler*/
    private $paymentHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->paymentHandler = new PaymentHandler($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getPaymentMethodList($customerId)
    {
        $result = $this->paymentHandler->getList($customerId);
        $this->response(true, null, $result);
    }

    public function getPaymentList($customerId)
    {
        return $this->paymentHandler->getList($customerId);
    }

    public function initPayment($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();
        $paymentMethod = array_key_exists('payment_method', $bodyParams)
            ? $bodyParams['payment_method']
            : Tools::getValue('payment_method');
        $note = array_key_exists('note', $bodyParams)
            ? $bodyParams['note']
            : Tools::getValue('note');
        $extra = array_key_exists('extra', $bodyParams)
            ? $bodyParams['extra']
            : Tools::getValue('extra');

        if (empty($extra)) {
            $extra = [];
        }

        if (strpos($paymentMethod, '?') > 0) {
            $paymentMethodData = explode('?', $paymentMethod);
            $paymentMethod = $paymentMethodData[0];
            parse_str($paymentMethodData[1], $parsedParams);
            $extra = array_merge($extra, $parsedParams);
        }

        $this->paymentHandler->initPayment($customerId, $paymentMethod, $note, $extra);
    }

    public function postPayment($customerId)
    {
        $this->paymentHandler->getList($customerId);

        $bodyParams = $this->dataHandler->getBodyParams();
        $paymentMethod = array_key_exists('payment_method', $bodyParams) ? $bodyParams['payment_method'] : Tools::getValue('payment_method');
        $transactionId = array_key_exists('transaction_id', $bodyParams) ? $bodyParams['transaction_id'] : Tools::getValue('transaction_id');

        $this->paymentHandler->postPayment($customerId, $paymentMethod, $transactionId);
    }

    public function validateCic()
    {
        $status = Tools::strtolower(trim(Tools::getValue('status')));
        $note = Tools::getValue('texte-libre');

        $extra = [
            'date' => Tools::getValue('date'),
            'amount' => Tools::getValue('montant'),
            'reference' => Tools::getValue('reference'),
            'return_code' => Tools::getValue('code-retour'),
            'cvx' => Tools::getValue('cvx'),
            'vld' => Tools::getValue('vld'),
            'brand' => Tools::getValue('brand'),
            'status3d' => Tools::getValue('status3ds'),
            'numauto' => Tools::getValue('numauto'),
            'motifrefus' => Tools::getValue('motifrefus'),
            'originecb' => Tools::getValue('originecb'),
            'bincb' => Tools::getValue('bincb'),
            'hpancb' => Tools::getValue('hpancb'),
            'ipclient' => Tools::getValue('ipclient'),
            'originetr' => Tools::getValue('originetr'),
            'veres' => Tools::getValue('veres'),
            'pares' => Tools::getValue('pares'),
            'mac' => Tools::getValue('MAC'),
            'note' => $note,
            'status' => $status,
        ];

        PrestaShopLogger::addLog('validateCIC: '.json_encode($extra), 2, 0, 'ORDER');

        $cart = new Cart((int) $extra['reference']);
        $this->paymentHandler->validatePayment((int) $cart->id_customer, 'sps2006_cmcic30', $note, $extra, false, false);
    }

    public function validateNewCarnet()
    {
        $status = Tools::strtolower(trim(Tools::getValue('status')));
        if ($status != 'processing') {
            exit($status);   // Success, Failure, Cancel
        }

        $bodyParams = $this->dataHandler->getBodyParams();
        $note = array_key_exists('note', $bodyParams) ? $bodyParams['note'] : Tools::getValue('note');
        $extra = array_key_exists('extra', $bodyParams) ? $bodyParams['extra'] : Tools::getValue('extra');
        $cartId = array_key_exists('OrdenID', $bodyParams) ? (int) $bodyParams['OrdenID'] : (int) Tools::getValue('OrdenID');

        if (!$extra) {
            $extra = [];
        }

        $extra['cart_id'] = $cartId;

        $this->paymentHandler->validatePayment(null, 'newcarnetpayment', $note, $extra, true, true);
    }

    public function validatePaypal($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();
        $paymentMethodNonce = array_key_exists('payment_method_nonce', $bodyParams) ? $bodyParams['payment_method_nonce'] : Tools::getValue('payment_method_nonce');
        $note = array_key_exists('note', $bodyParams) ? $bodyParams['note'] : Tools::getValue('note');
        $extra = array_key_exists('extra', $bodyParams) ? $bodyParams['extra'] : Tools::getValue('extra');

        if (!$extra) {
            $extra = [];
        }

        $extra['paymentMethodNonce'] = $paymentMethodNonce;

        $this->paymentHandler->validatePayment($customerId, $extra['method'], $note, $extra);
    }

    public function validateRedsys()
    {
        if (\Module::isEnabled('redsysoficial')) {
            require_once _PS_MODULE_DIR_.'redsysoficial/apiRedsys/apiRedsysFinal.php';
        } else {
            ResponseHandler::notFound($this->module->l('Payment method not found', 'paymentws'));
        }

        $status = Tools::strtolower(trim(Tools::getValue('status')));

        $note = '';
        $extra = [
            'Ds_SignatureVersion' => Tools::getValue('Ds_SignatureVersion'),
            'Ds_MerchantParameters' => Tools::getValue('Ds_MerchantParameters'),
            'Ds_Signature' => Tools::getValue('Ds_Signature'),
            'status' => $status,
        ];

        $rs = new \RedsysAPI();
        $decodec = json_decode($rs->decodeMerchantParameters($extra['Ds_MerchantParameters']));

        $orderRif = $decodec->Ds_Order;
        $idCart = ltrim(Tools::substr($orderRif, 0, strpos($orderRif, 'z')), '0');
        $extra['cart_id'] = (int) $idCart;

        $cart = new Cart($idCart);

        $this->paymentHandler->validatePayment((int) $cart->id_customer, 'redsysoficial', $note, $extra, false, true);
    }

    public function validateSatispay()
    {
        $manager = new SatispayManager('satispay', $this->dataHandler);
        $manager->handleCallback();
    }

    public function validateStripe($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();

        $paymentToken = array_key_exists('payment_token', $bodyParams) ? $bodyParams['payment_token'] : Tools::getValue('payment_token');
        $type = array_key_exists('type', $bodyParams) ? $bodyParams['type'] : Tools::getValue('type');
        $note = array_key_exists('note', $bodyParams) ? $bodyParams['note'] : Tools::getValue('note');
        $extra = array_key_exists('extra', $bodyParams) ? $bodyParams['extra'] : Tools::getValue('extra');

        if (!$extra) {
            $extra = [];
        }

        $extra['token'] = $paymentToken;
        $extra['type'] = $type;

        $this->paymentHandler->validatePayment($customerId, 'stripe_official', $note, $extra);
    }

    public function confirmStripePayment($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();

        $paymentIntentId = array_key_exists('payment_intent_id', $bodyParams) ? $bodyParams['payment_intent_id'] : Tools::getValue('payment_intent_id');
        $type = array_key_exists('type', $bodyParams) ? $bodyParams['type'] : Tools::getValue('type');
        $note = array_key_exists('note', $bodyParams) ? $bodyParams['note'] : Tools::getValue('note');
        $extra = array_key_exists('extra', $bodyParams) ? $bodyParams['extra'] : Tools::getValue('extra');

        if (!$extra) {
            $extra = [];
        }

        $extra['payment_intent_id'] = $paymentIntentId;
        $extra['type'] = $type;

        $this->paymentHandler->confirmPayment($customerId, 'stripe_official', $note, $extra);
    }

    public function validateWebGateway()
    {
        $bodyParams = $this->dataHandler->getBodyParams();
        $cartId = array_key_exists('id_cart', $bodyParams) ? $bodyParams['id_cart'] : Tools::getValue('id_cart');

        $this->paymentHandler->validateWebGateway($cartId);
    }
}
