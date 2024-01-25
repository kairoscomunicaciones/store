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
require_once(_PS_MODULE_DIR_.'mrshopapi/classes/AppHome.php');

use MrAPPs\MrShopApi\Utils\ImageUtils;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Remove unused thumbnails and regenerate with the new name with hash
 * @param $module
 * @throws PrestaShopDatabaseException
 */
function bannersMigration($module)
{
    $imageUtils = new ImageUtils();
    $folder = _PS_IMG_DIR_.'modules/'.$module->name.'/banners/';

    /* Banners to add hash */
    $bannersS = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
        SELECT `id_app_home` FROM `'._DB_PREFIX_.'app_home`
        WHERE `type` = "'.pSQL(AppHome::$TYPE_BANNER).'"
    ');

    $banners = [];
    foreach ($bannersS as $banner) {
        $banners[] = (int) $banner['id_app_home'];
    }

    $bannersFolders = glob($folder.'*', GLOB_ONLYDIR);
    foreach ($bannersFolders as $bannerFolder) {
        $banner = (int) basename($bannerFolder);
        if (in_array($banner, $banners)) {
            /** The original file */
            $file = glob($folder.$banner.'/'.$banner.'-banner_image_original.*');
            if ($file && count($file) > 0) {
                $file = $file[0];
                /* Generate hash */
                $hash = md5_file($file);
                if ($hash && $imageUtils->setBannerHash($banner, $hash)) {
                    $pathInfo = pathinfo($file);
                    $name = explode('-', $pathInfo['filename']);
                    $newOriginal = $folder.$banner.'/'.$name[0].'-'.$hash.'-'.$name[1].'.'.$pathInfo['extension'];
                    if (!file_exists($newOriginal)) {
                        /* Save the new file with updated name (ID-hash-bannertype.ext) */
                        copy($file, $newOriginal);
                    }

                    /* Delete all thumbnails inside the banner folder except the new one */
                    $imageUtils->clearThumbnails($banner, [$newOriginal]);

                    /* Regenerate thumbnails */
                    // $imageUtils->generateThumbnails($banner);
                }
            }
        } else {
            /* Delete old banner folder */
            $imageUtils->clearThumbnails($banner, [], true);
        }
    }

    /* Delete old files inside the banners folder */
    $oldFiles = array_filter(glob($folder.'*'), 'is_file');
    if ($oldFiles) {
        foreach ($oldFiles as $oldFile) {
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
    }
}

function upgrade_module_1_4_0($module)
{
    $installer = new MrAPPs\MrShopApi\Service\Installer($module);

    $installer->createTables();
    $installer->deleteTabs();
    $installer->createTabs();
    $installer->alterTables('alter_1.4.0.sql', false);

    bannersMigration($module);

    return true;
}
