<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:58:20
  from '/home2/inveriti/public_html/modules/multiprice/views/templates/multiprice-custom.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6998c2d19f0_31031421',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b5eac52c571ac2b3664531e2870db759c0efd4f' => 
    array (
      0 => '/home2/inveriti/public_html/modules/multiprice/views/templates/multiprice-custom.tpl',
      1 => 1697325103,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6998c2d19f0_31031421 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['multiprice']->value, 'mprice', false, 'multicurrency');
$_smarty_tpl->tpl_vars['mprice']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['multicurrency']->value => $_smarty_tpl->tpl_vars['mprice']->value) {
$_smarty_tpl->tpl_vars['mprice']->do_else = false;
?>
    <span class="multicurrency_custom"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['mprice']->value['value'], ENT_QUOTES, 'UTF-8');?>
</span>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
