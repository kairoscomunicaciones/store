<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class RkrAvailableValue extends ObjectModel
{
    public $id_available_value;
    public $id_form_field;
    public $value;
    public $label;

    public static $definition = [
        'table' => 'rkr_available_values_form_fields',
        'primary' => 'id_available_value',
        'multilang' => true,
        'fields' => [
            'id_form_field' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
            'value' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isJson',
                'allow_null' => true,
            ],
            'label' => [            // posibles valores 'placeholder', 'comment', ..
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'allow_null' => true,
            ],
        ],
    ];

    public static function findAvailableValues($id_form_field)
    {
        $query = new DbQuery();
        $query->select('value, label');
        $query->from('rkr_available_values_form_fields', 'avff');
        $query->where("avff.id_form_field = $id_form_field");

        return Db::getInstance()->ExecuteS($query);
    }
}
