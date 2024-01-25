<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:09:55
  from '/home2/inveriti/public_html/modules/ets_onepagecheckout/views/templates/hook/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6feb37eed11_00954906',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a875b1a45a03144cd52cac7e67219feb2bd94a87' => 
    array (
      0 => '/home2/inveriti/public_html/modules/ets_onepagecheckout/views/templates/hook/header.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6feb37eed11_00954906 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    var ETS_OPC_URL_OAUTH ='<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getModuleLink('ets_onepagecheckout','oauth'),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
';
    var ETS_OPC_CHECK_BOX_NEWSLETTER = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ETS_OPC_CHECK_BOX_NEWSLETTER']->value), ENT_QUOTES, 'UTF-8');?>
;
    var ETS_OPC_CHECK_BOX_OFFERS =<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ETS_OPC_CHECK_BOX_OFFERS']->value), ENT_QUOTES, 'UTF-8');?>
;
<?php echo '</script'; ?>
><?php }
}
