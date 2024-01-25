{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

<div class="form-horizontal well">
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Name' mod='g_ordermanager'}
		</label>
		<div class="col-lg-9">
			<input class="form-control" type="text" name="discount_name" value="" />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Type' mod='g_ordermanager'}
		</label>
		<div class="col-lg-9">
			<select class="form-control" name="discount_type" id="discount_type">
				<option value="1">{l s='Percent' mod='g_ordermanager'}</option>
				<option value="2">{l s='Amount' mod='g_ordermanager'}</option>
				<option value="3">{l s='Free shipping' mod='g_ordermanager'}</option>
			</select>
		</div>
	</div>

	<div id="discount_value_field" class="form-group">
		<label class="control-label col-lg-3">
			{l s='Value' mod='g_ordermanager'}
		</label>
		<div class="col-lg-9">
			<div class="input-group">
				<div class="input-group-addon">
					<span id="discount_currency_sign" style="display: none;">{$currency->sign|escape:'html':'UTF-8'}</span>
					<span id="discount_percent_symbol">%</span>
				</div>
				<input class="form-control" type="text" name="discount_value"/>
			</div>
			<p class="text-muted" id="discount_value_help" style="display: none;">
				{l s='This value must include taxes.' mod='g_ordermanager'}
			</p>
		</div>
	</div>

	{if $order->hasInvoice()}
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Invoice' mod='g_ordermanager'}
		</label>
		<div class="col-lg-9">
			<select name="discount_invoice">
				{foreach from=$invoices_collection item=invoice}
				<option value="{$invoice->id|escape:'html':'UTF-8'}" selected="selected">
					{$invoice->getInvoiceNumberFormatted($current_id_lang)|escape:'html':'UTF-8'} - {displayPrice price=$invoice->total_paid_tax_incl currency=$order->id_currency}
				</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3">
			<p class="checkbox">
				<label class="control-label" for="discount_all_invoices">
					<input type="checkbox" name="discount_all_invoices" id="discount_all_invoices" value="1" /> 
					{l s='Apply on all invoices' mod='g_ordermanager'}
				</label>
			</p>
			<p class="help-block">
				{l s='If you chooses to create this discount for all invoices, only one discount will be created per order invoice.' mod='g_ordermanager'}
			</p>
		</div>
	</div>
	{/if}

	<div class="row">
		<div class="col-lg-9 col-lg-offset-3">
			<button class="btn btn-default" type="button" id="cancel_add_voucher">
				<i class="icon-remove text-danger"></i>
				{l s='Cancel' mod='g_ordermanager'}
			</button>
			<button class="btn btn-default" type="submit" name="submitNewVoucher">
				<i class="icon-ok text-success"></i>
				{l s='Add' mod='g_ordermanager'}
			</button>
		</div>
	</div>
</div>