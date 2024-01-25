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

use Db;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Service\ImageService;
use Product;

class CustomizationWS extends ProductBaseWS
{
    private $imageService;

    private $cartHandler;

    const CUSTOMIZE_FILE = 'file';

    const CUSTOMIZE_TEXTFIELD = 'text';

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->imageService = new ImageService();
        $this->cartHandler = new CartHandler($this->dataHandler->getGuestId());
    }

    public function getList($params)
    {
        if (!array_key_exists('name', $params)) {
            $this->response(false, $this->module->l('Image not exists', 'customizationws'));
        }

        if (!preg_match('/^[a-zA-Z0-9_ -\.]+$/u', $params['name'])) {
            $this->response(false, $this->module->l('Uploaded file has to be an image', 'customizationws'));
        }

        $psUploadPath = $this->imageService->getPsUploadPath();
        $filename = $psUploadPath.'/'.$params['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array($ext, ['jpg','png'])) {
            $this->response(false, $this->module->l('Uploaded file has to be an image', 'customizationws'));
        }

        // Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: image/jpg');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        // header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: '.filesize($filename));
        header('Pragma: public');
        // Clear system output buffer
        flush();
        // Read the size of the file
        readfile($filename);
        // Terminate from the script
        exit();
    }

    public function updateOrCreate($params, $id, $id_customer)
    {
        if (!array_key_exists('id_product', $params)) {
            return $this->response(false, $this->module->l('Missing product', 'filews'));
        }

        $cart = $this->cartHandler->getCart($id_customer, false);
        $productId = (int) $params['id_product'];
        $productAttributeId = array_key_exists('id_product_attribute', $params) ? (int) $params['id_product_attribute'] : 0;
        $customizationFields = $this->getProductCustomizationFields($productId);
        $psUploadPath = $this->imageService->getPsUploadPath();

        $id_customization = 0;
        foreach ($customizationFields as $customField) {
            $fieldId = $customField['id'];

            if (
                $customField['required'] &&
                !isset($params[$fieldId]) &&
                !isset($_FILES[$fieldId])
            ) {
                $this->response(false, $this->module->l('Custom field is required', 'customizationws'));
            }

            $splitted = explode('_', $fieldId);
            if (count($splitted) != 2) {
                $this->response(false, $this->module->l('An error occured during customization save', 'customizationws'));
            }

            $index = (int) $splitted[1];
            $type = 0;
            $value = '';

            if ($customField['required']) {
                switch ($customField['type']) {
                    case CustomizationWS::CUSTOMIZE_FILE:
                        if (false == is_uploaded_file($_FILES[$fieldId]['tmp_name'])) {
                            $this->response(false, $this->module->l('Image file is required', 'customizationws'));
                        } else {
                            $mimetype = mime_content_type($_FILES[$fieldId]['tmp_name']);
                            if (strpos($mimetype, 'image/') !== 0) {
                                $this->response(false, $this->module->l('Uploaded file has to be an image', 'customizationws'));
                            }
                        }

                        $ext = pathinfo($_FILES[$fieldId]['name'], PATHINFO_EXTENSION);

                        if (!in_array($ext, ['jpg','png'])) {
                            $this->response(false, $this->module->l('Uploaded file has to be an image', 'customizationws'));
                        }

                        $name = 'mrshop_'.time().'_'.uniqid().".$ext";
                        $name = substr($name, 0, strrpos($name, '.'));
                        $outputPath = $psUploadPath.'/'.$name;

                        if (false == move_uploaded_file($_FILES[$fieldId]['tmp_name'], $outputPath)) {
                            $this->response(false, $this->module->l('An error occured during file upload', 'filews'));
                        }

                        $type = Product::CUSTOMIZE_FILE;
                        $value = $name;

                        break;
                    case CustomizationWS::CUSTOMIZE_TEXTFIELD:
                        $type = Product::CUSTOMIZE_TEXTFIELD;
                        $value = $params[$fieldId];

                        break;
                }
            }

            $id_customization = $this->_addCustomization(
                $cart,
                $productId,
                $productAttributeId,
                $index,
                $type,
                $value,
                0,
                true
            );
        }

        if ($id_customization === 0) {
            $this->response(false, $this->module->l('An error occured during customization save', 'customizationws'));
        }

        $this->response(true, null, [
            'id_customization' => $id_customization
        ]);
    }

    /**
     * Add customization item to database.
     *
     * @param int $id_product Product ID
     * @param int $id_product_attribute ProductAttribute ID
     * @param int $index Customization field identifier as id_customization_field in table customization_field
     * @param int $type Customization type can be Product::CUSTOMIZE_FILE or Product::CUSTOMIZE_TEXTFIELD
     * @param string $value Customization value
     * @param int $quantity Quantity value
     * @param bool $returnId if true - returns the customization record id
     * Cart->_addCustomization
     *
     * @return bool Success
     */
    public function _addCustomization($cart, $id_product, $id_product_attribute, $index, $type, $value, $quantity, $returnId = false)
    {
        $exising_customization = Db::getInstance()->executeS(
            'SELECT cu.`id_customization`, cd.`index`, cd.`value`, cd.`type` FROM `'._DB_PREFIX_.'customization` cu
            LEFT JOIN `'._DB_PREFIX_.'customized_data` cd
            ON cu.`id_customization` = cd.`id_customization`
            WHERE cu.id_cart = '.(int) $cart->id.'
            AND cu.id_product = '.(int) $id_product.'
            AND in_cart = 0'
        );

        if ($exising_customization) {
            // If the customization field is alreay filled, delete it
            foreach ($exising_customization as $customization) {
                if ($customization['type'] == $type && $customization['index'] == $index) {
                    Db::getInstance()->execute('
                        DELETE FROM `'._DB_PREFIX_.'customized_data`
                        WHERE id_customization = '.(int) $customization['id_customization'].'
                        AND type = '.(int) $customization['type'].'
                        AND `index` = '.(int) $customization['index']);
                    if ($type == Product::CUSTOMIZE_FILE) {
                        @unlink(_PS_UPLOAD_DIR_.$customization['value']);
                        @unlink(_PS_UPLOAD_DIR_.$customization['value'].'_small');
                    }

                    break;
                }
            }
            $id_customization = $exising_customization[0]['id_customization'];
        } else {
            Db::getInstance()->execute(
                'INSERT INTO `'._DB_PREFIX_.'customization` (`id_cart`, `id_product`, `id_product_attribute`, `quantity`)
                VALUES ('.(int) $cart->id.', '.(int) $id_product.', '.(int) $id_product_attribute.', '.(int) $quantity.')'
            );
            $id_customization = Db::getInstance()->Insert_ID();
        }

        $query = 'INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`)
            VALUES ('.(int) $id_customization.', '.(int) $type.', '.(int) $index.', \''.pSQL($value).'\')';

        if (!Db::getInstance()->execute($query)) {
            return false;
        }

        if (true === $returnId) {
            return (int) $id_customization;
        }

        return true;
    }
}
