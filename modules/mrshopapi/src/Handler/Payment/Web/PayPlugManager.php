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
use Media;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;

class PayPlugManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();
        $this->description = $module->l('Pay securely by credit card.', 'payplugmanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with a credit card', 'payplugmanager', $langIsoCode);
    }

    public function isValidPaymentMethod($cart = null)
    {
        return parent::isValidPaymentMethod($cart) &&
            $this->getModuleInstance()->active;
    }

    private function getPaymentOptions($cart)
    {
        $configClass = 'PayPlug\classes\ConfigClass';
        $dependenciesClassName = 'PayPlug\classes\DependenciesClass';
        // dalla versione 3.9.x payplug ha modificato le classi

        if (class_exists($dependenciesClassName)) {
            $moduleInstance = $this->getModuleInstance();
            $optionsProvider = new $configClass(new $dependenciesClassName());
            $options = $optionsProvider->getAvailableOptions($cart);
        }
        // dalla versione 3.3.0 payplug ha spostato ulteriormente della logica in delle sotto classi
        elseif (class_exists($configClass) && method_exists($configClass, 'getAvailableOptions')) {
            $moduleInstance = $this->getModuleInstance();
            $payplugClassName = 'PayPlug\classes\PayPlugClass';
            $optionsProvider =  new $configClass(new $payplugClassName());
            $options = call_user_func_array($configClass.'::getAvailableOptions', [$cart]);
        } else {
            // dalla versione 3.2.0 payplug ha spostato ulteriormente della logica in delle sotto classi
            $configClass = 'PayPlug\classes\PayPlugClass';
            $moduleInstance = $this->getModuleInstance();
            $optionsProvider = class_exists($configClass)
                ? new $configClass()
                : $moduleInstance;

            $options = $optionsProvider->getAvailableOptions($cart);
        }

        $module = $this->getApiModule();
        $payplugCards = [];

        if ($options['one_click']) {
            // getCards
            if (method_exists($optionsProvider, 'getCardsByCustomer') && version_compare($moduleInstance->version, '3.2.0', '<')) {
                $payplugCards = $optionsProvider->getCardsByCustomer((int) $cart->id_customer, true);
            } elseif (method_exists($optionsProvider, 'getPlugin')) {
                $card = $optionsProvider->getPlugin()->getCard();
                $payplugCards = method_exists($card, 'getCardsByCustomer') ? $card->getCardsByCustomer((int) $cart->id_customer, true) : $card->getCards((int) $cart->id_customer, true);
            }
        }
        $paymentList = [];

        $imgLang = $this->context->language->iso_code === 'it' ? 'it' : 'default';

        // OneClick Payment
        if ($options['one_click'] && !empty($payplugCards)) {
            foreach ($payplugCards as $card) {
                $brand = $card['brand'] != 'none' ? Tools::ucfirst($card['brand']) : $module->l('Card', 'payplugmanager');
                $params = [
                    'pc' => (int) $card['id_payplug_card'],
                    'pay' => '1',
                    'id_cart' => (int) $this->context->cart->id,
                    'method' => 'one_click',
                    'def' => (int) $options['deferred'],
                ];

                $paymentList[] = [
                    'logo' => Media::getMediaPath(_PS_MODULE_DIR_.$this->moduleName.'/views/img/'.Tools::strtolower($card['brand']).'.png'),
                    'text' => $brand.' **** **** **** '.$card['last4'].' - '.$module->l('Expiry date', 'payplugmanager').': '.$card['expiry_date'],
                    'parameters' => $params,
                ];
            }
        }

        // Standard Payment or new card from one-click
        $params = [
            'pc' => 'new_card',
            'pay' => '1',
            'id_cart' => (int) $this->context->cart->id,
            'method' => 'standard',
            'def' => (int) $options['deferred'],
        ];

        $paymentList[] = [
            'logo' => Media::getMediaPath(_PS_MODULE_DIR_.$this->moduleName.'/views/img/'.(count($payplugCards) > 0 ? 'none' : 'logos_schemes_'.$imgLang).'.png'),
            'text' => count($payplugCards) > 0 ? $module->l('Pay with a different card', 'payplugmanager') : $module->l('Pay with a credit card', 'payplugmanager'),
            'parameters' => $params,
        ];

        // Installment Payment
        if ($options['installment']) {
            $params = [
                'pc' => 'new_card',
                'pay' => '1',
                'id_cart' => (int) $this->context->cart->id,
                'method' => 'installment',
                'def' => (int) $options['deferred'],
            ];

            $paymentList[] = [
                'logo' => ApiUtils::getFullDomain().Media::getMediaPath(_PS_MODULE_DIR_.$this->moduleName.'/views/img/logos_schemes_installment_'.Configuration::get('PAYPLUG_INST_MODE').'_'.$imgLang.'.png'),
                'text' => sprintf($module->l('Pay by card in %s installments', 'payplugmanager'), Configuration::get('PAYPLUG_INST_MODE')),
                'parameters' => $params,
            ];
        }

        // Oney payment
        if ($options['oney'] && class_exists($dependenciesClassName)) {
            $amount = $cart->getOrderTotal(true, \Cart::BOTH);
            $dependencies = new $dependenciesClassName();
            $plugin =$dependencies->getPlugin();

            $oney = $plugin->getOney();
            $is_elligible = $oney->isValidOneyAmount($amount); // Ref on payplug/src/repositories/OneyRepository

            if ($is_elligible['result']) {
                $params = [
                    'pc' => 'new_card',
                    'pay' => '1',
                    'id_cart' => (int) $this->context->cart->id,
                    'method' => 'oney',
                    'def' => (int) $options['deferred'],
                ];

                $paymentList[] = [
                    'logo' => ApiUtils::getFullDomain(),
                    'text' => $module->l('Pay with Oney', 'payplugmanager'),
                    'parameters' => $params,
                ];
            }
        }

        return $paymentList;
    }

    public function getStructure($customerId, $cartId)
    {
        $structure = parent::getStructure($customerId, $cartId);
        $paymentOptions = $this->getPaymentOptions(new Cart((int) $cartId));

        $structures = [];

        foreach ($paymentOptions as $paymentOption) {
            $updatedStructure = array_merge($structure, []);

            if (!$paymentOption['logo']) {
                $paymentOption['logo'] = null;
            }
            $updatedStructure['logo'] = $paymentOption['logo'];
            $updatedStructure['display_name'] = $paymentOption['text'];
            $updatedStructure['name'] = $updatedStructure['name'].'?'.http_build_query($paymentOption['parameters']);

            $structures[] = $updatedStructure;
        }

        return $structures;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $context = Context::getContext();

        $urlRedirect = $this->context->link->getModuleLink($this->moduleName, 'dispatcher', $this->extraParameters, true);
        $urlReturn = $this->getRegEx($context->link->getPageLink('order-confirmation'));

        $errorReturn = [
            $this->getRegEx($context->link->getPageLink('order').'?\S*step=1'),
            $this->getRegEx($context->link->getPageLink('order').'?\S*step=3&error=1'),
        ];

        return [
            'method' => 'GET',
            'gateway' => $urlRedirect,
            'body' => null,
            'returnUrlSuccess' => $urlReturn,
            'returnUrlFailure' => $errorReturn,
            'returnUrlCancel' => [],
            'amount' => $totalAmount,
        ];
    }
}
