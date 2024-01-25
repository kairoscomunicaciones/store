<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminCustomFieldsHeadingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'CustomFieldsHeadings';
        $this->table = 'custom_field_headings';
        $this->deleted = false;
        $this->identifier = 'id_custom_field_headings';
        $this->lang = true ;
        $this->bootstrap = true ;
        $this->explicitSelect = true ;
        $this->context = Context::getContext();
        parent::__construct();
        $this->fields_list = array(
            'id_custom_field_headings' => array('title' => $this->l('ID'),
                'align' => 'center', 'class' => 'fixed-width-xs'
            ),
            'title' => array('title' => $this->l('Title'), 'align' => 'center', 'lang' => true)
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
    }
    
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    
    public function renderForm()
    {
        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Block headings for Custom Fields'),
                'icon' => 'icon-link'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Block Title'),
                    'name' => 'title',
                    'lang' => true,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' <>;=#{}',
                    'desc' => $this->l('You can use this heading in creating a custom fields block.'),
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
        return parent::renderForm();
    }
}
