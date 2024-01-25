<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:35
  from '/home2/inveriti/public_html/mails/_partials/download_product_virtual_products.txt' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ebfc00eb3_28640012',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9eb12f50bfe08c74ed837032d62f87515b3cb1f9' => 
    array (
      0 => '/home2/inveriti/public_html/mails/_partials/download_product_virtual_products.txt',
      1 => 1697325110,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ebfc00eb3_28640012 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'product');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
  [<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['text'], ENT_QUOTES, 'UTF-8');?>
](<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
) <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['complementary_text'], ENT_QUOTES, 'UTF-8');?>

<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
