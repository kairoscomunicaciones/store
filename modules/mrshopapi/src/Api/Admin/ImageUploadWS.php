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

namespace MrAPPs\MrShopApi\Api\Admin;

use MrAPPs\MrShopApi\Api\Contracts\WebservicePostInterface;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;

/**
 * Image Upload Endpoint
 */
class ImageUploadWS extends BaseWS implements WebservicePostInterface
{
    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
    }
   
    public function updateOrCreate($bodyParams, $id, $userId, $module)
    {
        $errors = [];
        /**
         * is_uploaded_file to prevent maliocious attacks
         * php.net/manual/en/function.is-uploaded-file.php
         */
        if (false == isset($_FILES['file']) || false == is_uploaded_file($_FILES['file']['tmp_name'])) {
            $errors[] = $this->module->l('Image file is required', 'imageuploadws');
        } else {
            $mimetype = mime_content_type($_FILES['file']['tmp_name']);
            if (strpos($mimetype, 'image/') !== 0) {
                $errors[] = $this->module->l('Uploaded file has to be an image', 'imageuploadws');
            }
        }
        
        $type = Tools::getValue('type');
        
        if (empty($type)) {
            $errors[] = $this->module->l('Type has to be specified', 'imageuploadws');
        } elseif (false == in_array($type, ['banner', 'logo', 'splash', 'icon', 'darkSplash', 'darkLogo'])) {
            $errors[] = sprintf($this->module->l('Invalid type %s', 'imageuploadws'), $type);
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (!in_array($ext, ['jpg','png'])) {
            $errors[] = $this->module->l('Uploaded file has to be an image', 'imageuploadws');
        }
        
        if (count($errors) > 0) {
            ResponseHandler::unprocessableEntity($errors);
        }
        
        $name = $type.'_'.time().'_'.uniqid().".$ext";
        $utils = new ImageUtils();
        $utils->createImageTmpDirectory();
        $fpath = $utils->tmpImagePath($name);
        if (false == move_uploaded_file($_FILES['file']['tmp_name'], $fpath)) {
            return $this->response(false, $this->module->l('An error occured during file upload', 'imageuploadws'));
        }
        
        $this->response(true, null, ImageUtils::presentTmpImage($name));
    }
}
