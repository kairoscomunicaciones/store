<?php
/* Smarty version 3.1.43, created on 2024-01-18 18:31:36
  from '/home/ecomunik2/htdocs/www.ecomunik2.store/admin730/themes/default/template/helpers/list/list_action_enable.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a9a6c8bc5818_61801467',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4ba83ec3291940139ab15442d875988be1b9f673' => 
    array (
      0 => '/home/ecomunik2/htdocs/www.ecomunik2.store/admin730/themes/default/template/helpers/list/list_action_enable.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a9a6c8bc5818_61801467 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['migrated_url_enable']->value))) {?>
  <?php $_smarty_tpl->_assignInScope('url_enable', $_smarty_tpl->tpl_vars['migrated_url_enable']->value);
}?>

<a class="list-action-enable<?php if ((isset($_smarty_tpl->tpl_vars['ajax']->value)) && $_smarty_tpl->tpl_vars['ajax']->value) {?> ajax_table_link<?php }
if ($_smarty_tpl->tpl_vars['enabled']->value) {?> action-enabled<?php } else { ?> action-disabled<?php }?>" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['url_enable']->value,'html','UTF-8' ));?>
"<?php if ((isset($_smarty_tpl->tpl_vars['confirm']->value))) {?> onclick="return confirm('<?php echo $_smarty_tpl->tpl_vars['confirm']->value;?>
');"<?php }?> title="<?php if ($_smarty_tpl->tpl_vars['enabled']->value) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enabled','d'=>'Admin.Global'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Disabled','d'=>'Admin.Global'),$_smarty_tpl ) );
}?>">
	<i class="icon-check<?php if (!$_smarty_tpl->tpl_vars['enabled']->value) {?> hidden<?php }?>"></i>
	<i class="icon-remove<?php if ($_smarty_tpl->tpl_vars['enabled']->value) {?> hidden<?php }?>"></i>
</a>
<?php }
}
