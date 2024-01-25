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

namespace MrAPPs\MrShopApi\Api\Front;

require_once _PS_MODULE_DIR_.'mrshopapi/classes/MrShopApiNotification.php';

use Context;
use DateTime;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CategoryBrandFacetsHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrShopApiNotification;

class NotificationWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
    }

    private function composeNotification($notification, $useWebPath)
    {
        $date = new DateTime($notification["date"]);
        $categoryBrandFacets = null;
        $webPath = null;
        $type = $notification["type"];
        switch ($notification['type']) {
            case MrShopApiNotification::$TYPE_SPECIAL_OFFERS:
                $webPath = 'special_offers';
                if ($useWebPath) {
                    $type = MrShopApiNotification::$TYPE_PRODUCTS;
                }

                break;
            case MrShopApiNotification::$TYPE_NEW_PRODUCTS:
                $webPath = 'new_products';
                if ($useWebPath) {
                    $type = MrShopApiNotification::$TYPE_PRODUCTS;
                }

                break;
            case MrShopApiNotification::$TYPE_CATEGORY_BRAND:
                $handler = new CategoryBrandFacetsHandler($this->module);
                $categoryBrandFacets = $handler->generateFacets((int) $notification["id_category"], (int) $notification["id_manufacturer"]);
                $webPath = 'products?'.$categoryBrandFacets;
                if ($useWebPath) {
                    $type = MrShopApiNotification::$TYPE_PRODUCTS;
                }

                break;
        }

        return [
            "id" => (int) $notification["id_notification"],
            "title" => $notification["title"],
            "type" => $type,
            "id_product" => (int) $notification["id_product"],
            "id_category" => (int) $notification["id_category"],
            "id_manufacturer" => (int) $notification["id_manufacturer"],
            "id_cms" => (int) $notification["id_cms"],
            "id_group" => (int) $notification["id_group"],
            "category_brand" => $categoryBrandFacets,
            "date" => $date->getTimestamp(),
            "web_path" => $webPath,
        ];
    }

    public function getList($params, $customerId)
    {
        $offset = array_key_exists('offset', $params) ? (int) $params['offset'] : 0;
        $limit = $this->defaultLimit;

        $notifications = MrShopApiNotification::getPaginated($offset, $limit, Context::getContext()->language->id, $customerId, true);

        if (empty($notifications) || !ApiUtils::isArray($notifications)) {
            $notifications = [];
        } else {
            $useWebPath = $this->dataHandler->getApiVersion() >= 9;

            foreach ($notifications as &$notification) {
                $notification = $this->composeNotification($notification, $useWebPath);
            }
        }
        $this->response(true, null, $notifications);
    }

    public function getDetail($id)
    {
        $notification = MrShopApiNotification::getNotification($id, Context::getContext()->language->id, true);

        if ($notification !== false) {
            $useWebPath = $this->dataHandler->getApiVersion() >= 9;
            $result = $this->composeNotification($notification, $useWebPath);

            $this->response(true, null, $result);
        } else {
            ResponseHandler::notFound($this->module->l('Notification not found', 'notificationws'));
        }
    }
}
