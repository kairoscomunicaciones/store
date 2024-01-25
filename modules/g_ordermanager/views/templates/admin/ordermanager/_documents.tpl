{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

<div class="table-responsive">
	<table class="table" id="documents_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Document' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Number' mod='g_ordermanager'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Amount' mod='g_ordermanager'}</span>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getDocuments() item=document}

				{if get_class($document) eq 'OrderInvoice'}
					{if isset($document->is_delivery)}
					<tr id="delivery_{$document->id|escape:'html':'UTF-8'}">
					{else}
					<tr id="invoice_{$document->id|escape:'html':'UTF-8'}">
					{/if}
				{elseif get_class($document) eq 'OrderSlip'}
					<tr id="orderslip_{$document->id|escape:'html':'UTF-8'}">
				{/if}

						<td>{dateFormat date=$document->date_add}</td>
						<td>
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									{l s='Delivery slip' mod='g_ordermanager'}
								{else}
									{l s='Invoice' mod='g_ordermanager'}
								{/if}
							{elseif get_class($document) eq 'OrderSlip'}
								{l s='Credit Slip' mod='g_ordermanager'}
							{/if}
						</td>
						<td>
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									<a class="_blank" title="{l s='See the document' mod='g_ordermanager'}" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateDeliverySlipPDF&amp;id_order_invoice={$document->id|escape:'html':'UTF-8'}">
								{else}
									<a class="_blank" title="{l s='See the document' mod='g_ordermanager'}" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateInvoicePDF&amp;id_order_invoice={$document->id|escape:'html':'UTF-8'}">
							   {/if}
							{elseif get_class($document) eq 'OrderSlip'}
								<a class="_blank" title="{l s='See the document' mod='g_ordermanager'}" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateOrderSlipPDF&amp;id_order_slip={$document->id|escape:'html':'UTF-8'}">
							{/if}
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									{Configuration::get('PS_DELIVERY_PREFIX', $current_id_lang, null, $order->id_shop)|escape:'html':'UTF-8'}{'%06d'|sprintf:$document->delivery_number|escape:'html':'UTF-8'}
								{else}
									{$document->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)|escape:'html':'UTF-8'}
								{/if}
							{elseif get_class($document) eq 'OrderSlip'}
								{Configuration::get('PS_CREDIT_SLIP_PREFIX', $current_id_lang)|escape:'html':'UTF-8'}{'%06d'|sprintf:$document->id|escape:'html':'UTF-8'}
							{/if}
							</a>
						</td>
						<td>
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								--
							{else}
								{displayPrice price=$document->total_paid_tax_incl currency=$currency->id}&nbsp;
								{if $document->getTotalPaid()}
									<span>
									{if $document->getRestPaid() > 0}
										({displayPrice price=$document->getRestPaid() currency=$currency->id} {l s='not paid' mod='g_ordermanager'})
									{elseif $document->getRestPaid() < 0}
										({displayPrice price=-$document->getRestPaid() currency=$currency->id} {l s='overpaid' mod='g_ordermanager'})
									{/if}
									</span>
								{/if}
							{/if}
						{elseif get_class($document) eq 'OrderSlip'}
							{displayPrice price=$document->total_products_tax_incl+$document->total_shipping_tax_incl currency=$currency->id}
						{/if}
						</td>
						<td class="text-right document_action">
						{if get_class($document) eq 'OrderInvoice'}
							{if !isset($document->is_delivery)}

								{if $document->getRestPaid()}
									<a href="#formAddPaymentPanel" class="js-set-payment btn btn-default anchor" data-amount="{$document->getRestPaid()|escape:'html':'UTF-8'}" data-id-invoice="{$document->id|escape:'html':'UTF-8'}" title="{l s='Set payment form' mod='g_ordermanager'}">
										<i class="icon-money"></i>
										{l s='Enter payment' mod='g_ordermanager'}
									</a>
								{/if}

								<a href="#" class="btn btn-default" onclick="$('#invoiceNote{$document->id|escape:'html':'UTF-8'}').show(); return false;" title="{if $document->note eq ''}{l s='Add note' mod='g_ordermanager'}{else}{l s='Edit note' mod='g_ordermanager'}{/if}">
									{if $document->note eq ''}
										<i class="icon-plus-sign-alt"></i>
										{l s='Add note' mod='g_ordermanager'}
									{else}
										<i class="icon-pencil"></i>
										{l s='Edit note' mod='g_ordermanager'}
									{/if}
								</a>

							{/if}
						{/if}
						</td>
					</tr>
				{if get_class($document) eq 'OrderInvoice'}
					{if !isset($document->is_delivery)}
					<tr id="invoiceNote{$document->id|escape:'html':'UTF-8'}" style="display:none">
						<td colspan="5">
							<form action="{$current_index|escape:'html':'UTF-8'}&amp;viewOrder&amp;id_order={$order->id|escape:'html':'UTF-8'}{if isset($smarty.get.token)}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}{/if}" method="post">
								<p>
									<label for="editNote{$document->id|escape:'html':'UTF-8'}" class="t">{l s='Note' mod='g_ordermanager'}</label>
									<input type="hidden" name="id_order_invoice" value="{$document->id|escape:'html':'UTF-8'}" />
									<textarea name="note" id="editNote{$document->id|escape:'html':'UTF-8'}" class="edit-note textarea-autosize">{$document->note|escape:'html':'UTF-8'}</textarea>
								</p>
								<p>
									<button type="submit" name="submitEditNote" class="btn btn-default">
										<i class="icon-save"></i>
										{l s='Save' mod='g_ordermanager'}
									</button>
									<a class="btn btn-default" href="#" id="cancelNote" onclick="$('#invoiceNote{$document->id|escape:'html':'UTF-8'}').hide();return false;">
										<i class="icon-remove"></i>
										{l s='Cancel' mod='g_ordermanager'}
									</a>
								</p>
							</form>
						</td>
					</tr>
					{/if}
				{/if}
			{foreachelse}
				<tr>
					<td colspan="5" class="list-empty">
						<div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='There is no available document' mod='g_ordermanager'}
						</div>
						{if isset($invoice_management_active) && $invoice_management_active}
							<a class="btn btn-default" href="{$current_index|escape:'html':'UTF-8'}&amp;viewOrder&amp;submitGenerateInvoice&amp;id_order={$order->id|escape:'html':'UTF-8'}{if isset($smarty.get.token)}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}{/if}">
								<i class="icon-repeat"></i>
								{l s='Generate invoice' mod='g_ordermanager'}
							</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
