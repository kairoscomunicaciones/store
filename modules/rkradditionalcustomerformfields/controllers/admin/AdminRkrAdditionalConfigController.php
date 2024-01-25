<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'rkradditionalcustomerformfields/classes/RkrCampos.php';

class AdminRkrAdditionalConfigController extends ModuleAdminController
{
    protected $position_identifier = 'id_form_field';
    private $requiredAddressFields = [];

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'rkr_additional_form_fields';
        $this->className = 'RkrCampos';
        $this->identifier = 'id_form_field';
        $this->lang = true;
        $this->_defaultOrderBy = 'position';

        $this->explicitSelect = true;

        parent::__construct();

        $this->page_header_toolbar_title = $this->module->l('Add Custom Fields', 'adminrkradditionalconfigcontroller');
        $this->requiredAddressFields = $this->module->getRequiredAddressFields($this->context->country->id);

        $this->fields_list = [
            'id_form_field' => [
                'title' => $this->module->l('ID', 'adminrkradditionalconfigcontroller'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
                'filter_key' => 'a!id_form_field',
                'orderby' => false,
                'search' => false,
            ],
            'name' => [
                'title' => $this->trans('Name', [], 'Admin.Global'),
                'orderby' => false,
                'search' => false,
            ],
            'label' => [
                'title' => $this->trans('Label', [], 'Admin.Global'),
                'orderby' => false,
                'search' => false,
            ],
            'type' => [
                'title' => $this->trans('Type', [], 'Admin.Global'),
                'orderby' => false,
                'search' => false,
            ],
            'required_address' => [
                'title' => $this->module->l('Required in address', 'adminrkradditionalconfigcontroller'),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'callback' => 'getIsRequired',
            ],
            'default_value' => [
                'title' => $this->module->l('Default value', 'adminrkradditionalconfigcontroller'),
                'orderby' => false,
                'search' => false,
                'callback' => 'getListDefaultvalue',
            ],
            'position' => [
                'title' => $this->trans('Position', [], 'Admin.Global'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
                'orderby' => true,
                'search' => false,
            ],
            'active' => [
                'title' => $this->trans('Active', [], 'Admin.Global'),
                'active' => 'status',
                'type' => 'bool',
                'filter_key' => 'a!active',
                'orderby' => false,
                'search' => false,
                'width' => 'auto',
            ],
        ];

        $url = $this->context->link->getBaseLink() . 'modules/' . $this->module->name . '/';
        $dirEs = $url . 'docs/readme-es.pdf';
        $dirEn = $url . 'docs/readme-en.pdf';

        $this->fields_options = [
            'selledplusconfig' => [
                'title' => $this->module->l('Extra config', 'adminrkradditionalconfigcontroller'),
                'image' => '../img/admin/cog.gif',
                'fields' => [
                    'RKR_ADDITIONAL_CREATEADDRESS' => [
                        'type' => 'bool',
                        'title' => $this->module->l('Create address when creating the account', 'adminrkradditionalconfigcontroller'),
                        'defaultValue' => false,
                        'desc' => $this->module->l('Will only be created if the required address fields are completed', 'adminrkradditionalconfigcontroller'),
                    ],
                    'RKR_ADDITIONAL_CREATEADDRESS_ALIAS' => [
                        'type' => 'text',
                        'title' => $this->module->l('Alias', 'adminrkradditionalconfigcontroller'),
                        'required' => true,
                        'validation' => 'isGenericName',
                        'size' => 32,
                        'defaultValue' => 'Address alias',
                        'desc' => $this->module->l('Aliases when creating addresses', 'adminrkradditionalconfigcontroller'),
                    ],
                    'RKR_ADDITIONAL_CREATEADDRESS_DEFAULT' => [
                        'type' => 'bool',
                        'title' => $this->module->l('Create address using default values', 'adminrkradditionalconfigcontroller'),
                        'defaultValue' => false,
                        'desc' => $this->module->l('This option only takes effect if defaults are filled.', 'adminrkradditionalconfigcontroller'),
                    ],
                    'RKR_ADDITIONAL_PUT_DEFAULT' => [
                        'type' => 'bool',
                        'title' => $this->module->l('Put default values on custom data', 'adminrkradditionalconfigcontroller'),
                        'defaultValue' => false,
                        'desc' => $this->module->l('Only required fields in addresses.', 'adminrkradditionalconfigcontroller'),
                    ],
                ],
                'submit' => ['title' => $this->trans('Save', [], 'Admin.Actions'), 'btn btn-default pull-right'],
                'bottom' => '<div><a href="' . $dirEs . '">Documentación</a><br><a href="' . $dirEn . '">Documentation</a></div>',
            ],
        ];

        $this->bulk_actions['activateRequiredAddress'] = [
            'text' => $this->module->l('Activate if required in address', 'adminrkradditionalconfigcontroller'),
            'icon' => 'icon-pencil',
            'confirm' => $this->module->l('Are you sure?', 'adminrkradditionalconfigcontroller'),
        ];
    }

    public function renderList()
    {
        $this->addRowAction('edit');

        return parent::renderList();
    }

    public function initToolbar()
    {
        $res = parent::initToolbar();
        unset($this->toolbar_btn['new']);

        return $res;
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        if (empty($this->display) || ($this->display && $this->display == 'options')) {
            $this->page_header_toolbar_btn['desc-module-back'] = [
                'href' => 'index.php?controller=AdminModules&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->module->l('Back', 'adminrkradditionalconfigcontroller'),
                'icon' => 'process-icon-back',
            ];
            $this->page_header_toolbar_btn['desc-module-translate'] = [
                'href' => '#',
                'desc' => $this->trans('Translate', [], 'Admin.Actions'),
                'modal_target' => '#moduleTradLangSelect',
                'icon' => 'process-icon-flag',
            ];
        }
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->module->l('Modify', 'adminrkradditionalconfigcontroller'),
            ],
            'input' => [
                ['type' => 'text',
                    'name' => 'name',
                    'label' => $this->trans('Name', [], 'Admin.Global'),
                    'maxchar' => 64,
                    'maxlength' => 64,
                    'required' => true,
                    'disabled' => true,
                    'size' => 32,
                    'class' => 'col-lg-6',
                ],
                ['type' => 'text',
                    'name' => 'label',
                    'label' => 'label',
                    'maxchar' => 64,
                    'maxlength' => 64,
                    'required' => true,
                    'lang' => true,
                ],
                ['type' => 'switch',
                    'label' => $this->trans('Required', [], 'Admin.Global'),
                    'name' => 'required',
                    'required' => true,
                    'values' => [
                        ['id' => 'active_on',
                            'value' => 1,
                        ], ['id' => 'active_off',
                            'value' => 0,
                        ],
                    ],
                    'desc' => $this->module->l('Make this field Required in form', 'adminrkradditionalconfigcontroller'),
                ],
                'default_value' => [
                    'type' => 'text',
                    'name' => 'default_value',
                    'label' => $this->module->l('Default value', 'adminrkradditionalconfigcontroller'),
                    'required' => $this->object->required_address && Configuration::get('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT'),
                    'desc' => $this->object->required_address ?
                        $this->module->l('This field is required if you want to create the address automatically without asking the customer for the information.', 'adminrkradditionalconfigcontroller') : '',
                ],
                ['type' => 'switch',
                    'label' => $this->trans('Active', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => true,
                    'values' => [
                        ['id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ], ['id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                    'desc' => $this->module->l('This field is for customer dialog', 'adminrkradditionalconfigcontroller'),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ],
        ];

        if ($this->object->type == 'countrySelect') {
            $this->fields_form['input']['default_value']['type'] = 'select';
            $this->fields_form['input']['default_value']['id'] = 'RKR_select';
            $this->fields_form['input']['default_value']['options'] = [
                'query' => $this->getAvailableCountries(),
                'id' => 'id_country',
                'name' => 'name',
            ];
        } elseif ($this->object->type == 'select' && $this->object->name == 'id_state') {
            $availableStates = $this->getAvailableStates();

            $this->fields_form['input']['default_value']['type'] = 'select';
            $this->fields_form['input']['default_value']['id'] = 'rkr_select';
            $this->fields_form['input']['default_value']['options'] = [
                'query' => $availableStates,
                'id' => 'id_state',
                'name' => 'name',
            ];
            if (!$availableStates && $availableStates !== false) {
                $this->fields_form['input']['default_value']['desc'] =
                    $this->module->l('Please select the country first', 'adminrkradditionalconfigcontroller');
                $this->fields_form['input']['default_value']['class'] = 'rkr_disabled';
            } elseif ($availableStates === false) {
                $this->fields_form['input']['default_value']['class'] = 'rkr_disabled';
                $this->fields_form['input']['default_value']['desc'] =
                    $this->module->l('Selected country by default have no states', 'adminrkradditionalconfigcontroller');
            }
        }

        if (Validate::isLoadedObject($this->object)) {
            $this->fields_value['default_value'] = json_decode($this->object->default_value);
        }

        return parent::renderForm();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();

        $moduleDir = _PS_MODULE_DIR_ . $this->module->name;
        if ($this->display == 'add' || $this->display == 'edit' || $this->display == 'list') {
            $this->addJS($moduleDir . '/views/js/back.js');
        }
    }

    // usado para validar los datos por defecto
    protected function _childValidation()
    {
        $defaultValue = Tools::getValue('default_value');

        if ($this->object->required_address && Configuration::get('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT') &&
            !$defaultValue) {
            $this->errors[] = sprintf(
                $this->module->l('The default value for field %1$s is required', 'adminrkradditionalconfigcontroller'),
                $this->object->label[(int) $this->context->language->id]
            );
        }

        if ($this->object->maxLength && (Tools::strlen($defaultValue) > $this->object->maxLength)) {
            $this->errors[] = sprintf(
                $this->module->l('The default value for %1$s field is too long (%2$d chars max).', 'adminrkradditionalconfigcontroller'),
                $this->object->label[(int) $this->context->language->id], $this->object->maxLength
            );
        }

        if ($this->object->constraint) {
            $method = $this->object->constraint;
            if (!Validate::$method($defaultValue) && !empty($defaultValue)) {
                $this->errors[] = sprintf(
                    $this->module->l('The default value for %1$s field is invalid', 'adminrkradditionalconfigcontroller'),
                    $this->object->label[(int) $this->context->language->id]
                );
            }
        }
    }

    protected function copyFromPost(&$object, $table)
    {
        parent::copyFromPost($object, $table);
        $object->default_value = Tools::getValue('default_value') ? json_encode(Tools::getValue('default_value')) : null;
    }

    protected function processBulkActivateRequiredAddress()
    {
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'rkr_additional_form_fields SET active = 1 WHERE required_address = 1';
        if ($this->requiredAddressFields) {
            $requiredAddress = implode("','", $this->requiredAddressFields);
            $sql .= " OR name IN ('$requiredAddress')";
        }

        $dbQuery = Db::getInstance();
        $dbQuery->query($sql);
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int) Tools::getValue('way');
        $id_reg = (int) Tools::getValue('id');
        $positions = Tools::getValue('form_field');

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $id_reg) {
                if ($campo = new RkrCampos((int) $pos[2])) {
                    if (isset($position) && $campo->updatePosition($way, $position)) {
                        echo 'ok position ' . (int) $position . ' for reg ' . (int) $pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update reg ' . (int) $id_reg . ' to position ' . (int) $position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This reg (' . (int) $id_reg . ') can t be loaded"}';
                }

                break;
            }
        }
    }

    public function initModal()
    {
        parent::initModal();
        $languages = Language::getLanguages(false);
        $translateLinks = [];
        if (version_compare(_PS_VERSION_, '1.7.2.1', '>=')) {
            $module = $this->module;
            $isNewTranslateSystem = $module->isUsingNewTranslationSystem();
            $link = Context::getContext()->link;
            foreach ($languages as $lang) {
                if ($isNewTranslateSystem) {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslationSf', true, [
                        'lang' => $lang['iso_code'],
                        'type' => 'modules',
                        'selected' => $module->name,
                        'locale' => $lang['locale'],
                    ]);
                } else {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslations', true, [], [
                        'type' => 'modules',
                        'module' => $module->name,
                        'lang' => $lang['iso_code'],
                    ]);
                }
            }
        }
        $this->context->smarty->assign([
            'trad_link' => 'index.php?tab=AdminTranslations&token=' . Tools::getAdminTokenLite('AdminTranslations') . '&type=modules&module=' . $this->module->name . '&lang=',
            'module_languages' => $languages,
            'module_name' => $this->module->name,
            'translateLinks' => $translateLinks,
        ]);
        $modal_content = $this->context->smarty->fetch('controllers/modules/modal_translation.tpl');
        $this->modals[] = [
            'modal_id' => 'moduleTradLangSelect',
            'modal_class' => 'modal-sm',
            'modal_title' => $this->module->l('Translate this module', 'adminrkradditionalconfigcontroller'),
            'modal_content' => $modal_content,
        ];
    }

    public function getIsRequired($valor, $arrDatos)
    {
        if ($valor || in_array($arrDatos['name'], $this->requiredAddressFields)) {
            return $this->trans('Yes', [], 'Admin.Global');
        } else {
            return $this->trans('No', [], 'Admin.Global');
        }
    }

    public function getListDefaultvalue($valor, $arrDatos)
    {
        switch ($arrDatos['name']) {
            case 'id_country':
                return Country::getNameById($this->context->language->id, (int) json_decode($valor));
            case 'id_state':
                return State::getNameById((int) json_decode($valor));
            default:
                return $valor;
        }
    }

    public function getAvailableCountries()
    {
        return array_merge(
            [['id_country' => 0, 'name' => $this->trans('Select', [], 'Admin.Actions')]],
            Country::getCountries($this->context->language->id, true)
        );
    }

    public function getAvailableStates()
    {
        $availableStates = [];
        if ($id_country = (int) json_decode(RkrCampos::getDefaultValueByname('id_country'))) {
            $states = State::getStatesByIdCountry($id_country, true, 'name', 'asc');
            if ($states) {
                $availableStates = array_merge(
                    [['id_state' => 0, 'name' => $this->trans('Select', [], 'Admin.Actions')]], $states
                );
            } else {
                $availableStates = false;
            }
        }

        return $availableStates;
    }
}
