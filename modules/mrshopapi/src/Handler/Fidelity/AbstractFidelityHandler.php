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
 *
 */

namespace MrAPPs\MrShopApi\Handler\Fidelity;

use Configuration;
use Context;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;

abstract class AbstractFidelityHandler
{
    protected function presentFidelityCredits($credits, $displayCredits)
    {
        $fidelity = [];
        $module = ApiUtils::getModule();
        $guestCheckout = (int) Configuration::get('PS_GUEST_CHECKOUT_ENABLED');
        if ($displayCredits) {
            $text =  sprintf($module->l('By completing the purchase you will accumulate %s that can be converted into a coupon for a future purchase.', 'abstractfidelityhandler'), $credits);
            $fidelity['format'] = [
                [
                    'text' => (string) $credits,
                    'color' => '#000000',
                    'dark_color' => '#FFFFFF'
                ]
            ];
            if ($guestCheckout) {
                $text .= "\n".'*'.$module->l('Not available for Instant checkout', 'abstractfidelityhandler');
            }
        } else {
            $text = null;
        }
        $fidelity['text'] = $text;

        return $fidelity;
    }

    protected function presentProductCredits($displayCredits, $credits, $totalCredits, $minimum, $discountedNoPoints)
    {
        $rewardData = [];
        $format     = [];
        $module     = ApiUtils::getModule();

        if ($displayCredits) {
            $text = sprintf($module->l('By purchasing this product you will add %s to your loyalty program. Your cart will total %s that can be converted into a coupon for a future purchase.', 'abstractfidelityhandler'), $credits, $totalCredits);

            $format[] = [
                'text' => (string) $credits,
                'color' => '#000000',
                'dark_color' => '#FFFFFF'
            ];
            $format[] = [
                'text' => (string) $totalCredits,
                'color' => '#000000',
                'dark_color' => '#FFFFFF'
            ];
        } elseif ($discountedNoPoints) {
            $text = $module->l('No reward will be credited for this product because it is already discounted.', 'abstractfidelityhandler');
        } else {
            $minimum = Tools::displayPrice($minimum, Context::getContext()->currency->id);
            $text = sprintf($module->l('Your basket must contain at least %s of products in order to get loyalty rewards.', 'abstractfidelityhandler'), $minimum);
            $format[] = [
                'text' => (string) $minimum,
                'color' => '#000000',
                'dark_color' => '#FFFFFF'
            ];
        }

        $rewardData['text'] = $text;
        $rewardData['format'] = $format;

        return $rewardData;
    }

    protected function presentProductGift($enabled, $totalAvailable)
    {
        $module   = ApiUtils::getModule();
        $giftData = [
            'purchase_as_gift' => [
                'enabled' => (bool) $enabled
            ]
        ];

        if ($enabled) {
            // To purchase this product you can use the rewards of your loyalty program! Your available balance is $totalAvailable; the cost of the product will be deducted from your account.
            // This action cannot be canceled, do you want to proceed with the purchase?
            $giftData['purchase_as_gift']['confirm_message'] = sprintf(
                $module->l('To purchase this product you can use the rewards of your loyalty program! Your available balance is %s; the cost of the product will be deducted from your account.\nThis action cannot be canceled, do you want to proceed with the purchase?', 'abstractfidelityhandler'),
                $totalAvailable
            );

            $giftData['purchase_as_gift']['format'] = [
                [
                    'text' => (string) $totalAvailable,
                    'color' => '#000000',
                    'dark_color' => '#FFFFFF'
                ]
            ];
        }

        return $giftData;
    }

    protected function presentGiftsListAction($enabled = false)
    {
        $module   = ApiUtils::getModule();

        return [
            'label'   => $module->l('View the list of available gift products', 'abstractfidelityhandler'),
            'enabled' => (bool) $enabled,
            'action'  => 'gifts_list'
        ];
    }

    protected function presentTransformCreditsAction($totalAvailableVoucher, $enabled = false)
    {
        $module   = ApiUtils::getModule();

        return [
            'label'   => $module->l('Request voucher', 'abstractfidelityhandler').' '.$totalAvailableVoucher,
            'enabled' => (bool) $enabled,
            'confirm_message' => $module->l('Are you sure you want to transform your rewards into vouchers ?', 'abstractfidelityhandler'),
            'action' => 'transform_credits',
            'id' => 'transform_credits',
        ];
    }

    protected function presentTransformCreditsActionDiffValues($enabled = false)
    {
        $module   = ApiUtils::getModule();

        return [
            'label'   => $module->l('Request voucher', 'abstractfidelityhandler'),
            'enabled' => (bool) $enabled,
            'confirm_message' => $module->l('Are you sure you want to transform your rewards into a voucher?', 'abstractfidelityhandler'),
            'action' => 'transform_credits',
            'id' => 'transform_credits',
        ];
    }

    protected function presentAskForPaymentAction($enabled = false)
    {
        $module   = ApiUtils::getModule();

        return [
            'label'   => $module->l('Request payment of rewards (only available on the website)', 'abstractfidelityhandler'),
            'enabled' => (bool) $enabled,
            'action' => 'ask_for_payment'
        ];
    }
}
