<?php
/* Smarty version 3.1.43, created on 2024-01-15 14:58:42
  from '/home2/inveriti/public_html/modules/customfields/views/templates/admin/pdf/pdf_customfields.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a5806217d999_78570614',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c2953779884b3f1f617df5e0d3ce6c5d2a50ed38' => 
    array (
      0 => '/home2/inveriti/public_html/modules/customfields/views/templates/admin/pdf/pdf_customfields.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a5806217d999_78570614 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
?>
<!-- registration fields heading -->
 <table id="summary-tab">
	<tr>
		<td class="center grey bold">
			<?php if ((isset($_smarty_tpl->tpl_vars['customfields_heading']->value)) && $_smarty_tpl->tpl_vars['customfields_heading']->value) {?>
				<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customfields_heading']->value,'htmlall','UTF-8' ));?>

			<?php } else { ?>
				<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Custom Fields','mod'=>'customfields','pdf'=>'true'),$_smarty_tpl ) );?>

			<?php }?>
		</td>
	</tr>
</table>
<!-- registration fields -->
<table id="summary-tab">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['checkout_steps']->value, 'title', false, 'key');
$_smarty_tpl->tpl_vars['title']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['title']->value) {
$_smarty_tpl->tpl_vars['title']->do_else = false;
?>
		<?php if ((isset($_smarty_tpl->tpl_vars['grouped_fields']->value[$_smarty_tpl->tpl_vars['key']->value])) && $_smarty_tpl->tpl_vars['grouped_fields']->value[$_smarty_tpl->tpl_vars['key']->value]) {?>
		 	<tr>
		 		<td colspan="2" class="header" valign="middle" style="border-top:1px solid #000;border-bottom:1px solid #000;text-align:center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['title']->value,'htmlall','UTF-8' ));?>

		 		</td>
			</tr>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grouped_fields']->value[$_smarty_tpl->tpl_vars['key']->value], 'field', false, NULL, 'custom_fields', array (
));
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
				<?php if (in_array($_smarty_tpl->tpl_vars['field']->value['field_type'],array('multiselect','radio','checkbox','select'))) {?>
                    <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['field']->value['field_value']);?>
                <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'message') {?>
                    <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['field']->value['default_value']);?>
                <?php } else { ?>
                    <?php $_smarty_tpl->_assignInScope('field_value', $_smarty_tpl->tpl_vars['obj_model']->value->getFormatedValue($_smarty_tpl->tpl_vars['field']->value));?>
                <?php }?>
				<?php echo smarty_function_cycle(array('values'=>array("color_line_even","color_line_odd"),'assign'=>'bgcolor_class'),$_smarty_tpl);?>

				<?php if ($_smarty_tpl->tpl_vars['field']->value['field_type'] == 'attachment') {?>
					<?php continue 1;?>
				<?php } else { ?>
					<tr class="product <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['bgcolor_class']->value,'htmlall','UTF-8' ));?>
">
						<td class="left grey bold" width="45%" style="margin-left:15px;">
							<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['field_name'],'htmlall','UTF-8' ));?>

						</td>
						
						<td class="left white" width="55%" style="margin-left:15px;">
							<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field_value']->value,'htmlall','UTF-8' ));?>

						</td>
					</tr>
				<?php }?>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php }?>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</table><?php }
}
