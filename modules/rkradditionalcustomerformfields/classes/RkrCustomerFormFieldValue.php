<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class RkrCustomerFormFieldValue extends ObjectModel
{
    public $id_value;
    public $id_customer;
    public $id_form_field;
    public $value;

    public static $definition = [
        'table' => 'rkr_customer_form_fields_values',
        'primary' => 'id_value',
        'fields' => [
            'id_form_field' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
            'id_customer' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
            ],
            'value' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isJson',
                'allow_null' => true,
            ],
        ],
    ];
}
