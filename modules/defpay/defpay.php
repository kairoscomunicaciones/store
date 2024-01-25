<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2023 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/models/defpayuser.php');

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use PrestaShop\PrestaShop\Adapter\Order\OrderPresenter;

class defpay extends PaymentModule
{
    public function __construct()
    {
        $this->_html = '';
        $this->name = 'defpay';
        $this->module_key = '680cd01f97ebd84b44bb98a1e54d758f';
        $this->tab = 'payments_gateways';
        $this->version = '1.3.1';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = 'https://mypresta.eu/modules/payment-process/deffered-payment.html';
        $this->need_instance = 1;
        $this->controllers = array('validation');
        $this->bootstrap = true;
        $this->currencies = false;
        $this->cashfee = $this->l("Deffered payments");
        parent::__construct();
        $this->secure_key = Tools::encrypt($this->name);
        $this->checkforupdates(0, 0);

        $this->displayName = $this->l('Deffered payments');
        $this->description = $this->l('This module creates a "deffered payment" payment method for selected customers');

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

    public function getCustomerGroups()
    {
        $customer_groups = array();
        if (isset($this->context->cart->id_customer)) {
            if ($this->context->cart->id_customer == 0) {
                $customer_groups[1] = 1;
            } else {
                foreach (Customer::getGroupsStatic($this->context->cart->id_customer) as $group) {
                    $customer_groups[$group] = 1;
                }
            }
        } elseif ($this->context->customer->is_guest == 1) {
            $customer_groups[1] = 2;
        } else {
            $customer_groups[1] = 1;
        }
        if (count($customer_groups) > 0) {
            return $customer_groups;
        } else {
            return false;
        }
    }

    public function inconsistency($ret)
    {
        $this->maybeUpdateDatabase('defpayuser', 'id_group', "INT", 0, "NOT NULL");
        return true;
    }

    private function InDelMenu($what, $controller, $name = null, $parent = null)
    {
        if ($what == 'install') {
            $tab = new Tab();
            $tab->class_name = $controller;
            $tab->id_parent = Tab::getIdFromClassName($parent);
            $tab->module = $this->name;
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $tab->name[$lang['id_lang']] = $name;
            }
            $tab->save();
            return true;
        } elseif ($what == 'uninstall') {
            $tab = new Tab(Tab::getIdFromClassName($controller));
            $tab->delete();
            return true;
        }
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
            ' . ($this->psversion() == 6 || $this->psversion() == 7 || $this->psversion(0) >= 8 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
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
                        $actual_version = defpayUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (defpayUpdate::version($this->version) < defpayUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = defpayUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (defpayUpdate::version($this->version) < defpayUpdate::version(defpayUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function hookActionAdminControllerSetMedia($params)
    {

        // FOR UPDATES ONLY
    }

    public function install()
    {
        if (!parent::install() OR
            !$this->InDelMenu('install', 'AdminDefPayUsers', $this->l('Deffered payments users'), 'AdminParentPayment') OR
            !$this->createOrderStates() OR
            !$this->installdb() OR
            !$this->registerhook('displayOrderDetail') OR
            !$this->registerHook('ActionAdminControllerSetMedia') OR
            !$this->alterTable('add') OR
            !$this->registerhook('displayAdminOrder') OR
            !$this->registerHook('displayHeader') OR
            !$this->registerHook('paymentOptions') OR
            !$this->registerHook('paymentReturn')) {
            return false;
        }
        return true;
    }

    private function installdb()
    {
        $prefix = _DB_PREFIX_;
        $engine = _MYSQL_ENGINE_;
        $statements = array();
        $statements[] = "CREATE TABLE IF NOT EXISTS `${prefix}defpayuser` (" .
            '`id_defpayuser` int(10) NOT NULL AUTO_INCREMENT, ' .
            '`id_customer` int(10) NOT NULL, ' .
            '`days` int(10) NOT NULL, ' .
            '`order_from` decimal(13,6) NOT NULL DEFAULT 0, ' .
            '`order_to` decimal(13,6) NOT NULL DEFAULT 0, ' .
            '`active` int(1) NOT NULL DEFAULT 1, ' .
            'PRIMARY KEY (`id_defpayuser`)' . ")";
        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                return false;
            }
        }
        $this->inconsistency(false);
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() OR !$this->InDelMenu('uninstall', 'AdminDefPayUsers', $this->l('Deffered payments'), 'AdminParentPayment')) {
            return false;
        }
        return true;
    }

    public function createOrderStates()
    {
        if (!Configuration::get('DEFPAY_OS_PLACED')) {
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = 'Deffered payment';
            }
            $order_state->send_email = false;
            $order_state->color = '#DDEEFF';
            $order_state->module_name = 'defpay';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = false;
            if ($order_state->add()) {
                $source = dirname(__FILE__) . '/../../img/os/9.gif';
                $destination = dirname(__FILE__) . '/../../img/os/' . (int)$order_state->id . '.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('PAYBYPHONE_OS_CALL', (int)$order_state->id);
        }
        return true;
    }

    public function alterTable($method)
    {
        if ($method == "add") {
            $this->maybeUpdateDatabase('orders', 'defpayday', "FLOAT", 0, "NOT NULL");
            $this->maybeUpdateDatabase('orders', 'defpaydate', "DATETIME", '', "NULL");
            $this->maybeUpdateDatabase('orders', 'defpayid', "FLOAT", 0, "NOT NULL");
            $this->maybeUpdateDatabase('orders', 'defpayrembef', "INT", 0, "NOT NULL");
            $this->maybeUpdateDatabase('orders', 'defpayremaft', "INT", 0, "NOT NULL");
        }
        return true;
    }

    public function hookdisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/defpay.css');
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        if (Configuration::get('DEFPAY_MOD_MAXCART') == 1) {
            $cart_total = $this->context->cart->getSummaryDetails();
            $default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
            $actual_currency = new Currency($this->context->currency->id);
            if ($cart_total['total_products_wt'] > Tools::convertPrice(Configuration::get('DEFPAY_MOD_MAXCART_V'), $actual_currency, $default_currency)) {
                return;
            }
        }

        if (Configuration::get('DEFPAY_MOD_MINCART') == 1) {
            $cart_total = $this->context->cart->getSummaryDetails();
            $default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
            $actual_currency = new Currency($this->context->currency->id);
            if ($cart_total['total_products_wt'] < Tools::convertPrice(Configuration::get('DEFPAY_MOD_MINCART_V'), $actual_currency, $default_currency)) {
                return;
            }
        }

        $defpayuser = defpayuser::getByCustomer(Context::getContext()->customer->id, $this->getCustomerGroups());
        if (Context::getContext()->customer->id != 0) {
            if ($defpayuser == false) {
                return;
            }
        } else {
            return;
        }

        $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
        $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime(date('Y-m-d'))));
        $this->smarty->assign(array(
            'defpayuser_days' => $days,
            'defpayuser_date' => $defpayuser_date,
            'this_path' => $this->_path,
            'this_path_cod' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/'
        ));
        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)->setCallToActionText($this->l('Deffered payment'))->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true))->setAdditionalInformation($this->fetch('module:defpay/views/templates/hook/payment.tpl'));

        return [$newOption];
    }

    public function hookPaymentReturn($params)
    {
        if (isset($params['order']->reference) && !empty($params['order']->reference)) {
            $this->order_presenter = new OrderPresenter();
            $order = new Order((int)($params['order']->id));
            $defpayuser = defpayuser::getByCustomer(Context::getContext()->customer->id);
            $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
            $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime(date('Y-m-d'))));
            $presentedOrder = $this->order_presenter->present($order);
            $this->context->smarty->assign('order_reference', $params['order']->reference);
            $this->context->smarty->assign('order', $presentedOrder);
            $this->context->smarty->assign('order_id', $order->id);
            $this->context->smarty->assign('defpayuser_message', Configuration::get('DEFPAY_MESSAGE', Context::getContext()->language->id));
            $this->context->smarty->assign('defpayuser_days', $days);
            $this->context->smarty->assign('defpayuser_date', Tools::displayDate($defpayuser_date));
        }
        if (!$this->active) {
            return;
        }
        return $this->display(__FILE__, 'confirmation.tpl');
    }

    public static function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = explode('.', $version);
        if ($part == 0) {
            return $exp[0];
        }
        if ($part == 1) {
            if ($exp[0] >= 8) {
                return 7;
            }
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
    }

    public function renderForm()
    {
        if ($this->psversion() == 7 || $this->psversion(0) >= 8) {
            $fields_form = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('details'),
                        'icon' => 'icon-cubes'
                    ),
                    'input' => array(
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Disable \'deffered payment\' method when cart is worth less than'),
                            'name' => 'DEFPAY_MOD_MINCART',
                            'desc' => $this->l('This option allows to disable this payment method for carts that are worth less than defined value below'),
                            'values' => array(
                                array(
                                    'id' => 'a_active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled')
                                ),
                                array(
                                    'id' => 'a_active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled')
                                )
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'prefix' => self::getDefaultCurrencySymbol(Configuration::get('PS_CURRENCY_DEFAULT')),
                            'label' => $this->l('Value'),
                            'name' => 'DEFPAY_MOD_MINCART_V',
                            'desc' => $this->l('Define min value of total cart, use dot symbol: . as a decimal separator') . ', ' . $this->l('Default shop currency.'),
                        ),

                        array(
                            'type' => 'switch',
                            'label' => $this->l('Disable \'deffered payment\' method when cart is worth more than'),
                            'name' => 'DEFPAY_MOD_MAXCART',
                            'desc' => $this->l('This option allows to disable this payment method for cart that will be worth more than defined value below'),
                            'values' => array(
                                array(
                                    'id' => 'a_active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled')
                                ),
                                array(
                                    'id' => 'a_active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled')
                                )
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'prefix' => self::getDefaultCurrencySymbol(Configuration::get('PS_CURRENCY_DEFAULT')),
                            'label' => $this->l('Value'),
                            'name' => 'DEFPAY_MOD_MAXCART_V',
                            'desc' => $this->l('Define max value of total cart, use dot symbol: . as a decimal separator') . ', ' . $this->l('Default shop currency.') . '<br><br><br><hr>'
                        ),
                        array(
                            'type' => 'select',
                            'label' => $this->l('Order status after purchase with deffered payment'),
                            'name' => 'DEFPAY_OS_PLACED',
                            'desc' => $this->l('Module will set this order status for purchases placed with deffered payment'),
                            'options' => array(
                                'query' => OrderState::getOrderStates(Context::getContext()->language->id),
                                'id' => 'id_order_state',
                                'name' => 'name'
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Defferal payment days'),
                            'name' => 'DEFPAY_DAYS',
                            'lang' => false,
                            'desc' => $this->l('Default value of defferal payment in days, this value will be used for customers allowed to use defferal payment method as long as you will not setup it individually for customer.') . ' <a href="' . $this->context->link->getAdminLink('AdminDefpayUsers', true) . '">' . $this->l('You can do it here') . '</a>',
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Send email after purchase'),
                            'name' => 'DEFPAY_MAIL_CONF',
                            'desc' => $this->l('Activate this option if you want to send an email with order confirmation to customer'),
                            'values' => array(
                                array(
                                    'id' => 'a_active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled')
                                ),
                                array(
                                    'id' => 'a_active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled')
                                )
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Title of email after order'),
                            'name' => 'DEFPAY_TITLE_MAIL',
                            'lang' => true,
                            'desc' => $this->l('This will be the title of email that module will send to customer after order'),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Send email with reminder'),
                            'name' => 'DEFPAY_MAIL_REMIND',
                            'desc' => $this->l('Activate this option if you want to send an email with payment reminder 3 days before deffered payment breakpoint date') . '<br/>' . $this->showCron(),
                            'values' => array(
                                array(
                                    'id' => 'a_active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled')
                                ),
                                array(
                                    'id' => 'a_active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled')
                                )
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Title of email with reminder'),
                            'name' => 'DEFPAY_TITLE_REMIND',
                            'lang' => true,
                            'desc' => $this->l('This will be the title of email that module will send to customer with reminder'),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Send email with reminder'),
                            'name' => 'DEFPAY_MAIL_RAFTER',
                            'desc' => $this->l('Activate this option if you want to send an email with payment reminder 3 days after deffered payment breakpoint date') . '<br/>' . $this->showCron(),
                            'values' => array(
                                array(
                                    'id' => 'a_active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled')
                                ),
                                array(
                                    'id' => 'a_active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled')
                                )
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Title of email with reminder'),
                            'name' => 'DEFPAY_TITLE_RAFTER',
                            'lang' => true,
                            'desc' => $this->l('This will be the title of email that module will send to customer with reminder'),
                        ),
                        array(
                            'type' => 'textarea',
                            'label' => $this->l('Detailed message'),
                            'name' => 'DEFPAY_MESSAGE',
                            'lang' => true,
                            'autoload_rte' => true,
                            'desc' => $this->l('This will be the message that module will send in email to customer after order. You can type here how customer should pay for the order (for example - add here your bank account number and other important informations)'),
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            );
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->id = 'defpayconfiguration';
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form)) . $this->checkforupdates(0, 1);
    }

    public function getDefaultCurrencySymbol($currency)
    {
        $c = new Currency($currency);
        return $c->sign;
    }

    public function showCron()
    {
        $croonurl = Tools::getProtocol(Tools::usingSecureMode()) . $_SERVER['HTTP_HOST'] . $this->getPathUri() . 'cronjob.php?key=' . $this->secure_key;
        return '<div class="alert alert-info">
                ' . $this->l('If you will turn this option on - you need to run cron task for url below. Only then reminders will be delivered.') . '<br />
                ' . $croonurl . '
                </div>';
    }

    public function getContent()
    {
        $this->context->controller->informations[] = $this->l('Do not forget to configure users / groups with privileges to deffered payment. You can do it here: ') . '<a href="'.$this->context->link->getAdminLink('AdminDefPayUsers').'">'.$this->l('Grant permissions').'</a>';
        $this->searchTool = new searchTooldefpay('defpay');

        // UPDATE 1.9.1: auto-create settings of css elemnents, to avoid issues
        if (Configuration::get('DEFPAY_DAYS') == false) {
            Configuration::updateValue('DEFPAY_DAYS', '14');
            $this->context->controller->warnings[] = $this->l('Setup default value for days of defferal payment. You can setup this value individually for each customer.');
        }

        if (Tools::isSubmit('btnSubmit')) {
            $this->_postProcess();
        }
        $this->_html .= $this->renderForm();
        return $this->_html;
    }

    public function getConfigFieldsValues()
    {
        $title = array();
        $title_remind = array();
        $title_rafter = array();
        $message = array();
        foreach (Language::getLanguages(true) AS $value) {
            $title[$value['id_lang']] = Configuration::get('DEFPAY_TITLE_MAIL', $value['id_lang']);
            $title_remind[$value['id_lang']] = Configuration::get('DEFPAY_TITLE_REMIND', $value['id_lang']);
            $title_rafter[$value['id_lang']] = Configuration::get('DEFPAY_TITLE_RAFTER', $value['id_lang']);
            $message[$value['id_lang']] = Configuration::get('DEFPAY_MESSAGE', $value['id_lang']);
        }

        return array(
            'DEFPAY_OS_PLACED' => Tools::getValue('DEFPAY_OS_PLACED', (Configuration::get('DEFPAY_OS_PLACED') != false ? Configuration::get('DEFPAY_OS_PLACED') : Configuration::get('DEFPAY_OS_PLACED'))),
            'DEFPAY_MOD_MAXCART_V' => Tools::getValue('DEFPAY_MOD_MAXCART_V', Configuration::get('DEFPAY_MOD_MAXCART_V')),
            'DEFPAY_MOD_MAXCART' => Tools::getValue('DEFPAY_MOD_MAXCART', Configuration::get('DEFPAY_MOD_MAXCART')),
            'DEFPAY_MOD_MINCART_V' => Tools::getValue('DEFPAY_MOD_MINCART_V', Configuration::get('DEFPAY_MOD_MINCART_V')),
            'DEFPAY_MOD_MINCART' => Tools::getValue('DEFPAY_MOD_MINCART', Configuration::get('DEFPAY_MOD_MINCART')),
            'DEFPAY_DAYS' => Tools::getValue('DEFPAY_DAYS', Configuration::get('DEFPAY_DAYS')),
            'DEFPAY_MAIL_RAFTER' => Tools::getValue('DEFPAY_MAIL_RAFTER', Configuration::get('DEFPAY_MAIL_RAFTER')),
            'DEFPAY_MAIL_REMIND' => Tools::getValue('DEFPAY_MAIL_REMIND', Configuration::get('DEFPAY_MAIL_REMIND')),
            'DEFPAY_MAIL_CONF' => Tools::getValue('DEFPAY_MAIL_CONF', Configuration::get('DEFPAY_MAIL_CONF')),
            'DEFPAY_MESSAGE' => $message,
            'DEFPAY_TITLE_MAIL' => $title,
            'DEFPAY_TITLE_REMIND' => $title_remind,
            'DEFPAY_TITLE_RAFTER' => $title_rafter,
        );
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('DEFPAY_MOD_MINCART', Tools::getValue('DEFPAY_MOD_MINCART'));
            Configuration::updateValue('DEFPAY_MOD_MINCART_V', Tools::getValue('DEFPAY_MOD_MINCART_V'));
            Configuration::updateValue('DEFPAY_MOD_MAXCART', Tools::getValue('DEFPAY_MOD_MAXCART'));
            Configuration::updateValue('DEFPAY_MOD_MAXCART_V', Tools::getValue('DEFPAY_MOD_MAXCART_V'));
            Configuration::updateValue('DEFPAY_OS_PLACED', Tools::getValue('DEFPAY_OS_PLACED'));
            Configuration::updateValue('DEFPAY_DAYS', Tools::getValue('DEFPAY_DAYS'));
            Configuration::updateValue('DEFPAY_MAIL_RAFTER', Tools::getValue('DEFPAY_MAIL_RAFTER'));
            Configuration::updateValue('DEFPAY_MAIL_REMIND', Tools::getValue('DEFPAY_MAIL_REMIND'));
            Configuration::updateValue('DEFPAY_MAIL_CONF', Tools::getValue('DEFPAY_MAIL_CONF'));
            Configuration::updateValue('DEFPAY_TITLE_REMIND', Tools::getValue('DEFPAY_TITLE_REMIND'));
            Configuration::updateValue('DEFPAY_TITLE_RAFTER', Tools::getValue('DEFPAY_TITLE_RAFTER'));

            $title = array();
            $title_remind = array();
            $title_rafter = array();
            $message = array();
            foreach (Language::getLanguages(true) AS $value) {
                $title[$value['id_lang']] = Tools::getValue('DEFPAY_TITLE_MAIL_' . $value['id_lang']);
                $title_rafter[$value['id_lang']] = Tools::getValue('DEFPAY_TITLE_RAFTER_' . $value['id_lang']);
                $title_remind[$value['id_lang']] = Tools::getValue('DEFPAY_TITLE_REMIND_' . $value['id_lang']);
                $message[$value['id_lang']] = Tools::getValue('DEFPAY_MESSAGE_' . $value['id_lang']);
            }
            Configuration::updateValue('DEFPAY_TITLE_MAIL', $title);
            Configuration::updateValue('DEFPAY_TITLE_REMIND', $title_remind);
            Configuration::updateValue('DEFPAY_TITLE_RAFTER', $title_rafter);
            Configuration::updateValue('DEFPAY_MESSAGE', $message, true);
        }
        $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
    }

    public function hookdisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);
        $defpayuser = defpayuser::getByCustomer($order->id_customer);
        if (isset($defpayuser[0]['days'])) {
            $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
            $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime(date('Y-m-d'))));
            $this->context->smarty->assign('order_reference', $order->reference);
            $this->context->smarty->assign('order_id', $order->id);
            $this->context->smarty->assign('defpayuser_message', Configuration::get('DEFPAY_MESSAGE', $order->id_lang));
            $this->context->smarty->assign('defpayuser_days', $days);
            $this->context->smarty->assign('defpayuser_date', Tools::displayDate($defpayuser_date));

            if (version_compare(_PS_VERSION_, '1.7.7') >= 0) {
                return $this->display(__FILE__, 'displayAdminOrder17.tpl');
            } else {
                return $this->display(__FILE__, 'displayAdminOrder16.tpl');
            }
        }
    }

    public function hookdisplayOrderDetail($params)
    {
    }

    public function cronJob()
    {
        If (Configuration::get('DEFPAY_MAIL_REMIND') == true) {
            $orders = $this->getOrdersToRemind();
            if (is_array($orders)) {
                if (count($orders) > 0) {
                    foreach ($orders as $ord) {
                        $message = Configuration::get('DEFPAY_MESSAGE', $ord['id_lang']);
                        $order = new Order($ord['id_order']);
                        $currency_order = new Currency($order->id_currency, $ord['id_currency']);
                        $defpayuser = defpayuser::getByCustomer($ord['id_customer']);
                        $customer = new Customer($ord['id_customer']);
                        $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
                        $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime($order->date_add)));
                        $templatevars['{customer_firstname}'] = $customer->firstname;
                        $templatevars['{customer_lastname}'] = $customer->lastname;
                        $templatevars['{order_id}'] = $order->id;
                        $templatevars['{order_reference}'] = $order->reference;
                        $templatevars['{defpay_deadline}'] = Tools::displayDate($defpayuser_date);
                        $templatevars['{defpay_days}'] = $days;
                        $templatevars['{defpay_message}'] = $message;
                        $templatevars['{order_total}'] = Tools::displayPrice($order->total_paid, $currency_order->id);
                        if (Mail::Send($ord['id_lang'], 'defpay-confirmation', Configuration::get('DEFPAY_TITLE_REMIND', Context::getContext()->language->id), $templatevars, (string)($customer->email), null, (string)Configuration::get('PS_SHOP_EMAIL', null, null, $ord['id_shop']), (string)Configuration::get('PS_SHOP_NAME', null, null, $ord['id_shop']), null, null, dirname(__file__) . '/mails/', false, $ord['id_shop'])) {
                            Db::getInstance()->update('orders', array('defpayrembef' => 1), 'id_order = ' . $ord['id_order']);
                        }
                    }
                }
            }
        }

        If (Configuration::get('DEFPAY_MAIL_RAFTER') == true) {
            $orders = $this->getOrdersToRemindAfter();
            if (is_array($orders)) {
                if (count($orders) > 0) {
                    foreach ($orders as $ord) {
                        $message = Configuration::get('DEFPAY_MESSAGE', $ord['id_lang']);
                        $order = new Order($ord['id_order']);
                        $currency_order = new Currency($order->id_currency, $ord['id_currency']);
                        $defpayuser = defpayuser::getByCustomer($ord['id_customer']);
                        $customer = new Customer($ord['id_customer']);
                        $days = (isset($defpayuser[0]['days']) ? ($defpayuser[0]['days'] > 0 ? $defpayuser[0]['days'] : Configuration::get('DEFPAY_DAYS')) : Configuration::get('DEFPAY_DAYS'));
                        $defpayuser_date = date("Y-m-d", strtotime("+" . $days . " days", strtotime($order->date_add)));
                        $templatevars['{customer_firstname}'] = $customer->firstname;
                        $templatevars['{customer_lastname}'] = $customer->lastname;
                        $templatevars['{order_id}'] = $order->id;
                        $templatevars['{order_reference}'] = $order->reference;
                        $templatevars['{defpay_deadline}'] = Tools::displayDate($defpayuser_date);
                        $templatevars['{defpay_days}'] = $days;
                        $templatevars['{defpay_message}'] = $message;
                        $templatevars['{order_total}'] = Tools::displayPrice($order->total_paid, $currency_order->id);
                        if (Mail::Send($ord['id_lang'], 'defpay-confirmation', Configuration::get('DEFPAY_TITLE_RAFTER', Context::getContext()->language->id), $templatevars, (string)($customer->email), null, (string)Configuration::get('PS_SHOP_EMAIL', null, null, $ord['id_shop']), (string)Configuration::get('PS_SHOP_NAME', null, null, $ord['id_shop']), null, null, dirname(__file__) . '/mails/', false, $ord['id_shop'])) {
                            Db::getInstance()->update('orders', array('defpayremaft' => 1), 'id_order = ' . $ord['id_order']);
                        }
                    }
                }
            }
        }
    }

    public function getOrdersToRemind()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'orders' . '` WHERE current_state = ' . Configuration::get('DEFPAY_OS_PLACED') . ' AND defpayid > 0 AND defpayrembef = 0 AND defpaydate BETWEEN "' . date("Y-m-d h:i:s", strtotime("-3 days", strtotime(date("Y-m-d")))) . '" AND "' . date("Y-m-d h:i:s", strtotime("-0 days", strtotime(date("Y-m-d")))) . '"');
    }

    public function getOrdersToRemindAfter()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'orders' . '` WHERE current_state = ' . Configuration::get('DEFPAY_OS_PLACED') . ' AND defpayid > 0 AND defpayremaft = 0 AND defpaydate BETWEEN "' . date("Y-m-d h:i:s", strtotime("+3 days", strtotime(date("Y-m-d")))) . '" AND "' . date("Y-m-d h:i:s", strtotime("+99999 days", strtotime(date("Y-m-d")))) . '"');
    }

}

class defpayUpdate extends defpay
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
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

if (file_exists(_PS_MODULE_DIR_ . 'defpay/lib/searchTool/searchTool.php')) {
    require_once _PS_MODULE_DIR_ . 'defpay/lib/searchTool/searchTool.php';
}
