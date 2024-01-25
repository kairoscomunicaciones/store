<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2023 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class defpayuser extends ObjectModel
{
    public $id_defpayuser;
    public $id_customer;
    public $id_group;
    public $order_from;
    public $order_to;
    public $days;
    public $active;

    public static $definition = array(
        'table' => 'defpayuser',
        'primary' => 'id_defpayuser',
        'multilang' => false,
        'fields' => array(
            'id_defpayuser' => array('type' => ObjectModel :: TYPE_INT),
            'id_customer' => array('type' => ObjectModel :: TYPE_INT),
            'id_group' => array('type' => ObjectModel :: TYPE_INT),
            'order_from' => array('type' => ObjectModel :: TYPE_FLOAT),
            'order_to' => array('type' => ObjectModel :: TYPE_FLOAT),
            'days' => array('type' => ObjectModel :: TYPE_FLOAT),
            'active' => array('type' => ObjectModel :: TYPE_INT),
        ),
    );

    public static function getAll()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`');
    }

    public static function getByCustomer($id_customer, $id_group = array(0))
    {
        $groups = array();
        if (is_array($id_group)) {
            foreach ($id_group AS $gk => $gv) {
                $groups[] = $gk;
            }
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . self::$definition['table'] . '` WHERE (id_customer = "'.$id_customer.'" OR id_group IN ('.implode(",", $groups).')) AND active = 1');
    }
}
