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
{if $specific_prices}
    {foreach from=$specific_prices item='specific_price'}
        <span>
        {if $specific_price.price !=-1 }
            {l s='Fixed' mod='ets_productmanager'}: {$specific_price.price|escape:'html':'UTF-8'}
        {/if}
        {if $specific_price.reduction}
            {l s='Impact' mod='ets_productmanager'}: -{$specific_price.reduction|escape:'html':'UTF-8'}
        {/if}
        </span>
        <br />
    {/foreach}
{/if}