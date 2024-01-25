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

use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class BestSalesHandler extends AbstractSearchHandler
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultProductSearchProvider()
    {
        return new BestSalesProductSearchProvider(
            $this->getTranslator()
        );
    }

    public function getControllerName()
    {
        return 'best-sales';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', 'sales', 'desc');
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('best-sales');
    }

    public function getListingLabel()
    {
        return $this->getTranslator()->trans('Best sellers', [], 'Shop.Theme.Catalog');
    }
}
