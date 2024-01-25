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

use Configuration;
use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use MrAPPs\MrShopApi\Handler\CategoryBrandFacetsHandler;
use MrShopApiNotification;
use Tools;

class FcmClient
{
    const IOS_TOPIC = 'ios_';

    const ANDROID_TOPIC = 'android_';

    const TOPIC_PREFIX = '';

    const SEND_TO_TOPIC = 'topic';

    const TEXT_MAX_LENGTH = 255;

    private $baseParameters;

    private $customParameters;

    private $module;

    private $language;

    private $client;

    private $serviceAccount;

    private $scope;

    private $projectId;

    public function __construct($module)
    {
        $this->module = $module;
        $this->language = null;
        $this->serviceAccount = self::firebaseJsonPath();
        $this->scope = FirebaseCloudMessaging::FIREBASE_MESSAGING;
        $this->resetParameters();
        $this->initClient();

        $data = self::readFirebaseJson(true);

        $this->projectId = isset($data['project_id']) ? $data['project_id'] : null;
    }

    private function initClient()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->serviceAccount);
        $googleClient = new Client();
        $googleClient->useApplicationDefaultCredentials();
        $googleClient->addScope($this->scope);
        $this->client = new FirebaseCloudMessaging($googleClient);
    }

    public function resetParameters()
    {
        $this->baseParameters = [
            'title' => Configuration::get('PS_SHOP_NAME'),
            'body' => null,
        ];
        $this->customParameters = [];

        return $this;
    }

    private function callFirebase($jsonNotification)
    {
        try {
            $request = new FirebaseCloudMessaging\SendMessageRequest($jsonNotification);
            if (is_null($this->projectId)) {
                return false;
            }
            $this->client->projects_messages->send('projects/'.$this->projectId, $request);

            return true;
        } catch (\Exception $exception) {
            error_log($exception);

            return false;
        }
    }

    /***************************** SETTER  ****************************/

    public function addCustomParameter($key, $value)
    {
        if (Tools::strlen($key) > 0 && Tools::strlen($value) > 0) {
            $this->customParameters[$key] = (string) $value;
        }

        return $this;
    }

    public function setType($type)
    {
        if (!in_array($type, MrShopApiNotification::getValidTypes())) {
            $type = MrShopApiNotification::$TYPE_GENERIC;
        }
        $this->addCustomParameter('type', trim($type));

        return $this;
    }

    public function setProduct($id_product = 0)
    {
        $this->addCustomParameter('id_product', $id_product);

        return $this;
    }

    public function setCategoryBrandFacets($id_category = 0, $id_manufacturer = 0)
    {
        $categoryBrandFacetsHandler = new CategoryBrandFacetsHandler($this->module);
        $parameters = $categoryBrandFacetsHandler->generateFacets($id_category, $id_manufacturer);
        $this->addCustomParameter('category_brand', $parameters);

        return $this;
    }

    public function setCMSPage($id_cms = 0)
    {
        $this->addCustomParameter('id_cms', $id_cms);

        return $this;
    }

    public function setOrder($id_order = 0)
    {
        $this->addCustomParameter('id_order', $id_order);

        return $this;
    }

    public function setBody($body)
    {
        $this->baseParameters['body'] = Tools::substr(trim($body), 0, self::TEXT_MAX_LENGTH);

        return $this;
    }

    public function setLanguage($language)
    {
        if ($language) {
            if (is_object($language)) {
                $this->language = [
                    'id_lang' => $language->id,
                    'iso_code' => $language->iso_code,
                ];
            } else {
                $this->language = $language;
            }
        }

        return $this;
    }

    private function composeTarget($baseTopic, $extraTopic = null)
    {
        if (!$this->language) {
            return false;
        }
        // var_dump($this->language['iso_code']);
        $base = $baseTopic.Tools::strtolower(trim($this->language['iso_code']));

        if ($extraTopic && is_array($extraTopic) && count($extraTopic) > 0) {
            foreach ($extraTopic as $key => $topic) {
                $extraTopic[$key] = "'".$topic."' in topics";
            }

            return [
                'base' => "'".$base."' in topics",
                'extra' => $extraTopic,
            ];
        } else {
            return $base;
        }
    }

    public function setNotificationParametersFromModel(MrShopApiNotification $notification = null)
    {
        if ($notification) {
            $idLang = $this->language ? $this->language['id_lang'] : Configuration::get('PS_LANG_DEFAULT');

            $this
                ->setType($notification->type)
                ->setBody($notification->title[$idLang]);

            switch ($notification->type) {
                case MrShopApiNotification::$TYPE_PRODUCT_DETAIL:
                    $this->setProduct($notification->id_product);

                    break;
                case MrShopApiNotification::$TYPE_CATEGORY_BRAND:
                    $this->setCategoryBrandFacets($notification->id_category, $notification->id_manufacturer);

                    break;
                case MrShopApiNotification::$TYPE_CMS:
                    $this->setCMSPage($notification->id_cms);

                    break;
            }
        }

        return $this;
    }

    private function composeNotificationJson($target)
    {
        $result = [
            'message' => [],
        ];

        if (is_array($target)) {
            $condition = $target['base'].' && ('.implode(' || ', $target['extra']).')';
            $result['message']['condition'] = $condition;
        } else {
            $result['message'][self::SEND_TO_TOPIC] = self::TOPIC_PREFIX.$target;
        }

        $result['message']['apns'] = [
            'payload' => [
                'aps' => [
                    'alert' => $this->baseParameters,
                    'badge' => 1,
                    'sound' => 'default',
                ],
            ],
        ];

        if (isset($this->customParameters)) {
            $result['message']['data'] = array_merge($this->baseParameters, $this->customParameters);
        } else {
            $result['message']['data'] = $this->baseParameters;
        }

        return $result;
    }

    public function sendNotification($extraTopic = [])
    {
        $output = [];
        foreach ([self::ANDROID_TOPIC, self::IOS_TOPIC] as $baseTopic) {
            $target = $this->composeTarget($baseTopic, $extraTopic);
            $json = $target ? $this->composeNotificationJson($target) : null;
            $output[] = [
                'topic' => $target,
                'result' => $json ? $this->callFirebase($json) : false,
            ];
        }

        return $output;
    }

    public static function readFirebaseJson($asJson = false, $filename = null)
    {
        $path = self::firebaseJsonPath($filename);
        $r = Tools::file_get_contents($path);

        return $asJson
            ? json_decode($r, true)
            : $r;
    }

    public static function writeFirebaseJson($content, $filename = null)
    {
        $path = self::firebaseJsonPath($filename);
        Installer::createConfigDirectory();

        return file_put_contents($path, $content);
    }

    public static function firebaseJsonPath($filename = null)
    {
        $f = !empty($filename)
            ? $filename
            : self::getFirebaseFilename();

        return _PS_CONFIG_DIR_.'modules/mrshopapi/'.$f;
    }

    public static function getFirebaseFilename()
    {
        $f = Configuration::get('MRSHOP_FIREBASE_SERVICE_ACCOUNT');
        if (empty($f)) {
            $f = 'firebase_config.json';
        }

        return $f;
    }
}
