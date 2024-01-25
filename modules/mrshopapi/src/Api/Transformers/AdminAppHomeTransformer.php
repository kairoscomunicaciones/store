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
 * @copyright Mr. APPs 2021
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Api\Transformers;

use AppHome;
use AppHomeCategory;
use AppHomeItem;
use Category;
use Context;
use Db;
use Language;
use Manufacturer;
use MrAPPs\MrShopApi\Handler\AppHomeHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use ObjectModelCore;
use Product;
use Validate;

class AdminAppHomeTransformer
{
    const TYPE_BANNER        = 'banner';

    const TYPE_CAROUSEL      = 'carousel';

    const TYPE_MANUFACTURERS = 'manufacturers';

    const TYPE_PRODUCTS      = 'products';

    const TYPE_SHOWCASE      = 'showcase-products';

    const TYPE_NEW_PRODUCTS  = "new-products";

    const TYPE_OFFERS        = "special_offers";

    const CAROUSEL_DEFAULT = 'carousel-default';

    const CAROUSEL_FULL = 'carousel-full';

    const CAROUSEL_MARGIN = 'carousel-margin';
    
    const BANNER_NOLINK         = 'no-link';

    const BANNER_PRODUCT_DETAIL = 'detail';

    const BANNER_CAT_BRAND      = 'category-manufacturer';

    const BANNER_OFFERS         = "special-offers";

    const BANNER_NEW_PRODUCTS   = "new-products";

    const BANNER_SHOWCASE       = "showcase";

    const BANNER_CMS            = "cms";
    
    const LAYOUT_HORIZONTAL = "horizontal";

    const LAYOUT_VERTICAL   = "vertical";
    
    const ACTIVATION_MANUALLY      = 'manually';

    const ACTIVATION_AUTOMATICALLY = 'automatically';
    
    const JSON_ID          = 'id';

    const JSON_TITLE       = 'title';

    const JSON_TYPE        = 'type';

    const JSON_BANNER_TYPE = 'bannerType';

    const JSON_BANNER_SIZE = 'bannerSize';

    const JSON_HIDE_TITLE_IN_HOME = 'hideTitleInHome';

    const JSON_LAYOUT      = 'layout';

    const JSON_ACTIVE      = 'active';

    const JSON_ACTIVE_FROM = 'activeFrom';

    const JSON_ACTIVE_TO   = 'activeTo';

    const JSON_ACTIVATION_TYPE = 'activationType';

    const JSON_ORDER        = 'order';

    const JSON_ORDER_TYPE   = 'orderType';

    const JSON_HAS_MULTILANG_IMAGE        = 'hasMultiLangImage';

    const JSON_IMAGE        = 'image';

    const JSON_PRODUCT      = 'product';

    const JSON_MANUFACTURER = 'manufacturer';

    const JSON_CMS          = 'cms';

    const JSON_CATEGORY     = 'category';

    const JSON_CAROUSEL_TYPE = 'carouselType';

    const JSON_BANNERS = 'banners';

    const JSON_DISPLAY_GROUPS = 'showToAllGroups';

    const JSON_GROUPS = 'groupsToShow';
    
    const JSON_ID_PRODUCT      = 'id_product';

    const JSON_ID_MANUFACTURER = 'id_manufacturer';

    const JSON_ID_CMS          = 'id_cms';

    const JSON_ID_CATEGORY     = 'id_category';
     
    const DATE_FORMAT       = 'Y-m-d H:i:s';
    
    public static function jsonCollection($items, $dataHandler, $imageService)
    {
        if (false == is_array($items) || ApiUtils::isAssociative($items)) {
            throw new \UnexpectedValueException('Argument has to be a positional array');
        }

        $homeIds = array_map(function ($item) {
            return (int) $item['id_app_home'];
        }, $items);
        
        $titles = static::loadTranslatedTitles($homeIds);

        return array_map(function ($item) use ($titles, $dataHandler, $imageService) {
            return static::jsonItem($item, $dataHandler, $imageService, $titles);
        }, $items);
    }
    
    public static function jsonItem($item, $dataHandler, $imageService, $titles = null)
    {
        if (!is_array($item) || !ApiUtils::isAssociative($item)) {
            throw new \UnexpectedValueException('Argument has to be an associative array');
        }

        $groupsToShow = null;
        $type = $item['type'];
        $orderBy = trim($item['order_by']);
        $layout = (int) $item['layout'];
        $showToAllGroups = (bool) $item['display_in_all_groups'];
        if (!$showToAllGroups) {
            $groupsToShow = self::presentGroups((int) $item['id_app_home']);
            $groupsToShow = array_map('intval', $groupsToShow);
        }

        $activationType = self::isEmptyDate($item['active_from']) && self::isEmptyDate($item['active_to'])
            ? self::ACTIVATION_MANUALLY
            : self::ACTIVATION_AUTOMATICALLY;

        $active = false == empty((int) $item['active']);

        if (!self::isEmptyDate($item['active_from'])) {
            if (is_numeric($item['active_from'])) {
                $activeFrom = new \DateTime();
                $activeFrom->setTimestamp($item['active_from'] / 1000);
            } else {
                $activeFrom = new \DateTime($item['active_from']);
            }
        } else {
            $activeFrom = null;
        }

        if (!self::isEmptyDate($item['active_to'])) {
            if (is_numeric($item['active_to'])) {
                $activeTo = new \DateTime();
                $activeTo->setTimestamp($item['active_to'] / 1000);
            } else {
                $activeTo = new \DateTime($item['active_to']);
            }
        } else {
            $activeTo = null;
        }

        if ($activationType == self::ACTIVATION_AUTOMATICALLY) {
            $active = self::isActive($item['active_from'], $item['active_to']);
        }

        $product = null;
        $category = null;
        $manufacturer = null;
        $cms = null;

        if (!empty($item['id_product'])) {
            $product = self::presentProduct($item['id_product']);
        }

        if ($item['id_category']) {
            $category = self::presentCategory($item['id_category']);
        }

        if ($item['id_manufacturer']) {
            $manufacturer = self::presentManufacturer($item['id_manufacturer']);
        }

        if ($item['id_cms']) {
            $cms = self::presentCmsPage($item['id_cms']);
        }

        $banners = [];
        $categories = [];
        $related = [];
        $image = null;
        $thumbnails = [];
        $webPath = null;

        $hasBannerMultiLang = false;
        if ($type == AppHome::$TYPE_BANNER) {
            // Default language
            $image = $imageService->getImagesByLang((int) $item['id_app_home'], AppHome::$TYPE_BANNER);
            if (false == isset($image['original'])) {
                $hasBannerMultiLang = true;
            }
        }

        if ($type == AppHome::$TYPE_CAROUSEL) {
            $appHomeBanners = AppHomeItem::getBanners((int) $item['id_app_home'], Context::getContext()->language->id);
            foreach ($appHomeBanners as $banner) {
                $hasBannerMultiLangImage = false;
                $bannerImage = $imageService->getImagesByLang((int) $banner['id_app_home_item'], AppHome::$TYPE_CAROUSEL);
                if (false == isset($bannerImage['original'])) {
                    $hasBannerMultiLangImage = true;
                }

                $bannerTitle = AppHomeItem::loadTitles((int) $banner['id_app_home_item']);

                $banners[] = [
                    'id' => (int) $banner['id_app_home_item'],
                    'bannerType' => $banner['banner_type'],
                    'title' => $bannerTitle,
                    'image' => $bannerImage,
                    'hasMultiLangImage' => $hasBannerMultiLangImage,
                    'product' => isset($banner['id_product']) ? [
                        'id' => (int) $banner['id_product'],
                        'name' => $banner['product_name']
                    ] : null,
                    'category' => isset($banner['id_category']) ? [
                        'id' => (int) $banner['id_category'],
                        'name' => $banner['category_name']
                    ] : null,
                    'manufacturer' => isset($banner['id_manufacturer']) ? [
                        'id' => (int) $banner['id_manufacturer'],
                        'name' => $banner['manufacturer_name']
                    ] : null,
                    'cms' => isset($banner['id_cms']) ? [
                        'id' => (int) $banner['id_cms'],
                        'name' => $banner['cms_name']
                    ] : null
                ];
            }
        } elseif ($type == AppHome::$TYPE_CATEGORIES) {
            $categories = AppHomeCategory::findCategories((int) $item['id_app_home']);
        } elseif ($type == AppHome::$TYPE_LVL_ONE_CATEGORIES) {
            $appHomeHandler = new AppHomeHandler($dataHandler);
            $categories = $appHomeHandler->composeLvlOneCategories();
        } elseif ($type != AppHome::$TYPE_BANNER) {
            $result = AppHomeHandler::getItems($dataHandler, $item, $layout, $orderBy);

            if ($result) {
                $related = $result['items'];
                $webPath = $result['web_path'];
            }
        }

        $v = [
            self::JSON_ID    => (int) $item['id_app_home'],
            self::JSON_TITLE => isset($titles[(int) $item['id_app_home']])
                                    ? $titles[(int) $item['id_app_home']]
                                    : null,
            self::JSON_CAROUSEL_TYPE => isset($item['carousel_type']) ? $item['carousel_type'] : null,
            self::JSON_TYPE           => $type,
            self::JSON_HAS_MULTILANG_IMAGE => $hasBannerMultiLang,
            self::JSON_IMAGE          => $image,
            self::JSON_BANNER_TYPE => $item['banner_type'],
            self::JSON_BANNER_SIZE => empty($item['banner_size']) ? AppHome::$BANNER_SIZE_RECTANGLE_HORIZONTAL : $item['banner_size'],
            self::JSON_HIDE_TITLE_IN_HOME => isset($item['hide_title_in_home']) && $item['hide_title_in_home'],
            self::JSON_LAYOUT      => (int) $item['layout'],
            self::JSON_ACTIVE      => $active,
            self::JSON_ACTIVE_FROM => isset($activeFrom)
                                    ? $activeFrom->getTimestamp() * 1000
                                    : null,
            self::JSON_ACTIVE_TO   => isset($activeTo)
                                    ? $activeTo->getTimestamp() * 1000
                                    : null,
            self::JSON_ACTIVATION_TYPE => $activationType,
            self::JSON_ORDER         => $item['position'],
            self::JSON_ORDER_TYPE    => $item['order_by'],
            self::JSON_PRODUCT       => $product,
            self::JSON_MANUFACTURER  => $manufacturer,
            self::JSON_CATEGORY      => $category,
            self::JSON_CMS           => $cms,
            'thumbnails'             => $thumbnails,
            'items'                  => $related,
            'banners'                => $banners,
            'categories'             => $categories,
            'webPath' => $webPath,
            self::JSON_DISPLAY_GROUPS => $showToAllGroups,
            self::JSON_GROUPS => $groupsToShow

        ];

        return $v;
    }
    
    public static function dbItem($item)
    {
        if (!isset($item[self::JSON_TYPE])) {
            return [];
        }
        
        $type = $item[self::JSON_TYPE];
        
        if ($type == AppHome::$TYPE_BANNER) {
            return static::transformBanner($item);
        } elseif (in_array($type, AppHome::getTypesWithLayout())) {
            return static::transformLayoutable($item);
        } else {
            return static::transformDefault($item);
        }
    }
    
    protected static function transformDefault($item)
    {
        $defs   = AppHome::$definition['fields'];
        $map    = self::jsonToDb();
        $retval = [];

        foreach ($map as $jfield => $field) {
            $d   = $defs[$field];
            
            if (!isset($item[$jfield])) {
                $retval[$field] = null;

                continue;
            }

            $val = $item[$jfield];
            switch ($jfield) {
                case self::JSON_BANNER_TYPE:
                case self::JSON_BANNER_SIZE:
                case self::JSON_LAYOUT:
                case self::JSON_ORDER_TYPE:
                case self::JSON_ID_PRODUCT:
                case self::JSON_ID_CATEGORY:
                case self::JSON_ID_MANUFACTURER:
                case self::JSON_ID_CMS:
                    $value = null;

                    break;
                case self::JSON_HIDE_TITLE_IN_HOME:
                    $value = $val;

                    break;
                case self::JSON_ACTIVE_FROM:
                case self::JSON_ACTIVE_TO:
                    if (self::isEmptyDate($val)) {
                        $value = null;
                    } else {
                        if (is_numeric($val)) {
                            $d = new \DateTime();
                            $d->setTimestamp($val / 1000);
                            $value = $d->format('Y-m-d H:i:s');
                        } else {
                            $value = $val;
                        }
                    }

                    break;
                case self::JSON_ACTIVE:
                    $f = isset($item[self::JSON_ACTIVE_FROM])
                        ? $item[self::JSON_ACTIVE_FROM]
                        : null;

                    $t = isset($item[self::JSON_ACTIVE_TO])
                        ? $item[self::JSON_ACTIVE_TO]
                        : null;
                    if (isset($f) && isset($t)) {
                        $value = self::isActive($f, $t);
                    } else {
                        $value = (bool) $val;
                    }

                    break;
                case self::JSON_DISPLAY_GROUPS:
                    $value = (bool) $val;

                    break;
                default:
                    $value = static::transformFromDefinition($d, $val);

                    break;
            }
            
            $retval[$field] = $value;
        }
        
        return $retval;
    }
    
    protected static function transformBanner($item)
    {
        $defs   = AppHome::$definition['fields'];
        $map    = self::jsonToDb();
        $retval = [];

        foreach ($map as $jfield => $field) {
            $d   = $defs[$field];
            
            if (!isset($item[$jfield])) {
                $retval[$field] = null;

                continue;
            }
            
            $val = $item[$jfield];
            
            switch ($jfield) {
                case self::JSON_ACTIVE_FROM:
                case self::JSON_ACTIVE_TO:
                    if (self::isEmptyDate($val)) {
                        $value = null;
                    } else {
                        if (is_numeric($val)) {
                            $d = new \DateTime();
                            $d->setTimestamp($val / 1000);
                            $value = $d->format('Y-m-d H:i:s');
                        } else {
                            $value = $val;
                        }
                    }

                    break;
                case self::JSON_LAYOUT:
                case self::JSON_ORDER_TYPE:
                case self::JSON_IMAGE:
                    $value = null;

                    break;
                case self::JSON_ACTIVE:
                    $f = isset($item[self::JSON_ACTIVE_FROM])
                            ? $item[self::JSON_ACTIVE_FROM]
                            : null;

                    $t = isset($item[self::JSON_ACTIVE_TO])
                            ? $item[self::JSON_ACTIVE_TO]
                            : null;
                    if (isset($f) && isset($t)) {
                        $value = self::isActive($f, $t);
                    } else {
                        $value = (bool) $val;
                    }

                    break;
                default:
                    $value = static::transformFromDefinition($d, $val);

                    break;
            }
            
            $retval[$field] = $value;
        }
        
        return $retval;
    }
    
    protected static function transformLayoutable($item)
    {
        $defs   = AppHome::$definition['fields'];
        $map    = self::jsonToDb();
        $retval = [];
        
        foreach ($map as $jfield => $field) {
            $d   = $defs[$field];
            
            if (!isset($item[$jfield])) {
                $retval[$field] = null;

                continue;
            }
            
            $val = $item[$jfield];
            switch ($jfield) {
                case self::JSON_BANNER_TYPE:
                case self::JSON_BANNER_SIZE:
                case self::JSON_ID_PRODUCT:
                case self::JSON_ID_MANUFACTURER:
                case self::JSON_ID_CMS:
                    $value = null;

                    break;
                case self::JSON_ID_CATEGORY:
                    $value = $item['type'] == AppHome::$TYPE_CATEGORY_PRODUCTS ? (int) $val : null;

                    break;
                case self::JSON_HIDE_TITLE_IN_HOME:
                    $value = $val;

                    break;
                case self::JSON_ACTIVE_FROM:
                case self::JSON_ACTIVE_TO:
                    if (self::isEmptyDate($val)) {
                        $value = null;
                    } else {
                        if (is_numeric($val)) {
                            $d = new \DateTime();
                            $d->setTimestamp($val / 1000);
                            $value = $d->format('Y-m-d H:i:s');
                        } else {
                            $value = $val;
                        }
                    }

                    break;
                case self::JSON_ACTIVE:
                    $f = isset($item[self::JSON_ACTIVE_FROM])
                        ? $item[self::JSON_ACTIVE_FROM]
                        : null;

                    $t = isset($item[self::JSON_ACTIVE_TO])
                        ? $item[self::JSON_ACTIVE_TO]
                        : null;
                    if (isset($f) && isset($t)) {
                        $value = self::isActive($f, $t);
                    } else {
                        $value = (bool) $val;
                    }

                    break;
                default:
                    $value = static::transformFromDefinition($d, $val);

                    break;
            }
            
            $retval[$field] = $value;
        }
        
        return $retval;
    }
    
    protected static function isActive($dateFrom, $dateTo)
    {
        $now = new \DateTime();

        $startedFrom = 0;
        if (!self::isEmptyDate($dateFrom)) {
            if (is_numeric($dateFrom)) {
                $activeFrom = new \DateTime();
                $activeFrom->setTimestamp($dateFrom / 1000);
            } else {
                $activeFrom = new \DateTime($dateFrom);
            }
            $startedFrom = $now->getTimestamp() - $activeFrom->getTimeStamp();
        }

        $endsIn = 0;
        if (!self::isEmptyDate($dateTo)) {
            if (is_numeric($dateFrom)) {
                $activeTo = new \DateTime();
                $activeTo->setTimestamp($dateTo / 1000);
            } else {
                $activeTo = new \DateTime($dateTo);
            }
            $endsIn = $activeTo->getTimestamp() - $now->getTimeStamp();
        }

        return $startedFrom >= 0 && $endsIn >= 0;
    }
    
    protected static function isEmptyDate($date)
    {
        return empty($date) || $date == '0000-00-00 00:00:00';
    }
    
    protected static function transformFromDefinition($d, $val)
    {
        if (!empty($d['lang'])) {
            return static::langToDb($val);
        } else {
            return static::baseTransform($d, $val);
        }
    }
    
    protected static function baseTransform($def, $val)
    {
        switch ($def['type']) {
            case ObjectModelCore::TYPE_INT:
                return (int) $val;
            case ObjectModelCore::TYPE_BOOL:
                return false == empty($val);
            default:
                return $val;
        }
    }
    
    public static function loadTranslatedTitles($homeIds)
    {
        $retval = [];
        $i = is_array($homeIds)
                ? $homeIds
                : [$homeIds];
        
        $ids = array_map(function ($item) {
            return (int) $item;
        }, $i);
        
        if (empty($ids)) {
            return $retval;
        }

        $query = "SELECT *
            FROM "._DB_PREFIX_."app_home_lang ahl
            INNER JOIN "._DB_PREFIX_."lang l ON ahl.id_lang = l.id_lang
            WHERE id_app_home IN (".implode(',', $ids).") AND l.active = 1
            ORDER BY ahl.id_app_home";
        
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        
        foreach ($results as $row) {
            $id_home = $row['id_app_home'];
            if (empty($retval[$id_home])) {
                $retval[$id_home] = [];
            }
            
            $iso = Language::getIsoById($row['id_lang']);
            $retval[$id_home][$iso] = $row['title'];
        }
        
        return $retval;
    }
    
    public static function jsonToDbField($field)
    {
        $map = static::jsonToDb();
        
        if (!isset($map[$field])) {
            throw new \InvalidArgumentException("Field $field does not exists");
        }
        
        return $map[$field];
    }
    
    public static function langToDb($langField)
    {
        $retval = [];

        if (is_array($langField) && !empty($langField)) {
            foreach ($langField as $iso => $value) {
                if (!Validate::isLanguageIsoCode($iso)) {
                    throw new \UnexpectedValueException($iso.' is not a valid iso code');
                }
                
                $idLang = (int) Language::getIdByIso($iso);
                $retval[$idLang] = !empty($value) ? $value : null;
            }
        }
        
        return $retval;
    }
    
    public static function jsonToDb()
    {
        return [
            self::JSON_TITLE       => 'title',
            self::JSON_TYPE        => 'type',
            self::JSON_CAROUSEL_TYPE => 'carousel_type',
            self::JSON_BANNER_TYPE => 'banner_type',
            self::JSON_BANNER_SIZE => 'banner_size',
            self::JSON_HIDE_TITLE_IN_HOME => 'hide_title_in_home',
            self::JSON_LAYOUT      => 'layout',
            self::JSON_ACTIVE      => 'active',
            self::JSON_ACTIVE_FROM => 'active_from',
            self::JSON_ACTIVE_TO   => 'active_to',
            self::JSON_ORDER       => 'position',
            self::JSON_ORDER_TYPE  => 'order_by',
            self::JSON_ID_PRODUCT      => 'id_product',
            self::JSON_ID_MANUFACTURER => 'id_manufacturer',
            self::JSON_ID_CMS          => 'id_cms',
            self::JSON_ID_CATEGORY     => 'id_category',
            self::JSON_IMAGE           => 'hash',
            self::JSON_DISPLAY_GROUPS  => 'display_in_all_groups'
        ];
    }
    
    protected static function presentProduct($product_id)
    {
        $id_lang = Context::getContext()->language->id;
        $product = new Product($product_id, false, $id_lang);
        
        if (Validate::isLoadedObject($product)) {
            return [
                'id'   => $product->id,
                'name' => $product->name
            ];
        }
        
        return null;
    }
    
    protected static function presentCategory($category_id)
    {
        $id_lang = Context::getContext()->language->id;
        $category = new Category($category_id, $id_lang);
        
        if (Validate::isLoadedObject($category)) {
            return [
                'id'   => $category->id,
                'name' => $category->name
            ];
        }
        
        return null;
    }
    
    protected static function presentManufacturer($manufacturer_id)
    {
        $id_lang = Context::getContext()->language->id;
        $manufacturer = new Manufacturer($manufacturer_id, $id_lang);
        
        if (Validate::isLoadedObject($manufacturer)) {
            return [
                'id'   => $manufacturer->id,
                'name' => $manufacturer->name
            ];
        }
        
        return null;
    }
    
    protected static function presentCmsPage($id_cms)
    {
        $id_lang = Context::getContext()->language->id;
        $sql = 'SELECT a.id_cms as id, al.meta_title AS name
            FROM `'._DB_PREFIX_.'cms` a LEFT JOIN `'._DB_PREFIX_.'cms_lang` al ON a.id_cms = al.id_cms
            WHERE a.active = 1 AND al.id_lang = '.(int) $id_lang.'
                AND al.id_shop = '.(int) Context::getContext()->shop->id.'
                AND a.id_cms = '.(int) $id_cms.'
            ORDER BY position';
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        
        if (empty($res)) {
            return null;
        }

        return $res[0];
    }

    protected static function presentGroups($id_app_home)
    {
        $query = "SELECT id_group 
        FROM "._DB_PREFIX_."app_home_group
        WHERE id_app_home = ".(int) $id_app_home;
        $res = Db::getInstance()->executeS($query);

        return array_column($res, 'id_group');
    }
}
