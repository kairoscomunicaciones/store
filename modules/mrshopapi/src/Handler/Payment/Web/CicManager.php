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

use Cart;
use Configuration;
use Context;
use Customer;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use PrestashopLogger;
use Tools;

class CicManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay securely by credit card.', 'cicmanager');
        if (parent::isValidPaymentMethod()) {
            require_once _PS_MODULE_DIR_.'sps2006_cmcic30/CMCIC_Tpe.inc.php';
        }
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('CM - CIC', 'cicmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if ($cart === null) {
            return true;
        }

        return parent::isValidPaymentMethod($cart) &&
            $this->dataHandler->getApiVersion() > 4 &&
            Tools::strlen(Configuration::get('MYBQE_TPE')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_VERSION')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_SERVEUR')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_CODESOCIETE')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_URLOK')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_URLKO')) > 0 &&
            Tools::strlen(Configuration::get('MYBQE_KEYPASS')) > 0;
    }

    public function getConfiguration()
    {
        if (!$this->moduleInstance) {
            return null;
        }

        return [
            'tpe' => Configuration::get('MYBQE_TPE'),
            'version' => Configuration::get('MYBQE_VERSION'),
            'server' => Configuration::get('MYBQE_SERVEUR'),
            'company_code' => Configuration::get('MYBQE_CODESOCIETE'),
            'url_ok' => Configuration::get('MYBQE_URLOK'),
            'url_ko' => Configuration::get('MYBQE_URLKO'),
            'keypass' => Configuration::get('MYBQE_KEYPASS'),
        ];
    }

    public function getStructure($customerId, $cartId = null)
    {
        if (!$this->moduleInstance) {
            return null;
        }

        $structure = parent::getStructure($customerId, $cartId);

        $serverParsed = parse_url(Configuration::get('MYBQE_SERVEUR'), PHP_URL_PATH);
        if ($serverParsed === '/test/') {
            $structure['is_sandbox'] = true;
        }

        return $structure;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();
        $config = $this->getConfiguration();

        $data = [
            'date' => date('d/m/Y:H:i:s'),
            'lang' => Tools::strtoupper($context->language->iso_code),
            'currency' => Tools::strtoupper($context->currency->iso_code),
            'reference' => $cart->id,
            'email' => $customer->email,
            'amount' => number_format(($totalAmount), 2, '.', ''),
            'note' => $note,
        ];

        $this->getMAC($config, $data);

        $returnUrlSuccess = $this->getRegEx($config['url_ok']);
        $returnUrlFailure = $this->getRegEx($config['url_ko']);

        $params = [
            'method' => 'POST',
            'gateway' => $data['gateway'],
            'body' => http_build_query(
                [
                    'version' => $config['version'],
                    'TPE' => $config['tpe'],
                    'date' => $data['date'],
                    'montant' => $data['amount'].$data['currency'],
                    'reference' => $data['reference'],
                    'MAC' => $data['mac'],
                    'url_retour' => $config['url_ko'],
                    'url_retour_ok' => $config['url_ok'],
                    'url_retour_err' => $config['url_ko'],
                    'lgue' => $data['lang'],
                    'societe' => $config['company_code'],
                    'texte-libre' => $data['note'],
                    'mail' => $data['email'],
                ]
            ),
            'returnUrlSuccess' => $returnUrlSuccess,
            'returnUrlFailure' => $returnUrlFailure,
            'returnUrlCancel' => $returnUrlFailure,
            'amount' => $data['amount'],
        ];

        return $params;
    }

    public function handleValidatePayment($customer, $cart, $note, $extra)
    {
        PrestaShopLogger::addLog('handleValidatePayment', 2, 0, 'ORDER');

        $config = $this->getConfiguration();

        $this->getValidationMAC($config, $extra);

        $status = null;
        $error = null;
        if ($extra['validation_mac'] === $extra['mac']) {
            switch ($extra['return_code']) {
                case 'Annulation':
                    $status = 'Annulation';

                    break;

                case 'paiement':
                case 'payetest':
                    $status = 'PAY-OK';

                    break;

                default:
                    break;
            }
            $receipt = CMCIC_CGI2_MACOK;
            $MACConfirmed = true;
        } else {
            $receipt = CMCIC_CGI2_MACNOTOK.$extra['cgi2_fields'];
            $MACConfirmed = false;
        }

        if ($status === 'PAY-OK') {
            try {
                $cartId = $extra['reference'];
                $cart = new Cart($cartId);
                if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->moduleInstance) {
                    Tools::redirect($this->context->link->getPageLink('order'));
                }

                $total = (float) $cart->getOrderTotal(true, 3);
                $customer = new Customer((int) $cart->id_customer);

                // todo check $extra elements if usefull
                $mailVars = [];
                $this->moduleInstance->validateOrder(
                    $cart->id,
                    _PS_OS_PAYMENT_,
                    $total,
                    $this->moduleInstance->displayName,
                    $note,
                    $mailVars,
                    (int) $cart->id_currency,
                    false,
                    $customer->secure_key
                );

                $this->cartHandler->generateNewCartFromCustomer($customer->id);
            } catch (Exception $e) {
                Tools::redirect($this->context->link->getPageLink('order'));
            }
        } else {
            Tools::redirect($this->context->link->getPageLink('order'));
        }
    }

    private function getMAC($config, &$data)
    {
        define('CMCIC_TPE', $config['tpe']);
        define('CMCIC_CLE', $this->LitKey($config['keypass'], $config['tpe']));
        define('CMCIC_VERSION', $config['version']);
        define('CMCIC_SERVEUR', $config['server']);
        define('CMCIC_CODESOCIETE', $config['company_code']);
        define('CMCIC_URLOK', $config['url_ok']);
        define('CMCIC_URLKO', $config['urk_ko']);

        $oTpe = new \CMCIC_Tpe($data['lang']);

        $oHmac = new \CMCIC_Hmac($oTpe);

        $CtlHmac = sprintf(CMCIC_CTLHMAC, $oTpe->sVersion, $oTpe->sNumero, $oHmac->computeHmac(sprintf(CMCIC_CTLHMACSTR, $oTpe->sVersion, $oTpe->sNumero)));

        $PHP1_FIELDS = sprintf(
            CMCIC_CGI1_FIELDS,
            $oTpe->sNumero,
            $data['date'],
            $data['amount'],
            $data['currency'],
            $data['reference'],
            $data['note'],
            $oTpe->sVersion,
            $oTpe->sLangue,
            $oTpe->sCodeSociete,
            $data['email'],
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );
        $data['mac'] = $oHmac->computeHmac($PHP1_FIELDS);
        $data['gateway'] = $oTpe->sUrlPaiement;
    }

    private function getValidationMAC($config, &$data)
    {
        define('CMCIC_TPE', $config['tpe']);
        define('CMCIC_CLE', $this->LitKey($config['keypass'], $config['tpe']));
        define('CMCIC_VERSION', $config['version']);
        define('CMCIC_SERVEUR', $config['server']);
        define('CMCIC_CODESOCIETE', $config['company_code']);
        define('CMCIC_URLOK', $config['url_ok']);
        define('CMCIC_URLKO', $config['urk_ko']);

        $oTpe = new \CMCIC_Tpe();

        $oHmac = new \CMCIC_Hmac($oTpe);

        $CtlHmac = sprintf(CMCIC_CTLHMAC, $oTpe->sVersion, $oTpe->sNumero, $oHmac->computeHmac(sprintf(CMCIC_CTLHMACSTR, $oTpe->sVersion, $oTpe->sNumero)));

        $cgi2_fields = sprintf(
            CMCIC_CGI2_FIELDS,
            $oTpe->sNumero,
            $data['date'],
            $data['montant'],
            $data['reference'],
            $data['texte-libre'],
            $oTpe->sVersion,
            $data['code-retour'],
            $data['cvx'],
            $data['vld'],
            $data['brand'],
            $data['status3ds'],
            $data['numauto'],
            $data['motifrefus'],
            $data['originecb'],
            $data['bincb'],
            $data['hpancb'],
            $data['ipclient'],
            $data['originetr'],
            $data['veres'],
            $data['pares']
        );
        $data['validation_mac'] = $oHmac->computeHmac($cgi2_fields);
        $data['cgi2_fields'] = $cgi2_fields;
    }

    private function LitKey($dir, $tp)
    {
        $file = _PS_MODULE_DIR_.$this->moduleName.'/'.$dir.'/'.trim($tp).'.key';
        if (file_exists($file)) {
            $data_cle = explode(' ', nl2br(Tools::file_get_contents($file)));

            return Tools::substr($data_cle[2], 0, -3);
        } else {
            return null;
        }
    }
}
