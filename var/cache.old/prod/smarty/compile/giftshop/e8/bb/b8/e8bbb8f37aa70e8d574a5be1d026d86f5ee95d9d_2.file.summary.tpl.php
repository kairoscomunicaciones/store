<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:58:20
  from '/home2/inveriti/public_html/modules/customfields/views/templates/hook/summary.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6998c28c3e0_34558775',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e8bbb8f37aa70e8d574a5be1d026d86f5ee95d9d' => 
    array (
      0 => '/home2/inveriti/public_html/modules/customfields/views/templates/hook/summary.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6998c28c3e0_34558775 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<?php if (count($_smarty_tpl->tpl_vars['summary_fields']->value) > 0) {?>
	<div class="clearfix box card card-block" id="customfieldsblock_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['type']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
		<form class="checkout_form" id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['type']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
_form" method="post" action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['action_controller']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" data-step="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['type']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields']->value, 'field');
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
			<?php if (!empty($_smarty_tpl->tpl_vars['field']->value['sub_heading'])) {?><h3 class="page-subheading"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['sub_heading'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</h3><?php }?>
				<div class="clearfix"></div>
				<div class="input_wrapper required form-group text form-group row<?php if ($_smarty_tpl->tpl_vars['field']->value['dependant'] > 0) {?> rf_no_display rf_no_display_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> rf_only_f_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                data-id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                data-f="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                data-v="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                data-dependant-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['dependant'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
		            <label class="input_label <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?> required <?php }
if ($_smarty_tpl->tpl_vars['version']->value >= 1.7) {?>col-md-3<?php }?> form-control-label">
                        <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required'] && $_smarty_tpl->tpl_vars['version']->value >= 1.7) {?><span style="color: #FF5555!important">*</span><?php }?>
		            	<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                    </label>
					<div class="<?php if ($_smarty_tpl->tpl_vars['version']->value >= 1.7) {?>col-md-6<?php }?>">
                        <?php if ($_smarty_tpl->tpl_vars['field']->value['field_type'] != 'message') {?>
                            <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['model']->value->getFormatedValue($_smarty_tpl->tpl_vars['field']->value));?>
                        <?php }?>
						<?php if ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'text') {?>
							<?php $_smarty_tpl->_assignInScope('text_default_value', $_smarty_tpl->tpl_vars['field']->value['default_value']);?>
							<?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
								<?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
									<span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
								<?php } else { ?>
									<input type="text" name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]" value=""  class="text <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }
if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>validate_field<?php }?> form-control" <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?> data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>/>
								<?php }?>
							<?php } else { ?>
								<input type="text"
                                data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                value="<?php if (!empty($_smarty_tpl->tpl_vars['field_value']->value) && $_smarty_tpl->tpl_vars['field_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_smarty_tpl->tpl_vars['text_default_value']->value) && $_smarty_tpl->tpl_vars['text_default_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_default_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>" 
                                class="text <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }
if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>validate_field<?php }?> form-control"
                                <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?> data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>/>
							<?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'textarea') {?>
							<?php $_smarty_tpl->_assignInScope('texta_default_value', $_smarty_tpl->tpl_vars['field']->value['default_value']);?>
							<?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>

								<?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
									<span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
								<?php } else { ?>
									<textarea name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                    data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    class="form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required<?php }?>" <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>></textarea>
								<?php }?>
							<?php } else { ?>
								<textarea name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                class="form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required<?php }?>" <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>><?php if (!empty($_smarty_tpl->tpl_vars['field_value']->value) && $_smarty_tpl->tpl_vars['field_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_smarty_tpl->tpl_vars['text_default_value']->value) && $_smarty_tpl->tpl_vars['text_default_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_default_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?></textarea>
							<?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'date') {?>
                            <?php $_smarty_tpl->_assignInScope('date_default_value', $_smarty_tpl->tpl_vars['field']->value['default_value']);?>
							<?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
								<?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
									<span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
								<?php } else { ?>
									<input class="fields_datapicker form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?> is_required <?php }?> validate_field"
                                    type="text"
                                    data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                    value="<?php if (!empty($_smarty_tpl->tpl_vars['field_value']->value) && $_smarty_tpl->tpl_vars['field_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_smarty_tpl->tpl_vars['date_default_value']->value) && $_smarty_tpl->tpl_vars['date_default_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['date_default_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>" 
                                    data-validate="isDate"/>
                                    <p class="small"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date formate','mod'=>'customfields'),$_smarty_tpl ) );?>
: <strong>Y-m-d</strong></p>
								<?php }?>
							<?php } else { ?>
								<input class="fields_datapicker form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?> is_required <?php }?> validate_field"
                                type="text"
                                name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                value="<?php if (!empty($_smarty_tpl->tpl_vars['field_value']->value) && $_smarty_tpl->tpl_vars['field_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_smarty_tpl->tpl_vars['date_default_value']->value) && $_smarty_tpl->tpl_vars['date_default_value']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['date_default_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>"
                                data-validate="isDate"/>
                                <p class="small"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date formate','mod'=>'customfields'),$_smarty_tpl ) );?>
: <strong>Y-m-d</strong></p>
							<?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'boolean') {?>
                            <?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                    <span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                <?php } else { ?>
                                    <select class="select form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                    name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                    data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                    <option value="No"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
                                    <option value="Yes"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Yes','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
                                </select>
                                <?php }?>
                            <?php } else { ?>
    							<select class="select form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
    								<option value="No" <?php if (!empty($_smarty_tpl->tpl_vars['value_reg_fields']->value) && $_smarty_tpl->tpl_vars['value_reg_fields']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['value_reg_fields']->value, 'field_edit');
$_smarty_tpl->tpl_vars['field_edit']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field_edit']->value) {
$_smarty_tpl->tpl_vars['field_edit']->do_else = false;
if (!empty($_smarty_tpl->tpl_vars['field_edit']->value) && $_smarty_tpl->tpl_vars['field_edit']->value && $_smarty_tpl->tpl_vars['field_edit']->value['id_custom_field'] == $_smarty_tpl->tpl_vars['field']->value['id_custom_field'] && !empty($_smarty_tpl->tpl_vars['field_edit']->value['value']) && $_smarty_tpl->tpl_vars['field_edit']->value['value'] == 'No') {?>selected="selected"<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
    								<option value="Yes" <?php if (!empty($_smarty_tpl->tpl_vars['value_reg_fields']->value) && $_smarty_tpl->tpl_vars['value_reg_fields']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['value_reg_fields']->value, 'field_edit');
$_smarty_tpl->tpl_vars['field_edit']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field_edit']->value) {
$_smarty_tpl->tpl_vars['field_edit']->do_else = false;
if (!empty($_smarty_tpl->tpl_vars['field_edit']->value) && $_smarty_tpl->tpl_vars['field_edit']->value && $_smarty_tpl->tpl_vars['field_edit']->value['id_custom_field'] == $_smarty_tpl->tpl_vars['field']->value['id_custom_field'] && !empty($_smarty_tpl->tpl_vars['field_edit']->value['value']) && $_smarty_tpl->tpl_vars['field_edit']->value['value'] == 'Yes') {?>selected="selected"<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Yes','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
    							</select>
                            <?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'select') {?>
                            <?php $_smarty_tpl->_assignInScope('summary_fields_values', $_smarty_tpl->tpl_vars['model']->value->getCustomFieldsValues($_smarty_tpl->tpl_vars['field']->value['id_custom_field']));?>

                            <?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                    <?php $_smarty_tpl->_assignInScope('field_value', FieldsModel::getFieldsValueById($_smarty_tpl->tpl_vars['field_value']->value));?>
                                    <span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                <?php } else { ?>
                                    <select class="select form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                    data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]" data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                    <option value=""><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select Option','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
                                        <option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                        </option>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </select>
                                <?php }?>
                            <?php } else { ?>
    							<select class="select form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
    								<option value=""><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select Option','mod'=>'customfields'),$_smarty_tpl ) );?>
</option>
    								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
    									<option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                        <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'] == $_smarty_tpl->tpl_vars['field_value']->value) {?>selected="selected"<?php }?>><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

    									</option>
    								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    							</select>
                            <?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'radio') {?>

                            <input class="checkboxes" type="hidden" data-required="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['value_required'], ENT_QUOTES, 'UTF-8');?>
" value="<?php if ($_smarty_tpl->tpl_vars['field']->value['dependant'] > 0) {?>1<?php } else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( intval($_smarty_tpl->tpl_vars['field_value']->value),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>"<?php if ($_smarty_tpl->tpl_vars['field']->value['dependant'] > 0) {?> data-depend="1"<?php } else { ?> data-depend="0"<?php }?>>
                            <?php $_smarty_tpl->_assignInScope('summary_fields_values', $_smarty_tpl->tpl_vars['model']->value->getCustomFieldsValues($_smarty_tpl->tpl_vars['field']->value['id_custom_field']));?>

                            <?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                    <?php $_smarty_tpl->_assignInScope('field_value', FieldsModel::getFieldsValueById($_smarty_tpl->tpl_vars['field_value']->value));?>
                                    <span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                <?php } else { ?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
                                        <div class="type_multiboxes" id="radio-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <input type="radio"
                                            id="radio_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            class="<?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                            data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
											data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]"
                                            value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"/>
                                        </div>
                                        <label class="type_multiboxes top" for="radio_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <span><span></span></span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                        </label>
                                        <div class="clearfix"></div>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                <?php }?>
                            <?php } else { ?>
    							<!-- <div class="clearfix"> -->
    								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
    								<!-- <div class="radio-inline"> -->
                                        <div class="type_multiboxes" id="radio-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <input type="radio"
                                            id="radio_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            class="<?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>"
                                            data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
											data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]"
                                            value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'] == $_smarty_tpl->tpl_vars['field_value']->value) {?>checked="checked"<?php }?>
                                            />
                                            <label class="type_multiboxes top" for="radio_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                <span><span></span></span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
    								<!-- </div> -->
    								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    							<!-- </div> -->
                            <?php }?>

                        <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'checkbox') {?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                <?php $_smarty_tpl->_assignInScope('options', explode(',',$_smarty_tpl->tpl_vars['field_value']->value));?>
                            <?php } else { ?>
                                <?php $_smarty_tpl->_assignInScope('options', array());?>
                            <?php }?>
                            <input class="checkboxes"
                            type="hidden"
                            data-required="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['value_required'], ENT_QUOTES, 'UTF-8');?>
"
                            data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                            value="<?php if ($_smarty_tpl->tpl_vars['field']->value['dependant'] > 0) {?>1<?php } else {
echo htmlspecialchars(count($_smarty_tpl->tpl_vars['options']->value), ENT_QUOTES, 'UTF-8');
}?>"<?php if ($_smarty_tpl->tpl_vars['field']->value['dependant'] > 0) {?> data-depend="1"<?php } else { ?> data-depend="0"<?php }?>>
                            <?php $_smarty_tpl->_assignInScope('summary_fields_values', $_smarty_tpl->tpl_vars['model']->value->getCustomFieldsValues($_smarty_tpl->tpl_vars['field']->value['id_custom_field']));?>

                            <?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                    <?php $_smarty_tpl->_assignInScope('opts', array());?>
                                    <?php if ((isset($_smarty_tpl->tpl_vars['options']->value)) && is_array($_smarty_tpl->tpl_vars['options']->value)) {?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['options']->value, 'id_value');
$_smarty_tpl->tpl_vars['id_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_value']->value) {
$_smarty_tpl->tpl_vars['id_value']->do_else = false;
?>
                                           <?php $_tmp_array = isset($_smarty_tpl->tpl_vars['opts']) ? $_smarty_tpl->tpl_vars['opts']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = FieldsModel::getFieldsValueById($_smarty_tpl->tpl_vars['id_value']->value);
$_smarty_tpl->_assignInScope('opts', $_tmp_array);?>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php }?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['opts']->value)) && $_smarty_tpl->tpl_vars['opts']->value && is_array($_smarty_tpl->tpl_vars['opts']->value)) {?>
                                        <?php $_smarty_tpl->_assignInScope('field_value', implode(',',$_smarty_tpl->tpl_vars['opts']->value));?>
                                    <?php }?>
                                    <span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                <?php } else { ?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
                                    <!-- <div class="checkbox"> -->
                                        <div class="type_multiboxes" id="checkbox-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <input type="checkbox"
											data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]" id="checkbox_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            class="<?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required<?php }?>"/>
                                            <label class="type_multiboxes" for="checkbox_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                <span></span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
                                    <!-- </div> -->
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                <?php }?>
                            <?php } else { ?>
    							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
    								<!-- <div class="checkbox"> -->
                                        <div class="type_multiboxes" id="checkbox-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <input type="checkbox"
											data-field="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]" id="checkbox_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                            class="<?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required<?php }?> form-control"
                                            <?php if ((isset($_smarty_tpl->tpl_vars['options']->value)) && is_array($_smarty_tpl->tpl_vars['options']->value) && in_array($_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],$_smarty_tpl->tpl_vars['options']->value)) {?>checked="checked"<?php }?>
                                            />
                                            <label class="type_multiboxes" for="checkbox_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                <span></span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
    								<!-- </div> -->
    							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php }?>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'multiselect') {?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
                                <?php $_smarty_tpl->_assignInScope('options', explode(',',$_smarty_tpl->tpl_vars['field_value']->value));?>
                            <?php } else { ?>
                                <?php $_smarty_tpl->_assignInScope('options', array());?>
                            <?php }?>
                            <input class="checkboxes" type="hidden" data-required="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['value_required'], ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(count($_smarty_tpl->tpl_vars['options']->value), ENT_QUOTES, 'UTF-8');?>
">
                            <?php $_smarty_tpl->_assignInScope('summary_fields_values', $_smarty_tpl->tpl_vars['model']->value->getCustomFieldsValues($_smarty_tpl->tpl_vars['field']->value['id_custom_field']));?>

                            <?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>

                                    <?php $_smarty_tpl->_assignInScope('opts', array());?>
                                    <?php if ((isset($_smarty_tpl->tpl_vars['options']->value)) && is_array($_smarty_tpl->tpl_vars['options']->value)) {?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['options']->value, 'id_value');
$_smarty_tpl->tpl_vars['id_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_value']->value) {
$_smarty_tpl->tpl_vars['id_value']->do_else = false;
?>
                                           <?php $_tmp_array = isset($_smarty_tpl->tpl_vars['opts']) ? $_smarty_tpl->tpl_vars['opts']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = FieldsModel::getFieldsValueById($_smarty_tpl->tpl_vars['id_value']->value);
$_smarty_tpl->_assignInScope('opts', $_tmp_array);?>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php }?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['opts']->value)) && $_smarty_tpl->tpl_vars['opts']->value && is_array($_smarty_tpl->tpl_vars['opts']->value)) {?>
                                        <?php $_smarty_tpl->_assignInScope('field_value', implode(',',$_smarty_tpl->tpl_vars['opts']->value));?>
                                    <?php }?>
                                    <span class="form-control"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                <?php } else { ?>
                                    <select name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]"
                                    multiple="multiple"
                                    data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    class="type_multiboxes multiselect form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
                                            <option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                            </option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <p><small><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Hold CTRL/Command key to select multiple values.','mod'=>'customfields'),$_smarty_tpl ) );?>
</small></p>
                                <?php }?>
                            <?php } else { ?>
    							<select name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
][]" multiple="multiple" class="type_multiboxes multiselect form-control <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }?>">
    								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['summary_fields_values']->value, 'summary_fields_value');
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['summary_fields_value']->value) {
$_smarty_tpl->tpl_vars['summary_fields_value']->do_else = false;
?>
    									<option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" <?php if ((isset($_smarty_tpl->tpl_vars['options']->value)) && is_array($_smarty_tpl->tpl_vars['options']->value) && in_array($_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value_id'],$_smarty_tpl->tpl_vars['options']->value)) {?>selected="selected"<?php }?>><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['summary_fields_value']->value['field_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                        </option>
    								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    							</select>
    							<p><small><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Hold CTRL/Command key to select multiple values.','mod'=>'customfields'),$_smarty_tpl ) );?>
</small></p>
                            <?php }?>

                        <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'attachment') {?>

							<div id="field_attachment_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
								<?php $_smarty_tpl->_assignInScope('root_dir', (((defined('_PS_ROOT_DIR_') ? constant('_PS_ROOT_DIR_') : null)).('/')));?>
								<?php if ($_smarty_tpl->tpl_vars['field']->value['editable'] == 0) {?>
									<!--  -->

									<?php if ((isset($_smarty_tpl->tpl_vars['field_value']->value)) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
										<p><a class="btn btn-primary button" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['action_controller']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
&downloadFile&l=<?php ob_start();
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
$_prefixVariable3 = ob_get_clean();
echo htmlspecialchars(base64_encode($_prefixVariable3), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','mod'=>'customfields'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars(pathinfo(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( smarty_modifier_replace($_smarty_tpl->tpl_vars['field_value']->value,$_smarty_tpl->tpl_vars['root_dir']->value,''),'htmlall','UTF-8' )),(defined('PATHINFO_FILENAME') ? constant('PATHINFO_FILENAME') : null)), ENT_QUOTES, 'UTF-8');?>
</a></p>
									<?php } else { ?>
										<input type="file"
                                        name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                                        data-type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_type'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                        class="form-control attachment <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }
if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>validate_field<?php }?>" <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?> data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['extensions'])) && $_smarty_tpl->tpl_vars['field']->value['extensions']) {?> data-extensions="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['extensions'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>>
										<?php if ((isset($_smarty_tpl->tpl_vars['field']->value['extensions'])) && $_smarty_tpl->tpl_vars['field']->value['extensions']) {?> <p><small><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Allowed file types','mod'=>'customfields'),$_smarty_tpl ) );?>
: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['extensions'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</small></p><?php }?>
										<p class="alert alert-danger error extension_error"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Image type not allowed.','mod'=>'customfields'),$_smarty_tpl ) );?>
</p>
									<?php }?>
								<?php } else { ?>
								<?php if (!empty($_smarty_tpl->tpl_vars['field_value']->value) && $_smarty_tpl->tpl_vars['field_value']->value) {?>
									<p><a class="btn btn-primary button" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['action_controller']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
&downloadFile&l=<?php ob_start();
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
$_prefixVariable4 = ob_get_clean();
echo htmlspecialchars(base64_encode($_prefixVariable4), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','mod'=>'customfields'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars(pathinfo(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( smarty_modifier_replace($_smarty_tpl->tpl_vars['field_value']->value,$_smarty_tpl->tpl_vars['root_dir']->value,''),'htmlall','UTF-8' )),(defined('PATHINFO_FILENAME') ? constant('PATHINFO_FILENAME') : null)), ENT_QUOTES, 'UTF-8');?>
</a></p>
								<?php }?>
								<input type="file" name="fields[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['id_custom_field'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]" value=""  class="form-control attachment <?php if ($_smarty_tpl->tpl_vars['field']->value['value_required']) {?>is_required <?php }
if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?>validate_field<?php }?>" <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['field_validation'])) && $_smarty_tpl->tpl_vars['field']->value['field_validation']) {?> data-validate="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_validation'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['field']->value['extensions'])) && $_smarty_tpl->tpl_vars['field']->value['extensions']) {?> data-extensions="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['extensions'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>>
								<?php if ((isset($_smarty_tpl->tpl_vars['field']->value['extensions'])) && $_smarty_tpl->tpl_vars['field']->value['extensions']) {?> <p><small><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Allowed file types','mod'=>'customfields'),$_smarty_tpl ) );?>
: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['extensions'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</small></p><?php }?>
								<p class="alert alert-danger error extension_error"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Image type not allowed.','mod'=>'customfields'),$_smarty_tpl ) );?>
</p>
								<?php }?>
							</div>

						<?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'message') {?>
							<b><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['default_value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</b>
						<?php }?>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<input type="hidden" name="type"  value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['type']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"/>
		</form>
	</div>
<?php }
}
}
