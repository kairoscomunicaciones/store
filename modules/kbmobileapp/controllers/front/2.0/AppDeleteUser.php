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

class AppDeleteUser extends AppCore
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
        if (empty($email)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('An email address required.'),
                'AppDeleteUSer'
            );
            $this->writeLog('Email address not provided.');
        } elseif (!Validate::isEmail($email)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Invalid email address.'),
                'AppDeleteUSer'
            );
            $this->writeLog('Invalid email address.');
        } else {
            $sql = 'SELECT id_customer FROM ' . _DB_PREFIX_ . 'customer WHERE email = "' . pSQL($email) . '"';
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
            if (!$result) {
                $this->content['status'] = 'failure';
                $this->content['message'] = '';
            } else{
                $customer_id = $result[0]['id_customer'];
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(
                        'DELETE FROM ' . _DB_PREFIX_ . 'customer '
                    . 'WHERE id_customer =' . (int) $customer_id
                );
                
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(
                        'DELETE FROM ' . _DB_PREFIX_ . 'kbmobileApp_unique_verification '
                    . 'WHERE id_customer =' . (int) $customer_id
                );
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('User deleted successfully.'),
                    'AppDeleteUSer'
                );
            }
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
}
     ?>
