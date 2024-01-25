<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:36
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/customize/1/1669695277/ve/template.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ec0760ee7_70388513',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bfa1403bb0e3adf33df95d61ca0f8d13ed319660' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/customize/1/1669695277/ve/template.tpl',
      1 => 1700942423,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ec0760ee7_70388513 (Smarty_Internal_Template $_smarty_tpl) {
?><table id="wrap" style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<table style="margin-bottom: 20px; width: 100%; float: left;">
<tbody>
<tr>
<td style="float: left; width: 40%;"><?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
</td>
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
<p><?php echo $_smarty_tpl->tpl_vars['billing_firstname']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['billing_lastname']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['billing_address1']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['billing_address2']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['billing_city']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['billing_postcode']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['billing_state']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['billing_phone']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['billing_phone_mobile']->value;?>
</p>
</td>
<td style="width: 21%;">
<h4 class="header_title">DIRECCIÓN DE COBRO</h4>
<p><?php echo $_smarty_tpl->tpl_vars['delivery_firstname']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['delivery_lastname']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['delivery_address1']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['delivery_address2']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['delivery_city']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['delivery_postcode']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['delivery_state']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['delivery_phone']->value;?>
 <br /><?php echo $_smarty_tpl->tpl_vars['delivery_phone_mobile']->value;?>
</p>
</td>
<td style="width: 21%;"><strong class="strong_item">Nota de Entrega No: </strong><?php echo $_smarty_tpl->tpl_vars['invoice_number']->value;?>
 <br /><strong class="strong_item">Transportista: </strong><?php echo $_smarty_tpl->tpl_vars['order_carrier_name']->value;?>
 <br /><strong class="strong_item">Fecha: </strong><?php echo $_smarty_tpl->tpl_vars['invoice_date']->value;?>
 <br /><strong class="strong_item">Total pagado: </strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['total_paid']->value),$_smarty_tpl ) );?>
</td>
</tr>
</tbody>
</table>
<br /><?php echo $_smarty_tpl->tpl_vars['products_list']->value;?>

<table style="width: 100%;" cellpadding="7" cellspacing="0">
<tbody>
<tr>
<td style="float: right; width: 60%;"></td>
<td style="float: right; width: 40%;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Total Compra: </strong></td>
<td style="text-align: center; width: 40%;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['total_products']->value),$_smarty_tpl ) );?>
</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Descuentos: </strong></td>
<td style="text-align: center; width: 40%;">-<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['total_discounts_tax_excl']->value),$_smarty_tpl ) );?>
</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Costo de envío : </strong></td>
<td style="text-align: center; width: 40%;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['total_shipping_tax_excl']->value),$_smarty_tpl ) );?>
</td>
</tr>
<tr>
<td style="text-align: right; width: 60%;"><strong class="strong_item">Impuestos : </strong></td>
<td style="text-align: center; width: 40%;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['footer']->value['total_taxes']),$_smarty_tpl ) );?>
</td>
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
<td style="text-align: center; width: 40%;" class="total_text"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['total_paid']->value),$_smarty_tpl ) );?>
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
<td style="width: 50%;">
<table style="width: 100%;">
<tbody>
<tr>
<td><strong class="strong_item">Método de Pago</strong> : <?php echo $_smarty_tpl->tpl_vars['payment']->value;?>
</td>
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
</table><?php }
}
