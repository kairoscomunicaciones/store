<?php
/* Smarty version 3.1.43, created on 2024-01-12 16:41:17
  from '/home2/inveriti/public_html/themes/giftshop/templates/checkout/_partials/cart-summary-items-subtotal.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a1a3edebe1c1_97548483',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cc31ce8c5fb8480d8d34e2621c15f80d7b50474b' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/checkout/_partials/cart-summary-items-subtotal.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a1a3edebe1c1_97548483 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="card-block cart-summary-line cart-summary-items-subtotal clearfix" id="items-subtotal">
  <span class="label"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['cart']->value['summary_string'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
  <span class="value"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['cart']->value['subtotals']['products']['amount'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayMultiPrice','price'=>call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['cart']->value['totals']['total']['amount'],'html','UTF-8' ))),$_smarty_tpl ) );?>

  </span>
</div>
<?php }
}
