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

use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\Fidelity\FidelityHandlerException;
use MrAPPs\MrShopApi\Handler\Fidelity\UnsupportedFidelityActionException;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class FidelityWS extends ProductBaseWS
{
    private $handler = null;

    /**
     * FidelityWS constructor.
     *
     * @param $dataHandler
     */
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->handler = ApiUtils::getFidelityHandler();
    }

    /**
     * Get rewards history, rewards account and products gifts list.
     * Transform credits and ask for payment
     *
     * @param $params
     * @param $customerId
     */
    public function getList($params, $customerId)
    {
        if (!$this->handler->isModuleActive()) {
            $this->response(false, $this->module->l('Module not installed', 'fidelityws'), null);
        }

        $action = isset($params['id']) && !empty($params['id']) ? $params['id'] : null;

        if (!$this->handler->isFidelityProgramActive()) {
            $this->response(false, $this->module->l('Fidelity program is disabled', 'fidelityws'), null);
        }

        switch ($action) {
            case 'transform_credits':
                try {
                    $result = $this->handler->transformCredits($customerId, $params);

                    if ($result) {
                        $this->response(true, null, ['success_message' => $this->module->l('Voucher created with success', 'fidelityws')]);
                    } else {
                        $this->response(false, $this->module->l('Voucher can\'t be created', 'fidelityws'), null);
                    }
                } catch (FidelityHandlerException $ex) {
                    $this->response(false, $ex->getMessage(), null);
                }

                break;

            case 'ask_for_payment':
                // returns the site page to reward
                try {
                    $redirectUrl = $this->handler->getAskForPaymentUrl($customerId);
                    $this->response(true, null, ['redirect_url' => $redirectUrl]);
                } catch (UnsupportedFidelityActionException $ex) {
                    ResponseHandler::methodNotAllowed($ex->getMessage());
                } catch (FidelityHandlerException $ex) {
                    $this->response(false, $ex->getMessage());
                }

                break;

            case 'history':
                $page = isset($params['page']) ? (int) $params['page'] : 1;
                $limit = isset($params['limit']) && (int) $params['limit'] > 0 ? (int) $params['limit'] : $this->defaultLimit;
                $this->response(true, null, $this->handler->getRewardsHistory($customerId, $page, $limit));

                break;

            case 'account':
                $this->response(true, null, $this->handler->getRewardsAccount($customerId));

                break;

            case 'gifts_list':
                $page = isset($params['page']) ? $params['page'] : 1;
                $limit = isset($params['limit']) && (int) $params['limit'] > 0 ? (int) $params['limit'] : $this->defaultLimit;
                $order = isset($params['order']) ? $params['order'] : null;

                $orderOptions = $this->getOrderFieldAndWay($order);
                if ($orderOptions !== null) {
                    $orderField = $orderOptions['field'];
                    $orderWay = $orderOptions['way'];
                    $orderFieldForModule = explode('.', $orderField)[1];
                } else { // Default sort
                    $orderField = 'position';
                    $orderWay = 'desc';
                    $order = 'product.position.desc';
                    $orderFieldForModule = $orderField;
                }

                $data = $this->handler->getProductsGiftsList((int) $page, (int) $limit, $orderFieldForModule, $orderWay);
                $response = [];
                $response['count'] = (int) $data['total'];
                if ($data['result'] !== false && count($data['result']) > 0) {
                    $response['result'] = $this->refactorProductResponse($data['result'], $customerId);
                } else {
                    $response['result'] = [];
                }

                $sortOrdersTemp = [
                    'position' => [
                        'desc' => $this->module->l('Relevance', 'fidelityws'),
                    ],
                    'name' => [
                        'asc' => $this->module->l('Name, A to Z', 'fidelityws'),
                        'desc' => $this->module->l('Name, Z to A', 'fidelityws'),
                    ],
                    'price' => [
                        'asc' => $this->module->l('Price, lowest first', 'fidelityws'),
                        'desc' => $this->module->l('Price, highest first', 'fidelityws'),
                    ],
                ];

                $sortOrders = [];
                foreach ($sortOrdersTemp as $fieldName => $directions) {
                    foreach ($directions as $direction => $label) {
                        $encodedOrder = 'product.'.$fieldName.'.'.$direction;
                        $sortOrders[] = [
                            'label' => $label,
                            'encoded_order' => $encodedOrder,
                            'selected' => ($encodedOrder == $order),
                        ];
                    }
                }

                $response['sort_orders'] = $response['count'] > 0 ? $sortOrders : [];
                $this->response(true, null, $response);

                break;

            default:
                $this->response(false, $this->module->l('There was an error while retrieving data', 'fidelityws'));

                break;
        }
    }

    /**
     * Purchase a gift with products
     *
     * @param $params
     * @param $id
     * @param $idCustomer
     */
    public function updateOrCreate($params, $id, $idCustomer)
    {
        if (!$this->handler->isModuleActive()) {
            $this->response(false, $this->module->l('Module not installed', 'fidelityws'), null);
        }

        $action = !empty($id)
            ? $id
            : null;

        try {
            switch ($action) {
                case 'transform_credits':
                    try {
                        $result = $this->handler->transformCredits($idCustomer, $params);

                        if ($result) {
                            $this->response(true, null, ['success_message' => $this->module->l('Voucher created with success', 'fidelityws')]);
                        } else {
                            $this->response(false, $this->module->l('Voucher can\'t be created', 'fidelityws'), null);
                        }
                    } catch (FidelityHandlerException $ex) {
                        $this->response(false, $ex->getMessage(), null);
                    }

                    break;
                case 'purchase_with_rewards':
                    $idProduct = false == empty($params['id_product'])
                        ? $params['id_product']
                        : null;

                    $idProductAttribute = false == empty($params['id_product_attribute'])
                        ? $params['id_product_attribute']
                        : null;

                    $purchase = $this->handler->purchaseWithRewards($idProduct, $idProductAttribute);
                    if (empty($purchase['has_error'])) {
                        $this->response(true, null, ['success_message' => $this->module->l('Gift product purchased with success!', 'fidelityws')]);
                    } else {
                        $this->response(false, $purchase['error_msg'], null);
                    }

                    break;
                default:
                    $this->response(false, $this->module->l('Invalid action', 'fidelityws'), null);

                    break;
            }
        } catch (UnsupportedFidelityActionException $ex) {
            ResponseHandler::methodNotAllowed($ex->getMessage());
        } catch (FidelityHandlerException $ex) {
            $this->response(false, $ex->getMessage(), null);
        }
    }
}
