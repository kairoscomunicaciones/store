<?php
/* Smarty version 3.1.43, created on 2023-12-13 09:40:11
  from '/home2/inveriti/public_html/modules/multiprice/views/templates/multiprice.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579b43be3c814_28567380',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a49c35dfb196a6ae93a5acec841ef86b039fa179' => 
    array (
      0 => '/home2/inveriti/public_html/modules/multiprice/views/templates/multiprice.tpl',
      1 => 1697325103,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579b43be3c814_28567380 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="multiprice_container">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['multiprice']->value, 'mprice', false, 'multicurrency');
$_smarty_tpl->tpl_vars['mprice']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['multicurrency']->value => $_smarty_tpl->tpl_vars['mprice']->value) {
$_smarty_tpl->tpl_vars['mprice']->do_else = false;
?>
        <br/>
        <?php if (Configuration::get('MP_TIN')) {?>
            <span class="multicurrency_tin multicurrency_tin_product_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['multiprice_id_product']->value, ENT_QUOTES, 'UTF-8');?>
 multicurrency_tin_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['multicurrency']->value, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['mprice']->value['tax_inc'], ENT_QUOTES, 'UTF-8');?>
</span>
        <?php }?>
        <?php if (Configuration::get('MP_TINL')) {?>
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax incl.','mod'=>'multiprice'),$_smarty_tpl ) );?>

        <?php }?>

        <?php if (Configuration::get('MP_TEX')) {?>
            <span class="multicurrency_tex multicurrency_tex_product_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['multiprice_id_product']->value, ENT_QUOTES, 'UTF-8');?>
 multicurrency_tex_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['multicurrency']->value, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['mprice']->value['tax_exc'], ENT_QUOTES, 'UTF-8');?>
</span>
        <?php }?>
        <?php if (Configuration::get('MP_TEXL')) {?>
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excl.','mod'=>'multiprice'),$_smarty_tpl ) );?>

        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div><?php }
}
