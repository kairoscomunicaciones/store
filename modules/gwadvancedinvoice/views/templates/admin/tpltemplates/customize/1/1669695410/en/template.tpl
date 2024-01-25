<table id="wrap" style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<table style="margin-bottom: 20px; width: 100%; float: left;" class="border_bottom">
<tbody>
<tr>
<td style="width: 40%;">{$logo}</td>
<td style="width: 25%;"></td>
<td style="width: 35%;">
<table style="float: right;">
<tbody>
<tr>
<td style="width: 7%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice//views/img/imgtemplates/facebook.png" alt="" width="25" height="25" /></td>
<td style="width: 93%;">
<table cellpadding="3" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td style="font-size: 7pt; vertical-align: middle;">https://instagram.com</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 7%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice//views/img/imgtemplates/twitter.png" alt="" width="25" height="25" /></td>
<td style="width: 93%;">
<table cellpadding="3" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td style="font-size: 7pt; vertical-align: middle;">https://instagram.com</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 7%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice//views/img/imgtemplates/instagram.png" alt="" width="25" height="25" /></td>
<td style="width: 93%;">
<table cellpadding="3" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td style="font-size: 7pt; vertical-align: middle;">https://instagram.com</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<br />
<table style="width: 100%; float: left;">
<tbody>
<tr>
<td style="width: 65%;"></td>
<td style="width: 35%; text-align: right;">
<h4 class="invoice_title">INVOICE</h4>
<br />{$barcode_invoice}</td>
</tr>
</tbody>
</table>
<br />
<table style="width: 100%; float: left;">
<tbody>
<tr>
<td style="width: 21%;">
<h4 class="header_title">BILLING ADDRESS</h4>
<p>{$billing_firstname} {$billing_lastname} <br />{$billing_address1} {$billing_address2} <br />{$billing_city} - {$billing_postcode} {$billing_state} <br />{$billing_phone} <br />{$billing_phone_mobile}</p>
</td>
<td style="width: 21%;">
<h4 class="header_title">DELIVERY ADDRESS</h4>
<p>{$delivery_firstname} {$delivery_lastname} <br />{$delivery_address1} {$delivery_address2} <br />{$delivery_city} - {$delivery_postcode} {$delivery_state} <br />{$delivery_phone} <br />{$delivery_phone_mobile}</p>
</td>
<td style="width: 30%;"></td>
<td style="width: 28%;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 10%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/invoice_number.png" alt="" width="25" height="25" /></td>
<td style="width: 90%;">
<table cellpadding="2" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Invoice No: </strong>{$invoice_number}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 10%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/acount.png" alt="" width="25" height="26" /></td>
<td style="width: 90%;">
<table cellpadding="2" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Carier: </strong>{$order_carrier_name}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 10%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/invoice_date.png" alt="" width="25" height="26" /></td>
<td style="width: 90%;">
<table cellpadding="2" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Invoice Date: </strong>{$invoice_date}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 10%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/total.png" alt="" width="25" height="27" /></td>
<td style="width: 90%;">
<table cellpadding="2" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Total Due: </strong>{displayPrice currency=$order->id_currency price=$total_paid}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<br />{$products_list} <br />
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td style="width: 60%;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 6%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/terms.png" alt="" width="25" height="25" /></td>
<td style="width: 94%;">
<table style="width: 100%;" cellpadding="3" cellspacing="0">
<tbody>
<tr>
<td><strong class="strong_item">Terms &amp; Conditions</strong><br />Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 6%;"><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/payment.png" alt="" width="25" height="25" /></td>
<td style="width: 94%;">
<table style="width: 100%;" cellpadding="3" cellspacing="0">
<tbody>
<tr>
<td class="payment_method"><strong class="strong_item">Payment Method</strong> : {$payment}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 40%;">
<table style="width: 100%;" cellpadding="5" cellspacing="0">
<tbody>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
<td style="text-align: center; width: 40%;">{displayPrice currency=$order->id_currency price=$total_products}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
<td style="text-align: center; width: 40%;">-{displayPrice currency=$order->id_currency price=$total_discounts_tax_excl}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
<td style="text-align: center; width: 40%;">{displayPrice currency=$order->id_currency price=$total_shipping_tax_excl}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
<td style="text-align: center; width: 40%;">{displayPrice currency=$order->id_currency price=$footer.total_taxes}</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" class="total_wp" cellpadding="5" cellspacing="0">
<tbody>
<tr>
<td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
<td style="text-align: center; width: 40%;" class="total_title_background total_text">{displayPrice currency=$order->id_currency price=$total_paid}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<br />
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 50%;"><br /><br /><br /><br /><br />
<h4 class="thanksfor">Thank for your bussiness !</h4>
</td>
<td style="text-align: center; width: 10%;"></td>
<td style="text-align: center; width: 40%;">
<table style="width: 100%; margin-left: auto; margin-right: auto;">
<tbody>
<tr style="text-align: center;">
<td><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/founder.png" alt="" width="170" /></td>
</tr>
<tr style="text-align: center;">
<td>
<p>Nguyen Van Nham</p>
<p><strong class="strong_item">CEO &amp; Founder</strong></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>