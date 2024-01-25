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

namespace MrAPPs\MrShopApi\Utils;

use Configuration;
use Context;
use Customer;
use Db;
use Group;
use Language;
use Module;
use MrAPPs\MrShopApi\Handler\Api\AppConfigurationHandler;
use MrAPPs\MrShopApi\Handler\Fidelity\AdvansedCouponFidelityHandler;
use MrAPPs\MrShopApi\Handler\Fidelity\AllinoneRewardsFidelityHandler;
use MrAPPs\MrShopApi\Handler\Fidelity\FidelityHandlerInterface;
use MrAPPs\MrShopApi\Handler\Fidelity\InactiveFidelityHandler;
use Product;
use Validate;

class ApiUtils
{
    const MODULE_NAME = 'mrshopapi';

    public static function getFullDomain($add_base_uri = true)
    {
        return Context::getContext()->shop->getBaseURL(true, $add_base_uri);
    }

    public static function getFullUrl($relativeUrl)
    {
        $r = static::stripLeadingSlash($relativeUrl);

        return self::getFullDomain().$r;
    }

    public static function stripLeadingSlash($path)
    {
        return ltrim($path, '/');
    }

    /**
     * @return false|\MrShopApi
     */
    public static function getModule()
    {
        return Module::getInstanceByName('mrshopapi');
    }

    // Ottiene la prima valuta attiva
    public static function getFirstCurrency()
    {
        // La prima valuta attiva
        $tmp = Db::getInstance()->executeS('
            SELECT `id_currency`
            FROM `'._DB_PREFIX_.'currency`
            WHERE `deleted` = 0
            AND `active` = 1
            ORDER BY `id_currency` ASC');

        return (int) (count($tmp) > 0 ?  ($tmp[0]['id_currency']) : Configuration::get('PS_CURRENCY_DEFAULT'));
    }

    public static function includeTaxes($customer_id)
    {
        if (!Configuration::get('PS_TAX')) {
            return false;
        }

        return !Product::getTaxCalculationMethod($customer_id);
    }

    public static function getTemplateFullPath($relativeTplPath = null)
    {
        return version_compare(_PS_VERSION_, '1.7.0.0', '>=')
                ? 'module:mrshopapi/views/templates/front/'.$relativeTplPath
                : $relativeTplPath;
    }

    public static function getSettings()
    {
        // GDPR
        $isGdprActive = false;
        $registrationMessages = [];
        $profileMessages = [];

        if (Module::isEnabled('psgdpr')) {
            $isGdprActive = true;

            foreach (Language::getLanguages(true) as $lang) {
                $idLang = (int) $lang['id_lang'];
                $isoCode = $lang['iso_code'];
                $registrationMessages[$isoCode] = trim(Configuration::get('PSGDPR_CREATION_FORM', $idLang));
                $profileMessages[$isoCode] = trim(Configuration::get('PSGDPR_CUSTOMER_FORM', $idLang));
            }
        } else {
            foreach (Language::getLanguages(true) as $lang) {
                $idLang = (int) $lang['id_lang'];
                $isoCode = $lang['iso_code'];
                $registrationMessages[$isoCode] = trim(Configuration::get('MRSHOP_REGISTRATION_MSG', $idLang));
                $profileMessages[$isoCode] = trim(Configuration::get('MRSHOP_PROFILE_MSG', $idLang));
            }
        }

        $email = trim(Configuration::get('PS_SHOP_EMAIL'));
        $primaryColor = trim(Configuration::get('MRSHOP_PRIMARY_COLOR'));
        $secondaryColor = trim(Configuration::get('MRSHOP_SECONDARY_COLOR'));
        $textColorOnPrimary = trim(Configuration::get('MRSHOP_TEXT_COLOR_ON_PRIMARY'));
        $textColorOnSecondary = trim(Configuration::get('MRSHOP_TEXT_COLOR_ON_SECONDARY'));
        $darkStatusbar = (bool) Configuration::get('MRSHOP_DARK_STATUSBAR');

        if (!$darkSecondaryColor = Configuration::get('MRSHOP_DARK_SECONDARY_COLOR')) {
            $darkSecondaryColor = $secondaryColor;
        }

        if (!$darkTextColorOnSecondary = Configuration::get('MRSHOP_DARK_TEXT_COLOR_ON_SECONDARY')) {
            $darkTextColorOnSecondary = $textColorOnSecondary;
        }

        $roundedImages = (bool) Configuration::get('MRSHOP_ROUNDED_IMAGES');
        $showNavBar = (bool) Configuration::get('MRSHOP_SHOW_NAVBAR');
        $productsBackgroundGrey = (bool) Configuration::get('MRSHOP_PRODUCTS_BACKGROUND_GREY');

        if (!$productsShape = Configuration::get('MRSHOP_PRODUCTS_SHAPE')) {
            $productsShape = Enums::PRODUCTS_SHAPE_SQUARE;
        }

        if (!$appFont = Configuration::get('MRSHOP_APP_FONT')) {
            $appFont = 'System';
        }

        $termsAndConditions = (int) Configuration::get('PS_CONDITIONS_CMS_ID');
        $homeCategory = (int) Configuration::get('PS_HOME_CATEGORY');
        $productsLayout = (int) Configuration::get('MRSHOP_PRODUCTS_LAYOUT', null, null, null, 1);

        $ageVerificationActive = (bool) Configuration::get('MRSHOP_ENABLE_AGE_VERIFICATION');
        $ageVerificationMinAge = (int) Configuration::get('MRSHOP_AGE_VERIFICATION_MIN_AGE');

        return [
            'email' => empty($email) ? null : $email,
            'theme_primary_color' => ApiUtils::nullifyIfEmpty($primaryColor),
            'theme_secondary_color' => ApiUtils::nullifyIfEmpty($secondaryColor),
            'theme_text_color_on_primary' => ApiUtils::nullifyIfEmpty($textColorOnPrimary),
            'theme_text_color_on_secondary' => ApiUtils::nullifyIfEmpty($textColorOnSecondary),
            'theme_dark_statusbar' => $darkStatusbar,
            'theme_dark_secondary_color' => $darkSecondaryColor,
            'theme_dark_text_color_on_secondary' => $darkTextColorOnSecondary,
            'theme_rounded_images' => $roundedImages,
            'theme_product_detail_fixed_navbar' => $showNavBar,
            'theme_products_background_grey' => $productsBackgroundGrey,
            'theme_products_shape' => $productsShape,
            'theme_app_font' => $appFont,

            'pages' => ['terms_and_conditions' => ApiUtils::nullifyIfEmpty($termsAndConditions)],
            'home_category' => $homeCategory,
            'gdpr' => [
                'active' => $isGdprActive,
                'registration_message' => $registrationMessages,
                'profile_message' => $profileMessages,
                'delete_account' => true
            ],
            'age_verification' => [
                'active' => $ageVerificationActive,
                'minimum_age' => $ageVerificationMinAge,
            ],
            'products_layout' => $productsLayout,
        ];
    }

    public static function getSocialLogin()
    {
        $appleEnabled = false;
        $facebookEnabled = false;
        $googleEnabled = false;

        $facebookId = '';
        $googleIosId = '';
        $googleAndroidId = '';

        $socialLogin = Module::getInstanceByName('mrshopsociallogin');

        if (Validate::isLoadedObject($socialLogin) && $socialLogin->active) {
            $appleEnabled = (bool) Configuration::get('MRSHOP_APPLE_ENABLED_APP');
            $facebookEnabled = (bool) Configuration::get('MRSHOP_FACEBOOK_ENABLED_APP');
            $googleEnabled = (bool) Configuration::get('MRSHOP_GOOGLE_ENABLED_APP');

            // Client ID
            $facebookId = trim(Configuration::get('MRSHOPSOCIALLOGIN_FACEBOOK_ID'));
            $googleIosId = trim(Configuration::get('MRSHOP_GOOGLE_IOS_ID_APP'));
            $googleAndroidId = trim(Configuration::get('MRSHOP_GOOGLE_ID_APP'));
        }

        return [
            'apple' => ['enabled' => $appleEnabled],
            'facebook' => ['enabled' => $facebookEnabled, 'id' => $facebookId],
            'google' => ['enabled' => $googleEnabled, 'id' => ['android' => $googleAndroidId, 'ios' => $googleIosId]],
        ];
    }

    public static function getLanguagesOptions()
    {
        /** @var \Shop $shop */
        $shop = Context::getContext()->shop;
        $langs = Language::getLanguages(true, $shop->id);

        $retval = [];
        foreach ($langs as $lang) {
            $retval[] = [
                'value' => $lang['iso_code'],
                'text'    => $lang['name']
            ];
        }

        return $retval;
    }

    public static function nullifyIfEmpty($value)
    {
        return empty($value) ? null : $value;
    }

    public static function defaultIfNull(&$value, $default)
    {
        return isset($value) ? $value : $default;
    }

    public static function isArray($toValidate)
    {
        $result = is_array($toValidate);

        if (!$result && class_exists('AbstractLazyArray')) {
            $result = $toValidate instanceof AbstractLazyArray;
        }

        return $result;
    }

    public static function isAssociative(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    public static function isCatalogMode()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return Configuration::isCatalogMode();
        }

        return (bool) Configuration::get('PS_CATALOG_MODE') ||
            !ApiUtils::showPricesForCurrentGroup();
    }

    public static function showPricesForCurrentGroup()
    {
        return Group::isFeatureActive() ? (bool) Group::getCurrent()->show_prices : true;
    }

    public static function getEntityManager()
    {
        /** @var EntityManagerInterface $entityManager */
        return Context::getContext()->controller->get('doctrine.orm.entity_manager');
    }

    public static function composeRegEx($pattern)
    {
        $pattern = str_replace('.', '\.', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = str_replace('?', '\?', $pattern);

        return '/^'.$pattern.'/i';
    }

    /**
     * Replace all unsupoported mobile text view html tags
     *
     * @param string $text
     *
     * @return string $text formatted
     */
    public static function replaceUnsupportedTextViewTag($text)
    {
        $text = str_replace('<table', '<div', $text);
        $text = str_replace('</table>', '</div>', $text);

        $text = str_replace('<tbody', '<div', $text);
        $text = str_replace('</tbody>', '</div>', $text);

        $text = str_replace('<tr', '<div', $text);
        $text = str_replace('</tr>', '</div>', $text);

        $text = str_replace('<td', '<span', $text);
        $text = str_replace('</td>', '</span>', $text);

        $text = str_replace('<th', '<span', $text);
        $text = str_replace('</th>', '</span>', $text);

        $text = str_replace("\n", '</br>', $text);

        return $text;
    }

    public static function getUploadMaxSize()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = ApiUtils::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = ApiUtils::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        return $max_size;
    }

    private static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    public static function isFcmConfigured()
    {
        return !empty(Configuration::get('MRSHOP_FIREBASE_SERVICE_ACCOUNT'));
    }

    public static function isB2B()
    {
        return (bool) Configuration::get('PS_B2B_ENABLE');
    }

    public static function isGdprEnabled($gdprModule = false)
    {
        if (!isset($gdprModule)) {
            $gdprModule = Module::getInstanceByName('psgdpr');
        }

        if (!Validate::isLoadedObject($gdprModule) || !$gdprModule->active) {
            return false;
        }

        return true;
    }

    public static function isPS16()
    {
        return version_compare(_PS_VERSION_, '1.7.0', '<');
    }

    /**
     * Authenticate user into frontend
     * @param int $customerId
     */
    public static function startCustomerSession($customerId)
    {
        $context = Context::getContext();
        $customer = new Customer((int) $customerId);

        if (self::isPS16()) {
            $cookie = Context::getContext()->cookie;
            $cookie->id_compare = isset($cookie->id_compare)
                ? $cookie->id_compare
                : \CompareProduct::getIdCompareByIdCustomer($customer->id);
            $cookie->id_customer = (int) ($customer->id);
            $cookie->customer_lastname = $customer->lastname;
            $cookie->customer_firstname = $customer->firstname;
            $cookie->logged = 1;
            $customer->logged = 1;
            $cookie->is_guest = $customer->isGuest();
            $cookie->passwd = $customer->passwd;
            $cookie->email = $customer->email;
            $context->customer = $customer;
            $context->customer->id_customer = (int) $customer->id;
            $context->customer->secure_key = $customer->secure_key;
            $context->customer->save();

            if (Configuration::get('PS_CART_FOLLOWING') && (empty($context->cookie->id_cart) || \Cart::getNbProducts($context->cookie->id_cart) == 0) && $id_cart = (int) \Cart::lastNoneOrderedCart($context->customer->id)) {
                $context->cart = new \Cart($id_cart);
            } else {
                $id_carrier = (int) $context->cart->id_carrier;
                $context->cart->id_carrier = 0;
                $context->cart->setDeliveryOption(null);
                $context->cart->id_address_delivery = (int) \Address::getFirstCustomerAddressId((int) ($customer->id));
                $context->cart->id_address_invoice = (int) \Address::getFirstCustomerAddressId((int) ($customer->id));
            }
            $context->cart->id_customer = (int) $customer->id;
            $context->cart->secure_key = $customer->secure_key;

            if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                $delivery_option = [$context->cart->id_address_delivery => $id_carrier.','];
                $context->cart->setDeliveryOption($delivery_option);
            }

            $context->cart->save();
            $context->cookie->id_cart = (int) $context->cart->id;
            $cookie->write();
            $context->cart->autosetProductAddress();
            \Hook::exec('actionAuthentication', ['customer' => $context->customer]);
        } else {
            $context->updateCustomer($customer);
        }
    }

    /**
     *
     * @return FidelityHandlerInterface
     * @throws \RuntimeException
     */
    public static function getFidelityHandler()
    {
        $handlers = [
            AdvansedCouponFidelityHandler::class,
            AllinoneRewardsFidelityHandler::class
        ];

        foreach ($handlers as $fqcn) {
            if (call_user_func("$fqcn::isActive")) {
                return new $fqcn;
            }
        }

        return new InactiveFidelityHandler;
    }

    public static function isAppRequested()
    {
        return (bool) Configuration::get('MRSHOP_APP_REQUESTED');
    }

    public static function setAppRequested($requested = true)
    {
        Configuration::updateValue('MRSHOP_APP_REQUESTED', $requested);
        if ($requested && (int) Configuration::get('MRSHOP_LAST_ALLOWED_STEP') != AppConfigurationHandler::MAX_STEPS) {
            Configuration::updateValue('MRSHOP_LAST_ALLOWED_STEP', AppConfigurationHandler::MAX_STEPS);
        }
    }

    public static function cacheBurstedUrl($fsPath)
    {
        $fpath = _PS_ROOT_DIR_.$fsPath;
        $url   = static::getFullUrl($fsPath);

        if (file_exists($fpath)) {
            $url .= '?rnd='.filemtime($fpath);
        }

        return $url;
    }

    public static function assetUrl($viewPath)
    {
        return self::cacheBurstedUrl('/modules/'.self::MODULE_NAME.'/views'.$viewPath);
    }

    public static function isModuleActive($moduleName, $modulesList)
    {
        $index = md5($moduleName);
        if (is_array($modulesList) && array_key_exists($index, $modulesList)) {
            return $modulesList[$index] == sha1($moduleName.true);
        }

        return false;
    }

    public static function refactorRow($row, $apiVersion)
    {
        if (false == self::isArray($row)) {
            return false;
        }

        $data = [];
        foreach ($row as $key => $value) {
            switch (true) {
                case strpos($key, 'quantity') !== false:
                    if ($apiVersion <= 11) {
                        $data[$key] = (int) $value;
                    } else {
                        $data[$key] = (float) $value;
                    }

                    break;
                    // INTVAL
                case strpos($key, 'id') === 0:
                case strpos($key, '_id') !== false:
                case strpos($key, 'position') === 0:
                    $data[$key] = (int) $value;

                    break;
                    // FLOATVAL
                case strpos($key, 'price') !== false:
                    $data[$key] = (float) $value;

                    break;
                    // BOOLEAN
                case $key == "active":
                case strpos($key, 'show_') === 0:
                case strpos($key, 'is_') === 0:
                case strpos($key, 'need_') === 0:
                case strpos($key, 'on_') === 0:
                    $data[$key] = (bool) $value;

                    break;
                default:
                    $data[$key] = $value;

                    break;
            }
        }

        return $data;
    }

    public static function updateModulesList($modules)
    {
        $modulesList = [];

        foreach ($modules as $moduleName => $enabled) {
            $modulesList[md5($moduleName)] = sha1($moduleName.$enabled);
        }

        Configuration::updateValue('POHS_RM_SUTATS', json_encode($modulesList));
    }

    public static function isPsBilling()
    {
        return !((bool) Configuration::get('_FROM_PADDLE_', null, null, null, true));
    }
}
