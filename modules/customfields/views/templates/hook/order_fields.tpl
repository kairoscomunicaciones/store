{*
* Customfields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
*  @author    FMM Modules
*  @copyright 2022 FMM Modules All right reserved
*  @license   FMM Modules
*  @package   Customfields
*}
{if isset($checkout_fields) AND $checkout_fields}
    <div class="clearfix box card card-block" id="checkoutblock-wrapper">
        <h3>{if isset($customfields_heading) AND $customfields_heading}{$customfields_heading|escape:'htmlall':'UTF-8'}{else}{l s='Custom Fields' mod='customfields' pdf='true'}{/if}</h3><hr>
        <ul>
        {foreach from=$checkout_fields key=key item=summary_fields}
            {if isset($summary_fields) AND $summary_fields}
                <li>
                    <div class="clearfix accordion {if $version < 1.7}psOld{/if}" id="customfieldsblock_{$key|escape:'htmlall':'UTF-8'}">
                        <h3 class="box accordion-header">{$checkout_labels[$key]|escape:'htmlall':'UTF-8'}</h3>
                		<form class="std checkout_form well accordion-content" id="{$key|escape:'htmlall':'UTF-8'}_form" method="post" action="{$action_controller|escape:'htmlall':'UTF-8'}" data-step="{$key|escape:'htmlall':'UTF-8'}">
                			{foreach from=$summary_fields item=field}
                				<div class="clearfix"></div>
                				<div class="input_wrapper required form-group text form-group row{if $field['dependant'] > 0} rf_no_display rf_no_display_{$field['dependant_field']|escape:'htmlall':'UTF-8'}_{$field['dependant_value']|escape:'htmlall':'UTF-8'}{/if} rf_only_f_{$field['dependant_field']|escape:'htmlall':'UTF-8'}"
                                data-id="{$field.id_custom_field|escape:'htmlall':'UTF-8'}"
                                data-f="{$field['dependant_field']|escape:'htmlall':'UTF-8'}"
                                data-v="{$field['dependant_value']|escape:'htmlall':'UTF-8'}"
                                data-dependant-field="{$field['dependant']|escape:'htmlall':'UTF-8'}">
                		            <label class="input_label {if $field['value_required']} required {/if}{if $version >= 1.7}col-md-3{/if} form-control-label">
                                        {if $field.value_required AND $version >= 1.7}<span style="color: #FF5555!important">*</span>{/if}
                		            	{$field.field_name|escape:'htmlall':'UTF-8'}
                                    </label>
                					<div class="{if $version >= 1.7}col-md-6{/if}">
                                        {if $field.field_type neq 'message'}
                                            {assign var='field_value' value=$model->getFormatedValue($field)}
                                        {/if}
                						{if $field.field_type eq 'text'}
            								{assign var="text_default_value" value=$field['default_value']}
            								{if $field.editable == 0}
            									{if isset($field_value) AND $field_value}
            										<span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
            									{else}
            										<input type="text" name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" value=""  class="text {if $field['value_required']}is_required {/if}{if isset($field['field_validation']) AND $field['field_validation'] }validate_field{/if} form-control" {if isset($field['field_validation']) AND $field['field_validation']} data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if}/>
            									{/if}
            								{else}
            									<input type="text"
                                                name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]"
                                                value="{if !empty($field_value) AND $field_value}{$field_value|escape:'htmlall':'UTF-8'}{elseif !empty($text_default_value) AND $text_default_value}{$text_default_value|escape:'htmlall':'UTF-8'}{/if}" 
                                                class="text {if $field['value_required']}is_required {/if}{if isset($field['field_validation']) AND $field['field_validation'] }validate_field{/if} form-control"
                                                {if isset($field['field_validation']) AND $field['field_validation']} data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if}/>
            								{/if}

                						{elseif $field.field_type eq 'textarea'}
            								{assign var="texta_default_value" value=$field['default_value']}
            								{if $field.editable == 0}

            									{if isset($field_value) AND $field_value}
            										<span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
            									{else}
            										<textarea name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" class="form-control {if $field['value_required']}is_required{/if}" {if isset($field['field_validation']) AND $field['field_validation']}data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if}></textarea>
            									{/if}
            								{else}
            									<textarea name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" class="form-control {if $field['value_required']}is_required{/if}" {if isset($field['field_validation']) AND $field['field_validation']}data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if}>{if !empty($field_value) AND $field_value}{$field_value|escape:'htmlall':'UTF-8'}{elseif !empty($text_default_value) AND $text_default_value}{$text_default_value|escape:'htmlall':'UTF-8'}{/if}</textarea>
            								{/if}

                						{elseif $field.field_type eq 'date'}
                                            {assign var="date_default_value" value=$field['default_value']}
            								{if $field.editable == 0}

            									{if isset($field_value) AND $field_value}
            										<span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
            									{else}
            										<input class="fields_datapicker form-control {if $field['value_required']} is_required {/if} validate_field" type="text" name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" value="{if !empty($field_value) AND $field_value}{$field_value|escape:'htmlall':'UTF-8'}{elseif isset($date_default_value) AND $date_default_value}{$date_default_value|escape:'htmlall':'UTF-8'}{/if}" data-validate="isDate"/>
                                                    <p class="small">{l s='Date formate' mod='customfields'}: <strong>Y-m-d</strong></p>
            									{/if}
            								{else}
            									<input class="fields_datapicker form-control {if $field['value_required']} is_required {/if} validate_field"
                                                type="text"
                                                name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]"
                                                value="{if !empty($field_value) AND $field_value}{$field_value|escape:'htmlall':'UTF-8'}{elseif !empty($date_default_value) AND $date_default_value}{$date_default_value|escape:'htmlall':'UTF-8'}{/if}"
                                                data-validate="isDate"/>
                                                <p class="small">{l s='Date formate' mod='customfields'}: <strong>Y-m-d</strong></p>

            								{/if}

                						{elseif $field.field_type eq 'boolean'}
                                            {if $field.editable == 0}
                                                {if isset($field_value) AND $field_value}
                                                    <span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
                                                {else}
                                                    <select class="select form-control {if $field['value_required']}is_required {/if}"
                                                    name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]"
                                                    data-field="{$field['id_custom_field']|escape:'htmlall':'UTF-8'}"
                                                    data-type="{$field.field_type|escape:'htmlall':'UTF-8'}">
                                                    <option value="No">{l s='No' mod='customfields'}</option>
                                                    <option value="Yes">{l s='Yes' mod='customfields'}</option>
                                                </select>
                                                {/if}
                                            {else}
                    							<select class="select form-control {if $field['value_required']}is_required {/if}"
                                                name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]"
                                                data-field="{$field['id_custom_field']|escape:'htmlall':'UTF-8'}"
                                                data-type="{$field.field_type|escape:'htmlall':'UTF-8'}">
                    								<option value="No" {if !empty($field_value) AND $field_value == 'No'}selected="selected"{/if}>{l s='No' mod='customfields'}</option>
                    								<option value="Yes" {if !empty($field_value) AND $field_value == 'Yes'}selected="selected"{/if}>{l s='Yes' mod='customfields'}</option>
                    							</select>
                                            {/if}

                						{elseif $field.field_type eq 'select'}
                                            {assign var='summary_fields_values' value=$model->getCustomFieldsValues($field['id_custom_field'])}

                                            {if $field.editable == 0}
                                                {if isset($field_value) AND $field_value}
                                                    {$field_value = FieldsModel::getFieldsValueById($field_value)}
                                                    <span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
                                                {else}
                                                    <select class="select form-control {if $field['value_required']}is_required {/if}" name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" >
                                                    <option value="">{l s='Select Option' mod='customfields'}</option>
                                                    {foreach from=$summary_fields_values item=summary_fields_value}
                                                        <option value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                        </option>
                                                    {/foreach}
                                                </select>
                                                {/if}
                                            {else}
                    							<select class="select form-control {if $field['value_required']}is_required {/if}" name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}]" >
                    								<option value="">{l s='Select Option' mod='customfields'}</option>
                    								{foreach from=$summary_fields_values item=summary_fields_value}
                    									<option value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                        {if isset($field_value) AND $summary_fields_value.field_value_id == $field_value}selected="selected"{/if}>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                    									</option>
                    								{/foreach}
                    							</select>
                                            {/if}

                						{elseif $field.field_type eq 'radio'}
                                            <input class="checkboxes" type="hidden" data-required="{$field['value_required']}" value="{$field_value|escape:'htmlall':'UTF-8'}">
                                            {assign var='summary_fields_values' value=$model->getCustomFieldsValues($field['id_custom_field'])}

                                            {if $field.editable == 0}
                                                {if isset($field_value) AND $field_value}
                                                    {$field_value = FieldsModel::getFieldsValueById($field_value)}
                                                    <span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
                                                {else}
                                                    {foreach from=$summary_fields_values item=summary_fields_value}
                                                        <div class="type_multiboxes" id="uniform-{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                            <input type="radio"
                                                            id="radio_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            class="{if $field['value_required']}is_required {/if}"
                                                            data-type="{$field.field_type|escape:'htmlall':'UTF-8'}"
                                                            data-field="{$field['id_custom_field']|escape:'htmlall':'UTF-8'}"
                                                            name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]"
                                                            value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"/>
                                                            <label class="type_multiboxes top" for="radio_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                                <span><span></span></span>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                            </label>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    {/foreach}
                                                {/if}
                                            {else}
                    							<!-- <div class="clearfix"> -->
                    								{foreach from=$summary_fields_values item=summary_fields_value}
                    								<!-- <div class="radio-inline"> -->
                                                        <div class="type_multiboxes" id="uniform-{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                            <input type="radio"
                                                            id="radio_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            class="{if $field['value_required']}is_required {/if}"
                                                            data-type="{$field.field_type|escape:'htmlall':'UTF-8'}"
                                                            data-field="{$field['id_custom_field']|escape:'htmlall':'UTF-8'}"
                                                            name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]"
                                                            value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            {if isset($field_value) AND $summary_fields_value.field_value_id == $field_value}checked="checked"{/if}
                                                            />
                                                            <label class="type_multiboxes top" for="radio_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                                <span><span></span></span>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                            </label>
                                                        </div>
                                                        <div class="clearfix"></div>
                    								<!-- </div> -->
                    								{/foreach}
                    							<!-- </div> -->
                                            {/if}

                                        {elseif $field.field_type eq 'checkbox'}
                                            {if isset($field_value) AND $field_value}
                                                {assign var='options' value=explode(',', $field_value)}
                                            {else}
                                                {assign var='options' value=[]}
                                            {/if}

                                            <input class="checkboxes"
                                            type="hidden"
                                            data-required="{$field['value_required']}"
                                            data-type="{$field.field_type|escape:'htmlall':'UTF-8'}">

                                            {assign var='summary_fields_values' value=$model->getCustomFieldsValues($field['id_custom_field'])}

                                            {if $field.editable == 0}
                                                {if isset($field_value) AND $field_value}
                                                    {assign var='opts' value=[]}
                                                    {if isset($options) AND is_array($options)}
                                                        {foreach from=$options item=id_value}
                                                           {$opts[] = FieldsModel::getFieldsValueById($id_value)}
                                                        {/foreach}
                                                    {/if}

                                                    {if isset($opts) AND $opts AND is_array($opts)}
                                                        {$field_value = implode(',', $opts)}
                                                    {/if}
                                                    <span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
                                                {else}
                                                    {foreach from=$summary_fields_values item=summary_fields_value}
                                                    <!-- <div class="checkbox"> -->
                                                        <div class="type_multiboxes checker" id="uniform-{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                            <input type="checkbox"
                                                            value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]" id="checkbox_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            class="{if $field['value_required']}is_required{/if}"/>
                                                            <label class="type_multiboxes" for="checkbox_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                                <span></span>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                            </label>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    <!-- </div> -->
                                                {/foreach}
                                                {/if}
                                            {else}
                    							{foreach from=$summary_fields_values item=summary_fields_value}
                    								<!-- <div class="checkbox"> -->
                                                        <div class="type_multiboxes" id="checkbox-{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                            <input type="checkbox"
                                                            data-field="{$field['id_custom_field']|escape:'htmlall':'UTF-8'}"
                                                            data-type="{$field.field_type|escape:'htmlall':'UTF-8'}"
                                                            value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]" id="checkbox_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}"
                                                            class="{if $field['value_required']}is_required{/if} form-control"
                                                            {if isset($options) AND is_array($options) AND in_array($summary_fields_value.field_value_id, $options)}checked="checked"{/if}/>
                                                            <label class="type_multiboxes" for="checkbox_{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">
                                                                <span></span>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                            </label>
                                                        </div>
                                                        <div class="clearfix"></div>
                    								<!-- </div> -->
                    							{/foreach}
                                            {/if}

                						{elseif $field.field_type eq 'multiselect'}
                                            {if isset($field_value) AND $field_value}
                                                {assign var='options' value=explode(',', $field_value)}
                                            {else}
                                                {assign var='options' value=[]}
                                            {/if}
                                            <input class="checkboxes" type="hidden" data-required="{$field['value_required']}" value="{count($options)}">
                                            {assign var='summary_fields_values' value=$model->getCustomFieldsValues($field['id_custom_field'])}

                                            {if $field.editable == 0}
                                                {if isset($field_value) AND $field_value}

                                                    {assign var='opts' value=[]}
                                                    {if isset($options) AND is_array($options)}
                                                        {foreach from=$options item=id_value}
                                                           {$opts[] = FieldsModel::getFieldsValueById($id_value)}
                                                        {/foreach}
                                                    {/if}

                                                    {if isset($opts) AND $opts AND is_array($opts)}
                                                        {$field_value = implode(',', $opts)}
                                                    {/if}
                                                    <span class="form-control">{$field_value|escape:'htmlall':'UTF-8'}</span>
                                                {else}
                                                    <select name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]" multiple="multiple" class="type_multiboxes multiselect form-control {if $field['value_required']}is_required {/if}">
                                                        {foreach from=$summary_fields_values item=summary_fields_value}
                                                            <option value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}">{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                            </option>
                                                        {/foreach}
                                                    </select>
                                                    <p><small>{l s='Hold CTRL/Command key to select multiple values.' mod='customfields'}</small></p>
                                                {/if}
                                            {else}
                    							<select name="fields[{$field['id_custom_field']|escape:'htmlall':'UTF-8'}][]" multiple="multiple" class="type_multiboxes multiselect form-control {if $field['value_required']}is_required {/if}">
                    								{foreach from=$summary_fields_values item=summary_fields_value}
                    									<option value="{$summary_fields_value['field_value_id']|escape:'htmlall':'UTF-8'}" {if isset($options) AND is_array($options) AND in_array($summary_fields_value.field_value_id, $options)}selected="selected"{/if}>{$summary_fields_value['field_value']|escape:'htmlall':'UTF-8'}
                                                        </option>
                    								{/foreach}
                    							</select>
                    							<p><small>{l s='Hold CTRL/Command key to select multiple values.' mod='customfields'}</small></p>
                                            {/if}

                                        {elseif $field.field_type eq 'attachment'}

                							<div id="field_attachment_{$field['id_custom_field']|escape:'htmlall':'UTF-8'}">
                								{assign var='root_dir' value=($smarty.const._PS_ROOT_DIR_|cat:'/')}
                								{if $field.editable == 0}
                									<!-- {*assign var='field_value' value=''*} -->

                									{if isset($field_value) AND $field_value}
                										<p><a class="btn btn-primary button" href="{$action_controller|escape:'htmlall':'UTF-8'}&downloadFile&l={base64_encode({$field_value|escape:'htmlall':'UTF-8'})}">{l s='Download' mod='customfields'} {pathinfo($field_value|replace:$root_dir:''|escape:'htmlall':'UTF-8', $smarty.const.PATHINFO_FILENAME)}</a></p>
                									{else}
                										<input type="file" name="fields[{$field.id_custom_field|escape:'htmlall':'UTF-8'}]" value="" class="form-control attachment {if $field['value_required']}is_required {/if}{if isset($field['field_validation']) AND $field['field_validation'] }validate_field{/if}" {if isset($field['field_validation']) AND $field['field_validation']} data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if} {if isset($field.extensions) AND $field.extensions} data-extensions="{$field.extensions|escape:'htmlall':'UTF-8'}"{/if}>
                										{if isset($field.extensions) AND $field.extensions} <p><small>{l s='Allowed file types' mod='customfields'}: {$field.extensions|escape:'htmlall':'UTF-8'}</small></p>{/if}
                										<p class="alert alert-danger error extension_error">{l s='Image type not allowed.' mod='customfields'}</p>
                									{/if}
                								{else}
                								{if !empty($field_value) AND $field_value}
        											<p><a class="btn btn-primary button" href="{$action_controller|escape:'htmlall':'UTF-8'}&downloadFile&l={base64_encode({$field_value|escape:'htmlall':'UTF-8'})}">{l s='Download' mod='customfields'} {pathinfo($field_value|replace:$root_dir:''|escape:'htmlall':'UTF-8', $smarty.const.PATHINFO_FILENAME)}</a></p>
                								{/if}
                								<input type="file" name="fields[{$field.id_custom_field|escape:'htmlall':'UTF-8'}]" value=""  class="form-control attachment {if $field['value_required']}is_required {/if}{if isset($field['field_validation']) AND $field['field_validation'] }validate_field{/if}" {if isset($field['field_validation']) AND $field['field_validation']} data-validate="{$field['field_validation']|escape:'htmlall':'UTF-8'}"{/if} {if isset($field.extensions) AND $field.extensions} data-extensions="{$field.extensions|escape:'htmlall':'UTF-8'}"{/if}>
                								{if isset($field.extensions) AND $field.extensions} <p><small>{l s='Allowed file types' mod='customfields'}: {$field.extensions|escape:'htmlall':'UTF-8'}</small></p>{/if}
                								<p class="alert alert-danger error extension_error">{l s='Image type not allowed.' mod='customfields'}</p>
                								{/if}
                							</div>

                						{elseif $field.field_type eq 'message'}
                							<b>{$field['default_value']|escape:'htmlall':'UTF-8'}</b>
                						{/if}
                					</div>
                					<div class="clearfix"></div>
                				</div>
                			{/foreach}
                			<input type="hidden" name="type"  value="{$key|escape:'htmlall':'UTF-8'}"/>
                            <input type="hidden" name="id_order"  value="{$id_order|escape:'htmlall':'UTF-8'}"/>
                            <input type="hidden" name="id_cart"  value="{$id_cart|escape:'htmlall':'UTF-8'}"/>
                		</form>
                    </div>
                </li>
            {/if}
        {/foreach}
        </ul>
        <hr>
        {if (isset($is_editable) AND $is_editable > 0) OR (isset($nbrMissingFields) AND $nbrMissingFields > 0) OR (isset($nbrNonEditableValues) AND $nbrNonEditableValues > 0)}
        <form action="javascript:void(0);">
            <footer class="form-footer text-sm-center">
                <div class="form-group row">
                    <div class="col-lg-12">
                        {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}
                    </div>
                </div>
                <button class="btn btn-primary form-control-submit" name="saveCustomFields" type="submit">
                  {l s='Save Fields' mod='customfields'}
                </button>
            </footer>
            <div class="sending_loader" style="display: none;"></div>
        </form>
        {/if}
    </div>
{/if}
