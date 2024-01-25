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

use Context;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Shop;

class StoreWS extends BaseWS
{
    private static $KILOM = 6371;

    private static $MILES = 3959;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->allowCacheParameters(['filter', 'lat', 'lng', 'radius', 'unit']);
    }

    private function getQueryV3()
    {
        if (version_compare(_PS_VERSION_, '1.7.3', '>=') === true) {
            return 'SELECT DISTINCT a.id_store AS id, cl.name AS country, s.name AS state, sl.name AS name, sl.address1, sl.address2, a.city, a.postcode, a.latitude, a.longitude, sl.hours, a.phone, a.fax, a.email, sl.note, a.active
                  FROM `'._DB_PREFIX_.'store` a
                   '.Shop::addSqlAssociation('store', 'a').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country
                  INNER JOIN `'._DB_PREFIX_.'store_lang` sl ON sl.id_store = a.id_store
                  LEFT JOIN `'._DB_PREFIX_.'state` s ON s.id_state = a.id_state
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id;
        } else {
            return 'SELECT DISTINCT a.id_store AS id, cl.name AS country, s.name AS state, a.name AS name, a.address1, a.address2, a.city, a.postcode, a.latitude, a.longitude, a.hours, a.phone, a.fax, a.email, a.note, a.active
                  FROM `'._DB_PREFIX_.'store` a
                   '.Shop::addSqlAssociation('store', 'a').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country
                  LEFT JOIN `'._DB_PREFIX_.'state` s ON s.id_state = a.id_state
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id;
        }
    }

    private function getQuery($params = [])
    {
        $lat = $params['lat'];
        $lng = $params['lng'];
        $radius = array_key_exists('radius', $params) ? $params['radius'] : 10;

        if (array_key_exists('unit', $params)) {
            $unit = $params['unit'] === 'km' ? self::$KILOM : self::$MILES;
        } else {
            $unit = self::$KILOM;
        }

        if (version_compare(_PS_VERSION_, '1.7.3', '>=') === true) {
            return 'SELECT DISTINCT a.id_store AS id, cl.name AS country, s.name AS state, sl.name AS name, sl.address1, sl.address2, a.city, a.postcode, a.latitude, a.longitude,
                           ('.$unit.'*acos(cos(radians('.(float) $lat.'))*cos(radians(a.latitude))*cos(radians(a.longitude)-radians('.(float) $lng.'))+sin(radians('.(float) $lat.'))*sin(radians(a.latitude)))) AS distance
                  FROM `'._DB_PREFIX_.'store` a
                   '.Shop::addSqlAssociation('store', 'a').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country
                  INNER JOIN `'._DB_PREFIX_.'store_lang` sl ON sl.id_store = a.id_store
                  LEFT JOIN `'._DB_PREFIX_.'state` s ON s.id_state = a.id_state
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id.' 
                  HAVING distance < '.(int) $radius;
        } else {
            return 'SELECT DISTINCT a.id_store AS id, cl.name AS country, s.name AS state, a.name AS name, a.address1, a.address2, a.city, a.postcode, a.latitude, a.longitude,
                           ('.$unit.'*acos(cos(radians('.(float) $lat.'))*cos(radians(a.latitude))*cos(radians(a.longitude)-radians('.(float) $lng.'))+sin(radians('.(float) $lat.'))*sin(radians(a.latitude)))) AS distance
                  FROM `'._DB_PREFIX_.'store` a
                   '.Shop::addSqlAssociation('store', 'a').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country
                  LEFT JOIN `'._DB_PREFIX_.'state` s ON s.id_state = a.id_state
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id.' 
                  HAVING distance < '.(int) $radius;
        }
    }

    public function getList($params)
    {
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            $this->getDetail($filter['id']);
        }

        if (!array_key_exists('lat', $params) ||
            !array_key_exists('lng', $params)) {
            $this->response(false, $this->module->l('Params required', 'storews'), null);
        }

        $isList = true;
        if ($this->dataHandler->getApiVersion() >= 4) {
            $query = $this->getQuery($params);
        } else {
            $isList = false;
            $query = $this->getQueryV3().' LIMIT 1';
        }

        $storesData = $this->refactorData($query);

        $stores = [];

        foreach ($storesData as $store) {
            $stores[] = $this->refactorStore($store, $isList);
        }

        $this->response(true, null, $stores);
    }

    public function getDetail($id)
    {
        $query = $this->getQueryV3().' AND a.id_store = '.(int) $id;
        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $this->response(true, null, $this->refactorStore($data, false));
        } else {
            ResponseHandler::notFound($this->module->l('Store not found', 'storews'));
        }
    }

    private function refactorStore($store, $isList)
    {
        $hours = [];

        if (array_key_exists('hours', $store)) {
            $hoursData = null;

            if (!empty($store['hours'])) {
                $hoursData = json_decode($store['hours']);

                if (empty($hoursData)) {
                    $hoursData = unserialize($store['hours']);
                }
            }

            foreach ($hoursData as $key => $hoursDay) {
                if (!empty($hoursDay) && ((ApiUtils::isArray($hoursDay) && !empty($hoursDay[0]))
                        || (!ApiUtils::isArray($hoursDay) && !empty($hoursDay)))) {
                    $value = ApiUtils::isArray($hoursDay) && !empty($hoursDay[0]) ? $hoursDay[0] : $hoursDay;

                    $hours[] = [
                        'day' => $key,
                        'hours' => $value,
                    ];
                }
            }
        }

        $_store = [
            'id' => $store['id'],
            'name' => ApiUtils::nullifyIfEmpty($store['name']),
            'address1' => $store['address1'],
            'address2' => ApiUtils::nullifyIfEmpty($store['address2']),
            'city' => $store['city'],
            'postcode' => empty($store['postcode']) ? null : $store['postcode'],
            'latitude' => (float) $store['latitude'],
            'longitude' => (float) $store['longitude'],
            'state' => $store['state'],
            'country' => $store['country'],
        ];

        if (!$isList) {
            $details = [
                'email' => ApiUtils::nullifyIfEmpty($store['email']),
                'phone' => empty($store['phone']) ? null : $store['phone'],
                'fax' => empty($store['fax']) ? null : $store['fax'],
                'hours' => ApiUtils::nullifyIfEmpty($hours),
            ];

            $_store = array_merge($_store, $details);
        } else {
            if (array_key_exists('distance', $store)) {
                $_store['distance'] = (float) $store['distance'];
            }
        }

        return $_store;
    }
}
