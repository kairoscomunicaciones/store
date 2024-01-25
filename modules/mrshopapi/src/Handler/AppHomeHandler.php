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

namespace MrAPPs\MrShopApi\Handler;

use AppHome;
use AppHomeItem;
use Combination;
use Configuration;
use Context;
use Customer;
use Db;
use FrontController;
use Group;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Front\BestSalesWS;
use MrAPPs\MrShopApi\Api\Front\CategoryWS;
use MrAPPs\MrShopApi\Api\Front\ManufacturerWS;
use MrAPPs\MrShopApi\Api\Front\NewProductWS;
use MrAPPs\MrShopApi\Api\Front\PricesDropWS;
use MrAPPs\MrShopApi\Api\Front\ProductBaseWS;
use MrAPPs\MrShopApi\Api\Front\ProductWS;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Service\ImageService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\Enums;
use Product;
use Shop;
use Tools;
use Validate;

class AppHomeHandler
{
    private $module = null;

    /** @var DataHandler $dataHandler */
    private $dataHandler;

    public $priceFormatter;

    /** @var ImageService */
    public $imageService;

    public function __construct($dataHandler)
    {
        $this->dataHandler = $dataHandler;
        $this->module = ApiUtils::getModule();
        $this->imageService = new ImageService();
    }

    private function getTitle($id)
    {
        $query = '
            SELECT p.title
            FROM (
            SELECT `title` FROM `'._DB_PREFIX_.'app_home_lang` WHERE `id_app_home` = "'.(int) $id.'" AND `id_lang` = "'.(int) Context::getContext()->language->id.'"
            UNION ALL
            SELECT `title` FROM `'._DB_PREFIX_.'app_home_lang` WHERE `id_app_home` = "'.(int) $id.'" AND `id_lang` = '.Configuration::get('PS_LANG_DEFAULT').'
            ) AS p
        ';

        $recordset = Db::getInstance()->executeS($query);

        return isset($recordset[0]['title']) && !empty($recordset[0]['title'])
            ? $recordset[0]['title']
            : (isset($recordset[1]['title']) && !empty($recordset[1]['title'])
                ? $recordset[1]['title']
                : '');
    }

    public static function getItems($dataHandler, $row, $layout, $orderBy, $customerId = null)
    {
        $type = $row['type'];
        $params = ['fromAdmin' => true];
        if (Tools::strlen($orderBy) > 0) {
            if ($type == AppHome::$TYPE_MANUFACTURERS) {
                $params['sort'] = $orderBy;
            } else {
                $params['order'] = $orderBy;
            }
        }

        if ($layout == Enums::LAYOUT_TYPE_VERTICAL) {
            $_GET['limit'] = 6;
        }

        $objectWS = null;
        $webPath = null;

        switch ($type) {
            case AppHome::$TYPE_SHOWCASE_PRODUCTS:
                $params['home'] = true;
                $objectWS = new ProductWS($dataHandler, false);
                $webPath = 'products?home=1';

                break;
            case AppHome::$TYPE_NEW_PRODUCTS:
                $objectWS = new NewProductWS($dataHandler);
                $webPath = 'new_products';

                break;
            case AppHome::$TYPE_SPECIAL_OFFERS:
                $objectWS = new PricesDropWS($dataHandler);
                $webPath = 'special_offers';

                break;
            case AppHome::$TYPE_MANUFACTURERS:
                $objectWS = new ManufacturerWS($dataHandler);

                break;
            case AppHome::$TYPE_CATEGORY_PRODUCTS:
                $params['id_category'] = (int) $row['id_category'];
                $_GET['id_category'] = (int) $row['id_category'];
                $objectWS = new ProductWS($dataHandler, false);
                $webPath = 'products?id_category='.$row['id_category'];

                break;
            case AppHome::$TYPE_BEST_SELLER:
                $objectWS = new BestSalesWS($dataHandler);
                $webPath = 'best_sales';

                break;
        }

        if ($objectWS) {
            $result = $objectWS->getList($params, $customerId, false);
            if ($result) {
                return [
                    'items' => isset($result['result']) && is_array($result['result']) ? $result['result'] : [],
                    'web_path' => $webPath,
                ];
            }
        }

        return null;
    }

    /**
     * Get required informations on best sales products.
     *
     * @param int $idLang Language id
     * @param int $pageNumber Start from (optional)
     * @param int $nbProducts Number of products to return (optional)
     *
     * @return array|bool from Product::getProductProperties
     *                    `false` if failure
     */
    public static function getBestSeller($idLang, $pageNumber = 0, $nbProducts = 10, $orderBy = null, $orderWay = null)
    {
        $context = Context::getContext();
        if ($pageNumber < 1) {
            $pageNumber = 1;
        }
        if ($nbProducts < 1) {
            $nbProducts = 10;
        }
        $finalOrderBy = $orderBy;
        $orderTable = '';

        $invalidOrderBy = !Validate::isOrderBy($orderBy);
        if ($invalidOrderBy || null === $orderBy) {
            $orderBy = 'quantity';
            $orderTable = 'ps';
        }

        if ($orderBy == 'date_add' || $orderBy == 'date_upd') {
            $orderTable = 'product_shop';
        }

        $invalidOrderWay = !Validate::isOrderWay($orderWay);
        if ($invalidOrderWay || null === $orderWay || $orderBy == 'sales') {
            $orderWay = 'DESC';
        }

        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;

        // no group by needed : there's only one attribute with default_on=1 for a given id_product + shop
        // same for image with cover=1
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
					'.(Combination::isFeatureActive() ? 'product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute,' : '').'
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					m.`name` AS manufacturer_name, p.`id_manufacturer` as id_manufacturer,
					image_shop.`id_image` id_image, il.`legend`,
					ps.`quantity` AS sales, t.`rate`, pl.`meta_keywords`, pl.`meta_title`, pl.`meta_description`,
					DATEDIFF(p.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(int) $interval.' DAY)) > 0 AS new'
            .' FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false);
        if (Combination::isFeatureActive()) {
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
							ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $context->shop->id.')';
        }

        $sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int) $context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $idLang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int) $context->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p', 0);

        $sql .= '
				WHERE product_shop.`active` = 1
					AND product_shop.`visibility` != \'none\'';

        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql .= ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
            JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '='.(int) Group::getCurrent()->id).')
            WHERE cp.`id_product` = p.`id_product`)';
        }

        if ($finalOrderBy != 'price') {
            $sql .= '
					ORDER BY '.(!empty($orderTable) ? ''.pSQL($orderTable).'.' : '').''.pSQL($orderBy).' '.pSQL($orderWay).'
					LIMIT '.(int) (($pageNumber - 1) * $nbProducts).', '.(int) $nbProducts;
        }
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($finalOrderBy == 'price') {
            Tools::orderbyPrice($result, $orderWay);
            $result = array_slice($result, (int) (($pageNumber - 1) * $nbProducts), (int) $nbProducts);
        }
        if (!$result) {
            return false;
        }

        return Product::getProductsProperties($idLang, $result);
    }

    private function composeSection($row, $weight, $customerId)
    {
        $id = (int) $row['id_app_home'];
        $type = trim($row['type']);
        $bannerType = $type == AppHome::$TYPE_BANNER ? trim($row['banner_type']) : '';
        $bannerSize = $type == AppHome::$TYPE_BANNER ? (!empty(trim($row['banner_size'])) ? trim($row['banner_size']) : AppHome::$BANNER_SIZE_RECTANGLE_HORIZONTAL) : '';
        $currentBannerType = $bannerType;
        $useWebPath = $this->dataHandler->getApiVersion() >= 9;
        $webPath = null;

        $currentType = $type;

        $hideTitleInHome = isset($row['hide_title_in_home']) && $row['hide_title_in_home'];
        $categoryBrand = null;
        $products = [];
        $manufacturers = [];
        $banners = [];
        $categories = [];
        if ($this->isOrderedSectionType($type)) {
            $orderBy = trim($row['order_by']);
            $layout = (int) $row['layout'];

            $result = $this->getItems($this->dataHandler, $row, $layout, $orderBy, $customerId);

            if ($result) {
                $webPath = $result['web_path'];

                switch ($type) {
                    case AppHome::$TYPE_BEST_SELLER:
                    case AppHome::$TYPE_CATEGORY_PRODUCTS:
                    case AppHome::$TYPE_SHOWCASE_PRODUCTS:
                    case AppHome::$TYPE_NEW_PRODUCTS:
                    case AppHome::$TYPE_SPECIAL_OFFERS:
                        $products = $result['items'];
                        if ($useWebPath) {
                            $currentType = AppHome::$TYPE_PRODUCTS;
                        }

                        break;
                    case AppHome::$TYPE_MANUFACTURERS:
                        $manufacturers = $result['items'];

                        break;
                }
            }
        } else {
            switch ($type) {
                case AppHome::$TYPE_CAROUSEL:
                    $banners = $this->composeCarousel($id);

                    break;
                case AppHome::$TYPE_CATEGORIES:
                    $categories = $this->composeCategories($id);

                    break;
                case AppHome::$TYPE_LVL_ONE_CATEGORIES:
                    $categories = $this->composeLvlOneCategories($customerId);
                    $currentType = AppHome::$TYPE_CATEGORIES;

                    break;
                case AppHome::$TYPE_BANNER:
                    switch ($bannerType) {
                        case AppHome::$BANNER_TYPE_CATEGORY_BRAND:
                            $categoryBrandFacetsHandler = new CategoryBrandFacetsHandler($this->module);
                            $categoryBrand = $categoryBrandFacetsHandler->generateFacets($row['id_category'], $row['id_manufacturer']);
                            $webPath = 'products?'.$categoryBrand;
                            if ($useWebPath) {
                                $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;
                            }

                            break;
                        case AppHome::$BANNER_TYPE_NEW_PRODUCTS:
                            $webPath = 'new_products';
                            if ($useWebPath) {
                                $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;
                            }

                            break;
                        case AppHome::$BANNER_TYPE_SHOWCASE:
                            $webPath = 'products?home=1';
                            if ($useWebPath) {
                                $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;
                            }

                            break;
                        case AppHome::$BANNER_TYPE_SPECIAL_OFFERS:
                            $webPath = 'special_offers';
                            if ($useWebPath) {
                                $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;
                            }

                            break;
                        default:
                            $webPath = null;
                            if ($useWebPath) {
                                $currentBannerType = $bannerType;
                            }

                            break;
                    }

                    break;
            }
        }

        $baseUrl = ApiUtils::getFullDomain();
        $lang = Context::getContext()->language->id;

        $hash = $this->imageService->getImageHashByLang(AppHome::$TYPE_BANNER, $id, $lang);
        $image = $baseUrl.'/img/modules/'.$this->module->name.'/banners/'.$hash.'_original.jpg';

        return [
            'id' => $id,
            'title' => $this->getTitle($id),
            'weight' => $weight,
            'type' => $currentType,
            'image' => $image,
            'web_path' => $webPath,
            'carousel_type' => $row['carousel_type'],
            'banner_type' => $currentBannerType,
            'banner_style' => $bannerSize,
            'id_product' => (int) $row['id_product'],
            'id_category' => (int) $row['id_category'],
            'id_manufacturer' => (int) $row['id_manufacturer'],
            'id_cms' => (int) $row['id_cms'],
            'products' => $products,
            'manufacturers' => $manufacturers,
            'layout' => (int) $row['layout'],
            'category_brand' => $categoryBrand,
            'hide_title_in_home' => $hideTitleInHome,
            'banners' => $banners,
            'categories' => $categories
        ];
    }

    public function composeCarousel($id)
    {
        $banners = AppHomeItem::getBanners($id, Context::getContext()->language->id);

        $retval = [];
        $webPath = null;
        foreach ($banners as $banner) {
            $title = $banner['title'];
            $currentBannerType = $banner['banner_type'];
            switch ($banner['banner_type']) {
                case AppHome::$BANNER_TYPE_NEW_PRODUCTS:
                    $webPath = 'new_products';
                    $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;

                    break;
                case AppHome::$BANNER_TYPE_SHOWCASE:
                    $webPath = 'products?home=1';
                    $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;

                    break;
                case AppHome::$BANNER_TYPE_SPECIAL_OFFERS:
                    $webPath = 'special_offers';
                    $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;

                    break;
                case AppHome::$BANNER_TYPE_CATEGORY_BRAND:
                    $categoryBrandFacetsHandler = new CategoryBrandFacetsHandler($this->module);
                    $categoryBrand = $categoryBrandFacetsHandler->generateFacets((int) $banner['id_category'], (int) $banner['id_manufacturer']);
                    $webPath = 'products?'.$categoryBrand;
                    $currentBannerType = AppHome::$BANNER_TYPE_PRODUCTS;

                    break;

                default:
                    $webPath = null;
            }

            $baseUrl = ApiUtils::getFullDomain();
            $hash = $this->imageService->getImageHashByLang(AppHome::$TYPE_CAROUSEL, (int) $banner['id_app_home_item'], Context::getContext()->language->id);
            $image = '';
            if (!empty($hash)) {
                $image = $baseUrl.'img/modules/'.$this->module->name.'/carousel/'.$hash.'_original.jpg';
            }
            $retval[] = [
                'id' => $banner['id_app_home_item'],
                'banner_type' => $currentBannerType,
                'image' => $image,
                'title' => $title,
                'web_path' => $webPath,
                'id_product' => isset($banner['id_product']) ? (int) $banner['id_product'] : null,
                'id_category' => isset($banner['id_category']) ? (int) $banner['id_category'] : null,
                'id_manufacturer' => isset($banner['id_manufacturer']) ? (int) $banner['id_manufacturer'] : null,
                'id_cms' => isset($banner['id_cms']) ? (int) $banner['id_cms'] : null
            ];
        }

        return $retval;
    }

    public function composeCategories($id)
    {
        // Not used this type at the moment
        // Usefull for future integration
        $idLang = (int) Context::getContext()->language->id;

        return [];
    }

    public function composeLvlOneCategories($customerId = null)
    {
        $limit = 10;
        $offset = 0;

        $idLang = (int) Context::getContext()->language->id;
        $parentCategory = !empty($parentIdCategory) ? $parentIdCategory : Configuration::get('PS_HOME_CATEGORY');

        $hasProductsQuery = '(SELECT cp.`id_product` 
                    FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
                    INNER JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
                    WHERE cp.`id_category` = a.id_category
                    AND product_shop.`active` = 1
                    AND product_shop.`visibility` in ("both","catalog") 
                    ORDER BY cp.position LIMIT 1) AS first_product_id';

        $query = 'SELECT DISTINCT a.id_category AS id, 
                         a.id_parent   AS parent_id, 
                         al.name, 
                         a.`level_depth` AS level_depth,
                         category_shop.`position` AS position,
                        '.$hasProductsQuery.'
                            FROM   `'._DB_PREFIX_.'category` a
                            '.Shop::addSqlAssociation('category', 'a').'
                            LEFT JOIN `'._DB_PREFIX_.'category_lang` al ON al.id_category = a.id_category'.Shop::addSqlRestrictionOnLang('al').'
                            INNER JOIN `'._DB_PREFIX_.'category_group` ag ON a.id_category = ag.id_category WHERE a.active = 1
                      AND al.id_lang = '.pSQL($idLang).($customerId !== null
                ? ' AND ag.id_group IN (SELECT id_group FROM `'._DB_PREFIX_.'customer_group` cg WHERE cg.id_customer = '.pSQL((int) $customerId).')'
                : ' AND al.id_lang = '.pSQL($idLang).' AND ag.id_group='.Configuration::get('PS_GUEST_GROUP')).' AND a.id_parent = '.(int) $parentCategory
            .' ORDER BY a.level_depth ASC, a.position ASC'
            .' LIMIT '.$offset.','.$limit;

        $baseWs = new BaseWS($this->dataHandler);
        $result = $baseWs->refactorData($query);
        $output = [];

        if (ApiUtils::isArray($result)) {
            foreach ($result as $item) {
                $hasProducts = $item['first_product_id'] != null;
                $imgPath = $item['id'].'-0_thumb.jpg';
                if (file_exists(_PS_CAT_IMG_DIR_.$imgPath)) {
                    $productImage = ApiUtils::getFullDomain(false)._THEME_CAT_DIR_.$imgPath;
                } elseif ($hasProducts) {
                    $idProduct = (int) $item['first_product_id'];
                    $coverImages = ProductBaseWS::getCoverImages($idProduct);
                    $productImage = $coverImages['image'];
                } else {
                    $productImage = null;
                }

                $output[] = [
                    'id' => $item['id'],
                    'id_parent' => $item['parent_id'],
                    'name' => $item['name'],
                    'image' => $productImage,
                    'position' => $item['position'],
                    'has_products' => $hasProducts,
                ];
            }
        }

        return $output;
    }

    public function getSectionsWS($customerId)
    {
        if (Context::getContext()->customer->logged) {
            $groups = Customer::getGroupsStatic($customerId);
        } else {
            $groups = [Configuration::get('PS_UNIDENTIFIED_GROUP'), Configuration::get('PS_GUEST_GROUP')];
        }

        $where = 'ah.active = 1 && (ah.display_in_all_groups = 1 OR ah.display_in_all_groups = 0 && ahg.id_group IN ('.implode(',', array_map('intval', $groups)).'))';
        if ($this->dataHandler->getApiVersion() <= 3) {
            $where .= ' AND ah.`type` != "'.AppHome::$TYPE_MANUFACTURERS.'"';
        }

        $query = '
          SELECT DISTINCT ah.* 
          FROM `'._DB_PREFIX_.'app_home` ah
          LEFT JOIN `'._DB_PREFIX_.'app_home_group` ahg ON ah.id_app_home=ahg.id_app_home
          WHERE '.$where.' 
          ORDER BY ah.position ASC, ah.id_app_home ASC
        ';

        $recordset = Db::getInstance()->executeS($query);

        $output = [];
        $weight = 0;
        foreach ($recordset as $row) {
            if ($this->typeSupportedOnApi($row['type'])) {
                continue;
            }
            $output[] = $this->composeSection($row, ++$weight, $customerId);
        }

        return $output;
    }

    /**
     * Retrieve banner hash by id
     *
     * @param $id
     *
     * @return false|string|null
     */
    public function getBannerHash($id)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `hash` FROM `'._DB_PREFIX_.'app_home`
            WHERE `id_app_home` = "'.pSQL($id).'"
        ');
    }

    private function isOrderedSectionType($type)
    {
        $notOrderedSectionTypes = [
            AppHome::$TYPE_BEST_SELLER,
            AppHome::$TYPE_MANUFACTURERS,
            AppHome::$TYPE_SHOWCASE_PRODUCTS,
            AppHome::$TYPE_CATEGORY_PRODUCTS,
            AppHome::$TYPE_NEW_PRODUCTS,
            AppHome::$TYPE_SPECIAL_OFFERS
        ];

        return in_array($type, $notOrderedSectionTypes);
    }

    private function typeSupportedOnApi($type, $api_version = 11)
    {
        return in_array($type, AppHome::getTypesSupported($api_version)) && $this->dataHandler->getApiVersion() < $api_version;
    }
}
