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

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__) . '/models/FieldsModel.php';
include_once dirname(__FILE__) . '/models/CustomFieldsHeadings.php';

class CustomFields extends Module
{
    public $trans = array();

    public $file_errors = array();

    protected $ajaxController = null;

    protected $id_shop = null;

    protected $id_shop_group = null;

    protected $order_steps = array();

    /* zelgra one page checkout module */
    protected $zelgra_is_module = false;
    /* pts one page checkout module */
    protected $pts_is_module = false;

    private $tab_module = 'customfields';

    private $tab_class = 'AdminCustomFields';

    public function __construct()
    {
        $this->name = 'customfields';
        $this->tab = 'front_office_features';
        $this->version = '2.2.3';
        $this->author = 'FMM Modules';
        $this->bootstrap = true;
        $this->module_key = 'cd34d56e2bc861010705859b1c14a5b5';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';

        parent::__construct();

        $this->description = $this->l('This module allows to add custom fields during checkout.');
        $this->displayName = $this->l('Custom Fields');

        if ($this->id_shop === null || !Shop::isFeatureActive()) {
            $this->id_shop = Shop::getContextShopID();
        } else {
            $this->id_shop = $this->context->shop->id;
        }
        if ($this->id_shop_group === null || !Shop::isFeatureActive()) {
            $this->id_shop_group = Shop::getContextShopGroupID();
        } else {
            $this->id_shop_group = $this->context->shop->id_shop_group;
        }

        $this->trans = $this->getCfTranslations();

        $this->file_errors = $this->getFileErrors();

        $this->order_steps = $this->getCheckoutSteps();

        // zelgra onepagecheckout module
        $this->checkOnestepModules(
            'onepagecheckout',
            'zelgra_is_module',
            'pts_is_module'
        );

        // pts onepagecheckout module
        $this->checkOnestepModules(
            'onepagecheckoutps',
            'pts_is_module',
            'zelgra_is_module'
        );
    }

    public function install()
    {
        if (!$this->existsTab()) {
            if (!$this->addTab()) {
                return false;
            }
        }

        Configuration::updateValue(
            'CUSTOMFIELD_BLOCK_HEADING',
            array($this->context->language->id => $this->l('Custom Fields'))
        );
        include dirname(__FILE__) . '/sql/install.php';
        if (parent::install() &&
            $this->registerHook(array(
                'header',
                'newOrder',
                'adminOrder',
                'ModuleRoutes',
                'displayPaymentTop',
                'displayPDFInvoice',
                'displayPDFDeliverySlip',
                'displayAfterCarrier',
                'orderDetailDisplayed',
                'displayBackOfficeHeader',
                'displayShoppingCartFooter',
                'registerGDPRConsent',
                'actionDeleteGDPRCustomer',
                'actionExportGDPRData',
            ))) {
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        if (!$this->removeTab()) {
            return false;
        }

        include dirname(__FILE__) . '/sql/uninstall.php';

        $this->unregisterHook('header');
        $this->unregisterHook('newOrder');
        $this->unregisterHook('adminOrder');
        $this->unregisterHook('displayPaymentTop');
        $this->unregisterHook('displayPDFInvoice');
        $this->unregisterHook('orderDetailDisplayed');
        $this->unregisterHook('displayPDFDeliverySlip');
        $this->unregisterHook('displayBackOfficeHeader');
        $this->unregisterHook('displayShoppingCartFooter');
        $this->unregisterHook('registerGDPRConsent');
        $this->unregisterHook('actionDeleteGDPRCustomer');
        $this->unregisterHook('actionExportGDPRData');
        Configuration::deleteByName('CUSTOMFIELD_BLOCK_HEADING');
        parent::uninstall();
        return true;
    }

    private function addTab()
    {
        $return = true;
        $tab = new Tab();
        $tab->class_name = $this->tab_class;
        $tab->id_parent = 0;
        $tab->module = $this->name;
        $tab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l('Custom Fields');
        $return &= $tab->add();

        $subtab = new Tab();
        $subtab->class_name = 'AdminFields';
        $subtab->id_parent = $tab->id;
        $subtab->module = $this->name;
        $subtab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l('Manage Custom Fields');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $subtab->icon = 'content_paste';
        }
        $return &= $subtab->add();

        $subtab2 = new Tab();
        $subtab2->class_name = 'AdminCustomFieldsHeadings';
        $subtab2->id_parent = $tab->id;
        $subtab2->module = $this->name;
        $subtab2->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $this->l(
            'Custom Fields Headings'
        );
        if (true === Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $subtab2->icon = 'insert_drive_file';
        }
        $return &= $subtab2->add();

        $subtab3 = new Tab();
        $subtab3->class_name = 'AdminCustomFieldsSetting';
        $subtab3->id_parent = $tab->id;
        $subtab3->module = $this->name;
        $subtab3->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $this->l(
            'Custom Fields Settings'
        );
        if (true === Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $subtab3->icon = 'settings';
        }
        $return &= $subtab3->add();

        return $return;
    }

    private function removeTab()
    {
        $return = true;
        $tabs = ['AdminCustomFieldsSetting', 'AdminCustomFieldsHeadings', 'AdminFields', $this->tab_class];
        foreach ($tabs as $tab) {
            if (Validate::isLoadedObject($tab = Tab::getInstanceFromClassName($tab))) {
                return $return &= $tab->delete();
            }
        }
        return $return;
    }

    public function existsTab()
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT id_tab AS id
            FROM `' . _DB_PREFIX_ . 'tab` t WHERE LOWER(t.`class_name`) = \'' . pSQL($this->tab_class) . '\'');
        if (count($result) == 0) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $this->html = $this->display(__FILE__, 'views/templates/hook/info.tpl');
        $output = '';
        if (Tools::isSubmit('submitConfiguration')) {
            $message_trads = array();
            foreach ($_POST as $key => $value) {
                if (preg_match('/CUSTOMFIELD_BLOCK_HEADING_/i', $key)) {
                    $id_lang = preg_split('/CUSTOMFIELD_BLOCK_HEADING_/i', $key);
                    $message_trads[(int) $id_lang[1]] = $value;
                }
            }

            Configuration::updateValue('CUSTOMFIELD_BLOCK_HEADING', $message_trads, true);
            Configuration::updateValue('CUSTOMFIELD_PDF_INVOICE', Tools::getValue('CUSTOMFIELD_PDF_INVOICE'));
            Configuration::updateValue('CUSTOMFIELD_DELIVERY_SLIP', Tools::getValue('CUSTOMFIELD_DELIVERY_SLIP'));
            Configuration::updateValue('CUSTOMFIELD_CUSTOMER_EMAIL', Tools::getValue('CUSTOMFIELD_CUSTOMER_EMAIL'));
            $output .= $this->displayConfirmation($this->l('Configuration updated'));
        }
        return $this->html . $output . $this->renderForm();
    }

    public function renderForm()
    {
        $radio = (Tools::version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio';
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Custom Fields Main Heading:'),
                        'name' => 'CUSTOMFIELD_BLOCK_HEADING',
                        'col' => 6,
                    ),
                    array(
                        'type' => $radio,
                        'label' => $this->l('Show Fields on PDF Invoice'),
                        'desc' => $this->l('Display custom fields in PDF invoice Page.'),
                        'name' => 'CUSTOMFIELD_PDF_INVOICE',
                        'values' => array(
                            array(
                                'id' => 'CUSTOMFIELD_PDF_INVOICE_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'CUSTOMFIELD_PDF_INVOICE_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $radio,
                        'label' => $this->l('Show Fields on PDF Delivery Slip'),
                        'desc' => $this->l('Display custom fields in PDF Delivery Slip page.'),
                        'name' => 'CUSTOMFIELD_DELIVERY_SLIP',
                        'values' => array(
                            array(
                                'id' => 'CUSTOMFIELD_DELIVERY_SLIP_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'CUSTOMFIELD_DELIVERY_SLIP_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $radio,
                        'label' => $this->l('Show Fields in Customer Email'),
                        'desc' => $this->l('Fields will be shown in order confirmation email send to customer.'),
                        'name' => 'CUSTOMFIELD_CUSTOMER_EMAIL',
                        'values' => array(
                            array(
                                'id' => 'CUSTOMFIELD_CUSTOMER_EMAIL_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'CUSTOMFIELD_CUSTOMER_EMAIL_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfiguration';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $return = array(
            'CUSTOMFIELD_PDF_INVOICE' => Configuration::get('CUSTOMFIELD_PDF_INVOICE', null, $this->id_shop_group, $this->id_shop),
            'CUSTOMFIELD_DELIVERY_SLIP' => Configuration::get('CUSTOMFIELD_DELIVERY_SLIP', null, $this->id_shop_group, $this->id_shop),
            'CUSTOMFIELD_CUSTOMER_EMAIL' => Configuration::get('CUSTOMFIELD_CUSTOMER_EMAIL', null, $this->id_shop_group, $this->id_shop),
        );
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $return['CUSTOMFIELD_BLOCK_HEADING'][(int) $lang['id_lang']] = Tools::getValue('CUSTOMFIELD_BLOCK_HEADING_' . (int) $lang['id_lang'], Configuration::get('CUSTOMFIELD_BLOCK_HEADING', (int) $lang['id_lang']));
        }
        return $return;
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
    }

    public function hookModuleRoutes()
    {
        return array(
            'module-' . $this->name . '-ajax' => array(
                'controller' => 'ajax',
                'rule' => 'checkout-fields',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => $this->name,
                ),
            ),
        );
    }

    public function hookHeader()
    {
        $this->ajaxController = Context::getContext()->link->getModuleLink($this->name, 'ajax', array('ajax' => true));
        $controller = Dispatcher::getInstance()->getController();
        if ($controller == 'orderdetail' || $controller == 'history') {
            $this->context->controller->addJs($this->_path . 'views/js/accordion_slide.js');
            $this->context->controller->addCss($this->_path . 'views/css/accordion_slide.css');
        }
        Media::addJsDef(array('checkout_label' => $this->l('Checkout')));
        Media::addJsDef(array('is_required_label' => ' ' . $this->l('is required')));
        Media::addJsDef(array('ajax_controller' => $this->ajaxController));
        $this->context->controller->addJS(($this->_path) . 'views/js/jquery-watch.js');

        $this->context->controller->addCss($this->_path . 'views/css/customfields_common.css');
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            return $this->psNew($controller);
        } else {
            return $this->psOld($controller);
        }
    }

    public function hookdisplayPaymentTop()
    {
        if (!$this->zelgra_is_module && !$this->pts_is_module) {
            $id_lang = $this->context->cookie->id_lang;
            $obj_model = new FieldsModel();
            $payment_fields = $obj_model->getCustomFields('payment', $id_lang);

            $cart_products = array();
            // if (isset($this->context->cart)) {
            //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
            //     $payment_fields = $this->filterFieldsByProducts($payment_fields, $cart_products);
            // }
            if ($payment_fields == false) {
                $payment_fields = [];
            }
            $payment_fields_values = array();
            foreach ($payment_fields as $sf) {
                $payment_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
            }

            $model = new FieldsModel();
            if (isset($payment_fields) && !empty($payment_fields)) {
                foreach ($payment_fields as &$field) {
                    if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                        $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                    }
                    else {
                        $field['sub_heading'] = '';
                    }
                }
            }
            $this->context->smarty->assign('model', $model);
            $this->context->smarty->assign('summary_fields_values', $payment_fields_values);
            $this->context->smarty->assign('summary_fields', $payment_fields);
            $this->context->smarty->assign('type', 'payment');
            $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));
            $this->context->smarty->assign('version', _PS_VERSION_);
            $this->context->smarty->assign('action_controller', $this->ajaxController);
            return $this->display(__FILE__, 'summary.tpl');
        }
    }

    public function hookNewOrder($data)
    {
        $where = array('id_order' => $data['order']->id);
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'custom_field_userdata`
            SET `id_order` = ' . (int) $data['order']->id . '
            WHERE `id_cart` = ' . (int) $data['order']->id_cart);
        } else {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->autoexecute(_DB_PREFIX_ . 'custom_field_userdata', $where, 'UPDATE', 'id_cart =' . (int) $data['order']->id_cart);
        }
    }

    public function hookdisplayShoppingCartFooter()
    {
        $id_lang = $this->context->cookie->id_lang;
        $obj_model = new FieldsModel();
        $summary_fields = $obj_model->getCustomFields('summary', $id_lang);
        $summary_fields_values = array();
        $cart_products = array();
        // if (isset($this->context->cart)) {
        //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
        //     $summary_fields = $this->filterFieldsByProducts($summary_fields, $cart_products);
        // }

        if (isset($summary_fields) && $summary_fields) {
            foreach ($summary_fields as $sf) {
                $summary_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
            }
            $model = new FieldsModel();
            if (isset($summary_fields) && !empty($summary_fields)) {
                foreach ($summary_fields as &$field) {
                    if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                        $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                    }
                    else {
                        $field['sub_heading'] = '';
                    }
                }
            }
            $this->context->smarty->assign('model', $model);
            $this->context->smarty->assign('version', _PS_VERSION_);
            $this->context->smarty->assign('summary_fields_values', $summary_fields_values);
            $this->context->smarty->assign('summary_fields', $summary_fields);
            $this->context->smarty->assign('type', 'summary');
            $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));
            $this->context->smarty->assign('action_controller', $this->ajaxController);
            if (isset($this->context->cart) && $this->context->cart->getProducts()) {
                return $this->display(__FILE__, 'summary.tpl');
            }
        }
    }

    public function hookOrderDetailDisplayed($params)
    {
        $id_lang = $this->context->cookie->id_lang;
        $id_shop = $this->context->shop->id;
        $id_order = (isset($params) && isset($params['order']) && $params['order']->id) ? $params['order']->id : 0;
        $id_cart = (isset($params) && isset($params['order']) && $params['order']->id_cart) ? $params['order']->id_cart : 0;
        $model = new FieldsModel();

        $cart = null;
        $summary_fields = FieldsModel::getCustomFields(
            'summary',
            $id_lang,
            $id_shop,
            $id_cart,
            't.show_customer = 1'
        );
        $delivery_fields = FieldsModel::getCustomFields(
            'delivery',
            $id_lang,
            $id_shop,
            $id_cart,
            't.show_customer = 1'
        );
        $billing_fields = FieldsModel::getCustomFields(
            'billing',
            $id_lang,
            $id_shop,
            $id_cart,
            't.show_customer = 1'
        );
        $shipping_fields = FieldsModel::getCustomFields(
            'shipping',
            $id_lang,
            $id_shop,
            $id_cart,
            't.show_customer = 1'
        );
        $payment_fields = FieldsModel::getCustomFields(
            'payment',
            $id_lang,
            $id_shop,
            $id_cart,
            't.show_customer = 1'
        );

        if ($id_cart) {
            $cart = new Cart($id_cart);
            $cart_products = array();
            if (isset($cart)) {
                $cart_products = $cart->getProducts(false, false, null, false);
                $summary_fields = $this->filterFieldsByProducts(
                    $summary_fields,
                    $cart_products
                );
                $delivery_fields = $this->filterFieldsByProducts(
                    $delivery_fields,
                    $cart_products
                );
                $billing_fields = $this->filterFieldsByProducts(
                    $billing_fields,
                    $cart_products
                );
                $shipping_fields = $this->filterFieldsByProducts(
                    $shipping_fields,
                    $cart_products
                );
                $payment_fields = $this->filterFieldsByProducts(
                    $payment_fields,
                    $cart_products
                );
            }
        }

        $checkout_fields = array(
            'summary' => $summary_fields,
            'delivery' => $delivery_fields,
            'billing' => $billing_fields,
            'shipping' => $shipping_fields,
            'payment' => $payment_fields,
        );

        $customfields_heading = Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id);
        $this->context->smarty->assign('model', $model);
        $this->context->smarty->assign('id_module', $this->id);
        $this->context->smarty->assign('id_order', $id_order);
        $this->context->smarty->assign('id_cart', $id_cart);
        $this->context->smarty->assign('version', _PS_VERSION_);
        $this->context->smarty->assign('checkout_labels', $this->order_steps);
        $this->context->smarty->assign('checkout_fields', $checkout_fields);
        $this->context->smarty->assign('customfields_heading', $customfields_heading);
        $this->context->smarty->assign('is_editable', FieldsModel::countEditableFields());
        $this->context->smarty->assign('nbrNonEditableValues', FieldsModel::countEditableFieldValues($id_cart));
        $this->context->smarty->assign('nbrMissingFields', FieldsModel::countNonEditableMissigFields($id_cart));
        $this->context->smarty->assign(
            'action_controller',
            Context::getContext()->link->getModuleLink(
                $this->name,
                'ajax',
                ['ajax' => true]
            )
        );
        return $this->display(__FILE__, 'order_fields.tpl');
    }

    public function hookAdminOrder($params)
    {
        $id_order = $params['id_order'];
        if ($id_order && Validate::isLoadedObject($order = new Order($id_order))) {
            $model = new FieldsModel();
            $fields = $model->getOrderFields('val.id_order = ' . (int) $id_order . ' and a.show_admin = 1');
            if (isset($fields) && $fields) {
                $customfields_heading = Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id);
                $grouped_fields = array();
                foreach ($fields as $field) {
                    $grouped_fields[$field['field_placement']][] = $field;
                }
                $this->smarty->assign(array(
                    'version' => _PS_VERSION_,
                    'checkout_steps' => $this->order_steps,
                    'grouped_fields' => $grouped_fields,
                    'customfields_heading' => $customfields_heading,
                    'obj_model' => $model,
                    'downloadLink' => $this->context->link->getAdminLink('AdminFields'),
                )
                );
                return $this->display(dirname(__FILE__), 'views/templates/admin/admin_fields.tpl');
            }
        }
    }

    public function hookDisplayAfterCarrier()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $id_lang = $this->context->cookie->id_lang;
            $obj_model = new FieldsModel();
            $shipping_fields = FieldsModel::getCustomFields('shipping', $id_lang);
            $cart_products = array();
            // if (isset($this->context->cart)) {
            //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
            //     $shipping_fields = $this->filterFieldsByProducts($shipping_fields, $cart_products);
            // }

            if (isset($shipping_fields) && $shipping_fields) {
                $shipping_fields_values = array();
                foreach ($shipping_fields as $sf) {
                    $shipping_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
                }

                $model = new FieldsModel();
                if (isset($shipping_fields) && !empty($shipping_fields)) {
                    foreach ($shipping_fields as &$field) {
                        if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                            $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                        }
                        else {
                            $field['sub_heading'] = '';
                        }
                    }
                }
                $this->context->smarty->assign('model', $model);
                $this->context->smarty->assign('version', _PS_VERSION_);
                $this->context->smarty->assign('summary_fields_values', $shipping_fields_values);
                $this->context->smarty->assign('summary_fields', $shipping_fields);
                $this->context->smarty->assign('type', 'shipping');
                $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));
                $this->context->smarty->assign('action_controller', $this->ajaxController);
                return $this->display(__FILE__, 'summary.tpl');
            }
        }
    }

    public function hookDisplayPDFDeliverySlip($params)
    {
        $id_order = $params['object']->id_order;
        if (Configuration::get('CUSTOMFIELD_DELIVERY_SLIP', null, $this->id_shop_group, $this->id_shop) == 1) {
            return $this->getPDFFields($id_order);
        }
    }

    public function hookDisplayPDFInvoice($params)
    {
        $id_order = $params['object']->id_order;
        if (Configuration::get('CUSTOMFIELD_PDF_INVOICE', null, $this->id_shop_group, $this->id_shop) == 1) {
            return $this->getPDFFields($id_order);
        }
    }

    public function getPDFFields($id_order, $where = null)
    {
        if ($id_order && Validate::isLoadedObject($order = new Order($id_order))) {
            $model = new FieldsModel();
            if (!$where) {
                $where = ' and a.show_admin = 1';
            }
            $fields = $model->getOrderFields('val.id_order = ' . (int) $id_order . $where);
            if (isset($fields) && $fields) {
                $customfields_heading = Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id);
                $grouped_fields = array();
                foreach ($fields as $field) {
                    $grouped_fields[$field['field_placement']][] = $field;
                }
                $this->smarty->assign(array(
                    'version' => _PS_VERSION_,
                    'checkout_steps' => $this->order_steps,
                    'grouped_fields' => $grouped_fields,
                    'customfields_heading' => $customfields_heading,
                    'obj_model' => $model,
                )
                );
                return $this->display(dirname(__FILE__), 'views/templates/admin/pdf/pdf_customfields.tpl');
            }
        }
    }

    public function psOld($controller)
    {
        // Get controller
        if (!in_array($controller, array('orderopc', 'order', 'orderdetail', 'history'))) {
            return false;
        }

        $id_lang = $this->context->cookie->id_lang;
        $this->context->controller->addCSS(__PS_BASE_URI__ . 'js/jquery/ui/themes/base/jquery.ui.theme.css', 'all');
        $this->context->controller->addCSS(__PS_BASE_URI__ . 'js/jquery/ui/themes/base/jquery.ui.datepicker.css', 'all');
        $this->context->controller->addCSS(($this->_path) . 'views/css/customfields.css', 'all');
        $this->context->controller->addJS(($this->_path) . 'views/js/customfields.js');
        $this->context->controller->addJqueryUI('ui.datepicker');
        $this->context->controller->addJS(($this->_path) . 'views/js/validate_custom_fields.js');

        $obj_model = new FieldsModel();
        if (Tools::getValue('step') == '1') {
            $summary_fields = $obj_model->getCustomFields('summary', $id_lang);
            $cart_products = array();
            // if (isset($this->context->cart)) {
            //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
            //     $summary_fields = $this->filterFieldsByProducts($summary_fields, $cart_products);
            // }
            $fields = array();
            foreach ($summary_fields as $field) {
                $fields[$field['id_custom_field']] = $field['field_value'];
            }

            $summaFields = FieldsModel::fieldValidate('summary', $fields);
            if (isset($summaFields) && $summaFields) {
                Tools::redirect('order.php');
            }
        }

        $script = '<script type="text/javascript">$(function() {';
        $step = Tools::getValue('step');

        if ($controller == 'orderdetail') {
            $this->context->controller->addJs($this->_path . 'views/js/accordion_slide.js');
            $this->context->controller->addCss($this->_path . 'views/css/accordion_slide.css');
        }

        if ($controller == 'orderopc' || $this->context->controller->php_self == 'order-opc') {
            $script .= $this->summaryJavascript();
            $script .= $this->addressJavascript();
            $script .= $this->shippingJavascript();
            $script .= $this->paymentJavascript();
        } else {
            if ($step == 0) {
                $script .= $this->summaryJavascript();
            } elseif ($step == 1) {
                $script .= $this->addressJavascript();
            } elseif ($step == 2) {
                $script .= $this->shippingJavascript();
            } elseif ($step == 3) {
                $script .= $this->paymentJavascript();
            }
        }

        $script .= 'runScript();';
        $script .= '});</script>';
        return $script;
    }

    public function psNew($controller)
    {
        // Get controller
        if (!in_array($controller, array('cart', 'order', 'orderdetail'))) {
            return false;
        }

        $this->context->controller->registerStylesheet(
            'theme-css',
            'js/jquery/ui/themes/base/jquery.ui.theme.css',
            array('media' => 'all', 'priority' => 50)
        );
        $this->context->controller->registerStylesheet(
            'datepicker-css',
            'js/jquery/ui/themes/base/jquery.ui.datepicker.css',
            array('media' => 'all', 'priority' => 50)
        );
        $this->context->controller->registerStylesheet(
            'customfields_17css',
            'modules/' . $this->name . '/views/css/customfields_17.css',
            array('priority' => 900, 'media' => 'all')
        );

        $this->context->controller->addJqueryUI('ui.datepicker');

        if ($this->pts_is_module) {
            $this->context->controller->registerJavascript(
                'customfieldsjs',
                'modules/' . $this->name . '/views/js/customfields.js',
                array('position' => 'bottom', 'priority' => 1000)
            );
        } else {
            $this->context->controller->registerJavascript(
                'customfields_17js',
                'modules/' . $this->name . '/views/js/customfields_17.js',
                array('position' => 'bottom', 'priority' => 1000)
            );
        }

        $this->context->controller->registerJavascript(
            'validate_custom_fields_17js',
            'modules/' . $this->name . '/views/js/validate_custom_fields_17.js',
            array('position' => 'bottom', 'priority' => 1001)
        );

        $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $base_dir = ($force_ssl == 1) ? _PS_BASE_URL_SSL_ . __PS_BASE_URI__ : _PS_BASE_URL_ . __PS_BASE_URI__;
        $script = '<script type="text/javascript" src="' . $base_dir . 'js/jquery/jquery-1.11.0.min.js"></script>';
        $script .= '<script type="text/javascript">var baseDir = "' . $base_dir . '";$(function() {';

        if ($controller == 'orderdetail') {
            $this->context->controller->addJs($this->_path . 'views/js/accordion_slide.js');
            $this->context->controller->addCss($this->_path . 'views/css/accordion_slide.css');
        }
        if ($controller == 'cart') {
            $script .= $this->summaryJavascript();
        } else {
            $script .= $this->addressJavascript();
            $script .= $this->shippingJavascript();
            $script .= $this->paymentJavascript();
        }

        $script .= 'runScript();';
        $script .= '});</script>';
        return $script;
    }

    protected function summaryJavascript()
    {
        $id_lang = $this->context->cookie->id_lang;
        $obj_model = new FieldsModel();
        $summary_fields = $obj_model->getCustomFields('summary', $id_lang);
        $cart_products = array();
        // if (isset($this->context->cart)) {
        //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
        //     $summary_fields = $this->filterFieldsByProducts($summary_fields, $cart_products);
        // }
        if (!empty($summary_fields)) {
            return 'orderStep(\'summary\');';
        }
        return '';
    }

    public function addressJavascript()
    {
        $id_lang = $this->context->cookie->id_lang;
        $obj_model = new FieldsModel();
        $billing_fields = $obj_model->getCustomFields('billing', $id_lang);
        $delivery_fields = $obj_model->getCustomFields('delivery', $id_lang);

        $cart_products = array();
        // if (isset($this->context->cart)) {
        //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
        //     $billing_fields = $this->filterFieldsByProducts($billing_fields, $cart_products);
        //     $delivery_fields = $this->filterFieldsByProducts($delivery_fields, $cart_products);
        // }

        $js = '';
        $this->context->smarty->assign('version', _PS_VERSION_);
        $this->context->smarty->assign('action_controller', $this->ajaxController);
        if (!empty($billing_fields)) {
            $js .= 'orderStep(\'billing\');';
        }

        if (!empty($delivery_fields)) {
            $js .= 'orderStep(\'delivery\');';
        }

        $billing_html = '';
        $shipping_html = '';
        if (isset($billing_fields) && $billing_fields) {
            // billing fields
            $summary_fields_values = array();
            foreach ($billing_fields as $sf) {
                $summary_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
            }

            $model = new FieldsModel();
            if (isset($billing_fields) && !empty($billing_fields)) {
                foreach ($billing_fields as &$field) {
                    if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                        $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                    }
                    else {
                        $field['sub_heading'] = '';
                    }
                }
            }
            $this->context->smarty->assign('model', $model);
            $this->context->smarty->assign('summary_fields_values', $summary_fields_values);
            $this->context->smarty->assign('summary_fields', $billing_fields);
            $this->context->smarty->assign('type', 'billing');
            $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));

            $billing_html = '<div style=\'float:left;width: 345px !important;\'>' . addslashes($this->display(__FILE__, 'summary.tpl')) . '</div>';
            $billing_html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $billing_html);
        }
        if (isset($delivery_fields) && $delivery_fields) {
            // delivery fields
            $summary_fields_values = array();

            foreach ($delivery_fields as $sf) {
                $summary_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
            }

            $model = new FieldsModel();
            if (isset($delivery_fields) && !empty($delivery_fields)) {
                foreach ($delivery_fields as &$field) {
                    if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                        $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                    }
                    else {
                        $field['sub_heading'] = '';
                    }
                }
            }
            $this->context->smarty->assign('model', $model);
            $this->context->smarty->assign('summary_fields_values', $summary_fields_values);
            $this->context->smarty->assign('summary_fields', $delivery_fields);
            $this->context->smarty->assign('type', 'delivery');
            $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));

            $shipping_html = '<div class=\'fmm_cf_shipping_block\' style=\'float:right;width: 345px !important;\'>' . addslashes($this->display(__FILE__, 'summary.tpl')) . '</div>';
            $shipping_html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $shipping_html);
        }

        if ($this->zelgra_is_module) {
            $js .= 'addAddress("' . $billing_html . $shipping_html . '<div style=\'clear:both;\'></div>", true, "zelgra");';
        } elseif ($this->pts_is_module) {
            $js .= 'addAddress("' . $billing_html . $shipping_html . '<div style=\'clear:both;\'></div>", true, "pts");';
        } else {
            $js .= 'addAddress("' . $billing_html . $shipping_html . '<div style=\'clear:both;\'></div>", false);';
        }
        return $js;
    }

    protected function shippingJavascript()
    {
        $id_lang = $this->context->cookie->id_lang;
        $obj_model = new FieldsModel();
        $shipping_fields = $obj_model->getCustomFields('shipping', $id_lang);
        $cart_products = array();
        // if (isset($this->context->cart)) {
        //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
        //     $shipping_fields = $this->filterFieldsByProducts($shipping_fields, $cart_products);
        // }
        $js = '';
        if (isset($shipping_fields) && $shipping_fields) {
            $js .= 'orderStep(\'shipping\');';
            // delivery fields
            $summary_fields_values = array();
            foreach ($shipping_fields as $sf) {
                $summary_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
            }

            $model = new FieldsModel();
            if (isset($shipping_fields) && !empty($shipping_fields)) {
                foreach ($shipping_fields as &$field) {
                    if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                        $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                    }
                    else {
                        $field['sub_heading'] = '';
                    }
                }
            }
            $this->context->smarty->assign('model', $model);
            $this->context->smarty->assign('version', _PS_VERSION_);
            $this->context->smarty->assign('summary_fields_values', $summary_fields_values);
            $this->context->smarty->assign('summary_fields', $shipping_fields);
            $this->context->smarty->assign('type', 'shipping');
            $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));
            $this->context->smarty->assign('action_controller', $this->ajaxController);
            $shipping_html = addslashes($this->display(__FILE__, 'summary.tpl'));
            $shipping_html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $shipping_html);

            if ($this->zelgra_is_module) {
                $js .= 'addCarrier(\'' . $shipping_html . '\', true, "zelgra");';
            } elseif ($this->pts_is_module) {
                $js .= 'addCarrier(\'' . $shipping_html . '\', true, "pts");';
            } else {
                $js .= 'addCarrier(\'' . $shipping_html . '\', false);';
            }
        }
        return $js;
    }

    protected function paymentJavascript()
    {
        $id_lang = $this->context->cookie->id_lang;
        $obj_model = new FieldsModel();
        $payment_fields = $obj_model->getCustomFields('payment', $id_lang);
        $cart_products = array();
        $js = '';
        // if (isset($this->context->cart)) {
        //     $cart_products = $this->context->cart->getProducts(false, false, null, false);
        //     $payment_fields = $this->filterFieldsByProducts($payment_fields, $cart_products);
        // }
        if (isset($payment_fields) && $payment_fields) {
            $js .= 'orderStep(\'payment\');';

            if ($this->zelgra_is_module || $this->pts_is_module) {
                $payment_fields_values = array();
                foreach ($payment_fields as $sf) {
                    $payment_fields_values[$sf['id_custom_field']] = $obj_model->getCustomFieldsValues($sf['id_custom_field']);
                }

                $model = new FieldsModel();
                if (isset($payment_fields) && !empty($payment_fields)) {
                    foreach ($payment_fields as &$field) {
                        if (isset($field['id_heading']) && (int)$field['id_heading'] > 0) {
                            $field['sub_heading'] = $model->getSubHeading($field['id_heading'], $id_lang);
                        }
                        else {
                            $field['sub_heading'] = '';
                        }
                    }
                }
                $this->context->smarty->assign('model', $model);
                $this->context->smarty->assign('version', _PS_VERSION_);
                $this->context->smarty->assign('summary_fields_values', $payment_fields_values);
                $this->context->smarty->assign('summary_fields', $payment_fields);
                $this->context->smarty->assign('type', 'payment');
                $this->context->smarty->assign('CUSTOMFIELD_BLOCK_HEADING', Configuration::get('CUSTOMFIELD_BLOCK_HEADING', $this->context->language->id));
                $this->context->smarty->assign('action_controller', $this->ajaxController);
                $payment_html = addslashes($this->display(__FILE__, 'summary.tpl'));
                $payment_html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $payment_html);

                $mod = ($this->zelgra_is_module) ? 'zelgra' : (($this->pts_is_module) ? 'pts' : '');
                $js .= 'addPayment(\'' . $payment_html . '\', true, "' . $mod . '");';
            }
        }
        return $js;
    }

    private function filterFieldsByProducts(&$fields = array(), &$products = array())
    {
        $model = new FieldsModel();
        $category_products = array();
        if (isset($fields) && $fields && isset($products) && $products) {
            // getting ids
            foreach ($products as $key => $product) {
                if (isset($product['id_product'])) {
                    $products[$key] = $product['id_product'];
                }
            }

            //filtering fields
            if (isset($products) && $products) {
                foreach ($fields as $key => $field) {
                    $exists = array();
                    if (!empty($field['products'])) {
                        $selected_products = explode(',', $field['products']);
                    } else {
                        $selected_products = array();
                    }
                    $id_category = (int) $model->needleCheckCategory($field['id_custom_field']);
                    if ($id_category > 0) {
                        $category = new Category($id_category, (int) $this->context->language->id);
                        $category_products = $category->getProducts((int) $this->context->language->id, 1, 100);
                        if (!empty($category_products)) {
                            foreach ($category_products as $l_key => $product) {
                                if (isset($product['id_product'])) {
                                    $category_products[$l_key] = $product['id_product'];
                                }
                            }
                        }
                        $selected_products = array_merge($selected_products, $category_products);
                    }
                    if (!empty($selected_products)) {
                        $exists = array_intersect($products, $selected_products);
                        if (!$exists) {
                            unset($fields[$key]);
                        }
                    }
                }
            }
            return $fields;
        } else {
            return false;
        }
    }

    public function getUnserialize($field)
    {
        if (isset($field) && isset($field['field_type']) && isset($field['field_value'])) {
            $field_type = $field['field_type'];
            $field_value = $field['field_value'];
            if ($field_value && in_array($field_type, array('multiselect', 'radio', 'checkbox', 'message'))) {
                $value = unserialize($field_value);
                return join(', ', $value);
            }
            return $field_value;
        }
    }

    /**
     * GDPR Compliance Hooks
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $cartList = Cart::getCustomerCarts($customer['id'], true);
            if (isset($cartList) && $cartList) {
                foreach ($cartList as $cart) {
                    FieldsModel::deleteByCart((int) $cart['id_cart']);
                }
                return json_decode(true);
            }
            return json_encode($this->l('Custom Fields : Unable to delete customer data.'));
        }
    }

    public function hookActionExportGDPRData($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $id_lang = $this->context->customer->id_lang;
            $allFields = FieldsModel::getFieldLabels($id_lang);
            $cartList = Cart::getCustomerCarts($customer['id'], true);
            $customeFieldsData = array();
            if (isset($cartList) && $cartList) {
                foreach ($cartList as $cart) {
                    $customeFieldsData[$cart['id_cart']] = FieldsModel::getFieldsData('id_cart = ' . (int) $cart['id_cart']);
                }
            }
            $customerData = array();
            $customfieldsData = array();
            if (isset($allFields) && $allFields) {
                foreach ($allFields as $index => $fields) {
                    if (isset($customeFieldsData) && $customeFieldsData) {
                        foreach ($customeFieldsData as $key => $cf) {
                            $customerData[$key][$this->l('Cart ID')] = $key;
                            if (isset($cf[$fields['id_custom_field']])) {
                                if ($fields['field_type'] == 'attachment') {
                                    $value = Tools::getShopDomain() . DIRECTORY_SEPARATOR . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', $cf[$fields['id_custom_field']]['field_value']);
                                    $customerData[$key][$fields['field_name']] = $value;
                                } else {
                                    $customerData[$key][$fields['field_name']] = $cf[$fields['id_custom_field']]['field_value'];
                                }
                            } else {
                                $customerData[$key][$fields['field_name']] = '--';
                            }
                        }
                    }
                }
            }
            if (isset($customerData) && $customerData) {
                foreach ($customerData as $cdata) {
                    array_push($customfieldsData, $cdata);
                }
            }
            if (isset($customfieldsData) && $customfieldsData) {
                return json_encode($customfieldsData);
            }
            return json_encode($this->l('Custom Fields : There is no data to export.'));
        }
        return json_encode($this->l('Custom Fields : Unable to export customer data.'));
    }

    /**
     * get checkout steps
     * @return array
     */
    protected function getCheckoutSteps()
    {
        return array(
            'summary' => $this->l('Summary Information'),
            'delivery' => $this->l('Shipping Address'),
            'billing' => $this->l('Billing Address'),
            'shipping' => $this->l('Shipping Information'),
            'payment' => $this->l('Payment Information'),
        );
    }

    /**
     * return module text translations
     * @return array
     */
    protected function getCfTranslations()
    {
        return array(
            'invalid' => $this->l('is invalid.'),
            'required' => $this->l('is required.'),
            'type' => $this->l('file type is invalid.'),
            'size' => $this->l('size exceeds the limit.'),
            'upload_error' => $this->l('An error occurred while attempting to upload the file.'),
            'update_success' => $this->l('Registration fields updated successfully.'),
        );
    }

    /**
     * return translatable file error messages
     * @return array
     */
    protected function getFileErrors()
    {
        return array(
            'UPLOAD_ERR_INI_SIZE' => $this->l('The uploaded file exceeds the upload_max_filesize directive in php.ini'),
            'UPLOAD_ERR_FORM_SIZE' => $this->l('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'),
            'UPLOAD_ERR_PARTIAL' => $this->l('The uploaded file was only partially uploaded.'),
            'UPLOAD_ERR_NO_FILE' => $this->l('No file was uploaded.'),
            'UPLOAD_ERR_NO_TMP_DIR' => $this->l('Missing a temporary folder.'),
            'UPLOAD_ERR_CANT_WRITE' => $this->l('Failed to write file to disk.'),
            'UPLOAD_ERR_EXTENSION' => $this->l('A PHP extension stopped the file upload.'),
        );
    }

    /**
     * check if 3rd party opc modules installed
     * @param string $opc_module
     * @param string $installed_opc
     * @param string $uninstalled_opc
     * @return void
     */
    protected function checkOnestepModules($opc_module, $installed_opc, $uninstalled_opc)
    {
        if (Module::isInstalled($opc_module) && Module::isEnabled($opc_module)) {
            $opcModule = Module::getInstanceByName($opc_module);
            $isOpcEnabled = Configuration::get('PS_ORDER_PROCESS_TYPE', 0);
            if ($opcModule instanceof Module) {
                $this->{$installed_opc} = true;
                if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<') && !$isOpcEnabled) {
                    $this->{$uninstalled_opc} = false;
                }
            }
        }
    }

    /**
     * get old-new table names
     * @return array
     */
    public function getNewTableNames()
    {
        return array(
            'fmm_custom_fields' => 'custom_field',
            'fmm_custom_fields_lang' => 'custom_field_lang',
            'fmm_custom_fields_shop' => 'custom_field_shop',
            'fmm_custom_userdata' => 'custom_field_userdata',
            'fmm_custom_fields_group' => 'custom_field_group',
            'fmm_custom_fields_values' => 'custom_field_values',
            'fmm_custom_fields_categories' => 'custom_field_categories',
        );
    }

    /**
     * reset tab menu
     * @return boolean
     */
    public function resetMenuTab()
    {
        $reset = true;
        if ($this->removeTab()) {
            $reset &= $this->addTab();
        }
        return $reset;
    }
}
