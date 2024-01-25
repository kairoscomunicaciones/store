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
{if $field.type=='text' || $field.type=='tags'}
    {foreach from=$languages item='language'}
        {if $language.id_lang!=$id_lang_current}
            <div class="translatable-field lang-{$language.id_lang|intval}" style="display:none;">
                <div class="col-lg-11">
                    {if isset($valueFieldPost)}
                        {if isset($valueFieldPost[$field.name][$language.id_lang])}
                            {assign var='value_text' value=$valueFieldPost[$field.name][$language.id_lang]}
                        {else}
                            {assign var='value_text' value=''}
                        {/if}
                    {/if}
                    <input placeholder="{$row.title|escape:'html':'UTF-8'}" title="{$row.title|escape:'html':'UTF-8'}" class="form-control {if $field.type=='tags'} tagify{/if}" id="{$field.name|escape:'html':'UTF-8'}_{$id_product|intval}_{$language.id_lang|intval}" name="{$field.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                </div>
                <div class="col-lg-1">
                    <div class="toggle_form">
                        <button class="btn btn-default dropdown-toggle" type="button" tabindex="-1" data-toggle="dropdown">
                            {$language.iso_code|escape:'html':'UTF-8'}
                            <i class="icon-caret-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            {foreach from=$languages item='lang'}
                                <li>
                                    <a class="hideOtherLanguageInline" href="#" tabindex="-1" data-id-lang="{$lang.id_lang|intval}">{$lang.name|escape:'html':'UTF-8'}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        {/if}
    {/foreach}
{/if}
{if $field.type=='textarea'}
    {foreach from=$languages item='language'}
        {if $language.id_lang!=$id_lang_current}
            <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                <div class="col-lg-11">
                    {if isset($valueFieldPost[$field.name][$language.id_lang])}
                        {assign var='value_text' value=$valueFieldPost[$field.name][$language.id_lang]}
                    {else}
                        {assign var='value_text' value=''}
                    {/if}
                    <textarea id="{$field.name|escape:'html':'UTF-8'}_{$id_product|intval}_{$language.id_lang|intval}" placeholder="{$row.title|escape:'html':'UTF-8'}" class="form-control{if isset($field.autoload_rte) && $field.autoload_rte} ets_pmn_autoload_rte ets_pmn_autoload_rte_runing{/if}{if isset($field.small_text) && $field.small_text} change_length{/if}" name="{$field.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-default dropdown-toggle" type="button" tabindex="-1" data-toggle="dropdown">
                        {$language.iso_code|escape:'html':'UTF-8'}
                        <i class="icon-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$languages item='lang'}
                            <li>
                                <a class="hideOtherLanguageInline" href="#" tabindex="-1" data-id-lang="{$lang.id_lang|intval}">{$lang.name|escape:'html':'UTF-8'}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/if}
    {/foreach}
{/if}