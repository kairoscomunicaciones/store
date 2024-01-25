<?php
/**
 * 2007-2022 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author ETS-Soft <etssoft.jsc@gmail.com>
 * @copyright  2007-2022 ETS-Soft
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

if (!defined('_PS_VERSION_'))
    exit;

/**
 * Class AdminOrderManagerExportsController
 * @property \Ets_ordermanager $module;
 */
class AdminOrderManagerExportsController extends ModuleAdminController
{
    public $mod_path;
    public $is17 = false;
    public function __construct()
    {
        $this->table = 'ets_export_order_rule';
        $this->bootstrap = true;
        parent::__construct();
        if(Tools::isSubmit('searchCustomer'))
            $this->_searchCustomer();
        $this->mod_path = $this->module->getLocalPath();
        $this->_conf += array(
            101 => $this->l('The email has been sent successfully to receivers'),
            102 => $this->l('Save files to web directory successfully'),
            103 => $this->l('Send via FTP successfully')
        );
        $this->field_export_default = 'o.id_order,o.reference,c.firstname,c.lastname,new_client,c.email,phone,cl.name,address,o.total_paid_tax_incl,osl.name,o.invoice_number,ca.name,o.total_shipping_tax_incl,o.delivery_number,op.payment_method,o.date_add';
        $controller = Tools::getValue('controller');
        $this->controller_name = $controller && Validate::isControllerName($controller) ?  htmlentities($controller) :'';
        $this->context->smarty->assign(
            array(
                'token_request' => md5($this->module->id),
                'module_link' => $this->context->link->getAdminLink('AdminModules').'&configure=ets_ordermanager&module_name=ets_ordermanager',
                'ETS_ODE_USE_CRONJOB' => Configuration::get('ETS_ODE_USE_CRONJOB'),
            )
        );
        if(version_compare(_PS_VERSION_, '1.7.0.0', '>='))
        {

            $this->module->_postOrder();
        }
    }
    public function _searchCustomer()
    {
        $query = Tools::getValue('q', false);
        if (!$query OR $query == '' OR Tools::strlen($query) < 1 && Validate::isCleanHtml($query))
            die();
        $sql = 'SELECT c.`id_customer`, c.`firstname`, c.lastname,c.email
        		FROM `' . _DB_PREFIX_ . 'customer` c
        		WHERE (c.lastname LIKE \'%' . pSQL($query) . '%\' OR c.firstname LIKE \'%' . pSQL($query) . '%\' OR c.email LIKE \'%' . pSQL($query) . '%\')';
        $items = Db::getInstance()->executeS($sql);
        if ($items)
            foreach ($items AS $item) {
                echo (int)$item['id_customer'] . '|' . trim($item['firstname']) . '|' . trim($item['lastname']) . '|' . $item['email'] . "\n";
            }
        die();
    }
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->context->controller->addJqueryPlugin('autocomplete');
        $link_css = $this->mod_path.'views/css/';
        $link_js = $this->mod_path.'views/js/';
        $this->addCSS(array(
            $link_css.'admin.css',
        ), 'all');
        $this->addJS(array(
            $link_js.'admin.js',
        ));
    }

    public function currentIndex($arguments = array())
    {
        return $this->context->link->getAdminLink($this->controller_name, (isset($arguments['token'])? $arguments['token'] : true))
            . (isset($arguments['conf']) && $arguments['conf']? '&conf='.(int)$arguments['conf'] : '')
            . (isset($arguments['id']) && $arguments['id']? '&id_ets_export_order_rule='.(int)$arguments['id'] : '');
    }

    public function postProcess()
    {
        parent::postProcess();
       if (($action = Tools::getValue('action')) && $action == 'updateFieldsOrdering') {
            $exported_fields = '';
            if (($fields = Tools::getValue('fields')) && Ets_ordermanager::validateArray($fields)) {
                foreach ($fields as $field) {

                    $exported_fields .= str_replace(array(' ', '+'), '_', $field) . ',';
                }
                die(trim($exported_fields, ','));
            }
        }
        if (($id = (int)Tools::getValue('id_ets_export_order_rule')) && ($action = Tools::isSubmit('action')) && $action == 'submitExportOrder') {
            $this->module->actionExportOrSendOrder(array(
                'id' => $id
            ));
        } elseif (Tools::isSubmit('submitSendMail') && ($id = (int)Tools::getValue('id_ets_export_order_rule'))) {
            $export_orders = new Ode_export($id, $this->context->language->id);
            if ($export_orders->receivers_mail && ($receivers_mail = explode(',', $export_orders->receivers_mail))) {
                foreach ($receivers_mail as $email) {
                    if (!Validate::isEmail(trim($email))) {
                        $this->errors[] = $email . ' ' . $this->l('is not email');
                    }
                }
            } else {
                $this->errors[] = $this->l('Receiver email is invalid');
            }
            if (!$this->errors) {
                $this->module->actionExportOrSendOrder(array(
                    'obj' => $export_orders,
                    'sendmail' => true,
                ));
                if (!$this->errors)
                    Tools::redirectAdmin($this->currentIndex(array('conf' => 101)));
            }
        } elseif (Tools::isSubmit('submitSendFTP') && $id) {
            $this->module->actionExportOrSendOrder(array(
                'id' => $id,
                'schedule' => 'ftp',
            ));
            if (!$this->module->_errors)
                Tools::redirectAdmin($this->currentIndex(array('conf' => 103)));
                
        } elseif (Tools::isSubmit('submitSaveFile') && $id) {
            $this->module->actionExportOrSendOrder(array(
                'id' => $id,
                'schedule' => 'local',
            ));
            if (!$this->module->_errors)
                Tools::redirectAdmin($this->currentIndex(array('conf' => 102)));
        } elseif (Tools::isSubmit('saveOrderExport')) {
            if ($id_ets_export_order_rule = $this->module->_postExporter()) {
                if(Tools::isSubmit('saveOrderExportAndExport'))
                {
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrderManagerExports').'&id_ets_export_order_rule='.$id_ets_export_order_rule.'&action=submitExportOrder');
                }
                Tools::redirectAdmin($this->currentIndex(array(
                    'conf' => 102,
                    'id' => (int)$id_ets_export_order_rule,
                )));
            }
        } elseif (Tools::isSubmit('change_enabled') && $id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule')) {
            $change_enabled = (int)Tools::getValue('change_enabled');
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'ets_export_order_rule` SET active="' . (int)$change_enabled . '" WHERE id_ets_export_order_rule=' . (int)$id_ets_export_order_rule);
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrderManagerExports', true) . '&conf=5');
        } elseif (Tools::isSubmit('del') && ($del = Tools::getValue('del')) && $del == 'yes' && ($id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule'))) {
            $paymentmethod = new Ode_export($id_ets_export_order_rule);
            $paymentmethod->delete();
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrderManagerExports', true) . '&conf=2');
        }
        if ($this->module->_errors)
            $this->errors = $this->module->_errors;
    }

    public function getFilterParams($field_list)
    {
        $params = '';
        if ($field_list) {
            foreach ($field_list as $key => $val) {
                $post_val = Tools::getValue($key);
                if ($post_val != '' && Validate::isCleanHtml($post_val)) {
                    $params .= '&' . $key . '=' . urlencode($post_val);
                }
            }
            unset($val);
        }
        return $params;
    }

    public function renderList()
    {
        $fields_list = array(
            'id_ets_export_order_rule' => array(
                'title' => $this->l('ID'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'file_format' => array(
                'title' => $this->l('File format'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'send_file_via_email' => array(
                'title' => $this->l('Send via email'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'export_to_server2' => array(
                'title' => $this->l('Send via FTP'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'export_to_server1' => array(
                'title' => $this->l('Save to web directory'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
            'date_type' => array(
                'title' => $this->l('Date of order'),
                'width' => 100,
                'type' => 'text',
                'filter' => false
            ),
        );
        $order_exports = $this->getOrderExportWithFilter();
        $tpl_vars = array(
            'name' => 'ets_ordermanager',
            'actions' => array('edit', 'delete', 'export'),
            'currentIndex' => $this->currentIndex(),
            'identifier' => 'id_ets_export_order_rule',
            'show_action' => true,
            'title' => $this->l('Export orders'),
            'fields_list' => $fields_list,
            'field_values' => $order_exports,
            'filter_params' => $this->getFilterParams($fields_list),
            'show_reset' => false,
            'show_add_new' => true,
            'hours' => $this->getHours('h A'),
            'weeks' => $this->getWeeks(),
            'months' => $this->getMonths(),
            'schedule' => Ode_defines::getInstance($this->module)->getFields('schedule'),
            'table' => $this->table,
            
        );
        $this->context->smarty->assign($tpl_vars);
        return $this->context->smarty->fetch($this->getTemplatePath().'helper-list.tpl');
    }

    public function renderForm()
    {
        if (Tools::isSubmit('saveOrderExport')) {
            $exported_fields = Tools::getValue('exported_fields','');
            if(!Validate::isCleanHtml($exported_fields))
                $exported_fields = '';
            $specific_customer = Tools::getValue('specific_customer');
            $this->context->smarty->assign(array(
                'customers' => $this->module->getCustomers($specific_customer && Validate::isCleanHtml($specific_customer) ? $specific_customer :''),
                'list_fields' => $this->module->getListFields($exported_fields),
            ));
        }
        elseif (($id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule'))) {
            if (($order_exporter = Db::getInstance()->getRow('SELECT exported_fields, specific_customer FROM `' . _DB_PREFIX_ . 'ets_export_order_rule` WHERE id_ets_export_order_rule=' . (int)$id_ets_export_order_rule))) {
                $this->context->smarty->assign(array(
                    'customers' => $this->module->getCustomers($order_exporter['specific_customer']),
                    'list_fields' => $this->module->getListFields($order_exporter['exported_fields']),
                ));
            }
        } 
        else
        {
            $this->context->smarty->assign(array(
                'customers' => $this->module->getCustomers(array()),
                'list_fields' => $this->module->getListFields($this->field_export_default),
            ));
        }
        //Form
        $id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule');
        $order_exporter = new Ode_export($id_ets_export_order_rule);
        $configs = Ode_defines::getInstance($this->module)->getFields('rule');
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Export orders').($order_exporter->id ? ' ('.$order_exporter->name[$this->context->language->id].')':''),
                    'icon' => 'icon-cogs'
                ),
                'input' => $configs,
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->context->link->getAdminLink('AdminOrderManagerExports'),
                        'icon' => 'process-icon-back',
                        'title' => $this->l('Back'),
                    ),
                    array(
                        'name' => 'saveOrderExportAndExport',
                        'icon' => 'process-icon-export',
                        'title' => $this->l('Save & Export'),
                        'class' => 'pull-right',
                        'type' => 'submit',
                    )
                )
            ),
        );
        if(version_compare(_PS_VERSION_, '1.7', '>=')){
            $this->is17 = true;
        } else {
            $this->is17 = false;
        }
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this->module;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveOrderExport';
        $helper->currentIndex = $this->currentIndex(array(
            'token' => false,
        )).( ($id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule'))  ? '&updateets_export_order_rule&id_ets_export_order_rule='.$id_ets_export_order_rule :'&addets_export_order_rule');
        $helper->token = Tools::getAdminTokenLite($this->controller_name);
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getFieldOrderExport($order_exporter, $configs),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'is_ps16' => $this->is17 ? false:true,
            'isConfigForm' => true,
            'configTabs' => Ode_defines::getInstance($this->module)->getFields('tabs'),
            'currencies' => Currency::getCurrencies(),
            'defaultFormCurrency' => (int)Configuration::get('PS_CURRENCY_DEFAULT'),
            'image_baseurl' => $this->module->getPathUri() . 'views/img/',
            'hours' => $this->getHours('H'),
            'weeks' => $this->getWeeks(),
            'months' => $this->getMonths(),
            'ETS_ODE_USE_CRONJOB' => Configuration::get('ETS_ODE_USE_CRONJOB'),
        );
        $html = $helper->generateForm(array($fields_form));
        $this->context->smarty->assign(array(
            'html' => $html,
            'ets_odm_link_customer_search' => $this->context->link->getAdminLink('AdminOrderManagerExports').'&searchCustomer=1',
        ));
        return $this->context->smarty->fetch($this->getTemplatePath().'form.tpl');

    }

    public function getHours($format = 'g:i a', $start = 0, $end = 86400, $step = 3600)
    {
        $times = array();
        foreach (range($start, $end, $step) as $timestamp) {
            $hours = gmdate('H', $timestamp);
            if (!empty($format))
                $times[(int)$hours] = gmdate($format, $timestamp);
            else $times[(int)$hours] = $hours;
        }
        return $times;
    }

    public function getWeeks()
    {
        setlocale(LC_TIME, str_replace('-', '_', $this->context->language->language_code));
        $timestamp = strtotime('next Sunday');
        $days = array();
        for ($ik = 0; $ik < 7; $ik++) {
            $days[$ik] = date('l', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }
        return $days;
    }

    public function getMonths()
    {
        return array(
            array(
                'label' => $this->l('First day of month'),
                'id_option' => 'start',
            ),
            array(
                'label' => $this->l('Middle of month'),
                'id_option' => '15th of month',
            ),
            array(
                'label' => $this->l('Last day of month'),
                'id_option' => 'end',
            ),
        );
    }

    public function getFieldOrderExport($order_exporter, $configs)
    {
        if (!$order_exporter || !$configs)
            return;
        $languages = Language::getLanguages(false);
        $fields = array();
        if (Tools::isSubmit('saveOrderExport')) {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (($key = $config['name']) && $key == 'exported_fields')
                        continue;
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $fields[$key][$l['id_lang']] = Tools::getValue($key . '_' . $l['id_lang']);
                        }
                    } elseif ($key == 'spent_from' || $key == 'spent_to') {
                        $fields[$key] = trim(($spent = Tools::getValue($key))) != '' ? (float)$spent * $this->context->currency->conversion_rate : 0;
                    } elseif (($config['type'] == 'select' && isset($config['multiple']) && $config['multiple'])) {
                        $fields[$key . ($config['type'] == 'select' ? '[]' : '')] = Tools::getValue($key, array());
                    } elseif ($key != 'specific_customer_select') {
                        $fields[$key] = Tools::getValue($key);
                    } else {
                        $fields[$key] = '';
                    }
                }
                $exported_fields = Tools::getValue('exported_fields');
                $fields['exported_fields'] = $exported_fields && Validate::isCleanHtml($exported_fields) ? $exported_fields : '';
            }
        } else {
            if ($configs) {
                foreach ($configs as $config) {
                    if (($key = $config['name']) && $key == 'exported_fields')
                        continue;
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {

                            $value = $order_exporter->id && ($val = $order_exporter->$key) ? $val[$l['id_lang']] : (isset($config['default_lang']) ? $this->module->getTextLang($config['default_lang'],$l) : (isset($config['default']) ? $config['default']:''));
                            $fields[$key][$l['id_lang']] = $value;
                        }
                        unset($value);
                    } elseif ($key == 'spent_from' || $key == 'spent_to') {
                        $fields[$key] = $order_exporter->id ? ($order_exporter->$key != '' ? (float)$order_exporter->$key * $this->context->currency->conversion_rate : '') : (isset($config['default']) ? $config['default'] : '');
                    } elseif (($config['type'] == 'select' && isset($config['multiple']) && $config['multiple'])) {
                        $fields[$key . ($config['type'] == 'select' ? '[]' : '')] = $order_exporter->id ? ($order_exporter->$key != '' ? ($order_exporter->$key != 'all' ? explode(',', $order_exporter->$key) : 'all') : array()) : (isset($config['default']) ? $config['default'] : array());
                    } elseif ($key != 'specific_customer_select') {
                        $fields[$key] = $order_exporter->id ? (isset($order_exporter->$key) ? $order_exporter->$key :'' ) : (isset($config['default']) ? $config['default'] : '');
                    } else {
                        $fields[$key] = $order_exporter->id ? (isset($order_exporter->$key) ? $order_exporter->$key : '') : (isset($config['default']) ? $config['default'] : '');
                    }
                }
                $fields['exported_fields'] = $order_exporter->id ? $order_exporter->exported_fields : $this->field_export_default;
            }
        }
        //custom fields.
        if(Tools::isSubmit('exported_fields'))
            $exported_fields = explode(',',Tools::getValue('exported_fields'));
        elseif($order_exporter->id)
            $exported_fields = explode(',',$order_exporter->exported_fields);
        else
            $exported_fields = explode(',',$this->field_export_default);                    
        if ($exported_fields && Ets_ordermanager::validateArray($exported_fields)) {
            if ($order_fields = Ode_defines::getInstance($this->module)->getFields('order')) {
                foreach ($order_fields as $order_field) {
                    if (in_array($order_field['id'], $exported_fields))
                        $fields['exported_fields_' . $order_field['id']] = 1;
                }
            }
            if ($customer_fields = Ode_defines::getInstance($this->module)->getFields('customer')) {
                foreach ($customer_fields as $customer_field) {
                    if (in_array($customer_field['id'], $exported_fields))
                        $fields['exported_fields_' . $customer_field['id']] = 1;
                }
            }
            if ($shipping_fields = Ode_defines::getInstance($this->module)->getFields('shipping')) {
                foreach ($shipping_fields as $shipping_field) {
                    if (in_array($shipping_field['id'], $exported_fields))
                        $fields['exported_fields_' . $shipping_field['id']] = 1;
                }
            }
            if ($invoice_fields = Ode_defines::getInstance($this->module)->getFields('invoice')) {
                foreach ($invoice_fields as $invoice_field) {
                    if (in_array($invoice_field['id'], $exported_fields))
                        $fields['exported_fields_' . $invoice_field['id']] = 1;
                }
            }
            if ($carrier_fields = Ode_defines::getInstance($this->module)->getFields('carrier')) {
                foreach ($carrier_fields as $carrier_field) {
                    if (in_array($carrier_field['id'], $exported_fields))
                        $fields['exported_fields_' . $carrier_field['id']] = 1;
                }
            }
            if ($product_fields = Ode_defines::getInstance($this->module)->getFields('product')) {
                foreach ($product_fields as $product_field) {
                    if (in_array($product_field['id'], $exported_fields))
                        $fields['exported_fields_' . $product_field['id']] = 1;
                }
            }
            if ($payment_fields = Ode_defines::getInstance($this->module)->getFields('payment')) {
                foreach ($payment_fields as $payment_field) {
                    if (in_array($payment_field['id'], $exported_fields)) {
                        $fields['exported_fields_' . $payment_field['id']] = 1;
                    }
                }
            }
            if ($other_fields = Ode_defines::getInstance($this->module)->getFields('other')) {
                foreach ($other_fields as $other_field) {
                    if (in_array($other_field['id'], $exported_fields))
                        $fields['exported_fields_' . $other_field['id']] = 1;
                }
            }
        }
        $current_tab = Tools::getValue('current_tab');
        $fields['current_tab'] = $current_tab && Validate::isCleanHtml($current_tab) ? $current_tab :'';
        $fields['send_file_time_hours'] = $order_exporter->send_file_time_hours;
        $fields['send_file_time_weeks'] = $order_exporter->send_file_time_weeks;
        $fields['send_file_time_months'] = $order_exporter->send_file_time_months;
        $fields['server1_time_hours'] = $order_exporter->server1_time_hours;
        $fields['server1_time_weeks'] = $order_exporter->server1_time_weeks;
        $fields['server1_time_months'] = $order_exporter->server1_time_months;
        $fields['server2_time_hours'] = $order_exporter->server2_time_hours;
        $fields['server2_time_weeks'] = $order_exporter->server2_time_weeks;
        $fields['server2_time_months'] = $order_exporter->server2_time_months;

        return $fields;
    }

    public function getOrderExportWithFilter()
    {
        $sql = '
            SELECT * FROM `' . _DB_PREFIX_ . 'ets_export_order_rule` ox 
            LEFT JOIN `' . _DB_PREFIX_ . 'ets_export_order_rule_lang` oxl ON (ox.id_ets_export_order_rule = oxl.id_ets_export_order_rule AND oxl.id_lang = ' . (int)$this->context->language->id . ')
            INNER JOIN `' . _DB_PREFIX_ . 'ets_export_order_rule_shop` oxs ON (ox.id_ets_export_order_rule = oxs.id_ets_export_order_rule)
            WHERE oxs.id_shop = ' . (int)$this->context->shop->id . '  
            ORDER BY position asc
        ';
        $datas = Db::getInstance()->executeS($sql);
        if ($datas) {
            foreach ($datas as &$data) {
                if ($data['date_type'] == 'this_month')
                    $data['date_type'] = $this->l('This month');
                if ($data['date_type'] == 'month_1')
                    $data['date_type'] = $this->l('Month -1');
                if ($data['date_type'] == 'this_year')
                    $data['date_type'] = $this->l('This year');
                if ($data['date_type'] == 'year_1')
                    $data['date_type'] = $this->l('Year -1');
                if ($data['date_type'] == 'from_to')
                    $data['date_type'] = ($data['from_date'] && $data['from_date']!='0000-00-00' ? $this->l('From').' '.$data['from_date'] .' ':'') . ($data['to_date'] && $data['to_date']!='0000-00-00' ? $this->l('to') .' '. $data['to_date']:'');
                if ($data['date_type'] == 'today')
                    $data['date_type'] = $this->l('Today');
                if ($data['date_type'] == 'yesterday')
                    $data['date_type'] = $this->l('Yesterday');
                if ($data['date_type'] == 'any_date')
                    $data['date_type'] = $this->l('Any date');
                if($data['date_type']=='day_before')
                    $data['date_type'] = $data['day_before'].' '.($data['day_before']==1 ? $this->l('day before'): $this->l('days before'));
                
            }
        }
        return $datas;
    }
}