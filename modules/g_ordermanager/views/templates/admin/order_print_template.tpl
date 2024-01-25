{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<style>
    table
    {
        width:100%;
    }
    table.table_border{
        border:1pt solid #ccc;
    }
    th,.total_title{
        background-color:#ccc;
        font-weight:both;
    }
    .even
    {
        background-color:#fdfdfd;
    }
    .odd
    {
        background-color:#f0f0f0;
    }
    .text-right{
        text-align:right;
    }
</style>
<table  style="width: 100%; font-size:9pt;"  cellpadding="4" cellspacing="0">
    <tr>
        <td style=" width: 30%;"><img src="{$logo|escape:'html':'UTF-8'}" alt="" /></td>
        <td style=" width: 20%;"></td>
        <td style=" width: 20%;"></td>
        <td style="float: right; width: 30%;background-color:#ccc;">
            <table style="float:right;text-align:right;">
                <tr>
                    <td style="float: right;font-size:15pt;">#{$order->id|escape:'html':'UTF-8'}</td>
                </tr>
                <tr>
                    <td>{dateFormat date=$order->date_add full=false}</td>
                </tr>
                <tr>
                    <td>{displayPrice price=$order->total_paid_tax_incl currency=$currency->id}</td>
                </tr>
            </table>
            
        </td>
    </tr>
    <tr>
        <td colspan="2"><h4>{l s='Status' mod='g_ordermanager'}</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
                    <tr>
                        <th>{l s='Status' mod='g_ordermanager'}</th>
                        <th>{l s='Employee' mod='g_ordermanager'}</th>
                        <th>{l s='Date' mod='g_ordermanager'}</th>
                    </tr>
                </thead>
                <tbody>
					{foreach from=$history item=row key=key}
						{if ($key == 0)}
							<tr>
                                {assign var="text_color" value='white'}
                                {if isset($row['text-color'])}
								    {assign var="text_color" value=$row['text-color']}
                                {/if}
                                <td style="text-align:left;background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{$row['ostate_name']|stripslashes}</td>
								<td style="text-align:center;background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes} {$row['employee_lastname']|stripslashes}{/if}</td>
								<td style="text-align:right;background-color:{$row['color']|escape:'html':'UTF-8'};color:{$text_color|escape:'html':'UTF-8'}">{dateFormat date=$row['date_add'] full=true}</td>
							</tr>
						{else}
							<tr class="{cycle values="odd,even"}">
								<td style="text-align:left;">{$row['ostate_name']|stripslashes|escape:'html':'UTF-8'}</td>
								<td style="text-align:center;">{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes|escape:'html':'UTF-8'} {$row['employee_lastname']|stripslashes}{else}&nbsp;{/if}</td>
								<td style="text-align:right;">{dateFormat date=$row['date_add'] full=true}</td>
							</tr>
						{/if}
					{/foreach}
				</tbody>
			</table>
        
        </td>
        <td colspan="2"><h4>{l s='Payment' mod='g_ordermanager'}</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
    			<thead>
    				<tr>
    					<th class="" >{l s='Date' mod='g_ordermanager'}</th>
    					<th >{l s='Payment method' mod='g_ordermanager'}</th>
    					<th >{l s='Transaction ID' mod='g_ordermanager'}</th>
    					<th >{l s='Amount' mod='g_ordermanager'}</th>
    				</tr>
    			</thead>
    			<tbody>
    				{foreach from=$order->getOrderPaymentCollection() item=payment}
    				<tr class="{cycle values="odd,even"}">
    					<td>{dateFormat date=$payment->date_add full=true}</td>
    					<td>{$payment->payment_method|escape:'html':'UTF-8'}</td>
    					<td>{$payment->transaction_id|escape:'html':'UTF-8'}</td>
    					<td>{displayPrice price=$payment->amount currency=$payment->id_currency}</td>
    				</tr>
    				{/foreach}
    			</tbody>
    		</table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4>{l s='Shipping' mod='g_ordermanager'}</h4>
            <table class="table_border" cellpadding="4" cellspacing="0">
        		<thead>
        			<tr>
        				<th>
        					<span class="title_box ">{l s='Date' mod='g_ordermanager'}</span>
        				</th>
        				<th>
        					<span class="title_box ">{l s='Carrier' mod='g_ordermanager'}</span>
        				</th>
        				<th>
        					<span class="title_box ">{l s='Weight' mod='g_ordermanager'}</span>
        				</th>
        				<th>
        					<span class="title_box ">{l s='Shipping cost' mod='g_ordermanager'}</span>
        				</th>
        				<th>
        					<span class="title_box ">{l s='Tracking number' mod='g_ordermanager'}</span>
        				</th>
        			</tr>
        		</thead>
        		<tbody>
        			{foreach from=$order->getShipping() item=line}
        			<tr class="{cycle values="odd,even"}">
        				<td>{dateFormat date=$line.date_add full=true}</td>
        				<td>{$line.carrier_name|escape:'html':'UTF-8'}</td>
        				<td class="weight">{$line.weight|string_format:"%.3f"|escape:'html':'UTF-8'} {Configuration::get('PS_WEIGHT_UNIT')|escape:'html':'UTF-8'}</td>
        				<td class="center">
        					{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
        						{displayPrice price=$line.shipping_cost_tax_incl currency=$currency->id}
        					{else}
        						{displayPrice price=$line.shipping_cost_tax_excl currency=$currency->id}
        					{/if}
        				</td>
        				<td>
        					<span class="shipping_number_show">{if $line.url && $line.tracking_number}<a class="_blank" href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number|escape:'html':'UTF-8'}</a>{else}{$line.tracking_number|escape:'html':'UTF-8'}{/if}</span>
        				</td>
        			</tr>
        			{/foreach}
        		</tbody>
        	</table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4>{l s='Customer' mod='g_ordermanager'}</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
                    <tr>
                        <th>{l s='Name' mod='g_ordermanager'}</th>
                        <th >{l s='Email' mod='g_ordermanager'}</th>
                        <th>{l s='Account registered' mod='g_ordermanager'}</th>
                        <th>{l s='Orders' mod='g_ordermanager'}</th>
                        <th>{l s='Total' mod='g_ordermanager'}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="even">
                        <td>{$customer->firstname|escape:'html':'UTF-8'} {$customer->lastname|escape:'html':'UTF-8'}</td>
                        <td>{$customer->email|escape:'html':'UTF-8'}</td>
                        <td>{dateFormat date=$customer->date_add full=true}</td>
                        <td>{$customerStats['nb_orders']|intval}</td>
                        <td>{displayPrice price=Tools::ps_round(Tools::convertPrice($customerStats['total_orders'], $currency), 2) currency=$currency->id}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
                <thead>
                    <tr>
                        <th>{l s='Shipping address' mod='g_ordermanager'}</th>
                        <th>{l s='Invoice address' mod='g_ordermanager'}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{displayAddressDetail address=$addresses.delivery newLine='<br />'}
            				{if $addresses.delivery->other}
            					<hr />{$addresses.delivery->other|escape:'html':'UTF-8'}<br />
            				{/if}
                        </td>
                        <td>{displayAddressDetail address=$addresses.invoice newLine='<br />'}
            				{if $addresses.invoice->other}
            					<hr />{$addresses.invoice->other|escape:'html':'UTF-8'}<br />
            				{/if}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4>{l s='Product' mod='g_ordermanager'}</h4>
            <table style="font-size:8pt;max-width:100%;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
					<tr>
                        <th style="width:7%;"></th>
						<th style="width:40%;"><span class="title_box ">{l s='Product' mod='g_ordermanager'}</span></th>
						<th style="width:10%;">
							<span class="title_box ">{l s='Unit Price' mod='g_ordermanager'}</span>
						</th>
						<th class="text-center"  style="width:10%;"><span class="title_box ">{l s='Qty' mod='g_ordermanager'}</span></th>
						{if isset($stock_management) &&   $stock_management}<th class="text-center"><span class="title_box ">{l s='Available quantity' mod='g_ordermanager'}</span></th>{/if}
                        <th>
							<span class="title_box ">{l s='Total' mod='g_ordermanager'}</span>
						</th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$products item=product key=k}
					{if $product['customizedDatas']}
                        {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                        	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
                        {else}
                        	{assign var=product_price value=$product['unit_price_tax_incl']}
                        {/if}
                        	<tr class="{cycle values="odd,even"}">
                                <td style="width:7%;">{if isset($product.image) && $product.image->id}{$product.image_tag nofilter}{/if}</td> {* <img /> tag , no need to escape *}
                        		<td><span class="productName">{$product['product_name']|escape:'html':'UTF-8'} - {l s='Customized' mod='g_ordermanager'}</span><br />
                        			{if ($product['product_reference'])}{l s='Reference number:' mod='g_ordermanager'} {$product['product_reference']|escape:'html':'UTF-8'}<br />{/if}
                        			{if ($product['product_supplier_reference'])}{l s='Supplier reference:' mod='g_ordermanager'} {$product['product_supplier_reference']|escape:'html':'UTF-8'}{/if}
                        		</td>
                        		<td><span class="product_price_show">{displayPrice price=$product_price currency=$currency->id|intval}</span></td>
                        		<td class="productQuantity text-center">{$product['customizationQuantityTotal']|escape:'html':'UTF-8'}</td>
                                {if isset($stock_management) &&   $stock_management}<td class="text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
                        		
                                <td class="total_product">
                        		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                        			{displayPrice price=Tools::ps_round($product['product_price'] * $product['customizationQuantityTotal'], 2) currency=$currency->id|intval}
                        		{else}
                        			{displayPrice price=Tools::ps_round($product['product_price_wt'] * $product['customizationQuantityTotal'], 2) currency=$currency->id|intval}
                        		{/if}
                        		</td>
                        	</tr>
                        	{foreach $product['customizedDatas'] as $customizationPerAddress}
                        		{foreach $customizationPerAddress as $customizationId => $customization}
                        			<tr class="{cycle values="odd,even"}">
                        				<td colspan="2">
                        				    <div class="form-horizontal">
                        						{foreach $customization.datas as $type => $datas}
                        							{if ($type == Product::CUSTOMIZE_FILE)}
                                                    {foreach from=$datas item=data}{if $data['name']}{$data['name']|escape:'html':'UTF-8'}{else}{l s='Picture #' mod='g_ordermanager'}{$data@iteration|escape:'html':'UTF-8'}{/if}{/foreach}
                        							{elseif ($type == Product::CUSTOMIZE_TEXTFIELD)}
                                                    {foreach from=$datas item=data}{if $data['name']}{l s='%s' sprintf=$data['name'] mod='g_ordermanager'}{else}{l s='Text #%s' sprintf=$data@iteration  mod='g_ordermanager'}{/if}:{$data['value']|escape:'html':'UTF-8'}{/foreach}
                        							{/if}
                                                    <br/>
                        						{/foreach}
                        					</div>
                        				</td>
                        				<td>-</td>
                        				<td class="productQuantity text-center">
                        					<span class="product_quantity_show{if (int)$customization['quantity'] > 1} red bold{/if}">{$customization['quantity']|escape:'html':'UTF-8'}</span>
                        				</td>
                                        <td class="text-center">-</td>
                                        
                        				<td class="total_product">
                        					{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                        						{displayPrice price=Tools::ps_round($product['product_price'] * $customization['quantity'], 2) currency=$currency->id|intval}
                        					{else}
                        						{displayPrice price=Tools::ps_round($product['product_price_wt'] * $customization['quantity'], 2) currency=$currency->id|intval}
                        					{/if}
                        				</td>
                        			</tr>
                        		{/foreach}
                        	{/foreach}
                        {/if}
                        {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                        	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
                        {else}
                        	{assign var=product_price value=$product['unit_price_tax_incl']}
                        {/if}
                        
                        {if ($product['product_quantity'] > $product['customized_product_quantity'])|escape:'html':'UTF-8'}
                        <tr class="{cycle values="odd,even"}">
                            <td  style="width:7%;">{if isset($product.image) && $product.image->id}{$product.image_tag nofilter}{/if}</td> {* <img /> tag content. No need to escape*}
                        	<td><span class="productName">{$product['product_name']|escape:'html':'UTF-8'}</span><br />
                    			{if $product.product_reference}{l s='Reference number:' mod='g_ordermanager'} {$product.product_reference|escape:'html':'UTF-8'}<br />{/if}
                    			{if $product.product_supplier_reference}{l s='Supplier reference:' mod='g_ordermanager'} {$product.product_supplier_reference|escape:'html':'UTF-8'}{/if}
                        	</td>
                        	<td>
                        		<span class="product_price_show">{displayPrice price=$product_price currency=$currency->id}</span>
                        	</td>
                        	<td class="productQuantity text-center">
                        		<span class="product_quantity_show{if (int)$product['product_quantity'] - (int)$product['customized_product_quantity'] > 1} badge{/if}">{(int)$product['product_quantity'] - (int)$product['customized_product_quantity']|escape:'html':'UTF-8'}</span>
                        	</td>
                        	{if isset($stock_management) &&   $stock_management}<td class="productQuantity product_stock text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
                        	
                            <td class="total_product">
                        		{displayPrice price=(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal'])) currency=$currency->id}
                        	</td>
                        </tr>
                        {/if}
				{/foreach}
				</tbody>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="2"  style="width:50%;">{if $discounts}<br />
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
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
            		<tr class="{cycle values="odd,even"}">
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
            {/if}{if isset($notes) && $notes}<table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
            	<thead>
            		<tr>
            			<th>
            				<span class="title_box ">
            					{l s='Notes' mod='g_ordermanager'}
            				</span>
            			</th>
            		</tr>
            	</thead>
            	<tbody>
            		{foreach from=$notes item=note}
            		<tr class="{cycle values="odd,even"}">
            			<td>{$note['notes']|escape:'html':'UTF-8'}</td>
            		</tr>
            		{/foreach}
            	</tbody>
            </table>{/if}
        </td>
        <td colspan="2" style="width:50%;"><br />
            <table class="table_border" cellpadding="4" cellspacing="0">
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
				<tr class="odd">
					<td class="text-right total_title">{l s='Products:' mod='g_ordermanager'}</td>
					<td class="amount text-right nowrap">
						{displayPrice price=$order_product_price currency=$currency->id}
					</td>
				</tr>{if $order->total_discounts_tax_incl != 0}
				<tr  class="odd">
					<td class="text-right total_title">{l s='Discounts' mod='g_ordermanager'}</td>
					<td class="amount text-right nowrap">
						-{displayPrice price=$order_discount_price currency=$currency->id}
					</td>
				</tr>
                {/if}{if $order->total_wrapping_tax_incl != 0}
				<tr class="odd">
					<td class="text-right total_title">{l s='Wrapping' mod='g_ordermanager'}</td>
					<td class="amount text-right nowrap">
						{displayPrice price=$order_wrapping_price currency=$currency->id}
					</td>
				</tr>
                {/if}
				<tr class="odd">
					<td class="text-right total_title">{l s='Shipping' mod='g_ordermanager'}</td>
					<td class="amount text-right nowrap" >
						{displayPrice price=$order_shipping_price currency=$currency->id}
					</td>
				</tr>
				{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
				<tr class="odd">
					<td class="text-right total_title">{l s='Taxes' mod='g_ordermanager'}</td>
					<td class="amount text-right nowrap" >{displayPrice price=($order->total_paid_tax_incl-$order->total_paid_tax_excl) currency=$currency->id}</td>
				</tr>
				{/if}
				{assign var=order_total_price value=$order->total_paid_tax_incl}
				<tr class="odd">
					<td class="text-right total_title"><strong>{l s='Total' mod='g_ordermanager'}</strong></td>
					<td class="amount text-right nowrap ">
						<strong>{displayPrice price=$order_total_price currency=$currency->id}</strong>
					</td>
				</tr>
			</table>
        </td>
    </tr>
</table>