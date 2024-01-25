<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:12
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/list/list_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7002c2e12d9_60175205',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '155b5e5fe7b7dd1f905c904bea9d4eb92ec0b5c1' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/list/list_header.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7002c2e12d9_60175205 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_69993252365a7002c2d8160_63515345', 'leadin');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "helpers/list/list_header.tpl");
}
/* {block 'leadin'} */
class Block_69993252365a7002c2d8160_63515345 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'leadin' => 
  array (
    0 => 'Block_69993252365a7002c2d8160_63515345',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<div class="panel">
		<div class="panel-heading">
			<i class="icon-time"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quick change status for selected orders','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

		</div>
			<div class="radio">
                <div class="col-lg-4">
                    <label for="id_order_state">
    					<select id="id_order_state" name="id_order_state">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order_statuses']->value, 'order_status_name', false, 'id_order_state');
$_smarty_tpl->tpl_vars['order_status_name']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_order_state']->value => $_smarty_tpl->tpl_vars['order_status_name']->value) {
$_smarty_tpl->tpl_vars['order_status_name']->do_else = false;
?>
    			                 <option value="<?php echo intval($_smarty_tpl->tpl_vars['id_order_state']->value);?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order_status_name']->value ));?>
</option>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    					</select>
    				</label>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-default" name="submitUpdateOrderStatus">
    					<i class="icon-check"></i>
    					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</button>
                </div>
			</div>
	</div>
	<div class="box_gridsetting_showin_box_wp">
		<div class="box_setting_showin_box box_gridsetting_showin_box">
			<div class="box_setting_showin_top box_setting_showinclose"></div>
			<div id="box_setting_showin" class="box_setting_showin">
				<div class="showin-heading-box"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Grid List','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div>
				<div class="gpopup_content">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orderfields']->value, 'orderfieldgroup');
$_smarty_tpl->tpl_vars['orderfieldgroup']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['orderfieldgroup']->value) {
$_smarty_tpl->tpl_vars['orderfieldgroup']->do_else = false;
?>
							<h4><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderfieldgroup']->value['label'],'html','UTF-8' ));?>
</h4>
							<ul class="orderlistfield">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orderfieldgroup']->value['cols'], 'orderfield');
$_smarty_tpl->tpl_vars['orderfield']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['orderfield']->value) {
$_smarty_tpl->tpl_vars['orderfield']->do_else = false;
?>
									<li class="">
										<label><input class="order_field_config" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderfield']->value['key'],'html','UTF-8' ));?>
" name="order_fields[<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderfield']->value['key'],'html','UTF-8' ));?>
]" id="order_fields_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderfield']->value['key'],'html','UTF-8' ));?>
" type="checkbox" <?php if ((isset($_smarty_tpl->tpl_vars['order_fields']->value[$_smarty_tpl->tpl_vars['orderfield']->value['key']]))) {?> checked="checked" <?php }?> /> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderfield']->value['name'],'html','UTF-8' ));?>
</label>
									</li>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</ul>
						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					
				</div>
				<div class="gpopupp_footer">
					<button type="button" class="pull-right btn btn-default gbtn-default" id="box_setting_showinreset"><i class="icon icon-refresh"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Reset to Default','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
					<button type="button" class="btn btn-primary box_setting_showin_save"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
					<button type="button" class="btn btn-default box_setting_showinclose"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Close','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
				</div>
			</div>
		</div>
	</div>
	<div class="box_grid_duplicate_order_wp g_ordermanager_popup_wp">
		<div class="box_duplicate_showin_top box_setting_showinclose"></div>
			<div id="box_duplicate_showin" class="box_duplicate_showin">
				<div class="showin-heading-box"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duplicate order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div>
				<div class="gpopup_content">
					<p class="alert alert-info"><label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duplicate order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 :</label> #<span class="duplicate_order_ref"></span></p>
					<div class="gpopup_duplicate_search_customer">
						<label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Assign this order to other customer ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
						<input type="text" class="duplicate_search_customer" id="customerorder" value="" />
						<div id="customers"></div>
					</div>
					<div class="gpopup_duplicate_content_loading"></div>
				</div>
				<div class="gpopupp_footer">
					<button type="button" class="btn btn-default box_setting_showinclose"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
					<button type="button" class="btn btn-primary pull-right box_duplicate_save"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Next','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
				</div>
			</div>
		</div>
	</div>
<?php
}
}
/* {/block 'leadin'} */
}
