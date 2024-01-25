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
<section id="order-history" class="box">
    <h3>{l s='Additional info' mod='ets_onepagecheckout'}</h3>
    <ul>
    {foreach from=$additional_fields item='field'}
        <li>
            <strong>{$field.title|escape:'html':'UTF-8'}:</strong> {if $field.value}{if $field.file_name}<a href="{$link->getModuleLink('ets_onepagecheckout','download',['file_name'=>$field.value])|escape:'html':'UTF-8'}" title="{l s='Download' mod='ets_onepagecheckout'}">{$field.file_name|escape:'html':'UTF-8'}</a>{else}{$field.value|nl2br nofilter}{/if}{else}--{/if}
        </li>
    {/foreach}
    </ul>
</section>