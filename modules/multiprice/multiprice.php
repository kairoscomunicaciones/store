<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class multiprice extends Module
{
    public function __construct()
    {
        ini_set("display_errors", 0);
        error_reporting(0); //E_ALL
        $this->name = 'multiprice';
        $this->tab = 'front_office_features';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = 'https://mypresta.eu/modules/front-office-features/show-price-in-different-currencies-at-the-same-time.html';
        $this->version = '1.5.2';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Display price in many currencies');
        $this->description = $this->l('This is prestashop module that allows to show many prices of product in many currencies');
        if (Configuration::get('MP_CURR') != false) {
            foreach (explode(",", Configuration::get('MP_CURR')) as $currency) {
                $this->mp_curr[$currency] = true;
            }
        }
        $this->checkforupdates();
        $this->priceModifiersProductLists = array(
            array('name' => 'old_price', 'name_show' => 'old_price ' . $this->l('(visible only if product has discount)')),
            array('name' => 'before_price', 'name_show' => 'before_price'),
            array('name' => 'unit_price', 'name_show' => 'unit_price'),
            array('name' => 'weight', 'name_show' => 'weight'),
            array('name' => 'custom', 'name_show' => 'custom'),
        );
        $this->priceModifiersProductPage = array(
            array('name' => 'old_price', 'name_show' => 'old_price ' . $this->l('(visible only if product has discount)')),
            array('name' => 'weight', 'name_show' => 'weight'),
            array('name' => 'price', 'name_show' => 'price'),
            array('name' => 'after_price', 'name_show' => 'after_price'),
            array('name' => 'custom', 'name_show' => 'custom'),
        );
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = multipriceUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (multipriceUpdate::version($this->version) < multipriceUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = multipriceUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (multipriceUpdate::version($this->version) < multipriceUpdate::version(multipriceUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }

    public function inconsistency($ret)
    {
        return;
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayMultiPrice') ||
            !$this->alterTable('add') ||
            !$this->registerHook('header') ||
            !$this->registerHook('actionObjectOrderAddAfter') ||
            !$this->registerHook('displayProductPriceBlock') ||
            !$this->registerHook('ActionAdminControllerSetMedia') ||
            !Configuration::updateValue('MP_PP', 'weight') ||
            !Configuration::updateValue('MP_PL', 'weight')) {
            return false;
        }
        return true;
    }

    public function hookHeader($params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/multiprice.css');
    }

    private function maybeUpdateDatabase($table, $column, $type = "int(8)", $default = "1", $null = "NULL", $onUpdate = '', $drop = false, $wtd = 'ADD')
    {
        $sql = 'DESCRIBE ' . _DB_PREFIX_ . $table;
        $columns = Db::getInstance()->executeS($sql);
        $found = false;
        foreach ($columns as $col) {
            if ($col['Field'] == $column) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            if ($drop == false) {
                if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` ' . $wtd . ' `' . $column . '` ' . $type . ' DEFAULT ' . $default . ' ' . $null . ' ' . $onUpdate)) {
                    return false;
                }
            }
        } else {
            if ($wtd == 'MODIFY') {
                if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` ' . $wtd . ' `' . $column . '` ' . $type . ' DEFAULT ' . $default . ' ' . $null . ' ' . $onUpdate)) {
                    return false;
                }
            }

            if ($drop == true) {
                if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` DROP COLUMN `' . $column . '`')) {
                    return false;
                }
            }
        }

        return true;
    }

    public function alterTable($method)
    {
        $this->maybeUpdateDatabase('orders', 'currencies', "TEXT", NULL);
        return true;
    }

    public function hookactionObjectOrderAddAfter($params)
    {
        if (isset($params['object']->id)) {
            $array = array();
            foreach (Currency::getCurrencies(true, false) AS $currency) {
                $array[] = $currency->iso_code . '|' . $currency->id . '|' . $currency->conversion_rate;
            }
            Db::getInstance()->update('orders', array('currencies' => implode(";", $array)), 'id_order = ' . $params['object']->id);
        }
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJS($this->_path . 'views/js/multiprice.js');
    }

    public function returnOldPrice($id_order, $amount, Currency $currency_from = null, Currency $currency_to = null)
    {
        if (isset($id_order)) {
            if ($currency_from == $currency_to) {
                return $amount;
            }

            $historical_currencies = $this->getCurrenciesIdOrder($id_order);
            $historical_currencies = explode(";", $historical_currencies['currencies']);

            foreach ($historical_currencies AS $currency_entry) {
                $exploded_currency_entry = explode("|", $currency_entry);
                if ($exploded_currency_entry[0] == $currency_from->iso_code) {
                    $currency_from->conversion_rate = $exploded_currency_entry[2];
                }
                if ($exploded_currency_entry[0] == $currency_to->iso_code) {
                    $currency_to->conversion_rate = $exploded_currency_entry[2];
                }
            }

            if ($currency_from->id == Configuration::get('PS_CURRENCY_DEFAULT')) {
                $amount *= $currency_to->conversion_rate;
            } else {
                $conversion_rate = ($currency_from->conversion_rate == 0 ? 1 : $currency_from->conversion_rate);
                $amount = $amount / $conversion_rate;
                $amount *= $currency_to->conversion_rate;
            }
            return Tools::ps_round($amount, Context::getContext()->getComputingPrecision());
        }
    }

    public function getCurrenciesIdOrder($id_order)
    {
        return Db::getInstance()->getRow('SELECT currencies FROM ' . _DB_PREFIX_ . 'orders WHERE id_order = ' . $id_order);
    }

    public function hookdisplayMultiPrice($params)
    {
        if (isset($params['order'])) {
            $order = new Order($params['order']);
            $currency_now = new Currency($order->id_currency, Context::getContext()->language->id);

            foreach ($this->mp_curr AS $currency => $val) {
                if ($currency != $currency_now->id) {
                    $currency_to = new Currency($currency);
                    $this->multiprice[$currency]['value'] = Tools::displayPrice(multiprice::returnOldPrice($params['order'], $params['price'], $currency_now, $currency_to), $currency_to);
                }
            }
            if (count($this->multiprice) <= 0) {
                return false;
            }

            $this->context->smarty->assign('multiprice', $this->multiprice);
            return $this->display(__file__, 'views/templates/multiprice-custom.tpl');


        }

        if (isset($params['price'])) {
            if (isset($params['currency'])) {
                $currency_now = new Currency($params['currency'], Context::getContext()->language->id);
            } else {
                $currency_now = $this->context->currency;
            }

            if (count($this->mp_curr) > 0) {
                foreach ($this->mp_curr AS $currency => $val) {
                    if ($currency != $currency_now->id) {
                        $currency_to = new Currency($currency);
                        $this->multiprice[$currency]['value'] = Tools::displayPrice(Tools::convertPriceFull($params['price'], $currency_now, $currency_to), $currency_to);
                    }
                }
            }

            if (count($this->multiprice) <= 0) {
                return false;
            }

            $this->context->smarty->assign('multiprice', $this->multiprice);
            return $this->display(__file__, 'views/templates/multiprice-custom.tpl');
        }
    }

    public function hookdisplayProductPriceBlock($params)
    {
        if (Configuration::get('mp_visibility_rule') == 1) {
            $associated = true;
            if (!in_array(($params['product']['id_product']), explode(",", Configuration::get('mp_visibility_products')))) {
                $associated = false;
            }

            if (Configuration::get('mp_visibility_cat') != '' ||
                Configuration::get('mp_visibility_cat') != false ||
                Configuration::get('mp_visibility_cat') != null) {
                $exploded_categories = explode(",", Configuration::get('mp_visibility_cat'));
                $associated_with_cat = false;
                foreach (Product::getProductCategories($params['product']['id_product']) AS $pkey => $pval) {
                    foreach ($exploded_categories AS $exk => $exv) {
                        if ($pval == $exv) {
                            $associated_with_cat = true;
                        }
                    }
                }
            } else {
                $associated_with_cat = false;
            }

            if (!$associated_with_cat && !$associated) {
                return;
            }
        }

        if (Configuration::get('MP_SHOW_TYPES') == true) {
            return '<div style="display:block; clear:both; background: orange; border:2px dotted black; padding:10px; font-size:16px; text-align:center; color:#000;">' . (isset($params['type']) ? $params['type'] : 'unknown') . '</div>';
        }

        if (Configuration::get('MP_PL') == 'custom') {
            $MP_plc = (Configuration::get('MP_PLC') != "" && Configuration::get('MP_PLC') != false ? Configuration::get('MP_PLC') : 'weight');
        } else {
            $MP_plc = Configuration::get('MP_PL');
        }

        if (Configuration::get('MP_PP') == 'custom') {
            $MP_ppl = (Configuration::get('MP_PP') != "" && Configuration::get('MP_PPC') != false ? Configuration::get('MP_PPC') : 'weight');
        } else {
            $MP_ppl = Configuration::get('MP_PP');
        }

        if (Tools::version_compare(_PS_VERSION_, '1.7.5.0', '<')) {
            $params['product'] = (array)$params['product'];
        }

        if (Tools::GetValue('controller') == 'product') {
            $product = new Product($params['product']['id'], true, $this->context->language->id);
            $this->product_id_for_cache = $params['product']['id'] . "-productPage";
        } else {
            $product = new Product($params['product']['id_product'], true, $this->context->language->id);
            $this->product_id_for_cache = $params['product']['id_product'] . "-productsList";
        }


        $this->multiprice = array();
        if (Tools::GetValue('controller') == 'product' && $params['type'] == $MP_ppl) {
            $this->context->smarty->assign('isproductpage', true);
            $params['product']['price_tax_exc'] = $product->getPrice(false, $params['product']['id_product_attribute'], 6, null, false, true, Tools::getValue('quantity_wanted', 1));
            $params['product']['price'] = $product->getPrice(true, $params['product']['id_product_attribute'], 6, null, false, true, Tools::getValue('quantity_wanted', 1));
            $multiprice_id_product = $params['product']['id'];

            if (count($this->mp_curr) > 0) {
                foreach ($this->mp_curr AS $currency => $val) {
                    if ($currency != $this->context->currency->id) {
                        $currency_to = new Currency($currency);
                        $this->multiprice[$currency]['tax_exc'] = Tools::displayPrice(Tools::convertPriceFull($params['product']['price_tax_exc'], $this->context->currency, $currency_to), $currency_to);
                        $this->multiprice[$currency]['tax_inc'] = Tools::displayPrice(Tools::convertPriceFull($params['product']['price'], $this->context->currency, $currency_to), $currency_to);
                    }
                }
            }

            if (count($this->multiprice) <= 0) {
                return false;
            }

            $this->context->smarty->assign('multiprice_id_product', $multiprice_id_product);
            $this->context->smarty->assign('multiprice', $this->multiprice);
            return $this->display(__file__, 'views/templates/multiprice.tpl');
        } elseif (Tools::GetValue('controller') != 'product' && $params['type'] == $MP_plc) {
            $this->context->smarty->assign('isproductpage', false);
            $params['product']['price_tax_exc'] = $product->getPrice(false, $params['product']['id_product_attribute'], 6);
            $params['product']['price'] = $product->getPrice(true, $params['product']['id_product_attribute'], 6);
            $multiprice_id_product = $params['product']['id_product'];

            if (count($this->mp_curr) > 0) {
                foreach ($this->mp_curr AS $currency => $val) {
                    if ($currency != $this->context->currency->id) {
                        $currency_to = new Currency($currency);
                        $this->multiprice[$currency]['tax_exc'] = Tools::displayPrice(Tools::convertPriceFull($params['product']['price_tax_exc'], $this->context->currency, $currency_to), $currency_to);
                        $this->multiprice[$currency]['tax_inc'] = Tools::displayPrice(Tools::convertPriceFull($params['product']['price'], $this->context->currency, $currency_to), $currency_to);
                    }
                }
            }

            if (count($this->multiprice) <= 0) {
                return false;
            }

            $this->context->smarty->assign('multiprice_id_product', $multiprice_id_product);
            $this->context->smarty->assign('multiprice', $this->multiprice);
            return $this->display(__file__, 'views/templates/multiprice.tpl');
        }
    }

    public function getContent()
    {
        if (Tools::isSubmit('btnSubmit')) {
            $this->_postProcess();
        }
        return $this->checkModules() . $this->renderForm() . $this->checkforupdates(0, 1);
    }

    public function checkModules()
    {
        if (Tools::getValue('dynamicProduct') == 1) {
            $file = '../modules/dynamicproduct/controllers/front/calculator.php';
            if (file_exists($file)) {
                $data = file($file);
                $contents = file_get_contents($file);
                if (strpos($contents, 'displayMultiPrice') == false) {
                    if (!function_exists('replace_aline')) {
                        function replace_aline($data)
                        {
                            if (stristr($data, '$response =')) {
                                return '$calculated_prices[\'final_prices\'][\'multiprice\'] = Hook::exec(\'displayMultiPrice\', array(\'price\' => $calculated_prices[\'final_prices\'][\'price_ttc_nr\']));' . "\n" . '$response =' . "\n";
                            }
                            return $data;
                        }
                    }
                    $data = array_map('replace_aline', $data);
                    file_put_contents($file, implode('', $data));
                }
            }

            $file = '../modules/dynamicproduct/views/js/front/product.js';
            if (file_exists($file)) {
                $contents = file_get_contents($file);
                if (strpos($contents, 'multiprice_container') == false) {
                    $contents = str_replace("n.text(t.formatted_prices.price_ht_nr))", "n.text(t.formatted_prices.price_ht_nr)); $('.multiprice_container').html(t.final_prices.multiprice);", $contents);
                    file_put_contents($file, $contents);
                }
            }
        }

        $js_modified = 0;
        $php_modified = 0;
        $file = '../modules/dynamicproduct/views/js/front/product.js';
        if (file_exists($file)) {
            $contents = file_get_contents($file);
            if (strpos($contents, 'multiprice_container') !== false) {
                $js_modified = 1;
            }
        }

        $file = '../modules/dynamicproduct/controllers/front/calculator.php';
        if (file_exists($file)) {
            $data = file($file);
            $contents = file_get_contents($file);
            if (strpos($contents, 'displayMultiPrice') !== false) {
                $php_modified = 1;
            }
        }

        if (Module::getInstanceByName('dynamicproduct')->active == 1 && ($js_modified == 0 || $php_modified == 0)) {
            return "<div class='panel'><h3>" . $this->l('Important information') . "</h3>" . $this->l('Your shop uses Dynamic Product module that changes the way of how PrestaShop calculates product prices. To make  Dynamic Product module work and display and refresh various currencies on product page click on "install modifications" button below.') . '<form method="post"><input type="hidden" name="dynamicProduct" value="1"/><button class="btn btn-default">' . $this->l('Install modifications') . '</button></form></div>';
        }
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('What kind of price you want to display'),
                    'icon' => 'icon-wrench'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show tax excluded price'),
                        'name' => 'MP_TEX',
                        'desc' => $this->l('Option if enabled will display tax excluded product price'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show \'tax excluded\' label'),
                        'name' => 'MP_TEXL',
                        'desc' => $this->l('Option if enabled will display \'tax excluded\' label'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show tax included price'),
                        'name' => 'MP_TIN',
                        'desc' => $this->l('Option if enabled will display tax included product price'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show \'tax included\' label'),
                        'name' => 'MP_TINL',
                        'desc' => $this->l('Option if enabled will display \'tax included\' label'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Allow to use and display disabled currencies'),
                        'name' => 'MP_DISCURR',
                        'desc' => $this->l('If in your shop you\'ve got disabled currencies you can turn this option on - thanks to this module will display prices in currencies that are disabled.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->l('Currencies to show'),
                        'desc' => $this->l('Check currencies above. Module will show prices in these currencies') . '. ' . $this->l('If you want to show here disabled currencies - please turn on option "Allow to use disabled currencies".'),
                        'name' => 'MP_CURR',
                        'values' => array(
                            'query' => Currency::getCurrencies(false, (Configuration::get('MP_DISCURR') == true ? false : true), 'id_currency'),
                            'id' => 'id_currency',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'class' => 't',
                        'label' => $this->l('Product page position'),
                        'name' => 'MP_PP',
                        'desc' => $this->l('Select position where the module will appear in displayProductPriceBlock hook on product page.') .
                            '<div class="alert alert-info"><a class="multipricefancybox" href="https://i.imgur.com/Om9vnXL.png"> ' . $this->l('This screenshot of product page') . '</a> ' .
                            $this->l('shows a default classic theme displayProductPriceBlock positions.') . ' ' .
                            $this->l('Please note that some theme developers do not follow PrestaShop standards - in these authors\' themes you may not have these default positions, but some custom ones.') . ' ' .
                            $this->l('For these templates you may need to use "custom" position. In this case turn on option "Display available positions" and check your product page and products listings (like category page)') .
                            '</div>',
                        'options' => array(
                            'query' => $this->priceModifiersProductPage,
                            'id' => 'name',
                            'name' => 'name_show'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'class' => 't',
                        'label' => $this->l('Product page custom position'),
                        'name' => 'MP_PPC',
                        'desc' => $this->l('If you selected "custom" as product page position just') . ' ' . $this->l('type here non-default hook\'s "type" variable value'),
                    ),
                    array(
                        'type' => 'select',
                        'class' => 't',
                        'label' => $this->l('Product\'s list position'),
                        'name' => 'MP_PL',
                        'desc' => $this->l('Select position where the module will appear in displayProductPriceBlock hook on product\'s listings') .
                            '<div class="alert alert-info"><a class="multipricefancybox" href="https://i.imgur.com/4aN0aWP.png"> ' . $this->l('This screenshot of category products list') . '</a> ' .
                            $this->l('shows a default classic theme displayProductPriceBlock positions.') . ' ' .
                            $this->l('Please note that some theme developers do not follow PrestaShop standards - in these authors\' themes you may not have these default positions, but some custom ones.') . ' ' .
                            $this->l('For these templates you may need to use "custom" position. In this case turn on option "Display available positions" and check your product page and products listings (like category page)') .
                            '</div>',
                        'options' => array(
                            'query' => $this->priceModifiersProductLists,
                            'id' => 'name',
                            'name' => 'name_show'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'class' => 't',
                        'label' => $this->l('Product\'s listing custom position'),
                        'name' => 'MP_PLC',
                        'desc' => $this->l('If you selected "custom" as product\'s listing position just') . ' ' . $this->l('type here non-default hook\'s "type" variable value'),
                    ),
                    array(
                        'type' => (version_compare(PS_VERSION, '1.6') > 0) ? 'radio' : 'switch',
                        'class' => 't',
                        'label' => $this->l('Display available positions'),
                        'name' => 'MP_SHOW_TYPES',
                        'desc' => '<div class="alert alert-danger">' .
                            $this->l('Use this option temporarily, to identify position of the hook to use in your template. Option will show available positions like I show') . ' ' .
                            '<a class="multipricefancybox" href="https://i.imgur.com/Om9vnXL.png"> ' .
                            $this->l('on this screenshot') . '</a> ' .
                            '</div>',
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
                        'type' => 'select',
                        'label' => $this->l('Module visibility for selected products'),
                        'name' => 'mp_visibility_rule',
                        'desc' => $this->returnSelectedProducts(),
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('Show module for selected products')
                                ),
                                array(
                                    'id_option' => 0,
                                    'name' => $this->l('Show module for all products')
                                )
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = 'multipriceform';
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function returnSelectedProducts()
    {
        $root = Category::getRootCategory();
        $tree = new HelperTreeCategories('associated-categories-tree', $this->l('Categories'));
        $tree->setRootCategory($root->id);
        $tree->setUseCheckBox(true);
        $tree->setUseSearch(true);
        $tree->setSelectedCategories(explode(',', Configuration::get('mp_visibility_cat')));
        $category_tree = $tree->render();
        $this->context->smarty->assign('categoryTree', $category_tree);
        $this->context->smarty->assign('selectedProducts', (Configuration::get('mp_visibility_products') == "" || Configuration::get('mp_visibility_products') == false ? false : explode(",", Configuration::get('mp_visibility_products'))));
        return $this->display(__file__, 'views/templates/selectedProducts.tpl');
    }

    public function getConfigFieldsValues()
    {
        $mp_curr = array();
        foreach (explode(",", Configuration::get('MP_CURR')) as $currency) {
            $mp_curr['MP_CURR_' . $currency] = true;
        }

        return array_merge(array(
            'mp_visibility_rule' => Tools::getValue('mp_visibility_rule', Configuration::get('mp_visibility_rule')),
            'MP_TIN' => Tools::getValue('MP_TIN', Configuration::get('MP_TIN')),
            'MP_TEX' => Tools::getValue('MP_TEX', Configuration::get('MP_TEX')),
            'MP_TINL' => Tools::getValue('MP_TINL', Configuration::get('MP_TINL')),
            'MP_TEXL' => Tools::getValue('MP_TEXL', Configuration::get('MP_TEXL')),
            'MP_PP' => Tools::getValue('MP_PP', Configuration::get('MP_PP')),
            'MP_PPC' => Tools::getValue('MP_PPC', Configuration::get('MP_PPC')),
            'MP_PL' => Tools::getValue('MP_PL', Configuration::get('MP_PL')),
            'MP_PLC' => Tools::getValue('MP_PLC', Configuration::get('MP_PLC')),
            'MP_SHOW_TYPES' => Tools::getValue('MP_SHOW_TYPES', Configuration::get('MP_SHOW_TYPES')),
            'MP_DISCURR' => Tools::getValue('MP_DISCURR', Configuration::get('MP_DISCURR')),
        ), $mp_curr);
    }

    protected function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('mp_visibility_rule', Tools::getValue('mp_visibility_rule'));
            Configuration::updateValue('mp_visibility_products', implode(",", Tools::getValue('mp_products', array())));
            Configuration::updateValue('mp_visibility_cat', implode(",", Tools::getValue('categoryBox', array())));
            Configuration::updateValue('MP_TINL', Tools::getValue('MP_TINL'));
            Configuration::updateValue('MP_TEXL', Tools::getValue('MP_TEXL'));
            Configuration::updateValue('MP_TIN', Tools::getValue('MP_TIN'));
            Configuration::updateValue('MP_TEX', Tools::getValue('MP_TEX'));
            Configuration::updateValue('MP_PP', Tools::getValue('MP_PP'));
            Configuration::updateValue('MP_PPC', Tools::getValue('MP_PPC'));
            Configuration::updateValue('MP_PL', Tools::getValue('MP_PL'));
            Configuration::updateValue('MP_PLC', Tools::getValue('MP_PLC'));
            Configuration::updateValue('MP_DISCURR', Tools::getValue('MP_DISCURR'));
            Configuration::updateValue('MP_SHOW_TYPES', Tools::getValue('MP_SHOW_TYPES'));
            $mp_curr = array();
            foreach (Currency::getCurrencies(false, (Configuration::get('MP_DISCURR') == true ? false : true), 'id_currency') AS $currency) {
                if (Tools::getValue('MP_CURR_' . $currency['id_currency'], 'false') != 'false') {
                    $mp_curr[] = $currency['id_currency'];
                }

            }
            Configuration::updateValue('MP_CURR', implode(",", $mp_curr));
        }
    }

    public static function returnProductName($id)
    {
        $product = new Product($id, true, context::getContext()->language->id);
        return $product->name . ' (ref: ' . $product->reference . ')';
    }
}

class multipriceUpdate extends multiprice
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 4) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 3) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "0000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "00000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}
