<?php
/* Smarty version 3.1.43, created on 2024-01-18 18:31:36
  from '/home/ecomunik2/htdocs/www.ecomunik2.store/modules/prestaheroconnect/views/templates/hook/admin_head.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a9a6c8aca136_43042875',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '07fdebdfb898fb9eddccced09df493814ac333d9' => 
    array (
      0 => '/home/ecomunik2/htdocs/www.ecomunik2.store/modules/prestaheroconnect/views/templates/hook/admin_head.tpl',
      1 => 1697325101,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a9a6c8aca136_43042875 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    var PH_CON_TRANS = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'json_encode' ][ 0 ], array( $_smarty_tpl->tpl_vars['transJs']->value ));?>
;
    var PH_CON_LINKS = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'json_encode' ][ 0 ], array( $_smarty_tpl->tpl_vars['phLinks']->value ));?>
;
    var PH_CON_LOGO = '<?php echo $_smarty_tpl->tpl_vars['phLogoIcon']->value;?>
';
    var PH_CON_MODULE_LIST_URL = '<?php echo $_smarty_tpl->tpl_vars['linkModuleList']->value;?>
';
    var PH_CON_ACCOUNT_NAME = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['accountName']->value,'html','UTF-8' ));?>
";
    var PH_CON_LINK_LOGOUT = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['linkLogoutPhAccount']->value,'quotes','UTF-8' ));?>
";
    var PH_CON_LINK_AJAX_MODULE = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['linkAjaxModule']->value,'quotes','UTF-8' ));?>
";
    var PH_CON_CONTROLLER = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['controllerName']->value,'html','UTF-8' ));?>
";
    var PH_CON_IS17 = <?php if ($_smarty_tpl->tpl_vars['is17']->value) {?>1<?php } else { ?>0<?php }?>;
    var PH_CON_MSG_ACTION = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['msgAction']->value,'html','UTF-8' ));?>
";
    var PH_CON_IS_OLD_ACTION = <?php if ($_smarty_tpl->tpl_vars['isOldAction']->value) {?>1<?php } else { ?>0<?php }?>;
    var PH_CON_IS_LOGGED = <?php if ($_smarty_tpl->tpl_vars['isLogged']->value) {?>1<?php } else { ?>0<?php }?>;
    var PH_CON_RELOAD_UPGRADE_COUNT = <?php if ($_smarty_tpl->tpl_vars['reloadUpgradeCount']->value) {?>1<?php } else { ?>0<?php }?>;
    var PH_CON_UPGRADE_COUNT = <?php echo intval($_smarty_tpl->tpl_vars['upgradeCount']->value);?>
;
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['globalJs']->value,'quotes','UTF-8' ));?>
" defer="defer"><?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['linkAdminJs']->value) {
echo '<script'; ?>
 src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['linkAdminJs']->value,'quotes','UTF-8' ));?>
" defer="defer"><?php echo '</script'; ?>
>
<?php }
}
}