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
 * API to update customer basic information
 */
use PrestaShop\PrestaShop\Core\Security\PasswordPolicyConfiguration;
require_once 'AppCore.php';

class AppUpdateProfile extends AppCore
{
    private $product = null;

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        $this->updateCustomerInfo();
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }


    /**
     * Update customer information
     *
     */
    public function updateCustomerInfo()
    {
        $user_data = Tools::getValue('personal_info', json_encode(array()));
        $user_data = json_decode($user_data);
        
        
        $email = Tools::getValue('email', '');
        if ($email && Validate::isEmail($email)) {
            if (Customer::customerExists(strip_tags($email))) {
                $customer_obj = new Customer();
                $customer_tmp = $customer_obj->getByEmail($email);

                $customer = new Customer($customer_tmp->id);
                $customer_obj = new Customer();
                if (!empty($user_data)) {
                    $authentication = $customer_obj->getByEmail(trim($email), trim($user_data->password));
                    if (!Validate::isName($user_data->first_name)) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('Invalid First Name.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('Invalid First Name.');
                    } elseif (!Validate::isName($user_data->last_name)) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('Invalid Last Name.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->content['message'] = "Invalid Last Name.";
                        $this->writeLog('Invalid Last Name.');
                    } elseif (!$user_data->password || !$authentication) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('Authentication failed.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('Authentication failed.');
                    } elseif (!empty($user_data->new_password)
                        && $user_data->new_password != $user_data->cnfrm_password) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('The password and confirmation do not match.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('The password and confirmation do not match.');
                    } elseif (_PS_VERSION_ < '8.0.0' && !empty($user_data->new_password) && !Validate::isPasswd($user_data->new_password)) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('Invalid Password.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('Invalid Password');
                    }elseif (_PS_VERSION_ >= '8.0.0' && !empty($user_data->new_password) && !Validate::isAcceptablePasswordLength($user_data->new_password)) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = sprintf(parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('Password must be between %s and %s characters long.'), 
                            'AppUpdateProfile'
                            ),
                                Configuration::get(PasswordPolicyConfiguration::CONFIGURATION_MINIMUM_LENGTH),
                                Configuration::get(PasswordPolicyConfiguration::CONFIGURATION_MAXIMUM_LENGTH)

                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('Invalid Password');
                    } elseif (!empty($user_data->new_password) && $user_data->new_password != $user_data->cnfrm_password) {
                        $this->content['status'] = 'failure';
                        $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), 
                            $this->l('The password and confirmation do not match.'), 
                            'AppUpdateProfile'
                        );
                        $this->content['session_data'] = (String) $this->context->cart->id;
                        $this->content['user_details'] = array(
                            'customer_id' => '0',
                            'firstname' => '',
                            'lastname' => '',
                            'mobile_number' => '',
                            'email' => '',
                            'wishlist_count' => 0,
                            'cart_count' => 0
                        );
                        $this->writeLog('The password and confirmation do not match.');
                    } else {
                        $customer->firstname = ucwords($user_data->first_name);
                        $customer->lastname = ucwords($user_data->last_name);
                        if (!empty($user_data->title)) {
                            $customer->id_gender = (int) $user_data->title;
                        }
                        if (!empty($user_data->new_password)) {
                            $passd = Tools::encrypt($user_data->new_password);
                            $customer->passwd = $passd;
                        }
                        if ($customer->update(true)) {
                            $this->context->customer = $customer;
                            $this->context->cookie->id_customer = (int) $customer->id;
                            $this->context->cookie->customer_lastname = $customer->lastname;
                            $this->context->cookie->customer_firstname = $customer->firstname;
                            $this->context->cookie->passwd = $customer->passwd;
                            $this->context->cookie->logged = 1;
                            $this->context->cookie->email = $customer->email;
                            $this->context->cookie->is_guest = $customer->is_guest;
                            $this->content['status'] = 'success';
                            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('Personal Information updated successfully.'), 
                                'AppUpdateProfile'
                            );
                            $this->content['session_data'] = (String) $this->context->cart->id;
                            $this->content['user_details'] = array(
                                'customer_id' => (String) $customer->id,
                                'firstname' => $customer->firstname,
                                'lastname' => $customer->lastname,
                                'mobile_number' => '',
                                'email' => $customer->email,
                                'wishlist_count' => $this->getWishListCount($customer->id),
                                'cart_count' => Cart::getNbProducts($this->context->cookie->id_cart)
                            );
                            $this->writeLog('Personal Information updated successfully.');
                        } else {
                            $this->content['status'] = 'failure';
                            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('The information cannot be updated.'), 
                                'AppUpdateProfile'
                            );
                            $this->content['session_data'] = (String) $this->context->cart->id;
                            $this->content['user_details'] = array(
                                'customer_id' => '0',
                                'firstname' => '',
                                'lastname' => '',
                                'mobile_number' => '',
                                'email' => '',
                                'wishlist_count' => 0,
                                'cart_count' => 0
                            );
                            $this->writeLog('The information cannot be updated.');
                        }
                    }
                } else {
                    $this->content['status'] = 'failure';
                    $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false), 
                        $this->l('Personal information is missing.'), 
                        'AppUpdateProfile'
                    );
                    $this->content['session_data'] = (String) $this->context->cart->id;
                    $this->content['user_details'] = array(
                        'customer_id' => '0',
                        'firstname' => '',
                        'lastname' => '',
                        'mobile_number' => '',
                        'email' => '',
                        'wishlist_count' => 0,
                        'cart_count' => 0
                    );
                    $this->writeLog('Personal information is missing.');
                }
            } else {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('Customer with this email is not exist.'), 
                    'AppUpdateProfile'
                );
                $this->content['session_data'] = (String) $this->context->cart->id;
                $this->content['user_details'] = array(
                    'customer_id' => '0',
                    'firstname' => '',
                    'lastname' => '',
                    'mobile_number' => '',
                    'email' => '',
                    'wishlist_count' => 0,
                    'cart_count' => 0
                );
                $this->writeLog('Customer with this email is not exist.');
            }
        } else {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Email address is missing or invalid.'), 
                'AppUpdateProfile'
            );
            $this->content['session_data'] = (String) $this->context->cart->id;
            $this->content['user_details'] = array(
                'customer_id' => '0',
                'firstname' => '',
                'lastname' => '',
                'mobile_number' => '',
                'email' => '',
                'wishlist_count' => 0,
                'cart_count' => 0
            );
            $this->writeLog('Email address is missing or invalid.');
        }
    }
}
