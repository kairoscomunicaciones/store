<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:36
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/base/tax-tab.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ec0703df4_40649145',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd73813c40e6da5eed138e3ab801186354ea25266' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/tpltemplates/base/tax-tab.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ec0703df4_40649145 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
?>

<?php if ($_smarty_tpl->tpl_vars['version']->value == 160) {
if ($_smarty_tpl->tpl_vars['tax_exempt']->value || (((isset($_smarty_tpl->tpl_vars['product_tax_breakdown']->value)) && count($_smarty_tpl->tpl_vars['product_tax_breakdown']->value) > 0) || ((isset($_smarty_tpl->tpl_vars['ecotax_tax_breakdown']->value)) && count($_smarty_tpl->tpl_vars['ecotax_tax_breakdown']->value) > 0))) {
if ($_smarty_tpl->tpl_vars['tax_exempt']->value) {
if ((isset($_smarty_tpl->tpl_vars['taxexempt_label']->value)) && $_smarty_tpl->tpl_vars['taxexempt_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxexempt_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Exempt of VAT according section 259B of the General Tax Code.','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>

<?php }
} else { ?><table id="tax-tab" width="100%" cellpadding="5" cellspacing="0">
		<thead>
        <tr style="line-height:5px;">
			<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxdetail_label']->value)) && $_smarty_tpl->tpl_vars['taxdetail_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxdetail_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tax Detail','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
			<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxrate_label']->value)) && $_smarty_tpl->tpl_vars['taxrate_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxrate_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tax Rate','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
			<?php if (!$_smarty_tpl->tpl_vars['use_one_after_another_method']->value) {?>
				<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxtotalexcl_label']->value)) && $_smarty_tpl->tpl_vars['taxtotalexcl_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxtotalexcl_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total Tax Excl','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
			<?php }?>
			<th class="header-right small"><?php if ((isset($_smarty_tpl->tpl_vars['taxtotal_label']->value)) && $_smarty_tpl->tpl_vars['taxtotal_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxtotal_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total Tax','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
		</tr>
        </thead>
        <tbody>
		<?php if ((isset($_smarty_tpl->tpl_vars['product_tax_breakdown']->value))) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product_tax_breakdown']->value, 'product_tax_infos', false, 'rate');
$_smarty_tpl->tpl_vars['product_tax_infos']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['rate']->value => $_smarty_tpl->tpl_vars['product_tax_infos']->value) {
$_smarty_tpl->tpl_vars['product_tax_infos']->do_else = false;
?>
            <?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

			<tr class="product <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['bgcolor_class']->value,'htmlall','UTF-8' ));?>
" style="line-height:6px;">
			 <td>
				<?php if (!(isset($_smarty_tpl->tpl_vars['pdf_product_tax_written']->value))) {?>
					<?php if ((isset($_smarty_tpl->tpl_vars['taxproduct_label']->value)) && $_smarty_tpl->tpl_vars['taxproduct_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxproduct_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
					<?php $_smarty_tpl->_assignInScope('pdf_product_tax_written', 1);?>
				<?php }?>
			</td>
			 <td style="text-align: right;"><?php echo floatval($_smarty_tpl->tpl_vars['rate']->value);?>
 %</td>
			<?php if (!$_smarty_tpl->tpl_vars['use_one_after_another_method']->value) {?>
			 <td style=" text-align: right;">
				 <?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['product_tax_infos']->value['total_price_tax_excl']),$_smarty_tpl ) );?>

			 </td>
			<?php }?>
			 <td style="text-align: right;"><?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['product_tax_infos']->value['total_amount']),$_smarty_tpl ) );?>
</td>
			</tr>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<?php }?>

			<?php if ((isset($_smarty_tpl->tpl_vars['shipping_tax_breakdown']->value))) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['shipping_tax_breakdown']->value, 'shipping_tax_infos');
$_smarty_tpl->tpl_vars['shipping_tax_infos']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['shipping_tax_infos']->value) {
$_smarty_tpl->tpl_vars['shipping_tax_infos']->do_else = false;
?>
            <?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

			<tr class="product <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['bgcolor_class']->value,'htmlall','UTF-8' ));?>
" style="line-height:6px;">
			 <td>
				<?php if (!(isset($_smarty_tpl->tpl_vars['pdf_shipping_tax_written']->value))) {?>
					<?php if ((isset($_smarty_tpl->tpl_vars['taxshipping_label']->value)) && $_smarty_tpl->tpl_vars['taxshipping_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxshipping_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
					<?php $_smarty_tpl->_assignInScope('pdf_shipping_tax_written', 1);?>
				<?php }?>
			 </td>
			 <td style=" text-align: right;"><?php echo floatval($_smarty_tpl->tpl_vars['shipping_tax_infos']->value['rate']);?>
 %</td>
			<?php if (!$_smarty_tpl->tpl_vars['use_one_after_another_method']->value) {?>
				 <td style=" text-align: right;"><?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['shipping_tax_infos']->value['total_tax_excl']),$_smarty_tpl ) );?>
</td>
			<?php }?>
			 <td style=" text-align: right;"><?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['shipping_tax_infos']->value['total_amount']),$_smarty_tpl ) );?>
</td>
			</tr>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php }?>

		<?php if ((isset($_smarty_tpl->tpl_vars['ecotax_tax_breakdown']->value))) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ecotax_tax_breakdown']->value, 'ecotax_tax_infos');
$_smarty_tpl->tpl_vars['ecotax_tax_infos']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ecotax_tax_infos']->value) {
$_smarty_tpl->tpl_vars['ecotax_tax_infos']->do_else = false;
?>
				<?php if ($_smarty_tpl->tpl_vars['ecotax_tax_infos']->value['ecotax_tax_excl'] > 0) {?>
                <?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

				<tr class="product <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['bgcolor_class']->value,'htmlall','UTF-8' ));?>
" style="line-height:6px;">
					<td><?php if ((isset($_smarty_tpl->tpl_vars['taxecotax_label']->value)) && $_smarty_tpl->tpl_vars['taxecotax_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxecotax_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ecotax','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></td>
					<td style="text-align: right;"><?php echo floatval($_smarty_tpl->tpl_vars['ecotax_tax_infos']->value['rate']);?>
 %</td>
					<?php if (!$_smarty_tpl->tpl_vars['use_one_after_another_method']->value) {?>
						<td style=" text-align: right;"><?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['ecotax_tax_infos']->value['ecotax_tax_excl']),$_smarty_tpl ) );?>
</td>
					<?php }?>
					<td style=" text-align: right;"><?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>($_smarty_tpl->tpl_vars['ecotax_tax_infos']->value['ecotax_tax_incl']-$_smarty_tpl->tpl_vars['ecotax_tax_infos']->value['ecotax_tax_excl'])),$_smarty_tpl ) );?>
</td>
				</tr>
				<?php }?>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php }?>
        </tbody>
	</table>
 <?php }?>
    <!--  / TAX DETAILS -->
    <?php }
} else { ?>
    <?php if ($_smarty_tpl->tpl_vars['tax_exempt']->value) {?>
        <?php if ((isset($_smarty_tpl->tpl_vars['taxexempt_label']->value)) && $_smarty_tpl->tpl_vars['taxexempt_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxexempt_label']->value,'htmlall','UTF-8' ));
} else { ?>
    	<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Exempt of VAT according to section 259B of the General Tax Code.','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>

        <?php }?>
    
    <?php } elseif (((isset($_smarty_tpl->tpl_vars['tax_breakdowns']->value)) && $_smarty_tpl->tpl_vars['tax_breakdowns']->value)) {?>
    	<table id="tax-tab" width="100%" cellpadding="5" cellspacing="0">
    		<thead>
    			<tr>
    				<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxdetail_label']->value)) && $_smarty_tpl->tpl_vars['taxdetail_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxdetail_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tax Detail','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
    				<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxrate_label']->value)) && $_smarty_tpl->tpl_vars['taxrate_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxrate_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tax Rate','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
    				<?php if ($_smarty_tpl->tpl_vars['display_tax_bases_in_breakdowns']->value) {?>
    					<th class="header small"><?php if ((isset($_smarty_tpl->tpl_vars['taxbaseprice_label']->value)) && $_smarty_tpl->tpl_vars['taxbaseprice_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxbaseprice_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Base price','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
    				<?php }?>
    				<th class="header-right small"><?php if ((isset($_smarty_tpl->tpl_vars['taxtotal_label']->value)) && $_smarty_tpl->tpl_vars['taxtotal_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxtotal_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total Tax','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?></th>
    			</tr>
    		</thead>
    		<tbody>
    		<?php $_smarty_tpl->_assignInScope('has_line', false);?>
    		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tax_breakdowns']->value, 'bd', false, 'label');
$_smarty_tpl->tpl_vars['bd']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['label']->value => $_smarty_tpl->tpl_vars['bd']->value) {
$_smarty_tpl->tpl_vars['bd']->do_else = false;
?>
    			<?php $_smarty_tpl->_assignInScope('label_printed', false);?>
    			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bd']->value, 'line');
$_smarty_tpl->tpl_vars['line']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['line']->value) {
$_smarty_tpl->tpl_vars['line']->do_else = false;
?>
    				<?php if ($_smarty_tpl->tpl_vars['line']->value['rate'] == 0) {?>
    					<?php continue 1;?>
    				<?php }?>
    				<?php $_smarty_tpl->_assignInScope('has_line', true);?>
                    <?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

    				<tr class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['bgcolor_class']->value,'htmlall','UTF-8' ));?>
" >
    					<td class="white">
    						<?php if (!$_smarty_tpl->tpl_vars['label_printed']->value) {?>
    							<?php if ($_smarty_tpl->tpl_vars['label']->value == 'product_tax') {?>
    								<?php if ((isset($_smarty_tpl->tpl_vars['taxproduct_label']->value)) && $_smarty_tpl->tpl_vars['taxproduct_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxproduct_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
    							<?php } elseif ($_smarty_tpl->tpl_vars['label']->value == 'shipping_tax') {?>
    								<?php if ((isset($_smarty_tpl->tpl_vars['taxshipping_label']->value)) && $_smarty_tpl->tpl_vars['taxshipping_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxshipping_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
    							<?php } elseif ($_smarty_tpl->tpl_vars['label']->value == 'ecotax_tax') {?>
    								<?php if ((isset($_smarty_tpl->tpl_vars['taxecotax_label']->value)) && $_smarty_tpl->tpl_vars['taxecotax_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxecotax_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ecotax','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
    							<?php } elseif ($_smarty_tpl->tpl_vars['label']->value == 'wrapping_tax') {?>
    								<?php if ((isset($_smarty_tpl->tpl_vars['taxwrapping_label']->value)) && $_smarty_tpl->tpl_vars['taxwrapping_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['taxwrapping_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wrapping','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
    							<?php }?>
    							<?php $_smarty_tpl->_assignInScope('label_printed', true);?>
    						<?php }?>
    					</td>
    
    					<td class="center white">
    						<?php echo floatval($_smarty_tpl->tpl_vars['line']->value['rate']);?>
 %
    					</td>
    
    					<?php if ($_smarty_tpl->tpl_vars['display_tax_bases_in_breakdowns']->value) {?>
    						<td class="right white">
    							<?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }?>
    							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['line']->value['total_tax_excl']),$_smarty_tpl ) );?>

    						</td>
    					<?php }?>
    
    					<td class="right white">
    						<?php if ((isset($_smarty_tpl->tpl_vars['is_order_slip']->value)) && $_smarty_tpl->tpl_vars['is_order_slip']->value) {?>- <?php }?>
    						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('currency'=>$_smarty_tpl->tpl_vars['order']->value->id_currency,'price'=>$_smarty_tpl->tpl_vars['line']->value['total_amount']),$_smarty_tpl ) );?>

    					</td>
    				</tr>
    			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    
    		<?php if (!$_smarty_tpl->tpl_vars['has_line']->value) {?>
    		<tr>
    			<td class="white center" colspan="<?php if ($_smarty_tpl->tpl_vars['display_tax_bases_in_breakdowns']->value) {?>4<?php } else { ?>3<?php }?>">
    				<?php if ((isset($_smarty_tpl->tpl_vars['notax_label']->value)) && $_smarty_tpl->tpl_vars['notax_label']->value != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['notax_label']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No taxes','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
    			</td>
    		</tr>
    		<?php }?>
    
    		</tbody>
    	</table>
    
    <?php }
}
}
}
