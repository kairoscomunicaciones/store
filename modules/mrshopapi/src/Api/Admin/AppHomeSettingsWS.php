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
use AppHome;
use AppHomeCategory;
use AppHomeItem;
use Db;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceCountInterface as Count;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceDeleteInterface as DeleteItem;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetDetailInterface as GetDetail;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Api\Transformers\AdminAppHomeTransformer;
use MrAPPs\MrShopApi\Api\Validators\AdminAppHomeValidator;
use MrAPPs\MrShopApi\Api\Validators\AppHomeItemValidator;
use MrAPPs\MrShopApi\Handler\Api\AppConfigurationHandler;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Service\ImageService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Validate;

class AppHomeSettingsWS extends BaseWS implements GetList, GetDetail, DeleteItem, UpsertItem, Count
{
    private $db;

    public $cacheHandler;

    // private $imageUtils;
    private $imageService;

    const BULK_DELETE  = 'delete';

    const BULK_ENABLE  = 'enable';

    const BULK_DISABLE = 'disable';

    const BULK_SORT    = 'sort';

    const BULK_ACTION_KEY = 'bulkAction';

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->db = Db::getInstance(_PS_USE_SQL_SLAVE_);
        // $this->imageUtils = new ImageUtils();
        $this->imageService = new ImageService();
        $this->cacheHandler = new CacheHandler($dataHandler);
    }

    public function getList($params, $userId)
    {
        $limit = null;
        $offset = null;

        if (array_key_exists('limit', $params)) {
            $limit = (int) $params['limit'];
        }

        if (array_key_exists('offset', $params)) {
            $offset = (int) $params['offset'];
        }

        if (isset($limit) && isset($offset)) {
            $query = "SELECT *
                FROM "._DB_PREFIX_."app_home
                ORDER BY position ASC
                LIMIT ".$limit."
                OFFSET ".$offset;
        } else {
            $query = "SELECT *
                FROM "._DB_PREFIX_."app_home
                ORDER BY position ASC";
        }

        $results = $this->db->executeS($query);
        if (!is_array($results)) {
            return $this->response(false, $this->module->l('An error occured while trying to retrive data', 'apphomesettingsws'));
        }

        $res = AdminAppHomeTransformer::jsonCollection($results, $this->dataHandler, $this->imageService);
        $this->response(true, null, $res);
    }

    public function getDetail($id, $userId)
    {
        $query = "SELECT *
            FROM "._DB_PREFIX_."app_home
            WHERE id_app_home = ".(int) $id;

        $results = $this->db->executeS($query);
        if (!is_array($results)) {
            return $this->response(false, $this->module->l('An error occured while trying to retrive data', 'apphomesettingsws'));
        }

        if (count($results) == 0) {
            ResponseHandler::notFound(
                sprintf($this->module->l('App home with id %d cannot be found', 'apphomesettingsws'), $id)
            );
        }

        $titles = AdminAppHomeTransformer::loadTranslatedTitles([(int) $id]);
        $r = AdminAppHomeTransformer::jsonItem($results[0], $this->dataHandler, $this->imageService, $titles);

        $this->response(true, null, $r);
    }

    public function count($params, $userId)
    {
        $count = AppHome::count();
        $this->response(true, null, [
            'count' => $count
        ]);
    }

    public function deleteItem($id, $userId)
    {
        if (false == $this->_deleteHome($id)) {
            $this->response(
                false,
                sprintf(
                    '%s%d',
                    $this->module->l('An error occured while trying to delete app home with id ', 'apphomesettingsws'),
                    (int) $id
                )
            );
        }
        $this->cacheHandler->deleteCacheForAppHome();
        $this->response();
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        if (isset($bodyParams[self::BULK_ACTION_KEY])) {
            return $this->manageBulkAction($bodyParams, $userId);
        }
        $id = (int) $id;
        $id = $id > 0
                ? $id
                : null;

        $appHome = new AppHome($id);

        if (is_null($id)) {
            $query = 'SELECT MAX(position)
                FROM '._DB_PREFIX_.'app_home';

            $position = (int) $this->db->getValue($query);
            $bodyParams['position'] = $position + 1;
        }

        $errors = AdminAppHomeValidator::create($this->module, $appHome)->validate($bodyParams);
        if (false == empty($errors)) {
            ResponseHandler::unprocessableEntity($errors);
        }

        $data = AdminAppHomeTransformer::dbItem($bodyParams);

        if (empty($id)) {
            $data['position'] = (int) $this->getMaxPosition()+1;
        }

        $data['hash'] = $this->imageService->hasOriginalImage($id);
        $appHome->hydrate($data);

        if (false == $appHome->save()) {
            $this->response(false, $this->module->l('An error occured while saving app home', 'apphomesettingsws'));
        }

        if ($data['type'] == AppHome::$TYPE_CAROUSEL) {
            $banners = new AppHomeItemValidator((int) $appHome->id, $this->module, $bodyParams['banners']);

            if (false == empty($banners->errors)) {
                ResponseHandler::unprocessableEntity($banners->errors);
            }

            // If there isn't banners
            // Create/Update inactive
            if (count($banners->appHomeItems) == 0) {
                $appHome->active = false;
                $appHome->save();
            } else {
                for ($i = 0; $i < count($banners->appHomeItems); $i++) {
                    $banners->appHomeItems[$i]->save();
                    $id = $banners->appHomeItems[$i]->id;
                    $this->imageService->copyMultiLangImage(AppHome::$TYPE_CAROUSEL, $bodyParams['banners'][$i]['image'], $id, $bodyParams['banners'][$i]['hasMultiLangImage']);
                }
            }

            AppHomeItem::deleteOthers((int) $appHome->id, $banners->appHomeItems);
        }

        if ($data['type'] == AppHome::$TYPE_CATEGORIES) {
            AppHomeCategory::alignCategories($appHome->id, $bodyParams['categories']);
        }

        if (isset($bodyParams['image']) && $data['type'] == AppHome::$TYPE_BANNER) {
            $this->imageService->copyMultiLangImage(AppHome::$TYPE_BANNER, $bodyParams['image'], $appHome->id, $bodyParams['hasMultiLangImage']);
        }

        if (isset($bodyParams['groupsToShow']) && !$appHome->display_in_all_groups) {
            $deleteQuery = "DELETE FROM "._DB_PREFIX_."app_home_group WHERE id_app_home = $appHome->id";
            $this->db->execute($deleteQuery);
            $query = '';
            foreach ($bodyParams['groupsToShow'] as $group) {
                $query.= 'INSERT INTO '._DB_PREFIX_.'app_home_group (id_app_home,id_group) VALUES ('.(int) $appHome->id.','.(int) $group.');';
            }

            $this->db->execute($query);
        };
        $this->cacheHandler->deleteCacheForAppHome();
        $this->getDetail($appHome->id, $userId);
    }

    protected function getMaxPosition()
    {
        $query = "SELECT MAX(position)
            FROM "._DB_PREFIX_."app_home";

        return $this->db->getValue($query);
    }

    protected function manageBulkAction($bodyParams, $userId)
    {
        $actions = [self::BULK_DELETE, self::BULK_ENABLE, self::BULK_DISABLE, self::BULK_SORT];

        if (false == in_array($bodyParams[self::BULK_ACTION_KEY], $actions)) {
            ResponseHandler::badRequest($this->module->l('Invalid bulk action', 'apphomesettingsws'));
        }

        if (empty($bodyParams['ids'])) {
            ResponseHandler::badRequest($this->module->l('There was an error while executing operation', 'apphomesettingsws'));
        }

        // always casted to array
        $ids = is_array($bodyParams['ids'])
                ? $bodyParams['ids']
                : [$bodyParams['ids']];

        // convert them to integers
        $ids = array_reduce(
            $ids,
            function ($carry, $item) {
                $i = (int) $item;

                if (false == empty($i)) {
                    $carry[] = $i;
                }

                return $carry;
            },
            []
        );

        // make them unique in the fastes way (fastest than array_unique)
        $ids = array_flip(array_flip($ids));

        if (empty($ids)) {
            ResponseHandler::badRequest($this->module->l('Ids are an empty set', 'apphomesettingsws'));
        }

        $action = $bodyParams[self::BULK_ACTION_KEY];
        switch ($action) {
            case self::BULK_ENABLE:
            case self::BULK_DISABLE:
                $enable = $action == self::BULK_ENABLE
                    ? '1' : '0';
                $query = "UPDATE "._DB_PREFIX_."app_home
                    SET
                        active = ".(int) $enable.",
                        active_from = NULL,
                        active_to = NULL
                    WHERE id_app_home IN (".implode(',', array_map('intval', $ids)).")";

                $result = $this->db->query($query);

                break;
            case self::BULK_DELETE:
                $result = true;
                foreach ($ids as $id) {
                    $result = $result && $this->_deleteHome($id);
                }

                break;
            case self::BULK_SORT:
                if (count($ids) > 0) {
                    $currentPosition = $this->db->getValue("SELECT position FROM "._DB_PREFIX_."app_home WHERE id_app_home = ".(int) $ids[0]);

                    $cases = [];
                    foreach ($ids as $id) {
                        $cases[] = "when id_app_home = ".(int) $id." then ".(int) $currentPosition;
                        $currentPosition++;
                    }

                    $query = "UPDATE "._DB_PREFIX_."app_home
                    SET position = (case ".implode(' ', $cases)." end)
                    WHERE id_app_home IN (".implode(',', array_map('intval', $ids)).")";

                    $result = $this->db->query($query);
                }

                break;
        }

        if (false == $result) {
            $this->response(false, $this->module->l('An error occured while saving home sections', 'apphomesettingsws'));
        }
        $this->cacheHandler->deleteCacheForAppHome();

        return $this->getList($bodyParams, $userId);
    }

    private function _deleteHome($id)
    {
        $row = new AppHome((int) $id);

        if ($row->type == AppHome::$TYPE_CAROUSEL) {
            $banners = AppHomeItem::getItems((int) $id);
            if (isset($banners) && count($banners) > 0) {
                foreach ($banners as $b) {
                    $item = new AppHomeItem((int) $b['id_app_home_item']);
                    $item->delete();
                }
            }
        }

        if (Validate::isLoadedObject($row) && false == $row->delete()) {
            return false;
        }

        if (!ApiUtils::isAppRequested() && !AppHome::isConfigured()) {
            $lastAllowedStep = (int) \Configuration::get('MRSHOP_LAST_ALLOWED_STEP');
            if ($lastAllowedStep >= AppConfigurationHandler::HOME_STEP) {
                \Configuration::updateValue('MRSHOP_LAST_ALLOWED_STEP', AppConfigurationHandler::HOME_STEP - 1);
            }
        }

        // $this->imageUtils->clearThumbnails((int) $id, [], true);

        return true;
    }
}
