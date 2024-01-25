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

namespace MrAPPs\MrShopApi\Api;

use AddressFormat;
use Context;
use Country;
use Db;
use ImageType;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Service\PriceFormatter;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;
use Validate;

class BaseWS
{
    public $context;

    public $priceFormatter;

    protected $defaultLimit = 10;

    // Cache params
    protected $cacheEnabled = true;

    protected $cacheTime = 900; // cache default time 15 min

    protected $cacheUserGroups = false; // generate a different file for every user group

    protected $cacheAllowedMethods = ['list', 'detail']; // generate a cache only for allowed method

    private $cacheAllowedParameters = ['version', 'id_as_string']; // allowed cache parameters

    /** @var CacheHandler $cacheHandler */
    public $cacheHandler;

    public $cacheDir;

    public $cacheIdentifier;

    /** @var DataHandler $dataHandler */
    protected $dataHandler;

    protected $module;

    public function __construct($dataHandler)
    {
        $this->dataHandler = $dataHandler;
        $this->module = ApiUtils::getModule();
        $this->context = Context::getContext();
        $this->priceFormatter = new PriceFormatter();
        $this->cacheHandler = null;

        if (_PS_MODE_DEV_) {
            $this->cacheEnabled = false;
        }
    }

    protected function allowCacheParameters($params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }
        foreach ($params as $p) {
            if (!in_array($p, $this->cacheAllowedParameters)) {
                $this->cacheAllowedParameters[] = $p;
            }
        }
    }

    public function isCacheParameterAllowed($param)
    {
        return in_array($param, $this->cacheAllowedParameters);
    }

    public function isCacheEnabled($isListRequest = true)
    {
        return (bool) $this->cacheEnabled && in_array($isListRequest ? 'list' : 'detail', $this->cacheAllowedMethods);
    }

    public function isCacheUserGroupsHandlerEnabled()
    {
        return (bool) $this->cacheUserGroups;
    }

    public function getCurrencyId()
    {
        return $this->dataHandler->getCurrencyId();
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getApiVersion()
    {
        return $this->dataHandler->getApiVersion();
    }

    public function getCacheTime()
    {
        return $this->isCacheEnabled() ? (int) $this->cacheTime : 0;
    }

    public function setCacheParams($cacheHandler, $cacheDir, $cacheIdentifier)
    {
        $this->cacheHandler = $cacheHandler;
        $this->cacheDir = $cacheDir;
        $this->cacheIdentifier = $cacheIdentifier;
    }

    public function validateLimit($string)
    {
        return preg_match('/^\d+,\d+$/', $string);
    }

    public function getWhereOptionsFromFilter($filter, $fieldName, $tableAlias = 'a')
    {
        $operator = $this->getFilterOperatorAndValue($filter)['operator'];

        if (!in_array($operator, ["=",">",">=","<","<=","IN"])) {
            $operator = "=";
        }

        $value = $this->getFilterOperatorAndValue($filter)['value'];

        if (!is_numeric($value)) {
            $value = '"'.pSQL($value).'"';
        }

        return ' AND '.$tableAlias.'.'.bqSQL($fieldName).' '.$operator.' '.$value;
    }

    public function getFilterOperatorAndValue($filter)
    {
        if (strpos($filter, '[')) { // if filter is wrrefactorDataitten as key=>[value] or key=<[value]
            $array = explode('[', $filter);
            $operator = $array[0]; // < >
            $value = explode(']', $array[1])[0];

            return [
                'operator' => $operator,
                'value' => $value,
            ];
        } else {
            return [
                'operator' => '=',
                'value' => $filter,
            ];
        }
    }

    private function getLegacyPrefix($entity, $field)
    {
        if ($entity === 'product') {
            if ($field === 'name') {
                return 'pl.';
            } elseif ($field === 'position') {
                return '';
            } elseif ($field === 'manufacturer_name') {
                return 'm.';
            } else {
                return 'p.';
            }
        } elseif ($entity === 'manufacturer') {
            return 'm.';
        }
    }

    public function toLegacyOrderBy($prefix, $entity, $field)
    {
        if ($prefix) {
            return $this->getLegacyPrefix($entity, $field).($entity == 'product' && $field == 'manufacturer_name' ? 'name' : $field);
        } elseif ($entity === 'manufacturer' && $field === 'name') {
            return 'manufacturer_name';
        } else {
            return bqSQL($field);
        }
    }

    public function getOrderFieldAndWay($orderField)
    {
        $array = explode('.', $orderField);

        if (!preg_match('/^[a-zA-Z0-9_]+$/u', $array[0])
            || !Validate::isOrderBy($array[1])
            || !Validate::isOrderWay($array[2])
        ) {
            return null;
        }

        return [
            'field' => $this->toLegacyOrderBy(true, $array[0], $array[1]),
            'way' => Tools::strtoupper($array[2]),
        ];
    }

    public function getOrderOptions($string)
    {
        if (strpos($string, '.asc') !== false || strpos($string, '.desc') !== false) {
            $array = explode('.', $string);

            return ' ORDER BY '.$this->toLegacyOrderBy(true, $array[0], $array[1]).' '.Tools::strtoupper($array[2]);
        } else {
            return '';
        }
    }

    public function isModuleActive($moduleName)
    {
        return $this->dataHandler->isModuleActive($moduleName);
    }

    private function stringifyIfNeeded(&$data)
    {
        if (false == ApiUtils::isArray($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (ApiUtils::isArray($value)) {
                $data[$key] = $this->stringifyIfNeeded($value);
            } else {
                switch (true) {
                    // INTVAL or string
                    case strpos($key, 'id') === 0:
                    case strpos($key, '_id') !== false:
                        $data[$key] = empty($value) ? null : ($this->dataHandler->stringifyIds || !is_numeric((string) $value) ? (string) $value : (int) $value);

                        break;
                }
            }
        }

        return $data;
    }

    public function response($success = true, $error = null, $data = null, $echoResponse = true)
    {
        if ($success) {
            $result = $this->stringifyIfNeeded($data);
            $result = json_encode($result);

            if ($this->cacheHandler != null) {
                $this->cacheHandler->setCache($this->cacheDir, $this->cacheIdentifier, $result);
                $maxAge = $this->cacheHandler->getMaxAge();
            } else {
                $maxAge = null;
            }

            if ($echoResponse) {
                ResponseHandler::response(200, null, $result, $maxAge);
            } else {
                return $data;
            }
        } else {
            if ($echoResponse) {
                ResponseHandler::error($error);
            }
        }
    }

    public static function getProductImageUrl($item, $idImage = null, $type = null)
    {
        if (!isset($item['id_default_image'])) {
            $defaultImgId = null;
        } else {
            $defaultImgId = (int) $item['id_default_image'];
        }

        $noImage = empty($idImage) && (!isset($item['id_default_image']) || empty($item['id_default_image']));
        $imageId = ($idImage === null ? $defaultImgId : (int) $idImage);

        return Context::getContext()->link->getImageLink(
            $noImage ? '' : (!empty($item['link_rewrite']) ? $item['link_rewrite'] : 'image_'.$imageId),
            $noImage ? Context::getContext()->language->iso_code.'-default'
                : $imageId,
            $type
        );
    }

    public function refactorData($query, $multipleRows = true)
    {
        if ($multipleRows) {
            try {
                $result = [];

                $resultData = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

                if (ApiUtils::isArray($resultData)) {
                    foreach ($resultData as $item) {
                        $result[] = ApiUtils::refactorRow($item, $this->dataHandler->getApiVersion());
                    }
                } else {
                    $result = [];
                }
            } catch (\Exception $exception) {
                $result = null;
            }
        } else {
            $result = ApiUtils::refactorRow(Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query), $this->dataHandler->getApiVersion());

            if (ApiUtils::isArray($result) && count($result) == 0) {
                $result = false;
            }
        }

        return $result;
    }

    public static function getImageFormattedName($name)
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>=') === true) {
            return ImageType::getFormattedName($name);
        } else {
            return ImageType::getFormatedName($name);
        }
    }

    public function getAddressFieldsByCountry($id_country = null)
    {
        if (!$id_country) {
            $id_country = $this->context->country->id;
        }
        $fields = AddressFormat::getOrderedAddressFields((int) $id_country, true, true);
        $required = AddressFormat::getFieldsRequired();

        $country = new Country((int) $id_country);
        if (!Validate::isLoadedObject($country)) {
            return [];
        }

        /*
         * Flag "need zip code"
         * if enabled: force added "postcode" (required) in fields list (prestashop behavior)
         * if disabled: force removed "postcode" from fields list (prestashop behavior)
         */
        if ((bool) $country->need_zip_code) {
            if (!in_array('postcode', $fields)) {
                $fields[] = 'postcode';
            }
            $required[] = 'postcode';
        } else {
            do {
                $key = array_search('postcode', $fields);
                if ($key !== false) {
                    unset($fields[$key]);
                }
            } while ($key !== false);
        }

        /*
         * Flag "need identification number"
         * if enabled: force added "dni" (required) in fields list (prestashop behavior)
         * if disabled: do nothing, use general rules (prestashop behavior)
         */
        if ((bool) $country->need_identification_number) {
            if (!in_array('dni', $fields)) {
                $fields[] = 'dni';
            }
            $required[] = 'dni';
        }

        /*
         * Flag "contains states"
         * if enabled: force added "State:name" (required) in fields list (prestashop behavior)
         * if disabled: force removed "State:name" from fields list (prestashop behavior)
         */
        if ((bool) $country->contains_states) {
            if (!in_array('State:name', $fields)) {
                $fields[] = 'State:name';
            }
            $required[] = 'State:name';
        } else {
            do {
                $key = array_search('State:name', $fields);
                if ($key !== false) {
                    unset($fields[$key]);
                }
            } while ($key !== false);
        }

        $required = array_flip($required);

        $translations = [
            'Address:alias' => $this->module->l('Alias', 'basews'),
            'Address:firstname' => $this->module->l('First name', 'basews'),
            'Address:lastname' => $this->module->l('Last name', 'basews'),
            'Address:address1' => $this->module->l('Address', 'basews'),
            'Address:address2' => $this->module->l('Address Complement', 'basews'),
            'Address:city' => $this->module->l('City', 'basews'),
            'Address:postcode' => $this->module->l('Zip/Postal Code', 'basews'),
            'State:name' => $this->module->l('State', 'basews'),
            'Country:name' => $this->module->l('Country', 'basews'),
            'Address:phone' => $this->module->l('Phone', 'basews'),
            'Address:phone_mobile' => $this->module->l('Mobile phone', 'basews'),
            'Address:dni' => $this->module->l('Identification number', 'basews'),
            'Address:company' => $this->module->l('Company', 'basews'),
            'Address:vat_number' => $this->module->l('VAT number', 'basews'),
            'Address:other' => $this->module->l('Notes', 'basews'),
        ];

        $output = [];

        $output[] = [
            'field' => 'Address:alias',
            'required' => false,
            'label' => $translations['Address:alias'],
        ];

        foreach ($fields as $field) {
            $item = [];
            $item['field'] = Tools::strpos($field, ':') ? $field : 'Address:'.$field;
            $item['required'] = false;
            $item['label'] = isset($translations[$item['field']]) ? $translations[$item['field']] : '';
            foreach ($required as $key => $value) {
                if ($key === $field) {
                    $item['required'] = true;
                }
            }

            $output[] = $item;
        }

        return $output;
    }
}
