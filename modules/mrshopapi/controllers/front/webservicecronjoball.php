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
require_once _PS_MODULE_DIR_.'mrshopapi/classes/AppHome.php';

use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\NotificationsHandler;

class mrshopapiwebservicecronjoballModuleFrontController extends mrshopapibaseModuleFrontController
{
    public function initContent()
    {
        if (!Configuration::get('MRSHOP_CRONJOB_CONFIGURED')) {
            Configuration::updateValue('MRSHOP_CRONJOB_CONFIGURED', true);
        }

        $messages = [];

        // BANNERS
        AppHome::activateBanners();
        $messages[] = 'Banners activated';

        // NOTIFICATIONS
        $notificationsHandler = new NotificationsHandler($this->module);

        if ($notificationsHandler->sendNotifications()) {
            $messages[] = 'Notifications updated!';
        } else {
            $messages[] = 'No notifications to send';
        }

        ResponseHandler::success(join(' - ', $messages));
    }
}
