<?php

/**

* This is class will extends class ModuleAdminController.

* 

* @author    Globo Jsc <contact@globosoftware.net>

* @copyright 2017 Globo Jsc

* @license   please read license in file license.txt

* @link	     http://www.globosoftware.net/

*/



use PrestaShop\PrestaShop\Adapter\StockManager;

class AdminOrdermanagerController extends ModuleAdminController

{

    public $toolbar_title;



    protected $statuses_array = array();



    public function __construct()

    {

        $this->bootstrap = true;

        $this->table = 'order';

        $this->className = 'Order';

        $this->lang = false;

        

        parent::__construct();

        $this->explicitSelect = true;

        $this->allow_export = true;

        $this->deleted = false;

        $this->addRowAction('edit');

        $this->addRowAction('vieworderproduct');

        $this->addRowAction('duplicate');

        $this->addRowAction('delete');

        $this->addRowAction('loginascustomer');

        $this->context = Context::getContext();

        $this->context->smarty->assign(array(

			'versionleftthan161' => version_compare(_PS_VERSION_,'1.6.1','<'),

            'currentversionps'=>_PS_VERSION_,

            'admin_order_tab_link' => rawurlencode($this->context->link->getAdminLink('AdminOrders')),

            'admin_ordermanager_tab_link' => rawurlencode($this->context->link->getAdminLink('AdminOrdermanager')),

            'admin_customer_link' => rawurlencode($this->context->link->getAdminLink('AdminCustomers')),

            'admin_cartrules_link' => rawurlencode($this->context->link->getAdminLink('AdminCartRules')),

            'admin_cart_link' => rawurlencode($this->context->link->getAdminLink('AdminCarts')),

            'admin_address_link' => rawurlencode($this->context->link->getAdminLink('AdminAddresses')),

            'orderactioninfisrtcolumn'=>(int)Configuration::getGlobalValue('G_ORDER_ACTION_FISRT')

		));

        $this->_select = '  

        a.id_order,

        address.id_address,                   

		a.id_currency,

		a.id_order AS id_pdf,

        a.id_order AS productimages,

        c.`id_customer`,

        c.`firstname`,

        c.`lastname`,

		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,

		c.email,

        osl.`name` AS `osname`,

		os.`color`,

        ca.`name` as `caname`,

		IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new,

		country_lang.name as cname,

		IF(a.valid, 1, 0) badge_success';

        $this->_join = '

		LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)

		INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = a.id_address_delivery

		INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country

		INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$this->context->language->id.')

		LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)

		LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';

        $this->_join .= '

          LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = a.`id_carrier`)'; 

        $order_fields_position = Configuration::getGlobalValue('G_ORDER_FIELDS_POSITION');

        $order_fields = Configuration::getGlobalValue('G_ORDER_FIELDS');

        $order_fields_align = Configuration::getGlobalValue('G_ORDER_FIELDS_ALIGN');

        $order_fields_position = Tools::jsonDecode($order_fields_position,true);

        $order_fields = Tools::jsonDecode($order_fields,true);

        $order_fields_align = Tools::jsonDecode($order_fields_align,true);

        $cod_keys = array(

            'product_id','product_attribute_id','product_name','product_reference','product_ean13',

            'product_quantity','product_quantity_in_stock','product_weight','product_price',

            'unit_price_tax_incl','unit_price_tax_excl','total_price_tax_incl','total_price_tax_excl'

        );

        $cod_join = array();

        foreach($cod_keys as $cod_key){

            if(in_array('cod_'.$cod_key,array_keys($order_fields)))

                //$cod_join[] = 'GROUP_CONCAT('.$cod_key.' SEPARATOR ";") AS cod_'.pSQL($cod_key);

                $cod_join[] = $cod_key;

        }

        if($cod_join)

        {

            $this->_join .= 'LEFT JOIN (

                                    SELECT id_order,';//

            foreach($cod_join as $c_join){

                $this->_join .= 'GROUP_CONCAT('.pSql($c_join).' SEPARATOR ";") AS cod_'.pSQL($c_join).',';

            }

            $this->_join = Tools::substr($this->_join, 0, -1); // remove last , key

            $this->_join .= ' FROM `'._DB_PREFIX_.'order_detail` group by id_order

                                    ) cod ON (a.id_order = cod.id_order)';

            $this->_select .= ',cod.*';

        }

        if(in_array('message',array_keys($order_fields)))

        {

            $this->_join .= 'LEFT JOIN (

                                    SELECT id_order,message

                                    FROM `'._DB_PREFIX_.'message`

                                    group by id_order

                                    ORDER BY date_add ASC

                                    ) omes ON (a.id_order = omes.id_order)';

            $this->_select .= ',omes.message';

        }

        $this->_orderBy = 'id_order';

        $this->_orderWay = 'DESC';

        $this->_use_found_rows = true;

        $this->shopLinkType = 'shop';

        $this->shopShareDatas = Shop::SHARE_ORDER;



        $statuses = OrderState::getOrderStates((int)$this->context->language->id);

        foreach ($statuses as $status) {

            $this->statuses_array[$status['id_order_state']] = $status['name'];

        }

        

        $fields_list = array();

        if($order_fields)

            foreach(array_keys($order_fields_position) as $key)

            {

                if(isset($order_fields[$key])){

                    // if (!Configuration::get('PS_B2B_ENABLE') && $key=='company') continue;

                    $field = $this->getFieldList($key,isset($order_fields_align[$key]) ? $order_fields_align[$key] : null);

                    if(!empty($field)) $fields_list["$key"] =  $field;

                }

                    

            }

        $this->fields_list =$fields_list;

        $removeclicks = array();

        /* array(

            'reference',

            'customer',

            'osname',

            'payment',

            'cname',

            'caname',

            'date_add'

        );

        */ /** remove from 07/04/2021 */

        if($this->fields_list)

            foreach($this->fields_list as $key=>&$fields_list)

                if(in_array($key,$removeclicks))

                    $fields_list['remove_onclick'] = true;



        if (Tools::isSubmit('id_order')) {

            $order = new Order((int)Tools::getValue('id_order'));

            $this->context->cart = new Cart($order->id_cart);

            $this->context->customer = new Customer($order->id_customer);

        }

        $this->bulk_actions = array(

            'delete' => array(

                'text' => $this->l('Delete selected'),

                'confirm' => $this->l('Delete selected items?'),

                'icon' => 'icon-trash'

            ),
            
        );
 
        $this->toolbar_btn['changegrid'] = array(

			'href' => '#changegrid',

			'desc' => $this->l('Change Grid Config'),

		);

        $this->toolbar_btn['print'] = array(

			'href' => 'submitPrintOrder',

			'desc' => $this->l('Print Order'),

		);

        $this->toolbar_btn['invoice'] = array(

			'href' => 'submitExportOrderInvoice',

			'desc' => $this->l('Export Invoice'),

		);

        $this->toolbar_btn['delivery'] = array(

			'href' => 'submitExportOrderDelivery',

			'desc' => $this->l('Export Delivery'),

		);

        $this->toolbar_btn['csv'] = array(

			'href' => 'submitExportOrderCsv',

			'desc' => $this->l('Export CSV'),

		);
        
        $this->toolbar_btn['csv-csv1'] = array(
            'class' => 'process-icon-csv',
			'href' => 'submitExportOrderCsv1',
			'desc' => $this->l('Export CSV 1'),
            'id' => 'Export-CSV-1'
		);
        $this->toolbar_btn['csv-csv2'] = array(
            'class' => 'process-icon-csv',
			'href' =>  'submitExportOrderCsv2',
			'desc' => $this->l('Export CSV 2'),
            'id' => 'Export-CSV-2'

		);
    }

    public function getFieldList($field,$align='text-left')

    {

        $field_array = array();

        switch ($field) {

            case 'id_order' : 

                $field_array =array(

                        'title' => $this->l('ID'),

                        'align' => 'text-center',

                        'class' => 'fixed-width-xs',

                        'filter_key'=>'a!id_order'

                    );

                break;

            case 'id_country' : 

                $field_array =array(

                        'title' => $this->l('Id Country'),

                        'align' => $align,

                        'class'=>'order_field_'.$field,

                        'filter_key'=>'country!id_country'

                    );

                break;

            case 'reference':

                $field_array = array(

                    'title' => $this->l('Reference'),

                    'align' => $align,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'new':

                $field_array = array(

                    'title' => $this->l('New client'),

                    'align' => $align,

                    'type' => 'bool',

                    'tmpTableFilter' => true,

                    'orderby' => false,

                    'callback' => 'printNewCustomer',

                    'class'=>'order_field_'.$field

                );

                break;

            case 'customer':

                $field_array = array(

                    'title' => $this->l('Customer'),

                    'havingFilter' => true,

                    'align' => $align,

                    'class'=>'order_field_'.$field

                );

                break;

           case 'company':     

                $field_array = array(

                        'title' => $this->l('Company'),

                        'filter_key' => 'c!company',

                        'align' => $align,

                        'class'=>'order_field_'.$field

                );

                break;

                

           case 'total_paid_tax_incl' :     

                $field_array = array(

                    'title' => $this->l('Total'),

                    'align' => $align,

                    'type' => 'price',

                    'currency' => true,

                    'callback' => 'setOrderCurrencys',

                    'badge_success' => true,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'payment' :     

                $field_array = array(

                    'title' => $this->l('Payment'),

                    'align' => $align,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'osname': 

                    $field_array = array(

                    'title' => $this->l('Status'),

                    'type' => 'select',

                    'color' => 'color',

                    'list' => $this->statuses_array,

                    'filter_key' => 'os!id_order_state',

                    'filter_type' => 'int',

                    'order_key' => 'osname',

                    'align' => $align,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'date_add' :     

                $field_array = array(

                    'title' => $this->l('Date'),

                    'align' => $align,

                    'type' => 'datetime',

                    'filter_key' => 'a!date_add',

                    'class'=>'order_field_'.$field

                );

                break;

            case 'id_pdf':     

                $field_array = array(

                    'title' => $this->l('PDF'),

                    'align' => $align,

                    'callback' => 'printPDFIcons',

                    'orderby' => false,

                    'search' => false,

                    'remove_onclick' => true,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'productimages':     

                $field_array = array(

                    'title' => $this->l('Product Images'),

                    'align' => $align,

                    'callback' => 'printProductImages',

                    'orderby' => false,

                    'search' => false,

                    'remove_onclick' => true,

                    'class'=>'order_field_'.$field

                );

                break;

            case 'caname':

                $all_carrier = Carrier::getCarriers((int)$this->context->language->id,true,false,false,null,Carrier::ALL_CARRIERS);

                $carrier_array = array();

                foreach ($all_carrier as $row) {

                    $carrier_array[$row['id_carrier']] = $row['name'];

                }  

                $field_array = array(

                    'title' => $this->l('Carrier'),

                    'type' => 'select',

                    'list' => $carrier_array,

                    'filter_key' => 'a!id_carrier',

                    'filter_type' => 'int',

                    'order_key' => 'id_carrier',

                    'class'=>'order_field_'.$field,

                    'callback'=>'setOrderCarrierName'

                );

                break;

            case 'cname':   

                $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('

    			SELECT DISTINCT c.id_country, cl.`name`

    			FROM `'._DB_PREFIX_.'orders` o

    			'.Shop::addSqlAssociation('orders', 'o').'

    			INNER JOIN `'._DB_PREFIX_.'address` a ON a.id_address = o.id_address_delivery

    			INNER JOIN `'._DB_PREFIX_.'country` c ON a.id_country = c.id_country

    			INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.`id_country` = cl.`id_country` AND cl.`id_lang` = '.(int)$this->context->language->id.')

    			ORDER BY cl.name ASC');

    

                $country_array = array();

                foreach ($result as $row) {

                    $country_array[$row['id_country']] = $row['name'];

                }  

                $field_array = array(

                    'title' => $this->l('Delivery'),

                    'type' => 'select',

                    'list' => $country_array,

                    'filter_key' => 'country!id_country',

                    'filter_type' => 'int',

                    'order_key' => 'cname',

                    'class'=>'order_field_'.$field,

                    //'callback'=>'getDeliveryFormat'

                );

                break;

            default:

                $ordermanagerObj = Module::getInstanceByName('g_ordermanager');

                $orderFields = $ordermanagerObj->getOrderField();

                $title = '';

                if($orderFields)

                    foreach($orderFields as $_orderField){

                        if($_orderField && isset($_orderField['cols'])){

                            foreach($_orderField['cols'] as $orderField){

                                if(isset($orderField['key']) && $orderField['key'] == $field){

                                    $title = $orderField['name'];

                                    break;

                                }

                            }

                        }

                    }

                        

                $field_array = array(

                    'title' => $title,

                    'type' => 'text',

                    'class'=>'order_field_'.$field

                );

                if(in_array($field,array('id_address','id_state','address1','address2','postcode','city','company','other','phone','phone_mobile'))){

                    $field_array['filter_key'] = 'address!'.$field;

                }elseif(in_array($field,array('firstname','lastname','id_customer'))){

                    $field_array['filter_key'] = 'c!'.$field;

                }

                if(in_array($field,array('cod_product_price','cod_unit_price_tax_incl','cod_unit_price_tax_excl','cod_total_price_tax_incl','cod_total_price_tax_excl')))

                {

                    $field_array['callback'] = 'setOrderCurrencys';

                    $field_array['type'] = 'price';

                    $field_array['currency'] = true;

                }elseif(in_array($field,array('cod_product_id','cod_product_attribute_id','cod_product_name','cod_product_reference','cod_product_ean13','cod_product_quantity','cod_product_quantity_in_stock')))

                {

                    $field_array['callback'] = 'setOrderSplitLine';

                }elseif($field == 'cod_product_weight'){

                    $field_array['callback'] = 'setOrderWeightSplitLine';

                }

                break;

        }

        

        return $field_array;

    }

    public function printNewCustomer($id_order, $tr)

    {

        $id_order;

        return ($tr['new'] ? $this->l('Yes') : $this->l('No'));

    }

    public function printProductImages($id_order, $tr)

    {

        $tr;

        $order = new Order($id_order);

        $products = $this->getProducts($order);

        $this->context->smarty->assign(array(

                'products' => $products,

                'link'=>Context::getContext()->link,

                'messages'=>Message::getMessagesByOrderId($id_order,true)

            ));

        

        return $this->createTemplate('_orderproductimage.tpl')->fetch();

    }

    public function printPDFIcons($id_order, $tr)

    {

        static $valid_order_state = array();



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



        $this->context->smarty->assign(array(

            'order' => $order,

            'tr' => $tr

        ));



        return $this->createTemplate('_print_pdf_icon.tpl')->fetch();

    }

    public static function setOrderCarrierName($echo, $tr)

    {

        $tr;

        if($echo == '0'){

            $echo = str_replace(

                array('#', ';'),

                '',

                Configuration::get('PS_SHOP_NAME')

            );

        }

        return $echo;

    }

	

    public static function setOrderCurrencys($echo, $tr)

    {

        $order = new Order($tr['id_order']);

        $echos = explode(';',$echo);

        foreach($echos as &$_echo){

            if($_echo !='') 

                $_echo = Tools::displayPrice($_echo, (int)$order->id_currency);

            else

                $_echo = '--';

        }   

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        Context::getContext()->smarty->assign(array(

            'item_type'=>'order_split_line',

            'datas'=>$echos

        ));

        return Context::getContext()->smarty->fetch($tpl);

    }

	

    public static function setOrderSplitLine($echo, $tr)

    {

        $tr;

        $echos = explode(';',$echo);  

        foreach($echos as &$_echo){

            if(trim($_echo) =='') 

                $_echo = '--';

        } 

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        Context::getContext()->smarty->assign(array(

            'item_type'=>'order_split_line',

            'datas'=>$echos

        ));

        return Context::getContext()->smarty->fetch($tpl);

    }

    public static function setOrderWeightSplitLine($echo, $tr)

    {

        $tr;

        $weight_unit=Configuration::get('PS_WEIGHT_UNIT');

        $echos = explode(';',$echo);  

        if($echos){

            foreach($echos as &$_echo)

                if($_echo !='') $_echo .= $weight_unit;

        }

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        Context::getContext()->smarty->assign(array(

            'item_type'=>'order_split_line',

            'datas'=>$echos

        ));

        return Context::getContext()->smarty->fetch($tpl);

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

    public function renderList()

    {
  
        $this->tpl_list_vars['gordermanager_active'] = true;

        $this->tpl_list_vars['updateOrderStatus_mode'] = true;

        $this->tpl_list_vars['order_statuses'] = $this->statuses_array;

        $this->tpl_list_vars['REQUEST_URI'] = $_SERVER['REQUEST_URI'];

        $this->tpl_list_vars['POST'] = $_POST;

        $order_fields = Configuration::getGlobalValue('G_ORDER_FIELDS');

        $order_fields = Tools::jsonDecode($order_fields,true);

        if(!is_array($order_fields)) $order_fields = array();

        $this->tpl_list_vars['orderfields'] = $this->module->getOrderField();
    
        $this->tpl_list_vars['order_fields'] = $order_fields;
        
        return parent::renderList();
    
    }

    public function duplicateCart($idorder,$ajax=false,$id_customer_change = 0)

    {

        $order = new Order($idorder);

        if(Validate::isLoadedObject($order))

        {

            $products = $order->getProductsDetail();

            $cart = new Cart();

            $cart->id_shop_group = $order->id_shop_group;

            $cart->id_shop = $order->id_shop;

            $cart->id_carrier = $order->id_carrier;

            $change_customer = false;

            if($id_customer_change > 0){

                $customerObj = new Customer((int)$id_customer_change);

                if(Validate::isLoadedObject($customerObj)){

                    $change_customer = true;

                    $cart->id_customer = $customerObj->id;

                    $addresses  = $customerObj->getAddresses($order->id_lang);

                    if($addresses){

                        foreach($addresses as $address){

                            $cart->id_address_delivery = (int)$address['id_address'];

                            $cart->id_address_invoice = $cart->id_address_delivery;

                            break;

                        }

                    }else{

                        $cart->id_address_delivery = 0;

                        $cart->id_address_invoice = 0;

                    }

                }

            }

            if(!$change_customer){

                $cart->id_customer = $order->id_customer;

                $cart->id_address_delivery = $order->id_address_delivery;

                $cart->id_address_invoice = $order->id_address_invoice;

            }

            $cart->id_currency = $order->id_currency;

            $cart->id_lang = $order->id_lang;

            $cart->secure_key = $order->secure_key;

    

            $cart->add();

            if($products)

            {

                foreach($products as $product)

                {

                    $cart->updateQty($product['product_quantity'], $product['product_id'], isset($product['product_attribute_id']) ? $product['product_attribute_id'] : null,

                        null, 'up', $order->id_address_delivery, new Shop($order->id_shop));

                }

            }

            

            $customs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

    			SELECT *

    			FROM '._DB_PREFIX_.'customization c

    			LEFT JOIN '._DB_PREFIX_.'customized_data cd ON cd.id_customization = c.id_customization

    			WHERE c.id_cart = '.(int)$order->id_cart

            );

    

            $customs_by_id = array();

            foreach ($customs as $custom) {

                if (!isset($customs_by_id[$custom['id_customization']])) {

                    $customs_by_id[$custom['id_customization']] = array(

                        'id_product_attribute' => $custom['id_product_attribute'],

                        'id_product' => $custom['id_product'],

                        'quantity' => $custom['quantity']

                    );

                }

            }

    

            $custom_ids = array();

            foreach ($customs_by_id as $customization_id => $val) {

                Db::getInstance()->execute('

    				INSERT INTO `'._DB_PREFIX_.'customization` (id_cart, id_product_attribute, id_product, `id_address_delivery`, quantity, `quantity_refunded`, `quantity_returned`, `in_cart`)

    				VALUES('.(int)$cart->id.', '.(int)$val['id_product_attribute'].', '.(int)$val['id_product'].', '.(int)$order->id_address_delivery.', '.(int)$val['quantity'].', 0, 0, 1)'

                );

                $custom_ids[$customization_id] = Db::getInstance(_PS_USE_SQL_SLAVE_)->Insert_ID();

            }

    

            if ($customs) {

                $first = true;

                $sql_custom_data = 'INSERT INTO '._DB_PREFIX_.'customized_data (`id_customization`, `type`, `index`, `value`) VALUES ';

                foreach ($customs as $custom) {

                    if (!$first) {

                        $sql_custom_data .= ',';

                    } else {

                        $first = false;

                    }

    

                    $customized_value = $custom['value'];

    

                    if ((int)$custom['type'] == 0) {

                        $customized_value = md5(uniqid(rand(), true));

                        Tools::copy(_PS_UPLOAD_DIR_.$custom['value'], _PS_UPLOAD_DIR_.$customized_value);

                        Tools::copy(_PS_UPLOAD_DIR_.$custom['value'].'_small', _PS_UPLOAD_DIR_.$customized_value.'_small');

                    }

    

                    $sql_custom_data .= '('.(int)$custom_ids[$custom['id_customization']].', '.(int)$custom['type'].', '.

                        (int)$custom['index'].', \''.pSQL($customized_value).'\')';

                }

                Db::getInstance()->execute($sql_custom_data);

            }

            if($ajax)

                die("$cart->id");

            else return (int)$cart->id;

        }else{

            if($ajax)

                die('0');

            else return 0;

        }

    }

    

	public function getDeliveryOptionList(&$cart,$id_lang)

    {

        $delivery_option_list_formated = [];

        $delivery_option_list = $cart->getDeliveryOptionList();



        if (!count($delivery_option_list)) {

            return [];

        }

        foreach (current($delivery_option_list) as $key => $delivery_option) {

            $name = '';

            $first = true;

            foreach ($delivery_option['carrier_list'] as $carrier) {

                if (!$first) {

                    $name .= ', ';

                } else {

                    $first = false;

                }



                $name .= $carrier['instance']->name;



                if ($delivery_option['unique_carrier']) {

                    $name .= ' - ' . $carrier['instance']->delay[$id_lang];

                }

                if (!$cart->id_carrier) {

                    $cart->setDeliveryOption([$cart->id_address_delivery => (int) $carrier['instance']->id . ',']);

                    $cart->save();

                }

                

            }

            $delivery_option_list_formated[] = ['name' => $name, 'key' => $key,'price'=>$delivery_option['total_price_with_tax']];

        }

        return $delivery_option_list_formated;

    }

    public function getCartDetail($id_cart){

        if($id_cart > 0){

            $cartObj = new Cart($id_cart);

            $message_content = '';$customer_name = '';$id_customer = 0;

            if ($message = Message::getMessageByCartId((int) $cartObj->id)) {

                $message_content = $message['message'];

            }

            $customerObj = new Customer($cartObj->id_customer);

            $addresses = array();

            if(Validate::isLoadedObject($customerObj)){

                $id_customer = $cartObj->id_customer;

                $customer_name = $customerObj->firstname .' '.$customerObj->lastname;

                $addresses = $customerObj->getAddresses((int) $cartObj->id_lang);

                if($addresses)

                    foreach ($addresses as &$data) {

                        $address = new Address((int) $data['id_address']);

                        $data['formated_address'] = AddressFormat::generateAddress($address, [], '<br />');

                    }

            }

            $payment_methods = array();

            foreach (PaymentModule::getInstalledPaymentModules() as $payment) {

                $module = Module::getInstanceByName($payment['name']);

                if (Validate::isLoadedObject($module) && $module->active) {

                    $payment_methods[] = array(

                        'name'=>$module->name,

                        'label'=>$module->displayName

                    );

                }

            }

            return array(

                'error'=>0,

                'delivery_option_list' => $this->getDeliveryOptionList($cartObj,(int) $cartObj->id_lang),

                'cart' => $cartObj,

                'currency' => new Currency($cartObj->id_currency),

                'addresses' => $addresses,

                'id_cart' => $id_cart,

                'order_message' => $message_content,

                'payment_methods'=>$payment_methods,

                'customer_name'=>$customer_name,

                'id_customer'=>$id_customer

            );

        }

        return array();

    }

    public function postProcess()

    {

        

       if(Tools::isSubmit('OrderDuplicateAndChangeCustomer')){

            $id_cart = $this->duplicateCart((int)Tools::getValue('idorder'),false,(int)Tools::getValue('id_customer'));

            $result = array(

                'error'=>($id_cart> 0 ? 0 : 1),

                'id_cart'=>(int)$id_cart,

                'warning'=>($id_cart <= 0 ? $this->l('Can not duplicate order object.') : ''),

                'addcartlink'=>(version_compare(_PS_VERSION_, '1.7.7', '>=') ? $this->context->link->getAdminLink('AdminOrders',true,array('route'=>'admin_orders_create','cartId'=>(int)$id_cart)) : $this->context->link->getAdminLink('AdminOrders',true).'&id_cart='.(int)$id_cart.'&addorder')

            );

            die(Tools::jsonEncode($result));

        }

        elseif (Tools::isSubmit('searchCustomers')) {

            $this->ajaxProcessSearchCustomers();

        }

        elseif (Tools::isSubmit('updateGridConfig')) {

            $order_fields = Tools::getValue('order_fields');

            Configuration::updateGlobalValue('G_ORDER_FIELDS',Tools::jsonEncode($order_fields));

            die(Tools::jsonEncode(array('error'=>0,'warning'=>'')));

        }

        elseif (Tools::isSubmit('submitGenerateInvoice')) {

            $id_order = (int)Tools::getValue('id_order');

            $orderObj = new Order($id_order);

            if(Validate::isLoadedObject($orderObj)){

                if (!Configuration::get('PS_INVOICE', null, null, $orderObj->id_shop)) {

                    $this->errors[] = Tools::displayError('Invoice management has been disabled.');

                } elseif ($orderObj->hasInvoice()) {

                    $this->errors[] = Tools::displayError('This order already has an invoice.');

                } else {

                    $orderObj->setInvoice(true);

                    Tools::redirectAdmin(self::$currentIndex.'&id_order='.$orderObj->id.'&vieworder&conf=4&token='.$this->token);

                }

            }else{

                $this->errors[] = Tools::displayError('Can not load order object. #'.$id_order);

            }

        }

        

        else 

        if(Tools::isSubmit('loadOrderExtraData')){

            die($this->module->hookDisplayOrderproductInlist(array('idorder'=>(int)Tools::getValue('idorder'))));

        }else

        if(Tools::isSubmit('OrderDuplicate')) {

            $this->duplicateCart((int)Tools::getValue('idorder'),true);

        }

        elseif(Tools::isSubmit('OrderDuplicateAndGetCart')){

            $id_cart = $this->duplicateCart((int)Tools::getValue('idorder'));

            $result = array(

                'error'=>($id_cart> 0 ? 0 : 1),

                'id_cart'=>(int)$id_cart,

                'warning'=>($id_cart <= 0 ? $this->l('Can not duplicate order object.') : ''),

                'addcartlink'=>(version_compare(_PS_VERSION_, '1.7.7', '>=') ? $this->context->link->getAdminLink('AdminOrders',true,array('route'=>'admin_orders_create','cartId'=>(int)$id_cart)) : $this->context->link->getAdminLink('AdminOrders',true).'&id_cart='.(int)$id_cart.'&addorder')

            );

            die(Tools::jsonEncode($result));

        }

        elseif (Tools::isSubmit('setupCustomer2')) {

            $this->changeCustomerInCart();

        }

        elseif (Tools::isSubmit('addMessage')) {

            $this->ajaxAddMessage();

        }

        elseif (Tools::isSubmit('submitPrintOrder')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);

            if($id_orders){

                $this->downloadPrintPdf($id_orders);

            }else

                die('');

        }

        elseif (Tools::isSubmit('submitExportOrderInvoice')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);

            if($id_orders){

                $this->downloadInvoicePdf($id_orders);

            }else

                die('');

        }elseif (Tools::isSubmit('submitExportOrderDelivery')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);

            if($id_orders){

                $this->downloadDeliveryPdf($id_orders);

            }else

                die('');

            

        }elseif (Tools::isSubmit('submitExportOrderCsv')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);

            if($id_orders){

                $this->downloadCsv($id_orders);

            }else

                die('');

            

        }
        elseif (Tools::isSubmit('submitExportOrderCsv1')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);



            if($id_orders){
                $this->downloadCsvExtra($id_orders,'submitExportOrderCsv1');

            }else

                die('');
        }

        elseif (Tools::isSubmit('submitExportOrderCsv2')) {

            $idorders = Tools::getValue('idorders');

            $id_orders = explode('-',$idorders);

            if($id_orders){
                $this->downloadCsvExtra($id_orders,'submitExportOrderCsv2');

            }else

                die('');
        }

        elseif (Tools::isSubmit('OrderRemoveHistory')) {

            $this->ajaxRemoveOrderHistory();

        }

        elseif (Tools::isSubmit('OrderSubmitEditPayment')) {

            $this->ajaxOrderSubmitEditPayment();

        }

        elseif (Tools::isSubmit('OrderNewVoucher')) {

            $this->ajaxOrderNewVoucher();

        }

        elseif (Tools::isSubmit('OrderEditDiscount')) {

            $this->ajaxOrderEditDiscount();

        }

        elseif (Tools::isSubmit('OrderDeleteDiscount')) {

            $this->ajaxOrderDeleteDiscount();

        }

        elseif (Tools::isSubmit('OrderChangeReference')) {

            $this->ajaxOrderChangeReference();

        }elseif (Tools::isSubmit('OrderChangeAddress')) {

            $this->ajaxOrderChangeAddress();

        }elseif (Tools::isSubmit('OrderChangeState')) {

            $this->ajaxOrderChangeState();

        }elseif (Tools::isSubmit('OrderChangeStates')) {

            $this->ajaxOrderChangeStates();

        }

        elseif (Tools::isSubmit('OrderChangeDateAdd')) {

            $this->ajaxOrderChangeDateAdd();

        }elseif (Tools::isSubmit('OrderResendEmail')) {

            $this->ajaxOrderResendEmail();

        }elseif (Tools::isSubmit('OrderDeletePayment')) {

            $this->ajaxOrderDeletePayment();

        }elseif (Tools::isSubmit('OrderAddPayment')) {

            $this->ajaxOrderAddPayment();

        }

        elseif (Tools::isSubmit('OrderChangeCarrier')) {

            $this->ajaxOrderChangeCarrier();

        }

        elseif (Tools::isSubmit('ajaxUpdateAddresses')) {

            $this->ajaxOrderUpdateAddress();

        }

        // from version 1.0.1

        elseif (Tools::isSubmit('addOrderNote')) {

            $this->ajaxAddOrderNote();

        }elseif (Tools::isSubmit('deleteOrdernote')) {

            $this->ajaxDeleteOrdernote();

        }

        elseif (Tools::isSubmit('ajaxProcessDeleteProductLine')) {

            $this->ajaxProcessDeleteProductLine((int)Tools::getValue('id_order'),(int)Tools::getValue('id_order_detail'));

        }

        /* Partial refund from order */

        elseif (Tools::isSubmit('partialRefund')) {

            $params = array();

            if (Tools::isSubmit('id_order') && Tools::getValue('id_order') > 0) {

                $order = new Order(Tools::getValue('id_order'));

                if (!Validate::isLoadedObject($order)) {

                    $this->errors[] = $this->trans('The order cannot be found within your database.', array(), 'Admin.Orderscustomers.Notification');

                }

                ShopUrl::cacheMainDomainForShop((int)$order->id_shop);

            }

            if (isset($order) && Tools::isSubmit('partialRefundProduct') && ($refunds = Tools::getValue('partialRefundProduct')) && is_array($refunds)) {

                $amount = 0;

                $order_detail_list = array();

                $full_quantity_list = array();

                foreach ($refunds as $id_order_detail => $amount_detail) {

                    $quantity = Tools::getValue('partialRefundProductQuantity');

                    if (!$quantity[$id_order_detail]) {

                        continue;

                    }



                    $full_quantity_list[$id_order_detail] = (int)$quantity[$id_order_detail];



                    $order_detail_list[$id_order_detail] = array(

                        'quantity' => (int)$quantity[$id_order_detail],

                        'id_order_detail' => (int)$id_order_detail

                    );



                    $order_detail = new OrderDetail((int)$id_order_detail);

                    if (empty($amount_detail)) {

                        $order_detail_list[$id_order_detail]['unit_price'] = (!Tools::getValue('TaxMethod') ? $order_detail->unit_price_tax_excl : $order_detail->unit_price_tax_incl);

                        $order_detail_list[$id_order_detail]['amount'] = $order_detail->unit_price_tax_incl * $order_detail_list[$id_order_detail]['quantity'];

                    } else {

                        $order_detail_list[$id_order_detail]['amount'] = (float)str_replace(',', '.', $amount_detail);

                        $order_detail_list[$id_order_detail]['unit_price'] = $order_detail_list[$id_order_detail]['amount'] / $order_detail_list[$id_order_detail]['quantity'];

                    }

                    $amount += $order_detail_list[$id_order_detail]['amount'];

                    if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Tools::isSubmit('reinjectQuantities')) && $order_detail_list[$id_order_detail]['quantity'] > 0) {

                        $this->reinjectQuantity($order_detail, $order_detail_list[$id_order_detail]['quantity']);

                    }

                }



                $shipping_cost_amount = (float)str_replace(',', '.', Tools::getValue('partialRefundShippingCost')) ? (float)str_replace(',', '.', Tools::getValue('partialRefundShippingCost')) : false;



                if ($amount == 0 && $shipping_cost_amount == 0) {

                    if (!empty($refunds)) {

                        $this->errors[] = $this->trans('Please enter a quantity to proceed with your refund.', array(), 'Admin.Orderscustomers.Notification');

                    } else {

                        $this->errors[] = $this->trans('Please enter an amount to proceed with your refund.', array(), 'Admin.Orderscustomers.Notification');

                    }

                    return false;

                }



                $choosen = false;

                $voucher = 0;



                if ((int)Tools::getValue('refund_voucher_off') == 1) {

                    $amount -= $voucher = (float)Tools::getValue('order_discount_price');

                } elseif ((int)Tools::getValue('refund_voucher_off') == 2) {

                    $choosen = true;

                    $amount = $voucher = (float)Tools::getValue('refund_voucher_choose');

                }



                if ($shipping_cost_amount > 0) {

                    if (!Tools::getValue('TaxMethod')) {

                        $tax = new Tax();

                        $tax->rate = $order->carrier_tax_rate;

                        $tax_calculator = new TaxCalculator(array($tax));

                        $amount += $tax_calculator->addTaxes($shipping_cost_amount);

                    } else {

                        $amount += $shipping_cost_amount;

                    }

                }



                $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

                if (Validate::isLoadedObject($order_carrier)) {

                    $order_carrier->weight = (float)$order->getTotalWeight();

                    if ($order_carrier->update()) {

                        $order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);

                    }

                }



                if ($amount >= 0) {

                                        

                    if (!OrderSlip::create($order, $order_detail_list, $shipping_cost_amount, $voucher, $choosen,

                        (Tools::getValue('TaxMethod') ? false : true))) {

                        $this->errors[] = $this->trans('You cannot generate a partial credit slip.', array(), 'Admin.Orderscustomers.Notification');

                    } else {

                        Hook::exec('actionOrderSlipAdd', array('order' => $order, 'productList' => $order_detail_list, 'qtyList' => $full_quantity_list), null, false, true, false, $order->id_shop);

                        $customer = new Customer((int)($order->id_customer));

                        

                        $params['{lastname}'] = $customer->lastname;

                        $params['{firstname}'] = $customer->firstname;

                        $params['{id_order}'] = $order->id;

                        $params['{order_name}'] = $order->getUniqReference();

                        $orderLanguage = new Language((int) $order->id_lang);

                        @Mail::Send(

                            (int)$order->id_lang,

                            'credit_slip',

                            $this->trans(

                                'New credit slip regarding your order',

                                array(),

                                'Emails.Subject',

                                $orderLanguage->locale

                            ),

                            $params,

                            $customer->email,

                            $customer->firstname.' '.$customer->lastname,

                            null,

                            null,

                            null,

                            null,

                            _PS_MAIL_DIR_,

                            true,

                            (int)$order->id_shop

                        );

                    }



                    foreach ($order_detail_list as &$product) {

                        $order_detail = new OrderDetail((int)$product['id_order_detail']);

                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {

                            StockAvailable::synchronize($order_detail->product_id);

                        }

                    }



                    // Generate voucher

                    if($this->errors){



                    }else if (Tools::isSubmit('generateDiscountRefund') && $amount > 0) {

                        $cart_rule = new CartRule();

                        $cart_rule->description = $this->trans('Credit slip for order #%d', array('#%d' => $order->id), 'Admin.Orderscustomers.Feature');

                        $language_ids = Language::getIDs(false);

                        foreach ($language_ids as $id_lang) {

                            // Define a temporary name

                            $cart_rule->name[$id_lang] = sprintf('V0C%1$dO%2$d', $order->id_customer, $order->id);

                        }



                        // Define a temporary code

                        $cart_rule->code = sprintf('V0C%1$dO%2$d', $order->id_customer, $order->id);

                        $cart_rule->quantity = 1;

                        $cart_rule->quantity_per_user = 1;



                        // Specific to the customer

                        $cart_rule->id_customer = $order->id_customer;

                        $now = time();

                        $cart_rule->date_from = date('Y-m-d H:i:s', $now);

                        $cart_rule->date_to = date('Y-m-d H:i:s', strtotime('+1 year'));

                        $cart_rule->partial_use = 1;

                        $cart_rule->active = 1;



                        $cart_rule->reduction_amount = $amount;

                        $cart_rule->reduction_tax = $order->getTaxCalculationMethod() != PS_TAX_EXC;

                        $cart_rule->minimum_amount_currency = $order->id_currency;

                        $cart_rule->reduction_currency = $order->id_currency;



                        if (!$cart_rule->add()) {

                            $this->errors[] = $this->trans('You cannot generate a voucher.', array(), 'Admin.Orderscustomers.Notification');

                        } else {

                            // Update the voucher code and name

                            foreach ($language_ids as $id_lang) {

                                $cart_rule->name[$id_lang] = sprintf('V%1$dC%2$dO%3$d', $cart_rule->id, $order->id_customer, $order->id);

                            }

                            $cart_rule->code = sprintf('V%1$dC%2$dO%3$d', $cart_rule->id, $order->id_customer, $order->id);



                            if (!$cart_rule->update()) {

                                $this->errors[] = $this->trans('You cannot generate a voucher.', array(), 'Admin.Orderscustomers.Notification');

                            } else {

                                $currency = $this->context->currency;

                                $customer = new Customer((int)($order->id_customer));

                                $params['{lastname}'] = $customer->lastname;

                                $params['{firstname}'] = $customer->firstname;

                                $params['{id_order}'] = $order->id;

                                $params['{order_name}'] = $order->getUniqReference();

                                $params['{voucher_amount}'] = Tools::displayPrice($cart_rule->reduction_amount, $currency, false);

                                $params['{voucher_num}'] = $cart_rule->code;

                                $orderLanguage = new Language((int) $order->id_lang);

                                @Mail::Send(

                                    (int)$order->id_lang,

                                    'voucher',

                                    $this->trans(

                                        'New voucher for your order #%s',

                                        array($order->reference),

                                        'Emails.Subject',

                                        $orderLanguage->locale

                                    ),

                                    $params,

                                    $customer->email,

                                    $customer->firstname.' '.$customer->lastname,

                                    null,

                                    null,

                                    null,

                                    null,

                                    _PS_MAIL_DIR_,

                                    true,

                                    (int)$order->id_shop

                                );

                            }

                        }

                    }

                } else {

                    if (!empty($refunds)) {

                        $this->errors[] = $this->trans('Please enter a quantity to proceed with your refund.', array(), 'Admin.Orderscustomers.Notification');

                    } else {

                        $this->errors[] = $this->trans('Please enter an amount to proceed with your refund.', array(), 'Admin.Orderscustomers.Notification');

                    }

                }



                // Redirect if no errors

                if($this->errors){



                }else {

                    Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=30&token='.$this->token);

                }

            } else {

                $this->errors[] = $this->trans('The partial refund data is incorrect.', array(), 'Admin.Orderscustomers.Notification');

            }

        }



        /* Cancel product from order */

        elseif (Tools::isSubmit('cancelProduct')) {

            $params = array();

            if (Tools::isSubmit('id_order') && Tools::getValue('id_order') > 0) {

                $order = new Order(Tools::getValue('id_order'));

                if (!Validate::isLoadedObject($order)) {

                    $this->errors[] = $this->trans('The order cannot be found within your database.', array(), 'Admin.Orderscustomers.Notification');

                }

                ShopUrl::cacheMainDomainForShop((int)$order->id_shop);

            }

            if (isset($order)) {

                if (!Tools::isSubmit('id_order_detail') && !Tools::isSubmit('id_customization')) {

                    $this->errors[] = $this->trans('You must select a product.', array(), 'Admin.Orderscustomers.Notification');

                } elseif (!Tools::isSubmit('cancelQuantity') && !Tools::isSubmit('cancelCustomizationQuantity')) {

                    $this->errors[] = $this->trans('You must enter a quantity.', array(), 'Admin.Orderscustomers.Notification');

                } else {

                    $productList = Tools::getValue('id_order_detail');

                    if ($productList) {

                        $productList = array_map('intval', $productList);

                    }



                    $customizationList = Tools::getValue('id_customization');

                    if ($customizationList) {

                        $customizationList = array_map('intval', $customizationList);

                    }



                    $qtyList = Tools::getValue('cancelQuantity');

                    if ($qtyList) {

                        $qtyList = array_map('intval', $qtyList);

                    }



                    $customizationQtyList = Tools::getValue('cancelCustomizationQuantity');

                    if ($customizationQtyList) {

                        $customizationQtyList = array_map('intval', $customizationQtyList);

                    }



                    $full_product_list = $productList;

                    $full_quantity_list = $qtyList;



                    if ($customizationList) {

                        foreach ($customizationList as $key => $id_order_detail) {

                            $full_product_list[(int)$id_order_detail] = $id_order_detail;

                            if (isset($customizationQtyList[$key])) {

                                $full_quantity_list[(int)$id_order_detail] += $customizationQtyList[$key];

                            }

                        }

                    }



                    if ($productList || $customizationList) {

                        if ($productList) {

                            $id_cart = Cart::getCartIdByOrderId($order->id);

                            $customization_quantities = Customization::countQuantityByCart($id_cart);



                            foreach ($productList as $key => $id_order_detail) {

                                $qtyCancelProduct = abs($qtyList[$key]);

                                if (!$qtyCancelProduct) {

                                    $this->errors[] = $this->trans('No quantity has been selected for this product.', array(), 'Admin.Orderscustomers.Notification');

                                }



                                $order_detail = new OrderDetail($id_order_detail);

                                $customization_quantity = 0;

                                if (array_key_exists($order_detail->product_id, $customization_quantities) && array_key_exists($order_detail->product_attribute_id, $customization_quantities[$order_detail->product_id])) {

                                    $customization_quantity = (int)$customization_quantities[$order_detail->product_id][$order_detail->product_attribute_id];

                                }



                                if (($order_detail->product_quantity - $customization_quantity - $order_detail->product_quantity_refunded - $order_detail->product_quantity_return) < $qtyCancelProduct) {

                                    $this->errors[] = $this->trans('An invalid quantity was selected for this product.', array(), 'Admin.Orderscustomers.Notification');

                                }

                            }

                        }

                        if ($customizationList) {

                            $customization_quantities = Customization::retrieveQuantitiesFromIds(array_keys($customizationList));



                            foreach ($customizationList as $id_customization => $id_order_detail) {

                                $qtyCancelProduct = abs($customizationQtyList[$id_customization]);

                                $customization_quantity = $customization_quantities[$id_customization];



                                if (!$qtyCancelProduct) {

                                    $this->errors[] = $this->trans('No quantity has been selected for this product.', array(), 'Admin.Orderscustomers.Notification');

                                }



                                if ($qtyCancelProduct > ($customization_quantity['quantity'] - ($customization_quantity['quantity_refunded'] + $customization_quantity['quantity_returned']))) {

                                    $this->errors[] = $this->trans('An invalid quantity was selected for this product.', array(), 'Admin.Orderscustomers.Notification');

                                }

                            }

                        }



                        if($this->errors){



                        }else if ($productList) {

                            foreach ($productList as $key => $id_order_detail) {

                                $qty_cancel_product = abs($qtyList[$key]);

                                $order_detail = new OrderDetail((int)($id_order_detail));



                                if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Tools::isSubmit('reinjectQuantities')) && $qty_cancel_product > 0) {

                                    $this->reinjectQuantity($order_detail, $qty_cancel_product);

                                }



                                // Delete product

                                $order_detail = new OrderDetail((int)$id_order_detail);

                                if (!$order->deleteProduct($order, $order_detail, $qty_cancel_product)) {

                                    $this->errors[] = $this->trans('An error occurred while attempting to delete the product.', array(), 'Admin.Orderscustomers.Notification').' <span class="bold">'.$order_detail->product_name.'</span>';

                                }

                                // Update weight SUM

                                $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

                                if (Validate::isLoadedObject($order_carrier)) {

                                    $order_carrier->weight = (float)$order->getTotalWeight();

                                    if ($order_carrier->update()) {

                                        $order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);

                                    }

                                }



                                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && StockAvailable::dependsOnStock($order_detail->product_id)) {

                                    StockAvailable::synchronize($order_detail->product_id);

                                }

                                Hook::exec('actionProductCancel', array('order' => $order, 'id_order_detail' => (int)$id_order_detail), null, false, true, false, $order->id_shop);

                            }

                        }

                        if($this->errors){}

                        else if ($customizationList) {

                            foreach ($customizationList as $id_customization => $id_order_detail) {

                                $order_detail = new OrderDetail((int)($id_order_detail));

                                $qtyCancelProduct = abs($customizationQtyList[$id_customization]);

                                if (!$order->deleteCustomization($id_customization, $qtyCancelProduct, $order_detail)) {

                                    $this->errors[] = $this->trans('An error occurred while attempting to delete product customization.', array(), 'Admin.Orderscustomers.Notification').' '.$id_customization;

                                }

                            }

                        }

                        // E-mail params

                        if ((Tools::isSubmit('generateCreditSlip') || Tools::isSubmit('generateDiscount')) && !count($this->errors)) {

                            $customer = new Customer((int)($order->id_customer));

                            $params['{lastname}'] = $customer->lastname;

                            $params['{firstname}'] = $customer->firstname;

                            $params['{id_order}'] = $order->id;

                            $params['{order_name}'] = $order->getUniqReference();

                        }



                        // Generate credit slip

                        if($this->errors){



                        }else if (Tools::isSubmit('generateCreditSlip')) {

                            $product_list = array();

                            $amount = $order_detail->unit_price_tax_incl * $full_quantity_list[$id_order_detail];



                            $choosen = false;

                            if ((int)Tools::getValue('refund_total_voucher_off') == 1) {

                                $amount -= $voucher = (float)Tools::getValue('order_discount_price');

                            } elseif ((int)Tools::getValue('refund_total_voucher_off') == 2) {

                                $choosen = true;

                                $amount = $voucher = (float)Tools::getValue('refund_total_voucher_choose');

                            }

                            foreach ($full_product_list as $id_order_detail) {

                                $order_detail = new OrderDetail((int)$id_order_detail);

                                $product_list[$id_order_detail] = array(

                                    'id_order_detail' => $id_order_detail,

                                    'quantity' => $full_quantity_list[$id_order_detail],

                                    'unit_price' => $order_detail->unit_price_tax_excl,

                                    'amount' => isset($amount) ? $amount : $order_detail->unit_price_tax_incl * $full_quantity_list[$id_order_detail],

                                );

                            }



                            $shipping = Tools::isSubmit('shippingBack') ? null : false;



                            if (!OrderSlip::create($order, $product_list, $shipping, $voucher, $choosen)) {

                                $this->errors[] = $this->trans('A credit slip cannot be generated.', array(), 'Admin.Orderscustomers.Notification');

                            } else {

                                Hook::exec('actionOrderSlipAdd', array('order' => $order, 'productList' => $full_product_list, 'qtyList' => $full_quantity_list), null, false, true, false, $order->id_shop);

                                $orderLanguage = new Language((int) $order->id_lang);

                                @Mail::Send(

                                    (int)$order->id_lang,

                                    'credit_slip',

                                    $this->trans(

                                        'New credit slip regarding your order',

                                        array(),

                                        'Emails.Subject',

                                        $orderLanguage->locale

                                    ),

                                    $params,

                                    $customer->email,

                                    $customer->firstname.' '.$customer->lastname,

                                    null,

                                    null,

                                    null,

                                    null,

                                    _PS_MAIL_DIR_,

                                    true,

                                    (int)$order->id_shop

                                );

                            }

                        }



                        // Generate voucher

                        if($this->errors){



                        }else if (Tools::isSubmit('generateDiscount')) {

                            $cartrule = new CartRule();

                            $language_ids = Language::getIDs((bool)$order);

                            $cartrule->description = $this->trans('Credit card slip for order #%d', array('#%d' => $order->id), 'Admin.Orderscustomers.Feature');

                            foreach ($language_ids as $id_lang) {

                                // Define a temporary name

                                $cartrule->name[$id_lang] = 'V0C'.(int)($order->id_customer).'O'.(int)($order->id);

                            }

                            // Define a temporary code

                            $cartrule->code = 'V0C'.(int)($order->id_customer).'O'.(int)($order->id);



                            $cartrule->quantity = 1;

                            $cartrule->quantity_per_user = 1;

                            // Specific to the customer

                            $cartrule->id_customer = $order->id_customer;

                            $now = time();

                            $cartrule->date_from = date('Y-m-d H:i:s', $now);

                            $cartrule->date_to = date('Y-m-d H:i:s', $now + (3600 * 24 * 365.25)); /* 1 year */

                            $cartrule->active = 1;



                            $products = $order->getProducts(false, $full_product_list, $full_quantity_list);



                            $total = 0;

                            foreach ($products as $product) {

                                $total += $product['unit_price_tax_incl'] * $product['product_quantity'];

                            }



                            if (Tools::isSubmit('shippingBack')) {

                                $total += $order->total_shipping;

                            }



                            if ((int)Tools::getValue('refund_total_voucher_off') == 1) {

                                $total -= (float)Tools::getValue('order_discount_price');

                            } elseif ((int)Tools::getValue('refund_total_voucher_off') == 2) {

                                $total = (float)Tools::getValue('refund_total_voucher_choose');

                            }



                            $cartrule->reduction_amount = $total;

                            $cartrule->reduction_tax = true;

                            $cartrule->minimum_amount_currency = $order->id_currency;

                            $cartrule->reduction_currency = $order->id_currency;



                            if (!$cartrule->add()) {

                                $this->errors[] = $this->trans('You cannot generate a voucher.', array(), 'Admin.Orderscustomers.Notification');

                            } else {

                                // Update the voucher code and name

                                foreach ($language_ids as $id_lang) {

                                    $cartrule->name[$id_lang] = 'V'.(int)($cartrule->id).'C'.(int)($order->id_customer).'O'.$order->id;

                                }

                                $cartrule->code = 'V'.(int)($cartrule->id).'C'.(int)($order->id_customer).'O'.$order->id;

                                if (!$cartrule->update()) {

                                    $this->errors[] = $this->trans('You cannot generate a voucher.', array(), 'Admin.Orderscustomers.Notification');

                                } else {

                                    $currency = $this->context->currency;

                                    $params['{voucher_amount}'] = Tools::displayPrice($cartrule->reduction_amount, $currency, false);

                                    $params['{voucher_num}'] = $cartrule->code;

                                    $orderLanguage = new Language((int) $order->id_lang);

                                    @Mail::Send(

                                        (int)$order->id_lang,

                                        'voucher',

                                        $this->trans(

                                            'New voucher for your order #%s',

                                            array($order->reference),

                                            'Emails.Subject',

                                            $orderLanguage->locale

                                        ),

                                        $params,

                                        $customer->email,

                                        $customer->firstname.' '.$customer->lastname,

                                        null,

                                        null,

                                        null,

                                        null,

                                        _PS_MAIL_DIR_,

                                        true,

                                        (int)$order->id_shop

                                    );

                                }

                            }

                        }

                    } else {

                        $this->errors[] = $this->trans('No product or quantity has been selected.', array(), 'Admin.Orderscustomers.Notification');

                    }

                    // Redirect if no errors

                    if ($this->errors) {}

                    else{

                        Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=31&token='.$this->token);

                    }

                }

            }

        }



        /*Tung update 14/12/2021 */

        // search product

        elseif(Tools::getValue('action')=="searchProductsnew"){

            

            $this->searchProductNew();



        }elseif(Tools::isSubmit('submitEditNote')){

              $this->ajaxEditNote();

        }elseif(Tools::isSubmit('submitCustomerNote')){

            $this->ajaxCustomerNote();

        }

        

        

        // #from version 1.0.1

        

        

        return parent::postProcess();

    }

    

    public function ajaxProcessDeleteProductLine($id_order,$id_order_detail)

    {

        $res = true;



        $order_detail = new OrderDetail((int)$id_order_detail);

        $order = new Order((int)$id_order);



        $this->doDeleteProductLineValidation($order_detail, $order);



        // Update OrderInvoice of this OrderDetail

        if ($order_detail->id_order_invoice != 0) {

            $order_invoice = new OrderInvoice($order_detail->id_order_invoice);

            $order_invoice->total_paid_tax_excl -= $order_detail->total_price_tax_excl;

            $order_invoice->total_paid_tax_incl -= $order_detail->total_price_tax_incl;

            $order_invoice->total_products -= $order_detail->total_price_tax_excl;

            $order_invoice->total_products_wt -= $order_detail->total_price_tax_incl;

            $res &= $order_invoice->update();

        }



        // Update Order

        $order->total_paid -= $order_detail->total_price_tax_incl;

        $order->total_paid_tax_incl -= $order_detail->total_price_tax_incl;

        $order->total_paid_tax_excl -= $order_detail->total_price_tax_excl;

        $order->total_products -= $order_detail->total_price_tax_excl;

        $order->total_products_wt -= $order_detail->total_price_tax_incl;



        $res &= $order->update();



        // Reinject quantity in stock

        $this->reinjectQuantity($order_detail, $order_detail->product_quantity, true);



        // Update weight SUM

        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

        if (Validate::isLoadedObject($order_carrier)) {

            $order_carrier->weight = (float)$order->getTotalWeight();

            $res &= $order_carrier->update();

            if ($res) {

                $order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);

            }

        }



        if (!$res) {

            die(json_encode(array(

                'result' => $res,

                'error' => $this->trans('An error occurred while attempting to delete the product line.', array(), 'Admin.Orderscustomers.Notification')

            )));

        }



        // Get invoices collection

        $invoice_collection = $order->getInvoicesCollection();



        $invoice_array = array();

        foreach ($invoice_collection as $invoice) {

            /** @var OrderInvoice $invoice */

            $invoice->name = $invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);

            $invoice_array[] = $invoice;

        }



        $order = $order->refreshShippingCost();



        // Assign to smarty informations in order to show the new product line

        $this->context->smarty->assign(array(

            'order' => $order,

            'currency' => new Currency($order->id_currency),

            'invoices_collection' => $invoice_collection,

            'current_id_lang' => Context::getContext()->language->id,

            'link' => Context::getContext()->link,

            'current_index' => self::$currentIndex

        ));



        $this->sendChangedNotification($order);



        die(json_encode(array(

            'result' => $res,

            'order' => $order,

            'invoices' => $invoice_array,

            'documents_html' => $this->createTemplate('_documents.tpl')->fetch(),

            'shipping_html' => $this->createTemplate('_shipping.tpl')->fetch()

        )));

    }

    protected function doDeleteProductLineValidation(OrderDetail $order_detail, Order $order)

    {

        if (!Validate::isLoadedObject($order_detail)) {

            die(json_encode(array(

                'result' => false,

                'error' => $this->trans('The Order Detail object could not be loaded.', array(), 'Admin.Orderscustomers.Notification')

            )));

        }



        if (!Validate::isLoadedObject($order)) {

            die(json_encode(array(

                'result' => false,

                'error' => $this->trans('The order object cannot be loaded.', array(), 'Admin.Orderscustomers.Notification')

            )));

        }



        if ($order_detail->id_order != $order->id) {

            die(json_encode(array(

                'result' => false,

                'error' => $this->trans('You cannot delete the order detail.', array(), 'Admin.Orderscustomers.Notification')

            )));

        }

    }

    

    // from version 1.0.1

    public function ajaxAddOrderNote(){

        $noteval = Tools::getValue('noteval');

        $id_order = (int)Tools::getValue('id_order');

        $id_gordernotes = (int)Tools::getValue('id_gordernotes');

        $showinpdf = (Tools::getValue('showinpdf') ? 1 : 0);

        $result = array(

            'error'=>1,

            'warning'=>'',

            'datas'=>array()

        );

        if(Validate::isString($noteval)){

            $orderObj = new Order((int)$id_order);

            if(Validate::isLoadedObject($orderObj)){

                $sql = '';

                if($id_gordernotes > 0){

                    $sql = 'UPDATE `'._DB_PREFIX_.'gordernotes` 

                            SET notes = "'.pSql($noteval).'",showinpdf='.(int)$showinpdf.' 

                            WHERE id_order='.(int)$id_order.' AND id_gordernotes = '.(int)$id_gordernotes;

                }else{

                    $sql = 'INSERT INTO `'._DB_PREFIX_.'gordernotes`(id_order,notes,showinpdf,date_add) VALUES(

                        '.(int)$id_order.',

                        "'.pSql($noteval).'",

                        '.(int)$showinpdf.',

                        NOW()

                    )';

                }

                Db::getInstance()->execute($sql);

                $result = array(

                    'error'=>0,

                    'datas'=>$this->getAllNoteOrder((int)$id_order)

                );

                if($id_gordernotes > 0)

                    $result['warning'] = $this->l('Note update successful');

                else $result['warning'] = $this->l('Note add successful');

            }else $result['warning'] = $this->l('Order not found.');

        }else{

            $result['warning'] = $this->l('Note conntent invalid.');

        }

        die(Tools::jsonEncode($result));

    }

    public function ajaxDeleteOrdernote(){

        $id_order = (int)Tools::getValue('id_order');

        $id_gordernotes = (int)Tools::getValue('id_gordernotes');

        $result = array(

            'error'=>1,

            'warning'=>'',

            'datas'=>array()

        );

        if($id_gordernotes > 0){

            $orderObj = new Order((int)$id_order);

            if(Validate::isLoadedObject($orderObj)){

                $sql = 'DELETE FROM  `'._DB_PREFIX_.'gordernotes` WHERE 

                    id_order = '.(int)$id_order.' AND 

                    id_gordernotes = '.(int)$id_gordernotes;

                Db::getInstance()->execute($sql);

                $result = array(

                    'error'=>0,

                    'datas'=>$this->getAllNoteOrder((int)$id_order)

                );

                $result['warning'] = $this->l('Note remove successful');

            }else $result['warning'] = $this->l('Order not found.');

        }else{

            $result['warning'] = $this->l('Note conntent invalid.');

        }

        die(Tools::jsonEncode($result));

    }

    public function getAllNoteOrder($id_order,$showinpdf = ''){

        if($id_order > 0){

            $sql = 'SELECT * FROM `'._DB_PREFIX_.'gordernotes` 

                    WHERE id_order = '.(int)$id_order.($showinpdf !='' ? ' AND showinpdf = '.(int)$showinpdf : '');

            return Db::getInstance()->executeS($sql);

        }else return array();

    }

    // #from version 1.0.1

    public function downloadInvoicePdf($idorders = array())

    {

        $idorders = array_map('intval',$idorders);

        if(count($idorders) > 0){

            $order_invoice_list = Db::getInstance()->executeS('

    			SELECT oi.*

    			FROM `'._DB_PREFIX_.'order_invoice` oi

    			LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = oi.`id_order`)

    			WHERE oi.id_order IN ('.pSQL(implode(',',$idorders)).') 

    			'.Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o').'

    			AND oi.number > 0

    			ORDER BY oi.date_add ASC

    		');

    

            $order_invoice_collection = ObjectModel::hydrateCollection('OrderInvoice', $order_invoice_list);

            if (!count($order_invoice_collection)) {

                die(Tools::displayError('No invoice was found.'));

            }

            $pdf = new PDF($order_invoice_collection, PDF::TEMPLATE_INVOICE, Context::getContext()->smarty);

            $pdf->render();

            exit;

        }

        

    }

	

    public function downloadDeliveryPdf($idorders = array())

    {

        $idorders = array_map('intval',$idorders);

        if(count($idorders) > 0){

            $order_invoice_list = Db::getInstance()->executeS('

    			SELECT oi.*

    			FROM `'._DB_PREFIX_.'order_invoice` oi

    			LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = oi.`id_order`)

    			WHERE oi.id_order IN ('.pSQL(implode(',',$idorders)).') 

    			AND oi.delivery_date != \'0000-00-00 00:00:00\'

    			'.Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o').'

    			ORDER BY oi.delivery_date ASC

    		');

    

            $order_invoice_collection = ObjectModel::hydrateCollection('OrderInvoice', $order_invoice_list);

            if (!count($order_invoice_collection)) {

                die(Tools::displayError('No delivery was found.'));

            }

            $pdf = new PDF($order_invoice_collection, PDF::TEMPLATE_DELIVERY_SLIP, Context::getContext()->smarty);

            $pdf->render();

            exit;

        }

        

    }

    public function downloadPrintPdf($idorders = array())

    {

        $id_shop = Context::getContext()->shop->id;

        $idorders = array_map('intval',$idorders);

        if(count($idorders) > 0){

            $pdf = new  PDFGenerator((bool)Configuration::get('PS_PDF_USE_CACHE'), 'P');

            $pdf->setFontForLang(Context::getContext()->language->iso_code);

            $pdf->SetHeaderMargin(0);

		    $pdf->SetFooterMargin(0);

            $pdf->setMargins(5,5,5);

            $pdf->SetPrintHeader(false);

            $pdf->SetPrintFooter(false);

            $logo = '';

            if (Configuration::get('PS_LOGO', null, null, $id_shop) != false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop))) {

                $logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop);

            }

            

            $path_logo = $logo;

            $shopname  =  Configuration::get('PS_SHOP_NAME', null, null, $id_shop);

            foreach($idorders as $idorder)

            {

                

                $temp =  _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_print_template.tpl';

                $order = new Order($idorder);

                $products = $this->getProducts($order);

                $currency = new Currency((int)$order->id_currency);

                foreach ($products as &$product) {

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

                    if ($product['image'] != null)

        			{

        				$name = 'product_mini_'.(int)$product['product_id'].(isset($product['product_attribute_id']) ? '_'.(int)$product['product_attribute_id'] : '').'.jpg';

        				$path = _PS_PROD_IMG_DIR_.$product['image']->getExistingImgPath().'.jpg';

                        if (Tools::file_exists_no_cache(_PS_TMP_IMG_DIR_.$name)) {

        				    $infos = getimagesize(_PS_TMP_IMG_DIR_.$name);

                            if($infos[0] < 45){

                                ImageManager::thumbnail($path, $name, 45, 'jpg', true,true);

                            }

        				}

        				$product['image_tag'] = preg_replace(

        					'/\.*'.preg_quote(__PS_BASE_URI__, '/').'/',

        					_PS_ROOT_DIR_.DIRECTORY_SEPARATOR,

        					ImageManager::thumbnail($path, $name, 45, 'jpg', false),

        					1

        				);

        				if (Tools::file_exists_no_cache(_PS_TMP_IMG_DIR_.$name))

        					$product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);

        				else

        					$product['image_size'] = false;

        			}

                }

                $history = $order->getHistory($this->context->language->id);

                foreach ($history as &$order_state) {

                    $order_state['text-color'] = Tools::getBrightness($order_state['color']) < 128 ? 'white' : 'black';

                }

                $customer = new Customer($order->id_customer);

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

                $id_shop = Context::getContext()->shop->id;

                $data = array(

                    'shopname'  =>  $shopname,

                    'logo' =>$path_logo,

                    'currency' => new Currency($order->id_currency),

                    'customer'  =>  $customer,

                    'customerStats' => $customer->getStats(),

                    'addresses' => array(

                        'delivery' => $addressDelivery,

                        'deliveryState' => isset($deliveryState) ? $deliveryState : null,

                        'invoice' => $addressInvoice,

                        'invoiceState' => isset($invoiceState) ? $invoiceState : null

                    ),

                    'order' => $order,

                    'states' => OrderState::getOrderStates(Context::getContext()->language->id),

                    'history' => $history,

                    'products' => $products,

                    'notes'=>$this->getAllNoteOrder((int)$order->id,1),

                    'discounts' => $order->getCartRules(),

                    'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),

                );

                Context::getContext()->smarty->assign($data);

                $content = Context::getContext()->smarty->fetch($temp);

                $pdf->AddPage();

                $pdf->setAutoPageBreak(true);

                $pdf->writeHTML($content, true, false, true, false, '');

            }

            if (ob_get_level() && ob_get_length() > 0)

                ob_clean();

            $pdf->render('print_order_'.time().'.pdf','I');

            exit;

        }

        

    }

	public function displayVieworderproductLink($token = null, $id, $name = null)

    {

        $token;$id;$name;

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        Context::getContext()->smarty->assign(array(

            'item_type'=>'quickview'

        ));

        return Context::getContext()->smarty->fetch($tpl);

    }

    public function displayLoginascustomerLink($token = null, $id, $name = null)

    {

        $token;$name;

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        $id_customer = 0;

        $order = new Order((int)$id);

        if (Validate::isLoadedObject($order)) {

            $id_customer = $order->id_customer;

        }

        $gtoken = $this->module->getgToken($id_customer,$id);

        Context::getContext()->smarty->assign(array(

            'item_type'=>'loginascustomer',

            'id_order'=>(int)$id,

            'loginascustomer_link'=> $this->context->link->getModuleLink($this->module->name,'login',array('id_customer'=>$id_customer,'gtoken'=>$gtoken))

        ));

        return Context::getContext()->smarty->fetch($tpl);

    }

    public function displayDuplicateLink($token = null, $id, $name = null)

    {

        $token;$name;

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        Context::getContext()->smarty->assign(array(

            'item_type'=>'duplicate',

            'id'=>(int)$id

        ));

        return Context::getContext()->smarty->fetch($tpl);

    }

    public function renderView()

    {

        $order = new Order(Tools::getValue('id_order'));

        if (!Validate::isLoadedObject($order)) {

            $this->errors[] = Tools::displayError('The order cannot be found within your database.');

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



        // display warning if there are products out of stock

        $display_out_of_stock_warning = false;

        $current_order_state = $order->getCurrentOrderState();

        if (Configuration::get('PS_STOCK_MANAGEMENT') && (!Validate::isLoadedObject($current_order_state) || ($current_order_state->delivery != 1 && $current_order_state->shipped != 1))) {

            $display_out_of_stock_warning = true;

        }



        // products current stock (from stock_available)

        foreach ($products as &$product) {

            // Get total customized quantity for current product

            $customized_product_quantity = 0;



            if (is_array($product['customizedDatas'])) {

                foreach ($product['customizedDatas'] as $customizationPerAddress) {

                    foreach ($customizationPerAddress as  $customization) {

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



            // if the current stock requires a warning

            if ($product['current_stock'] <= 0 && $display_out_of_stock_warning) {

                $this->displayWarning($this->l('This product is out of stock: ').' '.$product['product_name']);

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

        }



        $gender = new Gender((int)$customer->id_gender, $this->context->language->id);



        $history = $order->getHistory($this->context->language->id);



        foreach ($history as &$order_state) {

            $order_state['text-color'] = Tools::getBrightness($order_state['color']) < 128 ? 'white' : 'black';

        }

        

        // from version 1.0.1

        $order_notes = $this->getAllNoteOrder((int)$order->id);

        // #from version 1.0.1



        // Smarty assign

        $this->tpl_view_vars = array(

            // from version 1.0.1

            'order_notes'=>$order_notes,

            // #from version 1.0.1

            

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

            'discounts' => $order->getCartRules(),

            'orders_total_paid_tax_incl' => $order->getOrdersTotalPaid(), // Get the sum of total_paid_tax_incl of the order with similar reference

            'total_paid' => $order->getTotalPaid(),

            'returns' => OrderReturn::getOrdersReturn($order->id_customer, $order->id),

            'customer_thread_message' => CustomerThread::getCustomerMessages($order->id_customer, null, $order->id),

            'orderMessages' => OrderMessage::getOrderMessages($order->id_lang),



            //tung update 13/12/2021

            // 'messages' => Message::getMessagesByOrderId($order->id, true),

            'messages' => CustomerMessage::getMessagesByOrderId($order->id, true),

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

            'current_index' => self::$currentIndex,

            'carrierModuleCall' => $carrier_module_call,

            'iso_code_lang' => $this->context->language->iso_code,

            'id_lang' => $this->context->language->id,

            'can_edit' => 1,

            'current_id_lang' => $this->context->language->id,

            'invoices_collection' => $order->getInvoicesCollection(),

            'not_paid_invoices_collection' => $order->getNotPaidInvoicesCollection(),

            'payment_methods' => $payment_methods,

            'invoice_management_active' => Configuration::get('PS_INVOICE', null, null, $order->id_shop),

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

            'order_token'=>Tools::getAdminToken('AdminOrders'),

            'branche_tpl_customized_data'=>_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_customized_data.tpl',

            'branche_tpl_product_line'=>_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_product_line.tpl',

            'branche_tpl_new_product'=> _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_new_product.tpl',

        );



        return parent::renderView();

    }

    public function renderForm()

    {

        if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {

                $this->errors[] = $this->l('You have to select a shop before creating new orders.');

            }

            $id_cart = (int)Tools::getValue('id_cart');

            $cart = new Cart((int)$id_cart);

            if ($id_cart && !Validate::isLoadedObject($cart)) {

                $this->errors[] = $this->l('This cart does not exists');

            }

            if ($id_cart && Validate::isLoadedObject($cart) && !$cart->id_customer) {

                $this->errors[] = $this->l('The cart must have a customer');

            }

            if (count($this->errors)) {

                return false;

            }

    

            parent::renderForm();

            $this->content = '';

            unset($this->toolbar_btn['save']);

            $this->addJqueryPlugin(array('autocomplete', 'fancybox', 'typewatch'));

    

            $defaults_order_state = array('cheque' => (int)Configuration::get('PS_OS_CHEQUE'),

                                                    'bankwire' => (int)Configuration::get('PS_OS_BANKWIRE'),

                                                    'cashondelivery' => Configuration::get('PS_OS_COD_VALIDATION') ? (int)Configuration::get('PS_OS_COD_VALIDATION') : (int)Configuration::get('PS_OS_PREPARATION'),

                                                    'other' => (int)Configuration::get('PS_OS_PAYMENT'));

            $payment_modules = array();

            foreach (PaymentModule::getInstalledPaymentModules() as $p_module) {

                $payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);

            }

            $idorder=(int)Tools::getValue('idorder');

            if($idorder >  0)

                $this->page_header_toolbar_title = $this->l('Duplicate Order #').$idorder;

            $this->context->smarty->assign(array(

                'recyclable_pack' => (int)Configuration::get('PS_RECYCLABLE_PACK'),

                'gift_wrapping' => (int)Configuration::get('PS_GIFT_WRAPPING'),

                'cart' => $cart,

                'currencies' => Currency::getCurrenciesByIdShop(Context::getContext()->shop->id),

                'langs' => Language::getLanguages(true, Context::getContext()->shop->id),

                'payment_modules' => $payment_modules,

                'order_states' => OrderState::getOrderStates((int)Context::getContext()->language->id),

                'defaults_order_state' => $defaults_order_state,

                'show_toolbar' => $this->show_toolbar,

                'toolbar_btn' => $this->toolbar_btn,

                'toolbar_scroll' => $this->toolbar_scroll,

                'PS_CATALOG_MODE' => Configuration::get('PS_CATALOG_MODE'),

                'title' => array($this->l('Orders'), $this->l('Create order')),

                'idorder'=>(int)Tools::getValue('idorder'),

            ));

            //$this->context->link->getAdminLink('AdminProducts')

            if(version_compare(_PS_VERSION_,'1.7.7','>='))

                $this->content .= $this->createTemplate('form_177x.tpl')->fetch();

            else

                $this->content .= $this->createTemplate('form.tpl')->fetch();

    }

    public function downloadCsv($idorders = array())

    {

        $ordermanagerObj = Module::getInstanceByName('g_ordermanager');

        $orderFields = $ordermanagerObj->getOrderField();

        $field_separator = Configuration::getGlobalValue('G_CSV_SEPARATOR');

        $multiple_separator = Configuration::getGlobalValue('G_CSV_MULTI_SEPARATOR');

        $order_fields_position = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS_POSITION');

        $order_fields = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS');

        $order_fields_position = Tools::jsonDecode($order_fields_position,true);

        $order_fields = Tools::jsonDecode($order_fields,true);

        $fieldexport = array_keys($order_fields);

        if($fieldexport){}else die(Tools::displayError('Please check config field to export.'));

        if($fieldexport)

        foreach($orderFields as $_orderField){

            if($_orderField && isset($_orderField['cols'])){

                foreach($_orderField['cols'] as $orderfield){

                    if(in_array($orderfield['key'],$fieldexport))

                        $order_fields_position[$orderfield['key']] = $orderfield['name'];  

                    else unset($order_fields_position[$orderfield['key']]);

                }

            }

        }

               





        if($field_separator == '') $field_separator = ';';

        if($multiple_separator == '') $multiple_separator = ',';

        $idorders = array_map('intval',$idorders);

        if($idorders){
            header('Content-Type: application/csv; charset=UTF-8');

			header('Content-Disposition: inline; filename="orders.csv"');

			header('Cache-Control: private, max-age=0, must-revalidate');
            header("Content-Transfer-Encoding: binary");
            echo $this->getcsvline($order_fields_position,$field_separator,$multiple_separator);

            $sql = 'SELECT ';

            if($order_fields)

            foreach(array_keys($order_fields) as $order_field){

                switch ($order_field) {

                    case 'id_order':

                        $sql .= 'a.id_order,';

                        break;

                    case 'id_country':

                        $sql .= 'address.id_country,';

                        break;

                    case 'id_customer':

                        $sql .= 'c.id_customer,';

                        break;  

                   case 'id_address':

                   case 'id_state':

                   case 'firstname':

                   case 'lastname':

                   case 'address1':

                   case 'address2':

                   case 'postcode':

                   case 'city':

                   case 'company':

                   case 'other':

                   case 'phone':

                   case 'phone_mobile':

                        $sql .= 'address.'.pSql($order_field).',';

                        break;    

                    case 'date_add':

                        $sql .= 'a.`date_add` AS `date_add`,';

                        break;

                    case 'id_currency':

                        $sql .= 'a.id_currency,';

                        break;

                    case 'id_pdf':

                        $sql .= 'a.id_order AS id_pdf,';

                        break;

                    case 'customer':

                        $sql .= 'CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,';

                        break;

                    case 'email':

                        $sql .= 'c.email,';

                        break;

                    case 'osname':

                        $sql .= 'osl.`name` AS `osname`,';

                        break;

                    case 'color':

                        $sql .= 'os.`color`,';

                        break;

                    case 'caname':

                        $sql .= 'ca.`name` as `caname`,';

                        break;

                    case 'new':

                        $sql .= 'IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new,';

                        break;

                    case 'cname':

                        $sql .= 'country_lang.name as cname,';

                        break;

                    case 'badge_success':

                        $sql .= 'IF(a.valid, 1, 0) badge_success,';

                        break;

                    case 'shop_name':

                        $sql .= 'shop.name AS shop_name,';

                        break;

                    default:

                        $sql .= pSql($order_field).',';

                        break;

                }

            }

            $orderlist = array();

            if($sql !='SELECT '){

                $sql = Tools::substr($sql, 0, -1); // remove last ,

                $sql .= ' FROM `'._DB_PREFIX_.'orders` a

                        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)

        		INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = a.id_address_delivery

        		INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country

        		INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$this->context->language->id.')

        		LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)

        		LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')

                LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = a.`id_carrier`)                 

                ';           

                $cod_keys = array(

                    'product_id','product_attribute_id','product_name','product_reference','product_ean13',

                    'product_quantity','product_quantity_in_stock','product_weight','product_price',

                    'unit_price_tax_incl','unit_price_tax_excl','total_price_tax_incl','total_price_tax_excl'

                );

                $cod_join = array();

                foreach($cod_keys as $cod_key){

                    if(in_array('cod_'.$cod_key,array_keys($order_fields)))

                        $cod_join[] = $cod_key;

                }

                if($cod_join)

                {

                    $sql .= 'LEFT JOIN (

                                SELECT id_order,';

                    foreach($cod_join as $c_join){

                        $sql .= ' GROUP_CONCAT('.pSql($c_join).' SEPARATOR "'.pSql($multiple_separator).'") AS cod_'.pSql($c_join).',';   

                    } 

                    $sql = Tools::substr($sql, 0, -1); // remove last , key

                    $sql .= '

                            FROM `'._DB_PREFIX_.'order_detail` group by id_order

                            ) cod ON (a.id_order = cod.id_order)';

                }

                if(in_array('message',array_keys($order_fields)))

                {

                    $sql .= 'LEFT JOIN (

                                SELECT id_order,message

                                FROM `'._DB_PREFIX_.'message`

                                group by id_order

                                ORDER BY date_add ASC

                                ) omes ON (a.id_order = omes.id_order)';

                }

                $sql .='

                        LEFT JOIN '._DB_PREFIX_.'shop shop ON a.id_shop = shop.id_shop

                        WHERE a.id_order IN ('.pSQL(implode(',',$idorders)).')

                        ORDER BY a.`id_order`' ;

                $orderlist = Db::getInstance()->ExecuteS($sql);

            }else{

                $orderlist = array();

            }

            

            foreach($orderlist AS $order){

				echo $this->getcsvline($order,$field_separator,$multiple_separator);

			}

            die();

        }

    }
    public function downloadCsvExtra($idorders = array(),$type_export='')

    {
        $ordermanagerObj = Module::getInstanceByName('g_ordermanager');

        $orderFields = $ordermanagerObj->getOrderField();

        $field_separator = Configuration::getGlobalValue('G_CSV_SEPARATOR');
        
        $multiple_separator = Configuration::getGlobalValue('G_CSV_MULTI_SEPARATOR');

        $order_fields_position = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS_POSITION');

        $order_fields = Configuration::getGlobalValue('G_CSV_ORDER_FIELDS');

        $order_fields_position = Tools::jsonDecode($order_fields_position,true);

        $order_fields = Tools::jsonDecode($order_fields,true);

        $fieldexport = array_keys($order_fields);

        if($fieldexport){}else die(Tools::displayError('Please check config field to export.'));

        if($fieldexport)

        foreach($orderFields as $_orderField){

            if($_orderField && isset($_orderField['cols'])){

                foreach($_orderField['cols'] as $orderfield){

                    if(in_array($orderfield['key'],$fieldexport))

                        $order_fields_position[$orderfield['key']] = $orderfield['name'];  

                    else unset($order_fields_position[$orderfield['key']]);

                }

            }

        }

               



        if($type_export == 'submitExportOrderCsv1'){
            $order_fields_position=array(
                'VALIDADOR' => 'VALIDADOR',
                'PRODUCTO'=> 'PRODUCTO',
                'CANTIDAD'=> 'CANTIDAD',
                'PESO'=> 'PESO',
                'PESO_TOTAL'=> 'PESO_TOTAL',
                'PRECIO'=> 'PRECIO'
            );
        }
        elseif($type_export == 'submitExportOrderCsv2'){
            $order_fields_position=array(
                'PRODUCTO'=> 'PRODUCTO',
                'CANTIDAD'=> 'CANTIDAD',
                'PESO'=> 'PESO',
                'PESO_TOTAL'=> 'PESO_TOTAL',
                'PRECIO'=> 'PRECIO'
            );
        }

        if($field_separator == '') $field_separator = ';';

        if($multiple_separator == '') $multiple_separator = ',';

        $idorders = array_map('intval',$idorders);

        if($idorders){
            header('Content-Type: application/csv; charset=UTF-8');

			header('Content-Disposition: inline; filename="orders.csv"');

			header('Cache-Control: private, max-age=0, must-revalidate');
            header("Content-Transfer-Encoding: binary");
            echo $this->getcsvline($order_fields_position,$field_separator,$multiple_separator);

            
            if($order_fields)

            $orderlist = array();

         
            $orderlist = array();
                if($type_export == 'submitExportOrderCsv1'){

                    $sql = 'SELECT c.validator as VALIDADOR, 
                    d.product_name AS PRODUCTO, 
                    SUM(d.product_quantity) AS CANTIDAD, 
                    d.product_weight AS PESO, 
                    d.product_weight*SUM(d.product_quantity) AS PESO_TOTAL,
                     d.product_price*SUM(d.product_quantity) AS PRECIO 
                    FROM `'._DB_PREFIX_.'orders` o LEFT JOIN `'._DB_PREFIX_.'customer` c ON (o.id_customer = c.id_customer) LEFT JOIN psgv_order_detail d ON (o.id_order = d.id_order) 
                    WHERE o.id_order IN('.pSQL(implode(',',$idorders)).')
                    GROUP BY c.validator, d.product_name'; // remove last ,
                }
                elseif($type_export == 'submitExportOrderCsv2'){

                    $sql ='SELECT d.product_name AS PRODUCTO, 
                    SUM(d.product_quantity) AS CANTIDAD, 
                    d.product_weight AS PESO, 
                    d.product_weight*SUM(d.product_quantity) AS PESO_TOTAL, 
                    d.product_price*SUM(d.product_quantity) AS PRECIO
                    FROM `'._DB_PREFIX_.'order_detail` d WHERE d.id_order IN('.pSQL(implode(',',$idorders)).')  GROUP BY d.product_name';
                }



                $orderlist = Db::getInstance()->ExecuteS($sql);
           

            

            foreach($orderlist AS $order){
                if(isset($order['PESO'])) 
                    $order['PESO']=number_format($order['PESO'],2);
                if(isset($order['PESO_TOTAL'])) 
                    $order['PESO_TOTAL']=number_format($order['PESO_TOTAL'],2);
                if(isset($order['PRECIO'])) 
                    $order['PRECIO']=number_format($order['PRECIO'],2);
              
				echo $this->getcsvline($order,$field_separator,$multiple_separator);

			}

            die();

        }

    }
    private function getcsvline($list,$field_separator,$multiple_separator){

		foreach($list as &$l){

		    if(strpos(addslashes($l), $multiple_separator) === false && strpos(addslashes($l), ' ') === false)

			   $l=addslashes($l);

            else $l='"'.addslashes($l).'"';

		}

		return implode($field_separator, $list)."\r\n";

	}

	

    public function initProcess()

    {

        if(Module::isInstalled('g_ordermanager') && Module::isEnabled('g_ordermanager') )

        {

            if (Tools::getIsset('duplicate'.$this->table)) {

                $id_order = (int)Tools::getValue('id_order');

                $idcart = (int)$this->duplicateCart($id_order,false);

                if($idcart){

                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders').'&id_cart='.$idcart.'&idorder='.$id_order.'&addorder');

                }else

                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));

            }

        }

        return parent::initProcess();

    }

	

    public function ajaxProcessLoadProductInformation()

    {

        $order_detail = new OrderDetail(Tools::getValue('id_order_detail'));

        if (!Validate::isLoadedObject($order_detail)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The OrderDetail object cannot be loaded.')

            )));

        }



        $product = new Product($order_detail->product_id);

        if (!Validate::isLoadedObject($product)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The product object cannot be loaded.')

            )));

        }



        $address = new Address(Tools::getValue('id_address'));

        if (!Validate::isLoadedObject($address)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The address object cannot be loaded.')

            )));

        }



        die(Tools::jsonEncode(array(

            'result' => true,

            'product' => $product,

            'tax_rate' => $product->getTaxesRate($address),

            'price_tax_incl' => Product::getPriceStatic($product->id, true, $order_detail->product_attribute_id, 2),

            'price_tax_excl' => Product::getPriceStatic($product->id, false, $order_detail->product_attribute_id, 2),

            'reduction_percent' => $order_detail->reduction_percent

        )));

    }

    protected function applyDiscountOnInvoice($order_invoice, $value_tax_incl, $value_tax_excl)

    {

        // Update OrderInvoice

        $order_invoice->total_discount_tax_incl += $value_tax_incl;

        $order_invoice->total_discount_tax_excl += $value_tax_excl;

        $order_invoice->total_paid_tax_incl -= $value_tax_incl;

        $order_invoice->total_paid_tax_excl -= $value_tax_excl;

        $order_invoice->update();

    }

    public function ajaxProcessAddProductOnOrder()

    {

        // Load object

        $order = new Order((int)Tools::getValue('id_order'));

        if (!Validate::isLoadedObject($order)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The order object cannot be loaded.')

            )));

        }

        



        $old_cart_rules = Context::getContext()->cart->getCartRules();



        $product_informations = Tools::getValue('add_product');

        $invoice_informations = array();

        if (Tools::getIsset('add_invoice')) {

            $invoice_informations = Tools::getValue('add_invoice');

        }

        $product = new Product($product_informations['product_id'], false, $order->id_lang);

        if (!Validate::isLoadedObject($product)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The product object cannot be loaded.')

            )));

        }

        $product_attribute_id = 0;

        if (isset($product_informations['product_attribute_id']) && $product_informations['product_attribute_id']) {

            $combination = new Combination($product_informations['product_attribute_id']);

            if (!Validate::isLoadedObject($combination)) {

                die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The combination object cannot be loaded.')

            )));

            }

            $product_attribute_id = (int)$product_informations['product_attribute_id'];

        }

        

        //check combination exist in order

        $orderDetailObj = $order->getOrderDetailList();

        $_id_order_detail = -1;

        if($orderDetailObj){

            foreach($orderDetailObj as $orderDetailItem){

                if($orderDetailItem['product_id'] == $product_informations['product_id'] && $orderDetailItem['product_attribute_id'] == $product_attribute_id){

                    $_id_order_detail = $orderDetailItem['id_order_detail'];

                    break;

                }

            }

        }

        if($_id_order_detail > 0){

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('Product already exist in order.')

            )));

        }

        //#



        // Total method

        $total_method = Cart::BOTH_WITHOUT_SHIPPING;



        // Create new cart

        $cart = new Cart();

        $cart->id_shop_group = $order->id_shop_group;

        $cart->id_shop = $order->id_shop;

        $cart->id_customer = $order->id_customer;

        $cart->id_carrier = $order->id_carrier;

        $cart->id_address_delivery = $order->id_address_delivery;

        $cart->id_address_invoice = $order->id_address_invoice;

        $cart->id_currency = $order->id_currency;

        $cart->id_lang = $order->id_lang;

        $cart->secure_key = $order->secure_key;



        // Save new cart

        $cart->add();



        // Save context (in order to apply cart rule)

        $this->context->cart = $cart;

        $this->context->customer = new Customer($order->id_customer);



        // always add taxes even if there are not displayed to the customer

        $use_taxes = true;



        $initial_product_price_tax_incl = Product::getPriceStatic($product->id, $use_taxes, isset($combination) ? $combination->id : null, 2, null, false, true, 1,

            false, $order->id_customer, $cart->id, $order->{Configuration::get('PS_TAX_ADDRESS_TYPE', null, null, $order->id_shop)});



        // Creating specific price if needed

        if ($product_informations['product_price_tax_incl'] != $initial_product_price_tax_incl) {

            $specific_price = new SpecificPrice();

            $specific_price->id_shop = 0;

            $specific_price->id_shop_group = 0;

            $specific_price->id_currency = 0;

            $specific_price->id_country = 0;

            $specific_price->id_group = 0;

            $specific_price->id_customer = $order->id_customer;

            $specific_price->id_product = $product->id;

            if (isset($combination)) {

                $specific_price->id_product_attribute = $combination->id;

            } else {

                $specific_price->id_product_attribute = 0;

            }

            $specific_price->price = $product_informations['product_price_tax_excl'];

            $specific_price->from_quantity = 1;

            $specific_price->reduction = 0;

            $specific_price->reduction_type = 'amount';

            $specific_price->reduction_tax = 0;

            $specific_price->from = '0000-00-00 00:00:00';

            $specific_price->to = '0000-00-00 00:00:00';

            $specific_price->add();

        }



        // Add product to cart

        $update_quantity = $cart->updateQty($product_informations['product_quantity'], $product->id, isset($product_informations['product_attribute_id']) ? $product_informations['product_attribute_id'] : null,

            isset($combination) ? $combination->id : null, 'up', 0, new Shop($cart->id_shop));



        if ($update_quantity < 0) {

            // If product has attribute, minimal quantity is set with minimal quantity of attribute

            $minimal_quantity = ($product_informations['product_attribute_id']) ? Attribute::getAttributeMinimalQty($product_informations['product_attribute_id']) : $product->minimal_quantity;

            die(Tools::jsonEncode(array('error' => sprintf(Tools::displayError('You must add %d minimum quantity', false), $minimal_quantity))));

        } elseif (!$update_quantity) {

            die(Tools::jsonEncode(array('error' => Tools::displayError('You already have the maximum quantity available for this product.', false))));

        }



        // If order is valid, we can create a new invoice or edit an existing invoice

        if ($order->hasInvoice()) {

            $order_invoice = new OrderInvoice($product_informations['invoice']);

            // Create new invoice

            if ($order_invoice->id == 0) {

                // If we create a new invoice, we calculate shipping cost

                $total_method = Cart::BOTH;

                // Create Cart rule in order to make free shipping

                if (isset($invoice_informations['free_shipping']) && $invoice_informations['free_shipping']) {

                    $cart_rule = new CartRule();

                    $cart_rule->id_customer = $order->id_customer;

                    $cart_rule->name = array(

                        Configuration::get('PS_LANG_DEFAULT') => $this->l('[Generated] CartRule for Free Shipping')

                    );

                    $cart_rule->date_from = date('Y-m-d H:i:s', time());

                    $cart_rule->date_to = date('Y-m-d H:i:s', time() + 24 * 3600);

                    $cart_rule->quantity = 1;

                    $cart_rule->quantity_per_user = 1;

                    $cart_rule->minimum_amount_currency = $order->id_currency;

                    $cart_rule->reduction_currency = $order->id_currency;

                    $cart_rule->free_shipping = true;

                    $cart_rule->active = 1;

                    $cart_rule->add();



                    // Add cart rule to cart and in order

                    $cart->addCartRule($cart_rule->id);

                    $values = array(

                        'tax_incl' => $cart_rule->getContextualValue(true),

                        'tax_excl' => $cart_rule->getContextualValue(false)

                    );

                    $order->addCartRule($cart_rule->id, $cart_rule->name[Configuration::get('PS_LANG_DEFAULT')], $values);

                }



                $order_invoice->id_order = $order->id;

                if ($order_invoice->number) {

                    Configuration::updateValue('PS_INVOICE_START_NUMBER', false, false, null, $order->id_shop);

                } else {

                    $order_invoice->number = Order::getLastInvoiceNumber() + 1;

                }



                $invoice_address = new Address((int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE', null, null, $order->id_shop)});

                $carrier = new Carrier((int)$order->id_carrier);

                $tax_calculator = $carrier->getTaxCalculator($invoice_address);



                $order_invoice->total_paid_tax_excl = Tools::ps_round((float)$cart->getOrderTotal(false, $total_method), 2);

                $order_invoice->total_paid_tax_incl = Tools::ps_round((float)$cart->getOrderTotal($use_taxes, $total_method), 2);

                $order_invoice->total_products = (float)$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

                $order_invoice->total_products_wt = (float)$cart->getOrderTotal($use_taxes, Cart::ONLY_PRODUCTS);

                $order_invoice->total_shipping_tax_excl = (float)$cart->getTotalShippingCost(null, false);

                $order_invoice->total_shipping_tax_incl = (float)$cart->getTotalShippingCost();



                $order_invoice->total_wrapping_tax_excl = abs($cart->getOrderTotal(false, Cart::ONLY_WRAPPING));

                $order_invoice->total_wrapping_tax_incl = abs($cart->getOrderTotal($use_taxes, Cart::ONLY_WRAPPING));

                $order_invoice->shipping_tax_computation_method = (int)$tax_calculator->computation_method;



                // Update current order field, only shipping because other field is updated later

                $order->total_shipping += $order_invoice->total_shipping_tax_incl;

                $order->total_shipping_tax_excl += $order_invoice->total_shipping_tax_excl;

                $order->total_shipping_tax_incl += ($use_taxes) ? $order_invoice->total_shipping_tax_incl : $order_invoice->total_shipping_tax_excl;



                $order->total_wrapping += abs($cart->getOrderTotal($use_taxes, Cart::ONLY_WRAPPING));

                $order->total_wrapping_tax_excl += abs($cart->getOrderTotal(false, Cart::ONLY_WRAPPING));

                $order->total_wrapping_tax_incl += abs($cart->getOrderTotal($use_taxes, Cart::ONLY_WRAPPING));

                $order_invoice->add();



                $order_invoice->saveCarrierTaxCalculator($tax_calculator->getTaxesAmount($order_invoice->total_shipping_tax_excl));



                $order_carrier = new OrderCarrier();

                $order_carrier->id_order = (int)$order->id;

                $order_carrier->id_carrier = (int)$order->id_carrier;

                $order_carrier->id_order_invoice = (int)$order_invoice->id;

                $order_carrier->weight = (float)$cart->getTotalWeight();

                $order_carrier->shipping_cost_tax_excl = (float)$order_invoice->total_shipping_tax_excl;

                $order_carrier->shipping_cost_tax_incl = ($use_taxes) ? (float)$order_invoice->total_shipping_tax_incl : (float)$order_invoice->total_shipping_tax_excl;

                $order_carrier->add();

            }

            // Update current invoice

            else {

                $order_invoice->total_paid_tax_excl += Tools::ps_round((float)($cart->getOrderTotal(false, $total_method)), 2);

                $order_invoice->total_paid_tax_incl += Tools::ps_round((float)($cart->getOrderTotal($use_taxes, $total_method)), 2);

                $order_invoice->total_products += (float)$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

                $order_invoice->total_products_wt += (float)$cart->getOrderTotal($use_taxes, Cart::ONLY_PRODUCTS);

                $order_invoice->update();

            }

        }



        // Create Order detail information

        $order_detail = new OrderDetail();

        $order_detail->createList($order, $cart, $order->getCurrentOrderState(), $cart->getProducts(), (isset($order_invoice) ? $order_invoice->id : 0), $use_taxes, (int)Tools::getValue('add_product_warehouse'));



        // update totals amount of order

        $order->total_products += (float)$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

        $order->total_products_wt += (float)$cart->getOrderTotal($use_taxes, Cart::ONLY_PRODUCTS);



        $order->total_paid += Tools::ps_round((float)($cart->getOrderTotal(true, $total_method)), 2);

        $order->total_paid_tax_excl += Tools::ps_round((float)($cart->getOrderTotal(false, $total_method)), 2);

        $order->total_paid_tax_incl += Tools::ps_round((float)($cart->getOrderTotal($use_taxes, $total_method)), 2);



        if (isset($order_invoice) && Validate::isLoadedObject($order_invoice)) {

            $order->total_shipping = $order_invoice->total_shipping_tax_incl;

            $order->total_shipping_tax_incl = $order_invoice->total_shipping_tax_incl;

            $order->total_shipping_tax_excl = $order_invoice->total_shipping_tax_excl;

        }

        // discount

        $order->total_discounts += (float)abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));

        $order->total_discounts_tax_excl += (float)abs($cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS));

        $order->total_discounts_tax_incl += (float)abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));



        // Save changes of order

        $order->update();



        // Update weight SUM

        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

        if (Validate::isLoadedObject($order_carrier)) {

            $order_carrier->weight = (float)$order->getTotalWeight();

            if ($order_carrier->update()) {

                $order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);

            }

        }



        // Update Tax lines

        $order_detail->updateTaxAmount($order);



        // Delete specific price if exists

        if (isset($specific_price)) {

            $specific_price->delete();

        }



        $products = $this->getProducts($order);



        // Get the last product

        $product = end($products);

        $resume = OrderSlip::getProductSlipResume((int)$product['id_order_detail']);

        $product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];

        $product['amount_refundable'] = $product['total_price_tax_excl'] - $resume['amount_tax_excl'];

        $product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl']);

        $product['return_history'] = OrderReturn::getProductReturnDetail((int)$product['id_order_detail']);

        $product['refund_history'] = OrderSlip::getProductSlipDetail((int)$product['id_order_detail']);

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

            'can_edit' => 1,

            'invoices_collection' => $invoice_collection,

            'current_id_lang' => Context::getContext()->language->id,

            'link' => Context::getContext()->link,

            'current_index' => self::$currentIndex,

            'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')

        ));



        $this->sendChangedNotification($order);

        $new_cart_rules = Context::getContext()->cart->getCartRules();

        sort($old_cart_rules);

        sort($new_cart_rules);

        $result = array_diff($new_cart_rules, $old_cart_rules);

        $refresh = false;



        $res = true;

        foreach ($result as $cart_rule) {

            $refresh = true;

            // Create OrderCartRule

            $rule = new CartRule($cart_rule['id_cart_rule']);

            $values = array(

                    'tax_incl' => $rule->getContextualValue(true),

                    'tax_excl' => $rule->getContextualValue(false)

                    );

            $order_cart_rule = new OrderCartRule();

            $order_cart_rule->id_order = $order->id;

            $order_cart_rule->id_cart_rule = $cart_rule['id_cart_rule'];

            $order_cart_rule->id_order_invoice = (isset($order_invoice) ? $order_invoice->id : 0);//$order_invoice->id;

            $order_cart_rule->name = $cart_rule['name'];

            $order_cart_rule->value = $values['tax_incl'];

            $order_cart_rule->value_tax_excl = $values['tax_excl'];

            $res &= $order_cart_rule->add();



            $order->total_discounts += $order_cart_rule->value;

            $order->total_discounts_tax_incl += $order_cart_rule->value;

            $order->total_discounts_tax_excl += $order_cart_rule->value_tax_excl;

            $order->total_paid -= $order_cart_rule->value;

            $order->total_paid_tax_incl -= $order_cart_rule->value;

            $order->total_paid_tax_excl -= $order_cart_rule->value_tax_excl;

        }



        // Update Order

        $res &= $order->update();





        die(Tools::jsonEncode(array(

            'result' => true,

            'view' => Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_product_line.tpl'),//$this->createTemplate('_product_line.tpl')->fetch(),

            'can_edit' => 1,

            'order' => $order,

            'invoices' => $invoice_array,

            'documents_html' => $this->createTemplate('_documents.tpl')->fetch(),

            'shipping_html' => $this->createTemplate('_shipping.tpl')->fetch(),

            'discount_form_html' => $this->createTemplate('_discount_form.tpl')->fetch(),

            'refresh' => $refresh

        )));

    }

    

    public function ajaxProcessEditProductOnOrder()

    {

            $res = true;

            $order = new Order((int)Tools::getValue('id_order'));

            $order_detail = new OrderDetail((int)Tools::getValue('product_id_order_detail'));

            $product_attribute_id = (int)Tools::getValue('product_attribute_id');

            if($product_attribute_id != $order_detail->product_attribute_id){

                //check combination exist in order

                $orderDetailObj = $order->getOrderDetailList();

                $_id_order_detail = -1;

                if($orderDetailObj){

                    foreach($orderDetailObj as $orderDetailItem){

                        if($orderDetailItem['product_id'] == $order_detail->product_id && $orderDetailItem['product_attribute_id'] == $product_attribute_id){

                            $_id_order_detail = $orderDetailItem['id_order_detail'];

                            break;

                        }

                    }

                }

                if($_id_order_detail > 0){

                    die(Tools::jsonEncode(array(

                        'result' => false,

                        'error' => Tools::displayError('Combination already exist in order.')

                    )));

                }

                $order_detail->product_attribute_id = (int)$product_attribute_id;

            }

            

            if (Tools::isSubmit('product_invoice')) {

                $order_invoice = new OrderInvoice((int)Tools::getValue('product_invoice'));

            }

            $this->doEditProductValidation($order_detail, $order, isset($order_invoice) ? $order_invoice : null);

            

            $product_quantity = 0;

            if (is_array(Tools::getValue('product_quantity'))) {

                foreach (Tools::getValue('product_quantity') as $id_customization => $qty) {

                    Db::getInstance()->update('customization', array('quantity' => (int)$qty), 'id_customization = '.(int)$id_customization);

                    $product_quantity += $qty;

                }

            } else {

                $product_quantity = Tools::getValue('product_quantity');

            }

            

            

            

            

            $product_name = Tools::getValue('productname');        

            

            $order_detail->product_name = $product_name;   

            $sql = array(); 

            if (is_array(Tools::getValue('customization_remove'))) {

                foreach (Tools::getValue('customization_remove') as $id_customization => $customization) {

                    foreach ($customization as $id_index) {

                        $_sql = 'SELECT value FROM ' . _DB_PREFIX_ . 'customized_data WHERE id_customization='.(int)$id_customization.' AND  `index`='.(int)$id_index;

                        $_old_filename = Db::getInstance()->getRow($_sql);

                        if($_old_filename)

                        {

                            if($_old_filename['value'] !='')

                            {

                                if(file_exists(_PS_UPLOAD_DIR_.$_old_filename['value'])) unlink(_PS_UPLOAD_DIR_.$_old_filename['value']);

                                if(file_exists(_PS_UPLOAD_DIR_.$_old_filename['value'].'_small')) unlink(_PS_UPLOAD_DIR_.$_old_filename['value'].'_small');

                            }

                            $sql[] = 'UPDATE ' . _DB_PREFIX_ . 'customized_data SET value = "" WHERE id_customization='.(int)$id_customization.' AND  `index`='.(int)$id_index;

                        }

                        

                    }

                }

                

            }

            $arrayid_customization = array();

            if (is_array(Tools::getValue('customization'))) {

                

                foreach (Tools::getValue('customization') as $id_customization => $customization) {

                    foreach ($customization as $id_index => $data) {

                        $arrayid_customization[(int)$id_customization] = (int)$id_customization;

                        $sql[] = 'UPDATE ' . _DB_PREFIX_ . 'customized_data SET value = "'.pSQL($data).'" WHERE id_customization='.(int)$id_customization.' AND  `index`='.(int)$id_index;

                    }

                }

                

            }

            if($arrayid_customization){

                $excludeIds = implode(',', array_map('intval', $arrayid_customization));

                if($product_attribute_id != $order_detail->product_attribute_id){

                    //change attribute id > customization

                    $sql[] = 'UPDATE ' . _DB_PREFIX_ . 'customization SET id_product_attribute ='.(int)$product_attribute_id.' WHERE id_customization IN('.pSQL($excludeIds).')'; // $excludeIds join array int

                    $order_detail->product_attribute_id = (int)$product_attribute_id;

                }

            }

            if(isset($_FILES['file']))

                if($_FILES['file']['name']){

                    $product_picture_width = (int)Configuration::get('PS_PRODUCT_PICTURE_WIDTH');

                    $product_picture_height = (int)Configuration::get('PS_PRODUCT_PICTURE_HEIGHT');

                    foreach (array_keys($_FILES['file']['name']) as $key) {

                        $file_name = md5(uniqid(rand(), true));

                        $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');

                        if (!$tmp_name || !move_uploaded_file($_FILES['file']['tmp_name'][$key], $tmp_name)) {

                            return false;

                        }

                        if (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name)) {

                            $this->errors[] = Tools::displayError('An error occurred during the image upload process.');

                        }elseif (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name.'_small', $product_picture_width, $product_picture_height)) {

                            $this->errors[] = Tools::displayError('An error occurred during the image upload process.');

                        } elseif (!chmod(_PS_UPLOAD_DIR_.$file_name, 0777) || !chmod(_PS_UPLOAD_DIR_.$file_name.'_small', 0777)) {

                            $this->errors[] = Tools::displayError('An error occurred during the image upload process.');

                        } else {

                            $id = explode('_',$key);

                            $_sql = 'SELECT value FROM ' . _DB_PREFIX_ . 'customized_data WHERE id_customization='.pSQL($id[0]).' AND  `index`='.pSQL($id[1]);

                            $_old_filename = Db::getInstance()->getRow($_sql);

                            if($_old_filename)

                            {

                                if($_old_filename['value'] !='')

                                {

                                    if(file_exists(_PS_UPLOAD_DIR_.$_old_filename['value'])) unlink(_PS_UPLOAD_DIR_.$_old_filename['value']);

                                    if(file_exists(_PS_UPLOAD_DIR_.$_old_filename['value'].'_small')) unlink(_PS_UPLOAD_DIR_.$_old_filename['value'].'_small');

                                }

                                $sql[] = 'UPDATE ' . _DB_PREFIX_ . 'customized_data SET value = "'.pSQL($file_name).'" WHERE id_customization='.pSQL($id[0]).' AND  `index`='.pSQL($id[1]);

                            }else{

                                $sql[] = 'INSERT INTO ' . _DB_PREFIX_ . 'customized_data(`id_customization`,`type`,`index`,`value`) VALUES('.pSQL($id[0]).','.pSQL(Product::CUSTOMIZE_FILE).','.pSQL($id[1]).',"'.pSQL($file_name).'")';

                            }

                        }

                    }

                }

                

            if($sql)

                    Db::getInstance()->execute(implode(';',$sql));

                    

            

            $product_price_tax_incl = Tools::ps_round(Tools::getValue('product_price_tax_incl'), 2);

            $product_price_tax_excl = Tools::ps_round(Tools::getValue('product_price_tax_excl'), 2);

            $total_products_tax_incl = $product_price_tax_incl * $product_quantity;

            $total_products_tax_excl = $product_price_tax_excl * $product_quantity;

    

            $diff_price_tax_incl = $total_products_tax_incl - $order_detail->total_price_tax_incl;

            $diff_price_tax_excl = $total_products_tax_excl - $order_detail->total_price_tax_excl;

    

            if (isset($order_invoice)) {

                if ($order_detail->id_order_invoice != $order_invoice->id) {

                    $old_order_invoice = new OrderInvoice($order_detail->id_order_invoice);

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

                    $order_invoice->total_products += $diff_price_tax_excl;

                    $order_invoice->total_products_wt += $diff_price_tax_incl;

    

                    $order_invoice->total_paid_tax_excl += $diff_price_tax_excl;

                    $order_invoice->total_paid_tax_incl += $diff_price_tax_incl;

                }

    

                $order = new Order($order_detail->id_order);

                $order->total_products += $diff_price_tax_excl;

                $order->total_products_wt += $diff_price_tax_incl;

    

                $order->total_paid += $diff_price_tax_incl;

                $order->total_paid_tax_excl += $diff_price_tax_excl;

                $order->total_paid_tax_incl += $diff_price_tax_incl;

    

                $res &= $order->update();

            }

    

            $old_quantity = $order_detail->product_quantity;

    

            $order_detail->product_quantity = $product_quantity;

            $order_detail->reduction_percent = 0;

    

            $res &= $order_detail->updateTaxAmount($order);

    

            $res &= $order_detail->update();

    

            $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

            if (Validate::isLoadedObject($order_carrier)) {

                $order_carrier->weight = (float)$order->getTotalWeight();

                $res &= $order_carrier->update();

                if ($res) {

                    $order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);

                }

            }

    

            if (isset($order_invoice)) {

                $res &= $order_invoice->update();

            }

    

            StockAvailable::updateQuantity($order_detail->product_id, $order_detail->product_attribute_id, ($old_quantity - $order_detail->product_quantity), $order->id_shop);

    

            $products = $this->getProducts($order);

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

    

            $invoice_collection = $order->getInvoicesCollection();

    

            $invoice_array = array();

            foreach ($invoice_collection as $invoice) {

                

                $invoice->name = $invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);

                $invoice_array[] = $invoice;

            }

    

            $this->context->smarty->assign(array(

                'product' => $product,

                'order' => $order,

                'currency' => new Currency($order->id_currency),

                'can_edit' => 1,

                'invoices_collection' => $invoice_collection,

                'current_id_lang' => Context::getContext()->language->id,

                'link' => Context::getContext()->link,

                'current_index' => self::$currentIndex,

                'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')

            ));

    

            if (!$res) {

                die(Tools::jsonEncode(array(

                    'result' => $res,

                    'error' => Tools::displayError('An error occurred while editing the product line.')

                )));

            }

            if (is_array(Tools::getValue('product_quantity'))) {

                $view = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_customized_data.tpl');//$this->createTemplate('_customized_data.tpl')->fetch();

            } else {

                $view = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/_product_line.tpl');//$this->createTemplate('_product_line.tpl')->fetch();

            }

    

            $this->sendChangedNotification($order);

    

            die(Tools::jsonEncode(array(

                'result' => $res,

                'view' => $view,

                'can_edit' => 1,

                'invoices_collection' => $invoice_collection,

                'order' => $order,

                'invoices' => $invoice_array,

                'documents_html' => $this->createTemplate('_documents.tpl')->fetch(),

                'shipping_html' => $this->createTemplate('_shipping.tpl')->fetch(),

                'customized_product' => is_array(Tools::getValue('product_quantity'))

            )));

    }

	protected function doEditProductValidation(OrderDetail $order_detail, Order $order, OrderInvoice $order_invoice = null)

    {

        if (!Validate::isLoadedObject($order_detail)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The Order Detail object could not be loaded.')

            )));

        }



        if (!empty($order_invoice) && !Validate::isLoadedObject($order_invoice)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The invoice object cannot be loaded.')

            )));

        }



        if (!Validate::isLoadedObject($order)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('The order object cannot be loaded.')

            )));

        }



        if ($order_detail->id_order != $order->id) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('You cannot edit the order detail for this order.')

            )));

        }

        if (!empty($order_invoice) && $order_invoice->id_order != Tools::getValue('id_order')) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('You cannot use this invoice for the order')

            )));

        }



        // Clean price

        $product_price_tax_incl = str_replace(',', '.', Tools::getValue('product_price_tax_incl'));

        $product_price_tax_excl = str_replace(',', '.', Tools::getValue('product_price_tax_excl'));



        if (!Validate::isPrice($product_price_tax_incl) || !Validate::isPrice($product_price_tax_excl)) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('Invalid price')

            )));

        }



        if (!is_array(Tools::getValue('product_quantity')) && !Validate::isUnsignedInt(Tools::getValue('product_quantity'))) {

            die(Tools::jsonEncode(array(

                'result' => false,

                'error' => Tools::displayError('Invalid quantity')

            )));

        } elseif (is_array(Tools::getValue('product_quantity'))) {

            foreach (Tools::getValue('product_quantity') as $qty) {

                if (!Validate::isUnsignedInt($qty)) {

                    die(Tools::jsonEncode(array(

                        'result' => false,

                        'error' => Tools::displayError('Invalid quantity')

                    )));

                }

            }

        }

    }

    

    public function sendChangedNotification(Order $order = null)

    {

        if (is_null($order)) {

            $order = new Order(Tools::getValue('id_order'));

        }



        Hook::exec('actionOrderEdited', array('order' => $order));

    }

    

    public function getAjaxNewDataRefresh($order)

    {

        $products = $this->getProducts($order);

		$product = end($products);

		$resume = OrderSlip::getProductSlipResume((int)$product['id_order_detail']);

		$product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];

		$product['amount_refundable'] = $product['total_price_tax_incl'] - $resume['amount_tax_incl'];

		$product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl']);

		$product['return_history'] = OrderReturn::getProductReturnDetail((int)$product['id_order_detail']);

		$product['refund_history'] = OrderSlip::getProductSlipDetail((int)$product['id_order_detail']);

		if ($product['id_warehouse'] != 0)

		{

			$warehouse = new Warehouse((int)$product['id_warehouse']);

			$product['warehouse_name'] = $warehouse->name;

		}

		else

			$product['warehouse_name'] = '--';

		$invoice_collection = $order->getInvoicesCollection();

		$invoice_array = array();

		foreach ($invoice_collection as $invoice)

		{

			$invoice->name = $invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);

			$invoice_array[] = $invoice;

		}

		$this->context->smarty->assign(array(

			'product' => $product,

			'order' => $order,

			'currency' => new Currency($order->id_currency),

			'can_edit' => 1,

			'invoices_collection' => $invoice_collection,

			'current_id_lang' => Context::getContext()->language->id,

			'link' => Context::getContext()->link,

			'current_index' => self::$currentIndex,

			'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),

            'discounts' => $order->getCartRules()

		));

		

		die(Tools::jsonEncode(array(

            'haserror'=>false,

            'warning'=>$this->l('Discount update successful.'),

			'result' => true,

			'view' => $this->createTemplate('_product_line.tpl')->fetch(),

			'can_edit' => 1,

			'order' => $order,

			'invoices' => $invoice_array,

			'documents_html' => $this->createTemplate('_documents.tpl')->fetch(),

			'shipping_html' => $this->createTemplate('_shipping.tpl')->fetch(),

			'discount_form_html' => $this->createTemplate('_discount_form.tpl')->fetch(),

            'discount_val_html' => $this->createTemplate('_discount_val.tpl')->fetch()

		)));

    }

    

    public function ajaxOrderDeletePayment()

    {

        $idorder = (int)Tools::getValue('idorder');

        $idpayment = Tools::getValue('idpayment');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'payment'=>''

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $order_payment = new OrderPayment($idpayment);

            if ($order_payment->id_currency == $this->context->currency->id)

    			$orderObj->total_paid_real -= $order_payment->amount;

    		else

    			$orderObj->total_paid_real -= Tools::ps_round(Tools::convertPrice($order_payment->amount, $order_payment->id_currency, false), 2);

            $order_payment->delete();

            $orderObj->update();

            $sql = 'DELETE FROM `'._DB_PREFIX_.'order_invoice_payment` WHERE id_order_payment ='.(int)$idpayment.';';

            if(Db::getInstance()->execute($sql))

            {

                $results['warning'] = $this->l('Payment deleted.');

                $payment_methods = array();

                foreach (PaymentModule::getInstalledPaymentModules() as $payment) {

                    $module = Module::getInstanceByName($payment['name']);

                    if (Validate::isLoadedObject($module) && $module->active) {

                        $payment_methods[] = $module->displayName;

                    }

                }

                Context::getContext()->smarty->assign(

                    array(

                        'order' => $orderObj,

                        'payment_methods' => $payment_methods,

                        'currencies' => Currency::getCurrenciesByIdShop($orderObj->id_shop),

                        'invoices_collection' => $orderObj->getInvoicesCollection(),

                        'orders_total_paid_tax_incl' => $orderObj->getOrdersTotalPaid(),

                        'currentState' => $orderObj->getCurrentOrderState(),

                        'currency' => new Currency($orderObj->id_currency),

                        'total_paid' => $orderObj->getTotalPaid(),

                        'link'=>$this->context->link,

                        'current_id_lang' => Context::getContext()->language->id,                                                

                    )

                );

                $results['payment'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_payment.tpl');

                

            }else{

                $results['haserror'] = true;

                $results['warning'] = $this->l('Error.Please try again.');

            }

        }

        else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    public function ajaxAddMessage(){

        $results = array(

            'haserror'=>false,

            'warning'=>'',

        );

        $errors = array();

        $idorder = (int)Tools::getValue('id_order');

        $order = new Order($idorder);

        if(Validate::isLoadedObject($order))

        {

            $customer = new Customer(Tools::getValue('id_customer'));

            if (!Validate::isLoadedObject($customer)) {

                $errors[] = Tools::displayError('The customer is invalid.');

            } elseif (!Tools::getValue('message')) {

                $errors[] = Tools::displayError('The message cannot be blank.');

            } else {

                /* Get message rules and and check fields validity */

                $rules = call_user_func(array('Message', 'getValidationRules'), 'Message');

                foreach ($rules['required'] as $field) {

                    if (($value = Tools::getValue($field)) == false && (string)$value != '0') {

                        if (!Tools::getValue('id_'.$this->table) || $field != 'passwd') {

                            $errors[] = sprintf(Tools::displayError('field %s is required.'), $field);

                        }

                    }

                }

                foreach ($rules['size'] as $field => $maxLength) {

                    if (Tools::getValue($field) && Tools::strlen(Tools::getValue($field)) > $maxLength) {

                        $errors[] = sprintf(Tools::displayError('field %1$s is too long (%2$d chars max).'), $field, $maxLength);

                    }

                }

                foreach ($rules['validate'] as $field => $function) {

                    if (Tools::getValue($field)) {

                        if (!Validate::{$function}(htmlentities(Tools::getValue($field), ENT_COMPAT, 'UTF-8'))) {

                            $errors[] = sprintf(Tools::displayError('field %s is invalid.'), $field);

                        }

                    }

                }



                if ($errors) {}

                else{

                    //check if a thread already exist

                    $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);

                    if (!$id_customer_thread) {

                        $customer_thread = new CustomerThread();

                        $customer_thread->id_contact = 0;

                        $customer_thread->id_customer = (int)$order->id_customer;

                        $customer_thread->id_shop = (int)$this->context->shop->id;

                        $customer_thread->id_order = (int)$order->id;

                        $customer_thread->id_lang = (int)$this->context->language->id;

                        $customer_thread->email = $customer->email;

                        $customer_thread->status = 'open';

                        $customer_thread->token = Tools::passwdGen(12);

                        $customer_thread->add();

                    } else {

                        $customer_thread = new CustomerThread((int)$id_customer_thread);

                    }



                    $customer_message = new CustomerMessage();

                    $customer_message->id_customer_thread = $customer_thread->id;

                    $customer_message->id_employee = (int)$this->context->employee->id;

                    $customer_message->message = Tools::getValue('message');

                    $customer_message->private = Tools::getValue('visibility');



                    if (!$customer_message->add()) {

                        $errors[] = Tools::displayError('An error occurred while saving the message.');

                    } elseif ($customer_message->private) {

                        //Tools::redirectAdmin(self::$currentIndex.'&id_order='.(int)$order->id.'&vieworder&conf=11&token='.$this->token);

                    } else {

                        $message = $customer_message->message;

                        if (Configuration::get('PS_MAIL_TYPE', null, null, $order->id_shop) != Mail::TYPE_TEXT) {

                            $message = Tools::nl2br($customer_message->message);

                        }



                        $varsTpl = array(

                            '{lastname}' => $customer->lastname,

                            '{firstname}' => $customer->firstname,

                            '{id_order}' => $order->id,

                            '{order_name}' => $order->getUniqReference(),

                            '{message}' => $message

                        );

                        if (@Mail::Send((int)$order->id_lang, 'order_merchant_comment',

                            Mail::l('New message regarding your order', (int)$order->id_lang), $varsTpl, $customer->email,

                            $customer->firstname.' '.$customer->lastname, null, null, null, null, _PS_MAIL_DIR_, true, (int)$order->id_shop)) {

                            //Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=11'.'&token='.$this->token);

                        }

                    }

                    //$errors[] = Tools::displayError('An error occurred while sending an email to the customer.');

                }

            }



        }else $errors[] = Tools::displayError('The order is invalid.');

        if($errors)

            $results = array(

                'haserror'=>true,

                'warning'=>implode(',',$errors)

            );

        else

            $results = array(

                'haserror'=>false,

                'warning'=>$this->l('Order message sent successfully'),

            );

        die(Tools::jsonEncode($results));

    }

    public function ajaxOrderAddPayment()

    {

        $idorder = (int)Tools::getValue('idorder');

        $orderObj = new Order((int)$idorder);//,(int)$this->context->language->id);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'payment'=>''

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $amount = str_replace(',', '.', Tools::getValue('payment_amount'));

            $currency = new Currency(Tools::getValue('payment_currency'));

            $order_has_invoice = $orderObj->hasInvoice();

            if ($order_has_invoice) {

                $order_invoice = new OrderInvoice(Tools::getValue('payment_invoice'));

            } else {

                $order_invoice = null;

            }

            $errors = '';

            if (!Validate::isLoadedObject($orderObj)) {

                $errors = $this->l('The order cannot be found');

            } elseif (!Validate::isNegativePrice($amount) || !(float)$amount) {

                $errors = $this->l('The amount is invalid.');

            } elseif (!Validate::isGenericName(Tools::getValue('payment_method'))) {

                $errors = $this->l('The selected payment method is invalid.');

            } elseif (!Validate::isString(Tools::getValue('payment_transaction_id'))) {

                $errors = $this->l('The transaction ID is invalid.');

            } elseif (!Validate::isLoadedObject($currency)) {

                $errors = $this->l('The selected currency is invalid.');

            } elseif ($order_has_invoice && !Validate::isLoadedObject($order_invoice)) {

                $errors = $this->l('The invoice is invalid.');

            } elseif (!Validate::isDate(Tools::getValue('payment_date'))) {

                $errors = $this->l('The date is invalid');

            } else {

                if (!$orderObj->addOrderPayment($amount, Tools::getValue('payment_method'), Tools::getValue('payment_transaction_id'), $currency, Tools::getValue('payment_date'), $order_invoice)) {

                    $errors = $this->l('An error occurred during payment.');

                }

            }

            if($errors !=''){

                $results['haserror'] = true;

                $results['warning'] = $errors;

            }else{

                $results['warning'] = $this->l('Payment added.');

                $payment_methods = array();

                foreach (PaymentModule::getInstalledPaymentModules() as $payment) {

                    $module = Module::getInstanceByName($payment['name']);

                    if (Validate::isLoadedObject($module) && $module->active) {

                        $payment_methods[] = $module->displayName;

                    }

                }

                Context::getContext()->smarty->assign(

                    array(

                        'order' => $orderObj,

                        'payment_methods' => $payment_methods,

                        'currencies' => Currency::getCurrenciesByIdShop($orderObj->id_shop),

                        'invoices_collection' => $orderObj->getInvoicesCollection(),

                        'orders_total_paid_tax_incl' => $orderObj->getOrdersTotalPaid(),

                        'currentState' => $orderObj->getCurrentOrderState(),

                        'currency' => new Currency($orderObj->id_currency),

                        'total_paid' => $orderObj->getTotalPaid(),

                        'link'=>$this->context->link,

                        'current_id_lang' => Context::getContext()->language->id,  

                    )

                );

                $results['payment'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_payment.tpl');

                

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderSubmitEditPayment()

    {

        $idorder = (int)Tools::getValue('idorder');

        $idpayment = (int)Tools::getValue('paymentid');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>''

        );

        $payment_method = Tools::getValue('payment_method');

        $payment_transaction_id = Tools::getValue('payment_transaction_id');

        $amount = str_replace(',', '.', Tools::getValue('payment_amount'));

    	$payment_currency = (int)Tools::getValue('payment_currency');

        $currency = new Currency(Tools::getValue('payment_currency'));

        $payment_invoice = (int)Tools::getValue('payment_invoice');

        $order_has_invoice = $orderObj->hasInvoice();

        $order_invoice = null;

        if ($order_has_invoice) {

            $order_invoice = new OrderInvoice($payment_invoice);

        }

        $payment_date = Tools::getValue('payment_date');

        $payment_date = date('Y-m-d H:i:s',strtotime($payment_date));

        if (!Validate::isNegativePrice($amount) || !(float)$amount) {

            $results['warning'] = $this->l('The amount is invalid.');

        } elseif (!Validate::isGenericName($payment_method)) {

            $results['warning'] = $this->l('The selected payment method is invalid.');

        } elseif (!Validate::isString($payment_transaction_id)) {

            $results['warning'] = $this->l('The transaction ID is invalid.');

        } elseif (!Validate::isLoadedObject($currency)) {

            $results['warning'] = $this->l('The selected currency is invalid.');

        } elseif ($order_has_invoice && !Validate::isLoadedObject($order_invoice)) {

            $results['warning'] = $this->l('The invoice is invalid.');

        } elseif (!Validate::isDate($payment_date)) {

            $results['warning'] = $this->l('The date is invalid');

        }

        if($results['warning'] !='') $results['haserror'] = true; 

        if(!$results['haserror'])

        if(Validate::isLoadedObject($orderObj))

        {

                           

            $order_payment = new OrderPayment($idpayment);

            

            $order_payment->date_add = $payment_date;

            if ($order_payment->id_currency == $orderObj->id_currency)

    			$orderObj->total_paid_real -= $order_payment->amount;

    		else

    			$orderObj->total_paid_real -= Tools::ps_round(Tools::convertPrice($order_payment->amount, $order_payment->id_currency, false), 2);

                

                

            if ($orderObj->id_currency == $payment_currency)

            {

                $order_payment->amount = $amount;

                $orderObj->total_paid_real += $amount;

            }	

    		else

            {

                $order_payment->amount = Tools::ps_round(Tools::convertPrice($amount, $payment_currency, false), 2);

                $orderObj->total_paid_real +=Tools::ps_round(Tools::convertPrice($amount, $payment_currency, false), 2);

            }

            $order_payment->id_currency = (int)$payment_currency;

            $order_payment->payment_method = ($payment_method ? $payment_method : $orderObj->payment);

    		$order_payment->transaction_id = $payment_transaction_id;

    		

            $res = $order_payment->update();

            $res &= $orderObj->update();

            $order_invoice_payment = (int)Db::getInstance()->getValue('

        			SELECT id_order_invoice FROM `'._DB_PREFIX_.'order_invoice_payment`

                    WHERE id_order_payment = '.(int)$idpayment.'

                    AND id_order ='.(int)$idorder.'

                ');

            if($order_invoice_payment)

            {

                if($order_invoice_payment != $payment_invoice)

                    $res &= Db::getInstance()->execute('

            			UPDATE `'._DB_PREFIX_.'order_invoice_payment`

            			SET id_order_invoice = '.(int)$payment_invoice.'

                        WHERE id_order_payment = '.(int)$idpayment.'

                        AND id_order = '.(int)$idorder.'

                    ');

     			Cache::clean('order_invoice_paid_*');

            }

            if($res)

            {

                $results['warning'] = $this->l('Payment edited.');

                $payment_methods = array();

                foreach (PaymentModule::getInstalledPaymentModules() as $payment) {

                    $module = Module::getInstanceByName($payment['name']);

                    if (Validate::isLoadedObject($module) && $module->active) {

                        $payment_methods[] = $module->displayName;

                    }

                }

                Context::getContext()->smarty->assign(

                    array(

                        'order' => $orderObj,

                        'payment_methods' => $payment_methods,

                        'currencies' => Currency::getCurrenciesByIdShop($orderObj->id_shop),

                        'invoices_collection' => $orderObj->getInvoicesCollection(),

                        'orders_total_paid_tax_incl' => $orderObj->getOrdersTotalPaid(),

                        'currentState' => $orderObj->getCurrentOrderState(),

                        'currency' => new Currency($orderObj->id_currency),

                        'total_paid' => $orderObj->getTotalPaid(),

                        'link'=>$this->context->link,

                        'current_id_lang' => Context::getContext()->language->id,  

                    )

                );

                $results['payment'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_payment.tpl');

            }else{

                $results['haserror'] = true;

                $results['warning'] = $this->l('Payment can\'t be edited');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function changeCustomerInCart()

    {

        $id_customer = Tools::getValue('id_customer');

        $id_cart = Tools::getValue('id_cart');

        $cart = new Cart((int)$id_cart);

        $cart->id_customer = $id_customer;

        $cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)$id_customer);

        $cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)$id_customer);

        $cart->update();

        die();

    }

    

    public function ajaxRemoveOrderHistory()

    {

        $idorder = (int)Tools::getValue('idorder');

        $orderObj = new Order($idorder);

        $history = (int)Tools::getValue('history');

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'state'=>'',

            'current_history'=>'',

            'color_current_history'=>'black',

            'backgroud_current_history'=>'white',

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $sql = 'SELECT count(id_order) FROM `'._DB_PREFIX_.'order_history`

                WHERE id_order_history="'.(int)$history.'" AND id_order="'.(int)$idorder.'"';

            $result = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

            if($result >0){

                $sql = 'DELETE FROM `'._DB_PREFIX_.'order_history`  WHERE id_order_history="'.(int)$history.'" AND id_order="'.(int)$idorder.'";';

                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);

                $results['warning'] = $this->l('Order history removed.');

                $sql = 'SELECT * FROM `'._DB_PREFIX_.'order_history`

                        WHERE id_order="'.(int)$idorder.'" ORDER BY id_order_history DESC';

                $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

                if($result && isset($result['id_order_state']) && $result['id_order_state'] != $orderObj->getCurrentOrderState()){

                   $orderObj->current_state = (int)$result['id_order_state']; 

                   $orderObj->update();

                   $results['current_history'] = (int)$result['id_order_history']; 

                }else{

                    $orderObj->current_state = 0; 

                   $orderObj->update();

                }

                if($orderObj->current_state !=0){

                    $order_state = new OrderState($orderObj->current_state);

                    $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

                    Context::getContext()->smarty->assign(array(

                        'item_type'=>'color_field',

                        'backgroundcolor'=>$order_state->color,

                        'color'=>(Tools::getBrightness($order_state->color) < 128 ? 'white' : 'black'),

                        'statename'=>$order_state->name[(int)$this->context->language->id]

                    ));

                    $results['state'] = Context::getContext()->smarty->fetch($tpl);

                    $results['color_current_history'] = Tools::getBrightness($order_state->color) < 128 ? 'white' : 'black';

                    $results['backgroud_current_history'] = $order_state->color;

                }

            }else{

                $results['haserror'] = true;

                $results['warning'] = $this->l('Order history not found.');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    

    public function ajaxOrderNewVoucher()

    {

        $idorder = (int)Tools::getValue('idorder');

        $order = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>''

        );

        if(Validate::isLoadedObject($order))

        {

    			if (!Tools::getValue('discount_name'))

                {

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('You must specify a name in order to create a new discount.');

                    die(Tools::jsonEncode($results));

    			}

    			else

    			{

    				if ($order->hasInvoice())

    				{

    					if (!Tools::isSubmit('discount_all_invoices'))

    					{

    						$order_invoice = new OrderInvoice(Tools::getValue('discount_invoice'));

    						if (!Validate::isLoadedObject($order_invoice))

                            {

                                $results['haserror'] = true;

                                $results['warning'] = $this->l('Can\'t load Order Invoice object');

                                die(Tools::jsonEncode($results));

        					}

    					}

    				}

    

    				$cart_rules = array();

    				$discount_value = (float)str_replace(',', '.', Tools::getValue('discount_value'));

    				switch (Tools::getValue('discount_type'))

    				{

    					case 1:

    						if ($discount_value < 100)

    						{

    							if (isset($order_invoice))

    							{

    								$cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($order_invoice->total_paid_tax_incl * $discount_value / 100, 2);

    								$cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($order_invoice->total_paid_tax_excl * $discount_value / 100, 2);

    

    								$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    							}

    							elseif ($order->hasInvoice())

    							{

    								$order_invoices_collection = $order->getInvoicesCollection();

    								foreach ($order_invoices_collection as $order_invoice)

    								{

    									$cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($order_invoice->total_paid_tax_incl * $discount_value / 100, 2);

    									$cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($order_invoice->total_paid_tax_excl * $discount_value / 100, 2);

    

    									$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    								}

    							}

    							else

    							{

    								$cart_rules[0]['value_tax_incl'] = Tools::ps_round($order->total_paid_tax_incl * $discount_value / 100, 2);

    								$cart_rules[0]['value_tax_excl'] = Tools::ps_round($order->total_paid_tax_excl * $discount_value / 100, 2);

    							}

    						}

    						else{

                                $results['haserror'] = true;

                                $results['warning'] = $this->l('The discount value is invalid.');

                                die(Tools::jsonEncode($results));

    						}

    						break;

    					case 2:

    						if (isset($order_invoice))

    						{

    							if ($discount_value > $order_invoice->total_paid_tax_incl)

                                {

                                    $results['haserror'] = true;

                                    $results['warning'] = $this->l('The discount value is greater than the order invoice total.');

                                    die(Tools::jsonEncode($results));

                                }

    							else

    							{

    								$cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($discount_value, 2);

    								$cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);

    

    								$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    							}

    						}

    						elseif ($order->hasInvoice())

    						{

    							$order_invoices_collection = $order->getInvoicesCollection();

    							foreach ($order_invoices_collection as $order_invoice)

    							{

    								if ($discount_value > $order_invoice->total_paid_tax_incl)

                                    {

                                        $results['haserror'] = true;

                                        $results['warning'] = $this->l('The discount value is greater than the order invoice total.').$order_invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop);

                                        die(Tools::jsonEncode($results));

                                    }

    								else

    								{

    									$cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($discount_value, 2);

    									$cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);

    

    									$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    								}

    							}

    						}

    						else

    						{

    							if ($discount_value > $order->total_paid_tax_incl)

                                {

                                    $results['haserror'] = true;

                                    $results['warning'] = $this->l('The discount value is greater than the order total.');

                                    die(Tools::jsonEncode($results));

                                }

    							else

    							{

    								$cart_rules[0]['value_tax_incl'] = Tools::ps_round($discount_value, 2);

    								$cart_rules[0]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);

    							}

    						}

    						break;

    					case 3:

    						if (isset($order_invoice))

    						{

    							if ($order_invoice->total_shipping_tax_incl > 0)

    							{

    								$cart_rules[$order_invoice->id]['value_tax_incl'] = $order_invoice->total_shipping_tax_incl;

    								$cart_rules[$order_invoice->id]['value_tax_excl'] = $order_invoice->total_shipping_tax_excl;

    

    								$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    							}

    						}

    						elseif ($order->hasInvoice())

    						{

    							$order_invoices_collection = $order->getInvoicesCollection();

    							foreach ($order_invoices_collection as $order_invoice)

    							{

    								if ($order_invoice->total_shipping_tax_incl <= 0)

    									continue;

    								$cart_rules[$order_invoice->id]['value_tax_incl'] = $order_invoice->total_shipping_tax_incl;

    								$cart_rules[$order_invoice->id]['value_tax_excl'] = $order_invoice->total_shipping_tax_excl;

    

    								$this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);

    							}

    						}

    						else

    						{

    							$cart_rules[0]['value_tax_incl'] = $order->total_shipping_tax_incl;

    							$cart_rules[0]['value_tax_excl'] = $order->total_shipping_tax_excl;

    						}

    						break;

    					default:

                            $results['haserror'] = true;

                            $results['warning'] = $this->l('The discount type is invalid.');

                            die(Tools::jsonEncode($results));

    				}

    

    				$res = true;

    				foreach ($cart_rules as &$cart_rule)

    				{

    					$cartRuleObj = new CartRule();

    					$cartRuleObj->date_from = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($order->date_add)));

    					$cartRuleObj->date_to = date('Y-m-d H:i:s', strtotime('+1 hour'));

    					$cartRuleObj->name[Configuration::get('PS_LANG_DEFAULT')] = Tools::getValue('discount_name');

    					$cartRuleObj->quantity = 0;

    					$cartRuleObj->quantity_per_user = 1;

    					if (Tools::getValue('discount_type') == 1)

    						$cartRuleObj->reduction_percent = $discount_value;

    					elseif (Tools::getValue('discount_type') == 2)

    						$cartRuleObj->reduction_amount = $cart_rule['value_tax_excl'];

    					elseif (Tools::getValue('discount_type') == 3)

    						$cartRuleObj->free_shipping = 1;

    					$cartRuleObj->active = 0;

    					if ($res = $cartRuleObj->add())

    						$cart_rule['id'] = $cartRuleObj->id;

    					else

    						break;

    				}

    

    				if ($res)

    				{

    					foreach ($cart_rules as $id_order_invoice => $cart_rule)

    					{

    						$order_cart_rule = new OrderCartRule();

    						$order_cart_rule->id_order = $order->id;

    						$order_cart_rule->id_cart_rule = $cart_rule['id'];

    						$order_cart_rule->id_order_invoice = $id_order_invoice;

    						$order_cart_rule->name = Tools::getValue('discount_name');

    						$order_cart_rule->value = $cart_rule['value_tax_incl'];

    						$order_cart_rule->value_tax_excl = $cart_rule['value_tax_excl'];

    						$res &= $order_cart_rule->add();

    

    						$order->total_discounts += $order_cart_rule->value;

    						$order->total_discounts_tax_incl += $order_cart_rule->value;

    						$order->total_discounts_tax_excl += $order_cart_rule->value_tax_excl;

    						$order->total_paid -= $order_cart_rule->value;

    						$order->total_paid_tax_incl -= $order_cart_rule->value;

    						$order->total_paid_tax_excl -= $order_cart_rule->value_tax_excl;

    					}

    

    					$res &= $order->update();

    				}

    

    				if ($res)

    					return $this->getAjaxNewDataRefresh($order);

    				else{

    				   $results['haserror'] = true;

                       $results['warning'] = $this->l('An error occurred during the OrderCartRule creation');

    				}

    			}

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderEditDiscount()

    {

        $idorder = (int)Tools::getValue('idorder');

        $order = new Order($idorder);

        $id_order_cart_rule = (int)Tools::getValue('id_order_cart_rule');

        $discount_name = Tools::getValue('discount_name');

        $discount_price = Tools::getValue('discount_price');

        $order_cart_rule = new OrderCartRule($id_order_cart_rule);

        $results = array(

            'haserror'=>false,

            'warning'=>''

        );

        if(Validate::isLoadedObject($order))

        {

            if (Validate::isLoadedObject($order_cart_rule) && $order_cart_rule->id_order == $idorder)

    		{

                $value_tax_incl = Tools::ps_round($discount_price, 2);

                $value_tax_excl = Tools::ps_round($discount_price / (1 + ($order->getTaxesAverageUsed() / 100)), 2);

                $cartRuleObj = new CartRule($order_cart_rule->id_cart_rule);

                $cartRuleObj->name[Configuration::get('PS_LANG_DEFAULT')] = $discount_name;

    			$order->total_discounts -= $order_cart_rule->value;

    			$order->total_discounts_tax_incl -= $order_cart_rule->value;

    			$order->total_discounts_tax_excl -= $order_cart_rule->value_tax_excl;

    			$order->total_paid += $order_cart_rule->value;

    			$order->total_paid_tax_incl += $order_cart_rule->value;

    			$order->total_paid_tax_excl += $order_cart_rule->value_tax_excl;

                

                if ($value_tax_incl > $order->total_paid_tax_incl) {

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('The discount value is greater than the order total.');

                    die(Tools::jsonEncode($results));

                }

                

                

                if ($order_cart_rule->id_order_invoice)

    			{

    				$order_invoice = new OrderInvoice($order_cart_rule->id_order_invoice);

    				if (!Validate::isLoadedObject($order_invoice))

    					throw new PrestaShopException('Can\'t load Order Invoice object');

    				$order_invoice->total_discount_tax_excl -= $order_cart_rule->value_tax_excl;

    				$order_invoice->total_discount_tax_incl -= $order_cart_rule->value;

    				$order_invoice->total_paid_tax_excl += $order_cart_rule->value_tax_excl;

    				$order_invoice->total_paid_tax_incl += $order_cart_rule->value;

                    

                    if ($value_tax_incl > $order_invoice->total_paid_tax_incl) {

                        $results['haserror'] = true;

                        $results['warning'] = $this->l('The discount value is greater than the order invoice total.');

                        die(Tools::jsonEncode($results));

                    }

                    $order_invoice->total_discount_tax_incl += $value_tax_incl;

            		$order_invoice->total_discount_tax_excl += $value_tax_excl;

            		$order_invoice->total_paid_tax_incl -= $value_tax_incl;

            		$order_invoice->total_paid_tax_excl -= $value_tax_excl;

    				$order_invoice->update();

    			}

                $order->total_discounts += $value_tax_incl;

    			$order->total_discounts_tax_incl += $value_tax_incl;

    			$order->total_discounts_tax_excl += $value_tax_excl;

    			$order->total_paid -= $value_tax_incl;

    			$order->total_paid_tax_incl -= $value_tax_incl;

    			$order->total_paid_tax_excl -= $value_tax_excl;

                

                $order_cart_rule->value = $value_tax_incl;

                $order_cart_rule->value_tax_excl = $value_tax_excl;

                $order_cart_rule->name = $discount_name;

                $cartRuleObj->update();

                $order_cart_rule->update();

                $order->update();

                return $this->getAjaxNewDataRefresh($order);

            }else{

                $results['haserror'] = true;

                $results['warning'] = $this->l('Discount can\'t be loaded');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderDeleteDiscount()

    {

        $idorder = (int)Tools::getValue('idorder');

        $order = new Order($idorder);

        $id_order_cart_rule = (int)Tools::getValue('id_order_cart_rule');

        $order_cart_rule = new OrderCartRule($id_order_cart_rule);

        $results = array(

            'haserror'=>false,

            'warning'=>''

        );

        if(Validate::isLoadedObject($order))

        {

            if (Validate::isLoadedObject($order_cart_rule) && $order_cart_rule->id_order == $idorder)

    		{

    		    if ($order_cart_rule->id_order_invoice)

    			{

    				$order_invoice = new OrderInvoice($order_cart_rule->id_order_invoice);

    				if (!Validate::isLoadedObject($order_invoice))

    					throw new PrestaShopException('Can\'t load Order Invoice object');

    				$order_invoice->total_discount_tax_excl -= $order_cart_rule->value_tax_excl;

    				$order_invoice->total_discount_tax_incl -= $order_cart_rule->value;

    				$order_invoice->total_paid_tax_excl += $order_cart_rule->value_tax_excl;

    				$order_invoice->total_paid_tax_incl += $order_cart_rule->value;

    				$order_invoice->update();

    			}

    			$order->total_discounts -= $order_cart_rule->value;

    			$order->total_discounts_tax_incl -= $order_cart_rule->value;

    			$order->total_discounts_tax_excl -= $order_cart_rule->value_tax_excl;

    			$order->total_paid += $order_cart_rule->value;

    			$order->total_paid_tax_incl += $order_cart_rule->value;

    			$order->total_paid_tax_excl += $order_cart_rule->value_tax_excl;

    			$order_cart_rule->delete();

    			$order->update();

    		    

                return $this->getAjaxNewDataRefresh($order);

            }else{

                $results['haserror'] = true;

                $results['warning'] = $this->l('Discount can\'t be loaded');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderChangeReference()

    {

        $idorder = (int)Tools::getValue('idorder');

        $orderObj = new Order($idorder);

        $reference = Tools::getValue('reference');

        $results = array(

            'haserror'=>false,

            'warning'=>''

        );

        if(Validate::isLoadedObject($orderObj))

        {

            if($orderObj->reference != $reference){

                $sql = 'SELECT count(id_order) FROM `'._DB_PREFIX_.'orders`

                    WHERE reference="'.pSQL($reference).'"';

                $result = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

                if($result <=0){

                    $sql = 'UPDATE `'._DB_PREFIX_.'orders` SET reference="'.pSQL($reference).'" WHERE id_order='.(int)$idorder.';';

                    $sql .= 'UPDATE `'._DB_PREFIX_.'order_payment` SET order_reference="'.pSQL($reference).'" WHERE order_reference="'.pSql($orderObj->reference).'";';

                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);

                    $results['warning'] = $this->l('reference update successful.');

                }else{

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('reference exist.');

                }

            }else{

                $results['warning'] = $this->l('reference update successful.');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    public function ajaxOrderUpdateAddress()

    {

        $to_return = array();

        $customer = new Customer((int)Tools::getValue('id_customer'));

        $orders = new Order((int)Tools::getValue('id_order'));

        $addresses = $customer->getAddresses((int)Context::getContext()->language->id);

        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

        if($addresses)

            foreach ($addresses as &$data) {

                $address = new Address((int)$data['id_address']);

                Context::getContext()->smarty->assign(array(

                    'item_type'=>'addressformat',

                    'address'=>$address

                ));

                $data['formated_address'] =  Context::getContext()->smarty->fetch($tpl);

            }

        $to_return['addresses']=$addresses;

        $to_return['id_address_delivery']=(int)$orders->id_address_delivery;

        $to_return['id_address_invoice']=(int)$orders->id_address_invoice;

        $to_return['warning'] = $this->l('Address update successful.');

        echo Tools::jsonEncode($to_return);

        die('');

    }

    public function ajaxOrderChangeAddress()

    {

        $idorder = (int)Tools::getValue('idorder');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'address'=>'',

            'address_map'=>'',

            'country'=>'',

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $address = new Address(Tools::getValue('id_address'));

            if (Validate::isLoadedObject($address)) {

                $type = Tools::getValue('type');

                if ($type == 'shipping') {

                    $orderObj->id_address_delivery = $address->id;

                } elseif ($type == 'invoice') {

                    $orderObj->id_address_invoice = $address->id;

                }

                $orderObj->update();

                $results['warning'] = $this->l('Address update successful.');

                $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

                Context::getContext()->smarty->assign(array(

                    'item_type'=>'addressformat',

                    'address'=>$address

                ));

                $results['address'] =  Context::getContext()->smarty->fetch($tpl);

                $results['address_map'] = $address->address1.','.$address->postcode.','.$address->city.','.$address->country;

                $results['country'] = $address->country;

            } else {

                $results['haserror'] = true;

                $results['warning'] = $this->l('This address can\'t be loaded');

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderChangeState()

    {

        $idorder = (int)Tools::getValue('idorder');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'state'=>'',

            '_print_pdf_icon'=>'',

            'payment_history'=>'',

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $order_state = new OrderState(Tools::getValue('state'));

            if (!Validate::isLoadedObject($order_state)) {

                $results['haserror'] = true;

                $results['warning'] = $this->l('The new order status is invalid.');

            } else {

                $current_order_state = $orderObj->getCurrentOrderState();

                if ($current_order_state->id != $order_state->id) {

                    $history = new OrderHistory();

                    $history->id_order = $orderObj->id;

                    $history->id_employee = (int)$this->context->employee->id;

                    $use_existings_payment = false;

                    if (!$orderObj->hasInvoice()) {

                        $use_existings_payment = true;

                    }

                    $history->changeIdOrderState((int)$order_state->id, $orderObj, $use_existings_payment);

                    $carrier = new Carrier($orderObj->id_carrier, $orderObj->id_lang);

                    $templateVars = array();

                    if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $orderObj->shipping_number) {

                        $templateVars = array('{followup}' => str_replace('@', $orderObj->shipping_number, $carrier->url));

                    }

                    if ($history->addWithemail(true, $templateVars)) {

                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {

                            foreach ($orderObj->getProducts() as $product) {

                                if (StockAvailable::dependsOnStock($product['product_id'])) {

                                    StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);

                                }

                            }

                        }

                        $results['warning'] = $this->l('Status update successful.');

                        $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

                        Context::getContext()->smarty->assign(array(

                            'item_type'=>'color_field',

                            'backgroundcolor'=>$order_state->color,

                            'color'=>(Tools::getBrightness($order_state->color) < 128 ? 'white' : 'black'),

                            'statename'=>$order_state->name[(int)$this->context->language->id]

                        ));

                        $results['state'] = Context::getContext()->smarty->fetch($tpl);

                        

                        $this->context->smarty->assign(array(

                            'order' => $orderObj,

                            'link'=>$this->context->link

                        ));

                        $results['_print_pdf_icon'] =$this->createTemplate('_print_pdf_icon.tpl')->fetch();

                        

                        $history = $orderObj->getHistory($this->context->language->id);

                        foreach ($history as &$_order_state) {

                            $_order_state['text-color'] = Tools::getBrightness($order_state->color) < 128 ? 'white' : 'black';

                        }

                        Context::getContext()->smarty->assign(

                            array(

                                'history' => $history,

                                'link'=>$this->context->link

                            )

                        );

                        $results['payment_history'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_state.tpl');

                        

                        die(Tools::jsonEncode($results));

                    }

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('An error occurred while changing order status, or we were unable to send an email to the customer.');

                } else {

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('The order has already been assigned this status.');

                }

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderChangeStates()

    {

        $idorders = Tools::getValue('orders');

        $id_orders = explode('-',$idorders);

        $id_orders = array_map('intval',$id_orders);

        $results = array(

            'haserror'=>array(),

            'warning'=>array(),

            'state'=>array(),

            '_print_pdf_icon'=>array(),

            'payment_history'=>array(),

        );

        

        if($id_orders){

            $order_state = new OrderState(Tools::getValue('state'));

            if (Validate::isLoadedObject($order_state)) {

                foreach($id_orders as $idorder){

                    if((int)$idorder > 0){

                        $orderObj = new Order($idorder);

                        if(Validate::isLoadedObject($orderObj))

                        {



                                $current_order_state = $orderObj->getCurrentOrderState();

                                if ($current_order_state->id != $order_state->id) {

                                    $history = new OrderHistory();

                                    $history->id_order = $orderObj->id;

                                    $history->id_employee = (int)$this->context->employee->id;

            

                                    $use_existings_payment = false;

                                    if (!$orderObj->hasInvoice()) {

                                        $use_existings_payment = true;

                                    }

                                    $history->changeIdOrderState((int)$order_state->id, $orderObj, $use_existings_payment);

            

                                    $carrier = new Carrier($orderObj->id_carrier, $orderObj->id_lang);

                                    $templateVars = array();

                                    if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $orderObj->shipping_number) {

                                        $templateVars = array('{followup}' => str_replace('@', $orderObj->shipping_number, $carrier->url));

                                    }

                                    if ($history->addWithemail(true, $templateVars)) {

                                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {

                                            foreach ($orderObj->getProducts() as $product) {

                                                if (StockAvailable::dependsOnStock($product['product_id'])) {

                                                    StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);

                                                }

                                            }

                                        }

                                    }

                                    $results['warning'][$idorder] = '#'.$idorder.':'.$this->l('Status update successful.');

                                    $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

                                    Context::getContext()->smarty->assign(array(

                                        'item_type'=>'color_field',

                                        'backgroundcolor'=>$order_state->color,

                                        'color'=>(Tools::getBrightness($order_state->color) < 128 ? 'white' : 'black'),

                                        'statename'=>$order_state->name[(int)$this->context->language->id]

                                    ));

                                    $results['state'][$idorder] = Context::getContext()->smarty->fetch($tpl);

                                    $this->context->smarty->assign(array(

                                        'order' => $orderObj,

                                        'link'=>$this->context->link

                                    ));

                                    $results['_print_pdf_icon'][$idorder] =$this->createTemplate('_print_pdf_icon.tpl')->fetch();

                                    

                                    $history = $orderObj->getHistory($this->context->language->id);

                                    foreach ($history as &$_order_state) {

                                        $_order_state['text-color'] = Tools::getBrightness($_order_state['color']) < 128 ? 'white' : 'black';

                                    }

                                    Context::getContext()->smarty->assign(

                                        array(

                                            'history' => $history,

                                            'link'=>$this->context->link

                                        )

                                    );

                                    $results['payment_history'][$idorder] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_state.tpl');

                                    

                                } else {

                                    $results['haserror'][$idorder] = true;

                                    $results['warning'][$idorder] = '#'.$idorder.':'.$this->l('The order has already been assigned this status.');

                                }

                        }else{

                            $results['haserror'][$idorder] = true;

                            $results['warning'][$idorder] = '#'.$idorder.':'.$this->l('Order can\'t be loaded');

                        }

                    }

                }

            }

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderChangeDateAdd()

    {

        $idorder = (int)Tools::getValue('idorder');

        $dateadd = Tools::getValue('dateadd');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'dateadd'=>''

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $orderObj->date_add = date('Y-m-d H:i:s',strtotime($dateadd));

            $orderObj->update();

            $results['warning'] = $this->l('Date update successful.');

            $results['dateadd'] = Tools::displayDate($orderObj->date_add,null,true);

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    public function ajaxOrderResendEmail()

    {

        $idorder = (int)Tools::getValue('idorder');

        $stateemail= (int)Tools::getValue('state');

        $id_order_history = (int)Tools::getValue('history');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $order_state = new OrderState((int)$stateemail);

            if (!Validate::isLoadedObject($order_state)) {

                $results['haserror'] = true;

                $results['warning'] = $this->l('An error occurred while loading order status.');

            } else {

                $history = new OrderHistory((int)$id_order_history);

                $carrier = new Carrier($orderObj->id_carrier, $orderObj->id_lang);

                $templateVars = array();

                if ($order_state->id == Configuration::get('PS_OS_SHIPPING') && $orderObj->shipping_number) {

                    $templateVars = array('{followup}' => str_replace('@', $orderObj->shipping_number, $carrier->url));

                }

                if ($history->sendEmail($orderObj, $templateVars)) {

                    $results['warning'] = $this->l('Resend email successful.');

                }else{

                    $results['haserror'] = true;

                    $results['warning'] = $this->l('An error occurred while sending the e-mail to the customer.');

                }

            }

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    

    

    public function ajaxOrderChangeCarrier()

    {

        $idorder = (int)Tools::getValue('idorder');

        $idcarrier = Tools::getValue('idcarrier');

        $orderObj = new Order($idorder);

        $results = array(

            'haserror'=>false,

            'warning'=>'',

            'order_total'=>'',

            'carriername'=>'',

            'total_paid'=>'',

            'orderchangeshipping'=>'',

            'payment'=>''

            

        );

        if(Validate::isLoadedObject($orderObj))

        {

            $id_order_carrier = Db::getInstance()->getValue('

    			SELECT `id_order_carrier`

    			FROM `'._DB_PREFIX_.'order_carrier`

    			WHERE `id_order` = '.(int)$idorder);

            $order_carrier = new OrderCarrier($id_order_carrier);

            $idcarrier = (int)Tools::getValue('idcarrier');

            $shipping_cost_incl = Tools::getValue('shipping_cost_incl');

            $shipping_cost_excl = Tools::getValue('shipping_cost_excl');

            $tax_rate = Tools::getValue('tax_rate');

            $shipping_weight = Tools::getValue('shipping_weight');

            $tracking_number = Tools::getValue('tracking_number');

            if ($id_order_carrier) {

                $order_carrier->id_carrier = (int)$idcarrier;

                $order_carrier->shipping_cost_tax_excl = $shipping_cost_excl;

                $order_carrier->shipping_cost_tax_incl = $shipping_cost_incl;

                $order_carrier->weight = $shipping_weight;

                $order_carrier->tracking_number = $tracking_number;

                $order_carrier->update();

            }

            $orderObj->shipping_number = $tracking_number;

            $orderObj->id_carrier = (int)$idcarrier;

            $orderObj->total_shipping = $shipping_cost_incl;

            $orderObj->carrier_tax_rate = $tax_rate;

            $orderObj->total_paid += ($shipping_cost_incl - $orderObj->total_shipping_tax_incl);

            $orderObj->total_paid_tax_incl +=   ($shipping_cost_incl - $orderObj->total_shipping_tax_incl);

            $orderObj->total_paid_tax_excl += ($shipping_cost_excl - $orderObj->total_shipping_tax_excl);

            $orderObj->total_shipping_tax_incl = $shipping_cost_incl;

            $orderObj->total_shipping_tax_excl = $shipping_cost_excl;

            if ($orderObj->hasInvoice()) {

                $order_invoices_collection = $orderObj->getInvoicesCollection();

                foreach ($order_invoices_collection as $order_invoice) {

                    //if ($orderObj->total_shipping_tax_incl <= 0)

                    //    continue;

                    $order_invoice->total_paid_tax_incl += ($shipping_cost_incl - $order_invoice->total_shipping_tax_incl);

                    $order_invoice->total_paid_tax_excl += ($shipping_cost_excl - $order_invoice->total_shipping_tax_excl);

                    $order_invoice->total_shipping_tax_incl = $shipping_cost_incl;

                    $order_invoice->total_shipping_tax_excl = $shipping_cost_excl;

                    $order_invoice->update();

                }

            }

            

            $orderObj->update();

            Context::getContext()->smarty->assign(

                array(

                    'order' => $orderObj,

                    'currencies' => Currency::getCurrenciesByIdShop($orderObj->id_shop),

                    'invoices_collection' => $orderObj->getInvoicesCollection(),

                    'orders_total_paid_tax_incl' => $orderObj->getOrdersTotalPaid(),

                    'currentState' => $orderObj->getCurrentOrderState(),

                    'currency' => new Currency($orderObj->id_currency),

                    'total_paid' => $orderObj->getTotalPaid(),

                    'link'=>$this->context->link,

                    'current_id_lang' => Context::getContext()->language->id,

                )

            );

            $results['order_total'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_total.tpl');

            if(!Tools::getValue('orderlist'))

            {

                $results['orderchangeshipping'] = Hook::exec('OrderChangeShipping', array('order' => $orderObj));

                $payment_methods = array();

                foreach (PaymentModule::getInstalledPaymentModules() as $payment) {

                    $module = Module::getInstanceByName($payment['name']);

                    if (Validate::isLoadedObject($module) && $module->active) {

                        $payment_methods[] = $module->displayName;

                    }

                }

                Context::getContext()->smarty->assign(

                    array(

                        'order' => $orderObj,

                        'payment_methods' => $payment_methods,

                        'currencies' => Currency::getCurrenciesByIdShop($orderObj->id_shop),

                        'invoices_collection' => $orderObj->getInvoicesCollection(),

                        'orders_total_paid_tax_incl' => $orderObj->getOrdersTotalPaid(),

                        'currentState' => $orderObj->getCurrentOrderState(),

                        'currency' => new Currency($orderObj->id_currency),

                        'total_paid' => $orderObj->getTotalPaid(),

                        'link'=>$this->context->link,

                        'current_id_lang' => Context::getContext()->language->id,  

                    )

                );

                $results['payment'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/order_payment.tpl');

            }

            

            $carrierObj = new Carrier($idcarrier,$this->context->language->id);

            $results['carriername'] = $carrierObj->name;

            if($orderObj->valid){

                $tpl = _PS_MODULE_DIR_.'g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl';

                Context::getContext()->smarty->assign(array(

                    'item_type'=>'badgesuccess',

                    'total_paid_tax_incl'=>Tools::displayPrice($orderObj->total_paid_tax_incl, (int)$orderObj->id_currency),

                ));

                $results['total_paid'] = Context::getContext()->smarty->fetch($tpl);

            }

            else

                $results['total_paid'] = Tools::displayPrice($orderObj->total_paid_tax_incl, (int)$orderObj->id_currency);

            $results['warning'] = $this->l('Carrier update successful.');

        }else{

            $results['haserror'] = true;

            $results['warning'] = $this->l('Order can\'t be loaded');

        }

        die(Tools::jsonEncode($results));

    }

    public function ajaxProcessSearchProducts()

    {

        Context::getContext()->customer = new Customer((int)Tools::getValue('id_customer'));

        $currency = new Currency((int)Tools::getValue('id_currency'));

        if ($products = Product::searchByName((int)$this->context->language->id, pSQL(Tools::getValue('product_search')))) {

            foreach ($products as &$product) {

                // Formatted price

                $product['formatted_price'] = Tools::displayPrice(Tools::convertPrice($product['price_tax_incl'], $currency), $currency);

                // Concret price

                $product['price_tax_incl'] = Tools::ps_round(Tools::convertPrice($product['price_tax_incl'], $currency), 2);

                $product['price_tax_excl'] = Tools::ps_round(Tools::convertPrice($product['price_tax_excl'], $currency), 2);

                $productObj = new Product((int)$product['id_product'], false, (int)$this->context->language->id);

                $combinations = array();

                $attributes = $productObj->getAttributesGroups((int)$this->context->language->id);



                // Tax rate for this customer

                if (Tools::isSubmit('id_address')) {

                    $product['tax_rate'] = $productObj->getTaxesRate(new Address(Tools::getValue('id_address')));

                }



                $product['warehouse_list'] = array();



                foreach ($attributes as $attribute) {

                    if (!isset($combinations[$attribute['id_product_attribute']]['attributes'])) {

                        $combinations[$attribute['id_product_attribute']]['attributes'] = '';

                    }

                    $combinations[$attribute['id_product_attribute']]['attributes'] .= $attribute['attribute_name'].' - ';

                    $combinations[$attribute['id_product_attribute']]['id_product_attribute'] = $attribute['id_product_attribute'];

                    $combinations[$attribute['id_product_attribute']]['default_on'] = $attribute['default_on'];

                    if (!isset($combinations[$attribute['id_product_attribute']]['price'])) {

                        $price_tax_incl = Product::getPriceStatic((int)$product['id_product'], true, $attribute['id_product_attribute']);

                        $price_tax_excl = Product::getPriceStatic((int)$product['id_product'], false, $attribute['id_product_attribute']);

                        $combinations[$attribute['id_product_attribute']]['price_tax_incl'] = Tools::ps_round(Tools::convertPrice($price_tax_incl, $currency), 2);

                        $combinations[$attribute['id_product_attribute']]['price_tax_excl'] = Tools::ps_round(Tools::convertPrice($price_tax_excl, $currency), 2);

                        $combinations[$attribute['id_product_attribute']]['formatted_price'] = Tools::displayPrice(Tools::convertPrice($price_tax_excl, $currency), $currency);

                    }

                    if (!isset($combinations[$attribute['id_product_attribute']]['qty_in_stock'])) {

                        $combinations[$attribute['id_product_attribute']]['qty_in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$product['id_product'], $attribute['id_product_attribute'], (int)$this->context->shop->id);

                    }



                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && (int)$product['advanced_stock_management'] == 1) {

                        $product['warehouse_list'][$attribute['id_product_attribute']] = Warehouse::getProductWarehouseList($product['id_product'], $attribute['id_product_attribute']);

                    } else {

                        $product['warehouse_list'][$attribute['id_product_attribute']] = array();

                    }



                    $product['stock'][$attribute['id_product_attribute']] = Product::getRealQuantity($product['id_product'], $attribute['id_product_attribute']);

                }



                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && (int)$product['advanced_stock_management'] == 1) {

                    $product['warehouse_list'][0] = Warehouse::getProductWarehouseList($product['id_product']);

                } else {

                    $product['warehouse_list'][0] = array();

                }



                $product['stock'][0] = StockAvailable::getQuantityAvailableByProduct((int)$product['id_product'], 0, (int)$this->context->shop->id);



                foreach ($combinations as &$combination) {

                    $combination['attributes'] = rtrim($combination['attributes'], ' - ');

                }

                $product['combinations'] = $combinations;



                if ($product['customizable']) {

                    $product_instance = new Product((int)$product['id_product']);

                    $product['customization_fields'] = $product_instance->getCustomizationFields($this->context->language->id);

                }

            }



            $to_return = array(

                'products' => $products,

                'found' => true

            );

        } else {

            $to_return = array('found' => false);

        }



        $this->content = Tools::jsonEncode($to_return);

    }

    protected function reinjectQuantity($order_detail, $qty_cancel_product, $delete = false)

    {

        // Reinject product

        $reinjectable_quantity = (int)$order_detail->product_quantity - (int)$order_detail->product_quantity_reinjected;

        $quantity_to_reinject = $qty_cancel_product > $reinjectable_quantity ? $reinjectable_quantity : $qty_cancel_product;

        // @since 1.5.0 : Advanced Stock Management

        //$product_to_inject = new Product($order_detail->product_id, false, (int)$this->context->language->id, (int)$order_detail->id_shop);



        $product = new Product($order_detail->product_id, false, (int)$this->context->language->id, (int)$order_detail->id_shop);



        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $product->advanced_stock_management && $order_detail->id_warehouse != 0) {

            $manager = StockManagerFactory::getManager();

            $movements = StockMvt::getNegativeStockMvts(

                                $order_detail->id_order,

                                $order_detail->product_id,

                                $order_detail->product_attribute_id,

                                $quantity_to_reinject

                            );

            $left_to_reinject = $quantity_to_reinject;

            foreach ($movements as $movement) {

                if ($left_to_reinject > $movement['physical_quantity']) {

                    $quantity_to_reinject = $movement['physical_quantity'];

                }



                $left_to_reinject -= $quantity_to_reinject;

                if (Pack::isPack((int)$product->id)) {

                    // Gets items

                        if ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)) {

                            $products_pack = Pack::getItems((int)$product->id, (int)Configuration::get('PS_LANG_DEFAULT'));

                            // Foreach item

                            foreach ($products_pack as $product_pack) {

                                if ($product_pack->advanced_stock_management == 1) {

                                    $manager->addProduct(

                                        $product_pack->id,

                                        $product_pack->id_pack_product_attribute,

                                        new Warehouse($movement['id_warehouse']),

                                        $product_pack->pack_quantity * $quantity_to_reinject,

                                        null,

                                        $movement['price_te'],

                                        true

                                    );

                                }

                            }

                        }

                    if ($product->pack_stock_type == 0 || $product->pack_stock_type == 2 ||

                            ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 || Configuration::get('PS_PACK_STOCK_TYPE') == 2))) {

                        $manager->addProduct(

                                $order_detail->product_id,

                                $order_detail->product_attribute_id,

                                new Warehouse($movement['id_warehouse']),

                                $quantity_to_reinject,

                                null,

                                $movement['price_te'],

                                true

                            );

                    }

                } else {

                    $manager->addProduct(

                            $order_detail->product_id,

                            $order_detail->product_attribute_id,

                            new Warehouse($movement['id_warehouse']),

                            $quantity_to_reinject,

                            null,

                            $movement['price_te'],

                            true

                        );

                }

            }



            $id_product = $order_detail->product_id;

            if ($delete) {

                $order_detail->delete();

            }

            StockAvailable::synchronize($id_product);

        } elseif ($order_detail->id_warehouse == 0) {

            StockAvailable::updateQuantity(

                $order_detail->product_id,

                $order_detail->product_attribute_id,

                $quantity_to_reinject,

                $order_detail->id_shop,

                true,

                array(

                    'id_order' => $order_detail->id_order,

                    'id_stock_mvt_reason' => Configuration::get('PS_STOCK_CUSTOMER_RETURN_REASON')

                )

            );



            // sync all stock

            (new StockManager())->updatePhysicalProductQuantity(

                (int)$order_detail->id_shop,

                (int)Configuration::get('PS_OS_ERROR'),

                (int)Configuration::get('PS_OS_CANCELED'),

                null,

                (int)$order_detail->id_order

            );



            if ($delete) {

                $order_detail->delete();

            }

        } else {

            $this->errors[] = $this->trans('This product cannot be re-stocked.', array(), 'Admin.Orderscustomers.Notification');

        }

    }

    public function ajaxProcessSearchCustomers()

    {

        $searches = explode(' ', Tools::getValue('customer_search'));

        $customers = array();

        $searches = array_unique($searches);

        if($searches)

            foreach ($searches as $search) {

                if (!empty($search) && $results = Customer::searchByName($search, 50)) {

                    foreach ($results as $result) {

                        if ($result['active']) {

                            if (version_compare(_PS_VERSION_, '1.7.7', '>='))

                                $result['customer_link'] = $this->context->link->getAdminLink('AdminCustomers',true,array('route'=>'admin_customers_view','customerId'=>(int)$result['id_customer']));

                            else

                                $result['customer_link'] = $this->context->link->getAdminLink('AdminCustomers',true).'&viewcustomer&id_customer='.(int)$result['id_customer'];

                            $customers[$result['id_customer']] = $result;

                        }

                    }

                }

            }



        if ($customers) {

            $to_return = array(

                'customers' => $customers,

                'found' => true

            );

        } else {

            $to_return = array('found' => false);

        }



        die(Tools::jsonEncode($to_return));

    }

    





    //Tung update search product 12/12/2021

    public function searchProductNew(){ 

        Context::getContext()->customer = new Customer((int)Tools::getValue('id_customer'));

        $currency = new Currency((int)Tools::getValue('id_currency'));

        if ($products = Product::searchByName((int)$this->context->language->id, pSQL(Tools::getValue('product_search')))) {

            foreach ($products as &$product) {

                // Formatted price

                $product['formatted_price'] = Tools::displayPrice(Tools::convertPrice($product['price_tax_incl'], $currency), $currency);

                // Concret price

                $product['price_tax_incl'] = Tools::ps_round(Tools::convertPrice($product['price_tax_incl'], $currency), 2);

                $product['price_tax_excl'] = Tools::ps_round(Tools::convertPrice($product['price_tax_excl'], $currency), 2);

                $productObj = new Product((int)$product['id_product'], false, (int)$this->context->language->id);

                $combinations = array();

                $attributes = $productObj->getAttributesGroups((int)$this->context->language->id);



                // Tax rate for this customer

                if (Tools::isSubmit('id_address')) {

                    $product['tax_rate'] = $productObj->getTaxesRate(new Address(Tools::getValue('id_address')));

                }



                $product['warehouse_list'] = array();



                foreach ($attributes as $attribute) {

                    if (!isset($combinations[$attribute['id_product_attribute']]['attributes'])) {

                        $combinations[$attribute['id_product_attribute']]['attributes'] = '';

                    }

                    $combinations[$attribute['id_product_attribute']]['attributes'] .= $attribute['attribute_name'].' - ';

                    $combinations[$attribute['id_product_attribute']]['id_product_attribute'] = $attribute['id_product_attribute'];

                    $combinations[$attribute['id_product_attribute']]['default_on'] = $attribute['default_on'];

                    if (!isset($combinations[$attribute['id_product_attribute']]['price'])) {

                        $price_tax_incl = Product::getPriceStatic((int)$product['id_product'], true, $attribute['id_product_attribute']);

                        $price_tax_excl = Product::getPriceStatic((int)$product['id_product'], false, $attribute['id_product_attribute']);

                        $combinations[$attribute['id_product_attribute']]['price_tax_incl'] = Tools::ps_round(Tools::convertPrice($price_tax_incl, $currency), 2);

                        $combinations[$attribute['id_product_attribute']]['price_tax_excl'] = Tools::ps_round(Tools::convertPrice($price_tax_excl, $currency), 2);

                        $combinations[$attribute['id_product_attribute']]['formatted_price'] = Tools::displayPrice(Tools::convertPrice($price_tax_excl, $currency), $currency);

                    }

                    if (!isset($combinations[$attribute['id_product_attribute']]['qty_in_stock'])) {

                        $combinations[$attribute['id_product_attribute']]['qty_in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$product['id_product'], $attribute['id_product_attribute'], (int)$this->context->shop->id);

                    }



                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && (int)$product['advanced_stock_management'] == 1) {

                        $product['warehouse_list'][$attribute['id_product_attribute']] = Warehouse::getProductWarehouseList($product['id_product'], $attribute['id_product_attribute']);

                    } else {

                        $product['warehouse_list'][$attribute['id_product_attribute']] = array();

                    }



                    $product['stock'][$attribute['id_product_attribute']] = Product::getRealQuantity($product['id_product'], $attribute['id_product_attribute']);

                }



                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && (int)$product['advanced_stock_management'] == 1) {

                    $product['warehouse_list'][0] = Warehouse::getProductWarehouseList($product['id_product']);

                } else {

                    $product['warehouse_list'][0] = array();

                }



                $product['stock'][0] = StockAvailable::getQuantityAvailableByProduct((int)$product['id_product'], 0, (int)$this->context->shop->id);



                foreach ($combinations as &$combination) {

                    $combination['attributes'] = rtrim($combination['attributes'], ' - ');

                }

                $product['combinations'] = $combinations;



                if ($product['customizable']) {

                    $product_instance = new Product((int)$product['id_product']);

                    $product['customization_fields'] = $product_instance->getCustomizationFields($this->context->language->id);

                }

            }



            $to_return = array(

                'products' => $products,

                'found' => true

            );

        } else {

            $to_return = array('found' => false);

        }



        $this->content = Tools::jsonEncode($to_return);

    }

    // Tung update 14/12/2021 note invoice

    public function ajaxEditNote(){

        $g_note=Tools::getValue('note');

        $id_order_invoice=(int)Tools::getValue('id_order_invoice');

        if($id_order_invoice!=null){

            $sql = 'UPDATE `'._DB_PREFIX_.'order_invoice` 

            SET note = "'.pSql($g_note).'" 

            WHERE id_order_invoice='.(int)$id_order_invoice;

            

            if(Db::getInstance()->execute($sql)){

                print_r(true);

                die();

            }else{

                print_r(false);

                die();

            }

        }

    }

    // Tung update 14/12/2021 customer note

    public function ajaxCustomerNote(){

        $id_order=(int)Tools::getValue('id_order');

        $g_customer_note=Tools::getValue('noteContent');



        $order = new Order((int)$id_order);

        $customer = new Customer($order->id_customer);

        

        $customer->note = $g_customer_note;

       

        

       

        if($customer->save()){

            print_r(true);

                die();

            

        }else{

            print_r(false);

            die();

        }

    }

    

}

