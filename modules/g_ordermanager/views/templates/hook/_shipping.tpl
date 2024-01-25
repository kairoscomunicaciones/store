{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<div class="table-responsive">
	<table class="table" id="shipping_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">&nbsp;</span>
				</th>
				<th>
					<span class="title_box ">{l s='Carrier' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Weight' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Shipping cost' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Tracking number' mod='g_ordermanager'}</span>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getShipping() item=line}
			<tr>
				<td>{dateFormat date=$line.date_add full=true}</td>
				<td>&nbsp;</td>
				<td>{$line.carrier_name|escape:'html':'UTF-8'}</td>
				<td class="weight">{$line.weight|string_format:"%.3f"|escape:'html':'UTF-8'} {Configuration::get('PS_WEIGHT_UNIT')|escape:'html':'UTF-8'}</td>
				<td class="center">
					{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
						{displayPrice price=$line.shipping_cost_tax_incl currency=$currency->id}
					{else}
						{displayPrice price=$line.shipping_cost_tax_excl currency=$currency->id}
					{/if}
				</td>
				<td>
					<span class="shipping_number_show">{if $line.url && $line.tracking_number}<a class="_blank" href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number|escape:'html':'UTF-8'}</a>{else}{$line.tracking_number|escape:'html':'UTF-8'}{/if}</span>
				</td>
				<td>
                    <a href="#" class="edit_shipping_link btn btn-default" rel="change_carrier_{$order->id|escape:'html':'UTF-8'}">
						<i class="icon-pencil"></i>
						{l s='Edit' mod='g_ordermanager'}
					</a>
				</td>
			</tr>
            <tr class="editshippinglink" id="change_carrier_{$order->id|escape:'html':'UTF-8'}">
                <td colspan="7">
                    <div class="panel change_carrier">
                        <div class="panel-heading">{l s='Change Carrier' mod='g_ordermanager'}</div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Carrier' mod='g_ordermanager'}</label>
                    			<div class="col-lg-9">
                                    <select id="carrier_{$order->id|escape:'html':'UTF-8'}">
                                        {if $all_carrier}
                                            {foreach $all_carrier as $_carrier}
                                                <option value="{$_carrier.id_carrier|escape:'html':'UTF-8'}" {if $_carrier.id_carrier == $carrier->id} selected="selected"{/if}>{$_carrier.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Shipping cost' mod='g_ordermanager'}({l s='Tax incl.' mod='g_ordermanager'})</label>
                    			<div class="col-lg-9">
                                    <div class="input-group">
                                        <input type="text" rel="{$order->id|escape:'html':'UTF-8'}" class="shipping_cost_incl" id="shipping_cost_incl_{$order->id|escape:'html':'UTF-8'}" value="{$order->total_shipping_tax_incl|escape:'html':'UTF-8'}"  />
                                        <span class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Shipping cost' mod='g_ordermanager'}({l s='Tax excl.' mod='g_ordermanager'})</label>
                    			<div class="col-lg-9">
                                    <div class="input-group">
                                        <input type="text" rel="{$order->id|escape:'html':'UTF-8'}" class="shipping_cost_excl" id="shipping_cost_excl_{$order->id|escape:'html':'UTF-8'}" value="{$order->total_shipping_tax_excl|escape:'html':'UTF-8'}" />
                                        <span class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Tax rate' mod='g_ordermanager'}</label>
                    			<div class="col-lg-9">
                                    <div class="input-group">
                                        <input type="text" rel="{$order->id|escape:'html':'UTF-8'}" class="order_tax_rate" id="tax_rate_{$order->id|escape:'html':'UTF-8'}" value="{$order->carrier_tax_rate|escape:'html':'UTF-8'}" />
                                        <span class="input-group-addon">{l s='%' mod='g_ordermanager'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Shipping weight' mod='g_ordermanager'}</label>
                    			<div class="col-lg-9">
                                    <div class="input-group">
                                        <input type="text" id="shipping_weight_{$order->id|escape:'html':'UTF-8'}" value="{$order_carrier->weight|escape:'html':'UTF-8'}" />
                                        <span class="input-group-addon">{$weight_unit|escape:'html':'UTF-8'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Tracking number' mod='g_ordermanager'}</label>
                    			<div class="col-lg-9">
                                    <input type="text" id="tracking_number_{$order->id|escape:'html':'UTF-8'}" value="{$order_carrier->tracking_number|escape:'html':'UTF-8'}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-3">
                                    <button rel="{$order->id|escape:'html':'UTF-8'}" type="submit" class="btn btn-default pull-left orderpage OrderChangeCarrier"><i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}</button>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </td>
            </tr>
			{/foreach}
		</tbody>
	</table>
</div>