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
use CmsPage;
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceCountInterface as Count;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceDeleteInterface as DeleteItem;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Api\Front\CmsWS;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CmsPageHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class CmsPageWS extends BaseWS implements GetList, DeleteItem, UpsertItem, Count
{
    public $cacheHandler;

    private $handler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->cacheHandler = new CacheHandler($dataHandler);
        $this->handler = new CmsPageHandler();
    }

    public function getList($params, $userId)
    {
        if (!array_key_exists('limit', $params)) {
            $params['limit'] = $this->defaultLimit;
        }

        if (!array_key_exists('offset', $params)) {
            $params['offset'] = 0;
        }

        $res = $this->handler->getCmsPages(null, $params);
        $this->response(true, null, $res);
    }

    public function count($params, $userId)
    {
        $count = $this->handler->countCmsPages();
        $this->response(true, null, [
            'count' => $count
        ]);
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        if (!isset($bodyParams['id_cms'])) {
            $this->response(false, $this->module->l('ID cms not specified', 'cmspagews'), []);
        }

        $sql = 'SELECT id_cms_page
                  FROM `'._DB_PREFIX_.'mrshop_cms_page`
                  WHERE `id_cms` = '.(int) $bodyParams['id_cms'];

        $idCms = (int) Db::getInstance()->getValue($sql);

        $cms = new CmsPage($idCms);
        $cms->hydrate([
            'id_cms' => $bodyParams['id_cms'],
            'simple_cms' => !isset($bodyParams['simple_cms']) || (bool) $bodyParams['simple_cms']
        ]);

        if (!$cms->save()) {
            $this->response(false, $this->module->l('Error during save cms page', 'cmspagews'), []);
        }

        $this->response(true, null, $this->handler->getCmsPageById($bodyParams['id_cms']));
    }

    public function getDetail($id)
    {
        $this->response(true, null, [
            'url' => ApiUtils::getFullUrl('index.php?fc=module&module=mrshopapi&controller=cms&id='.$id)
        ]);
    }

    public function deleteItem($id, $userId)
    {
        $cms = new CmsPage($id);
        if (isset($cms->id)) {
            if (!$cms->delete()) {
                $this->response(false, $this->module->l('Error during delete cms page', 'cmspagews'), []);
            }

            return $this->response(true, null, true);
        } else {
            $this->response(false, $this->module->l('Cms page not found', 'cmspagews'), []);
        }
    }
}
