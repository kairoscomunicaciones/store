{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if (sizeof($discounts))}
<div class="panel panel-vouchers" style="{if !sizeof($discounts)}display:none;{/if}">
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
					{if $can_edit}
					<th></th>
					{/if}
				</tr>
			</thead>
			<tbody>
				{foreach from=$discounts item=discount}
				<tr>
					<td>
                        {$discount['name']|escape:'html':'UTF-8'}
                        <div class="discount_edit_field discount_edit_field_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}">
                            <input type="text" id="edit_discount_name_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}" value="{$discount['name']|escape:'html':'UTF-8'}" />
                        </div>
                    </td>
					<td>
					{if $discount['value'] != 0.00}
						-
					{/if}
					{displayPrice price=$discount['value'] currency=$currency->id}
                    <div class="discount_edit_field discount_edit_field_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}">
                        <div class="input-group">
							<div class="input-group-addon">
								{$currency->prefix|escape:'html':'UTF-8'}
								{$currency->suffix|escape:'html':'UTF-8'}
							</div>
							<input type="text" id="edit_discount_price_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}" value="{$discount['value']|escape:'html':'UTF-8'}" />
						</div>
                    </div>
					</td>
					{if $can_edit}
					<td>
						<a class="btn btn-default pull-right delete_edit_discount_link delete_edit_discount_link_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}" rel="{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}" href="{$current_index|escape:'html':'UTF-8'}&amp;submitDeleteVoucher&amp;id_order_cart_rule={$discount['id_order_cart_rule']|escape:'html':'UTF-8'}&amp;id_order={$order->id|escape:'html':'UTF-8'}&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
							<i class="icon-trash"></i>
						</a>
                        <a class="btn btn-default pull-right edit_discount_link edit_discount_link_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}" rel="{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}"  href="#">
                            <i class="icon-pencil"></i>
                        </a>
                        <div class="discount_edit_field discount_edit_field_{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}">
                            <a class="btn btn-default pull-right save_edit_discount_link" data-idorder="" rel="{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}"  href="#">
                                <i class="icon-save"></i>{l s='Save' mod='g_ordermanager'}
                            </a>
                            <a class="btn btn-default pull-right cancel_edit_discount_link" data-idorder="" rel="{$discount['id_order_cart_rule']|escape:'html':'UTF-8'}"  href="#">
                                <i class="icon-remove text-danger"></i>{l s='Cancel' mod='g_ordermanager'}
                            </a>
                        </div>
					</td>
					{/if}
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/if}