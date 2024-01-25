{if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && $address_selector == 'new'}
    <span class="permanent-warning" style="display: inline-block;"> {l s='Save your address first in order to check actual shipping methods & cost' mod='supercheckout'} </span>
{else}
<div class="velsof_sc_overlay"></div>
{if isset($is_virtual_cart) && $is_virtual_cart}
    <input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
    <div class="supercheckout-checkout-content" style="display:block">
        <div class="not-required-msg" style="display: block;">{l s='No Delivery Method Required' mod='supercheckout'}</div>
    </div>
{else}
   
    {if isset($shipping_errors) && is_array($shipping_errors)}
        {foreach from=$shipping_errors item='shippig_error'}
            <div class="supercheckout-checkout-content" style="display:block">
                <div class="permanent-warning" style="display: block;">{$shippig_error}</div>
            </div>
        {/foreach}
    {else}
        <div class="supercheckout-checkout-content" style="display:block"></div>
        <ul>
            <li class="highlight alert-info">
                <div class="radio ">
                    <input class="supercheckout_shipping_option delivery_option_radio" type="radio" name="delivery_option[{$id_address|intval}]" value="{$id_carrier nofilter}" id="shipping_method_{$id_address|intval}_{$id_carrier|intval}" checked="checked">
                    <label for="shipping_method_{$id_address|intval}_{$id_carrier|intval}">
                        {l s='Demo Carrier' mod='supercheckout'}                                                       
                        <span class="supercheckout-shipping-small-title shippingPrice">{if $is_free}{l s='Free' mod='supercheckout'}{else}{$shipping_cost}{/if}</span></label>
                        <p class="shippingInfo supercheckout-shipping-small-title">{l s='Delivery next day' mod='supercheckout'}</p>
                </div>
            </li>

        </ul>
        {/if}
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