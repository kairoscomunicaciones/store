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

use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

class mrshopapiwebservicerequestquoteModuleFrontController extends mrshopapibaseModuleFrontController
{
    public function initContent()
    {
        if (Module::isEnabled('euroartigiana')) {
            $jwt = $this->requestHandler->getJwtHandler();
            $customerId = $jwt->getCustomerId();

            if (!$customerId) {
                ResponseHandler::unauthorized('Customer not found');
            }

            $id_product = Tools::getValue('product_id');
            if (!$id_product) {
                ResponseHandler::badRequest('Product id not found');
            }

            $this->setupContext();

            $params = $this->dataHandler->getBodyParams();

            if (array_key_exists('fields', $params)) {
                if (!is_array($params['fields'])) {
                    $fields = json_decode($params['fields'], true);
                } else {
                    $fields = $params['fields'];
                }
            } else {
                $fields = [];
            }

            if (!$fields || !is_array($fields) || count($fields) === 0) {
                ResponseHandler::badRequest('Invalid params');
            }

            $module = Module::getInstanceByName('euroartigiana');
            if (Validate::isLoadedObject($module)) {
                $response = $module->requestQuote($id_product, $fields);

                if ($response['success']) {
                    ResponseHandler::success();
                } else {
                    ResponseHandler::error(implode(', ', $response['errors']));
                }
            }
        }

        ResponseHandler::badRequest('Module not found');
    }
}
