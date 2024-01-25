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

use PrestaShop\PrestaShop\Adapter\Manufacturer\ManufacturerProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Tools;

class ManufacturerHandler extends AbstractSearchHandler
{
    protected $manufacturer;

    public function __construct($manufacturer)
    {
        parent::__construct();
        $this->manufacturer = $manufacturer;
    }

    public function getControllerName()
    {
        return 'manufacturer';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', Tools::getProductsOrder('by'), Tools::getProductsOrder('way'));
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('manufacturer')
            ->setIdManufacturer($this->manufacturer->id);
    }

    public function getDefaultProductSearchProvider()
    {
        return new ManufacturerProductSearchProvider(
            $this->getTranslator(),
            $this->manufacturer
        );
    }

    public function getListingLabel()
    {
        return '';
    }
}
