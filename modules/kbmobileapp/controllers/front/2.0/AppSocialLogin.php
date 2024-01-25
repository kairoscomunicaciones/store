<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to login customer from social options Google and Facebook
 */

require_once 'AppCore.php';

class AppSocialLogin extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        $user_data = Tools::getValue('login', json_encode(array()));
        $user_data = json_decode($user_data);
        $cart_id = Tools::getValue('session_data', '');

        if (!empty($cart_id)) {
            $this->context->cart->id_currency = $this->context->currency->id;
            /**
            * Passes language id while creating new cart
            * TGapr2023 LangId_cart
            * @date 28-04-2023
            * @modifier Tanisha Gupta
            */
            $lang_id = (int)Language::getIdByIso(Tools::getValue('iso_code', ''));
            $this->context->cart = new Cart($cart_id, $lang_id);
            $this->context->cookie->id_cart = (int) $this->context->cart->id;
            $this->context->cookie->write();
        }
        
        if (!empty($user_data)) {
            $this->addUser($user_data);
        } else {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Social data is not available.'), 
                'AppSocialLogin'
            );
            $this->content['session_data'] = (String) $this->context->cart->id;
            $this->content['login_user'] = array(
                'customer_id' => '0',
                'firstname' => '',
                'lastname' => '',
                'mobile_number' => '',
                'email' => '',
                'wishlist_count' => 0,
                'cart_count' => 0
            );
            $this->writeLog('Social data is not available.');
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }

    /**
     * Add the social user to store if it is not registered
     *
     * @param array $user_data user information
     */
    public function addUser($user_data)
    {
        if (!empty($user_data)) {
            if (Customer::customerExists(strip_tags($user_data->email))) {
                $customer_obj = new Customer();
                $customer_tmp = $customer_obj->getByEmail($user_data->email);

                $customer = new Customer($customer_tmp->id);

                //Update Context
                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int) $customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->logged = 1;
                $this->context->cookie->email = $customer->email;
                $this->context->cookie->is_guest = $customer->is_guest;

                // Change made by Vibhaas on 4th May 2022
                // Set Guest ID. It will be used in the cart table which is important to restore the cart after the login as in the function Cart::lastNoneOrderedCart, id_guest !=0 is being checked. 
                if(empty($this->context->cookie->id_guest)) {
                    Guest::setNewGuest($this->context->cookie);
                }
                // End of Change
                
                if (Configuration::get('PS_CART_FOLLOWING')
                    && (empty($this->context->cookie->id_cart)
                    || Cart::getNbProducts($this->context->cookie->id_cart) == 0)
                    && $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id)) {
                    /**
                    * Passes language id while creating new cart
                    * TGapr2023 LangId_cart
                    * @date 28-04-2023
                    * @modifier Tanisha Gupta
                    */
                    $lang_id = (int)Language::getIdByIso(Tools::getValue('iso_code', ''));
                    $this->context->cart = new Cart($id_cart, $lang_id);
                } else {
                    $id_carrier = (int) $this->context->cart->id_carrier;
                    if (!$this->context->cart->id_address_delivery) {
                        $this->context->cart->id_carrier = 0;
                        $this->context->cart->setDeliveryOption(null);
                        $this->context->cart->id_address_delivery = (int)
                                Address::getFirstCustomerAddressId((int) $customer->id);
                        $this->context->cart->id_address_invoice = (int)
                                Address::getFirstCustomerAddressId((int) $customer->id);
                    }
                }
                $this->context->cart->secure_key = $customer->secure_key;

                if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $delivery_option = array($this->context->cart->id_address_delivery => $id_carrier . ',');
                    $this->context->cart->setDeliveryOption($delivery_option);
                }
                $this->context->cart->id_currency = $this->context->currency->id;
                  // Change made by Vibhaas on 4th May 2022
                if(!empty($this->context->cookie->id_guest)) {
                    $this->context->cart->id_guest = $this->context->cookie->id_guest;
                }
                //changes over 
                $this->context->cart->save();
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();
                $wishlist_count = $this->getWishListCount($customer->id);
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('User login successfully'), 
                    'AppSocialLogin'
                );
                $this->content['session_data'] = (String) $this->context->cart->id;
                $this->content['login_user'] = array(
                    'customer_id' => (String) $customer->id,
                    'firstname' => $customer->firstname,
                    'lastname' => $customer->lastname,
                    'mobile_number' => '',
                    'email' => $customer->email,
                    'wishlist_count' => $wishlist_count,
                    'cart_count' => Cart::getNbProducts($this->context->cookie->id_cart)
                );
                $this->writeLog('User login successfully');
            } else {
                $col_query = 'SHOW COLUMNS FROM ' . _DB_PREFIX_ . 'customer LIKE "id_lang"';
                $col_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($col_query);
                if (count($col_result) == 1) {
                    $col_exist = 1;
                } else {
                    $col_exist = 0;
                }
                $user_data->gender = 0;
                $insertion_time = date('Y-m-d H:i:s', time());
                $original_passd = Tools::substr(md5(uniqid(mt_rand(), true)), 0, 8);
                $passd = Tools::encrypt($original_passd);
                $secure_key = md5(uniqid(rand(), true));
                $gender_qry = '(select id_gender from ' . _DB_PREFIX_ . 'gender '
                        . 'where type = ' . pSQL($user_data->gender) . ')';
                $gender = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($gender_qry);
                if (empty($gender)) {
                    $user_data->gender = 0;
                }
                /*changes made by vibhaas on 4th may 2021 to split the first name and last name on social login */
                if($user_data->last_name == '')
                {
                    $first_name = $user_data->first_name;
                    $delimiter = ' ';
                    $full_name = explode($delimiter, $first_name);
                   
                    if (count($full_name) > 1) {
                        $user_data->first_name = $full_name[0];
                        unset($full_name[0]);
                        $last_name = implode(' ', $full_name);
                        $user_data->last_name = $last_name;
                    } else {
                        $user_data->first_name = $full_name[0];
                        $user_data->last_name = '. ';
                    }
                }

                $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'customer SET 
                    id_shop_group = ' . (int) $this->context->shop->id_shop_group . ', 
                    id_shop = ' . (int) $this->context->shop->id . ', 
                    id_gender = ' . (int) $user_data->gender . ', 
                    id_default_group = ' . (int) Configuration::get('PS_CUSTOMER_GROUP') . ',';
                if ($col_exist == 1) {
                    $sql .= 'id_lang = ' . (int) $this->context->language->id . ',';
                }
                $sql .= 'id_risk = 0, 
                    firstname = "' . pSQL(strip_tags($user_data->first_name)) . '", 
                    lastname = "' . pSQL(strip_tags($user_data->last_name)) . '", 
                    email = "' . pSQL(strip_tags($user_data->email)) . '", 
                    passwd = "' . pSQL($passd) . '", 
                    
                    max_payment_days = 0, 
                    secure_key = "' . pSQL($secure_key) . '", 
                    active = 1, date_add = "' . pSQL($insertion_time) . '", date_upd = "' . pSQL($insertion_time) . '"';

                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                $id_customer = Db::getInstance(_PS_USE_SQL_SLAVE_)->Insert_ID();
                $customer = new Customer();
                $customer->id = $id_customer;
                $customer->firstname = ucwords($user_data->first_name);
                $customer->lastname = ucwords($user_data->last_name);
                $customer->passwd = $passd;
                $customer->email = $user_data->email;
                $customer->secure_key = $secure_key;
                $customer->birthday = '';
                $customer->is_guest = 0;
                $customer->active = 1;
                $customer->logged = 1;

                $customer->cleanGroups();
                $customer->addGroups(array((int) Configuration::get('PS_CUSTOMER_GROUP')));

                $this->sendConfirmationMail($customer, $original_passd);
                //Update Context
                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int) $customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->logged = 1;
                $this->context->cookie->email = $customer->email;
                $this->context->cookie->is_guest = $customer->is_guest;
                //Cart
                
                
                if (Configuration::get('PS_CART_FOLLOWING')
                    && (empty($this->context->cookie->id_cart)
                    || Cart::getNbProducts($this->context->cookie->id_cart) == 0)
                    && $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id)) {
                    /**
                    * Passes language id while creating new cart
                    * TGapr2023 LangId_cart
                    * @date 28-04-2023
                    * @modifier Tanisha Gupta
                    */
                    $lang_id = (int)Language::getIdByIso(Tools::getValue('iso_code', ''));
                    $this->context->cart = new Cart($id_cart, $lang_id);
                } else {
                    $id_carrier = (int) $this->context->cart->id_carrier;
                    if (!$this->context->cart->id_address_delivery) {
                        $this->context->cart->id_carrier = 0;
                        $this->context->cart->setDeliveryOption(null);
                        $this->context->cart->id_address_delivery = (int)
                                Address::getFirstCustomerAddressId((int) $customer->id);
                        $this->context->cart->id_address_invoice = (int)
                                Address::getFirstCustomerAddressId((int) $customer->id);
                    }
                }
                $this->context->cart->secure_key = $customer->secure_key;

                if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $delivery_option = array($this->context->cart->id_address_delivery => $id_carrier . ',');
                    $this->context->cart->setDeliveryOption($delivery_option);
                }
                $this->context->cart->id_currency = $this->context->currency->id;
                //changes made by vibhaas on 4th may 2022
                // Set Guest ID. It will be used in the cart table which is important to restore the cart after the login as in the function Cart::lastNoneOrderedCart, id_guest !=0 is being checked. 
                if(empty($this->context->cookie->id_guest)) {
                    Guest::setNewGuest($this->context->cookie);
                }
                if(!empty($this->context->cookie->id_guest)) {
                    $this->context->cart->id_guest = $this->context->cookie->id_guest;
                }
                $this->context->cart->save();
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();
                $wishlist_count = $this->getWishListCount($customer->id);
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('User login successfully'), 
                    'AppSocialLogin'
                );
                $this->content['session_data'] = (String) $this->context->cart->id;
                $this->content['login_user'] = array(
                    'customer_id' => (String) $customer->id,
                    'firstname' => $customer->firstname,
                    'lastname' => $customer->lastname,
                    'mobile_number' => '',
                    'email' => $customer->email,
                    'wishlist_count' => $wishlist_count,
                    'cart_count' => Cart::getNbProducts($this->context->cookie->id_cart)
                );
                $this->writeLog('Social User login successfully');
            }
        }
    }

    /**
     * Send confirmation mail after registration
     *
     * @param Object Customer $customer customer object
     * @param string $passd password of the customer
     * @return bool
     */
    protected function sendConfirmationMail($customer, $passd)
    {
        if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }

        return Mail::Send(
            $this->context->language->id,
            'account',
            Mail::l('Welcome!'),
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{passwd}' => $passd
            ),
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }
}
