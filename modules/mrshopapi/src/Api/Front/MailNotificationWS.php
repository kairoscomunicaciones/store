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

use Controller;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

class MailNotificationWS extends BaseWS
{
    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!$this->isModuleActive('mrshopmailnotifications')) {
            ResponseHandler::unauthorized('Module not installed', 'mailnotificationws');
        }

        if (!$params || count($params) === 0 || !array_key_exists('id_product', $params) || !array_key_exists('id_product_attribute', $params)) {
            $this->response(false, $this->module->l('Invalid parameters', 'mailnotificationws'), null);
        } else {
            require_once _PS_MODULE_DIR_.'mrshopmailnotifications/controllers/front/actions.php';

            $customerEmail = array_key_exists('customer_email', $params) ? $params['customer_email'] : null;

            /** @var \MrshopMailNotificationsActionsModuleFrontController $controller */
            $controller = Controller::getController('MrshopMailNotificationsActionsModuleFrontController');

            if ($this->dataHandler->getApiVersion() >= 4) {
                $mail_alert = (bool) call_user_func(
                    'MrshopMailNotification::customerHasNotification',
                    $id_customer,
                    $params['id_product'],
                    $params['id_product_attribute'],
                    (int) $this->context->shop->id,
                    null,
                    $customerEmail
                );
            } else {
                $mail_alert = null; // Backwards compatibility
            }

            if ($mail_alert) {
                // Remove notification
                if (call_user_func(
                    'MrshopMailNotification::deleteAlert',
                    $id_customer,
                    $customerEmail,
                    $params['id_product'],
                    $params['id_product_attribute'],
                    (int) $this->context->shop->id
                )) {
                    ResponseHandler::success([
                        'message' => $this->module->l('Notification successfully removed', 'mailnotificationws'),
                    ]);
                } else {
                    ResponseHandler::error(
                        $this->module->l('An error occurred while processing your request, please try again later', 'mailnotificationws')
                    );
                }
            } else {
                // Add notification
                $data = $controller->addNotification(
                    $customerEmail,
                    $id_customer,
                    $params['id_product'],
                    $params['id_product_attribute']
                );

                if ($data['error'] === true) {
                    ResponseHandler::error($data['message']);
                } else {
                    ResponseHandler::success([
                        'message' => $this->module->l('When the product will be available you\'ll receive an email', 'mailnotificationws'),
                    ]);
                }
            }
        }
    }
}
