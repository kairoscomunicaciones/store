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

use Configuration;
use Context;
use MrAPPs\MrShopApi\Handler\Search\PricesDropHandler;
use Product;
use Tools;

class PricesDropWS extends ProductBaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
    }

    public function getList($params, $id_customer = null, $echoResponse = true)
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return $this->getList16($params, $id_customer, $echoResponse);
        }

        $limit = $this->defaultLimit;

        $handler = new PricesDropHandler();
        $searchVariables = $handler->getProductSearchVariablesResults($limit);
        $result = $searchVariables['result'];
        $items = $searchVariables['products'];
        $selectedSort = $result->getCurrentSortOrder();

        $count = (int) $result->getTotalProductsCount();

        $facetsData = $result->getFacetCollection() != null ? $result->getFacetCollection()->getFacets() : null;
        $facets = $this->refactorFacets($facetsData);

        $sortsData = $result->getAvailableSortOrders();
        $sortsOrder = $this->refactorSortOrder($sortsData, $selectedSort);

        if ($items !== false) {
            $items = $this->refactorProductResponse($items, $id_customer);
        } else {
            $items = [];
        }

        $response = [
            'facets' => $facets,
            'count' => $count,
            'result' => $items,
            'sort_orders' => $sortsOrder,
        ];

        return $this->response(true, null, $response, $echoResponse);
    }

    private function getList16($params, $id_customer = null, $echoResponse = true)
    {
        $limit = $this->defaultLimit;

        $page = max((int) Tools::getValue('page'), 1);
        $page = max(0, $page - 1);
        $order = array_key_exists('order', $params) ? $params['order'] : null;

        $orderOptions = $this->getOrderFieldAndWay($order);

        if ($orderOptions !== null) {
            $orderBy = $orderOptions['field'];
            $orderWay = $orderOptions['way'];
        } else {
            // Default sort
            $order = Tools::strtolower(trim(Configuration::get('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS')));
            $defaultOrder = explode('.', $order);

            if (count($defaultOrder) == 3 && $defaultOrder[0] == 'product') {
                $orderBy = $defaultOrder[1];
                $orderWay = $defaultOrder[2];
            } else {
                $orderBy = 'date_add';
                $orderWay = 'desc';
            }
        }

        $items = Product::getPricesDrop(
            (int) Context::getContext()->language->id,
            $page,
            $limit,
            false,
            $orderBy,
            $orderWay,
            null,
            false
        );

        $count = Product::getPricesDrop((int) Context::getContext()->language->id, $page, $limit, true);

        if ($items !== false) {
            $items = $this->refactorProductResponse($items, $id_customer);
        } else {
            $items = [];
        }

        $sortOrders = $count > 0 ? [
            [
                'encoded_order' => 'product.name.asc',
                'label' => $this->module->l('Name, A to Z', 'specialoffersws'),
            ],
            [
                'encoded_order' => 'product.name.desc',
                'label' => $this->module->l('Name, Z to A', 'specialoffersws'),
            ],
            [
                'encoded_order' => 'product.price.asc',
                'label' => $this->module->l('Price, lowest first', 'specialoffersws'),
            ],
            [
                'encoded_order' => 'product.price.desc',
                'label' => $this->module->l('Price, highest first', 'specialoffersws'),
            ],
        ] : [];
        foreach (array_keys($sortOrders) as $key) {
            $sortOrders[$key]['selected'] = ($order == $sortOrders[$key]['encoded_order']);
        }

        $response = [
            'count' => (int) $count,
            'result' => $items,
            'sort_orders' => $sortOrders,
        ];

        return $this->response(true, null, $response, $echoResponse);
    }
}
