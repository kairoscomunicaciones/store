<?php
/* Smarty version 3.1.43, created on 2024-01-11 12:47:23
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/gwadvancedinvoiceconfig/tabsconfig.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a01b9bcd9372_46439468',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8ac547c962e733f7af5aecc43ef5c87dd728fe5d' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/gwadvancedinvoiceconfig/tabsconfig.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a01b9bcd9372_46439468 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul class="tabs-create menuconfig">
    <li class="active"><a href="javascript:void(0);" data-id="configuration_form" data-tab="config_content"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'General','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
    <li><a href="javascript:void(0);" data-id="configuration_form" data-tab="extra_content"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Extra Content','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
    <?php if ((isset($_smarty_tpl->tpl_vars['countlang']->value)) && $_smarty_tpl->tpl_vars['countlang']->value > 1) {?>
	    <li><a href="javascript:void(0);" data-id="configuration_form_1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TRANSLATE FOR {$TAX_TAB} VARIABLE','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
	    <li><a href="javascript:void(0);" data-id="configuration_form_2"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add New Font','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
	    <?php } else { ?>
		    <li><a href="javascript:void(0);" data-id="configuration_form_1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TRANSLATE FOR {$TAX_TAB} VARIABLE','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
		    <li><a href="javascript:void(0);" data-id="configuration_form_1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add New Font','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</a></li>
    <?php }?>
</ul><?php }
}
