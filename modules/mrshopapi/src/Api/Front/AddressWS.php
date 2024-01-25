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

use Address;
use Context;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use Shop;
use Tools;
use Validate;

class AddressWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getList($params, $id_customer = null)
    {
        $order = array_key_exists('sort', $params) ? $params['sort'] : null;
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            return $this->getDetail($filter['id']);
        }

        $group = Context::getContext()->shop->getGroup();
        $shareOrder = isset($group->share_order) ? (bool) $group->share_order : false;

        $query = 'SELECT a.id_address AS id,
                  a.id_customer AS id_costumer, 
                  a.id_country AS id_country, 
                  a.id_state AS id_state,
                  a.alias AS alias, 
                  a.address1 AS address1, 
                  a.address2 AS address2, 
                  a.postcode AS postcode, 
                  a.city AS city, 
                  a.company AS company, 
                  a.lastname AS lastname, 
                  a.firstname AS firstname, 
                  a.phone AS phone, 
                  a.phone_mobile AS phone_mobile,
                  a.vat_number AS vat_number, 
                  a.dni AS dni, 
                  a.other AS other
                  FROM `'._DB_PREFIX_.'address` a
                  LEFT JOIN `'._DB_PREFIX_.'country` c ON (a.`id_country` = c.`id_country`)'
            .($shareOrder ? '' : Shop::addSqlAssociation('country', 'c')).
            ' WHERE a.active = 1 
                  AND a.deleted = 0
                  AND a.id_customer = '.(int) $id_customer
            .Shop::addSqlRestriction();

        if ($order !== null) {
            $query .= $this->getOrderOptions($order);
        }

        $result = $this->refactorData($query);

        $this->response(true, null, $result);
    }

    public function getDetail($id, $id_customer = null)
    {
        $query = 'SELECT id_address AS id, id_customer, id_country, id_state, alias, address1, address2, postcode, city, company, lastname, firstname, phone, phone_mobile, vat_number, dni, other
                  FROM `'._DB_PREFIX_.'address`
                  WHERE active = 1 AND deleted = 0
                  AND id_customer = '.(int) $id_customer.' 
                  AND id_address = '.(int) $id;

        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $this->response(true, null, $data);
        } else {
            $this->response(true, null, null);
        }
    }

    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!$params || count($params) === 0) {
            $this->response(false, $this->module->l('Invalid parameters', 'addressws'), null);
        } else {
            if ($id === null) {
                $id = array_key_exists('id_address', $params) && $params['id_address'] !== null ? (int) $params['id_address'] : null;
            }

            if ($id !== null) {
                $address = new Address($id);
                $isEdit = true;
            } else {
                $address = new Address();
                $isEdit = false;
            }

            if ($isEdit && (!Validate::isLoadedObject($address) || $address->deleted === true)) {
                $this->response(false, $this->module->l('Address does not exist', 'addressws'), null);
            }

            if ($isEdit && (int) $address->id_customer !== (int) $id_customer) {
                $this->response(false, $this->module->l('You can\'t access this address', 'addressws'), null);
            } else {
                if ($this->getApiVersion() <= 1) {
                    $address1Key = 'address';
                    $otherKey = 'notes';
                } else {
                    $address1Key = 'address1';
                    $otherKey = 'other';
                }

                $cleanedParams = [];
                $cleanedParams['alias'] = isset($params['alias']) ? ucwords(trim($params['alias'])) : '';
                $cleanedParams['firstname'] = isset($params['firstname']) ? ucwords(trim($params['firstname'])) : '';
                $cleanedParams['lastname'] = isset($params['lastname']) ? ucwords(trim($params['lastname'])) : '';
                $cleanedParams['address1'] = isset($params[$address1Key]) ? ucwords(trim($params[$address1Key])) : '';
                $cleanedParams['address2'] = isset($params['address2']) ? ucwords(trim($params['address2'])) : '';
                $cleanedParams['city'] = isset($params['city']) ? ucwords(trim($params['city'])) : '';
                $cleanedParams['postcode'] = isset($params['postcode']) ? trim($params['postcode']) : '';
                $cleanedParams['id_country'] = isset($params['id_country']) ? (int) $params['id_country'] : 0;
                $cleanedParams['id_state'] = isset($params['id_state']) ? (int) $params['id_state'] : 0;
                $cleanedParams['phone'] = isset($params['phone']) ? trim($params['phone']) : '';
                $cleanedParams['phone_mobile'] = isset($params['phone_mobile']) ? trim($params['phone_mobile']) : '';

                $cleanedParams['other'] = isset($params[$otherKey]) ? trim($params[$otherKey]) : '';

                $cleanedParams['dni'] = isset($params['dni']) ? trim($params['dni']) : '';
                $cleanedParams['company'] = isset($params['company']) ? trim($params['company']) : '';
                $cleanedParams['vat_number'] = isset($params['vat_number']) ? trim($params['vat_number']) : '';

                $addressFields = $this->getAddressFieldsByCountry($cleanedParams['id_country']);
                $address->id_customer = $id_customer;

                // Validations
                $errorFields = [];
                if ($cleanedParams['id_country'] == 0) {
                    $errorFields[] = $this->module->l('Country', 'addressws');
                }
                if (Tools::strlen($cleanedParams['alias']) == 0) {
                    $cleanedParams['alias'] = $this->module->l('My address', 'addressws');
                }
                if (Tools::strlen($cleanedParams['dni']) > 16) {
                    return $this->response(false, $this->module->l('The Identification Number must be between 0 and 16 characters.', 'addressws'), null);
                }

                foreach ($addressFields as $addressField) {
                    $required = $addressField['required'];
                    $field = $addressField['field'];

                    if ($field == 'State:name') {
                        $field = 'Address:id_state';
                    }

                    $prefix = 'Address:';
                    if (Tools::substr($field, 0, Tools::strlen($prefix)) == $prefix) {
                        $key = trim(Tools::substr($field, Tools::strlen($prefix)));

                        if (isset($cleanedParams[$key])) {
                            if ($required && empty($cleanedParams[$key])) {
                                $errorFields[] = $addressField['label'];
                            } else {
                                $address->$key = $cleanedParams[$key];
                            }
                            unset($cleanedParams[$key]);
                        } elseif ($required) {
                            $errorFields[] = $addressField['label'];
                        }
                    }
                }

                if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
                    if (empty($address->phone) && empty($address->phone_mobile)) {
                        $errorFields[] = $this->module->l('Phone or mobile phone', 'addressws');
                    }
                }

                if (count($errorFields) > 0) {
                    $this->response(false, $this->module->l('The following parameters are mandatory', 'addressws').': '.implode(', ', $errorFields), null);
                }

                // Extra parameters
                foreach ($cleanedParams as $key => $value) {
                    $address->$key = $value;
                }

                try {
                    if ($address->save()) {
                        ResponseHandler::successfulEmptyResponse();
                    }
                } catch (\Exception $exception) {
                    error_log($exception);
                    $this->response(
                        false,
                        $this->module->l('There was an error while updating this address, please check all fields.', 'addressws'),
                        null
                    );
                }
            }
        }
    }

    public function deleteItem($id, $id_customer = null)
    {
        $address = new Address($id);

        if ((int) $address->id_customer === (int) $id_customer) {
            $address->deleted = true;

            try {
                if ($address->save()) {
                    ResponseHandler::successfulEmptyResponse();
                }
            } catch (\Exception $exception) {
                $this->response(
                    false,
                    $this->module->l('There was an error while retrieving data', 'addressws'),
                    null
                );
            }
        } else {
            $this->response(false, $this->module->l('Cannot delete address', 'addressws'), null);
        }
    }
}
