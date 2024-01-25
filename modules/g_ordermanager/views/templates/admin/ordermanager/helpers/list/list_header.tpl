{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{extends file="helpers/list/list_header.tpl"}
{block name=leadin}
	<div class="panel">
		<div class="panel-heading">
			<i class="icon-time"></i>{l s='Quick change status for selected orders' mod='g_ordermanager'}
		</div>
			<div class="radio">
                <div class="col-lg-4">
                    <label for="id_order_state">
    					<select id="id_order_state" name="id_order_state">
                            {foreach from=$order_statuses item=order_status_name key=id_order_state}
    			                 <option value="{$id_order_state|intval}">{$order_status_name|escape}</option>
                            {/foreach}
    					</select>
    				</label>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-default" name="submitUpdateOrderStatus">
    					<i class="icon-check"></i>
    					{l s='Update' mod='g_ordermanager'}
    				</button>
                </div>
			</div>
	</div>
	<div class="box_gridsetting_showin_box_wp">
		<div class="box_setting_showin_box box_gridsetting_showin_box">
			<div class="box_setting_showin_top box_setting_showinclose"></div>
			<div id="box_setting_showin" class="box_setting_showin">
				<div class="showin-heading-box">{l s='Grid List' mod='g_ordermanager'}</div>
				<div class="gpopup_content">
						{foreach $orderfields as $orderfieldgroup}
							<h4>{$orderfieldgroup.label|escape:'html':'UTF-8'}</h4>
							<ul class="orderlistfield">
								{foreach $orderfieldgroup.cols as $orderfield}
									<li class="">
										<label><input class="order_field_config" rel="{$orderfield.key|escape:'html':'UTF-8'}" name="order_fields[{$orderfield.key|escape:'html':'UTF-8'}]" id="order_fields_{$orderfield.key|escape:'html':'UTF-8'}" type="checkbox" {if isset($order_fields[$orderfield.key])} checked="checked" {/if} /> {$orderfield.name|escape:'html':'UTF-8'}</label>
									</li>
								{/foreach}
							</ul>
						{/foreach}
					
				</div>
				<div class="gpopupp_footer">
					<button type="button" class="pull-right btn btn-default gbtn-default" id="box_setting_showinreset"><i class="icon icon-refresh"></i> {l s='Reset to Default' mod='g_ordermanager'}</button>
					<button type="button" class="btn btn-primary box_setting_showin_save">{l s='Save' mod='g_ordermanager'}</button>
					<button type="button" class="btn btn-default box_setting_showinclose">{l s='Close' mod='g_ordermanager'}</button>
				</div>
			</div>
		</div>
	</div>
	<div class="box_grid_duplicate_order_wp g_ordermanager_popup_wp">
		<div class="box_duplicate_showin_top box_setting_showinclose"></div>
			<div id="box_duplicate_showin" class="box_duplicate_showin">
				<div class="showin-heading-box">{l s='Duplicate order' mod='g_ordermanager'}</div>
				<div class="gpopup_content">
					<p class="alert alert-info"><label>{l s='Duplicate order' mod='g_ordermanager'} :</label> #<span class="duplicate_order_ref"></span></p>
					<div class="gpopup_duplicate_search_customer">
						<label>{l s='Assign this order to other customer ' mod='g_ordermanager'}</label>
						<input type="text" class="duplicate_search_customer" id="customerorder" value="" />
						<div id="customers"></div>
					</div>
					<div class="gpopup_duplicate_content_loading"></div>
				</div>
				<div class="gpopupp_footer">
					<button type="button" class="btn btn-default box_setting_showinclose">{l s='Cancel' mod='g_ordermanager'}</button>
					<button type="button" class="btn btn-primary pull-right box_duplicate_save">{l s='Next' mod='g_ordermanager'}</button>
				</div>
			</div>
		</div>
	</div>
{/block}