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

namespace MrAPPs\MrShopApi\Api\Admin;

use \MrAPPs\MrShopApi\Api\Contracts\WebservicePostInterface as UpsertItem;
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use OrderStatusNotification;

class OrderStatusNotificationWS extends BaseWS implements GetList, UpsertItem
{
    private $db;

    private $enableSent = false;

    public $cacheHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->db = Db::getInstance(_PS_USE_SQL_SLAVE_);
        $this->imageUtils = new ImageUtils();
        $this->cacheHandler = new CacheHandler($dataHandler);
    }

    public function getList($params, $userId)
    {
        $results = OrderStatusNotification::getActiveOrderStatuses();
        $this->response(true, null, static::pop($results));
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        if (false == isset($bodyParams['webhooks'])) {
            ResponseHandler::badRequest('Invalid request');
        }

        $current = OrderStatusNotification::getActiveOrderStatuses();
        $add = [];
        foreach ($bodyParams['webhooks'] as $item) {
            $found = false;

            foreach ($current as $currentItem) {
                if ((int) $item == (int) $currentItem['id_order_state']) {
                    $found = true;

                    break;
                }
            }

            if (!$found) {
                $add[] = $item;
            }
        }

        foreach ($add as $item) {
            $orderStatus = new OrderStatusNotification();
            $orderStatus->id_order_state = $item;
            $orderStatus->active = true;
            $orderStatus->save();
        }

        // Delete others
        $query = "DELETE FROM "._DB_PREFIX_."order_status_notifications
        WHERE id_order_state NOT IN ( ".implode(',', array_map('intval', $bodyParams['webhooks']))." )";
        $this->db->execute($query);

        return $this->response(true, null, $bodyParams['webhooks']);
    }

    public static function pop($results)
    {
        $retval = [];
        foreach ($results as $item) {
            $retval[] = (int) $item['id_order_state'];
        }

        return $retval;
    }
}
