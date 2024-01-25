<table id="wrap" style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<table style="margin-bottom: 20px; width: 100%; float: left;">
<tbody>
<tr>
<td style="float: left; width: 40%;">{$logo}</td>
<td style="width: 20%;">
<table style="width: 100%;">
<tbody>
<tr>
<td>
<h4 style="font-size: 7pt;" class="header_title">TELEPHONE &amp; FAX</h4>
</td>
</tr>
<tr>
<td style="font-size: 7pt;">+84 1234567890</td>
</tr>
<tr>
<td style="font-size: 7pt;">+84 9876543212</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 20%;">
<table style="width: 100%;">
<tbody>
<tr>
<td>
<h4 style="font-size: 7pt;" class="header_title">ADDRESS</h4>
</td>
</tr>
<tr>
<td style="font-size: 7pt;">210 Hoang Quoc Viet Street</td>
</tr>
<tr>
<td style="font-size: 7pt;">Ha Noi, Viet Nam</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 20%;">
<table style="width: 100%;">
<tbody>
<tr>
<td>
<h4 style="font-size: 7pt;" class="header_title">WEBSITE</h4>
</td>
</tr>
<tr>
<td style="font-size: 7pt;">demo@demo.com</td>
</tr>
<tr>
<td style="font-size: 7pt;">www.demodemo.com</td>
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
<td style="width: 37%;">
<h4 class="invoice_title">INVOICE</h4>
<br />{$barcode_invoice}</td>
<td style="width: 21%;">
<h4 class="header_title">BILLING ADDRESS</h4>
<p>{$billing_firstname} {$billing_lastname} <br />{$billing_address1} {$billing_address2} <br />{$billing_city} - {$billing_postcode} {$billing_state} <br />{$billing_phone} <br />{$billing_phone_mobile}</p>
</td>
<td style="width: 21%;">
<h4 class="header_title">DELIVERY ADDRESS</h4>
<p>{$delivery_firstname} {$delivery_lastname} <br />{$delivery_address1} {$delivery_address2} <br />{$delivery_city} - {$delivery_postcode} {$delivery_state} <br />{$delivery_phone} <br />{$delivery_phone_mobile}</p>
</td>
<td style="width: 21%;"><strong class="strong_item">Invoice No: </strong>{$invoice_number} <br /><strong class="strong_item">Carier: </strong>{$order_carrier_name} <br /><strong class="strong_item">Invoice Date: </strong>{$invoice_date} <br /><strong class="strong_item">Total Due: </strong>{displayPrice currency=$order->id_currency price=$total_paid}</td>
</tr>
</tbody>
</table>
<br />{$products_list}
<table style="width: 100%;" cellpadding="7" cellspacing="0">
<tbody>
<tr>
<td style="float: right; width: 60%;"></td>
<td style="float: right; width: 40%;">
<table style="width: 100%;">
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
</tbody>
</table>
</td>
</tr>
<tr class="total_wp">
<td style="float: right; width: 60%;"></td>
<td style="float: right; width: 40%;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
<td style="text-align: center; width: 40%;" class="total_text">{displayPrice currency=$order->id_currency price=$total_paid}</td>
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
<td style="width: 50%;">
<table style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Payment Method</strong> : {$payment}</td>
</tr>
<tr>
<td>
<p><strong class="strong_item">Terms &amp; Conditions</strong></p>
<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="text-align: center; width: 10%;"></td>
<td style="text-align: center; width: 40%;">
<table style="width: 80%; margin-left: auto; margin-right: auto;">
<tbody>
<tr>
<td><img src="https://inversionesrpz.com/modules/gwadvancedinvoice/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
</tr>
<tr>
<td>
<p>Nguyen Van Nham</p>
<p><strong class="strong_item">CEO &amp; Founder</strong></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="text-align: center;" colspan="3"><br /><br />
<h4 class="thanksfor">Thank for your bussiness !</h4>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>