<?php
/**
* Restrict Payment Methods
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    FMM Modules
* @copyright Copyright 2022 © FMM Modules All right reserved
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* @category  front_office_features
* @package   restrictpaymentmethods
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/Rpm.php');
class RestrictPaymentMethods extends Module
{
    public function __construct()
    {
        $this->name = 'restrictpaymentmethods';
        $this->tab = 'administration';
        $this->version = '2.0.3';
        $this->author = 'FMM Modules';
        $this->bootstrap = true;
        $this->module_key = 'b1431bc54d747dccaefca6ceae963dbf';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';
        $this->ps_versions_compliancy = array(
            'min' => '1.6.0.14',
        );
        parent::__construct();
        
        $this->displayName = $this->l('Restrict Payment Methods');
        $this->description = $this->l('Disable payment methods for specific cart total ranges, products or categories.');
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        return (parent::install()
                && $this->addTab()
                && $this->moveFiles()
                && $this->registerHook('actionAdminControllerSetMedia')
                && $this->registerHook('displayHeader')
                && $this->registerHook('displayPaymentTop')
                && $this->registerHook('displayProductButtons'));
    }
    public function hookDisplayHeader()
    {
        return $this->context->controller->addCss($this->local_path . 'views/css/' . $this->name . '.css');
    }
    
    public function hookActionAdminControllerSetMedia()
    {
        if (get_class($this->context->controller) == 'AdminRestrictPaymentMethodController') {
            $this->context->controller->addCSS($this->_path.'views/css/bo_'.$this->name.'.css');
            $this->context->controller->addJS($this->_path.'views/js/bo_'.$this->name.'.js');
            if (Tools::version_compare(_PS_VERSION_, '1.7.8.0', '>=') == true) {
                $this->context->controller->addJS($this->_path.'views/js/bo_hideandshow_ps1780.js');
            }
            else {
                $this->context->controller->addJS($this->_path.'views/js/bo_hideandshow.js');
            }
        }
    }
    
    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return ($this->removeTab() && parent::uninstall() && $this->delFiles());
    }

    public function moveFiles()
    {
        if (true === Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            @mkdir(_PS_OVERRIDE_DIR_.'classes');
            @mkdir(_PS_OVERRIDE_DIR_.'classes/checkout');
        }
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            Tools::copy(_PS_MODULE_DIR_.'restrictpaymentmethods/includes/PaymentOptionsFinder.php', _PS_OVERRIDE_DIR_.'classes/checkout/PaymentOptionsFinder.php');
        }
        else {
            Tools::copy(_PS_MODULE_DIR_.'restrictpaymentmethods/includes/Hook.php', _PS_OVERRIDE_DIR_.'classes/Hook.php');
            if (file_exists(_PS_CACHE_DIR_.'class_index.php')) {
                rename(_PS_CACHE_DIR_.'class_index.php', _PS_CACHE_DIR_.'class_index'.rand(pow(10, 3 - 1), pow(10, 3) - 1).'.php');
            }
        }
        return true;
    }
    
    public function delFiles()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            unlink(_PS_OVERRIDE_DIR_.'classes/checkout/PaymentOptionsFinder.php');
        }
        else {
            unlink(_PS_OVERRIDE_DIR_.'classes/Hook.php');
            if (file_exists(_PS_CACHE_DIR_.'class_index.php')) {
                rename(_PS_CACHE_DIR_.'class_index.php', _PS_CACHE_DIR_.'class_index'.rand(pow(10, 3 - 1), pow(10, 3) - 1).'.php');
            }
        }
        return true;
    }
    
    private function addTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminRestrictPaymentMethods';
        $tab->id_parent = 0;
        $tab->module = $this->name;
        $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->displayName;
        $tab->add();

        $subtab = new Tab();
        $subtab->class_name = 'AdminRestrictPaymentMethod';
        $subtab->id_parent = Tab::getIdFromClassName('AdminRestrictPaymentMethods');
        $subtab->module = $this->name;
        $subtab->name[(int)Configuration::get('PS_LANG_DEFAULT')] = $this->displayName;
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $subtab->icon = 'lock';
        }
        $subtab->add();
        return true;
    }

    private function removeTab()
    {
        $res = true;
        $id_tab = Tab::getIdFromClassName('AdminRestrictPaymentMethods');
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $res &= $tab->delete();
        }

        $id_tab1 = Tab::getIdFromClassName('AdminRestrictPaymentMethod');
        if ($id_tab1 != 0) {
            $tab = new Tab($id_tab1);
            $res &= $tab->delete();
        }
        return $res;
    }

    public function getContent()
    {
        $btn_link = $this->context->link->getAdminLink('AdminRestrictPaymentMethod');
        $this->context->smarty->assign('btn_link', $btn_link);
        $this->html = $this->display(__FILE__, 'views/templates/hook/info.tpl');
        $this->button = $this->display(__FILE__, 'views/templates/hook/button.tpl');
        return $this->postProcess().$this->html.$this->button.$this->renderForm();
    }
    
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                    'type' => 'switch',
                    'label' => $this->l('Enable Product Page Block'),
                    'name' => 'RPM_STATE_PPB',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                            ),
                        array(
                            'id' => 'rpm_off',
                            'value' => 0,
                            'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Product Block Heading'),
						'name' => 'RPM_HEADING',
						'desc' => $this->l('Enter Heading for the product page block or leave empty.')
					),
                    array(
                    'type' => 'switch',
                    'label' => $this->l('Show Message on Payment area?'),
                    'name' => 'RPM_STATE_SHOW_MSG',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_msg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                            ),
                        array(
                            'id' => 'rpm_msg_off',
                            'value' => 0,
                            'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
						'type' => 'textarea',
                        'label' => $this->l('Message for Payment area'),
                        'name' => 'RPM_PAYMENT_MSG',
                        'lang' => true,
                        'cols' => 60,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                        'hint' => $this->l('Invalid characters:').' <>;=#{}'
					),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit'.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }
    
    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();
        foreach ($languages as $lang) {
			$fields['RPM_HEADING'][$lang['id_lang']] = Tools::getValue('RPM_HEADING_'.$lang['id_lang'], Configuration::get('RPM_HEADING', $lang['id_lang']));
            $fields['RPM_PAYMENT_MSG'][$lang['id_lang']] = Tools::getValue('RPM_PAYMENT_MSG_'.$lang['id_lang'], Configuration::get('RPM_PAYMENT_MSG', $lang['id_lang']));
		}
        $fields['RPM_STATE_PPB'] = (int)Configuration::get('RPM_STATE_PPB');
        $fields['RPM_STATE_SHOW_MSG'] = (int)Configuration::get('RPM_STATE_SHOW_MSG');
        return $fields;
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submit'.$this->name)) {
            $languages = Language::getLanguages(false);
			$values = array();
            foreach ($languages as $lang) {
				$values['RPM_HEADING'][$lang['id_lang']] = Tools::getValue('RPM_HEADING_'.$lang['id_lang']);
                $values['RPM_PAYMENT_MSG'][$lang['id_lang']] = Tools::getValue('RPM_PAYMENT_MSG_'.$lang['id_lang']);
			}
            Configuration::updateValue('RPM_STATE_PPB', (int)Tools::getValue('RPM_STATE_PPB'));
            Configuration::updateValue('RPM_STATE_SHOW_MSG', (int)Tools::getValue('RPM_STATE_SHOW_MSG'));
            Configuration::updateValue('RPM_HEADING', $values['RPM_HEADING']);
            Configuration::updateValue('RPM_PAYMENT_MSG', $values['RPM_PAYMENT_MSG'], true);
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
        return '';
    }
    
    public function hookDisplayPaymentTop($params)
    {
        $show_msg = (int)Configuration::get('RPM_STATE_SHOW_MSG');
        $content = Configuration::get('RPM_PAYMENT_MSG', $this->context->language->id);
        if ($show_msg > 0) {
            $this->context->smarty->assign(array(
                                                 'rpm_payment_msg' => $content
                                                 ));
            return $this->display(__FILE__, 'payment.tpl');
        }
    }
    
    public function hookDisplayProductButtons($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        if (true == Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $payments = PaymentOptionsFinder::getInstalledPaymentModules($id_product);
        }
        else {
            $payments = Hook::getInstalledPaymentModules($id_product);
        }
        
        $show_blk = (int)Configuration::get('RPM_STATE_PPB');
        $blk_heading = Configuration::get('RPM_HEADING', $this->context->language->id);
        $this->context->smarty->assign(array(
                                             'payments_meths' => $payments,
                                             'rpm_show' => $show_blk,
                                             'rpm_heading' => $blk_heading
                                             ));
        if (true == Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return $this->display(__FILE__, 'v1_7/product.tpl');
        } else {
            return $this->display(__FILE__, 'v1_6/product.tpl');
        }
    }
}