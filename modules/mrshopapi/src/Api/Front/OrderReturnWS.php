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

use Configuration;
use Currency;
use DateTime;
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\CustomizationHandler;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use Order;
use OrderDetail;
use OrderReturn;
use OrderReturnState;
use Tools;

class OrderReturnWS extends BaseWS
{
    private $prefix;

    private $customizationHandler;

    private $externalModuleService;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->prefix = Configuration::get('PS_RETURN_PREFIX', $this->context->language->id);
        $this->customizationHandler = new CustomizationHandler();
        $this->externalModuleService = new ExternalModuleService();
    }

    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!array_key_exists('id_order', $params)) {
            $this->response(false, $this->module->l('Order not found', 'orderws'));
        }

        if (!array_key_exists('products', $params) || count($params['products']) == 0) {
            $this->response(false, $this->module->l('Select min. 1 product for continue with return', 'orderws'));
        }

        $order = new Order((int) $params['id_order']);
        if (!$order->isReturnable()) {
            $this->response(false, $this->module->l('Order cannot be return at the moment', 'orderws'));
        }

        $message = array_key_exists('message', $params) ? $params['message'] : null;

        $orderReturn = new OrderReturn();
        $orderReturn->id_order = $order->id;
        $orderReturn->id_customer = $id_customer;
        $orderReturn->state = '1';
        $orderReturn->date_add = new DateTime();
        $orderReturn->date_upd = new DateTime();
        $orderReturn->question = $message;
        $orderReturn->save();

        foreach ($params['products'] as $product) {
            $orderDetail = new OrderDetail((int) $product['id']);
            if ($orderDetail->id != 0) {
                Db::getInstance()->insert(
                    'order_return_detail',
                    [
                        'id_order_return' => (int) $orderReturn->id,
                        'id_order_detail' => (int) $orderDetail->id,
                        'product_quantity' => (int) $product['quantity'],
                        'id_customization' => (int) $orderDetail->id_customization
                    ]
                );
            }
        }

        $this->getDetail($orderReturn->id, $id_customer);
    }

    public function getList($params, $id_customer)
    {
        $orderReturns = OrderReturn::getOrdersReturn($id_customer);

        $out = [];
        foreach ($orderReturns as $or) {
            $idOrderReturn = (int) $or['id_order_return'];
            $out[] = [
                'id' => $idOrderReturn,
                'id_order' => (int) $or['id_order'],
                'returnable_number' => $this->getReturnNumber($idOrderReturn),
                'state' => $or['state_name'],
                'reference' => $or['reference'],
                'date_add' => (new DateTime($or['date_add']))->getTimestamp(),
                'date_update' => (new DateTime($or['date_upd']))->getTimestamp(),
            ];
        }

        $this->response(true, null, $out);
    }

    public function getDetail($id, $id_customer)
    {
        $orderReturn = new OrderReturn($id);
        $state = new OrderReturnState((int) $orderReturn->state);

        $order = new Order($orderReturn->id_order);
        $orderReturnProducts = OrderReturn::getOrdersReturnProducts($id, $order);
        $products = [];

        foreach ($orderReturnProducts as $orderDetailId => $detail) {
            $idCustomization = !empty($detail['id_customization']) ? (int) $detail['id_customization'] : null;
            $customizedFields = [];
            if ($idCustomization != false) {
                $customizedFields = $this->customizationHandler->getProductCustomizationValues($order->id_cart, (int) $detail['id_product']);
            }

            $idProduct = (int) $detail['id_product'];
            $coverImages = ProductBaseWS::getCoverImages($idProduct);
            $detail['image'] = $coverImages['image'];

            if ($this->dataHandler->isModuleActive('egbuycentimeter') && (!empty($product->unity))) {
                $quantities = $this->externalModuleService->egbuycentimeterGetQuantities(
                    $product->id,
                    0,
                    $detail['product_quantity']
                );

                $detail['unit_increment'] = $quantities['unit_increment'];
            } else {
                $detail['unit_increment'] = null;
            }

            $products[] = [
                'id_order_detail' => $orderDetailId,
                'id_product' => $detail['id_product'],
                'product_name' => $detail['product_name'],
                'quantity' => $detail['product_quantity'],
                'unit_increment' => $detail['unit_increment'],
                'id_customization' => $idCustomization,
                'customized_fields' => $customizedFields,
                'image' => $detail['image']
            ];
        }

        $this->response(true, null, [
            'id' => $orderReturn->id,
            'id_order' => $order->id,
            'returnable_number' => $this->getReturnNumber($orderReturn->id),
            'state' => $state->name[$this->context->language->id],
            'reference' => $order->reference,
            'date_add' => (new DateTime($orderReturn->date_add))->getTimestamp(),
            'date_update' => (new DateTime($orderReturn->date_upd))->getTimestamp(),
            'products' => $products
        ]);
    }

    private function getReturnNumber($idOrderReturn)
    {
        return $this->prefix.sprintf('%06d', $idOrderReturn);
    }
}
