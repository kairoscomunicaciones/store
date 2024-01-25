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
use Language;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;

class RetrieveAppDataWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getList($params)
    {
        $settings = ApiUtils::getSettings();
        $socialLogin = ApiUtils::getSocialLogin();

        $data = [
            'mrshopVersion' => trim($this->module->version),
            'prestashopVersion' => trim(_PS_VERSION_),
            'email' => $settings['email'],
            'appLang' => trim(Configuration::get('MRSHOP_APP_LANG', null, null, null, '')),
            'appTitle' => trim(Configuration::get('MRSHOP_APP_NAME', null, null, null, '')),
            'appDescription' => [],
            'appShortDescription' => [],
            'appKeywords' => [],
            'logoUrl' => trim(Configuration::get(ImageUtils::LOGO, null, null, null, '')),
            'darkLogoUrl' => trim(Configuration::get(ImageUtils::DARK_LOGO, null, null, null, '')),
            'splashUrl' => trim(Configuration::get(ImageUtils::SPLASH, null, null, null, '')),
            'darkSplashUrl' => trim(Configuration::get(ImageUtils::DARK_SPLASH, null, null, null, '')),
            'iconUrl' => trim(Configuration::get(ImageUtils::ICON, null, null, null, '')),
            'primaryColor' => $settings['theme_primary_color'],
            'secondaryColor' => $settings['theme_secondary_color'],
            'textColorOnPrimaryColor' => $settings['theme_text_color_on_primary'],
            'textColorOnSecondaryColor' => $settings['theme_text_color_on_secondary'],
            'darkSecondaryColor' => $settings['theme_dark_secondary_color'],
            'darkTextColorOnSecondaryColor' => $settings['theme_dark_text_color_on_secondary'],
            'darkStatusBar' => $settings['theme_dark_statusbar'],
            'pageSpecialOffers' => false,
            'pageNewProducts' => false,
            'socialLogin' => $socialLogin,
        ];

        foreach (Language::getLanguages(true) as $lang) {
            $idLang = (int) $lang['id_lang'];
            $appDescription = trim(Configuration::get('MRSHOP_APP_DESCRIPTION', $idLang));
            $appShortDescription = trim(Configuration::get('MRSHOP_APP_SHORT_DESCRIPTION', $idLang));
            $appKeywords = trim(Configuration::get('MRSHOP_APP_KEYWORDS', $idLang));

            if (!empty($appDescription) && !empty($appShortDescription) && !empty($appKeywords)) {
                $isoCode = $lang['iso_code'];
                $data['appDescription'][$isoCode] = $appDescription;
                $data['appShortDescription'][$isoCode] = $appShortDescription;
                $data['appKeywords'][$isoCode] = $appKeywords;
            }
        }

        $this->response(true, null, $data);
    }
}
