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

namespace MrAPPs\MrShopApi\Handler\Api;

use Context;
use MrAPPs\MrShopApi\Api\Admin\AppConfigurationWS;
use MrAPPs\MrShopApi\Api\Admin\AppCustomerWS;
use MrAPPs\MrShopApi\Api\Admin\AppHomeSettingsWS;
use MrAPPs\MrShopApi\Api\Admin\AutocompleteSearchWS;
use MrAPPs\MrShopApi\Api\Admin\CatalogWS;
use MrAPPs\MrShopApi\Api\Admin\CmsPageWS;
use MrAPPs\MrShopApi\Api\Admin\ImageUploadWS;
use MrAPPs\MrShopApi\Api\Admin\NotificationWS;
use MrAPPs\MrShopApi\Api\Admin\OrderStatusNotificationWS;
use MrAPPs\MrShopApi\Api\Admin\PaymentModulesWS;
use MrAPPs\MrShopApi\Api\Admin\TranslationsWS;
use MrAPPs\MrShopApi\Api\Admin\TypologiesWS;

class AdminApiRequestHandler extends AbstractApiRequestHandler
{
    public $resourceList;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        Context::getContext()->cart = new \Cart();
        Context::getContext()->customer = new \Customer();

        $devMode = defined('MRSHOP_DEV_MODE') && MRSHOP_DEV_MODE;

        $this->resourceList = [
            'translations' => [
                'class' => TranslationsWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['POST', 'PUT', 'PATCH', 'DELETE'],
            ],
            'configuration' => [
                'class' => AppConfigurationWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE'],
            ],
            'send_app' => [
                'class' => AppConfigurationWS::class,
                'custom_class_method' => 'sendApp',
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE'],
            ],
            'qrcode_app' => [
                'class' => AppConfigurationWS::class,
                'custom_class_method' => 'getQrCodeApp',
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE', 'POST'],
            ],
            'send_request_edit' => [
                'class' => AppConfigurationWS::class,
                'custom_class_method' => 'sendRequestEdit',
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE'],
            ],
            'module_changelog' => [
                'class' => AppConfigurationWS::class,
                'custom_class_method' => 'getModuleChangelog',
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE'],
            ],
            'image' => [
                'class' => ImageUploadWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['GET', 'PUT', 'PATCH', 'DELETE']
            ],
            'homes' => [
                'class' => AppHomeSettingsWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PATCH']
            ],
            'modules' => [
                'class' => PaymentModulesWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PATCH', 'DELETE']
            ],
            'typologies' => [
                'class' => TypologiesWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['POST', 'PUT', 'PATCH', 'DELETE']
            ],
            'autocomplete' => [
                'class' => AutocompleteSearchWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['POST', 'PUT', 'PATCH', 'DELETE']
            ],
            'check_status' => [
                'class' => AppCustomerWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['POST', 'PUT', 'PATCH', 'DELETE']
            ],
            'notifications' => [
                'class' => NotificationWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH']
            ],
            'webhook_notifications' => [
                'class' => OrderStatusNotificationWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH']
            ],
            'categories' => [
                'class' => CatalogWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PUT', 'PATCH', 'DELETE']
            ],
            'cms' => [
                'class' => CmsPageWS::class,
                'auth' => !$devMode,
                'forbidden_method' => ['PUT']
            ]
        ];

        if ($devMode) {
            $this->resourceList['reset_module'] = [
                'class' => AppCustomerWS::class,
                'custom_class_method' => 'resetModule',
                'auth' => false,
                'forbidden_method' => ['POST', 'PUT', 'PATCH', 'DELETE']
            ];
        }
    }

    protected function getUserId($throwErrorIfInvalidJwt)
    {
        $employee = Context::getContext()->employee;
        if (isset($employee) && isset($employee->id)) {
            return $employee->id;
        } else {
            return null;
        }
    }

    protected function isUserAuthorized()
    {
        $employeeId = $this->getUserId(false);

        return false == empty($employeeId);
    }
}
