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
<div id="custom_fields" class="mt-3">
    <p class="subtitle">{l s='Customers can personalize the product by entering some text or by providing custom image files.' mod='ets_productmanager'}</p>
    <ul class='customFieldCollection nostyle' data-prototype='<div class="row">
<input type="hidden" id="form_step6_custom_fields___name___id_customization_field" name="custom_fields[__name__][id_customization_field]" class="form-control" />

<div class="col-md-4 col-xs-10">
<fieldset class="form-group">
<label class="form-control-label">{l s='Label' mod='ets_productmanager' js=1}</label>
<div class="translations tabbable" id="form_step6_custom_fields___name___label">
<div class="translationsFields tab-content">
{foreach from =$languages item='lang'}
<div data-locale="{$lang.iso_code|escape:'html':'UTF-8'}" class="row translationsFields-form_step6_custom_fields___name___label_{$lang.id_lang|intval} tab-pane translation-field translatable-field lang-{$lang.id_lang|intval} {if $lang.id_lang==$id_lang_default} show active{/if}  translation-label-{$lang.iso_code|escape:'html':'UTF-8'}" {if $lang.id_lang!=$id_lang_default} style="display:none"{/if}>
    <div class="col-xs-12 col-sm-12">
        <input type="text" id="form_step6_custom_fields___name___label_{$lang.id_lang|intval}" name="custom_fields[__name__][label][{$lang.id_lang|intval}]"  class="form-control massedit-field" />
    </div>
</div> 
{/foreach} 
</div>
</div>
</fieldset>
</div>
<div class="col-md-3 col-xs-10">
<fieldset class="form-group">
<label class="form-control-label">{l s='Type' mod='ets_productmanager' js=1}</label>

<select id="form_step6_custom_fields___name___type" name="custom_fields[__name__][type]" class="c-select custom-select"><option value="1">{l s='Text' mod='ets_productmanager' js=1}</option><option value="0">{l s='File' mod='ets_productmanager' js=1}</option></select>
</fieldset>
</div>
<div class="col-md-1 col-xs-2">
<fieldset class="form-group">
<label class="form-control-label">&nbsp;</label>
<a class="btn btn-block delete" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
</fieldset>
</div>
<div class="col-md-4 col-xs-10">
<fieldset class="form-group">
<div>
<label class="form-control-label xs-hide">&nbsp;</label>
</div>
<div class="required-custom-field">
<div class="checkbox">                          
<label><input type="checkbox"
data-toggle="switch" class="tiny" id="form_step6_custom_fields___name___require" name="custom_fields[__name__][require]" value="1" />
Required</label>
</div>
</div>
</fieldset>
</div>
</div>'>
        {if isset($valueFieldPost.customizationFields) && $valueFieldPost.customizationFields}
            {foreach from =$valueFieldPost.customizationFields key='index' item ='customizationField'}
                <li>
                    <div class="row">
                        <input id="form_step6_custom_fields_{$index|intval}_id_customization_field" class="form-control" name="custom_fields[{$index|intval}][id_customization_field]" type="hidden" value="{$customizationField->id|intval}" />
                        <div class="col-md-4 col-xs-10">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='Label' mod='ets_productmanager'}</label>
                                <div id="form_step6_custom_fields_{$index|intval}_label" class="translations tabbable">
                                    <div class="translationsFields tab-content">
                                        {foreach from=$languages item='lang'}
                                            <div data-locale="{$lang.iso_code|escape:'html':'UTF-8'}" class="row translationsFields-form_step6_custom_fields_{$index|intval}_label_{$lang.id_lang|intval} tab-pane translation-field translatable-field lang-{$lang.id_lang|intval} {if $lang.id_lang==$id_lang_default} active{/if}  translation-label-{$lang.iso_code|escape:'html':'UTF-8'}" {if $lang.id_lang!=$id_lang_default} style="display:none"{/if}>
                                                <div class="col-xs-12 col-sm-12">
                                                    <input type="text" id="form_step6_custom_fields_{$index|intval}_label_{$lang.id_lang|intval}" name="custom_fields[{$index|intval}][label][{$lang.id_lang|intval}]"  class="form-control massedit-field" value="{$customizationField->name[$lang.id_lang]|escape:'html':'UTF-8'}"/>
                                                </div>
                                            </div> 
                                        {/foreach}
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-3 col-xs-10">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='Type' mod='ets_productmanager'}</label>
                                <select id="form_step6_custom_fields_{$index|intval}_type" class="c-select custom-select" name="custom_fields[{$index|intval}][type]">
                                    <option value="1"{if $customizationField->type==1} selected="selected"{/if}>{l s='Text' mod='ets_productmanager'}</option>
                                    <option value="0"{if $customizationField->type==0} selected="selected"{/if}>{l s='File' mod='ets_productmanager'}</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-1 col-xs-2">
                            <fieldset class="form-group">
                                <label class="form-control-label">&nbsp;</label>
                                <a class="btn btn-block delete">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </fieldset>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                              <div>
                                    <label class="form-control-label xs-hide">&nbsp;</label>
                              </div>
                              <div class="required-custom-field">
                                <div class="checkbox">                          
                                    <label><input data-toggle="switch" class="tiny" id="form_step6_custom_fields_{$index|intval}_require" name="custom_fields[{$index|intval}][required]" value="1" type="checkbox"{if $customizationField->required} checked="checked"{/if} /> {l s='Required' mod='ets_productmanager'}</label>
                                </div>
                              </div>
                            </fieldset>
                        </div>
                    </div>
                </li>
            {/foreach}
        {/if}
    </ul>
    <a class="btn btn-outline-secondary add ets_addfile_customization" href="#">
        <i class="fa fa-plus-circle"></i>
        {l s='Add a customization field' mod='ets_productmanager'}
    </a>
</div>