<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:37
  from 'module:codwfeeplusviewstemplates' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ec1270383_93462459',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30eed5d43b512a17918f77cf08e9c074bc9a8cb3' => 
    array (
      0 => 'module:codwfeeplusviewstemplates',
      1 => 1697325105,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ec1270383_93462459 (Smarty_Internal_Template $_smarty_tpl) {
?>
<p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your order on %s is complete.','sprintf'=>array($_smarty_tpl->tpl_vars['shop_name']->value),'mod'=>'codwfeeplus'),$_smarty_tpl ) );?>

	<br /><br />
	<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You have chosen the cash on delivery method.','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>

	<br /><br /><span class="bold"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your order will be sent very soon.','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>
</span>
	<br /><br /><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'For any questions or for further information, please contact our','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>
 <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getPageLink('contact',true),'html' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'customer support','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>
</a>.
</p>
<?php }
}
