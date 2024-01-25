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

namespace MrAPPs\MrShopApi\Service;

use Address;
use Carrier;
use Cart;
use CartRule;
use Context;
use Country;
use Module;
use State;
use StockAvailable;
use Tools;

class CodFeeHelper
{
    protected static $instance;

    protected static $isValidateOrderCod = false;

    /**
     * @param \Customer $customer
     * @param \Cart $cart
     * @return array
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function getCodFee($customer, $cart)
    {
        require_once(_PS_MODULE_DIR_.'codfee/classes/CodfeeConfiguration.php');
        $moduleInstance = self::getOrderValidatorInstance();

        $codFee = null;
        $feeAmount = 0;

        /* Code copied from original module codfee/codfee.php */

        $id_lang = $cart->id_lang;
        $id_shop = $cart->id_shop;

        $customer_groups = $customer->getGroupsStatic($customer->id);

        $carrier = new Carrier($cart->id_carrier);
        $carrier_ref = $carrier->id_reference;

        $address = new Address($cart->id_address_delivery);
        $country = new Country((int) $address->id_country);

        if ($address->id_state > 0) {
            $zone = State::getIdZone((int) $address->id_state);
        } else {
            $zone = $country->getIdZone((int) $country->id);
        }

        $manufacturers = '';
        $suppliers = '';
        $products = $cart->getProducts();

        foreach ($products as $product) {
            $manufacturers .= $product['id_manufacturer'].';';
            $suppliers .= $product['id_supplier'].';';
        }

        $manufacturers = explode(';', trim($manufacturers, ';'));
        $manufacturers = array_unique($manufacturers, SORT_REGULAR);

        $suppliers = explode(';', trim($suppliers, ';'));
        $suppliers = array_unique($suppliers, SORT_REGULAR);

        $order_total = $cart->getOrderTotal(true, 3);

        // Get codfee configuration filtered
        $codfeeconfs = new \CodfeeConfiguration();
        $codfeeconfs = $codfeeconfs->getFeeConfiguration(
            $id_shop,
            $id_lang,
            $customer_groups,
            $carrier_ref,
            $country,
            $zone,
            $products,
            $manufacturers,
            $suppliers,
            $order_total,
            true
        );

        if (!$codfeeconfs) {
            $feeAmount = 0;
        } else {
            $codFee = $codfeeconfs[0];

            $context = Context::getContext();
            $conv_rate = (float) $context->currency->conversion_rate;

            if ($codFee['hide_first_order'] == '1') {
                $customer_stats = $customer->getStats();
                if ($customer_stats['nb_orders'] == '0') {
                    $codFee = null;
                }
            }

            if ($codFee['only_stock'] == '1') {
                $no_stock = false;
                foreach ($products as $product) {
                    if (StockAvailable::getQuantityAvailableByProduct(
                        $product['id_product'],
                        $product['id_product_attribute']
                    ) <= 0) {
                        $no_stock = true;

                        break;
                    }
                }
                if ($no_stock) {
                    $codFee = null;
                }
            }

            $order_max = $codFee['order_max'] * (float) $conv_rate;
            $order_min = $codFee['order_min'] * (float) $conv_rate;

            if (($order_max > 0 && $order_total < $order_max) || ($order_max == 0)) {
                if (($order_min > 0 && $order_min <= $order_total) || ($order_min == 0)) {
                    $feeAmount = (float) Tools::ps_round((float) $moduleInstance->getFeeCost($cart, $codFee), 2);

                    if ($codFee['free_on_freeshipping'] == '1' && $cart->getOrderTotal(
                        true,
                        Cart::ONLY_SHIPPING
                    ) == 0) {
                        $feeAmount = (float) 0.00;
                    }
                    if ($codFee['free_on_freeshipping'] == '1' && count($cart->getCartRules(CartRule::FILTER_ACTION_SHIPPING)) > 0) {
                        $feeAmount = (float) 0.00;
                    }
                }
            }
        }

        return ['fee' => $codFee, 'amount' => $feeAmount, 'id' => $codfeeconfs[0]['id_codfee_configuration']];
    }

    public static function getOrderValidatorInstance()
    {
        if (false == isset(self::$instance)) {
            if (file_exists(_PS_MODULE_DIR_.'codfee/classes/ValidateOrderCod.php')) {
                require_once(_PS_MODULE_DIR_.'codfee/classes/ValidateOrderCod.php');
                self::$instance = new \ValidateOrderCod();
                // setto una variabile statica in modo da non dover replicare il codice di verifica dell'esistenza della classe nel tyoe check
                self::$isValidateOrderCod = true;
            } else {
                self::$instance = Module::getInstanceByName('codfee');
            }
        }

        return self::$instance;
    }

    /**
     * @param \Cart $cart
     * @param \Customer $customer
     * @param $totalAmount
     * @param $note
     * @return false|mixed
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function validate($cart, $customer, $totalAmount, $note)
    {
        // devono essere in ordine di versione decrescente
        $methodMap = [
            '1.7.7' => 'validateOrder177',
            '1.7.6' => 'validateOrder176',
            '1.7.4' => 'validateOrder174',
            '1.7'   => 'validateOrder17',
            '1.6'   => 'validateOrder16'
        ];

        $codFee = self::getCodFee($customer, $cart);

        /*
         * da circa la 3.4.5 codfee non si basa più sul modulo stesso per validare gli
         * ordini ma su di una sottoclasse del modulo, che deve quindi essere istanziata a mano,
         * che contiene tutti i metodi di validazione del modulo.
         */
        $orderValidator = self::getOrderValidatorInstance();

        $pars = [
            (int) $cart->id,
            (int) $codFee['fee']['initial_status'],
            $totalAmount,
            $codFee['amount'],
            /*
             * $orderValidator è di tipo CodFee perchè o è lei o è una sottoclasse, quindi posso sempre
             * chiamarci sopra display name
             */
            $orderValidator->displayName,
            $note,
            null,
            null,
            false,
            $customer->secure_key
        ];

        if (self::$isValidateOrderCod) {
            array_splice($pars, 4, 0, [(int) $codFee['fee']['id_codfee_configuration']]);
        }

        foreach ($methodMap as $minVersion => $method) {
            if (version_compare(_PS_VERSION_, $minVersion, '>=') && method_exists($orderValidator, $method)) {
                return call_user_func_array(
                    [$orderValidator, $method],
                    $pars
                );
            }
        }

        throw new \RuntimeException('Suitable validation method not found');
    }
}
