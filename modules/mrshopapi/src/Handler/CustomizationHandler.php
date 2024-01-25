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

namespace MrAPPs\MrShopApi\Handler;

use Cart;
use Context;
use Customization;
use CustomizationField;
use Db;
use MrAPPs\MrShopApi\Service\ImageService;
use Product;

class CustomizationHandler
{
    private $context;

    private $imageService;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->imageService = new ImageService();
    }

    public function getProductCustomizationValues($id_cart, $id_product)
    {
        $cart = new Cart($id_cart);

        $customizationFieldIds = $this->getNonDeletedCustomizationFieldIds($id_product);
        $customizationValues = $cart->getProductCustomization($id_product);
        $customizations = [];

        foreach ($customizationFieldIds as $fieldId) {
            foreach ($customizationValues as $customizationValue) {
                if ((int) $customizationValue['index'] == (int) $fieldId) {
                    $customizationField = new CustomizationField((int) $fieldId);

                    $type = $customizationValue['type'] == Product::CUSTOMIZE_FILE ? 'file' : 'text';
                    $id = $type.'_'.$fieldId;
                    $value = $customizationValue['type'] == Product::CUSTOMIZE_FILE ? $this->imageService->getCustomizationUrl($customizationValue['value']) : $customizationValue['value'];

                    $customizations[] = [
                        'id' => $id,
                        'type' => $type,
                        'name' => $customizationField->name[$this->context->language->id],
                        'required' => (bool) $customizationField->required,
                        'value' => $value
                    ];

                    continue;
                }
            }
        }

        return $customizations;
    }

    /*
        Prevent prestashop bug that not delete customization when cart product quantity is 0
        and prevent double products
    */
    public function deleteProductCustomization($id_cart, $id_customization)
    {
        $customization = new Customization($id_customization);
        if ($customization->id_cart == $id_cart && $customization->delete()) {
            $subQuery = "DELETE FROM "._DB_PREFIX_."customized_data WHERE id_customization = ".(int) $id_customization;
            Db::getInstance()->execute($subQuery);
        }
    }

    public function hasCustomization($product)
    {
        $ids = $this->getNonDeletedCustomizationFieldIds($product->id);

        return count($ids) > 0;
    }

    public function getNonDeletedCustomizationFieldIds($id_product)
    {
        if (!Customization::isFeatureActive()) {
            return [];
        }

        $results = Db::getInstance()->executeS(
            '
            SELECT `id_customization_field`
            FROM `'._DB_PREFIX_.'customization_field`
            WHERE `id_product` = '.(int) $id_product
        );

        return array_map(function ($result) {
            return (int) $result['id_customization_field'];
        }, $results);
    }
}
