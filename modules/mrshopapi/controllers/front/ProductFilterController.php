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

use MrAPPs\MrShopApi\Utils\ApiUtils;
use PrestaShop\PrestaShop\Core\Product\Search\FacetsRendererInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;

class ProductFilterController extends ProductListingFrontController
{
    /** string Internal controller name */
    public $php_self = '';

    /** @var bool If set to false, customer cannot view the current category. */
    public $customer_access = true;

    /**
     * Initializes controller.
     *
     * @see FrontController::init()
     *
     * @throws PrestaShopException
     */
    public function init()
    {
        parent::init();
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->container = $this->buildContainer();
        }
    }

    protected function getProductSearchQuery()
    {
        throw new \Exception('Not implemented yet');
    }

    protected function getDefaultProductSearchProvider()
    {
        throw new \Exception('Not implemented yet');
    }

    public function getListingLabel()
    {
        throw new \Exception('Not implemented yet');
    }

    private function getProductSearchProviderFromModules($query)
    {
        $providers = Hook::exec(
            'productSearchProvider',
            ['query' => $query],
            null,
            true
        );

        if (!ApiUtils::isArray($providers)) {
            $providers = [];
        }
        foreach ($providers as $provider) {
            if ($provider instanceof ProductSearchProviderInterface) {
                return $provider;
            }
        }
    }

    public function getProductSearchVariablesResults($handler, $resultsPerPage = 10)
    {
        // the search provider will need a context (language, shop...) to do its job
        $context = $this->getProductSearchContext();

        // the controller generates the query...
        /**@var \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query*/
        $query = $handler->getProductSearchQuery();

        $this->php_self = $handler->getControllerName();

        // ...modules decide if they can handle it (first one that can is used)
        $provider = $query->getQueryType() != 'best-sales' ? $this->getProductSearchProviderFromModules($query) : null;

        // if no module wants to do the query, then the core feature is used
        if (null === $provider) {
            $provider = $handler->getDefaultProductSearchProvider();
        }

        // we need to set a few parameters from back-end preferences
        $query
            ->setResultsPerPage($resultsPerPage)
            ->setPage(max((int) Tools::getValue('page'), 1));

        // get the parameters containing the encoded facets from the URL
        $encodedFacets = Tools::getValue('q');

        $query->setEncodedFacets($encodedFacets);

        Hook::exec('actionProductSearchProviderRunQueryBefore', [
            'query' => $query,
        ]);

        // We're ready to run the actual query!

        $result = $provider->runQuery(
            $context,
            $query
        );

        Hook::exec('actionProductSearchProviderRunQueryAfter', [
            'query' => $query,
            'result' => $result,
        ]);

        if (!$result->getCurrentSortOrder()) {
            $result->setCurrentSortOrder($query->getSortOrder());
        }

        // prepare the products
        $products = $this->prepareMultipleProductsForTemplate(
            $result->getProducts()
        );

        // render the facets
        if ($provider instanceof FacetsRendererInterface) {
            // with the provider if it wants to
            $rendered_facets = $provider->renderFacets(
                $context,
                $result
            );
            $rendered_active_filters = $provider->renderActiveFilters(
                $context,
                $result
            );
        } else {
            // with the core
            $rendered_facets = $this->renderFacets(
                $result
            );
            $rendered_active_filters = $this->renderActiveFilters(
                $result
            );
        }

        $pagination = $this->getTemplateVarPagination(
            $query,
            $result
        );

        $sort_orders = $this->getTemplateVarSortOrders(
            $result->getAvailableSortOrders(),
            $query->getSortOrder()->toString()
        );

        $sort_selected = false;
        if (!empty($sort_orders)) {
            foreach ($sort_orders as $order) {
                if (isset($order['current']) && true === $order['current']) {
                    $sort_selected = $order['label'];

                    break;
                }
            }
        }

        $searchVariables = [
            'result' => $result,
            'label' => $handler->getListingLabel(),
            'products' => $products,
            'sort_orders' => $sort_orders,
            'sort_selected' => $sort_selected,
            'pagination' => $pagination,
            'rendered_facets' => $rendered_facets,
            'rendered_active_filters' => $rendered_active_filters,
            'js_enabled' => $this->ajax,
            'current_url' => $this->updateQueryString([
                'q' => $result->getEncodedFacets(),
            ]),
        ];

        if (version_compare(_PS_VERSION_, '1.7.1.0', '>=')) {
            Hook::exec('filterProductSearch', ['searchVariables' => &$searchVariables]);
            Hook::exec('actionProductSearchAfter', $searchVariables);
        }

        return $searchVariables;
    }
}
