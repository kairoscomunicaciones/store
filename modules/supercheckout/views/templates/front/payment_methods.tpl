{if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && $address_selector == 'new'}
    <span class="permanent-warning" style="display: inline-block;">{l s='Please choose your shipping address first in order to check the payment methods.' mod='supercheckout'}</span>
{else}
    <div class="velsof_sc_overlay"></div>
    {if isset($payment_method_not_required)}
        <div class='supercheckout-checkout-content' style='display:block'>
            <div class='permanent-warning not-required-msg'>{l s='No payment method required.' mod='supercheckout'}</div>
        </div>

    {else if $free_payment_avail['check_avail'] || $free_payment_avail['wire_avail']}
        
            <ul>
                {if $free_payment_avail['check_avail']}
                <li class="">
                    <div class="radio ">
                        <input type="radio" name="payment_method" data-module-name="ps_checkpayment" value="{$free_payment_avail['check_id']}" id="{$free_payment_avail['check_id']}" checked="checked" class="">

                        <label id="payment_lbl_11" for="{$free_payment_avail['check_id']}">
                            {l s='Pay by Check' mod='supercheckout'}
                        </label>
                        <div class="paymentInfo" id="payment_methods_additional_container">
                            <div class="payment-option-1_info_container payment-additional-info" style="display: none;">
                                <div class="supercheckout-blocks js-additional-information definition-list additional-information">
                                </div>
                                <div id="pay-with-form">

                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            {/if}
            {if $free_payment_avail['wire_avail']}
                <li>
                    <div class="radio ">
                        <input type="radio" name="payment_method" data-module-name="ps_wirepayment" value="{$free_payment_avail['wire_id']}" id="{$free_payment_avail['wire_id']}" class="">

                        <label id="payment_lbl_29" for="{$free_payment_avail['wire_id']}">
                            {l s='Pay by bank wire' mod='supercheckout'}
                        </label>
                        <div class="paymentInfo" id="payment_methods_additional_container">
                            <div class="payment-option-2_info_container payment-additional-info" style="display:none;">
                                <div class="supercheckout-blocks js-additional-information definition-list additional-information">
                                </div>
                                <div id="pay-with-form">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            {/if}
        </ul>
    {else}
        <div class='supercheckout-checkout-content' style='display:block'>
            <div class='permanent-warning not-required-msg'>{l s='Currently, You do not have installed or enabled any of the following payment methods: 1. Payment By Check 2. BankWire. Please enable the same in order to place an order with Free Supercheckout module. To use other payment methods, please purchase our paid module.' mod='supercheckout'}</div>
        </div>
    {/if}
{/if}
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2016 Knowband
*}