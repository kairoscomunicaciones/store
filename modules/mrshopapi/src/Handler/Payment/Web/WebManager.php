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

use Context;
use Hook;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use PrestaShop\PrestaShop\Core\Payment\PaymentOptionFormDecorator;

class WebManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_WEB'];

        $module = $this->getApiModule();

        $this->description = sprintf($module->l('Pay with your %s account', 'webmanager'), $this->moduleInstance->name);
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return sprintf($module->l('Pay with %s', 'webmanager'), $this->moduleInstance->name);
    }

    public function isValidPaymentMethod($cart = null)
    {
        return parent::isValidPaymentMethod($cart) && (is_null($cart) || $cart->nbProducts() > 0);
    }

    public function getStructure($customerId, $cartId)
    {
        if (!$this->moduleInstance) {
            return null;
        }
        $structure = parent::getStructure($customerId, $cartId);
        $paymentOptions = Hook::exec('paymentOptions', [], $this->moduleInstance->id, true);
        $structures = [];

        foreach ($paymentOptions[$this->moduleInstance->name] as $i => $paymentOption) {
            $updatedStructure = array_merge($structure, [
                'name' => $this->moduleInstance->name.'?index='.$i,
                'display_name' => $paymentOption->getCallToActionText(),
                'description' => ''
            ]);

            $structures[] = $updatedStructure;
        }

        return $structures;
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        $instance = $this->getPaymentInstance();

        return [
            'method' => 'GET',
            'gateway' => $this->getCompletePaymentUrl($instance['index'], $note),
            'body' => null,
            'returnUrlSuccess'   => $this->getRegEx($this->getSuccessUrl()),
            'returnUrlFailure'   => $this->getRegEx($this->getErrorUrl()),
            'returnUrlCancel'    => [],
            'amount'             => $totalAmount
        ];
    }

    public function getWebPaymentForm($cart)
    {
        $instance = $this->getPaymentInstance();

        $formattedOption = $instance['paymentOption']->toArray();
        $formattedOption['id'] = $instance->name.'-option';

        if ($formattedOption['form']) {
            $decorator = new PaymentOptionFormDecorator();
            $formattedOption['form'] = $decorator->addHiddenSubmitButton(
                $formattedOption['form'],
                $formattedOption['id']
            );
        }

        return $formattedOption;
    }

    public function getOptionIndex()
    {
        return empty($this->extraParameters) ? null : (int) $this->extraParameters['index'];
    }

    private function getPaymentInstance()
    {
        $module = $this->getApiModule();
        $paymentOptions = Hook::exec('paymentOptions', [], $this->moduleInstance->id, true);
        $paymentOption = $paymentOptions[$this->moduleInstance->name];
        $index = (int) $this->getOptionIndex();
        // Try to get first index (so = 0) if extraParamets missing, if is set to other
        // Prevent error of undefined index
        try {
            $paymentOption = $paymentOption[$index];

            return ['paymentOption' => $paymentOption, 'index' => $index];
        } catch (\Exception $e) {
            throw new \Exception(sprintf($module->l('Payment method not found', 'webmanager'), $this->moduleInstance->name));
        }
    }

    private function getCompletePaymentUrl($instanceIndex, $note)
    {
        return Context::getContext()->link->getModuleLink('mrshopapi', 'order', [
            'payment_method' => $this->moduleInstance->name,
            'extra' => [
                'index' => $instanceIndex,
                'note' => $note
            ]
        ]);
    }

    /**
     * URL where the user is redirected when a payment error is occured
     * @return string
     */
    private function getErrorUrl()
    {
        $name = ApiUtils::isPS16() ? 'order' : 'cart';

        return Context::getContext()->link->getPageLink($name);
    }

    /**
     * URL where the user is redirected when the payment is correctly completed
     * @return string
     */
    private function getSuccessUrl()
    {
        return Context::getContext()->link->getPageLink('order-confirmation');
    }
}
