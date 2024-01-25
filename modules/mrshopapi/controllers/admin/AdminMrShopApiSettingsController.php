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
require_once _PS_MODULE_DIR_.'mrshopapi/controllers/admin/MrShopModuleAdminController.php';

use MrAPPs\MrShopApi\Handler\Api\AdminApiRequestHandler;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class AdminMrShopApiSettingsController extends MrShopModuleAdminController
{
    private $emailSupport = 'info@mr-apps.com';

    protected $fields_form;

    protected $name;

    /** @var MrShopApi */
    public $module;

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->display = 'view';
        $this->name = 'mrshopapi';
        $this->fields_form = [];
        $this->iconUrl = $this->getBackendPreviewUrl('icon');
        $this->splashUrl = $this->getBackendPreviewUrl('splash');
        $act = Tools::getValue('action');
        $devMode = defined('MRSHOP_DEV_MODE') && MRSHOP_DEV_MODE;

        if (!$devMode) {
            $this->init();
        }

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->container = $this->buildContainer();
        }

        if (false == empty($act)) {
            if (!$devMode && !$this->checkToken()) {
                ResponseHandler::methodNotAllowed('Not allowed');
            }

            $data = new DataHandler(true);
            $requestHandler = new AdminApiRequestHandler($data);
            $requestHandler->setResource($act);
            $requestHandler->setMethod($_SERVER['REQUEST_METHOD']);
            $requestHandler->setGETParams($_GET);
            if (Tools::getValue('id') != null) {
                $requestHandler->setId(Tools::getValue('id'));
            }
            $requestHandler->loadData();
        } else {
            $this->addCSS(ApiUtils::assetUrl('/css/app.css'));
            $this->addCSS(ApiUtils::assetUrl('/css/chunk-vendors.css'));
            $this->addCSS('//cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css');
        }
    }

    public function initPageHeaderToolbar()
    {
        $this->show_toolbar = false;
        $this->page_header_toolbar_btn = [];
    }

    public function initToolBarTitle()
    {
        //   $this->toolbar_title[] = $this->l('App settings');
    }

    private function getBackendPreviewUrl($type)
    {
        $shopBaseUrl = ApiUtils::getFullDomain();

        return $this->module->backend_base_url.'/preview/'.$type.
            '?url='.$shopBaseUrl.'img/modules/'.$this->module->name.'/'.$type.'.jpg'.
            '&shop_url='.$shopBaseUrl.
            '&secret='.trim(Configuration::get('MRSHOP_SECRET', null, null, null, ''));
    }

    public function getAdminBaseLink($idShop, $ssl = null, $relativeProtocol = false)
    {
        if (null === $ssl) {
            $ssl = Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE');
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            // Use the matching shop if present, or fallback on the default one
            if (null !== $idShop) {
                $shop = new Shop($idShop);
            } else {
                $shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
            }
        } else {
            $shop = Context::getContext()->shop;
        }

        $sslEnabled = Configuration::get('PS_SSL_ENABLED');

        if ($relativeProtocol) {
            $base = '//'.($ssl && $sslEnabled ? $shop->domain_ssl : $shop->domain);
        } else {
            $base = (($ssl && $sslEnabled) ? 'https://'.$shop->domain_ssl : 'http://'.$shop->domain);
        }

        return $base.$shop->getBaseURI();
    }

    public function renderView()
    {
        if (Shop::isFeatureActive()) {
            $appShop = (int) Configuration::getGlobalValue('MRSHOP_MULTISTORE_ID');
            if ($this->context->shop->id != $appShop) {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMrShopApiSettings', true).'&setShopContext=s-'.$appShop);
            }
        }

        $baseUrl = ApiUtils::getFullDomain();
        if (Tools::substr($baseUrl, Tools::strlen($baseUrl) - 1) == '/') {
            $baseUrl = Tools::substr($baseUrl, 0, Tools::strlen($baseUrl) - 1);
        }

        $apiAdminUrl = $this->getAdminBaseLink((int) $this->context->shop->id).basename(_PS_ADMIN_DIR_);
        if (Tools::substr($apiAdminUrl, Tools::strlen($apiAdminUrl) - 1) == '/') {
            $apiAdminUrl = Tools::substr($apiAdminUrl, 0, Tools::strlen($apiAdminUrl) - 1);
        }

        $isPsBilling = ApiUtils::isPsBilling();

        // Allow to auto-install Account
        if ($isPsBilling) {
            try {
                $accountsFacade = $this->module->container->getService('mrshopapi.ps_accounts_facade');
                $accountsService = $accountsFacade->getPsAccountsService();
            } catch (\PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException $e) {
                $accountsInstaller = $this->module->container->getService('mrshopapi.ps_accounts_installer');
                $accountsInstaller->install();
                $accountsFacade = $this->module->container->getService('mrshopapi.ps_accounts_facade');
                $accountsService = $accountsFacade->getPsAccountsService();
            }

            $showPsAccounts = false;
            if (!$accountsService->isAccountLinked()) {
                $showPsAccounts = true;
            }

            if ($showPsAccounts) {
                Media::addJsDef([
                    'contextPsAccounts' => $accountsFacade->getPsAccountsPresenter()
                        ->present($this->name),
                ]);

                // Retrieve the PrestaShop Account CDN
                $this->context->smarty->assign('urlAccountsCdn', $accountsService->getAccountsCdn());

                return $this->context->smarty->fetch(_PS_MODULE_DIR_.'mrshopapi/views/templates/admin/mr_shop_api_settings/ps_accounts.tpl');
            }

            try {
                $billingFacade = $this->module->container->getService('mrshopapi.ps_billings_facade');
                $partnerLogo = _PS_MODULE_DIR_.'mrshopapi/views/img/placeholder.3989ea07.png';

                $languageRoute = $this->context->language->iso_code ? '/it' : '';

                $this->context->smarty->assign('urlBilling', "https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js");
                // Billing
                Media::addJsDef($billingFacade->present([
                    'logo' => $partnerLogo,
                    'tosLink' => $this->getTosLink($this->context->language->iso_code),
                    'privacyLink' => 'https://ecommerceapp.it'.$languageRoute.'/privacy-policy',
                    'emailSupport' => $this->emailSupport,
                ]));
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }

        $isMultistore = false;
        $shopDescription = '';

        if (Shop::isFeatureActive()) {
            $isMultistore = true;
            $shopDescription = $this->context->shop->name.' <br/> '.$baseUrl;
        }

        $this->tpl_view_vars = [
            'appSecretCode' => \Configuration::get('MRSHOP_SECRET'),
            'merchantEmail' => \Configuration::get('PS_SHOP_EMAIL'),
            'isMultistore' => $isMultistore ? 'true' : 'false',
            'shopDescription' => $shopDescription,
            'apiBaseUrl' => $baseUrl,
            'apiAdminUrl' => $apiAdminUrl,
            'widgetJavascriptUrl' => ApiUtils::assetUrl('/js/app.min.js'),
            'widgetVendorsJavascriptUrl' => ApiUtils::assetUrl('/js/chunk-vendors.min.js'),
            'isPsBilling' => $isPsBilling
        ];

        $this->context->smarty->assign('pathVendor', _PS_MODULE_DIR_.'mrshopapi/views/js/chunk-vendors.min.js');
        $this->context->smarty->assign('pathApp', _PS_MODULE_DIR_.'mrshopapi/views/js/app.min.js');
        $this->context->smarty->assign($this->tpl_view_vars);

        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'mrshopapi/views/templates/admin/mr_shop_api_settings/settings.tpl');
    }

    /**
     * Get the Tos URL from the context language, if null, send default link value
     *
     * @return string
     */
    public function getTosLink($iso_lang)
    {
        switch ($iso_lang) {
            case 'it':
                $url = 'https://ecommerceapp.it/it/prestashop/termini-e-condizioni';

                break;
            default:
                $url = 'https://ecommerceapp.it/prestashop/terms-and-condition';

                break;
        }

        return $url;
    }
}
