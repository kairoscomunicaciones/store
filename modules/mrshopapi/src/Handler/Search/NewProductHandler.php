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

use PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class NewProductHandler extends AbstractSearchHandler
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getControllerName()
    {
        return 'new-products';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', 'date_add', 'desc');
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('new-products');
    }

    public function getDefaultProductSearchProvider()
    {
        return new NewProductsProductSearchProvider(
            $this->getTranslator()
        );
    }

    public function getListingLabel()
    {
        return $this->getTranslator()->trans(
            'New products',
            [],
            'Shop.Theme.Catalog'
        );
    }
}
