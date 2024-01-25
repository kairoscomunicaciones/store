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
<table id="informational-tab" cellspacing="0" cellpadding="0" style="width: 100%;border-collapse: collapse;">
    <tr>
        {if isset($additional_fields) && $additional_fields}
            <td style="text-align:left;font-size:11px;border:1px solid #999999;width:100%;margin-left:-20%">
                <table cellspacing="0" cellpadding="0" style="width: 100%;border-collapse: collapse;">
                    <tr>
                        <td style="width: 100%;">
                            <h4 class="bold" style="text-align:left;display:block;font-size:11px;">{$order_info_title|escape:'html':'UTF-8'}</h4>
                            <br/><br/>
                            {foreach from=$additional_fields item='field'}
                                {$field.title|escape:'html':'UTF-8'}:
                                {if $field.value}
                                    {if $field.file_name}
                                        {if $is_admin}
                                            <a href="{$link_base|escape:'html':'UTF-8'}/modules/ets_onepagecheckout/download.php?file_name={$field.value|escape:'html':'UTF-8'}&id_order={$id_order|intval}" title="{l s='Download' mod='ets_onepagecheckout'}">
                                        {else}
                                            <a href="{$link->getModuleLink('ets_onepagecheckout','download',['file_name'=>$field.value])|escape:'html':'UTF-8'}" title="{l s='Download' mod='ets_onepagecheckout'}">
                                        {/if}
                                            {$field.file_name|escape:'html':'UTF-8'}
                                        </a>
                                    {else}
                                        {$field.value|nl2br nofilter}
                                    {/if}
                                {else}
                                    --
                                {/if}
                                <br />
                            {/foreach}
                        </td>
                    </tr>
                </table>
            </td>
        {/if}
    </tr>
</table>
