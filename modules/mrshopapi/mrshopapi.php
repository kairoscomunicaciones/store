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
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'mrshopapi/vendor/autoload.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/AppHome.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/AppHomeItem.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/CmsPage.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/MrShopPaymentModule.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/MrShopApiNotification.php';
require_once _PS_MODULE_DIR_.'mrshopapi/classes/OrderStatusNotification.php';

class MrShopApi extends Module
{
    const API_VERSION = 7;

    const FERMOPOINT_MIN_VERSION = 8;

    const AMAZONPAY_MIN_VERSION  = 7;

    public $container;

    public $context;

    public $backend_base_url;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->display = 'view';

        $this->name = 'mrshopapi';
        $this->tab = 'front_office_features';
        $this->module_key = '22c46a83a656ed1bd0043e1b1d4fca48';
        $this->version = '2.5.2';

        $this->author = 'Mr. APPs';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
        $this->backend_base_url = defined('_MRSHOP_API_URL_')
            ? _MRSHOP_API_URL_
            : 'https://api.ecommerceapp.it/';

        if (!defined('_MRSHOP_API_URL_')) {
            define('_MRSHOP_API_URL_', $this->backend_base_url);
        }

        parent::__construct();

        $this->displayName = $this->l('Mr Shop App');
        $this->description = $this->l('Create and customize your app with Mr Shop!');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $warning = '';
        if (!Configuration::get('MRSHOP_CRONJOB_CONFIGURED')) {
            $cronUrl = MrAPPs\MrShopApi\Utils\ApiUtils::getFullDomain().MrAPPs\MrShopApi\Api\Routes::BASE_CRON_PATH.'all';
            $warning = $this->l('Configure a cronjob that runs the following link every minute: ').' '.$cronUrl.'. ';
        }
        if (!MrAPPs\MrShopApi\Utils\ApiUtils::isFcmConfigured()) {
            $warning .=  $this->l('Configure firebase to enable notifications').'. ';
        }
        if ($warning) {
            $this->warning = $warning;
        }

        Context::getContext()->cookie->id_country = Configuration::get('PS_COUNTRY_DEFAULT');

        if ($this->container === null) {
            $this->container = new \PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer(
                $this->name,
                $this->getLocalPath()
            );
        }
    }

    public function l($string, $specific = false, $locale = null)
    {
        return html_entity_decode(parent::l($string, $specific, $locale));
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMrShopApiSettings', true));
    }

    public function install()
    {
        $installer = new MrAPPs\MrShopApi\Service\Installer($this);

        if (Shop::isFeatureActive()) {
            Configuration::updateGlobalValue('MRSHOP_MULTISTORE_ID', Shop::getContextShopID(true));
        }

        if (
            !parent::install() ||
            !$installer->createTables() ||
            !$installer->alterTables()  ||
            !$installer->createTabs()   ||
            !$installer->install() ||
            !$installer->installPsAccounts() ||
            !$installer->createAnonymousCustomer() ||

            // Hooks
            !$this->registerHook('moduleRoutes') ||
            !$this->registerHook('actionOrderStatusPostUpdate') ||
            !$this->registerHook('actionObjectCustomerMessageAddAfter') ||
            !$this->registerHook('actionHtaccessCreate')
        ) {
            return false;
        }

        return true;
    }

    public function hookModuleRoutes()
    {
        return MrAPPs\MrShopApi\Api\Routes::CURRENT;
    }

    public function uninstall()
    {
        $installer = new MrAPPs\MrShopApi\Service\Installer($this);

        if (
            !parent::uninstall() ||
            !$installer->deleteTabs()
        ) {
            return false;
        }

        return true;
    }

    public function validateFieldLength($fields, $errors, $className)
    {
        foreach ($fields as $key => $input) {
            $input['lang'] = (isset($input['lang'])) ? $input['lang'] : false;
            if (isset($input['size'])) {
                $maxInputSize = $input['size'];
                foreach (Language::getLanguages() as $language) {
                    if ($input['lang']) {
                        $inputValue = Tools::getValue($key.'_'.$language['id_lang']);
                    } else {
                        $inputValue = Tools::getValue($key);
                    }

                    $inputSize = Tools::strlen($inputValue);
                    if ($inputSize > $maxInputSize) {
                        $errors[] = sprintf(
                            $this->l('The "%1$s" field (%2$s) is too long (%3$d chars max).'),
                            call_user_func([$className, 'displayFieldName'], $key, $className),
                            $language['iso_code'],
                            $maxInputSize
                        );
                    }
                    if ($input['required'] && $inputSize == 0) {
                        $errors[] = sprintf(
                            $this->l('The "%1$s" field (%2$s) is required'),
                            call_user_func([$className, 'displayFieldName'], $key, $className),
                            $language['iso_code']
                        );
                    }
                    if (!$input['lang']) {
                        break;
                    }
                }
            }
        }

        return $errors;
    }

    // ---------------------------------------------------------------------------------------
    // ----------------------------------- HOOK LANGUAGES ------------------------------------
    // ---------------------------------------------------------------------------------------

    public function cleanupCache()
    {
        $dataHandler = new MrAPPs\MrShopApi\Handler\Api\DataHandler(true);
        $apiRequestHandler = new MrAPPs\MrShopApi\Handler\Api\ApiRequestHandler($dataHandler);
        $apiRequestHandler->setupContext('cart');
        $cacheHandler = new MrAPPs\MrShopApi\Handler\Api\CacheHandler($dataHandler);
        $cacheHandler->deleteAll();
    }

    // -----------------------------------------------------------------------------------
    // ----------------------------------- HOOK ORDERS -----------------------------------
    // -----------------------------------------------------------------------------------

    public function hookActionOrderStatusPostUpdate($params)
    {
        $notificationsHandler = new MrAPPs\MrShopApi\Handler\NotificationsHandler($this);
        $notificationsHandler->sendOrderStatusChangeNotification((int) $params['id_order']);

        return true;
    }

    // -----------------------------------------------------------------------------------
    // --------------------------- HOOK CUSTOMER MESSAGES --------------------------------
    // -----------------------------------------------------------------------------------

    public function hookActionObjectCustomerMessageAddAfter($params)
    {
        $notificationsHandler = new MrAPPs\MrShopApi\Handler\NotificationsHandler($this);
        $notificationsHandler->sendMessageToCustomer($params['object']);

        return true;
    }

    // -----------------------------------------------------------------------------------
    // --------------------------- HOOK GENERATE HTACCESS --------------------------------
    // -----------------------------------------------------------------------------------

    public function hookActionHtaccessCreate()
    {
        $installer = new MrAPPs\MrShopApi\Service\Installer($this);
        $installer->generateHtaccess();

        return true;
    }
}
