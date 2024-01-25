{**
* Registration Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FME Modules
*  @copyright 2022 fmemodules All right reserved
*  @license   FMM Modules
*  @package   Registration Fields
 *}
<!-- registration fields heading -->
 <table id="summary-tab">
	<tr>
		<td class="center grey bold">
			{if isset($customfields_heading) AND $customfields_heading}
				{$customfields_heading|escape:'htmlall':'UTF-8'}
			{else}
				{l s='Custom Fields' mod='customfields' pdf='true'}
			{/if}
		</td>
	</tr>
</table>
<!-- registration fields -->
<table id="summary-tab">
	{foreach from=$checkout_steps key=key item=title}
		{if isset($grouped_fields[$key]) AND $grouped_fields[$key]}
		 	<tr>
		 		<td colspan="2" class="header" valign="middle" style="border-top:1px solid #000;border-bottom:1px solid #000;text-align:center">{$title|escape:'htmlall':'UTF-8'}
		 		</td>
			</tr>
			{foreach from=$grouped_fields.$key item=field name=custom_fields}
				{if in_array($field.field_type, array('multiselect', 'radio', 'checkbox', 'select'))}
                    {assign var='field_value' value=$field.field_value}
                {elseif $field.field_type == 'message'}
                    {assign var='field_value' value=$field.default_value}
                {else}
                    {assign var='field_value' value=$obj_model->getFormatedValue($field)}
                {/if}
				{cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
				{if $field.field_type == 'attachment'}
					{continue}
				{else}
					<tr class="product {$bgcolor_class|escape:'htmlall':'UTF-8'}">
						<td class="left grey bold" width="45%" style="margin-left:15px;">
							{$field.field_name|escape:'htmlall':'UTF-8'}
						</td>
						
						<td class="left white" width="55%" style="margin-left:15px;">
							{$field_value|escape:'htmlall':'UTF-8'}
						</td>
					</tr>
				{/if}
			{/foreach}
		{/if}
	{/foreach}
</table>