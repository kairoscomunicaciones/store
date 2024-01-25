<?php
/**
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
*  @author    FMM Modules
*  @copyright 2022 FMM Modules All right reserved
*  @license   FMM Modules
*/

class AdminFieldsController extends AdminController
{
    public function __construct()
    {
        $this->table = 'custom_field';
        $this->className = 'FieldsModel';
        $this->identifier = 'id_custom_field';
        $this->lang = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bootstrap = true;
        $this->multishop_context = Shop::CONTEXT_ALL;

        parent::__construct();
        $this->context = Context::getContext();

        $this->position_identifier = 'position';
        $this->_orderBy = 'position';

        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

        $this->fields_list = array(
            'id_custom_field' => array(
                'title'  => $this->l('ID'),
                'width' => 25,
                'search'    => false,
            ),
            'field_name' => array(
                'title' => $this->l('Field Name'),
                'width' => 'auto',
                'color' => 'red',
                'search'    => false,
            ),
            'field_type' => array(
                'title' => $this->l('Field Type'),
                'width' => 'auto',
                'search'    => false,
            ),
            'field_placement' => array(
                'title' => $this->l('Checkout Step'),
                'width' => 'auto',
                'search'    => false,
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'editable' => array(
                'title' => $this->l('Editable'),
                'active' => 'editable',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'value_required' => array(
                'title' => $this->l('Required'),
                'active' => 'value_required',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'value_required' => array(
                'title' => $this->l('Required'),
                'active' => 'value_required',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'show_customer' => array(
                'title' => $this->l('Show to Customer'),
                'active' => 'show_customer',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'show_admin' => array(
                'title' => $this->l('Show to Admin'),
                'active' => 'show_admin',
                'type' => 'bool',
                'align' => 'center',
                'orderby' => false,
                'search'    => false,
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
                'search'    => false,
            )
        );
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['save']);
        unset($this->toolbar_btn['cancel']);
    }

    public function initPageHeaderToolbar()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
            if (empty($this->display)) {
                $this->page_header_toolbar_btn['new_customer'] = array(
                    'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                    'desc' => $this->l('Add new Field'),
                    'icon' => 'process-icon-new'
                );
            }
            parent::initPageHeaderToolbar();
        }
    }

    public function renderList()
    {
        // Adds an Edit button for each result
        $this->addRowAction('edit');
        // Adds a Delete button for each result
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function renderForm()
    {
        $id_custom_field = (int)Tools::getValue('id_custom_field');
        $obj_model = new FieldsModel($id_custom_field);
        $back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex.'&token='.$this->token;
        }

        $this->context->smarty->assign('mode', $this->display);
        $custom_field_types = $this->getCustomFieldTypes();

        $current_object = $this->loadObject(true);
        $shops = '';
        $selected_shops = '';
        if (Shop::isFeatureActive()) {
            $shops = $this->renderShops();
            $selected_shops = ($current_object && $current_object->assoc_shops)? $current_object->assoc_shops : '';
        }
        $this->context->smarty->assign(array('shops' => $shops, 'selected_shops' => $selected_shops));

        $fieldinfo = _PS_MODULE_DIR_.'customfields/views/templates/admin/custom_fields/fieldinfo.tpl';

        $languages = Language::getLanguages();
        $jquery_options = '';
        if ($id_custom_field != '') {
            $list_options = $obj_model->getCustomFieldValues($id_custom_field);
            foreach ($list_options as $list_option) {
                $jquery_options .= 'newOption(\''.$list_option['id_custom_field'].'\',\''.$list_option['field_value'].'\');';
            }
        }

        $products = array();
        $field_values = array();
        $customer_groups = Group::getGroups($this->context->language->id);
        $categories = Category::getSimpleCategories($this->context->language->id);
        if (isset($current_object) && isset($current_object->products) && $current_object->products) {
            $products = explode(',', $current_object->products);
        }
        $headings_collection = $obj_model->getHeadingsCollection($this->context->language->id);
        $empty_entry = array(100000 => array('id_custom_field_headings' => 0, 'title' => $this->l('--')));
        $headings_collection = $empty_entry + $headings_collection;
        $fields_collection = $obj_model->getCustomFieldsCollection();
        if ($id_custom_field > 0 && !empty($fields_collection)) {
            foreach ($fields_collection as &$field) {
                $field['dep_check'] = $obj_model->getDependantCheckedVal($id_custom_field);
            }
        }
        if ($id_custom_field > 0) {
            if ('boolean' == FieldsModel::getFieldType($current_object->dependant_field)) {
                $field_values = $this->getBooleanEquivalentVal($current_object->dependant_field);
            } else {
                $field_values = $obj_model->getCustomFieldsValues($current_object->dependant_field);
            }
            if (!empty($field_values)) {
                foreach ($field_values as &$field_val) {
                    $field_val['check'] = (int)$obj_model->getDependantCheckedValue($id_custom_field);
                }
            }
            foreach ($customer_groups as &$group) {
                $group['checked'] = (int)$obj_model->needleCheck('custom_field_group', 'id_group', $group['id_group'], $id_custom_field);
            }
            if (!empty($categories)) {
                foreach ($categories as &$category) {
                    $category['checked'] = (int)$obj_model->needleCheck('custom_field_categories', 'id_category', $category['id_category'], $id_custom_field);
                }
            }
        }

        $this->context->smarty->assign(array(
            'id_custom_field'   => $id_custom_field,
            'show_toolbar'      => true,
            'products'          => $products,
            'toolbar_btn'       => $this->toolbar_btn,
            'toolbar_scroll'    => $this->toolbar_scroll,
            'title'             => array($this->l('Custom Fields')),
            'defaultCurrency'   => Configuration::get('PS_CURRENCY_DEFAULT'),
            'id_lang_default'   => Configuration::get('PS_LANG_DEFAULT'),
            'languages'         => $languages,
            'currentToken'      => $this->token,
            'currentIndex'      => self::$currentIndex,
            'currentObject'     => $current_object,
            'currentTab'        => $this,
            'customFieldTypes'  => $custom_field_types,
            'fieldinfo'         => $fieldinfo,
            'jqueryOptions'     => $jquery_options,
            'version'           => _PS_VERSION_,
            'id_lang'           => $this->context->language->id,
            'this_module_path'  => __PS_BASE_URI__.'modules/customfields/views/',
            'action_url'        => self::$currentIndex.'&token='.$this->token,
            'fields_collection' => $fields_collection,
            'field_values'      => $field_values,
            'customer_groups'   => $customer_groups,
            'categories'        => $categories,
            'headings_collection' => $headings_collection
        ));

        parent::renderForm();
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
            return $this->context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/custom_fields/helpers/form/form_17.tpl');
        } else {
            return $this->context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/custom_fields/helpers/form/form.tpl');
        }
    }

    public function postProcess()
    {
        //parent::postProcess();
        $id_custom_field = (int)Tools::getValue('id_custom_field');
        $options = Tools::getValue('options');
        $groups = Tools::getValue('groups');
        $category = Tools::getValue('category');
        if (Shop::isFeatureActive()) {
            $assoc_shops = Tools::getValue('checkBoxShopAsso_'.$this->table);
            if (isset($assoc_shops) && is_array($assoc_shops)) {
                $assoc_shops = implode(',', $assoc_shops);
            }
            $_POST['assoc_shops'] = $assoc_shops;
        } else {
            $_POST['assoc_shops'] = (int)$this->context->shop->id;
        }

        // update boolean vallues
        if (Tools::isSubmit('editable'.$this->table)) {
            $id_custom_field = (int)Tools::getValue('id_custom_field');
            if (!FieldsModel::updateStatus('editable', $id_custom_field)) {
                $this->errors[] = $this->l('Editable permissions update error.');
            } else {
                $this->confirmations[] = $this->l('Editable permissions updated successfully');
            }
        } elseif (Tools::isSubmit('value_required'.$this->table)) {
            $id_custom_field = (int)Tools::getValue('id_custom_field');
            if (!FieldsModel::updateStatus('value_required', $id_custom_field)) {
                $this->errors[] = $this->l('Requried status update error.');
            } else {
                $this->confirmations[] = $this->l('Required status updated successfully');
            }
        } elseif (Tools::isSubmit('show_customer'.$this->table)) {
            $id_custom_field = (int)Tools::getValue('id_custom_field');
            if (!FieldsModel::updateStatus('show_customer', $id_custom_field)) {
                $this->errors[] = $this->l('Update error.');
            } else {
                $this->confirmations[] = $this->l('updated successfully.');
            }
        } elseif (Tools::isSubmit('show_admin'.$this->table)) {
            $id_custom_field = (int)Tools::getValue('id_custom_field');
            if (!FieldsModel::updateStatus('show_admin', $id_custom_field)) {
                $this->errors[] = $this->l('Update error.');
            } else {
                $this->confirmations[] = $this->l('Updated successfully.');
            }
        }
        parent::postProcess();
        $id_custom_field = (int)Tools::getValue('id_custom_field');
        $obj = new FieldsModel($id_custom_field);
        $opt_data = $obj->getCustomFieldsValues($id_custom_field);

        if (!empty($options)) {
        //dump($opt_data, $options);exit;
            //Delete all Previous Data
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'custom_field_values`
                WHERE `id_custom_field` = '.(int)$id_custom_field);

            //Insert new data
            foreach ($options as $option) {
                Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'custom_field_values` (id_custom_field, field_value)
                    VALUES ('.(int)$id_custom_field.', "'.pSQL($option).'")
                    ON DUPLICATE KEY UPDATE id_custom_field='.pSQL((int)$id_custom_field).', field_value="'.pSQL($option).'"');
            }

            if (!empty($opt_data)) {
                foreach ($opt_data as $key => $data) {
                    Db::getInstance()->update(
                        'custom_field_values',
                        [
                            'field_value_id' => (int) $data['field_value_id']
                        ],
                        'field_value = "' . pSQL($options[$key]) . '"'
                    );
                }
            }
        }

        if (!empty($groups)) {
            $obj->dumpCurrentData($id_custom_field, 'custom_field_group');
            $obj->populateTable('custom_field_group', 'id_group', $id_custom_field, $groups);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $obj->dumpCurrentData($id_custom_field, 'custom_field_categories');
            if (!empty($category)) {
                $obj->populateTable('custom_field_categories', 'id_category', $id_custom_field, $category);
            }
        }
    }

    public function init()
    {
        parent::init();
        $ajax = (int)Tools::getValue('ajax');
        $dep_id = (int)Tools::getValue('id_dep');
        if (Tools::isSubmit('downloadFile')) {
            $link = base64_decode(Tools::getValue('l'));
            FieldsModel::actionDownload($link);
        }
        if (Tools::getValue('ajaxSearch') && Tools::getValue('action') == 'searchProduct') {
            $query = Tools::replaceAccentedChars(urldecode(Tools::getValue('q')));
            $searchResults = array();
            if (isset($query) && $query) {
                $searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
            }
            die(Tools::jsonEncode($searchResults));
        }

        if (!$ajax && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $field_type = Tools::getValue('field_type');
            $dependant = (int)Tools::getValue('dependant');
            $dependant_field = (int)Tools::getValue('dependant_field');
            $dependant_field_value = (int)Tools::getValue('dependant_value');
            $groups = Tools::getValue('groups');
            if (isset($field_type) && $field_type && in_array($field_type, array('multiselect', 'select', 'checkbox', 'radio'))) {
                $options = Tools::getValue('options', array());

                if (empty($options)) {
                    $this->errors[] = $this->l('Field option(s) are empty');
                }

                foreach ($options as $option) {
                    if (!Validate::isGenericName($option)) {
                        $this->errors[] = $this->l('Field option(s) has invalid value');
                    }
                }
            }
            if ($dependant > 0 && $dependant_field <= 0) {
                $this->errors[] = $this->l('Please choose dependant field OR turn off dependant option.');
            } elseif ($dependant > 0 && $dependant_field > 0) {
                $object = new FieldsModel((int)$dependant_field);
                $vals = $object->getCustomFieldsValues($dependant_field);
                $vals = (int)count($vals);
                if ($dependant_field_value <= 0 && $vals > 0) {
                    $this->errors[] = $this->l('Please choose dependant field value OR turn off dependant option.');
                }
            }
            if (empty($groups)) {
                $this->errors[] = $this->l('You must select customer group(s).');
            }
        } elseif ($ajax > 0 && $dep_id > 0) {
            $field = $dep_id;
            $this->getAjaxFieldVals($field);
        }

        if (Tools::getIsset('products') && Tools::getValue('products') && is_array(Tools::getValue('products'))) {
            $_POST['products'] = implode(',', Tools::getValue('products'));
        } else {
            $_POST['products'] = '';
        }
    }

    public function getCustomFieldTypes()
    {
        $custom_field_types = array(
            'text'          => $this->l('Text Field'),
            'textarea'      => $this->l('Text Area'),
            'date'          => $this->l('Date'),
            'boolean'       => $this->l('Yes/No'),
            'multiselect'   => $this->l('Multiple Select'),
            'select'        => $this->l('Dropdown Select'),
            'checkbox'      => $this->l('Checkbox(s)'),
            'radio'         => $this->l('Radio Button(s)'),
            //'image'         => $this->l('Image'),
            'attachment'    => $this->l('Attachment'),
            'message'       => $this->l('Message (Display Only)')
            );
        return $custom_field_types;
    }

    public function renderShops()
    {
        $this->fields_form = array(
            'form' => array(
                'id_form' => 'field_shops',
                'input' => array(
                    array(
                        'type' => 'shop',
                        'label' => $this->l('Shop association:'),
                        'name' => 'checkBoxShopAsso',
                    ),
                )
            )
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_custom_field');
        $helper->identifier = $this->identifier;
        $helper->tpl_vars = array_merge(array(
                //'fields_value' => $fields_value,
                'languages' => $this->getLanguages(),
                'id_language' => $this->context->language->id
            ));
        return $helper->renderAssoShop();
    }

    public function getShopValues($object)
    {
        return array('shop' => $this->getFieldValue($object, 'shop'));
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUI('ui.sortable');
        $this->addJqueryPlugin(array('typewatch', 'autocomplete'));
    }

    protected function beforeAdd($object)
    {
        if (empty($object->position) || !FieldsModel::positionOccupied($object->position)) {
            $object->position = FieldsModel::getHigherPosition() + 1;
        }
        parent::beforeAdd($object);
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $id_field = (int)(Tools::getValue('id'));
        $positions = Tools::getValue('custom_field');

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $id_field) {
                if ($field = new FieldsModel((int)$pos[2])) {
                    if (isset($position) && $field->updatePosition($way, $position)) {
                        echo 'ok position '.(int)$position.' for field '.(int)$pos[1].'\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update field '.(int)$id_field.' to position '.(int)$position.' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This field ('.(int)$id_field.') can t be loaded"}';
                }
                break;
            }
        }
    }

    public function processPosition()
    {
        if (Tools::getIsset('update'.$this->table)) {
            $object = new FieldsModel((int)Tools::getValue('id_custom_field'));
            self::$currentIndex = self::$currentIndex.'&update'.$this->table;
        } else {
            $object = new FieldsModel((int)Tools::getValue('id'));
        }
        if (!Validate::isLoadedObject($object)) {
            $this->errors[] = $this->l('An error occurred while updating the status for an object.').
                ' <b>'.$this->table.'</b> '.$this->l('(cannot load object)');
        } elseif (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position'))) {
            $this->errors[] = $this->l('Failed to update the position.');
        } else {
            $id_identifier_str = ($id_identifier = (int)Tools::getValue($this->identifier)) ? '&'.$this->identifier.'='.$id_identifier : '';
            $redirect = self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=5'.$id_identifier_str.'&token='.$this->token;
            $this->redirect_after = $redirect;
        }
        return $object;
    }

    public function getAjaxFieldVals($field)
    {
        $return = array('hasError' => false, 'exist' => false, 'vals' => '');
        $object = new FieldsModel((int)$field);
        $vals = array();
        if ('boolean' == $object->field_type) {
            $vals = $this->getBooleanEquivalentVal($object->id);
        } else {
            $vals = $object->getCustomFieldsValues($field);
        }

        $return['vals'] = $vals;
        $return['exist'] = (int)count($return['vals']);
        die(Tools::jsonEncode($return));
    }

    protected function getBooleanEquivalentVal($id_field)
    {
        return array(
            array(
                'id_custom_field' => $id_field,
                'field_value_id' => 1,
                'field_value' => $this->l('Yes')
            ),
            array(
                'id_custom_field' => $id_field,
                'field_value_id' => 0,
                'field_value' => $this->l('No')
            ),
        );
    }
}
