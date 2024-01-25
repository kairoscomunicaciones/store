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

use Configuration;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\AppHomeHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;

class AppHomeWS extends ProductBaseWS
{
    /** @var AppHomeHandler $handler */
    private $handler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheTime = 900;
        $this->handler = new AppHomeHandler($dataHandler);
    }

    public function getList($params, $id_customer = null, $echoResponse = true)
    {
        $showLogo = (bool) Configuration::get('MRSHOP_ENABLE_LOGO');
        if ($showLogo) {
            $shopUrl = ApiUtils::getFullDomain();
            $logo = (Configuration::get(ImageUtils::LOGO) ? Configuration::get(ImageUtils::LOGO) : '');
            $logoUrl = Tools::substr($shopUrl, 0, Tools::strlen($shopUrl) - 1).ImageUtils::getImageRelativePath($logo, true);

            if (Configuration::get(ImageUtils::DARK_LOGO)) {
                $darkLogo = Configuration::get(ImageUtils::DARK_LOGO);
                $darkLogoUrl = Tools::substr($shopUrl, 0, Tools::strlen($shopUrl) - 1).ImageUtils::getImageRelativePath($darkLogo, true);
            } else {
                $darkLogoUrl = $logoUrl;
            }
        } else {
            $logoUrl = null;
            $darkLogoUrl = null;
        }

        $sections = $this->handler->getSectionsWS($id_customer);

        $data = [
            'show_logo' => $showLogo,
            'logo_url' => $logoUrl,
            'logo_dark_url' => $darkLogoUrl,
            'sections' => $sections,
        ];

        $this->response(true, null, $data, $echoResponse);
    }

    public function customResponse(&$cachedResponse, $id_customer = null)
    {
        $sections = &$cachedResponse['sections'];

        $showWishlist = $id_customer !== null && $this->isModuleActive('mrshopblockwishlist');

        foreach ($sections as &$section) {
            if (isset($section['products']) && count($section['products']) > 0) {
                $products = &$section['products'];

                foreach ($products as &$product) {
                    if ($showWishlist) {
                        $product['wishlist'] = $this->isProductOnWishlist($product['id'], $id_customer);
                    } else {
                        unset($product['wishlist']);
                    }
                }
            }
        }
    }
}
