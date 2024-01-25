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

use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class PricesDropHandler extends AbstractSearchHandler
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getControllerName()
    {
        return 'prices-drop';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', 'name', 'asc');
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('prices-drop');
    }

    public function getDefaultProductSearchProvider()
    {
        return new PricesDropProductSearchProvider(
            $this->getTranslator()
        );
    }

    public function getListingLabel()
    {
        return $this->getTranslator()->trans(
            'Prices drop',
            [],
            'Shop.Theme.Catalog'
        );
    }
}
