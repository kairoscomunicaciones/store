<?php
/* Smarty version 3.1.43, created on 2024-01-12 10:46:33
  from '/home2/inveriti/public_html/modules/ets_productmanager/views/templates/hook/admin_head.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a150c90259d4_34539814',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f1423722482f194b9635abadc3df7fddc07693d' => 
    array (
      0 => '/home2/inveriti/public_html/modules/ets_productmanager/views/templates/hook/admin_head.tpl',
      1 => 1697325099,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a150c90259d4_34539814 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
var ets_pmn_tax_rule_groups = {};
    <?php if ($_smarty_tpl->tpl_vars['tax_rule_groups']->value) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tax_rule_groups']->value, 'rule_groups');
$_smarty_tpl->tpl_vars['rule_groups']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['rule_groups']->value) {
$_smarty_tpl->tpl_vars['rule_groups']->do_else = false;
?>
            ets_pmn_tax_rule_groups[<?php echo intval($_smarty_tpl->tpl_vars['rule_groups']->value['id_tax_rules_group']);?>
] = <?php echo floatval($_smarty_tpl->tpl_vars['rule_groups']->value['value_tax']);?>
;
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }?>
    var ets_max_lang_text = <?php echo intval($_smarty_tpl->tpl_vars['ets_max_lang_text']->value);?>
;
    var ets_pmn_lang_current =<?php echo intval($_smarty_tpl->tpl_vars['ets_pmn_lang_current']->value);?>
;
    var update_successully_text ='<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Upload successfully','mod'=>'ets_productmanager','js'=>1),$_smarty_tpl ) );?>
';
    var Save_view_text ='<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save view','mod'=>'ets_productmanager','js'=>1),$_smarty_tpl ) );?>
';
    var Update_view_text ='<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update view','mod'=>'ets_productmanager','js'=>1),$_smarty_tpl ) );?>
';
    var Save_text ='<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'ets_productmanager','js'=>1),$_smarty_tpl ) );?>
';
    var Update_text = '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update','mod'=>'ets_productmanager','js'=>1),$_smarty_tpl ) );?>
'; 
<?php echo '</script'; ?>
>
<link href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['_PS_JS_DIR_']->value,'html','UTF-8' ));?>
jquery/ui/themes/base/jquery.ui.datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['_PS_JS_DIR_']->value,'html','UTF-8' ));?>
jquery/ui/themes/base/jquery.ui.theme.css" rel="stylesheet" type="text/css"/>
<?php if ((isset($_smarty_tpl->tpl_vars['ets_pmn_seo_meta_titles']->value)) && $_smarty_tpl->tpl_vars['ets_pmn_seo_meta_titles']->value) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ets_pmn_seo_meta_titles']->value, 'meta_title', false, 'id_lang');
$_smarty_tpl->tpl_vars['meta_title']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_lang']->value => $_smarty_tpl->tpl_vars['meta_title']->value) {
$_smarty_tpl->tpl_vars['meta_title']->do_else = false;
?>
        <?php if ($_smarty_tpl->tpl_vars['meta_title']->value) {?>
            <input type="hidden" id="ets_pmn_seo_metatitle_<?php echo intval($_smarty_tpl->tpl_vars['id_lang']->value);?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['meta_title']->value,'html','UTF-8' ));?>
" />
        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ets_pmn_seo_meta_descriptions']->value, 'meta_description', false, 'id_lang');
$_smarty_tpl->tpl_vars['meta_description']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_lang']->value => $_smarty_tpl->tpl_vars['meta_description']->value) {
$_smarty_tpl->tpl_vars['meta_description']->do_else = false;
?>
        <?php if ($_smarty_tpl->tpl_vars['meta_description']->value) {?>
            <textarea id="ets_pmn_seo_metadescription_<?php echo intval($_smarty_tpl->tpl_vars['id_lang']->value);?>
" style="display:none"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['meta_description']->value,'html','UTF-8' ));?>
</textarea>
        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>
<div class="ets_product_popup">
    <div class="popup_content table">
        <div class="popup_content_tablecell">
            <div class="popup_content_wrap" style="position: relative">
                <span class="close_popup" title="Close">+</span>
                <div id="block-form-popup-dublicate">

                </div>
            </div>
        </div>
    </div>
</div><?php }
}
