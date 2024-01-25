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
use Language;
use MrAPPs\MrShopApi\Api\Transformers\AdminAppHomeTransformer;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ImageUtils;
use ObjectModel;

class AdminAppHomeValidator
{
    protected $module;

    protected $rules;

    /** @var \ObjectModel $dummy */
    protected $dummy;

    public function __construct($module, $appHome)
    {
        $this->module = $module;
        $this->dummy  = $appHome;
        $this->rules = $this->dummy->getDefinition(AppHome::class)['fields'];
    }

    public function validate($params)
    {
        if (false == is_array($params) || false == ApiUtils::isAssociative($params)) {
            throw new \InvalidArgumentException('The argument to validate has to be an associative array');
        }
        $map = array_flip(AdminAppHomeTransformer::jsonToDb());
        $errors = [];
        foreach ($this->rules as $key => $input) {
            if (false == isset($map[$key])) {
                continue;
            }
            $k   = $map[$key];
            $val = isset($params[$k])
                    ? $params[$k]
                    : null;

            if (false == empty($input['lang']) && $input['lang'] == true) {
                $v = AdminAppHomeTransformer::langToDb($val);
                foreach ($v as $langId => $value) {
                    $result = $this->dummy->validateField($key, $value, (int) $langId, [], true);
                    if ($result !== true) {
                        $errors[] = $result;
                    }
                }
            } else {
                $result = $this->dummy->validateField($key, $val, null, [], true);
                if ($result !== true) {
                    $errors[] = $result;
                }
            }

            switch ($k) {
                case AdminAppHomeTransformer::JSON_TYPE:
                    $validTypes = AppHome::getValidTypes();
                    if (false == in_array($val, $validTypes)) {
                        $errors[] = sprintf($this->module->l('%s is not a valid section type', 'adminapphomevalidator'), $val);
                    }

                    break;
            }
        }

        if (isset($params[AdminAppHomeTransformer::JSON_TYPE])) {
            $type = $params[AdminAppHomeTransformer::JSON_TYPE];
            if ($type == AdminAppHomeTransformer::TYPE_BANNER) {
                $errors = array_merge(
                    $errors,
                    $this->validateBannerFields($params)
                );
            } elseif (in_array($type, AppHome::getTypesWithLayout())) {
                $errors = array_merge(
                    $errors,
                    $this->validateLayoutable($params)
                );
            }
        }

        return $errors;
    }

    private function checkValidDatesRange($validDates)
    {
        if (count($validDates) == 2) {
            /**
             * @var \DateTime $from
             * @var \DateTime $to
             */
            list($from, $to) = $validDates;

            if ($from->getTimestamp() > $to->getTimestamp()) {
                return $this->module->l('Activation date should be before deactivation date', 'adminapphomevalidator');
            }
        }

        return null;
    }

    protected function validateBannerFields($params)
    {
        $errors = [];
        $map = array_flip(AdminAppHomeTransformer::jsonToDb());
        $validDates = [];
        foreach ($this->rules as $key => $input) {
            if (isset($map[$key])) {
                $k = $map[$key];
                $val = isset($params[$k]) ? $params[$k] : null;
                switch ($k) {
                    case AdminAppHomeTransformer::JSON_ACTIVE_TO:
                    case AdminAppHomeTransformer::JSON_ACTIVE_FROM:
                        if (isset($val)) {
                            $d = new \DateTime();
                            $d->setTimestamp($val / 1000);
                            $index = $k == AdminAppHomeTransformer::JSON_ACTIVE_FROM
                                ? 0
                                : 1;
                            $validDates[$index] = $d;
                        }

                        break;
                    case AdminAppHomeTransformer::JSON_BANNER_TYPE:
                        $validTypes = AppHome::getValidBannerTypes();
                        if (false == in_array($val, $validTypes)) {
                            $errors[] = sprintf($this->module->l('%s is not a valid banner type', 'adminapphomevalidator'), $val);
                        }

                        break;
                    case AdminAppHomeTransformer::JSON_BANNER_SIZE:
                        $validSizes = AppHome::getValidBannerSizes();
                        if (false == in_array($val, $validSizes)) {
                            $errors[] = sprintf($this->module->l('%s is not a valid banner size', 'adminapphomevalidator'), $val);
                        }

                        break;
                    case AdminAppHomeTransformer::JSON_IMAGE:
                        $utils = new ImageUtils();
                        $exists = $utils->tmpFileExists($val);
                        if (false == $exists && false == empty($this->dummy->getId())) {
                            $p = $utils->getBannerImagePath($this->dummy->getId());
                            $exists = file_exists($p);
                        }

                        if (false == $exists) {
                            $errors[] = sprintf($this->module->l('Image %s has not been uploaded', 'adminapphomevalidator'), $val);
                        }

                        break;
                }
            }
        }

        $invalidRange = $this->checkValidDatesRange($validDates);
        if ($invalidRange) {
            $errors[] = $invalidRange;
        }

        return $errors;
    }

    protected function validateLayoutable($params)
    {
        $errors = [];
        $map = array_flip(AdminAppHomeTransformer::jsonToDb());
        $validDates = [];
        foreach ($this->rules as $key => $input) {
            if (isset($map[$key])) {
                $k = $map[$key];
                $val = isset($params[$k]) ? $params[$k] : null;
                switch ($k) {
                    case AdminAppHomeTransformer::JSON_ACTIVE_TO:
                    case AdminAppHomeTransformer::JSON_ACTIVE_FROM:
                        if (isset($val)) {
                            $d = new \DateTime();
                            $d->setTimestamp($val / 1000);
                            $index = $k == AdminAppHomeTransformer::JSON_ACTIVE_FROM
                                ? 0
                                : 1;
                            $validDates[$index] = $d;
                        }

                        break;
                    case AdminAppHomeTransformer::JSON_LAYOUT:
                        $validLayout = [1, 2];
                        if (false == in_array($val, $validLayout)) {
                            $errors[] = sprintf($this->module->l('%s is not a valid layout', 'adminapphomevalidator'), $val);
                        }

                        break;
                    case AdminAppHomeTransformer::JSON_ORDER_TYPE:
                        $validOrders = AppHome::getAvailableOrders();
                        if (false == in_array($val, $validOrders)) {
                            $errors[] = sprintf($this->module->l('%s is not a valid sort type', 'adminapphomevalidator'), $val);
                        }

                        break;
                }
            }
        }

        $invalidRange = $this->checkValidDatesRange($validDates);
        if ($invalidRange) {
            $errors[] = $invalidRange;
        }

        return $errors;
    }

    public static function create($module, $appHome)
    {
        return new static($module, $appHome);
    }
}
