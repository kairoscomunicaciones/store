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

namespace MrAPPs\MrShopApi\Api\Admin;

use \MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use Category;
use Context;
use Db;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface;
use Product;
use Tools;

class AutocompleteSearchWS extends BaseWS implements WebserviceGetListInterface
{
    const PRODUCT      = 'product';

    const CATEGORY     = 'category';

    const MANUFACTURER = 'manufacturer';

    const CMS          = 'cms';
    
    public function getList($params, $employeeId)
    {
        if (false == isset($params['filter']) || false == in_array($params['filter'], self::avaliableEntities())) {
            ResponseHandler::badRequest($this->module->l('Invalid filter type', 'autocompletesearchws'));
        }
        
        if (empty($params['q'])) {
            ResponseHandler::badRequest($this->module->l('Search string is not set', 'autocompletesearchws'));
        }

        $query  = Tools::strtolower(trim($params['q']));
        $filter = $params['filter'];
        $languageId = Context::getContext()->language->id;
        
        switch ($filter) {
            case self::PRODUCT:
                $products = Product::searchByName($languageId, $query);
                if (empty($products)) {
                    $res = [];
                } else {
                    $res = array_map(function ($row) {
                        return ['id' => (int) $row['id_product'], 'name' => $row['name']];
                    }, $products);
                }
            
                break;
            case self::CATEGORY:
                $categories = Category::searchByName($languageId, $query);
                if (empty($categories)) {
                    $res = [];
                } else {
                    $res = array_map(function ($row) {
                        return ['id' => (int) $row['id_category'], 'name' => $row['name']];
                    }, $categories);
                }
            
                break;
            case self::MANUFACTURER:
                $sql = new \DbQuery();
                $sql->select('m.*');
                $sql->from('manufacturer', 'm');
                $sql->where('`name` LIKE "%'.pSQL($query).'%"');
                $manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                if (empty($manufacturers)) {
                    $res = [];
                } else {
                    $res = array_map(function ($row) {
                        return ['id' => (int) $row['id_manufacturer'], 'name' => $row['name']];
                    }, $manufacturers);
                }
            
                break;
            case self::CMS:
                $sql = 'SELECT a.id_cms as id, al.meta_title AS name
                  FROM `'._DB_PREFIX_.'cms` a LEFT JOIN `'._DB_PREFIX_.'cms_lang` al ON a.id_cms = al.id_cms
                  WHERE a.active = 1 AND al.id_lang = '.(int) $languageId.' AND al.id_shop = '.(int) Context::getContext()->shop->id.'
                  AND  al.meta_title LIKE "%'.pSQL($query).'%"
                  ORDER BY position';
                $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                if (empty($res)) {
                    $res = [];
                }
        }
        
        $this->response(true, null, $res);
    }
    
    protected static function avaliableEntities()
    {
        return [self::PRODUCT, self::CATEGORY, self::MANUFACTURER, self::CMS];
    }
}
