<?php
/* Smarty version 3.1.43, created on 2024-01-16 20:08:25
  from '/home2/inveriti/public_html/modules/restrictpaymentmethods/views/templates/hook/v1_7/product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71a79aa6899_24933745',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c8587a1cc9be0a3d006ab524dd46a96b5f9f5dda' => 
    array (
      0 => '/home2/inveriti/public_html/modules/restrictpaymentmethods/views/templates/hook/v1_7/product.tpl',
      1 => 1697325099,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71a79aa6899_24933745 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['payments_meths']->value) && $_smarty_tpl->tpl_vars['rpm_show']->value > 0) {?>
<div id="rpm_payments_list">
    <table>
        <thead>
            <tr>
                <th><?php if (empty($_smarty_tpl->tpl_vars['rpm_heading']->value)) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment Methods Available','mod'=>'restrictpaymentmethods'),$_smarty_tpl ) );
} else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['rpm_heading']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?></th>
            </tr>
        </thead>
        <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['payments_meths']->value, 'meths');
$_smarty_tpl->tpl_vars['meths']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['meths']->value) {
$_smarty_tpl->tpl_vars['meths']->do_else = false;
?>
                <tr>
                    <td><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['meths']->value->displayName,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</td>
                </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tbody>
    </table>
</div>
<?php }
}
}
