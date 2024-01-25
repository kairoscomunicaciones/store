{*
* Registration Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FME Modules
*  @copyright 2022 FME Modules All right reserved
*  @license   FMM Modules
*  @package   Registration Fields
*}
{if $version < 1.6}<div class="separation"></div>{/if}
<div class="{if Tools::version_compare($version, '1.7.8.0', '>=')}card {/if}panel col-lg-12">
    <h2 class="{if Tools::version_compare($version, '1.7.8.0', '>=')}card-header {/if}panel-heading">
        <img width="16" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customfields/views/img/AdminCustom.png"/>&nbsp;
    {if isset($customfields_heading) AND $customfields_heading}{$customfields_heading|escape:'htmlall':'UTF-8'}{else}{l s='Custom Fields' mod='customfields' pdf='true'}{/if}
    </h2>
        <div {if $version >= 1.6}class="row{if Tools::version_compare($version, '1.7.8.0', '>=')} card-body{/if}"{/if}>
            {foreach from=$checkout_steps key=key item=title}
                {if !empty($grouped_fields.$key)|escape:'htmlall':'UTF-8'}
                    <div class="well{if Tools::version_compare($version, '1.7.8.0', '>=')} col-lg-12{/if}">
                    <h4 class="panel-heading">{$title|escape:'htmlall':'UTF-8'}</h4>
                        {foreach from=$grouped_fields.$key item=field name=custom_fields}
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">
                                {$field.field_name|escape:'htmlall':'UTF-8'} :
                            </label>
                            <div class="col-lg-9">
                                <strong><p class="form-control-static">

                                    {if in_array($field.field_type, array('multiselect', 'radio', 'checkbox', 'select'))}
                                        {assign var='field_value' value=$field.field_value}
                                    {elseif $field.field_type == 'message'}
                                        {assign var='field_value' value=$field.default_value}
                                    {else}
                                        {assign var='field_value' value=$obj_model->getFormatedValue($field)}
                                    {/if}

                                    {if $field.field_type == 'attachment' AND $field_value AND file_exists($field_value)}
                                        <a class="btn btn-default button" href="{$downloadLink|escape:'htmlall':'UTF-8'}&downloadFile&l={base64_encode($field_value)|escape:'htmlall':'UTF-8'}" target="_blank">
                                            {l s='Download Attachment' mod='customfields'} <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customfields/views/img/download.png" alt="{l s='Download Attachment' mod='customfields'}" title="{l s='Download Attachment' mod='customfields'}"/>
                                        </a>
                                    {else}
                                        {$field_value|escape:'htmlall':'UTF-8'}
                                    {/if}
                                </p></strong>
                            </div>
                        </div>
                        {/foreach}
                        <div class="clearfix"></div>
                    </div>
                {/if}
            {/foreach}
        </div>
        <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<!-- <div class="separation"></div> -->
