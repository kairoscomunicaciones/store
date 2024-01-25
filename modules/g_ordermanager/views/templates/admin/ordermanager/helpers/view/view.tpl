{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}
    {assign var="adminaddresseslink" value="{$link->getAdminLink('AdminAddresses',true)}&submitFormAjax=1"}
	<script type="text/javascript">
    /* token = '{getAdminToken tab='AdminOrders'}'; */
    var admin_ordermanager_tab_link = decodeURIComponent("{$admin_ordermanager_tab_link|escape:'htmlall':'UTF-8'}");
	var admin_order_tab_link = decodeURIComponent("{$admin_order_tab_link|escape:'htmlall':'UTF-8'}");
    var admin_address_link   = decodeURIComponent("{$admin_address_link|escape:'htmlall':'UTF-8'}");
    var admin_address_link_back = decodeURIComponent("{$adminaddresseslink|escape:'htmlall':'UTF-8'}");
	var id_order = {$order->id|escape:'html':'UTF-8'};
	var id_lang = {$current_id_lang|escape:'html':'UTF-8'};
	var id_currency = {$order->id_currency|escape:'html':'UTF-8'};
	var id_customer = {$order->id_customer|intval};
	{assign var=PS_TAX_ADDRESS_TYPE value=Configuration::get('PS_TAX_ADDRESS_TYPE')}
	var id_address = {$order->$PS_TAX_ADDRESS_TYPE|escape:'html':'UTF-8'};
	var currency_sign = "{$currency->sign|escape:'html':'UTF-8'}";
	var currency_format = "{$currency->format|escape:'html':'UTF-8'}";
	var currency_blank = "{$currency->blank|escape:'html':'UTF-8'}";
	var priceDisplayPrecision = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};
	var use_taxes = {if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}true{else}false{/if};
	var stock_management = {$stock_management|intval};
	var txt_add_product_stock_issue = "{l s='Are you sure you want to add this quantity?' js=1  mod='g_ordermanager'}";
	var txt_add_product_new_invoice = "{l s='Are you sure you want to create a new invoice?' js=1  mod='g_ordermanager'}";
	var txt_add_product_no_product = "{l s='Error: No product has been selected' js=1  mod='g_ordermanager'}";
	var txt_add_product_no_product_quantity = "{l s='Error: Quantity of products must be set' js=1  mod='g_ordermanager'}";
	var txt_add_product_no_product_price = "{l s='Error: Product price must be set' js=1  mod='g_ordermanager'}";
	var txt_confirm = "{l s='Are you sure?' js=1  mod='g_ordermanager'}";
	var statesShipped = new Array();
	var has_voucher = {if count($discounts)}1{else}0{/if};
	{foreach from=$states item=state}
		{if (isset($currentState->shipped) && !$currentState->shipped && $state['shipped'])}
			statesShipped.push({$state['id_order_state']|escape:'html':'UTF-8'});
		{/if}
	{/foreach}
	var order_discount_price = {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
									{$order->total_discounts_tax_excl|escape:'html':'UTF-8'}
								{else}
									{$order->total_discounts_tax_incl|escape:'html':'UTF-8'}
								{/if};

	var errorRefund = "{l s='Error. You cannot refund a negative amount.'  mod='g_ordermanager'}";
	</script>

	{assign var="hook_invoice" value={hook h="displayInvoice" id_order=$order->id}}
	{if ($hook_invoice)}
	<div>{$hook_invoice nofilter}</div>{* html content, no need to escape *}
	{/if}
	<div class="panel kpi-container order_view_page">
		<div class="row">
			<div class="col-xs-6 col-sm-3 box-stats color3" >
				<div class="kpi-content">
					<i class="icon-calendar-empty"></i>
					<span class="title">{l s='Date' mod='g_ordermanager'}</span>
					<span class="value">{dateFormat date=$order->date_add full=false}</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color4" >
				<div class="kpi-content">
					<i class="icon-money"></i>
					<span class="title">{l s='Total' mod='g_ordermanager'}</span>
					<span class="value">{displayPrice price=$order->total_paid_tax_incl currency=$currency->id}</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color2" >
				<div class="kpi-content">
					<i class="icon-comments"></i>
					<span class="title">{l s='Messages' mod='g_ordermanager'}</span>
					<span class="value"><a href="{$link->getAdminLink('AdminCustomerThreads')|escape:'html':'UTF-8'}&amp;id_order={$order->id|intval}">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</a></span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color1" >
				<a href="#start_products">
					<div class="kpi-content">
						<i class="icon-book"></i>
						<span class="title">{l s='Products' mod='g_ordermanager'}</span>
						<span class="value">{sizeof($products)|escape:'html':'UTF-8'}</span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-7">
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-credit-card"></i>
					{l s='Order' mod='g_ordermanager'}
					
                    <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='Click to change order reference' mod='g_ordermanager'}">
 				        <span class="badge orderreference">
                            {$order->reference|escape:'html':'UTF-8'}
            			</span>
                    </span>
					<span class="badge">{l s='#' mod='g_ordermanager'}{$order->id|escape:'html':'UTF-8'}</span>
					<div class="panel-heading-action">
						<div class="btn-group">
							<a class="btn btn-default{if !$previousOrder} disabled{/if}" href="{$link->getAdminLink('AdminOrdermanager')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$previousOrder|intval}">
								<i class="icon-backward"></i>
							</a>
							<a class="btn btn-default{if !$nextOrder} disabled{/if}" href="{$link->getAdminLink('AdminOrdermanager')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$nextOrder|intval}">
								<i class="icon-forward"></i>
							</a>
						</div>
					</div>
				</div>
                <div class="panel change_reference">
                    <div class="panel-heading">#{l s='Change Reference' mod='g_ordermanager'}</div>
                    <div class="col-lg-6">
                        <div class="row">
                			<div class="col-lg-9">
                                <input type="text" id="reference_{$order->id|escape:'html':'UTF-8'}" value="{$order->reference|escape:'html':'UTF-8'}" />
                                <small class="text-muted">{l s='Maximum 9 charachter.' mod='g_ordermanager'}</small>
                            </div>
                            <div class="col-lg-3">
                                <button rel="{$order->id|escape:'html':'UTF-8'}" type="submit" class="btn btn-default pull-left OrderChangeReference"><i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}</button>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
				<!-- Orders Actions -->
				<div class="well hidden-print">
					<a class="btn btn-default print_order_link" href="submitPrintOrder"  rel="{$order->id|escape:'html':'UTF-8'}">
						<i class="icon-print"></i>
						{l s='Print order' mod='g_ordermanager'}
					</a>
					&nbsp;
					{if Configuration::get('PS_INVOICE') && count($invoices_collection) && $order->invoice_number}
						<a data-selenium-id="view_invoice" class="btn btn-default _blank" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateInvoicePDF&amp;id_order={$order->id|intval}">
							<i class="icon-file"></i>
							{l s='View invoice' mod='g_ordermanager'}
						</a>
					{else}
						<span class="span label label-inactive">
							<i class="icon-remove"></i>
							{l s='No invoice' mod='g_ordermanager'}
						</span>
					{/if}
					&nbsp;
					{if $order->delivery_number}
						<a class="btn btn-default _blank"  href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateDeliverySlipPDF&amp;id_order={$order->id|intval}">
							<i class="icon-truck"></i>
							{l s='View delivery slip' mod='g_ordermanager'}
						</a>
					{else}
						<span class="span label label-inactive">
							<i class="icon-remove"></i>
							{l s='No delivery slip' mod='g_ordermanager'}
						</span>
					{/if}
					&nbsp;
					{if Configuration::get('PS_ORDER_RETURN')}
						<a id="desc-order-standard_refund" class="btn btn-default" href="#refundForm">
							<i class="icon-exchange"></i>
							{if $order->hasBeenShipped()}
								{l s='Return products' mod='g_ordermanager'}
							{elseif $order->hasBeenPaid()}
								{l s='Standard refund' mod='g_ordermanager'}
							{else}
								{l s='Cancel products' mod='g_ordermanager'}
							{/if}
						</a>
						&nbsp;
					{/if}
					{if $order->hasInvoice()}
						<a id="desc-order-partial_refund" class="btn btn-default" href="#refundForm">
							<i class="icon-exchange"></i>
							{l s='Partial refund' mod='g_ordermanager'}
						</a>
					{/if}
                    <a id="desc-order-copy" class="btn btn-default duplicateorder" rel="{$order->id|escape:'html':'UTF-8'}" href="#copyForm">
						<i class="icon-copy"></i>
						{l s='Duplicate order' mod='g_ordermanager'}
					</a>
				</div>
				<!-- Tab nav -->
				<ul class="nav nav-tabs" id="tabOrder">
                    {if isset($HOOK_TAB_ORDER)}
					   {$HOOK_TAB_ORDER nofilter}{* html content, no need to escape *}
                    {/if}
					<li class="active">
						<a href="#status">
							<i class="icon-time"></i>
							{l s='Status' mod='g_ordermanager'} <span class="badge">{$history|@count|escape:'html':'UTF-8'}</span>
						</a>
					</li>
					<li>
						<a href="#documents">
							<i class="icon-file-text"></i>
							{l s='Documents' mod='g_ordermanager'} <span class="badge">{$order->getDocuments()|@count|escape:'html':'UTF-8'}</span>
						</a>
					</li>
				</ul>
				<!-- Tab content -->
				<div class="tab-content panel">
                    {if isset($HOOK_CONTENT_ORDER)}
					   {$HOOK_CONTENT_ORDER nofilter}{* html content, no need to escape *}
                    {/if}
					<!-- Tab status -->
					<div class="tab-pane active" id="status">
						<h4 class="visible-print">{l s='Status' mod='g_ordermanager'} <span class="badge">({$history|@count|escape:'html':'UTF-8'})</span></h4>
						<!-- History of status -->
						<div class="table-responsive order_history_table">
							<table class="table history-status row-margin-bottom">
								<tbody>
									{foreach from=$history item=row key=key}
										{if ($key == 0)}
											<tr class="order_history_{$order->id|intval}_{$row['id_order_history']|intval}">
                                                {assign var="text_color" value='white'}
                                                {if isset($row['text-color'])}
												    {assign var="text_color" value=$row['text-color']}
                                                {/if}
                                                
                                                <td style="background-color:{$row['color']|escape:'html':'UTF-8'}"><img src="../img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" alt="{$row['ostate_name']|stripslashes}" /></td>
												<td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{$row['ostate_name']|stripslashes}</td>
												<td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes} {$row['employee_lastname']|stripslashes}{/if}</td>
												<td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{dateFormat date=$row['date_add'] full=true}</td>
                                                {if isset($versionleftthan161) && $versionleftthan161 =='1'}
                                                {else}
                                                <td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}" class="text-right">
            										{if $row['send_email']|intval}
            											<a class="btn btn-default resend_email" data-idorder="{$order->id|intval}" data-state="{$row['id_order_state']|intval}" data-history="{$row['id_order_history']|intval}" href="#" title="{l s='Resend this email to the customer' mod='g_ordermanager'}">
            												<i class="icon-mail-reply"></i>
            												{l s='Resend email' mod='g_ordermanager'}
            											</a>
            										{/if}
            									</td>
                                                {/if}
                                                <td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}" class="text-right">
                                                    <a class="btn btn-default delete_status_history" href="#" data-idorder="{$order->id|intval}" data-history="{$row['id_order_history']|intval}">
                                                        <i class="icon-trash"></i>
                                                    </a>
                                                </td>
											</tr>
										{else}
											<tr class="order_history_{$order->id|intval}_{$row['id_order_history']|intval}">
												<td><img src="../img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" /></td>
												<td>{$row['ostate_name']|stripslashes|escape:'html':'UTF-8'}</td>
												<td>{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes|escape:'html':'UTF-8'} {$row['employee_lastname']|stripslashes}{else}&nbsp;{/if}</td>
												<td>{dateFormat date=$row['date_add'] full=true}</td>
												{if isset($versionleftthan161) && $versionleftthan161 =='1'}
                                                {else}
                                                <td class="text-right">
            										{if $row['send_email']|intval}
            											<a class="btn btn-default resend_email" data-idorder="{$order->id|intval}" data-state="{$row['id_order_state']|intval}" data-history="{$row['id_order_history']|intval}" href="#" title="{l s='Resend this email to the customer' mod='g_ordermanager'}">
            												<i class="icon-mail-reply"></i>
            												{l s='Resend email' mod='g_ordermanager'}
            											</a>
            										{/if}
            									</td>
                                                {/if}
                                                <td>
                                                    <a class="btn btn-default delete_status_history" href="#" data-idorder="{$order->id|intval}" data-history="{$row['id_order_history']|intval}">
                                                        <i class="icon-trash"></i>
                                                    </a>
                                                </td>
											</tr>
										{/if}
									{/foreach}
								</tbody>
							</table>
						</div>
						<!-- Change status form -->
                        <div class="row">
                            <div class="col-lg-9">
                    			<select id="id_order_state_{$order->id|escape:'html':'UTF-8'}" class="chosen form-control" name="id_order_state">
                    			{foreach from=$states item=state}
                    				<option value="{$state['id_order_state']|intval}"{if isset($currentState) && $state['id_order_state'] == $currentState->id} selected="selected" disabled="disabled"{/if}>{$state['name']|escape}</option>
                    			{/foreach}
                    			</select>
                    		</div>
                    		<div class="col-lg-3">
                    			<button rel="{$order->id|escape:'html':'UTF-8'}"  type="submit" name="submitState" class="btn btn-primary submitChangeState">
                    				{l s='Update status' mod='g_ordermanager'}
                    			</button>
                    		</div>
                        </div>
					</div>
					<!-- Tab documents -->
					<div class="tab-pane" id="documents">
						<h4 class="visible-print">{l s='Documents' mod='g_ordermanager'} <span class="badge">({$order->getDocuments()|@count|escape:'html':'UTF-8'})</span></h4>
						{* Include document template *}
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
                        									<input type="hidden" class="id_order_invoice-note" name="id_order_invoice" value="{$document->id|escape:'html':'UTF-8'}" />
                        									<textarea name="note" id="editNote{$document->id|escape:'html':'UTF-8'}" class="edit-note edit-note-value textarea-autosize">{$document->note|escape:'html':'UTF-8'}</textarea>
                        								</p>
                        								<p>
                        									<button type="button" name="submitEditNote" id="submitEditNote" class="btn btn-default">
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
					</div>
				</div>
				<script>
					$('#tabOrder a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				</script>
				<hr />
				<!-- Tab nav -->
				<ul class="nav nav-tabs" id="myTab">
                    {if isset($HOOK_TAB_SHIP)}
					   {$HOOK_TAB_SHIP nofilter}{* html content, no need to escape *}
                    {/if}
					<li class="active">
						<a href="#shipping">
							<i class="icon-truck "></i>
							{l s='Shipping' mod='g_ordermanager'} <span class="badge">{$order->getShipping()|@count|escape:'html':'UTF-8'}</span>
						</a>
					</li>
					<li>
						<a href="#returns">
							<i class="icon-undo"></i>
							{l s='Merchandise Returns' mod='g_ordermanager'} <span class="badge">{$order->getReturn()|@count|escape:'html':'UTF-8'}</span>
						</a>
					</li>
				</ul>
				<!-- Tab content -->
				<div class="tab-content panel">
                {if isset($HOOK_CONTENT_SHIP)}
				   {$HOOK_CONTENT_SHIP nofilter}{* html content, no need to escape *}
                {/if}
					<!-- Tab shipping -->
					<div class="tab-pane active" id="shipping">
						<h4 class="visible-print">{l s='Shipping' mod='g_ordermanager'} <span class="badge">({$order->getShipping()|@count|escape:'html':'UTF-8'})</span></h4>
						<!-- Shipping block -->
						{if !$order->isVirtual()}
						<div class="form-horizontal">
							{if $order->gift_message}
							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Message' mod='g_ordermanager'}</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$order->gift_message|nl2br|escape:'html':'UTF-8'}</p>
								</div>
							</div>
							{/if}
                            <div id="orderchangeshipping_wp">
                                {hook h='OrderChangeShipping' order=$order}
                            </div>
							{if $carrierModuleCall}
								{$carrierModuleCall nofilter}{* html content, no need to escape *}
							{/if}
							<hr />
							{if $order->recyclable}
								<span class="label label-success"><i class="icon-check"></i> {l s='Recycled packaging' mod='g_ordermanager'}</span>
							{else}
								<span class="label label-inactive"><i class="icon-remove"></i> {l s='Recycled packaging' mod='g_ordermanager'}</span>
							{/if}

							{if $order->gift}
								<span class="label label-success"><i class="icon-check"></i> {l s='Gift wrapping' mod='g_ordermanager'}</span>
							{else}
								<span class="label label-inactive"><i class="icon-remove"></i> {l s='Gift wrapping' mod='g_ordermanager'}</span>
							{/if}
						</div>
						{/if}
					</div>
					<!-- Tab returns -->
					<div class="tab-pane" id="returns">
						<h4 class="visible-print">{l s='Merchandise Returns' mod='g_ordermanager'} <span class="badge">({$order->getReturn()|@count|escape:'html':'UTF-8'})</span></h4>
						{if !$order->isVirtual()}
						<!-- Return block -->
							{if $order->getReturn()|count > 0}
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th><span class="title_box ">{l s='Date' mod='g_ordermanager'}</span></th>
											<th><span class="title_box ">{l s='Type' mod='g_ordermanager'}</span></th>
											<th><span class="title_box ">{l s='Carrier' mod='g_ordermanager'}</span></th>
											<th><span class="title_box ">{l s='Tracking number' mod='g_ordermanager'}</span></th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$order->getReturn() item=line}
										<tr>
											<td>{$line.date_add|escape:'html':'UTF-8'}</td>
											<td>{$line.type|escape:'html':'UTF-8'}</td>
											<td>{$line.state_name|escape:'html':'UTF-8'}</td>
											<td class="actions">
												<span class="shipping_number_show">{if isset($line.url) && isset($line.tracking_number)}<a href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number|escape:'html':'UTF-8'}</a>{elseif isset($line.tracking_number)}{$line.tracking_number|escape:'html':'UTF-8'}{/if}</span>
												{if $line.can_edit}
												<form method="post" action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|intval}&amp;id_order_invoice={if $line.id_order_invoice}{$line.id_order_invoice|intval}{else}0{/if}&amp;id_carrier={if $line.id_carrier}{$line.id_carrier|escape:'html':'UTF-8'}{else}0{/if}">
													<span class="shipping_number_edit" style="display:none;">
														<button type="button" name="tracking_number">
															{$line.tracking_number|htmlentities|escape:'html':'UTF-8'}
														</button>
														<button type="submit" class="btn btn-default" name="submitShippingNumber">
															{l s='Update' mod='g_ordermanager'}
														</button>
													</span>
													<button href="#" class="edit_shipping_number_link">
														<i class="icon-pencil"></i>
														{l s='Edit' mod='g_ordermanager'}
													</button>
													<button href="#" class="cancel_shipping_number_link" style="display: none;">
														<i class="icon-remove"></i>
														{l s='Cancel' mod='g_ordermanager'}
													</button>
												</form>
												{/if}
											</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
							{else}
							<div class="list-empty hidden-print">
								<div class="list-empty-msg">
									<i class="icon-warning-sign list-empty-icon"></i>
									{l s='No merchandise returned yet' mod='g_ordermanager'}
								</div>
							</div>
							{/if}
							{if $carrierModuleCall}
								{$carrierModuleCall nofilter}{* html content, no need to escape *}
							{/if}
						{/if}
					</div>
				</div>
				<script>
					$('#myTab a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				</script>
			</div>
			<!-- Payments block -->
            <div id="formAddPaymentPanel"  class="panel change_payment">
            	<div class="panel-heading">
            		<i class="icon-money"></i>
            		{l s='Payment' mod='g_ordermanager'}
            	</div>
                <div class="payment_table">
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
                </div>
            	{if (!$order->valid && sizeof($currencies) > 1)}
            		<form class="form-horizontal well" method="post" action="{$currentIndex|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
            			<div class="row">
            				<label class="control-label col-lg-3">{l s='Change currency' mod='g_ordermanager'}</label>
            				<div class="col-lg-6">
            					<select name="new_currency">
            					{foreach from=$currencies item=currency_change}
            						{if $currency_change['id_currency'] != $order->id_currency}
            						<option value="{$currency_change['id_currency']|escape:'html':'UTF-8'}">{$currency_change['name']|escape:'html':'UTF-8'} - {$currency_change['sign']|escape:'html':'UTF-8'}</option>
            						{/if}
            					{/foreach}
            					</select>
            					<p class="help-block">{l s='Do not forget to update your exchange rate before making this change.' mod='g_ordermanager'}</p>
            				</div>
            				<div class="col-lg-3">
            					<button type="submit" class="btn btn-default" name="submitChangeCurrency"><i class="icon-refresh"></i> {l s='Change' mod='g_ordermanager'}</button>
            				</div>
            			</div>
            		</form>
            	{/if}
            </div>
			{hook h="displayAdminOrderLeft" id_order=$order->id}
		</div>
		<div class="col-lg-5">
			<!-- Customer informations -->
			<div class="panel">
				{if $customer->id}
					<div class="panel-heading">
						<i class="icon-user"></i>
						{l s='Customer' mod='g_ordermanager'}
						<span class="badge">
							<a href="?tab=AdminCustomers&amp;id_customer={$customer->id|escape:'html':'UTF-8'}&amp;viewcustomer&amp;token={getAdminToken tab='AdminCustomers'}">
								{if Configuration::get('PS_B2B_ENABLE')}{$customer->company|escape:'html':'UTF-8'} - {/if}
								{$gender->name|escape:'html':'UTF-8'}
								{$customer->firstname|escape:'html':'UTF-8'}
								{$customer->lastname|escape:'html':'UTF-8'}
							</a>
						</span>
						<span class="badge">
							{l s='#' mod='g_ordermanager'}{$customer->id|escape:'html':'UTF-8'}
						</span>
					</div>
					<div class="row">
						<div class="col-xs-6">
							{if ($customer->isGuest())}
								{l s='This order has been placed by a guest.' mod='g_ordermanager'}
								{if (!Customer::customerExists($customer->email))}
									<form method="post" action="index.php?tab=AdminCustomers&amp;id_customer={$customer->id|escape:'html':'UTF-8'}&amp;id_order={$order->id|intval}&amp;token={getAdminToken tab='AdminCustomers'}">
										<input type="hidden" name="id_lang" value="{$order->id_lang|escape:'html':'UTF-8'}" />
										<input class="btn btn-default" type="submit" name="submitGuestToCustomer" value="{l s='Transform a guest into a customer' mod='g_ordermanager'}" />
										<p class="help-block">{l s='This feature will generate a random password and send an email to the customer.' mod='g_ordermanager'}</p>
									</form>
								{else}
									<div class="alert alert-warning">
										{l s='A registered customer account has already claimed this email address' mod='g_ordermanager'}
									</div>
								{/if}
							{else}
								<dl class="well list-detail">
									<dt>{l s='Email' mod='g_ordermanager'}</dt>
										<dd><a href="mailto:{$customer->email|escape:'html':'UTF-8'}"><i class="icon-envelope-o"></i> {$customer->email|escape:'html':'UTF-8'}</a></dd>
									<dt>{l s='Account registered' mod='g_ordermanager'}</dt>
										<dd class="text-muted"><i class="icon-calendar-o"></i> {dateFormat date=$customer->date_add full=true}</dd>
									<dt>{l s='Valid orders placed' mod='g_ordermanager'}</dt>
										<dd><span class="badge">{$customerStats['nb_orders']|intval}</span></dd>
									<dt>{l s='Total spent since registration' mod='g_ordermanager'}</dt>
										<dd><span class="badge badge-success">{displayPrice price=Tools::ps_round(Tools::convertPrice($customerStats['total_orders'], $currency), 2) currency=$currency->id}</span></dd>
									{if Configuration::get('PS_B2B_ENABLE')}
										<dt>{l s='Siret' mod='g_ordermanager'}</dt>
											<dd>{$customer->siret|escape:'html':'UTF-8'}</dd>
										<dt>{l s='APE' mod='g_ordermanager'}</dt>
											<dd>{$customer->ape|escape:'html':'UTF-8'}</dd>
									{/if}
								</dl>
							{/if}
						</div>

						<div class="col-xs-6">
							<div class="form-group hidden-print">
								<a href="?tab=AdminCustomers&amp;id_customer={$customer->id|escape:'html':'UTF-8'}&amp;viewcustomer&amp;token={getAdminToken tab='AdminCustomers'}" class="btn btn-default btn-block">{l s='View full details...' mod='g_ordermanager'}</a>
							</div>
							<div class="panel panel-sm">
								<div class="panel-heading">
									<i class="icon-eye-slash"></i>
									{l s='Private note' mod='g_ordermanager'}
								</div>
								<form id="customer_note" class="form-horizontal" action="{$current_index|escape:'html':'UTF-8'}&amp;viewOrder&amp;id_order={$order->id|escape:'html':'UTF-8'}{if isset($smarty.get.token)}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}{/if}" method="post" onsubmit="saveCustomerNote({$customer->id|escape:'html':'UTF-8'});return false;" >
									<div class="form-group">
										<div class="col-lg-12">
											<textarea name="note" id="noteContent" class="textarea-autosize" onkeyup="$(this).val().length > 0 ? $('#submitCustomerNote').removeAttr('disabled') : $('#submitCustomerNote').attr('disabled', 'disabled')">{$customer->note|escape:'html':'UTF-8'}</textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<button type="button" id="submitCustomerNote" class="btn btn-default pull-right" disabled="disabled">
												<i class="icon-save"></i>
												{l s='Save' mod='g_ordermanager'}
											</button>
										</div>
									</div>
									<span id="note_feedback"></span>
								</form>
							</div>
						</div>
					</div>
				{/if}
				<!-- Tab nav -->
				<div class="row">
					<ul class="nav nav-tabs" id="tabAddresses">
						<li class="active">
							<a href="#addressShipping">
								<i class="icon-truck"></i>
								{l s='Shipping address' mod='g_ordermanager'}
							</a>
						</li>
						<li>
							<a href="#addressInvoice">
								<i class="icon-file-text"></i>
								{l s='Invoice address' mod='g_ordermanager'}
							</a>
						</li>
					</ul>
					<!-- Tab content -->
					<div class="tab-content panel">
						<!-- Tab status -->
						<div class="tab-pane  in active" id="addressShipping">
							<!-- Addresses -->
							<h4 class="visible-print">{l s='Shipping address' mod='g_ordermanager'}</h4>
							{if !$order->isVirtual()}
							<!-- Shipping address -->
								{if $can_edit}
                                <div class="form-horizontal hidden-print">
                                        <div class="form-group">
											<div class="col-lg-9">
                                                <select name="id_address" id="shipping_id_address_{$order->id|intval}">
													{foreach from=$customer_addresses item=address}
													<option value="{$address['id_address']|escape:'html':'UTF-8'}"
														{if $address['id_address'] == $order->id_address_delivery}
															selected="selected"
														{/if}>
														{$address['alias']|escape:'html':'UTF-8'} -
														{$address['address1']|escape:'html':'UTF-8'}
														{$address['postcode']|escape:'html':'UTF-8'}
														{$address['city']|escape:'html':'UTF-8'}
														{if !empty($address['state'])}
															{$address['state']|escape:'html':'UTF-8'}
														{/if},
														{$address['country']|escape:'html':'UTF-8'}
													</option>
													{/foreach}
												</select>
											</div>
											<div class="col-lg-3">
                                                <button class="btn btn-default submitAddressShipping" data-type="shipping" data-id_order="{$order->id|intval}"><i class="icon-refresh"></i> {l s='Change' mod='g_ordermanager'}</button>
                                            </div>
										</div>
                                    </div>
								{/if}
								<div class="well">
									<div class="row">
										<div class="col-sm-6">
											<a  id="edit_delivery_address_{$order->id|intval}"  class="btn btn-default pull-right ifancybox" href="{$link->getAdminLink('AdminAddresses')|escape:'html':'UTF-8'}&id_address={$addresses.delivery->id|escape:'html':'UTF-8'}&id_order={$order->id|escape:'html':'UTF-8'}&address_type=1&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back={$adminaddresseslink|urlencode}#">
												<i class="icon-pencil"></i>
												{l s='Edit' mod='g_ordermanager'}
											</a>
                                            <div id="shipping_address_text_{$order->id|intval}">
    											{displayAddressDetail address=$addresses.delivery newLine='<br />'}
    											{if $addresses.delivery->other}
    												<hr />{$addresses.delivery->other|escape:'html':'UTF-8'}<br />
    											{/if}
                                            </div>
										</div>
										<div class="col-sm-6 hidden-print">
											<div id="map-delivery-canvas-{$order->id|escape:'html':'UTF-8'}" style="height: 190px"></div>
										</div>
									</div>
								</div>
							{/if}
						</div>
						<div class="tab-pane " id="addressInvoice">
							<!-- Invoice address -->
							<h4 class="visible-print">{l s='Invoice address' mod='g_ordermanager'}</h4>
							{if $can_edit}
                                <div class="form-horizontal hidden-print">
                                	<div class="form-group">
										<div class="col-lg-9">
                                            <select name="id_address" id="invoice_id_address_{$order->id|intval}">
												{foreach from=$customer_addresses item=address}
												<option value="{$address['id_address']|escape:'html':'UTF-8'}"
													{if $address['id_address'] == $order->id_address_invoice}
													selected="selected"
													{/if}>
													{$address['alias']|escape:'html':'UTF-8'} -
													{$address['address1']|escape:'html':'UTF-8'}
													{$address['postcode']|escape:'html':'UTF-8'}
													{$address['city']|escape:'html':'UTF-8'}
													{if !empty($address['state'])}
														{$address['state']|escape:'html':'UTF-8'}
													{/if},
													{$address['country']|escape:'html':'UTF-8'}
												</option>
												{/foreach}
											</select>
										</div>
										<div class="col-lg-3">
										    <button class="btn btn-default submitAddressShipping" data-type="invoice"  data-id_order="{$order->id|intval}"  ><i class="icon-refresh"></i> {l s='Change' mod='g_ordermanager'}</button>
                                        </div>
									</div>
                                </div>
							{/if}
							<div class="well">
								<div class="row">
									<div class="col-sm-6">
										<a id="edit_invoice_address_{$order->id|intval}" class="btn btn-default pull-right ifancybox" href="{$link->getAdminLink('AdminAddresses')|escape:'html':'UTF-8'}&id_address={$addresses.invoice->id|escape:'html':'UTF-8'}&id_order={$order->id|escape:'html':'UTF-8'}&address_type=2&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back={$adminaddresseslink|urlencode}#">
											<i class="icon-pencil"></i>
											{l s='Edit' mod='g_ordermanager'}
										</a>
                                        <div id="invoice_address_text_{$order->id|intval}">
    										{displayAddressDetail address=$addresses.invoice newLine='<br />'}
    										{if $addresses.invoice->other}
    											<hr />{$addresses.invoice->other|escape:'html':'UTF-8'}<br />
    										{/if}
                                        </div>
									</div>
									<div class="col-sm-6 hidden-print">
										<div id="map-invoice-canvas-{$order->id|escape:'html':'UTF-8'}" style="height: 190px"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<script>
					$('#tabAddresses a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				</script>
			</div>
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-envelope"></i> {l s='Messages' mod='g_ordermanager'} <span class="badge">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</span>
				</div>
				{if (sizeof($messages))}
					<div class="panel panel-highlighted">
						<div class="message-item">
							{foreach from=$messages item=message}
								<div class="message-avatar">
									<div class="avatar-md">
										<i class="icon-user icon-2x"></i>
									</div>
								</div>
								<div class="message-body">

									<span class="message-date">&nbsp;<i class="icon-calendar"></i>
										{dateFormat date=$message['date_add']} -
									</span>
									<h4 class="message-item-heading">
										{if ($message['elastname']|escape:'html':'UTF-8')}{$message['efirstname']|escape:'html':'UTF-8'}
											{$message['elastname']|escape:'html':'UTF-8'}{else}{$message['cfirstname']|escape:'html':'UTF-8'} {$message['clastname']|escape:'html':'UTF-8'}
										{/if}
										{if ($message['private'] == 1)}
											<span class="badge badge-info">{l s='Private' mod='g_ordermanager'}</span>
										{/if}
									</h4>
									<p class="message-item-text">
										{$message['message']|escape:'html':'UTF-8'|nl2br}
									</p>
								</div>
							{/foreach}
						</div>
					</div>
				{/if}
				<div id="messages" class="well hidden-print">
					<form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}" method="post" onsubmit="if (getE('visibility').checked == true) return confirm('{l s='Do you want to send this message to the customer?' mod='g_ordermanager'}');">
						<div id="message" class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Choose a standard message' mod='g_ordermanager'}</label>
								<div class="col-lg-9">
									<select class="chosen form-control" name="order_message" id="order_message" onchange="orderOverwriteMessage(this, '{l s='Do you want to overwrite your existing message?' mod='g_ordermanager'}')">
										<option value="0" selected="selected">-</option>
										{foreach from=$orderMessages item=orderMessage}
										<option value="{$orderMessage['message']|escape:'html':'UTF-8'}">{$orderMessage['name']|escape:'html':'UTF-8'}</option>
										{/foreach}
									</select>
									<p class="help-block">
										<a href="{$link->getAdminLink('AdminOrderMessage')|escape:'html':'UTF-8'}">
											{l s='Configure predefined messages' mod='g_ordermanager'}
											<i class="icon-external-link"></i>
										</a>
									</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Display to customer?' mod='g_ordermanager'}</label>
								<div class="col-lg-9">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="visibility" id="visibility_on" value="0" />
										<label for="visibility_on">
											{l s='Yes' mod='g_ordermanager'}
										</label>
										<input type="radio" name="visibility" id="visibility_off" value="1" checked="checked" />
										<label for="visibility_off">
											{l s='No' mod='g_ordermanager'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Message' mod='g_ordermanager'}</label>
								<div class="col-lg-9">
									<textarea id="txt_msg" class="textarea-autosize" name="message">{Tools::getValue('message')|escape:'html':'UTF-8'}</textarea>
									<p id="nbchars"></p>
								</div>
							</div>


							<input type="hidden" name="id_order" value="{$order->id|escape:'html':'UTF-8'}" />
							<input type="hidden" name="id_customer" value="{$order->id_customer|escape:'html':'UTF-8'}" />
							<button type="submit" id="submitMessage2" class="btn btn-primary pull-right" name="submitMessage">
								{l s='Send message' mod='g_ordermanager'}
							</button>
							<a class="btn btn-default" href="{$link->getAdminLink('AdminCustomerThreads')|escape:'html':'UTF-8'}&amp;id_order={$order->id|intval}">
								{l s='Show all messages' mod='g_ordermanager'}
								<i class="icon-external-link"></i>
							</a>
						</div>
					</form>
				</div>
			</div>
			{hook h="displayAdminOrderRight" id_order=$order->id}
		</div>
	</div>
	{hook h="displayAdminOrder" id_order=$order->id}
	<div class="row" id="start_products">
		<div class="col-lg-12">
			<form class="container-command-top-spacing" action="{$current_index|escape:'html':'UTF-8'}&amp;vieworder&amp;token={$smarty.get.token|escape:'html':'UTF-8'}&amp;id_order={$order->id|intval}" method="post" onsubmit="return orderDeleteProduct('{l s='This product cannot be returned.' mod='g_ordermanager'}', '{l s='Quantity to cancel is greater than quantity available.' mod='g_ordermanager'}');">
				<input type="hidden" name="id_order" value="{$order->id|escape:'html':'UTF-8'}" />
				<div style="display: none">
					<input type="hidden" value="{$order->getWarehouseList()|implode|escape:'html':'UTF-8'}" id="warehouse_list" />
				</div>

				<div class="panel">
					<div class="panel-heading">
						<i class="icon-shopping-cart"></i>
						{l s='Products' mod='g_ordermanager'} <span class="badge">{$products|@count|escape:'html':'UTF-8'}</span>
					</div>
					<div id="refundForm">
					<!--
						<a href="#" class="standard_refund"><img src="../img/admin/add.gif" alt="{l s='Process a standard refund' mod='g_ordermanager'}" /> {l s='Process a standard refund' mod='g_ordermanager'}</a>
						<a href="#" class="partial_refund"><img src="../img/admin/add.gif" alt="{l s='Process a partial refund' mod='g_ordermanager'}" /> {l s='Process a partial refund' mod='g_ordermanager'}</a>
					-->
					</div>

					{capture "TaxMethod"}
						{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
							{l s='tax excluded.' mod='g_ordermanager'}
						{else}
							{l s='tax included.' mod='g_ordermanager'}
						{/if}
					{/capture}
					{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
						<input type="hidden" name="TaxMethod" value="0">
					{else}
						<input type="hidden" name="TaxMethod" value="1">
					{/if}
					<div class="table-responsive">
						<table class="table" id="orderProducts">
							<thead>
								<tr>
									<th></th>
									<th><span class="title_box ">{l s='Product' mod='g_ordermanager'}</span></th>
									<th>
										<span class="title_box ">{l s='Unit Price' mod='g_ordermanager'}</span>
										<small class="text-muted">{$smarty.capture.TaxMethod|escape:'html':'UTF-8'}</small>
									</th>
									<th class="text-center"><span class="title_box ">{l s='Qty' mod='g_ordermanager'}</span></th>
									{if $display_warehouse}<th><span class="title_box ">{l s='Warehouse' mod='g_ordermanager'}</span></th>{/if}
									{if ($order->hasBeenPaid())}<th class="text-center"><span class="title_box ">{l s='Refunded' mod='g_ordermanager'}</span></th>{/if}
									{if ($order->hasBeenDelivered() || $order->hasProductReturned())}
										<th class="text-center"><span class="title_box ">{l s='Returned' mod='g_ordermanager'}</span></th>
									{/if}
									{if isset($stock_management) &&  $stock_management}<th class="text-center"><span class="title_box ">{l s='Available quantity' mod='g_ordermanager'}</span></th>{/if}
									<th>
										<span class="title_box ">{l s='Total' mod='g_ordermanager'}</span>
										<small class="text-muted">{$smarty.capture.TaxMethod|escape:'html':'UTF-8'}</small>
									</th>
									<th style="display: none;" class="add_product_fields"></th>
									<th style="display: none;" class="edit_product_fields"></th>
									<th style="display: none;" class="standard_refund_fields">
										<i class="icon-minus-sign"></i>
										{if ($order->hasBeenDelivered() || $order->hasBeenShipped())}
											{l s='Return' mod='g_ordermanager'}
										{elseif ($order->hasBeenPaid())}
											{l s='Refund' mod='g_ordermanager'}
										{else}
											{l s='Cancel' mod='g_ordermanager'}
										{/if}
									</th>
									<th style="display:none" class="partial_refund_fields">
										<span class="title_box ">{l s='Partial refund' mod='g_ordermanager'}</span>
									</th>
									{if !$order->hasBeenDelivered()}
									<th></th>
									{/if}
								</tr>
							</thead>
							<tbody>
                            {foreach from=$products item=product key=k}
								{* Include customized datas partial *}
								{include file='../../_customized_data.tpl'}
								{* Include product line partial *}
								{include file='../../_product_line.tpl'}
							{/foreach}
							{if $can_edit}
								{include file='../../_new_product.tpl'}
							{/if}
							</tbody>
						</table>
					</div>

					{if $can_edit}
					<div class="row-margin-bottom row-margin-top order_action">
						<button type="button" id="add_product" class="btn btn-default">
							<i class="icon-plus-sign"></i>
							{l s='Add a product' mod='g_ordermanager'}
						</button>
						<button id="add_voucher" class="btn btn-default" type="button" >
							<i class="icon-ticket"></i>
							{l s='Add a new discount' mod='g_ordermanager'}
						</button>
					</div>
					{/if}
					<div class="clear">&nbsp;</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="alert alert-warning">
								{l s='For this customer group, prices are displayed as: [1]%s[/1]' sprintf=[$smarty.capture.TaxMethod] tags=['<strong>'] mod='g_ordermanager'}
								{if !Configuration::get('PS_ORDER_RETURN')}
									<br/><strong>{l s='Merchandise returns are disabled' mod='g_ordermanager'}</strong>
								{/if}
							</div>
                            <div class="current-edit" id="voucher_form" style="display:none;">
                                {include file='../../_discount_form.tpl'}
							</div>
                            <div id="order-note" class="panel">
                                <h1 class="panel-heading">{l s='Order Notes' mod='g_ordermanager'}</h1>
                                <div class="order-note-history">
                                    <div class="panel panel-highlighted">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{l s='Notes' mod='g_ordermanager'}</th>
                                                    <th>{l s='Show in pdf' mod='g_ordermanager'}</th>
                                                    <th>{l s='Date add' mod='g_ordermanager'}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {if isset($order_notes) && $order_notes}
                                                {foreach $order_notes as $order_note}
                                                    <tr id="note_{$order_note.id_gordernotes|intval}">
                                                        <td  class="noteval">{$order_note.notes|escape:'html':'UTF-8'}</td>
                                                        <td><input type="checkbox" name="showinpdf[{$order_note.id_gordernotes|intval}]" {if $order_note.showinpdf} checked="checked" {/if} value="1" /></td>
                                                        <td>{$order_note.date_add|escape:'html':'UTF-8'}</td>
                                                        <td>
                                                            <button class="editordernote btn btn-default" rel="{$order_note.id_gordernotes|intval}"><i class="icon-pencil"></i></button>
                                                            <button class="deleteordernote btn btn-default" rel="{$order_note.id_gordernotes|intval}"><i class="icon-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                            {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                <div class="order-note-form  well hidden-print">
                                    <p class="form-group">
                                        <label class="control-label col-lg-3">{l s='Note' mod='g_ordermanager'}</label>
                                        <input type="hidden" value="" id="id_gordernotes" value="" autocomplete="off" />
                                        <div class="col-lg-9">
                                            <textarea name="order_note" class="textarea-autosize" id="order_note"></textarea>
                                        </div>
                                    </p>
                                    <div class="form-group">
        								<label class="control-label col-lg-3">{l s='Show in pdf?' mod='g_ordermanager'}</label>
        								<div class="col-lg-9">
        									<span class="switch prestashop-switch fixed-width-lg">
        										<input type="radio" name="ordernotepdf" id="ordernotepdf_on" value="1" />
        										<label for="ordernotepdf_on">
        											{l s='Yes' mod='g_ordermanager'}
        										</label>
        										<input type="radio" name="ordernotepdf" id="ordernotepdf_off" value="0" checked="checked" />
        										<label for="ordernotepdf_off">
        											{l s='No' mod='g_ordermanager'}
        										</label>
        										<a class="slide-button btn"></a>
        									</span>
        								</div>
        							</div>
                                    <button type="button" id="submitOrderNote" class="btn btn-primary pull-right" name="submitOrderNote">
        								<span class="add_label">{l s='Add' mod='g_ordermanager'}</span>
                                        <span class="edit_label">{l s='Edit' mod='g_ordermanager'}</span>
        							</button>
                                    <button type="button" class="btn btn-default" id="cancel_editnote">{l s='Cancel' mod='g_ordermanager'}</button>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
						</div>
						<div class="col-xs-6">
                            <div id="panel-vouchers-wp">
                                {include file='../../_discount_val.tpl'}
                            </div>
							<div class="panel panel-total">
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
							</div>
						</div>
					</div>
					<div style="display: none;" class="standard_refund_fields form-horizontal panel">
						<div class="form-group">
							{if ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN'))}
							<p class="checkbox">
								<label for="reinjectQuantities">
									<input type="checkbox" id="reinjectQuantities" name="reinjectQuantities" />
									{l s='Re-stock products' mod='g_ordermanager'}
								</label>
							</p>
							{/if}
							{if ((!$order->hasBeenDelivered() && $order->hasBeenPaid()) || ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))}
							<p class="checkbox">
								<label for="generateCreditSlip">
									<input type="checkbox" id="generateCreditSlip" name="generateCreditSlip" onclick="toggleShippingCost()" />
									{l s='Generate a credit slip' mod='g_ordermanager'}
								</label>
							</p>
							<p class="checkbox">
								<label for="generateDiscount">
									<input type="checkbox" id="generateDiscount" name="generateDiscount" onclick="toggleShippingCost()" />
									{l s='Generate a voucher' mod='g_ordermanager'}
								</label>
							</p>
							<p class="checkbox" id="spanShippingBack" style="display:none;">
								<label for="shippingBack">
									<input type="checkbox" id="shippingBack" name="shippingBack" />
									{l s='Repay shipping costs' mod='g_ordermanager'}
								</label>
							</p>
							{if $order->total_discounts_tax_excl > 0 || $order->total_discounts_tax_incl > 0}
							<br/><p>{l s='This order has been partially paid by voucher. Choose the amount you want to refund:' mod='g_ordermanager'}</p>
							<p class="radio">
								<label id="lab_refund_total_1" for="refund_total_1">
									<input type="radio" value="0" name="refund_total_voucher_off" id="refund_total_1" checked="checked" />
									{l s='Include amount of initial voucher: ' mod='g_ordermanager'}
								</label>
							</p>
							<p class="radio">
								<label id="lab_refund_total_2" for="refund_total_2">
									<input type="radio" value="1" name="refund_total_voucher_off" id="refund_total_2"/>
									{l s='Exclude amount of initial voucher: ' mod='g_ordermanager'}
								</label>
							</p>
							<div class="nowrap radio-inline">
								<label id="lab_refund_total_3" class="pull-left" for="refund_total_3">
									{l s='Amount of your choice: ' mod='g_ordermanager'}
									<input type="radio" value="2" name="refund_total_voucher_off" id="refund_total_3"/>
								</label>
								<div class="input-group col-lg-1 pull-left">
									<div class="input-group-addon">
										{$currency->prefix|escape:'html':'UTF-8'}
										{$currency->suffix|escape:'html':'UTF-8'}
									</div>
									<input type="text" class="input fixed-width-md" name="refund_total_voucher_choose" value="0"/>
								</div>
							</div>
							{/if}
						{/if}
						</div>
						{if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))}
						<div class="row">
							<input type="submit" name="cancelProduct" value="{if $order->hasBeenDelivered()}{l s='Return products' mod='g_ordermanager'}{elseif $order->hasBeenPaid()}{l s='Refund products' mod='g_ordermanager'}{else}{l s='Cancel products' mod='g_ordermanager'}{/if}" class="btn btn-default" />
						</div>
						{/if}
					</div>
					<div style="display:none;" class="partial_refund_fields">
						<p class="checkbox">
							<label for="reinjectQuantitiesRefund">
								<input type="checkbox" id="reinjectQuantitiesRefund" name="reinjectQuantities" />
								{l s='Re-stock products' mod='g_ordermanager'}
							</label>
						</p>
						<p class="checkbox">
							<label for="generateDiscountRefund">
								<input type="checkbox" id="generateDiscountRefund" name="generateDiscountRefund" onclick="toggleShippingCost()" />
								{l s='Generate a voucher' mod='g_ordermanager'}
							</label>
						</p>
						{if $order->total_discounts_tax_excl > 0 || $order->total_discounts_tax_incl > 0}
						<p>{l s='This order has been partially paid by voucher. Choose the amount you want to refund:' mod='g_ordermanager'}</p>
						<p class="radio">
							<label id="lab_refund_1" for="refund_1">
								<input type="radio" value="0" name="refund_voucher_off" id="refund_1" checked="checked" />
								{l s='Product(s) price: ' mod='g_ordermanager'}
							</label>
						</p>
						<p class="radio">
							<label id="lab_refund_2" for="refund_2">
								<input type="radio" value="1" name="refund_voucher_off" id="refund_2"/>
								{l s='Product(s) price, excluding amount of initial voucher: ' mod='g_ordermanager'}
							</label>
						</p>
						<div class="nowrap radio-inline">
								<label id="lab_refund_3" class="pull-left" for="refund_3">
									{l s='Amount of your choice: ' mod='g_ordermanager'}
									<input type="radio" value="2" name="refund_voucher_off" id="refund_3"/>
								</label>
								<div class="input-group col-lg-1 pull-left">
									<div class="input-group-addon">
										{$currency->prefix|escape:'html':'UTF-8'}
										{$currency->suffix|escape:'html':'UTF-8'}
									</div>
									<input type="text" class="input fixed-width-md" name="refund_voucher_choose" value="0"/>
								</div>
							</div>
						{/if}
						<br/>
						<button type="submit" name="partialRefund" class="btn btn-default">
							<i class="icon-check"></i> {l s='Partial refund' mod='g_ordermanager'}
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<!-- Sources block -->
			{if (sizeof($sources))}
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-globe"></i>
					{l s='Sources' mod='g_ordermanager'} <span class="badge">{$sources|@count|escape:'html':'UTF-8'}</span>
				</div>
				<ul {if sizeof($sources) > 3}style="height: 200px; overflow-y: scroll;"{/if}>
				{foreach from=$sources item=source}
					<li>
						{dateFormat date=$source['date_add'] full=true}<br />
						<b>{l s='From' mod='g_ordermanager'}</b>{if $source['http_referer'] != ''}<a href="{$source['http_referer']|escape:'html':'UTF-8'}">{parse_url($source['http_referer'], $smarty.const.PHP_URL_HOST)|regex_replace:'/^www./':''|escape:'html':'UTF-8'}</a>{else}-{/if}<br />
						<b>{l s='To' mod='g_ordermanager'}</b> <a href="http://{$source['request_uri']|escape:'html':'UTF-8'}">{$source['request_uri']|truncate:100:'...'|escape:'html':'UTF-8'}</a><br />
						{if $source['keywords']}<b>{l s='Keywords' mod='g_ordermanager'}</b> {$source['keywords']|escape:'html':'UTF-8'}<br />{/if}<br />
					</li>
				{/foreach}
				</ul>
			</div>
			{/if}

			<!-- linked orders block -->
			{if count($order->getBrother()) > 0}
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-cart"></i>
					{l s='Linked orders' mod='g_ordermanager'}
				</div>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>
									{l s='Order no. ' mod='g_ordermanager'}
								</th>
								<th>
									{l s='Status' mod='g_ordermanager'}
								</th>
								<th>
									{l s='Amount' mod='g_ordermanager'}
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							{foreach $order->getBrother() as $brother_order}
							<tr>
								<td>
									<a href="{$current_index|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$brother_order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">#{$brother_order->id|escape:'html':'UTF-8'}</a>
								</td>
								<td>
									{$brother_order->getCurrentOrderState()->name[$current_id_lang]|escape:'html':'UTF-8'}
								</td>
								<td>
									{displayPrice price=$brother_order->total_paid_tax_incl currency=$currency->id}
								</td>
								<td>
									<a href="{$current_index|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$brother_order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
										<i class="icon-eye-open"></i>
										{l s='See the order' mod='g_ordermanager'}
									</a>
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</div>
			{/if}
		</div>
	</div>

	<script type="text/javascript">
		var geocoder = new google.maps.Geocoder();
		var delivery_map, invoice_map;

		$(document).ready(function()
		{
            //tung update 14/12/2021 note invoice
            $('#submitEditNote').click(function(){
                $.ajax({
                    type:"POST",
                    url: $(this).closest('form').attr('action'),
                    dataType: "json",
                    data : {
                        note:$(this).closest('form').find('.edit-note-value').val(),
                        id_order_invoice: $(this).closest('form').find('.id_order_invoice-note').val(),
                        submitEditNote: '',
                        },
                    success : function(res)
                    {
                        if(res==true) showSuccessMessage('saved');
                        else showSuccessMessage('error');
                    }
                });
            });
            //
            //tung update 14/12/2021 customer note
            
            $('#submitCustomerNote').click(function(){
                $.ajax({
                    type:"POST",
                    url: $(this).closest('form').attr('action'),
                    dataType: "json",
                    data : {
                        noteContent:$(this).closest('form').find('#noteContent').val(),
                        submitCustomerNote: '',
                        },
                    success : function(res)
                    {
                        if(res==true) showSuccessMessage('saved');
                        else showSuccessMessage('error');
                    }
                });
            });

			$(".textarea-autosize").autosize();

			geocoder.geocode({
				address: '{$addresses.delivery->address1|@addcslashes:'\''},{$addresses.delivery->postcode|@addcslashes:'\''|escape:'html':'UTF-8'},{$addresses.delivery->city|@addcslashes:'\''|escape:'html':'UTF-8'}{if isset($addresses.deliveryState->name) && $addresses.delivery->id_state},{$addresses.deliveryState->name|@addcslashes:'\''|escape:'html':'UTF-8'}{/if},{$addresses.delivery->country|@addcslashes:'\''|escape:'html':'UTF-8'}'
				}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK)
				{
					delivery_map = new google.maps.Map(document.getElementById('map-delivery-canvas-{$order->id|escape:'html':'UTF-8'}'), {
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						center: results[0].geometry.location
					});
					var delivery_marker = new google.maps.Marker({
						map: delivery_map,
						position: results[0].geometry.location,
						url: 'http://maps.google.com?q={$addresses.delivery->address1|urlencode|escape:'html':'UTF-8'},{$addresses.delivery->postcode|urlencode|escape:'html':'UTF-8'},{$addresses.delivery->city|urlencode|escape:'html':'UTF-8'}{if isset($addresses.deliveryState->name) && $addresses.delivery->id_state},{$addresses.deliveryState->name|urlencode|escape:'html':'UTF-8'}{/if},{$addresses.delivery->country|urlencode|escape:'html':'UTF-8'}'
					});
					google.maps.event.addListener(delivery_marker, 'click', function() {
						window.open(delivery_marker.url);
					});
				}
			});

			geocoder.geocode({
				address: '{$addresses.invoice->address1|@addcslashes:'\''|escape:'html':'UTF-8'},{$addresses.invoice->postcode|@addcslashes:'\''|escape:'html':'UTF-8'},{$addresses.invoice->city|@addcslashes:'\''|escape:'html':'UTF-8'}{if isset($addresses.deliveryState->name) && $addresses.invoice->id_state},{$addresses.deliveryState->name|@addcslashes:'\''|escape:'html':'UTF-8'}{/if},{$addresses.invoice->country|@addcslashes:'\''|escape:'html':'UTF-8'}'
				}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK)
				{
					invoice_map = new google.maps.Map(document.getElementById('map-invoice-canvas-{$order->id|escape:'html':'UTF-8'}'), {
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						center: results[0].geometry.location
					});
					invoice_marker = new google.maps.Marker({
						map: invoice_map,
						position: results[0].geometry.location,
						url: 'http://maps.google.com?q={$addresses.invoice->address1|urlencode|escape:'html':'UTF-8'},{$addresses.invoice->postcode|urlencode|escape:'html':'UTF-8'},{$addresses.invoice->city|urlencode|escape:'html':'UTF-8'}{if isset($addresses.deliveryState->name) && $addresses.invoice->id_state},{$addresses.deliveryState->name|urlencode|escape:'html':'UTF-8'}{/if},{$addresses.invoice->country|urlencode|escape:'html':'UTF-8'}'
					});
					google.maps.event.addListener(invoice_marker, 'click', function() {
						window.open(invoice_marker.url);
					});
				}
			});
            
            /*
			$('.datetimepicker').datetimepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',
				// Define a custom regional settings in order to use PrestaShop translation tools
				currentText: '{l s='Now' js=1 mod='g_ordermanager'}',
				closeText: '{l s='Done' js=1 mod='g_ordermanager'}',
				ampm: false,
				amNames: ['AM', 'A'],
				pmNames: ['PM', 'P'],
				timeFormat: 'hh:mm:ss tt',
				timeSuffix: '',
				timeOnlyTitle: '{l s='Choose Time' js=1 mod='g_ordermanager'}',
				timeText: '{l s='Time' js=1 mod='g_ordermanager'}',
				hourText: '{l s='Hour' js=1 mod='g_ordermanager'}',
				minuteText: '{l s='Minute' js=1 mod='g_ordermanager'}'
			});
            */
		});

		// Fix wrong maps center when map is hidden
		$('#tabAddresses').click(function(){
		    if (typeof delivery_map != 'undefined' && typeof invoice_map != 'undefined')
            {
    			x = delivery_map.getZoom();
    			c = delivery_map.getCenter();
    			google.maps.event.trigger(delivery_map, 'resize');
    			delivery_map.setZoom(x);
    			delivery_map.setCenter(c);
    
    			x = invoice_map.getZoom();
    			c = invoice_map.getCenter();
    			google.maps.event.trigger(invoice_map, 'resize');
    			invoice_map.setZoom(x);
    			invoice_map.setCenter(c);
            }
		});
        function getSummary()
    	{
    		$.ajax({
    			type:"POST",
    			url: admin_ordermanager_tab_link,
    			dataType: "json",
    			data : {
    				ajaxUpdateAddresses: true,
    				id_customer: id_customer,
    				id_order: id_order
    				},
    			success : function(res)
    			{
                        var addresses_delivery_options = '';
                        var addresses_invoice_options = '';
                        var address_invoice_detail = '';
                        var address_delivery_detail = '';
                        var delivery_address_edit_link = '';
                        var invoice_address_edit_link = '';
    			     $.each(res.addresses, function() {
            			if (this.id_address == res.id_address_invoice)
            			{
            				address_invoice_detail = this.formated_address;
            				invoice_address_edit_link = admin_address_link+"&id_address="+this.id_address+"&id_order="+id_order+"&address_type=2&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back="+admin_address_link_back+"#";
                        }
            
            			if(this.id_address == res.id_address_delivery)
            			{
            				address_delivery_detail = this.formated_address;
            				delivery_address_edit_link = admin_address_link+"&id_address="+this.id_address+"&id_order="+id_order+"&address_type=1&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back="+admin_address_link_back+"#";
                        }
            
            			addresses_delivery_options += '<option value="'+this.id_address+'" '+(this.id_address == res.id_address_delivery ? 'selected="selected"' : '')+'>'+this.alias+'</option>';
            			addresses_invoice_options += '<option value="'+this.id_address+'" '+(this.id_address == res.id_address_invoice ? 'selected="selected"' : '')+'>'+this.alias+'</option>';
            		});
                    $('#shipping_id_address_'+id_order).html(addresses_delivery_options);
		            $('#invoice_id_address_'+id_order).html(addresses_invoice_options);
                    $('#shipping_address_text_'+id_order).html(address_delivery_detail);
		            $('#invoice_address_text_'+id_order).html(address_invoice_detail);
                    $('#edit_delivery_address_'+id_order).attr('href', delivery_address_edit_link);
		            $('#edit_invoice_address_'+id_order).attr('href', invoice_address_edit_link);
                    showSuccessMessage('#'+id_order+' : '+data.warning);
    			}
    		});
    	}
	</script>

{/block}
