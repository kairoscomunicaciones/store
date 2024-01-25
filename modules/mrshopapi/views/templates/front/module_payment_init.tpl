{*
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2022
 * @license Mr. APPs
*}

<!doctype html>
<html lang="{$language.locale}">

<head>
    {block name='head'}
        {include file='_partials/head.tpl'}
    {/block}
    <style>
        p.alert.alert-warning.accept-cgv {
            display: none;
        }
        div#payment-option-1-container {
            display: none;
        }
    </style>
</head>

<body id="checkout" class="{$page.body_classes|classnames}">

{block name='hook_after_body_opening_tag'}
    {hook h='displayAfterBodyOpeningTag'}
{/block}
<main>
    <section id="wrapper">
        <div class="container" style="margin-top: 25px;">
            {block name="content_wrapper"}
            <div id="content-wrapper" class="js-content-wrapper left-column right-column col-sm-4 col-md-6">
                {hook h='displayPaymentTop' mod=$moduleName}
                {block name='content'}
                <div class="payment-options">
                    <h4>{l s='Pay with ' mod='mrshopapi'}{$moduleDisplayName}</h4>
                </div>
                <div id="{$buttonId}-form" class="">
                    {if $option.form && !$option.module_name|strstr:"ps_checkout"}
                    {$option.form nofilter}
                    <div id="payment-confirmation" class="js-payment-confirmation">
                        <div class="ps-shown-by-js">
                            <button type="submit" class="btn btn-primary center-block">
                                {$buttonText}
                            </button>
                        </div>
                    </div>
                    {elseif $option.module_name|strstr:"ps_checkout"}
                    <div class="payment-options" style="margin-bottom:20px;">
                        <div id="ps_checkout-loader" class="express-checkout-block mb-2"
                             style="display: none;">
                            <div class="express-checkout-block-wrapper">
                                <p class="express-checkout-spinner-text"></p>
                                <div class="express-checkout-spinner">
                                    <img src="/modules/ps_checkout/views/img/tail-spin.svg"
                                         alt="Si prega di attendere, caricamento di metodi di pagamento aggiuntivi in corso.">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="payment-option-1-container" class="payment-option clearfix" style="display:none">
                                                <span class="custom-radio pull-xs-left">
                                                    <input class="ps-shown-by-js binary" id="payment-option-1"
                                                           data-module-name="{$option.module_name}"
                                                           name="payment-option" type="radio" required="" checked>
                                                </span>
                                <form method="GET" class="ps-hidden-by-js" style="display: none;">
                                    <button class="ps-hidden-by-js" type="submit"
                                            name="select_payment_option" value="payment-option-1"
                                            style="display: none;"></button>
                                </form>
                                <label for="payment-option-1">
                                    <span><strong>{$option.call_to_action_text}</strong></span>
                                </label>
                            </div>
                            <form id="conditions-to-approve" method="GET" style="display:none">
                                <ul>
                                    <li>
                                        <span class="custom-checkbox">
                                            <input id="conditions_to_approve[terms-and-conditions]"
                                                   name="conditions_to_approve[terms-and-conditions]" required=""
                                                   type="checkbox" value="1" class="" checked>
                                        </span>
                                        <div class="condition-label">
                                            <label class="js-terms"
                                                   for="conditions_to_approve[terms-and-conditions]">
                                                Accetto i <a
                                                        href="https://demo.ecommerceapp.it/it/content/3-termini-e-condizioni-di-uso"
                                                        id="cta-terms-and-conditions-0">termini del servizio</a>
                                                e aderirò a essi incondizionatamente.
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                            <div id="payment-confirmation" class="js-payment-confirmation" style="display:none">
                                <div class="ps-shown-by-js">
                                    <button type="submit" class="btn btn-primary center-block">
                                        {$buttonText}
                                    </button>
                                </div>
                            </div>
                        </div>
                        {else}
                        <form id="payment-form" method="POST" action="{$option.action nofilter}">
                            {foreach from=$option.inputs item=input}
                                <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                            {/foreach}
                            <button style="display:none" id="{$buttonId}" type="submit"></button>
                            <div id="payment-confirmation" class="js-payment-confirmation">
                                <div class="ps-shown-by-js">
                                    <button type="submit" class="btn btn-primary center-block">
                                        {$buttonText}
                                    </button>
                                </div>
                            </div>
                        </form>
                        {/if}
                    </div>
                    {/block}
                    {hook h='displayPaymentByBinaries' mod=$moduleName}
                </div>
                {/block}
            </div>
    </section>
</main>

{block name='javascript_bottom'}
    {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    <script>
        jQuery( document ).ready(function () {
            jQuery('#payment-confirmation button[type="submit"]').on('click', function () {
                jQuery('#{$buttonId}').click();
            })
        })
    </script>
{/block}

{block name='hook_before_body_closing_tag'}
    {hook h='displayBeforeBodyClosingTag'}
{/block}
</body>
</html>