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

use AppHome;
use AppHomeItem;
use Language;

class AppHomeItemValidator
{
    private $module;
    // private $imageUtils;
    // private $imageService;

    public $appHomeItems = [];

    public $errors = [];

    public $requiredFields = [];

    const JSON_IMAGE = 'image';

    const JSON_TITLE = 'title';

    public function __construct($appHomeId, $module, $banners)
    {
        $this->requiredFields = [
            'image' => [],
            'bannerType' => [],
            'product' => [
                'dependsOn' => [
                    'bannerType' => AppHome::$BANNER_TYPE_PRODUCT_DETAIL
                ]
            ],
            'category' => [
                'dependsOn' => [
                    'bannerType' => AppHome::$BANNER_TYPE_CATEGORY_BRAND
                ]
            ],
            'manufacturer' => [
                'dependsOn' => [
                    'bannerType' => AppHome::$BANNER_TYPE_CATEGORY_BRAND
                ]
            ],
            'cms' => [
                'dependsOn' => [
                    'bannerType' => AppHome::$BANNER_TYPE_CMS
                ]
            ]
        ];
        $this->module = $module;
        // $this->imageUtils = new ImageUtils();
        // $this->imageService = $imageService;
        $this->validate($appHomeId, $banners);
    }

    public function validate($appHomeId, $items)
    {
        foreach ($items as $item) {
            $id = isset($item['id']) ? $item['id'] : null;

            $tmp = [];
            $tmp['id_app_home'] = $appHomeId;

            // is it required ?
            foreach ($item as $key => $val) {
                $value = $val;
                if ($this->isRequiredKeys($key)) {
                    switch ($key) {
                        case self::JSON_TITLE:
                            // title: {'it': '', 'en': '', ...}
                            // All languages required
                            if (is_array($val)) {
                                $languages = Language::getLanguages();
                                foreach ($languages as $l) {
                                    $valid = false;
                                    foreach ($val as $iso => $title) {
                                        if ($iso == $l['iso_code']) {
                                            if ($l['active'] == true) {
                                                $valid = true;

                                                break;
                                            }
                                        }
                                    }
                                    if ($valid == false) {
                                        $this->errors[] = sprintf(
                                            $this->module->l('Missing title (%s)'),
                                            $l['iso_code']
                                        );
                                    } else {
                                        $value[(int) $l['id_lang']] = $title;
                                    }
                                }
                            } else {
                                $this->errors[] = $this->module->l('Invalid title');

                                return;
                            }

                            break;
                        case self::JSON_IMAGE:
                            // Image exists ?
                            $imageValid = isset($item[$key]) && isset($item['hasMultiLangImage']);
                            if ($imageValid) {
                                if (false == isset($item['id'])) {
                                    // New record default null
                                    $value = null;
                                } else {
                                    $appItem = new AppHomeItem((int) $item['id']);
                                    $value = $appItem->hash;
                                }
                            } else {
                                $this->errors[] = $this->module->l('Image is required');

                                return;
                            }

                            break;
                    }
                } else {
                    // Other types
                    foreach ($this->requiredFields as $fieldName => $validator) {
                        if ($key == $fieldName) {
                            if (count($validator) == 0 && empty($val)) {
                                $this->errors[] = sprintf($this->module->l('%s field is required'), $key);

                                return;
                            } elseif (count($validator) > 0 && isset($validator['dependsOn'])) {
                                foreach ($validator['dependsOn'] as $dependKey => $dependVal) {
                                    if ($key == $dependKey && (empty($item[$dependKey]) || $item[$dependKey] != $dependVal)) {
                                        $this->errors[] = sprintf($this->module->l('Banner is not valid'), $dependKey);

                                        return;
                                    }
                                }
                            }

                            if (isset($item[$key]['id'])) {
                                $value = $item[$key]['id'];
                            } else {
                                $value = $item[$key];
                            }

                            break;
                        }
                    }
                }

                $tmp[$this->getObjectModelKey($key)] = $value;
            }

            $appHomeItem = new AppHomeItem($id);
            $appHomeItem->hydrate($tmp);
            $this->appHomeItems[] = $appHomeItem;
        }
    }

    private function getObjectModelKey($jsonKey)
    {
        if (in_array($jsonKey, array_keys($this->jsonToObjectModel()))) {
            return $this->jsonToObjectModel()[$jsonKey];
        }

        return $jsonKey;
    }

    private function jsonToObjectModel()
    {
        return [
            'bannerType' => 'banner_type',
            'title' => 'title',
            'image' => 'hash',
            'product' => 'id_product',
            'cms' => 'id_cms',
            'manufacturer' => 'id_manufacturer',
            'category' => 'id_category'
        ];
    }

    private function isRequiredKeys($key)
    {
        return in_array($key, [
            self::JSON_IMAGE,
            self::JSON_TITLE
        ]);
    }
}
