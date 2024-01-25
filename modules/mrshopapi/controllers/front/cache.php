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

use MrAPPs\MrShopApi\Handler\Api\CacheHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

class mrshopapicacheModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        try {
            $this->module->cleanupCache();
            ResponseHandler::success(['success' => true]);
        } catch (\Exception $e) {
            ResponseHandler::badRequest($e->getMessage());
        }
    }
}
