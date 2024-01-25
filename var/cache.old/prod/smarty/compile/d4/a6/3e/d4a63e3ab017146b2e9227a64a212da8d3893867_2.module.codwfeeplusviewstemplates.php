<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:58:20
  from 'module:codwfeeplusviewstemplates' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6998c14e7a8_34721270',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd4a63e3ab017146b2e9227a64a212da8d3893867' => 
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
function content_65a6998c14e7a8_34721270 (Smarty_Internal_Template $_smarty_tpl) {
?><p id="codwfeeplus_payment_infos" data-ajaxurl="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ajax_link']->value, ENT_QUOTES, 'UTF-8');?>
">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Pay with cash on delivery (COD)','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>

    <?php if ($_smarty_tpl->tpl_vars['fee']->value > 0) {?>:&nbsp;<span id="codwfeeplus_fee" class="price">+<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['fee_formatted']->value, ENT_QUOTES, 'UTF-8');?>
</span><?php }?>
    &nbsp;<span>(<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You pay for the merchandise upon delivery','mod'=>'codwfeeplus'),$_smarty_tpl ) );?>
)</span>
</p><?php }
}
