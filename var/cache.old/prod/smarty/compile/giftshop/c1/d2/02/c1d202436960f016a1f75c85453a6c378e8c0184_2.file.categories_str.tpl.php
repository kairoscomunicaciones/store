<?php
/* Smarty version 3.1.43, created on 2024-01-16 16:04:00
  from '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/categories_str.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6e13019fd22_04989534',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c1d202436960f016a1f75c85453a6c378e8c0184' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/categories_str.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6e13019fd22_04989534 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['categories']->value && count($_smarty_tpl->tpl_vars['categories']->value) > 1) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categories']->value, 'category');
$_smarty_tpl->tpl_vars['category']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->do_else = false;
?>
        <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['category']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
<br />
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} elseif ((isset($_smarty_tpl->tpl_vars['categories']->value[0]['title']))) {?>
    <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['categories']->value[0]['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

<?php }
}
}
