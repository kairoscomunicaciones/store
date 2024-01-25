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
<h4 style="font-size: 7pt;" class="header_title">TELÉFONO</h4>
</td>
</tr>
<tr>
<td style="font-size: 7pt;">WHATSAPP 0412-5930109</td>
</tr>
<tr>
<td style="font-size: 7pt;"></td>
</tr>
</tbody>
</table>
</td>
<td style="width: 20%;">
<table style="width: 100%;">
<tbody>
<tr>
<td>
<h4 style="font-size: 7pt;" class="header_title">DIRECCIÓN</h4>
</td>
</tr>
<tr>
<td style="font-size: 7pt;">Los Flores de Catia</td>
</tr>
<tr>
<td style="font-size: 7pt;">Caracas</td>
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
<td style="font-size: 7pt;">admin@inversionesrpz.com</td>
</tr>
<tr>
<td style="font-size: 7pt;">www.inversionesrpz.com</td>
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
<h4 class="invoice_title">NOTA DE ENTREGA</h4>
<br /><br /></td>
<td style="width: 21%;">
<h4 class="header_title">DIRECCIÓN  DE ENVÍO</h4>
<p>{$billing_firstname} {$billing_lastname} <br />{$billing_address1} {$billing_address2} <br />{$billing_city} - {$billing_postcode} {$billing_state} <br />{$billing_phone} <br />{$billing_phone_mobile}</p>
</td>
<td style="width: 21%;">
<h4 class="header_title">DIRECCIÓN DE COBRO</h4>
<p>{$delivery_firstname} {$delivery_lastname} <br />{$delivery_address1} {$delivery_address2} <br />{$delivery_city} - {$delivery_postcode} {$delivery_state} <br />{$delivery_phone} <br />{$delivery_phone_mobile}</p>
</td>
<td style="width: 21%;"><strong class="strong_item">Nota de Entrega No: </strong>{$invoice_number} <br /><strong class="strong_item">Transportista: </strong>{$order_carrier_name} <br /><strong class="strong_item">Fecha: </strong>{$invoice_date} <br /><strong class="strong_item">Total pagado: </strong>{displayPrice currency=$order->id_currency price=$total_paid}</td>
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
<td style="text-align: right; width: 60%;"><strong class="strong_item">Total Compra: </strong></td>
<td style="text-align: center; width: 40%;">{displayPrice currency=$order->id_currency price=$total_products}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Descuentos: </strong></td>
<td style="text-align: center; width: 40%;">-{displayPrice currency=$order->id_currency price=$total_discounts_tax_excl}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Costo de envío : </strong></td>
<td style="text-align: center; width: 40%;">{displayPrice currency=$order->id_currency price=$total_shipping_tax_excl}</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Impuestos : </strong></td>
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
<td><strong class="strong_item">Método de Pago</strong> : {$payment}</td>
</tr>
<tr>
<td>
<p><strong class="strong_item">Términos y Condiciones</strong></p>
<p>El cliente acepta recibir la mercancía en los términos y condiciones acordados durante la compra.</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="text-align: center; width: 10%;"></td>
<td style="text-align: center; width: 40%;">
<table style="height: 235px; width: 264px; margin-left: auto; margin-right: auto;">
<tbody>
<tr>
<td><img src="https://inversionesrpz.com/img/cms/FirmaRPZ.jpg" alt="" width="166" height="193" /></td>
</tr>
<tr>
<td>
<p>Administración</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="text-align: center;" colspan="3"><br /><br />
<h4 class="thanksfor">¡Gracias por su compra!</h4>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>