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
<script type="text/javascript">
var selected_shops = "{$selected_shops|escape:'htmlall':'UTF-8'}";
var mod_url = "{$action_url|escape:'htmlall':'UTF-8'}";
$(document).ready(function()
{
    $('.language_flags').css('float','left').hide();
    $(".pointer").addClass("btn btn-default dropdown-toggle");

    // shop association
    $(".tree-item-name input[type=checkbox]").each(function()
    {
        $(this).prop("checked", false);
        $(this).removeClass("tree-selected");
        $(this).parent().removeClass("tree-selected");
        if ($.inArray($(this).val(), selected_shops) != -1)
            {
                $(this).prop("checked", true);
                $(this).parent().addClass("tree-selected");
                $(this).parents("ul.tree").each(
                    function()
                    {
                        $(this).children().children().children(".icon-folder-close")
                            .removeClass("icon-folder-close")
                            .addClass("icon-folder-open");
                        $(this).show();
                    }
                );
            }

    });
});

function rfCheckDepend(el) {
        _value = parseInt($(el).val());
        if (_value > 0) {
            $('#rf_dependant_field').show();
        }
        else {
            $('#rf_dependant_field').hide();
        }
    }
    function rfGetRelativeVals(_el) {
        _val_f = parseInt($(_el).val());
        var _list = '';
        if (_val_f > 0) {
            $('#dependant_value').removeAttr('disabled');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: mod_url+'&ajax=1&id_dep='+_val_f,
                success: function(data)
                {
                    var _count = parseInt(data.exist);
                    if (_count > 0) {
                        var _raw = data.vals;
                        $('#dependant_value').removeAttr('disabled');
                        $.each(_raw, function(index,value){
                            _list += '<option value="'+_raw[index]['field_value_id']+'">'+_raw[index]['field_value']+'</option>';
                        });
                        $('#dependant_value').html(_list);
                        console.log('count '+_count);
                    }
                    else {
                        $('#dependant_value').attr('disabled', true);
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        }
        else {
            $('#dependant_value').attr('disabled', true);
        }
    }
function checkAllChilds(__el) {
    if ($(__el).is(':checked')) {
        $(__el).parent().parent().parent().parent().find('input[type="checkbox"]').attr('checked', true);
    }
    else {
        $(__el).parent().parent().parent().parent().find('input[type="checkbox"]').attr('checked', false);
    }
}
</script>
<fieldset id="top">
    {if $version < 1.6}<legend>{else}<h3 class="panel-heading">{/if}
        {l s='Custom Field' mod='customfields'}
    {if $version < 1.6}</legend>{else}</h3>{/if}

        {assign var=var_field_type value=$currentTab->getFieldValue($currentObject, 'field_type')}
        <label class="col-lg-3 control-label required">{l s='Field Name ' mod='customfields'}</label>
        <div class="margin-form form-group">
          <div class="col-lg-8">
            <div class="translatable">
            {foreach from=$languages item=language}
                <div class="lang_{$language.id_lang|intval} col-lg-9" style="display:{if $language.id_lang == $id_lang_default}block{else}none{/if};float:left;margin-bottom:10px;">
                    <input type="text" id="field_name_{$language.id_lang|intval}" name="field_name_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'field_name', $language.id_lang|intval)|escape:'htmlall':'UTF-8'}" />
                </div>
            {/foreach}
            </div>
            <p class="preference_description"></p>
          </div>
        </div>
        <div class="clearfix"></div>

        <label class="col-lg-3 control-label required">{l s='Default Value' mod='customfields'}</label>
        <div class="margin-form form-group">
          <div class="col-lg-8">
            <div class="translatable">
            {foreach from=$languages item=language}
                <div class="lang_{$language.id_lang|intval} col-lg-9" style="display:{if $language.id_lang == $id_lang_default}block{else}none{/if};float:left;margin-bottom:10px;">
                    <input type="text" id="default_value_{$language.id_lang|intval}" name="default_value_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'default_value', $language.id_lang|intval)|escape:'htmlall':'UTF-8'}" />
                </div>
            {/foreach}
            </div>
            <div class="clearfix"></div>
            <div id="date-hint" class="hint help-block" style="{if isset($var_field_type) AND $var_field_type == 'date'}display: block;{else}display: none;{/if}">
                {l s='Date formate' mod='customfields'}: <strong>Y-m-d</strong>
                <br>
                {l s='example' mod='customfields'}: <strong>{date('Y-m-d')}</strong>
            </div>
          </div>
        </div>

        <div class="clearfix"></div>
        
        <label class="col-lg-3 control-label">{l s='Block Heading ' mod='customfields'}</label>
        <div class="margin-form form-group">
            <div class="col-lg-8">
                {*headings_collection*}
                <select id="id_heading" name="id_heading">
                    {assign var=var_field_heading value=$currentTab->getFieldValue($currentObject, 'id_heading')}
                    {foreach from=$headings_collection item=heading}
                        <option value="{$heading.id_custom_field_headings|escape:'htmlall':'UTF-8'}" {if $var_field_heading == $heading.id_custom_field_headings}selected="selected"{/if}>{$heading.title|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <p class="help-block">{l s='Select a heading to make it as a block of fields.' mod='customfields'}</p>
            </div>
        </div>

        <label class="col-lg-3 control-label">{l s='Field Type' mod='customfields'}</label>
        <div class="margin-form form-group">
          <div class="col-lg-4">
            <select id="field_type" name="field_type">
                {foreach from=$customFieldTypes key=fieldk item=fieldv}
                <option value="{$fieldk|escape:'htmlall':'UTF-8'}" {if $var_field_type eq $fieldk}selected="selected"{/if}>{l s=$fieldv mod='customfields'}</option>
                {/foreach}
            </select>
            <p class="preference_description"></p>
          </div>
        </div>
        <div class="clearfix"></div>

        <div class="form-wrapper" id="setting-attachment" style="{if isset($var_field_type) AND $var_field_type AND $var_field_type == 'image' OR $var_field_type == 'attachment'}display:block;{else}display:none;{/if}">
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip">{l s='Maximum size' mod='customfields'}</span>
                </label>
                <div class="col-lg-2">
                    <div class="input-group">
                        <input type="text" value="{$currentTab->getFieldValue($currentObject, 'attachment_size')|escape:'htmlall':'UTF-8'}" name="attachment_size" size="5" class="form-control">
                        <span class="input-group-addon">MB</span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip">{l s='File Types' mod='customfields'}</span>
                </label>
                <div class="col-lg-6">
                    <input type="text" value="{$currentTab->getFieldValue($currentObject, 'extensions')|escape:'htmlall':'UTF-8'}" name="extensions" class="form-control">
                    <p class="help-block hint-block">{l s='Enter comma(,) separated values.' mod='customfields'}</p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
            
        
        <div class="clearfix"></div>

        <div id="field_validation_holder">
            <label class="col-lg-3 control-label">{l s='Field Validation' mod='customfields'}</label>
            <div class="margin-form form-group">
              <div class="col-lg-4">
                {assign var=var_field_validation value=$currentTab->getFieldValue($currentObject, 'field_validation')}
                <select id="field_validation" name="field_validation">
                    <option value="" {if $var_field_validation eq ''}selected="selected"{/if}>{l s='None' mod='customfields'}</option>
                    <option value="isFloat" {if $var_field_validation eq 'isFloat'}selected="selected"{/if}>{l s='Decimal Number' mod='customfields'}</option>
                    <option value="isInt" {if $var_field_validation eq 'isInt'}selected="selected"{/if}>{l s='Integer Number' mod='customfields'}</option>
                    <option value="isEmail" {if $var_field_validation eq 'isEmail'}selected="selected"{/if}>{l s='Email Address' mod='customfields'}</option>
                    <option value="isUrl" {if $var_field_validation eq 'isUrl'}selected="selected"{/if}>{l s='Website Url Address' mod='customfields'}</option>
                    <option value="isGenericName" {if $var_field_validation eq 'isGenericName'}selected="selected"{/if}>{l s='Letters Only' mod='customfields'}</option>
                    <option value="isString" {if $var_field_validation eq 'isString'}selected="selected"{/if}>{l s='Letters and/or Numbers' mod='customfields'}</option>
                    <option value="isDate" {if $var_field_validation eq 'isDate'}selected="selected"{/if}>{l s='Date' mod='customfields'}</option>
                </select>
                <p class="preference_description"></p>
              </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <label class="col-lg-3 control-label">{l s='Checkout Step' mod='customfields'}</label>
        <div class="margin-form form-group">
          <div class="col-lg-4">
            {assign var=var_field_placement value=$currentTab->getFieldValue($currentObject, 'field_placement')}
            <select id="field_placement" name="field_placement">
                <option value="summary" {if $var_field_placement eq 'summary'}selected="selected"{/if}>{l s='Summary Information' mod='customfields'}</option>
                <option value="delivery" {if $var_field_placement eq 'delivery'}selected="selected"{/if}>{l s='Shipping Address' mod='customfields'}</option>
                <option value="billing" {if $var_field_placement eq 'billing'}selected="selected"{/if}>{l s='Billing Address' mod='customfields'}</option>
                <option value="shipping" {if $var_field_placement eq 'shipping'}selected="selected"{/if}>{l s='Shipping Information' mod='customfields'}</option>
                <option value="payment" {if $var_field_placement eq 'payment'}selected="selected"{/if}>{l s='Payment Information' mod='customfields'}</option>
            </select>
            <p class="preference_description"></p>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="margin-form form-group">
            <label class="col-lg-3 control-label">{l s='Category Rule' mod='customfields'}</label>
            <div class="col-lg-9">
                <div class="col-lg-9 rcg_max_height">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> </th>
                            <th>
                                <span class="title_box">
                                    {l s='ID' mod='customfields'}
                                </span>
                            </th>
                            <th>
                                <span class="title_box">
                                    {l s='Name' mod='customfields'}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {if !isset($categories) || empty($categories)}
                        <tr>
                            <td>{l s='No categories found.' mod='customfields'}</td>
                        </tr>
                        {else}
                        {foreach from=$categories item=category}
                            <tr>
                            <td>
                                <input type="checkbox" name="category[]" value="{$category.id_category}"{if isset($category.checked) && $category.checked > 0} checked="checked"{/if} />
                            </td>
                            <td>
                                {$category.id_category}
                            </td>
                            <td>
                                {$category.name}
                            </td>
                            </tr>
                        {/foreach}
                        {/if}
                    </tbody>
                </table>
                <p class="help-block">{l s='Please select categories if any, field will only show if any product of category finds in cart.' mod='customfields'}</p>
            </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- Product rule -->
        <label class="col-lg-3 control-label">{l s='Product Rule' mod='customfields'}</label>
        <div class="margin-form form-group">
          <div class="col-lg-8">
            <div class="input-group col-lg-7 text ac_input">
                <input type="text" class="col-lg-6 text ac_input" id="CUSTOMFIELDS_PRODUCT" name="CUSTOMFIELDS_PRODUCT" autocomplete="off">
                <span class="input-group-addon"><i class="icon-search"></i> {l s='Search' mod='customfields'}</span>
            </div>
            <div class="clearfix"></div>
            <p class="help-block preference_description">{l s='Field will be shown if cart has following product(s).' mod='customfields'}</p>
            <div class="input-group col-lg-9">
                {include file='./products.tpl'}
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="clearfix"></div>

        <!-- Multishop -->
        {if isset($shops) AND $shops}
            <label class="col-lg-3 control-label">{l s='Shop Association' mod='customfields'}</label>
            <div class="margin-form form-group">
              <div class="col-lg-6">{$shops}{* html content *}
                </div>
            </div>
            <div class="clearfix"></div>
        {/if}
        <div class="margin-form form-group">
            <label class="col-lg-3 control-label required">{l s='Select Group' mod='customfields'}</label>
            <div class="col-lg-9">
                <div class="col-lg-8">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="checkAllChilds(this);" /></th>
                            <th>
                                <span class="title_box">
                                    {l s='ID' mod='customfields'}
                                </span>
                            </th>
                            <th>
                                <span class="title_box">
                                    {l s='Name' mod='customfields'}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$customer_groups item=group}
                            <tr>
                            <td>
                                <input type="checkbox" name="groups[]" value="{$group.id_group}"{if isset($group.checked) && $group.checked > 0} checked="checked"{/if} />
                            </td>
                            <td>
                                {$group.id_group}
                            </td>
                            <td>
                                {$group.name}
                            </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
                <p class="help-block">{l s='Please select customer Group access for this field.' mod='customfields'}</p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="field-editable" style="{if isset($var_field_type) AND $var_field_type AND in_array($var_field_type, array('text', 'textarea', 'date', 'attachment'))}display:block;{else}display:none;{/if}">
                <label class="col-lg-3 control-label">{l s='Editable' mod='customfields'}</label>
                <div class="margin-form form-group col-lg-9">
                    {if $version < 1.6}
                      <div class="col-lg-4">
                            <input type="radio" name="editable" id="editable_on" value="1" {if $currentTab->getFieldValue($currentObject, 'editable')|escape:'htmlall':'UTF-8'}checked="checked"{/if} />
                            <label class="t" for="editable_on"> <img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='customfields'}" title="{l s='Yes' mod='customfields'}" style="cursor:pointer" /></label>
                            <input type="radio" name="editable" id="editable_off" value="0" {if !$currentTab->getFieldValue($currentObject, 'editable')|escape:'htmlall':'UTF-8'}checked="checked"{/if} />
                            <label class="t" for="editable_off"> <img src="../img/admin/disabled.gif" alt="{l s='No' mod='customfields'}" title="{l s='No' mod='customfields'}" style="cursor:pointer" /></label>
                      </div>
                     {else}
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input id="editable_on" type="radio" {if $currentTab->getFieldValue($currentObject, 'editable') == 1}checked="checked"{/if} value="1" name="editable" class="form-control">
                                <label class="t" for="editable_on">{l s='Yes' mod='customfields'}</label>
                                <input id="editable_off" type="radio" {if $currentTab->getFieldValue($currentObject, 'editable') == 0}checked="checked"{/if} value="0" name="editable" class="form-control">
                                <label class="t" for="editable_off">{l s='No' mod='customfields'}</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                    {/if}
                </div>
                <div class="clearfix"></div>
            </div>

        <label class="col-lg-3 control-label">{l s='Values Required' mod='customfields'}</label>
        <div class="margin-form form-group col-lg-9">
            {if $version < 1.6}
              <div class="col-lg-4">
                &nbsp;&nbsp;
                <input type="radio" name="value_required" id="required_on" value="1" {if $currentTab->getFieldValue($currentObject, 'value_required')|intval}checked="checked"{/if} />
                <label class="t" for="required_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled' mod='customfields'}" title="{l s='Enabled' mod='customfields'}" style="cursor:pointer" /></label>
                &nbsp;&nbsp;
                <input type="radio" name="value_required" id="required_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'value_required')|intval}checked="checked"{/if} />
                <label class="t" for="required_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled' mod='customfields'}" title="{l s='Disabled' mod='customfields'}" style="cursor:pointer" /></label>
              </div>
            {else}
            <div class="col-lg-4">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input id="value_required_on" type="radio" {if $currentTab->getFieldValue($currentObject, 'value_required') == 1}checked="checked"{/if} value="1" name="value_required" class="form-control">
                    <label class="t" for="value_required_on">{l s='Yes' mod='customfields'}</label>
                    <input id="value_required_off" type="radio" {if $currentTab->getFieldValue($currentObject, 'value_required') == 0}checked="checked"{/if} value="0" name="value_required" class="form-control">
                    <label class="t" for="value_required_off">{l s='No' mod='customfields'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        {/if}
         </div>
        <div class="clearfix"></div>

        <label class="col-lg-3 control-label">{l s='Show in Customer Account' mod='customfields'}</label>
        <div class="margin-form form-group col-lg-9">
            {if $version < 1.6}
              <div class="col-lg-4">
                &nbsp;&nbsp;
                <input type="radio" name="show_customer" id="show_customer_on" value="1" {if $currentTab->getFieldValue($currentObject, 'show_customer')|intval}checked="checked"{/if} />
                <label class="t" for="show_customer_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled' mod='customfields'}" title="{l s='Enabled' mod='customfields'}" style="cursor:pointer" /></label>
                &nbsp;&nbsp;
                <input type="radio" name="show_customer" id="show_customer_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'show_customer')|intval}checked="checked"{/if} />
                <label class="t" for="show_customer_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled' mod='customfields'}" title="{l s='Disabled' mod='customfields'}" style="cursor:pointer" /></label>
              </div>
            {else}
            <div class="col-lg-4">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input id="show_customer_on" type="radio" {if $currentTab->getFieldValue($currentObject, 'show_customer') == 1}checked="checked"{/if} value="1" name="show_customer" class="form-control">
                    <label class="t" for="show_customer_on">{l s='Yes' mod='customfields'}</label>
                    <input id="show_customer_off" type="radio" {if $currentTab->getFieldValue($currentObject, 'show_customer') == 0}checked="checked"{/if} value="0" name="show_customer" class="form-control">
                    <label class="t" for="show_customer_off">{l s='No' mod='customfields'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
            {/if}
        </div>
        <div class="clearfix"></div>

        <label class="col-lg-3 control-label">{l s='Show in Admin Order' mod='customfields'}</label>
        <div class="margin-form form-group col-lg-9">
            {if $version < 1.6}
              <div class="col-lg-4">
                &nbsp;&nbsp;
                <input type="radio" name="show_admin" id="show_admin_on" value="1" {if $currentTab->getFieldValue($currentObject, 'show_admin')|intval}checked="checked"{/if} />
                <label class="t" for="show_admin_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled' mod='customfields'}" title="{l s='Enabled' mod='customfields'}" style="cursor:pointer" /></label>
                &nbsp;&nbsp;
                <input type="radio" name="show_admin" id="show_admin_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'show_admin')|intval}checked="checked"{/if} />
                <label class="t" for="show_admin_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled' mod='customfields'}" title="{l s='Disabled' mod='customfields'}" style="cursor:pointer" /></label>
              </div>
            {else}
            <div class="col-lg-4">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input id="show_admin_on" type="radio" {if $currentTab->getFieldValue($currentObject, 'show_admin') == 1}checked="checked"{/if} value="1" name="show_admin" class="form-control">
                    <label class="t" for="show_admin_on">{l s='Yes' mod='customfields'}</label>
                    <input id="show_admin_off" type="radio" {if $currentTab->getFieldValue($currentObject, 'show_admin') == 0}checked="checked"{/if} value="0" name="show_admin" class="form-control">
                    <label class="t" for="show_admin_off">{l s='No' mod='customfields'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
            {/if}
        </div>
        <div class="clearfix"></div>

        <label class="col-lg-3 control-label">{l s='Field Status' mod='customfields'}</label>
        <div class="margin-form form-group">
            {if $version < 1.6}
                <div class="col-lg-4">
                    &nbsp;&nbsp;
                    <input type="radio" name="active" id="active_on" value="1" {if $currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
                    <label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled' mod='customfields'}" title="{l s='Enabled' mod='customfields'}" style="cursor:pointer" /></label>
                    &nbsp;&nbsp;
                    <input type="radio" name="active" id="active_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
                    <label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled' mod='customfields'}" title="{l s='Disabled' mod='customfields'}" style="cursor:pointer" /></label>
                </div>
            {else}
            <div class="col-lg-4">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input id="active_on" type="radio" {if $currentTab->getFieldValue($currentObject, 'active') == 1}checked="checked"{/if} value="1" name="active" class="form-control">
                    <label class="t" for="active_on">{l s='Yes' mod='customfields'}</label>
                    <input id="active_off" type="radio" {if $currentTab->getFieldValue($currentObject, 'active') == 0}checked="checked"{/if} value="0" name="active" class="form-control">
                    <label class="t" for="active_off">{l s='No' mod='customfields'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
            {/if}
        </div>
        <div class="clearfix"></div>
        <label class="col-lg-3 control-label">{l s='Dependant Field' mod='customfields'}</label>
        <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input id="dependant_on" onclick="rfCheckDepend(this)" type="radio" {if $currentTab->getFieldValue($currentObject, 'dependant') == 1}checked="checked"{/if} value="1" name="dependant" class="form-control">
                <label class="t" for="dependant_on">{l s='Yes' mod='customfields'}</label>
                <input id="dependant_off" onclick="rfCheckDepend(this)" type="radio" {if $currentTab->getFieldValue($currentObject, 'dependant') == 0}checked="checked"{/if} value="0" name="dependant" class="form-control">
                <label class="t" for="dependant_off">{l s='No' mod='customfields'}</label>
                <a class="slide-button btn"></a>
            </span>
        </div>
        <div class="clearfix"></div>
        <div id="rf_dependant_field" style="{if $currentTab->getFieldValue($currentObject, 'dependant') > 0}display: block;{else}display: none;{/if} padding-top: 15px;">
            <label class="col-lg-3 control-label">{l s='Select Dependant Field' mod='customfields'}</label>
            <div class="col-lg-9">
                <select name="dependant_field" class="col-lg-8" onchange="rfGetRelativeVals(this);">
                    <option value="0">-</option>
                    {if !empty($fields_collection)}
                        {foreach from=$fields_collection item=field}
                            {if $field.id_custom_field != $id_custom_field && in_array($field.field_type, ['select', 'checkbox', 'radio', 'boolean'])}<option value="{$field.id_custom_field|escape:'htmlall':'UTF-8'}"{if isset($field.dep_check) && $field.dep_check == $field.id_custom_field} selected="selected"{/if}>{$field.id_custom_field|escape:'htmlall':'UTF-8'} - {$field.field_name|escape:'htmlall':'UTF-8'}</option>{/if}
                        {/foreach}
                    {/if}
                </select>
            </div>
            <div class="clearfix" style="clear: both;padding-top: 15px;"></div>
            <label class="col-lg-3 control-label">{l s='Select Dependant Value' mod='customfields'}</label>
            <div class="col-lg-9">
                <select name="dependant_value" class="col-lg-8" id="dependant_value">
                    {if empty($field_values)}
                        <option value="0">-</option>
                    {else}
                        {foreach from=$field_values item=field}
                            <option value="{$field.field_value_id|escape:'htmlall':'UTF-8'}"{if $field.check && $field.check == $field.field_value_id} selected="selected"{/if}>{$field.field_value|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    {/if}
                </select>
                <small class="form-text help-block" style="display: block; clear: both; padding-top: 5px;">{l s='Choose dependant field first so this option is populated.' mod='customfields'}</small>
            </div>
        </div>
        <div class="clearfix"></div>
</fieldset>
