<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_discount_val.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e5622e74_59110995',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '35e92be1da540b38a393be53009c6abc76af3604' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_discount_val.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e5622e74_59110995 (Smarty_Internal_Template $_smarty_tpl) {
if ((sizeof($_smarty_tpl->tpl_vars['discounts']->value))) {?>
<div class="panel panel-vouchers" style="<?php if (!sizeof($_smarty_tpl->tpl_vars['discounts']->value)) {?>display:none;<?php }?>">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>
						<span class="title_box ">
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discount name','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</span>
					</th>
					<th>
						<span class="title_box ">
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Value','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</span>
					</th>
					<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
					<th></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['discounts']->value, 'discount');
$_smarty_tpl->tpl_vars['discount']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['discount']->value) {
$_smarty_tpl->tpl_vars['discount']->do_else = false;
?>
				<tr>
					<td>
                        <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['name'],'html','UTF-8' ));?>

                        <div class="discount_edit_field discount_edit_field_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
">
                            <input type="text" id="edit_discount_name_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['name'],'html','UTF-8' ));?>
" />
                        </div>
                    </td>
					<td>
					<?php if ($_smarty_tpl->tpl_vars['discount']->value['value'] != 0.00) {?>
						-
					<?php }?>
					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value'],'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

                    <div class="discount_edit_field discount_edit_field_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
">
                        <div class="input-group">
							<div class="input-group-addon">
								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->prefix,'html','UTF-8' ));?>

								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->suffix,'html','UTF-8' ));?>

							</div>
							<input type="text" id="edit_discount_price_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['value'],'html','UTF-8' ));?>
" />
						</div>
                    </div>
					</td>
					<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
					<td>
						<a class="btn btn-default pull-right delete_edit_discount_link delete_edit_discount_link_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;submitDeleteVoucher&amp;id_order_cart_rule=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
">
							<i class="icon-trash"></i>
						</a>
                        <a class="btn btn-default pull-right edit_discount_link edit_discount_link_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
"  href="#">
                            <i class="icon-pencil"></i>
                        </a>
                        <div class="discount_edit_field discount_edit_field_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
">
                            <a class="btn btn-default pull-right save_edit_discount_link" data-idorder="" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
"  href="#">
                                <i class="icon-save"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                            </a>
                            <a class="btn btn-default pull-right cancel_edit_discount_link" data-idorder="" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['id_order_cart_rule'],'html','UTF-8' ));?>
"  href="#">
                                <i class="icon-remove text-danger"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                            </a>
                        </div>
					</td>
					<?php }?>
				</tr>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</tbody>
		</table>
	</div>
</div>
<?php }
}
}
