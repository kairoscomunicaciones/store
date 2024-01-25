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

class AppGetConfig extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        $session_data = Tools::getValue('session_data', '');
        $this->content['status'] = 'success';
        $this->getWhatsappChatData();
        /* start:changes started by Tarun Gupta on 8/9/2020 to get the Hyperlocal configuration*/
        $this->getHyperlocalConfiguration();
        /* End:changes started by Tarun Gupta on 8/9/2020 to get the Hyperlocal configuration*/
        $this->getLiveChatData();
        $this->getLogConfigurationData();
        $this->getFingerprintLoginConfigurationData();
        $this->getPhoneNumberRegistrationConfigurationData();
        /* start:changes started by Aayushi Agarwal on 30 August 18 to add gdpr tab*/
        $this->getGdprData();
        /* start:changes started by Aayushi Agarwal on 30 August 18 to add gdpr tab*/
        $this->content['session_data'] = $session_data;
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    /*
     * Function get GDPR Configuration
     */
    public function getGdprData()
    {
        $fetch_gdpr_content_query = "SELECT page_content_html FROM " . _DB_PREFIX_ . "kbmobileapp_gdpr_page_content where id_gdpr_page_content = 1";
        $gdpr_content = Db::getInstance()->executeS($fetch_gdpr_content_query);
        $gdpr_content = Tools::unSerialize($gdpr_content[0]['page_content_html']);
        $id_lang = Language::getIdByIso(Tools::getValue('iso_code'), false);
        if (isset($gdpr_content[$id_lang])) {
            
            $html = Tools::htmlentitiesDecodeUTF8($gdpr_content[$id_lang]);
            $html = preg_replace("/[\n\r]/", "", $html);
        } else {
            $html = '';
        }
        $show_checkbox_data = array();
        $show_checkbox_data['login_page'] = "0";
        $show_checkbox_data['signup_page'] = "0";
        $show_checkbox_data['guest_login_page'] = "0";
        
        $show_checkbox_data['personal_details_page'] = "0";
        $show_checkbox_data['address_page'] = "0";
        $show_checkbox_data['checkout_page'] = "0";
        $show_checkbox_data['payment_page'] = "0";
        $allowed_screens = Tools::unSerialize(Configuration::get('kb_gdpr_allowed_screens'));
        if (!empty($allowed_screens)) {
            foreach ($allowed_screens as $allowed_screens_key => $allowed_screens_value) {
                if ($allowed_screens_key == 0) {
                    $show_checkbox_data['login_page'] = "1";
                } else if ($allowed_screens_key == 1) {
                    $show_checkbox_data['signup_page'] = "1";
                } else if ($allowed_screens_key == 2) {
                    $show_checkbox_data['guest_login_page'] = "1";
                } else if ($allowed_screens_key == 3) {
                    $show_checkbox_data['personal_details_page'] = "1";
                } else if ($allowed_screens_key == 4) {
                    $show_checkbox_data['address_page'] = "1";
                } else if ($allowed_screens_key == 5) {
                    $show_checkbox_data['checkout_page'] = "1";
                } else if ($allowed_screens_key == 6) {
                    $show_checkbox_data['payment_page'] = "1";
                }
            }
        }
        $this->content['gdpr_configurations'] = array(
            'status' => Configuration::get('kb_gdpr_enable'),
            'terms_and_conditions_html' => $html,
            'show_checkbox_data' => $show_checkbox_data,
        );
    }
    /*
     * Function to get the live chat configuration
     */
    public function getLiveChatData()
    {
        if (Configuration::get('KB_MOBILE_APP_CHAT_SUPPORT_KEY')) {
            $chat_api_key = Configuration::get('KB_MOBILE_APP_CHAT_SUPPORT_KEY');
        } else {
            $chat_api_key = '';
        }
        $this->content['zopim_chat_configurations'] = array(
            'status' => Configuration::get('KB_MOBILE_APP_CHAT_SUPPORT'),
            'chat_api_key' => $chat_api_key
        );
    }
    public function getWhatsappChatData()
    {
        if (Configuration::get('KB_MOBILE_WHATSAPP_CHAT_NUMBER')) {
            $whatsapp_number = Configuration::get('KB_MOBILE_WHATSAPP_CHAT_NUMBER');
        } else {
            $whatsapp_number = '';
        }
        if (Configuration::get('KB_MOBILE_WHATSAPP_CHAT_SUPPORT')) {
            $this->content['whatsapp_configurations'] = array (
                'is_enabled' => true,
                'chat_number' => $whatsapp_number
            );
        } else {
            $this->content['whatsapp_configurations'] = array (
                'is_enabled' => false,
                'chat_number' => $whatsapp_number
            );
        }
    }
    public function getPhoneNumberRegistrationConfigurationData()
    {
        $this->content['phone_number_registartion_configurations'] = array (
            'is_enabled' => Configuration::get('KB_MOBILEAPP_PHONE_NUMBER_REGISTRTAION'),
            'is_mandatory' => Configuration::get('KB_MOBILEAPP_PHONE_NUMBER_MANDATORY')
        );
    }
    public function getFingerprintLoginConfigurationData()
    {
        $this->content['fingerprint_configurations'] = array (
            'is_enabled' => Configuration::get('KB_MOBILEAPP_FINGERPRINT_LOGIN'),
        );
    }
    public function getLogConfigurationData()
    {
        $this->content['log_configurations'] = array(
            'status' => Configuration::get('KB_MOBILE_APP_ERROR_REPORTING')
        );
    }
}
