<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:41
  from '/home2/inveriti/public_html/admin730/themes/new-theme/template/helpers/shops_list/list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a70049c06904_39730491',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e3a9e589b96c93b78e49adf3b19578b45405fdfd' => 
    array (
      0 => '/home2/inveriti/public_html/admin730/themes/new-theme/template/helpers/shops_list/list.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a70049c06904_39730491 (Smarty_Internal_Template $_smarty_tpl) {
?><ul class="items-list"><li<?php if (!(isset($_smarty_tpl->tpl_vars['current_shop_value']->value)) || $_smarty_tpl->tpl_vars['current_shop_value']->value == '') {?> class="active"<?php }?>><a class="dropdown-item" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['url']->value,'html','UTF-8' ));?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'All shops','d'=>'Admin.Global'),$_smarty_tpl ) );?>
</a></li><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tree']->value, 'group_data', false, 'group_id');
$_smarty_tpl->tpl_vars['group_data']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['group_id']->value => $_smarty_tpl->tpl_vars['group_data']->value) {
$_smarty_tpl->tpl_vars['group_data']->do_else = false;
if (!(isset($_smarty_tpl->tpl_vars['multishop_context']->value)) || $_smarty_tpl->tpl_vars['is_group_context']->value) {?><li class="group<?php if ($_smarty_tpl->tpl_vars['current_shop_value']->value == ('g-').($_smarty_tpl->tpl_vars['group_id']->value)) {?> active<?php }?>"><a class="dropdown-item<?php if ($_smarty_tpl->tpl_vars['multishop_context_group']->value == false) {?> disabled<?php }?>" href="<?php if ($_smarty_tpl->tpl_vars['multishop_context_group']->value == false) {?>#<?php } else {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['url']->value,'html','UTF-8' ));?>
g-<?php echo $_smarty_tpl->tpl_vars['group_id']->value;
}?>"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s group','sprintf'=>array(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group_data']->value['name'],'html','UTF-8' )))),$_smarty_tpl ) );?>
</a></li><?php } elseif (!$_smarty_tpl->tpl_vars['is_all_context']->value) {?><ul class="group <?php if ($_smarty_tpl->tpl_vars['multishop_context_group']->value == false) {?> disabled<?php }?>"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s group','sprintf'=>array(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group_data']->value['name'],'html','UTF-8' )))),$_smarty_tpl ) );
}
if (!(isset($_smarty_tpl->tpl_vars['multishop_context']->value)) || $_smarty_tpl->tpl_vars['is_shop_context']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['group_data']->value['shops'], 'shop_data', false, 'shop_id');
$_smarty_tpl->tpl_vars['shop_data']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['shop_id']->value => $_smarty_tpl->tpl_vars['shop_data']->value) {
$_smarty_tpl->tpl_vars['shop_data']->do_else = false;
if (($_smarty_tpl->tpl_vars['shop_data']->value['active'])) {?><li class="shop<?php if ($_smarty_tpl->tpl_vars['current_shop_value']->value == ('s-').($_smarty_tpl->tpl_vars['shop_id']->value)) {?> active<?php }?>"><a class="dropdown-item <?php if ($_smarty_tpl->tpl_vars['shop_data']->value['uri'] == NULL) {?> disabled<?php }?>" href="<?php if ($_smarty_tpl->tpl_vars['shop_data']->value['uri'] == NULL) {?>#<?php } else {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['url']->value,'html','UTF-8' ));?>
s-<?php echo $_smarty_tpl->tpl_vars['shop_id']->value;
}?>"><?php echo $_smarty_tpl->tpl_vars['shop_data']->value['name'];?>
</a><?php if ($_smarty_tpl->tpl_vars['shop_data']->value['uri'] == NULL) {?><a class="link-shop" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminShop',true),'html','UTF-8' ));?>
" target="_blank"><i class="material-icons">&#xE869;</i></a><?php } else { ?><a class="link-shop" href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getBaseLink($_smarty_tpl->tpl_vars['shop_data']->value['id_shop']);?>
" target="_blank"><i class="material-icons">&#xE8F4;</i></a><?php }?></li><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
if (!(!(isset($_smarty_tpl->tpl_vars['multishop_context']->value)) || $_smarty_tpl->tpl_vars['is_group_context']->value)) {?></ul><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></ul>
<?php }
}
