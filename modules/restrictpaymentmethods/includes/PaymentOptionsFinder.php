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

use PrestaShop\PrestaShop\Core\Payment\PaymentOptionFormDecorator;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use PrestaShopBundle\Service\Hook\HookFinder;
include_once(_PS_MODULE_DIR_.'restrictpaymentmethods/classes/Rpm.php');
class PaymentOptionsFinder extends PaymentOptionsFinderCore
{
    public function find() //getPaymentOptions()
    {
        $paymentOptions = array();
        // Payment options coming from intermediate, deprecated version of the Advanced API
        //$this->hookName = 'displayPaymentEU';
        //$rawDisplayPaymentEUOptions = parent::find();
        //$paymentOptions = array_map(
        //    array('PrestaShop\PrestaShop\Core\Payment\PaymentOption', 'convertLegacyOption'),
        //    $rawDisplayPaymentEUOptions
        //);

        // Advanced payment options coming from regular Advanced API
        $this->hookName = 'advancedPaymentOptions';
        $paymentOptions = array_merge($paymentOptions, parent::find());

        // Payment options coming from regular Advanced API
        $this->hookName = 'paymentOptions';
        $this->expectedInstanceClasses = array('PrestaShop\PrestaShop\Core\Payment\PaymentOption');
        $paymentOptions = array_merge($paymentOptions, parent::find());

        // Safety check
        foreach ($paymentOptions as $moduleName => $paymentOption) {
            if (!is_array($paymentOption)) {
                unset($paymentOptions[$moduleName]);
            }
        }
        $paymentOptions = $this->filterAllPaymentMethods($paymentOptions, null);
        
        return $paymentOptions;
    }
    
    public function getInstalledPaymentModules($id_product = null)
    {
        $refurb = array();
        $payments = Db::getInstance()->executeS('
		SELECT DISTINCT m.`id_module`, h.`id_hook`, m.`name`, hm.`position`
		FROM `'._DB_PREFIX_.'module` m
		LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`'
        .Shop::addSqlRestriction(false, 'hm').'
		LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
		INNER JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.id_shop='.(int)Context::getContext()->shop->id.')
		WHERE h.`name` = \'paymentOptions\'');
        if (!empty($payments)) {
            foreach($payments as $pm) {
                $refurb[$pm['name']] = $pm['name'];
            }
        }
        $paymentOptions = self::filterAllPaymentMethods($refurb, $id_product);
        if (!empty($paymentOptions)) {
            foreach($paymentOptions as &$method) {
                $method = Module::getInstanceByName($method);
            }
        }
        return $paymentOptions;
    }
    
    private function filterAllPaymentMethods($paymentOptions, $id_product)
    {
        //Module - RestrictPaymentMethods - FMM
        $rpm_status = Module::isEnabled('restrictpaymentmethods');
        if ($rpm_status == true) {
            $rpm = new Rpm;
            $context = Context::getContext();
            $id_shop = (int)$context->shop->id;
            $rules = $rpm->countActiveRules($id_shop);
            $get_count = (int)count($rules);
            //If found active rules
            if ($get_count > 0) {
                $id_cart = (int)$context->cart->id;
                foreach ($rules as $rule) {
                    $flag = false;
                    //1. check for product rule
                    $rule_products = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_product', 'restrictpaymentmethods_products');
                    if (!empty($rule_products) && (int)$rule['rpm_has_products'] > 0) {
                        foreach ($rule_products as $product) {
                            $qty = $rpm->getProductQuantity($product['id_product'], null, null, $id_cart);
                            if ((int)$qty > 0) {
                                $flag = true;
                            }
                        }
                    }
                    if ($flag == false) {
                        //2. check for category rule
                        $rule_categories = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_category', 'restrictpaymentmethods_categories');
                        $cart = new Cart($id_cart);
                        if (!empty($rule_categories) && (int)$rule['rpm_has_categories'] > 0) {
                            //Reset the array
                            foreach($rule_categories as $key => $category) {
                                $rule_categories[$key] = $category['id_category'];
                            }
                            $products = $cart->getProducts(true);
                            foreach ($products as $product) {
                                $product_categories = Product::getProductCategories($product['id_product']);
                                $result = array_intersect($rule_categories, $product_categories);
                                if (is_array($result) && !empty($result)) {
                                    $flag = true;
                                }
                            }
                        }
                    }
                    if ($flag == false) {
                        //3. check for range rule for cart amount
                        $rule_class = new Rpm($rule['id_restrictpaymentmethods']);
                        if ((int)$rule_class->range_max > 0 && (int)$rule['rpm_has_range'] > 0) {
                            $default_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
                            $active_currency = (int)$context->currency->id;
                            $use_tax_display = ((int)$rule_class->range_tax > 0) ? true : false;
                            if ((int)$rule_class->range_calc == 1 || (int)$rule_class->range_calc == 2) {
                                $type = Cart::ONLY_PRODUCTS;//1
                            }
                            else {
                                $type = Cart::BOTH;//3
                            }
                            $cart_total = $rpm->getTotalCart($id_cart, $use_tax_display, $type);
                            if ((int)$rule_class->range_calc == 2) {
                                $price = $rpm->getTotalCart($id_cart, $use_tax_display, Cart::ONLY_DISCOUNTS);
                                $cart_total = $cart_total - $price;
                            }
                            //Now do the conversion if the currency is not default one.
                            if ($default_currency != $active_currency) {
                                $currency_current = new Currency($active_currency);
                                $store_currency = new Currency($default_currency);
                                $cart_total = Tools::convertPriceFull($cart_total, $currency_current, $store_currency);
                            }
                            if ($cart_total >= $rule_class->range_min && $cart_total <= $rule_class->range_max) {
                                $flag = true;
                            }
                        }
                    }
                    if ($flag == false) {
                        //4. check Customer Group match
                        $id_customer = (int)$context->customer->id;
                        if ($id_customer > 0 && (int)$rule['rpm_has_groupBox'] > 0) {
                            $rule_customer_group = (int)$rpm->getCustomerQualification($rule['id_restrictpaymentmethods'], $id_customer);
                            if ($rule_customer_group > 0) {
                                $flag = true;
                            }
                        }
                    }
                    //5. Brands check
                    if ($flag == false) {
                        $rule_brands = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_brand', 'restrictpaymentmethods_brands');
                        if (!empty($rule_brands) && (int)$rule['rpm_has_brands'] > 0) {
                            $cart = new Cart($id_cart);
                            $products = $cart->getProducts(true);
                            //Reset the array
                            foreach($rule_brands as $key => $brand) {
                                $rule_brands[$key] = $brand['id_brand'];
                            }
                            foreach ($products as $product) {
                                if (is_array($product) && in_array($product['id_manufacturer'], $rule_brands)) {
                                    $flag = true;
                                }
                            }
                        }
                    }
                    //6. Suppliers check
                    if ($flag == false) {
                        $rule_supps = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_supplier', 'restrictpaymentmethods_suppliers');
                        if (!empty($rule_supps) && (int)$rule['rpm_has_suppliers'] > 0) {
                            $cart = new Cart($id_cart);
                            $products = $cart->getProducts(true);
                            //Reset the array
                            foreach($rule_supps as $supp) {
                                foreach ($products as $product) {
                                    $product_suppliers = $rpm->getProductSuppliers($product['id_product']);
                                    //Reset the array
                                    foreach($product_suppliers as $key => $supplier) {
                                        $product_suppliers[$key] = $supplier['id_supplier'];
                                    }
                                    if (in_array($supp['id_supplier'], $product_suppliers)) {
                                        $flag = true;
                                    }
                                }
                            }
                        }
                    }
                    //7. check address City match
                    if ($flag == false) {
                        $cart = new Cart($id_cart);
                        $rule_cities = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'name', 'restrictpaymentmethods_cities');
                        if (!empty($rule_cities) && (int)$rule['rpm_has_cities'] > 0 && $cart->id_address_delivery) {
                            $address = new Address($cart->id_address_delivery);
                            $find_needle = (int)$rpm->needleCheck('restrictpaymentmethods_cities', 'name', $address->city, $rule['id_restrictpaymentmethods']);
                            if ($find_needle > 0) {
                                $flag = true;
                            }
                        }
                    }
                    //8. check address ZipCodes match
                    if ($flag == false) {
                        $cart = new Cart($id_cart);
                        $rule_zips = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'code', 'restrictpaymentmethods_zips');
                        if (!empty($rule_zips) && (int)$rule['rpm_has_zips'] > 0 && $cart->id_address_delivery) {
                            $address = new Address($cart->id_address_delivery);
                            $find_needle = (int)$rpm->needleCheck('restrictpaymentmethods_zips', 'code', $address->postcode, $rule['id_restrictpaymentmethods']);
                            if ($find_needle > 0) {
                                $flag = true;
                            }
                        }
                    }
                    //9. check week days match
                    if ($flag == false) {
                        $rule_days = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_day', 'restrictpaymentmethods_days');
                        if (!empty($rule_days) && (int)$rule['rpm_has_days'] > 0) {
                            $today = (int)date('N');//starting from Monday = 1, it represents the numeric position of today
                            $find_needle = (int)$rpm->needleCheck('restrictpaymentmethods_days', 'id_day', $today, $rule['id_restrictpaymentmethods']);
                            if ($find_needle > 0) {
                                $flag = true;
                            }
                        }
                    }
                    //10.product page hook execution
                    if ($flag == false && $id_product && (int)$id_product > 0) {
                        $recrawlRuleProducts = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_product', 'restrictpaymentmethods_products');
                        if (!empty($recrawlRuleProducts) && (int)$rule['rpm_has_products'] > 0) {
                            foreach ($recrawlRuleProducts as $product) {
                                if ((int)$id_product === (int)$product['id_product']) {
                                    $flag = true;
                                }
                            }
                        }
                        if ($flag == false) {//check for category rules
                            $recrawlRuleCategories = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'id_category', 'restrictpaymentmethods_categories');
                            if (!empty($recrawlRuleCategories) && (int)$rule['rpm_has_categories'] > 0) {
                                //Reset the array
                                foreach($recrawlRuleCategories as $key => $category) {
                                    $recrawlRuleCategories[$key] = $category['id_category'];
                                }
                                $product_categories = Product::getProductCategories((int)$id_product);
                                $result = array_intersect($recrawlRuleCategories, $product_categories);
                                if (is_array($result) && !empty($result)) {
                                    $flag = true;
                                }
                            }
                        }
                    }
                    //Disable payments if rule matches
                    if ($flag == true) {
                        $payments = $rpm->getCollection($rule['id_restrictpaymentmethods'], 'name', 'restrictpaymentmethods_payment');
                        foreach ($payments as $payment) {
                            unset($paymentOptions[$payment['name']]);
                        }
                    }
                }
            }
        }
        return $paymentOptions;
    }
}
