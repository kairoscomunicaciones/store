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
class MrShopApiNotification extends ObjectModel
{
    public static $TYPE_GENERIC = 'GENERIC';

    public static $TYPE_PRODUCT_DETAIL = 'PRODUCT_DETAIL';

    public static $TYPE_CATEGORY_BRAND = 'CATEGORY_BRAND';

    public static $TYPE_SPECIAL_OFFERS = 'SPECIAL_OFFERS';

    public static $TYPE_NEW_PRODUCTS = 'NEW_PRODUCTS';

    public static $TYPE_CMS = 'CMS';

    public static $TYPE_ORDER_STATE_CHANGED = 'ORDER_STATE_CHANGED';

    public static $TYPE_PRODUCTS = 'PRODUCTS';

    public static $TYPE_ORDER_ASSISTANCE = 'ASSISTANCE';

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'notification',
        'primary' => 'id_notification',
        'multilang' => true,
        'fields' => [
            'date' => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => false, 'size' => 255],
            'active' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => false, 'size' => 255],
            'sent' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => false, 'size' => 255],
            'title' => ['type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'size' => 255],
            'type' => ['type' => self::TYPE_STRING, 'lang' => false, 'required' => true, 'size' => 50],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_category' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_manufacturer' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_cms' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_group' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
        ],
    ];

    public $id_notification;

    /** @var string Date */
    public $date;

    /** @var bool Active */
    public $active;

    /** @var bool Sent */
    public $sent;

    /** @var string Title */
    public $title;

    /** @var string Type */
    public $type;

    /** @var int default Product id */
    public $id_product;

    /** @var int default Category id */
    public $id_category;

    /** @var int default Manufacturer id */
    public $id_manufacturer;

    /** @var int default CMS page id */
    public $id_cms;

    /** @var int default group category id */
    public $id_group;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->date = date('Y-m-d H:i');
        $this->type = self::$TYPE_GENERIC;
        $this->active = false;
        $this->sent = false;
        parent::__construct($id, $id_lang, $id_shop);
    }

    protected $webserviceParameters = [];

    public static function getValidTypes()
    {
        return [
            self::$TYPE_GENERIC,
            self::$TYPE_PRODUCT_DETAIL,
            self::$TYPE_CATEGORY_BRAND,
            self::$TYPE_SPECIAL_OFFERS,
            self::$TYPE_NEW_PRODUCTS,
            self::$TYPE_CMS,
            self::$TYPE_ORDER_STATE_CHANGED,
        ];
    }

    public static function getNotificationsToSend()
    {
        $now = new \DateTime();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            "
		        SELECT DISTINCT n.id_notification, n.id_group
                 FROM "._DB_PREFIX_."notification n
                 WHERE n.sent = 0 AND n.date <= '".$now->format('Y-m-d H:i:s')."'"
        );

        return $result;
    }

    public static function getNotificationsPool()
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		        SELECT n.id, n.id_notification, n.id_customer, n.id_lang
                FROM '._DB_PREFIX_.'notification_pool n
                LIMIT 5
			');

        return $result;
    }

    public static function getNotification($id_notification, $id_language, $onlyActive = false)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT n.id_notification, n.date, nl.title, n.type, n.id_product, n.id_category, n.id_manufacturer, n.id_cms ,n.id_group,
                FROM '._DB_PREFIX_.'notification n
                INNER JOIN '._DB_PREFIX_.'notification_lang nl ON n.id_notification=nl.id_notification
                WHERE nl.id_lang='.(int) $id_language.' AND n.id_notification='.(int) $id_notification.($onlyActive ? ' AND active=1 ' : ''));
    }

    public static function countNotifications($onlyActive = false)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
				SELECT COUNT(*) FROM '._DB_PREFIX_.'notification'.($onlyActive ? ' WHERE active=1' : '')
        );

        return (int) $result[0]['COUNT(*)'];
    }

    public static function getPaginated($offset, $limit, $id_language, $id_customer, $onlyActive = false)
    {
        $now = new \DateTime();
        $sql = "
            SELECT n.id_notification, n.date, nl.title, n.type, n.id_product, n.id_category, n.id_manufacturer, n.id_cms ,n.id_group
            FROM `"._DB_PREFIX_."notification` n
            INNER JOIN "._DB_PREFIX_."notification_lang nl ON n.id_notification=nl.id_notification
            WHERE nl.id_lang=".(int) $id_language.($onlyActive ? " AND active=1 " : "")."
            AND n.date <= '".$now->format('Y-m-d H:i:s')."' AND  (n.id_group=0 || n.id_group IS NULL ".($id_customer == null ? "" : "|| n.id_group in ( SELECT id_group FROM "._DB_PREFIX_."customer_group c where c.id_customer=".(int) $id_customer.")").")
            ORDER BY n.date DESC,n.id_notification DESC 
            LIMIT ".(int) $limit.' OFFSET '.(int) $offset;

        return Db::getInstance()->executeS($sql);
    }

    public static function updateStatus($active, $ids)
    {
        if ($ids !== null) {
            $query = 'UPDATE `'._DB_PREFIX_.self::$definition['table'].'`
             SET active = '.(int) $active.' 
             WHERE '.self::$definition['primary'].' IN ('.implode(', ', array_map('intval', $ids)).')';

            return Db::getInstance()->execute($query);
        } else {
            return false;
        }
    }

    public static function deleteNotificationCustomerId($id_notification)
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_.'notification_pool` WHERE id_notification='.(int) $id_notification;
        Db::getInstance()->execute($sql);
    }

    public static function cleanDeleteById($id)
    {
        $query = "DELETE FROM "._DB_PREFIX_."notification_lang
        WHERE id_notification = ".$id;
        Db::getInstance()->execute($query);

        $query = "DELETE FROM "._DB_PREFIX_."notification
            WHERE id_notification = ".$id;
        Db::getInstance()->execute($query);
    }

    public static function getCategoryById($id, $id_lang = null)
    {
        $id_lang = isset($id_lang) ? $id_lang : Context::getContext()->language->id;
        $query = "SELECT "._DB_PREFIX_."category.id_category as 'id', "._DB_PREFIX_."category_lang.`name`
        FROM "._DB_PREFIX_."category INNER JOIN "._DB_PREFIX_."category_lang
        ON "._DB_PREFIX_."category.id_category = "._DB_PREFIX_."category_lang.id_category
        WHERE "._DB_PREFIX_."category.id_category = ".(int) $id." AND "._DB_PREFIX_."category_lang.id_lang = ".(int) $id_lang;
        $category = Db::getInstance()->executeS($query);

        return $category[0];
    }

    public static function getCmsById($id, $id_lang = null)
    {
        $id_lang = isset($id_lang) ? $id_lang : Context::getContext()->language->id;
        $query = "SELECT
            "._DB_PREFIX_."cms.id_cms as 'id',
            "._DB_PREFIX_."cms_lang.meta_title as 'name'
        FROM "._DB_PREFIX_."cms INNER JOIN "._DB_PREFIX_."cms_lang
        ON "._DB_PREFIX_."cms.id_cms = "._DB_PREFIX_."cms_lang.id_cms
        WHERE "._DB_PREFIX_."cms.id_cms = ".(int) $id." AND "._DB_PREFIX_."cms_lang.id_lang = ".(int) $id_lang;
        $cms = Db::getInstance()->executeS($query);

        return $cms[0];
    }

    public static function getProductById($id, $id_lang = null)
    {
        $id_lang = isset($id_lang) ? $id_lang : Context::getContext()->language->id;
        $query = "SELECT "._DB_PREFIX_."product.id_product as 'id', "._DB_PREFIX_."product_lang.name
        FROM "._DB_PREFIX_."product INNER JOIN "._DB_PREFIX_."product_lang
        ON "._DB_PREFIX_."product.id_product = "._DB_PREFIX_."product_lang.id_product
        WHERE "._DB_PREFIX_."product.id_product = ".(int) $id." AND "._DB_PREFIX_."product_lang.id_lang = ".(int) $id_lang;
        $product = Db::getInstance()->executeS($query);

        return $product[0];
    }

    public static function getNotificationById($id)
    {
        $query = "SELECT * 
        FROM "._DB_PREFIX_."notification
        WHERE id_notification = ".(int) $id;
        $notification = Db::getInstance()->executeS($query);

        return $notification;
    }

    public static function count()
    {
        $query = "SELECT COUNT(*) as 'total'
        FROM "._DB_PREFIX_."notification;";
        $res = Db::getInstance()->executeS($query);

        return (int) $res[0]['total'];
    }
}
