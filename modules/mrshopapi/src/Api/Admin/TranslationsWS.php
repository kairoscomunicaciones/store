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

use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface;
use MrAPPs\MrShopApi\Service\TranslationsService;

class TranslationsWS extends BaseWS implements WebserviceGetListInterface
{
    /** @var TranslationsService */
    private $translationsService;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->translationsService = new TranslationsService($this->module);
    }

    public function getList($params, $employeeId)
    {
        $retval = $this->translationsService->getJson();

        $this->response(true, null, $retval);
    }
}
