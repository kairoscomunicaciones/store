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
use Category;
use Context;
use Db;
use Language;
use Manufacturer;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceCountInterface as Count;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceDeleteInterface as DeleteItem;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetDetailInterface as GetDetail;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Api\Validators\NotificationValidator;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\NotificationsHandler;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use MrShopApiNotification;
use Notification;
use Product;

class NotificationWS extends BaseWS implements GetList, GetDetail, DeleteItem, UpsertItem, Count
{
    private $db;

    private $enableSent = false;

    public $cacheHandler;

    const BULK_DELETE  = 'delete';

    const BULK_ENABLE  = 'enable';

    const BULK_DISABLE = 'disable';

    private $actions = [
        self::BULK_DELETE,
        self::BULK_ENABLE,
        self::BULK_DISABLE
    ];

    const BULK_ACTION_KEY = 'bulkAction';

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
        $limit = $this->defaultLimit;
        $offset = 0;

        if (array_key_exists('limit', $params)) {
            $limit = (int) $params['limit'];
        }

        if (array_key_exists('offset', $params)) {
            $offset = (int) $params['offset'];
        }

        $query = "SELECT *
            FROM "._DB_PREFIX_."notification
            ORDER BY id_notification DESC
            LIMIT ".$limit."
            OFFSET ".$offset;
        $results = $this->db->executeS($query);
        if (false == is_array($results)) {
            return $this->response(false, $this->module->l('An error occured while trying to retrive data', 'notificationws'));
        }

        $retval = static::loadTranslatedContents($results);
        $this->response(true, null, $retval);
    }

    public function getDetail($id, $userId)
    {
        $results = MrShopApiNotification::getNotificationById($id);
        $retval = static::loadTranslatedContents($results);
        $this->response(true, null, $retval[0]);
    }

    public function deleteItem($id, $userId)
    {
        $notification = new MrShopApiNotification((int) $id);
        if (isset($notification->id_notification)) {
            MrShopApiNotification::cleanDeleteById($id);
            $this->response();
        } else {
            $this->response(false, $this->module->l('Notification not found'));
        }
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        if (isset($bodyParams[self::BULK_ACTION_KEY])) {
            // Bulk actions
            return $this->execBulkAction($bodyParams, $userId);
        }

        $id = (int) $id > 0 ? $id : null;
        $validator = new NotificationValidator($this->module, $id, $bodyParams);
        if ($validator->isValid && isset($validator->notification)) {
            $validator->notification->save();
            MrShopApiNotification::deleteNotificationCustomerId($validator->notification->id);

            if ($validator->sentImmediately && $this->enableSent) {
                $notificationsHandler = new NotificationsHandler($this->module);
                $notificationsHandler->sendNotifications();
            }

            return $this->getDetail($validator->notification->id, $userId);
        } elseif (false == empty($validator->errors)) {
            ResponseHandler::unprocessableEntity($validator->errors);
        } else {
            ResponseHandler::unprocessableEntity([]);
        }
    }

    public function count($params, $userId)
    {
        $total = MrShopApiNotification::count();
        $this->response(true, null, [
            'count' => $total
        ]);
    }

    public function execBulkAction($bodyParams, $userId)
    {
        if (in_array($bodyParams[self::BULK_ACTION_KEY], $this->actions) == false) {
            ResponseHandler::badRequest($this->module->l('Invalid bulk action'));
        }

        if (empty($bodyParams['ids'])) {
            ResponseHandler::badRequest($this->module->l('There was an error while executing operation', 'notificationws'));
        }

        $query = "";
        switch ($bodyParams[self::BULK_ACTION_KEY]) {
            case self::BULK_ENABLE:
            case self::BULK_DISABLE:
                $active = $bodyParams[self::BULK_ACTION_KEY] == self::BULK_ENABLE ? 1 : 0;
                $query = "UPDATE "._DB_PREFIX_."notification
                SET active = ".$active."
                WHERE id_notification IN ( ".implode(',', array_map('intval', $bodyParams['ids']))." )";

                break;
            case self::BULK_DELETE:
                $query = "DELETE FROM "._DB_PREFIX_."notification
                WHERE id_notification IN ( ".implode(',', array_map('intval', $bodyParams['ids']))." ) ";

                break;
        }

        if (!empty($query)) {
            $this->db->execute($query);
        }

        return $this->getList($bodyParams, $userId);
    }

    public static function loadTranslatedContents($items)
    {
        $notificationIds = array_map(function ($item) {
            return (int) $item['id_notification'];
        }, $items);

        $titles = [];

        if (isset($notificationIds) && count($notificationIds) > 0) {
            $query = "SELECT *
                    FROM "._DB_PREFIX_."notification_lang
                    WHERE id_notification IN (".implode(',', $notificationIds).")
                    ORDER BY id_notification";

            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            foreach ($results as $row) {
                $id_home = $row['id_notification'];
                if (empty($titles[$id_home])) {
                    $titles[$id_home] = [];
                }

                $iso = Language::getIsoById($row['id_lang']);
                $titles[$id_home][$iso] = $row['title'];
            }
        }

        return static::popItems($items, $titles);
    }

    public static function popItems($items, $titles)
    {
        foreach ($items as &$item) {
            switch ($item['type']) {
                case MrShopApiNotification::$TYPE_CATEGORY_BRAND:
                    if (isset($item['id_category'])) {
                        $item['category'] = MrShopApiNotification::getCategoryById($item['id_category']);
                    }

                    if (isset($item['id_manufacturer'])) {
                        $name = Manufacturer::getNameById($item['id_manufacturer']);
                        $item['manufacturer'] = [
                            'id' => (int) $item['id_manufacturer'],
                            'name' => $name
                        ];
                    }

                    break;
                case MrShopApiNotification::$TYPE_CMS:
                    if (isset($item['id_cms'])) {
                        $item['cms'] = MrShopApiNotification::getCmsById($item['id_cms']);
                    }

                    break;
                case MrShopApiNotification::$TYPE_PRODUCT_DETAIL:
                    if (isset($item['id_product'])) {
                        $item['product'] = MrShopApiNotification::getProductById($item['id_product']);
                    }

                    break;
            }
        }

        $results = array_map(function ($item) use ($titles) {
            return [
                'id' => (int) $item['id_notification'],
                'sentDate' => $item['date'],
                'title' => isset($titles[(int) $item['id_notification']])
                    ? $titles[(int) $item['id_notification']]
                    : null,
                'notificationType' => $item['type'],
                'sent' => (bool) $item['sent'],
                'active' => (bool) $item['active'],
                'category' => isset($item['category']) ? $item['category'] : null,
                'manufacturer' => isset($item['manufacturer']) ? $item['manufacturer'] : null,
                'cms' => isset($item['cms']) ? $item['cms']: null,
                'product' => isset($item['product']) ? $item['product']: null,
                'group' => (int) $item['id_group']
            ];
        }, $items);

        return $results;
    }
}
