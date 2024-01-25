<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:12
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7002c358476_43533120',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b67f289cac71133f792f91cee9f9aa295d4662fb' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/extra_html.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7002c358476_43533120 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['item_type']->value)) && $_smarty_tpl->tpl_vars['item_type']->value != '') {?>
    <?php if ($_smarty_tpl->tpl_vars['item_type']->value == 'order_split_line') {?>
        <?php if ((isset($_smarty_tpl->tpl_vars['datas']->value))) {?>
            <div class="max_height_box">
                <?php if ($_smarty_tpl->tpl_vars['datas']->value) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
$_smarty_tpl->tpl_vars['data']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->do_else = false;
?>
                        <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value,'html','UTF-8' ));
if (count($_smarty_tpl->tpl_vars['datas']->value) > 1) {?><span class="order_split_line"></span><?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
            </div>
        <?php }?>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'quickview') {?>
        <a href="#" class="order_field_viewproduct" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quickview order product(s)','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
"><i class="icon-eye"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</a>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'duplicate') {?>
        <a title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duplicate','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" class="duplicateorder" rel="<?php echo intval($_smarty_tpl->tpl_vars['id']->value);?>
" href="#copyForm">
        	<i class="icon-copy"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duplicate','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

        </a>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'color_field') {?>
        <span class="label color_field" style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['backgroundcolor']->value,'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['color']->value,'html','UTF-8' ));?>
;"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['statename']->value,'html','UTF-8' ));?>
</span>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'badgesuccess') {?>
        <span class="badge badge-success"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['total_paid_tax_incl']->value,'html','UTF-8' ));?>
</span>
    
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'addressformat') {?>
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayAddressDetail'][0], array( array('address'=>$_smarty_tpl->tpl_vars['address']->value,'newLine'=>'<br />'),$_smarty_tpl ) );?>

		<?php if ($_smarty_tpl->tpl_vars['address']->value->other) {?>
			<hr /><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value->other,'html','UTF-8' ));?>
<br />
		<?php }?>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'loginascustomer') {?>
        <a target="_blank" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Login As Customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" class="loginascustomer"  href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['loginascustomer_link']->value,'html','UTF-8' ));?>
">
            <i class="icon icon-key"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Login As Customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

        </a>
    <?php } elseif ($_smarty_tpl->tpl_vars['item_type']->value == 'printshippinglabel') {?>
        <a target="_blank" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping Label','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" class="printshippinglabel"  href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['printshippinglabel_link']->value,'html','UTF-8' ));?>
">
            <i class="icon-truck icon fa-truck"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping Label','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

        </a>
    <?php }
}
}
}
