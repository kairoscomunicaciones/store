{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'customergroupconfig'}
        <div class="col-sm-9">
            <div class="{$input.name|escape:'htmlall':'UTF-8'}_wp allrule_box" rel="{$input.name|escape:'htmlall':'UTF-8'}">
                <div class="panel">
                    <div class="panel-heading">
                        <i class="icon-list"></i>{l s='List of Formats' mod='gwadvancedinvoice'}
                        <span class="panel-heading-action label-tooltip" data-toggle="tooltip" data-original-title="Add new" data-html="true" data-placement="top">
                            <button class="btn btn-default pull-right addnewrule_bt" rel="{$input.name|escape:'htmlall':'UTF-8'}"><i class="icon process-icon-new"></i></button>
                        </span>
					</div>
                    
                    <table class="table">
                        <thead>
                            <th>{l s='Format' mod='gwadvancedinvoice'}</th>
                            <th>{l s='Groups' mod='gwadvancedinvoice'}</th>
                            <th>{l s='Action' mod='gwadvancedinvoice'}</th>
                        </thead>
                        <tbody>
                            <tr class="demo_data" style="display:none;">
                                <td class="numberformat"></td>
                                <td class="groups"></td>
                                <td>
                                    <button class="delete_rule btn btn-default"><i class="icon icon-trash"></i></button>
                                    <button class="edit_rule  btn btn-default"><i class="icon icon-edit"></i></button>
                                </td>
                            </tr>
                    {if isset($fields_value['allrule']) && $fields_value['allrule']}
                        {foreach $fields_value['allrule'] as $rule}
                            <tr class="rule_{$rule.id_gwaicustomnumber|intval}"  data-type="{$rule.type|intval}" data-id_rule="{$rule.id_gwaicustomnumber|intval}" data-current="{$rule.current_counter_nbr|escape:'htmlall':'UTF-8'}" data-start="{$rule.start|escape:'htmlall':'UTF-8'}" data-step="{$rule.step|escape:'htmlall':'UTF-8'}" data-length="{$rule.length|escape:'htmlall':'UTF-8'}" data-numberformat="{$rule.numberformat|escape:'htmlall':'UTF-8'}" data-groups="{$rule.groups|escape:'htmlall':'UTF-8'}" data-resettype="{$rule.resettype|escape:'htmlall':'UTF-8'}" data-resetnumber="{$rule.resetnumber|escape:'htmlall':'UTF-8'}" data-resetdate="{$rule.resetdate|escape:'htmlall':'UTF-8'}">
                                <td class="numberformat">{$rule.numberformat|escape:'htmlall':'UTF-8'}</td>
                                <td class="groups">{$rule.groups_name|escape:'htmlall':'UTF-8'}</td>
                                <td>
                                    <button class="delete_rule btn btn-default" data-type="{$rule.type|escape:'htmlall':'UTF-8'}" rel="{$rule.id_gwaicustomnumber|intval}"><i class="icon icon-trash"></i></button>
                                    <button class="edit_rule  btn btn-default" data-type="{$rule.type|escape:'htmlall':'UTF-8'}" rel="{$rule.id_gwaicustomnumber|intval}"><i class="icon icon-edit"></i></button>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                    </table>
                    
                </div>
            </div>
            <div class="addnewrule {$input.name|escape:'htmlall':'UTF-8'}_addnewrule panel" style="display:none;">
                <input type="hidden" value="" name="id_rule" />
                <input type="hidden" value="{$input.name|escape:'htmlall':'UTF-8'}" class="type_rule" name="type_rule" />
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='Start number of {COUNTER}' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-sm-3">
            			<div class="form-element">
            				<input type="text"  class="field-short number_input {$input.name|escape:'htmlall':'UTF-8'}_start" name="start" value="1" />
            			</div>
            		</div>
            	</div>
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='{COUNTER} increase by Step' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-sm-3">
            			<div class="form-element">
            				<input type="text" class="field-short unsigned_number number_input {$input.name|escape:'htmlall':'UTF-8'}_step" name="step" value="1" />
            			</div>
            		</div>
            	</div>
                <div class="form-group col-sm-12">
                    <label class="col-sm-3 control-label" style="text-align:right;">
                        {l s='Current {COUNTER} number' mod='gwadvancedinvoice'}
                    </label>
                    <div class="col-sm-3">
                        <div class="form-element">
                            <input type="text" class="field-short unsigned_number number_input {$input.name|escape:'htmlall':'UTF-8'}_current" name="current" value="" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_order" id="{$input.name|escape:'htmlall':'UTF-8'}_order_on" value="1">
							<label for="{$input.name|escape:'htmlall':'UTF-8'}_order_on">{l s='Yes' mod='gwadvancedinvoice'}</label>
							<input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_order" id="{$input.name|escape:'htmlall':'UTF-8'}_order_off" value="0" checked="checked">
							<label for="{$input.name|escape:'htmlall':'UTF-8'}_order_off">{l s='No' mod='gwadvancedinvoice'}</label>
							<a class="slide-button btn"></a>
						</span>
                    </div>
					<div class="col-lg-6 col-lg-offset-6">
						<div class="help-block">{l s='Select Yes for update Current {COUNTER} number' mod='gwadvancedinvoice'}</div>
					</div>
                </div>
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='{COUNTER} Length' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-sm-3">
            			<div class="form-element">
            				<input type="text" class="field-short unsigned_number number_input {$input.name|escape:'htmlall':'UTF-8'}_length" name="length" value="6" />
            			</div>
                        
            		</div>
                    <div class="col-lg-9 col-lg-offset-3">
            			<div class="help-block">{l s='E.g If you set length=4. it will be shown 0001, 000X' mod='gwadvancedinvoice'}</div>
            		</div>
            	</div>
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='Format' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="{$input.name|escape:'htmlall':'UTF-8'}_format field-short"  name="numberformat" value="" />
                            <div class="input-group-addon">
                                <div class="col-lg-2">
                                    <a href="" class="dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                        {l s='Choose shortcode' mod='gwadvancedinvoice'}
                                        <i class="icon-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu choose_shortcode" rel="{$input.name|escape:'htmlall':'UTF-8'}">
                                        <li><a data-shortcode="COUNTER">{l s='{COUNTER} : Current counter by rule.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="DD">{l s='{DD} : Date add (01->31)' mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="D">{l s='{D} : Date add without leading zeros (1->31)' mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="MM">{l s='{MM} : Month add (01->12)' mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="M">{l s='{M} : Month add without leading zeros (1->12)' mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="YY">{l s='{YY} : Year add 4 digits (e.g. : %s)' sprintf=$smarty.now|date_format:"Y" mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="Y">{l s='{Y} : Year add 2 digits (e.g. : %s)' sprintf=$smarty.now|date_format:"y" mod='gwadvancedinvoice'}}</a></li>
                                        <li><a data-shortcode="ID_CUSTOMER">{l s='{ID_CUSTOMER} : Customer Id.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="ID_GROUP">{l s='{ID_GROUP} : Customer Id Group.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="ID_SHOP">{l s='{ID_SHOP} : Id Shop.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="GROUP">{l s='{GROUP} : Customer Group Name (e.g. : GUEST , CUSTOMER ...)' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="ID_ORDER">{l s='{ID_ORDER} : Order Id.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="ORDER_REFERENCE">{l s='{ORDER_REFERENCE} : Order Reference (Only use for INVOICE and DELIVERY number)).' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="ID_INVOICE">{l s='{ID_INVOICE} : Invoice Id.' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="RANDOM">{l s='{RANDOM} : Random text (e.g. : KLWXNYSXH).' mod='gwadvancedinvoice'}</a></li>
                                        <li><a data-shortcode="RANDOM_NUMBER">{l s='{RANDOM_NUMBER} : Random number (0->999999)(e.g. : 104516).' mod='gwadvancedinvoice'}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
            		</div>
                    <div class="col-lg-9 col-lg-offset-3">
            			<div class="help-block">{l s='E.g INVOICE {Y}-{COUNTER}' mod='gwadvancedinvoice'}</div>
                        <div class="text-info">{l s='Next number'  mod='gwadvancedinvoice'}: <strong class="nextnumber"></strong> <button class="btn btn-default getnextnumber" rel="{$input.name|escape:'htmlall':'UTF-8'}"><i class="icon-refresh"></i></button></div>
            		</div>
            	</div>
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='Customer Group' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-sm-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="fixed-width-xs"></th>
                                    <th class="fixed-width-xs"><span class="title_box">{l s='ID' mod='gwadvancedinvoice'}</span></th>
                                    <th>{l s='Group name' mod='gwadvancedinvoice'}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {if isset($fields_value.allgroup)}
                                    {foreach $fields_value.allgroup as $group}
                                        <tr>
                                            <td class="fixed-width-xs">
                                                <input name="groupBox_{$input.name|escape:'htmlall':'UTF-8'}[]" class="groupBox_{$input.name|escape:'htmlall':'UTF-8'}" type="checkbox"  id="groupBox_{$input.name|escape:'htmlall':'UTF-8'}_{$group.id_group|intval}" value="{$group.id_group|intval}" />
                                            </td>
                                            <td class="fixed-width-xs">{$group.id_group|intval}</td>
                                            <td><label for="groupBox_{$input.name|escape:'htmlall':'UTF-8'}_{$group.id_group|intval}">{$group.name|escape:'htmlall':'UTF-8'}</label></td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
            			<div class="help-block">{l s='Apply the format to a customer group.' mod='gwadvancedinvoice'}</div>
            		</div>
            	</div>
                <div class="form-group col-sm-12">
            		<label class="col-sm-3 control-label" style="text-align:right;">
            			{l s='Reset {COUNTER}' mod='gwadvancedinvoice'}
            		</label>
            		<div class="col-sm-9">
                        <div class="form-group col-sm-12">
                            <div class="col-sm-2">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_0" value="0" />
                            </div>
                            <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_0">{l s='None' mod='gwadvancedinvoice'}</label>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-1">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_1" value="1" />
                            </div>
                            <div class="col-sm-3">
                                <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_1">{l s='When counter =' mod='gwadvancedinvoice'}</label>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="resetnumber" class="{$input.name|escape:'htmlall':'UTF-8'}_reset_val unsigned_number number_input" value="" />
                            </div>
                            <div class="col-sm-3">
                                <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_1">{l s='From' mod='gwadvancedinvoice'}</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-1">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_2" value="2" />
                            </div>
                            <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_2">{l s='Every day' mod='gwadvancedinvoice'}</label>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-1">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_3" value="3" />
                            </div>
                            <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_3">{l s='Every month' mod='gwadvancedinvoice'}</label>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-1">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_4" value="4" />
                            </div>
                            <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_4">{l s='Every year' mod='gwadvancedinvoice'}</label>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-1">
                                <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}_reset" class="field-short {$input.name|escape:'htmlall':'UTF-8'}_reset" id="{$input.name|escape:'htmlall':'UTF-8'}_reset_5" value="5" />
                            </div>
                            <div class="col-sm-3">
                                <label for="{$input.name|escape:'htmlall':'UTF-8'}_reset_5">{l s='When date =' mod='gwadvancedinvoice'}</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="resetdate" class="{$input.name|escape:'htmlall':'UTF-8'}_reset_date_val date_val" value="" />
                                <div class="help-block">{l s='Year-Month-Day Hour:Minute:Second' mod='gwadvancedinvoice'}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">{l s='The option allows you to reset the {COUNTER} tags to start value.' mod='gwadvancedinvoice'}</div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <label class="col-sm-3 control-label" style="text-align:right;"></label>
                    <div class="col-sm-6"><a rel="{$input.name|escape:'htmlall':'UTF-8'}" class="addgrouprule btn btn-success">{l s='Save' mod='gwadvancedinvoice'}</a></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        {addJsDef allgroup=$fields_value.allgroup}
    {/if}
    {$smarty.block.parent}
{/block}