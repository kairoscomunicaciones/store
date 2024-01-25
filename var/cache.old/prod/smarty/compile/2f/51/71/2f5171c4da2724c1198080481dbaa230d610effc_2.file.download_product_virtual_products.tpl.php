<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:35
  from '/home2/inveriti/public_html/mails/_partials/download_product_virtual_products.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ebfc06de2_71582992',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2f5171c4da2724c1198080481dbaa230d610effc' => 
    array (
      0 => '/home2/inveriti/public_html/mails/_partials/download_product_virtual_products.tpl',
      1 => 1697325110,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ebfc06de2_71582992 (Smarty_Internal_Template $_smarty_tpl) {
?><ul>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'product');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
  <li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['text'], ENT_QUOTES, 'UTF-8');?>
</a> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['complementary_text'], ENT_QUOTES, 'UTF-8');?>
</li>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
<?php }
}
