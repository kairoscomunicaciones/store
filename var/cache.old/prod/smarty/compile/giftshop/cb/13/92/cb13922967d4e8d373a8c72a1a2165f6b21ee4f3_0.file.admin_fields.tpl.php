<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:53
  from '/home2/inveriti/public_html/modules/customfields/views/templates/admin/admin_fields.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ed1293b78_95735285',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cb13922967d4e8d373a8c72a1a2165f6b21ee4f3' => 
    array (
      0 => '/home2/inveriti/public_html/modules/customfields/views/templates/admin/admin_fields.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04ed1293b78_95735285 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['version']->value < 1.6) {?><div class="separation"></div><?php }?>
<div class="<?php if (Tools::version_compare($_smarty_tpl->tpl_vars['version']->value,'1.7.8.0','>=')) {?>card <?php }?>panel col-lg-12">
    <h2 class="<?php if (Tools::version_compare($_smarty_tpl->tpl_vars['version']->value,'1.7.8.0','>=')) {?>card-header <?php }?>panel-heading">
        <img width="16" src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( (defined('__PS_BASE_URI__') ? constant('__PS_BASE_URI__') : null),'htmlall','UTF-8' ));?>
modules/customfields/views/img/AdminCustom.png"/>&nbsp;
    <?php if ((isset($_smarty_tpl->tpl_vars['customfields_heading']->value)) && $_smarty_tpl->tpl_vars['customfields_heading']->value) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customfields_heading']->value,'htmlall','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Custom Fields','mod'=>'customfields','pdf'=>'true'),$_smarty_tpl ) );
}?>
    </h2>
        <div <?php if ($_smarty_tpl->tpl_vars['version']->value >= 1.6) {?>class="row<?php if (Tools::version_compare($_smarty_tpl->tpl_vars['version']->value,'1.7.8.0','>=')) {?> card-body<?php }?>"<?php }?>>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['checkout_steps']->value, 'title', false, 'key');
$_smarty_tpl->tpl_vars['title']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['title']->value) {
$_smarty_tpl->tpl_vars['title']->do_else = false;
?>
                <?php if (!call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( empty($_smarty_tpl->tpl_vars['grouped_fields']->value[$_smarty_tpl->tpl_vars['key']->value]),'htmlall','UTF-8' ))) {?>
                    <div class="well<?php if (Tools::version_compare($_smarty_tpl->tpl_vars['version']->value,'1.7.8.0','>=')) {?> col-lg-12<?php }?>">
                    <h4 class="panel-heading"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['title']->value,'htmlall','UTF-8' ));?>
</h4>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grouped_fields']->value[$_smarty_tpl->tpl_vars['key']->value], 'field', false, NULL, 'custom_fields', array (
));
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">
                                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_name'],'htmlall','UTF-8' ));?>
 :
                            </label>
                            <div class="col-lg-9">
                                <strong><p class="form-control-static">

                                    <?php if (in_array($_smarty_tpl->tpl_vars['field']->value['field_type'],array('multiselect','radio','checkbox','select'))) {?>
                                        <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['field']->value['field_value']);?>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'message') {?>
                                        <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['field']->value['default_value']);?>
                                    <?php } else { ?>
                                        <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['obj_model']->value->getFormatedValue($_smarty_tpl->tpl_vars['field']->value));?>
                                    <?php }?>

                                    <?php if ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'attachment' && $_smarty_tpl->tpl_vars['field_value']->value && file_exists($_smarty_tpl->tpl_vars['field_value']->value)) {?>
                                        <a class="btn btn-default button" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['downloadLink']->value,'htmlall','UTF-8' ));?>
&downloadFile&l=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( base64_encode($_smarty_tpl->tpl_vars['field_value']->value),'htmlall','UTF-8' ));?>
" target="_blank">
                                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download Attachment','mod'=>'customfields'),$_smarty_tpl ) );?>
 <img src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( (defined('__PS_BASE_URI__') ? constant('__PS_BASE_URI__') : null),'htmlall','UTF-8' ));?>
modules/customfields/views/img/download.png" alt="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download Attachment','mod'=>'customfields'),$_smarty_tpl ) );?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download Attachment','mod'=>'customfields'),$_smarty_tpl ) );?>
"/>
                                        </a>
                                    <?php } else { ?>
                                        <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' ));?>

                                    <?php }?>
                                </p></strong>
                            </div>
                        </div>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <div class="clearfix"></div>
                    </div>
                <?php }?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
        <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<!-- <div class="separation"></div> -->
<?php }
}
