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

use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Api\Front\CategoryWS;
use MrAPPs\MrShopApi\Handler\Api\CacheHandler;

class CatalogWS extends BaseWS implements GetList
{
    public $cacheHandler;

    protected $default_layout_id = 0; // image

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->db = Db::getInstance(_PS_USE_SQL_SLAVE_);
        $this->cacheHandler = new CacheHandler($dataHandler);
    }

    public function getList($params, $userId)
    {
        if (!array_key_exists('id_layout', $params)) {
            $params['id_layout'] = $this->default_layout_id; // image
        }

        $categoryWs = new CategoryWS($this->dataHandler);
        $categoryWs->getList($params);
    }
}
