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

namespace MrAPPs\MrShopApi\Api\Admin;

use Configuration;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetDetailInterface;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface;
use MrAPPs\MrShopApi\Api\Contracts\WebservicePostInterface;
use MrAPPs\MrShopApi\Exceptions\HttpException;
use MrAPPs\MrShopApi\Handler\Api\AppConfigurationHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Service\MrShopCustomer\MrShopCustomerClient;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class AppConfigurationWS extends BaseWS implements WebservicePostInterface, WebserviceGetListInterface, WebserviceGetDetailInterface
{
    /** @var AppConfigurationHandler */
    private $configHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->configHandler = new AppConfigurationHandler($this->module, $dataHandler);
    }

    public function getList($params, $userId)
    {
        $this->respondWithCurrent();
    }

    public function getDetail($id, $userId)
    {
        $this->respondWithCurrent((int) $id);
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        $step = empty($id)
            ? null
            : (int) $id;

        $form = isset($bodyParams['form']) ? $bodyParams['form'] : $bodyParams;
        $updateRequired = isset($bodyParams['updateRequired']) ? $bodyParams['updateRequired'] : false;

        $result = $this->configHandler
            ->save($form, $step);

        if ($result === false) {
            $m = $this->module->l('An error occured while trying to save data', 'appconfigurationws');
            $this->response(false, $m);
        } elseif ($result !== true) {
            $messages = is_array($result)
                ? $result
                : [$result];
            ResponseHandler::unprocessableEntity($messages);
        } else {
            Configuration::updateValue('MRSHOP_APP_UPDATE_REQUIRED', $updateRequired);
            $step = isset($step)
                ? $step
                : null;
            $this->respondWithCurrent($step, $form);
        }
    }

    public function sendApp()
    {
        if (!$this->configHandler->load()->canSend()) {
            $m = $this->module->l('Configuration is invalid, you cannot require an app update', 'appconfigurationws');
            ResponseHandler::unprocessableEntity($m);
        }

        $client = new MrShopCustomerClient();

        try {
            $response = $client->sendAppRequest();

            if (!isset($response['requestAppUrl']) || $response['requestAppUrl'] == null) {
                ApiUtils::setAppRequested();
            }

            ResponseHandler::response(200, null, $response);
        } catch (HttpException $ex) {
            ResponseHandler::response($ex->getCode(), $ex->getMessage());
        }
    }

    public function getQrCodeApp()
    {
        $client = new MrShopCustomerClient();

        try {
            $response = $client->sendQrCodeAppRequest();
            ResponseHandler::response(200, null, $response);
        } catch (HttpException $ex) {
            ResponseHandler::response($ex->getCode(), $ex->getMessage());
        }
    }

    public function sendRequestEdit()
    {
        $client = new MrShopCustomerClient();

        try {
            $response = $client->sendRequestEdit();

            if (!isset($response['requestAppUrl']) || $response['requestAppUrl'] == null) {
                Configuration::updateValue('MRSHOP_APP_UPDATE_REQUIRED', false);
                ApiUtils::setAppRequested();
                ResponseHandler::response(200, null, $response);
            } else {
                ResponseHandler::error(
                    $this->module->l('Before proceed with an App Update you must activate a subscription plan', 'appconfigurationws')
                );
            }
        } catch (HttpException $ex) {
            ResponseHandler::response($ex->getCode(), $ex->getMessage());
        }
    }

    public function getModuleChangelog()
    {
        $client = new MrShopCustomerClient();

        try {
            $response = $client->getModuleVersions(\Tools::getValue('module_name'));
            ResponseHandler::response(200, null, $response);
        } catch (HttpException $ex) {
            ResponseHandler::response($ex->getCode(), $ex->getMessage());
        }
    }

    protected function respondWithCurrent($step = null, $bodyParams = null)
    {
        $s = is_int($step)
                ? $step
                : null;

        $data = $this->configHandler->getCurrent($s, $bodyParams);
        $this->response(true, null, $data);
    }
}
