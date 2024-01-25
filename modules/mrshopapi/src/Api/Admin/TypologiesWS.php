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
 * @copyright Mr. APPs 2021
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Api\Admin;

use MrAPPs\MrShopApi\Api\Contracts\WebserviceGetListInterface;
use MrAPPs\MrShopApi\Service\TranslationsService;

class TypologiesWS extends BaseWS implements WebserviceGetListInterface
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
        $c = [
            'section_types'            => $this->translationsService->getSectionsType(),
            'section_products_layouts' => $this->translationsService->getSectionProductsLayouts(),
            'carousel_types'           => $this->translationsService->getCarouselsType(),
            'banner_types'             => $this->translationsService->getBannerTypes(),
            'banner_sizes'             => $this->translationsService->getBannerSizes(),
            'section_products_sorting' => $this->translationsService->getSectionProductsSortings(),
            'catalog_layouts'          => $this->translationsService->getCatalogueLayoutTypes(),
            'app_navigation_types'     => $this->translationsService->getAppNavigationTypes(),
            'product_row_layout'       => $this->translationsService->getProductRowLayout(),
            'app_font_types'           => $this->translationsService->getAppFonts(),
            'products_shape_types'     => $this->translationsService->getProductsShape(),
            'scheduled_notification_types' => $this->translationsService->getNotificationTypes(),
            'webhook_notification_types' => $this->translationsService->getOrderStatusTypes(),
            'customer_groups' => $this->translationsService->getCustomerGroups(),
            'cms_page_types' => $this->translationsService->getCmsPageTypes()
        ];
        
        $retval = [];
        
        foreach ($c as $set => $options) {
            $retval[$set] = [];
            
            foreach ($options as $value => $text) {
                $retval[$set][] = compact('value', 'text');
            }
        }
        
        $this->response(true, null, $retval);
    }
}
