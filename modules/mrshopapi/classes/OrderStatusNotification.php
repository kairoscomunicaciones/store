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
class OrderStatusNotification extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'order_status_notifications',
        'primary' => 'id_order_status_notification',
        'multilang' => false,
        'fields' => [
            'active' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true],
            'id_order_state' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => true, 'validate' => 'isUnsignedId'],
        ],
    ];

    /** @var bool Active */
    public $active;

    /** @var int default Order state id */
    public $id_order_state;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->active = true;
        $this->id_order_state = 0;
        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function autoAddOrderStatusNotification($configuration_name)
    {
        $orderStateId = (int) Configuration::get($configuration_name);
        $orderState = $orderStateId > 0 ? new OrderState($orderStateId) : null;

        if ($orderState && Validate::isLoadedObject($orderState) && !$orderState->deleted) {
            $sql = 'SELECT *
                  FROM `'._DB_PREFIX_.'order_status_notifications`
                  WHERE `id_order_state` = '.(int) $orderState->id;
            $recordset = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if ($recordset && is_array($recordset) && count($recordset) > 0) {
                return (int) $recordset[0]['id_order_status_notification'];
            } else {
                $notification = new OrderStatusNotification();
                $notification->id_order_state = $orderState->id;
                $notification->active = true;
                $notification->save();

                return $notification->id;
            }
        }

        return null;
    }

    public static function countNotifications()
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
				SELECT COUNT(*) FROM '._DB_PREFIX_.'order_status_notifications'
        );

        return (int) $result[0]['COUNT(*)'];
    }

    public static function getActiveOrderStatuses()
    {
        $query = "SELECT *
        FROM "._DB_PREFIX_."order_status_notifications
        WHERE "._DB_PREFIX_."order_status_notifications.active = 1";

        return Db::getInstance()->executeS($query);
    }
}
