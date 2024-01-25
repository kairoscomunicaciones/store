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

use Configuration;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentManager;
use Order;
use Tools;
use Validate;

class ChequeManager extends PaymentManager
{
    public function __construct($name, $dataHandler)
    {
        parent::__construct($name, $dataHandler);
        $this->paymentType = self::$paymentTypes['TYPE_PAYMENT_DELAYED'];

        $module = $this->getApiModule();
        $this->description = $module->l('Please pay the invoice. We will process the order after receiving the indicated amount.', 'chequemanager');
    }

    public function getDisplayName($langIsoCode = null)
    {
        $module = $this->getApiModule();

        return $module->l('Pay with cheque', 'chequemanager', $langIsoCode);
    }

    public function handleInitPayment($customer, $cart, $note, $shop, $currency, $totalAmount)
    {
        try {
            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                $mailVars = [
                    '{check_name}' => Configuration::get('CHEQUE_NAME'),
                    '{check_address}' => Configuration::get('CHEQUE_ADDRESS'),
                    '{check_address_html}' => Tools::nl2br(Configuration::get('CHEQUE_ADDRESS')),
                ];
            } else {
                $mailVars = [
                    '{cheque_name}' => Configuration::get('CHEQUE_NAME'),
                    '{cheque_address}' => Configuration::get('CHEQUE_ADDRESS'),
                    '{cheque_address_html}' => str_replace("\n", '<br />', Configuration::get('CHEQUE_ADDRESS')),
                ];
            }

            if ($this->moduleInstance->validateOrder(
                $cart->id,
                Configuration::get('PS_OS_CHEQUE'),
                $totalAmount,
                $this->moduleInstance->displayName,
                $note,
                $mailVars,
                (int) ($currency->id),
                false,
                $customer->secure_key
            )) {
                $orderId = (int) $this->moduleInstance->currentOrder;
                $order = new Order($orderId);

                $this->cartHandler->generateNewCartFromCustomer($customer->id);

                $text = sprintf(
                    "%s:\n%s\n\n%s:\n%s",
                    $this->getApiModule()->l('Cheque name', 'chequemanager'),
                    Configuration::get('CHEQUE_NAME'),
                    $this->getApiModule()->l('Cheque address', 'chequemanager'),
                    Configuration::get('CHEQUE_ADDRESS')
                );

                $this->setOrderIdForCustomFields($cart->id, $orderId);

                return [
                    'text' => $text,
                    'reference' => Validate::isLoadedObject($order) ? $order->reference : '',
                ];
            } else {
                throw new \Exception('Problems during order creation');
            }
        } catch (\Exception $ex) {
            ResponseHandler::badRequest($this->getApiModule()->l('Order not created', 'chequemanager'));
        }
    }
}
