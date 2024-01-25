{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

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