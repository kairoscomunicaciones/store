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

use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\WishlistHandler;

class WishlistWS extends BaseWS
{
    /**@var WishlistHandler $handler */
    private $handler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->handler = new WishlistHandler($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getMine($customerId)
    {
        $response = $this->handler->getProducts($customerId);
        $this->response(true, null, $response);
    }

    private function addRemoveProduct($customerId, $quantity)
    {
        $bodyParams = $this->dataHandler->getBodyParams();

        $response = $this->handler->addRemoveProduct(
            $customerId,
            array_key_exists('id_product', $bodyParams) ? (int) $bodyParams['id_product'] : null,
            array_key_exists('id_product_attribute', $bodyParams) ? (int) $bodyParams['id_product_attribute'] : null,
            $quantity,
            true
        );

        $this->response(true, null, $response);
    }

    public function addProduct($customerId)
    {
        $this->addRemoveProduct($customerId, 1);
    }

    public function removeProduct($customerId)
    {
        $this->addRemoveProduct($customerId, -1);
    }
}
