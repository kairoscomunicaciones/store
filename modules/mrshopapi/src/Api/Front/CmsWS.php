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

use Context;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class CmsWS extends BaseWS
{
    private function getBaseSql()
    {
        return 'SELECT c.`id_cms` AS id, c.`position`, cl.`meta_title`, cl.`content`,  COALESCE (mcms.`simple_cms`,0)
			FROM `'._DB_PREFIX_.'cms` c
			LEFT JOIN `'._DB_PREFIX_.'mrshop_cms_page` mcms
			ON (c.`id_cms` = mcms.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
            ON (c.`id_cms` = cl.`id_cms` AND cs.`id_shop` = cl.`id_shop`)
            WHERE cs.`id_shop` = '.(int) Context::getContext()->shop->id.'
            AND cl.`id_lang` = '.(int) Context::getContext()->language->id;
    }

    private function renderContent($cmsPage)
    {
        if (!$cmsPage['simple_cms'] && $cmsPage !== false) {
            $path = ApiUtils::getTemplateFullPath('web_cms_page.tpl');
        } else {
            $path = ApiUtils::getTemplateFullPath('simple_web_cms_page.tpl');
        }

        $assetPath = ApiUtils::getFullUrl('modules/mrshopapi/views');
        $params = [
            'style' => $assetPath.'/css/front.css',
            'templatePath' => ApiUtils::getTemplateFullPath(),
            'cms' => $cmsPage
        ];

        $this->context->smarty->assign($params);

        return $this->context->smarty->fetch($path, null, null);
    }

    public function getList($params)
    {
        $query = $this->getBaseSql()
            .' ORDER BY c.`position` ASC';

        $result = $this->refactorData($query);

        $this->response(true, null, $result);
    }

    public function prepareDetailData($id)
    {
        $data = $this->refactorData($this->getBaseSql().' AND c.id_cms = '.(int) $id, false);
        $data['content'] = $this->renderContent($data);
        $data['url'] = ApiUtils::getFullUrl('index.php?fc=module&module=mrshopapi&controller=cms&id='.$id);

        return $data;
    }

    public function getDetail($id)
    {
        $data = $this->prepareDetailData($id);

        if ($data !== false) {
            $this->response(true, null, $data);
        } else {
            ResponseHandler::notFound($this->module->l('Page not found', 'cmsws'));
        }
    }
}
