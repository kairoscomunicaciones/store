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

use Configuration;
use Context;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Shop;
use Tools;

class ManufacturerWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheUserGroups = true;
        $this->allowCacheParameters(['filter', 'page']);
    }

    public function getList($params, $id_customer = null, $echoResponse = true)
    {
        $page = array_key_exists('page', $params) ? max((int) $params['page'], 1) : 1;
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            $this->getDetail($filter['id']);
        }

        $offset = $this->defaultLimit * ($page - 1);

        $query = 'SELECT m.id_manufacturer as id, name
                  FROM `'._DB_PREFIX_.'manufacturer` m'
                  .Shop::addSqlAssociation('manufacturer', 'm').
                  ' WHERE m.active = 1 ORDER BY m.name ASC'
                  .' LIMIT '.(int) $offset.','.(int) $this->defaultLimit;

        $result = $this->refactorData($query);

        if (ApiUtils::isArray($result)) {
            $protocolContent = Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://';

            foreach ($result as &$data) {
                $data['logo_url'] = $this->getManufacturerImageLink($data['id'], $protocolContent);
            }
        }

        $response = [
            'count' => $this->count(),
            'result' => $result,
        ];

        return $this->response(true, null, $response, $echoResponse);
    }

    public function count()
    {
        $query = 'SELECT COUNT(id_manufacturer) as cont
                  FROM `'._DB_PREFIX_.'manufacturer`
                  WHERE active = 1';

        $result = $this->refactorData($query, false);

        return $result ? (int) $result['cont'] : 0;
    }

    public function getDetail($id)
    {
        $query = 'SELECT m.id_manufacturer as id, m.name as name, ml.short_description AS short_description, ml.description AS description
                  FROM `'._DB_PREFIX_.'manufacturer` m '
                  .Shop::addSqlAssociation('manufacturer', 'm').
                  ' LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON m.id_manufacturer = ml.id_manufacturer
                  WHERE m.active = 1 
                  AND ml.id_lang = '.(int) Context::getContext()->language->id.'
                  AND m.id_manufacturer = '.(int) $id;

        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $protocolContent = Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://';
            $data['logo_url'] = $this->getManufacturerImageLink($data['id'], $protocolContent);

            $this->response(true, null, $data);
        } else {
            ResponseHandler::notFound($this->module->l('Manufacturer not found', 'manufacturerws'));
        }
    }

    /**
     * Returns a link to a manufacturer image for display
     *
     * @param $idManufacturer
     * @param $protocolContent
     * @param null $type image type (small_default, medium_default, large_default, etc.)
     *
     * @return string
     */
    public function getManufacturerImageLink($idManufacturer, $protocolContent, $type = null)
    {
        $idManufacturer = (int) $idManufacturer;

        if (file_exists(_PS_MANU_IMG_DIR_.$idManufacturer.(empty($type) ? '.jpg' : '-'.$type.'.jpg'))) {
            $uriPath = _THEME_MANU_DIR_.$idManufacturer.(empty($type) ? '.jpg' : '-'.$type.'.jpg');
        } elseif (!empty($type) && file_exists(_PS_MANU_IMG_DIR_.$idManufacturer.'.jpg')) { // !empty($type) because if is empty, is already tested
            $uriPath = _THEME_MANU_DIR_.$idManufacturer.'.jpg';
        } elseif (file_exists(_PS_MANU_IMG_DIR_.Context::getContext()->language->iso_code.(empty($type) ? '.jpg' : '-default-'.$type.'.jpg'))) {
            $uriPath = _THEME_MANU_DIR_.Context::getContext()->language->iso_code.(empty($type) ? '.jpg' : '-default-'.$type.'.jpg');
        } else {
            $uriPath = _THEME_MANU_DIR_.Context::getContext()->language->iso_code.'.jpg';
        }

        return $protocolContent.Tools::getMediaServer($uriPath).$uriPath;
    }
}
