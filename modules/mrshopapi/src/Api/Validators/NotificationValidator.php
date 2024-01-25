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

use Language;
use MrShopApiNotification;

class NotificationValidator
{
    private $id;

    private $body;

    private $module;

    public $errors = [];

    public $isValid = false;

    public $notification = null;

    public $sentImmediately = false;

    public function __construct($module, $id, $bodyParams)
    {
        $this->id = $id;
        $this->body = $bodyParams;
        $this->module = $module;
        $this->validate();
    }

    public function validate()
    {
        $this->notification = new MrShopApiNotification($this->id);

        $sentDate = new \DateTime();
        $sentDate->setTimestamp($this->body['sentDate'] / 1000);
        $this->notification->date = $sentDate->format('Y-m-d H:i:s');
        $this->notification->type = $this->body['notificationType'];
        $this->notification->id_group = $this->body['group'];

        switch ($this->body['notificationType']) {
            case MrShopApiNotification::$TYPE_PRODUCT_DETAIL:
                if (empty($this->body['product']) || (int) $this->body['product']['id'] == 0) {
                    $this->errors[] = $this->module->l('Select a product to continue');
                    $this->notification = null;

                    return;
                }

                $this->notification->id_product = (int) $this->body['product']['id'];

                break;
            case MrShopApiNotification::$TYPE_CATEGORY_BRAND:
                if (empty($this->body['category']) || (int) $this->body['category']['id'] == 0) {
                    $this->errors[] = $this->module->l('Select a category to continue');
                    $this->notification = null;

                    return;
                }

                $this->notification->id_category = (int) $this->body['category']['id'];

                if (isset($this->body['manufacturer']) && (int) $this->body['manufacturer']['id'] > 0) {
                    $this->notification->id_manufacturer = (int) $this->body['manufacturer']['id'];
                }

                break;
            case MrShopApiNotification::$TYPE_CMS:
                if (empty($this->body['cms']) || (int) $this->body['cms']['id'] == 0) {
                    $this->errors[] = $this->module->l('Select a CMS page to continue.');
                    $this->notification = null;

                    return;
                }

                $this->notification->id_cms = (int) $this->body['cms']['id'];

                break;
        }

        $this->isValid = true;
        if (static::immediatelySend($this->notification->date)) {
            $this->sentImmediately = true;
            $this->notification->active = true;
        }

        if ($this->isValid) {
            $this->validateTranslations();
        }
    }

    public static function immediatelySend($date)
    {
        return new \DateTime($date) <= new \DateTime();
    }

    public function validateTranslations()
    {
        $languages = Language::getLanguages();

        $title = [];
        foreach ($languages as $language) {
            $found = false;
            foreach ($this->body['title'] as $isoCode => $val) {
                if ($language['iso_code'] === $isoCode) {
                    $found = true;
                    $title[$language['id_lang']] = $val;

                    break;
                }
            }

            if (!$found) {
                $this->errors[] = $this->module->l('All languages are required');
                $this->notification = null;
                $this->isValid = false;

                return;
            }
        }

        $this->notification->title = $title;
    }
}
