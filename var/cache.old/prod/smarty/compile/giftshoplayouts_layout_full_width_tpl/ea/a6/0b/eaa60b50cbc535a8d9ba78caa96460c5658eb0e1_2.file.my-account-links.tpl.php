<?php
/* Smarty version 3.1.43, created on 2024-01-16 16:48:58
  from '/home2/inveriti/public_html/themes/giftshop/templates/customer/_partials/my-account-links.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6ebba1cc269_45279799',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eaa60b50cbc535a8d9ba78caa96460c5658eb0e1' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/customer/_partials/my-account-links.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6ebba1cc269_45279799 (Smarty_Internal_Template $_smarty_tpl) {
?><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['my_account'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="account-link">
  <i class="material-icons">&#xE5CB;</i>
  <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Back to your account','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
</a>
<a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['index'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="account-link">
  <i class="material-icons">&#xE88A;</i>
  <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
</a>
<?php }
}
