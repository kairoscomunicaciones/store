
<table class="product" width="100%" cellpadding="5" cellspacing="0">
    <thead>
    	<tr>
                            <th class="product header small  aligncenter " width="%">PLAY CODE</th>
                            <th class="product header small  alignleft " width="%">Product</th>
                            <th class="product header small  aligncenter " width="%">Qty</th>
                    </tr>
    </thead>
	<tbody>
    
    {assign var="colCount" value="3"}
	{foreach $order_details as $order_detail}
		{cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
		<tr class="product {$bgcolor_class}">
    
			               <td class="product  aligncenter "  width="%">{$order_detail.product_reference}</td>
                           <td class="product  alignleft "  width="%"><p class="product_name">{$order_detail.product_name}</p></td>
                           <td class="product  aligncenter "  width="%">{$order_detail.product_quantity}</td>
            		</tr>
    
		{foreach $order_detail.customizedDatas as $customizationPerAddress}
			{foreach $customizationPerAddress as $customizationId => $customization}
				<tr class="product customization_data {$bgcolor_class}">
					{if $colCount > 1}
                    <td class="product center">
						( x{if $customization.quantity == 0}1{else}{$customization.quantity}{/if})
					</td>
                    {/if}
                    <td  class="product" colspan="{if $colCount > 1}{$colCount-1}{/if}">
                        {if $colCount == 1}(x {if $customization.quantity == 0}1{else}{$customization.quantity}{/if}){/if}
						{if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
							<table style="width: 100%;">
								{foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
									<tr>
										<td style="width: 25%;">
											{$customization_infos.name|string_format:{l s='%s:' pdf='true' mod='gwadvancedinvoice'}}
										</td>
										<td>{$customization_infos.value}</td>
									</tr>
								{/foreach}
							</table>
						{/if}

						{if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
							<table style="width: 100%;">
								<tr>
									<td style="width: 70%;">{if isset($gimage_label) && $gimage_label !=''}{$gimage_label|escape:'html':'UTF-8'}{else}{l s='image(s):' pdf='true' mod='gwadvancedinvoice'}{/if}</td>
									<td>{count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])}</td>
								</tr>
							</table>
						{/if}
					</td>
				</tr>
			{/foreach}
		{/foreach}
	{/foreach}
	{assign var="shipping_discount_tax_incl" value="0"}
	{foreach from=$cart_rules item=cart_rule name="cart_rules_loop"}
		{if $smarty.foreach.cart_rules_loop.first}
		<tr class="discount">
			<th class="header" colspan="{$colCount}">
                {if isset($gdiscount_label) && $gdiscount_label !=''}
                {$gdiscount_label|escape:'html':'UTF-8'}
                {else}
				{l s='Discounts' pdf='true' mod='gwadvancedinvoice'}
                {/if}
			</th>
		</tr>
		{/if}
		<tr class="discount">
            {if $colCount > 1}
			<td class="white right" colspan="{$colCount - 1}">
				{$cart_rule.name}
			</td>
            {/if}
			<td class="right white">
                {if $colCount == 1}{$cart_rule.name}:{/if}
                
                                
                - {displayPrice currency=$order->id_currency price=$cart_rule.value_tax_excl}
                
                                
			</td>
		</tr>
	{/foreach}
    
    	</tbody>

</table>
