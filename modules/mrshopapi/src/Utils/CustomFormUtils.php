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
use Tools;

class CustomFormUtils
{
    const TEXT_FIELD = 'text';

    const TEXTAREA_FIELD = 'textarea';

    const EMAIL_FIELD = 'email';

    const BOOLEAN_FIELD = 'boolean';

    const VOTING_FIELD = 'voting';

    public static function getProductReviewFields(
        $criterions = [],
        $exclude = []
    ) {
        $module = ApiUtils::getModule();
        $productReviewFields = [];

        foreach ($criterions as $c) {
            $productReviewFields[] = [
                'id' => 'voting_'.$c['id_product_comment_criterion'],
                'name' => $c['name'],
                'type' => CustomFormUtils::VOTING_FIELD,
                'required' => true,
                'editable' => true,
                'default' => 1
            ];
        }

        $productReviewFields[] = [
            'id' => 'customer_name',
            'name' => $module->l('Your name', 'customformutils'),
            'type' => CustomFormUtils::TEXT_FIELD,
            'validation_regex' => '',
            'editable' => true,
            'required' => true,
            'default' => ''
        ];

        $productReviewFields[] = [
            'id' => 'title',
            'name' => $module->l('Title', 'customformutils'),
            'type' => CustomFormUtils::TEXT_FIELD,
            'validation_regex' => '',
            'editable' => true,
            'required' => true,
            'default' => ''
        ];

        $productReviewFields[] = [
            'id' => 'content',
            'name' => $module->l('Review', 'customformutils'),
            'type' => CustomFormUtils::TEXTAREA_FIELD,
            'validation_regex' => '',
            'editable' => true,
            'required' => true,
            'default' => ''
        ];

        if ($exclude) {
            foreach ($productReviewFields as $index => $field) {
                if (in_array($field['id'], $exclude)) {
                    array_splice($productReviewFields, $index, 1);
                }
            }
        }

        return $productReviewFields;
    }

    public static function getRegistrationFields($exclude = [])
    {
        $module = ApiUtils::getModule();

        $registrationFields = [
            [
                'id' => 'firstname',
                'name' => $module->l('Firstname', 'customformutils'),
                'type' => CustomFormUtils::TEXT_FIELD,
                'validation_regex' => ApiUtils::isPS16()
                    ? Tools::cleanNonUnicodeSupport('/^[^0-9!<>,;?=+()@#"°{}_$%:]*$/u')
                    : Tools::cleanNonUnicodeSupport('/^[^0-9!<>,;?=+()@#"°{}_$%:¤|]*$/u'),
                'validation' => '',
                'editable' => true,
                'required' => true,
                'default' => '',
            ],
            [
                'id' => 'lastname',
                'name' => $module->l('Lastname', 'customformutils'),
                'type' => CustomFormUtils::TEXT_FIELD,
                'validation_regex' => ApiUtils::isPS16()
                    ? Tools::cleanNonUnicodeSupport('/^[^0-9!<>,;?=+()@#"°{}_$%:]*$/u')
                    : Tools::cleanNonUnicodeSupport('/^[^0-9!<>,;?=+()@#"°{}_$%:¤|]*$/u'),
                'validation' => '',
                'editable' => true,
                'required' => true,
                'default' => '',
            ],
        ];

        if (ApiUtils::isB2B()) {
            $b2bRegistrationFields = [
                [
                    'id' => 'company',
                    'name' => $module->l('Company', 'customformutils'),
                    'type' => CustomFormUtils::TEXT_FIELD,
                    'validation_regex' => '',
                    'validation' => '',
                    'editable' => true,
                    'required' => false,
                    'default' => '',
                ],
                [
                    'id' => 'siret',
                    'name' => $module->l('Identification number', 'customformutils'),
                    'type' => CustomFormUtils::TEXT_FIELD,
                    'validation_regex' => '',
                    'validation' => '',
                    'editable' => true,
                    'required' => false,
                    'default' => '',
                ],
            ];

            if (ApiUtils::isPS16()) {
                $b2bRegistrationFields[] = [
                    'id' => 'ape',
                    'name' => $module->l('Ape', 'customformutils'),
                    'type' => CustomFormUtils::TEXT_FIELD,
                    'validation_regex' => '',
                    'validation' => '',
                    'editable' => true,
                    'required' => false,
                    'default' => '',
                ];

                $b2bRegistrationFields[] = [
                    'id' => 'website',
                    'name' => $module->l('Website', 'customformutils'),
                    'type' => CustomFormUtils::TEXT_FIELD,
                    'validation_regex' => '',
                    'validation' => 'isUrl',
                    'editable' => true,
                    'required' => false,
                    'default' => '',
                ];
            }

            $registrationFields = array_merge($registrationFields, $b2bRegistrationFields);
        }

        $registrationFields = array_merge($registrationFields, [
            [
                'id' => 'email',
                'name' => $module->l('Email', 'customformutils'),
                'type' => CustomFormUtils::EMAIL_FIELD,
                'validation_regex' => Tools::cleanNonUnicodeSupport('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui'),
                'validation' => 'isEmail',
                'editable' => true,
                'required' => true,
                'default' => '',
            ],
            [
                'id' => 'passwd',
                'name' => $module->l('Password', 'customformutils'),
                'type' => CustomFormUtils::TEXT_FIELD,
                'validation_regex' => ApiUtils::isPS16()
                    ? Tools::cleanNonUnicodeSupport('/^.{5,255}/u')
                    : Tools::cleanNonUnicodeSupport('/^.{5,72}/u'),
                'validation' => '',
                'editable' => true,
                'required' => true,
                'default' => '',
            ],
        ]);

        if ((bool) Configuration::get('PS_CUSTOMER_OPTIN')) {
            $registrationFields[] = [
                'id' => 'optin',
                'name' => $module->l('Receive special offers from our partners', 'customformutils'),
                'type' => CustomFormUtils::BOOLEAN_FIELD,
                'validation_regex' => '',
                'validation' => '',
                'editable' => true,
                'required' => false,
                'default' => false,
            ];
        }

        if (!ApiUtils::isPS16() || (ApiUtils::isPS16() && (bool) Configuration::get('PS_CUSTOMER_NWSL'))) {
            $registrationFields[] = [
                'id' => 'newsletter',
                'name' => $module->l('Subscribe to our newsletter', 'customformutils'),
                'type' => CustomFormUtils::BOOLEAN_FIELD,
                'validation_regex' => '',
                'validation' => '',
                'editable' => true,
                'required' => false,
                'default' => false,
            ];
        }

        if ($exclude) {
            foreach ($registrationFields as $index => $field) {
                if (in_array($field['id'], $exclude)) {
                    array_splice($registrationFields, $index, 1);
                }
            }
        }

        return $registrationFields;
    }
}
