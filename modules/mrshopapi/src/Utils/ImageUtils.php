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

namespace MrAPPs\MrShopApi\Utils;

use Context;
use Db;
use ImageManager;
use Tools;

class ImageUtils
{
    const ICON   = 'MRSHOP_ICON';

    const SPLASH = 'MRSHOP_SPLASH';

    const LOGO   = 'MRSHOP_LOGO';

    const DARK_SPLASH = 'MRSHOP_DARK_SPLASH';

    const DARK_LOGO   = 'MRSHOP_DARK_LOGO';

    const GLOBAL_IMAGES = [self::ICON, self::SPLASH, self::LOGO, self::DARK_SPLASH, self::DARK_LOGO];
    
    private $module;

    /**
     * ImageUtils constructor.
     */
    public function __construct()
    {
        $this->module = ApiUtils::getModule();
    }

    /**
     * Thumbnails
     *
     * @var array
     */
    public static $THUMBNAILS = [
        'BANNER_IMAGE_THUMBNAIL_ORIGINAL' => 'original',
        'BANNER_IMAGE_THUMBNAIL_APP' => 'app',
        'BANNER_IMAGE_THUMBNAIL_LIST' => 'list',
        'BANNER_IMAGE_THUMBNAIL_DETAIL' => 'detail',
    ];

    /**
     * Generate banner thumbnails
     *
     * @deprecated
     *
     * @param $id
     */
    public function generateThumbnails($id)
    {
        foreach (self::$THUMBNAILS as $key => $thumbnail) {
            $this->generateThumbnail($id, $thumbnail);
        }
    }

    /**
     * Generate banner specific thumbnail
     *
     * @param $id
     * @param $thumbnail
     *
     *
     * @return bool
     */
    public function generateThumbnail($id, $thumbnail)
    {
        $originalPath = $this->getBannerImagePath($id);

        if (!in_array($thumbnail, self::getValidBannerImageThumbnails()) ||
            Tools::strlen($originalPath) == 0 ||
            !file_exists($originalPath)) {
            return false;
        }

        $src_info = getimagesize($originalPath);

        $width = $src_info[0];
        $height = $src_info[1];

        $outputDimensions = $this->getThumbnailDimensions($thumbnail);
        if (!$outputDimensions) {
            return false;
        }

        // No need to elaborate the input image if it's already correct in terms of file type and dimensions
        if ($src_info[2] == IMAGETYPE_JPEG && $width == $outputDimensions['width'] && $height == $outputDimensions['height']) {
            $thumbnailPath = $this->getBannerImagePath($id, $thumbnail);

            return copy($originalPath, $thumbnailPath);
        }

        $newWidth = null;
        $newHeight = $width / $outputDimensions['width'] * $outputDimensions['height'];
        if ($newHeight > $height) {
            $newHeight = null;
            $newWidth = $height / $outputDimensions['height'] * $outputDimensions['width'];
        }

        if ($newWidth) {
            $newHeight = $height;
            $x = floor(($width - $newWidth) / 2);
            $y = 0;
        } else {
            $newWidth = $width;
            $x = 0;
            $y = floor(($height - $newHeight) / 2);
        }

        $cutCacheName = sprintf('%s_banner_image_%s_%s_%s_%s_%s.jpg', $this->module->name, $id, $thumbnail, 'cut', time(), mt_rand(1, 99999));

        // CUT
        $cutTmpPath = _PS_TMP_IMG_DIR_.$cutCacheName;
        ImageManager::cut($originalPath, $cutTmpPath, $newWidth, $newHeight, 'png', $x, $y);

        // RESIZE
        $resizeCacheName = sprintf('%s_banner_image_%s_%s_%s_%s.jpg', $this->module->name, $id, $thumbnail, time(), mt_rand(1, 99999));
        $resizeTmpPath = _PS_TMP_IMG_DIR_.$resizeCacheName;
        $this->resize($cutTmpPath, $resizeTmpPath, $outputDimensions['width'], $outputDimensions['height'], 'jpg');
        @unlink($cutTmpPath);

        if (!file_exists($resizeTmpPath)) {
            return false;
        }
        $thumbnailPath = $this->getBannerImagePath($id, $thumbnail);
        $result = copy($resizeTmpPath, $thumbnailPath);
        @unlink($resizeTmpPath);

        // Deleting Prestashop 1.7 list thumbnail cache
        if ($thumbnail == self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_LIST']) {
            $ps17ListThumb = sprintf('%s%s_mini_%s_%s.jpg', _PS_TMP_IMG_DIR_, 'app_home', $id, Context::getContext()->shop->id);
            if (file_exists($ps17ListThumb)) {
                @unlink($ps17ListThumb);
            }
        }

        return $result;
    }

    /**
     * Resize thumbnail
     *
     * @param $src_file
     * @param $dst_file
     * @param null $dst_width
     * @param null $dst_height
     * @param string $file_type
     *
     * @return bool
     */
    public function resize($src_file, $dst_file, $dst_width = null, $dst_height = null, $file_type = 'jpg')
    {
        clearstatcache(true, $src_file);

        if (!file_exists($src_file) || !filesize($src_file)) {
            return false;
        }

        list($tmp_width, $tmp_height, $type) = getimagesize($src_file);
        $rotate = 0;
        if (function_exists('exif_read_data') && function_exists('mb_strtolower')) {
            $exif = @exif_read_data($src_file);

            if ($exif && isset($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $src_width = $tmp_width;
                        $src_height = $tmp_height;
                        $rotate = 180;

                        break;

                    case 6:
                        $src_width = $tmp_height;
                        $src_height = $tmp_width;
                        $rotate = -90;

                        break;

                    case 8:
                        $src_width = $tmp_height;
                        $src_height = $tmp_width;
                        $rotate = 90;

                        break;

                    default:
                        $src_width = $tmp_width;
                        $src_height = $tmp_height;
                }
            } else {
                $src_width = $tmp_width;
                $src_height = $tmp_height;
            }
        } else {
            $src_width = $tmp_width;
            $src_height = $tmp_height;
        }

        if (!$src_width) {
            return false;
        }
        if (!$dst_width) {
            $dst_width = $src_width;
        }
        if (!$dst_height) {
            $dst_height = $src_height;
        }

        if (!ImageManager::checkImageMemoryLimit($src_file)) {
            return false;
        }

        $dest_image = imagecreatetruecolor($dst_width, $dst_height);

        // If image is a PNG and the output is PNG, fill with transparency. Else fill with white background.
        if ($file_type == 'png' && $type == IMAGETYPE_PNG) {
            imagealphablending($dest_image, false);
            imagesavealpha($dest_image, true);
            $transparent = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
            imagefilledrectangle($dest_image, 0, 0, $dst_width, $dst_height, $transparent);
        } else {
            $white = imagecolorallocate($dest_image, 255, 255, 255);
            imagefilledrectangle($dest_image, 0, 0, $dst_width, $dst_height, $white);
        }

        $src_image = ImageManager::create($type, $src_file);
        if ($rotate) {
            $src_image = imagerotate($src_image, $rotate, 0);
        }

        if ($dst_width >= $src_width && $dst_height >= $src_height) {
            imagecopyresized($dest_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        } else {
            ImageManager::imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height, 9);
        }
        $write_file = ImageManager::write($file_type, $dest_image, $dst_file);
        @imagedestroy($src_image);

        return $write_file;
    }

    /**
     * Get the banner thumbnail path
     *
     * @param int $id
     * @param null $thumbnail
     *
     * @return string
     */
    public function getBannerImagePath($id = 0, $thumbnail = null, $hash = null)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);
        if (empty($hash)) {
            $hash = $this->getBannerHash($id);
        }

        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            $thumbnail = self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_ORIGINAL'];
        }

        if ($dirs['dirs']['section'] != null) {
            return $dirs['dirs']['section'].'/'.$id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg';
        } else {
            return '';
        }
    }

    public function getCarouselImagePath($hash, $id = 0, $thumbnail = null)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);

        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            $thumbnail = self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_ORIGINAL'];
        }

        if ($dirs['dirs']['carouselSection'] != null) {
            return $dirs['dirs']['carouselSection'].'/'.$id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg';
        } else {
            return '';
        }
    }
    
    public static function getBannerImageRelativePath($id, $hash, $thumbnail = null)
    {
        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            $thumbnail = self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_ORIGINAL'];
        }
        // var_dump('/banners/'.(int)$id.'/'.(int)$id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg');die;
        return '/banners/'.(int) $id.'/'.(int) $id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg';
    }

    public static function getCarouselImageRelativePath($id, $hash, $thumbnail = null)
    {
        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            $thumbnail = self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_ORIGINAL'];
        }
        // var_dump('/banners/'.(int)$id.'/'.(int)$id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg');die;
        return '/carousel/'.(int) $id.'/'.(int) $id.'-'.$hash.'-banner_image_'.$thumbnail.'.jpg';
    }

    /**
     * Valid thumbnails
     *
     * @return array
     */
    public static function getValidBannerImageThumbnails()
    {
        return array_values(self::$THUMBNAILS);
    }

    /**
     * Get image directories with urls
     *
     * @param int $id
     *
     * @return array
     */
    public function getImageDirectoriesAndUrls($id = 0)
    {
        $moduleUrl = '/img/modules/'.$this->module->name;
        $bannersUrl = $moduleUrl.'/banners';
        $sectionUrl = ((int) $id > 0) ? $bannersUrl.'/'.$id : null;
        $carouselUrl = $moduleUrl.'/carousel';
        $carouselSectionUrl = ((int) $id > 0) ? $carouselUrl.'/'.$id : null;

        $moduleDir = _PS_ROOT_DIR_.$moduleUrl;
        $bannersDir = $moduleDir.'/banners';
        $sectionDir = ((int) $id > 0) ? $bannersDir.'/'.$id : null;
        $carouselDir = $moduleDir.'/carousel';
        $carouselSectionDir = ((int) $id > 0) ? $carouselDir.'/'.$id : null;

        $output = [
            'dirs' => [
                'module' => $moduleDir,
                'banners' => $bannersDir,
                'section' => $sectionDir,
                'carousel' => $carouselDir,
                'carouselSection' => $carouselSectionDir
            ],
            'urls' => [
                'module' => $moduleUrl,
                'banners' => $bannersUrl,
                'section' => $sectionUrl,
                'carousel' => $carouselUrl,
                'carouselSection' => $carouselSectionUrl
            ],
        ];

        return $output;
    }

    /**
     * Get thumbnails size
     *
     * @param $thumbnail
     *
     * @return array|bool
     */
    public function getThumbnailDimensions($thumbnail)
    {
        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            return false;
        }

        /*
         * rettangolare: 2000x1000
         * quadrato: 2000x2000
         * verticale: 2000x2700
         */

        $width = 0;
        switch ($thumbnail) {
            case self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_APP']:
                $width = 1000;

                break;
            case self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_LIST']:
                $width = 100;

                break;
            case self::$THUMBNAILS['BANNER_IMAGE_THUMBNAIL_DETAIL']:
                $width = 400;

                break;
        }
        $height = $width / 2;
        if ($width == 0 || $height == 0) {
            return false;
        }

        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * Delete old thumbnails, generate image hash and save the new thumbnail
     *
     * @param $id
     * @param $srcPath
     *
     * @return string|null
     */
    public function copyBannerImage($id, $srcPath)
    {
        $this->clearThumbnails($id);
        $this->setBannerHash($id, md5_file($srcPath));
        $dstPath = $this->getBannerImagePath($id);
        if (file_exists($srcPath)) {
            if (copy($srcPath, $dstPath)) {
                return null;
            } else {
                $this->setBannerHash($id, null);

                return $this->module->l('There was an error while duplicating the banner, please try again.', 'imageutils');
            }
        } else {
            $this->setBannerHash($id, null);

            return $this->module->l('Unable to find the uploaded image, please try again.', 'imageutils');
        }
    }

    public function copyCarouselImage($id, $srcPath, $hash)
    {
        // $this->clearThumbnails($id);
        $dstPath = $this->getCarouselImagePath($hash, $id);
        if (file_exists($srcPath)) {
            if (copy($srcPath, $dstPath)) {
                return null;
            } else {
                return $this->module->l('There was an error while duplicating the banner, please try again.', 'imageutils');
            }
        } else {
            return $this->module->l('Unable to find the uploaded image, please try again.', 'imageutils');
        }
    }

    public function carouselFileExists($id, $name)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);
        $path = $dirs['dirs']['carouselSection'].'/'.$name;

        return file_exists($path);
    }

    public function getCarouselHash($id, $name)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);
        $path = $dirs['dirs']['carouselSection'].'/'.$name;

        return md5_file($path);
    }

    /**
     * Create directories
     *
     * @param int $id
     */
    public function createImageDirectories($id = 0)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);

        $path_image = _PS_IMG_DIR_.'modules/mrshopapi';
        if (!file_exists($path_image)) {
            mkdir($path_image, 0755, true);
        }

        if (!file_exists($dirs['dirs']['module'])) {
            @mkdir($dirs['dirs']['module']);
        }
        if (!file_exists($dirs['dirs']['banners'])) {
            @mkdir($dirs['dirs']['banners']);
        }
        if ($dirs['dirs']['section'] != null && !file_exists($dirs['dirs']['section'])) {
            @mkdir($dirs['dirs']['section']);
        }
        if ($dirs['dirs']['carousel'] != null && !file_exists($dirs['dirs']['carousel'])) {
            @mkdir($dirs['dirs']['carousel']);
        }
        if ($dirs['dirs']['carouselSection'] != null && !file_exists($dirs['dirs']['carouselSection'])) {
            @mkdir($dirs['dirs']['carouselSection']);
        }
    }
    
    public function createImageTmpDirectory()
    {
        $path_image = _PS_IMG_DIR_.'modules/mrshopapi/tmp';
        if (!file_exists($path_image)) {
            mkdir($path_image, 0755, true);
        }
    }
    
    public function tmpFileExists($name)
    {
        return file_exists($this->tmpImagePath($name));
    }
    
    public function tmpImagePath($name)
    {
        if (preg_match('/^[a-zA-Z0-9_ -\.]+$/u', $name)) {
            return _PS_IMG_DIR_.'modules/mrshopapi/tmp/'.$name;
        }

        return null;
    }
    
    public function saveTmpImage($tmpName, $fileName)
    {
        $fpath    = $this->tmpImagePath($tmpName);
        $img_data = getimagesize($fpath);
        $width = $img_data[0];
        $height = $img_data[1];
        $oName = $fileName.'.jpg';
        $thumb_width  = 100;
        $thumb_height = ($height / $width) * $thumb_width;
        $destP        = self::getImageRelativePath($oName, false);
        $destThumb    = self::getImageRelativePath($fileName.'_thumb.jpg', false);

        // image
        if (!ImageManager::resize($fpath, $destP, $width, $height)) {
            return false;
        }
        if (!ImageManager::resize(
            $fpath,
            $destThumb,
            $thumb_width,
            $thumb_height
        )) {
            return false;
        }

        unlink($fpath);

        return $oName;
    }
    
    public function createBaseImageDirectory()
    {
        $path_image = static::getImagesBasePath();
        if (!file_exists($path_image)) {
            mkdir($path_image, 0755, true);
        }

        return $path_image;
    }

    /**
     * Get banner thumbnail url
     *
     * @param int $id
     * @param null $thumbnail
     *
     * @return string
     */
    public function getBannerImageUrl($id = 0, $thumbnail = null)
    {
        $dirs = $this->getImageDirectoriesAndUrls($id);
        if (!$thumbnail || !in_array($thumbnail, self::getValidBannerImageThumbnails())) {
            return '';
        }
        if ($dirs['urls']['section'] != null) {
            return $dirs['urls']['section'].'/'.$id.'-'.$this->getBannerHash($id).'-banner_image_'.$thumbnail.'.jpg';
        } else {
            return '';
        }
    }

    /**
     * Get banner hash
     *
     * @param $id
     *
     * @return false|string|null
     */
    public function getBannerHash($id)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `hash` FROM `'._DB_PREFIX_.'app_home`
            WHERE `id_app_home` = "'.pSQL($id).'"
        ');
    }

    /**
     * Set banner hash
     *
     * @param $id
     * @param $hash
     *
     * @return bool
     */
    public function setBannerHash($id, $hash)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('
            UPDATE `'._DB_PREFIX_.'app_home`
            SET `hash` = "'.$hash.'"
            WHERE `id_app_home` = '.$id.'
        ');
    }

    /**
     * Delete all banner thumbnails
     *
     * @param $id
     * @param array $exclude
     * @param $folder
     */
    public function clearThumbnails($id, $exclude = [], $folder = false)
    {
        $thumbnails = glob(_PS_IMG_DIR_.'modules/'.$this->module->name.'/banners/'.$id.'/*.*');
        foreach ($thumbnails as $thumbnail) {
            if (file_exists($thumbnail) && !in_array($thumbnail, $exclude)) {
                unlink($thumbnail);
            }
        }
        if ($folder) {
            $folder = _PS_IMG_DIR_.'modules/'.$this->module->name.'/banners/'.$id.'/';
            if (is_dir($folder)) {
                rmdir($folder);
            }
        }
    }
    
    /**
     * Presents the image entity
     * @param string $filename
     * @return array|null
     */
    public static function presentImage($filename)
    {
        $f = basename($filename);
        $fpath = static::getImageRelativePath($filename, false);
        
        if (false == file_exists($fpath) || is_dir($fpath)) {
            return null;
        }
            
        $rpath = static::getImageRelativePath($filename);

        return [
            'name' => $f,
            'url'  => ApiUtils::cacheBurstedUrl($rpath)
        ];
    }
    
    public static function presentTmpImage($filename)
    {
        return self::presentImage('tmp/'.$filename);
    }
    
    /**
     * Checks if the passed config key is a global image key
     * @param string $configKey
     * @return bool
     */
    public static function isGlobalAppImage($configKey)
    {
        return in_array($configKey, self::GLOBAL_IMAGES);
    }
    
    public static function getImagesBasePath($relative = false)
    {
        $p = 'modules/'.ApiUtils::MODULE_NAME;
        $c = $relative
                ? str_replace(_PS_ROOT_DIR_, '', _PS_IMG_DIR_)
                : _PS_IMG_DIR_;

        return $c.$p;
    }
    
    public static function getImageRelativePath($filename, $relative = true)
    {
        $stripped = ApiUtils::stripLeadingSlash($filename);

        return static::getImagesBasePath($relative)."/$stripped";
    }
}
