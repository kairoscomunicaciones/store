<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:36
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/customize/1/1669695277/ve/product_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ec0726639_11468540',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '490af027ab4906f5158b5f4c3ae151184d71a440' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/customize/1/1669695277/ve/product_list.tpl',
      1 => 1700942423,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ec0726639_11468540 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
?>
<table class="product" width="100%" cellpadding="5" cellspacing="0">
    <thead>
    	<tr>
                            <th class="product header small  alignleft " width="29%">Producto</th>
                            <th class="product header small  aligncenter " width="23%">Precio Unitario</th>
                            <th class="product header small  aligncenter " width="23%">Cantidad</th>
                            <th class="product header small  aligncenter " width="25%">Total</th>
                    </tr>
    </thead>
	<tbody>
    
    <?php $_smarty_tpl->_assignInScope('colCount', "4");?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order_details']->value, 'order_detail');
$_smarty_tpl->tpl_vars['order_detail']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['order_detail']->value) {
$_smarty_tpl->tpl_vars['order_detail']->do_else = false;
?>
		<?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

		<tr class="product <?php echo $_smarty_tpl->tpl_vars['bgcolor_class']->value;?>
">
    
			               <td class="product  alignleft "  width="29%"><p class="product_name"><?php echo $_smarty_tpl->tpl_vars['order_detail']->value['product_name'];?>
</p><?php echo $_smarty_tpl->tpl_vars['order_detail']->value['description_short'];?>
</td>
                           <td class="product  aligncenter "  width="23%"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['order_detail']->value['unit_price_tax_excl_including_ecotax']),$_smarty_tpl ) );?>
</td>
                           <td class="product  aligncenter "  width="23%"><?php echo $_smarty_tpl->tpl_vars['order_detail']->value['product_quantity'];?>
</td>
                           <td class="product  aligncenter "  width="25%"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['order_detail']->value['total_price_tax_excl_including_ecotax']),$_smarty_tpl ) );?>
</td>
            		</tr>
    
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order_detail']->value['customizedDatas'], 'customizationPerAddress');
$_smarty_tpl->tpl_vars['customizationPerAddress']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customizationPerAddress']->value) {
$_smarty_tpl->tpl_vars['customizationPerAddress']->do_else = false;
?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customizationPerAddress']->value, 'customization', false, 'customizationId');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customizationId']->value => $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
				<tr class="product customization_data <?php echo $_smarty_tpl->tpl_vars['bgcolor_class']->value;?>
">
					<?php if ($_smarty_tpl->tpl_vars['colCount']->value > 1) {?>
                    <td class="product center">
						( x<?php if ($_smarty_tpl->tpl_vars['customization']->value['quantity'] == 0) {?>1<?php } else {
echo $_smarty_tpl->tpl_vars['customization']->value['quantity'];
}?>)
					</td>
                    <?php }?>
                    <td  class="product" colspan="<?php if ($_smarty_tpl->tpl_vars['colCount']->value > 1) {
echo $_smarty_tpl->tpl_vars['colCount']->value-1;
}?>">
                        <?php if ($_smarty_tpl->tpl_vars['colCount']->value == 1) {?>(x <?php if ($_smarty_tpl->tpl_vars['customization']->value['quantity'] == 0) {?>1<?php } else {
echo $_smarty_tpl->tpl_vars['customization']->value['quantity'];
}?>)<?php }?>
						<?php if ((isset($_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_TEXTFIELD_') ? constant('_CUSTOMIZE_TEXTFIELD_') : null)])) && count($_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_TEXTFIELD_') ? constant('_CUSTOMIZE_TEXTFIELD_') : null)]) > 0) {?>
							<table style="width: 100%;">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_TEXTFIELD_') ? constant('_CUSTOMIZE_TEXTFIELD_') : null)], 'customization_infos');
$_smarty_tpl->tpl_vars['customization_infos']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customization_infos']->value) {
$_smarty_tpl->tpl_vars['customization_infos']->do_else = false;
?>
									<tr>
										<td style="width: 25%;">
											<?php ob_start();
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s:','pdf'=>'true','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
$_prefixVariable1 = ob_get_clean();
echo sprintf($_prefixVariable1,$_smarty_tpl->tpl_vars['customization_infos']->value['name']);?>

										</td>
										<td><?php echo $_smarty_tpl->tpl_vars['customization_infos']->value['value'];?>
</td>
									</tr>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</table>
						<?php }?>

						<?php if ((isset($_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_FILE_') ? constant('_CUSTOMIZE_FILE_') : null)])) && count($_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_FILE_') ? constant('_CUSTOMIZE_FILE_') : null)]) > 0) {?>
							<table style="width: 100%;">
								<tr>
									<td style="width: 70%;"><?php if ((isset($_smarty_tpl->tpl_vars['gimage_label']->value)) && $_smarty_tpl->tpl_vars['gimage_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gimage_label']->value,'html','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'image(s):','pdf'=>'true','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></td>
									<td><?php echo count($_smarty_tpl->tpl_vars['customization']->value['datas'][(defined('_CUSTOMIZE_FILE_') ? constant('_CUSTOMIZE_FILE_') : null)]);?>
</td>
								</tr>
							</table>
						<?php }?>
					</td>
				</tr>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php $_smarty_tpl->_assignInScope('shipping_discount_tax_incl', "0");?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['cart_rules']->value, 'cart_rule', false, NULL, 'cart_rules_loop', array (
  'first' => true,
  'index' => true,
));
$_smarty_tpl->tpl_vars['cart_rule']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cart_rule']->value) {
$_smarty_tpl->tpl_vars['cart_rule']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_cart_rules_loop']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_cart_rules_loop']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_cart_rules_loop']->value['index'];
?>
		<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_cart_rules_loop']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_cart_rules_loop']->value['first'] : null)) {?>
		<tr class="discount">
			<th class="header" colspan="<?php echo $_smarty_tpl->tpl_vars['colCount']->value;?>
">
                <?php if ((isset($_smarty_tpl->tpl_vars['gdiscount_label']->value)) && $_smarty_tpl->tpl_vars['gdiscount_label']->value != '') {?>
                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gdiscount_label']->value,'html','UTF-8' ));?>

                <?php } else { ?>
				<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discounts','pdf'=>'true','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>

                <?php }?>
			</th>
		</tr>
		<?php }?>
		<tr class="discount">
            <?php if ($_smarty_tpl->tpl_vars['colCount']->value > 1) {?>
			<td class="white right" colspan="<?php echo $_smarty_tpl->tpl_vars['colCount']->value-1;?>
">
				<?php echo $_smarty_tpl->tpl_vars['cart_rule']->value['name'];?>

			</td>
            <?php }?>
			<td class="right white">
                <?php if ($_smarty_tpl->tpl_vars['colCount']->value == 1) {
echo $_smarty_tpl->tpl_vars['cart_rule']->value['name'];?>
:<?php }?>
                
                                
				- <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['cart_rule']->value['value']),$_smarty_tpl ) );?>

                
                                
			</td>
		</tr>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    
    	</tbody>

</table>
<?php }
}
