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

require_once(dirname(__FILE__) . '../../../models/defpayuser.php');
require_once _PS_MODULE_DIR_ . 'defpay/defpay.php';

if (file_exists(_PS_MODULE_DIR_ . 'defpay/lib/searchTool/searchTool.php')) {
    require_once _PS_MODULE_DIR_ . 'defpay/lib/searchTool/searchTool.php';
}

class AdminDefPayUsersController extends ModuleAdminController
{
    protected $position_identifier = 'id_defpayuser';

    public function __construct()
    {
        $this->table = 'defpayuser';
        $this->className = 'defpayuser';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->bootstrap = true;
        $this->_orderBy = 'id_defpayuser';
        $this->fields_list = array(
            'id_defpayuser' => array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'orderby' => true,
                'width' => 30
            ),
            'id_customer' => array(
                'title' => $this->l('Customer'),
                'align' => 'left',
                'callback' => 'returndisplayCustomer',
                'orderby' => true,
            ),
            'id_group' => array(
                'title' => $this->l('Group'),
                'align' => 'left',
                'callback' => 'returndisplayGroup',
                'orderby' => true,
            ),
            'days' => array(
                'title' => $this->l('Days'),
                'align' => 'left',
                'orderby' => true,
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'align' => 'left',
                'type' => 'bool',
                'orderby' => true,
                'active' => 'status'
            ),
        );
        $this->searchTool = new searchTooldefpay('defpay', 'other');
    }


    public function returndisplayCustomer($entry, $row)
    {
        if ($entry == '0') {
            return '--';
        }
        $customer = new Customer($entry);
        return '(#'.$entry.') ' . $customer->email . ' - '.$customer->firstname.' '.$customer->lastname;
    }

    public function returndisplayGroup($entry, $row)
    {
        if ($entry == '0') {
            return '--';
        }
        $group = new Group($entry, Context::getContext()->language->id);
        return '(#'.$entry.') ' . $group->name;
    }

    public function renderForm()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryPlugin('autocomplete');
        $obj = $this->loadObject(true);
        if (isset($obj->id))
        {
            $this->display = 'edit';
        }
        else
        {
            $this->display = 'add';
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Grant permissions to deffered payments individually for each customer'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer'),
                    'name' => 'id_customer',
                    'multiple' => false,
                    'required' => true,
                    'desc' => $this->l('You can point exact customer OR group of customers'). '. ' .$this->l('Search for customer and just select it from suggestions list') . $this->searchTool->searchTool('customer', 'id_customer', 'replace', true, $obj->id_customer),
                    'prefix' => $this->searchTool->searchTool('customer', 'id_customer', 'replace'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Customer Group'),
                    'name' => 'id_group',
                    'required' => true,
                    'lang' => false,
                    'options' => array(
                        'query' => array_merge(array(array('id'=> 0, 'name' => $this->l('-- select --'))), Group::getGroups($this->context->language->id)), //array(array('name'=>$this->l('customer'), 'id_group' => 1))
                        'id' => 'id_group',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('You can point exact customer OR group of customers').'. '. $this->l('Here you can select group of customers'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Nb of days'),
                    'name' => 'days',
                    'required' => true,
                    'desc' => $this->l('Customer will be obligued to pay for order after this number of days'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => true,
                    'values' => array(
                        array(
                            'id' => 'active_def_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_def_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),

            'submit' => array(
                'title' => $this->l('Save')
            )
        );
        return parent::renderForm() . $this->searchTool->initTool();
    }
}