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
 * @copyright Mr. APPs 2022
 * @license Mr. APPs
 */

/**
 * Class MrShopPaymentModule
 */
class MrShopPaymentModule extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'mrshop_payment_module',
        'primary' => 'id_payment_module',
        'multilang' => false,
        'fields' => [
            'id_module' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => true, 'validate' => 'isUnsignedId'],
            'use_in_app' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true],
            'supported' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true]
        ],
    ];

    /** @var int default Module id */
    public $id_module;

    /** @var boolean */
    public $use_in_app;

    /** @var boolean */
    public $supported;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function syncModules($modules)
    {
        foreach ($modules as $module) {
            $found = MrShopPaymentModule::findByModuleId((int) $module['id_module']);
            if (!$found) {
                $paymentModule = new MrShopPaymentModule();
                $paymentModule->id_module = $module['id_module'];
                $paymentModule->use_in_app = (bool) $module['supported'];
                $paymentModule->supported = (bool) $module['supported'];
                $paymentModule->save();
            }
        }
    }

    public static function findByModuleId($moduleId)
    {
        $query = 'SELECT *
        FROM '._DB_PREFIX_.'mrshop_payment_module
        WHERE id_module = '.$moduleId;
        $result = Db::getInstance()->executeS($query);

        return $result == false ? false : $result[0];
    }

    public static function find()
    {
        $query = "SELECT
            "._DB_PREFIX_."mrshop_payment_module.id_payment_module,
            "._DB_PREFIX_."mrshop_payment_module.supported,
            "._DB_PREFIX_."mrshop_payment_module.use_in_app,
            "._DB_PREFIX_."module.name,
            "._DB_PREFIX_."module.version
        FROM "._DB_PREFIX_."mrshop_payment_module INNER JOIN "._DB_PREFIX_."module
        ON "._DB_PREFIX_."mrshop_payment_module.id_module = "._DB_PREFIX_."module.id_module";

        return Db::getInstance()->executeS($query);
    }

    public static function getAllowedPaymentModules()
    {
        $query = "SELECT
            "._DB_PREFIX_."mrshop_payment_module.id_payment_module,
            "._DB_PREFIX_."mrshop_payment_module.supported,
            "._DB_PREFIX_."mrshop_payment_module.use_in_app,
            "._DB_PREFIX_."module.name,
            "._DB_PREFIX_."module.version
        FROM "._DB_PREFIX_."mrshop_payment_module INNER JOIN "._DB_PREFIX_."module
        ON "._DB_PREFIX_."mrshop_payment_module.id_module = "._DB_PREFIX_."module.id_module
        WHERE use_in_app = 1;";

        return Db::getInstance()->executeS($query);
    }
}
