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

namespace MrAPPs\MrShopApi\Handler\Payment\Delayed;

use Cart;
use CartRule;
use Configuration;
use Language;
use Module;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Order;
use Validate;

class BankwireManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_PAYMENT_DELAYED'];

        $module = $this->getApiModule();

        $this->description = $module->l('Please pay the invoice. We will process the order after receiving the indicated amount.', 'bankwiremanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay by bank wire', 'bankwiremanager', $langIsoCode);
    }

    protected function getFeeDescription($feeAmount)
    {
        if ($feeAmount > 0) {
            return parent::getFeeDescription($feeAmount);
        }

        return "\n".$this->getApiModule()->l('Bank transfer discount', 'bankwiremanager').': '.$this->priceFormatter->format(\Tools::ps_round($feeAmount, 2), $this->dataHandler->getCurrencyId());
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        try {
            if ($this->moduleName === 'bankwirediscount') {
                return $this->handleBankWireDiscount($customer, $cart, $note, $currency);
            } else {
                return $this->handleBankWire($customer, $cart, $note, $currency, $totalAmount);
            }
        } catch (\Exception $e) {
            error_log($e);
            ResponseHandler::badRequest($this->getApiModule()->l('Payment Error', 'bankwiremanager'));
        }
    }

    private function handleBankWire($customer, $cart, $note, $currency, $amount)
    {
        $total = (float) $amount;
        $mailVars = [
            '{bankwire_owner}' => Configuration::get('BANK_WIRE_OWNER'),
            '{bankwire_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
            '{bankwire_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS')),
        ];

        if ($this->moduleInstance->validateOrder(
            $cart->id,
            Configuration::get('PS_OS_BANKWIRE'),
            $total,
            $this->moduleInstance->displayName,
            $note,
            $mailVars,
            (int) ($currency->id),
            false,
            $customer->secure_key
        )) {
            $orderId = (int) ($this->moduleInstance->currentOrder);
            $order = new Order($orderId);

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $module = $this->getApiModule();
            $text = sprintf(
                "%s:\n%s\n\n%s:\n%s\n\n%s:\n%s",
                $module->l('Account owner', 'bankwiremanager'),
                Configuration::get('BANK_WIRE_OWNER'),
                $module->l('Details', 'bankwiremanager'),
                Configuration::get('BANK_WIRE_DETAILS'),
                $module->l('Bank address', 'bankwiremanager'),
                Configuration::get('BANK_WIRE_ADDRESS')
            );

            $this->setOrderIdForCustomFields($cart->id, $orderId);

            return [
                'text' => $text,
                'reference' => Validate::isLoadedObject($order) ? $order->reference : '',
            ];
        } else {
            throw new \Exception('Problems during order creation');
        }
    }

    private function handleBankWireDiscount($customer, $cart, $note, $currency)
    {
        $module = $this->getApiModule();

        $this->addDiscount($module, $cart);
        $includeTaxes = ApiUtils::includeTaxes($customer->id);
        $totalAmount = $cart->getOrderTotal($includeTaxes, Cart::BOTH);

        $mailVars = [
            '{bankwire_owner}' => Configuration::get('BANKWIRE_OWNER'),
            '{bankwire_details}' => nl2br(Configuration::get('BANKWIRE_DETAILS')),
            '{bankwire_address}' => nl2br(Configuration::get('BANKWIRE_ADDRESS')),
        ];

        if ($this->moduleInstance->validateOrder(
            $cart->id,
            Configuration::get('PS_OS_BANKWIRE'),
            $totalAmount,
            $this->moduleInstance->displayName,
            $note,
            $mailVars,
            (int) ($currency->id),
            false,
            $customer->secure_key
        )) {
            $orderId = (int) ($this->moduleInstance->currentOrder);
            $order = new Order($orderId);

            $this->cartHandler->generateNewCartFromCustomer($customer->id);

            $text = sprintf(
                "%s:\n%s\n\n%s:\n%s\n\n%s:\n%s",
                $module->l('Account owner', 'bankwiremanager'),
                Configuration::get('BANKWIRE_OWNER'),
                $module->l('Details', 'bankwiremanager'),
                Configuration::get('BANKWIRE_DETAILS'),
                $module->l('Bank address', 'bankwiremanager'),
                Configuration::get('BANKWIRE_ADDRESS')
            );

            $this->setOrderIdForCustomFields($cart->id, $orderId);

            return [
                'text' => $text,
                'reference' => Validate::isLoadedObject($order) ? $order->reference : '',
            ];
        } else {
            throw new \Exception('Problems during order creation');
        }
    }

    private function addDiscount(Module $module, $cart)
    {
        if ($this->moduleName !== 'bankwirediscount') {
            return;
        }

        $discount = $this->moduleInstance->calculateDiscount();

        if ($discount > 0) {
            $voucher = new CartRule();
            $name = $module->l('Bankwire Payment Discount', 'bankwiremanager');
            $nameValue = [];
            foreach (Language::getLanguages(false) as $lang) {
                $nameValue[$lang['id_lang']] = $name;
            }
            $voucher->name = $nameValue;

            $voucher->reduction_amount = round($discount, 2);
            $voucher->reduction_tax = 1;
            $voucher->reduction_currency = (int) $cart->id_currency;
            $voucher->minimum_amount_currency = (int) $cart->id_currency;
            $voucher->minimum_amount = 0;
            $voucher->quantity = 1;
            $voucher->quantity_per_user = 1;
            $voucher->cart_rule_restriction = 1;
            $voucher->date_from = date('Y-m-d H:i:s', time() - 30);
            $voucher->date_to = date('Y-m-d H:i:s', time() + 30);

            if ($voucher->save()) {
                $cart->addCartRule((int) ($voucher->id));
                $cart->update();
            }
        }
    }
}
