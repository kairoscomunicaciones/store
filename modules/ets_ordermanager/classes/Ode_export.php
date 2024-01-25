<?php
/**
 * 2007-2022 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author ETS-Soft <etssoft.jsc@gmail.com>
 * @copyright  2007-2022 ETS-Soft
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

if (!defined('_PS_VERSION_'))
    exit;

class Ode_export extends ObjectModel
{
    public $id_ets_export_order_rule;
    public $exported_fields;
    public $date_type;
    public $from_date;
    public $to_date;
    public $day_before;
    public $order_status;
    public $customer_group;
    public $specific_customer;
    public $specific_order;
    public $id_country;
    public $id_state;
    public $order_carrier;
    public $payment_method;
    public $manufacturer;
    public $supplier;
    public $category;
    public $specific_product;
    public $spent_from;
    public $spent_to;
    public $order_total;
    public $file_format;
    public $file_name_prefix;
    public $file_name_incl_name_rule;
    public $sort_by;
    public $convert_in_currency;
    public $receivers_mail;
    public $position;
    public $name;
    public $title_mail;
    public $description_mail;
    public $send_file_via_email;
    public $send_file_schedule;
    public $send_file_time;
    public $send_file_time_hours;
    public $send_file_time_weeks;
    public $send_file_time_months;
    public $send_file_date;
    public $send_file_filter;
    public $export_to_server1;
    public $directory_path1;
    public $server1_schedule;
    public $server1_time;
    public $server1_filter;
    public $server1_time_hours;
    public $server1_time_weeks;
    public $server1_time_months;
    public $server1_date;
    public $export_to_server2;
    public $global_ftp;
    public $host;
    public $username;
    public $password;
    public $port;
    public $directory_path2;
    public $server2_schedule;
    public $server2_time;
    public $server2_filter;
    public $server2_time_hours;
    public $server2_time_weeks;
    public $server2_time_months;
    public $server2_date;
    public $delete_exported_files;
    public static $definition = array(
        'table' => 'ets_export_order_rule',
        'primary' => 'id_ets_export_order_rule',
        'multilang' => true,
        'fields' => array(
            'exported_fields' => array('type' => self::TYPE_STRING),
            'date_type' => array('type' => self::TYPE_STRING),
            'from_date' => array('type' => self::TYPE_STRING),
            'to_date' => array('type' => self::TYPE_STRING),
            'day_before' => array('type' => self::TYPE_STRING),
            'order_status' => array('type' => self::TYPE_STRING),
            'send_file_via_email' => array('type' => self::TYPE_STRING),
            'customer_group' => array('type' => self::TYPE_STRING),
            'specific_customer' => array('type' => self::TYPE_STRING),
            'specific_order' => array('type' => self::TYPE_STRING),
            'id_country' => array('type' => self::TYPE_STRING),
            'id_state' => array('type' => self::TYPE_INT),
            'order_carrier' => array('type' => self::TYPE_STRING),
            'payment_method' => array('type' => self::TYPE_STRING),
            'manufacturer' => array('type' => self::TYPE_STRING),
            'supplier' => array('type' => self::TYPE_STRING),
            'category' => array('type' => self::TYPE_STRING),
            'specific_product' => array('type' => self::TYPE_STRING),
            'spent_from' => array('type' => self::TYPE_STRING),
            'spent_to' => array('type' => self::TYPE_STRING),
            'file_format' => array('type' => self::TYPE_STRING),
            'file_name_prefix' => array('type' => self::TYPE_STRING),
            'file_name_incl_name_rule' => array('type'=>self::TYPE_INT),
            'sort_by' => array('type' => self::TYPE_STRING),
            'convert_in_currency' => array('type'=>self::TYPE_INT),
            'receivers_mail' => array('type' => self::TYPE_STRING),
            'position' => array('type' => self::TYPE_INT),
            'title_mail' => array('type' => self::TYPE_STRING,'lang'=>true),
            'description_mail' => array('type' => self::TYPE_STRING,'lang'=>true),

            'send_file_schedule' => array('type' => self::TYPE_STRING),
            'send_file_time_hours' => array('type' => self::TYPE_STRING),
            'send_file_time_weeks' => array('type' => self::TYPE_STRING),
            'send_file_time_months' => array('type' => self::TYPE_STRING),
            'send_file_date' => array('type' => self::TYPE_DATE),

            'export_to_server1' => array('type' => self::TYPE_BOOL),
            'directory_path1' => array('type' => self::TYPE_STRING),
            'server1_schedule' => array('type' => self::TYPE_STRING),
            'server1_time_hours' => array('type' => self::TYPE_STRING),
            'server1_time_weeks' => array('type' => self::TYPE_STRING),
            'server1_time_months' => array('type' => self::TYPE_STRING),
            'server1_date' => array('type' => self::TYPE_DATE),

            'export_to_server2' => array('type' => self::TYPE_BOOL),
            'global_ftp' => array('type' => self::TYPE_BOOL),
            'host' => array('type' => self::TYPE_STRING),
            'username' => array('type' => self::TYPE_STRING),
            'port' => array('type' => self::TYPE_STRING),
            'password' => array('type' => self::TYPE_STRING),
            'directory_path2' => array('type' => self::TYPE_STRING),
            'server2_schedule' => array('type' => self::TYPE_STRING),
            'server2_time_hours' => array('type' => self::TYPE_STRING),
            'server2_time_weeks' => array('type' => self::TYPE_STRING),
            'server2_time_months' => array('type' => self::TYPE_STRING),
            'server2_date' => array('type' => self::TYPE_DATE),

            'delete_exported_files' => array('type' => self::TYPE_STRING),
            'send_file_filter' => array('type' => self::TYPE_STRING),
            'server1_filter' => array('type' => self::TYPE_STRING),
            'server2_filter' => array('type' => self::TYPE_STRING),
            //Lang fields
            'name' => array('type' => self::TYPE_STRING, 'lang' => true),
        )
    );
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        if($this->exported_fields)
        {
            $fields = explode(',',$this->exported_fields);
            $orderMangage = Module::getInstanceByName('ets_ordermanager');
            foreach($fields as $key=> $field)
                if(!$orderMangage->getField($field))
                    unset($fields[$key]);
            $this->exported_fields = implode(',',$fields);
        }
        //if($this->file_format=='xlsx')
//            $this->file_format='xls';
    }
    public static function getExports($context)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        return Db::getInstance()->executeS('
            SELECT oe.id_ets_export_order_rule as `id`, oel.name
            FROM `' . _DB_PREFIX_ . 'ets_export_order_rule` oe
            LEFT JOIN `' . _DB_PREFIX_ . 'ets_export_order_rule_lang` oel ON (oe.id_ets_export_order_rule = oel.id_ets_export_order_rule AND oel.id_lang = ' . (int)$context->language->id . ')
            INNER JOIN `' . _DB_PREFIX_ . 'ets_export_order_rule_shop` oes ON (oes.id_ets_export_order_rule = oe.id_ets_export_order_rule)
            WHERE oes.id_shop = '.(int)$context->shop->id.'
        ');
    }

    public function add($auto_date = true, $null_values = false)
    {
        $res = parent::add($auto_date, $null_values);
        if ($this->id) {
            $res &= Db::getInstance()->execute('
                INSERT INTO `' . _DB_PREFIX_ . 'ets_export_order_rule_shop` (`id_ets_export_order_rule`, `id_shop`)
                VALUES(' . (int)$this->id . ', ' . (int)Context::getContext()->shop->id . ')'
            );
        }
        return $res;
    }

    public function delete()
    {
        if (($res = parent::delete())) {
            $res &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'ets_export_order_rule_shop` WHERE `id_ets_export_order_rule` = '.(int)$this->id);
        }
        return $res;
    }
}