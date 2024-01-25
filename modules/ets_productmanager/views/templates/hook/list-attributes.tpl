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
{if $product_attributes}
    {foreach from=$product_attributes item='product_attribute'}
        <span>{$product_attribute.name|escape:'html':'UTF-8'} ({l s='Qty:' mod='ets_productmanager'}&nbsp;{$product_attribute.quantity|intval}, {l s='Impact:' mod='ets_productmanager'}&nbsp;{$product_attribute.price|escape:'html':'UTF-8'})</span><br />
    {/foreach}
{/if}