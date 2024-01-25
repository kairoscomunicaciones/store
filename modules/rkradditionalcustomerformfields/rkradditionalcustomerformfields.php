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

class RkrAdditionalCustomerFormFields extends Module
{
    const INSTALL_SQL_FILE = 'tablas.sql';

    public function __construct()
    {
        $this->name = 'rkradditionalcustomerformfields';
        $this->version = '1.1.1';
        $this->tab = 'administration';
        $this->author = 'Rekire';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('RKR registration form');
        $this->description = $this->l('Add address field on registration form');

        $this->ps_versions_compliancy = ['min' => '1.7.6', 'max' => _PS_VERSION_];
    }

    public function install($keep = true)
    {
        Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS', 0);
        Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT', 0);
        Configuration::updateValue('RKR_ADDITIONAL_CREATEADDRESS_ALIAS', 'Address alias');
        Configuration::updateValue('RKR_ADDITIONAL_PUT_DEFAULT', 0);

        if ($keep) {
            if (!$this->createDB_file() || !$this->insertDataToDb()) {
                return false;
            }
        }

        $ret = parent::install() &&
            $this->registerHook('additionalCustomerFormFields') &&
            $this->registerHook('displayAdminCustomers') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('actionCustomerAccountUpdate') &&
            $this->registerHook('actionFrontControllerSetMedia');

        if ($ret) {
            $this->info();
        }

        return $ret;
    }

    private function info()
    {
        $varList = ['{url}' => $_SERVER['SERVER_NAME'],
            '{moduleName}' => $this->displayName,
            '{shopUrl}' => $this->context->link->getAdminLink('AdminShopUrl',false),
        ];
        $allContacts = [ 'instalacion@rekire.com' ];

        Mail::Send(
            $this->context->language->id,
            'notifications',
            'Nueva instalación del módulo ' . $this->name,
            $varList,
            $allContacts,
            'RKR Módulos Prestashop',
            null,
            null,
            '',
            null,
            dirname(__FILE__) . '/mails/'
        );
    }

    public function uninstall($keep = true)
    {
        if (!parent::uninstall() || ($keep && !$this->deleteDB()) ||
            !$this->unregisterHook('additionalCustomerFormFields') ||
            !$this->unregisterHook('displayAdminCustomers') ||
            !$this->unregisterHook('actionCustomerAccountAdd') ||
            !$this->unregisterHook('actionCustomerAccountUpdate') ||
            !$this->registerHook('actionFrontControllerSetMedia')
        ) {
            return false;
        }

        Configuration::deleteByName('RKR_ADDITIONAL_CREATEADDRESS');
        Configuration::deleteByName('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT');
        Configuration::deleteByName('RKR_ADDITIONAL_CREATEADDRESS_ALIAS');
        Configuration::deleteByName('RKR_ADDITIONAL_PUT_DEFAULT');

        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        // redirigimos a un controller para tratar la configuración allí
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminRkrAdditionalConfig'));
    }

    private function createDB_file()
    {
        if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
            return false;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
            return false;
        }
        $sql = str_replace(['PREFIX_', 'ENGINE_TYPE'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql);

        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                return false;
            }
        }

        return true;
    }

    private function deleteDB()
    {
        return Db::getInstance()->execute(
            'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'rkr_additional_form_fields,
            ' . _DB_PREFIX_ . 'rkr_additional_form_fields_lang,
            ' . _DB_PREFIX_ . 'rkr_available_values_form_fields,
            ' . _DB_PREFIX_ . 'rkr_customer_form_fields_values');
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if (($this->context->controller->php_self == 'authentication' ||
                $this->context->controller->php_self == 'identity') &&
            (($this->context->customer->isLogged() && !Address::getFirstCustomerAddressId($this->context->customer->id)) ||
                !$this->context->customer->isLogged())
        ) {
            $this->context->controller->registerJavascript(
                'module-rkradditionalcustomerformfields',
                'modules/' . $this->name . '/views/js/front.js',
                []
            );
        }
    }

    public function hookAdditionalCustomerFormFields($params)
    {
        if (($this->context->controller->php_self == 'authentication' ||
                $this->context->controller->php_self == 'identity') &&
            (($this->context->customer->isLogged() && !Address::getFirstCustomerAddressId($this->context->customer->id)) ||
                !$this->context->customer->isLogged())
        ) {
            $format = [];
            foreach (RkrCampos::findIdIsEnabled() as $camposArray) {
                $id_form_field = $camposArray['id_form_field'];
                $campo = new RkrCampos((int) $id_form_field, $this->context->language->id);

                $formField = new FormField();
                $formField->setname($campo->name)
                    ->setLabel($campo->label)
                    ->setType($campo->type)
                    ->setRequired($campo->required)
                    ->setMaxLength($campo->maxLength)
                    ->addConstraint($campo->constraint);

                if ($campo->type == 'countrySelect') {
                    $countryPresent = true;
                    $formField->setType('select');
                    $formField->setValue($this->context->country->id);
                    foreach (Country::getCountries($this->context->language->id, true) as $pais) {
                        $formField->addAvailableValue($pais['id_country'], $pais['name']);
                    }
                }
                if ($campo->type === 'select' && $campo->name === 'id_state') {
                    if ($this->context->country->contains_states) {
                        $states = State::getStatesByIdCountry($this->context->country->id, true, 'name', 'asc');
                        foreach ($states as $state) {
                            $formField->addAvailableValue($state['id_state'], $state['name']);
                        }
                    }
                }

                if ($this->context->customer->isLogged()) {
                    if ($valor = $campo->getValue($this->context->customer->id)) {
                        $formField->setValue(json_decode($valor));
                    }
                }
                $format[$campo->name] = $formField;
            }

            if (isset($countryPresent)) {
                $ajaxLink = $this->context->link->getModuleLink(
                    $this->name,
                    'ajax',
                    [
                        'ajax' => '1',
                        'action' => 'getStates',
                    ]
                );
                $formField = new FormField();
                $formField->setname('rkr_change_country');
                $formField->setValue($ajaxLink);
                $formField->setType('hidden');
                $format['rkr_change_country'] = $formField;
            }

            return $format;
        }

        return [];
    }

    public function hookActionCustomerAccountAdd($params)
    {
        if (!isset($params['newCustomer']) || !isset($params['newCustomer']->id)) {
            return;
        }

        $customer = $params['newCustomer'];
        $id_customer = $customer->id;

        $additionalFields = RkrCampos::findIsEnabled();

        $idCountry = (int) Tools::getValue('id_country',
            json_decode(RkrCampos::getDefaultValueByname('id_country')));

        $requiredOnAddress = $this->getRequiredAddressFields($idCountry);
        $defaultValues = RkrCampos::getDefaultValues();
        $defaultValuesFieldName = array_flip(array_column($defaultValues, 'name'));

        if (Configuration::get('RKR_ADDITIONAL_CREATEADDRESS') &&
            Configuration::get('RKR_ADDITIONAL_CREATEADDRESS_ALIAS') &&
            $idCountry
        ) {
            $createAddress = true;
            $addressData = [
                'id_customer' => $id_customer,
                'alias' => Configuration::get('RKR_ADDITIONAL_CREATEADDRESS_ALIAS'),
                'firstname' => Tools::getValue('firstname'),
                'lastname' => Tools::getValue('lastname'),
            ];

            $useDefaultValues = Configuration::get('RKR_ADDITIONAL_CREATEADDRESS_DEFAULT');
            foreach ($requiredOnAddress as $requiredAddressField) {
                if (Tools::getIsset($requiredAddressField) && $value = Tools::getValue($requiredAddressField)) {
                    $addressData[$requiredAddressField] = $value;
                } else {
                    if ($useDefaultValues &&
                        $value = json_decode(
                            $defaultValues[$defaultValuesFieldName[$requiredAddressField]]['default_value'])) {
                        $addressData[$requiredAddressField] = $value;
                    } else {
                        $createAddress = false;
                        break;
                    }
                }
            }
            if ($createAddress) {
                if ($additionalFields) { // campos que no son obligatorios para crear una dirección pero están presentes en el formulario
                    foreach ($additionalFields as $camposArray) {
                        if (!isset($addressData[$camposArray['name']])) {
                            $addressData[$camposArray['name']] = Tools::getValue($camposArray['name']);
                        }
                    }
                }

                $newAddress = new Address();
                $newAddress->hydrate($addressData);
                $newAddress->save();
            }
        }

        $defaultToCustomData = Configuration::get('RKR_ADDITIONAL_PUT_DEFAULT');
        foreach (RkrCampos::getAllFields() as $camposArray) {
            $value = null;
            $fieldName = $camposArray['name'];
            $idFormField = $camposArray['id_form_field'];
            if ((!$camposArray['active'] &&
                    (in_array($fieldName, $requiredOnAddress) === false || !isset($defaultValuesFieldName[$fieldName])))
                || (!$defaultToCustomData && !$camposArray['active'])) {
                continue;
            }

            if ($defaultToCustomData) {
                if (!Tools::getValue($fieldName) && isset($defaultValuesFieldName[$fieldName])) {
                    $value = $defaultValues[$defaultValuesFieldName[$fieldName]]['default_value'];
                }
            }
            $datos = ['id_form_field' => $idFormField,
                'id_customer' => $id_customer,
                'value' => $value ?? json_encode(Tools::getValue($fieldName)), ];

            $insertado = Db::getInstance()->insert('rkr_customer_form_fields_values', $datos, true);
        }
    }

    public function getRequiredAddressFields($id_country = 0)
    {
        $query = new DbQuery();
        $query->select('field_name');
        $query->from('required_field', 'r_f');
        $query->where("object_name = 'CustomerAddress'");
        $fieldsRequired = array_column(Db::getInstance()->ExecuteS($query), 'field_name');
        $fieldsDefaultRequired = array_column(RkrCampos::getRequiredAddressFields(), 'name');

        $moreRequired = [];

        if ($id_country) {
            if (Country::containsStates($id_country)) {
                $moreRequired[] = 'id_state';
            }
            if ($this->dniRequired($id_country)) {
                $moreRequired[] = 'dni';
            }
        }

        return array_merge($fieldsRequired, $fieldsDefaultRequired, $moreRequired);
    }

    public function hookActionCustomerAccountUpdate($params)
    {
        if (!isset($params['customer']) || !isset($params['customer']->id)) {
            return;
        }

        $id_customer = $params['customer']->id;

        if (!Address::getFirstCustomerAddressId($id_customer) &&
            ($additionalFileds = RkrCampos::findIsEnabled())
        ) {
            foreach ($additionalFileds as $camposArray) {
                $id_form_field = $camposArray['id_form_field'];
                $datos = [
                    'id_form_field' => $id_form_field,
                    'id_customer' => $id_customer,
                    'value' => json_encode(Tools::getValue($camposArray['name'])),
                ];

                Db::getInstance()->insert('rkr_customer_form_fields_values', $datos, true, true, Db::ON_DUPLICATE_KEY);
            }
        }
    }

    // a partir de la version 1.7.6 está basado en .twig
    public function hookDisplayAdminCustomers($params)
    {
        if ($this->isSymfonyContext()) {
            $idCustomer = $params['id_customer'];

            $campos = RkrCampos::findValuesByIdAndLang((int) $params['id_customer'], (int) $this->context->language->id);

            foreach ($campos as &$campo) {
                if ($campo['value']) {
                    switch ($campo['name']) {
                        case 'id_country':
                            $campo['value'] = Country::getNameById(
                                $this->context->language->id, (int) json_decode($campo['value']));
                            break;
                        case 'id_state':
                            $campo['value'] = State::getNameById((int) json_decode($campo['value']));
                            break;
                        default:
                            $campo['value'] = json_decode($campo['value']);
                            break;
                    }
                }
            }
            unset($campo);

            return $this->get('twig')->render('@Modules/rkradditionalcustomerformfields/views/admin/aditional_fields.twig', [
                'campos' => $campos,
                'title' => $this->l('Custom data'),
            ]);
        }

        return null;
    }

    private function insertDataToDb()
    {
        $camposDireccion = [
            'address1' => [
                'label' => 'Address', 'type' => 'text', 'constraint' => 'isAddress', 'maxLength' => 128,
                'required_address' => true,
            ],
            'address2' => ['label' => 'Address Complement', 'type' => 'text', 'constraint' => 'isAddress', 'maxLength' => 128],
            'id_country' => [
                'label' => 'Country', 'type' => 'countrySelect', 'constraint' => 'isUnsignedId',
                'required_address' => true,
            ],
            'phone' => ['label' => 'Phone', 'type' => 'tel', 'constraint' => 'isPhoneNumber', 'maxLength' => 32],
            'phone_mobile' => [
                'label' => 'Mobile phone', 'type' => 'tel', 'constraint' => 'isPhoneNumber', 'maxLength' => 32,
            ],
            'dni' => ['label' => 'Identification number', 'type' => 'text', 'constraint' => 'isDniLite', 'maxLength' => 16],
            'postcode' => ['label' => 'Zip/Postal Code', 'type' => 'text', 'constraint' => 'isPostCode', 'maxLength' => 12],
            'city' => [
                'label' => 'City', 'type' => 'text', 'constraint' => 'isCityName', 'maxLength' => 64,
                'required_address' => true,
            ],
            'other' => ['label' => 'Other', 'type' => 'text', 'constraint' => 'isMessage', 'maxLength' => 300],
            'company' => ['label' => 'Company', 'type' => 'text', 'constraint' => 'isGenericName', 'maxLength' => 16],
            'vat_number' => ['label' => 'VAT number', 'type' => 'text', 'constraint' => 'isGenericName'],
            'id_state' => ['label' => 'State', 'type' => 'select', 'constraint' => 'isUnsignedId'],
        ];

        $insertado = true;
        $posicion = 0;
        foreach ($camposDireccion as $name => $campos) {
            if (!RkrCampos::isExist($name)) {
                $campo = new RkrCampos();
                $campo->name = $name;
                $campo->label = $this->transMultiLangField($campos['label']);
                $campo->type = $campos['type'];
                $campo->required = false;
                $campo->required_address = $campos['required_address'] ?? 0;
                $campo->maxLength = $campos['maxLength'] ?? null;
                $campo->constraint = $campos['constraint'] ?? null;
                $campo->active = false;
                $campo->position = $posicion;
                $insertado &= $campo->save(true);
                ++$posicion;
            }
        }

        return $insertado;
    }

    public function transMultiLangField($value)
    {
        $languages = Language::getLanguages(false);

        $res = [];
        foreach ($languages as $lang) {
            $trans = Context::getContext()->getTranslator()->trans($value, [], 'Shop.Forms.Labels', $lang['locale']);
            $res[$lang['id_lang']] = $trans;
        }

        return $res;
    }

    public function dniRequired($idCountry)
    {
        return (bool) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT c.`need_identification_number` ' .
            'FROM `' . _DB_PREFIX_ . 'country` c ' .
            'WHERE c.`id_country` = ' . (int) $idCountry
        );
    }
}
