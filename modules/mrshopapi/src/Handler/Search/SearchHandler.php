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

namespace MrAPPs\MrShopApi\Handler\Search;

use PrestaShop\PrestaShop\Adapter\Search\SearchProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class SearchHandler extends AbstractSearchHandler
{
    protected $search_string;

    public function __construct($search_string)
    {
        parent::__construct();
        $this->search_string = $search_string;
    }

    public function getControllerName()
    {
        return 'search';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', 'position', 'desc');
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setSearchString($this->search_string);
    }

    public function getDefaultProductSearchProvider()
    {
        return new SearchProductSearchProvider(
            $this->getTranslator()
        );
    }

    public function getListingLabel()
    {
        return $this->getTranslator()->trans('Search results', [], 'Shop.Theme.Catalog');
    }
}
