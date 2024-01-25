{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if $version == 160}
{if $tax_exempt || ((isset($product_tax_breakdown) && $product_tax_breakdown|@count > 0) || (isset($ecotax_tax_breakdown) && $ecotax_tax_breakdown|@count > 0))}
{if $tax_exempt}
{if isset($taxexempt_label) && $taxexempt_label !=''}{$taxexempt_label|escape:'htmlall':'UTF-8'}{else}
{l s='Exempt of VAT according section 259B of the General Tax Code.'  mod='gwadvancedinvoice'}
{/if}
{else}<table id="tax-tab" width="100%" cellpadding="5" cellspacing="0">
		<thead>
        <tr style="line-height:5px;">
			<th class="header small">{if isset($taxdetail_label) && $taxdetail_label !=''}{$taxdetail_label|escape:'htmlall':'UTF-8'}{else}{l s='Tax Detail' mod='gwadvancedinvoice'}{/if}</th>
			<th class="header small">{if isset($taxrate_label) && $taxrate_label !=''}{$taxrate_label|escape:'htmlall':'UTF-8'}{else}{l s='Tax Rate' mod='gwadvancedinvoice'}{/if}</th>
			{if !$use_one_after_another_method}
				<th class="header small">{if isset($taxtotalexcl_label) && $taxtotalexcl_label !=''}{$taxtotalexcl_label|escape:'htmlall':'UTF-8'}{else}{l s='Total Tax Excl'  mod='gwadvancedinvoice'}{/if}</th>
			{/if}
			<th class="header-right small">{if isset($taxtotal_label) && $taxtotal_label !=''}{$taxtotal_label|escape:'htmlall':'UTF-8'}{else}{l s='Total Tax'  mod='gwadvancedinvoice'}{/if}</th>
		</tr>
        </thead>
        <tbody>
		{if isset($product_tax_breakdown)}
			{foreach $product_tax_breakdown as $rate => $product_tax_infos}
            {cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
			<tr class="product {$bgcolor_class|escape:'htmlall':'UTF-8'}" style="line-height:6px;">
			 <td>
				{if !isset($pdf_product_tax_written)}
					{if isset($taxproduct_label) && $taxproduct_label !=''}{$taxproduct_label|escape:'htmlall':'UTF-8'}{else}{l s='Products'  mod='gwadvancedinvoice'}{/if}
					{assign var=pdf_product_tax_written value=1}
				{/if}
			</td>
			 <td style="text-align: right;">{$rate|floatval} %</td>
			{if !$use_one_after_another_method}
			 <td style=" text-align: right;">
				 {if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$product_tax_infos.total_price_tax_excl}
			 </td>
			{/if}
			 <td style="text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$product_tax_infos.total_amount}</td>
			</tr>
			{/foreach}
			{/if}

			{if isset($shipping_tax_breakdown)}
			{foreach $shipping_tax_breakdown as $shipping_tax_infos}
            {cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
			<tr class="product {$bgcolor_class|escape:'htmlall':'UTF-8'}" style="line-height:6px;">
			 <td>
				{if !isset($pdf_shipping_tax_written)}
					{if isset($taxshipping_label) && $taxshipping_label !=''}{$taxshipping_label|escape:'htmlall':'UTF-8'}{else}{l s='Shipping'  mod='gwadvancedinvoice'}{/if}
					{assign var=pdf_shipping_tax_written value=1}
				{/if}
			 </td>
			 <td style=" text-align: right;">{$shipping_tax_infos.rate|floatval} %</td>
			{if !$use_one_after_another_method}
				 <td style=" text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$shipping_tax_infos.total_tax_excl}</td>
			{/if}
			 <td style=" text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$shipping_tax_infos.total_amount}</td>
			</tr>
			{/foreach}
		{/if}

		{if isset($ecotax_tax_breakdown)}
			{foreach $ecotax_tax_breakdown as $ecotax_tax_infos}
				{if $ecotax_tax_infos.ecotax_tax_excl > 0}
                {cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
				<tr class="product {$bgcolor_class|escape:'htmlall':'UTF-8'}" style="line-height:6px;">
					<td>{if isset($taxecotax_label) && $taxecotax_label !=''}{$taxecotax_label|escape:'htmlall':'UTF-8'}{else}{l s='Ecotax'  mod='gwadvancedinvoice'}{/if}</td>
					<td style="text-align: right;">{$ecotax_tax_infos.rate|floatval} %</td>
					{if !$use_one_after_another_method}
						<td style=" text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$ecotax_tax_infos.ecotax_tax_excl}</td>
					{/if}
					<td style=" text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=($ecotax_tax_infos.ecotax_tax_incl - $ecotax_tax_infos.ecotax_tax_excl)}</td>
				</tr>
				{/if}
			{/foreach}
		{/if}
        </tbody>
	</table>
 {/if}
    <!--  / TAX DETAILS -->
    {/if}
{else}
    {if $tax_exempt}
        {if isset($taxexempt_label) && $taxexempt_label !=''}{$taxexempt_label|escape:'htmlall':'UTF-8'}{else}
    	{l s='Exempt of VAT according to section 259B of the General Tax Code.'  mod='gwadvancedinvoice'}
        {/if}
    
    {elseif (isset($tax_breakdowns) && $tax_breakdowns)}
    	<table id="tax-tab" width="100%" cellpadding="5" cellspacing="0">
    		<thead>
    			<tr>
    				<th class="header small">{if isset($taxdetail_label)  && $taxdetail_label !=''}{$taxdetail_label|escape:'htmlall':'UTF-8'}{else}{l s='Tax Detail'  mod='gwadvancedinvoice'}{/if}</th>
    				<th class="header small">{if isset($taxrate_label)  && $taxrate_label !=''}{$taxrate_label|escape:'htmlall':'UTF-8'}{else}{l s='Tax Rate'  mod='gwadvancedinvoice'}{/if}</th>
    				{if $display_tax_bases_in_breakdowns}
    					<th class="header small">{if isset($taxbaseprice_label)  && $taxbaseprice_label !=''}{$taxbaseprice_label|escape:'htmlall':'UTF-8'}{else}{l s='Base price'  mod='gwadvancedinvoice'}{/if}</th>
    				{/if}
    				<th class="header-right small">{if isset($taxtotal_label) && $taxtotal_label !=''}{$taxtotal_label|escape:'htmlall':'UTF-8'}{else}{l s='Total Tax'  mod='gwadvancedinvoice'}{/if}</th>
    			</tr>
    		</thead>
    		<tbody>
    		{assign var=has_line value=false}
    		{foreach $tax_breakdowns as $label => $bd}
    			{assign var=label_printed value=false}
    			{foreach $bd as $line}
    				{if $line.rate == 0}
    					{continue}
    				{/if}
    				{assign var=has_line value=true}
                    {cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
    				<tr class="{$bgcolor_class|escape:'htmlall':'UTF-8'}" >
    					<td class="white">
    						{if !$label_printed}
    							{if $label == 'product_tax'}
    								{if isset($taxproduct_label)  && $taxproduct_label !=''}{$taxproduct_label|escape:'htmlall':'UTF-8'}{else}{l s='Products'  mod='gwadvancedinvoice'}{/if}
    							{elseif $label == 'shipping_tax'}
    								{if isset($taxshipping_label)  && $taxshipping_label !=''}{$taxshipping_label|escape:'htmlall':'UTF-8'}{else}{l s='Shipping'  mod='gwadvancedinvoice'}{/if}
    							{elseif $label == 'ecotax_tax'}
    								{if isset($taxecotax_label)  && $taxecotax_label !=''}{$taxecotax_label|escape:'htmlall':'UTF-8'}{else}{l s='Ecotax' mod='gwadvancedinvoice'}{/if}
    							{elseif $label == 'wrapping_tax'}
    								{if isset($taxwrapping_label)  && $taxwrapping_label !=''}{$taxwrapping_label|escape:'htmlall':'UTF-8'}{else}{l s='Wrapping' mod='gwadvancedinvoice'}{/if}
    							{/if}
    							{assign var=label_printed value=true}
    						{/if}
    					</td>
    
    					<td class="center white">
    						{$line.rate|floatval} %
    					</td>
    
    					{if $display_tax_bases_in_breakdowns}
    						<td class="right white">
    							{if isset($is_order_slip) && $is_order_slip}- {/if}
    							{displayPrice currency=$order->id_currency price=$line.total_tax_excl}
    						</td>
    					{/if}
    
    					<td class="right white">
    						{if isset($is_order_slip) && $is_order_slip}- {/if}
    						{displayPrice currency=$order->id_currency price=$line.total_amount}
    					</td>
    				</tr>
    			{/foreach}
    		{/foreach}
    
    		{if !$has_line}
    		<tr>
    			<td class="white center" colspan="{if $display_tax_bases_in_breakdowns}4{else}3{/if}">
    				{if isset($notax_label) && $notax_label !=''}{$notax_label|escape:'htmlall':'UTF-8'}{else}{l s='No taxes' mod='gwadvancedinvoice'}{/if}
    			</td>
    		</tr>
    		{/if}
    
    		</tbody>
    	</table>
    
    {/if}
{/if}