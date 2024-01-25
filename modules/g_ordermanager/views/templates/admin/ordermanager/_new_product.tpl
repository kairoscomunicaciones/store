{*
* Do not edit the file if you want to upgrade in future.
*
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{assign var="currencySymbolBeforeAmount" value=$currency->format[0] === '¤'}
<tr id="new_product" style="display:none">
	<td style="display:none;" colspan="2">
		<input type="hidden" id="add_product_product_id" name="add_product[product_id]" value="0" />

		<div class="form-group">
			<label>{l s='Product:' mod='g_ordermanager'}</label>
			<div class="input-group">
				<input type="text" id="add_product_product_name2" value=""/>
				<div class="input-group-addon">
					<i class="icon-search"></i>
				</div>
			</div>
		</div>

		<div id="add_product_product_attribute_area" class="form-group" style="display: none;">
			<label>{l s='Combinations' mod='g_ordermanager'}</label>
			<select name="add_product[product_attribute_id]" id="add_product_product_attribute_id"></select>
		</div>

		<div id="add_product_product_warehouse_area" class="form-group" style="display: none;">
			<label>{l s='Warehouse' mod='g_ordermanager'}</label>
			<select  id="add_product_warehouse" name="add_product_warehouse"></select>
		</div>
	</td>
	{if ($order->getTaxCalculationMethod() != $smarty.const.PS_TAX_EXC)}
	<td></td>
	{/if}
	<td style="display:none;">
		<div class="row">
			<div class="input-group fixed-width-xl">
				{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
				<input type="text" name="add_product[product_price_tax_excl]" id="add_product_product_price_tax_excl" value="" disabled="disabled" />
				{if !($currencySymbolBeforeAmount)}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="input-group fixed-width-xl">
				{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
				<input type="text" name="add_product[product_price_tax_incl]" id="add_product_product_price_tax_incl" value="" disabled="disabled" />
				{if !($currencySymbolBeforeAmount)}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
			</div>
		</div>
	</td>

	<td style="display:none;" class="productQuantity">
		<input type="number" class="form-control fixed-width-sm" name="add_product[product_quantity]" id="add_product_product_quantity" value="1" disabled="disabled" />
	</td>
	{if ($order->hasBeenPaid())}<td style="display:none;" class="productQuantity"></td>{/if}
	{if $display_warehouse}<td></td>{/if}
	{if ($order->hasBeenDelivered())}<td style="display:none;" class="productQuantity"></td>{/if}
	<td style="display:none;" class="productQuantity" id="add_product_product_stock">0</td>
	<td style="display:none;" id="add_product_product_total">{displayPrice price=0 currency=$currency->id}</td>
	<td style="display:none;" colspan="2">
		{if sizeof($invoices_collection)}
		<select class="form-control" name="add_product[invoice]" id="add_product_product_invoice" disabled="disabled">
			<optgroup class="existing" label="{l s='Existing' mod='g_ordermanager'}">
				{foreach from=$invoices_collection item=invoice}
				<option value="{$invoice->id|escape:'html':'UTF-8'}">{$invoice->getInvoiceNumberFormatted($current_id_lang)|escape:'html':'UTF-8'}</option>
				{/foreach}
			</optgroup>
			<optgroup label="{l s='New' mod='g_ordermanager'}">
				<option value="0">{l s='Create a new invoice' mod='g_ordermanager'}</option>
			</optgroup>
		</select>
		{/if}
	</td>
	<td style="display:none;">
		<button type="button" class="btn btn-default" id="cancelAddProduct">
			<i class="icon-remove text-danger"></i>
			{l s='Cancel' mod='g_ordermanager'}
		</button>
		<button type="button" class="btn btn-default" id="submitAddProduct2" disabled="disabled">
			<i class="icon-ok text-success"></i>
			{l s='Add' mod='g_ordermanager'}
		</button>
	</td>
</tr>

<tr id="new_invoice" style="display:none">
	<td colspan="10">
		<h4>{l s='New invoice information' mod='g_ordermanager'}</h4>
		<div class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Carrier' mod='g_ordermanager'}</label>
				<div class="col-lg-9">
					<p class="form-control-static"><strong>{$carrier->name|escape:'html':'UTF-8'}</strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Shipping Cost' mod='g_ordermanager'}</label>
				<div class="col-lg-9">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="add_invoice[free_shipping]" value="1" />
							{l s='Free shipping' mod='g_ordermanager'}
						</label>
						<p class="help-block">{l s='If you don\'t select "Free shipping," the normal shipping cost will be applied.' mod='g_ordermanager'}</p>
					</div>
				</div>
			</div>
		</div>
	</td>
</tr>
