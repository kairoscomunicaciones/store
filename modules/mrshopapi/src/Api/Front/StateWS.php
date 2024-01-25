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

use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

class StateWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->allowCacheParameters(['sort', 'filter']);
    }

    public function getList($params)
    {
        $order = array_key_exists('sort', $params) ? $params['sort'] : null;
        $filter = array_key_exists('filter', $params) ? $params['filter'] : [];

        if (array_key_exists('id', $filter) && $filter['id'] !== null) {
            $this->getDetail($filter['id']);
        }

        $query = 'SELECT id_state AS id, id_country, a.name
                  FROM `'._DB_PREFIX_.'state` a
                  WHERE a.active = 1';

        if (isset($filter['id_country']) && $filter['id_country'] !== null) {
            $query .= $this->getWhereOptionsFromFilter($filter['id_country'], 'id_country');
        }

        if ($order !== null) {
            $query .= $this->getOrderOptions($order);
        } else {
            $query .= ' ORDER BY a.name ASC';
        }

        $result = $this->refactorData($query);

        $this->response(true, null, $result);
    }

    public function getDetail($id)
    {
        $query = 'SELECT id_state AS id, id_country, name
                  FROM `'._DB_PREFIX_.'state` a
                  WHERE a.active = 1 AND a.id_state = '.(int) $id;

        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $this->response(true, null, $data);
        } else {
            ResponseHandler::notFound($this->module->l('State not found', 'statews'));
        }
    }
}
