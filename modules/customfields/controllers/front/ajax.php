<?php
/**
 * FMM Custom Fields
 *
 * NOTICE OF LICENSE
 *
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by FMM Modules.
 *
 * @author    FMM Modules
 * @copyright 2022 FMM Modules All right reserved
 * @license   FMM Modules
 */

class CustomFieldsAjaxModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    public function init()
    {
        parent::init();
        require_once($this->module->getLocalPath().'models/FieldsModel.php');
        if (Tools::isSubmit('downloadFile')) {
            $link = base64_decode(Tools::getValue('l'));
            FieldsModel::actionDownload($link);
        }
    }

    public function initContent()
    {
        parent::initContent();
        $model = new FieldsModel();
        $id_cart = (Tools::getValue('id_cart'))? (int)Tools::getValue('id_cart') : (int)$this->context->cart->id;
        $id_order = (Tools::getValue('id_order'))? (int)Tools::getValue('id_order') : 0;

        $return = $model->saveFieldValues(Tools::getValue('fields'), Tools::getValue('type'), $id_cart);
        if (is_array($return)) {
            die(Tools::jsonEncode(array('result' => false, 'type' => Tools::getValue('type'), 'errors' => $return)));
        }
        // set order id for new fields (for order detail page only)
        $model->setOrderId($id_cart, $id_order);
        die(Tools::jsonEncode(array('result' => true, 'type' => Tools::getValue('type'))));
    }
}
