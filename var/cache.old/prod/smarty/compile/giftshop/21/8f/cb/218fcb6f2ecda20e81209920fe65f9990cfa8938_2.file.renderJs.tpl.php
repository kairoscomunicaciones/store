<?php
/* Smarty version 3.1.43, created on 2024-01-16 20:08:25
  from '/home2/inveriti/public_html/modules/ybc_productimagehover/views/templates/hook/renderJs.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71a79753640_95257557',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '218fcb6f2ecda20e81209920fe65f9990cfa8938' => 
    array (
      0 => '/home2/inveriti/public_html/modules/ybc_productimagehover/views/templates/hook/renderJs.tpl',
      1 => 1697325104,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71a79753640_95257557 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
 var baseAjax ='<?php if ((isset($_smarty_tpl->tpl_vars['_PI_VER_17_']->value)) && $_smarty_tpl->tpl_vars['_PI_VER_17_']->value) {
echo $_smarty_tpl->tpl_vars['baseAjax']->value;
} else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['baseAjax']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>';
 var YBC_PI_TRANSITION_EFFECT = '<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['YBC_PI_TRANSITION_EFFECT']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
';
 var _PI_VER_17_ = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['_PI_VER_17_']->value), ENT_QUOTES, 'UTF-8');?>

 var _PI_VER_16_ = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['_PI_VER_16_']->value), ENT_QUOTES, 'UTF-8');?>

<?php echo '</script'; ?>
><?php }
}
