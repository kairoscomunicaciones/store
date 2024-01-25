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

namespace MrAPPs\MrShopApi\Api\Front;

use Category;
use Module;
use Product;
use Validate;

class RelatedProductWS extends ProductBaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->allowCacheParameters(['id']);
    }

    public function getList($params, $id_customer = null)
    {
        $product_id = array_key_exists('id', $params) && $params['id'] ? $params['id'] : null;

        if (!$product_id) {
            $this->response(false, 'No product id set');
        }

        $product = new Product((int) $product_id);

        if (!$product->id) {
            $this->response(false, 'No product found with id: '.$product_id);
        }

        $products = [];

        /** @var BlockLayered $module */
        $module = Module::getInstanceByName('ps_categoryproducts');

        if (!Validate::isLoadedObject($module) || !$module->active) {
            $module = Module::getInstanceByName('mrshoprelatedproducts');
            if (!Validate::isLoadedObject($module) || !$module->active) {
                $module = null;
            }
        }

        if ($module) {
            $data = $module->getWidgetVariables(
                null,
                ['product' => $product]
            );

            if ($data !== false) {
                foreach ($data['products'] as $key => $item) {
                    if ($item['id_product'] === $product_id) {
                        unset($data['products'][$key]);
                    }
                }
                $products = $this->refactorProductResponse($data['products'], $id_customer);
            }

            $nbProducts = count($products);
        } else {
            if (isset($product->id_category_default) && $product->id_category_default > 1) {
                $category = new Category((int) $product->id_category_default);

                if (!Validate::isLoadedObject($category) || !$category->active) {
                    $nbProducts = 0;
                } else {
                    $products = $this->refactorProductResponse($category->getProducts($this->context->language->id, 1, 12), $id_customer);

                    for ($i = 0; $i < count($products); ++$i) {
                        $product = $products[$i];

                        if ($product['id'] === (int) $product_id) {
                            unset($products[$i]);
                            $i = (int) count($products);
                        }
                    }

                    $products = array_values($products);

                    $nbProducts = (int) count($products);
                }
            }
        }

        $response = [
            'count' => $nbProducts,
            'result' => $products,
        ];

        $this->response(true, null, $response);
    }
}
