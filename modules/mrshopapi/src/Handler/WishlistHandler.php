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

namespace MrAPPs\MrShopApi\Handler;

use Context;
use Module;
use MrAPPs\MrShopApi\Api\Front\ProductBaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Product;
use Tools;
use Validate;

class WishlistHandler
{
    private $context;

    private $module;

    private $dataHandler;

    public function __construct($dataHandler)
    {
        $this->dataHandler = $dataHandler;
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();

        if (!$this->isModuleActive()) {
            ResponseHandler::badRequest($this->module->l('Module not installed', 'wishlisthandler'));
        }

        require_once _PS_MODULE_DIR_.'mrshopblockwishlist/classes/MrShopWishList.php';
    }

    private function isModuleActive()
    {
        return Module::isEnabled('mrshopblockwishlist');
    }

    public function getWishlist($customerId)
    {
        $wishList = call_user_func('MrShopWishList::getDefault', $customerId);

        if (!empty($wishList)) {
            $mrshopWishlist = new \MrShopWishList((int) $wishList[0]['id_wishlist']);

            return Validate::isLoadedObject($mrshopWishlist) ? $mrshopWishlist : null;
        } else {
            return null;
        }
    }

    public function getOrCreateWishlist($customerId)
    {
        $wishlistName = $this->module->l('My Wishlist', 'wishlisthandler');

        if (method_exists('MrShopWishList', 'getOrCreateDefaultWishlist')) {
            return \MrShopWishList::getOrCreateDefaultWishlist($wishlistName, $customerId);
        }

        // Get Wishlist
        $wishlist = $this->getWishlist($customerId);
        if ($wishlist == null) {
            $wishlist = new \MrShopWishList();
            $wishlist->id_shop = 1;
            $wishlist->id_shop_group = 1;
            $wishlist->name = $wishlistName;
            $wishlist->id_customer = $customerId;
            $wishlist->default = 1;
            list($us, $s) = explode(' ', microtime());
            srand($s * $us);
            $wishlist->token = Tools::strtoupper(Tools::substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$customerId), 0, 16));
            $wishlist->add();
        }

        return $wishlist;
    }

    public function getProducts($customerId, $wishlistData = null)
    {
        if ($wishlistData == null) {
            $wishlistData = $this->getOrCreateWishlist($customerId);
            if ($wishlistData == null) {
                ResponseHandler::badRequest($this->module->l('Wishlist not found', 'wishlisthandler'));
            }
        }

        $products = call_user_func(
            'MrShopWishList::getProductByIdCustomer',
            $wishlistData->id,
            $customerId,
            $this->context->language->id
        );

        $baseWs = new ProductBaseWS($this->dataHandler);

        $wishlist = [
            'id' => (int) $wishlistData->id,
            'name' => $wishlistData->name,
        ];

        foreach ($products as $key => $prodotto) {
            $p = new Product((int) $prodotto['id_product'], true);

            $item = [];
            $item['id'] = $p->id;
            $item['link_rewrite'] = $prodotto['link_rewrite'];
            $item['name'] = $prodotto['name'];
            $item['reference'] = $p->reference;
            $item['quantity_available'] = $p->quantity;
            $item['show_price'] = $p->show_price;
            $item['id_default_image'] = 0;

            unset($item['attributes_small']);

            if ((int) $prodotto['id_product_attribute'] !== 0) {
                $item['id_default_image'] = (int) $p->getCombinationImageById(
                    $prodotto['id_product_attribute'],
                    Context::getContext()->language->id
                )['id_image'];
            }

            if ((int) $item['id_default_image'] == 0) {
                $idProduct = (int) $p->id;
                $coverImages = ProductBaseWS::getCoverImages($idProduct);
                $item = array_merge($item, $coverImages);
            }

            $item['id_category_default'] = (int) $p->id_category_default;
            $item['id_manufacturer'] = (int) $p->id_manufacturer;

            $products[$key] = $baseWs->refactorProduct($item, $customerId, false);
        }

        $wishlist['products'] = $products;

        return $wishlist;
    }

    public function addRemoveProduct($customerId, $productId, $productAttributeId, $quantity, $returnWishlist = false)
    {
        if ($productId === null) {
            ResponseHandler::error($this->module->l('Invalid parameters', 'wishlisthandler'));
        }

        // Get Wishlist
        /** @var \MrShopWishList $wishlist */
        $wishlist = $this->getOrCreateWishlist($customerId);
        $wishlistId = $wishlist->id;

        $id_product_attribute = $productAttributeId !== null ? (int) $productAttributeId : 0;

        // Add or Remove the product
        if ((int) $quantity <= 0) {
            $success = call_user_func('\MrShopWishList::removeProduct', $wishlistId, $customerId, $productId, $id_product_attribute);
        } else {
            $success = call_user_func('\MrShopWishList::addProduct', $wishlistId, $customerId, $productId, $id_product_attribute);
        }

        if (!$success) {
            ResponseHandler::badRequest($this->module->l('Failed to change the wishlist', 'wishlisthandler'));
        }

        if ((bool) $returnWishlist) {
            return $this->getProducts($customerId, $wishlist);
        } else {
            ResponseHandler::successfulEmptyResponse();
        }
    }
}
