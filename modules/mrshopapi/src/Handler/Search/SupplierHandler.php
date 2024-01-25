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

use PrestaShop\PrestaShop\Adapter\Supplier\SupplierProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class SupplierHandler extends AbstractSearchHandler
{
    protected $supplier_id;

    public function __construct($supplier_id)
    {
        parent::__construct();
        $this->supplier_id = $supplier_id;
    }

    public function getControllerName()
    {
        return 'supplier';
    }

    public function getDefaultSort()
    {
        return new SortOrder('product', 'position', 'asc');
    }

    public function getProductSearchQuery()
    {
        return $this->getQuery()
            ->setQueryType('supplier')
            ->setIdSupplier($this->supplier_id);
    }

    public function getDefaultProductSearchProvider()
    {
        return new SupplierProductSearchProvider(
            $this->getTranslator(),
            $this->supplier_id
        );
    }

    public function getListingLabel()
    {
        return '';
    }
}
