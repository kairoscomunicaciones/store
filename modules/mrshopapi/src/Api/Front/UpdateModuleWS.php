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
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;

class UpdateModuleWS extends BaseWS
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
    }

    public function updateAppData()
    {
        $params = $this->dataHandler->getBodyParams();

        $appTitle = isset($params['appTitle']) ? trim($params['appTitle']) : null;
        $appShortDescription = isset($params['appShortDescription']) ? trim($params['appShortDescription']) : null;
        $appDescription = isset($params['appDescription']) ? trim($params['appDescription']) : null;
        $appKeywords = isset($params['appKeywords']) ? trim($params['appKeywords']) : null;
        $primaryColor = isset($params['primaryColor']) ? trim($params['primaryColor']) : null;
        $secondaryColor = isset($params['secondaryColor']) ? trim($params['secondaryColor']) : null;
        $textColorOnPrimaryColor = isset($params['textColorOnPrimaryColor']) ? trim($params['textColorOnPrimaryColor']) : null;
        $textColorOnSecondaryColor = isset($params['textColorOnSecondaryColor']) ? trim($params['textColorOnSecondaryColor']) : null;
        $darkStatusBar = isset($params['darkStatusBar']) ? (bool) $params['darkStatusBar'] : null;
        $appleLoginEnabled = isset($params['socialLoginAppleEnabled']) ? (bool) $params['socialLoginAppleEnabled'] : null;
        $facebookLoginEnabled = isset($params['facebookLoginEnabled']) ? (bool) $params['facebookLoginEnabled'] : null;
        $googleLoginEnabled = isset($params['googleLoginEnabled']) ? (bool) $params['googleLoginEnabled'] : null;
        $facebookClientId = isset($params['facebookClientId']) ? trim($params['facebookClientId']) : null;
        $googleAndroidClientId = isset($params['googleAndroidClientId']) ? trim($params['googleAndroidClientId']) : null;
        $googleIosClientId = isset($params['googleIosClientId']) ? trim($params['googleIosClientId']) : null;
        $translations = isset($params['translations']) && is_array($params['translations']) ? $params['translations'] : [];

        $appleClientId = isset($params['appleClientId']) ? trim($params['appleClientId']) : null;
        $appleTeamId = isset($params['appleTeamId']) ? trim($params['appleTeamId']) : null;
        $appleKeyId = isset($params['appleKeyId']) ? trim($params['appleKeyId']) : null;
        $appleKeyFile = isset($params['appleKeyFile']) ? trim($params['appleKeyFile']) : null;

        if ($appTitle !== null) {
            Configuration::updateValue('MRSHOP_APP_NAME', $appTitle);
        }
        if ($primaryColor !== null) {
            Configuration::updateValue('MRSHOP_PRIMARY_COLOR', $primaryColor);
        }
        if ($secondaryColor !== null) {
            Configuration::updateValue('MRSHOP_SECONDARY_COLOR', $secondaryColor);
        }
        if ($textColorOnPrimaryColor !== null) {
            Configuration::updateValue('MRSHOP_TEXT_COLOR_ON_PRIMARY', $textColorOnPrimaryColor);
        }
        if ($textColorOnSecondaryColor !== null) {
            Configuration::updateValue('MRSHOP_TEXT_COLOR_ON_SECONDARY', $textColorOnSecondaryColor);
        }
        if ($darkStatusBar !== null) {
            Configuration::updateValue('MRSHOP_DARK_STATUSBAR', $darkStatusBar);
        }
        if ($appleLoginEnabled !== null) {
            Configuration::updateValue('MRSHOP_APPLE_ENABLED_APP', $appleLoginEnabled);
        }
        if ($facebookLoginEnabled !== null) {
            Configuration::updateValue('MRSHOP_FACEBOOK_ENABLED_APP', $facebookLoginEnabled);
        }
        if ($googleLoginEnabled !== null) {
            Configuration::updateValue('MRSHOP_GOOGLE_ENABLED_APP', $googleLoginEnabled);
        }
        if ($facebookClientId !== null) {
            Configuration::updateValue('MRSHOP_FACEBOOK_ID_APP', $facebookClientId);
        }
        if ($googleAndroidClientId !== null) {
            Configuration::updateValue('MRSHOP_GOOGLE_ID_APP', $googleAndroidClientId);
        }
        if ($googleIosClientId !== null) {
            Configuration::updateValue('MRSHOP_GOOGLE_IOS_ID_APP', $googleIosClientId);
        }
        if ($appleClientId !== null) {
            Configuration::updateValue('MRSHOPSOCIALLOGIN_APPLE_CLIENT_ID', $appleClientId);
        }
        if ($appleTeamId !== null) {
            Configuration::updateValue('MRSHOPSOCIALLOGIN_APPLE_TEAM_ID', $appleTeamId);
        }
        if ($appleKeyId !== null) {
            Configuration::updateValue('MRSHOPSOCIALLOGIN_APPLE_KEY_ID', $appleKeyId);
        }

        if ($appleKeyFile !== null) {
            // Apple key file .p8
            $dirPath = _PS_CONFIG_DIR_.'modules/mrshopsociallogin/';
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            $fileName = 'appleKeyFile.p8';
            $filePath = $dirPath.$fileName;
            file_put_contents($filePath, $appleKeyFile, LOCK_EX);
            Configuration::updateValue('MRSHOPSOCIALLOGIN_APPLE_KEY_FILE', $fileName);
        }

        if (count($translations) > 0) {
            $appDescription = [];
            $appShortDescription = [];
            $appKeywords = [];
            foreach ($translations as $langCode => $values) {
                $idLang = (int) Db::getInstance()->getValue('SELECT `id_lang` FROM `'._DB_PREFIX_.'lang` WHERE `iso_code` = \''.pSQL(Tools::strtolower($langCode)).'\'');

                if (Tools::strlen($values['appDescription']) > 0) {
                    $appDescription[$idLang] = $values['appDescription'];
                }
                if (Tools::strlen($values['appShortDescription']) > 0) {
                    $appShortDescription[$idLang] = $values['appShortDescription'];
                }
                if (Tools::strlen($values['appKeywords']) > 0) {
                    $appKeywords[$idLang] = $values['appKeywords'];
                }
            }

            if (count($appDescription) > 0) {
                Configuration::updateValue('MRSHOP_APP_DESCRIPTION', $appDescription);
            }

            if (count($appShortDescription) > 0) {
                Configuration::updateValue('MRSHOP_APP_SHORT_DESCRIPTION', $appShortDescription);
            }

            if (count($appKeywords) > 0) {
                Configuration::updateValue('MRSHOP_APP_KEYWORDS', $appKeywords);
            }
        } else {
            if ($appShortDescription !== null) {
                Configuration::updateValue('MRSHOP_APP_SHORT_DESCRIPTION', $appShortDescription);
            }
            if ($appDescription !== null) {
                Configuration::updateValue('MRSHOP_APP_DESCRIPTION', $appDescription);
            }
            if ($appKeywords !== null) {
                Configuration::updateValue('MRSHOP_APP_KEYWORDS', $appKeywords);
            }
        }

        $images = [
            [
                'name' => 'logo',
                'webPath' => isset($params['logoUrl']) ? trim($params['logoUrl']) : null,
                'configName' => ImageUtils::LOGO
            ],
            [
                'name' => 'darkLogo',
                'webPath' => isset($params['darkLogoUrl']) ? trim($params['darkLogoUrl']) : null,
                'configName' => ImageUtils::DARK_LOGO
            ],
            [
                'name' => 'splash',
                'webPath' => isset($params['splashUrl']) ? trim($params['splashUrl']) : null,
                'configName' => ImageUtils::SPLASH
            ],
            [
                'name' => 'darkSplash',
                'webPath' => isset($params['darkSplashUrl']) ? trim($params['darkSplashUrl']) : null,
                'configName' => ImageUtils::DARK_SPLASH
            ],
            [
                'name' => 'icon',
                'webPath' => isset($params['iconUrl']) ? trim($params['iconUrl']) : null,
                'configName' => ImageUtils::ICON
            ],
        ];

        $utils = new ImageUtils();
        $utils->createImageTmpDirectory();

        foreach ($images as $image) {
            $url = $image['webPath'];
            if (!empty($url)) {
                $fileData = Tools::file_get_contents($url);

                if ($fileData) {
                    $fileName = $image['name'];
                    $fullFileName = $fileName.'.jpg';
                    $path = $utils->tmpImagePath($fullFileName);
                    $utils->createImageTmpDirectory();
                    $utils->createBaseImageDirectory();
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    if (file_put_contents($path, $fileData)) {
                        if ($utils->saveTmpImage($fullFileName, $fileName)) {
                            Configuration::updateValue($image['configName'], $fullFileName);
                        }
                    }
                }
            }
        }

        ResponseHandler::success();
    }

    public function updateDynamicLinks()
    {
        $params = $this->dataHandler->getBodyParams();
        $appleAppSiteAssociation = isset($params['appleAppSiteAssociation']) ? trim($params['appleAppSiteAssociation']) : '';
        $androidAssetLinksJson = isset($params['androidAssetLinksJson']) ? trim($params['androidAssetLinksJson']) : '';

        try {
            // Apple apple-app-site-association
            if (Tools::strlen($appleAppSiteAssociation) > 0) {
                $filePath = _PS_ROOT_DIR_.'/apple-app-site-association';
                file_put_contents($filePath, $appleAppSiteAssociation, LOCK_EX);
            }

            // Android assetlinks.json
            if (Tools::strlen($androidAssetLinksJson) > 0) {
                $dirPath = _PS_ROOT_DIR_.'/.well-known';
                if (!file_exists($dirPath)) {
                    mkdir($dirPath);
                }
                $filePath = $dirPath.'/assetlinks.json';
                file_put_contents($filePath, $androidAssetLinksJson, LOCK_EX);
            }

            ResponseHandler::success();
        } catch (\Exception $ex) {
            ResponseHandler::badRequest($ex->getMessage());
        }
    }

    public function updateActiveModules()
    {
        $modules = $this->dataHandler->getBodyParams();
        ApiUtils::updateModulesList($modules);
        ApiUtils::setAppRequested();

        ResponseHandler::success($modules);
    }
}
