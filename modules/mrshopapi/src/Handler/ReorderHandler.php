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

use Cart;
use CartRule;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use Order;
use Validate;

class ReorderHandler
{
    private $module = null;

    private $context = null;

    public function __construct($module, $context)
    {
        $this->module = $module;
        $this->context = $context;
    }

    public function reorder($idOrder, $idCustomer)
    {
        if (!$idOrder) {
            ResponseHandler::badRequest($this->module->l('Order not found.', 'reorderhandler'));
        }

        $oldCart = new Cart(Order::getCartIdStatic($idOrder, $idCustomer));
        if (!Validate::isLoadedObject($oldCart)) {
            ResponseHandler::badRequest($this->module->l('Cart cannot be loaded.', 'reorderhandler'));
        }

        $duplication = $oldCart->duplicate();
        if (!$duplication || !Validate::isLoadedObject($duplication['cart'])) {
            ResponseHandler::badRequest($this->module->l('Sorry, we cannot duplicate your order.', 'reorderhandler'));
        }

        if (!$duplication['success']) {
            ResponseHandler::badRequest($this->module->l('Some items are no longer available; we cannot duplicate your order.', 'reorderhandler'));
        }

        // Success
        $this->context->cookie->id_cart = $duplication['cart']->id;
        $this->context->cart = $duplication['cart'];
        CartRule::autoAddToCart($this->context);
        $this->context->cookie->write();
        ResponseHandler::success(['cart_id' => $duplication['cart']->id]);
    }
}
