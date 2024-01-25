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
class AppDeleteGDPRUserData extends AppCore
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
            $mail_content = $this->kbNotifyAdminMailContent();
            $flag = $this->kbSendNotificationAdmin($mail_content, $email);
            if ($flag) {
                $this->content['status'] = "success";
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Your GDPR Account Deletion Request has been registered.'),
                    'AppDeleteGDPRUserData'
                );
            } else {
                $this->content['status'] = "failure";
            }
        } else {
            $this->content['status'] = "failure";
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    public function kbSendNotificationAdmin($mail_template, $email)
    {
        // changes done by rishabh jain
        $context = Context::getContext();
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = Configuration::get('PS_LANG_DEFAULT', null, (int)Context::getContext()->shop->id_shop_group, $id_shop);
        $template = array(
            '{template}' => $mail_template['body'],
            '{minimal_image}' => Context::getcontext()->link->getMediaLink(
                __PS_BASE_URI__ . 'modules/kbmobileapp/views/img/minimal6.png'
            ),
            '{email}' => $email,
            '{shop_logo}' => $this->context->link->getMediaLink(
                __PS_BASE_URI__ . 'img/logo.jpg'
            ),
            '{SHOP_NAME}' => Configuration::get('PS_SHOP_NAME'),
        );
        $admin_mail = Configuration::get('PS_SHOP_EMAIL');
        $flag = Mail::Send(
            $id_lang,
            'notify_admin',
            $mail_template['subject'],
            $template,
            $admin_mail,
            Configuration::get('PS_SHOP_NAME'),
            $email,
            (Context::getcontext()->customer->firstname ?
                Context::getcontext()->customer->firstname . ' '
                .Context::getcontext()->customer->lastname : null),
            null,
            null,
            _PS_MODULE_DIR_ . 'kbmobileapp/mails/'
        );
        return $flag;
    }
    
    public function kbNotifyAdminMailContent()
    {
        $mail_content = array();
        $this->context->smarty->assign(
            'shop_logo',
            $this->context->link->getMediaLink(
                __PS_BASE_URI__ . 'img/logo.jpg'
            )
        );
        
        $body =  $this->context->smarty->fetch(_PS_MODULE_DIR_.'/kbmobileapp/views/templates/admin/request_email.tpl');
        $body = str_replace('[', '{', $body);
        $body = str_replace(']', '}', $body);
        $body = str_replace('{email}', Tools::getValue('email', ''), $body);
        $mail_content['subject'] = $this->l('Customer has requested for GDPR Delete Request');
        $mail_content['body'] = $body;
        return $mail_content;
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
                'AppDeleteGDPRUserData'
            );
            $this->writeLog('Email address is not valid');
            return false;
        } else if (!Customer::customerExists(strip_tags($email))) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Customer with this email not exist'),
                'AppDeleteGDPRUserData'
            );
            $this->writeLog('Customer with this email not exist');
            return false;
        } else {
            return true;
        }
    }
}
