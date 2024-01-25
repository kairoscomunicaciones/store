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
require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php';

class mrshopapiwebserviceproductsshareModuleFrontController extends mrshopapibaseModuleFrontController
{
    public function initContent()
    {
        $link = $this->context->link;

        $productId = (int) Tools::getValue('id_product');
        $productAttributeId = (int) Tools::getValue('id_product_attribute');
        $redirectUrl = null;

        try {
            $product = new Product($productId);

            if (Validate::isLoadedObject($product)) {
                $redirectUrl = $link->getProductLink($product, null, null, null, null, null, $productAttributeId);
            }
        } catch (\Exception $e) {
        }

        if ($redirectUrl == null) {
            $redirectUrl = $link->getPageLink('pagenotfound');
        }

        Tools::redirect($redirectUrl);
    }
}
