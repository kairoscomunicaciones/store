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

use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;

class ScratchToWinWS extends BaseWS
{
    /**@var \MrAPPs\MrShopScratchToWin\Services\ScratchToWinManager $scratchToWinManager*/
    private $scratchToWinManager = null;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->cacheEnabled = false;
        if (Module::isEnabled('mrshopscratchtowin')) {
            require_once _PS_MODULE_DIR_.'mrshopscratchtowin/src/Services/ScratchToWinManager.php';
            $this->scratchToWinManager = new \MrAPPs\MrShopScratchToWin\Services\ScratchToWinManager(Module::getInstanceByName('mrshopscratchtowin'));
        } else {
            ResponseHandler::methodNotAllowed('Scratch To Win not enabled');
        }
    }

    private function sendResponse($data)
    {
        if (!$data) {
            ResponseHandler::error($this->module->l('There was an error while executing operation', 'scratchtowinws'));
        } else {
            ResponseHandler::success($data);
        }
    }

    public function initScratchToWin($customerId)
    {
        $data = $this->scratchToWinManager->initScratchToWin($this->context->language->id);
        $this->sendResponse($data);
    }

    public function scratchToWinCompleted($customerId)
    {
        $data = $this->scratchToWinManager->scratchToWinCompleted();
        $this->sendResponse($data);
    }
}
