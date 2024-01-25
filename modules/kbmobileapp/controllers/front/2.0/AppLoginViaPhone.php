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

class AppLoginViaPhone extends AppCore
{

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if (!Tools::getIsset('mobile_number') || !Tools::getIsset('country_code')) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Mobile Number or country code is not available.'), 
                'AppLoginViaPhone'
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
            $this->writeLog('Mobile Number or country code is not available.');
        } else {
            $user_data = array();
            $user_data['mobile_number'] = Tools::getValue('mobile_number');
            $user_data['country_code'] = urlencode(Tools::getValue('country_code'));
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
            if ($user_data['country_code'] != '' && $user_data['mobile_number'] != '') {
                $this->addUser($user_data);
            } else {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('Empty Mobile Number or Country Code.'), 
                    'AppLoginViaPhone'
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
                $this->writeLog('Empty Mobile Number or Country Code.');
            }
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
            $is_exist_mobile_number = $this->isMobileNumberExistLogin($user_data, $this->context->shop->id);
            if ($is_exist_mobile_number) {
                $customer_obj = new Customer();
                $customer = new Customer($is_exist_mobile_number);
                if (isset($customer->active) && !$customer->active) {
                    $this->content['status'] = 'failure';
                    $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false), 
                        $this->l('Your account isn\'t available at this time.'), 
                        'AppLoginViaPhone'
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
                    $this->writeLog('Account is not active');
                } else {
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
                    if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id)) {
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
                        $delivery_option = array(
                            $this->context->cart->id_address_delivery => $id_carrier . ',');
                        $this->context->cart->setDeliveryOption($delivery_option);
                    }
                    $this->context->cart->id_currency = $this->context->currency->id;
                    // changes made by vibhaas on 4th may 2021
                    // Set Guest ID. It will be used in the cart table which is important to restore the cart after the login as in the function Cart::lastNoneOrderedCart, id_guest !=0 is being checked. 
                if (empty($this->context->cookie->id_guest)) {
                        Guest::setNewGuest($this->context->cookie);
                    }
                    if (!empty($this->context->cookie->id_guest)) {
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
                        $this->l('User login successfully.'), 
                        'AppLoginViaPhone'
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
                }
            } else {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('This Mobile number is not linked with any account.'), 
                    'AppLogin'
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
                $this->writeLog('Invalid mobile number');
            }
        }
    }
}
