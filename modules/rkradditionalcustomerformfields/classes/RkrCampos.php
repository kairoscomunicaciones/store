<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once _PS_MODULE_DIR_ . 'rkradditionalcustomerformfields/classes/RkrAvailableValue.php';

class RkrCampos extends ObjectModel
{
    /** @var int position */
    public $position;
    public $active = true;
    public $id_form_field;
    public $name;
    public $label;
    public $type;
    public $default_value;
    public $required;
    public $required_address;
    public $maxLength;
    public $constraint;

    public $availableValues = false;

    public static $definition = [
        'table' => 'rkr_additional_form_fields',
        'primary' => 'id_form_field',
        'multilang' => true,
        'fields' => [
            'name' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 64,
                'required' => true,
            ],
            'label' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 64,
                'required' => true,
                'lang' => true,
            ],
            'type' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 64,
                'required' => true,
            ],
            'default_value' => [
                'type' => self::TYPE_STRING,
                'allow_null' => true,
                'copy_post' => false,
            ],
            'required' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
            ],
            'required_address' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
            ],
            'maxLength' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
                'allow_null' => true,
            ],
            'constraint' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
            ],
            'position' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
        ],
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);

        if ($id) {
            $this->availableValues = RkrAvailableValue::findAvailableValues($id);
        }
    }

    public static function isExist($name)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from(self::$definition['table'], 'aff');
        $query->where("aff.name = '$name'");

        return (bool) Db::getInstance()->getRow($query);
    }

    public static function findIdIsEnabled()
    {
        $query = new DbQuery();
        $query->select('id_form_field');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->where('aff.active = 1');
        $query->orderBy('aff.position');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function findIsEnabled()
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->where('aff.active = 1');
        $query->orderBy('aff.position');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function getAllFields()
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->orderBy('aff.position');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function isEnabledType($type = 'CountrySelect')
    {
        return (bool) Db::getInstance()->ExecuteS(
            'SELECT id_form_field FROM ' . _DB_PREFIX_ . "rkr_additional_form_fields WHERE 
                    active=1 AND type='$type';"
        );
    }

    public static function allRequiredAddressWithDefaultValues()
    {
        return !Db::getInstance()->ExecuteS(
            'SELECT id_form_field FROM ' . _DB_PREFIX_ . 'rkr_additional_form_fields WHERE 
                    required_address = 1 and default_value is null;'
        );
    }

    public static function getAllFieldsName()
    {
        $query = new DbQuery();
        $query->select('name');
        $query->from('rkr_additional_form_fields', 'aff');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function getDefaultValueByname($name)
    {
        $query = new DbQuery();
        $query->select('default_value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->where("aff.name = '$name'");

        return Db::getInstance()->getValue($query);
    }

    public static function getDefaultValues()
    {
        $query = new DbQuery();
        $query->select('name, default_value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->where('default_value IS NOT NULL');

        return Db::getInstance()->ExecuteS($query);
    }

    public function getValue($id_customer)
    {
        $query = new DbQuery();
        $query->select('value');
        $query->from('rkr_customer_form_fields_values', 'cffv');
        $query->where('cffv.id_form_field = ' . $this->id);
        $query->where("cffv.id_customer = $id_customer");

        return Db::getInstance()->getValue($query);
    }

    public static function findValuesByIdCustomer($id_customer)
    {
        $query = new DbQuery();
        $query->select('aff.*, cffv.value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->innerJoin('rkr_customer_form_fields_values', 'cffv', 'aff.id_form_field = cffv.id_form_field');
        $query->where('aff.active = 1');
        $query->where("cffv.id_customer = $id_customer");
        $query->orderBy('aff.position');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function findValuesByIdAndLang($id_customer, $id_lang)
    {
        $query = new DbQuery();
        $query->select('aff.*, aff_lang.label, cffv.value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->innerJoin('rkr_additional_form_fields_lang', 'aff_lang', 'aff.id_form_field = aff_lang.id_form_field');
        $query->leftJoin('rkr_customer_form_fields_values', 'cffv', 'aff.id_form_field = cffv.id_form_field');
        $query->where('aff.active = 1 OR cffv.value is not null');
        $query->where("aff_lang.id_lang = $id_lang");
        $query->where("cffv.id_customer = $id_customer");
        $query->orderBy('aff.position');

        return Db::getInstance()->ExecuteS($query);
    }

    public static function findValuesByCustomerAndFieldName($id_customer, $name = 'id_country')
    {
        $query = new DbQuery();
        $query->select('cffv.value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->innerJoin('rkr_customer_form_fields_values', 'cffv', 'aff.id_form_field = cffv.id_form_field');
        $query->where("aff.name = $name");
        $query->where("cffv.id_customer = $id_customer");

        return Db::getInstance()->getValue($query);
    }

    public static function getRequiredAddressFields()
    {
        $query = new DbQuery();
        $query->select('name');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->where('aff.required_address = 1');

        return Db::getInstance()->ExecuteS($query);
    }

    public function updatePosition($way, $position)
    {
        $res = Db::getInstance()->executeS('SELECT c.`id_form_field`, c.`position` FROM `'
            . _DB_PREFIX_ . 'rkr_additional_form_fields` c ORDER BY c.`position` ASC');

        if (!$res) {
            return false;
        }

        foreach ($res as $reg) {
            if ((int) $reg['id_form_field'] == (int) $this->id) {
                $reg_movido = $reg;
            }
        }

        if (!isset($reg_movido) || !isset($position)) {
            return false;
        }

        $result = Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'rkr_additional_form_fields`
				SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
				WHERE `position`
				' . ($way
                    ? '> ' . (int) $reg_movido['position'] . ' AND `position` <= ' . (int) $position
                    : '< ' . (int) $reg_movido['position'] . ' AND `position` >= ' . (int) $position))
            && Db::getInstance()->execute('
				UPDATE `' . _DB_PREFIX_ . 'rkr_additional_form_fields`
				SET `position` = ' . (int) $position . '
				WHERE `id_form_field` = ' . (int) $reg_movido['id_form_field']);

        return $result;
    }
}
