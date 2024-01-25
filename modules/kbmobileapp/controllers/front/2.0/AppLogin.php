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
 * API to handle login action of customer
 * called from login page in APP
 */

require_once 'AppCore.php';

class AppLogin extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * Get email,password and session_data and logged in them after validation
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        
        $email = Tools::getValue('email', '');
        $password = Tools::getValue('password', '');
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
        if (empty($email)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('An email address required.'), 
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
            $this->writeLog('Email address not provided.');
        } elseif (!Validate::isEmail($email)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Invalid email address.'), 
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
            $this->writeLog('Invalid email address.');
        } elseif (empty($password)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Password is required.'), 
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
            $this->writeLog('Password is not provided.');
            /**
             * isPasswd method is removed in PS8 so added condition if ps version less than 8, only then check whether password is valid or not. Otherwise, not.
             * TGfeb2023 pass_validate
             * @date 17-02-2023
             * @author Tanisha Gupta 
             */
        } elseif (_PS_VERSION_ < '8.0.0' && !Validate::isPasswd($password)) {   
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('Invalid Password.'), 
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
                $this->writeLog('Invalid Password.');
        } else {
            $customer = new Customer();
            Hook::exec('actionBeforeAuthentication');
            $authentication = $customer->getByEmail(trim($email), trim($password));
            if (isset($authentication->active) && !$authentication->active) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('Your account isn\'t available at this time.'), 
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
                $this->writeLog('Account is not active');
            } elseif (!$authentication || !$customer->id) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('User details are not available in database.'), 
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
                $this->writeLog('Authentication failed.');
            } else {
                $this->context->cookie->id_customer = (int) ($customer->id);
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $customer->logged = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;

                // Add customer to the context
                $this->context->customer = $customer;
//
                if (Configuration::get('PS_CART_FOLLOWING') &&
                        (empty($this->context->cookie->id_cart) ||
                        Cart::getNbProducts($this->context->cookie->id_cart) == 0) &&
                        $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id)) {
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
                        $d_id = (int) Address::getFirstCustomerAddressId((int) ($customer->id));
                        $this->context->cart->id_address_delivery = $d_id;
                        $i_id = (int) Address::getFirstCustomerAddressId((int) ($customer->id));
                        $this->context->cart->id_address_invoice = $i_id;
                    }
                }
                $this->context->cart->id_customer = (int) $customer->id;
                $this->context->cart->secure_key = $customer->secure_key;

                if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $delivery_option = array($this->context->cart->id_address_delivery => $id_carrier . ',');
                    $this->context->cart->setDeliveryOption($delivery_option);
                }
                
                /*changes madse by vibhaas  on 4th may 2021*/
                // Set Guest ID. It will be used in the cart table which is important to restore the cart after the login as in the function Cart::lastNoneOrderedCart, id_guest !=0 is being checked. 
                if(empty($this->context->cookie->id_guest)) {
                    Guest::setNewGuest($this->context->cookie);
                }
                if(!empty($this->context->cookie->id_guest)) {
                    $this->context->cart->id_guest = $this->context->cookie->id_guest;
                }
                /*changes over */

                $this->context->cart->id_currency = $this->context->currency->id;
                $this->context->cart->save();
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();

                Hook::exec('actionAuthentication', array('customer' => $this->context->customer));
                $wishlist_count = $this->getWishListCount($customer->id);
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('User login successfully'), 
                    'AppLogin'
                );
                $this->content['session_data'] = (String) $this->context->cart->id;
                $this->content['login_user'] = array(
                    'customer_id' => $customer->id,
                    'firstname' => $customer->firstname,
                    'lastname' => $customer->lastname,
                    'mobile_number' => '',
                    'email' => $customer->email,
                    'wishlist_count' => $wishlist_count,
                    'cart_count' => Cart::getNbProducts($this->context->cookie->id_cart)
                );

                // Login information have changed, so we check if the cart rules still apply
                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);
            }
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
}
