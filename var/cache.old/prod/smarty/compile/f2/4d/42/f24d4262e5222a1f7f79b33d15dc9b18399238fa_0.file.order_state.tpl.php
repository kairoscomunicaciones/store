<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:24:05
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/order_state.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a0081555a987_86610116',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f24d4262e5222a1f7f79b33d15dc9b18399238fa' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/order_state.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a0081555a987_86610116 (Smarty_Internal_Template $_smarty_tpl) {
?>
<table class="table history-status row-margin-bottom">
	<tbody>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['history']->value, 'row', false, 'key');
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
			<?php if (($_smarty_tpl->tpl_vars['key']->value == 0)) {?>
				<tr class="order_history_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
_<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
">
                    <?php $_smarty_tpl->_assignInScope('text_color', 'white');?>
                    <?php if ((isset($_smarty_tpl->tpl_vars['row']->value['text-color']))) {?>
					    <?php $_smarty_tpl->_assignInScope('text_color', $_smarty_tpl->tpl_vars['row']->value['text-color']);?>
                    <?php }?>
                    
                    <td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
"><img src="../img/os/<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_state']);?>
.gif" width="16" height="16" alt="<?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['ostate_name']);?>
" /></td>
					<td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['ostate_name']);?>
</td>
					<td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php if ($_smarty_tpl->tpl_vars['row']->value['employee_lastname']) {
echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_firstname']);?>
 <?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_lastname']);
}?></td>
					<td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['row']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
</td>
                    <?php if ((isset($_smarty_tpl->tpl_vars['versionleftthan161']->value)) && $_smarty_tpl->tpl_vars['versionleftthan161']->value == '1') {?>
                    <?php } else { ?>
                    <td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
" class="text-right">
						<?php if (intval($_smarty_tpl->tpl_vars['row']->value['send_email'])) {?>
							<a class="btn btn-default resend_email" data-idorder="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" data-state="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_state']);?>
" data-history="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
" href="#" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Resend this email to the customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
								<i class="icon-mail-reply"></i>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Resend email','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</a>
						<?php }?>
					</td>
                    <?php }?>
                    <td style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
" class="text-right">
                        <a class="btn btn-default delete_status_history" href="#" data-idorder="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" data-history="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
">
                            <i class="icon-trash"></i>
                        </a>
                    </td>
				</tr>
			<?php } else { ?>
				<tr class="order_history_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
_<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
">
					<td><img src="../img/os/<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_state']);?>
.gif" width="16" height="16" /></td>
					<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( stripslashes($_smarty_tpl->tpl_vars['row']->value['ostate_name']),'html','UTF-8' ));?>
</td>
					<td><?php if ($_smarty_tpl->tpl_vars['row']->value['employee_lastname']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_firstname']),'html','UTF-8' ));?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_lastname']),'html','UTF-8' ));
} else { ?>&nbsp;<?php }?></td>
					<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['row']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
</td>
					<?php if ((isset($_smarty_tpl->tpl_vars['versionleftthan161']->value)) && $_smarty_tpl->tpl_vars['versionleftthan161']->value == '1') {?>
                    <?php } else { ?>
                    <td class="text-right">
						<?php if (intval($_smarty_tpl->tpl_vars['row']->value['send_email'])) {?>
							<a class="btn btn-default resend_email" data-idorder="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" data-state="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_state']);?>
" data-history="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
" href="#" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Resend this email to the customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
								<i class="icon-mail-reply"></i>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Resend email','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</a>
						<?php }?>
					</td>
                    <?php }?>
                    <td>
                        <a class="btn btn-default delete_status_history" href="#" data-idorder="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" data-history="<?php echo intval($_smarty_tpl->tpl_vars['row']->value['id_order_history']);?>
">
                            <i class="icon-trash"></i>
                        </a>
                    </td>
				</tr>
			<?php }?>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</tbody>
</table><?php }
}
