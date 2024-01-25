{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{* Generate HTML code for printing Invoice Icon with link *}
<span class="btn-group-action">
	<span class="btn-group">
        <a class="btn btn-default print_order_link" href="submitPrintOrder" rel="{$order->id|escape:'htmlall':'UTF-8'}">
			<i class="icon-print"></i>
		</a>
	{if Configuration::get('PS_INVOICE') && $order->invoice_number}
		<a class="btn btn-default _blank" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateInvoicePDF&amp;id_order={$order->id|escape:'htmlall':'UTF-8'}">
			<i class="icon-file-text"></i>
		</a>
	{/if}
	{* Generate HTML code for printing Delivery Icon with link *}
	{if $order->delivery_number}
		<a class="btn btn-default _blank" href="{$link->getAdminLink('AdminPdf')|escape:'html':'UTF-8'}&amp;submitAction=generateDeliverySlipPDF&amp;id_order={$order->id|escape:'htmlall':'UTF-8'}">
			<i class="icon-truck"></i>
		</a>
	{/if}
	</span>
</span>