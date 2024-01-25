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
{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
{else}
	{assign var=product_price value=$product['unit_price_tax_incl']}
{/if}
{assign var="customized_product_quantity" value=0}
{if isset($product['customized_product_quantity'])}
    {assign var="customized_product_quantity" value=$product['customized_product_quantity']}
{elseif isset($product['customizationQuantityTotal'])}
    {assign var="customized_product_quantity" value=$product['customizationQuantityTotal']}
{/if}
{capture "TaxMethod"}
	{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
		{l s='tax excluded.' mod='g_ordermanager'}
	{else}
		{l s='tax included.' mod='g_ordermanager'}
	{/if}
{/capture}
{if ($product['product_quantity'] > $customized_product_quantity)}
<tr class="product-line-row">
	<td>{if isset($product.image) && $product.image->id}{$product.image_tag nofilter}{/if}</td>{* <img /> tag content, no need to escape *}
	<td>
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}&amp;id_product={$product['product_id']|intval}&amp;updateproduct&amp;token={getAdminToken tab='AdminProducts'}">
			<span class="productName">{$product['product_name']|escape:'html':'UTF-8'}</span><br />
			{if $product.product_reference}{l s='Reference number:' mod='g_ordermanager'} {$product.product_reference|escape:'html':'UTF-8'}<br />{/if}
			{if $product.product_supplier_reference}{l s='Supplier reference:' mod='g_ordermanager'} {$product.product_supplier_reference|escape:'html':'UTF-8'}{/if}
		</a>
		<div class="row-editing-warning" style="display:none;">
			<div class="alert alert-warning">
				<strong>{l s='Editing this product line will remove the reduction and base price.' mod='g_ordermanager'}</strong>
			</div>
		</div>
        <div class="product_price_edit" style="display:none;">
        {hook h='orderProuductLideEdit' product=$product}
        </div>
	</td>
	<td>
		<span class="product_price_show">{displayPrice price=$product_price currency=$currency->id}</span>
		{if $can_edit}
		<div class="product_price_edit" style="display:none;">
			<input type="hidden" name="product_id_order_detail" class="edit_product_id_order_detail" value="{$product['id_order_detail']|escape:'html':'UTF-8'}" />
			<div class="form-group">
				<div class="fixed-width-xl">
					<div class="input-group">
						{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
						<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_excl'], 2)|escape:'html':'UTF-8'}"/>
						{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
					</div>
				</div>
				<br/>
				<div class="fixed-width-xl">
					<div class="input-group">
						{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
						<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_incl'], 2)|escape:'html':'UTF-8'}"/>
						{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
					</div>
				</div>
			</div>
		</div>
		{/if}
	</td>
	<td class="productQuantity text-center">
		<span class="product_quantity_show{if (int)$product['product_quantity'] - (int)$customized_product_quantity > 1} badge{/if}">{(int)$product['product_quantity'] - (int)$customized_product_quantity|escape:'html':'UTF-8'}</span>
		{if $can_edit}
		<span class="product_quantity_edit" style="display:none;">
			<input type="text" name="product_quantity" class="edit_product_quantity" value="{$product['product_quantity']|htmlentities|escape:'html':'UTF-8'}"/>
		</span>
		{/if}
	</td>
	{if $display_warehouse}
		<td>
			{$product.warehouse_name|escape:'html':'UTF-8'}
			{if $product.warehouse_location}
				<br>{l s='Location' mod='g_ordermanager'}: <strong>{$product.warehouse_location|escape:'html':'UTF-8'}</strong>
			{/if}
		</td>
	{/if}
	{if ($order->hasBeenPaid())}
		<td class="productQuantity text-center">
			{if !empty($product['amount_refund'])}
				{l s='%s (%s refund)' sprintf=[$product['product_quantity_refunded'], $product['amount_refund']] mod='g_ordermanager'}
			{/if}
			<input type="hidden" value="{$product['quantity_refundable']|escape:'html':'UTF-8'}" class="partialRefundProductQuantity" />
			<input type="hidden" value="{(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal']))|escape:'html':'UTF-8'}" class="partialRefundProductAmount" />
            {if isset($product['refund_history']) && $product['refund_history'] &&  count($product['refund_history'])}
				<span class="tooltip">
					<span class="tooltip_label tooltip_button">+</span>
					<span class="tooltip_content">
					<span class="title">{l s='Refund history' mod='g_ordermanager'}</span>
					{foreach $product['refund_history'] as $refund}
						{l s='%1s - %2s' sprintf=[{dateFormat date=$refund.date_add}, {displayPrice price=$refund.amount_tax_incl}]  mod='g_ordermanager'}<br />
					{/foreach}
					</span>
				</span>
			{/if}
		</td>
	{/if}
	{if $order->hasBeenDelivered() || $order->hasProductReturned()}
		<td class="productQuantity text-center">
			{$product['product_quantity_return']|escape:'html':'UTF-8'}
			{if isset($product['return_history']) && $product['return_history'] &&  count($product['return_history'])}
				<span class="tooltip">
					<span class="tooltip_label tooltip_button">+</span>
					<span class="tooltip_content">
					<span class="title">{l s='Return history' mod='g_ordermanager'}</span>
					{foreach $product['return_history'] as $return}
						{l s='%1s - %2s - %3s' sprintf=[{dateFormat date=$return.date_add}, $return.product_quantity, $return.state] mod='g_ordermanager'}<br />
					{/foreach}
					</span>
				</span>
			{/if}
		</td>
	{/if}
	{* tung update 15/12/2021
	{if isset($stock_management) && $stock_management}<td class="productQuantity product_stock text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
	*}
	{if isset($product['current_stock']) && $product['current_stock']}<td class="productQuantity product_stock text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
	
	<td class="total_product">
		{displayPrice price=(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal'])) currency=$currency->id}
	</td>
	<td colspan="2" style="display: none;" class="add_product_fields">&nbsp;</td>
	<td class="cancelCheck standard_refund_fields current-edit AAA" style="display:none">
		<input type="hidden" name="totalQtyReturn" id="totalQtyReturn" value="{$product['product_quantity_return']|escape:'html':'UTF-8'}" />
		<input type="hidden" name="totalQty" id="totalQty" value="{$product['product_quantity']|escape:'html':'UTF-8'}" />
		<input type="hidden" name="productName" id="productName" value="{$product['product_name']|escape:'html':'UTF-8'}" />
	{if ((!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN')) AND (int)($product['product_quantity_return']) < (int)($product['product_quantity']))}
		<input type="checkbox" name="id_order_detail[{$product['id_order_detail']|escape:'html':'UTF-8'}]" id="id_order_detail[{$product['id_order_detail']|escape:'html':'UTF-8'}]" value="{$product['id_order_detail']|escape:'html':'UTF-8'}" onchange="setCancelQuantity(this, {$product['id_order_detail']|escape:'html':'UTF-8'}, {$product['product_quantity'] - $product['customizationQuantityTotal'] - $product['product_quantity_return'] - $product['product_quantity_refunded']|escape:'html':'UTF-8'})" {if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}disabled="disabled" {/if}/>
	{else}
		--
	{/if}
	</td>
	<td class="cancelQuantity standard_refund_fields current-edit" style="display:none">
	{if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}
		<input type="hidden" name="cancelQuantity[{$product['id_order_detail']|escape:'html':'UTF-8'}]" value="0" />
	{elseif (!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN'))}
		<input type="text" id="cancelQuantity_{$product['id_order_detail']|escape:'html':'UTF-8'}" name="cancelQuantity[{$product['id_order_detail']|escape:'html':'UTF-8'}]" onchange="checkTotalRefundProductQuantity(this)" value="" />
	{/if}

	{if $product['customizationQuantityTotal']}
		{assign var=productQuantity value=($product['product_quantity']-$product['customizationQuantityTotal'])}
	{else}
		{assign var=productQuantity value=$product['product_quantity']}
	{/if}

	{if ($order->hasBeenDelivered())}
		{$product['product_quantity_refunded']|escape:'html':'UTF-8'}/{$productQuantity-$product['product_quantity_refunded']|escape:'html':'UTF-8'}
	{elseif ($order->hasBeenPaid())}
		{$product['product_quantity_return']|escape:'html':'UTF-8'}/{$productQuantity|escape:'html':'UTF-8'}
	{else}
		0/{$productQuantity|escape:'html':'UTF-8'}
	{/if}
	</td>
	<td class="partial_refund_fields current-edit" colspan="2" style="display:none; width: 250px;">
		{if $product['quantity_refundable'] > 0}
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
			{assign var='amount_refundable' value=$product['amount_refundable']}
		{else}
            {if isset($product['amount_refundable_tax_incl'])}
			     {assign var='amount_refundable' value=$product['amount_refundable_tax_incl']}
            {else}
                {assign var='amount_refundable' value=$product['amount_refundable']}
            {/if}
		{/if}
		<div class="form-group">
			<div class="{if $product['amount_refundable'] > 0}col-lg-4{else}col-lg-12{/if}">
				<label class="control-label">
					{l s='Quantity:' mod='g_ordermanager'}
				</label>
				<div class="input-group">
					<input onchange="checkPartialRefundProductQuantity(this)" type="text" name="partialRefundProductQuantity[{{$product['id_order_detail']|escape:'html':'UTF-8'}|escape:'html':'UTF-8'}]" value="0" />
					<div class="input-group-addon">/ {$product['quantity_refundable']|escape:'html':'UTF-8'}</div>
				</div>
			</div>
			<div class="{if $product['quantity_refundable'] > 0}col-lg-8{else}col-lg-12{/if}">
				<label class="control-label">
					<span class="title_box ">{l s='Amount:' mod='g_ordermanager'}</span>
					<small class="text-muted">({$smarty.capture.TaxMethod|escape:'html':'UTF-8'})</small>
				</label>
				<div class="input-group">
					{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
					<input onchange="checkPartialRefundProductAmount(this)" type="text" name="partialRefundProduct[{$product['id_order_detail']|escape:'html':'UTF-8'}]" />
					{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
				</div>
				<p class="help-block"><i class="icon-warning-sign"></i> {l s='(Max %s %s)' sprintf=[Tools::displayPrice(Tools::ps_round($amount_refundable, 2), $currency->id) , $smarty.capture.TaxMethod] mod='g_ordermanager'}</p>
			</div>
		</div>
		{/if}
	</td>
    {if $can_edit}
	<td class="product_invoice" style="display: none;">
		{if sizeof($invoices_collection)}
		<select name="product_invoice" class="edit_product_invoice">
			{foreach from=$invoices_collection item=invoice}
			<option value="{$invoice->id|escape:'html':'UTF-8'}" {if $invoice->id == $product['id_order_invoice']}selected="selected"{/if}>
				#{Configuration::get('PS_INVOICE_PREFIX', $current_id_lang, null, $order->id_shop)|escape:'html':'UTF-8'}{'%06d'|sprintf:$invoice->number|escape:'html':'UTF-8'}
			</option>
			{/foreach}
		</select>
		{else}
		&nbsp;
		{/if}
	</td>
	<td class="product_action text-right">
		{* edit/delete controls *}
		<div class="btn-group order_action">
			<button type="button" class="btn btn-default edit_product_change_link2">
				<i class="icon-pencil"></i>
				{l s='Edit' mod='g_ordermanager'}
			</button>
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="#" class="delete_product_line2">
						<i class="icon-trash"></i>
						{l s='Delete' mod='g_ordermanager'}
					</a>
				</li>
			</ul>
		</div>
		{* Update controls *}
		<button type="button" class="btn btn-default product_price_edit submitProductChange2" style="display:none;">
			<i class="icon-ok"></i>
			{l s='Update' mod='g_ordermanager'}
		</button>
		<button type="button" class="btn btn-default cancel_product_change_link" style="display: none;">
			<i class="icon-remove"></i>
			{l s='Cancel' mod='g_ordermanager'}
		</button>
	</td>
	{/if}
</tr>
{/if}
