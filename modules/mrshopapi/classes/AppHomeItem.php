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
class AppHomeItem extends ObjectModel
{
    public static $definition = [
        'table' => 'app_home_item',
        'primary' => 'id_app_home_item',
        'multilang' => true,
        'fields' => [
            'id_app_home' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedId'],
            'title' => ['type' => self::TYPE_STRING, 'lang' => true, 'required' => false, 'size' => 255],
            'hash' => ['type' => self::TYPE_STRING, 'required' => false, 'lang' => false],
            'banner_type' => ['type' => self::TYPE_STRING, 'required' => true],
            'id_product' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_category' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_manufacturer' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
            'id_cms' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => false, 'validate' => 'isUnsignedId'],
        ]
    ];

    /** @var int default AppHome id */
    public $id_app_home;

    /** @var string Title */
    public $title;

    /** @var string default Image hash */
    public $hash;

    /** @var string default BannerType hash */
    public $banner_type;

    /** @var int default Product id */
    public $id_product;

    /** @var int default Category id */
    public $id_category;

    /** @var int default Manufacturer id */
    public $id_manufacturer;

    /** @var int default Cms id */
    public $id_cms;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function getItems($id_app_home)
    {
        $query = "SELECT *
        FROM "._DB_PREFIX_."app_home_item
        WHERE id_app_home = ".(int) $id_app_home;

        return Db::getInstance()->executeS($query);
    }

    public static function getBanners($id_app_home, $id_lang)
    {
        $query = "
    SELECT
        ahi.id_app_home_item,
        ahi.id_app_home,
        ahil.title,
        ahi.hash,
        ahi.banner_type,
        pl.id_product,
        pl.name as 'product_name',
        cl.id_category,
        cl.name as 'category_name',
        m.id_manufacturer,
        m.name as 'manufacturer_name',
        cml.id_cms,
        cml.meta_title as 'cms_name'
    FROM "._DB_PREFIX_."app_home_item ahi LEFT JOIN "._DB_PREFIX_."product_lang pl 
    ON ahi.id_product = pl.id_product AND pl.id_lang = ".(int) $id_lang." LEFT JOIN "._DB_PREFIX_."category_lang cl 
    ON ahi.id_category = cl.id_category AND cl.id_lang = ".(int) $id_lang." LEFT JOIN "._DB_PREFIX_."manufacturer m 
    ON ahi.id_manufacturer = m.id_manufacturer LEFT JOIN "._DB_PREFIX_."cms_lang cml 
    ON ahi.id_cms = cml.id_cms AND cml.id_lang = ".(int) $id_lang." LEFT JOIN "._DB_PREFIX_."app_home_item_lang ahil 
    ON ahi.id_app_home_item = ahil.id_app_home_item AND ahil.id_lang = ".(int) $id_lang."
    WHERE ahi.id_app_home = ".(int) $id_app_home;

        return Db::getInstance()->executeS($query);
    }

    public static function loadTitles($id_app_home_item)
    {
        $query = "SELECT
            "._DB_PREFIX_."app_home_item_lang.title,
            "._DB_PREFIX_."lang.iso_code
        FROM "._DB_PREFIX_."app_home_item_lang INNER JOIN "._DB_PREFIX_."lang
        ON "._DB_PREFIX_."app_home_item_lang.id_lang = "._DB_PREFIX_."lang.id_lang
        WHERE id_app_home_item = ".(int) $id_app_home_item;
        $result = Db::getInstance()->executeS($query);

        $retval = [];
        if (isset($result)) {
            foreach ($result as $r) {
                if (isset($r['title']) && isset($r['iso_code'])) {
                    $retval[$r['iso_code']] = $r['title'];
                }
            }
        }

        return $retval;
    }

    public static function updateImageLang($id, $hash, $iso = null)
    {
        if (isset($iso)) {
            $query = "UPDATE "._DB_PREFIX_."app_home_item_lang
            SET `lang_hash` = '".pSQL($hash)."'
            WHERE id_app_home_item = ".(int) $id." AND id_lang = (
                SELECT "._DB_PREFIX_."lang.id_lang
                FROM "._DB_PREFIX_."lang
                WHERE "._DB_PREFIX_."lang.iso_code = '".pSQL($iso)."'
            )";

            return Db::getInstance()->execute($query);
        } else {
            Db::getInstance()->execute("UPDATE "._DB_PREFIX_."app_home_item_lang
            SET `lang_hash` = NULL
            WHERE id_app_home_item = ".(int) $id);

            return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
                UPDATE `'._DB_PREFIX_.'app_home_item`
                SET `hash` = "'.pSQL($hash).'"
                WHERE `id_app_home_item` = '.(int) $id.'
            ');
        }
    }

    public static function alignCarouselItemLang($items)
    {
        $languages = Language::getLanguages();
        foreach ($items as $item) {
            foreach ($languages as $l) {
                $res = Db::getInstance()->executeS("SELECT *
                FROM "._DB_PREFIX_."app_home_item_lang
                WHERE id_app_home_item = ".(int) $item['id_app_home_item']);
                if (isset($res) && count($res) == 0 && isset($item['id_app_home_item']) && isset($l['id_lang'])) {
                    Db::getInstance()->execute("
                        INSERT INTO "._DB_PREFIX_."app_home_item_lang (id_app_home_item, id_lang, title, lang_hash)
                        VALUES (".(int) $item['id_app_home_item'].", ".(int) $l['id_lang'].", '', '');");
                }
            }
        }
    }

    public function delete()
    {
        Db::getInstance()->execute("
        DELETE FROM "._DB_PREFIX_."app_home_item_lang
        WHERE id_app_home_item = ".(int) $this->id);

        return parent::delete();
    }

    public static function deleteOthers($id_app_home, $appHomeItems)
    {
        $currentIds = [];
        foreach ($appHomeItems as $current) {
            if (isset($current->id)) {
                $currentIds[] = (int) $current->id;
            }
        }

        if (count($currentIds) > 0) {
            Db::getInstance()->execute("DELETE FROM "._DB_PREFIX_."app_home_item_lang  WHERE id_app_home_item IN ( SELECT ai.id_app_home_item FROM "._DB_PREFIX_."app_home_item ai
            WHERE ai.id_app_home = ".$id_app_home." AND ai.id_app_home_item NOT IN ( ".implode(' , ', array_map('intval', $currentIds))." ) )");

            $query = "DELETE FROM "._DB_PREFIX_."app_home_item
            WHERE id_app_home = ".$id_app_home." AND id_app_home_item NOT IN ( ".implode(' , ', array_map('intval', $currentIds))." )";
        } else {
            $query = "DELETE FROM "._DB_PREFIX_."app_home_item
            WHERE id_app_home = ".$id_app_home;
        }

        return Db::getInstance()->execute($query);
    }
}
