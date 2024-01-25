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

namespace MrAPPs\MrShopApi\Api\Validators;

use Configuration;
use Module;
use MrAPPs\MrShopApi\Exceptions\AppConfigurationValidationException;
use MrAPPs\MrShopApi\Handler\Payment\Sdk\PaypalBraintreeManager;
use MrAPPs\MrShopApi\Service\TranslationsService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use Tools;

class AppConfigurationValidator
{
    const RULE_REQUIRED     = 'required';

    // just a placeholder
    const RULE_NOT_REQUIRED = 'not_required';

    const RULE_SET          = 'set';

    const RULE_IMAGE        = 'image';

    const RULE_MULTILANG    = 'multilang';

    const RULE_BOOL         = 'bool';

    const RULE_STRING       = 'string';

    const RULE_POSITIVE     = 'positive';

    const RULE_COLOR        = 'color';

    const RULE_JSON         = 'json';
    
    protected $module;

    /** @var \MrAPPs\MrShopApi\Service\TranslationsService */
    protected $translationsService;

    /** @var \MrAPPs\MrShopApi\Utils\ImageUtils */
    protected $imageUtils;
    
    public function __construct($module)
    {
        $this->module = $module;
        $this->translationsService = new TranslationsService($module);
        $this->imageUtils = new ImageUtils();
    }
    
    public function validateData($data, $fields = null)
    {
        if (empty($fields) || false == is_array($fields)) {
            $rules = $this->getValidators($data);
        } else {
            $rules = $this->getValidationsRulesFor($data, $fields);
        }

        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            foreach ($ruleSet as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleKey = array_splice($ruleParts, 0, 1);
                $params = count($ruleParts) > 0
                    ? explode(',', $ruleParts[0])
                    : [];

                try {
                    // avrei dovuto usare delle classi separate ma sarei stato qui fino al 2050
                    switch ($ruleKey[0]) {
                        case self::RULE_REQUIRED:
                            $this->checkRequired($field, $data);

                            break;
                        case self::RULE_SET:
                            $this->checkSet($field, $data);

                            break;
                        case self::RULE_IMAGE:
                            $this->checkImage($field, $data);

                            break;
                        case self::RULE_MULTILANG:
                            $this->checkMultilang($field, $data, (int) $params[0], (int) $params[1]);

                            break;
                        case self::RULE_BOOL:
                            $this->checkBoolean($field, $data);

                            break;
                        case self::RULE_STRING:
                            $this->checkString($field, $data);

                            break;
                        case self::RULE_POSITIVE:
                            $this->checkPositive($field, $data);

                            break;
                        case self::RULE_COLOR:
                            $this->checkColor($field, $data);

                            break;
                        case self::RULE_JSON:
                            $this->checkJson($field, $data);

                            break;
                    }
                } catch (\Exception $ex) {
                    if (empty($errors[$field])) {
                        $errors[$field] = [];
                    }

                    $errors[$field][] = $ex->getMessage();
                }
            }
        }

        return [
            'valid' => count($errors) == 0,
            'errors' => $errors
        ];
    }
    
    protected function checkRequired($field, $data)
    {
        if (!isset($data[$field])) {
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('%s field is required', 'appconfigurationvalidator'), $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function checkSet($field, $data)
    {
        if (isset($data[$field]) && !empty($data[$field])) {
            $value = $data[$field];
            $set = $this->getSetFor($field);
            
            if (false == in_array($value, $set)) {
                $name = $this->translationsService->getForField($field);
                $message = sprintf($this->module->l('%s field has an invalid value', 'appconfigurationvalidator'), $name);

                throw new AppConfigurationValidationException($message);
            }
        }
    }
    
    protected function checkMultilang($field, $data, $minLength, $maxLength)
    {
        $name = $this->translationsService->getForField($field);
        $errors = [];
        if (isset($data[$field]) && !empty($data[$field])) {
            $setted = 0;
            if (false == is_array($data[$field])) {
                $errors[] = sprintf($this->module->l('%s has to have multiple values', 'appconfigurationvalidator'), $name);
            } else {
                $val = $data[$field];
                $ls = $this->getLangOptions();

                foreach ($ls as $iso) {
                    if (false == isset($val[$iso])) {
                        continue;
                    }

                    $trans = trim($val[$iso]);

                    if (empty($trans)) {
                        continue;
                    }

                    $setted++;

                    if (Tools::strlen($trans) < $minLength || Tools::strlen($trans) > $maxLength) {
                        $errors[] = sprintf($this->module->l('%s has to be between %s and %s characters', 'appconfigurationvalidator'), $name, $minLength, $maxLength);
                    }
                }

                if ($setted == 0) {
                    $errors[] = sprintf($this->module->l('%s hasn\'t setted translations', 'appconfigurationvalidator'), $name);
                }
            }
        }
        if (count($errors) > 0) {
            throw new AppConfigurationValidationException($errors);
        }
    }
    
    protected function checkBoolean($field, $data)
    {
        if (isset($data[$field]) && $data[$field] !== true && $data[$field] !== false) {
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('%s has to be true or false', 'appconfigurationvalidator'), $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function checkString($field, $data)
    {
        if (isset($data[$field]) && false == empty($data[$field]) && empty(trim($data[$field]))) {
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('%s can\'t be an empty string', 'appconfigurationvalidator'), $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function checkPositive($field, $data)
    {
        if (isset($data[$field]) && !empty($data[$field]) && (false == is_int($data[$field]) || $data[$field] < 0)) {
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('%s has to be a positive integer', 'appconfigurationvalidator'), $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function checkImage($field, $data)
    {
        if (isset($data[$field]) && !empty($data[$field])) {
            $filename = $data[$field]['name'];
            if ($this->imageUtils->tmpFileExists($filename)) {
                return true;
            }
            
            $fpath = ImageUtils::getImageRelativePath($filename, false);
            if (file_exists($fpath)) {
                return true;
            }
            
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('Cannot find %s image for %s', 'appconfigurationvalidator'), $filename, $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function checkColor($field, $data)
    {
        if (isset($data[$field]) && !empty($data[$field])) {
            if (false === (bool) preg_match('/^#(\d|[a-fA-F]){6}$/i', $data[$field])) {
                $name = $this->translationsService->getForField($field);
                $message = sprintf($this->module->l('%s is not a valid color', 'appconfigurationvalidator'), $name);

                throw new AppConfigurationValidationException($message);
            }
        }
    }
    
    protected function checkJson($field, $data)
    {
        if ((isset($data[$field]['value'])
            && !empty($data[$field]['value']))) {
            $j = json_decode($data[$field]['value'], true);

            if (empty($j) || JSON_ERROR_NONE !== json_last_error()) {
                $name = $this->translationsService->getForField($field);
                $message = sprintf($this->module->l('%s is not a valid JSON', 'appconfigurationvalidator'), $name);

                throw new AppConfigurationValidationException($message);
            }
        } elseif (!Configuration::get('MRSHOP_FIREBASE_SERVICE_ACCOUNT', null, null, null, null)) {
            $name = $this->translationsService->getForField($field);
            $message = sprintf($this->module->l('%s is required', 'appconfigurationvalidator'), $name);

            throw new AppConfigurationValidationException($message);
        }
    }
    
    protected function getValidators($data = null)
    {
        $paypalRequired = PaypalBraintreeManager::isOneOfPaypalModulesEnabled() && $data['paypalEnabled']
                ? self::RULE_REQUIRED
                : self::RULE_NOT_REQUIRED;
        
        $socialRequired = Module::isEnabled('mrshopsociallogin')
                ? self::RULE_REQUIRED
                : self::RULE_NOT_REQUIRED;
        
        $minAgeRequired = empty($data['ageEnabled'])
                ? self::RULE_NOT_REQUIRED
                : self::RULE_REQUIRED;
        $logoRequired = empty($data['showLogo'])
                ? self::RULE_NOT_REQUIRED
                : self::RULE_REQUIRED;
        
        $waRequired = empty($data['showWhatsappSupport'])
                ? self::RULE_NOT_REQUIRED
                : self::RULE_REQUIRED;

        $gdprRequired = $data['showGdpr']
            ? [self::RULE_REQUIRED, self::RULE_MULTILANG.':10,1000']
            : [self::RULE_NOT_REQUIRED];

        $requiredColors = [self::RULE_REQUIRED, self::RULE_COLOR];
        $requiredBools = [self::RULE_REQUIRED, self::RULE_BOOL];
        $logoRules = [$logoRequired, self::RULE_IMAGE];

        return [
            'defaultLanguage'          => [self::RULE_REQUIRED, self::RULE_SET], // deve essere in un insieme
            'name'                     => [self::RULE_REQUIRED, self::RULE_STRING], // deve esserci
            'icon'                     => [self::RULE_REQUIRED, self::RULE_IMAGE],
            'splash'                   => [self::RULE_REQUIRED, self::RULE_IMAGE],
            'darkSplash'               => [self::RULE_NOT_REQUIRED, self::RULE_IMAGE],
            'shortDescription'         => [self::RULE_REQUIRED, self::RULE_MULTILANG.':0,80'],
            'description'              => [self::RULE_REQUIRED, self::RULE_MULTILANG.':300,4000'],
            'keywords'                 => [self::RULE_REQUIRED, self::RULE_MULTILANG.':0,100'],
            'mainColor'                => $requiredColors,
            'secondaryColor'           => $requiredColors,
            'darkSecondaryColor'       => $requiredColors,
            'textMainColor'            => $requiredColors,
            'textSecondaryColor'       => $requiredColors,
            'darkTextColorOnSecondary' => $requiredColors,
            'isBlackText'              => $requiredBools,
            'navigationType'           => [self::RULE_REQUIRED, self::RULE_SET],
            'catalogLayoutType'        => [self::RULE_REQUIRED, self::RULE_SET],
            'productsLayoutType'       => [self::RULE_REQUIRED, self::RULE_SET],
            'showStockQt'              => $requiredBools,
            'showAvailable'            => $requiredBools,
            'showLogo'                 => $requiredBools,
            "logo"                     => $logoRules,
            "darkLogo"                 => $logoRules,
            'isPaypalSandbox'          => [$paypalRequired, self::RULE_BOOL],
            'paypalEnabled'            => [self::RULE_BOOL],
            'accessTokenSandbox'       => [self::RULE_STRING],
            'accessTokenLive'          => [$paypalRequired, self::RULE_STRING],
            'facebookEnabled'          => [$socialRequired, self::RULE_BOOL],
            'googleEnabled'            => [$socialRequired, self::RULE_BOOL],
            'ageEnabled'               => $requiredBools,
            'minAge'                   => [$minAgeRequired, self::RULE_POSITIVE],
            'showWhatsappSupport'      => [self::RULE_BOOL],
            'whatsappSupportNumber'    => [$waRequired, self::RULE_STRING],
            'firebaseConfiguration'    => [self::RULE_JSON],
            'signupMsg'                => $gdprRequired,
            'profileMsg'               => $gdprRequired,
            'roundedImages'            => [self::RULE_NOT_REQUIRED, self::RULE_BOOL],
            'productsBackgroundGrey'   => [self::RULE_NOT_REQUIRED, self::RULE_BOOL],
            'showNavBar'               => [self::RULE_NOT_REQUIRED, self::RULE_BOOL],
            'productsShape'            => [self::RULE_NOT_REQUIRED, self::RULE_SET],
            'appFont'                  => [self::RULE_NOT_REQUIRED, self::RULE_SET],
        ];
    }
    
    protected function getLangOptions()
    {
        $opt = ApiUtils::getLanguagesOptions();

        return array_map(function ($item) {
            return $item['value'];
        }, $opt);
    }
    
    protected function getSetFor($field)
    {
        $set = [];
        switch ($field) {
            case 'defaultLanguage':
                $set = $this->getLangOptions();

                break;
            case 'navigationType':
                $set = array_keys($this->translationsService->getAppNavigationTypes());

                break;
            case 'catalogLayoutType':
                $set = array_keys($this->translationsService->getCatalogueLayoutTypes());

                break;
            case 'productsLayoutType':
                $set = array_keys($this->translationsService->getProductRowLayout());

                break;
            case 'productsShape':
                $set = array_keys($this->translationsService->getProductsShape());

                break;
            case 'appFont':
                $set = array_keys($this->translationsService->getAppFonts());

                break;
        }
        
        return $set;
    }
    
    protected function getValidationsRulesFor($data, $fields)
    {
        $allRules = $this->getValidators($data);
        $retval = [];
        foreach ($fields as $field) {
            if (false == isset($allRules[$field])) {
                throw new \RuntimeException('Invalid field '.$field);
            }
            $retval[$field] = $allRules[$field];
        }
        
        return $retval;
    }
}
