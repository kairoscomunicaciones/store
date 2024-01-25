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

namespace MrAPPs\MrShopApi\Handler\Api;

use Configuration;
use Context;
use Language;
use Module;
use MrAPPs\MrShopApi\Api\Validators\AppConfigurationValidator;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\PaypalBraintreeManager;
use MrAPPs\MrShopApi\Service\FcmClient;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;

class AppConfigurationHandler
{
    protected $data = [];

    protected $module;

    /**@var DataHandler $dataHandler*/
    protected $dataHandler;

    protected $validator;

    /** @var \MrAPPs\MrShopApi\Utils\ImageUtils **/
    protected $imageUtils;

    const HOME_STEP = 3;

    /* Do that because next allowed is ever + 1 */
    const INIT_STEP = self::HOME_STEP - 1;

    const MAX_STEPS = 5;

    protected $steps = [
        [
            'mainColor',
            'secondaryColor',
            'darkSecondaryColor',
            'textMainColor',
            'textSecondaryColor',
            'darkTextColorOnSecondary',
            'isBlackText',
        ],
        [
            'navigationType',
            'catalogLayoutType',
            'productsLayoutType',
            'showStockQt',
            'showAvailable',
            'roundedImages',
            'productsBackgroundGrey',
            'appFont',
            'productsShape',
            'showNavBar'
        ],
        [
            'showLogo',
            "logo",
            "darkLogo",
        ],
        [
            'isPaypalSandbox',
            'paypalEnabled',
            'accessTokenSandbox',
            'accessTokenLive',
            'facebookEnabled',
            'firebaseConfiguration',
            'googleEnabled',
            'ageEnabled',
            'minAge',
            'showWhatsappSupport',
            'whatsappSupportNumber',
            'signupMsg',
            'profileMsg'
        ],
        [
            'defaultLanguage',
            'name',
            'icon',
            'splash',
            'darkSplash',
            'shortDescription',
            'description',
            'keywords',
        ]
    ];

    protected $lastAllowedStep;

    protected $keyMap = [
        'name'                     => 'MRSHOP_APP_NAME',
        'icon'                     => ImageUtils::ICON,
        'splash'                   => ImageUtils::SPLASH,
        'darkSplash'               => ImageUtils::DARK_SPLASH,

        'shortDescription'         => 'MRSHOP_APP_SHORT_DESCRIPTION',
        'description'              => 'MRSHOP_APP_DESCRIPTION',
        'keywords'                 => 'MRSHOP_APP_KEYWORDS',

        'mainColor'                => 'MRSHOP_PRIMARY_COLOR',
        'secondaryColor'           => 'MRSHOP_SECONDARY_COLOR',
        'darkSecondaryColor'       => 'MRSHOP_DARK_SECONDARY_COLOR',
        'textMainColor'            => 'MRSHOP_TEXT_COLOR_ON_PRIMARY',
        'textSecondaryColor'       => 'MRSHOP_TEXT_COLOR_ON_SECONDARY',
        'darkTextColorOnSecondary' => 'MRSHOP_DARK_TEXT_COLOR_ON_SECONDARY',
        'isBlackText'              => 'MRSHOP_DARK_STATUSBAR',

        'navigationType'           => 'MRSHOP_NAVIGATION_TYPE',
        'catalogLayoutType'        => 'MRSHOP_CATALOG_LAYOUT',
        'productsLayoutType'       => 'MRSHOP_PRODUCTS_LAYOUT',
        'showStockQt'              => 'MRSHOP_SHOW_PRODUCT_STOCK_QUANTITY',
        'showAvailable'            => 'MRSHOP_SHOW_PRODUCTS_AVAILABILITY',
        'roundedImages'            => 'MRSHOP_ROUNDED_IMAGES',
        'productsBackgroundGrey'   => 'MRSHOP_PRODUCTS_BACKGROUND_GREY',
        'appFont'                  => 'MRSHOP_APP_FONT',
        'showNavBar'               => 'MRSHOP_SHOW_NAVBAR',
        'productsShape'            => 'MRSHOP_PRODUCTS_SHAPE',

        'showLogo'                 => 'MRSHOP_ENABLE_LOGO',
        'logo'                     => ImageUtils::LOGO,
        'darkLogo'                 => ImageUtils::DARK_LOGO,

        'paypalEnabled'            => 'MRSHOP_PAYPAL_APP_ENABLED',
        'isPaypalSandbox'          => 'MRSHOP_PAYPAL_SANDBOX',
        'accessTokenSandbox'       => 'MRSHOP_PAYPAL_SANDBOX_BRAINTREE_ACCESS_TOKEN',
        'accessTokenLive'          => 'MRSHOP_PAYPAL_LIVE_BRAINTREE_ACCESS_TOKEN',

        'facebookEnabled'          => 'MRSHOP_FACEBOOK_ENABLED_APP',
        'googleEnabled'            => 'MRSHOP_GOOGLE_ENABLED_APP',

        'ageEnabled'               => 'MRSHOP_ENABLE_AGE_VERIFICATION',
        'minAge'                   => 'MRSHOP_AGE_VERIFICATION_MIN_AGE',

        'showWhatsappSupport'      => 'MRSHOP_SHOW_WHATSAPP_SUPPORT',
        'whatsappSupportNumber'    => 'MRSHOP_WHATSAPP_SUPPORT',

        'signupMsg'                => 'MRSHOP_REGISTRATION_MSG',
        'profileMsg'               => 'MRSHOP_PROFILE_MSG',

        'firebaseConfiguration'    => 'MRSHOP_FIREBASE_SERVICE_ACCOUNT',
        'defaultLanguage'          => 'MRSHOP_APP_LANG'
    ];

    protected $boolean = [
        'paypalEnabled',
        'isPaypalSandbox',
        'showStockQt',
        'showAvailable',
        'isBlackText',
        'facebookEnabled',
        'googleEnabled',
        'ageEnabled',
        'showLogo',
        'showWhatsappSupport',
        'showGdpr',
        'roundedImages',
        'showNavBar',
        'productsBackgroundGrey'
    ];

    protected $integers = [
        'minAge',
        'navigationType',
        'catalogLayoutType',
        'productsLayoutType'
    ];

    protected $multilang = [
        'shortDescription',
        'description',
        'keywords',
        'signupMsg',
        'profileMsg'
    ];

    protected $requiredUpdateFields = [
        'name' => true,
        'shortDescription' => true,
        'description' => true,
        'icon' => true,
        'splash' => true,
        'logo'=> true,
        'darkSplash' => true,
        'darkLogo' => true,
        'mainColor' => false,
        'secondaryColor' => false,
        'textSecondaryColor' => false,
        'darkSecondaryColor' => false,
        'darkTextColorOnSecondary' => false,
        'isBlackText' => false,
        'navigationType' => false,
        'catalogLayoutType' => false,
        'productsLayoutType' => false,
        'showStockQt' => false,
        'showAvailable' => false,
        'showLogo' => false,
        'firebaseConfiguration' => false,
        'boolean' => false,
        'ageEnabled' => false,
        'minAge' => false,
        'showPaypal' => false,
        'paypalEnabled' => false,
        'isPaypalSandbox' => false,
        'accessTokenSandbox' => false,
        'accessTokenLive' => false,
        'facebookEnabled' => true,
        'googleEnabled' => true,
        'defaultLanguage' => false,
        'roundedImages' => false,
        'showNavBar' => false,
        'signupMsg'  => false,
        'profileMsg' => false,
        'productsShape' => false,
        'appFont' => false,
    ];

    public function __construct($module, $dataHandler)
    {
        $this->module = $module;
        $this->dataHandler = $dataHandler;
        $this->validator = new AppConfigurationValidator($module);
        $this->imageUtils = new ImageUtils();
    }

    /**
     * Saves data into db
     * @param array $form
     * @return boolean
     */
    public function save($form, $step = null)
    {
        $fields = [];
        if (empty($step)) {
            foreach ($this->steps as $s) {
                $fields = array_merge($fields, $s);
            }
        } else {
            $fields = $this->getStepFields($step);
        }

        $result = $this->validator->validateData($form, $fields);
        if (!$result['valid']) {
            return $result['errors'];
        }

        $transformed = [];
        $deleting    = [];

        foreach ($fields as $outKey) {
            if (false == empty($this->keyMap[$outKey])) {
                $inKey = $this->keyMap[$outKey];
            } else {
                continue;
            }

            $val = isset($form[$outKey])
                    ? $form[$outKey]
                    : null;

            // every null value stands for deleting configuration key from db
            if (is_null($val)) {
                $deleting[] = $inKey;

                continue;
            }

            /*
             * the firebase json is a special case where we have to save the content
             * inside a file and save the file name into db
             */
            if ($outKey == 'firebaseConfiguration') {
                if (empty($val['name'])) {
                    return false;
                }

                $emptyValue = !isset($val['value']) || empty($val['value']);

                if ($emptyValue && !FcmClient::readFirebaseJson(true, $val['name'])) {
                    return false;
                }

                if (!$emptyValue && false == FcmClient::writeFirebaseJson($val['value'], $val['name'])) {
                    return false;
                }

                $transformed[$inKey] = $val['name'];
            } elseif (ImageUtils::isGlobalAppImage($inKey)) {
                $filename = $val['name'];
                if ($this->imageUtils->tmpFileExists($filename)) {
                    // occhio che qua si gioca sul fatto che la outkey coincide con il nome finale dell'immagine
                    $res = $this->imageUtils->saveTmpImage($filename, $outKey);
                    if ($res === false) {
                        return false;
                    }

                    $transformed[$inKey] = $res;
                } else {
                    $transformed[$inKey] = $filename;
                }
            } elseif (in_array($outKey, $this->multilang)) {
                $languages = $this->getLanguageList();
                $values = [];
                foreach ($languages as $lang) {
                    $idLang  = (int) $lang['id_lang'];
                    $isoCode = $lang['iso_code'];

                    $values[$idLang] = isset($val[$isoCode])
                            ? $this->transformValue($inKey, $val[$isoCode])
                            : '';
                }

                $transformed[$inKey] = $values;
            } else {
                $transformed[$inKey] = $this->transformValue($inKey, $val);
            }
        }

        // update each config
        foreach ($transformed as $key => $value) {
            if (false == Configuration::updateValue($key, $value)) {
                return false;
            }
        }

        // delete config
        foreach ($deleting as $key) {
            if (false == Configuration::deleteByName($key)) {
                return false;
            }
        }

        if (!empty($step)) {
            $lastAllowedStep = (int) Configuration::get('MRSHOP_LAST_ALLOWED_STEP');

            if (
                ($step <= self::MAX_STEPS && $step >= self::HOME_STEP) &&
                ($step >= $lastAllowedStep)
            ) {
                $this->lastAllowedStep = $step;
                Configuration::updateValue('MRSHOP_LAST_ALLOWED_STEP', (string) $step);
            }
        }

        $this->data = array_merge($this->data, $fields);

        return true;
    }

    public function canSend()
    {
        return self::MAX_STEPS == $this->lastAllowedStep;
    }

    /**
     * Returns the current saved configuration
     * @return array
     */
    public function getCurrent($step = null, $bodyParams = null)
    {
        if (!$bodyParams) {
            $this->refresh();
            $form = $this->presentData();
        } else {
            $form = $bodyParams;

            foreach ($this->keyMap as $outKey => $inKey) {
                if (ImageUtils::isGlobalAppImage($inKey)) {
                    $form[$outKey] = ImageUtils::presentImage(Configuration::get($inKey));
                } else if ($outKey == 'firebaseConfiguration') {
                    if (!FcmClient::readFirebaseJson(true, Configuration::get($inKey))) {
                        $form[$outKey] = null;
                    }
                }
            }
        }

        $baseUrl = ApiUtils::getFullDomain();
        if (Tools::substr($baseUrl, Tools::strlen($baseUrl) - 1) == '/') {
            $baseUrl = Tools::substr($baseUrl, 0, Tools::strlen($baseUrl) - 1);
        }

        $allowedStep = $this->getNextAllowedStep();

        $canSend = $this->canSend();

        $currentStep = isset($step) && $step <= $allowedStep
            ? $step
            : $allowedStep;

        $socialLoginConfigured = false;
        $socialLoginEnabled =  Module::isEnabled('mrshopsociallogin');

        $messages = [];
        if ($socialLoginEnabled) {
            require_once(_PS_MODULE_DIR_.'mrshopsociallogin/classes/CredentialKeysProvider.php');

            $facebookCredentials = \CredentialKeysProvider::getConfigKeysFor(\CredentialKeysProvider::FACEBOOK);
            $googleCredentials = \CredentialKeysProvider::getConfigKeysFor(\CredentialKeysProvider::GOOGLE);
            $appleCredentials = \CredentialKeysProvider::getConfigKeysFor(\CredentialKeysProvider::APPLE);

            if ((\Configuration::get($facebookCredentials['enabled']) || \Configuration::get($googleCredentials['enabled'])) && \Configuration::get($appleCredentials['enabled'])) {
                $socialLoginConfigured = !ApiUtils::isAppRequested() || ApiUtils::isModuleActive('mrshopsociallogin', $this->dataHandler->getJsonDecodedList());
            } else {
                $socialLoginUrl = Context::getContext()->link->getAdminLink('AdminModules', true).'&configure=mrshopsociallogin';
                if (ApiUtils::isAppRequested()) {
                    $level = 'error';

                    if (ApiUtils::isModuleActive('mrshopsociallogin', $this->dataHandler->getJsonDecodedList())) {
                        $text = sprintf(
                            "%s <a href='%s'>%s</a> %s",
                            $this->module->l('You must configure the social login module from', 'appconfigurationhandler'),
                            $socialLoginUrl,
                            $this->module->l('here', 'appconfigurationhandler'),
                            $this->module->l('to enable Social Login on App', 'appconfigurationhandler')
                        );
                    } else {
                        $text = sprintf(
                            "%s <a href='%s'>%s</a> %s",
                            $this->module->l('You must configure the social login module from', 'appconfigurationhandler'),
                            $socialLoginUrl,
                            $this->module->l('here', 'appconfigurationhandler'),
                            $this->module->l('and have an Advanced Plan to enable Social Login on App', 'appconfigurationhandler')
                        );
                    }
                } else {
                    $level = 'warning';
                    $text = sprintf(
                        "%s <a href='%s'>%s</a> %s",
                        $this->module->l('You must configure the social login module from', 'appconfigurationhandler'),
                        $socialLoginUrl,
                        $this->module->l('here', 'appconfigurationhandler'),
                        $this->module->l('and choose the Advanced Plan to enable Social Login on App', 'appconfigurationhandler')
                    );
                }
                $messages[] = [
                    'level' => $level,
                    'text' => $text
                ];
            }
        }

        $socialLogin = [
            'show' => $socialLoginEnabled,
            'enabled' => $socialLoginConfigured,
            'messages' => $messages,
        ];

        return [
            'languages' => ApiUtils::getLanguagesOptions(),
            'canSend' => $canSend,
            'isStepper' => !($canSend && ApiUtils::isAppRequested()),
            'qrData' => [
                'name' => $form['name'],
                'base_url' => $baseUrl
            ],
            'allowedStep' => $allowedStep,
            'currentStep' => $currentStep,
            'wishList' => (int) Module::isEnabled('mrshopblockwishlist'),
            'form' => $form,
            'updateRequired' => (bool) Configuration::get('MRSHOP_APP_UPDATE_REQUIRED'),
            'requireUpdateField' => $this->requiredUpdateFields,
            'socialLogin' => $socialLogin,
        ];
    }

    public function load()
    {
        $this->refresh();

        return $this;
    }

    /**
     * Presents data based on data type
     * @return array
     */
    protected function presentData()
    {
        $retval = [];

        foreach ($this->data as $key => $value) {
            if (false == isset($this->keyMap[$key])) {
                /*
                 * some keys in data are calculated and haven't a configuration
                 * key, do i use their values as is
                 */
                $retval[$key] = $value;

                continue;
            }

            $configKey = $this->keyMap[$key];

            if (ImageUtils::isGlobalAppImage($configKey)) {
                $retval[$key] = ImageUtils::presentImage($value);
            } else if (in_array($key, $this->multilang)) {
                $retval[$key] = $this->transformMultilang($configKey, $key);
            } else {
                $retval[$key] = $this->transformValue($key, $value);
            }

            if ($key == 'firebaseConfiguration') {
                if (!FcmClient::readFirebaseJson(true, Configuration::get($configKey))) {
                    $retval[$key] = null;
                }
            }
        }

        return $retval;
    }

    /**
     * Transform value based on column type
     * @param string $inKey
     * @param mixed $value
     * @return mixed
     */
    protected function transformValue($inKey, $value)
    {
        if (in_array($inKey, $this->boolean)) {
            return false == empty($value);
        } elseif (in_array($inKey, $this->integers)) {
            return (int) $value;
        } else {
            if (is_array($value)) {
                return $value;
            }

            $value = trim((string) $value);

            return false == empty($value)
                ? (string) $value
                : '';
        }
    }

    protected function transformMultilang($inKey, $outKey)
    {
        $retval = [];
        $languages = $this->getLanguageList();

        foreach ($languages as $lang) {
            $v = Configuration::get($inKey, $lang['id_lang']);
            $retval[$lang['iso_code']] = $this->transformValue($outKey, $v);
        }

        return $retval;
    }

    protected function getLanguageList()
    {
        static $languages = null;
        if (is_null($languages)) {
            $shop = Context::getContext()->shop;
            $languages = Language::getLanguages(true, $shop->id);
        }

        return $languages;
    }

    private function getFirebaseConfiguration($firebaseConfigurationKey)
    {
        $outKey = $this->keyMap[$firebaseConfigurationKey];
        $firebaseName = Configuration::get($outKey, null, null, null, null);

        return [
            'active' => $firebaseName != null,
            'name' => $firebaseName,
        ];
    }

    private function getConfigInMultipleLangs($key, $idShopGroup = null, $idShop = null)
    {
        $resultsArray = [];
        foreach (\Language::getIDs() as $idLang) {
            $resultsArray[$idLang] = \Configuration::get($key, $idLang, $idShopGroup, $idShop);
        }

        return $resultsArray;
    }

    /**
     * Reloads data from DB
     * @return array
     */
    protected function refresh()
    {
        $this->lastAllowedStep = null;
        $this->setAllowedStep();
        $this->data = [
            'showPaypal' => PaypalBraintreeManager::isOneOfPaypalModulesEnabled(),
            'showGdpr' => !Module::isEnabled('psgdpr'),
        ];

        foreach ($this->keyMap as $outKey => $inKey) {
            if ($outKey == 'firebaseConfiguration') {
                $this->data[$outKey] = $this->getFirebaseConfiguration($outKey);
            } else {
                if (in_array($outKey, $this->multilang)) {
                    $this->data[$outKey] = $this->getConfigInMultipleLangs($inKey);
                } else {
                    $this->data[$outKey] = \Configuration::get($inKey, null, null, null, null);
                }
            }
        }

        return $this->data;
    }

    public function setAllowedStep()
    {
        $lastAllowedStep = Configuration::get('MRSHOP_LAST_ALLOWED_STEP');
        if (!$lastAllowedStep) {
            $lastAllowedStep = self::INIT_STEP;
            Configuration::updateValue('MRSHOP_LAST_ALLOWED_STEP', $lastAllowedStep);
        }
        $this->lastAllowedStep = (int) $lastAllowedStep;
    }

    public function getNextAllowedStep()
    {
        $this->setAllowedStep();

        if ($this->lastAllowedStep == self::MAX_STEPS) {
            return $this->lastAllowedStep;
        }

        return $this->lastAllowedStep + 1;
    }

    protected function getStepFields($i)
    {
        if (false == isset($this->steps[$i - 1])) {
            throw new \RuntimeException("Step $i is not defined");
        }

        return $this->steps[$i - 1];
    }
}
