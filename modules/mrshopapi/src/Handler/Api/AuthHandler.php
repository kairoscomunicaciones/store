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

namespace MrAPPs\MrShopApi\Handler\Api;

use Configuration;
use Context;
use Customer;
use Db;
use Exception;
use Gender;
use Guest;
use Hook;
use Mail;
use Module;
use MrAPPs\MrShopApi\Api\Front\CustomerWS;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Proxy\MrShopSocialHandlerProxy;
use MrAPPs\MrShopApi\Service\Crypto16Helper;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use PrestaShop\PrestaShop\Adapter\ServiceLocator;
use Shop;
use Tools;
use Validate;

class AuthHandler
{
    private $context = null;

    private $module = null;

    private $crypto = null;

    /** @var DataHandler $dataHandler */
    private $dataHandler = null;

    private function getCrypto()
    {
        if ($this->crypto == null) {
            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                $this->crypto = ServiceLocator::get('\\PrestaShop\\PrestaShop\\Core\\Crypto\\Hashing');
            } else {
                require_once _PS_MODULE_DIR_.'mrshopapi/src/Service/Crypto16Helper.php';

                $this->crypto = new Crypto16Helper();
            }
        }

        return $this->crypto;
    }

    public function __construct($dataHandler)
    {
        $this->dataHandler = $dataHandler;
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();
    }

    private function isSocialLoginModuleActive()
    {
        $module = Module::getInstanceByName('mrshopsociallogin');

        return Validate::isLoadedObject($module) && $module->active;
    }

    /**
     * Version > 5 user custom fields structure
     *
     * @param $parameters
     */
    public function register($parameters)
    {
        if (isset($parameters['provider']) && isset($parameters['identifier']) && !$this->isSocialLoginModuleActive()) {
            ResponseHandler::unauthorized($this->module->l('Module not installed', 'authhandler'));
        }

        $formattedFields = [];
        foreach ($parameters['fields'] as $field) {
            $formattedFields[$field['id']] = $field['value'];
        }

        /* FIELDS */
        $isGuest = (bool) !array_key_exists('passwd', $formattedFields);
        $firstname = ucwords(trim($formattedFields['firstname']));
        $lastname = ucwords(trim($formattedFields['lastname']));
        $email = trim($formattedFields['email']);
        $password = $isGuest ? 'random' : trim($formattedFields['passwd']);
        // optional for ps16
        $newsletter = isset($formattedFields['newsletter']) && (bool) $formattedFields['newsletter'];
        // optional
        $optin = isset($formattedFields['optin']) && (bool) $formattedFields['optin'];
        if (ApiUtils::isB2B()) {
            $company = trim($formattedFields['company']);
            $siret = trim($formattedFields['siret']);
            // only ps16
            $ape = isset($formattedFields['ape']) ? trim($formattedFields['ape']) : '';
            $website = isset($formattedFields['website']) ? trim($formattedFields['website']) : '';
        }

        /* CHECKS - FILTERS */
        if (Customer::customerExists($email, true)) {
            ResponseHandler::badRequest($this->module->l('Email address is already associated with an account.', 'authhandler'));
        }

        $hashedPassword = $this->getCrypto()->hash($password);

        /* CUSTOMER */
        $customer = new Customer();
        $customer->firstname = $firstname;
        $customer->lastname = $lastname;
        $customer->email = $email;
        $customer->passwd = $hashedPassword;
        $customer->newsletter = $newsletter;
        $customer->optin = $optin;
        $customer->is_guest = $isGuest;
        if (ApiUtils::isB2B()) {
            $customer->company = $company;
            $customer->siret = $siret;
            $customer->ape = $ape;
            $customer->website = $website;
        }

        if ($customer->newsletter) {
            $module_newsletter = Module::getInstanceByName('blocknewsletter');
            if (Validate::isLoadedObject($module_newsletter) && $module_newsletter->active) {
                $module_newsletter->confirmSubscription($customer->email);
            }
        }

        try {
            $customer->save();

            if ($isGuest) {
                $guest = new Guest($this->dataHandler->getGuestId());
                $guest->id_customer = $customer->id;
                $guest->save();
            }

            if (isset($parameters['provider']) && isset($parameters['identifier'])) {
                $this->socialRegister($parameters['identifier'], $customer->id, $parameters['provider']);
            }

            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                $this->context->updateCustomer($customer);
            }
            $this->context->cart->update();

            if (!Module::isEnabled('extendedregistration') &&
                !Module::isEnabled('validatecustomer')) {
                if (_PS_VERSION_ > '1.7') {
                    $_POST['password'] = $password;
                }

                Hook::exec('actionCustomerAccountAdd', [
                    '_POST' => [
                        'passwd' => $password,
                    ],
                    'executed_from_webservice' => true,
                    'newCustomer' => $customer,
                ]);
            }

            if (Module::isEnabled('acactiv')) {
                $this->manageAcactiv($customer);
            } elseif (Module::isEnabled('customeractivation')) {
                $this->manageCustomerActivation($customer);
            } elseif (Module::isEnabled('validatecustomer')) {
                $this->manageValidateCustomer($customer);
            } elseif (Module::isEnabled('confirmaccount')) {
                $this->manageConfirmAccount($customer);
            }

            $this->sendConfirmationMail($customer);
            ApiUtils::startCustomerSession((int) $customer->id);
            ResponseHandler::success(['id' => $customer->id]);
        } catch (Exception $ex) {
            error_log($ex);
            ResponseHandler::badRequest($this->module->l('There was an error while saving data', 'authhandler'));
        }
    }

    private function manageAcactiv($customer)
    {
        if (Configuration::get('ACACTIV_SENDTOME') != true) {
            $message = $this->module->l('We sent an email with account activation link to your email address.');
        } else {
            $message = $this->module->l('Your account is registered properly. Now we have to review it and manually activate. Thank you for your patience.');
        }

        ResponseHandler::success(['id' => $customer->id, 'needs_verification' => true, 'message' => $message]);
    }

    private function manageCustomerActivation($customer)
    {
        $message = $this->module->l('Your account is registered properly. Now we have to review it and manually activate. Thank you for your patience.');
        ResponseHandler::success(['id' => $customer->id, 'needs_verification' => true, 'message' => $message]);
    }

    private function manageValidateCustomer($customer)
    {
        $groups = Configuration::get('PS_MOD_VALCUS_GROUPS');
        $groups = explode(',', $groups);
        $needValidation = false;
        foreach ($groups as $id) {
            if ($customer->id_default_group == $id) {
                $needValidation = true;

                break;
            }
        }

        if ($needValidation) {
            $this->context->customer->mylogout();
            $customer->active = 0;
            $customer->update();
            Db::getInstance()->insert('customer_validate', ['id_customer' => (int) $customer->id]);
            if (Configuration::get('PS_MOD_VALCUS_SEND_REGMAIL')) {
                $emails = explode(',', Configuration::get('PS_MOD_VALCUS_EMAILS'));
                foreach ($emails as $email) {
                    // Send mail
                    Mail::Send(
                        Configuration::get('PS_LANG_DEFAULT'),
                        'new_reg',
                        Mail::l('A new customers has registered', Configuration::get('PS_LANG_DEFAULT')),
                        [
                            '{email}' => $customer->email,
                            '{shopname}' => $this->context->shop->name,
                        ],
                        $email,
                        $this->context->shop->name,
                        null,
                        $this->context->shop->name,
                        null,
                        null,
                        _PS_MODULE_DIR_.'validatecustomer/mails/'
                    );
                }
            }

            $message = $this->module->l('Thank you for registering. Your account must be approved by an admin before you can login', 'authhandler');
            ResponseHandler::success([
                'id' => $customer->id,
                'needs_verification' => true,
                'message' => $message,
            ]);
        }
    }

    private function manageConfirmAccount($customer)
    {
        $message = $this->module->l(
            'Welcome ! A message has been sent to you on your mailbox. Thank you to click on the link located there and then you can log in to your account',
            'authhandler'
        );
        ResponseHandler::success([
            'id' => $customer->id,
            'needs_verification' => true,
            'message' => $message
        ]);
    }

    public function login($email, $password, $idGuest = null, $currencyId = null)
    {
        if (!Validate::isEmail($email)) {
            ResponseHandler::badRequest($this->module->l('Invalid email address', 'authhandler'));
        }

        $customerId = (int) $this->customerExists($email);

        if (!$password || empty(trim($password))) {
            $password = '';
        }

        return $this->authenticateCustomer($customerId, $idGuest, $password, $currencyId);
    }

    public function refreshToken()
    {
        $token = isset($this->dataHandler->getBodyParams()['refresh_token'])
            ? $this->dataHandler->getBodyParams()['refresh_token']
            : null;
        if (empty($token)) {
            ResponseHandler::badRequest($this->module->l('Refresh token is required', 'authhandler'));
        }
        $jwt = new JwtHandler($this->module);
        $result = $jwt->verifyRefreshToken($token);

        if (false == $result['success']) {
            ResponseHandler::unauthorized($result['message']);
        }

        $jwt->invalidateRefreshToken($token);
        $customerId = (int) $result['data']['id'];

        if (!$this->customerExists(null, $customerId)) {
            ResponseHandler::unauthorized($result['message']);
        }

        return $this->authenticateCustomer($customerId);
    }

    private function checkCustomerActivationEnabled($customerId)
    {
        $sql = 'SELECT a.active AS `active` FROM `'._DB_PREFIX_.'librasoft_custactiv` a WHERE a.`id_customer` = '
            .(int) $customerId;
        $result = Db::getInstance()->getValue($sql);

        return (int) $result == 1 || $result === false;
    }

    public function customerExists($email = null, $customerId = null)
    {
        if ((empty($email) && empty($customerId)) && !Validate::isEmail($email)) {
            return false;
        }

        $result = Db::getInstance()->getValue('
        SELECT `id_customer`
        FROM `'._DB_PREFIX_.'customer`
        WHERE '.(!empty($email) ? '`email` = \''.pSQL($email).'\'' : 'id_customer = '.(int) $customerId).' '
            .Shop::addSqlRestriction(Shop::SHARE_CUSTOMER).
            ' AND `is_guest` = 0', false);

        return (int) $result;
    }

    public function authenticateCustomer($customerId, $idGuest = null, $password = null, $currencyId = null)
    {
        if ($customerId == 0) {
            ResponseHandler::badRequest($this->module->l('Customer not found', 'authhandler'));
        }

        if (Customer::isBanned($customerId) ||
            (Module::isEnabled('customeractivation') && !$this->checkCustomerActivationEnabled($customerId))) {
            ResponseHandler::unauthorized($this->module->l('Your account isn\'t available at this time, please contact us', 'authhandler'));
        }

        $customer = new Customer($customerId);

        if ($password !== null && !$this->getCrypto()->checkHash($password, $customer->passwd)) {
            ResponseHandler::unauthorized($this->module->l('Authentication failed', 'authhandler'));
        }

        if ($idGuest != null) {
            $guest = new Guest((int) $idGuest);
            if (!empty($guest->id_customer) && (int) $guest->id_customer != $customerId) {
                $idGuest = null;
            } else {
                $customer->id_guest = $idGuest;
            }
        }

        $token = null;
        $cart = null;

        try {
            if (empty($customer->id_guest)) {
                $guestId = Guest::getFromCustomer($customerId);
                if (!$guestId) {
                    $guest = new Guest();
                    $guest->id_customer = $customerId;
                    $guest->accept_language = Context::getContext()->language->iso_code;
                    $guest->save();
                    $guestId = $guest->id;
                }

                $customer->id_guest = $guestId;
            }

            $customer->save();

            $jwt = new JwtHandler($this->module);
            $token = $jwt->encode($customer);

            // Ultimo carrello dell'utente
            $cartHandler = new CartHandler($idGuest);
            $cart = $cartHandler->getCart($customer->id, true, $currencyId);

            $refreshToken = $jwt->encodeRefreshToken($customer);
        } catch (Exception $ex) {
            $refreshToken = null;
        }

        // Info utente
        $customerWs = new CustomerWS($this->dataHandler);
        $customerInfo = $customerWs->getCustomerInfo($customerId);
        ApiUtils::startCustomerSession((int) $customerId);

        return [
            'token' => $token,
            'refresh_token' => $refreshToken,
            'full_name' => trim($customer->firstname.' '.$customer->lastname),
            'cart_id' => ($cart !== null) ? $cart->id : 0,
            'user' => $customerInfo,
        ];
    }

    public function editPassword($customerId, $oldPassword, $newPassword)
    {
        $customer = new Customer((int) $customerId);
        if (!Validate::isLoadedObject($customer)) {
            ResponseHandler::badRequest($this->module->l('Customer not found', 'authhandler'));
        }

        if (!$this->getCrypto()->checkHash($oldPassword, $customer->passwd)) {
            ResponseHandler::badRequest($this->module->l('The old password is incorrect', 'authhandler'));
        }

        if (method_exists(Validate::class, 'isAcceptablePasswordLength')) {
            $checkPW = Validate::isAcceptablePasswordLength($newPassword);
        } elseif (method_exists(Validate::class, 'isPlaintextPassword')) {
            $checkPW = Validate::isPlaintextPassword($newPassword);
        } else {
            $checkPW = Validate::isPasswd($newPassword);
        }
        if (!$checkPW) {
            ResponseHandler::badRequest($this->module->l('Invalid password format', 'authhandler'));
        }

        $hashedPassword = $this->getCrypto()->hash($newPassword);
        $customer->passwd = $hashedPassword;

        try {
            $customer->update();
            ResponseHandler::success(['message' => $this->module->l('Password changed', 'authhandler')]);
        } catch (Exception $ex) {
            ResponseHandler::badRequest($this->module->l('There was an error while saving data', 'authhandler'));
        }
    }

    public function resetPassword($email)
    {
        if (Tools::strlen($email) == 0 || !Validate::isEmail($email)) {
            ResponseHandler::badRequest($this->module->l('Invalid email address', 'authhandler'));
        }

        $customer = new Customer();
        $customer->getByemail($email);

        if (!Validate::isLoadedObject($customer)) {
            ResponseHandler::badRequest($this->module->l('There is no account registered for this email address', 'authhandler'));
        }

        if (!$customer->active) {
            ResponseHandler::badRequest($this->module->l('You cannot regenerate the password for this account', 'authhandler'));
        }

        if ((strtotime($customer->last_passwd_gen.'+'.($min_time = (int) Configuration::get('PS_PASSWD_TIME_FRONT')).' minutes') - time()) > 0) {
            ResponseHandler::badRequest(
                sprintf('%s %s %s', $this->module->l('You can regenerate your password only every', 'authhandler'), (int) $min_time, $this->module->l('minutes', 'authhandler'))
            );
        }

        if (!$customer->hasRecentResetPasswordToken()) {
            $customer->stampResetPasswordToken();
            $customer->update();
        }

        $urlRequest = 'token='.$customer->secure_key.'&id_customer='.(int) $customer->id;
        if (!ApiUtils::isPS16()) {
            $urlRequest .= '&reset_token='.$customer->reset_password_token;
        }

        // Invio email
        $mail_params = [
            '{email}' => $customer->email,
            '{lastname}' => $customer->lastname,
            '{firstname}' => $customer->firstname,
            '{url}' => $this->context->link->getPageLink(
                'password',
                true,
                null,
                $urlRequest
            ),
        ];

        if (!Mail::Send(
            $this->context->language->id,
            'password_query',
            Mail::l('Password query confirmation'),
            $mail_params,
            $customer->email,
            $customer->firstname.' '.$customer->lastname
        )) {
            ResponseHandler::badRequest($this->module->l('An error occurred while sending the email', 'authhandler'));
        }

        $customer->last_passwd_gen = date('Y-m-d H:i:s', time());
        $customer->save();

        ResponseHandler::success([
            'message' => $this->module->l('An email has been sent for password reset', 'authhandler'),
        ]);
    }

    public function socialLogin($params, $idGuest)
    {
        if (!$this->isSocialLoginModuleActive()) {
            ResponseHandler::unauthorized($this->module->l('Module not installed', 'authhandler'));
        }

        $response = call_user_func(MrShopSocialHandlerProxy::class.'::getUser', null, $params['provider'], $params['token'], true);

        if ($response['success']) {
            if ($response['action'] === 'login') {
                $customerId = (int) $response['id_customer'];

                return $this->authenticateCustomer($customerId, $idGuest, null, null);
            } else {
                $data = [
                    'customer' => $response['user'],
                ];

                if (array_key_exists('access_token', $response)) {
                    $data['access_token'] = $response['access_token'];
                }

                return $data;
            }
        } else {
            ResponseHandler::unauthorized($response['error'], 'authhandler');
        }
    }

    public function socialRegister($identifier, $customerId, $provider)
    {
        require_once _PS_MODULE_DIR_.'mrshopsociallogin/classes/MrShopSocialCustomer.php';

        return call_user_func('MrShopSocialCustomer::createCustomer', $identifier, $customerId, $provider);
    }

    private function sendConfirmationMail(Customer $customer)
    {
        if ($customer->is_guest || !Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }

        return Mail::Send(
            $this->context->language->id,
            'account',
            $this->module->l('Welcome!', 'authhandler'),
            [
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
            ],
            $customer->email,
            $customer->firstname.' '.$customer->lastname
        );
    }
}
