<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/hook/_productline.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e55e8583_23661298',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a052af8a8616b5363d6061bd171102f2ae6adec6' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/hook/_productline.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e55e8583_23661298 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="add_product_product_attribute_area">
    <div id="add_product_product_attribute_area_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id_order_detail']->value,'html','UTF-8' ));?>
" class="form-group" style="">
		<label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product name','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
        <input type="hidden" id="productname_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id_order_detail']->value,'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['productname']->value,'html','UTF-8' ));?>
" />
        <input type="text" name="productname" id="productname<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id_order_detail']->value,'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_name'],'html','UTF-8' ));?>
" />
        <?php if ($_smarty_tpl->tpl_vars['combinations']->value) {?>
        <label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Combinations','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
		<select name="product_attribute_id" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id_order_detail']->value,'html','UTF-8' ));?>
" class="change_product_attribute" id="add_product_product_attribute_id_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id_order_detail']->value,'html','UTF-8' ));?>
">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['combinations']->value, 'combination', false, 'key');
$_smarty_tpl->tpl_vars['combination']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['combination']->value) {
$_smarty_tpl->tpl_vars['combination']->do_else = false;
?>
                <option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'html','UTF-8' ));?>
" <?php if ($_smarty_tpl->tpl_vars['product_attribute_id']->value == $_smarty_tpl->tpl_vars['key']->value) {?> selected="selected" <?php }?>><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['combination']->value,'html','UTF-8' ));?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
        <?php }?>
	</div>
</div>
<?php }
}
