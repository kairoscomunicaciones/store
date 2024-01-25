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

namespace MrAPPs\MrShopApi\Service;

use AppHome;
use AppHomeItem;
use Configuration;
use Context;
use Db;
use Language;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;
use Validate;

class ImageService extends ImageUtils
{
    /*
        - null if module folder
        - string if subfolder of image module
    */
    private $image_types;

    public function __construct()
    {
        parent::__construct();
        $this->image_types = [
            AppHome::$TYPE_BANNER => 'banners',
            AppHome::$TYPE_CAROUSEL => 'carousel',
            ImageUtils::DARK_LOGO => null,
            ImageUtils::DARK_SPLASH => null
        ];
    }

    public function getImagePath($hash, $type = '', $thumbnail = null)
    {
        // Default original
        $thumbnail = $this->getThumbnail($thumbnail);
        if (false == isset($this->image_types[$type])) {
            $filename = Configuration::get($type);
            $stripped = ApiUtils::stripLeadingSlash($filename);

            return $this->getModuleImageFolder().'/'.$stripped;
        }

        return $this->generateFileName($hash, $type, $thumbnail);
    }

    public function getImageUrl($hash, $type, $thumbnail = null)
    {
        // Default original
        if (isset($hash)) {
            $thumbnail = $this->getThumbnail($thumbnail);
            $filename = $this->getFileName($hash, $type, $thumbnail);
            $shopUrl = ApiUtils::getFullDomain();

            return [
                'name' => $filename,
                'url' => Tools::substr($shopUrl, 0, Tools::strlen($shopUrl) - 1).'/img/modules/'.ApiUtils::MODULE_NAME.'/'.$filename
            ];
        }

        return null;
    }

    public function imageTypeExists($hash, $type)
    {
        $originalPath = $this->getImagePath($hash, $type);

        return file_exists($originalPath);
    }

    public function isImageTypeSupported($type = null)
    {
        if (false == isset($type)) {
            return false;
        }

        return in_array($type, $this->image_types);
    }

    public function getSupportedTypeImages()
    {
        return $this->image_types;
    }

    public function getThumbnail($thumbnail = null)
    {
        // Default original
        if (!$thumbnail || !in_array($thumbnail, ImageUtils::getValidBannerImageThumbnails())) {
            $thumbnail = self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_ORIGINAL'];
        }

        return $thumbnail;
    }

    public function generateFileName($hash, $type, $thumbnail)
    {
        return $this->getModuleImageFolder().'/'.$this->image_types[$type].'/'.$hash.'_'.$thumbnail.'.jpg';
    }

    public function getFileName($hash, $type, $thumbnail)
    {
        return $this->image_types[$type].'/'.$hash.'_'.$thumbnail.'.jpg';
    }

    public function getModuleImageFolder()
    {
        return _PS_IMG_DIR_.'modules/'.ApiUtils::MODULE_NAME;
    }

    public function getPsUploadPath()
    {
        return _PS_UPLOAD_DIR_;
    }

    public function getPsUploadUrl()
    {
        $shopUrl = ApiUtils::getFullDomain();

        return $shopUrl.'upload';
    }

    public function getCustomizationUrl($name)
    {
        $shopUrl = ApiUtils::getFullDomain();

        return $shopUrl.'client_mrshop/customizations?name='.$name;
    }

    public function getImagesByLang($id_app_home, $type, $id_lang = null)
    {
        // if (false == isset($id_lang)) {
        //     $id_lang = Context::getContext()->language->id;
        // }

        $fullLangs = (bool) (false == isset($id_lang));
        $query = "";

        switch ($type) {
            case AppHome::$TYPE_BANNER:
                $query = "SELECT
                    "._DB_PREFIX_."app_home.hash,
                    "._DB_PREFIX_."app_home_lang.id_lang,
                    "._DB_PREFIX_."lang.iso_code,
                    "._DB_PREFIX_."app_home_lang.hash as 'lang_hash'
                FROM "._DB_PREFIX_."app_home LEFT JOIN "._DB_PREFIX_."app_home_lang
                ON "._DB_PREFIX_."app_home.id_app_home = "._DB_PREFIX_."app_home_lang.id_app_home LEFT JOIN "._DB_PREFIX_."lang
                ON "._DB_PREFIX_."app_home_lang.id_lang = "._DB_PREFIX_."lang.id_lang
                WHERE "._DB_PREFIX_."app_home.id_app_home = ".(int) $id_app_home." AND "._DB_PREFIX_."lang.active = 1";

                break;
            case AppHome::$TYPE_CAROUSEL:
                $query = "SELECT
                    "._DB_PREFIX_."app_home_item.hash,
                    "._DB_PREFIX_."app_home_item_lang.id_lang,
                    "._DB_PREFIX_."lang.iso_code,
                    "._DB_PREFIX_."app_home_item_lang.lang_hash
                FROM "._DB_PREFIX_."app_home_item LEFT JOIN "._DB_PREFIX_."app_home_item_lang
                ON "._DB_PREFIX_."app_home_item.id_app_home_item = "._DB_PREFIX_."app_home_item_lang.id_app_home_item LEFT JOIN "._DB_PREFIX_."lang
                ON "._DB_PREFIX_."app_home_item_lang.id_lang = "._DB_PREFIX_."lang.id_lang
                WHERE "._DB_PREFIX_."app_home_item.id_app_home_item = ".(int) $id_app_home." AND "._DB_PREFIX_."lang.active = 1";

                break;
        }

        if (!$fullLangs) {
            switch ($type) {
                case AppHome::$TYPE_BANNER:
                    $query .= " AND "._DB_PREFIX_."app_home_lang.id_lang = ".(int) $id_lang;

                    break;
                case AppHome::$TYPE_CAROUSEL:
                    $query .= " AND "._DB_PREFIX_."app_home_item_lang.id_lang = ".(int) $id_lang;

                    break;
            }
        }

        $res = Db::getInstance()->executeS($query);
        if (isset($res) && count($res) > 0) {
            $output = [];
            foreach ($res as $image) {
                if ($this->isImageMultiLang($image)) {
                    $output[$image['iso_code']] = $this->getImageUrl($image['lang_hash'], $type);
                } else {
                    $output = [
                        'original' => $this->getImageUrl($res[0]['hash'], $type)
                    ];
                }
            }

            return $output;
        }

        return null;
    }

    public function getImageHashByLang($type, $id_app_home, $id_lang)
    {
        $query = "";
        switch ($type) {
            case AppHome::$TYPE_BANNER:
                $query = "SELECT
                    "._DB_PREFIX_."app_home.hash,
                    "._DB_PREFIX_."app_home_lang.id_lang,
                    "._DB_PREFIX_."lang.iso_code,
                    "._DB_PREFIX_."app_home_lang.hash as 'lang_hash'
                FROM "._DB_PREFIX_."app_home LEFT JOIN "._DB_PREFIX_."app_home_lang
                ON "._DB_PREFIX_."app_home.id_app_home = "._DB_PREFIX_."app_home_lang.id_app_home LEFT JOIN "._DB_PREFIX_."lang
                ON "._DB_PREFIX_."app_home_lang.id_lang = "._DB_PREFIX_."lang.id_lang
                WHERE "._DB_PREFIX_."app_home.id_app_home = ".$id_app_home." AND "._DB_PREFIX_."app_home_lang.id_lang = ".(int) $id_lang;

                break;
            case AppHome::$TYPE_CAROUSEL:
                $query = "SELECT
                    "._DB_PREFIX_."app_home_item.hash,
                    "._DB_PREFIX_."app_home_item_lang.id_lang,
                    "._DB_PREFIX_."lang.iso_code,
                    "._DB_PREFIX_."app_home_item_lang.lang_hash
                FROM "._DB_PREFIX_."app_home_item LEFT JOIN "._DB_PREFIX_."app_home_item_lang
                ON "._DB_PREFIX_."app_home_item.id_app_home_item = "._DB_PREFIX_."app_home_item_lang.id_app_home_item LEFT JOIN "._DB_PREFIX_."lang
                ON "._DB_PREFIX_."app_home_item_lang.id_lang = "._DB_PREFIX_."lang.id_lang
                WHERE "._DB_PREFIX_."app_home_item.id_app_home_item = ".(int) $id_app_home." AND "._DB_PREFIX_."app_home_item_lang.id_lang = ".(int) $id_lang;

                break;
        }

        $res = Db::getInstance()->executeS($query);
        if (isset($res) && count($res) > 0) {
            if (self::isImageMultiLang($res[0])) {
                return $res[0]['lang_hash'];
            } else {
                return $res[0]['hash'];
            }
        }

        return '';
    }

    public function isImageMultiLang($image)
    {
        if (false == isset($image)) {
            return false;
        }

        if (isset($image['hash']) && (false == isset($image['lang_hash']) || empty($image['lang_hash']))) {
            return false;
        } else {
            return true;
        }
    }

    public function fileExists($image, $has_multi_lang = null)
    {
        if (!isset($has_multi_lang)) {
            $has_multi_lang = false;
        }

        $fileExists = false;
        if ($has_multi_lang) {
            foreach ($image as $iso => $v) {
                if (Validate::isLangIsoCode($iso)) {
                    $fileExists = parent::tmpFileExists($v['name']);
                    // Stop if not exists
                    if (!$fileExists) {
                        break;
                    }
                }
            }
        } else {
            $fileExists = parent::tmpFileExists($image['original']['name']);
        }

        return $fileExists;
    }

    public function getTmpFileImagePaths($image, $has_multi_lang = null)
    {
        if (!isset($has_multi_lang)) {
            $has_multi_lang = false;
        }

        $paths = [];
        if ($has_multi_lang) {
            foreach ($image as $iso => $v) {
                if (Validate::isLangIsoCode($iso)) {
                    $path = parent::tmpImagePath($v['name']);
                    $paths[$iso] = $path;
                }
            }
        } else {
            $paths['original'] = parent::tmpImagePath($image['original']['name']);
        }

        return $paths;
    }

    public function copyMultiLangImage($type, $image, $id = 0, $has_multi_lang = null)
    {
        if (!isset($has_multi_lang)) {
            $has_multi_lang = false;
        }

        $paths = $this->getTmpFileImagePaths($image, $has_multi_lang);

        // Save
        if ($has_multi_lang) {
            foreach ($paths as $iso => $v) {
                if (Validate::isLangIsoCode($iso)) {
                    $copied = $this->setMultiLangHash($type, $id, $v, $iso);
                    if ($copied) {
                        unlink($v);
                    }
                }
            }
        } else {
            $copied = $this->setMultiLangHash($type, $id, $paths['original']);
            if ($copied) {
                unlink($paths['original']);
            }
        }
    }

    public function setMultiLangHash($type, $id, $tmpPath, $iso = null)
    {
        if (!file_exists($tmpPath)) {
            return false;
        }

        Installer::createImgModulesDirectory();

        $hash = md5_file($tmpPath);
        $path = $this->getImagePath($hash, $type);

        // Copy if not exists
        if (!file_exists($path)) {
            $copied = copy($tmpPath, $path);
        } else {
            $copied = true;
        }

        if ($copied) {
            switch ($type) {
                case AppHome::$TYPE_BANNER:
                    return AppHome::updateImageLang($id, $hash, $iso);
                case AppHome::$TYPE_CAROUSEL:
                    return AppHomeItem::updateImageLang($id, $hash, $iso);
            }
        }

        return $copied;
    }

    public function hasOriginalImage($banner_id)
    {
        if (!isset($banner_id)) {
            return null;
        }

        $query = 'SELECT '._DB_PREFIX_.'app_home.hash
        FROM '._DB_PREFIX_.'app_home
        WHERE id_app_home = '.(int) $banner_id;
        $res = Db::getInstance()->executeS($query);
        if (!$res && !isset($res[0]['hash'])) {
            return null;
        } else {
            return $res[0]['hash'];
        }
    }
}
