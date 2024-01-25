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
 * API to get Live chat support status and its key
 */

require_once 'AppCore.php';
class AppGetGDPRDataInformation extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if ($this->validateCustomer()) {
            $email = Tools::getValue('email', '');
            $id_customer = Customer::customerExists($email, true, false);
            if (!empty($id_customer)) {
                $customer = new Customer($id_customer);
                $customers = Customer::getCustomersByEmail($email);
                if (!empty($customer)) {
                    $data = $this->kbPersonalInfo($customer);
                    $orders = $this->kbCustomerOrders($customer);
                    $address = $this->kbCustomerAddresses($customer);
                }
                $filename = time() . '-' . 'mobileapp' . '-' . $id_customer . '.csv';
                $path = _PS_MODULE_DIR_ . 'kbmobileapp/GDPR/'.$filename;
                $file = fopen($path, 'w');
                header("Content-Transfer-Encoding: Binary");
                header('Content-Type: application/excel');
                header('Content-Disposition: attachment; filename=' . basename($filename));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                ob_clean();
                $file_to_download = $this->kbCsvExport($data, $file);
                $file_to_download = $this->kbCsvExport($orders, $file);
                $file_to_download = $this->kbCsvExport($address, $file);
                if ($this->checkSecureUrl()) {
                    $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
                } else {
                    $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
                }
                chmod(_PS_MODULE_DIR_ . 'kbmobileapp/GDPR/', 0755);
                $file_url = $module_dir . 'kbmobileapp/GDPR/'.$filename;
                $this->content['file_url'] = $file_url;
                $this->content['file_name'] = $filename;
                $this->content['file_extension'] = "csv";
                $this->content['status'] = "success";
            } else {
                $this->content['status'] = "failure";
            }
        }
        
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    
    /*
     * Function to fetch address of customer and their corresponding fields
     */
    public function kbCustomerAddresses($customer)
    {
        $data = array();
        $id_lang = Context::getContext()->language->id;
        $addresses = $customer->getAddresses(Context::getContext()->language->id);
        
        $data['type'] = array(
                parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('ADDRESSES'),
                    'AppGetGDPRDataInformation'
                ));
        $labels = array();
        $label_array = array(
            "1" => 'Alias',
            "2" => 'Company',
            "3" => 'First Name',
            "4" => 'Last Name',
            "5" => 'Address Line 1',
            "6" => 'Address Line 2',
            "7" => 'Postcode',
            "8" => 'City',
            "9" => 'State',
            "10" => 'Country',
            "11" => 'Notes',
            "12" => 'Phone',
            "13" => 'Mobile Phone',
            "14" => 'VAT number',
            "15" => 'DNI',
            "16" => 'Date',
        );
        foreach ($label_array as $label_array_value) {
                $labels[] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l($label_array_value),
                    'AppGetGDPRDataInformation'
                );
        }
        $data['label'] = $labels;
        
        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                $data['rec'][] = array(
                    $address['alias'],
                    $address['company'],
                    $address['firstname'],
                    $address['lastname'],
                    $address['address1'],
                    $address['address2'],
                    $address['postcode'],
                    $address['city'],
                    $address['state'],
                    $address['country'],
                    $address['other'],
                    $address['phone'],
                    $address['phone_mobile'],
                    $address['vat_number'],
                    $address['dni'],
                    Tools::displayDate($address['date_add'], $id_lang),
                );
            }
        }
        return $data;
    }
    /*
     * Function to fetch customer orders and their corresponding fields
     */
    public function kbCustomerOrders($customer)
    {
        $data = array();
        $id_lang = Context::getContext()->language->id;
        $currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $orders = Order::getCustomerOrders($customer->id);
        //$data['type'] = array($module->l('ORDERS', 'KbGdprPortability'));
        $data['type'] = array(
                parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('ORDERS'),
                    'AppGetGDPRDataInformation'
                ));
        $labels = array();
        $label_array = array(
            "1" => 'Reference',
            "2" => 'Date',
            "3" => 'Payment',
            "4" => 'Order State',
            "5" => 'Total Price',
        );
        foreach ($label_array as $label_array_value) {
            $labels[] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l($label_array_value),
                'AppGetGDPRDataInformation'
            );
        }
        $data['label'] = $labels;
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $data['rec'][] = array(
                    $order['reference'],
                    Tools::displayDate($order['date_add'], $id_lang),
                    $order['payment'],
                    (isset($order['order_state']))? $order['order_state']:'',
                    Tools::displayPrice($order['total_paid'], $currency),
                );
            }
        }
        return $data;
    }
    /*
     * Function to fetch personal data of customer and fields corresponding to them.
     */
    public function kbPersonalInfo($customer)
    {
        $data = array();
        $id_lang = Context::getContext()->language->id;
        $lang = Language::getLanguage($id_lang);
        $gender = new Gender($customer->id_gender, $lang, Context::getContext()->shop->id);
        $data['type'] = array(parent::getTranslatedTextByFileAndISO(
            Tools::getValue('iso_code', false),
            $this->l('PERSONAL INFO'),
            'AppGetGDPRDataInformation'
        ));
        $labels = array();
        $label_array = array(
            "1" => 'First Name',
            "2" => 'Last Name',
            "3" => 'Language',
            "4" => 'Note',
            "5" => 'Gender',
            "6" => 'Birthday',
            "7" => 'Age',
            "8" => 'Email',
            "9" => 'Newsletter',
            "10" => 'Website',
            "11" => 'Company',
            "12" => 'Last Passwd Generated',
            "13" => 'Account Creation Date',
        );
        foreach ($label_array as $label_array_value) {
                $labels[] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l($label_array_value),
                    'AppGetGDPRDataInformation'
                );
        }
        $data['label'] = $labels;
        $data['rec'][] = array(
                $customer->firstname,
                $customer->lastname,
                $lang['name'],
                $customer->note,
                $gender->name,
                $customer->birthday,
                ($customer->birthday != '0000-00-00')?date_diff(date_create($customer->birthday), date_create('now'))->y: parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Unknown'),
                    'AppGetGDPRDataInformation'
                ),
                $customer->email,
                ($customer->newsletter) ? parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Yes'),
                    'AppGetGDPRDataInformation'
                ):parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('No'),
                    'AppGetGDPRDataInformation'
                ),
                $customer->website,
                $customer->company,
                $customer->last_passwd_gen,
                $customer->date_add,
            );
        return $data;
    }
    
    public function kbCsvExport($fields, $file)
    {
        $data = array();
        if (!empty($fields)) {
            if (isset($fields['type'])) {
                fputcsv($file, $fields['type']);
            }
            foreach ($fields['label'] as $field) {
                $data[] = $field;
            }
            if (!empty($data)) {
                fputcsv($file, $data, ',');
            }
            if (!empty($fields['rec']) && isset($fields['rec'])) {
                foreach ($fields['rec'] as $field) {
                    fputcsv($file, $field, ',');
                }
            }
        }
        $line = array();
        fputcsv($file, $line);
        return $data;
    }

    public function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * Validate customer i.e email is valid or not or customer with provided email address is exist or not
     *
     * @return bool
     */
    public function validateCustomer()
    {
        $email = Tools::getValue('email', '');
        if (!Validate::isEmail($email)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Email address is not valid'),
                'AppGetGDPRInformation'
            );
            $this->writeLog('Email address is not valid');
            return false;
        } else if (!Customer::customerExists(strip_tags($email))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Customer with this email not exist'),
                'AppGetGDPRInformation'
            );
            $this->writeLog('Customer with this email not exist');
            return false;
        } else {
            return true;
        }
    }
}
