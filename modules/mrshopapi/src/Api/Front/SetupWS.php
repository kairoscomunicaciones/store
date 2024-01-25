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
use Currency;
use Exception;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\AuthHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\StripeManager;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\CustomFormUtils;
use Shop;

class SetupWS extends BaseWS
{
    public function getList($params, $id_customer = null)
    {
        $data = ApiUtils::getSettings();
        $authHandler = new AuthHandler($this->dataHandler);
        $socialEnabled = false;

        if ($this->isModuleActive('ps_socialfollow') ||
            $this->isModuleActive('blocksocial')) {
            $facebookUrl = null;
            $instagramUrl = null;
            $twitterUrl = null;
            $youtubeUrl = null;
            $googlePlusUrl = null;
            $pinterestUrl = null;
            $vimeoUrl = null;

            try {
                $idLang = null;
                if ($this->isModuleActive('ps_socialfollow')) {
                    $socialFollow = Module::getInstanceByName('ps_socialfollow');
                    if (version_compare($socialFollow->version, '2.2.0', '>=') === true) {
                        $idLang = $this->context->language->id;
                    }
                }
                $configuration = Configuration::getMultiple(
                    [
                        'BLOCKSOCIAL_FACEBOOK',
                        'BLOCKSOCIAL_INSTAGRAM',
                        'BLOCKSOCIAL_TWITTER',
                        'BLOCKSOCIAL_YOUTUBE',
                        'BLOCKSOCIAL_GOOGLE_PLUS',
                        'BLOCKSOCIAL_PINTEREST',
                        'BLOCKSOCIAL_VIMEO',
                    ],
                    $idLang
                );

                $facebookUrl = $configuration['BLOCKSOCIAL_FACEBOOK'];
                $instagramUrl = $configuration['BLOCKSOCIAL_INSTAGRAM'];
                $twitterUrl = $configuration['BLOCKSOCIAL_TWITTER'];

                $youtubeUrl = $configuration['BLOCKSOCIAL_YOUTUBE'];
                $googlePlusUrl = $configuration['BLOCKSOCIAL_GOOGLE_PLUS'];
                $pinterestUrl = $configuration['BLOCKSOCIAL_PINTEREST'];
                $vimeoUrl = $configuration['BLOCKSOCIAL_VIMEO'];
            } catch (Exception $exception) {
            }

            $data['facebook_url'] = ApiUtils::nullifyIfEmpty($facebookUrl);
            $data['instagram_url'] = ApiUtils::nullifyIfEmpty($instagramUrl);
            $data['twitter_url'] = ApiUtils::nullifyIfEmpty($twitterUrl);
            $data['youtube_url'] = ApiUtils::nullifyIfEmpty($youtubeUrl);
            $data['googleplus_url'] = ApiUtils::nullifyIfEmpty($googlePlusUrl);
            $data['pinterest_url'] = ApiUtils::nullifyIfEmpty($pinterestUrl);
            $data['vimeo_url'] = ApiUtils::nullifyIfEmpty($vimeoUrl);

            $socialEnabled = $data['facebook_url']
                || $data['instagram_url']
                || $data['twitter_url']
                || $data['youtube_url']
                || $data['googleplus_url']
                || $data['pinterest_url']
                || $data['vimeo_url'];
        }

        $wishlistEnabled = false;
        if ($this->isModuleActive('mrshopblockwishlist')) {
            $wishlistEnabled = true;
        }

        $mailNotificationsEnabled = false;
        if ($this->isModuleActive('mrshopmailnotifications')) {
            $mailNotificationsEnabled = true;
        }

        $socialLoginEnabled = false;
        if ($this->isModuleActive('mrshopsociallogin')) {
            $socialLoginEnabled = true;
        }

        $checkoutCustomFieldsEnabled = false;
        if ($this->isModuleActive('customfields')) {
            $checkoutCustomFieldsEnabled = true;
        }

        $data['wishlist_available'] = $wishlistEnabled;
        $data['mailnotifications_available'] = $mailNotificationsEnabled;
        $data['relatedproducts_available'] = true;
        $data['socialfollow_available'] = $socialEnabled;
        $data['sociallogin_available'] = $socialLoginEnabled;
        $data['checkout_custom_fields_available'] = $checkoutCustomFieldsEnabled;

        if ($socialLoginEnabled) {
            $data['sociallogin_apple_enabled'] = (bool) Configuration::get('MRSHOP_APPLE_ENABLED_APP');
            $data['sociallogin_facebook_enabled'] = (bool) Configuration::get('MRSHOP_FACEBOOK_ENABLED_APP');
            $data['sociallogin_google_enabled'] = (bool) Configuration::get('MRSHOP_GOOGLE_ENABLED_APP');
        }

        $data['upload_max_size'] = ApiUtils::getUploadMaxSize();

        $data['default_language'] = (int) Configuration::get('PS_LANG_DEFAULT');

        $data['address_fields'] = $this->getAddressFieldsByCountry();

        $data['show_prices'] = true;

        $data['whatsapp_support'] = Configuration::get('MRSHOP_SHOW_WHATSAPP_SUPPORT') ? Configuration::get('MRSHOP_WHATSAPP_SUPPORT') : null;

        $data['display_product_quantities'] = (bool) Configuration::get('PS_DISPLAY_QTIES');
        $data['last_quantities_threshold'] = (int) Configuration::get('PS_LAST_QTIES');

        $data['show_product_stock_quantity'] = (bool) Configuration::get('MRSHOP_SHOW_PRODUCT_STOCK_QUANTITY');
        $data['show_availability'] = (bool) Configuration::get('MRSHOP_SHOW_PRODUCTS_AVAILABILITY');

        $data['navigation_type'] = (int) Configuration::get('MRSHOP_NAVIGATION_TYPE');
        $data['catalog_layout'] = (int) Configuration::get('MRSHOP_CATALOG_LAYOUT');

        $stripeManager = new StripeManager('stripe_official', $this->dataHandler);

        if ($stripeManager->isValidPaymentMethod()) {
            $config = $stripeManager->getConfiguration();
            $data['stripe_token'] = $config['publishable'];
        }

        $currencies = [];

        $defaultCurrency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        foreach (Currency::getCurrenciesByIdShop(Context::getContext()->shop->id) as $currency) {
            if ((bool) $currency['active'] && !(bool) $currency['deleted']) {
                $currencies[] = [
                    'id' => (int) $currency['id_currency'],
                    'name' => $currency['name'],
                    'iso_code' => $currency['iso_code'],
                    'sign' => $currency['sign'],
                    'is_default' => (bool) ((int) $currency['id_currency'] === $defaultCurrency),
                ];
            }
        }

        $data['currencies'] = $currencies;

        $storesCount = $this->getStoresCount();

        if ($storesCount > 0) {
            $data['show_stores'] = true;
            /* Single store id, null = multiple stores, number = id of the unique store */
            $store = $this->getStore();

            $data['single_store_id'] = $storesCount == 1 ? $store['id'] : null;

            $data['stores_default_lat'] = $store['lat'];
            $data['stores_default_lng'] = $store['lng'];
        } else {
            $data['show_stores'] = false;
            $data['single_store_id'] = null;
            $data['stores_default_lat'] = null;
            $data['stores_default_lng'] = null;
        }

        $data['b2b'] = ApiUtils::isB2B();

        $data['registration_fields'] = CustomFormUtils::getRegistrationFields();

        $this->addCustomerFields($data, $id_customer);

        // If guest_checkout is enabled, recover fields
        $data['guest_registration_fields'] = null;
        if (!empty(Configuration::get('PS_GUEST_CHECKOUT_ENABLED'))) {
            $data['guest_registration_fields'] = CustomFormUtils::getRegistrationFields(['passwd']);
        }

        $data['is_order_returnable'] = (bool) Configuration::get('PS_ORDER_RETURN', null, null, $this->context->shop->id);
        $data['product_reviews_enabled'] = $this->dataHandler->isModuleActive('productcomments');
        $data['assistance_chat_enabled'] = (bool) version_compare(_PS_VERSION_, '1.7.0.0', '>=');

        $this->response(true, null, $data);
    }

    private function addCustomerFields(&$data, $id_customer = null)
    {
        if ($id_customer == null && $this->dataHandler->getGuestId() == null) {
            $cartHandler = new CartHandler($this->dataHandler->getGuestId());
            $cart = $cartHandler->getCart($id_customer, true, $this->getCurrencyId());
            $data['id_guest'] = (int) $cart->id_guest;
        } else {
            $data['id_guest'] = (int) $this->dataHandler->getGuestId();
        }

        $data["fidelity_available"] = ApiUtils::getFidelityHandler()->isFidelityAvailable();

        $scratchToWin = [
            'enabled' => false,
        ];
        if ($this->isModuleActive('mrshopscratchtowin') && $id_customer != null) {
            require_once _PS_MODULE_DIR_.'mrshopscratchtowin/src/Services/ScratchToWinManager.php';
            $scratchToWinManager = new \MrAPPs\MrShopScratchToWin\Services\ScratchToWinManager(Module::getInstanceByName('mrshopscratchtowin'));
            $scratchData = $scratchToWinManager->setup();
            $scratchToWinEnabled = $scratchData['enabled'];
            $scratchToWin['enabled'] = $scratchToWinEnabled;

            if ($scratchToWinEnabled) {
                $scratchToWin['description'] = $scratchData['description'];
                $scratchToWin['when_scratch_available'] = $scratchData['when_scratch_available'];
            }
        }

        $data['scratch_to_win'] = $scratchToWin;

        $data['is_catalog'] = ApiUtils::isCatalogMode();
    }

    public function customResponse(&$cachedResponse, $id_customer = null)
    {
        $this->addCustomerFields($cachedResponse, $id_customer);
    }

    private function getStoresCount()
    {
        $result = $this->refactorData('SELECT DISTINCT COUNT(a.id_store) AS stores
                  FROM `'._DB_PREFIX_.'store` a
                  '.Shop::addSqlAssociation('store', 'a').'
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country 
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id, false);

        if ($result) {
            return (int) $result['stores'];
        }

        return 0;
    }

    private function getStore()
    {
        $result = $this->refactorData('SELECT DISTINCT a.id_store, a.latitude, a.longitude
                  FROM `'._DB_PREFIX_.'store` a 
                  '.Shop::addSqlAssociation('store', 'a').' 
                  INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON cl.id_country = a.id_country 
                  WHERE a.active = 1 AND cl.id_lang='.(int) Context::getContext()->language->id, false);

        if ($result) {
            $response = [
                'lat' => (float) $result['latitude'],
                'lng' => (float) $result['longitude'],
            ];

            $response['id'] = (int) $result['id_store'];

            return $response;
        } else {
            return null;
        }
    }
}
