{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<div class="panel_wp change_reference">
    <button class="gclose_popup"><i class="icon-close"></i></button>
    <div class="panel_wp-heading">#{l s='Reference' mod='g_ordermanager'}</div>
    <div class="col-lg-12">
        <div class="row">
			<div class="col-lg-9">
				<div class="input-group edit_order_reference">
                	<input type="text" class="order_reference" id="reference_{$order->id|escape:'html':'UTF-8'}" value="{$order->reference|escape:'html':'UTF-8'}" />
					<div class="input-group-btn">
						<a href="#" class="btn btn-default generate_ex_reference"><i class="icon-random"></i>{l s='Generate' mod='g_ordermanager'}</a>
					</div>
				</div>
				<small class="text-muted">{l s='Maximum 9 characters' mod='g_ordermanager'}</small>
            </div>
            <div class="col-lg-3">
                <button rel="{$order->id|escape:'html':'UTF-8'}" type="submit" class="btn btn-default pull-left OrderChangeReference"><i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}</button>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="panel_wp change_date">
    <button class="gclose_popup"><i class="icon-close"></i></button>
    <div class="panel_wp-heading">{l s='Order date' mod='g_ordermanager'}</div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-9">
				<div class="input-group">
					<span class="input-group-addon">{l s='Date add' mod='g_ordermanager'}</span>
					<input id="dateadd_{$order->id|escape:'html':'UTF-8'}" type="text" class="datetimepicker input-medium" name="date_from" value="{$order->date_add|escape:'html':'UTF-8'}" />
					<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#dateadd_{$order->id|escape:'html':'UTF-8'}').datetimepicker({
                            	beforeShow: function (input, inst) {
                                    setTimeout(function () {
                                        inst.dpDiv.css({
                                            'z-index': 1031
                                        });
                                    }, 0);
                                },
                            	prevText: '',
                            	nextText: '',
                            	dateFormat: 'yy-mm-dd',
                            	// Define a custom regional settings in order to use PrestaShop translation tools
                            	currentText: "{l s='Now' mod='g_ordermanager'}",
                            	closeText: "{l s='Done' mod='g_ordermanager'}",
                            	ampm: false,
                            	amNames: ['AM', 'A'],
                            	pmNames: ['PM', 'P'],
                            	timeFormat: 'hh:mm:ss tt',
                            	timeSuffix: '',
                            	timeOnlyTitle:  "{l s='Choose Time' mod='g_ordermanager'}",
                            	timeText:  "{l s='Time' mod='g_ordermanager'}",
                            	hourText:  "{l s='Hour' mod='g_ordermanager'}",
                            	minuteText:  "{l s='Minute' mod='g_ordermanager'}",
                            });

                        })
                    </script>
				</div>
			</div>
            <div class="col-lg-3">
                <button rel="{$order->id|escape:'html':'UTF-8'}" type="submit" class="btn btn-default pull-left OrderChangeDateAdd"><i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}</button>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="panel_wp change_status">
    <button class="gclose_popup"><i class="icon-close"></i></button>
	<div class="panel_wp-heading">{l s='Status' mod='g_ordermanager'}</div>
    
    <div class="col-lg-12">
    	<div class="row">
    		<div class="col-lg-9">
    			<select id="id_order_state_{$order->id|escape:'html':'UTF-8'}" class="chosen form-control" name="id_order_state">
    			{foreach from=$states item=state}
    				<option value="{$state['id_order_state']|intval}"{if isset($currentState) && $state['id_order_state'] == $currentState->id} selected="selected" disabled="disabled"{/if}>{$state['name']|escape|escape:'html':'UTF-8'}</option>
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
    <div class="col-lg-126">
    	<div class="row">
            <div class="table-responsive order_history_table">
				<table class="table history-status row-margin-bottom">
					<tbody>
						{foreach from=$history item=row key=key}
							{if ($key == 0)}
								<tr  class="order_history_{$order->id|intval}_{$row['id_order_history']|intval}">
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
								<tr  class="order_history_{$order->id|intval}_{$row['id_order_history']|intval}">
									<td><img src="../img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" /></td>
									<td>{$row['ostate_name']|stripslashes|escape:'html':'UTF-8'}</td>
									<td>{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes|escape:'html':'UTF-8'} {$row['employee_lastname']|stripslashes|escape:'html':'UTF-8'}{else}&nbsp;{/if}</td>
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
                                    <td class="text-right">
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
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="panel_wp change_carrier">
    <button class="gclose_popup"><i class="icon-close"></i></button>
    <div class="panel_wp-heading">{l s='Carrier' mod='g_ordermanager'}</div>
    <div class="col-lg-12">
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
                <button rel="{$order->id|escape:'html':'UTF-8'}" type="submit" class="btn btn-default pull-left OrderChangeCarrier"><i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}</button>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="panel_wp change_payment">
    <button class="gclose_popup"><i class="icon-close"></i></button>
	<div class="panel_wp-heading">
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
        					<div class="input-group fixed-width-xl">
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
        					<div class="input-group fixed-width-xl">
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
		<form class="form-horizontal well" method="post" action="index.php?controller=AdminOrdermanager&amp;vieworder&amp;id_order={$order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
			<div class="row">
				<label class="control-label col-lg-3">{l s='Currency' mod='g_ordermanager'}</label>
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
<div class="panel_wp change_customer">
    <button class="gclose_popup"><i class="icon-close"></i></button>
	{if $customer->id}
		<div class="panel_wp-heading">
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
				<div class="panel_wp panel_wp-sm">
					<div class="panel_wp-heading">
						<i class="icon-eye-slash"></i>
						{l s='Private note' mod='g_ordermanager'}
					</div>
					<div class="form-group">
						<div class="col-lg-12">
							<textarea name="note" id="noteContent_{$order->id|escape:'html':'UTF-8'}" class="textarea-autosize">{$customer->note|escape:'html':'UTF-8'}</textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<button type="submit" data-noteContent="{$order->id|escape:'html':'UTF-8'}" data-customer="{$customer->id|escape:'html':'UTF-8'}"  class="submitSentCustomerNote btn btn-default pull-right">
								<i class="icon-save"></i>
								{l s='Save' mod='g_ordermanager'}
							</button>
						</div>
					</div>
					<span id="note_feedback"></span>
				</div>
			</div>
		</div>
	{/if}
 </div>
<div class="tab-content panel_wp change_cname">
    <button class="gclose_popup"><i class="icon-close"></i></button>
	<!-- Tab status -->
	<div class="tab-pane  in active col-lg-6" id="addressShipping">
		<!-- Addresses -->
		<h4>{l s='Shipping address' mod='g_ordermanager'}</h4>
		{if !$order->isVirtual()}
		<!-- Shipping address -->
			{if $can_edit}
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
			{/if}
			<div class="well">
				<div class="row">
					<div class="col-sm-6">
                        <div id="shipping_address_text_{$order->id|intval}">
    						{displayAddressDetail address=$addresses.delivery newLine='<br />'}
    						{if $addresses.delivery->other}
    							<hr />{$addresses.delivery->other|escape:'html':'UTF-8'}<br />
    						{/if}
                        </div>
					</div>
					<div class="col-sm-6 hidden-print">
						<div id="map-delivery-canvas" style="height: 190px"></div>
					</div>
				</div>
			</div>
		{/if}
	</div>
	<div class="tab-pane in active col-lg-6" id="addressInvoice">
		<!-- Invoice address -->
		<h4>{l s='Invoice address' mod='g_ordermanager'}</h4>
		{if $can_edit}
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
		{/if}
		<div class="well">
			<div class="row">
				<div class="col-sm-6">
                    <div id="invoice_address_text_{$order->id|intval}">
    					{displayAddressDetail address=$addresses.invoice newLine='<br />'}
    					{if $addresses.invoice->other}
    						<hr />{$addresses.invoice->other|escape:'html':'UTF-8'}<br />
    					{/if}
                    </div>
				</div>
				<div class="col-sm-6 hidden-print">
					<div id="map-invoice-canvas" style="height: 190px"></div>
				</div>
			</div>
		</div>
	</div>
    <div class="clear">&nbsp;</div>
</div>
<div class="panel_wp viewproduct">
    <button class="gclose_popup"><i class="icon-close"></i></button>
    <div class="panel_wp-heading">
    	<i class="icon-shopping-cart"></i>
    	{l s='Products' mod='g_ordermanager'} <span class="badge">{$products|@count|escape:'html':'UTF-8'}</span>
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
				</tr>
			</thead>
			<tbody>
			{foreach from=$products item=product key=k}
				{include file=$branche_tpl_customized_data}
				{include file=$branche_tpl_product_line}
			{/foreach}
			</tbody>
		</table>
	</div>
    <div class="clear">&nbsp;</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="alert alert-warning">
				{l s='For this customer group, prices are displayed as: [1]%s[/1]' sprintf=[$smarty.capture.TaxMethod] tags=['<strong>']  mod='g_ordermanager'}
				{if !Configuration::get('PS_ORDER_RETURN')}
					<br/><strong>{l s='Merchandise returns are disabled' mod='g_ordermanager'}</strong>
				{/if}
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel_wp panel_wp-vouchers" style="{if !sizeof($discounts)}display:none;{/if}">
				{if (sizeof($discounts) || $can_edit)}
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>
									<span class="title_box ">
										{l s='Discount name' mod='g_ordermanager'}
									</span>
								</th>
								<th>
									<span class="title_box ">
										{l s='Value' mod='g_ordermanager'}
									</span>
								</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$discounts item=discount}
							<tr>
								<td>{$discount['name']|escape:'html':'UTF-8'}</td>
								<td>
								{if $discount['value'] != 0.00}
									-
								{/if}
								{displayPrice price=$discount['value'] currency=$currency->id}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				{/if}
			</div>
			<div class="panel_wp panel_wp-total">
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
								<p class="help-block"><i class="icon-warning-sign"></i> {l s='(%s)' sprintf=$smarty.capture.TaxMethod  mod='g_ordermanager'}</p>
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
</div>