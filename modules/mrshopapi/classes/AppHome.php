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
class AppHome extends ObjectModel
{
    public static $TYPE_BANNER = 'BANNER';

    public static $TYPE_CAROUSEL = 'CAROUSEL';

    public static $TYPE_LVL_ONE_CATEGORIES = 'LVL_ONE_CATEGORIES';

    public static $TYPE_BEST_SELLER = 'BEST_SELLER';

    public static $TYPE_CATEGORIES = 'CATEGORIES';

    public static $TYPE_SHOWCASE_PRODUCTS = 'SHOWCASE_PRODUCTS';

    public static $TYPE_NEW_PRODUCTS = 'NEW_PRODUCTS';

    public static $TYPE_SPECIAL_OFFERS = 'SPECIAL_OFFERS';

    public static $TYPE_MANUFACTURERS = 'MANUFACTURERS';

    public static $TYPE_PRODUCTS = 'PRODUCTS';

    public static $TYPE_CATEGORY_PRODUCTS = 'CATEGORY_PRODUCTS';

    public static $CAROUSEL_DEFAULT = 'CAROUSEL_DEFAULT';

    public static $CAROUSEL_FULL = 'CAROUSEL_FULL';

    public static $CAROUSEL_MARGIN = 'CAROUSEL_MARGIN';

    public static $BANNER_TYPE_NO_LINK = 'BANNER_NO_LINK';

    public static $BANNER_TYPE_PRODUCT_DETAIL = 'BANNER_PRODUCT_DETAIL';

    public static $BANNER_TYPE_CATEGORY_BRAND = 'BANNER_CATEGORY_BRAND';

    public static $BANNER_TYPE_SPECIAL_OFFERS = 'BANNER_SPECIAL_OFFERS';

    public static $BANNER_TYPE_SHOWCASE = 'BANNER_SHOWCASE';

    public static $BANNER_TYPE_NEW_PRODUCTS = 'BANNER_NEW_PRODUCTS';

    public static $BANNER_TYPE_CMS = 'BANNER_CMS';

    public static $BANNER_TYPE_PRODUCTS = 'BANNER_PRODUCTS';

    public static $BANNER_SIZE_RECTANGLE_HORIZONTAL = 'BANNER_RECTANGLE_HORIZONTAL';

    public static $BANNER_SIZE_RECTANGLE_HORIZONTAL_HALF = 'BANNER_RECTANGLE_HORIZONTAL_HALF';

    public static $BANNER_SIZE_RECTANGLE_VERTICAL = 'BANNER_RECTANGLE_VERTICAL';

    public static $BANNER_SIZE_RECTANGLE_VERTICAL_HALF = 'BANNER_RECTANGLE_VERTICAL_HALF';

    public static $BANNER_SIZE_SQUARE = 'BANNER_SQUARE';

    public static $BANNER_SIZE_SQUARE_HALF = 'BANNER_SQUARE_HALF';

    const SORT_NAME_ASC       = 'product.name.asc';

    const SORT_NAME_DESC      = 'product.name.desc';

    const SORT_PRICE_ASC      = 'product.price.asc';

    const SORT_PRICE_DESC     = 'product.price.desc';

    const SORT_CREATE_ASC     = 'product.date_add.asc';

    const SORT_CREATE_DESC    = 'product.date_add.desc';

    const SORT_UPDATE_ASC     = 'product.date_upd.asc';

    const SORT_UPDATE_DESC    = 'product.date_upd.desc';

    const SORT_BRAND_ASC      = 'product.manufacturer_name.asc';

    const SORT_BRAND_DESC     = 'product.manufacturer_name.desc';

    const SORT_QUANT_ASC      = 'product.quantity.asc';

    const SORT_QUANT_DESC     = 'product.quantity.desc';

    const SORT_REFERENCE_ASC  = 'product.reference.asc';

    const SORT_REFERENCE_DESC = 'product.reference.desc';

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'app_home',
        'primary' => 'id_app_home',
        'multilang' => true,
        'fields' => [
            'carousel_type'      => ['type' => self::TYPE_STRING],
            'position'           => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'active'             => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true],
            'title'              => ['type' => self::TYPE_STRING, 'lang' => true, 'required' => false, 'size' => 255],
            'type'               => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => true, 'size' => 50],
            'banner_type'        => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 50],
            'banner_size'        => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 50],
            'order_by'           => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 50],
            'layout'             => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_product'         => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_category'        => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_manufacturer'    => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_cms'             => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'active_from'        => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 255],
            'active_to'          => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 255],
            'hash'               => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 32],
            'hide_title_in_home' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true],
            'display_in_all_groups' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => false]
        ],
    ];

    /** @var int default 0 */
    public $position;

    /** @var bool Active */
    public $active;

    /** @var string Date */
    public $active_from;

    /** @var string Date */
    public $active_to;

    /** @var string Title */
    public $title;

    /** @var string Type */
    public $type;

    /** @var string Banner type */
    public $banner_type;

    /** @var string Carousel type */
    public $carousel_type;

    /** @var string Banner size */
    public $banner_size;

    /** @var string Order by */
    public $order_by;

    /** @var int default Layout */
    public $layout;

    /** @var int default Product id */
    public $id_product;

    /** @var int default Category id */
    public $id_category;

    /** @var int default Manufacturer id */
    public $id_manufacturer;

    /** @var int default CMS page id */
    public $id_cms;

    /** @var string Image hash */
    public $hash;

    /** @var bool $hide_title_in_home */
    public $hide_title_in_home;

    /** @var bool display_in_all_groups */
    public $display_in_all_groups;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->active = false;
        $this->type = self::$TYPE_BANNER;
        parent::__construct($id, $id_lang, $id_shop);
    }

    protected $webserviceParameters = [];

    public static function getValidTypes()
    {
        return [
            self::$TYPE_BANNER,
            self::$TYPE_SHOWCASE_PRODUCTS,
            self::$TYPE_SPECIAL_OFFERS,
            self::$TYPE_NEW_PRODUCTS,
            self::$TYPE_MANUFACTURERS,
            self::$TYPE_CAROUSEL,
            self::$TYPE_CATEGORIES,
            self::$TYPE_LVL_ONE_CATEGORIES,
            self::$TYPE_CATEGORY_PRODUCTS,
            self::$TYPE_BEST_SELLER
        ];
    }

    public static function getValidBannerTypes()
    {
        return [
            self::$BANNER_TYPE_NO_LINK,
            self::$BANNER_TYPE_PRODUCT_DETAIL,
            self::$BANNER_TYPE_CATEGORY_BRAND,
            self::$BANNER_TYPE_SPECIAL_OFFERS,
            self::$BANNER_TYPE_NEW_PRODUCTS,
            self::$BANNER_TYPE_SHOWCASE,
            self::$BANNER_TYPE_CMS,
        ];
    }

    public static function getValidBannerSizes()
    {
        return [
            self::$BANNER_SIZE_RECTANGLE_HORIZONTAL,
            self::$BANNER_SIZE_RECTANGLE_HORIZONTAL_HALF,
            self::$BANNER_SIZE_RECTANGLE_VERTICAL,
            self::$BANNER_SIZE_RECTANGLE_VERTICAL_HALF,
            self::$BANNER_SIZE_SQUARE,
            self::$BANNER_SIZE_SQUARE_HALF,
        ];
    }

    public static function getValidCarouselTypes()
    {
        return [
            self::$CAROUSEL_DEFAULT,
            self::$CAROUSEL_FULL,
            self::$CAROUSEL_MARGIN
        ];
    }

    public static function activateBanners()
    {
        // Deactivate all banners with date interval management
        // Activate only banners with current date in specified date interval
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
            UPDATE `'._DB_PREFIX_.'app_home`
            SET `active` = 0
            WHERE `type` = "'.pSQL(self::$TYPE_BANNER).'" 
            AND (`active_from` != 0 OR `active_to` != 0);
            UPDATE `'._DB_PREFIX_.'app_home`
            SET `active` = 1
            WHERE `type` = "'.pSQL(self::$TYPE_BANNER).'" 
            AND (
                (`active_from` != 0 AND `active_to` != 0 AND NOW() BETWEEN `active_from` AND `active_to`) OR
                (`active_from` != 0 AND `active_to` = 0 AND NOW() >= `active_from`) OR
                (`active_from` = 0 AND `active_to` != 0 AND NOW() <= `active_to`)
            )');
    }

    public static function getAvailableOrders()
    {
        return [
            self::SORT_NAME_ASC,
            self::SORT_NAME_DESC,
            self::SORT_PRICE_ASC,
            self::SORT_PRICE_DESC,
            self::SORT_CREATE_ASC,
            self::SORT_CREATE_DESC,
            self::SORT_UPDATE_ASC,
            self::SORT_UPDATE_DESC,
            self::SORT_BRAND_ASC,
            self::SORT_BRAND_DESC,
            self::SORT_QUANT_ASC,
            self::SORT_QUANT_DESC,
            self::SORT_REFERENCE_ASC,
            self::SORT_REFERENCE_DESC,
        ];
    }

    public static function getTypesWithLayout()
    {
        return [
            self::$TYPE_SHOWCASE_PRODUCTS,
            self::$TYPE_NEW_PRODUCTS,
            self::$TYPE_SPECIAL_OFFERS,
            self::$TYPE_BEST_SELLER,
            self::$TYPE_CATEGORY_PRODUCTS,
        ];
    }

    public static function getSubItemsType()
    {
        return [
            self::$TYPE_BANNER,
            self::$TYPE_CATEGORY_PRODUCTS
        ];
    }

    public static function getListCategoriesType()
    {
        return [
            self::$TYPE_CATEGORIES,
            self::$TYPE_LVL_ONE_CATEGORIES
        ];
    }

    public static function isConfigured()
    {
        $result = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
          SELECT COUNT(`id_app_home`) 
          FROM `'._DB_PREFIX_.'app_home` 
          WHERE active = 1');

        return $result > 0;
    }

    /*
        From API version >= 11
    */
    public static function getTypesSupported($api_version = 11)
    {
        return [
            AppHome::$TYPE_LVL_ONE_CATEGORIES,
            AppHome::$TYPE_CATEGORIES,
            AppHome::$TYPE_CAROUSEL,
            AppHome::$TYPE_CATEGORY_PRODUCTS,
            AppHome::$TYPE_BEST_SELLER
        ];
    }

    public static function updateImageLang($id, $hash, $iso = null)
    {
        if (isset($iso)) {
            Db::getInstance()->execute("UPDATE "._DB_PREFIX_."app_home
            SET `hash` = NULL
            WHERE id_app_home = ".(int) $id);
            $query = "UPDATE "._DB_PREFIX_."app_home_lang
            SET `hash` = '".pSQL($hash)."'
            WHERE id_app_home = ".(int) $id." AND id_lang = (
                SELECT "._DB_PREFIX_."lang.id_lang
                FROM "._DB_PREFIX_."lang
                WHERE "._DB_PREFIX_."lang.iso_code = '".pSQL($iso)."'
            )";

            return Db::getInstance()->execute($query);
        } else {
            Db::getInstance()->execute("UPDATE "._DB_PREFIX_."app_home_lang
            SET `hash` = NULL
            WHERE id_app_home = ".(int) $id);

            return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                UPDATE `'._DB_PREFIX_.'app_home`
                SET `hash` = "'.pSQL($hash).'"
                WHERE `id_app_home` = '.(int) $id);
        }
    }

    public static function count()
    {
        $query = "SELECT COUNT(*) as 'total'
        FROM "._DB_PREFIX_."app_home";
        $res = Db::getInstance()->executeS($query);

        return (int) $res[0]['total'];
    }
}
