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

use Db;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface;
use MrAPPs\MrShopApi\Exceptions\HttpException;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Service\MrShopCustomer\MrShopCustomerClient;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class AppCustomerWS extends BaseWS implements WebserviceGetListInterface
{
    protected $customerClient;
    
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->customerClient = new MrShopCustomerClient();
    }
    
    /**
     * Gets the app status
     * @param array $params
     * @param int $userId
     */
    public function getList($params, $userId)
    {
        try {
            $response = $this->customerClient->checkStatus();

            if (isset($response['appStatus']) && in_array($response['appStatus'], ['COMPLETED', 'REQUEST'])) {
                ApiUtils::setAppRequested();
            }

            if (isset($response['modules'])) {
                $filteredModules = [];

                foreach ($response['modules'] as $module) {
                    if (\Module::isEnabled($module['moduleName'])) {
                        $moduleInstance = \Module::getInstanceByName($module['moduleName']);
                        $filteredModules[] = array_merge(
                            $module,
                            [
                                'needUpdate' => version_compare($moduleInstance->version, $module['version'], "<"),
                                'needInstall' => false
                            ]
                        );
                    } else {
                        $filteredModules[] = array_merge(
                            $module,
                            [
                                'needUpdate' => true,
                                'needInstall' => true
                            ]
                        );
                    }
                }

                $response['modules'] = $filteredModules;
            }

            $hasSubscription = isset($response['haveSubscription']) && $response['haveSubscription'];

            if (!$hasSubscription && isset($response['supportEndDate'])) {
                $date = new \DateTime($response['supportEndDate']);
                $now = new \DateTime('now');
                $dateInterval = new \DateInterval('P2M');
                $date->sub($dateInterval);
                if ($date < $now) {
                    $date->add($dateInterval);
                    $limitExpirationDate = new \DateTime($response['supportEndDate']);
                    $limitExpirationDate->add(new \DateInterval('P1M'));

                    $preRequestAnchor = "<a href='".$response['requestAppUrl']."' target='_blank'>";
                    $afterRequestAnchor = "</a>";

                    $supportExpired = $date < $now
                        ? $this->module->l('has expired on', 'appcustomerws')
                        : $this->module->l('will expire on', 'appcustomerws');

                    $supportEndMessage = sprintf(
                        "
        %s %s %s
        <br/>%s %s
        <br/> -%s
        <br/> -%s
        <br/> -%s
        <br/> -%s
        <br/>%s
        <br/>%s: %s",
                        $this->module->l('Dear customer, your service', 'appcustomerws'),
                        $supportExpired,
                        $date->format('d/m/Y'),
                        sprintf(
                            $this->module->l('Therefore, please subscribe %s here %s', 'appcustomerws'),
                            $preRequestAnchor,
                            $afterRequestAnchor
                        ),
                        $this->module->l('one of the subscription plans to continue:', 'appcustomerws'),
                        $this->module->l('Receive continuous updates of the Mr Shop plugin', 'appcustomerws'),
                        $this->module->l('Get technical support from our team', 'appcustomerws'),
                        $this->module->l('Get technical maintenance and bugfixing for your Mobile App', 'appcustomerws'),
                        $this->module->l('Keep your mobile app on stores', 'appcustomerws'),
                        $this->module->l('If you don’t sign up for a new subscription by the date mentioned above, the Mr Shop module will be disabled and your mobile app will be removed from the Apple App Store and Google Play Store.', 'appcustomerws'),
                        $this->module->l('Choose now your subscription', 'appcustomerws'),
                        sprintf(
                            $this->module->l('%s Subscribe Now %s', 'appcustomerws'),
                            $preRequestAnchor,
                            $afterRequestAnchor
                        )
                    );

                    $response['priorityMessage'] = $supportEndMessage;
                }
            }

            ResponseHandler::success($response);
        } catch (\Exception $ex) {
            if ($ex->getCode() == 404) {
                ApiUtils::setAppRequested(false);
            }
            ResponseHandler::response($ex->getCode(), $ex->getMessage());
        }
    }

    public function resetModule()
    {
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("DELETE FROM "._DB_PREFIX_."configuration WHERE name LIKE 'MRSHOP_%' AND name NOT LIKE 'MRSHOP_SECRET%'");
        ResponseHandler::success();
    }
}
