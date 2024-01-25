{*
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
*}
<div class="card-block cart-summary-totals">
    {if isset($cart.subtotals.tax) && $cart.subtotals.tax}
      <div class="cart-summary-line">
        <span class="label sub">{l s='%label%:' sprintf=['%label%' => $cart.subtotals.tax.label] mod='ets_onepagecheckout'}</span>
        <span class="value sub">{$cart.subtotals.tax.value|escape:'html':'UTF-8'}</span>
      </div>
    {/if}
    {if isset($configuration.display_prices_tax_incl) &&  !$configuration.display_prices_tax_incl && isset($configuration.taxes_enabled) && $configuration.taxes_enabled}
      <div class="cart-summary-line cart-total">
        <span class="label">{$cart.totals.total_including_tax.label|escape:'html':'UTF-8'}</span>
        <span class="value">{$cart.totals.total_including_tax.value|escape:'html':'UTF-8'}
        {hook h='displayMultiPrice' price=$cart.totals.total_including_tax.amount}
        </span>
      </div>
    {else}
      <div class="cart-summary-line cart-total">
        <span class="label">{$cart.totals.total.label|escape:'html':'UTF-8'}&nbsp;{if isset($configuration.taxes_enabled) && $configuration.taxes_enabled}{$cart.labels.tax_short|escape:'html':'UTF-8'}{/if}</span>
        <span class="value">{$cart.totals.total.value|escape:'html':'UTF-8'}
        -&nbsp;{hook h='displayMultiPrice' price=$cart.totals.total.amount}
        </span>
      </div>
    {/if}
    
</div>