<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:12
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7002c9a42c1_41791891',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '04b9ab1099985c1367963d4e7c3b2ca7c5d5bf0c' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/footer.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7002c9a42c1_41791891 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    var requied_choose = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please select order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
    var remove_confirm = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delete selected item(s)','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
    var content_invalid = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Content invalid, Please check again.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
    var admincartlink = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addslashes($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminCarts')),'html','UTF-8' ));?>
";
    var admincartstoken = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['admincartstoken']->value,'html','UTF-8' ));?>
";
    var token_admin_ordermanager = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['adminordermanagerstoken']->value,'html','UTF-8' ));?>
";
    function reloadAllDatetimepicker()
    {
        if($('.datetimepicker').length > 0)
            $('.datetimepicker').each(function(){
                if(!$(this).hasClass('hasDatepicker'))
                    $(this).datetimepicker({
            			prevText: '',
            			nextText: '',
            			dateFormat: 'yy-mm-dd',
            			currentText: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Now','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
',
            			closeText: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Done','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
',
            			ampm: false,
            			amNames: ['AM', 'A'],
            			pmNames: ['PM', 'P'],
            			timeFormat: 'hh:mm:ss tt',
            			timeSuffix: '',
            			timeOnlyTitle: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Choose Time','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
',
            			timeText: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Time','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
',
            			hourText: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Hour','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
',
            			minuteText: '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Minute','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
'
            		});
                });
    }
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['base_uri']->value,'html','UTF-8' ));?>
modules/g_ordermanager/views/js/admin/g_ordermanager.js"><?php echo '</script'; ?>
>
<a id="linkDynamic" target="_blank" href="#"></a><?php }
}
