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

namespace MrAPPs\MrShopApi\Service;

use Address;
use AppHome;
use AppHomeItem;
use Configuration;
use Context;
use Customer;
use Db;
use Exception;
use Language;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Service\MrShopCustomer\MrShopCustomerClient;
use MrShopPaymentModule;
use OrderStatusNotification;
use PrestaShop\PrestaShop\Adapter\ServiceLocator;
use PrestaShop\PrestaShop\Core\Crypto\Hashing;
use Tab;
use Tools;

class Installer
{
    const INSTALL_SQL_FILE = 'install.sql';

    const ALTER_SQL_FILE = 'alter.sql';

    const ALTER_CMS_TABLE_VERSION = 'alter_2.2.0.sql';

    const FROM_ADDONS_FILE = 'from_addons';

    /**@var \MrShopApi */
    private $module;

    private $imageService;

    /**@var Context $context */
    private $context;

    public function __construct($module)
    {
        $this->module = $module;
        $this->imageService = new ImageService();
        $this->context = Context::getContext();
    }

    public static function createConfigDirectory()
    {
        $configDirectory = _PS_CONFIG_DIR_.'modules/mrshopapi/';
        if (!file_exists($configDirectory)) {
            mkdir(
                $configDirectory,
                0755,
                true
            );
        }
    }

    public static function createImgModulesDirectory()
    {
        $dirs = [
            'banners',
            'carousel'
        ];
        $imgDir = _PS_IMG_DIR_.'modules/mrshopapi/';
        if (!file_exists($imgDir)) {
            mkdir(
                $imgDir,
                0755,
                true
            );
        }

        foreach ($dirs as $dir) {
            $full_dir = $imgDir.$dir.'/';
            if (!file_exists($full_dir)) {
                mkdir(
                    $full_dir,
                    0755,
                    true
                );
            }
        }
    }

    private function _createTab($parentId, $className, $displayName, $icon = null)
    {
        Db::getInstance(_PS_USE_SQL_SLAVE_)
            ->execute(
                'DELETE FROM `'._DB_PREFIX_.'tab` 
                     WHERE `class_name` = "'.pSQL($className).'"
                     AND module = "'.pSQL($this->module->name).'"'
            );

        $tab = new Tab();
        $tab->name = [];

        $tab->id_parent = $parentId;
        $tab->module = $this->module->name;

        if ($className) {
            $tab->class_name = $className;
        }

        if ($icon) {
            $tab->icon = $icon;
        }

        foreach (Language::getLanguages(true) as $lang) {
            $lang = (object) $lang;
            $tab->name[$lang->id_lang] = isset($displayName[$lang->iso_code]) ? $displayName[$lang->iso_code] : $displayName['en'];
        }

        $tab->add();

        return $tab;
    }

    public function createTabs()
    {
        try {
            $appTab = new Tab();
            $appTab->name = [];

            foreach (Language::getLanguages(true) as $lang) {
                $appTab->name[$lang['id_lang']] = ($lang['iso_code'] == 'it') ? 'App Mobile' : 'Mobile App';
            }

            $appTab->id_parent = 0; // Tab App
            $appTab->active = 1;
            $appTab->module = $this->module->name;
            $appTab->class_name = 'MrShopApi';
            $appTab->add();

            $idTabParent = $appTab->id;

            $appSettings = ['it' => 'Impostazioni', 'en' => 'Settings'];

            $this->_createTab($idTabParent, 'AdminMrShopApiSettings', $appSettings, 'settings');
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function deleteTabs()
    {
        $tabs = ['AdminMrShopApiCmsPages', 'AdminMrShopApiAppHome', 'AdminMrShopApiNotifications', 'AdminMrShopApiSettings', 'MrShopApp', 'MrShopApi'];

        if (version_compare(_PS_VERSION_, '1.7.1.0', '<')) {
            $tabRepository = null;
        } else {
            $tabRepository = $this->module->get('prestashop.core.admin.tab.repository');
        }

        foreach ($tabs as $tab) {
            if ($tabRepository != null) {
                $id = (int) $tabRepository->findOneIdByClassName($tab);
            } else {
                $id = (int) Tab::getIdFromClassName($tab);
            }

            try {
                if ($id > 0) {
                    $mrshopTab = new Tab($id);
                    $mrshopTab->delete();
                }
            } catch (\Exception $e) {
            }
        }

        return true;
    }

    public function installPsAccounts()
    {
        $fromAddonsPath = _PS_MODULE_DIR_.'/mrshopapi/config/'.self::FROM_ADDONS_FILE;
        $fromAddonsFile = file_exists($fromAddonsPath);

        if ($fromAddonsFile) {
            $customerClient = new MrShopCustomerClient();

            try {
                // If check status return error app not exists
                // So is it from addons, otherwise fromAddonsFile not exists
                $response = $customerClient->checkStatus();
            } catch (Exception $e) {
                $response = [
                    'isPrestashopAddonsApp' => true
                ];
            }

            if (array_key_exists('isPrestashopAddonsApp', $response) && $response['isPrestashopAddonsApp'] == true) {
                Configuration::updateValue('_FROM_PADDLE_', 0);

                return $this->module->container->getService('mrshopapi.ps_accounts_installer')->install();
            }
        }

        Configuration::updateValue('_FROM_PADDLE_', true);

        return true;
    }

    public function install()
    {
        $secret = trim(Configuration::get('MRSHOP_SECRET', null, null, null, ''));
        $secretJwt = trim(Configuration::get('MRSHOP_SECRET_JWT', null, null, null, ''));

        if (Tools::strlen($secret) == 0) {
            $now = new \DateTime();
            Configuration::updateValue('MRSHOP_SECRET', bin2hex(openssl_random_pseudo_bytes(32).$now->format('Y-m-d|H:i:s')));
        }
        if (Tools::strlen($secretJwt) == 0) {
            Configuration::updateValue('MRSHOP_SECRET_JWT', bin2hex(openssl_random_pseudo_bytes(32)));
        }

        $registrationMsg = trim(Configuration::get('MRSHOP_REGISTRATION_MSG', $this->context->language->id, null, null, ''));
        $profileMsg = trim(Configuration::get('MRSHOP_PROFILE_MSG', $this->context->language->id, null, null, ''));

        if (Tools::strlen($registrationMsg) == 0) {
            Configuration::updateValue('MRSHOP_REGISTRATION_MSG', '');
        }
        if (Tools::strlen($profileMsg) == 0) {
            Configuration::updateValue('MRSHOP_PROFILE_MSG', '');
        }

        if ((int) Configuration::get('MRSHOP_MAX_PRODUCT_SHOWN', null, null, null, 0) == 0) {
            Configuration::updateValue('MRSHOP_MAX_PRODUCT_SHOWN', 10);
        }

        $defaultOrderShowcaseProducts = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_SHOWCASE_PRODUCTS', null, null, null, ''));
        if (Tools::strlen($defaultOrderShowcaseProducts) == 0) {
            Configuration::updateValue('MRSHOP_DEFAULT_ORDER_SHOWCASE_PRODUCTS', "product.date_add.desc");
        }

        $defaultOrderNewProducts = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_NEW_PRODUCTS', null, null, null, ''));
        if (Tools::strlen($defaultOrderNewProducts) == 0) {
            Configuration::updateValue('MRSHOP_DEFAULT_ORDER_NEW_PRODUCTS', "product.date_add.desc");
        }

        $defaultOrderManufacturerProducts = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_MANUFACTURER_PRODUCTS', null, null, null, ''));
        if (Tools::strlen($defaultOrderManufacturerProducts) == 0) {
            Configuration::updateValue('MRSHOP_DEFAULT_ORDER_MANUFACTURER_PRODUCTS', "product.date_add.desc");
        }

        $defaultOrderSpecialOffers = trim(Configuration::get('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS', null, null, null, ''));
        if (Tools::strlen($defaultOrderSpecialOffers) == 0) {
            Configuration::updateValue('MRSHOP_DEFAULT_ORDER_SPECIAL_OFFERS', "product.date_add.desc");
        }

        if ((int) Configuration::get('MRSHOP_PRODUCTS_LAYOUT', null, null, null, 0) == 0) {
            Configuration::updateValue('MRSHOP_PRODUCTS_LAYOUT', 1);
        }

        if (OrderStatusNotification::countNotifications() == 0) {
            OrderStatusNotification::autoAddOrderStatusNotification('PS_OS_PAYMENT');
            OrderStatusNotification::autoAddOrderStatusNotification('PS_OS_SHIPPING');
            OrderStatusNotification::autoAddOrderStatusNotification('PS_OS_DELIVERED');
            OrderStatusNotification::autoAddOrderStatusNotification('PS_OS_CANCELED');
        }

        $firebaseServiceAccount = trim(Configuration::get('MRSHOP_FIREBASE_SERVICE_ACCOUNT', null, null, null, ''));
        if (Tools::strlen($firebaseServiceAccount) == 0) {
            Configuration::updateValue('MRSHOP_FIREBASE_SERVICE_ACCOUNT', '');
        }

        self::createConfigDirectory();
        self::createImgModulesDirectory();

        return true;
    }

    public function execSql($file, $ignoreErrors = false)
    {
        $path = dirname(__FILE__).'/../../sql/'.$file;
        if (!file_exists($path)) {
            error_log('Unable to find SQL file: '.$path);

            return false;
        } else {
            if (!$sql = Tools::file_get_contents($path)) {
                error_log('Unable to read content of SQL file: '.$path);

                return false;
            }
        }

        $sql = str_replace(['PREFIX_', 'ENGINE_TYPE'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        $result = true;
        foreach ($sql as $query) {
            if ($query) {
                try {
                    $result = $result && Db::getInstance()->execute(trim($query));
                } catch (\Exception $ex) {
                    error_log($ex);
                    $result = false;
                }
            }
        }

        return $ignoreErrors || $result;
    }

    public function createTables()
    {
        return $this->execSql(self::INSTALL_SQL_FILE, false);
    }

    public function createAnonymousCustomer()
    {
        $passwd = 'prestashop';

        if (version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            $crypto = ServiceLocator::get(Hashing::class);
            $passwd = $crypto->hash(Tools::passwdGen(64));
        }

        $query = 'SELECT id_customer, email FROM `'._DB_PREFIX_.'customer` c WHERE email = "anonymous@mrshop.com" or email = "anonymous@anonymous.com"';
        $anonymousCustomer = Db::getInstance()->getRow($query);

        if (isset($anonymousCustomer['id_customer'])) {
            $id_address = Address::getFirstCustomerAddressId($anonymousCustomer['id_customer']);

            Configuration::updateValue('MRSHOP_ANONYMOUS_CUSTOMER', $anonymousCustomer['id_customer']);
            Configuration::updateValue('MRSHOP_ANONYMOUS_ADDRESS', $id_address);

            return true;
        }

        // create an anonymous customer
        $customer = new Customer();
        $customer->id_gender = 1;
        $customer->lastname = 'Anonymous';
        $customer->firstname = 'Anonymous';
        $customer->email = 'anonymous@mrshop.com';
        $customer->passwd = $passwd;
        $customer->optin = (bool) Configuration::get('PS_CUSTOMER_OPTIN');

        $customer->active = false;
        if ($customer->save() == false) {
            return false;
        }

        // create an anonymous address
        $address = new Address();
        $address->id_customer = $customer->id;
        $address->alias = 'Anonymous';
        $address->company = 'Anonymous';
        $address->lastname = 'Anonymous';
        $address->firstname = 'Anonymous';
        $address->address1 = 'Anonymous';
        $address->phone = '0000000000';
        $address->phone_mobile = '0000000000';
        $address->vat_number = '0000';
        $address->dni = '0000';
        $address->postcode = '00000';
        $address->id_country = Configuration::get('PS_COUNTRY_DEFAULT');
        $address->city = 'Anonymous';
        if ($address->save() == false) {
            return false;
        }

        Configuration::updateValue('MRSHOP_ANONYMOUS_CUSTOMER', $customer->id);
        Configuration::updateValue('MRSHOP_ANONYMOUS_ADDRESS', $address->id);

        unset($customer, $address);

        return true;
    }

    public function alterTables($alterFile = self::ALTER_SQL_FILE, $ignoreErrors = true)
    {
        if ($alterFile == self::ALTER_CMS_TABLE_VERSION) {
            $this->deleteInactiveCmsPages();
        }

        return $this->execSql($alterFile, $ignoreErrors);
    }

    public function syncImages()
    {
        $this->syncAppHomeImages();
        $this->syncAppHomeItemImages();
    }

    public function syncPaymentModules()
    {
        $paymentHandler = new PaymentHandler(new DataHandler(true));
        $modules = $paymentHandler->getModuleList();
        MrShopPaymentModule::syncModules($modules);
    }

    private function syncAppHomeImages()
    {
        $query = "SELECT *
        FROM "._DB_PREFIX_."app_home
        WHERE hash IS NOT NULL AND hash != ''";
        $appHomes = Db::getInstance()->executeS($query);
        if (isset($appHomes)) {
            foreach ($appHomes as $appHome) {
                if (isset($appHome['hash'])) {
                    $oldPath = $this->imageService->getModuleImageFolder().$this->imageService->getBannerImageRelativePath($appHome['id_app_home'], $appHome['hash']);
                    $newPath = $this->imageService->getImagePath($appHome['hash'], AppHome::$TYPE_BANNER);

                    if (copy($oldPath, $newPath)) {
                        unlink($oldPath);
                    }
                }
            }
        }
    }

    private function syncAppHomeItemImages()
    {
        $query = "SELECT *
        FROM "._DB_PREFIX_."app_home_item
        WHERE hash IS NOT NULL AND hash != '';";
        $items = Db::getInstance()->executeS($query);
        if (isset($items)) {
            foreach ($items as $item) {
                if (isset($item['hash'])) {
                    $oldPath = $this->imageService->getModuleImageFolder().$this->imageService->getCarouselImageRelativePath($item['id_app_home'], $item['hash']);
                    $newPath = $this->imageService->getImagePath($item['hash'], AppHome::$TYPE_CAROUSEL);

                    if (copy($oldPath, $newPath)) {
                        unlink($oldPath);
                    }
                }
            }
        }

        AppHomeItem::alignCarouselItemLang($items);
    }

    private function deleteInactiveCmsPages()
    {
        // Delete active = 0 cms pages before drop column
        $query = "DELETE FROM "._DB_PREFIX_."mrshop_cms_page
        WHERE active = 0";
        Db::getInstance()->execute($query);
    }

    public function generateHtaccess()
    {
        // Default values for parameters
        $path = _PS_ROOT_DIR_.'/.htaccess';

        // Check current content of .htaccess and save all code outside of prestashop comments
        $specific_before = $specific_after = '';
        if (file_exists($path)) {
            $content = Tools::file_get_contents($path);
            if (preg_match('#^(.*)\# ~~start-mrshop~~.*\# ~~end-mrshop~~[^\n]*(.*)$#s', $content, $m)) {
                $specific_before = $m[1];
                $specific_after = $m[2];
            }

            // Write .htaccess data
            if (!$write_fd = @fopen($path, 'wb')) {
                return false;
            }
            if ($specific_before) {
                fwrite($write_fd, trim($specific_before)."\n\n");
            }

            // Write data in .htaccess file
            fwrite($write_fd, "# ~~start-mrshop~~ Do not remove this comment, Prestashop will keep automatically the code outside this comment when .htaccess will be generated again\n");
            fwrite($write_fd, "# .htaccess automaticaly generated by PrestaShop e-commerce open-source solution\n");
            fwrite($write_fd, "# https://www.prestashop.com - https://www.prestashop.com/forums\n\n");

            // RewriteEngine
            fwrite($write_fd, "<IfModule mod_rewrite.c>\n");

            // Ensure HTTP_MOD_REWRITE variable is set in environment
            fwrite($write_fd, "<IfModule mod_env.c>\n");
            fwrite($write_fd, "SetEnv HTTP_MOD_REWRITE On\n");
            fwrite($write_fd, "</IfModule>\n\n");
            $content = '
RewriteEngine on

RewriteRule . - [E=REWRITEBASE:/]

#webservicerequestquote
RewriteRule ^([a-z]{2}\/)?client_mrshop/request/quote/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservicerequestquote&product_id=$2 [QSA,NC,L]
#cache
RewriteRule ^([a-z]{2}\/)?client_mrshop/remove/cache$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=cache [QSA,NC,L]
#webservicecronjoball
RewriteRule ^([a-z]{2}\/)?cron_mrshop/all$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservicecronjoball [QSA,NC,L]
#mobileigfspayment
RewriteRule ^([a-z]{2}\/)?client_mrshop/cart/mobile_igfs_payment$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=mobileigfspayment [QSA,NC,L]
#mobilepayonwebpayment
RewriteRule ^([a-z]{2}\/)?client_mrshop/cart/mobile_payonweb_payment$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=mobilepayonwebpayment [QSA,NC,L]
#webserviceapplycoupon
RewriteRule ^([a-z]{2}\/)?client_mrshop/cart/coupon/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservice&entity=cart\/coupon&id=$2 [QSA,NC,L]
#webservicecustomerswishlist
RewriteRule ^([a-z]{2}\/)?client_mrshop/wishlist/mine/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservicecustomerswishlist&entity=wishlist\/$2 [QSA,NC,L]
#webserviceproductsshare
RewriteRule ^([a-z]{2}\/)?client_mrshop/share/products/(.*)/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webserviceproductsshare&id_product=$2&id_product_attribute=$3 [QSA,NC,L]
#webservice
RewriteRule ^([a-z]{2}\/)?client_mrshop/(.*)/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservice&entity=$2&id=$3 [QSA,NC]
RewriteRule ^([a-z]{2}\/)?client_mrshop/(.*)$ %{ENV:REWRITEBASE}index.php?fc=module&module=mrshopapi&controller=webservice&entity=$2 [QSA,NC,L]
';

            fwrite($write_fd, $content);

            fwrite($write_fd, "</IfModule>\n\n");

            fwrite($write_fd, '# ~~end-mrshop~~ Do not remove this comment, Prestashop will keep automatically the code outside this comment when .htaccess will be generated again');
            if ($specific_after) {
                fwrite($write_fd, "\n\n".trim($specific_after));
            }
            fclose($write_fd);
        }
    }
}
