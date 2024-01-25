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

use MrAPPs\MrShopApi\Api\Routes;
use MrAPPs\MrShopApi\Utils\ApiUtils;

require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/base.php';

class mrshopapiwebserviceModuleFrontController extends mrshopapibaseModuleFrontController
{
    public function initContent()
    {
        $resource = Tools::getValue('entity');
        $id = Tools::getValue('id', null);

        if ($id) {
            $possibleResource = $resource.'/'.$id;
            if (Routes::contains($possibleResource)) {
                $resource = $possibleResource;
            } else {
                $this->requestHandler->setId($id);
            }
        }

        $this->setupContext($resource);
        $this->requestHandler->setResource($resource);
        $this->requestHandler->setMethod($_SERVER['REQUEST_METHOD']);
        $this->requestHandler->setGETParams($_GET);
        
        if ($resource == 'content_management_system' && $id != null) {
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
        }

        $this->requestHandler->loadData();
    }
}
