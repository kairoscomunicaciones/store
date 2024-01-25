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

use \MrAPPs\MrShopApi\Api\Contracts\WebservicePostInterface as UpsertItem;
use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface as GetList;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrShopPaymentModule;

class PaymentModulesWS extends BaseWS implements GetList, UpsertItem
{
    public function getList($params, $employeeId)
    {
        $paymentHandler = new PaymentHandler(new DataHandler(true));

        $modules = $paymentHandler->getModuleList();
        MrShopPaymentModule::syncModules($modules);

        $paymentModules = MrShopPaymentModule::find();
        foreach ($paymentModules as $paymentModule) {
            foreach ($modules as &$module) {
                if ($module['moduleName'] == $paymentModule['name']) {
                    $module['use_in_app'] = (bool) $paymentModule['use_in_app'];
                    $module['version'] = $paymentModule['version'];
                    $module['id_payment_module'] = $paymentModule['id_payment_module'];
                }
            }
        }

        $this->response(true, null, $modules);
    }

    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        $paymentModule = new MrShopPaymentModule((int) $id);
        if ($paymentModule->id != null) {
            $paymentModule->use_in_app = !(bool) $paymentModule->use_in_app;
            $paymentModule->save();
        }

        $this->getList([], $userId);
    }
}
