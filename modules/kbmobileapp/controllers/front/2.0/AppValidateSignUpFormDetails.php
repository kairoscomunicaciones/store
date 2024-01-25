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
 * API to validate User Data during the Signup Process
 */
use PrestaShop\PrestaShop\Core\Security\PasswordPolicyConfiguration;
require_once 'AppCore.php';

class AppValidateSignUpFormDetails extends AppCore
{

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if (!Validate::isName(Tools::getValue('firstname'))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Invalid First Name.'), 
                'AppRegisterUser'
            );
            $this->writeLog('Invalid First Name.');
        } elseif (!Validate::isName(Tools::getValue('lastname'))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Invalid Last Name.'), 
                'AppRegisterUser'
            );
            $this->writeLog('Invalid Last Name.');
        } elseif (!Validate::isEmail(Tools::getValue('email'))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Invalid email address.'), 
                'AppRegisterUser'
            );
            $this->writeLog('Invalid email address.');
        } elseif (Customer::customerExists(strip_tags(Tools::getValue('email')))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('An account using this email address has already been registered.'), 
                'AppRegisterUser'
            );
            $this->writeLog('Email address has already been registered.');
        } elseif (_PS_VERSION_ < '8.0.0' && !Validate::isPasswd(Tools::getValue('password'))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Invalid Password.'), 
                'AppRegisterUser'
            );
            $this->writeLog('Invalid Password.');
        }elseif (_PS_VERSION_ >= '8.0.0' && !Validate::isAcceptablePasswordLength(Tools::getValue('password'))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = sprintf(parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false), 
                $this->l('Password must be between %s and %s characters long.'), 
                'AppRegisterUser'
                ),
                    Configuration::get(PasswordPolicyConfiguration::CONFIGURATION_MINIMUM_LENGTH),
                    Configuration::get(PasswordPolicyConfiguration::CONFIGURATION_MAXIMUM_LENGTH)

            );
            $this->writeLog('Invalid Password.');
        }elseif (!Tools::getIsset('mobile_number') || !Tools::getIsset('country_code')) {
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Mobile Number or country code is not available.'),
                    'AppLoginViaPhone'
                ),
            );
            $this->writeLog('Mobile Number or country code is not available.');
        } else {
            $user_data = array();
            $user_data['mobile_number'] = Tools::getValue('mobile_number');
            $user_data['country_code'] = urlencode(Tools::getValue('country_code'));
            $session_data = Tools::getValue('session_data', '');
            $is_exist_mobile_number = $this->isMobileNumberExistLogin($user_data, $this->context->shop->id);
            if ($is_exist_mobile_number) {
                $this->content = array(
                    'status' => 'failure',
                    'message' => parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('Mobile number already exists into the database..'),
                        'AppCheckIfContactNumberExists'
                    ),
                );
                $this->writeLog('Mobile Number is not available.');
            } else {
                $this->content = array(
                    'status' => 'success',
                    'message' => "",
                );
                $this->writeLog('Signup Data is correct.');
            }
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
}
