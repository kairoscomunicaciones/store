<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer tohttp://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2015 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 */

class OneClickCheckout extends Module
{

    public function __construct()
    {
        $this->name = 'oneclickcheckout';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'Knowband';
        $this->need_instance = 0;
        $this->module_key = '3f96ad8299076c1faae227334cec4ab5';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('One Click Checkout');
        $this->description = $this->l('This is the module for facilitate user to purchase particular product.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('oneclickcheckout')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
                !$this->registerHook('displayProductButtons') ||
                !$this->registerHook('actionValidateOrder') ||
                !$this->registerHook('displayHeader')
        ) {
            return false;
        }
        $defaultsettings = $this->getDefaultSettings();
        $defaultsettings = serialize($defaultsettings);
        Configuration::updateValue('oneclickcheckout', $defaultsettings);
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
                !$this->unregisterHook('displayProductButtons') ||
                !$this->unregisterHook('actionValidateOrder') ||
                !$this->unregisterHook('displayHeader')
        ) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('oneclickcheckout')) {
            $formvalue = Tools::getValue('oneclickcheckout');
            $content = serialize($formvalue);
            Configuration::updateValue('oneclickcheckout', $content);
            $output .= $this->displayConfirmation($this->l('Configuration has been saved successfully.'));
        }
        return $output . $this->displayForm();
    }

    private function defaultTitles($tag)
    {
        $titles = array(
            $this->l('Share this product with your friends'),
        );

        return $titles[$tag];
    }

    private function getDefaultSettings()
    {
        $settings = array(
            'enable' => 0,
            'title_1_style' => array(
                'bold' => 0,
                'italic' => 0,
                'color' => '#000000'
            )
        );
        return $settings;
    }

    public function displayForm()
    {
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'oneclickcheckout/views/css/occ_css.css');
        $this->context->controller->addJs(_PS_MODULE_DIR_.'oneclickcheckout/views/js/occ_js.js');
        $this->context->controller->addJs(_PS_MODULE_DIR_.'oneclickcheckout/views/js/velovalidation.js');
        if (version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
            $enable_button_type = 'radio';
        } else {
            $enable_button_type = 'switch';
        }
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('General Settings'),
            ),
            'input' => array(
                array(
                    'type' => $enable_button_type,
                    'label' => $this->l('Enable/Disable'),
                    'name' => 'oneclickcheckout[choose]',
                    'hint' => $this->l('Enable/Disable module functionality at the front end'),
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'label' => $this->l('Background Color'),
                    'type' => 'color',
                    'class' => 'general_tab',
                    'name' => 'oneclickcheckout[background]',
                    'hint' => $this->l('Choose the background color for the `Buy Now` button at the front end')
                ),
                array(
                    'label' => $this->l('Text Color'),
                    'type' => 'color',
                    'class' => 'general_tab',
                    'name' => 'oneclickcheckout[text]',
                    'hint' => $this->l('Choose the text color for the `Buy Now` button at the front end')
                ),
                array(
                    'label' => $this->l('Border Color'),
                    'type' => 'color',
                    'class' => 'general_tab',
                    'name' => 'oneclickcheckout[border]',
                    'hint' => $this->l('Choose the border color for the `Buy Now` button at the front end')
                ),
            ),
            'buttons' => array(
                array(
                    'title' => $this->l('Submit'),
                    'type' => 'submit',
                    'icon' => 'process-icon-save',
                    'class' => 'pull-right kbocc_config_submit_button',
                    'id' => 'save_occ_config'
                )
            )
        );

        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $form_data = Tools::getValue('oneclickcheckout');
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list'),
            )
        );
        $config = Configuration::get('oneclickcheckout');
        $this->oneclickcheckout_settings = Tools::unSerialize($config);
        if (!empty($this->oneclickcheckout_settings)) {
            if (isset($this->oneclickcheckout_settings['choose'])) {
                $reg_data = $this->oneclickcheckout_settings;
            } else {
                $reg_data = array(
                    'choose' => '',
                    'background' => '#2fb5d2',
                    'text' => '#ffffff',
                    'border' => '#2fb5d2'
                );
            }
        } else {
            $reg_data = null;
        }
        $helper->fields_value['oneclickcheckout[choose]'] = isset($form_data['choose'])
                ? $form_data['choose'] :Tools::htmlentitiesDecodeUTF8($reg_data['choose']);
        $helper->fields_value['oneclickcheckout[background]'] = isset($form_data['background'])
                ? $form_data['background'] : Tools::htmlentitiesDecodeUTF8($reg_data['background']);
        $helper->fields_value['oneclickcheckout[text]'] = isset($form_data['text'])
                ? $form_data['text'] : Tools::htmlentitiesDecodeUTF8($reg_data['text']);
        $helper->fields_value['oneclickcheckout[border]'] = isset($form_data['border'])
                ? $form_data['border'] : Tools::htmlentitiesDecodeUTF8($reg_data['border']);
        return $helper->generateForm($fields_form).$this->renderErrorTranslationsTemplate();
    }

    public function renderErrorTranslationsTemplate()
    {
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.'oneclickcheckout/views/templates/admin/translations_error_assign.tpl'
        );
    }
    
    public function hookDisplayProductButtons($params)
    {
        $plugin_data = Tools::unSerialize(Configuration::get('oneclickcheckout'));
        if ($plugin_data && (isset($plugin_data['choose']) && $plugin_data['choose'] == 1)) {
            $yes = 'yes';
            if ((Configuration::get('PS_CATALOG_MODE')) == '1') {
                $this->smarty->assign(array(
                    'catalog_mode' => $yes
                ));
            }
            $link = $this->context->link->getModuleLink(
                'oneclickcheckout',
                'oneclickcheckout',
                array(),
                (bool)Configuration::get('PS_SSL_ENABLED')
            );
        
            $this->smarty->assign(array(
                'background' => $plugin_data['background'],
                'text' => $plugin_data['text'],
                'border' => $plugin_data['border'],
                'link' => $link
            ));
            return $this->display(__FILE__, 'views/templates/hook/show_button.tpl');
        }
    }
    
    public function hookActionValidateOrder($params)
    {
        $plugin_data = Tools::unSerialize(Configuration::get('oneclickcheckout'));
        if ($plugin_data && (isset($plugin_data['choose']) && $plugin_data['choose'] == 1)) {
            if (isset($params['cart']->id)) {
                if (isset($this->context->cookie->new_cart_id) && $this->context->cookie->new_cart_id != '') {
                    if ((int) $params['cart']->id == (int) $this->context->cookie->new_cart_id) {
                        $this->context->cookie->__unset('new_cart_id');
                    }
                }
            }
        }
    }

    public function notconfirmorder()
    {
        if ((int) $this->context->cookie->old_cart_id != 0) {
            $id_cart = $this->context->cookie->old_cart_id;
            $new_products = array();
            if (isset($this->context->cookie->new_cart_id) && $this->context->cookie->new_cart_id != '') {
                $new_id_cart = (int) $this->context->cookie->new_cart_id;
                $new_cart_obj = new Cart($new_id_cart);
                $new_products = $new_cart_obj->getProducts();
                unset($new_cart_obj);
            }
            $this->context->cart = new Cart((int) $id_cart);
            if (is_array($new_products) && count($new_products) > 0) {
                foreach ($new_products as $np) {
                    $this->context->cart->updateQty(
                        (int) $np['quantity'],
                        (int) $np['id_product'],
                        (int) $np['id_product_attribute'],
                        false,
                        'up'
                    );
                }
            }
            $this->context->cart->save();
            if ($this->context->cart->id) {
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
            }
            $this->context->cart->autosetProductAddress();
            if ($this->context->cookie->on_order_page_set == 'NO') {
                $this->context->cookie->__unset('old_cart_id');
                $this->context->cookie->__unset('on_order_page_set');
                $this->context->cookie->__unset('new_cart_id');
            }
        }
    }

    public function hookDisplayHeader()
    {
        $plugin_data = Tools::unSerialize(Configuration::get('oneclickcheckout'));
        if ($plugin_data && (isset($plugin_data['choose']) && $plugin_data['choose'] == 1)) {
            $this->context->controller->addJS($this->_path . 'views/js/occ_frontjs.js');
            if ((int) $this->context->cookie->old_cart_id != 0) {
                $heystack = get_class($this->context->controller);
                $a = "ModuleFrontController";
                $b = "AddressController";
                $c = "OrderController";
                $d = "AuthController";
                $e = "CartController";
                if (!(($heystack == "OrderController")||
                        ($heystack == "AuthController")||
                        ($heystack == "CartController")||
                        ($heystack == "AddressController")||
                        ($heystack == "ModuleFrontController")||
                        (strpos($heystack, $a)) ||
                        (strpos($heystack, $b)) ||
                        (strpos($heystack, $c)) || (strpos($heystack, $d))||
                        (strpos($heystack, $e)))) {
                    $this->context->cookie->on_order_page_set = 'NO';
                    $this->notconfirmorder();
                }
            }
        }
    }
}
