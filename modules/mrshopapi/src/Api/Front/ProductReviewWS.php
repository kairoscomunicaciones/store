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
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use Product;

class ProductReviewWS extends BaseWS
{
    private $externalModuleService;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->cacheEnabled = false;
        $this->externalModuleService = new ExternalModuleService();
    }

    public function getList($params, $id_customer)
    {
        if (!array_key_exists('id_product', $params)) {
            $this->response(false, $this->module->l('Product not found', 'productreviewws'));
        }

        $page = array_key_exists('page', $params) ? (int) $params['page'] : 1;

        try {
            $product = new Product((int) $params['id_product']);
            $count = $this->externalModuleService->productcommentsCountComments($product);
            $comments = $this->externalModuleService->productcommentsGetComments($product, $page);
        } catch (\Exception $exception) {
            $count = 0;
            $comments = [];
        }

        $this->response(true, null, [
            'count' => (int) $count,
            'results' => $comments
        ]);
    }

    public function updateOrCreate($params, $id, $id_customer = null)
    {
        if (!array_key_exists('title', $params) || empty($params['title'])) {
            $this->response(false, $this->module->l('Title cannot be empty', 'productreviewws'));
        }

        if ($id_customer == null && (!array_key_exists('customer_name', $params) || empty($params['customer_name']))) {
            $this->response(false, $this->module->l('Customer name cannot be empty', 'productreviewws'));
        }

        if ($id_customer != null) {
            $params['customer_name'] = '';
            $params['id_guest'] = 0;
            $params['id_customer'] = $id_customer;
        } else {
            $params['id_customer'] = 0;
        }

        if (!array_key_exists('id_product', $params)) {
            $this->response(false, $this->module->l('Product not found', 'productreviewws'));
        }

        $criterions = [];
        foreach ($params as $key => $v) {
            if (strpos($key, 'voting_') !== false) {
                $criterions[explode('_', $key)[1]] = $params[$key];
            }
        }
        $this->externalModuleService->productcommentsAddComment($params, $criterions);

        $this->response(true, null, []);
    }
}
