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

class CountryWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->allowCacheParameters(['sort', 'filter']);
    }

    public function getList($params)
    {
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            $this->getDetail($filter['id']);
        }

        $query = 'SELECT c.id_country as id, cl.name, id_zone, need_zip_code, zip_code_format, iso_code, need_identification_number
                  FROM `'._DB_PREFIX_.'country` c '.Shop::addSqlAssociation('country', 'c').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON c.id_country = cl.id_country
                  WHERE c.active = 1 AND cl.id_lang = '.(int) Context::getContext()->language->id.
                 ' ORDER BY cl.name ASC';

        $result = $this->refactorData($query);

        if (ApiUtils::isArray($result)) {
            foreach ($result as &$item) {
                $item['address_fields'] = $this->getAddressFieldsByCountry($item['id']);
            }
        }

        $this->response(true, null, $result);
    }

    public function getDetail($id)
    {
        $query = 'SELECT c.id_country as id, name, id_zone, need_zip_code, zip_code_format, iso_code, need_identification_number
                  FROM `'._DB_PREFIX_.'country` c '.Shop::addSqlAssociation('country', 'c').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON c.id_country = cl.id_country
                  WHERE c.active = 1 
                  AND cl.id_lang = '.(int) Context::getContext()->language->id.'
                  AND c.id_country = '.(int) $id;

        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $this->response(true, null, $data);
        } else {
            ResponseHandler::successfulEmptyResponse();
        }
    }
}
