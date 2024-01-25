<?php
/**
* This is main class of module. 
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

if (!defined("_PS_VERSION_"))
    exit;
include_once(_PS_MODULE_DIR_ . 'gwadvancedinvoice/model/gwadvancedinvoicetemplateModel.php');
class Gwadvancedinvoice extends Module
{
    public function __construct()
    {
        $this->name = "gwadvancedinvoice";
        $this->tab = "billing_invoicing";
        $this->version = "1.3.1";
        $this->author = "Globo Jsc";
        $this->need_instance = 1;
        $this->bootstrap = 1;
        $this->module_key = '4271a0ff21d167ad428a7ca2fb61544c';
        parent::__construct();
        $this->displayName = $this->l('Advanced Invoice Builder and Custom Number');
        $this->description = $this->l('Advanced Invoice Template Builder is the perfect tool to customize your invoice without any technical knowledge required.');
        $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        if (Shop::isFeatureActive()){
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        return parent::install()
            && $this->_createTables()
            && $this->_createTab()
            && $this->_installFonts()
            && $this->registerHook('actionObjectOrderAddAfter')
            && $this->registerHook('actionOrderStatusPostUpdate')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->fixViewCustomizeNumberInTpl(true);
    }
    public function uninstall()
    {
        return parent::uninstall()
            && $this->_deleteTables()
            && $this->_deleteTab()
            && $this->unregisterHook("actionObjectOrderAddAfter")
            && $this->unregisterHook("actionOrderStatusPostUpdate")
            && $this->unregisterHook("ActionAdminControllerSetMedia")
            && $this->fixViewCustomizeNumberInTpl(false);
    }

    private function _createTables()
    {
         $response = (bool) Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate` (
                `id_gwadvancedinvoicetemplate` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `rtl` INT NULL DEFAULT  "0",
                `active` tinyint(1) unsigned NOT NULL,
                `choose_design` varchar(255) NOT NULL,
                `layout` varchar(255) NOT NULL,
                `activeheader` tinyint(1) unsigned NOT NULL,
                `activefooter` tinyint(1) unsigned NOT NULL,
                `pagesize` varchar(10) NOT NULL,
                `mgheader` INT NOT NULL DEFAULT  "0",
                `mgfooter` INT NOT NULL DEFAULT  "0",
                `mgcontent` VARCHAR( 255 ) NULL,
                `barcodetype` varchar(10) NOT NULL,
                `barcodeformat` varchar(255) NULL,
                `barcodeproducttype` varchar(10) NOT NULL,
                `barcodeproductformat` varchar(255) NULL,
                `pageorientation` varchar(5) NOT NULL,
                `discountval` varchar(15) NOT NULL,
                `customcss` text NULL,
                `template_config` text NULL,
                `hidden_discount` INT NULL DEFAULT  "0",
                PRIMARY KEY (`id_gwadvancedinvoicetemplate`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $response &= (bool) Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate_lang` (
                        `id_gwadvancedinvoicetemplate` int(10) unsigned NOT NULL,
                        `id_lang` int(10) unsigned NOT NULL,
                        `title` varchar(255) NOT NULL,
                        `invoice` text  NULL,
                        `header` text  NULL,
                        `footer` text  NULL,
                        `watermark` varchar(255) NULL,
                        `watermarktext` varchar(255) NULL,
                        `watermarkfont` varchar(255) NULL,
                        `watermarksize` INT(10) NULL,
                        `productcolumns` text  NULL,
                        PRIMARY KEY (`id_gwadvancedinvoicetemplate`,`id_lang`)
                    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
                ');
        $response &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate_shop` (
                `id_gwadvancedinvoicetemplate` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gwadvancedinvoicetemplate`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $response &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gwaicustomnumber` (
                `id_gwaicustomnumber` int(10) unsigned AUTO_INCREMENT NOT NULL,
                `type` varchar(2) NULL,
                `start` int(10) unsigned NOT NULL,
                `step` int(10) unsigned NOT NULL,
                `length` int(10) unsigned NOT NULL,
                `numberformat` varchar(255) NULL,
                `groups` varchar(255) NULL,
                `resettype` int(1) unsigned NOT NULL,
                `resetnumber` int(10) NULL,
                `resetdate` TIMESTAMP NULL,
                `id_shop` int(10) unsigned NOT NULL,
                `current_counter_nbr` int(10) NULL DEFAULT  "0",
                PRIMARY KEY (`id_gwaicustomnumber`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        // from version 1.2.1
        $response &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gwaicnrandom` (
            	`id_gwaicnrandom` INT(10) UNSIGNED AUTO_INCREMENT NOT NULL,
                `type` varchar(2) NULL,
            	`id_object` INT(10) UNSIGNED NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                `random` varchar(255) NULL,
                `random_number` varchar(255) NULL,
            	PRIMARY KEY (`id_gwaicnrandom`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;');
        
        //custom order reference
        $response &= Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'orders` MODIFY `reference` VARCHAR(32);');
        $response &= Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'order_payment` MODIFY `order_reference` VARCHAR(32)');
        return $response;
    }
    public function fixViewCustomizeNumberInTpl($install = true){
        $tpl_files = array(
            array(
                'file'=>'_documents.tpl',
                'changes'=>array(
                                array(
                                    'old'=>'{Configuration::get(\'PS_DELIVERY_PREFIX\', $current_id_lang, null, $order->id_shop)}{\'%06d\'|sprintf:$document->delivery_number}',
                                    'new'=>'{$document->getDeliveryNumberFormatted($current_id_lang, $order->id_shop)}'
                                ),
                                array(
                                    'old'=>'#{Configuration::get(\'PS_DELIVERY_PREFIX\', $current_id_lang)}{\'%06d\'|sprintf:$document->delivery_number}',
                                    'new'=>'{$document->getDeliveryNumberFormatted($current_id_lang, $order->id_shop)}'
                                )
                    )
            ),
            array(
                'file'=>'_product_line.tpl',
                'changes'=>array(
                                array(
                                    'old'=>'#{Configuration::get(\'PS_INVOICE_PREFIX\', $current_id_lang, null, $order->id_shop)}{\'%06d\'|sprintf:$invoice->number}',
                                    'new'=>'{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)}'
                                ),
                                array(
                                    'old'=>'#{Configuration::get(\'PS_INVOICE_PREFIX\', $current_id_lang)}{\'%06d\'|sprintf:$invoice->number}',
                                    'new'=>'{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)}'
                                )
                    )
            ),
            array(
                'file'=>'order-confirmation.tpl',
                'isfront'=>1,
                'changes'=>array(
                                array(
                                    'old'=>'{$id_order_formatted}',
                                    'new'=>'{$reference_order}'
                                )
                    )
            )
            
        );
        
        
        foreach($tpl_files as $tpl_file){
            $tpl_path = _PS_BO_ALL_THEMES_DIR_.'default'.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'orders'.DIRECTORY_SEPARATOR;
            $file_name = $tpl_file['file'];
            if(isset($tpl_file['isfront']) && $tpl_file['isfront'] == 1) $tpl_path = _PS_THEME_DIR_;
            if (Tools::file_exists_cache($tpl_path.$file_name)) {
                if($install){
                    //backup file
                    copy($tpl_path.$file_name, $tpl_path.$file_name.'.bk');
                    //get content
                    $content = Tools::file_get_contents($tpl_path.$file_name);
                    //change data
                    foreach ($tpl_file['changes'] as $change) {
                        $content = str_replace($change['old'], $change['new'], $content);
                    }
                    //save data
                    file_put_contents($tpl_path.$file_name, $content);
                }
                else{
                    //check backup file exist
                    if (Tools::file_exists_cache($tpl_path.$file_name.'.bk')) {
                        //backup file
                        copy($tpl_path.$file_name, $tpl_path.$file_name.'.bk_'.time());
                        //get backup content
                        $content = Tools::file_get_contents($tpl_path.$file_name.'.bk');
                        //save data
                        file_put_contents($tpl_path.$file_name, $content);
                        //remove backup file
                        @unlink($tpl_path.$file_name.'.bk');
                    }
                }
                // Clear compiled
                Context::getContext()->smarty->clearCompiledTemplate();
            }
        }
        return true;
    }
    public function hookActionObjectOrderAddAfter($params)
    {
        $active_custom_number = (bool)Configuration::get('CUS_ORDER_ACTIVE',null,null,(int)$params['object']->id_shop);
        if($active_custom_number)
            $this->customizeNumber('O',$params['object']);
    }
    public function hookActionOrderStatusPostUpdate($params){
        $id_order = (int)$params['id_order'];
        $orderObj = new Order((int)$id_order);
        if(Validate::isLoadedObject($orderObj)){
            $active_custom_number = (bool)Configuration::get('CUS_ORDER_ACTIVE',null,null,(int)$orderObj->id_shop);
            if($active_custom_number){
                $sql = 'UPDATE `' . _DB_PREFIX_ . 'order_payment` SET order_reference = "'.pSql($orderObj->reference).'" 
                        WHERE id_order_payment IN (
                            SELECT id_order_payment
                            FROM `' . _DB_PREFIX_ . 'order_invoice_payment`
                            WHERE id_order = '.(int)$id_order.'
                        )';
                Db::getInstance()->execute($sql);
            }
        }
    }
    public function customizeNumber($type,$itemObj,$test = false){
        $id_shop = $this->context->shop->id;
        
        
        $customer_group = '';
        $numberconfig = array();
        if(!$test){
            $orderObj = $itemObj;
            if($type == 'I' || $type == 'D') 
                $orderObj = new Order($itemObj->id_order);
                //$orderObj = $itemObj->getOrder(); // mising function from ps 1.6.0.14
            $id_customer = (int)$orderObj->id_customer;
            $id_shop = (int)$orderObj->id_shop;
            // get number config
            $customer_group = (int)Customer::getDefaultGroupId($id_customer);
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` c WHERE FIND_IN_SET('.pSQL($customer_group).',groups) AND c.type="'.pSql($type).'" AND c.id_shop = '.(int)$id_shop;
            $numberconfig =  Db::getInstance()->getRow($sql);
        }else{
            $numberconfig['start'] = Tools::getValue('start');
            $numberconfig['step'] = Tools::getValue('step');
            $numberconfig['current_counter_nbr'] = Tools::getValue('current');
            $assign_group = Tools::getValue('groupBox_'.Tools::getValue('type_rule'));
            $_assign_group = array();
            if(is_array($assign_group))
                $_assign_group = array_map('intval', $assign_group);
            // get first customer group
            if($_assign_group)
                foreach($_assign_group as $_group){$customer_group = (int)$_group;break;}
            else return array();
            $numberconfig['groups'] = implode(',',$_assign_group);
            $numberconfig['resettype'] = Tools::getValue(Tools::getValue('type_rule').'_reset');
            $numberconfig['resetnumber'] = Tools::getValue('resetnumber');
            $numberconfig['resetdate'] = Tools::getValue('resetdate');
            $numberconfig['numberformat'] = Tools::getValue('numberformat');
            $numberconfig['length'] = Tools::getValue('length');
            
            
        }
        $shop_group_id = (int)Shop::getGroupFromShop($id_shop, true);
        
        $config_key = '';
        if($type == 'I'){
            $config_key = 'INVOICE';
        }elseif($type == 'D'){
            $config_key = 'DELIVERY';
        }elseif($type == 'O'){
            $config_key = 'ORDER';
        }
        $active_custom_number = true;
        if(!$test)
            $active_custom_number = (bool)Configuration::get('CUS_'.$config_key.'_ACTIVE',null,null,(int)$id_shop);
        if($numberconfig && isset($numberconfig['numberformat']) && trim($numberconfig['numberformat']) !=''  && $active_custom_number){
            // if have rule
            $start = (int)$numberconfig['start'];if($start < 1) $start = 1;
            $step = (int)$numberconfig['step'];if($step < 1) $step = 1;
            // get last id
            
            $old_reset_date_old = Configuration::get('GAI_RESET_DATE_'.$type.'_'.(int)$customer_group, null, $shop_group_id, $id_shop);
            $old_reset_date = $old_reset_date_old;
            if($old_reset_date == '' || $old_reset_date == '0000-00-00'){
                $old_reset_date = '1970-01-01 00:00:00'; // Unix timestamp
            }
            switch ((int)$numberconfig['resettype']) {
                case '0':
                    $old_reset_date = '1970-01-01 00:00:00'; // Unix timestamp
                    break;
                case '2':
                    // reset every day
                    $day_now = date('Y-m-d');
                    $old_reset_date = $day_now.' 00:00:00';
                    break;
                case '3':
                    // reset every month
                    $month_now = date('Y-m');
                    $old_reset_date = $month_now.'-01'.' 00:00:00';
                    break;
                case '4':
                    // reset every year
                    $year_now = date('Y');
                    $old_reset_date = $year_now.'-01'.'-01'.' 00:00:00';
                    break;
                case '5':
                    // reset by date
                    $time_reset_config = $numberconfig['resetdate'];
                    if($time_reset_config !=null && $time_reset_config !=''){
                        $time_reset = date('Y-m-d H:i:s',strtotime($time_reset_config));
                        $old_reset_date = $time_reset;
                    }else{
                        $old_reset_date = '1970-01-01 00:00:00'; // Unix timestamp
                    }
                    break;
            }
            if(!$test && $old_reset_date_old != $old_reset_date && (int)$numberconfig['resettype'] != 0) {
                Configuration::updateValue('GAI_RESET_DATE_' . $type . '_' . (int)$customer_group, $old_reset_date, false, $shop_group_id, $id_shop);
                $sql = 'UPDATE `'._DB_PREFIX_.'gwaicustomnumber` SET current_counter_nbr=0  WHERE id_gwaicustomnumber = '.(int)$numberconfig['id_gwaicustomnumber'];
                Db::getInstance()->Execute($sql);
                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` c WHERE FIND_IN_SET('.pSQL($customer_group).',groups) AND c.type="'.pSql($type).'" AND c.id_shop = '.(int)$id_shop;
                $numberconfig =  Db::getInstance()->getRow($sql);
            }
            $next_number = $numberconfig['current_counter_nbr'] + $step;
            /*
            $reset = (int)$numberconfig['resettype'];
            $number_reset = 0;
            if($reset == 1){
                if($numberconfig['resetnumber'] > 1)
                    $number_reset = (int)$numberconfig['resetnumber'];
                if($type == 'O'){
                    $next_number = (($total-1)*$step) + $start;
                }else{
                    $next_number = ($total*$step) + $start;
                }
                    
            }else{
                if($type == 'O')
                    $next_number = ($total-1)*$step + $start;
                else
                    $next_number = $total*$step + $start;
            }
            if($next_number < $start || $total <= 0) $next_number = $start;
            if($total<=1 && $type == 'O') $next_number = $start;
            if($reset == 1 && $next_number >= $number_reset && $number_reset > 0)
            {
                $next_number = $start;
                if(!$test)
                    Configuration::updateValue('GAI_RESET_DATE_'.$type.'_'.(int)$customer_group, date('Y-m-d H:i:s'),false, $shop_group_id, $id_shop);
            }
            */
            $reset = (int)$numberconfig['resettype'];
            $number_reset = 0;
            if($reset == 1)
                if($numberconfig['resetnumber'] > 1)
                    $number_reset = (int)$numberconfig['resetnumber'];
            if($next_number < $start || $numberconfig['current_counter_nbr'] <= 0) $next_number = $start;
            //if($numberconfig['current_counter_nbr']<=1 && $type == 'O') $next_number = $start;
            if($reset == 1 && $next_number >= $number_reset && $number_reset > 0)
            {
                $next_number = $start;
                if(!$test) {
                    Configuration::updateValue('GAI_RESET_DATE_' . $type . '_' . (int)$customer_group, date('Y-m-d H:i:s'), false, $shop_group_id, $id_shop);
                }
            }
            if(!$test){
                // update custom number
                if($type == 'O'){
                    $orderObj->reference = $this->formatNumber($type,(int)$next_number,$orderObj);
                    $orderObj->save();
                }elseif($type == 'I'){
                    $itemObj->number = (int)$next_number;
                    $itemObj->save();
                }elseif($type == 'D'){
                    $itemObj->delivery_number = (int)$next_number;
                    $itemObj->save();
                }
                $sql = 'UPDATE `'._DB_PREFIX_.'gwaicustomnumber` SET current_counter_nbr= '.(int)$next_number.' WHERE id_gwaicustomnumber = '.(int)$numberconfig['id_gwaicustomnumber'];
                Db::getInstance()->Execute($sql);
                return true;
            }else {
                $numberconfig['customer_group'] = $customer_group;
                $numberconfig['next_number'] = $next_number;
                return $numberconfig;
            }
        }else return false;
    }
    public function hookActionAdminControllerSetMedia($params)
    {
        $controller = Tools::getValue('controller');
        if ($controller == 'AdminGwaicustomnumber') {
            $this->context->controller->addCss($this->_path.'/views/css/admin/gwadvancedinvoice.css');
            $this->context->controller->addJqueryPlugin('datepicker');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/gwadvancedinvoice.js');
        }elseif ($controller == 'AdminGwadvancedinvoicetemplate') {
            $this->context->controller->addCss($this->_path.'/views/css/admin/gwadvancedinvoice.css');
            $this->context->controller->addJqueryPlugin('colorpicker');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/colResizable-1.5.min.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/gwadvancedinvoice.js');
        }elseif ($controller == 'AdminGwadvancedinvoiceconfig') {
            $this->context->controller->addCss($this->_path.'/views/css/admin/gwadvancedinvoice.css');
            $this->context->controller->addJqueryPlugin('colorpicker');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/colResizable-1.5.min.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/gwadvancedinvoice.js');
        }
    }
    public function getTotal($type,$groups,$old_reset_date,$id_shop){
        $sql = '';
        if($type == 'O'){
            $sql = 'SELECT COUNT(id_order) FROM `' . _DB_PREFIX_ . 'orders` o
                    LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON(o.id_customer = c.id_customer)
                    WHERE  FIND_IN_SET(c.id_default_group,"'.pSql($groups).'") AND o.date_add >="'.pSql($old_reset_date).'" '
                    .' AND o.id_shop = '.(int)$id_shop;
        }else{
            $number_column = 'number';
            if($type == 'D') $number_column = 'delivery_number';
            $sql = 'SELECT COUNT(id_order_invoice) FROM `' . _DB_PREFIX_ . 'order_invoice` oi
                    INNER JOIN ' . _DB_PREFIX_ . 'orders o ON(o.id_order = oi.id_order)
                    LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON(o.id_customer = c.id_customer) 
                    WHERE  FIND_IN_SET(c.id_default_group,"'.pSql($groups).'") 
                    AND (oi.'.pSql($number_column).' <> 0 AND TRIM(IFNULL(oi.'.pSql($number_column).',"")) <> "")
                    AND oi.date_add >="'.pSql($old_reset_date).'" '
                    .' AND o.id_shop = '.(int)$id_shop;
            
        }     
        return (int)Db::getInstance()->getValue($sql);
    }
    public function getInvoiceId($id_order,$number){
        $sql = 'SELECT id_order_invoice FROM  `' . _DB_PREFIX_ . 'order_invoice` WHERE id_order ='.(int)$id_order.' AND number = '.(int)$number;
        return (int)Db::getInstance()->getValue($sql);
    }
    public function formatNumber($type,$number,$itemObj,$test=false,$testdata = array()){
        $date_add = date('Y-m-d');
        $id_lang = (int)$this->context->language->id;
        $id_shop = (int)$this->context->shop->id;
        $customer_group = 0;
        $numberconfig = array();
        if($test){
            $numberconfig = $testdata;
            $customer_group = (int)$numberconfig['customer_group'];
        }else{
            $date_add = $itemObj->date_add;
            $orderObj = $itemObj;
            if($type == 'I' || $type == 'D'){
                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
                    $orderObj = $itemObj->getOrder();
                }else{
                    $orderObj = new Order($itemObj->id_order);
                }
            }
            $id_customer = (int)$orderObj->id_customer;
            // get number config
            $customer_group = (int)Customer::getDefaultGroupId($id_customer);
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` c WHERE FIND_IN_SET('.pSQL($customer_group).',groups) AND c.type="'.pSql($type).'" AND c.id_shop = '.(int)$orderObj->id_shop;
            $numberconfig =  Db::getInstance()->getRow($sql);
            $id_shop = (int)$orderObj->id_shop;
            $id_lang = (int)$orderObj->id_lang;
        }
        $config_key = '';
        if($type == 'I'){
            $config_key = 'INVOICE';
        }elseif($type == 'D'){
            $config_key = 'DELIVERY';
        }elseif($type == 'O'){
            $config_key = 'ORDER';
        }
        $active_custom_number = true;
        if(!$test)
            $active_custom_number = (bool)Configuration::get('CUS_'.$config_key.'_ACTIVE',null,null,(int)$id_shop);
        if($numberconfig && $active_custom_number){ 
            // get fomart
            $number_format = $numberconfig['numberformat'];
            $length = (int)$numberconfig['length'];if($length < 2) $length = 2;
            $matches = array();
            preg_match_all('/\{(.*?)\}/', $number_format, $matches);
            if(isset($matches[0]) && $matches[0]){
                foreach($matches[0] as $shortcode)
                {
                    $data_shortcode = '';
                    switch ($shortcode) {
                        case '{dd}':
                        case '{DD}':
                            $data_shortcode = date('d',strtotime($date_add));
                            break;
                        case '{d}':
                        case '{D}':
                            $data_shortcode = date('j',strtotime($date_add));
                            break;
                        case '{mm}':
                        case '{MM}':
                            $data_shortcode = date('m',strtotime($date_add));
                            break;
                        case '{m}':
                        case '{M}':
                            $data_shortcode = date('n',strtotime($date_add));
                            break;
                        case '{yy}':
                        case '{YY}':
                            $data_shortcode = date('Y',strtotime($date_add));
                            break;
                        case '{y}':
                        case '{Y}':
                            $data_shortcode = date('y',strtotime($date_add));
                            break;
                        case '{id_customer}':
                        case '{ID_CUSTOMER}':
                            if($test) $data_shortcode = 1; else $data_shortcode = (int)$id_customer;
                            break;
                        case '{id_group}':
                        case '{ID_GROUP}':
                            $data_shortcode = (int)$customer_group;
                            break;
                        case '{id_shop}':
                        case '{ID_SHOP}':
                            $data_shortcode = (int)$id_shop;
                            break;
                        case '{group}':
                        case '{GROUP}':
                            $groupObj = new Group((int)$customer_group,(int)$id_lang,(int)$id_shop);
                            $data_shortcode = Tools::strtoupper($groupObj->name);
                            break;
                        case '{counter}':
                        case '{COUNTER}':
                            $data_shortcode = str_pad($number,$length,0,STR_PAD_LEFT);
                            break;
                        case '{id_order}':
                        case '{ID_ORDER}':
                            if(!$test) 
                                $data_shortcode = $orderObj->id;
                            else{
                                //get next id_order
                                $sql = 'SELECT MAX(id_order)+1 FROM '._DB_PREFIX_.'orders';
                                $data_shortcode = (int)Db::getInstance()->getValue($sql);
                            }
                            break;
                        case '{order_reference}':
                        case '{ORDER_REFERENCE}':
                            if(!$test){ 
                                if($type == 'I' || $type == 'D')
                                    $data_shortcode = $orderObj->reference;
                            }else{
                                $data_shortcode = Order::generateReference();
                            }
                            break;
                        case '{id_invoice}':
                        case '{ID_INVOICE}':
                            if(!$test){ 
                                if($type == 'I' || $type == 'D')
                                    $data_shortcode = $itemObj->id;
                            }else{
                                // get next id_invoice
                                $sql = 'SELECT MAX(id_order_invoice)+1 FROM '._DB_PREFIX_.'order_invoice';
                                $data_shortcode = (int)Db::getInstance()->getValue($sql);
                            }
                            break;
                        case '{random}':
                        case '{RANDOM}':
                            if($test){
                                $data_shortcode = Order::generateReference();
                            }else{
                                if (version_compare($this->version, '1.2.0', '>')){
                                    // add to gwaicnrandom table
                                    $sql = 'SELECT random FROM `'._DB_PREFIX_.'gwaicnrandom`
                                        WHERE type="'.pSql($type).'" AND id_object = '.(($type == 'I' || $type == 'D') ? (int)$itemObj->id : (int)$orderObj->id).' AND id_shop = '.(int)$id_shop.'
                                    ';
                                    $random_number = Db::getInstance()->getValue($sql);
                                    if($random_number)
                                        $data_shortcode = $random_number;
                                    else{
                                        $data_shortcode = Order::generateReference();
                                        $data_shortcode2 = (int)rand(0,999999);
                                        $sql = 'INSERT INTO `'._DB_PREFIX_.'gwaicnrandom`(type,id_object,id_shop,random_number,random)
                                            VALUES("'.pSQL($type).'",'.(int)(($type == 'I' || $type == 'D') ? (int)$itemObj->id : (int)$orderObj->id).','.(int)$id_shop.','.(int)$data_shortcode2.',"'.pSQL($data_shortcode).'")';
                                        Db::getInstance()->execute($sql);
                                    }
                                }else{
                                    $data_shortcode = Order::generateReference();
                                }
                            }
                            break;
                        case '{random_number}':
                        case '{RANDOM_NUMBER}':
                            if($test){
                                $data_shortcode = (int)rand(0,999999);
                            }else{
                                if (version_compare($this->version, '1.2.0', '>')){
                                    // add to gwaicnrandom table
                                    $sql = 'SELECT random_number FROM `'._DB_PREFIX_.'gwaicnrandom`
                                        WHERE type="'.pSql($type).'" AND id_object = '.(($type == 'I' || $type == 'D') ? (int)$itemObj->id : (int)$orderObj->id).' AND id_shop = '.(int)$id_shop.'
                                    ';
                                    $random_number = Db::getInstance()->getValue($sql);
                                    if($random_number)
                                        $data_shortcode = (int)$random_number;
                                    else{
                                        $data_shortcode = (int)rand(0,999999);
                                        $data_shortcode2 = Order::generateReference();
                                        $sql = 'INSERT INTO `'._DB_PREFIX_.'gwaicnrandom`(type,id_object,id_shop,random_number,random)
                                            VALUES("'.pSQL($type).'",'.(int)(($type == 'I' || $type == 'D') ? (int)$itemObj->id : (int)$orderObj->id).','.(int)$id_shop.','.(int)$data_shortcode.',"'.pSQL($data_shortcode2).'")';
                                        Db::getInstance()->execute($sql);
                                    }
                                }else{
                                    $data_shortcode = (int)rand(0,999999);
                                }
                            }
                            break;
                            
                    }
                    $number_format = str_replace($shortcode,$data_shortcode,$number_format);
                }
            }
            return $number_format;
        }else{
            // return default
            return Configuration::get('PS_' . $config_key . '_PREFIX', $id_lang, null, $id_shop)
                . sprintf('%06d', (int)$number);
        }
        
    }
    private function _installFonts(){
        $fonts_dir = _PS_MODULE_DIR_.$this->name.'/views/fonts';
        if(Tools::file_exists_no_cache($fonts_dir)){
            $fonts = scandir($fonts_dir);
            foreach($fonts as $font){
                if (!in_array($font, array('.', '..', '.svn', '.git', '__MACOSX'))){
                    if (Tools::substr($font, -4) == '.ttf'){
                        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
                            TCPDF_FONTS::addTTFfont($fonts_dir.'/'.$font, 'TrueTypeUnicode', '', 96);
                        }else {
                            $pdf_render = new PDFGenerator((bool)Configuration::get('PS_PDF_USE_CACHE'));
                            $pdf_render->addTTFfont($fonts_dir.'/'.$font, 'TrueTypeUnicode', '', 96);
                        }
                    }
                }
            }
        }
        return true;
    }
    private function _deleteTables()
    {
        $_allgroup = Group::getGroups((int)$this->context->language->id,(int)$this->context->shop->id);
        if($_allgroup)
            foreach($_allgroup as $group){
                Configuration::deleteByName('GAI_RESET_DATE_O_'.(int)$group['id_group']);
                Configuration::deleteByName('GAI_RESET_DATE_I_'.(int)$group['id_group']);
                Configuration::deleteByName('GAI_RESET_DATE_D_'.(int)$group['id_group']);
            }
        return (bool)Db::getInstance()->execute('
                DROP TABLE IF EXISTS    `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate`, 
                                        `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate_lang`, 
                                        `' . _DB_PREFIX_ . 'gwadvancedinvoicetemplate_shop`,
                                        `' . _DB_PREFIX_ . 'gwaicustomnumber`,
                                        `'._DB_PREFIX_.'gwaicnrandom`;
        ');
    }
    private function _createTab()
    {
        $res = true;
        $tabparent = "AdminGwadvancedinvoice";
        $id_parent = Tab::getIdFromClassName($tabparent);
        if(!$id_parent){
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "AdminGwadvancedinvoice";
            $tab->name = array();
            foreach (Language::getLanguages() as $lang){
                $tab->name[$lang["id_lang"]] = $this->l('Advanced Invoice');
            }
            $tab->id_parent = 0;
            $tab->module = $this->name;
            $res &= $tab->add();
            $id_parent = $tab->id;
        }
        $subtabs = array(
            array(
                'class'=>'AdminGwadvancedinvoiceconfig',
                'name'=>'General Settings'
            ),
            array(
                'class'=>'AdminGwadvancedinvoicetemplate',
                'name'=>'Manage Templates'
            ),
            array(
                'class'=>'AdminGwaicustomnumber',
                'name'=>'Custom number'
            ),
            array(
                'class'=>'AdminGwadvancedinvoiceabout',
                'name'=>'List Variables'
            ),
        );
        foreach($subtabs as $subtab){
            $idtab = Tab::getIdFromClassName($subtab['class']);
            if(!$idtab){
                $tab = new Tab();
                $tab->active = 1;
                $tab->class_name = $subtab['class'];
                $tab->name = array();
                foreach (Language::getLanguages() as $lang){
                    $tab->name[$lang["id_lang"]] = $subtab['name'];
                }
                $tab->id_parent = $id_parent;
                $tab->module = $this->name;
                $res &= $tab->add();
            }
        }
        return $res;
    }
    private function _deleteTab()
    {
        $id_tabs = array("AdminGwadvancedinvoiceconfig","AdminGwadvancedinvoicetemplate","AdminGwadvancedinvoiceabout","AdminGwaicustomnumber");
        foreach($id_tabs as $id_tab){
            $idtab = Tab::getIdFromClassName($id_tab);
            $tab = new Tab((int)$idtab);
            $parentTabID = $tab->id_parent;
            $tab->delete();
            $tabCount = Tab::getNbTabs((int)$parentTabID);
            if ($tabCount == 0){
                $parentTab = new Tab((int)$parentTabID);
                $parentTab->delete();
            }
        }
        return true;
    }
    public function hookAjaxCall($params){
        $res = array();
        Context::getContext()->smarty->assign('templates',$this->getBaseTemplateConfig($params['template'],$params['pagesize']));
        $res['templates'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/hook/templates.tpl');
        $res = Tools::jsonEncode($res);
		return $res;
    }
    public function hookAllSize()
    {
        $toal_pagesize = array('A4','A5','A6','A7','usletter');
        $allresult = array();
        foreach ($toal_pagesize as $pagedt) {
            $res = array();
            Context::getContext()->smarty->assign('templates',$this->getBaseTemplateConfig('',$pagedt));
            $res = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/hook/templates.tpl');
            $allresult[$pagedt] = $res;
        }
        return $allresult;
    }
    public function hookAjaxCallStyle($params){
        $choose_design = $params['choose_design'];
        $id_language = (int)Context::getContext()->language->id;
        if(isset($params['id_language']) && $params['id_language'] > 0)
            $id_language =  (int)$params['id_language'];
        $file = '';
        $style = '';
        $temp = str_replace('-','/',$choose_design);
        if($temp !='' && is_string($temp)){
            $language = new Language($id_language);
            if(Tools::file_exists_no_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/'.$language->iso_code.'/styles.tpl')){
                $file = _PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/'.$language->iso_code.'/styles.tpl';
            }elseif(Tools::file_exists_no_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/en/styles.tpl')){
                $file = _PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/en/styles.tpl';
            }
            if($file !=''){
                Context::getContext()->smarty->assign($params['template_config']);
                $style = Context::getContext()->smarty->fetch($file);
            }  
        }
        die(strip_tags($style));
    }
    public function getContent()
	{
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminGwadvancedinvoiceconfig'));
	}   
    public function getBaseTemplateConfig($temp = '',$pagesize=''){
        $templates = array();
        $template = null;
        $fontsdir = opendir(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/');
		while (($file = readdir($fontsdir)) !== false) {
		    if (!in_array($file, array('.', '..', '.svn', '.git', '__MACOSX')) && is_dir(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$file)){
                if(Tools::file_exists_no_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$file.'/config.php')){
                    include_once(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$file.'/config.php');
                    //$template = $file::getTemplate();
                    //fix error in php version 5.2
                    $template = call_user_func_array(array($file, 'getTemplate'), array());
                    if($template)
                        $templates[$template['id']] = $template;
                    
                }
		    }
		}
        if($temp !='' && isset($templates["$temp"])){
            return $templates["$temp"];
        }else{
            if($pagesize !=''){
                $results = array();
                foreach($templates as $key=>$template){
                    if(in_array($pagesize,$template['pagesize'])){
                        $results[$key] = $template;
                    }
                }
                return $results;
            }
            else
                return array();
        }
        closedir($fontsdir);
    }
    public function importGTemplate($zipfile = ''){
        $result = array();
        $filepath = _PS_MODULE_DIR_ . 'gwadvancedinvoice/exports/';
        if($zipfile =='' || !file_exists($filepath.$zipfile)){
            $result = array('error'=>1,'warrning'=>$this->l('Zip file does not exist')); 
        }else{
            /*remove old file before archive */
            if(file_exists($filepath.'extracts/gwadvancedinvoice.xml'))
                @unlink($filepath.'extracts/gwadvancedinvoice.xml');
            /* unzip file */
            $zip = new ZipArchive();
            if($zip->open($filepath.$zipfile))
            {
                /* check file data */
                if ($zip->locateName('gwadvancedinvoice.xml') === false){
                    $result = array('error'=>1,'warrning'=> $this->l('Zip file is invalid'));
                }else{
                    /* Extract */
                    $extracts_folder = true;
                    if (!file_exists($filepath.'extracts/')){
                        if (!mkdir($filepath.'extracts/', 0777, true)) {
                            if (!mkdir($filepath.'extracts/', 0755, true)) {
                                $result = array('error'=>1,'warrning'=> $this->l('Cannot extract zip file'));
                                $extracts_folder = false;
                            }
                        }
                    }
                    if ($extracts_folder){
                        if(!Tools::ZipExtract($filepath.$zipfile, $filepath.'extracts/')){
                            $result = array('error'=>1,'warrning'=> $this->l('Cannot extract zip file'));
                        }else{
                            if(Tools::getValue('delete_old_template')){
                                $alltemplate = gwadvancedinvoicetemplateModel::getAllBlock();
                                if($alltemplate){
                                    foreach($alltemplate as $template){
                                        $templateObj = new gwadvancedinvoicetemplateModel((int)$template['id_gwadvancedinvoicetemplate']);
                                        if(Validate::isLoadedObject($templateObj))
                                            $templateObj->delete();
                                    }
                                }
                            }
                            $langs = Language::getLanguages();
                            $langs_id = array();
                            if($langs)
                                foreach($langs as $lang){
                                    $langs_id[$lang['iso_code']] = (int)$lang['id_lang'];
                                }
                            $definition_template = ObjectModel::getDefinition('gwadvancedinvoicetemplateModel');
                            /* read xml file */
                            $datas = @simplexml_load_file($filepath.'/extracts/gwadvancedinvoice.xml','SimpleXMLElement', LIBXML_NOCDATA);
                            if($datas->gtemplate){
                                $shops = Shop::getShops(true,null,true);
                                if(!is_array($shops) || (is_array($shops) && empty($shops))){
                                    $shops = array((int)$this->context->shop->id);
                                }
                                foreach($datas->gtemplate as $gtemplate){
                                    $newObj = new gwadvancedinvoicetemplateModel();
                                    if(Tools::getValue('override_old_template')){
                                        $newObj = new gwadvancedinvoicetemplateModel((int)$gtemplate['id']);
                                        if(Validate::isLoadedObject($newObj)){
                                            $newObj = $this->emptyObjData($newObj,$definition_template);
                                        }
                                    }
                                    $default_datas = array();
                                    foreach($gtemplate as $field_key=>$_data)
                                    {
                                        if( $field_key !='datafields')
                                        {
                                            if(isset($definition_template['fields']) && isset($definition_template['fields'][$field_key]))
                                            {
                                                $lang = '';
                                                if(isset($_data['lang'])){
                                                    $lang = (string)$_data['lang'];
                                                    if($lang !='' && isset($langs_id[$lang])){
                                                        $newObj->{$field_key}[(int)$langs_id[$lang]] = (string)$_data;
                                                    }
                                                    if(!isset($default_datas[$field_key]) || $default_datas[$field_key] == '') $default_datas[$field_key] = (string)$_data;
                                                }else{
                                                    $newObj->{$field_key} = (string)$_data;
                                                }
                                            }
                                        }
                                    }
                                    $newObj = $this->fixEmptyLangField($newObj,$definition_template['fields'],$default_datas);
                                    $layout = time().rand(1,999999999);
                                    $newObj->layout = $layout;
                                    
                                    
                                    /* remove old link shortcode */
                                    $base_url = Tools::getHttpHost(true).__PS_BASE_URI__;
                                    foreach($langs as $lang){
                                        $newObj->invoice[(int)$lang['id_lang']] = str_replace(
                                                array('{$shop_base_url}','%7B$shop_base_url%7D','%7B','%7D'),
                        						array($base_url,$base_url,'{','}'),
                        						$newObj->invoice[(int)$lang['id_lang']]);
                                    }
                                    if($newObj->save()){
                                        /* import img */
                                        if(file_exists($filepath.'extracts/img/img/cms'))
                                            Gwadvancedinvoice::importTemplateImage($filepath.'extracts/img/cms',_PS_IMG_.'cms');
                                        if(file_exists($filepath.'extracts/img/modules/gwadvancedinvoice'))
                                            Gwadvancedinvoice::importTemplateImage($filepath.'extracts/img/modules/gwadvancedinvoice',_PS_MODULE_DIR_.'gwadvancedinvoice');
                                        $temp = $newObj->choose_design;
                                        /* create tpl template */
                                        foreach($langs as $lang){
                                            // fix utf8 title error when json_encode
                                            $productcolumns = Tools::jsonDecode($newObj->productcolumns[$lang['id_lang']]);
                                		    if(isset($productcolumns->title))
                                                foreach($productcolumns->title as &$title)
                                                    $title = html_entity_decode($title);
                                            // #fix utf8 title error when json_encode
                                            $styles = '';
                                            if(Tools::file_exists_no_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/'.$lang['iso_code'].'/styles.tpl')){
                                                $styles = Tools::file_get_contents(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/'.$lang['iso_code'].'/styles.tpl');
                                            }elseif(Tools::file_exists_no_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/en/styles.tpl')){
                                                $styles = Tools::file_get_contents(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/tpltemplates/base/'.$temp.'/en/styles.tpl');
                                            }
                                            foreach($shops as $shop){
                                                $discounttype = $newObj->discountval;
                                                if($discounttype != 'exclude') $discounttype = 'include';
                                                gwadvancedinvoicetemplateModel::writeTemplate(
                                                    'styles.tpl',
                                                    '../modules/'.$this->name.'/views/templates/admin/tpltemplates/customize/'.$shop.'/'.$layout.'/'.$lang['iso_code'],
                                                    $styles);
                                                gwadvancedinvoicetemplateModel::writeTemplate(
                                                    'product_list.tpl',
                                                    '../modules/'.$this->name.'/views/templates/admin/tpltemplates/customize/'.$shop.'/'.$layout.'/'.$lang['iso_code'],
                                                    gwadvancedinvoicetemplateModel::getProductListTpl(
                                                        (isset($productcolumns->widthtitle) ? $productcolumns->widthtitle : array()),
                                                        (isset($productcolumns->title) ? $productcolumns->title : array()),
                                                        (isset($productcolumns->content) ? $productcolumns->content : array()),
                                                        (isset($productcolumns->align) ? $productcolumns->align : array()),
                                                        $temp,
                                                        $lang['iso_code'],
                                                        $discounttype
                                                    )
                                                );
                                                gwadvancedinvoicetemplateModel::writeTemplate(
                                                    'template.tpl',
                                                    '../modules/'.$this->name.'/views/templates/admin/tpltemplates/customize/'.$shop.'/'.$layout.'/'.$lang['iso_code'],
                                                    str_replace('{displayPrice:$','{displayPrice currency=$order->id_currency price=$',(isset($newObj->invoice[$lang['id_lang']]) ? $newObj->invoice[$lang['id_lang']] : ''))
                                                );
                                                $header_content = '';
                                                if(isset($newObj->header[$lang['id_lang']])) $header_content = $newObj->header[$lang['id_lang']];
                                                if($newObj->activeheader){
                                                    gwadvancedinvoicetemplateModel::writeTemplate(
                                                        'header.tpl',
                                                        '../modules/'.$this->name.'/views/templates/admin/tpltemplates/customize/'.$shop.'/'.$layout.'/'.$lang['iso_code'],
                                                        $header_content
                                                    );
                                                }
                                                $footer_content = '';
                                                if(isset($newObj->footer[$lang['id_lang']])) $footer_content = $newObj->footer[$lang['id_lang']];
                                                if($newObj->activefooter){
                                                    gwadvancedinvoicetemplateModel::writeTemplate(
                                                        'footer.tpl',
                                                        '../modules/'.$this->name.'/views/templates/admin/tpltemplates/customize/'.$shop.'/'.$layout.'/'.$lang['iso_code'],
                                                        $footer_content
                                                    );
                                                }
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            $result = array('error'=>0,'warrning'=> '');
                        }
                    }
                }
            }else{
               $result = array('error'=>1,'warrning'=> $this->l('Can not open zip file.')); 
            }
            /* remove zip file */
            @unlink($filepath.$zipfile);
            /* remove extract file */
            gwadvancedinvoicetemplateModel::delTree($filepath.'extracts/');
            
        }
    }
    public static function importTemplateImage($dir = '',$destination=''){
        if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
				if ($object != '.' && $object != '..' && $object !='')
				{
					if (filetype($dir.'/'.$object) == 'dir'){
					   if(!Tools::file_exists_no_cache($destination.'/'.$object)){
                            if (!mkdir($destination.'/'.$object, 0777, true)) {
                                if (!mkdir($destination.'/'.$object, 0755, true)) {
                                    return false;
                                }
                            }
                        }
						self::importTemplateImage($dir.'/'.$object,$destination.'/'.$object);
					}else{
					   $object = basename($object);
					   if(!Tools::file_exists_no_cache($destination.'/'.$object)){
					       /* check img type */
                           $type = Tools::strtolower(Tools::substr(strrchr(basename($object), '.'), 1));
                           if(in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
					           Tools::copy($dir.'/'.$object,$destination.'/'.$object);
					   }
					       
					}
						
				}
			reset($objects);
		}
    }
    public function downloadExportTemplate($gid_templates = ''){
        /* Download zip file */
        $xmldatas = array();$files = array();
        $tpl = _PS_MODULE_DIR_.'gwadvancedinvoice/views/templates/admin/extrahtml.tpl';
        $gtemplates = array();
        $gtemplateIDs = array_map('intval', explode(',', $gid_templates));
        $definition = ObjectModel::getDefinition('gwadvancedinvoicetemplateModel');
        $fields = $definition['fields'];
        $langs = Language::getLanguages();
        $langs_iso = array();
        if($langs)
            foreach($langs as $lang){
                $langs_iso[(int)$lang['id_lang']] = $lang['iso_code'];
            }
        if($gtemplateIDs){
            foreach($gtemplateIDs as $gtemplateID){
                if($gtemplateID > 0){
                    $templateObj = new gwadvancedinvoicetemplateModel((int)$gtemplateID);
                    if(Validate::isLoadedObject($templateObj)){
                        if(isset($templateObj->watermark) && $templateObj->watermark){
                            if(is_array($templateObj->watermark)){
                                foreach($templateObj->watermark as $watermark){
                                    if($watermark !='' && $watermark !='#'){
                                        $type = Tools::strtolower(Tools::substr(strrchr($watermark, '.'), 1));
                                        if(in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                                            $files[$watermark]  = 'modules/gwadvancedinvoice/views/img/watermark/'.$watermark;
                                    }
                                        
                                }
                            }else{
                                if($templateObj->watermark !='' && $templateObj->watermark !='#'){
                                    $type = Tools::strtolower(Tools::substr(strrchr($templateObj->watermark, '.'), 1));
                                        if(in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                                            $files[$templateObj->watermark]  = 'modules/gwadvancedinvoice/views/img/watermark/'.$templateObj->watermark;
                                }
                                    
                            }
                        }
                        $base_url = Tools::getHttpHost().__PS_BASE_URI__;
                        if($langs){
                            foreach($langs as $lang){
                                /* get all image */
                                preg_match_all("/(<img[^>]*src *= *[\"'](http|https)\:\/\/".preg_quote($base_url,'/')."?)([^\"']*)/i",$templateObj->invoice[(int)$lang['id_lang']], $matches); 
                                if(isset($matches[3]) && $matches[3]){
                                    if(is_array($matches[3])){
                                        foreach($matches[3] as $_img){
                                            if($_img !='' && $_img !='#')
                                            {
                                                $type = Tools::strtolower(Tools::substr(strrchr($_img, '.'), 1));
                                                if(in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                                                    $files[$_img] = $_img;
                                            }
                                        }
                                    }else{
                                        if($matches[3] !='' && $matches[3] !='#'){
                                            $type = Tools::strtolower(Tools::substr(strrchr($matches[3], '.'), 1));
                                            if(in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                                                $files[$matches[3]] = $matches[3];
                                        } 
                                    }
                                }
                                /* remove old link */
                                $templateObj->invoice[(int)$lang['id_lang']] = str_replace(
                        						array('http:/'.'/'.$base_url,'https:/'.'/'.$base_url),
                        						'{$shop_base_url}',
                        						$templateObj->invoice[(int)$lang['id_lang']]);
                            }
                        }
                        $gtemplates[(int)$templateObj->id] = (array)$templateObj;
                    }
                }
            }
        }
        Context::getContext()->smarty->assign(
            array(
                'exportTemplateToXml'=>1,
                'gtemplates'=>$gtemplates,
                'fields'=>$fields,
                'langs_iso'=>$langs_iso
            )
        );
        $xmldata = trim(Context::getContext()->smarty->fetch($tpl));
        $xmldatas = array(
            array('filename'=>'gwadvancedinvoice.xml','data'=>$xmldata) 
        ); 
        $filename = 'gwadvancedinvoice_'.date('d-m-Y H_i_s');
        $filepath = _PS_MODULE_DIR_ . 'gwadvancedinvoice/exports/';
        $zip = new ZipArchive();
        if ($zip->open($filepath.$filename.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
            if($xmldatas)
                foreach($xmldatas as $xmldata)
                    $zip->addFromString($xmldata['filename'], $xmldata['data']);
            if($files)
            {
                foreach($files as $file){
                    if($file !='' && file_exists(_PS_ROOT_DIR_.'/'.$file))
                        $zip->addFile(_PS_ROOT_DIR_ .'/'.$file,'img/'.$file);
                }
            }
            $zip->close();
            if (ob_get_length() > 0) ob_end_clean();
            ob_start();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$filename.'.zip'."\"");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($filepath.$filename.'.zip'));
            ob_end_flush();
            @readfile($filepath.$filename.'.zip');
            @unlink($filepath.$filename.'.zip');
        }else{
            die($this->l('Failed to create archive'));
        }
        die();
    }
    public function emptyObjData($obj,$fields){
        $langs = Language::getLanguages();
        if(isset($fields['fields']))
            foreach($fields['fields'] as $field_key=>$field){
                if($field_key !='id' && $field_key != $fields['primary'])
                    if(isset($field['lang']) && $field['lang'] == 1){
                        if(!isset($obj->{$field_key})) $obj->{$field_key} = array();
                        foreach($langs as $lang){
                            $obj->{$field_key}[(int)$lang['id_lang']] = '';
                        }
                    }else $obj->{$field_key} = '';
            }
        return $obj;
   }
    /* Fix empty lang field */
   public function fixEmptyLangField($obj,$fields,$default_datas){
        $langs = Language::getLanguages(false);
        if($fields)
        foreach($fields as $field_key=>$field){
            if(isset($field['lang']) && $field['lang'] == 1){
                if(!isset($obj->{$field_key})) $obj->{$field_key} = array();
                if(isset($default_datas[$field_key]))
                    foreach($langs as $lang){
                        if(
                            !isset($obj->{$field_key}[(int)$lang['id_lang']]) || $obj->{$field_key}[(int)$lang['id_lang']] == '' || $obj->{$field_key}[(int)$lang['id_lang']] == null
                            
                        )
                        {
                            $obj->{$field_key}[(int)$lang['id_lang']] = $default_datas[$field_key];
                        }
                    }
            }
        }
        return $obj;
   }
 }
?>