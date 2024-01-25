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

if (!defined('_ETS_ODE_MODULE_')) {
    define('_ETS_ODE_MODULE_', 'ets_ordermanager');
}
if (!defined('_PS_VERSION_'))
    exit;
require_once(dirname(__FILE__) . '/classes/Ode_defines.php');
require_once(dirname(__FILE__) . '/classes/Ode_export.php');
require_once(dirname(__FILE__) . '/classes/PDFALL.php');
require_once(dirname(__FILE__) . '/classes/HTMLTemplateLabelDelivery.php');

class Ets_ordermanager extends Module
{
    public $is17 = false;
    public $is15 = false;
    public $colnames = array();
    private $baseAdminPath;
    private $errorMessage;
    private $_html;
    public $_list_order_default = array();
    public $title_fields = array();
    public $_errors = array();
    public $bulk_orders = array();

    public function __construct()
    {
        $this->name = 'ets_ordermanager';
        $this->tab = 'front_office_features';
        $this->version = '2.5.3';
        $this->author = 'ETS-Soft';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        parent::__construct();
        $this->shortlink = 'https://mf.short-link.org/';
        if (($configure = Tools::getValue('configure')) && $configure == $this->name && Tools::isSubmit('othermodules')) {
            $this->displayRecommendedModules();
        }
        $this->title_fields = array(
            'id_order' => array(
                'title' => $this->l('Order ID'),
                'group' => $this->l('Order'),
                'beggin' => true,
                'all' => true,
            ),
            'id_cart' => array(
                'title' => $this->l('Cart ID'),
            ),
            'reference' => array(
                'title' => $this->l('Order reference'),
            ),
            'total_paid_tax_incl' => array(
                'title' => $this->l('Total'),
            ),
            'osname' => array(
                'title' => $this->l('Order status'),
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
            ),
            'order_note' => array(
                'title' => $this->l('Order note'),
            ),
            'vat_number' => array(
                'title' => $this->l('VAT number'),
                'end' => true,
            ),
            'customer' => array(
                'title' => $this->l('Full name'),
                'group' => $this->l('Customer'),
                'beggin' => true,
                'all' => true,
            ),
            'email' => array(
                'title' => $this->l('Email'),
            ),
            'number_phone' => array(
                'title' => $this->l('Phone number'),
            ),
            'address_invoice' => array(
                'title' => $this->l('Invoice address'),
            ),
            'new' => array(
                'title' => $this->l('New client'),
            ),
            'customer_group' => array(
                'title' => $this->l('Client group'),
                'end' => true,
            ),
            'caname' => array(
                'title' => $this->l('Shipping method'),
                'beggin' => true,
                'group' => $this->l('Shipping'),
                'all' => true,
            ),
            'shipping_cost_tax_incl' => array(
                'title' => $this->l('Total shipping cost'),
            ),
            'cname' => array(
                'title' => $this->l('Delivery'),
            ),
            'address1' => array(
                'title' => $this->l('Shipping address'),
            ),
            'city' => array(
                'title' => $this->l('Ship to city'),
            ),
            'company' => array(
                'title' => $this->l('Company'),
            ),
            'tracking_number' => array(
                'title' => $this->l('Tracking number'),
            ),
            'postcode' => array(
                'title' => $this->l('Postal code'),
                'end' => true,
            ),
            'payment' => array(
                'title' => $this->l('Payment method'),
                'beggin' => true,
                'group' => $this->l('Others'),
                'all' => true
            ),
            'fee' => array(
                'title' => Configuration::get('ETS_PMF_TEXT_PAYMENT_FEE', Context::getContext()->language->id) ?: $this->l('Payment fee'),
            ),
            'id_pdf' => array(
                'title' => $this->l('PDF'),
            ),
            'images' => array(
                'title' => $this->l('Products'),
            ),
            'last_message' => array(
                'title' => $this->l('Last message'),
                'end' => true,
            ),
        );
        if (!Module::isInstalled('ets_payment_with_fee') || !Module::isEnabled('ets_payment_with_fee')) {
            unset($this->title_fields['fee']);
        }
        $this->_list_order_default = array('id_order', 'reference', 'customer', 'images', 'order_note', 'total_paid_tax_incl', 'payment', 'osname', 'date_add', 'id_pdf');
        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true;
        if (version_compare(_PS_VERSION_, '1.6', '<'))
            $this->is15 = true;
        if ((string)Tools::substr(sprintf('%o', fileperms(dirname(__FILE__))), -4) != '0755')
            @chmod(dirname(__FILE__), 0755);
        if ((string)Tools::substr(sprintf('%o', fileperms(dirname(__FILE__) . '/cronjob.php')), -4) != '0755')
            @chmod(dirname(__FILE__) . '/cronjob.php', 0755);
        $this->displayName = $this->l('Order Manager');
        $this->description = $this->l('Edit/delete orders, export orders to CSV/Excel, customizable order listing page with quick view. All-in-one order management tool to manage your orders easily and effectively.');
        $this->module_key = '60ded2caf885919a865348206f5089d5';
        $this->ps_versions_compliancy = array('min' => '1.5.0.0', 'max' => _PS_VERSION_);
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=') && isset($this->context->employee) && isset($this->context->employee->id) && $this->context->employee->id) {
            if (Tools::isSubmit('export_all_order') || Tools::isSubmit('print_slips_all_order') || Tools::isSubmit('print_invoice_all_order') || Tools::isSubmit('delete_all_order') || Tools::isSubmit('restore_all_order') || Tools::isSubmit('print_delivery_label_all_order')) {
                $order_orders_bulk = Tools::getValue('order_orders_bulk');
                $this->bulk_orders['order_orders_bulk'] = $order_orders_bulk && Ets_ordermanager::validateArray($order_orders_bulk) ? $order_orders_bulk : array();
                if (Tools::isSubmit('export_all_order'))
                    $this->bulk_orders['export_all_order'] = 1;
                if (Tools::isSubmit('print_slips_all_order'))
                    $this->bulk_orders['print_slips_all_order'] = 1;
                if (Tools::isSubmit('print_invoice_all_order'))
                    $this->bulk_orders['print_invoice_all_order'] = 1;
                if (Tools::isSubmit('print_delivery_label_all_order'))
                    $this->bulk_orders['print_delivery_label_all_order'] = 1;
                if (Tools::isSubmit('delete_all_order'))
                    $this->bulk_orders['delete_all_order'] = 1;
                if (Tools::isSubmit('restore_all_order'))
                    $this->bulk_orders['restore_all_order'] = 1;
                if (Tools::isSubmit('viewtrash'))
                    $this->bulk_orders['viewtrash'] = 1;
                $this->context->cookie->ets_odm_bulk_order = json_encode($this->bulk_orders);
                $this->context->cookie->write();
            } else {
                if ($this->context->cookie->ets_odm_bulk_order) {
                    $this->bulk_orders = json_decode($this->context->cookie->ets_odm_bulk_order, true);
                    $this->context->cookie->ets_odm_bulk_order = '';
                    $this->context->cookie->write();
                }
            }
            unset($this->title_fields['id_pdf']);

        }
    }
    public function copyTranslations()
    {
        $ps_translations_dir = $this->is17 ? _PS_ROOT_DIR_ . '/app/Resources/translations/' : _PS_ROOT_DIR_ . '/translations/';
        $tempDir = dirname(__FILE__) . '/views/templates/admin/_configure/templates/';
        $copy_trans = array();
        $this->copyAllFiles($copy_trans, $tempDir . 'orders', $tempDir);

        if (($languages = Language::getLanguages(false)) && $copy_trans) {

            foreach ($languages as $language) {
                if (!@file_exists(($trans_file = dirname(__FILE__) . '/translations/' . $language['iso_code'] . '.php'))) {
                    @file_put_contents($trans_file, "<?php\n\nglobal \$_MODULE;\n\$_MODULE = array();\n");
                }
                if (!is_writable($trans_file)) {
                    $this->displayWarning($this->l('This file must be writable:') . $trans_file);
                }

                $str_write = Tools::file_get_contents($trans_file);
                $_MODULE = array();
                include $trans_file;

                if ($this->is17 && ($order_trans = @glob($ps_translations_dir . $language['locale'] . DIRECTORY_SEPARATOR . 'AdminOrders*.' . $language['locale'] . '.xlf'))) {
                    foreach ($order_trans as $trans) {
                        if (($dataXML = @simplexml_load_file($trans)) && !empty($dataXML->file)) {
                            foreach ($dataXML->file as $file) {
                                if ($this->fileInArray((string)$file['original'], $copy_trans) && ($array_trans = (array)$file->body) && !empty($array_trans['trans-unit'])) {
                                    foreach ((array)$array_trans['trans-unit'] as $trans_unit) {
                                        if (!empty($trans_unit['id']) && isset($trans_unit->target)) {
                                            $keyMd5 = '<{' . $this->name . '}prestashop>' . basename((string)$file['original'], '.tpl') . '_' . (string)$trans_unit['id'];
                                            if (empty($_MODULE[$keyMd5])) {
                                                $str_write .= "\$_MODULE['" . $keyMd5 . "'] = '" . pSQL($trans_unit->target) . "';\n";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif (!$this->is17 && @file_exists(($ps_trans_file = $ps_translations_dir . $language['iso_code'] . DIRECTORY_SEPARATOR . 'admin.php'))) {
                    $_LANGADM = array();
                    include $ps_trans_file;

                    if (!empty($_LANGADM)) {
                        foreach ($copy_trans as $copy_tran) {
                            if (file_exists($tempDir . $copy_tran)) {

                                $basename = basename($copy_tran, '.tpl');
                                $dataTPL = Tools::file_get_contents($tempDir . $copy_tran);

                                $regex = '/\{l\s*s=([\'\"])' . _PS_TRANS_PATTERN_ . '\1.*\s+mod=\'' . $this->name . '\'.*\}/U';
                                if (preg_match_all($regex, $dataTPL, $matches) && count($matches) > 2) {
                                    foreach ($matches[2] as $match) {

                                        $strMd5 = md5($match);
                                        $keyMd5 = '<{' . $this->name . '}prestashop>' . $basename . '_' . $strMd5;

                                        if (empty($_MODULE[$keyMd5]) && !empty($_LANGADM['AdminOrders' . $strMd5])) {
                                            $str_write .= "\$_MODULE['" . $keyMd5 . "'] = '" . pSQL($_LANGADM['AdminOrders' . $strMd5]) . "';\n";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                @file_put_contents($trans_file, $str_write);
            }
        }
    }

    public function fileInArray($original, $copy_trans)
    {
        if (empty($copy_trans))
            return false;
        foreach ($copy_trans as $file) {
            if (@strpos($original, $file) !== false) {
                return true;
            }
        }
        return false;
    }

    public function copyAllFiles(&$copy_trans, $path, $cutPath)
    {
        if ($files = glob($path . '/*')) {
            foreach ($files as $file) {
                if (!@is_dir($file) && basename($file, '.php') != 'index') {
                    $copy_trans[] = str_replace($cutPath, '', $file);
                } else
                    $this->copyAllFiles($copy_trans, $file, $cutPath);

            }
            unset($files);
        }
    }

    /**
     * @see Module::install()
     */
    public function install()
    {
        if (Module::isInstalled('ets_delete_order')) {
            throw new PrestaShopException($this->l("The module Delete Order has been installed"));
        }
        if (Module::isInstalled('etsloginascustomer')) {
            throw new PrestaShopException($this->l("The module Login as customer has been installed"));
        }
        $this->copyTranslations();
        return parent::install()
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayOdeProductList')
            && $this->registerHook('displayOdeCronjobRules')
            && $this->registerHook('displayDashboardToolbarTopMenu')
            && $this->registerHook('displayOdeCustomerList')
            && $this->registerHook('actionObjectLanguageAddAfter')
            && $this->registerHook('actionOrderGridQueryBuilderModifier')
            && $this->registerHook('actionOrderGridDefinitionModifier')
            && $this->registerHook('actionCustomerGridDefinitionModifier')
            && $this->registerHook('actionOrderGridDataModifier')
            && $this->registerHook('displayBlockInputChangeInLine')
            && $this->registerHook('actionDispatcherBefore')
            && $this->registerHook('displayShopLicenseEditField')
            && $this->registerHook('displayDisplayIdCart')
            && $this->registerHook('displayAdminGridTableBefore')
            && $this->registerHook('actionObjectOrderDeleteAfter')
            && $this->installConfigs()
            && $this->_installDb()
            && $this->_installOverried()
            && $this->_installTabs()
            && $this->createTemplateMail();
    }

    public function installOverrides()
    {
        return parent::installOverrides() && $this->_installOverried();
    }

    public function unInstallOverrides()
    {
        return parent::uninstallOverrides() && $this->_unInstallOverried();
    }

    public function createTemplateMail()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $this->copy_directory(dirname(__FILE__) . '/mails/en', dirname(__FILE__) . '/mails/' . $language['iso_code']);
        }
        return true;
    }

    public function copy_directory($src, $dst)
    {
        $dir = opendir($src);
        if (!file_exists($dst))
            @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copy_directory($src . '/' . $file, $dst . '/' . $file);
                } elseif (!file_exists($dst . '/' . $file)) {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function installConfigs()
    {
        $languages = Language::getLanguages(false);
        $configs = Ode_defines::getInstance()->getFields('config');
        if ($configs) {
            foreach ($configs as $key => $config) {
                if (isset($config['lang']) && $config['lang']) {
                    $values = array();
                    foreach ($languages as $lang) {
                        $values[$lang['id_lang']] = (isset($config['default']) ? $config['default'] : '');
                    }
                    $this->updateValues($config, $key, $values, true);
                } else {
                    $this->updateValues($config, $key, (isset($config['default']) ? $config['default'] : ''), true);
                }
            }
        }
        return true;
    }

    public function _installOverried()
    {
        $this->copy_directory(dirname(__FILE__) . '/views/templates/admin/_configure/templates', _PS_OVERRIDE_DIR_ . 'controllers/admin/templates');
        if (!$this->is17) {
            $dir = $this->context->smarty->getTemplateDir(0) . 'controllers' . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR;
            if (!file_exists($dir . 'page_header_toolbar.tpl'))
                Tools::copy(dirname(__FILE__) . '/views/templates/admin/_configure/templates/orders/page_header_toolbar.tpl', $dir . 'page_header_toolbar.tpl');
        }
        $this->installTemplatePdfDeliveryLabel();
        return true;
    }

    public function _unInstallOverried()
    {
        $this->delete_directory(_PS_OVERRIDE_DIR_ . 'controllers/admin/templates');
        if (Module::isInstalled('ets_payment_with_fee'))
            $this->copy_directory(_PS_MODULE_DIR_ . 'ets_payment_with_fee/views/templates/admin/templates', _PS_OVERRIDE_DIR_ . 'controllers/admin/templates');
        return true;
    }

    public function _installDb()
    {
        $fieldsorders = Db::getInstance()->ExecuteS('DESCRIBE ' . _DB_PREFIX_ . 'orders');
        $check_add_order_note = true;
        $check_add_deleted = true;
        foreach ($fieldsorders as $field) {
            if ($field['Field'] == 'order_note') {
                $check_add_order_note = false;
                if (!$check_add_deleted)
                    break;
            }
            if ($field['Field'] == 'deleted') {
                $check_add_deleted = false;
                if (!$check_add_order_note)
                    break;
            }
        }
        if ($check_add_order_note)
            Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'orders` ADD  `order_note` text default ""');
        if ($check_add_deleted)
            Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'orders` ADD  `deleted` INT(1) default "0"');
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_export_order_rule`( 
            `id_ets_export_order_rule` INT(11) NOT NULL AUTO_INCREMENT , 
            `exported_fields` TEXT NULL ,
            `date_type` VARCHAR(33) NULL,
            `send_file_via_email` INT(1),            
            `send_file_schedule` VARCHAR(50),            
            `send_file_time_hours` VARCHAR(20),
            `send_file_time_weeks` VARCHAR(20),
            `send_file_time_months` VARCHAR(20),
            `send_file_date` DATETIME,
                        
            `export_to_server1` INT(1),
            `directory_path1` VARCHAR(254) NULL,
            `server1_schedule` VARCHAR(50),            
            `server1_time_hours` VARCHAR(20),
            `server1_time_weeks` VARCHAR(20),
            `server1_time_months` VARCHAR(20),
            `server1_date` DATETIME,
            
            `export_to_server2` INT(1),
            `global_ftp` INT(1),
            `host` VARCHAR(50) NULL,
            `username` VARCHAR(150) NULL,
            `port` VARCHAR(6) NULL,
            `password` VARCHAR(150) NULL,
            `directory_path2` VARCHAR(254) NULL,
            `server2_schedule` VARCHAR(50),            
            `server2_time_hours` VARCHAR(20),
            `server2_time_weeks` VARCHAR(20),
            `server2_time_months` VARCHAR(20),
            `server2_date` DATETIME,
            `delete_exported_files` VARCHAR(20),
            `from_date` date,
            `to_date` date,
            `day_before` int (11) NULL,
            `order_status` TEXT NULL ,
            `customer_group` VARCHAR(254) NULL , 
            `specific_customer` text NULL ,
            `specific_order` text NULL ,  
            `id_country` TEXT NULL , 
            `id_state` INT(11) NULL ,
            `order_carrier` text NULL , 
            `payment_method` text NULL , 
            `manufacturer` text NULL, 
            `supplier` text NULL, 
            `category` text NULL, 
            `specific_product` text NULL, 
            `spent_from` VARCHAR(15), 
            `spent_to` VARCHAR(15),
            `file_format` VARCHAR(254) NULL,
            `file_name_prefix` VARCHAR(254) NULL,
            `file_name_incl_name_rule` INT(1),
            `sort_by` VARCHAR(254) NULL,
            `convert_in_currency` INT(11),
            `receivers_mail` text,
            `position` int (11), 
            `send_file_filter` VARCHAR(222),
            `server1_filter` VARCHAR(222),
            `server2_filter` VARCHAR(222),                      
        PRIMARY KEY (`id_ets_export_order_rule`)) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8')
            && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_export_order_rule_lang` (
            `id_ets_export_order_rule` INT(11) NOT NULL ,
            `id_lang` INT(11) NOT NULL , 
            `name` VARCHAR(254) NOT NULL,
            `title_mail` text,
            `description_mail` text, 
        PRIMARY KEY (`id_ets_export_order_rule`, `id_lang`)) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8')
            && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_export_order_rule_shop` (
            `id_ets_export_order_rule` INT(11) NOT NULL ,
            `id_shop` INT(11) NOT NULL ,
        PRIMARY KEY (`id_ets_export_order_rule`, `id_shop`)) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8')
            && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_odm_customer_login` ( 
            `id_customer` INT(11) NOT NULL , 
            `token` VARCHAR(50) NOT NULL , 
            `date_add` DATETIME NOT NULL ,
             PRIMARY KEY (`id_customer`)) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8'
            ) && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_odm_order_detail_refund` ( 
            `id_order_detail` INT(11) NOT NULL , 
            `refund` INT(1) , 
             INDEX (`id_order_detail`,`refund`)) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8'
            );
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallConfigs()
            && $this->_uninstallDb()
            && $this->_unInstallOverried()
            && $this->_uninstallTabs()
            && $this->unregisterHook('displayBackOfficeHeader')
            && $this->unregisterHook('displayOdeProductList')
            && $this->unregisterHook('displayOdeCronjobRules')
            && $this->unregisterHook('displayDashboardToolbarTopMenu')
            && $this->unregisterHook('displayOdeCustomerList')
            && $this->unregisterHook('actionObjectLanguageAddAfter')
            && $this->unregisterHook('actionOrderGridQueryBuilderModifier')
            && $this->unregisterHook('actionOrderGridDefinitionModifier')
            && $this->unregisterHook('actionOrderGridDataModifier')
            && $this->unregisterHook('displayBlockInputChangeInLine')
            && $this->unregisterHook('actionDispatcherBefore')
            && $this->unregisterHook('displayShopLicenseEditField')
            && $this->unregisterHook('displayDisplayIdCart')
            && $this->unregisterHook('displayAdminGridTableBefore')
            && $this->unregisterHook('actionObjectOrderDeleteAfter');
    }

    public function uninstallConfigs()
    {
        $configs = Ode_defines::getInstance()->getFields('config');
        if ($configs) {
            foreach ($configs as $key => $config) {
                Configuration::deleteByName($key);
                unset($config);
            }
        }
        return true;
    }

    private function _uninstallDb()
    {
        Configuration::deleteByName('ETS_ORDERMANAGE_ARRANGE_LIST');
        @unlink(dirname(__file__) . '/cronjob.log');
        return Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'ets_export_order_rule')
            && Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'ets_export_order_rule_lang')
            && Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'ets_export_order_rule_shop');
    }

    public function _installTabs()
    {
        if ($parentId = Tab::getIdFromClassName('AdminParentOrders')) {
            $languages = Language::getLanguages(false);
            $tab = new Tab();
            $tab->id_parent = (int)$parentId;
            $tab->class_name = 'AdminOrderManagerExports';
            $tab->icon = 'icon-AdminPriceRule';
            $tab->module = $this->name;
            foreach ($languages as $l) {
                $tab->name[$l['id_lang']] = $this->getTextLang('Export orders', $l) ?: $this->l('Export orders');
            }
            if (!Tab::getIdFromClassName($tab->class_name))
                return $tab->add();
        }
        return true;
    }

    public function _uninstallTabs()
    {
        if ($id = Tab::getIdFromClassName('AdminOrderManagerExports')) {
            $tab = new Tab((int)$id);
            if ($tab->delete()) {
                return true;
            }
        }
        return true;
    }

    public function hookDisplayOdeCronjobRules($params)
    {
        $rules = Ode_export::getExports($this->context);
        $secure = isset($params['secure']) && $params['secure'] ? $params['secure'] : Tools::strtolower(Tools::passwdGen(10));
        $this->smarty->assign(array(
            'path_uri' => $this->getPathUri(),
            'php_path' => (defined('PHP_BINDIR') && PHP_BINDIR && is_string(PHP_BINDIR) ? PHP_BINDIR . '/' : '') . 'php ',
            'path_local' => $this->getLocalPath(),
            'domain' => Tools::getShopDomainSsl(true, true),
            'rules' => $rules,
            'secure' => $secure,
        ));
        return $this->display(__FILE__, 'cronjob.tpl');
    }

    public function isValidIds($excludeId)
    {
        if ($excludeId != '') {
            $ids = explode('-', $excludeId);
            if (!isset($ids[1]))
                $ids[1] = 0;
            if (Validate::isInt($ids[0]) && Validate::isInt($ids[1]))
                return (int)$ids[0] . '-' . (int)$ids[1];
            return false;
        }
        return false;
    }

    public function hookActionCustomerGridDefinitionModifier($params)
    {
        $definition = &$params['definition'];
        $columns = $definition->getColumns();
        $columns->remove('actions');
        $actions = (new PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection())
            ->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('edit'))
                    ->setName($this->trans('Edit', [], 'Admin.Actions'))
                    ->setIcon('edit')
                    ->setOptions([
                        'route' => 'admin_customers_edit',
                        'route_param_name' => 'customerId',
                        'route_param_field' => 'id_customer',
                    ])
            )
            ->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('view'))
                    ->setName($this->trans('View', [], 'Admin.Actions'))
                    ->setIcon('zoom_in')
                    ->setOptions([
                        'route' => 'admin_customers_view',
                        'route_param_name' => 'customerId',
                        'route_param_field' => 'id_customer',
                        //'clickable_row' => true,
                    ])
            )
            ->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\Customer\DeleteCustomerRowAction('delete'))
                ->setName($this->trans('Delete', [], 'Admin.Actions'))
                ->setIcon('delete')
                ->setOptions([
                    'customer_id_field' => 'id_customer',
                    'customer_delete_route' => 'admin_customers_delete',
                ])
            )->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('login_as_customer'))
                ->setName($this->l('Login as customer'))
                ->setIcon('fa fa-user')
                ->setOptions([
                    'route' => 'admin_customers_login_as_customer',
                    'route_param_name' => 'customerId',
                    'route_param_field' => 'id_customer',
                ])
            );
        if (Module::isEnabled('ets_trackingcustomer')) {
            $actions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('restore'))
                ->setName($this->l('Customer sessions'))
                ->setIcon('fa fa-file-o')
                ->setOptions([
                    'route' => 'admin_customers_activities',
                    'route_param_name' => 'customerId',
                    'route_param_field' => 'id_customer',
                ])
            );
        }
        if (Module::isEnabled('ets_livechat')) {
            $actions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('create_ticket_as_customer'))
                ->setName($this->l('Create ticket'))
                ->setIcon('fa fa-ticket')
                ->setOptions([
                    'route' => 'admin_customers_create_ticket_as_customer',
                    'route_param_name' => 'customerId',
                    'route_param_field' => 'id_customer',
                ])
            );
        }
        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn('actions'))
            ->setName($this->trans('Actions', [], 'Admin.Global'))
            ->setOptions([
                'actions' => $actions,
            ]));
    }

    public function hookActionOrderGridDefinitionModifier($params)
    {
        $controller = Tools::getValue('controller');
        if ($this->getRequestContainer()) {
            //Do nothing
        } else {
            if ($controller != 'AdminOrders' && $controller != 'adminorders')
                return '';
        }
        $previewColumn = (new PrestaShop\PrestaShop\Core\Grid\Column\Type\PreviewColumn('preview'))
            ->setOptions([
                'icon_expand' => 'keyboard_arrow_down',
                'icon_collapse' => 'keyboard_arrow_up',
                'preview_data_route' => 'admin_orders_preview',
                'preview_route_params' => [
                    'orderId' => 'id_order',
                ],
            ]);
        $defination = &$params['definition'];
        if (Tools::isSubmit('viewtrash'))
            $gridActions = new PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection();
        else {
            $gridActions = $defination->getGridActions();
            $gridActions->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Type\LinkGridAction('viewtrash'))
                    ->setName($this->l('View trash'))
                    ->setIcon('delete')
                    ->setOptions([
                        'route' => 'admin_orders_viewtrash',
                    ])
            );

        }

        if (Tools::isSubmit('viewtrash'))
            $bulkActions = new PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection();
        else
            $bulkActions = $defination->getBulkActions();
        $bulkActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction('export_selected'))
            ->setName($this->l('Export selected orders to csv'))
            ->setOptions([
                // in most cases submit action should be implemented by module
                'submit_route' => 'admin_customers_bulk_export',
            ])
        );
        $filters = new PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection();

        $columns = (new PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection())->add(
            (new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn('orders_bulk'))
                ->setOptions([
                    'bulk_field' => 'id_order',
                ])
        );
        $fields = $this->getFieldsListOrder();
        if ($fields) {
            $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('ajax_filter', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->l('Search ID'),
                    ],
                ])
                ->setAssociatedColumn('id_order')
            );
            foreach ($fields as $key => $field) {
                switch ($key) {
                    case 'id_order':
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('id_order', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search ID'),
                                ],
                            ])
                            ->setAssociatedColumn('id_order')
                        );
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn('id_order'))
                            ->setName($field['title'])
                            ->setOptions([
                                'identifier_field' => 'id_order',
                                'preview' => $previewColumn,
                                'clickable' => false,
                            ])
                        );
                        break;
                    case 'id_cart':
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('id_cart', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search Cart ID'),
                                ],
                            ])
                            ->setAssociatedColumn('id_cart')
                        );
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn('id_cart'))
                            ->setName($field['title'])
                            ->setOptions([
                                'identifier_field' => 'id_cart',
                                'clickable' => false,
                            ])
                        );
                        break;
                    case 'reference':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('reference'))
                            ->setName($this->trans('Reference', [], 'Admin.Global'))
                            ->setOptions([
                                'field' => 'reference',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('reference', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search reference'),
                                ],
                            ])
                            ->setAssociatedColumn('reference')
                        );
                        break;
                    case 'new':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\BooleanColumn('new'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'new',
                                'true_name' => $this->l('Yes'),
                                'false_name' => $this->l('No'),
                                'clickable' => false,
                            ])
                        );
                        // $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('new', PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType::class))
//                                ->setTypeOptions([
//                                    'required' => false,
//                                ])
//                                ->setAssociatedColumn('new')
//                        );
                        break;
                    case 'customer':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DisableableLinkColumn('customer'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'customer',
                                'disabled_field' => 'deleted_customer',
                                'route' => 'admin_customers_view',
                                'route_param_name' => 'customerId',
                                'route_param_field' => 'id_customer',
                                'target' => '_blank',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('customer', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->trans('Search customer', [], 'Admin.Actions'),
                                ],
                            ])
                            ->setAssociatedColumn('customer')
                        );
                        break;
                    case 'order_note':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('order_note'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'order_note',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('order_note', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search note'),
                                ],
                            ])
                            ->setAssociatedColumn('order_note')
                        );
                        break;
                    case 'total_paid_tax_incl':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\OrderPriceColumn('total_paid_tax_incl'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'total_paid_tax_incl',
                                'is_paid_field' => 'paid',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('total_paid_tax_incl', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search total'),
                                ],
                            ])
                            ->setAssociatedColumn('total_paid_tax_incl')
                        );
                        break;
                    case 'payment':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('payment'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'payment',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('payment', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search payment'),
                                ],
                            ])
                            ->setAssociatedColumn('payment')
                        );
                        break;
                    case 'osname':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ChoiceColumn('osname'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'current_state',
                                'route' => 'admin_orders_list_update_status',
                                'color_field' => 'color',
                                'choice_provider' => new PrestaShop\PrestaShop\Core\Form\ChoiceProvider\OrderStateByIdChoiceProvider($this->context->language->id, new PrestaShop\PrestaShop\Adapter\OrderState\OrderStateDataProvider(), new PrestaShop\PrestaShop\Core\Util\ColorBrightnessCalculator(), Context::getContext()->getTranslator()),
                                'record_route_params' => [
                                    'id_order' => 'orderId',
                                ],
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('osname', PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'choices' => (new PrestaShop\PrestaShop\Core\Form\ChoiceProvider\OrderStateByIdChoiceProvider($this->context->language->id, new PrestaShop\PrestaShop\Adapter\OrderState\OrderStateDataProvider(), new PrestaShop\PrestaShop\Core\Util\ColorBrightnessCalculator(), Context::getContext()->getTranslator()))->getChoices(),
                                'translation_domain' => false,
                            ])
                            ->setAssociatedColumn('osname')
                        );
                        break;
                    case 'date_add' :
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DateTimeColumn('date_add'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'date_add',
                                'format' => 'm/d/Y H:i:s',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('date_add', PrestaShopBundle\Form\Admin\Type\DateRangeType::class))
                            ->setTypeOptions([
                                'required' => false,
                            ])
                            ->setAssociatedColumn('date_add')
                        );
                        break;
                    case 'country_name':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('country_name'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'country_name',
                                'clickable' => false,
                            ])
                        );
                        $orderCountriesChoices = (new PrestaShop\PrestaShop\Adapter\Form\ChoiceProvider\OrderCountriesChoiceProvider())->getChoices();
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('country_name', Symfony\Component\Form\Extension\Core\Type\ChoiceType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'choices' => $orderCountriesChoices,
                            ])
                            ->setAssociatedColumn('country_name')
                        );
                        break;
                    case 'company':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('company'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'company',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('company', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search company'),
                                ],
                            ])
                            ->setAssociatedColumn('company')
                        );
                        break;
                    case 'last_message':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('last_message'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'last_message',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('last_message', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search message'),
                                ],
                            ])
                            ->setAssociatedColumn('last_message')
                        );
                        break;
                    case 'email':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('email'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'email',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('email', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search email'),
                                ],
                            ])
                            ->setAssociatedColumn('email')
                        );
                        break;
                    case 'number_phone':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('number_phone'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'number_phone',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('number_phone', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search phone number'),
                                ],
                            ])
                            ->setAssociatedColumn('number_phone')
                        );
                        break;
                    case 'address_invoice':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('address_invoice'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'address_invoice',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('address_invoice', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search Tracking number'),
                                ],
                            ])
                            ->setAssociatedColumn('address_invoice')
                        );
                        break;
                    case 'tracking_number':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('tracking_number'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'tracking_number',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('tracking_number', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search invoice address'),
                                ],
                            ])
                            ->setAssociatedColumn('tracking_number')
                        );
                        break;
                    case 'address1':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('address_delivery'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'address_delivery',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('address_delivery', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search shipping address'),
                                ],
                            ])
                            ->setAssociatedColumn('address_delivery')
                        );
                        break;
                    case 'caname':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('caname'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'caname',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('caname', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search shipping method'),
                                ],
                            ])
                            ->setAssociatedColumn('caname')
                        );
                        break;
                    case 'cname':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('cname'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'cname',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('cname', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search delivery'),
                                ],
                            ])
                            ->setAssociatedColumn('cname')
                        );
                        break;
                    case 'vat_number':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('vat_number'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'vat_number',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('vat_number', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search VAT number'),
                                ],
                            ])
                            ->setAssociatedColumn('vat_number')
                        );
                        break;
                    case 'postcode':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('postcode'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'postcode',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('postcode', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search Post code'),
                                ],
                            ])
                            ->setAssociatedColumn('postcode')
                        );
                        break;
                    case 'city':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('city'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'city',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('city', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search City'),
                                ],
                            ])
                            ->setAssociatedColumn('city')
                        );
                        break;
                    case 'company':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('company'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'company',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('company', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search Company'),
                                ],
                            ])
                            ->setAssociatedColumn('company')
                        );
                        break;
                    case 'customer_group':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('customer_group'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'customer_group',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('customer_group', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search client group'),
                                ],
                            ])
                            ->setAssociatedColumn('customer_group')
                        );
                        break;
                    case 'shipping_cost_tax_incl':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\OrderPriceColumn('shipping_cost_tax_incl'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'shipping_cost_tax_incl',
                                'is_paid_field' => 'paid',
                                'clickable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('shipping_cost_tax_incl', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search total shipping cost'),
                                ],
                            ])
                            ->setAssociatedColumn('shipping_cost_tax_incl')
                        );
                        break;
                    case 'images':
                        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('images'))
                            ->setName($field['title'])
                            ->setOptions([
                                'field' => 'images',
                                'clickable' => false,
                                'sortable' => false,
                            ])
                        );
                        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('images', Symfony\Component\Form\Extension\Core\Type\TextType::class))
                            ->setTypeOptions([
                                'required' => false,
                                'attr' => [
                                    'placeholder' => $this->l('Search product name'),
                                ],
                            ])
                            ->setAssociatedColumn('images')
                        );
                        break;
                }
            }
        }
        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn('id_pdf'))
            ->setName('')
            ->setOptions([
                'field' => 'id_pdf',
                'clickable' => false,
                'sortable' => false,
            ])
        );
        $columns->add((new PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn('actions'))
            ->setName($this->l('Actions'))
            ->setOptions([
                'actions' => $this->getRowActions(),
            ])
        );
        $filters->add((new PrestaShop\PrestaShop\Core\Grid\Filter\Filter('actions', PrestaShopBundle\Form\Admin\Type\SearchAndResetType::class))
            ->setTypeOptions([
                'reset_route' => 'admin_common_reset_search_by_filter_id',
                'reset_route_params' => [
                    'filterId' => 'order',
                ],
                'redirect_route' => 'admin_orders_index',
            ])
            ->setAssociatedColumn('actions')
        );
        $defination->setColumns($columns);
        if (Tools::isSubmit('viewtrash'))
            $defination->setFilters(new PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection());
        else
            $defination->setFilters($filters);
        $defination->setGridActions($gridActions);
        $defination->setBulkActions($bulkActions);
    }

    public function getFilters()
    {
        if (Tools::isSubmit('order') && ($submit_order = Tools::getValue('order')) && isset($submit_order['filters']) && ($submit_filters = $submit_order['filters'])) {
            return $submit_filters;
        } else {
            if ($filters = Db::getInstance()->getValue('SELECT filter FROM `' . _DB_PREFIX_ . 'admin_filter` WHERE employee="' . (int)Context::getContext()->employee->id . '" AND filter_id="order"')) {
                $filters = json_decode($filters, true);
                if (isset($filters['filters']) && $filters['filters']) {
                    return $filters['filters'];
                }

            }
        }
    }

    public function hookActionOrderGridQueryBuilderModifier($params)
    {
        $controller = Tools::getValue('controller');
        if ($controller != 'AdminOrders' && $controller != 'adminorders')
            return '';
        $fields = $this->getFieldsListOrder();
        if (isset($params['search_query_builder']) && $params['search_query_builder'] && isset($params['count_query_builder']) && $params['count_query_builder']) {
            $searchQueryBuilder = &$params['search_query_builder'];
            $countQueryBuilder = &$params['count_query_builder'];
            if (isset($fields['address_invoice'])) {
                $searchQueryBuilder->addSelect('address_invoice.address1 as address_invoice')
                    ->innerJoin('o', _DB_PREFIX_ . 'address', 'address_invoice', 'address_invoice.id_address = o.id_address_invoice');
                $countQueryBuilder->innerJoin('o', _DB_PREFIX_ . 'address', 'address_invoice', 'address_invoice.id_address = o.id_address_invoice');
            }
            if (isset($fields['caname'])) {
                $searchQueryBuilder->addSelect('carrier.name as caname')
                    ->leftJoin('o', _DB_PREFIX_ . 'carrier', 'carrier', 'carrier.id_carrier = o.id_carrier');
                $countQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'carrier', 'carrier', 'carrier.id_carrier = o.id_carrier');
            }
            if (isset($fields['cname'])) {
                $searchQueryBuilder->addSelect('cl.name as cname');

            }
            if (isset($fields['customer_group'])) {
                $searchQueryBuilder->addSelect('grl.name as customer_group')
                    ->leftJoin('cu', _DB_PREFIX_ . 'customer_group', 'cg', 'cu.id_customer= cg.id_customer AND cu.id_default_group=cg.id_group')
                    ->leftJoin('cg', _DB_PREFIX_ . 'group_lang', 'grl', 'grl.id_group= cg.id_group AND grl.id_lang="' . (int)$this->context->language->id . '"');
                $countQueryBuilder->leftJoin('cu', _DB_PREFIX_ . 'customer_group', 'cg', 'cu.id_customer= cg.id_customer AND cu.id_default_group=cg.id_group')
                    ->leftJoin('cg', _DB_PREFIX_ . 'group_lang', 'grl', 'grl.id_group= cg.id_group AND grl.id_lang="' . (int)$this->context->language->id . '"');
            }
            if (isset($fields['last_message']) || isset($fields['id_customer_thread']) || isset($fields['id_customer_message'])) {
                $searchQueryBuilder->addSelect('cm.message as last_message')
                    ->addSelect('cm.id_customer_thread')
                    ->addSelect('cm.id_customer_message')
                    ->leftJoin('o', _DB_PREFIX_ . 'customer_thread', 'ct', 'ct.id_order= o.id_order')
                    ->leftJoin('ct', '(SELECT max(id_customer_message) as id_customer_message, id_customer_thread,message FROM `' . _DB_PREFIX_ . 'customer_message` WHERE id_employee=0 GROUP BY id_customer_thread)', 'cm', 'cm.id_customer_thread=ct.id_customer_thread');
                $countQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'customer_thread', 'ct', 'ct.id_order= o.id_order')
                    ->leftJoin('ct', '(SELECT max(id_customer_message) as id_customer_message, id_customer_thread,message FROM `' . _DB_PREFIX_ . 'customer_message` WHERE id_employee=0 GROUP BY id_customer_thread)', 'cm', 'cm.id_customer_thread=ct.id_customer_thread');
            }
            if (isset($fields['shipping_cost_tax_excl']) || isset($fields['shipping_cost_tax_incl']) || isset($fields['id_order_carrier']) || isset($fields['tracking_number'])) {
                $searchQueryBuilder->addSelect('oca.shipping_cost_tax_excl')
                    ->addSelect('oca.shipping_cost_tax_incl')
                    ->addSelect('oca.id_order_carrier')
                    ->addSelect('oca.tracking_number')
                    ->leftJoin('o', _DB_PREFIX_ . 'order_carrier', 'oca', 'oca.id_order=o.id_order');
                $countQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'order_carrier', 'oca', 'oca.id_order=o.id_order');
            }
            if (isset($fields['images'])) {
                $searchQueryBuilder->addSelect('od.product_name')
                    ->leftJoin('o', _DB_PREFIX_ . 'order_detail', 'od', 'od.id_order=o.id_order');
                $countQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'order_detail', 'od', 'od.id_order=o.id_order');
            }
            $searchQueryBuilder
                ->addSelect('o.id_customer')
                ->addSelect('o.id_cart')
                ->addSelect('o.order_note')
                ->addSelect('o.current_state')
                ->addSelect('a.id_address')
                ->addSelect('a.address1 as address_delivery')
                ->addSelect('a.city')
                ->addSelect('a.company')
                ->addSelect('a.vat_number')
                ->addSelect('a.postcode')
                ->addSelect('o.id_address_invoice')
                ->addSelect('o.id_address_delivery')
                ->addSelect('c.id_country')
                ->addSelect('o.id_order AS id')
                ->addSelect('o.id_order AS id_pdf')
                ->addSelect('o.id_order as images')
                ->addSelect('cu.`firstname`')
                ->addSelect('cu.`lastname`')
                ->addSelect('cu.`email`')
                ->addSelect('IF(a.phone, a.`phone`, a.`phone_mobile`) as number_phone')
                ->addSelect('IF(a.phone, a.`phone`, a.`phone_mobile`) as phone')
                ->addSelect('o.id_carrier');
            if (isset($fields['fee']) && Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee')) {
                $searchQueryBuilder->addSelect('epo.fee');
                $searchQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'ets_paymentmethod_order', 'epo', 'epo.id_order = o.id_order');
            }
            // count
            if (isset($fields['fee']) && Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee')) {
                $countQueryBuilder->leftJoin('o', _DB_PREFIX_ . 'ets_paymentmethod_order', 'epo', 'epo.id_order = o.id_order');
            }

            if (Tools::isSubmit('viewtrash')) {
                $countQueryBuilder->andWhere('o.deleted=1');
                $searchQueryBuilder->andWhere('o.deleted=1');
            } else {
                $countQueryBuilder->andWhere('o.deleted!=1');
                $searchQueryBuilder->andWhere('o.deleted!=1');
            }
            if ($submit_filters = $this->getFilters()) {
                if (isset($submit_filters['order_note'])) {
                    $countQueryBuilder->andWhere('o.order_note LIKE "%' . pSQL($submit_filters['order_note']) . '%"');
                    $searchQueryBuilder->andWhere('o.order_note LIKE "%' . pSQL($submit_filters['order_note']) . '%"');
                }
                if (isset($submit_filters['caname'])) {
                    $countQueryBuilder->andWhere('carrier.name LIKE "%' . pSQL($submit_filters['caname']) . '%"');
                    $searchQueryBuilder->andWhere('carrier.name LIKE "%' . pSQL($submit_filters['caname']) . '%"');
                }
                if (isset($submit_filters['shipping_cost_tax_incl'])) {
                    $countQueryBuilder->andWhere('oca.shipping_cost_tax_incl = ' . (float)$submit_filters['shipping_cost_tax_incl']);
                    $searchQueryBuilder->andWhere('oca.shipping_cost_tax_incl = ' . (float)$submit_filters['shipping_cost_tax_incl']);
                }
                if (isset($submit_filters['email'])) {
                    $countQueryBuilder->andWhere('cu.email LIKE "%' . pSQL($submit_filters['email']) . '%"');
                    $searchQueryBuilder->andWhere('cu.email LIKE "%' . pSQl($submit_filters['email']) . '%"');
                }
                if (isset($submit_filters['number_phone'])) {
                    $countQueryBuilder->andWhere('(a.phone LIKE "%' . pSQL($submit_filters['number_phone']) . '%" OR a.phone_mobile LIKE "%' . pSQL($submit_filters['number_phone']) . '%")');
                    $searchQueryBuilder->andWhere('(a.phone LIKE "%' . pSQL($submit_filters['number_phone']) . '%" OR a.phone_mobile LIKE "%' . pSQL($submit_filters['number_phone']) . '%")');
                }
                if (isset($submit_filters['address_invoice'])) {
                    $countQueryBuilder->andWhere('address_invoice.address1 LIKE "%' . pSQL($submit_filters['address_invoice']) . '%"');
                    $searchQueryBuilder->andWhere('address_invoice.address1 LIKE "%' . pSQL($submit_filters['address_invoice']) . '%"');
                }
                if (isset($submit_filters['address_delivery'])) {
                    $countQueryBuilder->andWhere('a.address1 LIKE "%' . pSQL($submit_filters['address_delivery']) . '%"');
                    $searchQueryBuilder->andWhere('a.address1 LIKE "%' . pSQL($submit_filters['address_delivery']) . '%"');
                }
                if (isset($submit_filters['tracking_number'])) {
                    $countQueryBuilder->andWhere('oca.tracking_number LIKE "%' . pSQL($submit_filters['tracking_number']) . '%"');
                    $searchQueryBuilder->andWhere('oca.tracking_number LIKE "%' . pSQL($submit_filters['tracking_number']) . '%"');
                }
                if (isset($submit_filters['last_message'])) {
                    $countQueryBuilder->andWhere('cm.message LIKE "%' . pSQL($submit_filters['last_message']) . '%"');
                    $searchQueryBuilder->andWhere('cm.message LIKE "%' . pSQL($submit_filters['last_message']) . '%"');
                }
                if (isset($submit_filters['images'])) {
                    $countQueryBuilder->andWhere('od.product_name LIKE "%' . pSQL($submit_filters['images']) . '%"');
                    $searchQueryBuilder->andWhere('od.product_name LIKE "%' . pSQL($submit_filters['images']) . '%"');
                }
                if (isset($submit_filters['postcode'])) {
                    $countQueryBuilder->andWhere('a.postcode LIKE "%' . pSQL($submit_filters['postcode']) . '%"');
                    $searchQueryBuilder->andWhere('a.postcode LIKE "%' . pSQL($submit_filters['postcode']) . '%"');
                }
                if (isset($submit_filters['cname'])) {
                    $countQueryBuilder->andWhere('cl.name LIKE "%' . pSQL($submit_filters['cname']) . '%"');
                    $searchQueryBuilder->andWhere('cl.name LIKE "%' . pSQL($submit_filters['cname']) . '%"');
                }
                if (isset($submit_filters['city'])) {
                    $countQueryBuilder->andWhere('a.city LIKE "%' . pSQL($submit_filters['city']) . '%"');
                    $searchQueryBuilder->andWhere('a.city LIKE "%' . pSQL($submit_filters['city']) . '%"');
                }
                if (isset($submit_filters['company'])) {
                    $countQueryBuilder->andWhere('a.company LIKE "%' . pSQL($submit_filters['company']) . '%"');
                    $searchQueryBuilder->andWhere('a.company LIKE "%' . pSQL($submit_filters['company']) . '%"');
                }
                if (isset($submit_filters['customer_group'])) {
                    $countQueryBuilder->andWhere('grl.name LIKE "%' . pSQL($submit_filters['customer_group']) . '%"');
                    $searchQueryBuilder->andWhere('grl.name LIKE "%' . pSQL($submit_filters['customer_group']) . '%"');
                }
                if (isset($submit_filters['vat_number'])) {
                    $countQueryBuilder->andWhere('a.vat_number LIKE "%' . pSQL($submit_filters['vat_number']) . '%"');
                    $searchQueryBuilder->andWhere('a.vat_number LIKE "%' . pSQL($submit_filters['vat_number']) . '%"');
                }
                if (isset($submit_filters['id_cart'])) {
                    $countQueryBuilder->andWhere('o.id_cart = "' . (int)$submit_filters['id_cart'] . '"');
                    $searchQueryBuilder->andWhere('o.id_cart = "' . (int)$submit_filters['id_cart'] . '"');
                }
                //Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'admin_filter set filter=\'{"limit":'.(isset($submit_order['limit']) ? $submit_order['limit']:20).',"orderBy":"id_order","sortOrder":"DESC","filters":[]}\' WHERE filter_id="order" AND employee="'.(int)Context::getContext()->employee->id.'"');
            }

            if (($bulk_orders = $this->bulk_orders) && isset($bulk_orders['export_all_order'])) {
                if (isset($bulk_orders['order_orders_bulk']) && $bulk_orders['order_orders_bulk']) {
                    $countQueryBuilder->andWhere('o.id_order in (' . implode(',', array_map('intval', $bulk_orders['order_orders_bulk'])) . ')');
                    $searchQueryBuilder->andWhere('o.id_order in (' . implode(',', array_map('intval', $bulk_orders['order_orders_bulk'])) . ')');
                }
            }
            $orderbys = array('order_note', 'caname', 'shipping_cost_tax_incl', 'email', 'number_phone', 'address_invoice', 'address_delivery', 'tracking_number', 'last_message');
            if (Tools::isSubmit('order') && ($order_by = Tools::getValue('order')) && isset($order_by['orderBy']) && in_array($order_by['orderBy'], $orderbys)) {
                $searchQueryBuilder->addOrderBy($order_by['orderBy'], isset($order_by['sortOrder']) ? Tools::strtoupper($order_by['sortOrder']) : 'DESC');
            }
            $searchQueryBuilder->addGroupBy('o.id_order');
        }
    }

    private function getRowActions()
    {
        $rowActions = (new PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection())
            ->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('print_invoice'))
                    ->setName($this->l('View invoice'))
                    ->setIcon('receipt')
                    ->setOptions([
                        'accessibility_checker' => new PrestaShop\PrestaShop\Core\Grid\Action\Row\AccessibilityChecker\PrintInvoiceAccessibilityChecker(),
                        'route' => 'admin_orders_generate_invoice_pdf',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                        'use_inline_display' => true,
                    ])
            )
            ->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('print_delivery_slip'))
                    ->setName($this->l('View delivery slip'))
                    ->setIcon('local_shipping')
                    ->setOptions([
                        'accessibility_checker' => new PrestaShop\PrestaShop\Core\Grid\Action\Row\AccessibilityChecker\PrintDeliverySlipAccessibilityChecker(),
                        'route' => 'admin_orders_generate_delivery_slip_pdf',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                        'use_inline_display' => true,
                    ])
            )
            ->add(
                (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('view'))
                    ->setName($this->l('View'))
                    ->setIcon('zoom_in')
                    ->setOptions([
                        'route' => 'admin_orders_view',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                        'use_inline_display' => false,
                        'clickable_row' => true,
                    ])
            );
        if (Tools::isSubmit('viewtrash')) {
            if ($this->checkAccess('delete'))
                $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('delete'))
                    ->setName($this->l('Delete'))
                    ->setIcon('delete')
                    ->setOptions([
                        'route' => 'admin_orders_delete',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                        'confirm_message' => Tools::isSubmit('viewtrash') || Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently' ? $this->l('You are going to delete this order permanently and will not be able to restore it. Do you want to delete this order?') : $this->l('Order will be removed from list and moved to Trash, do you want to remove it?'),
                    ])
                );
            if ($this->checkAccess('update'))
                $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('restore'))
                    ->setName($this->l('Restore'))
                    ->setIcon('refresh')
                    ->setOptions([
                        'route' => 'admin_orders_restore',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                    ])
                );
        } else {
            if ($this->checkAccess('update')) {
                $rowActions->add(
                    (new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('edit'))
                        ->setName('Edit')
                        ->setIcon('edit')
                        ->setOptions([
                            'route' => 'admin_orders_edit',
                            'route_param_name' => 'orderId',
                            'route_param_field' => 'id_order',
                        ])
                );
            }
            if ($this->checkAccess('delete')) {
                $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('delete'))
                    ->setName($this->l('Delete'))
                    ->setIcon('delete')
                    ->setOptions([
                        'route' => 'admin_orders_delete',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                        'confirm_message' => Tools::isSubmit('viewtrash') || Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently' ? $this->l('You are going to delete this order permanently and will not be able to restore it. Do you want to delete this order?') : $this->l('Order will be removed from list and moved to Trash, do you want to remove it?'),
                    ])
                );
            }
            if ($this->checkAccess('create')) {
                $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('duplicate'))
                    ->setName($this->l('Duplicate'))
                    ->setIcon('content_copy')
                    ->setOptions([
                        'route' => 'admin_orders_duplicate',
                        'route_param_name' => 'orderId',
                        'route_param_field' => 'id_order',
                    ])
                );
            }
        }
        $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('print_shipping_label'))
            ->setName($this->l('Shipping label'))
            ->setIcon('fa fa-truck')
            ->setOptions([
                'route' => 'admin_orders_print_label_delivery',
                'route_param_name' => 'orderId',
                'route_param_field' => 'id_order',
            ])
        );
        $rowActions->add((new PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction('login_as_customer'))
            ->setName($this->l('Login as customer'))
            ->setIcon('fa fa-user')
            ->setOptions([
                'route' => 'admin_orders_login_as_customer',
                'route_param_name' => 'orderId',
                'route_param_field' => 'id_order',
            ])
        );
        return $rowActions;
    }

    public function hookDisplayBlockInputChangeInLine($params)
    {
        $controller = Tools::getValue('controller');
        if (($controller == 'AdminOrders' || $controller == 'adminorders') && isset($params['record']) && $params['record'] && isset($params['column']) && $params['column'] && isset($params['column']['id'])) {
            $tr = $params['record'];
            $key = $params['column']['id'];
            if (!isset($this->fields_list) || !$this->fields_list)
                $this->fields_list = $this->getFieldsListOrder();
            $fields_list = $this->fields_list;
            if (isset($fields_list[$key])) {
                $this->context->smarty->assign(
                    array(
                        'tr' => $tr,
                        'params' => $fields_list[$key],
                    )
                );
                return $this->display(__FILE__, 'input_change_inline.tpl');
            }
        }
    }

    public function hookActionOrderGridDataModifier($params)
    {
        $controller = Tools::getValue('controller');
        if ($controller != 'AdminOrders' && $controller != 'adminorders')
            return '';
        $data = &$params['data'];
        $results = $data->getRecords();
        $datas = array();
        foreach ($results as $result) {
            if (isset($result['shipping_cost_tax_excl'])) {
                $result['shipping_cost_tax_excl'] = Tools::displayPrice($result['shipping_cost_tax_excl'], (new Currency($result['id_currency'])));
                $result['shipping_cost_tax_incl'] = Tools::displayPrice($result['shipping_cost_tax_incl'], (new Currency($result['id_currency'])));
            }
            $datas[] = $result;
        }
        if (($bulk_orders = $this->bulk_orders) && isset($bulk_orders['export_all_order'])) {
            $fields_list = $this->getFieldsListOrder();
            if (isset($bulk_orders['order_orders_bulk']) && $bulk_orders['order_orders_bulk']) {
                $this->exportOrderToCSV($fields_list, $datas);
            } else
                $this->exportOrderToCSV($fields_list, false);
        }
        $recordCollection = new PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection($datas);
        $gridData = new PrestaShop\PrestaShop\Core\Grid\Data\GridData($recordCollection, $data->getRecordsTotal(), $data->getQuery());
        $data = $gridData;
    }

    public function getContent()
    {
        if (Tools::isSubmit('clear_log_cronjob')) {
            if (file_exists(dirname(__FILE__) . '/cronjob.log'))
                if (@unlink(dirname(__FILE__) . '/cronjob.log')) {
                    die(
                    json_encode(
                        array(
                            'success' => $this->l('Cleared cronjob log successfully'),
                        )
                    )
                    );
                } else
                    die(
                    json_encode(
                        array(
                            'error' => $this->l('Clear cronjob log error'),
                        )
                    )
                    );
            else
                die(
                json_encode(
                    array(
                        'success' => $this->l('Cleared cronjob log successfully'),
                    )
                )
                );
        }
        if (Tools::isSubmit('runCronJob'))
            $this->_runCronJob();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->processPost();
        if (($action = Tools::getValue('action')) && $action == 'updatePaymentOrdering')
            $this->updatePositionPayment();
        if (Tools::isSubmit('saveConfig')) {
            $this->postConfigs();
        }
        return ($this->_errors ? $this->displayError($this->_errors) : '') . $this->getAdminHtml();
    }

    public function processPost()
    {
        // search product.
        if (($query = Tools::getValue('q')) && $query && Validate::isCleanHtml($query)) {
            $imageType = $this->getMmType('cart');
            if ($pos = strpos($query, ' (ref:')) {
                $query = Tools::substr($query, 0, $pos);
            }
            $excludeIds = Tools::getValue('excludeIds');
            $excludedProductIds = array();
            if ($excludeIds && Validate::isCleanHtml($excludeIds) && $excludeIds != 'NaN') {
                $excludeIds = implode(',', array_map(array($this, 'isValidIds'), explode(',', $excludeIds)));
                if ($excludeIds && ($ids = explode(',', $excludeIds))) {
                    foreach ($ids as $id) {
                        $id = explode('-', $id);
                        if (isset($id[0]) && isset($id[1]) && !$id[1]) {
                            $excludedProductIds[] = (int)$id[0];
                        }
                    }
                }
            } else {
                $excludeIds = false;
            }
            $excludeVirtuals = (bool)Tools::getValue('excludeVirtuals', false);
            $exclude_packs = (bool)Tools::getValue('exclude_packs', false);
            if (version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
                $imgLeftJoin = ' LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`) ' . Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1');
            } else {
                $imgLeftJoin = ' LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ') ';
            }
            $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
            		FROM `' . _DB_PREFIX_ . 'product` p
            		' . Shop::addSqlAssociation('product', 'p') . '
                    LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int)$this->context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
            		' . pSQL($imgLeftJoin) . ' 
            		LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int)$this->context->language->id . ')
            		WHERE ' . ($excludedProductIds ? 'p.`id_product` NOT IN(' . pSQL(implode(',', $excludedProductIds)) . ') AND ' : '') . ' (pl.name LIKE "%' . pSQL($query) . '%" OR p.reference LIKE "%' . pSQL($query) . '%" OR p.id_product = ' . (int)$query . ')' .
                ($excludeVirtuals ? ' AND NOT EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
                ($exclude_packs ? ' AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
                ($imgLeftJoin ? 'AND image_shop.cover = 1' : '') . ' GROUP BY p.id_product';
            if (($items = Db::getInstance()->executeS($sql))) {
                $results = array();
                foreach ($items as $item) {
                    if (Combination::isFeatureActive() && (int)$item['cache_default_attribute']) {
                        $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, ag.`id_attribute_group`, pai.`id_image`, agl.`name` AS group_name, al.`name` AS attribute_name, NULL as `attribute`, a.`id_attribute`
            					FROM `' . _DB_PREFIX_ . 'product_attribute` pa
            					' . Shop::addSqlAssociation('product_attribute', 'pa') . '
            					LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
            					LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
            					LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
            					LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int)$this->context->language->id . ')
            					LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int)$this->context->language->id . ')
            					LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
            					WHERE pa.`id_product` = ' . (int)$item['id_product'] . ($excludeIds ? ' AND NOT FIND_IN_SET(CONCAT(pa.`id_product`,"-", IF(pa.`id_product_attribute` IS NULL OR od.`product_id` = '.(int)Configuration::get('PH_EXTEND_ID_PRODUCT').',0,pa.`id_product_attribute`)), "' . pSQL($excludeIds) . '")' : '') . '
            					GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
            					ORDER BY pa.`id_product_attribute`';
                        if (($combinations = Db::getInstance()->executeS($sql))) {
                            foreach ($combinations as $combination) {
                                $results[$combination['id_product_attribute']]['id_product'] = $item['id_product'];
                                $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                                $results[$combination['id_product_attribute']]['name'] = $item['name'];
                                // get name attribute with combination
                                !empty($results[$combination['id_product_attribute']]['attribute']) ? $results[$combination['id_product_attribute']]['attribute'] .= ' ' . $combination['group_name'] . '-' . $combination['attribute_name']
                                    : $results[$combination['id_product_attribute']]['attribute'] = $item['attribute'] . ' ' . $combination['group_name'] . '-' . $combination['attribute_name'];
                                // get reference combination
                                if (!empty($combination['reference'])) {
                                    $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                                } else {
                                    $results[$combination['id_product_attribute']]['ref'] = !empty($item['reference']) ? $item['reference'] : '';
                                }
                                // get image combination
                                if (empty($results[$combination['id_product_attribute']]['image'])) {
                                    $results[$combination['id_product_attribute']]['image'] = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], (!empty($combination['id_image']) ? (int)$combination['id_image'] : (int)$item['id_image']), $imageType));
                                }
                            }
                        }
                    } else {
                        $results[] = array(
                            'id_product' => (int)($item['id_product']),
                            'id_product_attribute' => 0,
                            'name' => $item['name'],
                            'attribute' => '',
                            'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                            'image' => str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], $item['id_image'], $imageType)),
                        );
                    }
                }
                if ($results) {
                    foreach ($results as &$item) {
                        echo trim($item['id_product'] . '|' . (int)($item['id_product_attribute']) . '|' . Tools::ucfirst($item['name']) . '|' . $item['attribute'] . '|' . $item['ref'] . '|' . $item['image']) . "\n";
                    }
                }
            }
            die;
        }
        if (($action = Tools::getValue('action')) && $action == 'odeAddProduct' && ($IDs = Tools::getValue('ids', false)) && Validate::isCleanHtml($IDs)) {
            die(json_encode(array(
                'html' => $this->hookDisplayOdeProductList(array('ids' => $IDs)),
            )));
        }
    }

    public function getMmType($image_type)
    {
        if (!$image_type)
            return 'cart';
        return $this->is17 ? ImageType::getFormattedName($image_type) : ImageType::getFormatedName($image_type);
    }

    public function hookActionObjectLanguageAddAfter()
    {
        $this->createTemplateMail();
    }

    public function hookDisplayOdeProductList($params)
    {
        if (isset($params['ids']) && ($productIds = $params['ids'])) {
            $IDs = explode(',', $productIds);
            $products = array();
            foreach ($IDs as $ID) {
                if ($ID && ($tmpIDs = explode('-', $ID))) {
                    $products[] = array(
                        'id_product' => $tmpIDs[0],
                        'id_product_attribute' => !empty($tmpIDs[1]) ? $tmpIDs[1] : 0,
                    );
                }
            }
            if ($products) {
                $products = $this->getBlockProducts($products);
            }
            $this->smarty->assign('products', $products);
            return $this->display(__FILE__, 'product-item.tpl');
        }
    }

    public function hookDisplayOdeCustomerList($params)
    {
        if (isset($params['ids']) && ($customerIds = $params['ids'])) {
            $IDs = explode(',', $customerIds);
            $customers = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'customer` WHERE id_customer IN (' . implode(',', array_map('intval', $IDs)) . ')');
            $this->smarty->assign('customers', $customers);
            return $this->display(__FILE__, 'list-customers.tpl');
        }
    }

    public function getBlockProducts($products)
    {
        if (!$products)
            return false;
        if (!is_array($products)) {
            $IDs = explode(',', $products);
            $products = array();
            foreach ($IDs as $ID) {
                if ($ID && ($tmpIDs = explode('-', $ID))) {
                    $products[] = array(
                        'id_product' => $tmpIDs[0],
                        'id_product_attribute' => !empty($tmpIDs[1]) ? $tmpIDs[1] : 0,
                    );
                }
            }
        }
        if ($products) {
            $context = Context::getContext();
            $id_group = isset($context->customer->id) && $context->customer->id ? Customer::getDefaultGroupId((int)$context->customer->id) : (int)Group::getCurrent()->id;
            $group = new Group($id_group);
            $useTax = $group->price_display_method ? false : true;
            foreach ($products as &$product) {
                $p = new Product($product['id_product'], true, $this->context->language->id, $this->context->shop->id);
                $product['link_rewrite'] = $p->link_rewrite;
                $product['price'] = Tools::displayPrice($p->getPrice($useTax, $product['id_product_attribute'] ? $product['id_product_attribute'] : null));
                if (($oldPrice = $p->getPriceWithoutReduct(!$useTax, $product['id_product_attribute'] ? $product['id_product_attribute'] : null)) && $oldPrice != $product['price']) {
                    $product['price_without_reduction'] = Tools::convertPrice($oldPrice);
                }
                if (isset($product['price_without_reduction']) && $product['price_without_reduction'] != $product['price']) {
                    $product['specific_prices'] = $p->specificPrice;
                }
                if (isset($product['specific_prices']) && $product['specific_prices'] && $product['specific_prices']['to'] != '0000-00-00 00:00:00') {
                    $product['specific_prices_to'] = $product['specific_prices']['to'];
                }
                $product['name'] = $p->name;
                $product['description_short'] = $p->description_short;
                $image = ($product['id_product_attribute'] && ($image = self::getCombinationImageById($product['id_product_attribute'], $context->language->id))) ? $image : Product::getCover($product['id_product']);
                $product['link'] = $context->link->getProductLink($product, null, null, null, null, null, $product['id_product_attribute'] ? $product['id_product_attribute'] : 0);
                //if (!$this->is17 || $this->context->controller->controller_type == 'admin') {
                $product['add_to_cart_url'] = isset($context->customer) && $this->is17 ? $context->link->getAddToCartURL((int)$product['id_product'], (int)$product['id_product_attribute']) : '';
                $imageType = $this->getMmType('cart');
                $product['image'] = $context->link->getImageLink($p->link_rewrite, isset($image['id_image']) ? $image['id_image'] : 0, $imageType);
                $product['price_tax_exc'] = Product::getPriceStatic((int)$product['id_product'], false, (int)$product['id_product_attribute'], (!$useTax ? 2 : 6), null, false, true, $p->minimal_quantity);
                $product['available_for_order'] = $p->available_for_order;
                if ($product['id_product_attribute']) {
                    $p->id_product_attribute = $product['id_product_attribute'];
                    $product['attributes'] = $p->getAttributeCombinationsById((int)$product['id_product_attribute'], $context->language->id);
                }
                //}
                $product['id_image'] = isset($image['id_image']) ? $image['id_image'] : 0;
                if ($this->is17 && $this->context->controller->controller_type != 'admin') {
                    $product['image_id'] = $product['id_image'];
                }
                $product['is_available'] = $p->checkQty(1);
                $product['allow_oosp'] = Product::isAvailableWhenOutOfStock($p->out_of_stock);
                $product['show_price'] = $p->show_price;
                if (!$this->is17) {
                    $product['out_of_stock'] = $p->out_of_stock;
                    $product['id_category_default'] = $p->id_category_default;
                    $product['ean13'] = $p->ean13;
                }
            }
            unset($context);
        }
        $controller = Tools::getValue('controller');
        if ($products && $this->context->controller->controller_type != 'admin' && $controller != 'AdminOrderManagerExports') {
            return $this->is17 ? $this->productsForTemplate($products, $this->context) : Product::getProductsProperties($this->context->language->id, $products);
        }
        return $products;
    }

    public static function getCombinationImageById($id_product_attribute, $id_lang)
    {
        if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
            return Product::getCombinationImageById($id_product_attribute, $id_lang);
        } else {
            if (!Combination::isFeatureActive() || !$id_product_attribute) {
                return false;
            }
            $result = Db::getInstance()->executeS('
                SELECT pai.`id_image`, pai.`id_product_attribute`, il.`legend`
                FROM `' . _DB_PREFIX_ . 'product_attribute_image` pai
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (il.`id_image` = pai.`id_image`)
                LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_image` = pai.`id_image`)
                WHERE pai.`id_product_attribute` = ' . (int)$id_product_attribute . ' AND il.`id_lang` = ' . (int)$id_lang . ' ORDER by i.`position` LIMIT 1'
            );
            if (!$result) {
                return false;
            }
            return $result[0];
        }
    }

    public function productsForTemplate($products)
    {
        if (!$products || !is_array($products))
            return array();
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $products_for_template = array();
        foreach ($products as $item) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($item),
                $this->context->language
            );
        }
        return $products_for_template;
    }

    public function updatePositionPayment()
    {
        if (($payment = Tools::getValue('payment')) && Ets_ordermanager::validateArray($payment, 'isInt')) {
            foreach ($payment as $key => $id) {
                Db::getInstance()->execute('update `' . _DB_PREFIX_ . 'ets_export_order_rule` set position="' . (int)$key . '" WHERE id_ets_export_order_rule=' . (int)$id);
            }
        }
    }

    public function actionExportOrSendOrder($args = array())
    {
        $order_exporter = isset($args['obj']) && Validate::isLoadedObject($args['obj']) ? $args['obj'] : false;
        if (!$order_exporter) {
            $id_ets_export_order_rule = isset($args['id']) && $args['id'] ? (int)$args['id'] : 0;
            if (!$id_ets_export_order_rule)
                return false;
            $order_exporter = new Ode_export($id_ets_export_order_rule, $this->context->language->id);
        }
        $filename = '';
        if ($order_exporter->exported_fields)
            $exported_fields = $order_exporter->exported_fields;
        else
            $exported_fields = 'o.delivery_number,o.id_order,o.reference,o.id_shop,o.id_lang,o.id_cart,o.id_currency,o.total_paid_tax_incl,o.total_paid_tax_excl,o.invoice_number,o.date_add,o.date_upd,o.id_customer,c.firstname,c.lastname,c.email,cl.name,s.name,od.product_id,od.product_name,od.product_quantity,o.product_quantity_in_stock,od.product_ean13,od.product_upc,od.product_reference,od.product_price,od.reduction_amount_tax_incl,od.original_product_price,od.product_supplier_reference,od.product_weight,od.tax_rate,od.total_price_tax_incl,od.total_price_tax_excl,o.id_carrier,o.total_shipping_tax_incl,o.total_shipping_tax_excl,o.delivery_number,o.delivery_date,oc.tracking_number,osl.name';
        if (!Module::isInstalled('ets_payment_with_fee') || !Module::isEnabled('ets_payment_with_fee')) {
            if (Tools::strpos($exported_fields, 'pam.fee,') !== false)
                $exported_fields = str_replace('pam.fee,', '', $exported_fields);
            else
                $exported_fields = trim(str_replace('pam.fee', '', $exported_fields), ',');
        }
        //build filter.
        $filter = 'WHERE o.id_shop=' . (int)$this->context->shop->id;
        if ($order_exporter->specific_customer)
            $filter .= ' AND c.id_customer IN (' . implode(',', array_map('intval', explode(',', $order_exporter->specific_customer))) . ')';
        else {
            if ($order_exporter->customer_group && trim($order_exporter->customer_group) != 'all') {
                $filter .= ' AND cg.id_group IN (' . implode(',', array_map('intval', explode(',', $order_exporter->customer_group))) . ')';
            }
            if ($order_exporter->id_country && trim($order_exporter->id_country) != 'all') {
                $filter .= ' AND cl.id_country IN (' . implode(',', array_map('intval', explode(',', $order_exporter->id_country))) . ')';
            }
        }
        $specific_order = str_replace(' ', '', trim($order_exporter->specific_order));
        if ($specific_order != '') {
            $specific_order = explode(',', $specific_order);
            $filter_order = '';
            if ($specific_order) {
                foreach ($specific_order as $order) {
                    if ($order) {
                        if (Tools::strpos($order, '-') === false)
                            $filter_order .= ' OR o.id_order = "' . (int)$order . '"';
                        elseif (($ids = explode('-', $order)) && Count($ids) == 2 && ($id1 = (int)trim($ids[0])) && ($id2 = (int)trim($ids[1]))) {
                            $filter_order .= ' OR (o.id_order >= ' . (int)$id1 . ' AND o.id_order <= ' . (int)$id2 . ')';
                        }
                    }

                }
            }
            if ($filter_order)
                $filter .= ' AND (0 ' . $filter_order . ')';
        }
        if ($order_exporter->order_carrier && trim($order_exporter->order_carrier) != 'all') {
            $filter .= ' AND ca.id_reference IN (' . implode(',', array_map('intval', explode(',', $order_exporter->order_carrier))) . ')';
        }
        if ($order_exporter->order_status && trim($order_exporter->order_status) != 'all') {
            $filter .= ' AND o.current_state IN (' . implode(',', array_map('intval', explode(',', $order_exporter->order_status))) . ')';
        }
        //payment methods
        if ($order_exporter->payment_method && trim($order_exporter->payment_method) != 'all') {
            $methods = array();
            if (($payment_methods = explode(',', $order_exporter->payment_method))) {
                foreach ($payment_methods as $method) {
                    $methods[] = $method;
                }
            }
            if ($methods)
                $filter .= ' AND o.module IN ("' . implode('","', $methods) . '")';
        }

        //manus
        if ($order_exporter->manufacturer && trim($order_exporter->manufacturer) != 'all') {
            $manus = array();
            if (($manufacturers = explode(',', $order_exporter->manufacturer))) {
                foreach ($manufacturers as $manu) {
                    $manus[] = (int)$manu;
                }
            }
            if ($manus)
                $filter .= ' AND mn.id_manufacturer IN (' . implode(',', $manus) . ') ';
        }
        //sups
        if ($order_exporter->supplier && trim($order_exporter->supplier) != 'all') {
            $sups = array();
            if (($suppliers = explode(',', $order_exporter->supplier))) {
                foreach ($suppliers as $sup) {
                    $sups[] = (int)$sup;
                }
            }
            if ($sups)
                $filter .= ' AND supp.id_supplier IN (' . implode(',', $sups) . ') ';
        }
        //cats
        if (trim($order_exporter->category)) {
            $cats = array();
            if (($categories = explode(',', $order_exporter->category))) {
                foreach ($categories as $cat) {
                    $cats[] = (int)$cat;
                }
            }
            if ($cats)
                $filter .= ' AND (cp.id_category IN (' . implode(',', $cats) . ') OR od.product_id="' . (int)Configuration::get('PH_EXTEND_ID_PRODUCT') . '") ';
        }
        //specific_product
        if (trim($order_exporter->specific_product)) {
            $filter .= ' AND FIND_IN_SET(CONCAT(od.`product_id`,"-", IF(od.`product_attribute_id` is NULL OR od.`product_id` = '.(int)Configuration::get('PH_EXTEND_ID_PRODUCT').', 0, od.`product_attribute_id`)), "' . pSQL($order_exporter->specific_product) . '") ';
        }
        if (trim($order_exporter->spent_from) != '') {
            $filter .= ' AND o.total_paid_tax_incl >= ' . (float)$order_exporter->spent_from . '*cu.conversion_rate';
        }
        if (trim($order_exporter->spent_to) != '') {
            $filter .= ' AND o.total_paid_tax_incl <= ' . (float)$order_exporter->spent_to . '*cu.conversion_rate';
        }
        if ($order_exporter->date_type == 'this_month') {
            $filename = 'this_month_';
            $filter .= ' AND MONTH(o.date_add) = ' . (int)date('m') . ' AND YEAR(o.date_add) = ' . (int)date('Y');
        } elseif ($order_exporter->date_type == 'this_year') {
            $filename = 'this_year_';
            $filter .= ' AND YEAR(o.date_add) = ' . (int)date('Y');
        } elseif ($order_exporter->date_type == 'month_1') {
            $filename = 'last_month_';
            $prevmonth = (int)date('m') - 1;
            if ($prevmonth < 1)
                $prevmonth = 12;
            $year = date('Y');
            if ($prevmonth >= 12)
                $year -= 1;
            $filter .= ' AND MONTH(o.date_add) = ' . (int)$prevmonth . ' AND YEAR(o.date_add) = ' . (int)$year;
        } elseif ($order_exporter->date_type == 'year_1') {
            $filename = 'last_year_';
            $prevyear = (int)date('Y') - 1;
            $filter .= ' AND YEAR(o.date_add) = ' . (int)$prevyear;
        }
        if ($order_exporter->date_type == 'from_to') {
            $filename = 'from_to_';
        }
        if ($order_exporter->date_type == 'from_to' && $order_exporter->from_date != '0000-00-00' && $order_exporter->from_date) {
            $filter .= ' AND o.date_add >="' . pSQL($order_exporter->from_date) . ' 00:00:00"';
        }
        if ($order_exporter->date_type == 'from_to' && $order_exporter->to_date != '0000-00-00' && $order_exporter->to_date) {
            $filter .= ' AND o.date_add <="' . pSQL($order_exporter->to_date) . ' 23:59:59"';
        }
        if ($order_exporter->date_type == 'day_before' && $order_exporter->day_before) {
            $filter .= ' AND o.date_add <="' . pSQL(date('Y-m-d 23:59:59', strtotime('-' . (int)$order_exporter->day_before . ' day'))) . '" AND o.date_add >="' . pSQL(date('Y-m-d 00:00:00', strtotime('-' . (int)$order_exporter->day_before . ' day'))) . '"';
        }
        if ($order_exporter->date_type == 'day_before') {
            $filename = 'the_day_before_';
        }
        if ($order_exporter->date_type == 'any_date')
            $filename = 'any_date_';
        if ($order_exporter->date_type == 'today') {
            $filename = 'today_';
            $filter .= ' AND o.date_add >= "' . pSQL(date('Y-m-d')) . ' 00:00:00"';
            $filter .= ' AND o.date_add <= "' . pSQL(date('Y-m-d')) . ' 23:59:59"';
        }
        if ($order_exporter->date_type == 'yesterday') {
            $filename = 'yesterday_';
            $filter .= ' AND o.date_add >= "' . pSQL(date('Y-m-d', strtotime('-1 day'))) . ' 00:00:00"';
            $filter .= ' AND o.date_add <= "' . pSQL(date('Y-m-d', strtotime('-1 day'))) . ' 23:59:59"';
        }
        //config order before export 2.
        $orders = $this->getOrderExport($exported_fields, $filter, $order_exporter->sort_by, $order_exporter->convert_in_currency);
        $filename = $order_exporter->file_name_prefix . ($order_exporter->file_name_incl_name_rule ? $order_exporter->name . '_' : '') . $filename;
        $schedule = isset($args['schedule']) ? trim($args['schedule']) : false;
        $sendmail = isset($args['sendmail']) ? $args['sendmail'] : false;
        $log = isset($args['log']) ? $args['log'] : false;
        $args = array(
            'order_exporter' => $order_exporter,
            'filename' => $filename,
            'orders' => $orders,
        );
        $ok = false;
        if ($sendmail || ($schedule == 'auto' && $order_exporter->send_file_via_email && $this->exportValid($order_exporter->send_file_schedule, $order_exporter->send_file_time_hours, $order_exporter->send_file_time_weeks, $order_exporter->send_file_time_months, $order_exporter->send_file_date))) {
            {
                $args_mail = $args;
                if ($order_exporter->send_file_filter == 'new' && $order_exporter->send_file_date && $order_exporter->send_file_date != '0000-00-00 00:00:00') {

                    $args_mail['orders'] = $this->getOrderExport($exported_fields, $filter . ' AND o.date_add > "' . pSQL($order_exporter->send_file_date) . '"', $order_exporter->sort_by, $order_exporter->convert_in_currency);
                }
                if ($this->mailSend($args_mail, $log))
                    $ok = true;
            }
        } elseif (!$sendmail && !$schedule) {
            $args['display'] = true;
            if ($this->exportOrder($args))
                $ok = true;
        }
        if ($schedule) {
            $args['schedule'] = $schedule;
            $args['out_file'] = $this->exportOrder($args);
            $args['filter'] = $filter;
            $args['exported_fields'] = $exported_fields;
            if ($this->uploadFiles($args, $log))
                $ok = true;
        }
        return $ok;
    }

    public function deleteExportedFiles($id = 0)
    {
        if (!$id)
            return;
        $order_export = new Ode_export($id);
        if ($order_export->id && $order_export->export_to_server1 && $order_export->delete_exported_files != 'never' && ($files = glob(_PS_ROOT_DIR_ . $this->pathFormat($order_export->directory_path1) . 'orders_' . $order_export->file_name_prefix . '*' . $order_export->date_type . '_*_' . $order_export->id . '.' . $order_export->file_format))) {
            foreach ($files as $file) {
                if (@file_exists($file)) {
                    $date_time = basename($file, '.' . $order_export->file_format);
                    $date_time = preg_replace('/^orders_' . $order_export->file_name_prefix . '(.*)?' . $order_export->date_type . '_*|_' . $order_export->id . '$/', '', $date_time);
                    $date_time = explode('_', $date_time);
                    $date_time = strtotime(implode('-', array_slice($date_time, 0, 3)) . ' ' . implode(':', array_slice($date_time, 3, count($date_time) - 1)));
                    $time_day = 24 * 3600;
                    switch ($order_export->delete_exported_files) {
                        case '1_week_old':
                            if (time() - $date_time >= 7 * $time_day)
                                @unlink($file);
                            break;
                        case '1_month':
                            if (time() - $date_time >= 30 * $time_day)
                                @unlink($file);
                            break;
                        case '3_month':
                            if (time() - $date_time >= 90 * $time_day)
                                @unlink($file);
                            break;
                        case '6_month':
                            if (time() - $date_time >= 180 * $time_day)
                                @unlink($file);
                            break;
                        case '1_year':
                            if (time() - $date_time >= 365 * $time_day)
                                @unlink($file);
                            break;
                    }
                }
            }
        }
    }

    public function exportValid($schedule, $hours, $weeks, $months, $date_log)
    {
        if (!$schedule)
            return false;
        $date_log = strtotime($date_log);
        if (time() <= $date_log)
            return false;
        switch ($schedule) {
            case 'daily':
                return ((int)$hours == (int)date('H') && (int)date('d') != (int)date('d', $date_log));
            case 'hourly':
                return (int)date('H') != (int)date('H', $date_log);
            case '5_minutes':
                return $date_log <= strtotime("-5 minutes");
            case '30_minutes':
                return $date_log <= strtotime("-30 minutes");
            case 'weekly':
                return ((int)$hours == (int)date('H') && (int)$weeks == (int)date('w') && (int)date('W') != (int)date('W', $date_log));
            case 'monthly':
                if ($months == 'start')
                    return date('d') == 1 && (int)$hours == (int)date('H') && (int)date('m') != (int)date('m', $date_log);
                elseif ($months == 'middle')
                    return (date('d') == 15) && (int)$hours == (int)date('H') && (int)date('m') != (int)date('m', $date_log);
                else
                    return (date('d') == (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')))) && (int)$hours == (int)date('H') && (int)date('m') != (int)date('m', $date_log);
                break;
        }
        return false;
    }

    public function exportOrder($args)
    {
        $order_exporter = isset($args['order_exporter']) ? $args['order_exporter'] : false;
        if (!$order_exporter)
            return;
        if ($order_exporter->file_format == 'xml') {
            return $this->exportOrderXML($args);
        } elseif ($order_exporter->file_format == 'xls') {
            return $this->exportOrderXLS($args);
        } elseif ($order_exporter->file_format == 'xlsx') {
            return $this->exportOrderXLSX($args);
        } else {
            return $this->exportOrderCSV($args);
        }
    }

    public function mailSend($args = array(), $log = false)
    {
        $order_exporter = isset($args['order_exporter']) ? $args['order_exporter'] : 0;
        if (!$order_exporter)
            return;
        $cron_job = false;
        $filename = isset($args['filename']) ? $args['filename'] : '';
        if ($order_exporter->receivers_mail && ($receivers_mail = explode(',', $order_exporter->receivers_mail))) {
            $template_vars = array(
                '{description_email}' => $order_exporter->description_mail ? str_replace('[date]', date('d_m_Y'), $order_exporter->description_mail) : $this->l('Here are orders exported on ') . date('d_m_Y'),
            );
            $file_attachment = array();
            if ($order_exporter->file_format == 'csv') {
                $file_attachment['content'] = $this->exportOrderCSV($args);
                $file_attachment['name'] = $filename . date('d_m_Y') . '.csv';
                $file_attachment['mime'] = 'application/csv';
            } elseif (trim($order_exporter->file_format) == 'xlsx') {
                $file_attachment['content'] = $this->exportOrderXLSX($args);
                $file_attachment['name'] = $filename . date('d_m_Y') . '.xlsx';
                $file_attachment['mime'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            } elseif (trim($order_exporter->file_format) == 'xls') {
                $file_attachment['content'] = $this->exportOrderXLS($args);
                $file_attachment['name'] = $filename . date('d_m_Y') . '.xls';
                $file_attachment['mime'] = 'application/xls';
            } else {
                $file_attachment['content'] = $this->exportOrderXML($args);
                $file_attachment['name'] = $filename . date('d_m_Y') . '.xml';
                $file_attachment['mime'] = 'text/xml';
            }
            foreach ($receivers_mail as $email) {
                if (!($id_lang = (int)Db::getInstance()->getValue('SELECT id_lang FROM `' . _DB_PREFIX_ . 'employee` WHERE email ="' . pSQL($email) . '"')))
                    $id_lang = $this->context->language->id;
                if (Mail::Send(
                    (int)$id_lang,
                    'order_export',
                    $order_exporter->title_mail ? str_replace('[date]', date('d/m/Y'), $order_exporter->title_mail) : ($this->getTextLang('Order on ', $id_lang) ?: $this->l('Order on ')) . date('dmY'),
                    $template_vars,
                    trim($email),
                    null,
                    null,
                    null,
                    $file_attachment,
                    null,
                    dirname(__FILE__) . '/mails/',
                    null,
                    $this->context->shop->id
                )) {
                    if ($total = $this->getTotalExported($args)) {
                        $cron_job = true;
                        $log_text = date('Y-m-d H:i:s') . ' Email sent to ' . $email . '| Rule: ' . $order_exporter->name . ' | ' . $total . ($total > 1 ? ' orders' : ' order');
                        echo $log_text . Module::getInstanceByName('ets_ordermanager')->displayText('', 'br');
                        if ($log)
                            Tools::error_log($log_text . "\n", 3, dirname(__file__) . '/cronjob.log');
                    }
                }
            }
            $order_exporter->send_file_date = date('Y-m-d H:i:s');
            $order_exporter->update();
            return $cron_job;
        }
    }

    public function getOrderExport($fields = '*', $filter = '', $orderBy = '', $id_currency = 0)
    {
        $exported_fields = $fields;
        if ($fields != '*') {
            $this->list_fields = $this->getListFields($fields);
        }
        $fields2 = $fields;
        $fields2 = str_replace('o.product_quantity_in_stock', 'od.product_quantity_in_stock', $fields2);
        $fields = str_replace(
            array('o.product_quantity_in_stock'),
            array('od.product_quantity_in_stock'),
            $fields
        );
        $fields = $this->buildFields($fields);
        $sql = 'SELECT ' . (string)$fields . ' FROM `' . _DB_PREFIX_ . 'orders` o '
            . (strpos($fields, 'od.', 0) !== false || (strpos($filter, 'od.', 0) !== false || strpos($filter, 'mn.', 0) !== false || strpos($filter, 'supp.', 0) !== false || strpos($filter, 'cp.', 0) !== false) ? ' LEFT JOIN `' . _DB_PREFIX_ . 'order_detail` od ON (o.id_order = od.id_order)' : '')
            . (strpos($fields, 'oc.', 0) !== false ? ' LEFT JOIN `' . _DB_PREFIX_ . 'order_carrier` oc ON (o.id_order = oc.id_order)' : '')
            . (strpos($fields, 'op.', 0) !== false ? ' LEFT JOIN `' . _DB_PREFIX_ . 'order_payment` op ON (o.reference = op.order_reference)' : '') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (osl.id_order_state = o.current_state AND osl.id_lang="' . (int)$this->context->language->id . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (o.id_customer = c.id_customer)'
            . (strpos($fields, 'cm.', 0) !== false || strpos($fields, 'em.', 0) !== false ? 'LEFT JOIN `' . _DB_PREFIX_ . 'customer_thread` ct ON (o.id_order = ct.id_order)' : '') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'customer_group` cg ON (o.id_customer = cg.id_customer)
        LEFT JOIN `' . _DB_PREFIX_ . 'address` a ON (o.id_address_delivery = a.id_address)
        LEFT JOIN `' . _DB_PREFIX_ . 'country` country ON (country.id_country = a.id_country)
        LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` cl ON (cl.id_country = country.id_country AND cl.id_lang="' . (int)$this->context->language->id . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'state` s ON (a.id_state = s.id_state)
        LEFT JOIN `' . _DB_PREFIX_ . 'lang` lg ON (o.id_lang = lg.id_lang)
        LEFT JOIN `' . _DB_PREFIX_ . 'address` ainvoice ON (o.id_address_invoice= ainvoice.id_address)
        LEFT JOIN `' . _DB_PREFIX_ . 'country` invoice_country ON (invoice_country.id_country = ainvoice.id_country)
        LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` clinvoice ON (clinvoice.id_country = invoice_country.id_country AND clinvoice.id_lang="' . (int)$this->context->language->id . '")
        LEFT JOIN `' . _DB_PREFIX_ . 'state` sinvoice ON (ainvoice.id_state = sinvoice.id_state)
        LEFT JOIN `' . _DB_PREFIX_ . 'carrier` ca ON (ca.id_carrier = o.id_carrier) 
        LEFT JOIN `' . _DB_PREFIX_ . 'shop` sh ON (sh.id_shop = o.id_shop) '
            . (strpos($filter, 'mn.', 0) !== false ? ' 
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (p.id_product=od.product_id)
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` mn ON (mn.id_manufacturer = p.id_manufacturer)' : '')
            . (strpos($filter, 'supp.', 0) !== false ? '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.id_product=od.product_id)
            LEFT JOIN `' . _DB_PREFIX_ . 'supplier` supp ON (supp.id_supplier = ps.id_supplier)' : '')
            . (strpos($filter, 'cp.', 0) !== false ? ' 
            LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (cp.id_product = od.product_id) 
            LEFT JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cp.id_category = cs.id_category AND cs.id_shop = ' . (int)$this->context->shop->id . ')' : '') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'currency` cu ON (cu.id_currency = o.id_currency) '
            . (Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee') && Tools::strpos($fields, 'pam.fee') !== false ? ' LEFT JOIN `' . _DB_PREFIX_ . 'ets_paymentmethod_order` pam ON (pam.id_order=o.id_order) ' : '')
            . ($filter ? $filter : '') . ' GROUP BY '
            . $fields2
            . ($orderBy ? ' ORDER BY ' . pSQL($orderBy) : '');
        $orders = Db::getInstance()->executeS($sql);
        if ($orders && Tools::strpos($exported_fields, 'shipping_address') !== false || Tools::strpos($exported_fields, 'invoice_address') !== false) {
            foreach ($orders as &$order) {

                $class_order = new Order($order['id_order']);
                if (Tools::strpos($exported_fields, 'shipping_address') !== false) {
                    $address_shipping = new Address($class_order->id_address_delivery);
                    $order['shipping_address'] = AddressFormat::generateAddressSmarty(array('address' => $address_shipping, 'newLine' => ' - '), $this->context->smarty);
                }
                if (Tools::strpos($exported_fields, 'invoice_address') !== false) {
                    $address_shipping = new Address($class_order->id_address_invoice);
                    $order['invoice_address'] = AddressFormat::generateAddressSmarty(array('address' => $address_shipping, 'newLine' => ' - '), $this->context->smarty);
                }
            }
        }
        if ($orders) {

            foreach ($orders as &$order) {
                if ($id_currency) {
                    $currency = new Currency($id_currency, $this->context->language->id);
                    $order['iso_code'] = $currency->iso_code;
                } else
                    $currency = new Currency($order['id_currency'], $this->context->language->id);
                $decimal = isset($currency->precision) ? $currency->precision : $currency->decimals;
                if (isset($order['currency_name']))
                    $order['currency_name'] = $currency->name;
                if (isset($order['total_paid_tax_incl']))
                    $order['total_paid_tax_incl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_paid_tax_incl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_paid_tax_incl'], $decimal);
                if (isset($order['total_paid_tax_excl']))
                    $order['total_paid_tax_excl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_paid_tax_excl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_paid_tax_excl'], $decimal);
                if (isset($order['total_price_tax_incl']))
                    $order['total_price_tax_incl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_price_tax_incl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_price_tax_incl'], $decimal);
                if (isset($order['total_price_tax_excl']))
                    $order['total_price_tax_excl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_price_tax_excl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_price_tax_excl'], $decimal);
                if (isset($order['product_price']))
                    $order['product_price'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['product_price'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['product_price'], $decimal);
                if (isset($order['original_product_price']))
                    $order['original_product_price'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['original_product_price'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['original_product_price'], $decimal);
                if (isset($order['total_shipping_tax_incl']))
                    $order['total_shipping_tax_incl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_shipping_tax_incl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_shipping_tax_incl'], $decimal);
                if (isset($order['total_shipping_tax_excl']))
                    $order['total_shipping_tax_excl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['total_shipping_tax_excl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['total_shipping_tax_excl'], $decimal);
                if (isset($order['discount_value']))
                    $order['discount_value'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['discount_value'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['discount_value'], $decimal);
                if (isset($order['reduction_amount_tax_incl']))
                    $order['reduction_amount_tax_incl'] = $id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['reduction_amount_tax_incl'], $order['id_currency'], false), $id_currency), $decimal) : Tools::ps_round($order['reduction_amount_tax_incl'], $decimal);
                if (isset($order['discount_percent']))
                    $order['discount_percent'] = Tools::ps_round($order['discount_percent'] * 100, 2) . '%';
                if (isset($order['date_add']))
                    $order['date_add'] = Tools::displayDate($order['date_add']);
                if (isset($order['date_upd']))
                    $order['date_upd'] = Tools::displayDate($order['date_upd']);
                if (isset($order['invoice_number'])) {
                    if ($order['invoice_number']) {
                        $id_order_invoice = Db::getInstance()->getValue('SELECT id_order_invoice FROM `' . _DB_PREFIX_ . 'order_invoice` WHERE number="' . pSQL($order['invoice_number']) . '"');
                        $order_invoice = new OrderInvoice($id_order_invoice);
                        $order['invoice_number'] = $order_invoice->getInvoiceNumberFormatted($this->context->language->id);
                    } else
                        $order['invoice_number'] = '';
                }
                //order1.
                if (isset($order['amount']) && $order['amount']) {
                    //$currency = new Currency(isset($order['id_currency']) ? $order['id_currency'] : $this->context->currency->id);
                    $order['amount'] = Tools::displayPrice($id_currency ? Tools::ps_round(Tools::convertPrice(Tools::convertPrice($order['amount'], $order['id_currency'], false), $id_currency), $decimal) : $order['amount'], $currency);
                }
                if ($id_currency)
                    $order['id_currency'] = $id_currency;
                unset($order['currency_id']);
            }
        }
        return $orders;
    }

    public function getListFields(&$fields)
    {
        $list_fields = array();
        if ($fields) {
            $fields = explode(',', $fields);
            foreach ($fields as $key => $field) {
                if (($array_field = $this->getField($field))) {
                    $list_fields[$array_field['key']] = $array_field;
                } else
                    unset($fields[$key]);
            }
        }
        $fields = implode(',', $fields);
        return $list_fields;
    }

    public function getField($field)
    {
        if ($order_fields = Ode_defines::getInstance($this)->getFields('order')) {
            foreach ($order_fields as $order_field) {
                if ($field == $order_field['id']) {
                    $order_field['class'] = str_replace('.', '_', $order_field['id']);
                    return $order_field;
                }
            }
        }
        if ($customer_fields = Ode_defines::getInstance($this)->getFields('customer')) {
            foreach ($customer_fields as $customer_field) {
                if ($field == $customer_field['id']) {
                    $customer_field['class'] = str_replace('.', '_', $customer_field['id']);
                    return $customer_field;
                }
            }
        }
        if ($shipping_fields = Ode_defines::getInstance($this)->getFields('shipping')) {
            foreach ($shipping_fields as $shipping_field) {
                if ($field == $shipping_field['id']) {
                    $shipping_field['class'] = str_replace('.', '_', $shipping_field['id']);
                    if ($shipping_field['id'] != 'shipping_address')
                        $shipping_field['name'] = $this->l('Shipping') . ' ' . ($shipping_field['id'] == 'a.vat_number' ? $shipping_field['name'] : Tools::strtolower($shipping_field['name']));
                    return $shipping_field;
                }
            }
        }
        if ($invoice_fields = Ode_defines::getInstance($this)->getFields('invoice')) {
            foreach ($invoice_fields as $invoice_field) {
                if ($field == $invoice_field['id']) {
                    $invoice_field['class'] = str_replace('.', '_', $invoice_field['id']);
                    if ($invoice_field['id'] != 'invoice_address')
                        $invoice_field['name'] = $this->l('Invoice') . ' ' . ($invoice_field['id'] == 'ainvoice.vat_number' ? $invoice_field['name'] : Tools::strtolower($invoice_field['name']));
                    return $invoice_field;
                }
            }
        }
        if ($product_fields = Ode_defines::getInstance($this)->getFields('product')) {
            foreach ($product_fields as $product_field) {
                if ($field == $product_field['id']) {
                    $product_field['class'] = str_replace('.', '_', $product_field['id']);
                    return $product_field;
                }
            }
        }
        if ($carrier_fields = Ode_defines::getInstance($this)->getFields('carrier')) {
            foreach ($carrier_fields as $carrier_field) {
                if ($field == $carrier_field['id']) {
                    $carrier_field['class'] = str_replace('.', '_', $carrier_field['id']);
                    return $carrier_field;
                }
            }
        }
        if ($payment_fields = Ode_defines::getInstance($this)->getFields('payment')) {
            foreach ($payment_fields as $payment_field) {
                if ($field == $payment_field['id']) {
                    $payment_field['class'] = str_replace('.', '_', $payment_field['id']);
                    return $payment_field;
                }
            }
        }
        if ($other_fields = Ode_defines::getInstance($this)->getFields('other')) {
            foreach ($other_fields as $other_field) {
                if ($field == $other_field['id']) {
                    $other_field['class'] = str_replace('.', '_', $other_field['id']);
                    return $other_field;
                }
            }
        }
        return '';
    }

    public function buildFields($fields)
    {
        if ($fields != '*') {
            $fields = explode(',', $fields);
            if ($fields && $this->list_fields) {
                foreach ($fields as &$field) {
                    if ($field == 'address') {
                        $field = 'IF(a.address1 is NOT NULL OR a.address1 != "", CONCAT(a.address1, ", ", a.city), CONCAT(a.address2, ", ", a.city)) as `address`';
                    } elseif ($field == 'a.phone' || $field == 'phone') {
                        $field = 'IF(a.phone is NOT NULL OR a.phone != "", a.phone, a.phone_mobile) as `phone`';
                    } elseif ($field == 'op.amount') {
                        $field = 'op.amount, op.id_currency as `currency_id`';
                    } elseif ($field == 'customer_msg') {
                        $field = '(SELECT GROUP_CONCAT(cm.message SEPARATOR ". ") FROM `' . _DB_PREFIX_ . 'customer_message` cm WHERE (cm.id_customer_thread = ct.id_customer_thread AND (cm.id_employee = 0 OR cm.id_employee is NULL))) as `customer_msg`';
                    } elseif ($field == 'employee_msg') {
                        $field = '(SELECT GROUP_CONCAT(em.message SEPARATOR ". ") FROM `' . _DB_PREFIX_ . 'customer_message` em WHERE (em.id_customer_thread = ct.id_customer_thread AND (em.id_employee != 0 AND em.id_employee is NOT NULL))) as `employee_msg`';
                    } elseif ($field == 'new_client') {
                        $field = 'IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = o.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, "No", "Yes") as new_client';
                    } elseif ($field == 'shipping_address')
                        $field = 'o.id_address_delivery as shipping_address';
                    elseif ($field == 'invoice_address')
                        $field = 'o.id_address_invoice as invoice_address';
                    elseif ($field == 'od.reduction_amount_tax_incl')
                        $field = 'IF(od.reduction_amount_tax_incl!=0,od.reduction_amount_tax_incl,od.reduction_percent*od.product_price/100) as reduction_amount_tax_incl';
                    elseif ($field == 'od.tax_rate')
                        $field = 'od.unit_price_tax_incl-od.unit_price_tax_excl as tax_rate';
                    elseif ($field == 'discount_value')
                        $field = ' o.total_discounts_tax_incl as discount_value';
                    elseif ($field == 'discount_percent')
                        $field = ' o.total_discounts_tax_incl/(o.total_paid_tax_incl+o.total_discounts_tax_incl) as discount_percent';
                    else {
                        if ($array_field = $this->getField($field)) {
                            $field = $field . ' as ' . $array_field['key'];
                        }
                    }

                }
                if (!in_array('o.id_order', $fields))
                    $fields[] = 'o.id_order';
                if (!in_array('o.id_currency', $fields))
                    $fields[] = 'o.id_currency';
                return implode(',', $fields);
            }
        }
        return $fields;
    }

    public function uploadFiles($args = array(), $log = false)
    {
        $order_export = isset($args['order_exporter']) ? $args['order_exporter'] : '';
        $schedule = isset($args['schedule']) ? trim($args['schedule']) : false;
        $cron_job = false;
        if (!$order_export)
            return;
        if ($order_export->id) {
            $data = isset($args['out_file']) ? $args['out_file'] : '';
            $filename = isset($args['filename']) ? 'orders_' . $args['filename'] . date('Y_m_d_H_i_s') . '_' . $order_export->id . '.' . $order_export->file_format : '';
            if (($schedule == 'local' && $order_export->export_to_server1) || ($schedule == 'auto' && $order_export->export_to_server1 && $this->exportValid($order_export->server1_schedule, $order_export->server1_time_hours, $order_export->server1_time_weeks, $order_export->server1_time_months, $order_export->server1_date))) {
                $args_local = $args;
                if ($order_export->server1_filter == 'new' && $order_export->server1_date && $order_export->server1_date != '0000-00-00 00:00:00') {
                    $args_local['orders'] = $this->getOrderExport($args_local['exported_fields'], $args_local['filter'] . ' AND o.date_add > "' . pSQL($order_export->server1_date) . '"', $order_export->sort_by, $order_export->convert_in_currency);
                    $data = $this->exportOrder($args_local);
                }
                $this->uploadFileTo($order_export->directory_path1, $filename, $data);
                if (!$this->_errors) {
                    $order_export->server1_date = date('Y-m-d H:i:s');
                    $order_export->update();
                    if ($total = $this->getTotalExported($args_local)) {
                        $cron_job = true;
                        $log_text = date('Y-m-d H:i:s') . ' Save to web directory | Rule: ' . $order_export->name . ' | ' . $total . ($total > 1 ? ' orders' : ' order');
                        echo $log_text . Module::getInstanceByName('ets_ordermanager')->displayText('', 'br');
                        if ($log)
                            Tools::error_log($log_text . "\n", 3, dirname(__file__) . '/cronjob.log');
                    }

                }
            }
            if (($schedule == 'ftp' && $order_export->export_to_server2) || ($schedule == 'auto' && $order_export->export_to_server2 && $this->exportValid($order_export->server2_schedule, $order_export->server2_time_hours, $order_export->server2_time_weeks, $order_export->server2_time_months, $order_export->server2_date))) {
                $args_ftp = $args;
                if ($order_export->server2_filter == 'new' && $order_export->server2_date && $order_export->server2_date != '0000-00-00 00:00:00') {
                    $args_ftp['orders'] = $this->getOrderExport($args_ftp['exported_fields'], $args_ftp['filter'] . ' AND o.date_add > "' . pSQL($order_export->server2_date) . '"', $order_export->sort_by, $order_export->convert_in_currency);
                    $data = $this->exportOrder($args_ftp);
                }
                if (!(int)$order_export->global_ftp) {
                    if ($this->uploadFTP(
                        trim($order_export->host),
                        trim($order_export->username),
                        trim($order_export->password),
                        $data,
                        trim($order_export->directory_path2),
                        $filename,
                        trim($order_export->port)
                    )) {
                        if ($total = $this->getTotalExported($args_ftp)) {
                            $cron_job = true;
                            $log_text = date('Y-m-d H:i:s') . ' Exported to FTP ' . $order_export->host . '| Rule: ' . $order_export->name . ' | ' . $total . ($total > 1 ? ' orders' : ' order');
                            echo $log_text . Module::getInstanceByName('ets_ordermanager')->displayText('', 'br');
                            if ($log)
                                Tools::error_log($log_text . "\n", 3, dirname(__file__) . '/cronjob.log');
                        }
                    }
                } else {
                    if ($this->uploadFTP(
                        trim(Configuration::get('ETS_ODE_HOST')),
                        trim(Configuration::get('ETS_ODE_USERNAME')),
                        trim(Configuration::get('ETS_ODE_PASSWORD')),
                        $data,
                        trim($order_export->directory_path2),
                        $filename,
                        trim(Configuration::get('ETS_ODE_PORT'))
                    )) {
                        if ($total = $this->getTotalExported($args_ftp)) {
                            $cron_job = true;
                            $log_text = date('Y-m-d H:i:s') . ' Exported to FTP ' . Configuration::get('ETS_ODE_HOST') . '| Rule: ' . $order_export->name . ' | ' . $total . ($total > 1 ? ' orders' : ' order');
                            echo $log_text . Module::getInstanceByName('ets_ordermanager')->displayText('', 'br');
                            if ($log)
                                Tools::error_log($log_text . "\n", 3, dirname(__file__) . '/cronjob.log');
                        }
                    }
                }
                if (!$this->_errors) {
                    $order_export->server2_date = date('Y-m-d H:i:s');
                    $order_export->update();
                }
            }
        }
        return $cron_job;
    }

    public function pathFormat($path)
    {
        return $path && $path != '/' ? '/' . trim($path, '/') . '/' : '/';
    }

    public function uploadFileTo($path, $filename, $data)
    {
        if (!@is_dir(($directory = trim(_PS_ROOT_DIR_ . $this->pathFormat($path))))) {
            @mkdir($directory, 0755, true);
        }
        if (!$filename) {
            $this->_errors[] = $this->l('Filename is required.');
        }
        if (!$this->_errors) {
            @file_put_contents($directory . $filename, $data);
        }
    }

    public function uploadFTP($host, $username, $password, $data, $path, $filename, $port)
    {
        if (!$filename) {
            $this->_errors[] = $this->l('Filename is required.');
        }
        if (!$host) {
            $this->_errors[] = $this->l('Host is required.');
        }
        if ($this->_errors) {
            return false;
        }
        $local_file = fopen('php://temp', 'r+');
        fwrite($local_file, $data);
        rewind($local_file);
        if (!($ftp_conn = @ftp_connect($host, $port))) {
            $this->_errors[] = $this->l('Connection fail.');
        } elseif ($host && $username && $password && @ftp_login($ftp_conn, $username, $password)) {
            @ftp_pasv($ftp_conn, false);
            $this->mkdirFTP($ftp_conn, $this->pathFormat($path));
            @ftp_fput($ftp_conn, $filename, $local_file, FTP_BINARY);
            @ftp_close($ftp_conn);
        } else {
            $this->_errors[] = $this->l('Login is failed. Please check your username and password');
        }
        fclose($local_file);
        if (!$this->_errors)
            return true;
        else
            return false;
    }

    public function mkdirFTP($ftp_conn, $ftp_path, $ftp_root = '/')
    {
        if (@ftp_chdir($ftp_conn, $ftp_root)) {
            $parts = explode('/', $ftp_path);
            foreach ($parts as $part) {
                if (!@ftp_chdir($ftp_conn, $part)) {
                    @ftp_mkdir($ftp_conn, $part);
                    @ftp_chdir($ftp_conn, $part);
                    @ftp_chmod($ftp_conn, 0755, $part);

                }
            }
        }
    }

    public function getTotalExported($args)
    {
        $orders = isset($args['orders']) ? $args['orders'] : array();
        if ($orders) {
            $array = array();
            $total = 0;
            foreach ($orders as $order) {
                if (!isset($order['id_order']) || !in_array($order['id_order'], $array)) {
                    $total++;
                    if (isset($order['id_order']))
                        $array[] = $order['id_order'];
                }
            }
            return $total;
        }
        return 0;
    }

    public function exportOrderCSV_bk($args = array())
    {
        $data = isset($args['orders']) ? $args['orders'] : array();
        $filename_prefix = isset($args['filename']) ? $args['filename'] : '';
        $display = false;// isset($args['display']) ? $args['display'] : '';
        $filename = $filename_prefix . date('d_m_Y') . ".csv";

        if ($display) {
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: text/csv; charset=utf-8");
        }
        $this->colnames = array();
        if ($this->list_fields) {
            foreach ($this->list_fields as $key => $field) {
                $this->colnames[$key] = $field['name'];
            }
        }
        $out_file = $display ? fopen("php://output", 'w') : fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'r+');
        $flag = false;
        if ($data) {
            foreach ($data as $row) {
                if (!$flag) {
                    $firstline = array_map(array($this, 'map_colnames'), array_keys($row));
                    fputcsv($out_file, $firstline, ',', '"');
                    $flag = true;
                }
                fputcsv($out_file, array_values($row), ',', '"');
            }
        } else {
            $firstline = array_map(array($this, 'map_colnames'), array_keys($this->colnames));
            fputcsv($out_file, $firstline, ',', '"');
        }
        if ($display) {
            fclose($out_file);
            exit;
        } else {
            rewind($out_file);
            return stream_get_contents($out_file);
        }
    }

    public function exportOrderCSV($args = array())
    {
        $data = isset($args['orders']) ? $args['orders'] : array();
        $filename_prefix = isset($args['filename']) ? $args['filename'] : '';
        $display = isset($args['display']) ? $args['display'] : '';
        $filename = $filename_prefix . date('d_m_Y') . ".csv";
        if ($display) {
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-type: application/x-msdownload");
        }
        $this->colnames = array();
        if ($this->list_fields) {
            foreach ($this->list_fields as $key => $field) {
                $this->colnames[$key] = $field['name'];
            }
        }
        $flag = false;
        $csv = '';
        if ($data) {
            foreach ($data as $row) {
                foreach (array_keys($row) as $k)
                    if (!isset($this->colnames[$k]))
                        unset($row[$k]);
                if (!$flag) {
                    $firstline = array_map(array($this, 'map_colnames'), array_keys($row));
                    $csv2 = '';
                    $count2 = 0;
                    foreach ($firstline as $k => $v) {
                        if ($count2 == 0) {
                            $csv2 .= '"' . $v . '","';
                        } elseif ($count2 == (count($row) - 1)) {
                            $csv2 .= $v . '"';
                        } else {
                            $csv2 .= $v . '","';
                        }
                        $count2++;
                    }
                    $csv .= $csv2 . "\r\n";
                    $flag = true;
                }
                if ($row) {
                    foreach ($row as &$val)
                        $val = str_replace(array("\r\n", "\r", "\n"), "", $val);
                }
                $csv2 = '';
                $count2 = 0;
                foreach ($row as $k => $v) {
                    if ($count2 == 0) {
                        $csv2 .= '"' . $v . '","';
                    } elseif ($count2 == (count($row) - 1)) {
                        $csv2 .= $v . '"';
                    } else {
                        $csv2 .= $v . '","';
                    }
                    $count2++;
                }
                $csv .= $csv2 . "\r\n";

            }
        } else {
            $firstline = array_map(array($this, 'map_colnames'), array_keys($this->colnames));
            $csv .= join("\t", $firstline) . "\r\n";
        }
        $csv = chr(255) . chr(254) . mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
        if ($display) {
            echo $csv;
            exit();
        } else {
            return $csv;
        }
    }

    public function exportOrderXLS($args = array())
    {
        $data = isset($args['orders']) ? $args['orders'] : array();
        $filename_prefix = isset($args['filename']) ? $args['filename'] : '';
        $display = isset($args['display']) ? $args['display'] : '';
        $filename = $filename_prefix . date('d_m_Y') . ".xls";
        if ($display) {
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-type: application/x-msdownload");
        }
        $sep = "\t";
        $this->colnames = array();
        if ($this->list_fields) {
            foreach ($this->list_fields as $key => $field) {
                $this->colnames[$key] = $field['name'];
            }
        }
        $flag = false;
        $schema_insert = "";
        if ($data) {
            foreach ($data as $row) {
                if (!$flag) {
                    foreach ($this->colnames as $colname) {
                        $schema_insert .= $colname . $sep;
                    }
                    $flag = true;
                    $schema_insert .= "\n";
                }
                foreach (array_keys($this->colnames) as $colname) {
                    $schema_insert .= (isset($row[$colname]) && $row[$colname] ? $row[$colname] : '') . $sep;
                }
                $schema_insert .= "\n";
            }
        } else {
            foreach ($this->colnames as $colname) {
                $schema_insert .= $colname . $sep;
            }
            $schema_insert .= "\n";
        }
        $csv = chr(255) . chr(254) . mb_convert_encoding($schema_insert, "UTF-16LE", "UTF-8");
        if ($display) {
            echo $csv;
            exit();
        } else
            return $csv;
    }

    public function exportOrderXlsx($args = array())
    {
        if (!class_exists('PHPExcel'))
            require_once dirname(__FILE__) . '/classes/PHPExcel.php';
        $data = isset($args['orders']) ? $args['orders'] : array();
        $filename_prefix = isset($args['filename']) ? $args['filename'] : '';
        $display = isset($args['display']) ? $args['display'] : '';
        $filename = $filename_prefix . date('d_m_Y') . ".xlsx";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel
            ->getProperties()
            ->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getActiveSheet()->setTitle('Order.' . date('m.d.Y'));
        $ik = 0;
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->colnames = array();
        if ($this->list_fields) {
            foreach ($this->list_fields as $key => $field) {
                $this->colnames[$key] = $field['name'];
            }
        }
        if ($this->colnames) {
            $ik++;
            $col = 0;
            $txt = '';
            $j = 0;
            foreach ($this->colnames as $column) {
                if ($col > 25) {
                    $txt = $str[$j];
                    $j++;
                    $col = 0;
                }
                $activeSheet = $txt . $str[$col] . $ik;
                $objPHPExcel
                    ->setActiveSheetIndex(0)
                    ->setCellValue($activeSheet, $column);
                $col++;
            }
        }
        if ($data) {
            foreach ($data as $row) {
                $ik++;
                $col = 0;
                $txt = '';
                $j = 0;
                foreach (array_keys($this->colnames) as $key) {
                    if ($col > 25) {
                        $txt = $str[$j];
                        $j++;
                        $col = 0;
                    }
                    $activeSheet = $txt . $str[$col] . $ik;
                    $objPHPExcel
                        ->setActiveSheetIndex(0)
                        ->setCellValue($activeSheet, isset($row[$key]) ? (string)$row[$key] : '');
                    $col++;
                }
            }
        }
        if ($display) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (!@is_dir(_PS_CACHE_DIR_ . DIRECTORY_SEPARATOR . $this->name))
            @mkdir(_PS_CACHE_DIR_ . DIRECTORY_SEPARATOR . $this->name, 0755, true);
        if (!file_exists(($path_file = _PS_CACHE_DIR_ . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $filename)))
            @unlink($path_file);
        $objWriter->save($path_file);
        if ($display) {
            readfile($path_file);
            exit;
        } else
            return Tools::file_get_contents($path_file);
    }

    public function exportOrderXML($args = array())
    {

        $data = isset($args['orders']) ? $args['orders'] : array();
        $filename_prefix = isset($args['filename']) ? $args['filename'] : '';
        $display = isset($args['display']) ? $args['display'] : '';
        $filename = $filename_prefix . date('d_m_Y') . '.xml';
        if ($display) {
            header('Content-type: text/xml; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        $this->colnames = array();
        if ($this->list_fields) {
            foreach ($this->list_fields as $key => $field) {
                $this->colnames[$key] = $field['name'];
            }
        }

        $xml_rowoutput = '';
        if ($data) {
            foreach ($data as $row) {
                //$xml_output .= '<row>' . "\n";
                $content_row = '';
                if ($this->colnames) {
                    foreach ($this->colnames as $key => $name) {
                        if (isset($row[$key]))
                            $content_row .= Module::getInstanceByName('ets_ordermanager')->displayText($row[$key], $key) . "\n";
                        unset($name);
                    }
                }
                $xml_rowoutput .= Module::getInstanceByName('ets_ordermanager')->displayText($content_row, 'row') . "\n";
            }
        }

        $xml_output = '<?xml version="1.0" encoding="UTF-8"?>' . Module::getInstanceByName('ets_ordermanager')->displayText($xml_rowoutput, 'entity_profile') . "\n";

        if ($display) {
            echo $xml_output;
            exit;
        } else
            return $xml_output;
    }

    public function _postExporter()
    {
        $errors = array();
        if (Tools::isSubmit('id_ets_export_order_rule') && ($id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule'))) {
            $order_exporter = new Ode_export($id_ets_export_order_rule);
        } else
            $order_exporter = new Ode_export();
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $name = Tools::getValue('name_' . $id_lang_default);
        if (!$name)
            $errors[] = $this->l('Rule name is required');
        elseif (!Validate::isCleanHtml($name))
            $errors[] = $this->l('Rule name is not valid');
        $date_type = Tools::getValue('date_type');
        if ($date_type == 'from_to') {
            $from_date = Tools::getValue('from_date');
            if ($from_date && !Validate::isDate($from_date))
                $errors[] = $this->l('From date is not valid');
            $to_date = Tools::getValue('to_date');
            if ($to_date && !Validate::isDate($to_date))
                $errors[] = $this->l('To date is not valid');
            if ($from_date && $to_date && strtotime($from_date) > strtotime($to_date) && $from_date != '0000-00-00' && $to_date != '0000-00-00')
                $errors[] = $this->l('To must be after From');
            if ((!$from_date || $from_date == '0000-00-00') && (!$to_date || $to_date == '0000-00-00'))
                $errors[] = $this->l('Enter From - To value');
        } elseif ($date_type == 'day_before') {
            if (!($day_before = Tools::getValue('day_before')))
                $errors[] = $this->l('The day before is required.');
            elseif (!Validate::isInt($day_before)) {
                $errors[] = $this->l('The day before is invalid.');
            }
        }
        $spent_from = Tools::getValue('spent_from');
        $spent_to = Tools::getValue('spent_to');
        if ((($spent_from && !Validate::isFloat($spent_from)) || (float)$spent_from < 0) || (($spent_to && !Validate::isFloat($spent_to)) || (float)$spent_to < 0))
            $errors[] = $this->l('Total order is invalid');

        if (!($exported_fields = Tools::getValue('exported_fields')))
            $errors[] = $this->l('Select at least 1 exported field');
        elseif (!Validate::isCleanHtml($exported_fields))
            $errors[] = $this->l('Exported field is not valid');
        if ($spent_from && $spent_to && Validate::isFloat($spent_to) && Validate::isFloat($spent_from) && (float)$spent_from > (float)$spent_to)
            $errors[] = $this->l('To must be greater than From');
        $send_file_via_email = (int)Tools::getValue('send_file_via_email');
        if ($send_file_via_email) {
            $title_mail_default = Tools::getValue('title_mail_' . $id_lang_default);
            if (!$title_mail_default)
                $errors[] = $this->l('Email title is required');
            elseif (!Validate::isCleanHtml($title_mail_default))
                $errors[] = $this->l('Email title is not valid');
            $description_mail_default = Tools::getValue('description_mail_' . $id_lang_default);
            if (!$description_mail_default)
                $errors[] = $this->l('Email content is required');
            elseif (!Validate::isCleanHtml($description_mail_default))
                $errors[] = $this->l('Email content is not valid');
            if (!($receivers_mail = trim(Tools::getValue('receivers_mail'))))
                $errors[] = $this->l('Receiver email is required');
            else {
                $receivers_mail = explode(',', Tools::getValue('receivers_mail'));
                if ($receivers_mail) {
                    foreach ($receivers_mail as $mail) {
                        if (trim($mail) && !Validate::isEmail(trim($mail))) {
                            $errors[] = sprintf($this->l('%s is invalid'), $mail);
                        }
                    }
                }
            }

        }
        $export_to_server2 = (int)Tools::getValue('export_to_server2');
        $global_ftp = (int)Tools::getValue('global_ftp');
        if ((int)$export_to_server2 > 0 && !(int)$global_ftp) {
            if (!($host = Tools::getValue('host'))) {
                $errors[] = $this->l('Host is required');
            } elseif (!Validate::isCleanHtml($host))
                $errors[] = $this->l('Host is not valid');
            if (!($username = Tools::getValue('username'))) {
                $errors[] = $this->l('User name is required');
            } elseif (!Validate::isCleanHtml($username))
                $errors[] = $this->l('User name is not valid');
            if (!($password = Tools::getValue('password'))) {
                $errors[] = $this->l('Password is required');
            } elseif (!Validate::isCleanHtml($password))
                $errors[] = $this->l('Password is not valid');
        }
        $send_file_time_hours = Tools::getValue('send_file_time_hours');
        if ($send_file_time_hours && !Validate::isCleanHtml($send_file_time_hours))
            $errors[] = $this->l('Hour to send file is not valid');
        $send_file_time_weeks = Tools::getValue('send_file_time_weeks');
        if ($send_file_time_weeks && !Validate::isCleanHtml($send_file_time_weeks))
            $errors[] = $this->l('Week to send file is not valid');
        $send_file_time_months = Tools::getValue('send_file_time_months');
        if ($send_file_time_months && !Validate::isCleanHtml($send_file_time_months))
            $errors[] = $this->l('Month to send file is not valid');
        $server1_time_hours = Tools::getValue('server1_time_hours');
        if ($server1_time_hours && !Validate::isCleanHtml($server1_time_hours))
            $errors[] = $this->l('Hour to send file via server is not valid');
        $server1_time_weeks = Tools::getValue('server1_time_weeks');
        if ($server1_time_weeks && !Validate::isCleanHtml($server1_time_weeks))
            $errors[] = $this->l('Week to send file via server is not valid');
        $server1_time_months = Tools::getValue('server1_time_months');
        if ($server1_time_months && !Validate::isCleanHtml($server1_time_months))
            $errors[] = $this->l('Month to send file via FTP is not valid');
        $server2_time_hours = Tools::getValue('server2_time_hours');
        if ($server2_time_hours && !Validate::isCleanHtml($server2_time_hours))
            $errors[] = $this->l('Hour to send file via FTP is not valid');
        $server2_time_weeks = Tools::getValue('server2_time_weeks');
        if ($server2_time_weeks && !Validate::isCleanHtml($server2_time_weeks))
            $errors[] = $this->l('Week to send file via FTP is not valid');
        $server2_time_months = Tools::getValue('server1_time_months');
        if ($server2_time_months && !Validate::isCleanHtml($server2_time_months))
            $errors[] = $this->l('Month to send file via FTP is not valid');
        if (count($errors)) {
            $this->errorMessage = $this->displayError($errors);
            if (Tools::isSubmit('ajax')) {
                die(
                json_encode(
                    array(
                        'error' => $this->errorMessage,
                    )
                )
                );
            }
            $this->context->controller->errors = $errors;
            return false;
        } else {
            $configs = Ode_defines::getInstance($this)->getFields('rule');
            if ($configs) {
                foreach ($configs as $config) {
                    if (!($key = $config['name']))
                        continue;
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $order_exporter->{$key}[$l['id_lang']] = Tools::getValue($key . '_' . $l['id_lang']) ?: Tools::getValue($key . '_' . $id_lang_default);
                        }
                    } elseif ($key == 'spent_from' || $key == 'spent_to') {
                        $value = (float)Tools::getValue($key) / $this->context->currency->conversion_rate;
                        $order_exporter->$key = $value ? $value : null;
                    } elseif ($config['type'] == 'categories') {
                        $order_exporter->$key = ($cats = Tools::getValue($key)) != '' ? implode(',', $cats) : '';
                    } elseif ($config['type'] == 'select' && isset($config['multiple']) && $config['multiple']) {
                        $order_exporter->$key = $this->getMultiValues($key);
                    } elseif ($config['type'] == 'password') {
                        $val = Tools::getValue($key);
                        if ($val)
                            $order_exporter->$key = $val;
                    } elseif ($key != 'specific_customer_select') {
                        $order_exporter->$key = Tools::getValue($key);
                    }
                }
            }
            $order_exporter->send_file_time_hours = $send_file_time_hours;
            $order_exporter->send_file_time_weeks = $send_file_time_weeks;
            $order_exporter->send_file_time_months = $send_file_time_months;
            $order_exporter->server1_time_hours = $server1_time_hours;
            $order_exporter->server1_time_weeks = $server1_time_weeks;
            $order_exporter->server1_time_months = $server1_time_months;
            $order_exporter->server2_time_hours = $server2_time_hours;
            $order_exporter->server2_time_weeks = $server2_time_weeks;
            $order_exporter->server2_time_months = $server2_time_months;
            if (Tools::isSubmit('id_ets_export_order_rule') && ($id_ets_export_order_rule = (int)Tools::getValue('id_ets_export_order_rule'))) {
                $order_exporter->update(true);
                if (Tools::isSubmit('ajax')) {
                    die(
                    json_encode(
                        array(
                            'success' => $this->l('Updated successfully'),
                            'id_ets_export_order_rule' => $order_exporter->id,
                            'redirect' => $this->context->link->getAdminLink('AdminOrderManagerExports')
                        )
                    )
                    );
                }
            } else {
                $position = Db::getInstance()->getValue('select MAX(position) FROM ' . _DB_PREFIX_ . 'ets_export_order_rule');
                $order_exporter->position = $position + 1;
                $order_exporter->add(true, true);
                if (Tools::isSubmit('ajax')) {
                    die(
                    json_encode(
                        array(
                            'success' => $this->l('Added successfully'),
                            'id_ets_export_order_rule' => $order_exporter->id,
                            'redirect' => $this->context->link->getAdminLink('AdminOrderManagerExports')
                        )
                    )
                    );
                }
            }
            return (int)$order_exporter->id;
        }
        return true;
    }

    public function getMultiValues($key)
    {
        return ($fields = Tools::getValue($key)) ? (!in_array('all', $fields) ? implode(',', $fields) : 'all') : '';
    }

    public function submitExportOrSendOrder($sendmail = false)
    {
        $filename = '';
        if (($exported_fields = Tools::getValue('exported_fields')) && Validate::isCleanHtml($exported_fields))
            $exported_fields = $exported_fields;
        else
            $exported_fields = 'o.delivery_number,o.id_order,o.reference,o.id_shop,o.id_lang,o.id_cart,o.id_currency,o.total_paid_tax_incl,o.total_paid_tax_excl,o.invoice_number,o.date_add,o.date_upd,o.id_customer,a.firstname,a.lastname,c.email,cl.name,s.name,od.product_id,od.product_name,od.product_quantity,o.product_quantity_in_stock,od.product_ean13,od.product_upc,od.product_reference,od.product_price,od.reduction_amount_tax_incl,od.original_product_price,od.product_supplier_reference,od.product_weight,od.tax_rate,od.total_price_tax_incl,od.total_price_tax_excl,o.id_carrier,o.total_shipping_tax_incl,o.total_shipping_tax_excl,o.delivery_number,o.delivery_date,oc.tracking_number,osl.name';

        //build filter.
        $filter = 'WHERE o.id_shop=' . (int)$this->context->shop->id;
        if (($specific_customer = Tools::getValue('specific_customer')) && ($specific_customer = explode(',', $specific_customer)) && Ets_ordermanager::validateArray($specific_customer, 'isInt'))
            $filter .= ' AND c.id_customer IN (' . implode(',', array_map('intval', $specific_customer)) . ')';
        else {
            if (($customer_group = Tools::getValue('customer_group')) && $this->isAll($customer_group) && Ets_ordermanager::validateArray($customer_group, 'isInt')) {
                $filter .= ' AND cg.id_group IN (' . implode(',', array_map('intval', $customer_group)) . ')';
            }
            if (($id_country = Tools::getValue('id_country')) && $this->isAll($id_country) && Ets_ordermanager::validateArray($id_country, 'isInt')) {
                $filter .= ' AND cl.id_country IN (' . implode(',', array_map('intval', $id_country)) . ')';
            }
        }
        if (($order_carrier = Tools::getValue('order_carrier')) && $this->isAll($order_carrier) && Ets_ordermanager::validateArray($order_carrier, 'isInt'))
            $filter .= ' AND ca.id_reference IN (' . implode(',', array_map('intval', $order_carrier)) . ')';

        if (($order_status = Tools::getValue('order_status')) && $this->isAll($order_status) && Ets_ordermanager::validateArray($order_status, 'isInt')) {
            $filter .= ' AND o.current_state IN (' . implode(',', array_map('intval', $order_status)) . ')';
        }

        //payment methods
        if (($_payment_method = Tools::getValue('payment_method')) && $this->isAll($_payment_method) && Validate::isCleanHtml($_payment_method)) {
            $methods = array();
            if (($payment_methods = explode(',', $_payment_method))) {
                foreach ($payment_methods as $method) {
                    $methods[] = $method;
                }
            }
            if ($methods)
                $filter .= ' AND o.module IN (' . implode(',', $methods) . ')';
        }

        //manus
        if (($_manufacturer = Tools::getValue('manufacturer')) && $this->isAll($_manufacturer) && Validate::isCleanHtml($_manufacturer)) {
            $manus = array();
            if (($manufacturers = explode(',', $_manufacturer))) {
                foreach ($manufacturers as $manu) {
                    $manus[] = (int)$manu;
                }
            }
            if ($manus)
                $filter .= ' AND mn.id_manufacturer IN (' . implode(',', array_map('intval', $manus)) . ') ';
        }
        //sups
        if (($_supplier = Tools::getValue('supplier')) && $this->isAll($_supplier) && Validate::isCleanHtml($_supplier)) {
            $sups = array();
            if (($suppliers = explode(',', $_supplier))) {
                foreach ($suppliers as $sup) {
                    $sups[] = (int)$sup;
                }
            }
            if ($sups)
                $filter .= ' AND supp.id_supplier IN (' . implode(',', array_map('intval', $sups)) . ') ';
        }
        //cats
        if (($categories = Tools::getValue('category')) && Ets_ordermanager::validateArray($categories, 'isInt')) {
            $filter .= ' AND (cp.id_category IN (' . implode(',', array_map('intval', $categories)) . ') OR od.product_id="' . (int)Configuration::get('PH_EXTEND_ID_PRODUCT') . '" ) ';
        }
        //specific_product
        if (($specific_product = Tools::getValue('specific_product')) && Validate::isCleanHtml($specific_product)) {
            $filter .= ' AND FIND_IN_SET(CONCAT(od.`product_id`,"-", IF(od.`product_attribute_id` IS NULL OR od.`product_id` = '.(int)Configuration::get('PH_EXTEND_ID_PRODUCT').', 0, od.`product_attribute_id`)), "' . pSQL($specific_product) . '") ';

        }

        if (($spent_from = (float)Tools::getValue('spent_from')) && (float)$spent_from > 0) {
            $filter .= ' AND o.total_paid_tax_incl >=' . (float)$spent_from . '*cu.conversion_rate';
        }
        if (($spent_to = (float)Tools::getValue('spent_to')) && (float)$spent_to > 0) {
            $filter .= ' AND o.total_paid_tax_incl <=' . (float)$spent_to . '*cu.conversion_rate';
        }
        $date_type = Tools::getValue('date_type');
        if ($date_type == 'this_month') {
            $filename = 'this_month_';
            $filter .= ' AND MONTH(o.date_add) = ' . (int)date('m') . ' AND YEAR(o.date_add) = ' . (int)date('Y');
        }
        if ($date_type == 'this_year') {
            $filename = 'this_year_';
            $filter .= ' AND YEAR(o.date_add) = ' . (int)date('Y');
        }
        if ($date_type == 'month_1') {
            $filename = 'last_month_';
            $prevmonth = (int)date('m') - 1;
            if ($prevmonth < 1)
                $prevmonth = 12;
            $year = date('Y');
            if ($prevmonth >= 12)
                $year -= 1;
            $filter .= ' AND MONTH(o.date_add) = ' . (int)$prevmonth . ' AND YEAR(o.date_add) = ' . (int)$year;
        }
        if ($date_type == 'year_1') {
            $filename = 'last_year_';
            $prevyear = (int)date('Y') - 1;
            $filter .= ' AND YEAR(o.date_add) = ' . (int)$prevyear;
        }
        if ($date_type == 'from_to') {
            $filename = 'from_to_';
        }
        if ($date_type == 'from_to' && ($from_date = Tools::getValue('from_date')) && $from_date != '0000-00-00' && Validate::isDate($from_date)) {
            $filter .= ' AND o.date_add >="' . pSQL($from_date) . ' 00:00:00"';
        }
        if ($date_type == 'from_to' && ($to_date = Tools::getValue('to_date')) && $to_date != '000-00-00' && Validate::isDate($to_date)) {
            $filter .= ' AND o.date_add <="' . pSQL($to_date) . ' 23:59:59"';
        }
        if ($date_type == 'day_before' && ($day_before = Tools::getValue('day_before'))) {
            $filter .= ' AND DATEDIFF("' . date('Y-m-d H:i:s') . '", o.date_add) <="' . (int)$day_before . '"';
        }
        if ($date_type == 'day_before') {
            $filename = 'the_day_before_';
        }
        if ($date_type == 'any_date')
            $filename = 'any_date';
        //config order before export 1.
        $sort_by = Tools::getValue('sort_by');
        $convert_in_currency = (int)Tools::getValue('convert_in_currency');
        $orders = $this->getOrderExport($exported_fields, $filter, $sort_by && Validate::isCleanHtml($sort_by) ? $sort_by : '', $convert_in_currency);
        $file_name_prefix = Tools::getValue('file_name_prefix');
        $file_name_incl_name_rule = (int)Tools::getValue('file_name_incl_name_rule');
        $name_lang = Tools::getValue('name_' . $this->context->language->id);
        $name_lang_default = Tools::getValue('name_' . (int)Configuration::get('PS_LANG_DEFAULT'));
        $filename = ($file_name_prefix && Validate::isCleanHtml($file_name_prefix) ? $file_name_prefix : '') . ($file_name_incl_name_rule ? ($name_lang && Validate::isCleanHtml($name_lang) ? $name_lang : ($name_lang_default && Validate::isCleanHtml($name_lang_default) ? $name_lang_default : '')) . '_' : '') . $filename;
        $args = array(
            'filename' => $filename,
            'orders' => $orders,
        );
        $file_format = Tools::getValue('file_format');
        if ($sendmail) {
            if (($receivers_mail = Tools::getValue('receivers_mail')) && ($receivers_mail = explode(',', $receivers_mail)) && Ets_ordermanager::validateArray(array_map('trim', $receivers_mail), 'isEmail')) {
                $template_vars = array(
                    '{description_email}' => ($description_mail = Tools::getValue('description_mail')) && Validate::isCleanHtml($description_mail) ? str_replace('[date]', date('d_m_Y'), $description_mail) : $this->l('Here is order on ') . date('d_m_Y'),
                );
                $file_attachement = array();

                if ($file_format == 'csv') {
                    $file_attachement['content'] = $this->exportOrderCSV($args);
                    $file_attachement['name'] = $filename . date('d_m_Y') . '.csv';
                    $file_attachement['mime'] = 'application/csv';
                } elseif ($file_format == 'xls') {
                    $file_attachement['content'] = $this->exportOrderXLS($args);
                    $file_attachement['name'] = $filename . date('d_m_Y') . '.xls';
                    $file_attachement['mime'] = 'application/xls';
                } elseif ($file_format == 'xlsx') {
                    $file_attachement['content'] = $this->exportOrderXLSX($args);
                    $file_attachement['name'] = $filename . date('d_m_Y') . '.xlsx';
                    $file_attachement['mime'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                } else {
                    $file_attachement['content'] = $this->exportOrderXML($args);
                    $file_attachement['name'] = $filename . date('d_m_Y') . '.xml';
                    $file_attachement['mime'] = 'text/xml';
                }
                foreach ($receivers_mail as $email) {
                    if (!($id_lang = (int)Db::getInstance()->getValue('SELECT id_lang FROM `' . _DB_PREFIX_ . 'employee` WHERE email ="' . pSQL($email) . '"')))
                        $id_lang = $this->context->language->id;
                    Mail::Send(
                        (int)$id_lang,
                        'order_export',
                        ($title_mail = Tools::getValue('title_mail')) && Validate::isCleanHtml($title_mail) ? str_replace('[date]', date('d/m/Y'), $title_mail) : ($this->getTextLang('Order on', $id_lang) ?: $this->l('Order on ')) . date('dmY'),
                        $template_vars,
                        trim($email),
                        null,
                        null,
                        null,
                        $file_attachement,
                        null,
                        dirname(__FILE__) . '/mails/',
                        null,
                        $this->context->shop->id
                    );
                }
            }
        } else {
            $args['display'] = true;
            if ($file_format == 'xml') {
                $this->exportOrderXML($args);
            } elseif ($file_format == 'xls') {
                $this->exportOrderXLS($args);
            } elseif ($file_format == 'xlsx') {
                $this->exportOrderXLSX($args);
            } else {
                $this->exportOrderCSV($args);
            }
        }
    }

    public function isAll($values)
    {
        if (in_array('all', $values))
            return false;
        return true;
    }

    public function postConfigs()
    {
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $configs = Ode_defines::getInstance($this)->getFields('config');
        if ($configs) {
            foreach ($configs as $key => $config) {
                if (isset($config['lang']) && $config['lang']) {
                    $val_lang_default = Tools::getValue($key . '_' . $id_lang_default);
                    if (isset($config['required']) && $config['required'] && $config['type'] != 'switch' && trim($val_lang_default) == '') {
                        $this->_errors[] = sprintf($this->l('%s is required'), $config['label']);
                    }
                    foreach ($languages as $lang) {
                        $val_lang = Tools::getValue($key . '_' . $lang['id_lang']);
                        if ($val_lang && !Validate::isCleanHtml($val_lang))
                            $this->_errors[] = sprintf($this->l('%s is not valid in %s'), $config['label'], $lang['iso_code']);
                    }
                } else {
                    $val = Tools::getValue($key);
                    if (isset($config['required']) && $config['required'] && $config['type'] != 'switch' && $this->requiredFields($key)) {
                        $this->_errors[] = sprintf($this->l('%s is required'), $config['label']);
                    } elseif (isset($config['validate']) && method_exists('Validate', $config['validate'])) {
                        $validate = $config['validate'];
                        if ($val && !Validate::$validate(trim($val)))
                            $this->_errors[] = sprintf($this->l('%s is invalid'), $config['label']);
                        unset($validate);
                    } elseif (!is_array($val) && !Validate::isCleanHtml(trim($val))) {
                        $this->_errors[] = sprintf($this->l('%s is invalid'), $config['label']);
                    }
                }
            }
        }
        $ETS_ODE_HOST = Tools::getValue('ETS_ODE_HOST');
        $ETS_ODE_USERNAME = Tools::getValue('ETS_ODE_USERNAME');
        $ETS_ODE_PASSWORD = Tools::getValue('ETS_ODE_PASSWORD');
        if (trim($ETS_ODE_HOST) != '' || trim($ETS_ODE_USERNAME) != '' || trim($ETS_ODE_PASSWORD) != '') {
            if (trim($ETS_ODE_HOST) == '')
                $this->_errors[] = $this->l('Host is required');
            if (trim($ETS_ODE_USERNAME) == '')
                $this->_errors[] = $this->l('Username is required');
            if (trim($ETS_ODE_PASSWORD) == '')
                $this->_errors[] = $this->l('Password is required');
        }
        if (!$this->_errors) {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        $values = array();
                        $val_lang_default = Tools::getValue($key . '_' . $id_lang_default);
                        foreach ($languages as $lang) {
                            $val_lang = Tools::getValue($key . '_' . $lang['id_lang']);
                            $values[$lang['id_lang']] = trim($val_lang) ?: trim($val_lang_default);
                        }
                        $this->updateValues($config, $key, $values, true);
                    } else {
                        $val = Tools::getValue($key);
                        if ($config['type'] == 'switch') {
                            $this->updateValues($config, $key, (int)trim($val) ? 1 : 0, true);
                        } elseif (($config['type'] == 'select' && isset($config['multiple']) && $config['multiple']) || ($config['type'] == 'check_list')) {
                            $this->updateValues($config, $key, implode(',', $val ?: array()), true);
                        } else
                            $this->updateValues($config, $key, trim($val), true);
                    }
                }
            }
        }
        if ($this->_errors) {
            {
                if (Tools::isSubmit('ajax')) {
                    die(
                    json_encode(
                        array(
                            'error' => $this->displayError($this->_errors),
                        )
                    )
                    );
                } else
                    $this->errorMessage = $this->displayError($this->_errors);
            }
        } else {
            if (Tools::isSubmit('ajax')) {
                $this->context->smarty->assign(
                    array(
                        'php_path' => (defined('PHP_BINDIR') && PHP_BINDIR && is_string(PHP_BINDIR) ? PHP_BINDIR . '/' : '') . 'php ',
                        'dir_cronjob' => dirname(__FILE__) . '/cronjob.php',
                        'ETS_ODE_CRONJOB_TOKEN' => Configuration::getGlobalValue('ETS_ODE_CRONJOB_TOKEN'),
                        'link_cronjob' => $this->getBaseLink() . '/modules/' . $this->name . '/cronjob.php?secure=' . Configuration::getGlobalValue('ETS_ODE_CRONJOB_TOKEN'),
                    )
                );
                die(
                json_encode(
                    array(
                        'success' => $this->l('Successful update'),
                        'cronjob_help_block' => $this->display(__FILE__, 'cronjob_help_block.tpl'),
                    )
                )
                );
            } else
                Tools::redirectAdmin($this->getAdminLink(4));
        }
    }

    public function getBaseLink()
    {
        $link = (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? 'https://' : 'http://') . $this->context->shop->domain . $this->context->shop->getBaseURI();
        return trim($link, '/');
    }

    public function updateValues($config, $key, $value, $html = false)
    {
        if (isset($config['global']) && $config['global'])
            Configuration::updateGlobalValue($key, $value, $html);
        else
            Configuration::updateValue($key, $value, $html);
    }

    public function requiredFields($key)
    {
        if (!$key)
            return false;
        $ETS_ODE_USE_CRONJOB = (int)Tools::getValue('ETS_ODE_USE_CRONJOB');
        $val = trim(Tools::getValue($key));
        switch ($key) {
            case 'ETS_ODE_CRONJOB_TOKEN':
                if ($ETS_ODE_USE_CRONJOB && $val == '')
                    return true;
                break;
            default:
                if ($val == '')
                    return true;
                break;
        }
        return false;
    }

    public function getAdminLink($conf = 0)
    {
        $args = ($conf ? '&conf=' . $conf : '') . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . (($tab = Tools::getValue('control')) && in_array($tab, array('manager_order', 'order_export', 'settings')) ? '&control=' . $tab : '');
        if (!$this->is15)
            return $this->context->link->getAdminLink('AdminModules', true) . $args;
        else
            return AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules') . $args;
    }

    public function getAdminHtml()
    {
        $this->smarty->assign(array(
            'ets_ordermanager_module_dir' => $this->_path,
            'ets_ordermanager_sidebar' => $this->renderSidebar(),
            'ets_ordermanager_body_html' => $this->renderAdminBodyHtml(),
            'ets_ordermanager_error_message' => $this->errorMessage,
            'token' => md5($this->id),
            'ETS_ODE_USE_CRONJOB' => (int)Configuration::getGlobalValue('ETS_ODE_USE_CRONJOB'),
            'module_link' => $this->getModuleLink(),
            'ets_odm_link_customer_search' => $this->context->link->getAdminLink('AdminOrderManagerExports') . '&searchCustomer=1',
        ));
        return $this->display(__FILE__, 'admin.tpl');
    }

    public function getCustomers($customerIds)
    {
        if ($customerIds) {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'customer` WHERE id_customer in (' . implode(',', array_map('intval', explode(',', $customerIds))) . ')';
            return Db::getInstance()->executeS($sql);
        } else
            return array();
    }

    public function renderSidebar()
    {
        $this->baseAdminPath = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $control = Tools::getValue('control');
        $this->smarty->assign(
            array(
                'link' => $this->context->link,
                'list' => array(
                    array(
                        'label' => $this->l('Manage orders'),
                        'url' => $this->baseAdminPath . '&control=manager_order',
                        'id' => 'ets_tab_manager_order',
                        'icon' => 'icon-shopping_basket'
                    ),
                    array(
                        'label' => $this->l('Export orders'),
                        'url' => $this->baseAdminPath . '&control=order_export&list=true',
                        'id' => 'ets_tab_order_export',
                        'icon' => 'icon-AdminPriceRule'
                    ),

                    array(
                        'label' => $this->l('Settings'),
                        'url' => $this->baseAdminPath . '&control=settings',
                        'id' => 'ets_tab_settings',
                        'icon' => 'icon-settings'
                    )

                ),
                'admin_path' => $this->baseAdminPath,
                'active' => 'ets_tab_' . ($control && in_array($control, array('manager_order', 'order_export', 'settings')) ? $control : 'export_order')
            )
        );
        return $this->display(__FILE__, 'sidebar.tpl');
    }

    public function renderAdminBodyHtml()
    {
        $this->renderConfigForm();
        return $this->_html;
    }

    public function renderConfigForm()
    {
        $configs = Ode_defines::getInstance()->getFields('config');
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        if ($configs) {
            foreach ($configs as $key => $config) {
                $arg = array(
                    'name' => $key,
                    'type' => $config['type'],
                    'label' => $config['label'],
                    'desc' => isset($config['desc']) ? $config['desc'] : false,
                    'col' => isset($config['col']) ? $config['col'] : 9,
                    'required' => isset($config['required']) && $config['required'] ? true : false,
                    'options' => isset($config['options']) && $config['options'] ? $config['options'] : array(),
                    'values' => $config['type'] == 'switch' ? array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ) : (isset($config['values']) ? $config['values'] : false),
                    'lang' => isset($config['lang']) ? $config['lang'] : false,
                    'tab' => isset($config['tab']) && $config['tab'] ? $config['tab'] : 'general',
                    'suffix' => isset($config['suffix']) && $config['suffix'] ? $config['suffix'] : false,
                    'autoload_rte' => isset($config['autoload_rte']) ? $config['autoload_rte'] : false,
                    'default' => isset($config['default']) ? $config['default'] : false,
                    'autocomplete' => isset($config['autocomplete']) ? $config['autocomplete'] : true,
                );
                if (isset($arg['suffix']) && !$arg['suffix'])
                    unset($arg['suffix']);
                $fields_form['form']['input'][] = $arg;
            }
        }
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveConfig';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . '&control=settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $fields = array();
        $languages = Language::getLanguages(false);
        $helper->override_folder = '/';
        if (Tools::isSubmit('saveConfig')) {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $fields[$key][$l['id_lang']] = Tools::getValue($key . '_' . $l['id_lang'], isset($config['default']) ? $config['default'] : '');
                        }
                    } elseif ($config['type'] == 'check_list') {
                        $fields[$key] = Tools::getValue($key, isset($config['default']) ? explode(',', $config['default']) : array());
                    } else
                        $fields[$key] = Tools::getValue($key, isset($config['default']) ? $config['default'] : '');
                }
            }
        } else {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $fields[$key][$l['id_lang']] = Configuration::get($key, $l['id_lang']);
                        }
                    } elseif ($config['type'] == 'check_list') {
                        $fields[$key] = Configuration::get($key) != '' ? explode(',', Configuration::get($key)) : array();
                    } else
                        $fields[$key] = Configuration::get($key);
                }
            }
        }
        $intro = true;
        $localIps = array(
            '127.0.0.1',
            '::1'
        );
        $baseURL = Tools::strtolower(self::getBaseModLink());
        if (!Tools::isSubmit('intro') && (in_array(Tools::getRemoteAddr(), $localIps) || preg_match('/^.*(localhost|demo|test|dev|:\d+).*$/i', $baseURL)))
            $intro = false;
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $fields,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'isConfigForm' => true,
            'image_baseurl' => $this->_path . 'views/img/',
            'path_uri' => $this->getPathUri(),
            'path_local' => $this->getLocalPath(),
            'domain' => Tools::getShopDomainSsl(true, true),
            'is15' => $this->is15,
            'order_manager_form' => $this->renderFormOrderManager(),
            'link' => $this->context->link,
            'ets_cron_log' => file_exists(dirname(__FILE__) . '/cronjob.log') ? Tools::file_get_contents(dirname(__FILE__) . '/cronjob.log') : '',
            'settingTabs' => Ode_defines::getInstance($this)->getFields('tab_setting'),
            'other_modules_link' => isset($this->refs) ? $this->refs . $this->context->language->iso_code : $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&othermodules=1',
            'time_zone' => date_default_timezone_get(),
            'time_now' => date('Y-m-d H:i:s'),
            'intro' => $intro,
            'php_path' => (defined('PHP_BINDIR') && PHP_BINDIR && is_string(PHP_BINDIR) ? PHP_BINDIR . '/' : '') . 'php ',
            'refsLink' => isset($this->refs) ? $this->refs . $this->context->language->iso_code : false,
        );
        $this->_html .= $helper->generateForm(array($fields_form));
    }

    public static function getBaseModLink()
    {
        $context = Context::getContext();
        return (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? 'https://' : 'http://') . $context->shop->domain . $context->shop->getBaseURI();
    }

    public function displayRecommendedModules()
    {
        $cacheDir = dirname(__file__) . '/../../cache/' . $this->name . '/';
        $cacheFile = $cacheDir . 'module-list.xml';
        $cacheLifeTime = 24;
        $cacheTime = (int)Configuration::getGlobalValue('ETS_MOD_CACHE_' . $this->name);
        $profileLinks = array(
            'en' => 'https://addons.prestashop.com/en/207_ets-soft',
            'fr' => 'https://addons.prestashop.com/fr/207_ets-soft',
            'it' => 'https://addons.prestashop.com/it/207_ets-soft',
            'es' => 'https://addons.prestashop.com/es/207_ets-soft',
        );
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
            if (@file_exists(dirname(__file__) . '/index.php')) {
                @copy(dirname(__file__) . '/index.php', $cacheDir . 'index.php');
            }
        }
        if (!file_exists($cacheFile) || !$cacheTime || time() - $cacheTime > $cacheLifeTime * 60 * 60) {
            if (file_exists($cacheFile))
                @unlink($cacheFile);
            if ($xml = self::file_get_contents($this->shortlink . 'ml.xml')) {
                $xmlData = @simplexml_load_string($xml);
                if ($xmlData && (!isset($xmlData->enable_cache) || (int)$xmlData->enable_cache)) {
                    @file_put_contents($cacheFile, $xml);
                    Configuration::updateGlobalValue('ETS_MOD_CACHE_' . $this->name, time());
                }
            }
        } else
            $xml = Tools::file_get_contents($cacheFile);
        $modules = array();
        $categories = array();
        $categories[] = array('id' => 0, 'title' => $this->l('All categories'));
        $enabled = true;
        $iso = Tools::strtolower($this->context->language->iso_code);
        $moduleName = $this->displayName;
        $contactUrl = '';
        if ($xml && ($xmlData = @simplexml_load_string($xml))) {
            if (isset($xmlData->modules->item) && $xmlData->modules->item) {
                foreach ($xmlData->modules->item as $arg) {
                    if ($arg) {
                        if (isset($arg->module_id) && (string)$arg->module_id == $this->name && isset($arg->{'title' . ($iso == 'en' ? '' : '_' . $iso)}) && (string)$arg->{'title' . ($iso == 'en' ? '' : '_' . $iso)})
                            $moduleName = (string)$arg->{'title' . ($iso == 'en' ? '' : '_' . $iso)};
                        if (isset($arg->module_id) && (string)$arg->module_id == $this->name && isset($arg->contact_url) && (string)$arg->contact_url)
                            $contactUrl = $iso != 'en' ? str_replace('/en/', '/' . $iso . '/', (string)$arg->contact_url) : (string)$arg->contact_url;
                        $temp = array();
                        foreach ($arg as $key => $val) {
                            if ($key == 'price' || $key == 'download')
                                $temp[$key] = (int)$val;
                            elseif ($key == 'rating') {
                                $rating = (float)$val;
                                if ($rating > 0) {
                                    $ratingInt = (int)$rating;
                                    $ratingDec = $rating - $ratingInt;
                                    $startClass = $ratingDec >= 0.5 ? ceil($rating) : ($ratingDec > 0 ? $ratingInt . '5' : $ratingInt);
                                    $temp['ratingClass'] = 'mod-start-' . $startClass;
                                } else
                                    $temp['ratingClass'] = '';
                            } elseif ($key == 'rating_count')
                                $temp[$key] = (int)$val;
                            else
                                $temp[$key] = (string)strip_tags($val);
                        }
                        if ($iso) {
                            if (isset($temp['link_' . $iso]) && isset($temp['link_' . $iso]))
                                $temp['link'] = $temp['link_' . $iso];
                            if (isset($temp['title_' . $iso]) && isset($temp['title_' . $iso]))
                                $temp['title'] = $temp['title_' . $iso];
                            if (isset($temp['desc_' . $iso]) && isset($temp['desc_' . $iso]))
                                $temp['desc'] = $temp['desc_' . $iso];
                        }
                        $modules[] = $temp;
                    }
                }
            }
            if (isset($xmlData->categories->item) && $xmlData->categories->item) {
                foreach ($xmlData->categories->item as $arg) {
                    if ($arg) {
                        $temp = array();
                        foreach ($arg as $key => $val) {
                            $temp[$key] = (string)strip_tags($val);
                        }
                        if (isset($temp['title_' . $iso]) && $temp['title_' . $iso])
                            $temp['title'] = $temp['title_' . $iso];
                        $categories[] = $temp;
                    }
                }
            }
        }
        if (isset($xmlData->{'intro_' . $iso}))
            $intro = $xmlData->{'intro_' . $iso};
        else
            $intro = isset($xmlData->intro_en) ? $xmlData->intro_en : false;
        $this->smarty->assign(array(
            'modules' => $modules,
            'enabled' => $enabled,
            'module_name' => $moduleName,
            'categories' => $categories,
            'img_dir' => $this->_path . 'views/img/',
            'intro' => $intro,
            'shortlink' => $this->shortlink,
            'ets_profile_url' => isset($profileLinks[$iso]) ? $profileLinks[$iso] : $profileLinks['en'],
            'trans' => array(
                'txt_must_have' => $this->l('Must-Have'),
                'txt_downloads' => $this->l('Downloads!'),
                'txt_view_all' => $this->l('View all our modules'),
                'txt_fav' => $this->l('Prestashop\'s favourite'),
                'txt_elected' => $this->l('Elected by merchants'),
                'txt_superhero' => $this->l('Superhero Seller'),
                'txt_partner' => $this->l('Module Partner Creator'),
                'txt_contact' => $this->l('Contact us'),
                'txt_close' => $this->l('Close'),
            ),
            'contactUrl' => $contactUrl,
        ));
        echo $this->display(__FILE__, 'module-list.tpl');
        die;
    }

    public static function file_get_contents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 60)
    {
        if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
            $stream_context = stream_context_create(array(
                "http" => array(
                    "timeout" => $curl_timeout,
                    "max_redirects" => 101,
                    "header" => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36'
                ),
                "ssl" => array(
                    "allow_self_signed" => true,
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            ));
        }
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => html_entity_decode($url),
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => $curl_timeout,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
            ));
            $content = curl_exec($curl);
            curl_close($curl);
            return $content;
        } elseif (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            return Tools::file_get_contents($url, $use_include_path, $stream_context);
        } else {
            return false;
        }
    }

    public function getModuleLink()
    {
        if (!(isset($this->baseAdminPath)) || !$this->baseAdminPath) {
            $this->baseAdminPath = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        }
        return $this->baseAdminPath;
    }

    public function map_colnames($input)
    {
        return isset($this->colnames[$input]) ? $this->colnames[$input] : $input;
    }

    public function cleanData(&$str)
    {
        if ($str == 't') $str = 'TRUE';
        if ($str == 'f') $str = 'FALSE';
        if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
            $str = "'$str";
        }
        if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    public function hookActionDispatcherBefore($params)
    {
        $context = $this->context;
        if(isset($params['controller_type']) && $params['controller_type']==Dispatcher::FC_ADMIN && isset($context->employee->id) && $context->employee->id && $context->cookie->passwd && $context->employee->isLoggedBack())
        {
            $controller = Tools::getValue('controller');
            if ($controller == 'AdminOrders' || $controller == 'adminorders' || $controller == 'AdminOrderManagerExports' || $controller=='AdminCustomers')
            {
                $this->addKeyTwig();
            }
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
                if (Tools::isSubmit('getFormDuplicate') || isset($this->bulk_orders['restore_all_order']) || isset($this->bulk_orders['delete_all_order']) || isset($this->bulk_orders['print_slips_all_order']) || isset($this->bulk_orders['print_invoice_all_order']) || isset($this->bulk_orders['print_delivery_label_all_order']) || Tools::isSubmit('btnSubmitDuplicateOrder') || Tools::isSubmit('btnSubmitArrangeListOrder') || Tools::isSubmit('btnSubmitRessetToDefaultList') || Tools::isSubmit('quickvieworder') || Tools::isSubmit('changeorderinline') || Tools::isSubmit('btnSubmitEditCustomerOrder'))
                    $this->_postOrder();
            }
        }
    }
    public function addJquery()
    {
        if (version_compare(_PS_VERSION_, '1.7.6.0', '>=') && version_compare(_PS_VERSION_, '1.7.7.0', '<'))
            $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/jquery-' . _PS_JQUERY_VERSION_ . '.min.js');
        else
            $this->context->controller->addJquery();
    }

    public function hookDisplayShopLicenseEditField($params)
    {
        $request = $this->getRequestContainer();
        if (isset($params['id_product']) && ($id_product = $params['id_product']) && (($id_order = Tools::getValue('id_order')) || ($request && ($id_order = $request->get('orderId'))))) {
            $sql = 'SELECT od.product_id,od.product_name,od.product_attribute_id FROM `' . _DB_PREFIX_ . 'order_detail` od
            WHERE od.id_order=' . (int)$id_order . ' AND od.product_id="' . (int)$id_product . '"';
            $product = Db::getInstance()->getRow($sql);
            if ($product) {
                $this->context->smarty->assign(
                    array(
                        'id_product' => $id_product,
                        'id_order' => $id_order,
                        'product_name' => $product['product_name'],
                        'id_product_attribute' => $product['product_attribute_id'],
                    )
                );
                return $this->display(__FILE__, 'product_name_edit.tpl');
            }
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::isSubmit('changeProductNameInstall') && ($id_product = Tools::getValue('id_product')) && ($id_order = Tools::getValue('id_order'))) {
            $error = '';
            if (!($product_name = Tools::getValue('product_name')))
                $error = $this->l('Product name is required');
            elseif (!Validate::isCleanHtml($product_name))
                $error = $this->l('Product name is not valid');
            $id_product_attribute = (int)Tools::getValue('id_product_attribute');
            if (!$error) {
                Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'order_detail` SET product_name="' . pSQL($product_name) . '" WHERE product_id="' . (int)$id_product . '" AND id_order="' . (int)$id_order . '" AND product_attribute_id="' . (int)$id_product_attribute . '"');
                $success = $this->l('Updated successfully');
                die(
                json_encode(
                    array(
                        'success' => $success,
                        'product_name' => $product_name,
                    )
                )
                );
            } else {
                die(
                json_encode(
                    array(
                        'errors' => $error,
                    )
                )
                );
            }
        }
        $controller = Tools::getValue('controller');
        $configure = Tools::getValue('configure');
        if (($configure == $this->name && $controller == 'AdminModules') || $controller == 'AdminOrderManagerExports') {
            $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
            if ($this->is15)
                $this->context->controller->addCSS($this->_path . 'views/css/admin15.css');
            $this->context->controller->addCSS($this->_path . 'views/css/other.css');
            $this->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/other.js');
            $this->context->controller->addJs($this->_path.'views/js/admin.js');
        }
        if ($controller == 'AdminOrders' || $controller == 'adminorders' || $controller == 'AdminOrderManagerExports') {
            $this->context->controller->addCSS($this->_path . 'views/css/order.css');
            $this->context->controller->addCSS($this->_path . 'views/css/ets_datetimepicker.css');
            $this->addJquery();
            $this->context->controller->addJqueryUI('ui.widget');
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJqueryPlugin('typeWatch');
            $this->context->controller->addJqueryPlugin('highlight');
            $this->context->controller->addJS($this->_path . 'views/js/moment.js');
            $this->context->controller->addJS($this->_path . 'views/js/ets_datetimepicker.js');
            $this->context->controller->addJS($this->_path . 'views/js/order.js');
            $this->context->controller->addJqueryUI('ui.datepicker');
            $this->context->smarty->assign('ets_odm_can_delete_order', $this->checkAccess('delete'));
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
                $this->context->controller->addCSS($this->_path . 'views/css/order1770.css');
                $_conf = array();
                $_conf['1111'] = $this->l('Order is moved to Trash successfully, you can restore the order from Trash or delete it forever.') . ' <a href="' . $this->context->link->getAdminLink('AdminOrders') . '&viewtrash=1" title="' . $this->l('View Trash') . '">' . $this->l('View Trash') . '</a>';
                $_conf['2222'] = $this->l('Order restored successfully.') . ' <a href="' . $this->context->link->getAdminLink('AdminOrders') . '" title="' . $this->l('View Order list') . '">' . $this->l('View Order list') . '</a>';;
                $_conf['3333'] = $this->l('Emptied trash successfully');
                $_conf['333'] = $this->l('Deleted order status successfully');
                $_conf['3'] = $this->l('Duplicated order successfully');
                $_conf['1'] = $this->l('Successfully deleted');
                if (($conf = (int)Tools::getValue('conf')) && isset($_conf[$conf])) {
                    $this->context->controller->confirmations = $_conf[$conf];
                }
            }
            $request = $this->getRequestContainer();
            $idOrder = null;
            if ($request) {
                $idOrder = $request->get('orderId');
            } else {
                $idOrder = (int)Tools::getValue('id_order');
            }

            if (!$idOrder && Configuration::get('ETS_ODM_ENABLE_INSTANT_FILTER') && (!($order = Tools::getValue('order')) || !isset($order['filters']) || !($filters = $order['filters']) || !isset($filters['ajax_filter']))) {
                $filter = Db::getInstance()->getValue('SELECT filter FROM `' . _DB_PREFIX_ . 'admin_filter` WHERE filter_id="order" AND employee="' . (int)$this->context->employee->id . '"');
                if ($filter) {
                    $admin_filter = json_decode($filter, true);
                    if (isset($admin_filter['filters']) && count($admin_filter['filters']) >= 2) {
                        $admin_filter['filters'] = array();
                        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'admin_filter` set filter="' . pSQL(json_encode($admin_filter)) . '" WHERE filter_id="order" AND employee="' . (int)$this->context->employee->id . '"');
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
                    }
                }
            }
        }
        if ($controller == 'AdminCustomers') {
            $this->context->controller->addCSS($this->_path . 'views/css/customer.css');
            $this->context->controller->addRowAction('loginascustomer');
        }
        if ($controller == 'AdminAddresses') {
            $this->context->controller->addCSS($this->_path . 'views/css/addresses.css');

        }
        if ($this->_errors && !$this->context->controller->errors)
            $this->context->controller->errors = $this->_errors;
        if ($controller == 'AdminCustomers') {
            $request = $this->getRequestContainer();
            $idCustomer = null;
            if ($request) {
                $idCustomer = $request->get('customerId');
            } else {
                $idCustomer = (int)Tools::getValue('id_customer');
            }
            if ($idCustomer) {
                $link_login_as_customer = $this->context->link->getAdminLink('AdminOrderManagerExports') . '&loginascustomerorder&id_customer=' . (int)$idCustomer;
                $this->smarty->assign(
                    array(
                        'link_login_as_customer' => $link_login_as_customer,
                    )
                );
                return $this->display(__FILE__, 'admin_header.tpl');
            }
        }
    }

    public function getSfContainer()
    {
        if (!class_exists('\PrestaShop\PrestaShop\Adapter\SymfonyContainer')) {
            $kernel = null;
            try {
                $kernel = new AppKernel('prod', false);
                $kernel->boot();
                return $kernel->getContainer();
            } catch (Exception $ex) {
                return null;
            }
        }
        $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer', 'getInstance'));
        return $sfContainer;
    }
    public function addKeyTwig()
    {
        if(version_compare(_PS_VERSION_, '1.7.7.0', '>='))
        {
            $request = $this->getRequestContainer();
            $pres8 = version_compare(_PS_VERSION_, '8', '>=');
            $this->assignTwigVar(
                array(
                    'ets_odm_link_order_duplicate'=>$this->context->link->getAdminLink('AdminOrderManagerExports') . '&duplicateorder&getFormDuplicate&ajax=1',
                    'ets_odm_link_order_print_label_delivery'=>$this->context->link->getAdminLink('AdminOrderManagerExports') . '&printdeliverylabelorder',
                    'ets_odm_link_order_login_as_customer' => $this->context->link->getAdminLink('AdminOrderManagerExports') . '&loginascustomerorder',
                    'ets_odm_link_order_restoreorder'=>$this->context->link->getAdminLink('AdminOrderManagerExports') . '&restoreorder',
                    'ets_odm_link_order_viewtrash'=>$this->context->link->getAdminLink('AdminOrders') . '&viewtrash',
                    'ets_odm_link_list_orders'=>$this->context->link->getAdminLink('AdminOrders'),
                    'Customize_order_list_text'=>$this->l('Customize order list'),
                    'ets_odm_link_order_arrange'=>$this->context->link->getAdminLink('AdminOrderManagerExports') . '&arrangeorder=1',
                    'module_ets_ordermanager' => $this,
                    'ets_odm_link_order_delete' =>$this->context->link->getAdminLink('AdminOrderManagerExports') . (Tools::isSubmit('viewtrash') || Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently' ? '&deleteOrder' : '&deleteordertrash'),
                    'ets_odm_confirm_trash_all_order'=>$this->l('Orders will be removed from list and moved to Trash, do you want to remove it?'),
                    'ets_odm_confirm_delete_all_order' =>$this->l('You are going to delete this order permanently and will not be able to restore it. Do you want to delete this order?'),
                    'ETS_ODE_BEHAVIOR_DELETE_ORDER' => Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER'),
                    'ETS_ODM_ENABLE_INSTANT_FILTER' => Configuration::get('ETS_ODM_ENABLE_INSTANT_FILTER'),
                    'Login_as_customer_text' =>$this->l('Login as customer'),
                    'Print_shipping_label_text' =>$this->l('Print shipping label'),
                    'Change_customer_text' => $this->l('Change customer'),
                    'ets_odm_can_delete_order' =>$this->checkAccess('delete'),
                    'ets_odm_can_edit_order' =>$this->checkAccess('update'),
                    'Trash_orders_text'=>$this->l('Trash'),
                    'ets_omd_is_viewtrash'=>Tools::isSubmit('viewtrash'),
                    'ets_odm_Save_text'=>$this->l('Save'),
                    'Print_selected_delivery_label_text'=>$this->l('Print selected shipping labels of selected orders'),
                    'ets_odm_Note_about_this_order_text'=>$this->l('Note about this order, only visible to staffs in back office.'),
                    'ets_odm_Private_order_note_text'=>$this->l('Private order note'),
                    'ets_odm_tax_excl_text'=>$this->l('tax excl'),
                    'ets_odm_tax_incl_text'=>$this->l('tax incl'),
                    'Restore_selected_orders_text'=>$this->l('Restore selected orders'),
                    'Delete_selected_orders_text'=>$this->l('Delete selected orders'),
                    'Print_selected_invoices_text'=>$this->l('Print selected invoices'),
                    'Print_selected_delivery_slips_text'=>$this->l('Print selected delivery slips'),
                    'Export_orders_by_rule_text'=>$this->l('Export orders by rule'),
                    'ets_odm_link_order_manager'=>$this->context->link->getAdminLink('AdminOrderManagerExports'),
                    'pres8' => $pres8,
                    'ets_odm_order_index_twig' => $pres8 ? '@!PrestaShop/Admin/Sell/Order/Order:index.html.twig': 'PrestaShopBundle:Admin/Sell/Order/Order:index.html.twig',
                    'ets_odm_gird_panel_twig' => $pres8 ? '@!PrestaShop/Admin/Common/Grid/grid_panel.html.twig' : 'PrestaShopBundle:Admin/Common/Grid:grid_panel.html.twig',
                    'ets_odm_gird_html_twig' => $pres8 ? '@!PrestaShop/Admin/Common/Grid/grid.html.twig':'PrestaShopBundle:Admin/Common/Grid:grid.html.twig',
                    'ets_odm_pagination_twig' => $pres8 ? '@!PrestaShop/Admin/Common/Grid/Blocks/pagination.html.twig':'PrestaShopBundle:Admin/Common/Grid/Blocks:pagination.html.twig',
                    'ets_odm_order_view_twig' => $pres8 ? '@!PrestaShop/Admin/Sell/Order/Order/view.html.twig': 'PrestaShopBundle:Admin/Sell/Order/Order:view.html.twig',
                    'ets_odm_carrier_list' => (($id_order = (int)Tools::getValue('id_order')) || ($request && ($id_order = $request->get('orderId')))) ? self::getListCarriersByIDOrder($id_order):'',
                )
            );
        }
    }
    public function assignTwigVar($params)
    {
        /** @var \Twig\Environment $tw */
        if(!class_exists('Ets_ordermanager_twig'))
            require_once(dirname(__FILE__).'/classes/Ets_ordermanager_twig.php');
        if($sfContainer = $this->getSfContainer())
        {
            try {
                $tw = $sfContainer->get('twig');
                $firstKey = array_keys($params)[0];
                if(!array_key_exists($firstKey, $tw->getGlobals()))
                    $tw->addExtension(new Ets_ordermanager_twig($params));
            } catch (\Twig\Error\RuntimeError $e) {
                // do no thing
            }
        }
    }
    public function addTwigVar($key, $value)
    {
        if ($sfContainer = $this->getSfContainer()) {
            $sfContainer->get('twig')->addGlobal($key, $value);
        }
    }

    public function countOrderExportWithFilter($filter = false)
    {
        $sql = 'SELECT COUNT(p.id_ets_export_order_rule) as total_payment 
                FROM `' . _DB_PREFIX_ . 'ets_export_order_rule` p,' . _DB_PREFIX_ . 'ets_export_order_rule_lang pl
                WHERE p.id_ets_export_order_rule=pl.id_ets_export_order_rule AND pl.id_lang="' . (int)$this->context->language->id . '"' . ($filter ? pSQL($filter) : '');
        $row = Db::getInstance()->getRow($sql);
        return isset($row['total_payment']) ? (int)$row['total_payment'] : 0;
    }

    public function getUrlExtra($field_list)
    {
        $params = '';
        if (($sort = Tools::strtolower(trim(Tools::getValue('sort')))) && isset($field_list[$sort])) {
            $params .= '&sort=' . $sort . '&sort_type=' . ($sort == 'asc' ? 'asc' : 'desc');
        }
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

    public function autoExport($id = 0, $log = false)
    {
        if (Configuration::getGlobalValue('ETS_ODE_USE_CRONJOB')) {
            $current_date = date('Y-m-d H:i:s');
            $common_sql = 'SELECT id_ets_export_order_rule FROM `' . _DB_PREFIX_ . 'ets_export_order_rule` WHERE 1 ' . ($id ? ' AND id_ets_export_order_rule=' . (int)$id : '');
            $sql = $common_sql . ' AND (
                
                    ("' . pSQL($current_date) . '" > send_file_date AND ((send_file_schedule = "daily" AND hour("' . pSQL($current_date) . '") = send_file_time_hours AND day("' . pSQL($current_date) . '") != day(send_file_date))
                        OR (send_file_schedule = "weekly" AND hour("' . pSQL($current_date) . '") = send_file_time_hours AND dayofweek("' . pSQL($current_date) . '") - 1 = send_file_time_weeks AND dayofyear("' . pSQL($current_date) . '") != dayofyear(send_file_date) )
                        OR (send_file_schedule = "hourly" AND hour("' . pSQL($current_date) . '") != hour(send_file_date))
                        OR (send_file_schedule= "5_minutes" AND (send_file_date="0000-00-00 00:00:00" || send_file_date <= "' . pSQL(date('Y-m-d H:i:s', strtotime("-5 minutes"))) . '" ))
                        OR (send_file_schedule= "30_minutes" AND (send_file_date="0000-00-00 00:00:00" || send_file_date <="' . pSQL(date('Y-m-d H:i:s', strtotime("-30 minutes"))) . '" ))
                        OR (send_file_schedule = "monthly" AND month(send_file_date)!= month("' . pSQL($current_date) . '") AND hour("' . pSQL($current_date) . '") = send_file_time_hours AND IF(send_file_time_months = "first", day("' . pSQL($current_date) . '") = 1, IF(send_file_time_months = "middle", day("' . pSQL($current_date) . '") = 15, day("' . pSQL($current_date) . '") = day(last_day("' . pSQL($current_date) . '")))))))

                   OR ("' . pSQL($current_date) . '" > server1_date AND ((server1_schedule = "daily" AND hour("' . pSQL($current_date) . '") = server1_time_hours AND day("' . pSQL($current_date) . '") != day(server1_date))
                       OR (server1_schedule = "weekly" AND hour("' . pSQL($current_date) . '") = server1_time_hours AND dayofweek("' . pSQL($current_date) . '") - 1 = server1_time_weeks AND dayofyear("' . pSQL($current_date) . '") != dayofyear(server1_date))
                       OR (server1_schedule = "hourly" AND hour("' . pSQL($current_date) . '") != hour(server1_date))
                       OR (send_file_schedule= "5_minutes" AND (server1_date="0000-00-00 00:00:00" || server1_date <= "' . pSQL(date('Y-m-d H:i:s', strtotime("-5 minutes"))) . '" ))
                       OR (send_file_schedule= "30_minutes" AND (server1_date="0000-00-00 00:00:00" || server1_date <="' . pSQL(date('Y-m-d H:i:s', strtotime("-30 minutes"))) . '" ))
                       OR (server1_schedule = "monthly" AND month(server1_date)!= month("' . pSQL($current_date) . '") AND hour("' . pSQL($current_date) . '") = send_file_time_hours AND IF(server1_time_months = "first", day("' . pSQL($current_date) . '") = 1, IF(server1_time_months = "middle", day("' . pSQL($current_date) . '") = 15, day("' . pSQL($current_date) . '") = day(last_day("' . pSQL($current_date) . '")))))))
                   OR ("' . pSQL($current_date) . '" > server2_date AND ((server2_schedule = "daily" AND hour("' . pSQL($current_date) . '") = server2_time_hours AND day("' . pSQL($current_date) . '") != day(server2_date))
                       OR (server2_schedule = "weekly" AND hour("' . pSQL($current_date) . '") = server2_time_hours AND dayofweek("' . pSQL($current_date) . '") - 1 = server2_time_weeks AND dayofyear("' . pSQL($current_date) . '") != dayofyear(server2_date))
                       OR (server2_schedule = "hourly" AND hour("' . pSQL($current_date) . '") != hour(server2_date))
                       OR (send_file_schedule= "5_minutes" AND (server2_date="0000-00-00 00:00:00" || server2_date <= "' . pSQL(date('Y-m-d H:i:s', strtotime("-5 minutes"))) . '" ))
                       OR (send_file_schedule= "30_minutes" AND (server2_date="0000-00-00 00:00:00" || server2_date <="' . pSQL(date('Y-m-d H:i:s', strtotime("-30 minutes"))) . '" ))
                       OR (server2_schedule = "monthly" AND month(server2_date)!= month("' . pSQL($current_date) . '") AND hour("' . pSQL($current_date) . '") = send_file_time_hours AND IF(server2_time_months = "first", day("' . pSQL($current_date) . '") = 1, IF(server2_time_months = "middle", day("' . pSQL($current_date) . '") = 15, day("' . pSQL($current_date) . '") = day(last_day("' . pSQL($current_date) . '")))))))
                )
            ';
            $orders_exporter = Db::getInstance()->executeS($sql);
            $cron_job = false;
            if ($orders_exporter) {
                foreach ($orders_exporter as $order_exporter) {
                    if ($this->actionExportOrSendOrder(array(
                        'id' => $order_exporter['id_ets_export_order_rule'],
                        'schedule' => 'auto',
                        'log' => $log
                    )))
                        $cron_job = true;
                }
            }
            //delete exported file.
            $order_exported = Db::getInstance()->executeS($common_sql);
            if ($order_exported) {
                foreach ($order_exported as $order) {
                    $this->deleteExportedFiles($order['id_ets_export_order_rule']);
                }
            }
            return $cron_job;
        }
    }

    public function getRequestContainer()
    {
        if ($this->is17) {
            if ($sfContainer = $this->getSfContainer()) {
                return $sfContainer->get('request_stack')->getCurrentRequest();
            }
        }
        return null;
    }

    public function hookDisplayDashboardToolbarTopMenu()
    {
        $toolbar_btn = array();
        $request = $this->getRequestContainer();
        $controller = Tools::getValue('controller');
        if ($controller == 'AdminOrders' && ((Tools::isSubmit('vieworder') && ($id_order = (int)Tools::getValue('id_order'))) || ($request && ($id_order = $request->get('orderId'))))) {
            $order = new Order($id_order);
            $deleted = Db::getInstance()->getValue('SELECT deleted FROM `' . _DB_PREFIX_ . 'orders` WHERE id_order=' . (int)$order->id);
            $toolbar_btn['view'] = array(
                'href' => $this->context->link->getAdminLink('AdminOrders'),
                'desc' => $this->l('Back to order list'),
                'class' => 'view-list btn-outline-secondary-ets',
                'table' => 'order',
                'icon' => 'icon-arrow-circle-left',
            );
            if ($this->checkAccess('delete')) {
                $toolbar_btn['delete'] = array(
                    'href' => (version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? $this->context->link->getAdminLink('AdminOrderManagerExports') : $this->context->link->getAdminLink('AdminOrders')) . '&id_order=' . $id_order . ($deleted || Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently' ? '&deleteorder=1&viewtrash=1' : '&deleteordertrash'),
                    'desc' => $this->l('Delete this order'),
                    'class' => 'delete btn-outline-secondary-ets',
                    'table' => 'order',
                    'icon' => 'icon-trash',
                    'js' => "if (confirm('" . ($deleted || Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently' ? $this->l('You are going to delete this order permanently and will not be able to restore it. Do you want to delete this order?') : $this->l('Order will be removed from list and moved to Trash, do you want to remove it?')) . "')){return true;}else{event.stopPropagation(); event.preventDefault();}",
                );
            }
            if ($this->checkAccess('create'))
                $toolbar_btn['duplicate'] = array(
                    'href' => $this->context->link->getAdminLink('AdminOrders') . '&id_order=' . $id_order . '&getFormDuplicate&ajax=1',
                    'desc' => $this->l('Duplicate this order'),
                    'class' => 'duplicate duplicate_order_list btn-outline-secondary-ets',
                    'table' => 'order',
                    'icon' => 'icon-copy',
                );
        } elseif ($controller == 'AdminOrders' && Tools::isSubmit('viewtrash')) {
            $toolbar_btn['view'] = array(
                'href' => $this->context->link->getAdminLink('AdminOrders'),
                'desc' => $this->l('Back to order list'),
                'class' => 'view-list btn-outline-secondary-ets',
                'table' => 'order',
                'icon' => 'icon-arrow-circle-left',
            );
        }
        if ($toolbar_btn) {
            $this->context->smarty->assign(
                array(
                    'toolbar_btn' => $toolbar_btn,
                )
            );
            return $this->display(__FILE__, 'new_action.tpl');
        }
        return '';
    }

    public static function isCurrentlyUsed($table = null, $has_active_column = false)
    {
        $query = new DbQuery();
        $query->select('`id_' . bqSQL($table) . '`');
        $query->from($table);
        if ($has_active_column) {
            $query->where('`active` = 1');
        }

        return (bool)Db::getInstance()->getValue($query);
    }

    public function getFieldsListOrder()
    {
        if (Tools::isSubmit('vieworder') && ($id_order = (int)Tools::getValue('id_order'))) {
            $order = new Order($id_order);
            if ($order->id_currency)
                $currency = new Currency($order->id_currency);
            else
                $currency = $this->context->currency;
            $this->context->smarty->assign(
                array(
                    'iso_code_currency' => $currency->iso_code,
                    'prestashop_is17' => $this->is17,
                )
            );
        }
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
        $statuses_array = array();
        foreach ($statuses as $status) {
            $statuses_array[$status['id_order_state']] = $status['name'];
        }
        $fields_list = array(
            'id_order' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
            ),
            'id_cart' => array(
                'title' => $this->l('Cart ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
                'edit' => true,
                'table_edit' => 'orders',
                'table_id' => 'id',
                'table_key' => 'id_order',
                'change' => 'reference',
                'type_edit' => 'text',
                'placeholder' => '',
                'validate' => 'isCleanHtml',
                'required' => false,
                'remove_onclick' => true,
            ),
            'new' => array(
                'title' => $this->l('New client'),
                'align' => 'text-center',
                'type' => 'bool',
                'tmpTableFilter' => false,
                'orderby' => false,
                'remove_onclick' => true,
                'search' => false,
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'havingFilter' => true,
                'edit' => true,
                'table_edit' => 'customer',
                'table_id' => 'id_customer',
                'table_key' => 'id_customer',
                'change' => 'firstname',
                'type_edit' => 'text',
                'placeholder' => 'firstname',
                'change2' => 'lastname',
                'placeholder2' => 'lastname',
                'validate' => 'isName',
                'required' => true,
                'remove_onclick' => true,

            ),
            'order_note' => array(
                'title' => $this->l('Order note'),
                'edit' => true,
                'table_edit' => 'orders',
                'table_id' => 'id',
                'table_key' => 'id_order',
                'change' => 'order_note',
                'type_edit' => 'textarea',
                'placeholder' => '',
                'validate' => 'isCleanHtml',
                'required' => false,
                'remove_onclick' => true,
            ),
        );
        if (Configuration::get('PS_B2B_ENABLE')) {
            $fields_list = array_merge($fields_list, array(
                'company' => array(
                    'title' => $this->l('Company'),
                    'filter_key' => 'c!company',
                ),
            ));
        }
        $fields_list = array_merge($fields_list, array(
            'total_paid_tax_incl' => array(
                'title' => $this->l('Total'),
                'align' => 'text-center',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true,
                'validate' => 'isPrice',
                'required' => false,
                'remove_onclick' => true,
            ),
            'payment' => array(
                'title' => $this->l('Payment'),
                'edit' => true,
                'table_edit' => 'orders',
                'table_id' => 'id',
                'table_key' => 'id_order',
                'change' => 'payment',
                'type_edit' => 'text',
                'placeholder' => '',
                'validate' => 'isGenericName',
                'required' => true,
                'remove_onclick' => true,
            ),
            'osname' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'color' => 'color',
                'list' => $statuses_array,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname',
                'edit' => true,
                'table_edit' => 'orders',
                'table_id' => 'id',
                'table_key' => 'id_order',
                'change' => 'current_state',
                'list_edits' => $statuses_array,
                'type_edit' => 'select',
                'placeholder' => '',
                'validate' => 'isUnsignedId',
                'required' => false,
                'remove_onclick' => true,
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'align' => 'text-left',
                'type' => 'datetime',
                'filter_key' => 'a!date_add',
                'edit' => true,
                'table_edit' => 'orders',
                'table_id' => 'id',
                'table_key' => 'id_order',
                'change' => 'date_add',
                'type_edit' => 'date',
                'placeholder' => '',
                'validate' => 'isDate',
                'retquired' => false,
                'remove_onclick' => true,
            ),
            'id_pdf' => array(
                'title' => $this->l('PDF'),
                'align' => 'text-center',
                'callback' => 'printPDFIcons',
                'orderby' => false,
                'search' => false,
                'remove_onclick' => true,
            ),
        ));

        if (self::isCurrentlyUsed('country', true)) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT DISTINCT c.id_country, cl.`name`
			FROM `' . _DB_PREFIX_ . 'orders` o
			' . Shop::addSqlAssociation('orders', 'o') . '
			INNER JOIN `' . _DB_PREFIX_ . 'address` a ON a.id_address = o.id_address_delivery
			INNER JOIN `' . _DB_PREFIX_ . 'country` c ON a.id_country = c.id_country
			INNER JOIN `' . _DB_PREFIX_ . 'country_lang` cl ON (c.`id_country` = cl.`id_country` AND cl.`id_lang` = ' . (int)$this->context->language->id . ')
			ORDER BY cl.name ASC');
            $country_array = array();
            foreach ($result as $row) {
                $country_array[$row['id_country']] = $row['name'];
            }

            $part1 = array_slice($fields_list, 0, 3);
            $part2 = array_slice($fields_list, 3);
            $countries = Country::getCountries($this->context->language->id, false);
            $list_country = array();
            if ($countries) {
                foreach ($countries as $country) {
                    $list_country[$country['id_country']] = $country['country'];
                }
            }
            $part1['cname'] = array(
                'title' => $this->l('Delivery'),
                'type' => 'select',
                'list' => $country_array,
                'filter_key' => 'country!id_country',
                'filter_type' => 'int',
                'order_key' => 'cname',
                'edit' => true,
                'table_edit' => 'address',
                'table_id' => 'id_address',
                'table_key' => 'id_address_delivery',
                'change' => 'id_country',
                'list_edits' => $list_country,
                'type_edit' => 'select',
                'placeholder' => '',
                'validate' => 'isUnsignedId',
                'required' => true,
                'remove_onclick' => true,
            );
            $fields_list = array_merge($part1, $part2);
        }
        $config_fileds = Configuration::get('ETS_ORDERMANAGE_ARRANGE_LIST') ? explode(',', Configuration::get('ETS_ORDERMANAGE_ARRANGE_LIST')) : $this->_list_order_default;
        if ($config_fileds) {
            $carriers = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'carrier` WHERE id_carrier >1 AND deleted !=1');
            $list_carriers = array();
            if ($carriers) {
                foreach ($carriers as $carrier)
                    $list_carriers[$carrier['id_carrier']] = $carrier['name'];
            }
            $part3 = array(
                'last_message' => array(
                    'title' => $this->l('Last message'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'callback' => 'printLastMessage',
                ),
                'email' => array(
                    'title' => $this->l('Email'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                ),
                'number_phone' => array(
                    'title' => $this->l('Phone number'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'align' => 'text-center',
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'phone',
                    'type_edit' => 'text',
                    'validate' => 'isPhoneNumber',
                    'required' => true,
                    'placeholder' => '',
                ),
                'address_invoice' => array(
                    'title' => $this->l('Invoice address'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_invoice',
                    'change' => 'address_invoice',
                    'type_edit' => 'text',
                    'validate' => 'isAddress',
                    'required' => true,
                    'placeholder' => '',
                ),
                'vat_number' => array(
                    'title' => $this->l('VAT number'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'vat_number',
                    'type_edit' => 'text',
                    'validate' => 'isGenericName',
                    'required' => false,
                    'placeholder' => '',
                ),
                'customer_group' => array(
                    'title' => $this->l('Client group'),
                    'orderby' => false,
                    'search' => false,
                    'remove_onclick' => true,
                ),
                'tracking_number' => array(
                    'title' => $this->l('Tracking number'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'order_carrier',
                    'table_id' => 'id_order_carrier',
                    'table_key' => 'id_order',
                    'change' => 'tracking_number',
                    'type_edit' => 'text',
                    'validate' => 'isTrackingNumber',
                    'required' => false,
                    'placeholder' => '',
                ),
                'postcode' => array(
                    'title' => $this->l('Postal code'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'postcode',
                    'type_edit' => 'text',
                    'validate' => 'isPostCode',
                    'required' => false,
                    'placeholder' => '',
                ),
                'address1' => array(
                    'title' => $this->l('Shipping address'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'address1',
                    'type_edit' => 'text',
                    'validate' => 'isAddress',
                    'required' => true,
                    'placeholder' => '',
                ),
                'city' => array(
                    'title' => $this->l('Ship to city'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'city',
                    'type_edit' => 'text',
                    'validate' => 'isCity',
                    'required' => true,
                    'placeholder' => '',
                ),
                'company' => array(
                    'title' => $this->l('Company'),
                    'havingFilter' => true,
                    'remove_onclick' => true,
                    'edit' => true,
                    'table_edit' => 'address',
                    'table_id' => 'id_address',
                    'table_key' => 'id_address_delivery',
                    'change' => 'company',
                    'type_edit' => 'text',
                    'validate' => 'isGenericName',
                    'required' => true,
                    'placeholder' => '',
                ),
                'caname' => array(
                    'title' => $this->l('Shipping method'),
                    'type' => 'select',
                    'list' => $list_carriers,
                    'filter_key' => 'carrier!id_carrier',
                    'filter_type' => 'int',
                    'order_key' => 'caname',
                    'edit' => true,
                    'table_edit' => 'orders',
                    'table_id' => 'id_order',
                    'table_key' => 'id_order',
                    'change' => 'id_carrier',
                    'list_edits' => $list_carriers,
                    'type_edit' => 'select',
                    'placeholder' => '',
                    'validate' => 'isUnsignedId',
                    'required' => true,
                    'remove_onclick' => true,
                ),
                'shipping_cost_tax_incl' => array(
                    'title' => $this->l('Total shipping cost'),
                    'align' => 'text-center',
                    'type' => 'price',
                    'currency' => true,
                    'callback' => 'setOrderCurrency',
                    'badge_success' => true,
                    'validate' => 'isPrice',
                    'required' => false,
                    'remove_onclick' => true,
                ),
                'images' => array(
                    'title' => $this->l('Products'),
                    'filter_key' => 'od!product_name',
                    'align' => 'text-left',
                    'callback' => 'printOrderProducts',
                    'orderby' => false,
                    'search' => true,
                    'remove_onclick' => true,
                    'havingFilter' => true,
                ),
            );
            if (Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee')) {
                $part3['fee'] = array(
                    'title' => Configuration::get('ETS_PMF_TEXT_PAYMENT_FEE', Context::getContext()->language->id) ?: $this->l('Payment fee'),
                    'align' => 'text-center',
                    'type' => 'price',
                    'currency' => true,
                    'callback' => 'setOrderCurrency',
                    'validate' => 'isPrice',
                    'required' => false,
                    'edit' => true,
                    'table_edit' => 'ets_paymentmethod_order',
                    'table_id' => 'id_order',
                    'table_key' => 'id_order',
                    'change' => 'fee',
                    'type_edit' => 'text',
                    'placeholder' => '',
                    'remove_onclick' => true,
                );
            }
            $fields_list = array_merge($fields_list, $part3);
            if ($config_fileds) {
                $fields = array();
                foreach ($config_fileds as $config_filed) {
                    if (isset($fields_list[$config_filed]))
                        $fields[$config_filed] = $fields_list[$config_filed];
                }
                if (!isset($fields['id_order'])) {
                    $fields = array_merge(array('id_order' => $fields_list['id_order']), $fields);
                }
                if ($fields)
                    return $fields;
            }
        }
        return $fields_list;

    }

    public function getFormArrangeOrder()
    {
        if (Configuration::get('ETS_ORDERMANAGE_ARRANGE_LIST')) {
            $list_fields = explode(',', Configuration::get('ETS_ORDERMANAGE_ARRANGE_LIST'));
        } else
            $list_fields = $this->_list_order_default;

        $this->context->smarty->assign(
            array(
                'list_fields' => $list_fields,
                'title_fields' => $this->title_fields,
            )
        );
        $display = $this->display(__FILE__, 'form_arrange.tpl');
        if (Tools::isSubmit('ajax')) {
            die(
            json_encode(
                array(
                    'block_html' => $display,
                )
            )
            );
        }
        return $display;
    }

    public function getFormDuplicate($id_order)
    {
        $carriers = self::getListCarriersByIDOrder($id_order);
        $this->context->smarty->assign(
            array(
                'fields' => $this->getOrderFieldsValues($id_order),
                'carriers' => $carriers,
                'link' => $this->context->link,
                'edit_customer' => Tools::isSubmit('edit_customer'),
                'link_new_adress' => $this->getLinkNewAddress(),
                'ets_ordermanager' => $this,
            )
        );
        if (Tools::isSubmit('ajax')) {
            die(json_encode(
                array(
                    'block_html' => $this->display(__FILE__, 'form_duplicate.tpl'),
                )
            ));
        }
        return $this->display(__FILE__, 'form_duplicate.tpl');
    }

    public function displaySuccessMessage($msg, $title = false, $link = false)
    {
        $this->smarty->assign(array(
            'msg' => $msg,
            'title' => $title,
            'link' => $link
        ));
        if ($msg)
            return $this->display(__FILE__, 'success_message.tpl');
    }

    public function getOrderFieldsValues($id_order)
    {
        $order = new Order($id_order);
        $fields = array();
        $fields['id_order'] = $order->id;
        $fields['reference'] = $order->reference;
        $fields['id_customer_order'] = $order->id_customer;
        $fields['id_carrier'] = $order->id_carrier;
        $fields['payment'] = $order->payment;
        $cart = new Cart($order->id_cart);
        if (!Tools::isSubmit('edit_customer')) {
            if ($cart->id)
                isset($cart->id);
            else {
                $cart->id_customer = $order->id_customer;
                $cart->id_address_delivery = $order->id_address_delivery;
                $cart->id_address_invoice = $order->id_address_invoice;
                $cart->id_currency = $order->id_currency;
            }
            $cart->add();
            $cart_products = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cart_product` WHERE id_cart=' . (int)$order->id_cart);
            if ($cart_products) {
                foreach ($cart_products as $products) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cart_product` SET id_cart="' . (int)$cart->id . '"';
                    foreach ($products as $field => $value) {
                        if ($field != 'id_cart')
                            $sql .= ',' . pSQL($field) . '="' . pSQL($value) . '"';
                    }
                    Db::getInstance()->execute($sql);
                }
            }
        }
        $fields['id_cart'] = $cart->id;
        if ($order->id_customer) {
            $customer = new Customer($order->id_customer);
            $fields['customer'] = $customer->firstname . ' ' . $customer->lastname;
        }
        return $fields;
    }

    public function duplicateOrder()
    {
        if ($id_order = (int)Tools::getValue('id_order')) {
            $order = new Order($id_order);
            $errors = array();
            if (Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee')) {
                $payment_fee = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'ets_paymentmethod_order` WHERE id_order=' . (int)$id_order);
            }
            unset($order->id);
            $id_cart = (int)Tools::getValue('id_cart');
            $cart = new Cart($id_cart);
            $order->id_cart = $cart->id;
            $order->id_customer = $cart->id_customer;
            if ($cart->id_address_delivery)
                $order->id_address_delivery = $cart->id_address_delivery;
            else
                $errors[] = $this->l('Delivery address is required');
            if ($cart->id_address_invoice)
                $order->id_address_invoice = $cart->id_address_invoice;
            else
                $errors[] = $this->l('Invoice address is required');
            if (($reference = Tools::getValue('reference')) && !Validate::isCleanHtml($reference))
                $errors[] = $this->l('Reference is not valid');
            elseif (Tools::strlen($reference) > 9)
                $errors[] = $this->l('Reference max length: 9 characters');
            else
                $order->reference = $reference;
            if (!($payment = Tools::getValue('payment')))
                $errors[] = $this->l('Payment method is required');
            elseif (!Validate::isGenericName($payment))
                $errors[] = $this->l('Payment method is not valid');
            $order->payment = $payment;
            $order->id_carrier = (int)Tools::getValue('id_carrier');
            if (!$errors) {
                if ($order->add()) {
                    $computingPrecision = Context::getContext()->getComputingPrecision();
                    $order_cart_rules = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_cart_rule` WHERE id_order=' . (int)$id_order);
                    if ($order_cart_rules) {
                        foreach ($order_cart_rules as $order_cart_rule) {
                            $order_cart_rule_class = new OrderCartRule($order_cart_rule['id_order_cart_rule']);
                            unset($order_cart_rule_class->id);
                            $order_cart_rule_class->id_order = $order->id;
                            $order_cart_rule_class->add();
                        }
                    }
                    $order_details = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_detail` WHERE id_order=' . (int)$id_order);
                    if ($order_details) {
                        foreach ($order_details as $order_detail) {
                            $order_detail_class = new OrderDetail($order_detail['id_order_detail']);
                            unset($order_detail_class->id);
                            $order_detail_class->id_order = $order->id;
                            $order_detail_class->add();
                        }
                    }
                    $order->total_shipping_tax_excl = Tools::ps_round((float)$cart->getPackageShippingCost($order->id_carrier, false), $computingPrecision);
                    $order->total_shipping_tax_incl = Tools::ps_round((float)$cart->getPackageShippingCost($order->id_carrier), $computingPrecision);
                    $order->total_paid_tax_incl += $order->total_shipping_tax_incl;
                    $order->total_paid_tax_excl += $order->total_shipping_tax_excl;
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->save();
                    $order_carriers = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_carrier` WHERE id_order=' . (int)$id_order);
                    if ($order_carriers) {
                        foreach ($order_carriers as $order_carrier) {
                            $order_carrier_class = new OrderCarrier((int)$order_carrier['id_order_carrier']);
                            unset($order_carrier_class->id);
                            $order_carrier_class->id_order = $order->id;
                            $order_carrier_class->id_carrier = $order->id_carrier;
                            $order_carrier_class->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                            $order_carrier_class->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                            $order_carrier_class->add();
                        }
                    }
                    $order_histories = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_history` WHERE id_order=' . (int)$id_order);
                    if ($order_histories) {
                        foreach ($order_histories as $order_history) {
                            $order_history_class = new OrderHistory($order_history['id_order_history']);
                            unset($order_history_class->id);
                            $order_history_class->id_order = $order->id;
                            $order_history_class->add();
                        }
                    }
                    $order_returns = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_return` WHERE id_order=' . (int)$id_order);
                    if ($order_returns) {
                        foreach ($order_returns as $order_return) {
                            $order_return_class = new OrderReturn($order_return['id_order_return']);
                            unset($order_return_class->id);
                            $order_return_class->id_order = $order->id;
                            $order_return_class->add();
                        }
                    }
                    if ($order->invoice_number) {
                        $order->setInvoice(true);
                        $order->setDelivery();
                    }
                    if (isset($payment_fee) && $payment_fee) {
                        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'ets_paymentmethod_order` (id_paymentmethod,id_order,method_name,fee) values("' . (int)$payment_fee['id_paymentmethod'] . '","' . (int)$order->id . '","' . pSQL($payment_fee['method_name']) . '","' . (float)$payment_fee['fee'] . '")');
                    }
                    die(
                    json_encode(
                        array(
                            'success' => true,
                            'link_redirect' => $this->getLinkOrderAdmin($order->id) . '&vieworder&conf=3',
                        )
                    )
                    );
                }
            } else {
                die(
                json_encode(
                    array(
                        'errors' => $this->displayError($errors)
                    )
                )
                );
            }
        }
    }

    public function getLinkAdminController($entiny, $params = array())
    {
        $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer', 'getInstance'));
        if (null !== $sfContainer) {
            $sfRouter = $sfContainer->get('router');
            return $sfRouter->generate(
                $entiny,
                $params
            );
        }

    }

    public function getLinkNewAddress()
    {
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $link_address = $this->getLinkAdminController('admin_addresses_create');
        } else
            $link_address = $this->context->link->getAdminLink('AdminAddresses') . '&addaddress=1';
        return $link_address;
    }

    public function getLinkAddressAdmin($id_address)
    {
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $link_order = $this->getLinkAdminController('admin_addresses_edit', array('addressId' => $id_address));
        } else
            $link_order = $this->context->link->getAdminLink('AdminAddresses') . '&id_address=' . (int)$id_address . '&updateaddress';
        return $link_order;
    }

    public function getLinkOrderAdmin($id_order)
    {
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $link_order = $this->getLinkAdminController('admin_orders_view', array('orderId' => $id_order));
        } else
            $link_order = $this->context->link->getAdminLink('AdminOrders') . '&id_order=' . (int)$id_order . '&vieworder';
        return $link_order;
    }

    public function getLinkCustomerAdmin($id_customer)
    {
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $link_customer = $this->getLinkAdminController('admin_customers_view', array('customerId' => $id_customer));
        } else
            $link_customer = $this->context->link->getAdminLink('AdminCustomers') . '&id_customer=' . (int)$id_customer . '&viewcustomer';
        return $link_customer;
    }

    public function editCustomerOrder()
    {
        if ($id_order = (int)Tools::getValue('id_order')) {
            $errors = array();
            $order = new Order($id_order);
            $id_cart = (int)Tools::getValue('id_cart');
            $cart = new Cart($id_cart);
            if ($cart->id_address_delivery)
                $order->id_address_delivery = $cart->id_address_delivery;
            else
                $errors[] = $this->l('Delivery address is required');
            if ($cart->id_address_invoice)
                $order->id_address_invoice = $cart->id_address_invoice;
            else
                $errors[] = $this->l('Invoice address is required');
            $order->id_cart = $cart->id;
            $order->id_customer = $cart->id_customer;
            if (!$errors) {
                if ($order->update()) {
                    die(
                    json_encode(
                        array(
                            'success' => true,
                            'link_redirect' => $this->getLinkOrderAdmin($order->id),
                        )
                    )
                    );
                }
            } else {
                die(
                json_encode(
                    array(
                        'errors' => $this->displayError($errors)
                    )
                )
                );
            }

        }
    }

    public function getQuickViewOrder($id_order)
    {
        $order = new Order($id_order);
        if (!Validate::isLoadedObject($order)) {
            $this->errors[] = $this->l('The order cannot be found within your database.');
        }
        $customer = new Customer($order->id_customer);
        $carrier = new Carrier($order->id_carrier);
        $products = $this->getProducts($order);
        $currency = new Currency((int)$order->id_currency);
        // Carrier module call
        $carrier_module_call = null;
        if ($carrier->is_module) {
            $module = Module::getInstanceByName($carrier->external_module_name);
            if (method_exists($module, 'displayInfoByCart')) {
                $carrier_module_call = call_user_func(array($module, 'displayInfoByCart'), $order->id_cart);
            }
        }

        // Retrieve addresses information
        $addressInvoice = new Address($order->id_address_invoice, $this->context->language->id);
        if (Validate::isLoadedObject($addressInvoice) && $addressInvoice->id_state) {
            $invoiceState = new State((int)$addressInvoice->id_state);
        }

        if ($order->id_address_invoice == $order->id_address_delivery) {
            $addressDelivery = $addressInvoice;
            if (isset($invoiceState)) {
                $deliveryState = $invoiceState;
            }
        } else {
            $addressDelivery = new Address($order->id_address_delivery, $this->context->language->id);
            if (Validate::isLoadedObject($addressDelivery) && $addressDelivery->id_state) {
                $deliveryState = new State((int)($addressDelivery->id_state));
            }
        }

        $this->toolbar_title = '';

        // gets warehouses to ship products, if and only if advanced stock management is activated
        $warehouse_list = null;

        $order_details = $order->getOrderDetailList();
        foreach ($order_details as $order_detail) {
            $product = new Product($order_detail['product_id']);

            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
                && $product->advanced_stock_management) {
                $warehouses = Warehouse::getWarehousesByProductId($order_detail['product_id'], $order_detail['product_attribute_id']);
                foreach ($warehouses as $warehouse) {
                    if (!isset($warehouse_list[$warehouse['id_warehouse']])) {
                        $warehouse_list[$warehouse['id_warehouse']] = $warehouse;
                    }
                }
            }
        }

        $payment_methods = array();
        foreach (PaymentModule::getInstalledPaymentModules() as $payment) {
            $module = Module::getInstanceByName($payment['name']);
            if (Validate::isLoadedObject($module) && $module->active) {
                $payment_methods[] = $module->displayName;
            }
        }

        // display warning if there are products out of stock
        $display_out_of_stock_warning = false;
        $current_order_state = $order->getCurrentOrderState();
        if (Configuration::get('PS_STOCK_MANAGEMENT') && (!Validate::isLoadedObject($current_order_state) || ($current_order_state->delivery != 1 && $current_order_state->shipped != 1))) {
            $display_out_of_stock_warning = true;
        }

        // products current stock informations (from stock_available)
        $stockLocationIsAvailable = false;
        foreach ($products as &$product) {
            // Get total customized quantity for current product
            $customized_product_quantity = 0;

            if (is_array($product['customizedDatas'])) {
                foreach ($product['customizedDatas'] as $customizationPerAddress) {
                    foreach ($customizationPerAddress as $customization) {
                        $customized_product_quantity += (int)$customization['quantity'];
                    }
                }
            }
            $product['customized_product_quantity'] = $customized_product_quantity;
            $product['current_stock'] = StockAvailable::getQuantityAvailableByProduct($product['product_id'], $product['product_attribute_id'], $product['id_shop']);
            $resume = OrderSlip::getProductSlipResume($product['id_order_detail']);
            $product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];
            $product['amount_refundable'] = $product['total_price_tax_excl'] - $resume['amount_tax_excl'];
            $product['amount_refundable_tax_incl'] = $product['total_price_tax_incl'] - $resume['amount_tax_incl'];
            $product['amount_refund'] = $order->getTaxCalculationMethod() ? Tools::displayPrice($resume['amount_tax_excl'], $currency) : Tools::displayPrice($resume['amount_tax_incl'], $currency);
            $product['refund_history'] = OrderSlip::getProductSlipDetail($product['id_order_detail']);
            $product['return_history'] = OrderReturn::getProductReturnDetail($product['id_order_detail']);

            // if the current stock requires a warning
            if ($product['current_stock'] <= 0 && $display_out_of_stock_warning) {
                $this->displayWarning($this->l('This product is out of stock: ') . ' ' . $product['product_name']);
            }
            if ($product['id_warehouse'] != 0) {
                $warehouse = new Warehouse((int)$product['id_warehouse']);
                $product['warehouse_name'] = $warehouse->name;
                $warehouse_location = WarehouseProductLocation::getProductLocation($product['product_id'], $product['product_attribute_id'], $product['id_warehouse']);
                if (!empty($warehouse_location)) {
                    $product['warehouse_location'] = $warehouse_location;
                } else {
                    $product['warehouse_location'] = false;
                }
            } else {
                $product['warehouse_name'] = '--';
                $product['warehouse_location'] = false;
            }

            if (!empty($product['location'])) {
                $stockLocationIsAvailable = true;
            }
        }

        // Package management for order
        foreach ($products as &$product) {
            $pack_items = $product['cache_is_pack'] ? Pack::getItemTable($product['id_product'], $this->context->language->id, true) : array();
            foreach ($pack_items as &$pack_item) {
                $pack_item['current_stock'] = StockAvailable::getQuantityAvailableByProduct($pack_item['id_product'], $pack_item['id_product_attribute'], $pack_item['id_shop']);
                // if the current stock requires a warning
                if ($product['current_stock'] <= 0 && $display_out_of_stock_warning) {
                    $this->displayWarning($this->l('This product which included in package (' . $product['product_name'] . ') is out of stock: ') . ' ' . $pack_item['product_name']);
                }
                $this->setProductImageInformations($pack_item);
                if ($pack_item['image'] != null) {
                    $name = 'product_mini_' . (int)$pack_item['id_product'] . (isset($pack_item['id_product_attribute']) ? '_' . (int)$pack_item['id_product_attribute'] : '') . '.jpg';
                    // generate image cache, only for back office
                    $pack_item['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_ . 'p/' . $pack_item['image']->getExistingImgPath() . '.jpg', $name, 45, 'jpg');
                    if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                        $pack_item['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                    } else {
                        $pack_item['image_size'] = false;
                    }
                }
            }
            $product['pack_items'] = $pack_items;
        }

        $gender = new Gender((int)$customer->id_gender, $this->context->language->id);

        $history = $order->getHistory($this->context->language->id);

        foreach ($history as &$order_state) {
            $order_state['text-color'] = Tools::getBrightness($order_state['color']) < 128 ? 'white' : 'black';
        }

        $shipping_refundable_tax_excl = $order->total_shipping_tax_excl;
        $shipping_refundable_tax_incl = $order->total_shipping_tax_incl;
        $slips = OrderSlip::getOrdersSlip($customer->id, $order->id);
        foreach ($slips as $slip) {
            $shipping_refundable_tax_excl -= $slip['total_shipping_tax_excl'];
            $shipping_refundable_tax_incl -= $slip['total_shipping_tax_incl'];
        }
        $shipping_refundable_tax_excl = max(0, $shipping_refundable_tax_excl);
        $shipping_refundable_tax_incl = max(0, $shipping_refundable_tax_incl);
        if (Module::isInstalled('ets_payment_with_fee') && Module::isEnabled('ets_payment_with_fee')) {
            if ($method_order = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'ets_paymentmethod_order` WHERE id_order =' . (int)$order->id)) {
                $price = $method_order['fee'];
                if ($price) {
                    $priceFormatter = new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter();
                    $this->context->smarty->assign(
                        array(
                            'payment_fee' => $priceFormatter->format($price, Currency::getCurrencyInstance((int)$order->id_currency)),
                        )
                    );
                }
            }
        }

        // Smarty assign
        $this->context->smarty->assign(array(
            'order' => $order,
            'link_view_order' => $this->getLinkOrderAdmin($order->id),
            'link_view_customer' => $this->getLinkCustomerAdmin($order->id_customer),
            'tpl_dir' => dirname(__FILE__),
            'order_state' => new OrderState($order->current_state, $this->context->language->id),
            'link' => $this->context->link,
            'cart' => new Cart($order->id_cart),
            'customer' => $customer,
            'gender' => $gender,
            'customer_addresses' => $customer->getAddresses($this->context->language->id),
            'addresses' => array(
                'delivery' => $addressDelivery,
                'deliveryState' => isset($deliveryState) ? $deliveryState : null,
                'invoice' => $addressInvoice,
                'invoiceState' => isset($invoiceState) ? $invoiceState : null,
            ),
            'current_index' => $this->context->link->getAdminLink('AdminOrders'),
            'customerStats' => $customer->getStats(),
            'products' => $products,
            'can_edit' => false,
            'currentIndex' => $this->context->link->getAdminLink('AdminOrders'),
            'stock_management' => true,
            'discounts' => $order->getCartRules(),
            'orders_total_paid_tax_incl' => $order->getOrdersTotalPaid(), // Get the sum of total_paid_tax_incl of the order with similar reference
            'total_paid' => $order->getTotalPaid(),
            'returns' => OrderReturn::getOrdersReturn($order->id_customer, $order->id),
            'shipping_refundable_tax_excl' => $shipping_refundable_tax_excl,
            'shipping_refundable_tax_incl' => $shipping_refundable_tax_incl,
            'customer_thread_message' => CustomerThread::getCustomerMessages($order->id_customer, null, $order->id),
            'orderMessages' => OrderMessage::getOrderMessages($order->id_lang),
            'messages' => $this->is17 ? CustomerThread::getCustomerMessagesOrder($order->id_customer, $order->id) : Message::getMessagesByOrderId($order->id, true),
            'carrier' => new Carrier($order->id_carrier),
            'history' => $history,
            'states' => OrderState::getOrderStates($this->context->language->id),
            'warehouse_list' => $warehouse_list,
            'sources' => ConnectionsSource::getOrderSources($order->id),
            'currentState' => $order->getCurrentOrderState(),
            'currency' => new Currency($order->id_currency),
            'currencies' => Currency::getCurrenciesByIdShop($order->id_shop),
            'previousOrder' => $order->getPreviousOrderId(),
            'nextOrder' => $order->getNextOrderId(),
            'carrierModuleCall' => $carrier_module_call,
            'iso_code_lang' => $this->context->language->iso_code,
            'id_lang' => $this->context->language->id,
            'current_id_lang' => $this->context->language->id,
            'invoices_collection' => $order->getInvoicesCollection(),
            'not_paid_invoices_collection' => $order->getNotPaidInvoicesCollection(),
            'payment_methods' => $payment_methods,
            'invoice_management_active' => Configuration::get('PS_INVOICE', null, null, $order->id_shop),
            'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
            'carrier_list' => $this->getCarrierList($order),
            'recalculate_shipping_cost' => (int)Configuration::get('PS_ORDER_RECALCULATE_SHIPPING') || !$this->is17,
            'stock_location_is_available' => $stockLocationIsAvailable,
            'ETS_PMF_TEXT_PAYMENT_FEE' => Configuration::get('ETS_PMF_TEXT_PAYMENT_FEE', $this->context->language->id) ?: $this->l('Payment fee'),
            'HOOK_CONTENT_ORDER' => Hook::exec('displayAdminOrderContentOrder', array(
                    'order' => $order,
                    'products' => $products,
                    'customer' => $customer,)
            ),
            'HOOK_CONTENT_SHIP' => Hook::exec('displayAdminOrderContentShip', array(
                    'order' => $order,
                    'products' => $products,
                    'customer' => $customer,)
            ),
            'HOOK_TAB_ORDER' => Hook::exec('displayAdminOrderTabOrder', array(
                    'order' => $order,
                    'products' => $products,
                    'customer' => $customer,)
            ),
            'HOOK_TAB_SHIP' => Hook::exec('displayAdminOrderTabShip', array(
                    'order' => $order,
                    'products' => $products,
                    'customer' => $customer,)
            ),
        ));
        $view = version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? $this->display(__FILE__, 'quick_view_order_1770.tpl') : $this->display(__FILE__, 'quick_view_order.tpl');
        die(json_encode(
            array(
                'order_quickview' => $view,
            )
        ));
    }

    protected function getProducts($order)
    {
        $products = $order->getProducts();
        foreach ($products as &$product) {
            if ($product['image'] != null) {
                $name = 'product_mini_' . (int)$product['product_id'] . (isset($product['product_attribute_id']) ? '_' . (int)$product['product_attribute_id'] : '') . '.jpg';
                // generate image cache, only for back office
                $product['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_ . 'p/' . $product['image']->getExistingImgPath() . '.jpg', $name, 45, 'jpg');
                if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                } else {
                    $product['image_size'] = false;
                }
            }
        }
        ksort($products);
        return $products;
    }

    protected function getCarrierList($order)
    {
        $cart = new Cart($order->id_cart);
        $address = new Address((int)$cart->id_address_delivery);
        return Carrier::getCarriersForOrder(Address::getZoneById((int)$address->id), null, $cart);
    }

    public function _postOrder()
    {
        $this->context->controller->_conf['1111'] = $this->l('Order is moved to Trash successfully, you can restore the order from Trash or delete it forever.') . Module::getInstanceByName('ets_ordermanager')->displayText($this->l('View Trash'), 'a', array('href' => $this->context->link->getAdminLink('AdminOrders') . '&viewtrash=1', 'title' => $this->l('View Trash')));

        $this->context->controller->_conf['2222'] = $this->l('Order restored successfully.') . Module::getInstanceByName('ets_ordermanager')->displayText($this->l('View Order list'), 'a', array('href' => $this->context->link->getAdminLink('AdminOrders'), 'title' => $this->l('View Order list')));
        $this->context->controller->_conf['3333'] = $this->l('Emptied trash successfully');
        $this->context->controller->_conf['333'] = $this->l('Deleted order status successfully');
        if (version_compare(_PS_VERSION_, '1.7', '<') && Tools::isSubmit('vieworder'))
            $this->context->controller->override_folder = '../../../../../override/controllers/admin/templates/orders/';
        if (Tools::isSubmit('viewtrash')) {
            if ($this->checkAccess('delete'))
                $this->context->controller->addRowAction('delete');
            if ($this->checkAccess('update'))
                $this->context->controller->addRowAction('restore');
            $this->context->controller->page_header_toolbar_title = $this->l('Trash');
        } else {
            if ($this->checkAccess('update'))
                $this->context->controller->addRowAction('edit');
            if ($this->checkAccess('delete'))
                $this->context->controller->addRowAction('delete');
            if ($this->checkAccess('create'))
                $this->context->controller->addRowAction('duplicate');
        }
        $this->context->controller->addRowAction('printdeliverylabel');
        $this->context->controller->addRowAction('loginascustomer');
        if (Tools::isSubmit('btnSubmitDuplicateOrder')) {
            if ($this->checkAccess('create'))
                $this->duplicateOrder();
            else
                $this->_errors[] = $this->l('You do not have permission to duplicate this.');
        }
        if (Tools::isSubmit('btnSubmitEditCustomerOrder')) {
            if ($this->checkAccess('update'))
                $this->editCustomerOrder();
            else
                $this->_errors[] = $this->l('You do not have permission to edit this.');
            if ($this->_errors) {
                die(
                json_encode(
                    array(
                        'errors' => $this->displayError($this->_errors),
                    )
                )
                );
            }
        }
        if (Tools::isSubmit('changeorderinline')) {
            if ($this->checkAccess('update'))
                $this->changeOrderInline();
            else
                $this->_errors[] = $this->l('You do not have permission to edit this.');
        }
        if (Tools::isSubmit('restoreorder') && ($id_order = (int)Tools::getValue('id_order'))) {
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'orders` set deleted=0 WHERE id_order=' . (int)$id_order);
            $this->upQuantityProduct($id_order);
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&viewtrash=1&conf=2222');
        }
        if (Tools::isSubmit('getFormDuplicate') && ($id_order = (int)Tools::getValue('id_order'))) {
            $this->getFormDuplicate($id_order);
        }
        if (Tools::isSubmit('quickvieworder') && ($id_order = (int)Tools::getValue('id_order'))) {
            $this->getQuickViewOrder($id_order);
        }
        if (Tools::isSubmit('deleteordertrash') && ($id_order = (int)Tools::getValue('id_order'))) {
            if ($this->checkAccess('delete')) {
                Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'orders` set deleted=1 WHERE id_order=' . (int)$id_order);
                $this->refundQuantityProduct($id_order);
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&conf=1111');
            } else
                $this->_errors[] = $this->l('You do not have permission to delete this.');
        }
        if ((Tools::isSubmit('deleteOrder') || Tools::isSubmit('deleteorder')) && ($id_order = (int)Tools::getValue('id_order')) && ($order = new Order($id_order)) && Validate::isLoadedObject($order)) {
            if ($this->checkAccess('delete')) {
                $order->delete();
                if (Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'permanently')
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&conf=1');
                else
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&conf=1&viewtrash=1');
            } else
                $this->_errors[] = $this->l('You do not have permission to delete this.');
        }
        if (Tools::isSubmit('restore_all_order') || isset($this->bulk_orders['restore_all_order'])) {
            if (($orders = Tools::getValue('orderBox', isset($this->bulk_orders['order_orders_bulk']) ? $this->bulk_orders['order_orders_bulk'] : false)) && Ets_ordermanager::validateArray($orders, 'isInt')) {
                foreach ($orders as $order) {
                    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'orders` SET deleted=0 WHERE id_order=' . (int)$order);
                    $this->upQuantityProduct($order);
                }
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&viewtrash=1&conf=2222');
            } else
                $this->_errors[] = $this->l('No order selected');
        }
        if (Tools::isSubmit('delete_all_order') || isset($this->bulk_orders['delete_all_order'])) {
            if ($this->checkAccess('delete')) {
                if (($orders = Tools::getValue('orderBox', isset($this->bulk_orders['order_orders_bulk']) ? $this->bulk_orders['order_orders_bulk'] : false)) && Ets_ordermanager::validateArray($orders, 'isInt')) {
                    $conf = 1;
                    foreach ($orders as $order) {
                        if (!Tools::isSubmit('viewtrash') && Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'move_to_trash') {
                            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'orders` SET deleted=1 WHERE id_order=' . (int)$order);
                            $this->refundQuantityProduct($order);
                            $conf = 1111;
                        } else {
                            $order_class = new Order($order);
                            $order_class->delete();
                            $conf = 1;
                        }
                    }
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&conf=' . $conf . (Tools::isSubmit('viewtrash') && Configuration::get('ETS_ODE_BEHAVIOR_DELETE_ORDER') == 'move_to_trash' ? '&viewtrash=1' : ''));
                }
                $this->_errors[] = $this->l('No order selected');
            } else
                $this->_errors[] = $this->l('You do not have permission to delete this order');
        }
        if (Tools::isSubmit('empty_trash_order')) {
            if ($this->checkAccess('delete')) {
                $orders = Db::getInstance()->executeS('SELECT id_order FROM `' . _DB_PREFIX_ . 'orders` WHERE deleted=1');
                if ($orders) {
                    foreach ($orders as $order) {
                        $order_class = new Order($order['id_order']);
                        $order_class->delete();

                    }
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&conf=3333&viewtrash=1');
                }
            } else
                $this->_errors[] = $this->l('You do not have permission to delete this');
        }
        if (Tools::isSubmit('arrangeorder')) {
            $this->getFormArrangeOrder();
        }
        if (Tools::isSubmit('btnSubmitArrangeListOrder')) {
            if (($listFieldOrders = Tools::getValue('listFieldOrders')) && Ets_ordermanager::validateArray($listFieldOrders)) {
                Configuration::updateValue('ETS_ORDERMANAGE_ARRANGE_LIST', implode(',', $listFieldOrders));
            } else
                Configuration::updateValue('ETS_ORDERMANAGE_ARRANGE_LIST', '');
            die(json_encode(
                array(
                    'success' => true,
                )
            ));
        }
        if (Tools::isSubmit('btnSubmitRessetToDefaultList')) {
            Configuration::updateValue('ETS_ORDERMANAGE_ARRANGE_LIST', '');
            die(json_encode(
                array(
                    'success' => 3,
                )
            ));
        }
        if (Tools::isSubmit('print_invoice_all_order') || isset($this->bulk_orders['print_invoice_all_order'])) {
            if ($list_id_orders = Tools::getValue('orderBox', isset($this->bulk_orders['order_orders_bulk']) ? $this->bulk_orders['order_orders_bulk'] : false)) {
                foreach ($list_id_orders as $key => $id_order) {
                    $order = new Order((int)$id_order);
                    if (!Validate::isLoadedObject($order))
                        unset($list_id_orders[$key]);
                    else
                        if (!Configuration::get('PS_INVOICE') || !$order->invoice_number) {
                            unset($list_id_orders[$key]);
                        }
                }
                if (!$list_id_orders) {
                    $this->_errors[] = $this->l('No order invoice selected.');
                }
                if (!$this->_errors)
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminPdf') . '&action=GenerateInvoicePdfAll&list_id_order=' . implode(',', $list_id_orders));

            } else
                $this->_errors[] = $this->l('No order selected');
        }
        if (Tools::isSubmit('print_slips_all_order') || isset($this->bulk_orders['print_slips_all_order'])) {
            if ($list_id_orders = Tools::getValue('orderBox', isset($this->bulk_orders['order_orders_bulk']) ? $this->bulk_orders['order_orders_bulk'] : false)) {
                foreach ($list_id_orders as $key => $id_order) {
                    $order = new Order((int)$id_order);
                    if (!Validate::isLoadedObject($order))
                        unset($list_id_orders[$key]);
                    else
                        if (!$order->delivery_number) {
                            unset($list_id_orders[$key]);
                        }
                }
                if (!$list_id_orders) {
                    $this->_errors[] = $this->l('No delivery slip selected.');
                }
                if (!$this->_errors)
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminPdf') . '&action=GenerateDeliverySlipPDFAll&list_id_order=' . implode(',', $list_id_orders));
            } else
                $this->_errors[] = $this->l('No order selected');
        }
        if (Tools::isSubmit('print_delivery_label_all_order') || isset($this->bulk_orders['print_delivery_label_all_order'])) {
            if ($list_id_orders = Tools::getValue('orderBox', isset($this->bulk_orders['order_orders_bulk']) ? $this->bulk_orders['order_orders_bulk'] : false)) {
                foreach ($list_id_orders as $key => $id_order) {
                    $order = new Order((int)$id_order);
                    if (!Validate::isLoadedObject($order))
                        unset($list_id_orders[$key]);
                }
                if (!$list_id_orders) {
                    $this->_errors[] = $this->l('No order selected.');
                }
                if (!$this->_errors) {
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminPdf') . '&action=GenerateDeliveryLabelByIdOrderAll&list_id_order=' . implode(',', $list_id_orders));
                }
            } else
                $this->_errors[] = $this->l('No order selected');
        }
        if (Tools::isSubmit('deletehistory') && ($id_order = (int)Tools::getValue('id_order')) && ($id_order_history = (int)Tools::getValue('id_order_history'))) {

            $order = new Order($id_order);
            if (Count($order->getHistory($this->context->language->id, false, true)) <= 1)
                $this->_errors[] = $this->l('Delete failed');
            else {
                $orderHistory = new OrderHistory($id_order_history);
                $orderHistory->delete();
                if ($orderHistory->id_order_state == $order->current_state) {
                    $id_order_state = Db::getInstance()->getValue('SELECT id_order_state FROM `' . _DB_PREFIX_ . 'order_history` WHERE id_order=' . (int)$id_order . ' ORDER BY id_order_history DESC');
                    $order->current_state = $id_order_state;
                    $order->update();
                }
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&vieworder&conf=333&id_order=' . (int)$id_order);

            }
        }
        if (($id_order = (int)Tools::getValue('id_order'))) {
            $this->context->smarty->assign(
                array(
                    'ets_carrier_list' => self::getListCarriersByIDOrder($id_order),
                )
            );
        }
        if (Tools::isSubmit('printdeliverylabelorder') && ($id_order = (int)Tools::getValue('id_order'))) {
            $order = new Order($id_order);
            if (!Validate::isLoadedObject($order))
                $this->_errors[] = $this->l('Order is not valid');
            else {
                $pdf = new PDF($order, 'LabelDeliveryPdf', Context::getContext()->smarty);
                $size_format = Configuration::get('ETS_ODE_DELIVERY_LABEL_SIZE_FORMAT') ?: 'A4';
                if (!in_array($size_format, array('A3', 'A4', 'A5', 'A6')))
                    $size_format = 'A4';
                if ($this->is17)
                    $pf = TCPDF_STATIC::getPageSizeFromFormat($size_format);
                else
                    $pf = $pdf->pdf_renderer->getPageSizeFromFormat($size_format);
                $reflectionClass = new ReflectionClass('PDFGeneratorCore');
                $reflectionFwPt = $reflectionClass->getProperty('fwPt');
                $reflectionFwPt->setAccessible(true);
                $reflectionFwPt->setValue($pdf->pdf_renderer, $pf[0]);
                $reflectionFhPt = $reflectionClass->getProperty('fhPt');
                $reflectionFhPt->setAccessible(true);
                $reflectionFhPt->setValue($pdf->pdf_renderer, $pf[1]);
                $reflectionPagedim = $reflectionClass->getProperty('pagedim');
                $reflectionPagedim->setAccessible(true);
                $reflectionPagedim->setValue($pdf->pdf_renderer, array());
                $pdf->pdf_renderer->setPageOrientation('P');
                if (!Configuration::get('ETS_ODE_DELIVERY_LABEL_USE_FOOTER'))
                    $pdf->pdf_renderer->setPrintFooter(false);
                if (!Configuration::get('ETS_ODE_DELIVERY_LABEL_USE_HEADER'))
                    $pdf->pdf_renderer->setPrintHeader(false);
                $pdf->render(true);
                $reference = $order->reference ?: $order->id;
                @unlink(dirname(__FILE__) . '/classes/' . $reference . '.png');
            }
        }
        if (Tools::isSubmit('loginascustomerorder')) {
            $this->loginascustomerorder();
        }
        if ($this->_errors)
            $this->context->controller->errors = $this->_errors;

    }

    public function loginascustomerorder()
    {
        $id_order = (int)Tools::getValue('id_order');
        $id_customer = (int)Tools::getValue('id_customer');
        if ($id_customer)
            $customer = new Customer($id_customer);
        elseif ($id_order) {
            $order = new Order($id_order);
            if (Validate::isLoadedObject($order)) {
                $customer = new Customer($order->id_customer);
            }
        }
        if (isset($customer) && Validate::isLoadedObject($customer) && !$customer->is_guest) {
            $token = MD5(time() . '-' . $customer->id . '-' . $customer->passwd);
            if (Db::getInstance()->getRow('SELECT * FROM  `' . _DB_PREFIX_ . 'ets_odm_customer_login` WHERE id_customer=' . (int)$customer->id))
                Db::getInstance()->execute('UPDATE  `' . _DB_PREFIX_ . 'ets_odm_customer_login` SET token="' . pSQL($token) . '",date_add="' . pSQL(date('Y-m-d H:i:s')) . '" WHERE id_customer=' . (int)$customer->id);
            else
                Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'ets_odm_customer_login` (id_customer,token,date_add) VALUES (' . (int)$customer->id . ',"' . pSQL($token) . '","' . pSQL(date('Y-m-d H:i:s')) . '")');
            if (Tools::isSubmit('ajax'))
                die(
                json_encode(
                    array(
                        'success' => true,
                        'link' => $this->context->link->getModuleLink($this->name, 'login', array('token' => $token)),
                    )
                )
                );
            else
                Tools::redirect($this->context->link->getModuleLink($this->name, 'login', array('token' => $token)));
        }
        die(
        json_encode(
            array(
                'error' => $this->l('Customer is not valid'),
            )
        )
        );
    }

    public function printPDFIcon($id_order)
    {
        static $valid_order_state = [];

        $order = new Order($id_order);
        if (!Validate::isLoadedObject($order)) {
            return '';
        }

        if (!isset($valid_order_state[$order->current_state])) {
            $valid_order_state[$order->current_state] = Validate::isLoadedObject($order->getCurrentOrderState());
        }

        if (!$valid_order_state[$order->current_state]) {
            return '';
        }
        if ($order->invoice_number || $order->delivery_number) {
            $this->context->smarty->assign([
                'odm_order' => $order,
                'ets_ordermanager' => $this,
            ]);

            return $this->display(__FILE__, 'print_pdf_icon.tpl');
        }
        return '';
    }

    public function changeOrderInline()
    {
        $errors = array();
        $id_order = (int)Tools::getValue('id_order');
        $table = Tools::getValue('table');
        $primary_key = Tools::getValue('primary_key');
        $key_value = (int)Tools::getValue('key_value');
        $key_change = Tools::getValue('key_change');
        $value_change = Tools::getValue('value_change');
        $success = $this->l('updated successfully');
        if ($key_change == 'address_delivery' || $key_change == 'address_invoice')
            $key_change = 'address1';
        $assign = array();
        $title_fileds = array(
            'shipping_cost_tax_incl' => array(
                'title' => $this->l('Shipping cost'),
            ),
            'shipping_cost_tax_excl' => array(
                'title' => $this->l('Shipping cost'),
            ),
            'weight' => array(
                'title' => $this->l('Weight'),
            ),
            'firstname' => array(
                'title' => $this->l('First name'),
            ),
            'lastname' => array(
                'title' => $this->l('Last name'),
            ),
            'id_country' => array(
                'title' => $this->l('Delivery')
            ),
            'current_state' => array(
                'title' => $this->l('Status'),
            ),
            'tracking_number' => array(
                'title' => $this->l('Tracking number'),
            ),
            'postcode' => array(
                'title' => $this->l('Postal code'),
            ),
            'amount' => array(
                'title' => $this->l('Amount'),
            ),
            'id_carrier' => array(
                'title' => $this->l('Shipping method'),
            ),
            'phone' => array(
                'title' => $this->l('Phone number'),
            )
        );
        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
            )
        );
        $this->title_fields = array_merge($this->title_fields, $title_fileds);
        $required = (int)Tools::getValue('required');
        if ($required && !$value_change) {
            $errors[] = (isset($this->title_fields[$key_change]['title']) ? $this->title_fields[$key_change]['title'] : $key_change) . ' ' . $this->l('is required');
        } elseif (($validate = Tools::getValue('validate')) && $value_change && method_exists('Validate', $validate) && !Validate::$validate($value_change)) {
            $errors[] = (isset($this->title_fields[$key_change]['title']) ? $this->title_fields[$key_change]['title'] : $key_change) . ' ' . $this->l('is not valid');
        } elseif (($validate = Tools::getValue('validate')) && $validate == 'isFloat' && $value_change < 0) {
            $errors[] = (isset($this->title_fields[$key_change]['title']) ? $this->title_fields[$key_change]['title'] : $key_change) . ' ' . $this->l('is not valid');
        } elseif ($key_change == 'reference' && Tools::strlen($value_change) > 9)
            $errors[] = $this->l('Reference order max length: 9 characters');
        if (!$errors) {
            if ($table == 'address') {
                $order = new Order($id_order);
                $address = new Address($order->{$primary_key});
                $address->{$key_change} = $value_change;
                unset($address->id);
                $address->deleted = 1;
                if ($address->add()) {
                    $order->{$primary_key} = $address->id;
                    $cart = new Cart($order->id_cart);
                    $cart->{$primary_key} = $address->id;
                    $cart->update();
                    $order->update();
                }
                if ($key_change == 'id_country') {
                    $country = new Country($value_change, $this->context->language->id);
                    $assign['value_changed'] = $country->name;
                } else {
                    $assign['value_changed'] = $value_change;
                }
            } else {
                if ($key_change == 'current_state') {
                    $order_state = new OrderState($value_change);
                    if (!Validate::isLoadedObject($order_state)) {
                        $errors[] = $this->l('The new order status is invalid.');
                    } else {
                        $order = new Order($id_order);
                        $current_order_state = $order->getCurrentOrderState();
                        if (!$current_order_state || $current_order_state->id != $order_state->id) {
                            // Create new OrderHistory
                            $history = new OrderHistory();
                            $history->id_order = $order->id;
                            $history->id_employee = (int)$this->context->employee->id;
                            $use_existings_payment = false;
                            if (!$order->hasInvoice()) {
                                $use_existings_payment = true;
                            }
                            $history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);
                            // Save all changes
                            $history->add();
                            $assign['badge_success'] = $order_state->logable;
                            $assign['PDFIcons'] = version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? $this->printPDFIcon($id_order) : $this->context->controller->printPDFIcons($id_order, false);

                        } else {
                            $errors[] = $this->l('The order has already been assigned this status.');
                        }
                    }
                    $assign['value_changed'] = $order_state->name[$this->context->language->id];
                    $assign['value_style'] = 'background-color:' . $order_state->color . ';color:' . (Tools::getBrightness($order_state->color) < 128 ? 'white' : '#383838') . ';';

                } else {
                    $assign['value_changed'] = $value_change . ($key_change == 'weight' ? ' ' . Configuration::get('PS_WEIGHT_UNIT') : '');
                    if ($key_change == 'amount' && $table == 'order_payment') {
                        $order_payment = new OrderPayment($key_value);
                        if ($order_payment->id_currency)
                            $currency = new Currency($order_payment->id_currency);
                        else
                            $currency = $this->context->currency;
                        $assign['value_changed'] = Tools::displayPrice($value_change, $currency);
                    }
                    if ($key_change == 'shipping_cost_tax_excl' || $key_change == 'shipping_cost_tax_incl') {
                        if (!Configuration::get('PS_ORDER_RECALCULATE_SHIPPING') && $this->is17) {
                            $errors[] = $this->l('You do not have permission to change shipping code');
                        } else {
                            $order = new Order($id_order);
                            if ($order->id_currency)
                                $currency = new Currency($order->id_currency);
                            else
                                $currency = $this->context->currency;
                            $cart = new Cart($order->id_cart);
                            if (!$cart->id)
                                $errors[] = $this->l('Cart') . ' #' . $order->id_cart . ' ' . $this->l('does not exists');
                            else {
                                $order_carrier = new OrderCarrier($key_value);
                                if (Configuration::get('PS_ATCP_SHIPWRAP')) {
                                    {
                                        $carrier_tax = $cart->getAverageProductsTaxRate();
                                    }
                                } else {
                                    $address = Address::initialize((int)$order->id_address_delivery);
                                    $carrier = new Carrier($order->id_carrier);
                                    $carrier_tax = $carrier->getTaxesRate($address);
                                }

                                if ($key_change == 'shipping_cost_tax_incl') {
                                    $shipping_cost_tax_excl = (float)$value_change / (1 + $carrier_tax / 100);
                                } elseif ($key_change == 'shipping_cost_tax_excl') {
                                    $shipping_cost_tax_excl = (float)$value_change;
                                }
                                //die($key_change.'x'.$shipping_cost_tax_excl.'x'.$carrier_tax);
                                if ($order_carrier->id_carrier) {

                                    $new_carrier = new Carrier($order_carrier->id_carrier);
                                    $id_tax_rules_group = (int)Db::getInstance()->getValue('SELECT id_tax_rules_group FROM `' . _DB_PREFIX_ . 'carrier` WHERE id_carrier=' . (int)$order_carrier->id_carrier);
                                    $carrier_group = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop` WHERE id_carrier=' . (int)$order_carrier->id_carrier . ' AND id_shop=' . (int)$this->context->shop->id);
                                    $id_reference = $new_carrier->id_reference;
                                    unset($new_carrier->id);
                                    $new_carrier->deleted = 1;
                                    $new_carrier->active = 1;
                                    $new_carrier->shipping_method = 2;
                                    $new_carrier->is_free = 0;
                                    $new_carrier->shipping_handling = 0;
                                    $new_carrier->shipping_external = 0;
                                    if ($new_carrier->add()) {

                                        if ($id_tax_rules_group) {
                                            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop`(`id_carrier`, `id_tax_rules_group`, `id_shop`) values("' . (int)$new_carrier->id . '","' . (int)$id_tax_rules_group . '","' . (int)$this->context->shop->id . '")');
                                        } elseif ($carrier_group)
                                            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop`(`id_carrier`, `id_tax_rules_group`, `id_shop`) values("' . (int)$new_carrier->id . '","' . (int)$carrier_group['id_tax_rules_group'] . '","' . (int)$carrier_group['id_shop'] . '")');
                                        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'carrier` set id_reference="' . (int)$id_reference . '",id_tax_rules_group="' . (int)$id_tax_rules_group . '" WHERE id_carrier=' . (int)$new_carrier->id);
                                        $rangPrice = new RangePrice();
                                        $rangPrice->id_carrier = $new_carrier->id;
                                        $rangPrice->delimiter1 = 0;
                                        $rangPrice->delimiter2 = 9999999999;
                                        if ($rangPrice->add()) {
                                            $zones = Db::getInstance()->executeS('SELECT id_zone FROM ' . _DB_PREFIX_ . 'zone');
                                            if ($zones) {
                                                foreach ($zones as $zone) {
                                                    $delivery = new Delivery();
                                                    $delivery->id_carrier = (int)$new_carrier->id;
                                                    $delivery->id_range_price = (int)$rangPrice->id;
                                                    $delivery->id_range_weight = 0;
                                                    $delivery->id_zone = (int)$zone['id_zone'];
                                                    $delivery->price = Tools::ps_round((float)$shipping_cost_tax_excl / $currency->conversion_rate, 2);
                                                    $delivery->id_shop = NULL;
                                                    $delivery->id_shop_group = NULL;
                                                    $delivery->add();
                                                    Db::getInstance()->execute('update `' . _DB_PREFIX_ . 'delivery` set id_shop = null, id_shop_group=null where id_delivery=' . (int)$delivery->id);
                                                }
                                            }
                                        }
                                        $order->id_carrier = $new_carrier->id;
                                        $order->update();
                                    }
                                }
                                Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_invoice_tax` WHERE id_order_invoice=' . (int)$order_carrier->id_order_invoice);
                                $order = $order->refreshShippingCost();
                                $order->update();
                                if (isset($new_carrier))
                                    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'order_carrier` set id_carrier=' . (int)$new_carrier->id . ' WHERE id_order=' . (int)$order_carrier->id_order);
                                $assign['value_changed'] = Tools::displayPrice($value_change, $currency);
                                $assign['value_order'] = $order;
                                if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
                                    $assign['is170'] = true;
                            }

                        }


                    } elseif ($key_change == 'id_carrier') {
                        $new_carrier = new Carrier($value_change);
                        $order = new Order($id_order);
                        $cart = new Cart($order->id_cart);

                        if (!$cart->id)
                            $errors[] = $this->l('Cart') . ' #' . $order->id_cart . ' ' . $this->l('does not exists');
                        else {
                            if ($order->id_currency)
                                $currency = new Currency($order->id_currency);
                            else
                                $currency = $this->context->currency;
                            $id_order_carrier = $order->getIdOrderCarrier();
                            $order_carrier = new OrderCarrier($id_order_carrier);
                            $id_tax_rules_group = (int)Db::getInstance()->getValue('SELECT id_tax_rules_group FROM `' . _DB_PREFIX_ . 'carrier` WHERE id_carrier=' . (int)$new_carrier->id);
                            $carrier_group = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop` WHERE id_carrier=' . (int)$new_carrier->id . ' AND id_shop=' . (int)$this->context->shop->id);
                            unset($new_carrier->id);
                            $new_carrier->deleted = 1;
                            $new_carrier->shipping_method = 2;
                            $new_carrier->is_free = 0;
                            $new_carrier->shipping_handling = 0;
                            if ($new_carrier->add()) {
                                if ($id_tax_rules_group) {
                                    Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop`(`id_carrier`, `id_tax_rules_group`, `id_shop`) values("' . (int)$new_carrier->id . '","' . (int)$id_tax_rules_group . '","' . (int)$this->context->shop->id . '")');
                                } elseif ($carrier_group)
                                    Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carrier_tax_rules_group_shop`(`id_carrier`, `id_tax_rules_group`, `id_shop`) values("' . (int)$new_carrier->id . '","' . (int)$carrier_group['id_tax_rules_group'] . '","' . (int)$carrier_group['id_shop'] . '")');
                                if (Configuration::get('PS_ATCP_SHIPWRAP')) {
                                    {
                                        $carrier_tax = $cart->getAverageProductsTaxRate();
                                    }
                                } else {
                                    $address = Address::initialize((int)$order->id_address_delivery);
                                    $carrier_tax = $new_carrier->getTaxesRate($address);
                                }
                                $shipping_cost_tax_excl = (float)$order_carrier->shipping_cost_tax_incl / (1 + $carrier_tax / 100);
                                $rangPrice = new RangePrice();
                                $rangPrice->id_carrier = $new_carrier->id;
                                $rangPrice->delimiter1 = 0;
                                $rangPrice->delimiter2 = 9999999999;
                                if ($rangPrice->add()) {
                                    $zones = Db::getInstance()->executeS('SELECT id_zone FROM ' . _DB_PREFIX_ . 'zone');
                                    if ($zones) {
                                        foreach ($zones as $zone) {
                                            $delivery = new Delivery();
                                            $delivery->id_carrier = (int)$new_carrier->id;
                                            $delivery->id_range_price = (int)$rangPrice->id;
                                            $delivery->id_range_weight = 0;
                                            $delivery->id_zone = (int)$zone['id_zone'];
                                            $delivery->price = Tools::ps_round((float)$shipping_cost_tax_excl / $currency->conversion_rate, 2);
                                            $delivery->id_shop = NULL;
                                            $delivery->id_shop_group = NULL;
                                            $delivery->add();
                                            Db::getInstance()->execute('update `' . _DB_PREFIX_ . 'delivery` set id_shop = null, id_shop_group=null where id_delivery=' . (int)$delivery->id);
                                        }
                                    }
                                }
                            }
                            $order->id_carrier = (int)$new_carrier->id;
                            $order_carrier->id_carrier = (int)$new_carrier->id;
                            $order_carrier->update();
                            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_invoice_tax` WHERE id_order_invoice=' . (int)$order_carrier->id_order_invoice);
                            $order->refreshShippingCost();
                            $assign['value_changed'] = $new_carrier->name;
                            $order->update();
                        }

                    } elseif ($table == 'ets_paymentmethod_order' && $key_change == 'fee') {
                        $order = new Order($key_value);
                        $fee_old = (float)Db::getInstance()->getValue('SELECT fee FROM ' . _DB_PREFIX_ . pSQL($table) . ' WHERE id_order=' . (int)$key_value);
                        $fee_incl_old = (float)Db::getInstance()->getValue('SELECT fee_incl FROM ' . _DB_PREFIX_ . pSQL($table) . ' WHERE id_order=' . (int)$key_value);
                        $fee_new = (float)$value_change;
                        $tax = ($fee_incl_old - $fee_old) / $fee_old;
                        $fee_incl_new = Tools::ps_round($fee_new + $fee_new * $tax, 2);
                        $order->total_paid += ($fee_incl_new - $fee_incl_old);
                        $order->total_paid_tax_incl += ($fee_incl_new - $fee_incl_old);
                        $order->total_paid_tax_excl += ($fee_new - $fee_old);
                        $order->total_paid_real += ($fee_new - $fee_old);
                        $order->update();
                        if ($order->id_currency)
                            $currency = new Currency($order->id_currency);
                        else
                            $currency = $this->context->currency;
                        $assign['value_changed'] = Tools::displayPrice($value_change, $currency);
                        $assign['total_paid_tax_incl'] = Tools::displayPrice($order->total_paid_tax_incl, $currency);
                        if (Db::getInstance()->getRow('SELECT fee FROM ' . _DB_PREFIX_ . pSQL($table) . ' WHERE id_order=' . (int)$key_value))
                            Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . (pSQL($table)) . ' SET ' . pSQL($key_change) . ' = "' . pSQL($value_change) . '",fee_incl="' . (float)$fee_incl_new . '" WHERE ' . pSQL($primary_key) . ' = "' . pSQL($key_value) . '"');
                        else
                            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'ets_paymentmethod_order`(id_paymentmethod,id_order,method_name,fee,fee_incl) values(0,"' . (int)$order->id . '","' . pSQL($order->payment) . '","' . (float)$value_change . '","' . (float)$fee_incl_new . '")');
                    } elseif ($key_change == 'tracking_number') {
                        $id_order_carrier = (int)$key_value;
                        $order_carrier = new OrderCarrier((int)$id_order_carrier);
                        $old_tracking_number = $order_carrier->tracking_number;
                        $tracking_number = $value_change;
                        $order = new Order($id_order);
                        $order->shipping_number = $tracking_number;
                        $order->update();
                        $order_carrier->tracking_number = pSQL($tracking_number);
                        $carrier = new Carrier((int)$order->id_carrier, $order->id_lang);
                        if ($order_carrier->update()) {
                            if (!empty($tracking_number) && $old_tracking_number != $tracking_number) {
                                if ($this->is17) {
                                    if ($order_carrier->sendInTransitEmail($order)) {
                                        $customer = new Customer((int)$order->id_customer);
                                        Hook::exec('actionAdminOrdersTrackingNumberUpdate', array(
                                            'order' => $order,
                                            'customer' => $customer,
                                            'carrier' => $carrier,
                                        ), null, false, true, false, $order->id_shop);
                                    }
                                } else {
                                    $customer = new Customer((int)$order->id_customer);
                                    $templateVars = array(
                                        '{followup}' => str_replace('@', $order->shipping_number, $carrier->url),
                                        '{firstname}' => $customer->firstname,
                                        '{lastname}' => $customer->lastname,
                                        '{id_order}' => $order->id,
                                        '{shipping_number}' => $order->shipping_number,
                                        '{order_name}' => $order->getUniqReference()
                                    );
                                    if (@Mail::Send((int)$order->id_lang, 'in_transit', Mail::l('Package in transit', (int)$order->id_lang), $templateVars,
                                        $customer->email, $customer->firstname . ' ' . $customer->lastname, null, null, null, null,
                                        _PS_MAIL_DIR_, true, (int)$order->id_shop)) {
                                        Hook::exec('actionAdminOrdersTrackingNumberUpdate', array('order' => $order, 'customer' => $customer, 'carrier' => $carrier), null, false, true, false, $order->id_shop);
                                    }
                                }
                            }
                        }
                        if ($carrier->url && !empty($tracking_number))
                            $assign['value_changed'] = Ets_ordermanager::displayText($tracking_number, 'a', array('href' => str_replace('@', $tracking_number, $carrier->url)));
                        else
                            $assign['value_changed'] = $tracking_number;
                    } else {
                        Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . (pSQL($table)) . ' SET ' . pSQL($key_change) . ' = "' . pSQL($value_change) . '" WHERE ' . pSQL($primary_key) . ' = "' . pSQL($key_value) . '"');
                        if ($key_change == 'firstname' || $key_change == 'lastname') {
                            $customer = new Customer($key_value);
                            $assign['value_changed'] = $key_change == 'firstname' ? Tools::substr($value_change, 0, 1) . '. ' . $customer->lastname : Tools::substr($customer->firstname, 0, 1) . '. ' . $value_change;
                        }

                    }
                }

            }
        }
        if ($errors) {
            die(
            json_encode(
                array(
                    'errors' => $this->displayError($errors),
                )
            )
            );
        }
        die(
        json_encode(
            array_merge(array('success' => $this->displaySuccessMessage((isset($this->title_fields[$key_change]['title']) ? $this->title_fields[$key_change]['title'] : $key_change) . ' ' . $success)), $assign)
        )
        );
    }

    public function _postCart()
    {
        if ($this->context->customer)
            $customer = $this->context->customer;
        else
            $customer = new Customer($this->context->cart->id_customer);
        if ($customer->id) {
            $addresses = $customer->getAddresses((int)$this->context->cart->id_lang);
            if ($this->context->cart->id && ($id_customer = (int)Tools::getValue('id_customer')) && $this->context->cart->id_customer != $id_customer) {
                $this->context->cart->id_customer = $id_customer;
                $this->context->cart->save();
            }
            $address_ok = false;
            if ($addresses) {
                foreach ($addresses as &$data) {
                    if ($data['id_address'] == $this->context->cart->id_address_delivery)
                        $address_ok = true;
                }
            }
            if (!$address_ok) {
                if (isset($addresses[0])) {
                    $this->context->cart->id_address_delivery = $addresses[0]['id_address'];
                    $this->context->cart->id_address_invoice = $addresses[0]['id_address'];
                } else {
                    $this->context->cart->id_address_delivery = 0;
                    $this->context->cart->id_address_invoice = 0;
                }
                $this->context->cart->update();
            }
        }

    }

    public function generateInvoicePDFByIdOrderAll($list_id_orders)
    {
        if ($list_id_orders && !is_array($list_id_orders))
            $list_id_orders = explode(',', $list_id_orders);
        $order_invoices_list = array();
        if ($list_id_orders) {
            foreach ($list_id_orders as $key => $id_order) {
                $order = new Order((int)$id_order);
                if (!Validate::isLoadedObject($order))
                    unset($list_id_orders[$key]);
                else
                    if (!Configuration::get('PS_INVOICE') || !$order->invoice_number) {
                        unset($list_id_orders[$key]);
                    }
            }
            if ($list_id_orders) {
                foreach ($list_id_orders as $id_order) {
                    if ($id_order) {
                        $order = new Order((int)$id_order);
                        if (!Validate::isLoadedObject($order))
                            die($this->l('The order cannot be found within your database.'));
                        $order_invoices_list[] = $order->getInvoicesCollection();
                    }

                }
            } else
                die($this->l('No order invoice selected.'));
        } else
            die($this->l('No order selected.'));
        $this->generatePDFAll($order_invoices_list, PDFALL::TEMPLATE_INVOICE_ALL);
    }

    public function generateDeliverySlipPDFByIdOrderAll($list_id_orders)
    {
        if ($list_id_orders && !is_array($list_id_orders))
            $list_id_orders = explode(',', $list_id_orders);
        $order_deliveries_list = array();
        if ($list_id_orders) {
            foreach ($list_id_orders as $key => $id_order) {
                $order = new Order((int)$id_order);
                if (!Validate::isLoadedObject($order))
                    unset($list_id_orders[$key]);
                else
                    if (!$order->delivery_number) {
                        unset($list_id_orders[$key]);
                    }
            }
            if ($list_id_orders) {
                foreach ($list_id_orders as $id_order) {
                    if ($id_order) {
                        $order = new Order((int)$id_order);
                        if (!Validate::isLoadedObject($order))
                            die($this->l('The order cannot be found within your database.'));

                        $order_deliveries_list[] = $order->getInvoicesCollection();
                    }

                }
            } else
                die($this->l('No delivery slip selected.'));
        } else
            die($this->l('No order selected.'));
        $this->generatePDFAll($order_deliveries_list, PDFALL::TEMPLATE_DELIVERY_SLIP_ALL);
    }

    public function generateDeliveryLabelByIdOrderAll($list_id_orders)
    {
        if ($list_id_orders && !is_array($list_id_orders))
            $list_id_orders = explode(',', $list_id_orders);
        $order_label_deliveries_list = array();
        if ($list_id_orders) {
            foreach ($list_id_orders as $id_order) {
                if ($id_order) {
                    $order = new Order((int)$id_order);
                    $order_label_deliveries_list[] = $order;
                }
            }
        }
        if ($order_label_deliveries_list) {
            $pdf = new PDFAll($order_label_deliveries_list, PDFALL::TEMPLATE_DELIVERY_LABEL_ALL, Context::getContext()->smarty);
            $size_format = Configuration::get('ETS_ODE_DELIVERY_LABEL_SIZE_FORMAT') ?: 'A4';
            if (!in_array($size_format, array('A3', 'A4', 'A5', 'A6')))
                $size_format = 'A4';
            if ($this->is17)
                $pf = TCPDF_STATIC::getPageSizeFromFormat($size_format);
            else
                $pf = $pdf->pdf_renderer->getPageSizeFromFormat($size_format);
            $reflectionClass = new ReflectionClass('PDFGeneratorCore');
            $reflectionFwPt = $reflectionClass->getProperty('fwPt');
            $reflectionFwPt->setAccessible(true);
            $reflectionFwPt->setValue($pdf->pdf_renderer, $pf[0]);
            $reflectionFhPt = $reflectionClass->getProperty('fhPt');
            $reflectionFhPt->setAccessible(true);
            $reflectionFhPt->setValue($pdf->pdf_renderer, $pf[1]);
            $reflectionPagedim = $reflectionClass->getProperty('pagedim');
            $reflectionPagedim->setAccessible(true);
            $reflectionPagedim->setValue($pdf->pdf_renderer, array());
            $pdf->pdf_renderer->setPageOrientation('P');
            if (!Configuration::get('ETS_ODE_DELIVERY_LABEL_USE_FOOTER'))
                $pdf->pdf_renderer->setPrintFooter(false);
            if (!Configuration::get('ETS_ODE_DELIVERY_LABEL_USE_HEADER'))
                $pdf->pdf_renderer->setPrintHeader(false);
            $pdf->renderAll(true, true);
            foreach ($order_label_deliveries_list as $order) {
                $reference = $order->reference ?: $order->id;
                @unlink(dirname(__FILE__) . '/classes/' . $reference . '.png');
            }
        } else
            die($this->l('No order selected.'));

    }

    public function generatePDFAll($objects, $template)
    {
        $pdf = new PDFAll($objects, $template, Context::getContext()->smarty);
        $pdf->renderAll();
    }

    public function exportOrderToCSV($fields, $orders)
    {
        if ($fields) {
            foreach ($fields as $key => $field) {
                if ($key == 'id_pdf' || $key == 'id_shop') {
                    unset($fields[$key]);
                    unset($field);
                }
            }
        }
        if ($orders) {
            $filename = 'export_orders_' . date('d_m_Y') . ".csv";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-type: application/x-msdownload");
            $flag = false;
            $csv = '';
            foreach ($orders as $order) {
                $titles = array();
                $values = array();
                foreach ($fields as $key => $field) {
                    if (!$flag) {
                        $titles[] = $this->title_fields[$key]['title'];
                    }
                    if (isset($field['type']) && $field['type'] == 'bool') {
                        if ($order[$key])
                            $values[] = $this->l('yes');
                        else
                            $values[] = $this->l('no');
                    } elseif (isset($field['type']) && $field['type'] == 'price') {
                        $values[] = Tools::displayPrice($order[$key]);
                    } elseif ($key == 'images') {
                        $values[] = trim(str_replace(array('  ', "\n", "\r\n"), '', $this->printOrderProducts($order[$key], $order, true)), ', ');
                    } else
                        $values[] = $order[$key];
                }
                if (!$flag) {
                    $csv .= join("\t", $titles) . "\r\n";
                    $flag = true;
                }
                $csv .= join("\t", $values) . "\r\n";
            }
            $csv = chr(255) . chr(254) . mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
            echo $csv;
            exit;
        } else
            die($this->l('No order selected.'));

    }

    public function delete_directory($directory)
    {
        $dir = opendir($directory);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($directory . '/' . $file)) {
                    $this->delete_directory($directory . '/' . $file);
                } else {
                    if (file_exists($directory . '/' . $file) && $file != 'index.php' && ($content = Tools::file_get_contents($directory . '/' . $file)) && Tools::strpos($content, 'overried by chung_ets') !== false) {
                        @unlink($directory . '/' . $file);
                        if (file_exists($directory . '/backup_' . $file))
                            copy($directory . '/backup_' . $file, $directory . '/' . $file);
                    }

                }
            }
        }
        closedir($dir);
    }

    public function ajaxProcessEditProductOnOrder()
    {
        // Return value
        $res = true;
        $product_invoice = (int)Tools::getValue('product_invoice');
        $id_order = (int)Tools::getValue('id_order');
        $product_id_order_detail = (int)Tools::getValue('product_id_order_detail');
        $order = new Order($id_order);
        $order_detail = new OrderDetail($product_id_order_detail);
        $product_name = Tools::getValue('product_name');
        $order_detail->product_name = $product_name && Validate::isCleanHtml($product_name) ? $product_name : '';
        if (Tools::isSubmit('product_invoice') && $product_invoice) {
            $order_invoice = new OrderInvoice($product_invoice);
        }

        // Check fields validity
        $this->doEditProductValidation($order_detail, $order, isset($order_invoice) ? $order_invoice : null);

        // If multiple product_quantity, the order details concern a product customized
        $quantity = 0;
        $product_quantity = Tools::getValue('product_quantity');
        if (is_array($product_quantity) && Ets_ordermanager::validateArray($product_quantity, 'isInt')) {
            foreach ($product_quantity as $id_customization => $qty) {
                // Update quantity of each customization
                Db::getInstance()->update('customization', array('quantity' => (int)$qty), 'id_customization = ' . (int)$id_customization);
                // Calculate the real quantity of the product
                $quantity += $qty;
            }
        } else {
            $quantity = (int)$product_quantity;
        }

        $product_price_tax_incl = Tools::ps_round((float)Tools::getValue('product_price_tax_incl'), 2);
        $product_price_tax_excl = Tools::ps_round((float)Tools::getValue('product_price_tax_excl'), 2);
        $total_products_tax_incl = $product_price_tax_incl * $quantity;
        $total_products_tax_excl = $product_price_tax_excl * $quantity;

        // Calculate differences of price (Before / After)
        $diff_price_tax_incl = $total_products_tax_incl - $order_detail->total_price_tax_incl;
        $diff_price_tax_excl = $total_products_tax_excl - $order_detail->total_price_tax_excl;

        // Apply change on OrderInvoice
        if (isset($order_invoice)) {
            // If OrderInvoice to use is different, we update the old invoice and new invoice
            if ($order_detail->id_order_invoice != $order_invoice->id) {
                $old_order_invoice = new OrderInvoice($order_detail->id_order_invoice);
                // We remove cost of products
                $old_order_invoice->total_products -= $order_detail->total_price_tax_excl;
                $old_order_invoice->total_products_wt -= $order_detail->total_price_tax_incl;

                $old_order_invoice->total_paid_tax_excl -= $order_detail->total_price_tax_excl;
                $old_order_invoice->total_paid_tax_incl -= $order_detail->total_price_tax_incl;

                $res &= $old_order_invoice->update();

                $order_invoice->total_products += $order_detail->total_price_tax_excl;
                $order_invoice->total_products_wt += $order_detail->total_price_tax_incl;

                $order_invoice->total_paid_tax_excl += $order_detail->total_price_tax_excl;
                $order_invoice->total_paid_tax_incl += $order_detail->total_price_tax_incl;

                $order_detail->id_order_invoice = $order_invoice->id;
            }
        }

        if ($diff_price_tax_incl != 0 && $diff_price_tax_excl != 0) {
            $order_detail->unit_price_tax_excl = $product_price_tax_excl;
            $order_detail->unit_price_tax_incl = $product_price_tax_incl;

            $order_detail->total_price_tax_incl += $diff_price_tax_incl;
            $order_detail->total_price_tax_excl += $diff_price_tax_excl;

            if (isset($order_invoice)) {
                // Apply changes on OrderInvoice
                $order_invoice->total_products += $diff_price_tax_excl;
                $order_invoice->total_products_wt += $diff_price_tax_incl;

                $order_invoice->total_paid_tax_excl += $diff_price_tax_excl;
                $order_invoice->total_paid_tax_incl += $diff_price_tax_incl;
            }

            // Apply changes on Order
            $order = new Order($order_detail->id_order);
            $order->total_products += $diff_price_tax_excl;
            $order->total_products_wt += $diff_price_tax_incl;

            $order->total_paid += $diff_price_tax_incl;
            $order->total_paid_tax_excl += $diff_price_tax_excl;
            $order->total_paid_tax_incl += $diff_price_tax_incl;

            $res &= $order->update();
        }

        $old_quantity = $order_detail->product_quantity;

        $order_detail->product_quantity = $quantity;
        $order_detail->reduction_percent = 0;

        // update taxes
        $res &= $order_detail->updateTaxAmount($order);

        // Save order detail
        $res &= $order_detail->update();

        // Update weight SUM
        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
        if (Validate::isLoadedObject($order_carrier)) {
            $order_carrier->weight = (float)$order->getTotalWeight();
            $res &= $order_carrier->update();
            if ($res) {
                $order->weight = sprintf('%.3f ' . Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);
            }
        }

        // Save order invoice
        if (isset($order_invoice)) {
            $res &= $order_invoice->update();
        }

        // Update product available quantity
        StockAvailable::updateQuantity($order_detail->product_id, $order_detail->product_attribute_id, ($old_quantity - $order_detail->product_quantity), $order->id_shop);

        $products = $this->getProductsByOrder($order);
        // Get the last product
        $product = $products[$order_detail->id];
        $resume = OrderSlip::getProductSlipResume($order_detail->id);
        $product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];
        $product['amount_refundable'] = $product['total_price_tax_excl'] - $resume['amount_tax_excl'];
        $product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl']);
        $product['refund_history'] = OrderSlip::getProductSlipDetail($order_detail->id);
        if ($product['id_warehouse'] != 0) {
            $warehouse = new Warehouse((int)$product['id_warehouse']);
            $product['warehouse_name'] = $warehouse->name;
            $warehouse_location = WarehouseProductLocation::getProductLocation($product['product_id'], $product['product_attribute_id'], $product['id_warehouse']);
            if (!empty($warehouse_location)) {
                $product['warehouse_location'] = $warehouse_location;
            } else {
                $product['warehouse_location'] = false;
            }
        } else {
            $product['warehouse_name'] = '--';
            $product['warehouse_location'] = false;
        }

        // Get invoices collection
        $invoice_collection = $order->getInvoicesCollection();

        $invoice_array = array();
        foreach ($invoice_collection as $invoice) {
            /* @var OrderInvoice $invoice */
            $invoice->name = $invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);
            $invoice_array[] = $invoice;
        }

        $order = $order->refreshShippingCost();

        $stockLocationIsAvailable = false;
        foreach ($products as $currentProduct) {
            if (!empty($currentProduct['location'])) {
                $stockLocationIsAvailable = true;
                break;
            }
        }

        // Assign to smarty informations in order to show the new product line
        $this->context->smarty->assign(array(
            'product' => $product,
            'order' => $order,
            'currency' => new Currency($order->id_currency),
            'can_edit' => $this->context->controller->access('edit'),
            'invoices_collection' => $invoice_collection,
            'current_id_lang' => Context::getContext()->language->id,
            'link' => Context::getContext()->link,
            'current_index' => $this->context->link->getAdminLink('AdminOrders'),
            'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
            'stock_location_is_available' => $stockLocationIsAvailable,
        ));
        if (!$res) {
            die(json_encode(array(
                'result' => $res,
                'error' => $this->l('An error occurred while editing the product line.'),
            )));
        }

        if (is_array($product_quantity) && Ets_ordermanager::validateArray($product_quantity, 'isInt')) {
            $view = $this->context->controller->createTemplate('_customized_data.tpl')->fetch();
        } else {
            $view = $this->context->controller->createTemplate('_product_line.tpl')->fetch();
        }

        $this->context->controller->sendChangedNotification($order);
        die(json_encode(array(
            'result' => $res,
            'view' => $view,
            'can_edit' => $this->context->controller->access('add'),
            'invoices_collection' => $invoice_collection,
            'order' => $order,
            'invoices' => $invoice_array,
            'documents_html' => $this->context->controller->createTemplate('_documents.tpl')->fetch(),
            'shipping_html' => $this->context->controller->createTemplate('_shipping.tpl')->fetch(),
            'customized_product' => is_array($product_quantity) && Ets_ordermanager::validateArray($product_quantity, 'isInt'),
        )));
    }

    public function doEditProductValidation(OrderDetail $order_detail, Order $order, OrderInvoice $order_invoice = null)
    {
        if (!Validate::isLoadedObject($order_detail)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The Order Detail object could not be loaded.'),
            )));
        }

        if (!empty($order_invoice) && !Validate::isLoadedObject($order_invoice)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The invoice object cannot be loaded.'),
            )));
        }

        if (!Validate::isLoadedObject($order)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The order object cannot be loaded.'),
            )));
        }

        if ($order_detail->id_order != $order->id) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('You cannot edit the order detail of this order.'),
            )));
        }

        // We can't edit a delivered order
        if ($order->hasBeenDelivered()) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('You cannot edit a delivered order.'),
            )));
        }
        $id_order = (int)Tools::getValue('id_order');
        if (!empty($order_invoice) && $order_invoice->id_order != $id_order) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('You cannot use this invoice for the order'),
            )));
        }

        // Clean price
        $product_price_tax_incl = str_replace(',', '.', Tools::getValue('product_price_tax_incl'));
        $product_price_tax_excl = str_replace(',', '.', Tools::getValue('product_price_tax_excl'));
        if (!Validate::isPrice($product_price_tax_incl) || !Validate::isPrice($product_price_tax_excl)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('Invalid price'),
            )));
        }
        $product_quantity = Tools::getValue('product_quantity');
        if (!is_array($product_quantity) && !Validate::isUnsignedInt($product_quantity)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('Invalid quantity'),
            )));
        } elseif (is_array($product_quantity)) {
            foreach ($product_quantity as $qty) {
                if (!Validate::isUnsignedInt($qty)) {
                    die(json_encode(array(
                        'result' => false,
                        'error' => $this->l('Invalid quantity'),
                    )));
                }
            }
        }
    }

    public function getProductsByOrder($order)
    {
        $products = $order->getProducts();
        foreach ($products as &$product) {
            if ($product['image'] != null) {
                $name = 'product_mini_' . (int)$product['product_id'] . (isset($product['product_attribute_id']) ? '_' . (int)$product['product_attribute_id'] : '') . '.jpg';
                // generate image cache, only for back office
                $product['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_ . 'p/' . $product['image']->getExistingImgPath() . '.jpg', $name, 45, 'jpg');
                if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                } else {
                    $product['image_size'] = false;
                }
            }
        }
        ksort($products);
        return $products;
    }

    public function ajaxProcessEditProductOnOrder16()
    {
        // Return value
        $res = true;
        $id_order = (int)Tools::getValue('id_order');
        $product_id_order_detail = (int)Tools::getValue('product_id_order_detail');
        $order = new Order((int)$id_order);
        $order_detail = new OrderDetail($product_id_order_detail);
        $product_invoice = (int)Tools::getValue('product_invoice');
        if (Tools::isSubmit('product_invoice')) {
            $order_invoice = new OrderInvoice($product_invoice);
        }

        // Check fields validity
        $this->doEditProductValidation($order_detail, $order, isset($order_invoice) ? $order_invoice : null);

        // If multiple product_quantity, the order details concern a product customized
        $quantity = 0;
        $product_quantity = Tools::getValue('product_quantity');
        if (is_array($product_quantity) && ($product_quantity = array_map('intval', $product_quantity)) && Ets_ordermanager::validateArray($product_quantity, 'isInt')) {
            foreach ($product_quantity as $id_customization => $qty) {
                // Update quantity of each customization
                Db::getInstance()->update('customization', array('quantity' => (int)$qty), 'id_customization = ' . (int)$id_customization);
                // Calculate the real quantity of the product
                $quantity += $qty;
            }
        } else {
            $quantity = (int)$product_quantity;
        }

        $product_price_tax_incl = Tools::ps_round((float)Tools::getValue('product_price_tax_incl'), 2);
        $product_price_tax_excl = Tools::ps_round((float)Tools::getValue('product_price_tax_excl'), 2);
        $total_products_tax_incl = $product_price_tax_incl * $quantity;
        $total_products_tax_excl = $product_price_tax_excl * $quantity;

        // Calculate differences of price (Before / After)
        $diff_price_tax_incl = $total_products_tax_incl - $order_detail->total_price_tax_incl;
        $diff_price_tax_excl = $total_products_tax_excl - $order_detail->total_price_tax_excl;

        // Apply change on OrderInvoice
        if (isset($order_invoice)) {
            // If OrderInvoice to use is different, we update the old invoice and new invoice
            if ($order_detail->id_order_invoice != $order_invoice->id) {
                $old_order_invoice = new OrderInvoice($order_detail->id_order_invoice);
                // We remove cost of products
                $old_order_invoice->total_products -= $order_detail->total_price_tax_excl;
                $old_order_invoice->total_products_wt -= $order_detail->total_price_tax_incl;

                $old_order_invoice->total_paid_tax_excl -= $order_detail->total_price_tax_excl;
                $old_order_invoice->total_paid_tax_incl -= $order_detail->total_price_tax_incl;

                $res &= $old_order_invoice->update();

                $order_invoice->total_products += $order_detail->total_price_tax_excl;
                $order_invoice->total_products_wt += $order_detail->total_price_tax_incl;

                $order_invoice->total_paid_tax_excl += $order_detail->total_price_tax_excl;
                $order_invoice->total_paid_tax_incl += $order_detail->total_price_tax_incl;

                $order_detail->id_order_invoice = $order_invoice->id;
            }
        }

        if ($diff_price_tax_incl != 0 && $diff_price_tax_excl != 0) {
            $order_detail->unit_price_tax_excl = $product_price_tax_excl;
            $order_detail->unit_price_tax_incl = $product_price_tax_incl;

            $order_detail->total_price_tax_incl += $diff_price_tax_incl;
            $order_detail->total_price_tax_excl += $diff_price_tax_excl;

            if (isset($order_invoice)) {
                // Apply changes on OrderInvoice
                $order_invoice->total_products += $diff_price_tax_excl;
                $order_invoice->total_products_wt += $diff_price_tax_incl;

                $order_invoice->total_paid_tax_excl += $diff_price_tax_excl;
                $order_invoice->total_paid_tax_incl += $diff_price_tax_incl;
            }

            // Apply changes on Order
            $order = new Order($order_detail->id_order);
            $order->total_products += $diff_price_tax_excl;
            $order->total_products_wt += $diff_price_tax_incl;

            $order->total_paid += $diff_price_tax_incl;
            $order->total_paid_tax_excl += $diff_price_tax_excl;
            $order->total_paid_tax_incl += $diff_price_tax_incl;

            $res &= $order->update();
        }

        $old_quantity = $order_detail->product_quantity;

        $order_detail->product_quantity = $quantity;
        $order_detail->reduction_percent = 0;

        // update taxes
        $res &= $order_detail->updateTaxAmount($order);

        // Save order detail
        $res &= $order_detail->update();

        // Update weight SUM
        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
        if (Validate::isLoadedObject($order_carrier)) {
            $order_carrier->weight = (float)$order->getTotalWeight();
            $res &= $order_carrier->update();
            if ($res) {
                $order->weight = sprintf("%.3f " . Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);
            }
        }

        // Save order invoice
        if (isset($order_invoice)) {
            $res &= $order_invoice->update();
        }

        // Update product available quantity
        StockAvailable::updateQuantity($order_detail->product_id, $order_detail->product_attribute_id, ($old_quantity - $order_detail->product_quantity), $order->id_shop);

        $products = $this->getProductsByOrder($order);
        // Get the last product
        $product = $products[$order_detail->id];
        $resume = OrderSlip::getProductSlipResume($order_detail->id);
        $product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];
        $product['amount_refundable'] = $product['total_price_tax_excl'] - $resume['amount_tax_excl'];
        $product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl']);
        $product['refund_history'] = OrderSlip::getProductSlipDetail($order_detail->id);
        if ($product['id_warehouse'] != 0) {
            $warehouse = new Warehouse((int)$product['id_warehouse']);
            $product['warehouse_name'] = $warehouse->name;
            $warehouse_location = WarehouseProductLocation::getProductLocation($product['product_id'], $product['product_attribute_id'], $product['id_warehouse']);
            if (!empty($warehouse_location)) {
                $product['warehouse_location'] = $warehouse_location;
            } else {
                $product['warehouse_location'] = false;
            }
        } else {
            $product['warehouse_name'] = '--';
            $product['warehouse_location'] = false;
        }

        // Get invoices collection
        $invoice_collection = $order->getInvoicesCollection();

        $invoice_array = array();
        foreach ($invoice_collection as $invoice) {
            /** @var OrderInvoice $invoice */
            $invoice->name = $invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);
            $invoice_array[] = $invoice;
        }

        // Assign to smarty informations in order to show the new product line
        $this->context->smarty->assign(array(
            'product' => $product,
            'order' => $order,
            'currency' => new Currency($order->id_currency),
            'can_edit' => $this->context->controller->tabAccess['edit'],
            'invoices_collection' => $invoice_collection,
            'current_id_lang' => Context::getContext()->language->id,
            'link' => Context::getContext()->link,
            'current_index' => $this->context->link->getAdminLink('AdminOrders'),
            'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
        ));

        if (!$res) {
            die(json_encode(array(
                'result' => $res,
                'error' => $this->l('An error occurred while editing the product line.')
            )));
        }
        if (is_array($product_quantity) && Ets_ordermanager::validateArray($product_quantity)) {
            $view = $this->context->controller->createTemplate('_customized_data.tpl')->fetch();
        } else {
            $view = $this->context->controller->createTemplate('_product_line.tpl')->fetch();
        }
        $this->context->controller->sendChangedNotification($order);
        die(json_encode(array(
            'result' => $res,
            'view' => $view,
            'can_edit' => $this->context->controller->tabAccess['edit'],
            'invoices_collection' => $invoice_collection,
            'order' => $order,
            'invoices' => $invoice_array,
            'documents_html' => $this->context->controller->createTemplate('_documents.tpl')->fetch(),
            'shipping_html' => $this->context->controller->createTemplate('_shipping.tpl')->fetch(),
            'customized_product' => is_array($product_quantity) && Ets_ordermanager::validateArray($product_quantity)
        )));
    }

    public static function getCurrencySign($id_currency)
    {
        $currency = new Currency($id_currency);
        return $currency->sign;
    }

    public static function getNoteOrderByID($id_order)
    {
        return Db::getInstance()->getValue('SELECT order_note FROM `' . _DB_PREFIX_ . 'orders` WHERE id_order=' . (int)$id_order);
    }

    public function getNoteOrder($id_order)
    {
        return Db::getInstance()->getValue('SELECT order_note FROM `' . _DB_PREFIX_ . 'orders` WHERE id_order=' . (int)$id_order);
    }

    public static function check16()
    {
        if (version_compare(_PS_VERSION_, '1.7', '<'))
            return true;
        return false;
    }

    public function displayLastMessage($last_message, $order)
    {
        $replied = (int)Db::getInstance()->getValue('SELECT id_customer_message FROM `' . _DB_PREFIX_ . 'customer_message` WHERE id_customer_thread = "' . (int)$order['id_customer_thread'] . '" AND id_employee=1 AND id_customer_message > "' . (int)$order['id_customer_message'] . '"');
        $this->context->smarty->assign(
            array(
                'replied' => $replied,
                'last_message' => $last_message,
            )
        );
        return $this->display(__FILE__, 'last_message.tpl');
    }

    public static function hasBeenShipped($order)
    {
        return count($order->getHistory((int)Context::getContext()->language->id, false, false, OrderState::FLAG_SHIPPED));
    }

    public static function hasBeenDelivered($order)
    {
        return count($order->getHistory((int)Context::getContext()->language->id, false, false, OrderState::FLAG_DELIVERY));
    }

    public function renderFormOrderManager()
    {
        if ($this->is17) {
            $permistions = array('read', 'create', 'update', 'delete');
            $slug = 'ROLE_MOD_TAB_ADMINORDERS_';
            if (Tools::isSubmit('savaOrderManagerConfig') && ($id_profile = (int)Tools::getValue('id_profile')) && $id_profile != 1 && ($perm = Tools::getValue('perm')) && (in_array($perm, $permistions) || $perm == 'all')) {
                if ($perm == 'all') {
                    foreach ($permistions as $permistion) {
                        $this->updatePermistionProfile($permistion, $id_profile);
                    }
                } else {
                    $this->updatePermistionProfile($perm, $id_profile);
                }
                die(
                json_encode(
                    array(
                        'success' => $this->l('Updated successfully'),
                    )
                )
                );
            }
            $profiles = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'profile_lang` where id_lang =' . (int)$this->context->language->id);
            foreach ($profiles as &$profile) {
                $all = 1;
                foreach ($permistions as $permistion) {
                    $id_authorization_role = Db::getInstance()->getValue('SELECT id_authorization_role FROM `' . _DB_PREFIX_ . 'authorization_role` WHERE slug ="' . pSQL($slug . Tools::strtoupper($permistion)) . '"');
                    $profile[$permistion] = Db::getInstance()->getValue('SELECT id_profile FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile ="' . (int)$profile['id_profile'] . '" AND id_authorization_role=' . (int)$id_authorization_role) ? true : false;
                    if (!$profile[$permistion])
                        $all = 0;
                }
                $profile['all'] = $all;
            }
        } else {
            $tabId = Tab::getIdFromClassName('AdminOrders');
            $permistions = array('view', 'add', 'edit', 'delete');
            if (Tools::isSubmit('savaOrderManagerConfig') && ($id_profile = (int)Tools::getValue('id_profile')) && $id_profile != 1 && ($perm = Tools::getValue('perm')) && (in_array($perm, $permistions) || $perm == 'all')) {
                if ($perm == 'all') {
                    foreach ($permistions as $permistion) {
                        $this->updatePermistionProfile($permistion, $id_profile);
                    }
                } else {
                    $this->updatePermistionProfile($perm, $id_profile);
                }
                die(
                json_encode(
                    array(
                        'success' => $this->l('Updated successfully'),
                    )
                )
                );
            }
            $profiles = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'profile_lang` where id_lang =' . (int)$this->context->language->id);
            foreach ($profiles as &$profile) {
                $all = 1;
                foreach ($permistions as $permistion) {
                    $profile[$permistion] = Db::getInstance()->getValue('SELECT `' . pSQL($permistion) . '` FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile ="' . (int)$profile['id_profile'] . '" AND id_tab=' . (int)$tabId);
                    if (!$profile[$permistion])
                        $all = 0;
                }
                $profile['all'] = $all;
            }
        }
        $this->context->smarty->assign(
            array(
                'profiles' => $profiles,
                'permistions' => $permistions,
                'is_ps16' => $this->is17 ? false : true,
            )
        );
        return $this->display(__FILE__, 'manager_form.tpl');
    }

    public function updatePermistionProfile($perm, $id_profile)
    {
        $enabled = (int)Tools::getValue('enabled');
        if ($this->is17) {
            $slug = 'ROLE_MOD_TAB_ADMINORDERS_';
            $id_authorization_role = Db::getInstance()->getValue('SELECT id_authorization_role FROM `' . _DB_PREFIX_ . 'authorization_role` WHERE slug ="' . pSQL($slug . Tools::strtoupper($perm)) . '"');
            if ($id_authorization_role) {
                if ($enabled) {
                    if (!Db::getInstance()->getValue('SELECT id_profile FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile ="' . (int)$id_profile . '" AND id_authorization_role=' . (int)$id_authorization_role))
                        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'access`(id_profile,id_authorization_role) VALUES("' . (int)$id_profile . '","' . (int)$id_authorization_role . '")');
                } else {
                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile =' . (int)$id_profile . ' AND id_authorization_role=' . (int)$id_authorization_role);
                }
            }
        } else {
            $tabId = Tab::getIdFromClassName('AdminOrders');
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'access` SET `' . pSQL($perm) . '` = "' . (int)$enabled . '" WHERE id_profile=' . (int)$id_profile . ' AND id_tab=' . (int)$tabId);
        }
    }

    public function checkAccess($action)
    {
        if ($this->context->employee->id_profile == 1)
            return true;
        else {
            if ($this->is17) {
                $slug = 'ROLE_MOD_TAB_ADMINORDERS_';
                $id_authorization_role = Db::getInstance()->getValue('SELECT id_authorization_role FROM `' . _DB_PREFIX_ . 'authorization_role` WHERE slug ="' . pSQL($slug . Tools::strtoupper($action)) . '"');
                return Db::getInstance()->getValue('SELECT id_profile FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile ="' . (int)$this->context->employee->id_profile . '" AND id_authorization_role=' . (int)$id_authorization_role) ? true : false;
            } else {
                $permistions = array('read' => 'view', 'create' => 'add', 'update' => 'edit', 'delete' => 'delete');
                $tabId = Tab::getIdFromClassName('AdminOrders');
                return Db::getInstance()->getValue('SELECT `' . pSQL($permistions[$action]) . '` FROM `' . _DB_PREFIX_ . 'access` WHERE id_profile=' . (int)$this->context->employee->id_profile . ' AND id_tab=' . (int)$tabId);
            }
        }
    }

    public static function getSignPrice($id_currency)
    {
        $currency = new Currency((int)$id_currency);
        return $currency->sign;
    }

    public static function getListCarriersByIDOrder($id_order)
    {
        $order = new Order($id_order);
        $carriers = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'carrier` c
        LEFT JOIN `' . _DB_PREFIX_ . 'carrier_lang` cl on (c.id_carrier = cl.id_carrier AND cl.id_lang="' . (int)Context::getContext()->language->id . '")
        WHERE ((c.deleted!=1 AND c.active=1) OR c.id_carrier = "' . (int)$order->id_carrier . '") GROUP BY c.id_carrier');
        if ($carriers) {
            foreach ($carriers as &$carrier) {
                if (!$carrier['name'])
                    $carrier['name'] = Context::getContext()->shop->name;
            }
        }
        return $carriers;
    }

    public static function checkViewModule()
    {
        if (Tools::isSubmit('liteDisplaying'))
            return false;
        if (Context::getContext()->employee->id_profile == 1)
            return true;
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $slug = 'ROLE_MOD_MODULE_ETS_ORDERMANAGER_READ';
            $id_authorization_role = Db::getInstance()->getValue('SELECT id_authorization_role FROM `' . _DB_PREFIX_ . 'authorization_role` WHERE slug ="' . pSQL($slug) . '"');
            return Db::getInstance()->getValue('SELECT id_profile FROM `' . _DB_PREFIX_ . 'module_access` WHERE id_profile ="' . (int)Context::getContext()->employee->id_profile . '" AND id_authorization_role=' . (int)$id_authorization_role) ? true : false;
        } else {
            $ets_ordermanager = Module::getInstanceByName('ets_ordermanager');
            return (int)Db::getInstance()->getValue('SELECT view FROM `' . _DB_PREFIX_ . 'module_access` WHERE id_profile="' . (int)Context::getContext()->employee->id_profile . '" AND id_module="' . (int)$ets_ordermanager->id . '"');
        }

    }

    public function displayRestoreLink($currentIndex, $identifier, $id, $table, $token)
    {
        $href = $currentIndex . '&' . $identifier . '=' . $id . '&restore' . $table . '&token=' . $token;
        $this->context->smarty->assign(
            array(
                $identifier => $id,
                'href' => $href,
                'action' => $this->l('Restore'),
            )
        );
        return $this->display(__FILE__, 'list_action_restore.tpl');
    }

    public function displayPrintDeliveryLabelLink($currentIndex, $identifier, $id, $table, $token)
    {
        if ($this->checkOrderIsVirtual($id))
            return '';
        $href = $currentIndex . '&' . $identifier . '=' . $id . '&printdeliverylabel' . $table . '&token=' . $token;
        $this->context->smarty->assign(
            array(
                $identifier => $id,
                'href' => $href,
                'action' => $this->l('Shipping label'),
            )
        );
        return $this->display(__FILE__, 'list_action_print_delivery_label.tpl');
    }

    public function displayLoginAsCustomerLink($currentIndex, $identifier, $id, $table, $token)
    {
        $controller = Tools::getValue('controller');
        if ($controller == 'AdminCustomers')
            $customer = new Customer($id);
        else {
            $order = new Order($id);
            $customer = new Customer($order->id_customer);
        }
        if (Validate::isLoadedObject($customer) && $customer->is_guest == 0) {
            $href = $this->context->link->getAdminLink('AdminOrders') . '&' . $identifier . '=' . $id . '&loginascustomerorder=1&token=' . $token;
            $this->context->smarty->assign(
                array(
                    $identifier => $id,
                    'href' => $href,
                    'action' => $this->l('Login as customer'),
                )
            );
            return $this->display(__FILE__, 'list_action_login_as_customer.tpl');
        }
        unset($table);
        unset($currentIndex);
        return '';
    }

    public function checkOrderIsCustomer($id_order, $id_customer = 0)
    {
        if ($id_customer) {
            $customer = new Customer($id_customer);
        } else {
            $order = new Order($id_order);
            $customer = new Customer($order->id_customer);
        }
        if (Validate::isLoadedObject($customer) && !$customer->is_guest)
            return true;
        else
            return false;
    }

    public function checkOrderIsVirtual($id_order)
    {
        if ($id_order && ($order = new Order($id_order)) && Validate::isLoadedObject($order)) {
            if ($order->isVirtual())
                return true;
            else
                return false;
        }
        $products = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_detail` od 
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (od.product_id = p.id_product)
            WHERE p.is_virtual=0
        ');
        if ($products)
            return false;
        else
            return true;
    }

    public function refreshShippingCost(&$order)
    {
        if (empty($order->id)) {
            return false;
        }
        if (!Configuration::get('PS_ORDER_RECALCULATE_SHIPPING') && $this->is17) {
            return $order;
        }
        $fake_cart = new Cart((int)$order->id_cart);
        if (!$fake_cart->id)
            return false;
        $new_cart = $fake_cart->duplicate();
        $new_cart = $new_cart['cart'];

        // assign order id_address_delivery to cart
        $new_cart->id_address_delivery = (int)$order->id_address_delivery;

        // assign id_carrier
        $new_cart->id_carrier = (int)$order->id_carrier;

        //remove all products : cart (maybe change in the meantime)
        foreach ($new_cart->getProducts() as $product) {
            $new_cart->deleteProduct((int)$product['id_product'], (int)$product['id_product_attribute']);
        }

        // add real order products
        foreach ($order->getProducts() as $product) {
            $new_cart->updateQty($product['product_quantity'], (int)$product['product_id']);
        }

        // get new shipping cost
        $base_total_shipping_tax_incl = (float)$new_cart->getPackageShippingCost((int)$new_cart->id_carrier, true, null);
        $base_total_shipping_tax_excl = (float)$new_cart->getPackageShippingCost((int)$new_cart->id_carrier, false, null);
        // calculate diff price, then apply new order totals
        $diff_shipping_tax_incl = $order->total_shipping_tax_incl - $base_total_shipping_tax_incl;
        $diff_shipping_tax_excl = $order->total_shipping_tax_excl - $base_total_shipping_tax_excl;

        $order->total_shipping_tax_excl = $order->total_shipping_tax_excl - $diff_shipping_tax_excl;
        $order->total_shipping_tax_incl = $order->total_shipping_tax_incl - $diff_shipping_tax_incl;
        $order->total_shipping = $order->total_shipping_tax_incl;
        $order->total_paid_tax_excl = $order->total_paid_tax_excl - $diff_shipping_tax_excl;
        $order->total_paid_tax_incl = $order->total_paid_tax_incl - $diff_shipping_tax_incl;
        $order->total_paid = $order->total_paid_tax_incl;
        $order->update();

        // save order_carrier prices, we'll save order right after this in update() method
        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
        $order_carrier->shipping_cost_tax_excl = $order->total_shipping_tax_excl;
        $order_carrier->shipping_cost_tax_incl = $order->total_shipping_tax_incl;
        $order_carrier->update();
        // remove fake cart
        $new_cart->delete();
        return $order;
    }

    public function doDeleteProductLineValidation($order_detail, $order)
    {
        if (!Validate::isLoadedObject($order_detail)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The Order Detail object could not be loaded.'),
            )));
        }
        if (!Validate::isLoadedObject($order)) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The order object cannot be loaded.'),
            )));
        }

        if ($order_detail->id_order != $order->id) {
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('You cannot delete the order detail.'),
            )));
        }

        // We can't edit a delivered order
        $products = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_detail` WHERE id_order =' . (int)$order->id);
        if (Count($products) <= 1)
            die(json_encode(array(
                'result' => false,
                'error' => $this->l('The shopping cart cannot be empty'),
            )));
    }

    public function printOrderProducts($id_order, $tr, $no_html = false)
    {
        if (!$id_order)
            $id_order = $tr['id_order'];
        $products = Db::getInstance()->executeS('SELECT product_name,product_id,product_attribute_id, product_quantity FROM `' . _DB_PREFIX_ . 'order_detail` WHERE id_order=' . (int)$id_order . ' ORDER BY id_order_detail ASC');
        if ($products) {
            foreach ($products as &$product) {
                $product_class = new Product($product['product_id'], false, $this->context->language->id);
                $image = false;
                if ($product['product_attribute_id']) {
                    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_image` pai
                    INNER JOIN `' . _DB_PREFIX_ . 'image` i ON pai.id_image=i.id_image WHERE pai.id_product_attribute=' . (int)$product['product_attribute_id'];
                    if (!$image = Db::getInstance()->getRow($sql . ' AND i.cover=1'))
                        $image = Db::getInstance()->getRow($sql);
                }
                if (!$image) {
                    $sql = 'SELECT i.id_image FROM `' . _DB_PREFIX_ . 'image` i';
                    if ($product['product_attribute_id'])
                        $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON (i.id_image=pai.id_image AND pai.id_product_attribute="' . (int)$product['product_attribute_id'] . '")';
                    $sql .= ' WHERE i.id_product="' . (int)$product['product_id'] . '"';
                    if (!$image = Db::getInstance()->getRow($sql . ' AND i.cover=1')) {
                        $image = Db::getInstance()->getRow($sql);
                    }
                }

                if ($image) {
                    if (version_compare(_PS_VERSION_, '1.7', '>='))
                        $type_image = ImageType::getFormattedName('small');
                    else
                        $type_image = ImageType::getFormatedName('small');
                    $product['image'] = $this->context->link->getImageLink($product_class->link_rewrite, $image['id_image'], $type_image);
                } else {
                    $product['image'] = '';
                }
            }
        }
        $this->context->smarty->assign(
            array(
                'products' => $products,
                'tr' => $tr,
                'no_html' => $no_html,
                'exportorder' => Tools::isSubmit('exportorder'),
                'link' => $this->context->link,
            )
        );
        return $this->display(__FILE__, 'images.tpl');
    }

    protected function setProductImageInformations(&$pack_item)
    {
        if (isset($pack_item['id_product_attribute']) && $pack_item['id_product_attribute']) {
            $id_image = Db::getInstance()->getValue('
                SELECT `image_shop`.id_image
                FROM `' . _DB_PREFIX_ . 'product_attribute_image` pai' .
                Shop::addSqlAssociation('image', 'pai', true) . '
                WHERE id_product_attribute = ' . (int)$pack_item['id_product_attribute']);
        }

        if (!isset($id_image) || !$id_image) {
            $id_image = Db::getInstance()->getValue('
                SELECT `image_shop`.id_image
                FROM `' . _DB_PREFIX_ . 'image` i' .
                Shop::addSqlAssociation('image', 'i', true, 'image_shop.cover=1') . '
                WHERE i.id_product = ' . (int)$pack_item['id_product']
            );
        }

        $pack_item['image'] = null;
        $pack_item['image_size'] = null;

        if ($id_image) {
            $pack_item['image'] = new Image($id_image);
        }
    }

    public function checkCreatedColumn($table, $column)
    {
        $fieldsCustomers = Db::getInstance()->ExecuteS('DESCRIBE ' . _DB_PREFIX_ . pSQL($table));
        $check_add = false;
        foreach ($fieldsCustomers as $field) {
            if ($field['Field'] == $column) {
                $check_add = true;
                break;
            }
        }
        return $check_add;
    }

    public function getCarrierOrder($id_carrier, $id_order)
    {
        $order = new Order($id_order);
        $order_carrier = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'order_carrier` WHERE id_carrier = "' . (int)$id_carrier . '" AND id_order="' . (int)$id_order . '"');
        $order_carrier['tax_incl'] = $order->getTaxCalculationMethod() == PS_TAX_INC ? true : false;
        $order_carrier['weight_unit'] = Configuration::get('PS_WEIGHT_UNIT');
        $currency = new Currency($order->id_currency);
        $order_carrier['sign'] = $currency->sign;
        return $order_carrier;
    }

    public function getTextLang($text, $lang, $file_name = '')
    {
        if (is_array($lang))
            $iso_code = $lang['iso_code'];
        elseif (is_object($lang))
            $iso_code = $lang->iso_code;
        else {
            $language = new Language($lang);
            $iso_code = $language->iso_code;
        }
        $modulePath = rtrim(_PS_MODULE_DIR_, '/') . '/' . $this->name;
        $fileTransDir = $modulePath . '/translations/' . $iso_code . '.' . 'php';
        if (!@file_exists($fileTransDir)) {
            return $text;
        }
        $fileContent = Tools::file_get_contents($fileTransDir);
        $text_tras = preg_replace("/\\\*'/", "\'", $text);
        $strMd5 = md5($text_tras);
        $keyMd5 = '<{' . $this->name . '}prestashop>' . ($file_name ?: $this->name) . '_' . $strMd5;
        preg_match('/(\$_MODULE\[\'' . preg_quote($keyMd5) . '\'\]\s*=\s*\')(.*)(\';)/', $fileContent, $matches);
        if ($matches && isset($matches[2])) {
            return $matches[2];
        }
        return $text;
    }

    public function installTemplatePdfDeliveryLabel()
    {
        $languages = Language::getLanguages(false);
        $headers = array();
        $contents = array();
        $footers = array();
        foreach ($languages as $language) {
            if (file_exists(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_lable_header.html'))
                $headers[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_lable_header.html');
            else
                $headers[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/en/shipping_lable_header.html');
            if (file_exists(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_label_content.html'))
                $contents[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_label_content.html');
            else
                $contents[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/en/shipping_label_content.html');
            if (file_exists(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_label_footer.html'))
                $footers[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/' . $language['iso_code'] . '/shipping_label_footer.html');
            else
                $footers[$language['id_lang']] = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/temp/en/shipping_label_footer.html');
        }
        Configuration::updateValue('ETS_ODE_DELIVERY_LABEL_HEADER', $headers, true);
        Configuration::updateValue('ETS_ODE_DELIVERY_LABEL_CONTENT', $contents, true);
        Configuration::updateValue('ETS_ODE_DELIVERY_LABEL_FOOTER', $footers, true);
    }

    public function displayText($content, $tag, $attr_datas = array())
    {
        $this->smarty->assign(array(
            'content' => $content,
            'tag' => $tag,
            'attr_datas' => $attr_datas,
        ));
        return $this->display(__FILE__, 'html.tpl');
    }

    public static function validateArray($array, $validate = 'isCleanHtml')
    {
        if (!is_array($array))
            return true;
        if (method_exists('Validate', $validate)) {
            if ($array && is_array($array)) {
                $ok = true;
                foreach ($array as $val) {
                    if (!is_array($val)) {
                        if ($val && !Validate::$validate($val)) {
                            $ok = false;
                            break;
                        }
                    } else
                        $ok = self::validateArray($val, $validate);
                }
                return $ok;
            }
        }
        return true;
    }

    public function _runCronJob()
    {
        if (!$this->autoExport(0, Configuration::getGlobalValue('ETS_ODE_SAVE_CRONJOB_TOKEN'))) {
            if (Configuration::getGlobalValue('ETS_ODE_SAVE_CRONJOB_TOKEN'))
                Tools::error_log(date('Y-m-d H:i:s') . " Cronjob done! Nothing to do\n", 3, dirname(__file__) . '/cronjob.log');

            if (Tools::isSubmit('ajax')) {
                die(
                json_encode(
                    array(
                        'success' => $this->l('Cronjob done! Nothing to do'),
                    )
                )
                );
            }
            die($this->l('Cronjob done! Nothing to do'));
        } else {
            if (Tools::isSubmit('ajax')) {
                die(
                json_encode(
                    array(
                        'success' => $this->l('Cronjob done!'),
                    )
                )
                );
            }
            die($this->l('Cronjob done!'));
        }
    }

    public function printAmountPayment($id_payment, $id_order)
    {
        $this->context->smarty->assign(
            array(
                'orderPayment' => new OrderPayment($id_payment),
                'id_order' => $id_order,
            )
        );
        return $this->display(__FILE__, 'input_payment.tpl');
    }

    public function hookDisplayDisplayIdCart($params)
    {
        if (isset($params['id_order']) && $params['id_order']) {
            $order = new Order($params['id_order']);
            return sprintf($this->l('Cart id: %d'), $order->id_cart);
        }
    }

    public function hookDisplayAdminGridTableBefore($params)
    {
        if (Tools::isSubmit('viewtrash')) {
            return $this->display(__FILE__, 'btn_trash.tpl');
        }
    }

    public function hookActionObjectOrderDeleteAfter($params)
    {
        if (isset($params['object']) && ($order = $params['object']) && Validate::isLoadedObject($order)) {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_invoice` where id_order=' . $order->id);
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_slip` where id_order=' . $order->id);
            $order_details = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_detail` where id_order=' . $order->id);
            foreach ($order_details as $order_detail) {
                Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_detail_tax` WHERE id_order_detail=' . (int)$order_detail['id_order_detail']);
            }
            $this->refundQuantityProduct($order->id);
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_detail` where id_order=' . (int)$order->id);
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_carrier` WHERE id_order=' . (int)$order->id);
        }
    }

    public function refundQuantityProduct($id_order)
    {
        $order_details = Db::getInstance()->executeS('SELECT od.* FROM `' . _DB_PREFIX_ . 'order_detail` od
        LEFT JOIN `' . _DB_PREFIX_ . 'ets_odm_order_detail_refund` odr ON (od.id_order_detail = odr.id_order_detail)
        where id_order=' . (int)$id_order . ' AND odr.id_order_detail is null');
        foreach ($order_details as $order_detail) {
            $quantity = $order_detail['product_quantity'] + StockAvailable::getQuantityAvailableByProduct($order_detail['product_id'], $order_detail['product_attribute_id'], $order_detail['id_shop']);
            StockAvailable::setQuantity($order_detail['product_id'], (int)$order_detail['product_attribute_id'], $quantity, $order_detail['id_shop']);
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'ets_odm_order_detail_refund`(id_order_detail,refund) VALUES("' . (int)$order_detail['id_order_detail'] . '","1")');
        }
    }

    public function upQuantityProduct($id_order)
    {
        $order_details = Db::getInstance()->executeS('SELECT od.* FROM `' . _DB_PREFIX_ . 'order_detail` od
        INNER JOIN `' . _DB_PREFIX_ . 'ets_odm_order_detail_refund` odr ON (od.id_order_detail = odr.id_order_detail)
        where id_order=' . (int)$id_order);
        foreach ($order_details as $order_detail) {
            $quantity = StockAvailable::getQuantityAvailableByProduct($order_detail['product_id'], $order_detail['product_attribute_id'], $order_detail['id_shop']) - $order_detail['product_quantity'];
            StockAvailable::setQuantity($order_detail['product_id'], (int)$order_detail['product_attribute_id'], $quantity > 0 ? $quantity : 0, $order_detail['id_shop']);
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'ets_odm_order_detail_refund` WHERE id_order_detail = ' . (int)$order_detail['id_order_detail']);
        }
    }
}