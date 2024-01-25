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

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Entity\Tools;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class CategoryProductHandler extends AbstractSearchHandler
{
    protected $category;

    public function __construct($category)
    {
        parent::__construct();
        $this->category = $category;
    }

    public function getDefaultProductSearchProvider()
    {
        return new CategoryProductSearchProvider(
            $this->getTranslator(),
            $this->category
        );
    }

    public function getControllerName()
    {
        return 'category';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', Tools::getProductsOrder('by'), Tools::getProductsOrder('way'));
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('category')
            ->setIdCategory($this->category->id);
    }

    public function getListingLabel()
    {
        return $this->getTranslator()->trans(
            'Category: %category_name%',
            ['%category_name%' => $this->category->name],
            'Shop.Theme.Catalog'
        );
    }
}
