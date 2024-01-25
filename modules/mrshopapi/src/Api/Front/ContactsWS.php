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

use AddressFormat;
use Configuration;
use Contact;
use Customer;
use CustomerThread;
use Mail;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use Order;
use Tools;
use Validate;

class ContactsWS extends BaseWS
{
    public function getList($params, $customerId)
    {
        $result = [
            'file_upload' => (bool) Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD'),
            'contacts' => [],
        ];

        $address = $this->context->shop->getAddress();

        $phone = Configuration::get('PS_SHOP_PHONE', false);

        if (!$phone || empty($phone)) {
            $phone = null;
        }

        $fax = Configuration::get('PS_SHOP_FAX', false);

        if (!$fax || empty($fax)) {
            $fax = null;
        }

        $email = Configuration::get('PS_SHOP_EMAIL', false);

        if (!$email || empty($email)) {
            $email = null;
        }

        $result['info'] = [
            'address' => AddressFormat::generateAddress($address),
            'phone' => $phone,
            'fax' => $fax,
            'email' => $email,
        ];

        $contacts = Contact::getContacts($this->context->language->id);
        foreach ($contacts as $contact) {
            $result['contacts'][] = [
                'email' => $contact['email'],
                'id' => (int) $contact['id_contact'],
                'name' => $contact['name'],
                'description' => $contact['description'],
            ];
        }

        $this->response(true, null, $result);
    }

    public function updateOrCreate($params, $id, $customerId, $module)
    {
        $file_attachment = Tools::fileAttachment('file');

        $customer = null;
        if ($customerId) {
            $customer = new Customer($customerId);

            if (!Validate::isLoadedObject($customer)) {
                ResponseHandler::error('Customer not found');
            }
        }

        $error = $this->validateParams($params, $module);

        if ($error) {
            ResponseHandler::error($error);
        } else {
            $contact = null;
            $contacts = Contact::getContacts($this->context->language->id);
            foreach ($contacts as $item) {
                if ((int) $item['id_contact'] === (int) $params['id_contact']) {
                    $contact = (object) $item;
                }
            }

            if ($contact == null) {
                ResponseHandler::error($module->l('Contact not found', 'contactsws'));
            }

            $from = trim($params['email']);
            $message = $params['message'];

            $id_order = array_key_exists('id_order', $params) ? (int) $params['id_order'] : null;

            $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($from, $id_order);
            if ($contact->customer_service) {
                if ((int) $id_customer_thread) {
                    $ct = new CustomerThread($id_customer_thread);
                    $ct->status = 'open';
                    $ct->id_lang = (int) $this->context->language->id;
                    $ct->id_contact = (int) $contact->id_contact;
                    $ct->id_order = (int) $id_order;
                    $ct->update();
                } else {
                    $ct = new CustomerThread();
                    if (isset($customer)) {
                        $ct->id_customer = (int) $customer->id;
                    }
                    $ct->id_shop = (int) $this->context->shop->id;
                    $ct->id_order = (int) $id_order;
                    $ct->id_contact = (int) $contact->id_contact;
                    $ct->id_lang = (int) $this->context->language->id;
                    $ct->email = $from;
                    $ct->status = 'open';
                    $ct->token = Tools::passwdGen(12);
                    $ct->add();
                }
            }

            $var_list = [
                '{order_name}' => '-',
                '{attached_file}' => '-',
                '{message}' => Tools::nl2br(Tools::stripslashes($message)),
                '{email}' => $from,
                '{product_name}' => '',
            ];

            if ($id_order) {
                $order = new Order((int) $id_order);
                $var_list['{order_name}'] = $order->getUniqReference();
                $var_list['{id_order}'] = (int) $order->id;
            }

            if (isset($file_attachment['name'])) {
                $var_list['{attached_file}'] = $file_attachment['name'];
            }

            // Mail to admin
            $mailToAdminResponse = Mail::Send(
                $this->context->language->id,
                'contact',
                $module->l('Message from contact form', 'contactsws'),
                $var_list,
                $contact->email,
                $contact->name,
                null,
                null,
                $file_attachment,
                null,
                _PS_MAIL_DIR_,
                false,
                null,
                null
            );

            if ($mailToAdminResponse) {
                ResponseHandler::success(['success' => true, 'message' => $module->l('Message successfully sent.', 'contactsws')]);
            } else {
                ResponseHandler::error('An error occurred while sending the message.', 'contactws');
            }
        }
    }

    private function validateParams($params, $module)
    {
        $extension = ['.txt', '.rtf', '.doc', '.docx', '.pdf', '.zip', '.png', '.jpeg', '.gif', '.jpg'];
        $file_attachment = Tools::fileAttachment('file');

        $error = null;

        if (!array_key_exists('email', $params) || !($from = trim($params['email'])) || !Validate::isEmail($from)) {
            $error = $module->l('Invalid email address.', 'contactsws');
        } elseif (!array_key_exists('message', $params) || !$params['message'] || !Validate::isCleanHtml($params['message'])) {
            $error = $module->l('Invalid message.', 'contactsws');
        } elseif (!array_key_exists('id_contact', $params) || !(Validate::isLoadedObject(new Contact((int) $params['id_contact'])))) {
            $error = $module->l('Please select a subject from the list provided. ', 'contactsws');
        } elseif (array_key_exists('id_order', $params) && !(Validate::isLoadedObject(new Order((int) $params['id_order'])))) {
            $error = $module->l('Invalid order.', 'contactsws');
        } elseif (!array_key_exists('agree', $params) || (bool) $params['agree'] == false) {
            $error = $module->l('You must agree to the privacy policy.', 'contactsws');
        } elseif (!empty($file_attachment['name']) && $file_attachment['error'] != 0) {
            $error = $module->l('An error occurred during the file-upload process.', 'contactsws');
        } elseif (!empty($file_attachment['name']) && !in_array(Tools::strtolower(Tools::substr($file_attachment['name'], -4)), $extension) && !in_array(Tools::strtolower(Tools::substr($file_attachment['name'], -5)), $extension)) {
            $error = $module->l('Bad file extension', 'contactsws');
        }

        return $error;
    }
}
