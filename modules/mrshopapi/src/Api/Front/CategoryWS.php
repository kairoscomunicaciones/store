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

use Category;
use Configuration;
use Context;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\Enums;
use Product;
use Shop;

class CategoryWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheUserGroups = true;
        $this->allowCacheParameters(['filter', 'id_category', 'offset', 'limit']);
    }

    public function getList($params, $idCustomer = null, $echoResponse = true)
    {
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];
        $id_layout = array_key_exists('id_layout', $params) ? (int) $params['id_layout'] : (int) Configuration::get('MRSHOP_CATALOG_LAYOUT');

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            $this->getDetail($filter['id'], $echoResponse);
        }

        if (array_key_exists('id_category', $params)) {
            $parentIdCategory = $params['id_category'];
        } else {
            $parentIdCategory = null;
        }

        if (array_key_exists('offset', $params)) {
            $offset = (int) $params['offset'];
        } else {
            $offset = -1;
        }

        $limit = array_key_exists('limit', $params) ? (int) $params['limit'] : $this->defaultLimit;

        $idLang = Context::getContext()->language->id;
        $parentCategory = !empty($parentIdCategory) ? $parentIdCategory : Configuration::get('PS_HOME_CATEGORY');

        $hasProductsQuery = '(SELECT cp.`id_product` 
                    FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
                    INNER JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
                    WHERE cp.`id_category` = a.id_category
                    AND product_shop.`active` = 1
                    AND product_shop.`visibility` in ("both","catalog") 
                    ORDER BY cp.position LIMIT 1) AS first_product_id';

        $whereQuery = ' WHERE a.active = 1
                      AND al.id_lang = '.(int) $idLang.
            ($idCustomer !== null
                ? ' AND ag.id_group IN (SELECT id_group FROM `'._DB_PREFIX_.'customer_group` cg WHERE cg.id_customer = '.(int) $idCustomer.')'
                : ' AND al.id_lang = '.(int) $idLang.' AND ag.id_group='.(int) Configuration::get('PS_GUEST_GROUP'));

        $subquery = ' ,a.id_category AS id, 
                                   a.id_parent   AS parent_id, 
                                   al.name, 
                                   a.`level_depth` AS level_depth,
                                   category_shop.`position` AS position,
                                   '.$hasProductsQuery.'
                            FROM   `'._DB_PREFIX_.'category` a
                            '.Shop::addSqlAssociation('category', 'a').'
                            LEFT JOIN `'._DB_PREFIX_.'category_lang` al ON al.id_category = a.id_category'.Shop::addSqlRestrictionOnLang('al').'
                            INNER JOIN `'._DB_PREFIX_.'category_group` ag ON a.id_category = ag.id_category';

        $query = 'SELECT p.* FROM ((SELECT DISTINCT "1" AS level'
            .$subquery.$whereQuery.' AND a.id_parent = '.(int) $parentCategory
            .' ORDER BY category_shop.`position` '
            .' '.($offset >= 0 ? ' LIMIT '.(int) $offset.','.(int) $limit : '').')
                UNION ALL 
                (SELECT DISTINCT "2"  AS level'.$subquery.$whereQuery.'
                AND a.id_parent IN (SELECT id_category 
                                    FROM   `'._DB_PREFIX_.'category` 
                                    WHERE  active = 1 
                                    AND id_parent = '.(int) $parentCategory.'))) AS p 
                ORDER  BY p.level ASC, 
                  p.level_depth ASC, 
                  p.position ASC';

        $result = $this->refactorData($query);
        $output = [];

        if (ApiUtils::isArray($result)) {
            foreach ($result as &$item) {
                if ($item['id']) {
                    $category = new Category($item['id'], $this->context->language->id);
                    $image_url = '';
                    if ($category->id_image) {
                        $image_url = $this->context->link->getCatImageLink($category->link_rewrite, (int) $category->id_image);
                    }

                    if (!empty($image_url)) {
                        $item['image'] = $image_url;
                    }
                }
                $hasProducts = $item['first_product_id'] != null;

                if ((int) $item['level'] == 1) {
                    if (!isset($output[$item['id']])) {
                        if (Module::isEnabled('riccionepiadina')) {
                            $pattern = '/\|.*/';
                            $item['name'] = preg_replace($pattern, '', $item['name']);
                        }
                        $output[$item['id']] = [    // the key is temporary
                            'id' => $item['id'],
                            'id_parent' => $item['parent_id'],
                            'name' => $item['name'],
                            'image' => $image_url,
                            'position' => $item['position'],
                            'categories' => [],
                            'products' => [],
                            'has_products' => $hasProducts,
                        ];
                    }
                } elseif ((int) $item['level'] == 2) {
                    $parentId = (int) $item['parent_id'];

                    if (isset($output[$parentId])) {
                        // non c'è un metodo su prestashop...
                        $categoryImageFound = false;
                        $categoryImage = null;
                        for ($i = 0; $i < 3; $i++) {
                            $imgPath = $item['id'].'-'.$i.'_thumb.jpg';
                            if (file_exists(_PS_CAT_IMG_DIR_.$imgPath)) {
                                $categoryImage = $imgPath;
                                $categoryImageFound = true;
                            }
                        }

                        if ($categoryImageFound) {
                            $item['image'] = ApiUtils::getFullDomain(false)._THEME_CAT_DIR_.$categoryImage;
                        } else {
                            if ($hasProducts && $id_layout == Enums::CATALOG_LAYOUT_IMAGE) {
                                $idProduct = (int) $item['first_product_id'];
                                $coverImages = ProductBaseWS::getCoverImages($idProduct);
                                $productImage = $coverImages['image'];
                            } else {
                                $productImage = null;
                            }
                            $item['image'] = $productImage;
                        }

                        if (!isset($output[$parentId]['categories'][$item['id']])) {
                            $output[$parentId]['categories'][$item['id']] = [
                                'id' => $item['id'],
                                'id_parent' => $parentId,
                                'name' => $item['name'],
                                'image' => $item['image'],
                                'position' => $item['position'],
                                'has_products' => $hasProducts,
                            ];
                        }
                    }
                }
            }

            if ($id_layout == Enums::CATALOG_LAYOUT_IMAGE) {
                $productController = new ProductWS($this->dataHandler);
            } else {
                $productController = null;
            }
            // Get products for first level categories without sub-categories
            foreach (array_keys($output) as $idCategory) {
                if (count($output[$idCategory]['categories']) > 0) {
                    unset($output[$idCategory]['products']);
                    $output[$idCategory]['categories'] = array_values($output[$idCategory]['categories']);
                } else {
                    unset($output[$idCategory]['categories']);
                    if ($productController) {
                        $products = $productController->getCategoryProducts($idCategory, $params, $idCustomer, $this->defaultLimit);
                        $output[$idCategory]['products'] = isset($products['result']) && ApiUtils::isArray($products['result']) ? $products['result'] : [];
                    } else {
                        $output[$idCategory]['products'] = [];
                    }
                }
            }
        }

        $this->response(true, null, array_values($output), $echoResponse);
    }

    public function getDetail($id, $echoResponse = true)
    {
        $data = $this->refactorData('SELECT a.id_category AS id, id_parent, position, name
                  FROM `'._DB_PREFIX_.'category` a LEFT JOIN `'._DB_PREFIX_.'category_lang` al ON a.id_category = al.id_category
                  WHERE a.active = 1
                  AND al.id_lang = '.(int) Context::getContext()->language->id.'
                  AND a.id_category = '.(int) $id, false);

        if ($data !== false) {
            $this->response(true, null, $data, $echoResponse);
        } else {
            if ($echoResponse) {
                ResponseHandler::notFound($this->module->l('Category not found', 'categoryws'));
            }
        }
    }
}
