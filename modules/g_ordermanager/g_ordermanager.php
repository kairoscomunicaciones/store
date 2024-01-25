<?php

/**

* This is main class of module.

*

* @author    Globo Jsc <contact@globosoftware.net>

* @copyright 2017 Globo., Jsc

* @license   please read license in file license.txt

* @link	     http://www.globosoftware.net/

*/



if (!defined('_PS_VERSION_'))

	exit;

class G_ordermanager extends Module

{

	public function __construct()

	{

		$this->name = 'g_ordermanager';

		$this->tab = 'administration';

		$this->version = '2.1.2';

		$this->author = 'Globo Jsc';

		$this->need_instance = 0;

		$this->bootstrap = true;

        $this->module_key = 'b878c33d7b1ecd63eb386a596729e0ac';

		parent::__construct();

		$this->displayName = $this->l('Advanced Order Management');

		$this->description = $this->l('The module is designed for better admin experience. Save your time by performing order actions in bulk. Moreover, it also allows you to edit & duplicate an existing order.');

        if (version_compare(_PS_VERSION_, '1.6.0.0 ', '>='))

            $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);

        else    $this->ps_versions_compliancy = array('min' => '1.5.0.0', 'max' => '1.5.99.99'); /** Fix bug install in ps v1.5 */

    }

    public function install()

	{

		if (!parent::install() ||

            !$this->registerHook('displayHeader') ||

            !$this->registerHook('displayOrderproductInlist') ||

            !$this->registerHook('displayBackOfficeFooter') ||

            !$this->registerHook('OrderChangeShipping') ||

            !$this->registerHook('displayBackOfficeHeader') ||

            !$this->registerHook('orderProuductLideEdit') ||

            !$this->registerHook('actionObjectOrderDeleteAfter') || 

            !$this->registerHook('actionAdminControllerSetMedia') ||

            !$this->setupConfigurationData() ||

            !$this->_createTab()



        )

			return false;

		return true;

	}

	public function uninstall()

	{

		if (!parent::uninstall() || !$this->uninstallConfigurationData() || !$this->_deleteTab())

			return false;

		return true;

	}

    private function _createTab()

    {

        $tabparent = "AdminParentOrders";

        $id_parent = Tab::getIdFromClassName($tabparent);

        $tab = new Tab();

        $tab->active = 1;

        $tab->class_name = "AdminOrdermanager";

        $tab->name = array();

        foreach (Language::getLanguages() as $lang){

            $tab->name[$lang["id_lang"]] = $this->l('Order management');

        }

        $tab->id_parent = $id_parent;

        $tab->module = $this->name;

        return $tab->add();

    }

    private function _deleteTab()

    {

        $idtab = Tab::getIdFromClassName('AdminOrdermanager');

        if($idtab > 0){

            $tab = new Tab((int)$idtab);

            $tab->delete();

        }

        return true;

    }

    public function setupConfigurationData()

    {

        $G_ORDER_FIELDS = array(

            'id_order'=>'on',

            'reference'=>'on',

            'new'=>'on',

            'cname'=>'on',

            'customer'=>'on',

            'total_paid_tax_incl'=>'on',

            'osname'=>'on',

            'payment'=>'on',

            'date_add'=>'on',

            'id_pdf'=>'on'

        );

        $G_ORDER_FIELDS_ALIGN = array(

            'id_order'=>'text-center',

            'reference'=>'text-center',

            'customer'=>'text-center',

            'osname'=>'text-center',

            'payment'=>'text-center',

            'cname'=>'text-center',

            'total_paid_tax_incl'=>'text-center',

            'id_pdf'=>'text-center',

            'date_add'=>'text-center'

        );

        $res = Configuration::updateGlobalValue('G_ORDER_FIELDS_POSITION',Tools::jsonEncode($G_ORDER_FIELDS));

        $res &= Configuration::updateGlobalValue('G_ORDER_FIELDS_ALIGN',Tools::jsonEncode($G_ORDER_FIELDS_ALIGN));

        $res &= Configuration::updateGlobalValue('G_ORDER_FIELDS',Tools::jsonEncode($G_ORDER_FIELDS));

        unset($G_ORDER_FIELDS['id_pdf']);

        $res &= Configuration::updateGlobalValue('G_CSV_ORDER_FIELDS',Tools::jsonEncode($G_ORDER_FIELDS));

        $res &= Configuration::updateGlobalValue('G_CSV_ORDER_FIELDS_POSITION',Tools::jsonEncode($G_ORDER_FIELDS));

        $res &= Configuration::updateGlobalValue('G_CSV_SEPARATOR',';');

        $res &= Configuration::updateGlobalValue('G_CSV_MULTI_SEPARATOR',',');

        $res &= (bool)Db::getInstance()->execute('

			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gordernotes` (

                `id_gordernotes` int(10) unsigned NOT NULL AUTO_INCREMENT,

				`id_order` int(10) unsigned NOT NULL,

                `notes` text NOT NULL,

                `showinpdf` tinyint(1) unsigned NULL DEFAULT \'0\',

                `date_add` datetime DEFAULT NULL,

				PRIMARY KEY (`id_gordernotes`)

			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;

		');





        return $res;

    }

    public function uninstallConfigurationData()

    {

        $res = Configuration::deleteByName('G_ORDER_FIELDS_POSITION');

        $res &= Configuration::deleteByName('G_ORDER_FIELDS_ALIGN');

        $res &= Configuration::deleteByName('G_ORDER_FIELDS');

        $res &= Configuration::deleteByName('G_CSV_ORDER_FIELDS');

        $res &= Configuration::deleteByName('G_CSV_ORDER_FIELDS_POSITION');

        $res &= Configuration::deleteByName('G_CSV_SEPARATOR');

        $res &= Configuration::deleteByName('G_CSV_MULTI_SEPARATOR');

        $res &= (bool)Db::getInstance()->execute('DROP TABLE IF EXISTS    `' . _DB_PREFIX_ . 'gordernotes`');

        return $res;

    }

    public function getContent()

    {

        $html = '';

        if (Tools::isSubmit('SubmitOrderFields'))

        {

            $order_fields_position = Tools::getValue('order_fields_position');

            $order_fields_align = Tools::getValue('order_fields_align');

            $order_fields = Tools::getValue('order_fields');

            if(!is_array($order_fields)) $order_fields = array();

            Configuration::updateGlobalValue('G_ORDER_ACTION_FISRT',(int)Tools::getValue('orderactioninfisrtcolumn'));

            Configuration::updateGlobalValue('G_ORDER_FIELDS',Tools::jsonEncode($order_fields));

            Configuration::updateGlobalValue('G_ORDER_FIELDS_POSITION',Tools::jsonEncode($order_fields_position));

            Configuration::updateGlobalValue('G_ORDER_FIELDS_ALIGN',Tools::jsonEncode($order_fields_align));

            $html .= $this->displayConfirmation($this->l('Settings saved successfully.'));

        }elseif (Tools::isSubmit('SubmitCsvExportFields'))

        {

            $order_fields = Tools::getValue('order_fields');

            $order_fields_position = Tools::getValue('order_fields_position');

            $field_separator = Tools::getValue('field_separator');

            $multiple_separator = Tools::getValue('multiple_separator');

            if($field_separator == '') $field_separator = ';';

            if($multiple_separator == '') $multiple_separator = ',';



            if(!is_array($order_fields)) $order_fields = array();



            //Tung update 14/12/2021

            // Configuration::updateGlobalValue('G_CSV_ORDER_FIELDS',Tools::jsonEncode($order_fields));

            Configuration::updateGlobalValue('G_CSV_ORDER_FIELDS',Tools::jsonEncode($order_fields_position));



            Configuration::updateGlobalValue('G_CSV_SEPARATOR',$field_separator);

            Configuration::updateGlobalValue('G_CSV_MULTI_SEPARATOR',$multiple_separator);

            Configuration::updateGlobalValue('G_CSV_ORDER_FIELDS_POSITION',Tools::jsonEncode($order_fields_position));

            $html .= $this->displayConfirmation($this->l('Settings saved successfully.'));

        }

		if (version_compare(_PS_VERSION_, '1.6.0.3', '>=') === true)

        {

			$this->context->controller->addjqueryPlugin('sortable');

        }

		elseif (version_compare(_PS_VERSION_, '1.6.0', '>=') === true)

			$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.sortable.js');

		else

			$this->context->controller->addJS($this->_path.'js/jquery.sortable.js');

        $this->context->controller->addJS($this->_path.'views/js/admin/g_ordermanager_config.js');

        $this->context->controller->addCSS($this->_path.'views/css/admin/g_ordermanager.css');



        $html.=$this->gridPosition();



        

        $html.=$this->csvExportField();

        return $html;

    }

    public function gridPosition()

    {

        $orderfields = $this->getOrderField();

        $order_fields_position = Configuration::getGlobalValue('G_ORDER_FIELDS_POSITION');

        $order_fields_align = Configuration::getGlobalValue('G_ORDER_FIELDS_ALIGN');

        $order_fields = Configuration::getGlobalValue('G_ORDER_FIELDS');

        $order_fields_position = Tools::jsonDecode($order_fields_position,true);

        $order_fields_align = Tools::jsonDecode($order_fields_align,true);

        $order_fields = Tools::jsonDecode($order_fields,true);

        foreach($orderfields as $orderfieldgroup){

            foreach($orderfieldgroup['cols'] as $orderfield){

                $order_fields_position[$orderfield['key']] = $orderfield;

            }

        }

        if(!is_array($order_fields_align)) $order_fields_align = array();

        if(!is_array($order_fields)) $order_fields = array();

        $this->context->smarty->assign(array(

            'current_url' => $this->context->link->getAdminLink('AdminModules').'&configure=g_ordermanager&tab_module=front_office_features&module_name=g_ordermanager',

            'orderfields'=>$orderfields,

            'order_fields_position'=>$order_fields_position,

            'order_fields_align'=>$order_fields_align,

            'order_fields'=>$order_fields,

            'orderactioninfisrtcolumn'=>Configuration::getGlobalValue('G_ORDER_ACTION_FISRT')

        ));

        return $this->display(__FILE__, 'views/templates/admin/grid.tpl');

    }

    public function getOrderField()

    {

        return array(

            array(

                'label'=>$this->l('Order'),

                'cols'=>array(

                    array('key'=>'id_order','name'=>$this->l('Id Order')),

                    array('key'=>'productimages','name'=>$this->l('Product Images')),

                    array('key'=>'reference','name'=>$this->l('Reference')),

                    array('key'=>'id_currency','name'=>$this->l('Id Currency')),

                    array('key'=>'payment','name'=>$this->l('Payment')),

                    array('key'=>'osname','name'=>$this->l('Status')),

                    array('key'=>'date_add','name'=>$this->l('Date')),

                    array('key'=>'id_pdf','name'=>$this->l('Pdf')),

                    array('key'=>'cname','name'=>$this->l('Delivery')),

                    array('key'=>'caname','name'=>$this->l('Carrier')),

                    array('key'=>'total_paid_tax_incl','name'=>$this->l('Total')),

                    array('key'=>'message','name'=>$this->l('Message'))

                )

            ),

            array(

                'label'=>$this->l('Customer'),

                'cols'=>array(

                    array('key'=>'new','name'=>$this->l('New client')),

                    array('key'=>'customer','name'=>$this->l('Customer')),

                    array('key'=>'email','name'=>$this->l('Email')),

                    array('key'=>'id_address','name'=>$this->l('Id Address')),

                    array('key'=>'id_country','name'=>$this->l('Id Country')),

                    array('key'=>'id_state','name'=>$this->l('Id State')),

                    array('key'=>'id_customer','name'=>$this->l('Id Customer')),

                    array('key'=>'firstname','name'=>$this->l('First Name')),

                    array('key'=>'lastname','name'=>$this->l('Last Name')),

                    array('key'=>'address1','name'=>$this->l('Address').'1'),

                    array('key'=>'address2','name'=>$this->l('Address').'2'),

                    array('key'=>'postcode','name'=>$this->l('Zip/Postal Code')),

                    array('key'=>'city','name'=>$this->l('City')),

                    array('key'=>'company','name'=>$this->l('Company')),

                    array('key'=>'other','name'=>$this->l('Other')),

                    array('key'=>'phone','name'=>$this->l('Home phone')),

                    array('key'=>'phone_mobile','name'=>$this->l('Mobile phone')),
                    
                    array('key'=>'validator','name'=>$this->l('validator')),

                )

            ),

            array(

                'label'=>$this->l('Product Detail'),

                'cols'=>array(

                    array('key'=>'cod_product_id','name'=>$this->l('Product Id')),

                    array('key'=>'cod_product_attribute_id','name'=>$this->l('Product Attribute Id')),

                    array('key'=>'cod_product_name','name'=>$this->l('Product name')),

                    array('key'=>'cod_product_reference','name'=>$this->l('Product Reference')),

                    array('key'=>'cod_product_ean13','name'=>$this->l('Product Ean13')),

                    array('key'=>'cod_product_quantity','name'=>$this->l('Product Quantity')),

                    array('key'=>'cod_product_quantity_in_stock','name'=>$this->l('Product Quantity In Stock')),

                    array('key'=>'cod_product_weight','name'=>$this->l('Product Weight')),

                    array('key'=>'cod_product_price','name'=>$this->l('Product Price')),

                    array('key'=>'cod_unit_price_tax_incl','name'=>$this->l('Product Unit Price Tax Incl')),

                    array('key'=>'cod_unit_price_tax_excl','name'=>$this->l('Product Unit Price Tax Excl')),

                    array('key'=>'cod_total_price_tax_incl','name'=>$this->l('Product Total Price Tax Incl')),

                    array('key'=>'cod_total_price_tax_excl','name'=>$this->l('Product Total Price Tax Excl')),

                )

            )

        );

    }

    public function csvExportField()

    {

        $orderfields = $this->getOrderField();

        $order_fields_position = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS_POSITION');

        $order_fields = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS');

        $field_separator = Configuration::getGlobalValue('G_CSV_SEPARATOR');

        $multiple_separator = Configuration::getGlobalValue('G_CSV_MULTI_SEPARATOR');

        $order_fields_position = Tools::jsonDecode($order_fields_position,true);

        $order_fields = Tools::jsonDecode($order_fields,true);

        foreach($orderfields as $orderfieldgroup){

            foreach($orderfieldgroup['cols'] as $orderfield){

                $order_fields_position[$orderfield['key']] = $orderfield;

            }

        }

        if(!is_array($order_fields)) $order_fields = array();

        if($field_separator == '') $field_separator = ';';

        if($multiple_separator == '') $multiple_separator = ',';

        $this->context->smarty->assign(array(

            'current_url' => $this->context->link->getAdminLink('AdminModules').'&configure=g_ordermanager&tab_module=front_office_features&module_name=g_ordermanager',

            'orderfields'=>$orderfields,

            'order_fields_position'=>$order_fields_position,

            'order_fields'=>$order_fields,

            'field_separator'=>$field_separator,

            'multiple_separator'=>$multiple_separator,

        ));

        return $this->display(__FILE__, 'views/templates/admin/csvexport.tpl');

    }

    public function hookDisplayBackOfficeHeader($params)

	{



        $controller = Tools::getValue('controller');

        if($controller == 'AdminOrdermanager')

        {

            $this->context->controller->addCSS($this->_path.'views/css/admin/g_ordermanager.css');

        }

    }

    public function hookDisplayBackOfficeFooter($params)

	{

        $controller = Tools::getValue('controller');

        if($controller == 'AdminOrdermanager')

        {

            $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;

            $protocol_content = ($useSSL) ? 'https://' : 'http://';

            $this->context->smarty->assign(

                array(

                    'base_uri'=>$protocol_content.Tools::getHttpHost().__PS_BASE_URI__,

                    'link'=>Context::getContext()->link,

                    'admincartstoken'=>Tools::getAdminTokenLite('AdminCarts'),

                    'adminordermanagerstoken'=>Tools::getAdminTokenLite('AdminOrdermanager')

                )

            );

            return $this->display(__file__, 'views/templates/admin/footer.tpl');

        }

	}

    public function hookOrderChangeShipping($params)

    {

        $order = $params['order'];

        $this->smarty->assign(

                array(

                    'weight_unit'=>Configuration::get('PS_WEIGHT_UNIT'),

                    'order_carrier' => new OrderCarrier((int)$order->getIdOrderCarrier()),

                    'carrier' => new Carrier($order->id_carrier),

                    'all_carrier'=>Carrier::getCarriers($this->context->language->id,true,false,false,null,Carrier::ALL_CARRIERS),

                    'currency' => new Currency($order->id_currency),

                    'order'=>$order

                )

        );

        return $this->display(__FILE__, '_shipping.tpl');

    }

    public function hookOrderProuductLideEdit($params)

    {

        $product = $params['product'];

        $productObj = new Product((int)$product['product_id']);

        if(Validate::isLoadedObject($productObj)){

            $combinations = $productObj->getAttributeCombinations($this->context->language->id);



                $_combinations = array();

                if(count($combinations) > 0)

                    foreach($combinations as $combination)

                    {

                        $_combinations[$combination['id_product_attribute']][] = $combination['group_name'].' : '.$combination['attribute_name'];

                    }

                $combinations = array();

                if(count($_combinations) > 0)

                    foreach($_combinations as $key=>$combination)

                    {

                        $combinations[$key] = implode(', ',$combination);

                    }

                $this->smarty->assign(

                                array(

                                    'combinations'=>$combinations,

                                    'id_order_detail'=>$product['id_order_detail'],

                                    'product_attribute_id'=>$product['product_attribute_id'],

                                    'product'=>$product,

                                    'productname'=>$productObj->name[$this->context->language->id]

                                )

                                );

        }



        return $this->display(__FILE__, '_productline.tpl');

    }

    public function hookActionObjectOrderDeleteAfter($params){

        $object = $params['object'];

        if(get_class($object) == 'Order'){

            if(Validate::isLoadedObject($object)){

                $array_table = array('order_history','order_return','order_slip','message');

                foreach($array_table as $_table){

                    $sql = 'DELETE FROM `'._DB_PREFIX_.pSql($_table).'` WHERE id_order='.(int)$object->id;

                    Db::getInstance()->Execute($sql);

                }

            }

        }

    }

    public function hookActionAdminControllerSetMedia(){

        $controller_admin = Tools::strtolower(Tools::getValue('controller'));

        if($controller_admin== 'adminordermanager'){

            $this->context->controller->addJqueryUI('ui.datepicker');

            $this->context->controller->addJS(_PS_JS_DIR_.'vendor/d3.v3.min.js');

            $this->context->controller->addJS('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');

            $this->context->controller->addJS(_PS_MODULE_DIR_.'g_ordermanager/views/js/admin/g_ordermanager_config.js');

            Media::addJsDef(

                array('admin_customer_link'=>$this->context->link->getAdminLink('AdminCustomers',true))

            );

            if (Tools::isSubmit('vieworder')) {

                

                if(file_exists(_PS_JS_DIR_.'admin/orders.js')) $this->context->controller->addJS(_PS_JS_DIR_.'admin/orders.js');

                else {

                    if (version_compare(_PS_VERSION_, '1.7.0.0', '>=') === true){

                        $this->context->controller->addJS( _PS_MODULE_DIR_.'g_ordermanager/views/js/admin/orders.js');

                    }else{

                        $this->context->controller->addJS( _PS_MODULE_DIR_.'g_ordermanager/views/js/admin/orders_ps16.js');

                    }

                }

                if(file_exists(_PS_JS_DIR_.'tools.js')) $this->context->controller->addJS(_PS_JS_DIR_.'tools.js');

                else {

                    if (version_compare(_PS_VERSION_, '1.7.0.0', '>=') === true){

                        $this->context->controller->addJS( _PS_MODULE_DIR_.'g_ordermanager/views/js/admin/tools.js');

                    }else{

                        $this->context->controller->addJS( _PS_MODULE_DIR_.'g_ordermanager/views/js/admin/tools_ps16.js');

                    }

                } 

            }

        }

    }

    public function hookDisplayOrderproductInlist($params)

    {

        $idorder = (int)$params['idorder'];

        $order = new Order((int)$idorder);



        if(Validate::isLoadedObject($order))

        {

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



            $this->toolbar_title = sprintf($this->l('Order #%1$d (%2$s) - %3$s %4$s'), $order->id, $order->reference, $customer->firstname, $customer->lastname);

            if (Shop::isFeatureActive()) {

                $shop = new Shop((int)$order->id_shop);

                $this->toolbar_title .= ' - '.sprintf($this->l('Shop: %s'), $shop->name);

            }



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

            // products current stock (from stock_available)

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

                $product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl'], $currency);

                $product['refund_history'] = OrderSlip::getProductSlipDetail($product['id_order_detail']);

                $product['return_history'] = OrderReturn::getProductReturnDetail($product['id_order_detail']);

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



            $gender = new Gender((int)$customer->id_gender, $this->context->language->id);



            $history = $order->getHistory($this->context->language->id);



            foreach ($history as &$order_state) {

                $order_state['text-color'] = Tools::getBrightness($order_state['color']) < 128 ? 'white' : 'black';

            }



            // Smarty assign

            $this->smarty->assign(

                array(

                'link' => $this->context->link,

                'order' => $order,

                'cart' => new Cart($order->id_cart),

                'customer' => $customer,

                'gender' => $gender,

                'customer_addresses' => $customer->getAddresses($this->context->language->id),

                'addresses' => array(

                    'delivery' => $addressDelivery,

                    'deliveryState' => isset($deliveryState) ? $deliveryState : null,

                    'invoice' => $addressInvoice,

                    'invoiceState' => isset($invoiceState) ? $invoiceState : null

                ),

                'customerStats' => $customer->getStats(),

                'products' => $products,

                'stock_location_is_available' => $stockLocationIsAvailable,

                'discounts' => $order->getCartRules(),

                'orders_total_paid_tax_incl' => $order->getOrdersTotalPaid(), // Get the sum of total_paid_tax_incl of the order with similar reference

                'total_paid' => $order->getTotalPaid(),

                'returns' => OrderReturn::getOrdersReturn($order->id_customer, $order->id),

                'customer_thread_message' => CustomerThread::getCustomerMessages($order->id_customer, null, $order->id),

                'orderMessages' => OrderMessage::getOrderMessages($order->id_lang),

                'messages' => Message::getMessagesByOrderId($order->id, true),

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

                'all_carrier'=>Carrier::getCarriers($this->context->language->id,true,false,false,null,Carrier::ALL_CARRIERS),

                'iso_code_lang' => $this->context->language->iso_code,

                'order_carrier' => new OrderCarrier((int)$order->getIdOrderCarrier()),

                'id_lang' => $this->context->language->id,

                'can_edit' => 1,

                'current_index' => 'index.php?controller=AdminOrders',

                'current_id_lang' => $this->context->language->id,

                'invoices_collection' => $order->getInvoicesCollection(),

                'not_paid_invoices_collection' => $order->getNotPaidInvoicesCollection(),

                'payment_methods' => $payment_methods,

                'invoice_management_active' => Configuration::get('PS_INVOICE', null, null, $order->id_shop),

                'weight_unit'=>Configuration::get('PS_WEIGHT_UNIT'),

                'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),

                'HOOK_CONTENT_ORDER' => Hook::exec('displayAdminOrderContentOrder', array(

                    'order' => $order,

                    'products' => $products,

                    'customer' => $customer)

                ),

                'HOOK_CONTENT_SHIP' => Hook::exec('displayAdminOrderContentShip', array(

                    'order' => $order,

                    'products' => $products,

                    'customer' => $customer)

                ),

                'HOOK_TAB_ORDER' => Hook::exec('displayAdminOrderTabOrder', array(

                    'order' => $order,

                    'products' => $products,

                    'customer' => $customer)

                ),

                'HOOK_TAB_SHIP' => Hook::exec('displayAdminOrderTabShip', array(

                    'order' => $order,

                    'products' => $products,

                    'customer' => $customer)

                ),

                'branche_tpl_customized_data'=>_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_customized_data.tpl',

                'branche_tpl_product_line'=>_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_product_line.tpl',

                'branche_tpl_new_product'=> _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_new_product.tpl',

        

                

            )

            );

            return $this->display(__FILE__, 'orderproduct_inlist.tpl');

        }

    }

    protected function getProducts($order)

    {

        $products = $order->getProducts();



        foreach ($products as &$product) {

            if ($product['image'] != null) {

                $name = 'product_mini_'.(int)$product['product_id'].(isset($product['product_attribute_id']) ? '_'.(int)$product['product_attribute_id'] : '').'.jpg';

                // generate image cache, only for back office

                $product['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_.'p/'.$product['image']->getExistingImgPath().'.jpg', $name, 45, 'jpg');

                if (file_exists(_PS_TMP_IMG_DIR_.$name)) {

                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);

                } else {

                    $product['image_size'] = false;

                }

            }

        }



        ksort($products);



        return $products;

    }

    public static function recurseCopy($src, $dst, $del = false)

    {

        if (!Tools::file_exists_cache($src)) {

            return false;

        }

        $dir = opendir($src);



        if (!Tools::file_exists_cache($dst)) {

            mkdir($dst);

        }

        while (false !== ($file = readdir($dir))) {

            if (($file != '.') && ($file != '..')) {

                if (is_dir($src.DIRECTORY_SEPARATOR.$file)) {

                    self::recurseCopy($src.DIRECTORY_SEPARATOR.$file, $dst.DIRECTORY_SEPARATOR.$file, $del);

                } else {

                    $overide_content =  Tools::file_get_contents($src.DIRECTORY_SEPARATOR.$file);

                    // remove mod translate

                    $overide_content = str_replace("mod='g_ordermanager'",'',$overide_content);

                    file_put_contents($dst.DIRECTORY_SEPARATOR.$file, $overide_content);

                    if ($del && is_writable($src.DIRECTORY_SEPARATOR.$file)) {

                        unlink($src.DIRECTORY_SEPARATOR.$file);

                    }

                }

            }

        }

        closedir($dir);

        if ($del && is_writable($src)) {

            rmdir($src);

        }

    }

    public function getgToken($id_customer = 0,$id_order = 0){

        if($id_customer > 0){

            return md5(_COOKIE_KEY_.(int)$id_customer.date("Ymd"));

        }

        elseif($id_order > 0){

            $order = new Order((int)$id_order);

            if (Validate::isLoadedObject($order)) {

                return md5(_COOKIE_KEY_.(int)$order->id_customer.date("Ymd"));

            }

        }

        return md5(_COOKIE_KEY_.(int)rand(1,9999999).date("Ymd"));

    }   

}

