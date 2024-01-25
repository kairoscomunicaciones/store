<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* @category  FMM Modules
* @package   restrictpaymentmethods
*/

class Rpm extends ObjectModel {
	public $title;
	public $active;
	public $start_date;
	public $expiry_date;
	public $range_min;
	public $range_max;
	public $range_tax;
	public $range_calc;
	public $rpm_has_categories;
	public $rpm_has_products;
	public $rpm_has_brands;
	public $rpm_has_suppliers;
	public $rpm_has_range;
	public $rpm_has_groupBox;
	public $rpm_has_cities;
	public $rpm_has_zips;
	public $rpm_has_days;
	
	public static $definition = array(
		'table' => 'restrictpaymentmethods',
		'primary' => 'id_restrictpaymentmethods',
		'multilang' => false,
		'fields' => array(
			'title' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => false, 'required' => false),
			'active' => array('type' => self::TYPE_BOOL),
			'rpm_has_categories' => array('type' => self::TYPE_BOOL),
			'rpm_has_products' => array('type' => self::TYPE_BOOL),
			'rpm_has_brands' => array('type' => self::TYPE_BOOL),
			'rpm_has_suppliers' => array('type' => self::TYPE_BOOL),
			'rpm_has_range' => array('type' => self::TYPE_BOOL),
			'rpm_has_groupBox' => array('type' => self::TYPE_BOOL),
			'rpm_has_cities' => array('type' => self::TYPE_BOOL),
			'rpm_has_zips' => array('type' => self::TYPE_BOOL),
			'rpm_has_days' => array('type' => self::TYPE_BOOL),
			'range_min' => array('type' => self::TYPE_INT),
			'range_max' => array('type' => self::TYPE_INT),
			'range_tax' => array('type' => self::TYPE_INT),
			'range_calc' => array('type' => self::TYPE_INT),
			'start_date' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
			'expiry_date' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
		),
	);
	
	public static function populateTable($table, $key, $id, $raw)
    {
		if (is_array($raw)) {
			foreach ($raw as $row) {
				Db::getInstance()->insert($table,
					array(
						'id_restrictpaymentmethods' => (int)$id,
						$key => $row)
					);
			}
		}
        $last_id = (int)Db::getInstance()->Insert_ID();
        return $last_id;
    }
	
	public static function needleCheck($table, $key, $id_key, $id_obj)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_restrictpaymentmethods`
            FROM `'._DB_PREFIX_.$table.'`
            WHERE `'.pSQL($key).'` = \''.pSQL($id_key).'\'
			AND `id_restrictpaymentmethods` = '.(int)$id_obj);
    }
	
	public static function getProductsCollection($id)
    {
		$sql = new DbQuery();
        $sql->select('`id_product`');
        $sql->from('restrictpaymentmethods_products');
		$sql->where('`id_restrictpaymentmethods` = '.(int)$id);
		return Db::getInstance()->executeS($sql);
	}
	
	public static function dumpCurrentData($id)
    {
		Db::getInstance()->delete('restrictpaymentmethods_payment', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_categories', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_products', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_group', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_brands', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_suppliers', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_cities', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_zips', 'id_restrictpaymentmethods = '.(int)$id);
		Db::getInstance()->delete('restrictpaymentmethods_days', 'id_restrictpaymentmethods = '.(int)$id);
	}
	
	public static function countActiveRules($id_shop)
    {
		$now = date('Y-m-d H:i:s');
        return Db::getInstance()->executeS('SELECT a.*
            FROM `'._DB_PREFIX_.'restrictpaymentmethods` a
			LEFT JOIN `'._DB_PREFIX_.'restrictpaymentmethods_shop` b
			ON (a.`id_restrictpaymentmethods` = b.`id_restrictpaymentmethods`)
            WHERE a.`active` > 0
			AND b.`id_shop` = '.(int)$id_shop.'
			AND
            (
                (a.`start_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' >= a.`start_date`)
                AND
                (a.`expiry_date` = \'0000-00-00 00:00:00\' OR \''.pSQL($now).'\' <= a.`expiry_date`)
            )');
    }
	
	public static function getCollection($id, $key, $table, $all = false)
    {
		$sql = new DbQuery();
		if ($all == true) {
			$sql->select($key);
		}
		else {
			$sql->select('`'.$key.'`');
		}
        $sql->from($table);
		$sql->where('`id_restrictpaymentmethods` = '.(int)$id);
		$return = Db::getInstance()->executeS($sql);
		return $return;
	}
	
	public static function getPmBunch($id)
    {
		$sql = new DbQuery();
        $sql->select('GROUP_CONCAT(pm.`name`) as payments');
        $sql->from('restrictpaymentmethods', 'a');
		$sql->leftJoin('restrictpaymentmethods_payment', 'pm', 'a.`id_restrictpaymentmethods` = pm.`id_restrictpaymentmethods`');
		$sql->where('pm.`id_restrictpaymentmethods` = '.(int)$id);
		$return = Db::getInstance()->executeS($sql);
		$return = end($return);
		return $return;
	}
	
	public function getProductQuantity($idProduct, $idProductAttribute = 0, $idCustomization = 0, $id_cart)
    {
        $productIsPack = Pack::isPack($idProduct);
        $defaultPackStockType = Configuration::get('PS_PACK_STOCK_TYPE');
        $packStockTypesAllowed = array(
            Pack::STOCK_TYPE_PRODUCTS_ONLY,
            Pack::STOCK_TYPE_PACK_BOTH,
        );
        $packStockTypesDefaultSupported = (int) in_array($defaultPackStockType, $packStockTypesAllowed);
        $firstUnionSql = 'SELECT cp.`quantity` as first_level_quantity, 0 as pack_quantity
          FROM `' . _DB_PREFIX_ . 'cart_product` cp';
        $secondUnionSql = 'SELECT 0 as first_level_quantity, cp.`quantity` * p.`quantity` as pack_quantity
          FROM `' . _DB_PREFIX_ . 'cart_product` cp' .
            ' JOIN `' . _DB_PREFIX_ . 'pack` p ON cp.`id_product` = p.`id_product_pack`' .
            ' JOIN `' . _DB_PREFIX_ . 'product` pr ON p.`id_product_pack` = pr.`id_product`';

        if ($idCustomization) {
            $customizationJoin = '
                LEFT JOIN `' . _DB_PREFIX_ . 'customization` c ON (
                    c.`id_product` = cp.`id_product`
                    AND c.`id_product_attribute` = cp.`id_product_attribute`
                )';
            $firstUnionSql .= $customizationJoin;
            $secondUnionSql .= $customizationJoin;
        }
        $commonWhere = '
            WHERE cp.`id_cart` = ' . (int)$id_cart;

        if ($idCustomization) {
            $commonWhere .= ' AND c.`id_customization` = ' . (int) $idCustomization;
        }
        $firstUnionSql .= $commonWhere;
        $firstUnionSql .= ' AND cp.`id_product` = ' . (int) $idProduct;
        $secondUnionSql .= $commonWhere;
        $secondUnionSql .= ' AND p.`id_product_item` = ' . (int) $idProduct;
        $secondUnionSql .= ' AND (pr.`pack_stock_type` IN (' . implode(',', $packStockTypesAllowed) . ') OR (
            pr.`pack_stock_type` = ' . Pack::STOCK_TYPE_DEFAULT . '
            AND ' . $packStockTypesDefaultSupported . ' = 1
        ))';
        $parentSql = 'SELECT
            COALESCE(SUM(first_level_quantity) + SUM(pack_quantity), 0) as deep_quantity,
            COALESCE(SUM(first_level_quantity), 0) as quantity
          FROM (' . $firstUnionSql . ' UNION ' . $secondUnionSql . ') as q';

        $return = Db::getInstance()->getRow($parentSql);
		return (int)$return['quantity'];
    }
	
	public static function getTotalCart($id_cart, $use_tax_display = false, $type = Cart::BOTH)
    {
        $cart = new Cart($id_cart);
        if (!Validate::isLoadedObject($cart)) {
            die(Tools::displayError());
        }

        $with_taxes = $use_tax_display;

        return $cart->getOrderTotal($with_taxes, $type);
    }
	
	public function containsProduct($id_product, $id_product_attribute = 0, $id_customization = 0, $id_cart)
    {
		$id_address_delivery = 0;
        $sql = 'SELECT cp.`quantity` FROM `'._DB_PREFIX_.'cart_product` cp';

        if ($id_customization) {
            $sql .= '
				LEFT JOIN `'._DB_PREFIX_.'customization` c ON (
					c.`id_product` = cp.`id_product`
					AND c.`id_product_attribute` = cp.`id_product_attribute`
				)';
        }

        $sql .= '
			WHERE cp.`id_product` = '.(int)$id_product.'
			AND cp.`id_cart` = '.(int)$id_cart;
        if (Configuration::get('PS_ALLOW_MULTISHIPPING')) {
            $sql .= ' AND cp.`id_address_delivery` = '.(int)$id_address_delivery;
        }

        if ($id_customization) {
            $sql .= ' AND c.`id_customization` = '.(int)$id_customization;
        }

        return Db::getInstance()->getRow($sql);
    }
	
	public static function getCustomerQualification($id, $id_customer)
    {
		$groups = Customer::getGroupsStatic($id_customer);
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_restrictpaymentmethods`)
        FROM `'._DB_PREFIX_.'restrictpaymentmethods_group`
		WHERE `id_group` IN ('.implode(',', array_map('intval', $groups)).')
		AND `id_restrictpaymentmethods` = '.(int)$id);
	}
	
	public static function getProductSuppliers($id)
    {
		$sql = new DbQuery();
        $sql->select('id_supplier');
        $sql->from('product_supplier');
		$sql->where('`id_product` = '.(int)$id);
		$sql->groupBy('`id_supplier`');
		$return = Db::getInstance()->executeS($sql);
		//$return = end($return);
		return $return;
	}
}