<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Handler;

use Context;
use Db;
use Module;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class CheckoutCustomFieldsHandler
{
    private static $STEPS = [
        'SUMMARY' => 'summary',
        'DELIVERY' => 'delivery',
        'BILLING' => 'billing',
        'SHIPPING' => 'shipping',
        'PAYMENT' => 'payment',
    ];

    private $module = null;

    private $context = null;

    private $fieldsHandler = null;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();
        $this->fieldsHandler = new \FieldsModel();
    }

    /**
     * @param $step
     *
     * @return bool
     */
    public function isValidStep($step)
    {
        foreach (self::$STEPS as $key => $STEP) {
            if ($step === $STEP) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        try {
            if (Module::isEnabled('riccionepiadina')) {
                $summary_fields = $this->getFieldsByStep(self::$STEPS['SUMMARY']);
                $id_cart = $this->context->cart->id;
                $date = $this->composeDeliveryMessage($id_cart);
                foreach ($summary_fields as &$field) {
                    if ($field['id'] == 3) {
                        $field['message'] = $field['message'].' '.$date;
                    }
                }
            } else {
                $summary_fields = $this->getFieldsByStep(self::$STEPS['SUMMARY']);
            }
            $delivery_fields = $this->getFieldsByStep(self::$STEPS['DELIVERY']);
            $billing_fields = $this->getFieldsByStep(self::$STEPS['BILLING']);
            $shipping_fields = $this->getFieldsByStep(self::$STEPS['SHIPPING']);
            $payment_fields = $this->getFieldsByStep(self::$STEPS['PAYMENT']);
        } catch (\Exception $ex) {
            return [
                self::$STEPS['SUMMARY'] => [],
                self::$STEPS['DELIVERY'] => [],
                self::$STEPS['BILLING'] => [],
                self::$STEPS['SHIPPING'] => [],
                self::$STEPS['PAYMENT'] => [],
            ];
        }

        return [
            self::$STEPS['SUMMARY'] => $summary_fields,
            self::$STEPS['DELIVERY'] => $delivery_fields,
            self::$STEPS['BILLING'] => $billing_fields,
            self::$STEPS['SHIPPING'] => $shipping_fields,
            self::$STEPS['PAYMENT'] => $payment_fields,
        ];
    }

    /**
     * INFO: copied from original module method "filterFieldsByProducts"
     *
     * @param $fields
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     */
    private function filterFields(&$fields)
    {
        $products = $this->context->cart->getProducts(false, false, null, false);

        if (isset($fields) && $fields && isset($products) && $products) {
            // getting ids
            foreach ($products as $key => $product) {
                if (isset($product['id_product'])) {
                    $products[$key] = (int) $product['id_product'];
                }
            }

            // filtering fields
            if (isset($products) && $products) {
                $productsCategoriesIds = Db::getInstance()->executeS(
                    '
                    SELECT DISTINCT cp.id_category
                    FROM `'._DB_PREFIX_.'category_product` cp
                    WHERE cp.id_product IN ('.implode(',', $products).')'
                );

                foreach ($fields as $key => $field) {
                    if (!empty($field['products'])) {
                        $selected_products = explode(',', $field['products']);
                    } else {
                        $selected_products = [];
                    }
                    $id_category = (int) $this->fieldsHandler->needleCheckCategory($field['id_custom_field']);

                    if ($id_category > 0) {
                        if (!empty($selected_products)) {
                            $exists = array_intersect($products, $selected_products);
                        } else {
                            $exists = false;
                        }
                        if (!$exists) {
                            foreach ($productsCategoriesIds as $productsCategoryId) {
                                if ((int) $productsCategoryId['id_category'] === $id_category) {
                                    $exists = true;

                                    break;
                                }
                            }
                        }

                        if (!$exists) {
                            unset($fields[$key]);
                        }
                    }
                }
            }

            return $fields;
        } else {
            return false;
        }
    }

    /**
     * Fix the module function getCustomFields to avoid duplicated caused by customer groups (distinct clausole missing on query)
     *
     * @param $fields
     *
     * @return array
     */
    private function filterCustomFields($fields)
    {
        $uniqueFields = [];
        $uniqueKeys = [];

        foreach ($fields as $key => $field) {
            $uniqueKeys[$field['id_custom_field']] = $key;
        }

        foreach ($uniqueKeys as $key) {
            $uniqueFields[] = $fields[$key];
        }

        return $uniqueFields;
    }

    /**
     * @param $step
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getFieldsByStep($step)
    {
        $fieldValidations = [
            'isGenericName' => '/^[^<>={}]+$/',
            'isString' => '/^[a-zA-Z 0-9 ]+$/',
            'isInt' => '/^[0-9]+$/',
            'isFloat' => '/^[-+]?[0-9]+(\.[0-9]+)?$/',
            'isUrl' => '/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i',
            'isEmail' => '/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+[._a-z\p{L}0-9-]*\.[a-z\p{L}0-9]+$/i',
            'isDate' => '/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/',
        ];

        $fields = $this->filterCustomFields($this->fieldsHandler->getCustomFields(
            $step,
            $this->context->language->id,
            $this->context->shop->id,
            $this->context->cart->id
        ));

        try {
            $this->filterFields($fields);
        } catch (\Exception $ex) {
            return [];
        }

        $output = [];

        foreach ($fields as $field) {
            $values = $this->fieldsHandler->getCustomFieldsValues($field['id_custom_field']);

            $item = [
                'id' => (int) $field['id_custom_field'],
                'name' => $field['field_name'],
                'type' => $field['field_type'],
                'validation_regex' => !empty($field['field_validation']) ? $fieldValidations[$field['field_validation']] : '',
                'validation' => $field['field_validation'],
                'editable' => (bool) (int) $field['editable'],
                'required' => (bool) (int) $field['value_required'],
                'default' => $field['default_value'],
            ];

            if ($field['dependant_field'] !== '0') {
                $item['depends_on'] = [
                    'field_id' => (int) $field['dependant_field'],
                    'value_id' => (int) $field['dependant_value'],
                ];
            }

            switch ($field['field_type']) {
                case 'date':
                    unset($item['default']);

                    break;

                case 'boolean':
                    $item['default'] = (bool) (int) $field['default_value'];

                    break;

                case 'multiselect':
                case 'select':
                case 'radio':
                case 'checkbox':
                    unset($item['default']);

                    if ($field['field_type'] === 'multiselect') {
                        $item['multiple'] = true;
                    } else {
                        $item['multiple'] = false;
                    }

                    if (count($values) > 0) {
                        foreach ($values as $value) {
                            $item['values'][] = [
                                'id' => (int) $value['field_value_id'],
                                'label' => $value['field_value'],
                            ];
                        }
                    }

                    break;

                case 'attachment':
                    unset($item['default']);
                    $item['extensions'] = $field['extensions'];
                    $item['max_size'] = $field['attachment_size'] * pow(1024, 2);

                    break;

                case 'message':
                    unset($item['required']);
                    unset($item['disabled']);
                    unset($item['default']);
                    $item['message'] = $field['default_value'];

                    break;

                default:
                    break;
            }

            $output[] = $item;
        }

        return $output;
    }

    /**
     * @param $params
     * @params $step
     *
     * @param $id_cart
     *
     * @return array
     */
    public function saveFields($params, $step, $id_cart)
    {
        try {
            $fields = [];

            foreach ($params as $param) {
                $id = isset($param['id']) ? $param['id'] : false;
                $value = isset($param['value']) ? $param['value'] : false;

                switch ($param['type']) {
                    case 'boolean':
                        $fields[$id] = (bool) $value ? 'Yes' : 'No';

                        break;
                    case 'select':
                    case 'radio':
                    case 'checkbox':
                        $fields[$id] = [$value];

                        break;
                    case 'multiselect':
                        $fields[$id] = $value;

                        break;
                    default:
                        $fields[$id] = (string) $value;

                        break;
                }
            }

            $response = ['success' => true, 'errors' => []];

            if (Module::isEnabled('riccionepiadina') && $step === self::$STEPS['PAYMENT']) {
                $module = Module::getInstanceByName('riccionepiadina');
                $idCustomer = $this->context->customer->id;
                $now = strtotime(date('Y-m-d'));
                if ($value < $now) {
                    $date = date('d/m/Y');
                } else {
                    $date = date('d/m/Y', $value);
                };
                $deliveryDate = $module->deliveryValidDate($idCustomer, $date);
                $fields[$id] = (string) $deliveryDate;
            }

            $res = $this->fieldsHandler->saveFieldValues($fields, $step, $id_cart);

            if (is_array($res)) {
                $response['success'] = false;
                $response['errors'][] = $res;
            } else {
                $response['success'] = (bool) $res;
            }

            return $response;
        } catch (\Exception $ex) {
            return ['success' => false, 'errors' => [$ex->getMessage()]];
        }
    }

    /**
     * @param $id_order
     *
     * @return array
     */
    public function getOrderFields($id_order)
    {
        $sql = 'SELECT a.`id_custom_field` as id, b.`field_name` as name, val.`field_value` as value, a.`field_type` as type
            FROM '._DB_PREFIX_.'fmm_custom_fields a
            INNER JOIN '._DB_PREFIX_.'fmm_custom_fields_lang b ON (a.id_custom_field = b.id_custom_field AND b.id_lang = '.(int) $this->context->language->id.')
            INNER JOIN '._DB_PREFIX_.'fmm_custom_userdata val ON (a.id_custom_field = val.id_custom_field)
            WHERE val.`id_order` = '.(int) $id_order.' AND a.`active` = 1
            ORDER BY a.`position` ASC';

        $fields = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$fields || !is_array($fields)) {
            return [];
        }

        foreach ($fields as &$field) {
            if (in_array($field['type'], ['multiselect', 'radio', 'checkbox', 'select'])) {
                $values = ($this->fieldsHandler->isSerialized($field['value'])) ? unserialize($field['value']) : $field['value'];
                $opts = [];
                if (isset($values) && is_array($values)) {
                    foreach ($values as $id_value) {
                        $opts[] = $this->fieldsHandler->getFieldsValueById($id_value);
                    }
                    $field['value'] = (isset($opts) && $opts && is_array($opts) ? implode(',', $opts) : '');
                } else {
                    $field['value'] = $this->fieldsHandler->getFieldsValueById($field['value']);
                }
            }
        }

        return $fields;
    }

    public function composeDeliveryMessage($id_cart)
    {
        $sql = 'SELECT field_value
            FROM '._DB_PREFIX_.'custom_field_userdata
            WHERE id_custom_field = 1 AND id_cart = '.(int) $id_cart.'
            ORDER BY `value_id` DESC';

        $date = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

        return $date;
    }
}
