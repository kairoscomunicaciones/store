<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/psbooking/views/templates/hook/adminBookingProductOrderDetails.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e5509c76_81651793',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1d7c2f8b4e0ff919d43f2bdc0b87a22302ac5071' => 
    array (
      0 => '/home2/inveriti/public_html/modules/psbooking/views/templates/hook/adminBookingProductOrderDetails.tpl',
      1 => 1697325102,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e5509c76_81651793 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="panel card mt-2">
	<div class="panel-heading card-header">
		<i class="icon-shopping-cart"></i>
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Booking product details','mod'=>'psbooking'),$_smarty_tpl ) );?>

	</div>
	<div class="table-responsive card-body">
		<table class="table" id="orderProducts">
			<thead>
				<tr>
					<th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Image','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
          <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product name','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
          <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duration','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
          <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quantity','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
          <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unit price (tax excl.)','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
          <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total price (tax excl.)','mod'=>'psbooking'),$_smarty_tpl ) );?>
</th>
				</tr>
			</thead>
			<tbody>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orderProducts']->value, 'product');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
          <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['isBookingProduct'])) && $_smarty_tpl->tpl_vars['product']->value['isBookingProduct']) {?>
            <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['booking_product_data'])) && $_smarty_tpl->tpl_vars['product']->value['booking_product_data']) {?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['booking_product_data'], 'bookingProduct');
$_smarty_tpl->tpl_vars['bookingProduct']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['bookingProduct']->value) {
$_smarty_tpl->tpl_vars['bookingProduct']->do_else = false;
?>
                <tr>
                  <td>
                    <span class="image">
                      <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['image'])) && $_smarty_tpl->tpl_vars['product']->value['image']->id) {
echo $_smarty_tpl->tpl_vars['product']->value['image_tag'];
}?>
                    </span>
                  </td>
                  <td>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts',true,array('id_product'=>intval($_smarty_tpl->tpl_vars['product']->value['product_id']),'updateproduct'=>'1'));?>
" target="_blank">
                      <span class="productName"><?php echo $_smarty_tpl->tpl_vars['product']->value['product_name'];?>
</span>
                    </a>
                  </td>
                  <td>
                    <?php if ($_smarty_tpl->tpl_vars['bookingProduct']->value['booking_type'] == 1) {?>
                      <?php echo Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']);?>
</br> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'to','mod'=>'psbooking'),$_smarty_tpl ) );?>
 </br> <?php echo Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_to']);?>

                    <?php } else { ?>
                      <?php echo Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']);?>
</br>
                      <?php echo $_smarty_tpl->tpl_vars['bookingProduct']->value['time_from'];?>
 - <?php echo $_smarty_tpl->tpl_vars['bookingProduct']->value['time_to'];?>

                    <?php }?>
                  </td>
                  <td><?php echo $_smarty_tpl->tpl_vars['bookingProduct']->value['quantity'];?>
</td>
                  <td><?php echo $_smarty_tpl->tpl_vars['bookingProduct']->value['unit_feature_price_tax_excl_formated'];?>
</td>
                  <td><?php echo $_smarty_tpl->tpl_vars['bookingProduct']->value['total_range_feature_price_tax_excl_formated'];?>
</td>
                </tr>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
          <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</tbody>
		</table>
	</div>
</div>
<?php }
}
