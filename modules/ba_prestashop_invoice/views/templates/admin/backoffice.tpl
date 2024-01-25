{*
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2022 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script>
/*** since version 1.1.36+ ***/
var bainvoice_formatted = JSON.parse('{$result_tpl nofilter}');{* escape is unnecessary *}
jQuery(document).ready(function(){
	// replace old formated to new format
	var bainvoice_doc;
	/*** since version 1.1.48+ ***/
	for (property in bainvoice_formatted) {
		bainvoice_doc = $('tr#' + property+ ' td:nth-child(3) a').text(bainvoice_formatted[property]);
		if(property.indexOf("invoice_") !== -1){
			$("#documents_table tr td a[href*='generateInvoicePDF']").text(bainvoice_formatted[property]);
			// since 1.7.7.0+
			$("#orderDocumentsTabContent tr td a[href*='generateInvoicePDF']").text(bainvoice_formatted[property]);
		}
		if(property.indexOf("delivery_") !== -1){
			$("#documents_table tr td a[href*='generateDeliverySlipPDF']").text(bainvoice_formatted[property]);
			// since 1.7.7.0+
			$("#orderDocumentsTabContent tr td a[href*='generateDeliverySlipPDF']").text(bainvoice_formatted[property]);
		}
		if(property.indexOf("orderslip_") !== -1){
			$("#documents_table tr td a[href*='generateOrderSlipPDF']").text(bainvoice_formatted[property]);
			// since 1.7.7.0+
			$("#orderDocumentsTabContent tr td a[href*='generateOrderSlipPDF']").text(bainvoice_formatted[property]);
		}
	}
});
</script>