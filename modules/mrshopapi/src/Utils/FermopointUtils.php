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
 *
 */

namespace MrAPPs\MrShopApi\Utils;

use Configuration;
use Country;
use Db;
use Module;
use State;

class FermopointUtils
{
    public static function getFermopointCarrierId()
    {
        return Configuration::get("FERMOPOINT_CAR_ID");
    }
    
    public static function isFermopointEnabled()
    {
        return Module::isEnabled('fermopoint');
    }
    
    public static function getPointInfo($cart_id)
    {
        $sql = "SELECT * 
            FROM "._DB_PREFIX_."fermopoint_bridge
                WHERE id_cart = ".(int) $cart_id;
        $row = Db::getInstance()->getRow($sql);
        
        if (empty($row)) {
            return null;
        }
        
        return unserialize($row['data']);
    }
    
    public static function refactorPointInfo($data, $customer_id)
    {
        $temp_address = $data->a;
        $country_id   = (int) Country::getByIso('IT');

        return [
            'id' => (int) $data->id,
            'id_customer' => (int) $customer_id,
            'id_country' => $country_id,
            'id_state' => (int) State::getIdByIso($temp_address->d, $country_id),
            'alias' => 'Fermo!point',
            'address1' => ApiUtils::defaultIfNull($temp_address->s, ''),
            'address2' => '',
            'postcode' => ApiUtils::defaultIfNull($temp_address->p, ''),
            'city' => ApiUtils::defaultIfNull($temp_address->c, ''),
            'firstname' => ApiUtils::defaultIfNull($data->p, ''),
            'lastname' => ApiUtils::defaultIfNull($data->s, ''),
            'phone' => '',
            'phone_mobile' => '',
            'vat_number' => '',
            'dni' => '',
            'other' => ''
        ];
    }
    
    public static function persistPoint($cart_id, $point)
    {
        $existing = self::getPointInfo($cart_id);
        $data = pSql(serialize($point));

        if (empty($existing)) {
            $sql = "INSERT INTO "._DB_PREFIX_."fermopoint_bridge
                VALUES (".(int) $cart_id.",'".$data."')";
        } else {
            $sql = "UPDATE "._DB_PREFIX_."fermopoint_bridge
                SET data='".$data."'
                WHERE id_cart = ".(int) $cart_id;
        }
        
        return Db::getInstance()->execute($sql);
    }

    /**
     *
     * @return \fermoPointApi
     * @throws \Exception
     */
    public static function getFermopointApi()
    {
        require_once _PS_MODULE_DIR_.'fermopoint/fermopoint.php';
        require_once _PS_MODULE_DIR_.'fermopoint/libs/fpTools.php';
        require_once _PS_MODULE_DIR_.'fermopoint/libs/api.php';
        require_once _PS_MODULE_DIR_.'fermopoint/libs/pointInfo.php';
        require_once _PS_MODULE_DIR_.'fermopoint/libs/PrestashopFermoPoint.php';
        require_once _PS_MODULE_DIR_.'fermopoint/models/fermopointBooking.php';
        
        $fpClass = '\fermoPointApi';
        if (false == class_exists($fpClass)) {
            throw new \Exception('Non esiste la classe fermoPointApi, probabilmente il modulo non è installato');
        }
        
        return new $fpClass;
    }
    
    public static function getAddress($fermopoint)
    {
        $address = "";
        
        if (isset($fermopoint->a)) {
            $temp_address = $fermopoint->a;
            // address1
            if (isset($temp_address->s)) {
                $address .= $temp_address->s;
            }

            // cap
            if (isset($temp_address->z)) {
                $address .= ", ".$temp_address->z;
            }
            
            // città
            if (isset($temp_address->c)) {
                $address .= " - ".$temp_address->c;
            }
        }
        
        return $address;
    }
    
    public static function getFermopointStatusFor($order_id)
    {
        $sql = "SELECT ticketId
                    FROM "._DB_PREFIX_."fermopoint_booking
                    WHERE id_order = ".(int) $order_id;

        $tickedId = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        
        if (empty($tickedId)) {
            return null;
        }
        
        try {
            $api = static::getFermopointApi();
            $response = $api->remoteRequest('orders/order/'.$tickedId);

            if ($response['status'] == 'OK' && isset($response['result']) && isset($response['result']['ticketId'])) {
                $module   = Module::getInstanceByName('mrshopapi');
                switch ($response['result']['state']) {
                    case "Init":
                        return $module->l('Order initialized', 'fermopointutils');
                    case "WaitingForPayment":
                    case "WaitingForPaymentConfirm":
                    case "WaitingForPaymentCapture":
                        return $module->l('Order pending confirmation', 'fermopointutils');
                    case "Booked":
                        return $module->l('Delivery booked, shipping underway', 'fermopointutils');
                    case "Arrived":
                        return $module->l('Arriving at Fermo!Point', 'fermopointutils');
                    case "Collected":
                        return $module->l('Retired at Fermo!Point', 'fermopointutils');
                    case "Canceled":
                        return $module->l('Canceled', 'fermopointutils');
                    case "Error":
                        return $module->l('Error', 'fermopointutils');
                    case "Blocked":
                        return $module->l('Blocked payment', 'fermopointutils');
                    case "InDelivery":
                        return $module->l('In delivery', 'fermopointutils');
                    case "InDeposit":
                        return $module->l('In deposit', 'fermopointutils');
                    case "Received":
                        return $module->l('Arrived to Fermo!Point destination', 'fermopointutils');
                    case "Sent":
                        return $module->l('Sent', 'fermopointutils');
                    case "ToDelivery":
                        return $module->l('Processing', 'fermopointutils');
                    case "ToDeposit":
                        return $module->l('Returned to deposit', 'fermopointutils');
                    default: // altri stati aggiunti dopo messa online del modulo
                        return $response['result']['state'];
                }
            }
        } catch (\Exception $ex) {
            error_log($ex->getMessage());

            return null;
        }
    }
}
