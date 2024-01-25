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
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\CheckoutCustomFieldsHandler;
use Validate;

class CheckoutCustomFieldsWS extends BaseWS
{
    /** @var CheckoutCustomFieldsHandler $handler */
    private $handler = null;

    /** @var CartHandler $handler */
    private $cartHandler = null;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->cacheEnabled = false;

        if ($this->isModuleActive('customfields')) {
            $this->handler = new CheckoutCustomFieldsHandler();
        }
        $this->cartHandler = new CartHandler();
    }

    public function getList($params)
    {
        if (!$this->isModuleActive('customfields')) {
            $this->response(false, $this->module->l('Module not installed', 'checkoutcustomfieldsws'), null);
        }

        $this->response(true, null, $this->handler->getFields());
    }

    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!$this->isModuleActive('customfields')) {
            $this->response(false, $this->module->l('Module not installed', 'checkoutcustomfieldsws'), null);
        }

        if (!array_key_exists('step', $params) || !$this->handler->isValidStep($params['step'])) {
            $this->response(false, $this->module->l('Invalid step', 'checkoutcustomfieldsws'), null);
        }

        $step = $params['step'];

        if (!array_key_exists('fields', $params)) {
            $this->response(false, $this->module->l('Invalid parameters', 'checkoutcustomfieldsws'), null);
        }

        $fields = $params['fields'];

        $cart = $this->cartHandler->getCart($id_customer, false, $this->getCurrencyId());

        if (Validate::isLoadedObject($cart)) {
            $res = $this->handler->saveFields($fields, $step, $cart->id);
            $this->response(
                $res['success'],
                $res['errors'],
                []
            );
        } else {
            $success = false;
            $this->response(
                $success,
                $this->module->l('Cart not found', 'checkoutcustomfieldsws')
            );
        }
    }
}
