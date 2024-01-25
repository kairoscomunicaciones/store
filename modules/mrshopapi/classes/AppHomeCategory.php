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
class AppHomeCategory extends ObjectModel
{
    public static $definition = [
        'table' => 'app_home_category',
        'primary' => 'id_app_home_category',
        'fields' => [
            'id_app_home' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedId'],
            'id_category' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedId']
        ]
    ];

    /** @var int default AppHome id */
    public $id_app_home;

    /** @var int default Category id */
    public $id_category;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function alignCategories($appHomeId, $categories)
    {
        $categoriesIds = [];
        foreach ($categories as $c) {
            $categoryId = (int) $c['id'];
            $found = AppHomeCategory::findAppHomeCategory($appHomeId, $categoryId);
            if (false == isset($found)) {
                // Create if not found
                $appHomeCategory = new AppHomeCategory();
                $appHomeCategory->hydrate([
                    'id_app_home' => $appHomeId,
                    'id_category' => $categoryId
                ]);
                $appHomeCategory->save();
            }

            $categoriesIds[] = $categoryId;
        }

        $deleteQuery = "DELETE FROM "._DB_PREFIX_."app_home_category
        WHERE "._DB_PREFIX_."app_home_category.id_category NOT IN ( ".implode(', ', array_map('intval', $categoriesIds))." ) 
        AND "._DB_PREFIX_."app_home_category.id_app_home = ".(int) $appHomeId;
        Db::getInstance()->execute($deleteQuery);
    }

    public static function findAppHomeCategory($appHomeId, $categoryId)
    {
        $query = "SELECT *
        FROM "._DB_PREFIX_."app_home_category
        WHERE id_category = ".(int) $categoryId." AND id_app_home = ".(int) $appHomeId;

        return Db::getInstance()->executeS($query);
    }

    public static function findCategories($appHomeId, $id_lang = null)
    {
        if (false == isset($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }

        $query = "SELECT
            "._DB_PREFIX_."app_home_category.id_category as 'id',
            "._DB_PREFIX_."category_lang.name
        FROM "._DB_PREFIX_."app_home_category INNER JOIN "._DB_PREFIX_."category_lang
        ON "._DB_PREFIX_."app_home_category.id_category = "._DB_PREFIX_."category_lang.id_category
        WHERE "._DB_PREFIX_."app_home_category.id_app_home = ".(int) $appHomeId." AND "._DB_PREFIX_."category_lang.id_lang = ".(int) $id_lang;

        return Db::getInstance()->executeS($query);
    }
}
