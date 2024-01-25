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
use Currency;
use Guest;
use Module;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;

class DataHandler
{
    private $apiVersion;

    private $currencyId;

    private $token = null;

    private $guestId = null;

    private $bodyParams;

    private $maxProductsShown = null;

    public $modulesStatus;

    public $stringifyIds = false;

    protected $mrshopModules = [
        'mrshopblockwishlist',
        'mrshopmailnotifications',
        'mrshopsociallogin',
        'mrshopcashondelivery'
    ];

    public function __construct($skipReading = false)
    {
        $this->modulesStatus = [];
        $currencyId = null;

        $httpHeaders = $this->getHttpHeaders();

        if (strpos($httpHeaders['Content-Type'], 'multipart/form-data') !== false
            || strpos($httpHeaders['Content-Type'], 'application/x-www-form-urlencoded') !== false) {
            $bodyParams = $_POST;
        } else {
            $bodyParams = json_decode(Tools::file_get_contents('php://input'), true);
        }

        if ($bodyParams == null) {
            $bodyParams = [];
        }

        $this->bodyParams = $bodyParams;

        if (!$skipReading) {
            $apiVersion = array_key_exists('version', $this->bodyParams) ? $this->bodyParams['version'] : Tools::getValue('version');
            $apiVersion = !empty($apiVersion) ? (int) $apiVersion : (int) Tools::getValue('version');
            if ($apiVersion < 1) {
                $apiVersion = 1;
            }

            $currencyId = array_key_exists('currency', $this->bodyParams) ? $this->bodyParams['currency'] : Tools::getValue('currency');

            $this->stringifyIds = $apiVersion >= 14 || array_key_exists('id_as_string', $this->bodyParams) || (bool) Tools::getValue('id_as_string');

            if (!$currencyId) {
                $currencyId = null;
            }

            $this->getToken(true);

            if (isset($this->bodyParams['id_guest']) || isset($_SERVER['HTTP_ID_GUEST']) || isset($_REQUEST['id_guest'])) {
                switch (true) {
                    case isset($this->bodyParams['id_guest']):
                        $this->guestId = (int) $this->bodyParams['id_guest'];

                        break;
                    case isset($_SERVER['HTTP_ID_GUEST']):
                        $this->guestId = (int) $_SERVER['HTTP_ID_GUEST'];

                        break;
                    case isset($_REQUEST['id_guest']):
                        $this->guestId = (int) $_REQUEST['id_guest'];

                        break;
                }
            }

            if ($this->guestId > 0) {
                $guest = new Guest($this->guestId);
                if (!\Validate::isLoadedObject($guest)) {
                    $this->guestId = null;
                }
            }
        } else {
            $apiVersion = \MrShopApi::API_VERSION;
        }

        $this->apiVersion = $apiVersion;

        if ($currencyId != null) {
            $this->currencyId = (int) $currencyId;
        } elseif (Context::getContext()->currency != null) {
            $this->currencyId = (int) Context::getContext()->currency->id;
        } else {
            $this->currencyId = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        }

        if (Context::getContext()->currency == null) {
            Context::getContext()->currency = new Currency($this->currencyId);
        }
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
    * Checks if api version is greater than or equal
    * @param int $minimumVersion
    * @return bool
    */
    public function isApiVersionMin($minimumVersion)
    {
        return $this->getApiVersion() >= (int) $minimumVersion;
    }

    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    public function isModuleActive($moduleName)
    {
        if (!in_array($moduleName, $this->modulesStatus, true)) {
            $enabled = Module::isEnabled($moduleName);
            if ($enabled && in_array($moduleName, $this->mrshopModules)) {
                $enabled = !ApiUtils::isAppRequested() || ApiUtils::isModuleActive(
                    $moduleName,
                    $this->getJsonDecodedList()
                );
            }

            $this->modulesStatus[$moduleName] = $enabled && ($moduleName != 'productcomments' || version_compare(_PS_VERSION_, '1.7.6.0', '>='));
        }

        return $this->modulesStatus[$moduleName];
    }

    public function getToken($load = false)
    {
        if ($load && !isset($this->token)) {
            switch (true) {
                case isset($this->bodyParams['auth_token']):
                    $this->token = $this->bodyParams['auth_token'];

                    break;
                case isset($_SERVER['HTTP_AUTH_TOKEN']):
                    $this->token = $_SERVER['HTTP_AUTH_TOKEN'];

                    break;
                case isset($_REQUEST['auth_token']):
                    $this->token = $_REQUEST['auth_token'];

                    break;
            }
        }

        return $this->token;
    }

    public function getGuestId()
    {
        return $this->guestId;
    }

    public function setGuestId($guestId)
    {
        $this->guestId = $guestId;

        return $this;
    }

    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    public function getJsonDecodedList()
    {
        return json_decode(
            Configuration::get('POHS_RM_SUTATS', null, null, null, '{}'),
            true
        );
    }

    public function getMaxProductsShown()
    {
        if ($this->maxProductsShown === null) {
            $this->maxProductsShown = 30;
        }

        return $this->maxProductsShown;
    }

    // helper to try to sort out headers for people who aren't running apache
    private function getHttpHeaders()
    {
        if (function_exists('apache_request_headers')) {
            // we need this to get the actual Authorization: header
            // because apache tends to tell us it doesn't exist
            $headers = call_user_func('apache_request_headers');

            // sanitize the output of apache_request_headers because
            // we always want the keys to be Cased-Like-This and arh()
            // returns the headers in the same case as they are in the
            // request
            $out = [];
            foreach ($headers as $key => $value) {
                $key = str_replace(' ', '-', ucwords(Tools::strtolower(str_replace('-', ' ', $key))));
                $out[$key] = $value;
            }
        } else {
            // otherwise we don't have apache and are just going to have to hope
            // that $_SERVER actually contains what we need
            $out = [];
            if (isset($_SERVER['CONTENT_TYPE'])) {
                $out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
            }
            if (isset($_ENV['CONTENT_TYPE'])) {
                $out['Content-Type'] = $_ENV['CONTENT_TYPE'];
            }

            foreach ($_SERVER as $key => $value) {
                if (Tools::substr($key, 0, 5) == 'HTTP_') {
                    // this is chaos, basically it is just there to capitalize the first
                    // letter of every word that is not an initial HTTP and strip HTTP
                    // code from przemek
                    $key = str_replace(' ', '-', ucwords(Tools::strtolower(str_replace('_', ' ', Tools::substr($key, 5)))));
                    $out[$key] = $value;
                }
            }
        }

        if (!isset($out['Content-Type'])) {
            $out['Content-Type'] = 'application/json';
        }

        return $out;
    }
}
