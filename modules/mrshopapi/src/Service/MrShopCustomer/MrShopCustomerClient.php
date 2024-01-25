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

namespace MrAPPs\MrShopApi\Service\MrShopCustomer;

use Configuration;
use MrAPPs\MrShopApi\Exceptions\HttpException;

use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;

class MrShopCustomerClient
{
    const CHECK_PATH    = '/customers/app/check';

    const QR_CODE_PATH = '/customers/qr-code';

    const SEND_APP_PATH = '/v2/apps/send';

    const REQUEST_EDIT_PATH = '/v2/apps/request-edit';

    const MODULES_LIST_PATH = '/mrshop/modules/list';

    const MODULE_VERSIONS_PATH = '/mrshop/modules/{moduleName}/versions';

    /**
     * Checks the subscription status
     * @return array
     */
    public function checkStatus()
    {
        $request = MrShopCustomerRequest::create(
            'GET',
            self::CHECK_PATH
        );

        return $this->sendRequest($request);
    }

    /**
     * Sends the request for app publishing
     * @return array{appId: int, requestAppUrl: string}
     */
    public function sendAppRequest()
    {
        $psShopId = false;
        $fromAddons = ApiUtils::isPsBilling();

        if ($fromAddons) {
            $accountsFacade = ApiUtils::getModule()->container->getService('mrshopapi.ps_accounts_facade');
            $accountsPresenter = $accountsFacade->getPsAccountsPresenter()
                ->present(ApiUtils::getModule()->name);
            $psShopId = $accountsPresenter['currentShop']['uuid'];
        }

        $body = [
            'mrshopVersion' => ApiUtils::getModule()->version,
            'prestashopVersion' => _PS_VERSION_,
            'email' => trim(Configuration::get('PS_SHOP_EMAIL')),
            'appTitle' => trim(Configuration::get('MRSHOP_APP_NAME')),
            'fromAddons' => $fromAddons,
            'psShopId' => $psShopId == false ? null : $psShopId
        ];

        $request = MrShopCustomerRequest::create(
            'POST',
            self::SEND_APP_PATH
        )->setBody($body);

        return $this->sendRequest($request);
    }

    public function sendQrCodeAppRequest()
    {
        $request = MrShopCustomerRequest::create(
            'GET',
            self::QR_CODE_PATH
        );

        $request->setQueryString([
            'name' => trim(Configuration::get('MRSHOP_APP_NAME'))
        ]);

        return $this->sendRequest($request);
    }

    public function sendRequestEdit()
    {
        $request = MrShopCustomerRequest::create(
            'POST',
            self::REQUEST_EDIT_PATH
        );

        return $this->sendRequest($request);
    }

    public function getModulesList()
    {
        $request = MrShopCustomerRequest::create(
            'GET',
            self::MODULES_LIST_PATH
        );

        return $this->sendRequest($request);
    }

    /**
     * Get module versions changelog
     * @return array
     */
    public function getModuleVersions($moduleName)
    {
        $request = MrShopCustomerRequest::create(
            'GET',
            str_replace(
                '{moduleName}',
                $moduleName,
                self::MODULE_VERSIONS_PATH
            )
        );

        return $this->sendRequest($request);
    }

    protected function sendRequest(MrShopCustomerRequest $request)
    {
        $curl = curl_init();
        $body = $request->getJsonBody();

        $headers = array_merge(
            $request->getHeaders(),
            [
                    'Content-Type: application/json',
                    'Content-Length: '.Tools::strlen($body)
                ]
        );

        $options = [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL            => $request->getUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 1,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_CUSTOMREQUEST  => $request->getMethod(),
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers
        ];

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $error = curl_errno($curl)
                ? curl_error($curl)
                : null;

        curl_close($curl);

        if (isset($error)) {
            $errorBody = json_decode($error, true);

            $msg = isset($errorBody['error']) && isset($errorBody['error']['message'])
                    ? $errorBody['error']['message']
                    : '';

            throw new HttpException($msg, $http_status);
        }

        return json_decode($response, true);
    }
}
