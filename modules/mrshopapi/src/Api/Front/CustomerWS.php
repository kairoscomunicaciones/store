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

namespace MrAPPs\MrShopApi\Api\Front;

use Configuration;
use Context;
use Customer;
use CustomerThread;
use Db;
use Exception;
use Gender;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\AuthHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\CustomFormUtils;
use Shop;
use Tools;
use Validate;

class CustomerWS extends BaseWS
{
    /**@var AuthHandler $authHandler*/
    private $authHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->authHandler = new AuthHandler($dataHandler);
        $this->cacheEnabled = false;
    }

    public function getDetail($id)
    {
        $data = $this->getCustomerInfo($id);
        if ($data !== false) {
            $this->response(true, null, $data);
        } else {
            ResponseHandler::notFound($this->module->l('Customer not found', 'customerws'));
        }
    }

    /**
     * Version > 5 use custom fields structure
     *
     * @param $params
     * @param $id
     */
    public function updateOrCreate($params, $id)
    {
        if (!$params || count($params) === 0) {
            $this->response(false, $this->module->l('Invalid parameters', 'customerws'), null);
        }

        $customer = new \Customer((int) $id);
        if (!Validate::isLoadedObject($customer)) {
            $this->response(false, $this->module->l('Customer not found', 'customerws'), null);
        }

        if ($this->dataHandler->getApiVersion() > 5) {
            $formattedFields = [];
            foreach ($params['fields'] as $field) {
                $formattedFields[$field['id']] = $field['value'];
            }

            /* FIELDS */
            $firstname = ucwords(trim($formattedFields['firstname']));
            $lastname = ucwords(trim($formattedFields['lastname']));
            $email = trim($formattedFields['email']);
            if (!Validate::isEmail($email)) {
                ResponseHandler::badRequest($this->module->l('Email address is not valid', 'customerws'));
            }

            $newsletter = false;
            if (!ApiUtils::isPS16() || (ApiUtils::isPS16() && (bool) Configuration::get('PS_CUSTOMER_NWSL'))) {
                $newsletter = (bool) $formattedFields['newsletter'];
            }

            $optin = false;
            if ((bool) Configuration::get('PS_CUSTOMER_OPTIN')) {
                $optin = (bool) $formattedFields['optin'];
            }

            if (ApiUtils::isB2B()) {
                $company = trim($formattedFields['company']);
                $siret = trim($formattedFields['siret']);
                $ape = isset($formattedFields['ape']) ? trim($formattedFields['ape']) : '';
                $website = isset($formattedFields['website']) ? trim($formattedFields['website']) : '';
            }

            $customer->firstname = $firstname;
            $customer->lastname = $lastname;
            $customer->email = $email;
            $customer->newsletter = $newsletter;
            $customer->optin = $optin;
            if (ApiUtils::isB2B()) {
                $customer->company = $company;
                $customer->siret = $siret;
                $customer->ape = $ape;
                $customer->website = $website;
            }
        } else {
            if (array_key_exists('firstname', $params)) {
                $customer->firstname = ucwords(trim($params['firstname']));
            }

            if (array_key_exists('lastname', $params)) {
                $customer->lastname = ucwords(trim($params['lastname']));
            }

            if (array_key_exists('email', $params)) {
                $email = trim($params['email']);

                if (!Validate::isEmail($email)) {
                    ResponseHandler::badRequest($this->module->l('Email address is not valid', 'customerws'));
                }

                $customer->email = $email;
            }

            $genders = Gender::getGenders();
            if ($genders->count() > 0) {
                if (array_key_exists('gender', $params)) {
                    $genderId = (int) $params['gender'];
                    $gender = new Gender($genderId);
                    if (!Validate::isLoadedObject($gender)) {
                        $gender = ($genders->count() > 0) ? $genders->getFirst() : null;
                    }

                    if (is_null($gender)) {
                        ResponseHandler::badRequest($this->module->l('Invalid gender', 'customerws'));
                    }

                    $customer->id_gender = $genderId;
                }
            }

            if (array_key_exists('newsletter', $params)) {
                $customer->newsletter = (bool) $params['newsletter'];
            }

            if (array_key_exists('newsletter_offers', $params)) {
                $customer->optin = (bool) $params['newsletter_offers'];
            }
        }

        try {
            if ($customer->save()) {
                ResponseHandler::successfulEmptyResponse();
            }
        } catch (Exception $exception) {
            $this->response(false, $this->module->l('There was an error while saving data', 'customerws'), null);
        }
    }

    public function deleteCustomerGdpr($customerId)
    {
        $gdprModule = Module::getInstanceByName('psgdpr');
        $customer = new Customer((int) $customerId);
        if (!Validate::isLoadedObject($customer)) {
            $this->response(false, $this->module->l('Customer does not exists', 'customerws'), null);
        }

        if (!ApiUtils::isGdprEnabled($gdprModule)) {
            $queries = [];

            // assign cart to an anonymous account in order to keep stats
            $queries[] = 'UPDATE `'._DB_PREFIX_.'cart` SET id_customer = '.(int) Configuration::get('MRSHOP_ANONYMOUS_CUSTOMER').',
            id_address_delivery = '.(int) Configuration::get('MRSHOP_ANONYMOUS_ADDRESS').',
            id_address_invoice = '.(int) Configuration::get('MRSHOP_ANONYMOUS_ADDRESS').'
            WHERE id_customer = '.(int) $customer->id;

            // delete cart rule associated to the customer
            $queries[] = 'DELETE FROM `'._DB_PREFIX_.'cart_rule` WHERE id_customer = '.(int) $customer->id;

            // delete specific price belong to the customer
            $queries[] = 'DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE id_customer = '.(int) $customer->id;

            // delete message send by the customer
            $queries[] = 'DELETE FROM `'._DB_PREFIX_.'message` WHERE id_customer = '.(int) $customer->id;

            // delete all messages send by the customer
            $customerMessages = CustomerThread::getCustomerMessages($customer->id);
            foreach ($customerMessages as $message) {
                $queries[] = 'DELETE FROM `'._DB_PREFIX_.'customer_message` WHERE id_customer_message = '.(int) $message['id_customer_message'];
            }
            $queries[] = 'DELETE FROM `'._DB_PREFIX_.'customer_thread` WHERE id_customer = '.(int) $customer->id;

            foreach ($queries as $query) {
                if (Db::getInstance()->execute($query) === false) {
                    return false;
                }
            }

            $customer->delete();
        } else {
            require_once _PS_MODULE_DIR_.'psgdpr/psgdpr.php';
            $ctrl = new \Psgdpr();
            $ctrl->module = $gdprModule;
            $ctrl->deleteCustomer('customer', $customer->id);
        }

        ResponseHandler::successfulEmptyResponse();
    }

    /**
     * Version > 5 use custom fields structure.
     *
     * @param $customerId
     *
     * @return array|array[]|bool|null
     */
    public function getCustomerInfo($customerId)
    {
        $query = 'SELECT id_customer AS id, id_default_group, id_lang, firstname, lastname, email, id_gender AS gender, newsletter, optin
            FROM '._DB_PREFIX_.'customer
            WHERE active = 1 AND id_customer ='.(int) $customerId.' '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER);
        
        $data = $this->refactorData($query, false);

        if ($this->dataHandler->getApiVersion() > 5) {
            if (ApiUtils::isB2B()) {
                $b2bQuery = 'SELECT company, siret, ape, website 
                FROM '._DB_PREFIX_.'customer
                WHERE active = 1 AND id_customer ='.(int) $customerId;
                $b2bData = $this->refactorData($b2bQuery, false);
                $data = array_merge($data, $b2bData);
            }
            // $fields = $this->authHandler->getRegistrationFields(['passwd']);
            $fields = CustomFormUtils::getRegistrationFields(['passwd']);
            foreach ($fields as &$field) {
                $field['default'] = $field['type'] == 'boolean' ? (bool) $data[$field['id']] : $data[$field['id']];
            }
            $data = [
                'id' => $data['id'],
                'id_default_group' => $data['id_default_group'],
                'id_lang' => $data['id_lang'],
                'fields' => $fields,
            ];
        } else {
            $data['newsletter'] = (bool) $data['newsletter'];
            $data['newsletter_offers'] = (bool) $data['optin'];
            unset($data['optin']);
            $data['gender'] = (int) $data['gender'];
        }

        return $data;
    }

    public function signup()
    {
        $this->authHandler->register($this->dataHandler->getBodyParams());
    }

    public function login()
    {
        $result = $this->authHandler->login(
            str_replace(' ', '', Tools::getValue('username')),
            str_replace(' ', '', Tools::getValue('password')),
            $this->dataHandler->getGuestId(),
            $this->dataHandler->getCurrencyId()
        );
        $this->response(true, null, $result);
    }

    public function socialLogin()
    {
        $result = $this->authHandler->socialLogin($this->dataHandler->getBodyParams(), $this->dataHandler->getGuestId());
        $this->response(true, null, $result);
    }

    public function resetPassword()
    {
        $this->authHandler->resetPassword(Tools::getValue('email'));
    }

    public function editPassword($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();

        $oldPwd = array_key_exists('old_password', $bodyParams) ? $bodyParams['old_password'] : Tools::getValue('old_password');
        $newPwd = array_key_exists('new_password', $bodyParams) ? $bodyParams['new_password'] : Tools::getValue('new_password');

        $this->authHandler->editPassword($customerId, $oldPwd, $newPwd);
    }

    public function refreshToken()
    {
        $result = $this->authHandler->refreshToken();
        $this->response(200, null, $result);
    }

    public function exportGdprProfile()
    {
        $gdprModule = Module::getInstanceByName('psgdpr');
        if (!ApiUtils::isGdprEnabled($gdprModule)) {
            ResponseHandler::badRequest('GDPR module not found or disabled.');
        }

        $customer = Context::getContext()->customer;

        $output = Tools::strtolower(trim(Tools::getValue('output')));
        switch ($output) {
            case 'pdf':
                require_once _PS_MODULE_DIR_.'psgdpr/controllers/front/ExportDataToPdf.php';
                $ctrl = new \psgdprExportDataToPdfModuleFrontController();
                $ctrl->module = $gdprModule;
                $ctrl->exportDataToPdf($customer->id);
                exit();

            case 'csv':
                require_once _PS_MODULE_DIR_.'psgdpr/controllers/front/ExportDataToCsv.php';
                $ctrl = new \psgdprExportDataToCsvModuleFrontController();
                $ctrl->module = $gdprModule;
                $ctrl->exportDataToCsv($customer->id);
                exit();

            default:
                ResponseHandler::badRequest('Output type not supported.');

                break;
        }
    }
}
