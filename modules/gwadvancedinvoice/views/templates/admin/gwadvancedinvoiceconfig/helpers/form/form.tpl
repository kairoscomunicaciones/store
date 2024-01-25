{*
* Do not edit the file if you want to upgrade the module in future. The file is added since version 1.0.5
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2016 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}
{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'customergroupselect'}
        <div class="col-lg-9 customergroupselect">
            <select name="{$input.name|escape:'html':'UTF-8'}_TYPE" class="fixed-width-xl customergroupselect_type">
                <option value="customer_group" {if !isset($fields_value["{$input.name}_TYPE"]) || $fields_value["{$input.name}_TYPE"] == 'customer_group'} selected="selected" {/if}>{l s='Customer group' mod='gwadvancedinvoice'}</option>
                <option value="payment_method" {if isset($fields_value["{$input.name}_TYPE"]) && $fields_value["{$input.name}_TYPE"] == 'payment_method'} selected="selected" {/if}>{l s='Payment method' mod='gwadvancedinvoice'}</option>
                <option value="order_status" {if isset($fields_value["{$input.name}_TYPE"]) && $fields_value["{$input.name}_TYPE"] == 'order_status'} selected="selected" {/if}>{l s='Order status' mod='gwadvancedinvoice'}</option>
            </select>
            <br />
            
            <div class="order_status_box customergroupselect_box" {if isset($fields_value["{$input.name}_TYPE"]) && $fields_value["{$input.name}_TYPE"] != 'order_status'} style="display:none;" {/if}>
                {if isset($fields_value['allstatus']) && $fields_value['allstatus']}
                    {foreach $fields_value['allstatus'] as $group}
                        {if ($input.name == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE' && $group.invoice) || ($input.name == 'GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE' && $group.delivery)}
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                {$group.name|escape:'html':'UTF-8'}
                            </label>
                            <div class="col-lg-3">
                                {if $input.name == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE'}
                                    <select name="GWADVANCEDINVOICE_STATUS_TEMPLATE_{$group.id_order_state|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDINVOICE_STATUS_TEMPLATE_{$group.id_order_state}"]) && $fields_value["GWADVANCEDINVOICE_STATUS_TEMPLATE_{$group.id_order_state}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else}
                                    <select name="GWADVANCEDDELIVERY_STATUS_TEMPLATE_{$group.id_order_state|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDDELIVERY_STATUS_TEMPLATE_{$group.id_order_state}"]) && $fields_value["GWADVANCEDDELIVERY_STATUS_TEMPLATE_{$group.id_order_state}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {/if}
                            </div>
                        </div>
                        {/if}
                    {/foreach}
                {/if}
            </div>
            
            <div class="customer_group_box customergroupselect_box" {if isset($fields_value["{$input.name}_TYPE"]) && $fields_value["{$input.name}_TYPE"] != 'customer_group'} style="display:none;" {/if}>
                {if isset($fields_value['groups']) && $fields_value['groups']}
                    {foreach $fields_value['groups'] as $group}
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                {$group.name|escape:'html':'UTF-8'}
                            </label>
                            <div class="col-lg-3">
                                {if $input.name == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE'}
                                    <select name="GWADVANCEDINVOICE_GROUP_{$group.id_group|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDINVOICE_GROUP_{$group.id_group}"]) && $fields_value["GWADVANCEDINVOICE_GROUP_{$group.id_group}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else}
                                    <select name="GWADVANCEDDELIVERY_GROUP_{$group.id_group|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDDELIVERY_GROUP_{$group.id_group}"]) && $fields_value["GWADVANCEDDELIVERY_GROUP_{$group.id_group}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {/if}
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div class="payment_method_box customergroupselect_box" {if !isset($fields_value["{$input.name}_TYPE"]) || $fields_value["{$input.name}_TYPE"] != 'payment_method'} style="display:none;" {/if}>
                {if isset($fields_value['payment_modules']) && $fields_value['payment_modules']}
                    {foreach $fields_value['payment_modules'] as $group}
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                {$group.name|escape:'html':'UTF-8'}
                            </label>
                            <div class="col-lg-3">
                                {if $input.name == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE'}
                                    <select name="GWADVANCEDINVOICE_PAYMENT_TEMPLATE_{$group.id_module|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDINVOICE_PAYMENT_TEMPLATE_{$group.id_module}"]) && $fields_value["GWADVANCEDINVOICE_PAYMENT_TEMPLATE_{$group.id_module}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else}
                                    <select name="GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_{$group.id_module|escape:'html':'UTF-8'}" class="fixed-width-xl">
                                        {if isset($input.options) && $input.options}
                                            {foreach $input.options as $option}
                                                <option {if isset($fields_value["GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_{$group.id_module}"]) && $fields_value["GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_{$group.id_module}"] == $option.value} selected="selected" {/if}  value="{$option.value|escape:'html':'UTF-8'}">{$option.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                {/if}
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            {$smarty.block.parent}
        </div>
    {elseif $input.type == 'opentab'}
        </div>
        <div class="{if isset($input.name) && $input.name}{$input.name|escape:'htmlall':'UTF-8'}{/if} {if $input.name == 'config_content'}activetab{/if}">
        <div>
    {elseif $input.type == 'closetab'}
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}