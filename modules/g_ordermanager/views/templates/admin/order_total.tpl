{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}
{capture "TaxMethod"}
	{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
		{l s='tax excluded.' mod='g_ordermanager'}
	{else}
		{l s='tax included.' mod='g_ordermanager'}
	{/if}
{/capture}
<div class="table-responsive">
	<table class="table">
		{* Assign order price *}
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
			{assign var=order_product_price value=($order->total_products)}
			{assign var=order_discount_price value=$order->total_discounts_tax_excl}
			{assign var=order_wrapping_price value=$order->total_wrapping_tax_excl}
			{assign var=order_shipping_price value=$order->total_shipping_tax_excl}
		{else}
			{assign var=order_product_price value=$order->total_products_wt}
			{assign var=order_discount_price value=$order->total_discounts_tax_incl}
			{assign var=order_wrapping_price value=$order->total_wrapping_tax_incl}
			{assign var=order_shipping_price value=$order->total_shipping_tax_incl}
		{/if}
		<tr id="total_products">
			<td class="text-right">{l s='Products:' mod='g_ordermanager'}</td>
			<td class="amount text-right nowrap">
				{displayPrice price=$order_product_price currency=$currency->id}
			</td>
			<td class="partial_refund_fields current-edit" style="display:none;"></td>
		</tr>
		<tr id="total_discounts" {if $order->total_discounts_tax_incl == 0}style="display: none;"{/if}>
			<td class="text-right">{l s='Discounts' mod='g_ordermanager'}</td>
			<td class="amount text-right nowrap">
				-{displayPrice price=$order_discount_price currency=$currency->id}
			</td>
			<td class="partial_refund_fields current-edit" style="display:none;"></td>
		</tr>
		<tr id="total_wrapping" {if $order->total_wrapping_tax_incl == 0}style="display: none;"{/if}>
			<td class="text-right">{l s='Wrapping' mod='g_ordermanager'}</td>
			<td class="amount text-right nowrap">
				{displayPrice price=$order_wrapping_price currency=$currency->id}
			</td>
			<td class="partial_refund_fields current-edit" style="display:none;"></td>
		</tr>
		<tr id="total_shipping">
			<td class="text-right">{l s='Shipping' mod='g_ordermanager'}</td>
			<td class="amount text-right nowrap" >
				{displayPrice price=$order_shipping_price currency=$currency->id}
			</td>
			<td class="partial_refund_fields current-edit" style="display:none;">
				<div class="input-group">
					<div class="input-group-addon">
						{$currency->prefix|escape:'html':'UTF-8'}
						{$currency->suffix|escape:'html':'UTF-8'}
					</div>
					<input type="text" name="partialRefundShippingCost" value="0" />
				</div>
				<p class="help-block"><i class="icon-warning-sign"></i> {l s='(%s)' sprintf=$smarty.capture.TaxMethod mod='g_ordermanager'}</p>
			</td>
		</tr>
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
		<tr id="total_taxes">
			<td class="text-right">{l s='Taxes' mod='g_ordermanager'}</td>
			<td class="amount text-right nowrap" >{displayPrice price=($order->total_paid_tax_incl-$order->total_paid_tax_excl) currency=$currency->id}</td>
			<td class="partial_refund_fields current-edit" style="display:none;"></td>
		</tr>
		{/if}
		{assign var=order_total_price value=$order->total_paid_tax_incl}
		<tr id="total_order">
			<td class="text-right"><strong>{l s='Total' mod='g_ordermanager'}</strong></td>
			<td class="amount text-right nowrap">
				<strong>{displayPrice price=$order_total_price currency=$currency->id}</strong>
			</td>
			<td class="partial_refund_fields current-edit" style="display:none;"></td>
		</tr>
	</table>
</div>