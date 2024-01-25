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
use Cart;
use Configuration;
use Context;
use Country;
use Currency;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use ShopUrl;
use Tools;

class MoneticoManager extends PaymentManager
{
    const REDIRECT_ROUTE_MONETICO1F = 'mobilemonetico1fpayment';

    const MODULE_MONETICO1F = 'monetico1f';

    const MODULE_SDEVMONETICO = 'sdevmonetico';

    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay by credit card (CB, VISA, MASTERCARD, ...)', 'moneticomanager');

        if ($this->moduleName == self::MODULE_SDEVMONETICO) {
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoTpe.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoAccount.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoPayments.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoReference.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoWS.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoDbTools.php';
            include_once _PS_MODULE_DIR_.'sdevmonetico/classes/MoneticoHmac.php';
        }
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay by credit card', 'moneticomanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        if (parent::isValidPaymentMethod($cart) &&
            $this->getModuleInstance()->active && $cart != null) {
            if ($this->moduleName == self::MODULE_MONETICO1F) {
                $cmcicActive = (bool) Configuration::get('CMCIC_ACTIVE');
                $minimumAmount = Configuration::get('CMCIC_MONTANTMINI');
                $activeIp = Configuration::get('CMCIC_IP_TEST_ACTIVE');
                $moneticoIp = Configuration::get('CMCIC_IP_TEST');
                $test1f = strstr(Configuration::get('CMCIC_SERVEUR'), 'test');

                if (
                    ($cmcicActive == 'on' && $activeIp == '1' && $moneticoIp == $_SERVER["REMOTE_ADDR"] && $test1f != '')
                    or ($cmcicActive == 'on' && $activeIp == '0' && $test1f != '')
                    or ($cmcicActive == 'on' && $test1f == '')
                ) {
                    if ($cart != null) {
                        $currency = new Currency((int) $cart->id_currency);
                        $amount = number_format(Tools::convertPrice($cart->getOrderTotal(true, 3), $currency), 2, '.', '');

                        return $minimumAmount <= $amount;
                    } else {
                        return true;
                    }
                }
            } elseif ($this->moduleName == self::MODULE_SDEVMONETICO) {
                $totalCart = $cart->getOrderTotal(true, Cart::BOTH);
                $moneticoAccounts = \MoneticoAccount::getAll(true);

                foreach ($moneticoAccounts as $moneticoAccount) {
                    if ($moneticoAccount->active
                        && $totalCart > (float) $moneticoAccount->min_order
                        && ((int) $moneticoAccount->max_order == 0 || $totalCart < (float) $moneticoAccount->max_order)) {
                        $moneticoAccount = new \MoneticoAccount((int) $moneticoAccount->id, (int) $this->context->language->id);
                        $addressInvoice = new Address($this->context->cart->id_address_invoice);

                        // autorisation de la boutique
                        if ($moneticoAccount->allShopsAssociated() && !$moneticoAccount->shopAssociated($this->context->shop->id)) {
                            continue;
                        }

                        // autorisation de la monnaie
                        if ($moneticoAccount->allCurrenciesAssociated() && !$moneticoAccount->currencyAssociated($this->context->cart->id_currency)) {
                            continue;
                        }

                        // autorisation du groupe
                        if ($moneticoAccount->allGroupsAssociated()) {
                            $is_allowed = false;
                            foreach ($this->context->customer->getGroups() as $id_group) {
                                if ($moneticoAccount->groupAssociated($id_group)) {
                                    $is_allowed = true;
                                }
                            }
                            if (!$is_allowed) {
                                continue;
                            }
                        }

                        // autorisation du pays
                        if ($moneticoAccount->allCountriesAssociated() && !$moneticoAccount->countryAssociated($addressInvoice->id_country)) {
                            continue;
                        }

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getModuleLogo()
    {
        if ($this->moduleName == self::MODULE_MONETICO1F) {
            $bankServer = Configuration::get('CMCIC_SERVEUR');

            if (strstr($bankServer, 'cic')) {
                $bankLogo = "logocic.jpg";
            } elseif (strstr($bankServer, 'creditmutuel')) {
                $bankLogo = "logocm.jpg";
            } elseif (strstr($bankServer, 'obc')) {
                $bankLogo = "logoobc.jpg";
            } elseif (strstr($bankServer, 'monetico')) {
                $bankLogo = "logomonetico.jpg";
            } else {
                $bankLogo = '';
            }

            return  ApiUtils::getFullDomain().'modules/'.$this->moduleName.'/img/'.$bankLogo;
        }

        return '';
    }

    private function giveDatePlusMonth($nb)
    {
        $date_next = [];
        $actual_month = date("m");

        $date_next['day'] = date("d");
        $date_next['month'] = $actual_month + $nb;
        $date_next['year'] = date("Y");
        if ($date_next['month'] > 12) {
            $date_next['month'] = $date_next['month'] - 12;
            $date_next['year'] = $date_next['year'] + 1;
        }
        if (checkdate($date_next['month'], $date_next['day'], $date_next['year']) == false) {
            while (checkdate($date_next['month'], $date_next['day'], $date_next['year']) == false) {
                $date_next['day']--;
            }
        }
        if (Tools::strlen($date_next['month'])==1) {
            $date_next['month'] = '0'.$date_next['month'];
        }

        return $date_next['day'].'/'.$date_next['month'].'/'.$date_next['year'];
    }

    private function composeSDevMoneticoPayForm($accountId, $cart)
    {
        $monetico_account = new \MoneticoAccount((int) $accountId, (int) $this->context->language->id);
        $monetico_tpe = new \MoneticoTpe((int) $monetico_account->id_tpe);
        $monetico_reference = \MoneticoReference::create($cart->id, $monetico_account->id);
        $address_invoice = new Address($cart->id_address_invoice);

        $fields = [];

        $reference = $monetico_reference->reference;
        $montant = number_format($cart->getOrderTotal(true, 3), 2, '.', '');
        $devise = $this->context->currency->iso_code;
        $date = date('d/m/Y:H:i:s');
        $lgue = Tools::strtoupper($this->context->language->iso_code);
        if ($lgue == 'GB') {
            $lgue = 'EN';
        }

        $email = $this->context->customer->email;

        $details_paiement = [
            'nbPayment' => 1,
            'typePayment' => '',
            'amountTotal' => $montant,
            'dateEch1' => '',
            'amountEch1' => '',
            'dateEch2' => '',
            'amountEch2' => '',
            'dateEch3' => '',
            'amountEch3' => '',
            'dateEch4' => '',
            'amountEch4' => '',
            'recurrent' => false,
            'type' => ''
        ];

        switch ($monetico_account->mode) {
            case '1X':
                $details_paiement['type'] = (int) $monetico_account->type;

                break;
            case 'NX':
                $details_paiement['nbPayment'] = (int) $monetico_account->nb_ech;
                $details_paiement['dateEch1'] = date('d/m/Y');
                $details_paiement['amountEch1'] = round(((float) $monetico_account->first_pay * $details_paiement['amountTotal']) / 100, 2).$devise;
                $restAmount = $details_paiement['amountTotal'] - $details_paiement['amountEch1'];
                $mensualite = round($restAmount / ($details_paiement['nbPayment'] - 1), 2);
                for ($i = 1; $i < $details_paiement['nbPayment']; $i++) {
                    $details_paiement['dateEch'.($i + 1)] = $this->giveDatePlusMonth($i);
                    if (($i + 1) == $details_paiement['nbPayment']) {
                        $details_paiement['amountEch'.($i + 1)] = $restAmount.$devise;
                    } else {
                        $restAmount -= $mensualite;
                        $details_paiement['amountEch'.($i + 1)] = $mensualite.$devise;
                    }
                }

                break;
            case 'RC':
                $details_paiement['recurrent'] = true;

                break;
            case 'C3':
            case 'C4':
            case '1E':
                // nothing
                break;
        }

        // gestion des protocoles
        switch ($monetico_account->mode) {
            case '1E':
                $protocole = '1euro';

                break;
            case 'C3':
                $protocole = '3xcb';

                break;
            case 'C4':
                $protocole = '4xcb';

                break;
            case 'PP':
                $protocole = 'paypal';

                break;
            case 'LP':
                $protocole = 'lyfpay';

                break;
            default:
                $protocole = null;

                break;
        }

        // enregistrement des paramètres
        $monetico_reference->datas = serialize($details_paiement);
        $monetico_reference->save();

        $url_ok = $this->context->link->getPageLink('order-confirmation', true, null, 'id_cart='.(int) $this->context->cart->id.'&id_module='.$this->moduleInstance->id.'&key='.$this->context->customer->secure_key);
        $url_ko = $this->context->link->getPageLink('order', true, null, 'step=3&error=1');
        $url_paiement = $this->moduleInstance->getMoneticoUrl($monetico_account->test);

        $oHmac = new \SdevMoneticoHmac($monetico_tpe->hmac);

        $texte_libre_form = $this->moduleInstance->l('Cart #').$this->context->cart->id.' ('.$email.')';
        $contexte_commande = $this->moduleInstance->getOrderContext();

        $fields['reference'] = $reference;
        $fields['TPE'] = $monetico_tpe->tpe;
        $fields['version'] = '3.0';
        $fields['montant'] = $montant.$devise;
        $fields['date'] = $date;
        $fields['lgue'] = $lgue;
        $fields['contexte_commande'] = $contexte_commande;
        $fields['societe'] = $monetico_tpe->societe;
        $fields['texte-libre'] = $texte_libre_form;
        $fields['mail'] = $email;
        $fields['url_retour_ok'] = $url_ok;
        $fields['url_retour_err'] = $url_ko;
        if ((float) $monetico_account->min_3ds > (float) $montant) {
            $fields['3dsdebrayable'] = 1;
        }

        $fields['ThreeDSecureChallenge'] = $monetico_account->threedsecurechallenge;

        if ($monetico_account->libellemonetique) {
            $fields['libelleMonetique'] = $monetico_account->libellemonetique;
        }

        if ($protocole) {
            $fields['protocole'] = $protocole;
        }

        if ($monetico_account->mode == '1X') {
            $fields['desactivemoyenpaiement'] = '1euro,3xcb,4xcb,lyfpay,paypal';
        }

        if ((bool) $monetico_account->enable_express_payment) {
            $fields['aliascb'] = 'CUST'.(int) $this->context->customer->id;
        }

        if ((bool) $monetico_account->enable_express_payment && $monetico_account->forcesaisiecb) {
            $fields['forcesaisiecb'] = $monetico_account->forcesaisiecb;
        }

        $fields['nbrech'] = $details_paiement['nbPayment'] > 1 ? $details_paiement['nbPayment'] : '';
        $fields['dateech1'] = $details_paiement['dateEch1'];
        $fields['montantech1'] = $details_paiement['amountEch1'];
        $fields['dateech2'] = $details_paiement['dateEch2'];
        $fields['montantech2'] = $details_paiement['amountEch2'];
        $fields['dateech3'] = $details_paiement['dateEch3'];
        $fields['montantech3'] = $details_paiement['amountEch3'];
        $fields['dateech4'] = $details_paiement['dateEch4'];
        $fields['montantech4'] = $details_paiement['amountEch4'];

        // préremplissage cofidis
        if ($monetico_account->mode == 'C3' || $monetico_account->mode == 'C4') {
            $fields['nomclient'] = bin2hex(Tools::substr($address_invoice->lastname, 0, 50));
            $fields['prenomclient'] = bin2hex(Tools::substr($address_invoice->firstname, 0, 50));
            $fields['adresseclient'] = bin2hex(Tools::substr($address_invoice->address1, 0, 100));
            if (!empty($address_invoice->address2)) {
                $fields['complementadresseclient'] = bin2hex(Tools::substr($address_invoice->address2, 0, 50));
            }
            $fields['codepostalclient'] = bin2hex(Tools::substr($address_invoice->postcode, 0, 10));
            $fields['villeclient'] = bin2hex(Tools::substr($address_invoice->city, 0, 50));
            $fields['paysclient'] = bin2hex(Country::getIsoById($address_invoice->id_country));
            if (!empty($address_invoice->phone)) {
                $fields['telephonefixeclient'] = bin2hex(Tools::substr($address_invoice->phone, 0, 20));
            }
            if (!empty($address_invoice->phone_mobile)) {
                $fields['telephonemobileclient'] = bin2hex(Tools::substr($address_invoice->phone_mobile, 0, 20));
            }
        }

        ksort($fields);

        $fields_to_encode = [];
        foreach ($fields as $key => $value) {
            $fields_to_encode[] = "$key=$value";
        }

        $fields['MAC'] = $oHmac->computeHmac(implode('*', $fields_to_encode));

        $inputs = [];

        foreach ($fields as $key => $value) {
            $inputs[$key] = [
                'name' => $key,
                'type' => 'hidden',
                'value' => $value
            ];
        }

        return [
            'action' => $url_paiement,
            'inputs' => $inputs
        ];
    }

    private function getAccountStructure($accountId, $cart)
    {
        $monetico_account = new \MoneticoAccount((int) $accountId, (int) $this->context->language->id);
        $address_invoice = new Address($cart->id_address_invoice);

        // autorisation de la boutique
        if ($monetico_account->allShopsAssociated() && !$monetico_account->shopAssociated($this->context->shop->id)) {
            return false;
        }

        // autorisation de la monnaie
        if ($monetico_account->allCurrenciesAssociated() && !$monetico_account->currencyAssociated($cart->id_currency)) {
            return false;
        }

        // autorisation du groupe
        if ($monetico_account->allGroupsAssociated()) {
            $is_allowed = false;
            foreach ($this->context->customer->getGroups() as $id_group) {
                if ($monetico_account->groupAssociated($id_group)) {
                    $is_allowed = true;
                }
            }
            if (!$is_allowed) {
                return false;
            }
        }

        // autorisation du pays
        if ($monetico_account->allCountriesAssociated() && !$monetico_account->countryAssociated($address_invoice->id_country)) {
            return false;
        }

        $current_ext = $this->moduleInstance->getCurrentExtension($accountId);

        if ($current_ext) {
            $image_to_return = Tools::getHttpHost(true).__PS_BASE_URI__.'modules/'.$this->moduleInstance->name.'/views/img/tpe/'.(int) $accountId.'.'.$current_ext;
        } else {
            $image_to_return = '';
        }

        return [
            'logo' => $image_to_return,
            'text' => $this->getDisplayName(),
            'parameters' => [ 'accountId' => $accountId]
        ];
    }

    private function getPaymentOptions($cart)
    {
        $paymentOptions = [];

        $totalCart = $cart->getOrderTotal(true, Cart::BOTH);
        $moneticoAccounts = \MoneticoAccount::getAll(true);

        foreach ($moneticoAccounts as $account) {
            if ($account->active
                && $totalCart > (float) $account->min_order
                && ((int) $account->max_order == 0 || $totalCart < (float) $account->max_order)) {
                $paymentOption = $this->getAccountStructure($account->id, $cart);
                if ($paymentOption) {
                    $paymentOptions[] = $paymentOption;
                }
            }
        }

        return $paymentOptions;
    }

    public function getStructure($customerId, $cartId)
    {
        $structure = parent::getStructure($customerId, $cartId);
        $structures = [];

        if ($this->moduleName == self::MODULE_MONETICO1F) {
            $bankServer = Configuration::get('CMCIC_SERVEUR');
            $structure['is_sandbox'] = strpos($bankServer, 'test') == false;

            $structures[] = $structure;
        } elseif ($this->moduleName == self::MODULE_SDEVMONETICO) {
            $paymentOptions = $this->getPaymentOptions(new \Cart((int) $cartId));

            foreach ($paymentOptions as $paymentOption) {
                $updatedStructure = array_merge($structure, []);

                $updatedStructure['logo'] = $paymentOption['logo'];
                $updatedStructure['display_name'] = $paymentOption['text'];
                $updatedStructure['name'] = $updatedStructure['name'].'?'.http_build_query($paymentOption['parameters']);

                $structures[] = $updatedStructure;
            }
        }

        return $structures;
    }

    /**
     * URL of the pay method page
     * @return string
     */
    public static function getMonetico1fRedirectUrl()
    {
        return Context::getContext()->link
            ->getModuleLink(ApiUtils::MODULE_NAME, self::REDIRECT_ROUTE_MONETICO1F, [], true);
    }

    public static function getErrorUrlRegex()
    {
        if (!$domain = ShopUrl::getMainShopDomainSSL()) {
            $domain = Tools::getHttpHost();
        }

        return '/^'.preg_quote((Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$domain).'.*mobilemonetic/i';
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $urlReturn = $this->getRegEx($this->context->link->getPageLink('order-confirmation'));

        if ($this->moduleName == self::MODULE_MONETICO1F) {
            $errorReturn = [
                self::getErrorUrlRegex()
            ];

            return [
                'method' => 'GET',
                'gateway' => self::getMonetico1fRedirectUrl(),
                'body' => null,
                'returnUrlSuccess' => $urlReturn,
                'returnUrlFailure' => $errorReturn,
                'returnUrlCancel' => [],
                'amount' => $totalAmount
            ];
        } elseif ($this->moduleName == self::MODULE_SDEVMONETICO) {
            $errorReturn = $this->getRegEx($this->context->link->getPageLink('order', true, null, 'step=3&error=1'));
            $gatewayUrl = Context::getContext()->link
                ->getModuleLink(
                    ApiUtils::MODULE_NAME,
                    'order',
                    ['payment_method' => $this->moduleName, 'extra' => $this->extraParameters],
                    true
                );

            return [
                'method' => 'GET',
                'gateway' => $gatewayUrl,
                'body' => null,
                'returnUrlSuccess' => $urlReturn,
                'returnUrlFailure' => $errorReturn,
                'returnUrlCancel' => [],
                'amount' => $totalAmount
            ];
        }

        return null;
    }

    public function getWebPaymentForm($cart)
    {
        if ($this->moduleName == self::MODULE_SDEVMONETICO && array_key_exists('accountId', $this->extraParameters)) {
            $accountId = $this->extraParameters['accountId'];

            return $this->composeSDevMoneticoPayForm($accountId, $cart);
        }

        return null;
    }
}
