<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 30%;"><img src="/img/cms/logo_play-esp.png" alt="REDSgrafica_181221_Zerbinando_Logo_black" /></td>
<td style="width: 70%;">
<h4 class="invoice_title" style="text-align: right;"><strong>ORDINE {$reference}</strong></h4>
<p style="text-align: right; font-size: 16px;"><span style="color: #000000;"><strong>DATA {$date_add}</strong></span></p>
</td>
</tr>
</tbody>
</table>
<p></p>
<hr />
<p></p>
<table style="width: 100%; float: left;">
<tbody>
<tr>
<td style="width: 33%;" colspan="2">
<h4 class="header_title">INVOICE ADDRESS</h4>
<p>{$billing_firstname} {$billing_lastname} <br /> {$billing_company} <br /> {$billing_address1} {$billing_address2} <br /> {$billing_city} - {$billing_postcode} {$billing_state} <br /> {$billing_country}<br />tel: {$billing_phone}  {$billing_phone_mobile}<br />P.iva {$billing_vat_number} | CF {$billing_dni}</p>
</td>
<td style="width: 33%;">
<h4 class="header_title">SHIPPING ADDRESS</h4>
<p>{$delivery_firstname} {$delivery_lastname} <br /> {$delivery_company} <br /> {$delivery_address1} {$delivery_address2} <br /> {$delivery_city} - {$delivery_postcode} {$delivery_state} <br /> {$delivery_country} <br />tel: {$delivery_phone}  {$delivery_phone_mobile}</p>
</td>
<td style="width: 33%;">
<h4 class="header_title">SHIPPING</h4>
<p>{$order_carrier_name}</p>
<h4 class="header_title">PAYMENT METHOD</h4>
 {$payment}</td>
</tr>
</tbody>
</table>
<p><br /> {$products_list}</p>
<table cellpadding="7" cellspacing="0" style="width: 100%;">
<tbody>
<tr>
<td style="float: right; width: 50%;"></td>
<td style="float: right; width: 50%;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 70%; text-align: right;"><strong class="strong_item">Total Products (excluding taxes):</strong></td>
<td style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$total_products}</td>
</tr>
<tr>
<td style="text-align: right; width: 70%;"><strong class="strong_item">Total discounts (excluding taxes):</strong></td>
<td style="width: 30%; text-align: right;">-{displayPrice currency=$order->id_currency price=$total_discounts_tax_excl}</td>
</tr>
<tr>
<td style="text-align: right; width: 70%;"><strong class="strong_item"><strong class="strong_item;">Shipping (<strong class="strong_item" style="font-size: 14.6667px;">excluding taxes</strong>)<strong class="strong_item" style="font-size: 11pt;">:</strong></strong></strong></td>
<td style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$total_shipping_tax_excl}</td>
</tr>
<tr>
<td style="width: 70%; text-align: right;"><strong class="strong_item">Taxes:</strong></td>
<td style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$footer.total_taxes}</td>
</tr>
<tr>
<td style="width: 70%; text-align: right;"><strong class="strong_item">Total Products (taxes included):</strong></td>
<td style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$total_products_wt}</td>
</tr>
<tr>
<td style="text-align: right; width: 70%;"><strong class="strong_item">Shipping <strong class="strong_item">(<strong class="strong_item" style="font-size: 14.6667px;">taxes included</strong>)</strong><strong class="strong_item" style="font-size: 11pt;">:</strong><br /></strong></td>
<td style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$total_shipping_tax_incl}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr class="total_wp">
<td style="float: right; width: 50%;"></td>
<td style="float: right; width: 50%;">
<table style="width: 100%;">
<tbody>
<tr>
<td class="total_text" style="text-align: right; width: 70%;"><strong>TOTAL:</strong></td>
<td class="total_text" style="width: 30%; text-align: right;">{displayPrice currency=$order->id_currency price=$total_paid}<br /><br /></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<h4 class="header_title">CUSTOMER MESSAGE</h4>
<p>{$order_notes}</p>
<p></p>