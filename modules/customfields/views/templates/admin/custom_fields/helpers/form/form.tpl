{*
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
*  @author    FME Modules
*  @copyright 2022 fmemodules.com All right reserved
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @category  FMM Modules
*  @package   Customfields
*}

{if $version < 1.6}{include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}{/if}
<div class="leadin">{block name="leadin"}{/block}</div>

<form class="panel form-horizontal" action="{$currentIndex|escape}&token={$currentToken|escape:'htmlall':'UTF-8'}&addcustom_field" name="custom_field_form" id="custom_field_form" method="post" enctype="multipart/form-data">
	{if $currentObject->id}<input type="hidden" name="id_custom_field" value="{$currentObject->id|intval}" />{/if}
	<input type="hidden" id="currentFormTab" name="currentFormTab" value="informations" />
	{include file=$fieldinfo}
	<div class="separation"></div>

	{if $version >= 1.6}
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminFields')|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='customfields'}</a>
		<button type="submit" name="submitAddcustom_field" class="btn btn-default pull-right">
			<i class="process-icon-save"></i> {l s='Save' mod='customfields'}
		</button>
		<button type="submit" name="submitAddcustom_fieldAndStay" class="btn btn-default pull-right">
			<i class="process-icon-save"></i> {l s='Save and stay' mod='customfields'}
		</button>
	</div>
	{else}
	    <div style="text-align:center">
			<input type="submit" value="{l s='Save' mod='customfields'}" class="button" name="submitAddcustom_field" id="{$table|escape:'htmlall':'UTF-8'}_form_submit_btn" />
		</div>
	{/if}
</form>

<script language="javascript">
		var editableFields = ['boolean', 'message'];
		var currentToken = "{$currentToken|escape:'htmlall':'UTF-8'}";
		var currentFormTab = "{if isset($smarty.post.currentFormTab)}{$smarty.post.currentFormTab|escape:'htmlall':'UTF-8'}{else}informations{/if}";

		var languages = new Array();
		{foreach from=$languages item=language key=k}
			languages[{$k|escape:'htmlall':'UTF-8'}] = {
				id_lang: {$language.id_lang|escape:'htmlall':'UTF-8'},
				iso_code: "{$language.iso_code|escape:'htmlall':'UTF-8'}",
				name: "{$language.name|escape:'htmlall':'UTF-8'}"
			};
		{/foreach}
		displayFlags(languages, {$id_lang_default|escape:'htmlall':'UTF-8'});

		function displayCartRuleTab(tab)
		{
			$('.cart_rule_tab').hide();
			$('.tab-page').removeClass('selected');
			$('#advance_blog_' + tab).show();
			$('#advance_blog_link_' + tab).addClass('selected');
			$('#currentFormTab').val(tab);
		}

		$('.cart_rule_tab').hide();
		$('.tab-page').removeClass('selected');
		$('#advance_blog_' + currentFormTab).show();
		$('#advance_blog_link_' + currentFormTab).addClass('selected');

		function checkOptions(){
			var field_type = $('#field_type').val();
			if (jQuery.inArray(field_type, editableFields) === -1) {
				$('#field-editable').show();
			} else {
				$('#field-editable').hide();
			}
			
			if (field_type == 'attachment') {
				$('#setting-attachment').show();
			} else {
				$('#setting-attachment').hide();
			}
			
			if( field_type == "multiselect" || field_type == "select" ||  field_type == "checkbox" || field_type == "radio"){
				$("#show_label").show();
				$("#show_options").show();
			}else{
				$("#show_label").hide();
				$("#show_options").hide();
			}

			if( field_type == "text" || field_type == "textarea" ||  field_type == "message"){
				$("#default_value_holder").show();
			}else{
				$("#default_value_holder").hide();
			}

			if( field_type == "text" || field_type == "textarea"){
				$("#field_validation_holder").show();
			}else{
				$("#field_validation_holder").hide();
			}
		}

		function updateRequriedOptions() {
		$("#field_type").parent().parent().after(`
		<div class='margin-form form-group' id='show_options'>
			<label class='col-lg-3 control-label' id='show_label'>{l s='Field Option(s)' mod='customfields' js=1}:</label>
			<div class='col-lg-9'>
				<div class='col-lg-12 form-group'>
					<a id='new_option' class='btn btn-default button' href='javascript:void(0);'>
						<i class="icon icon-plus-circle"></i> {l s='Add Option' mod='customfields'}
					</a>
				</div>
				<ul class='col-lg-8' style='list-style:none;' id='valuesOptions'></ul><div class="clearfix"></div>
			</div>
		</div>`);

		$("#new_option").click(function(){
			newOption();
		});

		$('.delopt').live('click',function(){
			$(this).closest('.option_line').remove();
		});

		$("#field_type").change(function(){
			checkOptions();
		});

		checkOptions();
	}

	function newOption(id,value){
		var newlist = $(`<li class='col-lg-12 option_line' style='cursor: pointer;margin-bottom:5px;margin-left: -15px;'>
		<div class="col-lg-9">
			<input type='text' id='options' name='options[]' />
		</div>
		<div class="col-lg-2">
			<a href='javascript:void(0);' class='delopt btn btn-danger button'>
				<i class="icon icon-trash"></i> {l s='Remove' mod='customfields' js=1}
			</a>
		</li>`);

		//$(newlist).prependTo("#valuesOptions");
		$('#valuesOptions').append(newlist);

		var input = newlist.find('input');

		if (value){
			input.val(value);
			var name = "options["+id+"]";
   		}
	}
		updateRequriedOptions();
		{$jqueryOptions}//html code, cannot be escaped
</script>
