{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if isset($item_type) && $item_type !=''}
    {if $item_type == 'order_split_line'}
        {if isset($datas)}
            <div class="max_height_box">
                {if $datas}
                    {foreach $datas as $data}
                        {$data|escape:'html':'UTF-8'}{if $datas|count > 1}<span class="order_split_line"></span>{/if}
                    {/foreach}
                {/if}
            </div>
        {/if}
    {elseif $item_type == 'quickview'}
        <a href="#" class="order_field_viewproduct" title="{l s='Quickview order product(s)' mod='g_ordermanager'}"><i class="icon-eye"></i> {l s='Order products' mod='g_ordermanager'}</a>
    {elseif $item_type == 'duplicate'}
        <a title="{l s='Duplicate' mod='g_ordermanager'}" class="duplicateorder" rel="{$id|intval}" href="#copyForm">
        	<i class="icon-copy"></i> {l s='Duplicate' mod='g_ordermanager'}
        </a>
    {elseif $item_type == 'color_field'}
        <span class="label color_field" style="background-color:{$backgroundcolor|escape:'html':'UTF-8'};color:{$color|escape:'html':'UTF-8'};">{$statename|escape:'html':'UTF-8'}</span>
    {elseif $item_type == 'badgesuccess'}
        <span class="badge badge-success">{$total_paid_tax_incl|escape:'html':'UTF-8'}</span>
    
    {elseif $item_type == 'addressformat'}
        {displayAddressDetail address=$address newLine='<br />'}
		{if $address->other}
			<hr />{$address->other|escape:'html':'UTF-8'}<br />
		{/if}
    {elseif $item_type == 'loginascustomer'}
        <a target="_blank" title="{l s='Login As Customer' mod='g_ordermanager'}" class="loginascustomer"  href="{$loginascustomer_link|escape:'html':'UTF-8'}">
            <i class="icon icon-key"></i> {l s='Login As Customer' mod='g_ordermanager'}
        </a>
    {elseif $item_type == 'printshippinglabel'}
        <a target="_blank" title="{l s='Shipping Label' mod='g_ordermanager'}" class="printshippinglabel"  href="{$printshippinglabel_link|escape:'html':'UTF-8'}">
            <i class="icon-truck icon fa-truck"></i> {l s='Shipping Label' mod='g_ordermanager'}
        </a>
    {/if}
{/if}