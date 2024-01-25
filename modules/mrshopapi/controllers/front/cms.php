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

use MrAPPs\MrShopApi\Api\Front\CmsWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php';

class mrshopapicmsModuleFrontController extends mrshopapibaseModuleFrontController
{
    public function initContent()
    {
        $this->ajax = false;

        $id = (int) Tools::getValue('id');
        if ($id == 0) {
            throw new \Exception('CMS page not found');
        }

        parent::initContent();
        $this->setMedia();
        $this->initHeader();
        $this->initFooter();

        if ($this->dataHandler->isModuleActive('mrshopfrontend')) {
            $path = 'modules/mrshopapi/views';
            $assetPath = ApiUtils::getFullUrl($path);
            $this->context->controller->addCSS($assetPath.'/css/cms-front.css');
        }

        $_GET['content_only'] = true;
        $this->context->smarty->assign([
            'layout' => $this->getLayout(),
            'stylesheets' => $this->getStylesheets(),
            'javascript' => $this->getJavascript(),
            'js_custom_vars' => Media::getJsDef(),
            'notifications' => $this->prepareNotifications(),
        ]);

        $this->assignGeneralPurposeVariables();
        $this->process();

        $this->context->smarty->assign([
            'HOOK_HEADER' => Hook::exec('displayHeader'),
        ]);

        if ($this->dataHandler->isModuleActive('mrshopfrontend')) {
            $this->context->smarty->assign([
                'mrShopSearchBar' => [
                    'searchBar' => ''
                ]
            ]);
        }

        $cmsWs = new CmsWS($this->dataHandler);
        $data = $cmsWs->prepareDetailData($id);

        ResponseHandler::responseHtml($data && isset($data['content']) ? $data['content'] : '');
    }
}
