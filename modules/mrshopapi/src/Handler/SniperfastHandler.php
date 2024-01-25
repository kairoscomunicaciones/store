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

namespace MrAPPs\MrShopApi\Handler;

use Context;
use Db;
use GuzzleHttp\Client;
use Module;
use MrAPPs\MrShopApi\Api\Front\ProductBaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Product;
use ProductFilterController;
use Tools;
use Validate;

class SniperfastHandler
{
    private $context;

    protected $module;

    private $apiVersion = 2;

    private $sniperKey;

    private $sniperName;

    private $sniperSort;

    private $sniperPage;

    private $sniperLimit;

    private $validConfiguration;

    private $httpClient;

    private $selectedFilters;

    private $emptyResult;

    public function __construct($sniperSort, $page, $limit)
    {
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();
        $this->sniperSort = $sniperSort;
        $this->sniperPage = $page;
        $this->sniperLimit = $limit;
        $this->emptyResult = [
            'total' => 0,
            'result' => []
        ];

        $this->httpClient = new Client([
            'base_url' => 'https://api.sniperfast.com'
        ]);

        $sql = "SELECT conf_key, conf_value FROM "._DB_PREFIX_."sniperfast_conf".
            " WHERE id_shop = ".(int) $this->context->shop->id.
            " AND id_lang = ".(int) $this->context->language->id.
            " AND (conf_key = 'sniper_index_name'
			   OR conf_key = 'sniper_key')";

        $res = Db::getInstance()->executeS($sql);

        foreach ($res as $row) {
            switch ($row['conf_key']) {
                case 'sniper_key':
                    $this->sniperKey = $row['conf_value'];

                    break;
                case 'sniper_index_name':
                    $this->sniperName = $row['conf_value'];

                    break;
            }
        }

        $this->validConfiguration = !!$this->sniperKey && !!$this->sniperName;
    }

    private function parseSelectedFilters($q)
    {
        $this->selectedFilters = [];

        if (!empty($q)) {
            $activeFilters = explode("/", $q);
            foreach ($activeFilters as $singleFilter) {
                $singleFilter = explode("=", $singleFilter);
                $key = $singleFilter[0];
                $data = $singleFilter[1];
                $this->selectedFilters[$key] = $data;
            }
        }
    }

    private function composeEncodedFacets($selectedFilters)
    {
        $func = function ($key, $filter) {
            return "{$key}={$filter}";
        };

        return implode('/', array_map($func, array_keys($selectedFilters), array_values($selectedFilters)));
    }

    private function mapFilter($filterKey)
    {
        switch ($filterKey) {
            case 'categories:lv1':
                return 'input[cat1]';
            case 'categories:lv2':
                return 'input[cat2]';
            case 'categories:lv3':
                return 'input[cat3]';
            case 'categories:lv4':
                return 'input[cat4]';
            case 'manufacturers':
                return 'filters[manufacturer]';
            case 'price-min':
                return 'filters[price][min]';
            case 'price-max':
                return 'filters[price][max]';
            default:
                return "featured[{$filterKey}]";
        }
    }

    private function mapFacet($fatherKey, $childKey = null)
    {
        return $childKey ? "{$fatherKey}:{$childKey}" : "{$fatherKey}";
    }

    private function composeFormData($additionalData)
    {
        if (!$this->sniperSort) {
            $this->sniperSort = 'rel';
        }

        $data = [
            'apiv' => $this->apiVersion,
            'key' => $this->sniperKey,
            'name' => $this->sniperName,
            'sort' => $this->sniperSort,
            'sort_num' => $this->sniperLimit,
            'sort_page' => $this->sniperPage,
        ];

        foreach ($this->selectedFilters as $key => $selectedFilter) {
            if ($key == 'price') {
                $priceData = explode('|', $selectedFilter);
                $data['filters[price][min]'] = $priceData[0];
                $data['filters[price][max]'] = $priceData[1];

                if (isset($this->selectedFilters['low_high_price'])) {
                    $priceRangeData = explode('|', $this->selectedFilters['low_high_price']);
                    $data['filters[price][low]'] = $priceRangeData[0];
                    $data['filters[price][high]'] = $priceRangeData[1];
                }
            } else {
                if (!in_array($key, ['low_high_price'])) {
                    $data[$this->mapFilter($key)] = $selectedFilter;
                }
            }
        }

        foreach ($additionalData as $key => $item) {
            $data[$key] = $item;
        }

        return http_build_query($data);
    }

    private function addOrRemoveFilter($selected, $facetKey, $facetValue)
    {
        $updatedFilters = array_merge($this->selectedFilters, []);

        if ($selected) {
            unset($updatedFilters[$facetKey]);
        } else {
            $updatedFilters[$facetKey] = $facetValue;
        }

        return $updatedFilters;
    }

    private function mapTranslation($key, $childKey = null)
    {
        switch ($key) {
            case 'feature':
                return $childKey;
            case 'categories':
                return $this->module->l('Categories', 'sniperfasthandler');
            case 'manufacturers':
                return $this->module->l('Manufacturers', 'sniperfasthandler');
            case 'price':
                return $this->module->l('Price', 'sniperfasthandler');
            default:
                return $key;
        }
    }

    private function mapResponse($response)
    {
        $facets = [];

        if (!isset($this->selectedFilters['low_high_price']) && isset($response['facets_data']['price']['low']) && isset($response['facets_data']['price']['high'])) {
            $this->selectedFilters['low_high_price'] = "{$response['facets_data']['price']['low']}|{$response['facets_data']['price']['high']}";
        }

        // facets
        foreach ($response['facets'] as $key => $array) {
            $isAssociative = !isset($array[0]);

            $facetGroup = [
                'id' => $key,
                'label' => $this->mapTranslation($key),
                'type' => $key,
                'input_type' => !empty($key == 'price') ? 'slider' : 'checkbox',
                'unique' => true,
                'filters' => [],
            ];

            foreach ($array as $childKey => $facet) {
                if ($isAssociative) {
                    $isAssociativeFacet = !isset($facet[0]);

                    if (!$isAssociativeFacet) {
                        $facetKey = $this->mapFacet($key, $childKey);
                        $facetGroup = [
                            'id' => $facetKey,
                            'label' => $this->mapTranslation($key, $childKey),
                            'type' => $key,
                            'input_type' => "checkbox",
                            'unique' => true,
                            'filters' => [],
                        ];

                        foreach ($facet as $facetChild) {
                            $selected = isset($this->selectedFilters[$facetKey]) && $this->selectedFilters[$facetKey] == $facetChild['key'];
                            $encodedFacets = $this->composeEncodedFacets(
                                $this->addOrRemoveFilter($selected, $facetKey, $facetChild['key'])
                            );

                            $facetGroup['filters'][] = [
                                'id' => $facetChild['key'],
                                'label' => $facetChild['key'],
                                'products_quantity' => $facetChild['count'],
                                'selected' => $selected,
                                'encoded_facets' => $encodedFacets,
                            ];
                        }

                        if (!empty($facetGroup['filters'])) {
                            $facets[] = $facetGroup;
                        }
                        $facetGroup = null;
                    } else {
                        if ($key == 'price' && $childKey == 'min') {
                            $this->selectedFilters['price'] = '{min}|{max}';

                            $encodedFacets = $this->composeEncodedFacets(
                                $this->selectedFilters
                            );

                            if (isset($this->selectedFilters['low_high_price'])) {
                                $lowHighPrice = explode('|', $this->selectedFilters['low_high_price']);
                                $low = (float) $lowHighPrice[0];
                                $high = (float) $lowHighPrice[1];
                            } else {
                                $low = $response['facets_data']['price']['low'];
                                $high = $response['facets_data']['price']['high'];
                            }

                            $facetGroup['filters'][] = [
                                'id' => $key,
                                'label' => $key,
                                'products_quantity' => null,
                                'symbol' => '€',
                                'min' => $low,
                                'max' => $high,
                                'current_min' => $response['facets_data']['price']['min'],
                                'current_max' => $response['facets_data']['price']['max'],
                                'encoded_facets' => $encodedFacets,
                            ];
                        }
                    }
                } else {
                    $isOneSelected = isset($this->selectedFilters[$key]);
                    $selected = $isOneSelected && $this->selectedFilters[$key] == $facet['key'];

                    $encodedFacets = $this->composeEncodedFacets(
                        $this->addOrRemoveFilter($selected, $key, $facet['key'])
                    );
                    $facetGroup['filters'][] = [
                        'id' => $facet['key'],
                        'label' => $facet['key'],
                        'products_quantity' => $facet['count'],
                        'selected' => $selected,
                        'encoded_facets' => $encodedFacets,
                    ];
                }
            }

            if ($facetGroup && !empty($facetGroup['filters'])) {
                $facets[] = $facetGroup;
            }
        }

        $response['facets'] = $facets;

        foreach ($response['result'] as &$item) {
            $item['id_product'] = $item['id'];
            $product = new Product((int) $item['id_product'], true, $this->context->language->id);

            $item['name'] = $product->name;
            $item['reference'] = $product->reference;
            $item['id_category_default'] = $product->id_category_default;
            $item['id_manufacturer'] = $product->id_manufacturer;
            $item['link_rewrite'] = $product->link_rewrite;
            $item['on_sale'] = $product->on_sale;
            $item['show_price'] = $product->show_price;
            unset($item['id']);
        }

        return $response;
    }

    public function search($query, $q)
    {
        if (!$this->validConfiguration) {
            return $this->emptyResult;
        }

        $this->parseSelectedFilters($q);
        $body = $this->composeFormData(['input[user_input]' => $query]);
        $response = $this->httpClient->post('search', [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8'
            ],
            'body' => $body
        ]);
        $body = \GuzzleHttp\json_decode((string) $response->getBody(), true);

        return isset($body['success']) && $body['success'] ? $this->mapResponse($body) : $this->emptyResult;
    }
}
