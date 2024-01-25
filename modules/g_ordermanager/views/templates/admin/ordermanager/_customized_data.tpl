{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if $product['customizedDatas']}
    {* Assign product price *}
    {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
    	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
    {else}
    	{assign var=product_price value=$product['unit_price_tax_incl']}
    {/if}
    	<tr class="customized customized-{$product['id_order_detail']|intval} product-line-row">
    		<td>
    			<input type="hidden" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
    			{if isset($product['image']) && $product['image']->id|intval}{$product['image_tag'] nofilter}{else}--{/if}{* <img/>tag content,no need to escape *}
    		</td>
    		<td>
    			<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}&amp;id_product={$product['product_id']|intval}&amp;updateproduct&amp;token={getAdminToken tab='AdminProducts'}">
    			<span class="productName">{$product['product_name']|escape:'html':'UTF-8'} - {l s='Customized' mod='g_ordermanager'}</span><br />
    			{if ($product['product_reference'])}{l s='Reference number:' mod='g_ordermanager'} {$product['product_reference']|escape:'html':'UTF-8'}<br />{/if}
    			{if ($product['product_supplier_reference'])}{l s='Supplier reference:' mod='g_ordermanager'} {$product['product_supplier_reference']|escape:'html':'UTF-8'}{/if}
    			</a>
                <div class="product_price_edit" style="display:none;">
                    {hook h='orderProuductLideEdit' product=$product}
                </div>
    		</td>
    		<td>
    			<span class="product_price_show">{displayPrice price=$product_price currency=$currency->id|intval}</span>
    			{if $can_edit}
    			<div class="product_price_edit" style="display:none;">
    				<input type="hidden" name="product_id_order_detail" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
    				<div class="form-group">
    					<div class="fixed-width-xl">
    						<div class="input-group">
    							{if $currency->format % 2}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
    							<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_excl'], 2)|escape:'html':'UTF-8'}" size="5" />
    							{if !($currency->format % 2)}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='g_ordermanager'}</div>{/if}
    						</div>
    					</div>
    					<br/>
    					<div class="fixed-width-xl">
    						<div class="input-group">
    							{if $currency->format % 2}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
    							<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_incl'], 2)|escape:'html':'UTF-8'}" size="5" />
    							{if !($currency->format % 2)}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='g_ordermanager'}</div>{/if}
    						</div>
    					</div>
    				</div>
    			</div>
    			{/if}
    		</td>
    		<td class="productQuantity text-center">{$product['customizationQuantityTotal']|escape:'html':'UTF-8'}</td>
    		{if $display_warehouse}<td>&nbsp;</td>{/if}
    		{if ($order->hasBeenPaid())}<td class="productQuantity text-center">{$product['customizationQuantityRefunded']|escape:'html':'UTF-8'}</td>{/if}
    		{if ($order->hasBeenDelivered() || $order->hasProductReturned())}<td class="productQuantity text-center">{$product['customizationQuantityReturned']|escape:'html':'UTF-8'}</td>{/if}
    		{if isset($stock_management) &&   $stock_management}<td class="text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
    		<td class="total_product">
    		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
    			{displayPrice price=Tools::ps_round($product['product_price'] * $product['customizationQuantityTotal'], 2) currency=$currency->id|intval}
    		{else}
    			{displayPrice price=Tools::ps_round($product['product_price_wt'] * $product['customizationQuantityTotal'], 2) currency=$currency->id|intval}
    		{/if}
    		</td>
    		<td class="cancelQuantity standard_refund_fields current-edit" style="display:none" colspan="2">
    			&nbsp;
    		</td>
    		<td class="edit_product_fields" colspan="2" style="display:none">&nbsp;</td>
    		<td class="partial_refund_fields current-edit" style="text-align:left;display:none;"></td>           
    		{if ($can_edit && !$order->hasBeenDelivered())}
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
    				<button type="button" rel="{$product['id_order_detail']|intval}" class="btn btn-default product_price_edit submitProductChange2" style="display:none;">
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
    	{foreach $product['customizedDatas'] as $customizationPerAddress}
    		{foreach $customizationPerAddress as $customizationId => $customization}
    			<tr id="customization_wp_{$product['id_order_detail']|intval}"  class="customized customized-{$product['id_order_detail']|intval}">
    				<td colspan="2">
    				<input type="hidden" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
    					<div class="form-horizontal">
    						{foreach $customization.datas as $type => $datas}
    							{if ($type == Product::CUSTOMIZE_FILE)}
    								{foreach from=$datas item=data}
    									<div class="form-group">
    										<span class="col-lg-4 control-label"><strong>{if $data['name']}{$data['name']|escape:'html':'UTF-8'}{else}{l s='Picture #' mod='g_ordermanager'}{$data@iteration|escape:'html':'UTF-8'}{/if}</strong></span>
    										<div class="col-lg-8">
                                                {if $data['value'] !=''}
    											<a href="displayImage.php?img={$data['value']|escape:'html':'UTF-8'}&amp;name={$order->id|intval}-file{$data@iteration|escape:'html':'UTF-8'}" class="_blank">
    												<img class="img-thumbnail" src="{$smarty.const._THEME_PROD_PIC_DIR_|escape:'html':'UTF-8'}{$data['value']|escape:'html':'UTF-8'}_small" alt=""/>
    											</a>
                                                {else}--{/if}
                                                <div class="product_price_edit" style="display:none;">
                                                    {if $data['value'] !=''}
                                                    <p>
                                                        <input type="checkbox" id="customization_remove_{$data['id_customization']|escape:'html':'UTF-8'}_{$data['index']|escape:'html':'UTF-8'}" name="customization_remove[{$data['id_customization']|escape:'html':'UTF-8'}][{$data['index']|escape:'html':'UTF-8'}]" value="{$data['index']|escape:'html':'UTF-8'}"/>
                                                        <label for="customization_remove_{$data['id_customization']|escape:'html':'UTF-8'}_{$data['index']|escape:'html':'UTF-8'}">{l s='Delete'  mod='g_ordermanager'}</label>
                                                    </p>
                                                    {/if}
                                                    <input rel="{$data['id_customization']|escape:'html':'UTF-8'}_{$data['index']|escape:'html':'UTF-8'}" class="customization_file_{$product['id_order_detail']|escape:'html':'UTF-8'}" type="file" name="customization[{$data['id_customization']|escape:'html':'UTF-8'}][{$data['index']|escape:'html':'UTF-8'}]" id="customization_{$data['id_customization']|escape:'html':'UTF-8'}_{$data['index']|escape:'html':'UTF-8'}" />
                                                </div>
    										</div>
    									</div>
    								{/foreach}
    							{elseif ($type == Product::CUSTOMIZE_TEXTFIELD)}
    								{foreach from=$datas item=data}
    									<div class="form-group">
    										<span class="col-lg-4 control-label"><strong>{if $data['name']}{l s='%s' sprintf=$data['name'] mod='g_ordermanager'}{else}{l s='Text #%s' sprintf=$data@iteration mod='g_ordermanager'}{/if}</strong></span>
    										<div class="col-lg-8">
    											<p class="form-control-static">{$data['value']|escape:'html':'UTF-8'}</p>
                                                <div class="product_price_edit" style="display:none;">
                                                    <textarea  class="customization_text_{$product['id_order_detail']|escape:'html':'UTF-8'}"   name="customization[{$data['id_customization']|escape:'html':'UTF-8'}][{$data['index']|escape:'html':'UTF-8'}]" id="customization_{$data['id_customization']|escape:'html':'UTF-8'}_{$data['index']|escape:'html':'UTF-8'}">{$data['value']|escape:'html':'UTF-8'}</textarea>
    										      </div>
                                            </div>
    									</div>
    								{/foreach}
    							{/if}
    						{/foreach}
    					</div>
    				</td>
    				<td>-</td>
    				<td class="productQuantity text-center">
    					<span class="product_quantity_show{if (int)$customization['quantity'] > 1} red bold{/if}">{$customization['quantity']|escape:'html':'UTF-8'}</span>
    					{if $can_edit}
    					<span class="product_quantity_edit" style="display:none;">
    						<input type="text" name="product_quantity[{$customizationId|intval}]" class="edit_product_quantity" value="{$customization['quantity']|htmlentities}" size="2" />
    					</span>
    					{/if}
    				</td>
    				{if $display_warehouse}<td>&nbsp;</td>{/if}
    				{if ($order->hasBeenPaid())}
    				<td class="text-center">
    					{if !empty($product['amount_refund'])}
    					{l s='%s (%s refund)' sprintf=[$customization['quantity_refunded'], $product['amount_refund']] mod='g_ordermanager'}
    					{/if}
    					<input type="hidden" value="{$product['quantity_refundable']|escape:'html':'UTF-8'}" class="partialRefundProductQuantity" />
    					<input type="hidden" value="{(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal']))|escape:'html':'UTF-8'}" class="partialRefundProductAmount" />
    				</td>
    				{/if}
    				{if ($order->hasBeenDelivered())}<td class="text-center">{$customization['quantity_returned']|escape:'html':'UTF-8'}</td>{/if}
    				<td class="text-center">-</td>
    				<td class="total_product">
    					{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
    						{displayPrice price=Tools::ps_round($product['product_price'] * $customization['quantity'], 2) currency=$currency->id|intval}
    					{else}
    						{displayPrice price=Tools::ps_round($product['product_price_wt'] * $customization['quantity'], 2) currency=$currency->id|intval}
    					{/if}
    				</td>
    				<td class="cancelCheck standard_refund_fields current-edit" style="display:none">
    					<input type="hidden" name="totalQtyReturn" id="totalQtyReturn" value="{$customization['quantity_returned']|intval}" />
    					<input type="hidden" name="totalQty" id="totalQty" value="{$customization['quantity']|intval}" />
    					<input type="hidden" name="productName" id="productName" value="{$product['product_name']|escape:'html':'UTF-8'}" />
    					{if ((!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN')) AND (int)($customization['quantity_returned']) < (int)($customization['quantity']))}
    						<input type="checkbox" name="id_customization[{$customizationId|intval}]" id="id_customization[{$customizationId|intval}]" value="{$product['id_order_detail']|intval}" onchange="setCancelQuantity(this, {$customizationId|intval}, {$customization['quantity'] - $product['customizationQuantityTotal'] - $product['product_quantity_reinjected']|escape:'html':'UTF-8'})" {if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}disabled="disabled" {/if}/>
    					{else}
    					--
    				{/if}
    				</td>
    				<td class="cancelQuantity standard_refund_fields current-edit" style="display:none">
    				{if ($customization['quantity_returned'] + $customization['quantity_refunded'] >= $customization['quantity'])}
    					<input type="hidden" name="cancelCustomizationQuantity[{$customizationId|intval}]" value="0" />
    				{elseif (!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN'))}
    					<input type="text" id="cancelQuantity_{$customizationId|intval}" name="cancelCustomizationQuantity[{$customizationId|intval}]" size="2" onclick="selectCheckbox(this);" value="" />0/{$customization['quantity']-$customization['quantity_refunded']|escape:'html':'UTF-8'}
    				{/if}
    				</td>
    				<td class="partial_refund_fields current-edit" colspan="2" style="display:none; width: 250px;">
    					{if $product['quantity_refundable'] > 0}
    					{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
    						{assign var='amount_refundable' value=$product['amount_refundable']}
    					{else}
    						{assign var='amount_refundable' value=$product['amount_refundable_tax_incl']}
    					{/if}
    					<div class="form-group">
    						<div class="{if $product['amount_refundable'] > 0}col-lg-4{else}col-lg-12{/if}">
    							<label class="control-label">
    								{l s='Quantity:' mod='g_ordermanager'}
    							</label>
    							<div class="input-group">
    								<input onchange="checkPartialRefundProductQuantity(this)" type="text" name="partialRefundProductQuantity[{$product['id_order_detail']|intval}]" value="{if ($customization['quantity']-$customization['quantity_refunded']) >0}1{else}0{/if}" />
    								<div class="input-group-addon">/ {$product['quantity_refundable']|escape:'html':'UTF-8'}</div>
    							</div>
    						</div>
    						<div class="{if $product['quantity_refundable'] > 0}col-lg-8{else}col-lg-12{/if}">
    							<label class="control-label">
    								<span class="title_box ">{l s='Amount:' mod='g_ordermanager'}</span>
    								<small class="text-muted">({$smarty.capture.TaxMethod|escape:'html':'UTF-8'})</small>
    							</label>
    							<div class="input-group">
    								{if $currency->format % 2}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
    								<input onchange="checkPartialRefundProductAmount(this)" type="text" name="partialRefundProduct[{$product['id_order_detail']|intval}]" />
    								{if !($currency->format % 2)}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
    							</div>
    							<p class="help-block"><i class="icon-warning-sign"></i> {l s='(Max %s %s)' sprintf=[Tools::displayPrice(Tools::ps_round($amount_refundable, 2), $currency->id), $smarty.capture.TaxMethod] mod='g_ordermanager'}</p>
    						</div>
    					</div>
    					{/if}
    				</td>
    				{if ($can_edit && !$order->hasBeenDelivered())}
    					<td class="edit_product_fields" colspan="2" style="display:none"></td>
    					<td class="product_action" style="text-align:right"></td>
    				{/if}
    			</tr>
    		{/foreach}
    	{/foreach}
    {/if}