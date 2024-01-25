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

use MrAPPs\MrShopApi\Handler\Api\ApiRequestHandler;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class mrshopapibaseModuleFrontController extends ModuleFrontController
{
    /** @var \ApiRequestHandler */
    protected $requestHandler;

    protected $dataHandler;

    protected function disableColumns()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        $this->display_footer = false;
        $this->display_header = false;
    }

    public function __construct()
    {
        parent::__construct();

        $this->ajax = true;

        $this->dataHandler = new DataHandler();
        $this->requestHandler = new ApiRequestHandler($this->dataHandler);

        $this->init();
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->container = $this->buildContainer();
        }
    }

    protected function setupContext($resource = '')
    {
        $this->requestHandler->setupContext($resource);
    }
    
    protected function setTemplateRelativePath($relativeTplPath)
    {
        $path = $this->getTemplateFullPath($relativeTplPath);
        $this->setTemplate($path);
    }
    
    protected function getTemplateFullPath($relativeTplPath = null)
    {
        return ApiUtils::getTemplateFullPath($relativeTplPath);
    }
}
