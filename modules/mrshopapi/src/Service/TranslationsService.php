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
 * @copyright Mr. APPs 2021
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Service;

use AppHome;
use Configuration;
use Context;
use Group;
use MrAPPs\MrShopApi\Handler\CmsPageHandler;
use MrShopApiNotification;
use OrderState;

class TranslationsService
{
    private $module;

    private $cmsHandler;

    private $translations = [];

    public function __construct($module)
    {
        $this->module = $module;
        $this->cmsHandler = new CmsPageHandler();
        $this->initTranslations();
    }

    public function getJson()
    {
        $retval = [];
        foreach ($this->translations as $path => $text) {
            $p = explode('.', $path);
            $retval = $this->injectInto($retval, $p, $text);
        }

        return $retval;
    }

    public function getForField($field)
    {
        $path = $field;

        return $this->getForPath($path);
    }

    public function getForPath($path)
    {
        return isset($this->translations[$path])
            ? $this->translations[$path]
            : null;
    }

    public function getSectionsType()
    {
        return [
            AppHome::$TYPE_BANNER            => $this->module->l('Banner', 'translationsservice'),
            AppHome::$TYPE_CAROUSEL          => $this->module->l('Carousel', 'translationsservice'),
            AppHome::$TYPE_SHOWCASE_PRODUCTS => $this->module->l('Showcase Products', 'translationsservice'),
            AppHome::$TYPE_SPECIAL_OFFERS    => $this->module->l('Special Offers', 'translationsservice'),
            AppHome::$TYPE_NEW_PRODUCTS      => $this->module->l('New Products', 'translationsservice'),
            AppHome::$TYPE_MANUFACTURERS     => $this->module->l('Manufacturers', 'translationsservice'),
            AppHome::$TYPE_CATEGORY_PRODUCTS => $this->module->l('Category products', 'translationsservice'),
            AppHome::$TYPE_LVL_ONE_CATEGORIES => $this->module->l('Main categories', 'translationsservice'),
            AppHome::$TYPE_BEST_SELLER       => $this->module->l('Best seller', 'translationsservice')
            // AppHome::$TYPE_CATEGORIES        => $this->module->l('Categories', 'translationsservice'),
        ];
    }

    public function getCarouselsType()
    {
        return [
            AppHome::$CAROUSEL_DEFAULT => $this->module->l('Default', 'translationsservice'),
            AppHome::$CAROUSEL_FULL => $this->module->l('Full', 'translationsservice'),
            AppHome::$CAROUSEL_MARGIN => $this->module->l('Margin', 'translationsservice')
        ];
    }

    public function getSectionProductsLayouts()
    {
        return [
            1 => $this->module->l('Horizontal', 'translationsservice'),
            2 => $this->module->l('Vertical', 'translationsservice'),
        ];
    }

    public function getBannerTypes()
    {
        return [
            AppHome::$BANNER_TYPE_NO_LINK        => $this->module->l('No Link', 'translationsservice'),
            AppHome::$BANNER_TYPE_PRODUCT_DETAIL => $this->module->l('Product Detail', 'translationsservice'),
            AppHome::$BANNER_TYPE_CATEGORY_BRAND => $this->module->l('Category/Brand', 'translationsservice'),
            AppHome::$BANNER_TYPE_SPECIAL_OFFERS => $this->module->l('Special Offers', 'translationsservice'),
            AppHome::$BANNER_TYPE_NEW_PRODUCTS   => $this->module->l('New Products', 'translationsservice'),
            AppHome::$BANNER_TYPE_SHOWCASE       => $this->module->l('Showcase Products', 'translationsservice'),
            AppHome::$BANNER_TYPE_CMS            => $this->module->l('CMS Page', 'translationsservice'),
        ];
    }

    public function getBannerSizes()
    {
        return [
            AppHome::$BANNER_SIZE_RECTANGLE_HORIZONTAL        => $this->module->l('Horizontal Rectangle', 'translationsservice'),
            AppHome::$BANNER_SIZE_RECTANGLE_HORIZONTAL_HALF   => $this->module->l('Half Horizontal Rectangle', 'translationsservice'),
            AppHome::$BANNER_SIZE_RECTANGLE_VERTICAL          => $this->module->l('Vertical Rectangle', 'translationsservice'),
            AppHome::$BANNER_SIZE_RECTANGLE_VERTICAL_HALF     => $this->module->l('Half Vertical Rectangle', 'translationsservice'),
            AppHome::$BANNER_SIZE_SQUARE                      => $this->module->l('Square', 'translationsservice'),
            AppHome::$BANNER_SIZE_SQUARE_HALF                 => $this->module->l('Half Square', 'translationsservice'),
        ];
    }

    public function getSectionProductsSortings()
    {
        return [
            AppHome::SORT_NAME_ASC       => $this->module->l('Name: from A to Z', 'translationsservice'),
            AppHome::SORT_NAME_DESC      => $this->module->l('Name: from Z to A', 'translationsservice'),
            AppHome::SORT_PRICE_ASC      => $this->module->l('Price: lowest first', 'translationsservice'),
            AppHome::SORT_PRICE_DESC     => $this->module->l('Price: highest first', 'translationsservice'),
            AppHome::SORT_CREATE_ASC     => $this->module->l('Date add: oldest first', 'translationsservice'),
            AppHome::SORT_CREATE_DESC    => $this->module->l('Date add: recent first', 'translationsservice'),
            AppHome::SORT_UPDATE_ASC     => $this->module->l('Date edit: oldest first', 'translationsservice'),
            AppHome::SORT_UPDATE_DESC    => $this->module->l('Date edit: recent first', 'translationsservice'),
            AppHome::SORT_BRAND_ASC      => $this->module->l('Brand: from A to Z', 'translationsservice'),
            AppHome::SORT_BRAND_DESC     => $this->module->l('Brand: from Z to A', 'translationsservice'),
            AppHome::SORT_QUANT_ASC      => $this->module->l('Quantity ascending', 'translationsservice'),
            AppHome::SORT_QUANT_DESC     => $this->module->l('Quantity descending', 'translationsservice'),
            AppHome::SORT_REFERENCE_ASC  => $this->module->l('Reference: from A to Z', 'translationsservice'),
            AppHome::SORT_REFERENCE_DESC => $this->module->l('Reference: from Z to A', 'translationsservice')
        ];
    }

    public function getCatalogueLayoutTypes()
    {
        return [
            0 => $this->module->l('Image Layout', 'translationsservice'),
            1 => $this->module->l('List Layout', 'translationsservice'),
        ];
    }

    public function getCustomerGroups()
    {
        $groups = Group::getGroups(Context::getContext()->language->id);

        $retval = [
            0 =>  $this->module->l('All', 'translationsservice')
        ];
        foreach ($groups as $s) {
            $retval[(int) $s['id_group']] = $s['name'];
        }

        return $retval;
    }

    public function getOrderStatusTypes()
    {
        $states = OrderState::getOrderStates(Context::getContext()->language->id);

        $retval = [];
        foreach ($states as $s) {
            $retval[(int) $s['id_order_state']] = $s['name'];
        }

        return $retval;
    }

    public function getNotificationTypes()
    {
        return [
            MrShopApiNotification::$TYPE_GENERIC => $this->module->l('Generic', 'translationsservice'),
            MrShopApiNotification::$TYPE_PRODUCT_DETAIL => $this->module->l('Product detail', 'translationsservice'),
            MrShopApiNotification::$TYPE_CATEGORY_BRAND => $this->module->l('Category/Brand', 'translationsservice'),
            MrShopApiNotification::$TYPE_SPECIAL_OFFERS => $this->module->l('Special offers', 'translationsservice'),
            MrShopApiNotification::$TYPE_NEW_PRODUCTS => $this->module->l('New products', 'translationsservice'),
            MrShopApiNotification::$TYPE_CMS => $this->module->l('Cms', 'translationsservice')
        ];
    }

    public function getAppNavigationTypes()
    {
        return [
            0 => $this->module->l('Tab bar', 'translationsservice'),
            1 => $this->module->l('Sidebar', 'translationsservice')
        ];
    }

    public function getProductRowLayout()
    {
        return [
            1 => $this->module->l('Two products layout', 'translationsservice'),
            2 => $this->module->l('One product layout', 'translationsservice'),
        ];
    }

    public function getProductsShape()
    {
        return [
            'SQUARE' => $this->module->l('Square', 'translationsservice'),
            'RECTANGLE' => $this->module->l('Rectangle', 'translationsservice'),
        ];
    }

    public function getAppFonts()
    {
        return [
            'System' => $this->module->l('System', 'translationsservice'),
            'Roboto' => $this->module->l('Roboto', 'translationsservice'),
            'RobotoCondensed' => $this->module->l('Roboto Condensed', 'translationsservice'),
            'OpenSans' => $this->module->l('Open Sans', 'translationsservice'),
            'OpenSansCondensed' => $this->module->l('Open Sans Condensed', 'translationsservice'),
            'Montserrat' => $this->module->l('Montserrat', 'translationsservice'),
            'Lato' => $this->module->l('Lato', 'translationsservice'),
            'Poppins' => $this->module->l('Poppins', 'translationsservice'),
            'PlayfairDisplay' => $this->module->l('Playfair Display', 'translationsservice'),
            'FiraSans' => $this->module->l('Fira Sans', 'translationsservice'),
            'Merriweather' => $this->module->l('Merriweather', 'translationsservice'),
        ];
    }

    public function getCmsPageTypes()
    {
        $types = $this->cmsHandler->getCmsPageTypes();

        $retval = [];
        if (isset($types) && count($types) > 0) {
            foreach ($types as $type) {
                $retval[(int) $type['id_cms']] = $type['meta_title'].' - '.$type['meta_description'];
            }
        }

        return $retval;
    }

    protected function injectInto($array, $path, $final)
    {
        if (false == isset($array[$path[0]])) {
            $array[$path[0]] = [];
        }
        if (count($path) == 1) {
            $array[$path[0]] = $final;
        } else {
            $array[$path[0]] = $this->injectInto($array[$path[0]], array_splice($path, 1), $final);
        }

        return $array;
    }

    protected function initTranslations()
    {
        $appRequested = (bool) Configuration::get('MRSHOP_APP_REQUESTED');
        $transAppRequested = $this->module->l('Requested', 'translationsservice');
        $transAppNotRequested = $this->module->l('Not requested', 'translationsservice');
        $appStatus = $appRequested ? $transAppRequested : $transAppNotRequested;
        $requestApp = $appRequested
                ? $this->module->l('Request edit', 'translationsservice')
                : $this->module->l('Request app', 'translationsservice');

        if (Context::getContext()->language->iso_code == "it") {
            $documentationUrl = "https://docs.google.com/document/d/1IZhEwhyTRP4esDe2qNoeOPW7OzQKP8soJy8HNZGT56o/edit?usp=sharing";
            $paypalUrl         = "https://docs.google.com/document/d/1lHY_2wkOave56prd5Er9lymdfW1xapP4VWFvrGPtN8I/edit?usp=sharing";

            $googlePlayUrl = "https://docs.google.com/document/d/1P50DsrNTb7rgLYeTpHcJnxlDWXwNThRg1E1o_5x_JYA/edit?usp=sharing";
            $appleAppStoreUrl = "https://docs.google.com/document/d/1Vy7k6W0UCVAJI6KMCJBlpn0Al5FDA1_c-c74sI6ukh4/edit?usp=sharing";
        } else {
            $documentationUrl = "https://docs.google.com/document/d/1o9ownE-25n_6PB-db3hR8shbpUQLo_1EhRmeQpzfYW8/edit?usp=sharing";
            $paypalUrl         = "https://docs.google.com/document/d/1CvJtBAivsZMlxyfCbiQpPStGg5sps9RLf_1M0aSzIUU/edit?usp=sharing";

            $googlePlayUrl = "https://docs.google.com/document/d/1QStKDYYGZC031-XGgWgzT9XQGoQvZW7HT5rrsiuZD9c/edit?usp=sharing";
            $appleAppStoreUrl = "https://docs.google.com/document/d/1pqU1BysxxNdyy3MQUtIDKANdmmhuVa9tYCrDybLXMhw/edit?usp=sharing";
        }

        $widgetDoc = sprintf(
            $this->module->l('Read our documentation to know how to setup the module and keep up to date with latest features: Read %1$shere%2$s', 'translationsservice'),
            '<a href="'.$documentationUrl.'" target="_blank"><strong>',
            '</strong></a>'
        );

        $paypalDocs = sprintf(
            $this->module->l('Read %1$shere%2$s to know how to configure PayPal', 'translationsservice'),
            '<a href="'.$paypalUrl.'" target="_blank"><strong>',
            '</strong></a>'
        );

        $playStoreLink = 'https://play.google.com/store/apps/details?id=it.ecommerceapp.showcase';
        $appStoreLink  = 'https://apps.apple.com/us/app/blue-fashion/id1435912682';

        $modalHintL   = $this->module->l('Install the Mr Shop App for your mobile device from %1$sGoogle&nbsp;Play&nbsp;Store%2$s or %3$sApple&nbsp;App&nbsp;Store%4$s and scan the QR code to see live app preview with currently saved configuration', 'translationsservice');

        $modalHint = sprintf(
            $modalHintL,
            '<a href="'.$playStoreLink.'" target="_blank"><strong>',
            '</strong></a>',
            '<a href="'.$appStoreLink.'" target="_blank"><strong>',
            '</strong></a>'
        );

        $facebookAdminIdMessage = sprintf(
            $this->module->l('To complete the configuration, please add the following ID as Administrator in your Facebook App: %1$s', 'translationsservice'),
            '<strong>1405398610</strong>'
        );

        $firebaseStepsMessage = sprintf(
            "%s <a href='https://console.firebase.google.com' target='_blank'>https://console.firebase.google.com</a><br/>%s <b>%s</b><br/>%s <b>%s</b>.<br/>%s <b>%s</b> %s.<br/>%s <b>%s</b> %s <b>mrappsshop@gmail.com</b> %s <b>%s</b> %s. <br/>%s <b>%s</b>. <br/>%s <b>%s</b> %s. <br/>%s",
            $this->module->l('Step 1: Log in with your Google Account on', 'translationsservice'),
            $this->module->l('Step 2: Click on', 'translationsservice'),
            $this->module->l('Add Project', 'translationsservice'),
            $this->module->l('Step 3: Choose a name for the project and click', 'translationsservice'),
            $this->module->l('Continue', 'translationsservice'),
            $this->module->l('Step 4: Once you created the project, access to', 'translationsservice'),
            $this->module->l('Users and permissions', 'translationsservice'),
            $this->module->l('section clicking on the settings icon', 'translationsservice'),
            $this->module->l('Step 5: Click on', 'translationsservice'),
            $this->module->l('Add Member', 'translationsservice'),
            $this->module->l('and enter the Email', 'translationsservice'),
            $this->module->l('with', 'translationsservice'),
            $this->module->l('Owner', 'translationsservice'),
            $this->module->l('permission', 'translationsservice'),
            $this->module->l('Step 6: Access the Service accounts section (always in the Settings section) and click', 'translationsservice'),
            $this->module->l('Generate new private key', 'translationsservice'),
            $this->module->l('Step 7: Click', 'translationsservice'),
            $this->module->l('Generate Key', 'translationsservice'),
            $this->module->l('in the popup that opens and download the related file', 'translationsservice'),
            $this->module->l('Step 8: Upload the newly downloaded json file in the following field', 'translationsservice')
        );

        $this->translations = [
            'qrCode.title'   => $this->module->l('QR Code', 'translationsservice'),
            'qrCode.android' => $this->module->l('Android', 'translationsservice'),
            'qrCode.ios'     => $this->module->l('iOS', 'translationsservice'),
            'defaultLanguage' => $this->module->l('App Language', 'translationsservice'),
            'name'            => $this->module->l('App name', 'translationsservice'),
            'icon'            => $this->module->l('Icon', 'translationsservice'),
            'splash'          => $this->module->l('Splash', 'translationsservice'),
            'mainColor'       => $this->module->l('Primary Color', 'translationsservice'),
            'textMainColor'   => $this->module->l('Text Color on Primary Color', 'translationsservice'),
            'secondaryColor'  => $this->module->l('Secondary Color', 'translationsservice'),
            'textSecondaryColor' => $this->module->l('Text Color on Secondary Color', 'translationsservice'),
            'isBlackText'     => $this->module->l('Use black color for status bar text', 'translationsservice'),
            'navigationType'  => $this->module->l('Navigation type', 'translationsservice'),
            'productsShape' => $this->module->l('Products Shape', 'translationsservice'),
            'catalogLayoutType' => $this->module->l('Catalog layout', 'translationsservice'),
            'productsLayoutType' => $this->module->l('Products layout', 'translationsservice'),
            'darkSecondaryColor' => $this->module->l('Secondary Color', 'translationsservice'),
            'darkTextColorOnSecondary' => $this->module->l('Text Color on Secondary Color', 'translationsservice'),
            'appFont' => $this->module->l('App Font', 'translationsservice'),
            'productsBackgroundGrey' => $this->module->l('Show grey brackground', 'translationsservice'),
            'roundedImages' => $this->module->l('Show rounded images', 'translationsservice'),
            'showNavBar' => $this->module->l('Show Navigation Bar', 'translationsservice'),
            'showStockQt' => $this->module->l('Show product stock quantity', 'translationsservice'),
            'showAvailable' => $this->module->l('Show availability', 'translationsservice'),
            'showLogo' => $this->module->l('Show logo', 'translationsservice'),
            'logo' => $this->module->l('Logo', 'translationsservice'),
            'description' => $this->module->l('Description', 'translationsservice'),
            'shortDescription' => $this->module->l('Short Description', 'translationsservice'),
            'iosKeywords' => $this->module->l('iOS Keywords', 'translationsservice'),
            'showWhatsappSupport' => $this->module->l('Show WhatsApp Support', 'translationsservice'),
            'whatsappSupportNumber' => $this->module->l('WhatsApp Support Number', 'translationsservice'),
            'hideAppPreview' => $this->module->l('Hide App Preview', 'translationsservice'),
            'showAppPreview' => $this->module->l('Show App Preview', 'translationsservice'),
            'actionsButton' => $this->module->l('Actions', 'translationsservice'),
            'hideActions' => $this->module->l('Hide Actions', 'translationsservice'),
            'showActions' => $this->module->l('Show Actions', 'translationsservice'),
            'table.headers.id' => $this->module->l('ID', 'translationsservice'),
            'table.headers.selected' => $this->module->l('Selected', 'translationsservice'),
            'table.headers.type' => $this->module->l('Type', 'translationsservice'),
            'table.headers.image' => $this->module->l('Image', 'translationsservice'),
            'table.headers.title' => $this->module->l('Title', 'translationsservice'),
            'table.headers.active' => $this->module->l('Active', 'translationsservice'),
            'table.headers.attivationType' => $this->module->l('Active period', 'translationsservice'),
            'table.headers.order' => $this->module->l('Position', 'translationsservice'),
            'table.headers.updateAction' => $this->module->l('Update', 'translationsservice'),
            'table.headers.deleteAction' => $this->module->l('Delete', 'translationsservice'),
            'table.headers.modulename' => $this->module->l('Module Name', 'translationsservice'),
            'table.headers.version' => $this->module->l('Version', 'translationsservice'),
            'table.headers.download' => $this->module->l('Download', 'translationsservice'),
            'table.headers.installed' => $this->module->l('Installed', 'translationsservice'),
            'table.headers.updated' => $this->module->l('Updated', 'translationsservice'),
            'modules.notInstalled' => $this->module->l('Not installed', 'translationsservice'),
            'table.headers.changelog' => $this->module->l('What\'s New', 'translationsservice'),
            'table.actionButtons.enableSelected' => $this->module->l('Enable', 'translationsservice'),
            'table.actionButtons.disableSelected' => $this->module->l('Disable', 'translationsservice'),
            'table.actionButtons.deleteSelected' => $this->module->l('Delete', 'translationsservice'),
            'table.messages.noSectionError' => $this->module->l('At least an home section is required', 'translationsservice'),
            'dialogSection.globalFields.type' => $this->module->l('Type', 'translationsservice'),
            'dialogSection.globalFields.title' => $this->module->l('Title', 'translationsservice'),
            'dialogSection.globalFields.active' => $this->module->l('Active', 'translationsservice'),
            'dialogSection.bannerFields.bannerType' => $this->module->l('Banner Type', 'translationsservice'),
            'dialogSection.bannerFields.bannerSize' => $this->module->l('Banner Size', 'translationsservice'),
            'dialogSection.bannerFields.image.label' => $this->module->l('Banner Image', 'translationsservice').'(.jpg,.png)',
            'dialogSection.bannerFields.image.message' => sprintf($this->module->l('Suggested banner dimension: %d x %d', 'translationsservice'), 1000, 500),
            'dialogSection.additionalBannerFields.product'  => $this->module->l('Product', 'translationsservice'),
            'dialogSection.additionalBannerFields.startTyping' => $this->module->l('Start typing to search', 'translationsservice'),
            'dialogSection.additionalBannerFields.category' => $this->module->l('Category', 'translationsservice'),
            'dialogSection.additionalBannerFields.manufacturer' => $this->module->l('Brand', 'translationsservice'),
            'dialogSection.additionalBannerFields.cms'      => $this->module->l('CMS Page', 'translationsservice'),
            'dialogSection.showcaseFields.order' => $this->module->l('Default Order', 'translationsservice'),
            'dialogSection.showcaseFields.layout' => $this->module->l('Layout', 'translationsservice'),
            'dialogSection.alertMessage' => '',
            'dialogSection.globalFields.hasMultiLangImage' => $this->module->l('Has image multi language ?', 'translationsservice'),
            'dialogSection.activeFrom' => $this->module->l('Active From', 'translationsservice'),
            'dialogSection.activeTo' => $this->module->l('Active To', 'translationsservice'),
            'dialogSection.newSectionTitle' => $this->module->l('New Home Section', 'translationsservice'),
            'dialogSection.editSectionTitle' => $this->module->l('Edit Home Section', 'translationsservice'),
            'dialogSection.datesTitle' => $this->module->l('Schedule section visibility', 'translationsservice'),
            'dialogSection.globalFields.hideTitleInHome' => $this->module->l('Hide title in home', 'translationsservice'),
            'dialogSection.moduleVersion.title' => $this->module->l('What\'s new in this version', 'translationsservice'),
            'dialogSection.moduleVersion.download' => $this->module->l('Download now', 'translationsservice'),
            'deleteDialogTitle' => $this->module->l('Delete selected item', 'translationsservice'),
            'deleteDialogMessage' => $this->module->l('Do you want to delete the selected item? This action cannot be undone', 'translationsservice'),
            'confirmDialogButton' => $this->module->l('Confirm', 'translationsservice'),
            'cancelDialogButton' => $this->module->l('Cancel', 'translationsservice'),
            'confirmDeleteDialogButton' => $this->module->l('Confirm', 'translationsservice'),
            'cancelDeleteDialogButton' => $this->module->l('Cancel', 'translationsservice'),
            'button' => $this->module->l('Save', 'translationsservice'),
            'stepperButtons.requestAppButton' => $requestApp,
            'stepperButtons.unloggedButton' => $this->module->l('Login', 'translationsservice'),
            'stepperButtons.unsubscribeButton' => $this->module->l('Unsubscribe', 'translationsservice'),
            'stepperButtons.showStepDocsButton' => $this->module->l('Show step documentation', 'translationsservice'),
            'stepperButtons.backButton' => $this->module->l('Back', 'translationsservice'),
            'stepperButtons.nextButton' => $this->module->l('Save and continue', 'translationsservice'),
            'stepperButtons.saveButton' => $this->module->l('Save and finish', 'translationsservice'),
            'stepperButtons.docsButton' => $this->module->l('Documentation', 'translationsservice'),
            'stepperButtons.cacheButton' => $this->module->l('Clear cache', 'translationsservice'),
            'stepperButtons.testAppButton' => $this->module->l('App Preview', 'translationsservice'),
            'stepperButtons.sendApp' => $this->module->l('Send Application', 'translationsservice'),
            'stepperButtons.sendUpdate' => $this->module->l('Request App Update', 'translationsservice'),
            'stepperButtons.tryIt' => $this->module->l('Try it now', 'translationsservice'),
            'firebase.title' => $this->module->l('Firebase Service Account', 'translationsservice'),
            'firebase.jsonError' => $this->module->l('Please upload your Firebase Configuration file', 'translationsservice'),
            'firebase.stepsMessage' => $firebaseStepsMessage,
            'ageChecker.title' => $this->module->l('Age verification settings', 'translationsservice'),
            'ageChecker.checkboxEnable' => $this->module->l('Enable age verification', 'translationsservice'),
            'ageChecker.age' => $this->module->l('Minimum age', 'translationsservice'),
            'paypal.title' => $this->module->l('Paypal settings', 'translationsservice'),
            'paypal.paypalEnabled' => $this->module->l('Enable PayPal on App', 'translationsservice'),
            'paypal.sandboxEnable' => $this->module->l('Sandbox mode', 'translationsservice'),
            'paypal.sandboxToken' => $this->module->l('Sandbox Access Token', 'translationsservice'),
            'paypal.liveToken' => $this->module->l('Live Access Token', 'translationsservice'),
            'socialLogin.title' => $this->module->l('Social login settings', 'translationsservice'),
            'socialLogin.facebook' => $this->module->l('Enable login with Facebook', 'translationsservice'),
            'socialLogin.facebookId' => $facebookAdminIdMessage,
            'socialLogin.google' => $this->module->l('Enable login with Google', 'translationsservice'),
            'whatsapp.title' => $this->module->l('WhatsApp Support', 'translationsservice'),
            'confirmSettings.title' => $this->module->l('Confirm you settings', 'translationsservice'),
            'confirmSettings.appStatus.text' => $this->module->l('App status', 'translationsservice'),
            'confirmSettings.appStatus.value' => $appStatus,
            'action.add' => $this->module->l('Add', 'translationsservice'),
            'action.remove' => $this->module->l('Remove', 'translationsservice'),
            'action.save' => $this->module->l('Save', 'translationsservice'),
            'action.upload' => $this->module->l('Upload', 'translationsservice'),
            'login.title'  => $this->module->l('Login', 'translationsservice'),
            'login.email'  => $this->module->l('Email', 'translationsservice'),
            'login.password' => $this->module->l('Password', 'translationsservice'),
            'login.saveButton' => $this->module->l('Login', 'translationsservice'),
            'login.inverseOperation' => $this->module->l('Go to signup', 'translationsservice'),
            'static.viewAll' => $this->module->l('View All', 'translationsservice'),
            'static.recapSectionTitle' => $this->module->l('Payment Modules', 'translationsservice'),
            'static.paypalDocs'        => $paypalDocs,
            'static.iconPreviewMessage' => $this->module->l('You must save the form for update the app preview in case of a new icon upload', 'translationsservice'),
            'static.splashPreviewMessage' => $this->module->l('You must save the form for update the app preview in case of a new splash icon upload.', 'translationsservice'),
            'static.updateAppMessage' => $this->module->l('If you change these parameters after the first submission of the app, an application update will be required to apply the changes', 'translationsservice'),
            'static.firebaseEnable' => $this->module->l('Firebase successfully configured', 'translationsservice'),
            'static.firebaseDisable' => $this->module->l('Firebase configuration missing', 'translationsservice'),
            'static.simpleUploader' => $this->module->l('Click here for upload file', 'translationsservice'),
            'static.settingsDoc' => $widgetDoc,
            'static.prestashopModuleDocUrl' => $documentationUrl,
            'static.appStatus' => $this->module->l('App Status', 'translationsservice'),
            'static.subscriptionStatus' => $this->module->l('Subscription Status', 'translationsservice'),
            'static.subscriptionGroup' => $this->module->l('Subscription Type', 'translationsservice'),
            'static.trailDays' => $this->module->l('Trial days', 'translationsservice'),
            'static.billingType.title' => $this->module->l('Billing Type', 'translationsservice'),
            'static.billingType.month' => $this->module->l('Monthly', 'translationsservice'),
            'static.billingType.year' => $this->module->l('Yearly', 'translationsservice'),
            'static.iosStoreUrl' => $this->module->l('iOS App Store link', 'translationsservice'),
            'static.googlePlayStoreUrl' => $this->module->l('Google Play Store link', 'translationsservice'),
            'static.appDetail' => $this->module->l('App Details', 'translationsservice'),
            'static.notAvailableYet' => $this->module->l('Not Available Yet', 'translationsservice'),
            'static.open' => $this->module->l('Open', 'translationsservice'),
            'static.close' => $this->module->l('Close', 'translationsservice'),
            'static.cancelledAt' => $this->module->l('Cancelled at', 'translationsservice'),
            'static.supportEndDate' => $this->module->l('Support End Date', 'translationsservice'),
            'static.textShopDescription' => $this->module->l('Shop configured:', 'translationsservice'),
            'static.subscriptionRequired' => $this->module->l('An active subscription is required for app publishing', 'translationsservice'),

            'static.youtube' => $this->module->l('YouTube How-To Channel link', 'translationsservice'),
            'static.appStoreMerchant' => $this->module->l('How to create a developer account for Apple App Store (required for Advanced plan)', 'translationsservice'),
            'static.moduleDoc' => $this->module->l('Module Documentation', 'translationsservice'),
            'static.googlePlayMerchant' => $this->module->l('How to create a developer account for Google Play store (required for Advanced plan)', 'translationsservice'),

            'static.youtubeUrl' => "https://www.youtube.com/channel/UCgCwmqJD_CKvRbPOHnqqXzw",
            'static.appStoreMerchantUrl' => $appleAppStoreUrl,
            'static.googlePlayMerchantUrl' => $googlePlayUrl,

            'steps.store' => $this->module->l('Store', 'translationsservice'),
            'steps.colors' => $this->module->l('Colors', 'translationsservice'),
            'steps.layout' => $this->module->l('Layout', 'translationsservice'),
            'steps.home' => $this->module->l('Home', 'translationsservice'),
            'steps.settings' => $this->module->l('Settings', 'translationsservice'),
            'steps.modules' => $this->module->l('Modules', 'translationsservice'),
            'steps.cms' => $this->module->l('Cms', 'translationsservice'),
            'steps.payment_modules' => $this->module->l('Payment modules', 'translationsservice'),
            'validation.required' => $this->module->l('{_field_} is required', 'translationsservice'),
            'validation.max' => $this->module->l('{_field_} can\'t be longer than {length} characters', 'translationsservice'),
            'validation.min_value' => $this->module->l('{_field_} has to be greater than or equal to {min}', 'translationsservice'),
            'validation.langRequired' => $this->module->l('{_field_} is required', 'translationsservice'),
            'validation.imageRequired' => $this->module->l('{_field_} is required', 'translationsservice'),
            'validation.relatedRequired' => $this->module->l('{_field_} is required', 'translationsservice'),
            'validation.multilangLen' => $this->module->l('{_field_} has to be between {min} and {max} characters', 'translationsservice'),
            'validation.email' => $this->module->l('{_field_} has to a valid email address', 'translationsservice'),
            'homesection.activation.manual' => $this->module->l('Manual', 'translationsservice'),
            'homesection.activation.from' => $this->module->l('From {date}', 'translationsservice'),
            'homesection.activation.to' => $this->module->l('until {date}', 'translationsservice'),
            'steps.notifications' => $this->module->l('Notifications', 'translationsservice'),
            'notifications.confirm.save' => $this->module->l('Data correctly saved', 'translationsservice'),
            'notifications.confirm.delete' => $this->module->l('Item correctly deleted', 'translationsservice'),
            'notifications.confirm.deleteBulk' => $this->module->l('Items correctly deleted', 'translationsservice'),
            'notifications.confirm.upload' => $this->module->l('File correctly uploaded', 'translationsservice'),
            'notifications.confirm.cacheClear' => $this->module->l('Cache correctly cleared', 'translationsservice'),
            'notifications.error' => $this->module->l('An error occured', 'translationsservice'),
            'notifications.tabs.validationError' => $this->module->l('Some inserted data is not valid, check the tabs header to view which section is involved', 'translationsservice'),
            'notifications.invalidLogin' => $this->module->l('Invalid credentials', 'translationsservice'),
            'hints.colors.primary' => $this->module->l('Ex. Android Toolbar and iOS Navigation Bar', 'translationsservice'),
            'hints.colors.secondary' => $this->module->l('Ex. Main buttons', 'translationsservice'),
            'hints.appFont' => $this->module->l('Choose a Custom App Font or use the System default', 'translationsservice'),
            'hints.stockQuantity'    => $this->module->l('Enable this option to show available quantity in product detail', 'translationsservice'),
            'hints.stockAvailble'    => $this->module->l('Enable this option to show availability in products list', 'translationsservice'),
            'hints.login.facebook'   => $this->module->l('Enables login with facebook', 'translationsservice'),
            'hints.login.google'     => $this->module->l('Enables login with google', 'translationsservice'),
            'hints.whatsappSupportNumber' => $this->module->l('Correct format: [country code][number], no space allowed. See https://countrycode.org for the country code list', 'translationsservice'),
            'hints.firebase' => $this->module->l('Firebase Service Account uploaded: {filename}', 'translationsservice'),
            'hints.icon' => $this->module->l('Suggested size 1024 x 1024', 'translationsservice'),
            'hints.splash' => $this->module->l('Suggested size 600 x 600', 'translationsservice'),
            'hints.logo' => $this->module->l('Suggested size 480 x 210 (.jpg,.png)', 'translationsservice'),
            'hints.QrCode' => $modalHint,
            'hints.description' => $this->module->l('Insert here a text describing the features of your app', 'translationsservice'),
            'hints.shortDescription' => $this->module->l('Subtitle for your app', 'translationsservice'),
            'hints.keywords' => $this->module->l('Format has to be like: "key1,key2,key3,some key,key4" (keys have to be comma separated)', 'translationsservice'),
            'datepicker.date' => $this->module->l('Date', 'translationsservice'),
            'datepicker.time' => $this->module->l('Time', 'translationsservice'),
            'subscriptionStatus.active' => $this->module->l('Active', 'translationsservice'),
            'subscriptionStatus.trialing' => $this->module->l('Trialing', 'translationsservice'),
            'subscriptionStatus.past_due' => $this->module->l('Expired', 'translationsservice'),
            'subscriptionStatus.paused' => $this->module->l('Paused', 'translationsservice'),
            'subscriptionStatus.refund' => $this->module->l('Refunded', 'translationsservice'),
            'subscriptionStatus.deleted' => $this->module->l('Deleted', 'translationsservice'),
            'subscriptionStatus.unknown' => $this->module->l('Unknown', 'translationsservice'),
            'subscriptionStatus.cancelled' => $this->module->l('Cancelled', 'translationsservice'),
            'appStatus.unknown' => $this->module->l('Unknown', 'translationsservice'),
            'appStatus.completed' => $this->module->l('Completed', 'translationsservice'),
            'appStatus.pending' => $this->module->l('Pending', 'translationsservice'),
            'appStatus.waiting' => $this->module->l('Waiting for stores', 'translationsservice'),
            'appStatus.request' => $this->module->l('App Requested', 'translationsservice'),
            'appStatus.cancelled' => $this->module->l('Cancelled', 'translationsservice'),
            'appStatus.request_update' => $this->module->l('Update Requested', 'translationsservice'),
            'cardTitles.information' => $this->module->l('App Information', 'translationsservice'),
            'cardTitles.images' => $this->module->l('Images', 'translationsservice'),
            'cardTitles.colors' => $this->module->l('Main Colors', 'translationsservice'),
            'cardTitles.darkColors' => $this->module->l('Dark Mode Colors', 'translationsservice'),
            'cardTitles.navigationType' => $this->module->l('Navigation Type', 'translationsservice'),
            'cardTitles.productsShape' => $this->module->l('Products Shape', 'translationsservice'),
            'cardTitles.generalSettings' => $this->module->l('General Settings', 'translationsservice'),
            'cardTitles.catalogLayout' => $this->module->l('Catalog Layout', 'translationsservice'),
            'cardTitles.productsLayout' => $this->module->l('Products Layout', 'translationsservice'),
            'cardTitles.listDetails' => $this->module->l('List Details', 'translationsservice'),
            'cardTitles.catalogDetails' => $this->module->l('Catalog Details', 'translationsservice'),
            'cardTitles.productDetail' => $this->module->l('Product Detail', 'translationsservice'),
            'cardTitles.cms' => $this->module->l('Cms pages', 'translationsservice'),
            'cardTitles.logo' => $this->module->l('Logo', 'translationsservice'),
            'cardTitles.home' => $this->module->l('Home Layout', 'translationsservice'),
            'cardTitles.payment' => $this->module->l('Payment Methods', 'translationsservice'),
            'cardTitles.modules' => $this->module->l('Modules Updates', 'translationsservice'),
            'cardTitles.gdpr' => $this->module->l('GDPR: Policy agreements', 'translationsservice'),
            'cardTitles.payment_modules' => $this->module->l('Manage Payment modules', 'translationsservice'),
            'cardSubTitles.payment_modules' => $this->module->l('Enable or disable Payment modules on app', 'translationsservice'),
            'cardSubTitles.cms' => $this->module->l('Manage cms pages', 'translationsservice'),
            'cardSubTitles.information' => $this->module->l('Define here your app information that will appear on the store sheet', 'translationsservice'),
            'cardSubTitles.images' => $this->module->l('Choose your app icon and image for the loading screen', 'translationsservice'),
            'cardSubTitles.colors' => $this->module->l('Define here your app color palette', 'translationsservice'),
            'cardSubTitles.darkColors' => $this->module->l('Define here your dark mode app color palette', 'translationsservice'),
            'cardSubTitles.navigationType' => $this->module->l('Choose the menu type of your App', 'translationsservice'),
            'cardSubTitles.productsShape' => $this->module->l('Choose the shape that fit better with your images', 'translationsservice'),
            'cardSubTitles.generalSettings' => $this->module->l('Choose the font and general App style', 'translationsservice'),
            'cardSubTitles.catalogLayout' => $this->module->l('Choose how to display the main categories of your catalog', 'translationsservice'),
            'cardSubTitles.productsLayout' => $this->module->l('Choose how many products to display on each row', 'translationsservice'),
            'cardSubTitles.listDetails' => $this->module->l('Options for Products\' List page', 'translationsservice'),
            'cardSubTitles.catalogDetails' => $this->module->l('Other options for your App', 'translationsservice'),
            'cardSubTitles.productDetail' => $this->module->l('Options for Product Detail page', 'translationsservice'),
            'cardSubTitles.logo' => $this->module->l('Choose your App logo', 'translationsservice'),
            'cardSubTitles.home' => $this->module->l('Manage all the sectors to be displayed on the Home Page', 'translationsservice'),
            'cardSubTitles.modules' => $this->module->l('List of all modules to update', 'translationsservice'),
            'cardSubTitles.payment' => $this->module->l('List of all supported payment modules currently configured on your Prestashop Website. To see all the payments modules supported in the app click here', 'translationsservice'),
            'gdpr.signupMsg' => $this->module->l('Agreement to privacy policy in the registration form', 'translationsservice'),
            'gdpr.profileMsg' => $this->module->l('Agreement to privacy policy in the profile', 'translationsservice'),
            'descriptionPlaceholder' => $this->module->l('Download now our Mobile App to purchase our products safe and easily, wherever you are!\nThanks to the App you can:\n\n- Browse our products catalog\n- Find easily the products you wish\n- Purchase in a few clicks\n- Be updated on the latest news and promotions thanks to push notifications\n- Ask for informations and check the orders status\n\nDownload it now for your smartphone and tablet.', 'translationsservice'),
            'table.headers.sentDate' => $this->module->l('Sent date', 'translationsservice'),
            'table.headers.sent' => $this->module->l('Sent', 'translationsservice'),
            'table.headers.moduleName' => $this->module->l('Module name', 'translationsservice'),
            'table.headers.status' => $this->module->l('Status', 'translationsservice'),
            'table.headers.supported' => $this->module->l('Supported', 'translationsservice'),
            'table.headers.useInAppAction' => $this->module->l('Use in app', 'translationsservice'),
            'table.headers.simpleCmsAction' => $this->module->l('Show simple page cms', 'translationsservice'),
            'dialogSection.newCmsPage' => $this->module->l('New cms page', 'translationsservice'),
            'dialogSection.showSimpleCms' => $this->module->l('Show simple page cms', 'translationsservice'),
            'dialogSection.additionalBannerFields.collection' => $this->module->l('Category', 'translationsservice'),
            'dialogSection.globalFields.status' => $this->module->l('Status', 'translationsservice'),
            'dialogSection.globalFields.cmsPage' => $this->module->l('Cms Page', 'translationsservice'),
            'dialogSection.manageRegisteredNotification' => $this->module->l('Edit status notifications', 'translationsservice'),
            'dialogSection.newScheduledNotification' => $this->module->l('New notification', 'translationsservice'),
            'dialogSection.editScheduledNotification' => $this->module->l('Edit notification', 'translationsservice'),
            'dialogSection.sentDate' =>  $this->module->l('Sent date', 'translationsservice'),
            'dialogSection.customerGroup' =>  $this->module->l('Send to a specific group', 'translationsservice'),
            'dialogSection.notificationType' => $this->module->l('Notification type', 'translationsservice'),
            'steps.notification' => $this->module->l('Notifications', 'translationsservice'),
            'cardTitles.notification' => $this->module->l('Push notifications', 'translationsservice'),
            'cardSubTitles.notification' => $this->module->l('Manage app push notifications', 'translationsservice'),
            'action.configureModule' => $this->module->l('Configure', 'translationsservice'),
            'dialogSection.globalFields.showToAllGroups' => $this->module->l('Show for all groups', 'translationsservice'),
            'dialogSection.globalFields.groupsToShow' => $this->module->l('Select groups', 'translationsservice'),
            'dialogSection.globalFields.visualizeAs' => $this->module->l('Display as:', 'translationsservice'),
            'table.headers.groups' => $this->module->l('Groups', 'translationsservice'),
            'validation.requestInAllLang' => $this->module->l('is required in all languages', 'translationsservice'),
            'cart.goToCheckout'  => $this->module->l('Go to checkout', 'translationsservice'),
            'cart.cartLabel'  => $this->module->l('Cart', 'translationsservice'),
            'cart.cartTotalProducts'  => $this->module->l('Total Products', 'translationsservice'),
            'cart.cartShipping'  => $this->module->l('Shipping cost', 'translationsservice'),
            'cart.cartTax'  => $this->module->l('Tax', 'translationsservice'),
            'cart.cartTotalCart'  => $this->module->l('Cart total (tax exl.)', 'translationsservice')
        ];
    }
}
