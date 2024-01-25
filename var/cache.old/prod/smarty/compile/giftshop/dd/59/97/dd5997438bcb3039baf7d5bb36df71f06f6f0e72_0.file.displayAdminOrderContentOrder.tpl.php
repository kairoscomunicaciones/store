<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/universalpay/views/templates/hook/displayAdminOrderContentOrder.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e52d1265_35680539',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dd5997438bcb3039baf7d5bb36df71f06f6f0e72' => 
    array (
      0 => '/home2/inveriti/public_html/modules/universalpay/views/templates/hook/displayAdminOrderContentOrder.tpl',
      1 => 1697325104,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e52d1265_35680539 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="tab-pane" id="up_fields">
    <div class="table-responsive">
        <table class="table" id="up_fields">
            <thead>
            <tr>
                <th>
                    <span class="title_box"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Name','mod'=>'universalpay'),$_smarty_tpl ) );?>
</span>
                </th>
                <th>
                    <span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Value','mod'=>'universalpay'),$_smarty_tpl ) );?>
</span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['up_fields']->value, 'up_field');
$_smarty_tpl->tpl_vars['up_field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['up_field']->key => $_smarty_tpl->tpl_vars['up_field']->value) {
$_smarty_tpl->tpl_vars['up_field']->do_else = false;
$__foreach_up_field_2_saved = $_smarty_tpl->tpl_vars['up_field'];
?>
                <tr>
                    <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['up_field']->key,'html','UTF-8' ));?>
</td>
                    <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['up_field']->value,'html','UTF-8' ));?>
</td>
                </tr>
            <?php
$_smarty_tpl->tpl_vars['up_field'] = $__foreach_up_field_2_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </tbody>
        </table>
    </div>
</div><?php }
}
