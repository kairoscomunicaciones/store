<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class AdminRestrictPaymentMethodController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'Rpm';
        $this->table = 'restrictpaymentmethods';
        $this->identifier = 'id_restrictpaymentmethods';
        $this->lang = false;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bootstrap = true;
        parent::__construct();
        $this->context = Context::getContext();
        $this->_select = 'a.id_restrictpaymentmethods payments';
        $this->_use_found_rows = false;
        
        $this->fields_list = array(
            'id_restrictpaymentmethods' => array(
                'title'     => 'ID',
                'width' => 25
            ),
            'title' => array(
                'title'     => $this->module->l('Title'),
                'width' => 'auto'
            ),
            'payments' => array(
                'title'     => $this->module->l('Payment Methods'),
                'width' => 'auto',
                'callback' => 'getPaymentMeths',
                'orderby' => false,
                'search' => false,
                'havingFilter' => false,
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'type' => 'bool',
                'active' => 'status'
                ),
            'start_date' => array(
                'title' => $this->module->l('Start'),
                'type' => 'date',
            ),
            'expiry_date' => array(
                'title' => $this->module->l('End'),
                'type' => 'date',
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
                )
            );
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUI(array('ui.datepicker'));
    }
    
    public function renderForm()
    {
        $obj = $this->loadObject(true);
        $type = (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<') == true) ? 'radio' : 'switch';
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Add/Edit Payment Restriction'),
                'icon' => 'icon-eye-slash'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'lang' => false,
                    'required' => false,
                    'hint' => $this->l('Invalid characters:').' <>;=#{}',
                    'desc' => $this->l('For back office use only.'),
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => true,
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
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Payment Methods to Disable'),
                    'required' => true,
                    'name' => 'payments',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Category Rule?'),
                    'name' => 'rpm_has_categories',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_categories_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_categories_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Cart has Categories'),
                    'name' => 'categories',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Products Rule?'),
                    'name' => 'rpm_has_products',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_products_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_products_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Cart has Products'),
                    'name' => 'products',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Brands Rule?'),
                    'name' => 'rpm_has_brands',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_brands_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_brands_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Cart has Brands'),
                    'name' => 'brands',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Suppliers Rule?'),
                    'name' => 'rpm_has_suppliers',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_suppliers_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_suppliers_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Cart has Suppliers'),
                    'name' => 'suppliers',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Cart Range Rule?'),
                    'name' => 'rpm_has_range',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_range_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_range_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Cart total in range'),
                    'name' => 'range',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Ranges include TAX?'),
                    'name' => 'range_tax',
                    'required' => false,
                    'class' => 'rpm_has_range',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'range_tax_on',
                            'value' => 0,
                            'label' => $this->l('Without Tax')
                        ),
                        array(
                            'id' => 'range_tax_off',
                            'value' => 1,
                            'label' => $this->l('With Tax')
                        )
                    )
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Ranges Calculation Criteria'),
                    'name' => 'range_calc',
                    'class' => 'rpm_has_range',
                    'required' => false,
                    'is_bool' => false,
                    'values' => array(
                        array(
                            'id' => 'range_calc_0',
                            'value' => 0,
                            'label' => $this->l('With Shipping and Discounts')
                        ),
                        array(
                            'id' => 'range_calc_1',
                            'value' => 1,
                            'label' => $this->l('Without Shipping and Discounts')
                        ),
                        array(
                            'id' => 'range_calc_2',
                            'value' => 2,
                            'label' => $this->l('Without Shipping but With Discounts')
                        )
                    )
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Customer Group Rule?'),
                    'name' => 'rpm_has_groupBox',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_groupBox_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_groupBox_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'group',
                    'label' => $this->l('Disable Payments for Customer Groups'),
                    'name' => 'groupBox',
                    'required' => false,
                    'values' => Group::getGroups($this->context->language->id),
                    'hint' => $this->l('Mark all of the customer groups which you would like to activate this rule.'),
                    'desc' => $this->l('*Warning: It will disable payment methods selected for these customer groups above.'),
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Cities Rule?'),
                    'name' => 'rpm_has_cities',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_cities_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_cities_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if City is used'),
                    'required' => false,
                    'name' => 'cities',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Zip Codes Rule?'),
                    'name' => 'rpm_has_zips',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_zips_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_zips_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if ZipCode is used'),
                    'required' => false,
                    'name' => 'zips',
                ),
                array(
                    'type' => $type,
                    'label' => $this->l('Week Days Rule?'),
                    'name' => 'rpm_has_days',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rpm_has_days_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'rpm_has_days_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Apply if Day is used'),
                    'required' => false,
                    'name' => 'days',
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Restriction Starts'),
                    'name' => 'start_date',
                    'filter_key' => 'a!start_date'
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Restriction Ends'),
                    'name' => 'expiry_date',
                    'filter_key' => 'a!expiry_date'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                ),
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
            );
        }
        $payment_methods = PaymentModule::getInstalledPaymentModules();
        if (!empty($payment_methods)) {
            foreach ($payment_methods as &$method) {
                $method['title'] = Module::getModuleName($method['name']);
            }
        }
        $products = array();
        $brands = array();
        $suppliers = array();
        $cities = array();
        $zips = array();
        $days = array(
            0 => array(
                'id_day' => 1,
                'day' => $this->l('Monday')
            ),
            1 => array(
                'id_day' => 2,
                'day' => $this->l('Tuesday')
            ),
            2 => array(
                'id_day' => 3,
                'day' => $this->l('Wednesday')
            ),
            3 => array(
                'id_day' => 4,
                'day' => $this->l('Thursday')
            ),
            4 => array(
                'id_day' => 5,
                'day' => $this->l('Friday')
            ),
            5 => array(
                'id_day' => 6,
                'day' => $this->l('Saturday')
            ),
            6 => array(
                'id_day' => 7,
                'day' => $this->l('Sunday')
            )
            );
        $categories = Category::getSimpleCategories($this->context->language->id);
        $url = $this->context->link->getAdminLink('AdminRestrictPaymentMethod', true);
        $ps_17 = (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) ? 1 : 0;
        if ($obj->id) {
            $cities = $obj->getCollection($obj->id, 'name', 'restrictpaymentmethods_cities');
            $zips = $obj->getCollection($obj->id, 'code', 'restrictpaymentmethods_zips');
            $brands = $obj->getCollection($obj->id, '*', 'restrictpaymentmethods_brands', true);
            if (!empty($brands)) {
                foreach ($brands as &$brand) {
                    $brand_obj = new Manufacturer($brand['id_brand'], $this->context->language->id);
                    $brand['name'] = $brand_obj->name;
                }
            }
            $suppliers = $obj->getCollection($obj->id, '*', 'restrictpaymentmethods_suppliers', true);
            if (!empty($suppliers)) {
                foreach ($suppliers as &$supplier) {
                    $supplier_obj = new Supplier($supplier['id_supplier'], $this->context->language->id);
                    $supplier['name'] = $supplier_obj->name;
                }
            }
            if (!empty($categories)) {
                foreach ($categories as &$category) {
                    $category['checked'] = (int)$obj->needleCheck('restrictpaymentmethods_categories', 'id_category', $category['id_category'], $obj->id);
                }
            }
            $products = $obj->getProductsCollection($obj->id);
            if (!empty($products) && is_array($products)) {
                foreach ($products as &$product) {
                    $product = new Product((int)$product['id_product'], true, (int)$this->context->language->id);
                    $product->id_product_attribute = (int)Product::getDefaultAttribute($product->id) > 0 ? (int)Product::getDefaultAttribute($product->id) : 0;
                    $_cover = ((int)$product->id_product_attribute > 0) ? Product::getCombinationImageById((int)$product->id_product_attribute, $this->context->language->id) : Product::getCover($product->id);
                    if (!is_array($_cover)) {
                       $_cover = Product::getCover($product->id);
                    }
                    $product->id_image = $_cover['id_image'];
                }
            }
            if (!empty($payment_methods)) {
                foreach ($payment_methods as &$payment) {
                    $payment['checked'] = (int)$obj->needleCheck('restrictpaymentmethods_payment', 'name', $payment['name'], $obj->id);
                }
            }
            $groups = Group::getGroups($this->context->language->id);
            foreach ($groups as $group) {
                $groups_ids = $obj->needleCheck('restrictpaymentmethods_group', 'id_group', $group['id_group'], $obj->id);
                $this->fields_value['groupBox_'.$group['id_group']] = $groups_ids;
            }
            foreach ($days as &$day) {
                $day['checked'] = (int)$obj->needleCheck('restrictpaymentmethods_days', 'id_day', $day['id_day'], $obj->id);
            }
        }
        else {
            $groups = Group::getGroups($this->context->language->id);
            foreach ($groups as $group) {
                $this->fields_value['groupBox_'.$group['id_group']] = 0;
            }
        }
        $default_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($default_currency);
        //dump($brands);exit;
        $this->context->smarty->assign(array(
            'obj' => $obj,
            'ps_17' => (int)$ps_17,
            'categories' => $categories,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'products' => $products,
            'cities' => $cities,
            'zips' => $zips,
            'days' => $days,
            'payment_methods' => $payment_methods,
            'action_url' => $url.'&action=getSearchProducts&forceJson=1&disableCombination=1&exclude_packs=0&excludeVirtuals=0&limit=20',
            'currency_code' => $currency->iso_code.'('.$currency->sign.')',
            'action_url_brands' => $url.'&action=getBrands',
            'action_url_supps' => $url.'&action=getSupps'
        ));
        return parent::renderForm();
    }
    
    public function init()
	{
		parent::init();
		Shop::addTableAssociation($this->table, array('type' => 'shop'));
		if (Shop::getContext() == Shop::CONTEXT_SHOP)
			$this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'restrictpaymentmethods_shop` sa ON (a.`id_restrictpaymentmethods` = sa.`id_restrictpaymentmethods` AND sa.id_shop = '.(int)$this->context->shop->id.') ';
		if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive())
			$this->_where = ' AND sa.`id_shop` = '.(int)Context::getContext()->shop->id;
	}
    
    public function initProcess()
    {
        $action = Tools::getValue('action');
        if (Tools::isSubmit('submitAddrestrictpaymentmethods')) {
            $rpm_has_categories = (int)Tools::getValue('rpm_has_categories');
            $rpm_has_products = (int)Tools::getValue('rpm_has_products');
            $rpm_has_brands = (int)Tools::getValue('rpm_has_brands');
            $rpm_has_suppliers = (int)Tools::getValue('rpm_has_suppliers');
            $rpm_has_range = (int)Tools::getValue('rpm_has_range');
            $rpm_has_groupBox = (int)Tools::getValue('rpm_has_groupBox');
            $rpm_has_cities = (int)Tools::getValue('rpm_has_cities');
            $rpm_has_zips = (int)Tools::getValue('rpm_has_zips');
            $rpm_has_days = (int)Tools::getValue('rpm_has_days');
            $range_from = (int)Tools::getValue('range_min');
            $range_max = (int)Tools::getValue('range_max');
            $payments = Tools::getValue('payments');
            $groups = Tools::getValue('groupBox');
            $categories = Tools::getValue('category');
            $products = Tools::getValue('related_products');
            $brands = Tools::getValue('brands_filter');
            $suppliers = Tools::getValue('suppliers_filter');
            $cities = Tools::getValue('city_filter');
            $zips = Tools::getValue('zip_filter');
            $days = Tools::getValue('days_filter');
            $cms = Tools::getValue('cms');
            if (empty($payments)) {
                $this->errors[] = $this->l('You must select one or more payment methods.');
            }
            elseif((empty($categories) || $rpm_has_categories <= 0) && (empty($products) || $rpm_has_products <= 0) && (empty($cities) || $rpm_has_cities <= 0) && (empty($zips) || $rpm_has_zips <= 0)
                   && (empty($groups) || $rpm_has_groupBox <= 0) && (empty($range_max) || $range_max < 1 || $rpm_has_range <= 0)
                   && (empty($days) || $rpm_has_days <= 0) && (empty($brands) || $rpm_has_brands <= 0) && (empty($suppliers) || $rpm_has_suppliers <= 0)) {
                $this->errors[] = $this->l('You must select any product, zip codes, cities, days, category OR Customer Group OR Cart total range to create a rule.');
            }
            elseif ($range_from > $range_max) {
                $this->errors[] = $this->l('Range rule is incorrect, From value must be less than To value.');
            }
        }
        if ($action == 'getSearchProducts') {
            $this->getSearchProducts();
            die();
        }
        if ($action == 'getBrands') {
            $this->getBrands();
            die();
        }
        if ($action == 'getSupps') {
            $this->getSuppliers();
            die();
        }
        return parent::initProcess();
    }
    
    public function postProcess()
    {
        parent::postProcess();
        $obj = $this->loadObject(true);
        $payments = Tools::getValue('payments');
        $categories = Tools::getValue('category');
        $products = Tools::getValue('related_products');
        $brands = Tools::getValue('brands_filter');
        $suppliers = Tools::getValue('suppliers_filter');
        $groups = Tools::getValue('groupBox');
        $cms = Tools::getValue('cms');
        $cities = Tools::getValue('city_filter');
        $zips = Tools::getValue('zip_filter');
        $days = Tools::getValue('days_filter');
        if ($obj->id && Tools::isSubmit('submitAddrestrictpaymentmethods')) {
            $obj->dumpCurrentData($obj->id);
            if (!empty($groups)) {
                $obj->populateTable('restrictpaymentmethods_group', 'id_group', $obj->id, $groups);
            }
            if (!empty($payments)) {
                $obj->populateTable('restrictpaymentmethods_payment', 'name', $obj->id, $payments);
            }
            if (!empty($categories)) {
                $obj->populateTable('restrictpaymentmethods_categories', 'id_category', $obj->id, $categories);
            }
            if (!empty($products)) {
                $obj->populateTable('restrictpaymentmethods_products', 'id_product', $obj->id, $products);
            }
            if (!empty($brands)) {
                $obj->populateTable('restrictpaymentmethods_brands', 'id_brand', $obj->id, $brands);
            }
            if (!empty($suppliers)) {
                $obj->populateTable('restrictpaymentmethods_suppliers', 'id_supplier', $obj->id, $suppliers);
            }
            if (!empty($cms)) {
                $obj->populateTable('restrictpaymentmethods_cms', 'id_cms', $obj->id, $cms);
            }
            if (!empty($cities)) {
                $obj->populateTable('restrictpaymentmethods_cities', 'name', $obj->id, $cities);
            }
            if (!empty($zips)) {
                $obj->populateTable('restrictpaymentmethods_zips', 'code', $obj->id, $zips);
            }
            if (!empty($days)) {
                $obj->populateTable('restrictpaymentmethods_days', 'id_day', $obj->id, $days);
            }
        }
    }
    
    protected function getSearchProducts()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die(json_encode($this->l('Found Nothing.')));
        }

        /*
         * In the SQL request the "q" param is used entirely to match result in database.
         * In this way if string:"(ref : #ref_pattern#)" is displayed on the return list,
         * they are no return values just because string:"(ref : #ref_pattern#)"
         * is not write in the name field of the product.
         * So the ref pattern will be cut for the search request.
         */
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }

        // Excluding downloadable products from packs because download from pack is not supported
        $forceJson = Tools::getValue('forceJson', false);
        $disableCombination = Tools::getValue('disableCombination', false);
        $excludeVirtuals = (bool)Tools::getValue('excludeVirtuals', true);
        $exclude_packs = (bool)Tools::getValue('exclude_packs', true);

        $context = Context::getContext();

        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)$context->language->id.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$context->language->id.')
                WHERE (pl.name LIKE \'%'.pSQL($query).'%\' OR p.reference LIKE \'%'.pSQL($query).'%\')'.
                (!empty($excludeIds) ? ' AND p.id_product NOT IN ('.$excludeIds.') ' : ' ').
                ($excludeVirtuals ? 'AND NOT EXISTS (SELECT 1 FROM `'._DB_PREFIX_.'product_download` pd WHERE (pd.id_product = p.id_product))' : '').
                ($exclude_packs ? 'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '').
                ' GROUP BY p.id_product';

        $items = Db::getInstance()->executeS($sql);
        if ($items && ($disableCombination ||$excludeIds)) {
            $results = array();
            foreach ($items as $item) {
                if (!$forceJson) {
                    $item['name'] = str_replace('|', '&#124;', $item['name']);
                    $results[] = trim($item['name']).(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '').'|'.(int)$item['id_product'];
                } else {
                    $cover = Product::getCover($item['id_product']);
                    $theName = $item['name'].(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '');
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => str_replace('\'', '', $theName),
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], (($item['id_image'])?$item['id_image']:$cover['id_image']), $this->getFormatedName('home'))),
                    );
                }
            }

            if (!$forceJson) {
                echo implode("\n", $results);
            } else {
                echo json_encode($results);
            }
        } elseif ($items) {
            // packs
            $results = array();
            foreach ($items as $item) {
                // check if product have combination
                if (Combination::isFeatureActive() && $item['cache_default_attribute']) {
                    $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, ag.`id_attribute_group`, pai.`id_image`, agl.`name` AS group_name, al.`name` AS attribute_name,
                                a.`id_attribute`
                            FROM `'._DB_PREFIX_.'product_attribute` pa
                            '.Shop::addSqlAssociation('product_attribute', 'pa').'
                            LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                            LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                            LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                            LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$context->language->id.')
                            LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)$context->language->id.')
                            LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
                            WHERE pa.`id_product` = '.(int)$item['id_product'].'
                            GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                            ORDER BY pa.`id_product_attribute`';

                    $combinations = Db::getInstance()->executeS($sql);
                    if (!empty($combinations)) {
                        foreach ($combinations as $combination) {
                            $cover = Product::getCover($item['id_product']);
                            $results[$combination['id_product_attribute']]['id'] = $item['id_product'];
                            $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                            !empty($results[$combination['id_product_attribute']]['name']) ? $results[$combination['id_product_attribute']]['name'] .= ' '.$combination['group_name'].'-'.$combination['attribute_name']
                            : $results[$combination['id_product_attribute']]['name'] = $item['name'].' '.$combination['group_name'].'-'.$combination['attribute_name'];
                            if (!empty($combination['reference'])) {
                                $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                            } else {
                                $results[$combination['id_product_attribute']]['ref'] = !empty($item['reference']) ? $item['reference'] : '';
                            }
                            if (empty($results[$combination['id_product_attribute']]['image'])) {
                                $results[$combination['id_product_attribute']]['image'] = str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], (($combination['id_image'])?$combination['id_image']:$cover['id_image']), $this->getFormatedName('home')));

                            }
                        }
                    } else {
                        $results[] = array(
                            'id' => $item['id_product'],
                            'name' => $item['name'],
                            'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                            'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $this->getFormatedName('home'))),
                        );
                    }
                } else {
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => $item['name'],
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $this->getFormatedName('home'))),
                    );
                }
            }
            echo json_encode(array_values($results));
        } else {
            echo json_encode(array());
        }
    }
    
    public function getFormatedName($name)
    {
        $theme_name = Context::getContext()->shop->theme_name;
        $name_without_theme_name = str_replace(array('_'.$theme_name, $theme_name.'_'), '', $name);
        //check if the theme name is already in $name if yes only return $name
        if (strstr($name, $theme_name) && ImageType::getByNameNType($name, 'products')) {
            return $name;
        } elseif (ImageType::getByNameNType($name_without_theme_name.'_'.$theme_name, 'products')) {
            return $name_without_theme_name.'_'.$theme_name;
        } elseif (ImageType::getByNameNType($theme_name.'_'.$name_without_theme_name, 'products')) {
            return $theme_name.'_'.$name_without_theme_name;
        } else {
            return $name_without_theme_name.'_default';
        }
    }
    
    public static function getPaymentMeths($id)
    {
        $class = new Rpm;
        $collection = $class->getPmBunch($id);
        return $collection['payments'];
    }
    
    protected function getBrands()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die(json_encode($this->l('Found Nothing.')));
        }
        $items = Db::getInstance()->executeS('SELECT *
        FROM `'._DB_PREFIX_.'manufacturer`
        WHERE `name` LIKE "%'.pSQL($query).'%"');
        if ($items) {
            $results = array();
            foreach ($items as $item) {
                $results[] = array(
                        'id' => $item['id_manufacturer'],
                        'name' => $item['name']
                    );
            }
            echo json_encode($results);
        }
    }
    
    protected function getSuppliers()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die(json_encode($this->l('Found Nothing.')));
        }
        $items = Db::getInstance()->executeS('SELECT *
        FROM `'._DB_PREFIX_.'supplier`
        WHERE `name` LIKE "%'.pSQL($query).'%"');
        if ($items) {
            $results = array();
            foreach ($items as $item) {
                $results[] = array(
                        'id' => $item['id_supplier'],
                        'name' => $item['name']
                    );
            }
            echo json_encode($results);
        }
    }
}
