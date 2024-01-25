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

<div id="product-images-form">
    <button class="float-right close ets_pmn_close_image" type="button">
        <i class="icon icon-close"></i>{l s='close' mod='ets_productmanager'}
    </button>
    <div class="row">
        <div class="col-lg-12 col-xl-7">
            <div class="checkbox">                          
                <label><input id="form_image_cover" name="image_cover" value="1"{if $image_class->cover} checked="checked"{/if} type="checkbox" />{l s='Cover image' mod='ets_productmanager'} </label>
            </div>
        </div>
        <div class="col-lg-12 col-xl-4">
            <a class="btn btn-link btn-sm open-image" href="{$url_image|escape:'html':'UTF-8'}">
                <i class="icon icon-zoom_in"></i>{l s='Zoom' mod='ets_productmanager'}
            </a>
        </div>
    </div>
    <label class="control-label">{l s='Caption' mod='ets_productmanager'}</label>
    <div id="form_image_legend" class="translations tabbable">
        {if $languages && count($languages)>1}
            {foreach from=$languages item='language'}
                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                    <div class="col-lg-11">
                        {assign var='value_text' value=$legends[$language.id_lang]}
                        <textarea class="form-control" name="legend_{$language.id_lang|intval}" >{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
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
                                    <a class="hideOtherLanguageImage" href="#" tabindex="-1" data-id-lang="{$lang.id_lang|intval}" data-iso="{$lang.iso_code|escape:'html':'UTF-8'}">{$lang.name|escape:'html':'UTF-8'}</a>
                                </li>
                            {/foreach}
                        </ul>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            {assign var='value_text' value=$legends[$id_lang_default]}
            <textarea class="form-control" name="legend_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
        {/if}
    </div>
    <div class="actions">
        <input type="hidden" name="id_image" value="{$image_class->id|intval}"/>
        <button class="btn btn-sm btn-primary pull-sm-right ets_pmn_save_image" type="button">{l s='Save image settings' mod='ets_productmanager'}</button>
    </div>
</div>