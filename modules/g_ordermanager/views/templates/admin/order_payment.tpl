{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if count($order->getOrderPayments()) > 0}
	<p class="alert alert-danger"{if round($orders_total_paid_tax_incl, 2) == round($total_paid, 2) || (isset($currentState) && $currentState->id == 6)} style="display: none;"{/if}>
		{l s='Warning' mod='g_ordermanager'}
		<strong>{displayPrice price=$total_paid currency=$currency->id}</strong>
		{l s='paid instead of' mod='g_ordermanager'}
		<strong class="total_paid">{displayPrice price=$orders_total_paid_tax_incl currency=$currency->id}</strong>
		{foreach $order->getBrother() as $brother_order}
			{if $brother_order@first}
				{if count($order->getBrother()) == 1}
					<br />{l s='This warning also concerns order ' mod='g_ordermanager'}
				{else}
					<br />{l s='This warning also concerns the next orders:' mod='g_ordermanager'}
				{/if}
			{/if}
			<a href="{$current_index|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$brother_order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
				#{'%06d'|sprintf:$brother_order->id|escape:'html':'UTF-8'}
			</a>
		{/foreach}
	</p>
{/if}
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th><span class="title_box ">{l s='Date' mod='g_ordermanager'}</span></th>
				<th><span class="title_box ">{l s='Payment method' mod='g_ordermanager'}</span></th>
				<th><span class="title_box ">{l s='Transaction ID' mod='g_ordermanager'}</span></th>
				<th><span class="title_box ">{l s='Amount' mod='g_ordermanager'}</span></th>
				<th><span class="title_box ">{l s='Invoice' mod='g_ordermanager'}</span></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getOrderPaymentCollection() item=payment}
			<tr>
				<td>{dateFormat date=$payment->date_add full=true}</td>
				<td>{$payment->payment_method|escape:'html':'UTF-8'}</td>
				<td>{$payment->transaction_id|escape:'html':'UTF-8'}</td>
				<td>{displayPrice price=$payment->amount currency=$payment->id_currency}</td>
				<td>
				{if $invoice = $payment->getOrderInvoice($order->id)}
					{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)|escape:'html':'UTF-8'}
				{else}
				{/if}
				</td>
				<td class="actions">
					<button class="btn btn-default open_payment_information2"  data-id="{$payment->id|escape:'html':'UTF-8'}">
						<i class="icon-search"></i>
						{l s='Details' mod='g_ordermanager'}
					</button>
                    <button class="btn btn-default edit_payment_information edit_payment_information_{$payment->id|escape:'html':'UTF-8'}"  data-id="{$payment->id|escape:'html':'UTF-8'}">
						<i class="icon-edit"></i>
					</button>
                    <button class="btn btn-default delete_payment_information" data-idorder="{$order->id|escape:'html':'UTF-8'}" data-id="{$payment->id|escape:'html':'UTF-8'}">
						<i class="icon-trash"></i>
					</button>
				</td>
			</tr>
			<tr class="payment_information" id="payment_information_{$payment->id|escape:'html':'UTF-8'}" style="display: none;">
				<td colspan="6">
					<p>
						<b>{l s='Card Number' mod='g_ordermanager'}</b>&nbsp;
						{if $payment->card_number}
							{$payment->card_number|escape:'html':'UTF-8'}
						{else}
							<i>{l s='Not defined' mod='g_ordermanager'}</i>
						{/if}
					</p>
					<p>
						<b>{l s='Card Brand' mod='g_ordermanager'}</b>&nbsp;
						{if $payment->card_brand}
							{$payment->card_brand|escape:'html':'UTF-8'}
						{else}
							<i>{l s='Not defined' mod='g_ordermanager'}</i>
						{/if}
					</p>
					<p>
						<b>{l s='Card Expiration' mod='g_ordermanager'}</b>&nbsp;
						{if $payment->card_expiration}
							{$payment->card_expiration|escape:'html':'UTF-8'}
						{else}
							<i>{l s='Not defined' mod='g_ordermanager'}</i>
						{/if}
					</p>
					<p>
						<b>{l s='Card Holder' mod='g_ordermanager'}</b>&nbsp;
						{if $payment->card_holder}
							{$payment->card_holder|escape:'html':'UTF-8'}
						{else}
							<i>{l s='Not defined' mod='g_ordermanager'}</i>
						{/if}
					</p>
				</td>
			</tr>
            <tr class="edit_payment_information" id="edit_payment_information_{$payment->id|escape:'html':'UTF-8'}" style="display: none;">
                <td>
					<div class="input-group fixed-width-l">
						<input type="text" name="payment_date" class="datetimepicker" value="{dateFormat date=$payment->date_add full=true}" />
						<div class="input-group-addon">
							<i class="icon-calendar-o"></i>
						</div>
					</div>
				</td>
				<td>
					<input name="payment_method" list="payment_method" class="payment_method" value="{$payment->payment_method|escape:'html':'UTF-8'}" />
					<datalist id="payment_method">
					{foreach from=$payment_methods item=payment_method}
						<option value="{$payment_method|escape:'html':'UTF-8'}">
					{/foreach}
					</datalist>
				</td>
				<td>
					<input type="text" name="payment_transaction_id" value="{$payment->transaction_id|escape:'html':'UTF-8'}" class="form-control fixed-width-sm"/>
				</td>
				<td>
					<input type="text" name="payment_amount" value="{Tools::ps_round(Tools::convertPrice($payment->amount, $currency), 2)|escape:'html':'UTF-8'}" class="form-control fixed-width-sm pull-left" />
					<select name="payment_currency" class="payment_currency form-control fixed-width-xs pull-left">
						{foreach from=$currencies item=current_currency}
							<option value="{$current_currency['id_currency']|escape:'html':'UTF-8'}"{if $current_currency['id_currency'] == $currency->id} selected="selected"{/if}>{$current_currency['sign']|escape:'html':'UTF-8'}</option>
						{/foreach}
					</select>
				</td>
				<td>
					{if count($invoices_collection) > 0}
						<select name="payment_invoice" id="payment_invoice">
						{foreach from=$invoices_collection item=invoice}
							<option value="{$invoice->id|escape:'html':'UTF-8'}" selected="selected">{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)|escape:'html':'UTF-8'}</option>
						{/foreach}
						</select>
					{/if}
				</td>
				<td class="actions">
					<button class="btn btn-primary OrderSubmitEditPayment" type="submit" data-idorder="{$order->id|escape:'html':'UTF-8'}" rel="{$payment->id|escape:'html':'UTF-8'}">
						{l s='Edit' mod='g_ordermanager'}
					</button>
                    <button class="btn btn-default cancel_edit_payment_information" type="submit" rel="{$payment->id|escape:'html':'UTF-8'}">
						<i class="icon-remove text-danger"></i>{l s='Cancel' mod='g_ordermanager'}
					</button>
				</td>
            </tr>
			{foreachelse}
			<tr>
				<td class="list-empty hidden-print" colspan="6">
					<div class="list-empty-msg">
						<i class="icon-warning-sign list-empty-icon"></i>
						{l s='No payment methods are available' mod='g_ordermanager'}
					</div>
				</td>
			</tr>
			{/foreach}
			<tr class="current-edit hidden-print">
				<td>
					<div class="input-group fixed-width-l">
						<input type="text" name="payment_date" class="datetimepicker" value="{date('Y-m-d')|escape:'html':'UTF-8'}" />
						<div class="input-group-addon">
							<i class="icon-calendar-o"></i>
						</div>
					</div>
				</td>
				<td>
					<input name="payment_method" list="payment_method" class="payment_method" />
					<datalist id="payment_method">
					{foreach from=$payment_methods item=payment_method}
						<option value="{$payment_method|escape:'html':'UTF-8'}">
					{/foreach}
					</datalist>
				</td>
				<td>
					<input type="text" name="payment_transaction_id" value="" class="form-control fixed-width-sm"/>
				</td>
				<td>
					<input type="text" name="payment_amount" value="" class="form-control fixed-width-sm pull-left" />
					<select name="payment_currency" class="payment_currency form-control fixed-width-xs pull-left">
						{foreach from=$currencies item=current_currency}
							<option value="{$current_currency['id_currency']|escape:'html':'UTF-8'}"{if $current_currency['id_currency'] == $currency->id} selected="selected"{/if}>{$current_currency['sign']|escape:'html':'UTF-8'}</option>
						{/foreach}
					</select>
				</td>
				<td>
					{if count($invoices_collection) > 0}
						<select name="payment_invoice" id="payment_invoice">
						{foreach from=$invoices_collection item=invoice}
							<option value="{$invoice->id|escape:'html':'UTF-8'}" selected="selected">{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)|escape:'html':'UTF-8'}</option>
						{/foreach}
						</select>
					{/if}
				</td>
				<td class="actions">
					<button class="btn btn-primary OrderSubmitAddPayment" type="submit" data-idorder="{$order->id|escape:'html':'UTF-8'}">
						{l s='Add' mod='g_ordermanager'}
					</button>
				</td>
			</tr>
		</tbody>
	</table>
</div>
