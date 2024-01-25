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

use Context;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use ProductFilterController;
use Tools;

require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/ProductFilterController.php';

class AbstractSearchHandler
{
    protected $controller;

    protected $translator;

    public function __construct()
    {
        $this->controller = new ProductFilterController();

        $this->controller->init();
        $this->translator = Context::getContext()->getTranslator(true);
    }

    public function getProductSearchVariablesResults($resultsPerPage = 10)
    {
        return $this->controller->getProductSearchVariablesResults($this, $resultsPerPage);
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getControllerName()
    {
        throw new \Exception('missing implementation');
    }

    public function getDefaultSort()
    {
        throw new \Exception('missing implementation');
    }

    public function getQuery()
    {
        if (($encodedSortOrder = Tools::getValue('order'))) {
            $currentSortOrder = SortOrder::newFromString(
                $encodedSortOrder
            );
        } else {
            $currentSortOrder = $this->getDefaultSort();
        }

        $query = new ProductSearchQuery();
        $query->setSortOrder($currentSortOrder);

        return $query;
    }
}
