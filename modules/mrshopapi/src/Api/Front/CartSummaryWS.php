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
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;

class CartSummaryWS extends BaseWS
{
    /**@var CartHandler $handler */
    private $handler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled   = false;
        $this->handler        = new CartHandler($this->dataHandler->getGuestId());
    }

    public function getList($params, $id_customer = null, $cart = null, $message = null, $checkPsVersion = false)
    {
        if ($cart === null) {
            $cart = $this->handler->getCart($id_customer, true, $this->getCurrencyId());
        }

        if ($cart !== null) {
            $paymentMethod = is_array($params) && array_key_exists(
                'payment_method',
                $params
            ) ? trim($params['payment_method']) : null;
            $response = $this->handler->getCartSummary($cart, $id_customer, $message, $checkPsVersion, $paymentMethod);
            $this->response(true, null, $response);
        } else {
            ResponseHandler::notFound($this->module->l('Cart not found', 'cartsummaryws'));
        }
    }
}
