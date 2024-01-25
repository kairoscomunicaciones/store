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

use Context;
use Module;
use MrAPPs\MrShopApi\Handler\Search\SearchHandler;
use MrAPPs\MrShopApi\Handler\SniperfastHandler;
use Search;
use Tools;

class SearchWS extends ProductBaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->allowCacheParameters(['query']);

        if (Module::isEnabled('sniperfast')) {
            $this->cacheEnabled = false;
        }
    }

    public function getList($params, $id_customer = null)
    {
        $search = array_key_exists('query', $params) ? $params['query'] : null;
        $limit = $this->defaultLimit;

        $page = max((int) Tools::getValue('page'), 1);

        $response = [];

        $order = isset($params['order']) ? $params['order'] : null;

        if ($search !== null) {
            if (Module::isEnabled('sniperfast')) {
                $q = isset($params['q']) ? $params['q'] : '';
                $sniperfastHandler = new SniperfastHandler($order, $page, $limit);
                $data = $sniperfastHandler->search($search, $q);
                $sortOrders = [
                    [
                        'label' => $this->module->l('Relevance', 'searchws'),
                        'encoded_order' => 'rel',
                        'selected' => (!$order || 'rel' == $order)
                    ],
                    [
                        'label' => $this->module->l('Best Sales', 'searchws'),
                        'encoded_order' => 'best',
                        'selected' => ('best' == $order)
                    ],
                    [
                        'label' => $this->module->l('Name, A to Z', 'searchws'),
                        'encoded_order' => 'name_asc',
                        'selected' => ('name_asc' == $order)
                    ],
                    [
                        'label' => $this->module->l('Name, Z to A', 'searchws'),
                        'encoded_order' => 'name_dec',
                        'selected' => ('name_dec' == $order)
                    ],
                    [
                        'label' => $this->module->l('Price, lowest first', 'searchws'),
                        'encoded_order' => 'price_asc',
                        'selected' => ('price_asc' == $order)
                    ],
                    [
                        'label' => $this->module->l('Price, highest first', 'searchws'),
                        'encoded_order' => 'price_dec',
                        'selected' => ('price_dec' == $order)
                    ],
                    [
                        'label' => $this->module->l('Quantity, lowest first', 'searchws'),
                        'encoded_order' => 'qty_asc',
                        'selected' => ('qty_asc' == $order)
                    ],
                    [
                        'label' => $this->module->l('Quantity, highest first', 'searchws'),
                        'encoded_order' => 'qty_dec',
                        'selected' => ('qty_dec' == $order)
                    ],
                    [
                        'label' => $this->module->l('Reference, Z to A', 'searchws'),
                        'encoded_order' => 'reference_asc',
                        'selected' => ('reference_asc' == $order)
                    ],
                    [
                        'label' => $this->module->l('Reference, A to Z ', 'searchws'),
                        'encoded_order' => 'reference_dec',
                        'selected' => ('reference_dec' == $order)
                    ],
                ];
            } else {
                if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
                    $data = $this->getList16($order, $search, $page, $limit);
                } else {
                    $data = $this->getList17($search, $limit);
                    $sortOrders = $data['sort_orders'];
                }
            }
        } else {
            $data['total'] = 0;
            $data['result'] = [];
        }

        $response['count'] = (int) $data['total'];

        if (isset($data['facets']) && $this->dataHandler->getApiVersion() >= 15) {
            $response['facets'] = $data['facets'];
        }

        if ($data['result'] !== false && count($data['result']) > 0) {
            $response['result'] = $this->refactorProductResponse($data['result'], $id_customer);
        } else {
            $response['result'] = [];
        }

        $response['sort_orders'] = $response['count'] > 0 && isset($sortOrders) ? $sortOrders : [];
        $this->response(true, null, $response);
    }

    private function getList16(
        $order,
        $search,
        $page,
        $limit
    ) {
        $orderOptions = $this->getOrderFieldAndWay($order);
        if ($orderOptions !== null) {
            $orderField = $orderOptions['field'];
            $orderWay = $orderOptions['way'];
        } else { // Default sort
            $orderField = 'position';
            $orderWay = 'desc';
            $order = 'product.position.desc';
        }

        $data = Search::find((int) Context::getContext()->language->id, $search, $page, $limit, $orderField, $orderWay);

        $sortOrdersTemp = [
            'position' => [
                'desc' => $this->module->l('Relevance', 'searchws'),
            ],
            'name' => [
                'asc' => $this->module->l('Name, A to Z', 'searchws'),
                'desc' => $this->module->l('Name, Z to A', 'searchws'),
            ],
            'price' => [
                'asc' => $this->module->l('Price, lowest first', 'searchws'),
                'desc' => $this->module->l('Price, highest first', 'searchws'),
            ],
        ];

        $sortOrders = [];

        foreach ($sortOrdersTemp as $fieldName => $directions) {
            foreach ($directions as $direction => $label) {
                $encodedOrder = 'product.'.$fieldName.'.'.$direction;
                $sortOrders[] = [
                    'label' => $label,
                    'encoded_order' => $encodedOrder,
                    'selected' => ($encodedOrder == $order),
                ];
            }
        }

        return $data;
    }

    private function getList17($search, $limit)
    {
        $handler = new SearchHandler($search);
        $searchVariables = $handler->getProductSearchVariablesResults($limit);
        $data = [];
        $result = $searchVariables['result'];
        $data['result'] = $searchVariables['products'];
        $selectedSort = $result->getCurrentSortOrder();

        $data['total'] = (int) $result->getTotalProductsCount();

        $sortsData = $result->getAvailableSortOrders();
        $sortOrders = $this->refactorSortOrder($sortsData, $selectedSort);

        $facetsData = $result->getFacetCollection() != null ? $result->getFacetCollection()->getFacets() : null;
        $data['facets'] = $this->refactorFacets($facetsData);
        $data['sort_orders'] = $sortOrders;

        return $data;
    }
}
