<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:12
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/list/list_content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7002c846e42_01711767',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30773be0b9a1f7415ecc953c6d5163811155d296' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/list/list_content.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7002c846e42_01711767 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/function.counter.php','function'=>'smarty_function_counter',),));
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'tr_count', null, null);
echo smarty_function_counter(array('name'=>'tr_count'),$_smarty_tpl);
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
<tbody>
<tr class="order_quick_action_wp">
    <td class="row-selector text-center">
        <input type="checkbox" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
Box[]', $(this).is(':checked'));" />
    </td>
    <td colspan="<?php if ($_smarty_tpl->tpl_vars['shop_link_type']->value) {
echo count($_smarty_tpl->tpl_vars['fields_display']->value)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 2,'html','UTF-8' ));
} else {
echo count($_smarty_tpl->tpl_vars['fields_display']->value)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));
}?>">
    <div class="row">
    	<div class="col-lg-8">
    		<?php if ($_smarty_tpl->tpl_vars['bulk_actions']->value && $_smarty_tpl->tpl_vars['has_bulk_actions']->value) {?>
    		<div class="btn-group bulk-actions">
    			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    				<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Bulk actions','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="caret"></span>
    			</button>
    			<ul class="dropdown-menu">
    			<li>
    				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
Box[]', true);return false;">
    					<i class="icon-check-sign"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select all','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</a>
    			</li>
    			<li>
    				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
Box[]', false);return false;">
    					<i class="icon-check-empty"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unselect all','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</a>
    			</li>
    			<li class="divider"></li>

                <li>
                    <a class="desc-order-print" href="submitPrintOrder">
                        <i class="icon-file-text"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Print Orders','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                    </a>
                </li>
                <li>
    				<a class="desc-order-invoice" href="submitExportOrderInvoice">
    					<i class="icon-file-text"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate Invoices','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</a>
    			</li>
                <li>
    				<a class="desc-order-delivery" href="submitExportOrderDelivery">
    					<i class="icon-truck"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate Delivery','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</a>
    			</li>
                <li>
    				<a class="desc-order-csv" href="submitExportOrderCsv">
    					<i class="icon-download"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Export CSV','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</a>
    			</li>
                <li class="divider"></li>
    			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bulk_actions']->value, 'params', false, 'key');
$_smarty_tpl->tpl_vars['params']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['params']->value) {
$_smarty_tpl->tpl_vars['params']->do_else = false;
?>
    				<li <?php if ($_smarty_tpl->tpl_vars['params']->value['text'] == 'divider') {?> class="divider"<?php }?>>
    					<?php if ($_smarty_tpl->tpl_vars['params']->value['text'] != 'divider') {?>
    					<a href="#" onclick="<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['confirm']))) {?>if (confirm('<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['confirm'],'html','UTF-8' ));?>
'))<?php }?>sendBulkAction($(this).closest('form').get(0), 'submitBulk<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'html','UTF-8' ));
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['table']->value,'html','UTF-8' ));?>
');">
    						<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['icon']))) {?><i class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['icon'],'html','UTF-8' ));?>
"></i><?php }?>&nbsp;<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['text'],'html','UTF-8' ));?>

    					</a>
    					<?php }?>
    				</li>
    			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    			</ul>
    		</div>
    		<?php }?>
    	</div>
    	<?php if (!$_smarty_tpl->tpl_vars['simple_header']->value && $_smarty_tpl->tpl_vars['list_total']->value > $_smarty_tpl->tpl_vars['pagination']->value[0]) {?>
    	<div class="col-lg-4">
    		    		<span class="pagination">
    			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Display','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
:
    			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    				<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['selected_pagination']->value,'html','UTF-8' ));?>

    				<i class="icon-caret-down"></i>
    			</button>
    			<ul class="dropdown-menu">
    			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pagination']->value, 'value');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
    				<li>
    					<a href="javascript:void(0);" class="pagination-items-page" data-items="<?php echo intval($_smarty_tpl->tpl_vars['value']->value);?>
" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value,'html','UTF-8' ));?>
</a>
    				</li>
    			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    			</ul>
    			/ <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_total']->value,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'result(s)','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    			<input type="hidden" class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
-pagination-items-page" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
_pagination" value="<?php echo intval($_smarty_tpl->tpl_vars['selected_pagination']->value);?>
" />
    		</span>
    		<?php echo '<script'; ?>
 type="text/javascript">
    			$('.pagination-items-page').on('click',function(e){
    				e.preventDefault();
    				$('.'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
    			});
    		<?php echo '</script'; ?>
>
    		<ul class="pagination pull-right">
    			<li <?php if ($_smarty_tpl->tpl_vars['page']->value <= 1) {?>class="disabled"<?php }?>>
    				<a href="javascript:void(0);" class="pagination-link" data-page="1" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
">
    					<i class="icon-double-angle-left"></i>
    				</a>
    			</li>
    			<li <?php if ($_smarty_tpl->tpl_vars['page']->value <= 1) {?>class="disabled"<?php }?>>
    				<a href="javascript:void(0);" class="pagination-link" data-page="<?php echo $_smarty_tpl->tpl_vars['page']->value-call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));?>
" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
">
    					<i class="icon-angle-left"></i>
    				</a>
    			</li>
    			<?php $_smarty_tpl->_assignInScope('p', 0);?>
    			<?php
 while ($_smarty_tpl->tpl_vars['p']->value++ < $_smarty_tpl->tpl_vars['total_pages']->value) {?>
    				<?php if ($_smarty_tpl->tpl_vars['p']->value < $_smarty_tpl->tpl_vars['page']->value-2) {?>
    					<li class="disabled">
    						<a href="javascript:void(0);">&hellip;</a>
    					</li>
    					<?php $_smarty_tpl->_assignInScope('p', $_smarty_tpl->tpl_vars['page']->value-3);?>
    				<?php } elseif ($_smarty_tpl->tpl_vars['p']->value > $_smarty_tpl->tpl_vars['page']->value+2) {?>
    					<li class="disabled">
    						<a href="javascript:void(0);">&hellip;</a>
    					</li>
    					<?php $_smarty_tpl->_assignInScope('p', $_smarty_tpl->tpl_vars['total_pages']->value);?>
    				<?php } else { ?>
    					<li <?php if ($_smarty_tpl->tpl_vars['p']->value == $_smarty_tpl->tpl_vars['page']->value) {?>class="active"<?php }?>>
    						<a href="javascript:void(0);" class="pagination-link" data-page="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['p']->value,'html','UTF-8' ));?>
" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['p']->value,'html','UTF-8' ));?>
</a>
    					</li>
    				<?php }?>
    			<?php }?>

    			<li <?php if ($_smarty_tpl->tpl_vars['page']->value >= $_smarty_tpl->tpl_vars['total_pages']->value) {?>class="disabled"<?php }?>>
    				<a href="javascript:void(0);" class="pagination-link" data-page="<?php echo $_smarty_tpl->tpl_vars['page']->value+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));?>
" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
">
    					<i class="icon-angle-right"></i>
    				</a>
    			</li>
    			<li <?php if ($_smarty_tpl->tpl_vars['page']->value >= $_smarty_tpl->tpl_vars['total_pages']->value) {?>class="disabled"<?php }?>>
    				<a href="javascript:void(0);" class="pagination-link" data-page="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['total_pages']->value,'html','UTF-8' ));?>
" data-list-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
">
    					<i class="icon-double-angle-right"></i>
    				</a>
    			</li>
    		</ul>
    		<?php echo '<script'; ?>
 type="text/javascript">
    			$('.pagination-link').on('click',function(e){
    				e.preventDefault();

    				if (!$(this).parent().hasClass('disabled'))
    					$('#submitFilter'+$(this).data("list-id")).val($(this).data("page")).closest("form").submit();
    			});
    		<?php echo '</script'; ?>
>
    	</div>
    	<?php }?>
    </div>
    </td>
</tr>
<?php if (count($_smarty_tpl->tpl_vars['list']->value)) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'tr', false, 'index');
$_smarty_tpl->tpl_vars['tr']->iteration = 0;
$_smarty_tpl->tpl_vars['tr']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['index']->value => $_smarty_tpl->tpl_vars['tr']->value) {
$_smarty_tpl->tpl_vars['tr']->do_else = false;
$_smarty_tpl->tpl_vars['tr']->iteration++;
$__foreach_tr_14_saved = $_smarty_tpl->tpl_vars['tr'];
?>
	<tr <?php if ($_smarty_tpl->tpl_vars['position_identifier']->value) {?> id="tr_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['position_group_identifier']->value,'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
_<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value['position']['position']))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['position']['position'],'html','UTF-8' ));
} else { ?>0<?php }?>"<?php }?> class="<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value['class']))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['class'],'html','UTF-8' ));
}?> <?php if ((1 & $_smarty_tpl->tpl_vars['tr']->iteration / 1)) {?>odd<?php }?> <?php if ($_smarty_tpl->tpl_vars['list_id']->value == 'order') {?> tr_order_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
 <?php }?> "<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value['color'])) && $_smarty_tpl->tpl_vars['color_on_bg']->value) {?> style="background-color: <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['color'],'html','UTF-8' ));?>
"<?php }?> >
		<?php if ($_smarty_tpl->tpl_vars['bulk_actions']->value && $_smarty_tpl->tpl_vars['has_bulk_actions']->value) {?>
			<td class="row-selector text-center <?php if ($_smarty_tpl->tpl_vars['orderactioninfisrtcolumn']->value) {
if ($_smarty_tpl->tpl_vars['has_actions']->value) {?> orderactioninfisrtcolumn <?php }
}?>">
				<?php if ((isset($_smarty_tpl->tpl_vars['list_skip_actions']->value['delete']))) {?>
					<?php if (!in_array($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],$_smarty_tpl->tpl_vars['list_skip_actions']->value['delete'])) {?>
						<input type="checkbox" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
Box[]" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
"<?php ob_start();
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));
$_prefixVariable1 = ob_get_clean();
if ((isset($_smarty_tpl->tpl_vars['checked_boxes']->value)) && is_array($_smarty_tpl->tpl_vars['checked_boxes']->value) && in_array($_prefixVariable1,$_smarty_tpl->tpl_vars['checked_boxes']->value)) {?> checked="checked"<?php }?> class="noborder" />
					<?php }?>
				<?php } else { ?>
					<input type="checkbox" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['list_id']->value,'html','UTF-8' ));?>
Box[]" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
"<?php ob_start();
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));
$_prefixVariable2 = ob_get_clean();
if ((isset($_smarty_tpl->tpl_vars['checked_boxes']->value)) && is_array($_smarty_tpl->tpl_vars['checked_boxes']->value) && in_array($_prefixVariable2,$_smarty_tpl->tpl_vars['checked_boxes']->value)) {?> checked="checked"<?php }?> class="noborder" />
				<?php }?>
                <?php if ($_smarty_tpl->tpl_vars['orderactioninfisrtcolumn']->value) {?>
                	<?php if ($_smarty_tpl->tpl_vars['has_actions']->value) {?>
                			<?php $_smarty_tpl->_assignInScope('compiled_actions', array());?>
                			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['actions']->value, 'action', false, 'key');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
                				<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['action']->value]))) {?>
                					<?php if ($_smarty_tpl->tpl_vars['key']->value == 0) {?>
                						<?php $_smarty_tpl->_assignInScope('action', $_smarty_tpl->tpl_vars['action']->value);?>
                					<?php }?>
                					<?php if ($_smarty_tpl->tpl_vars['action']->value == 'delete' && count($_smarty_tpl->tpl_vars['actions']->value) > 2) {?>
                						<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['compiled_actions']) ? $_smarty_tpl->tpl_vars['compiled_actions']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = 'divider';
$_smarty_tpl->_assignInScope('compiled_actions', $_tmp_array);?>
                					<?php }?>
                					<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['compiled_actions']) ? $_smarty_tpl->tpl_vars['compiled_actions']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['action']->value];
$_smarty_tpl->_assignInScope('compiled_actions', $_tmp_array);?>
                				<?php }?>
                			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                			<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 0) {?>
                				<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?><div class="btn-group-action ordermanager_list_action"><?php }?>
                				<div class="btn-group pull-right gquickview_btn">
                					<?php echo $_smarty_tpl->tpl_vars['compiled_actions']->value[0];?>
                					<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?>
                					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                						<i class="icon-caret-down"></i>&nbsp;
                					</button>
                						<ul class="dropdown-menu">
                						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['compiled_actions']->value, 'action', false, 'key');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
                							<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>
                							<li <?php if ($_smarty_tpl->tpl_vars['action']->value == 'divider' && count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 3) {?> class="divider"<?php }?>>
                								<?php if ($_smarty_tpl->tpl_vars['action']->value != 'divider') {
echo $_smarty_tpl->tpl_vars['action']->value;
}?>                							</li>
                							<?php }?>
                						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                						</ul>
                					<?php }?>
                				</div>
                				<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?></div><?php }?>
                			<?php }?>
                	<?php }?>
                    <?php }?>
			</td>
		<?php }?>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fields_display']->value, 'params', false, 'key');
$_smarty_tpl->tpl_vars['params']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['params']->value) {
$_smarty_tpl->tpl_vars['params']->do_else = false;
?>
			<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_123637603265a7002c803c23_24033119', "open_td");
?>

			<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_57583090665a7002c80db24_07776964', "td_content");
?>

			<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_156116637865a7002c8368b4_97076393', "close_td");
?>

		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php if ((isset($_smarty_tpl->tpl_vars['shop_link_type']->value)) && $_smarty_tpl->tpl_vars['shop_link_type']->value) {?>
		<td title="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['shop_name'],'html','UTF-8' ));?>
">
			<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value['shop_short_name']))) {?>
				<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['shop_short_name'],'html','UTF-8' ));?>

			<?php } else { ?>
				<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['shop_name'],'html','UTF-8' ));?>

			<?php }?>
		</td>
	<?php }?>
	<?php if ((isset($_smarty_tpl->tpl_vars['has_actions']->value)) && $_smarty_tpl->tpl_vars['has_actions']->value) {?>
		<td class="text-right">
			<?php $_smarty_tpl->_assignInScope('compiled_actions', array());?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['actions']->value, 'action', false, 'key');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
				<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['action']->value]))) {?>
					<?php if ($_smarty_tpl->tpl_vars['key']->value == 0) {?>
						<?php $_smarty_tpl->_assignInScope('action', $_smarty_tpl->tpl_vars['action']->value);?>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['action']->value == 'delete' && count($_smarty_tpl->tpl_vars['actions']->value) > 2) {?>
						<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['compiled_actions']) ? $_smarty_tpl->tpl_vars['compiled_actions']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = 'divider';
$_smarty_tpl->_assignInScope('compiled_actions', $_tmp_array);?>
					<?php }?>
					<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['compiled_actions']) ? $_smarty_tpl->tpl_vars['compiled_actions']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['action']->value];
$_smarty_tpl->_assignInScope('compiled_actions', $_tmp_array);?>
				<?php }?>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 0) {?>
				<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?><div class="btn-group-action ordermanager_list_action"><?php }?>
				<div class="btn-group pull-right gquickview_btn">
					<?php echo $_smarty_tpl->tpl_vars['compiled_actions']->value[0];?>
					<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?>
					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="icon-caret-down"></i>&nbsp;
					</button>
						<ul class="dropdown-menu">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['compiled_actions']->value, 'action', false, 'key');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
							<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>
							<li <?php if ($_smarty_tpl->tpl_vars['action']->value == 'divider' && count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 3) {?> class="divider"<?php }?>>
								<?php if ($_smarty_tpl->tpl_vars['action']->value != 'divider') {
echo $_smarty_tpl->tpl_vars['action']->value;
}?>							</li>
							<?php }?>
						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</ul>
					<?php }?>
				</div>
				<?php if (count($_smarty_tpl->tpl_vars['compiled_actions']->value) > 1) {?></div><?php }?>
			<?php }?>
		</td>
	<?php }?>
	</tr>
    <?php if ($_smarty_tpl->tpl_vars['list_id']->value == 'order') {?>
        <tr class="tr_custom_data">
            <?php $_smarty_tpl->_assignInScope('allcolspan', count($_smarty_tpl->tpl_vars['fields_display']->value)+1);?>
            <?php if ($_smarty_tpl->tpl_vars['shop_link_type']->value) {?>
                <?php $_smarty_tpl->_assignInScope('allcolspan', $_smarty_tpl->tpl_vars['allcolspan']->value+1);?>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['has_actions']->value) {?>
                <?php $_smarty_tpl->_assignInScope('allcolspan', $_smarty_tpl->tpl_vars['allcolspan']->value+1);?>
            <?php }?>
            <td  colspan="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['allcolspan']->value,'html','UTF-8' ));?>
" >
                <div class="order_extra_box " data-idorder="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
" id="order_extra_box_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
">
					<div class="box_setting_showin_top box_extra_showinclose"></div>
					<div class="order_extra_block_wp">
						<div class="showin-heading-box"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div>
						<div class="order_extra_block"><span class="lds-dual-ring-w"><span class="lds-dual-ring"></span></span></div>
						<div class="gpopupp_footer">
							<button type="button" class="btn btn-primary box_extra_showinclose"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Close','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
						</div>
					</div>
					
				</div>
            </td>
        </tr>
    <?php }
$_smarty_tpl->tpl_vars['tr'] = $__foreach_tr_14_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?>
	<tr>
		<td class="list-empty" colspan="<?php echo count($_smarty_tpl->tpl_vars['fields_display']->value)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));?>
">
			<div class="list-empty-msg">
				<i class="icon-warning-sign list-empty-icon"></i>
				<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No records found','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

			</div>
		</td>
	</tr>
<?php }?>
</tbody>
<?php }
/* {block "open_td"} */
class Block_123637603265a7002c803c23_24033119 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'open_td' => 
  array (
    0 => 'Block_123637603265a7002c803c23_24033119',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

				<td
					<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['position']))) {?>
						id="td_<?php if (!empty($_smarty_tpl->tpl_vars['position_group_identifier']->value)) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['position_group_identifier']->value,'html','UTF-8' ));
} else { ?>0<?php }?>_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));
if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'tr_count') > 1) {?>_<?php echo intval(($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'tr_count')-1));
}?>"
					<?php }?>
					class="<?php if (!$_smarty_tpl->tpl_vars['no_link']->value) {?>pointer<?php }
if ((isset($_smarty_tpl->tpl_vars['params']->value['position'])) && $_smarty_tpl->tpl_vars['order_by']->value == 'position' && $_smarty_tpl->tpl_vars['order_way']->value != 'DESC') {?> dragHandle<?php }
if ((isset($_smarty_tpl->tpl_vars['params']->value['class']))) {?> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['class'],'html','UTF-8' ));
}
if ((isset($_smarty_tpl->tpl_vars['params']->value['align']))) {?> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['align'],'html','UTF-8' ));
}?>"
					<?php if ((!(isset($_smarty_tpl->tpl_vars['params']->value['position'])) && !$_smarty_tpl->tpl_vars['no_link']->value && !(isset($_smarty_tpl->tpl_vars['params']->value['remove_onclick'])))) {?>
						onclick="document.location = '<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['identifier']->value,'html','UTF-8' ));?>
=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['identifier']->value],'html','UTF-8' ));?>
&amp;view<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['table']->value,'html','UTF-8' ));
if ($_smarty_tpl->tpl_vars['page']->value > 1) {?>&amp;page=<?php echo intval($_smarty_tpl->tpl_vars['page']->value);
}?>&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['token']->value,'html','UTF-8' ));?>
'">
					<?php } else { ?>
					>
				<?php }?>
			<?php
}
}
/* {/block "open_td"} */
/* {block "default_field"} */
class Block_117643020065a7002c82fa53_09840497 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
--<?php
}
}
/* {/block "default_field"} */
/* {block "td_content"} */
class Block_57583090665a7002c80db24_07776964 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'td_content' => 
  array (
    0 => 'Block_57583090665a7002c80db24_07776964',
  ),
  'default_field' => 
  array (
    0 => 'Block_117643020065a7002c82fa53_09840497',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['prefix']))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['prefix'],'html','UTF-8' ));
}?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_success'])) && $_smarty_tpl->tpl_vars['params']->value['badge_success'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_success'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_success'] == $_smarty_tpl->tpl_vars['params']->value['badge_success']) {?><span class="badge badge-success"><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_warning'])) && $_smarty_tpl->tpl_vars['params']->value['badge_warning'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_warning'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_warning'] == $_smarty_tpl->tpl_vars['params']->value['badge_warning']) {?><span class="badge badge-warning"><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_danger'])) && $_smarty_tpl->tpl_vars['params']->value['badge_danger'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_danger'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_danger'] == $_smarty_tpl->tpl_vars['params']->value['badge_danger']) {?><span class="badge badge-danger"><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['color'])) && (isset($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['params']->value['color']]))) {?>
					<span class="label color_field" style="background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['params']->value['color']],'html','UTF-8' ));?>
;color:<?php if (Tools::getBrightness($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['params']->value['color']]) < 128) {?>white<?php } else { ?>#383838<?php }?>">
				<?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]))) {?>
					<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['active']))) {?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['activeVisu']))) {?>
						<?php if ($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]) {?>
							<i class="icon-check-ok"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enabled','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						<?php } else { ?>
							<i class="icon-remove"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Disabled','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						<?php }?>
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['position']))) {?>
						<?php if (!$_smarty_tpl->tpl_vars['filters_has_value']->value && $_smarty_tpl->tpl_vars['order_by']->value == 'position' && $_smarty_tpl->tpl_vars['order_way']->value != 'DESC') {?>
							<div class="dragGroup">
								<div class="positions">
									<?php echo $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['position']+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));?>

								</div>
							</div>
						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['position']+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'html','UTF-8' ));?>

						<?php }?>
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['image']))) {?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['icon']))) {?>
						<?php if (is_array($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value])) {?>
							<?php if ((isset($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['class']))) {?>
								<i class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['class'],'html','UTF-8' ));?>
"></i>
							<?php } else { ?>
								<img src="../img/admin/<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['src'],'html','UTF-8' ));?>
" alt="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['alt'],'html','UTF-8' ));?>
" title="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['alt'],'html','UTF-8' ));?>
" />
							<?php }?>
						<?php }?>
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'price') {?>
					<?php echo $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value];?>
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['float']))) {?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'date') {?>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'full'=>0),$_smarty_tpl ) );?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'datetime') {?>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'full'=>1),$_smarty_tpl ) );?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'decimal') {?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf("%.2f",$_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]),'html','UTF-8' ));?>

					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'percent') {?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['type'])) && $_smarty_tpl->tpl_vars['params']->value['type'] == 'editable' && (isset($_smarty_tpl->tpl_vars['tr']->value['id']))) {?>
						<input type="text" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['id'],'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>
" class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'html','UTF-8' ));?>
" />
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['callback']))) {?>
						<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['maxlength'])) && Tools::strlen($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]) > $_smarty_tpl->tpl_vars['params']->value['maxlength']) {?>
							<span title="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],$_smarty_tpl->tpl_vars['params']->value['maxlength'],'...' ));?>
</span>
						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value];?>
						<?php }?>
					<?php } elseif ($_smarty_tpl->tpl_vars['key']->value == 'color') {?>
						<?php if (!is_array($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value])) {?>
						<div style="background-color: <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>
;" class="attributes-color-container"></div>
						<?php } else { ?> 						<img src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]['texture'],'html','UTF-8' ));?>
" alt="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value['name'],'html','UTF-8' ));?>
" class="attributes-color-container" />
						<?php }?>
					<?php } elseif ((isset($_smarty_tpl->tpl_vars['params']->value['maxlength'])) && Tools::strlen($_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value]) > $_smarty_tpl->tpl_vars['params']->value['maxlength']) {?>
						<span title="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],$_smarty_tpl->tpl_vars['params']->value['maxlength'],'...' )),'html','UTF-8' ));?>
</span>
					<?php } else { ?>
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tr']->value[$_smarty_tpl->tpl_vars['key']->value],'html','UTF-8' ));?>

					<?php }?>
				<?php } else { ?>
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_117643020065a7002c82fa53_09840497', "default_field", $this->tplIndex);
?>

				<?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['suffix']))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['params']->value['suffix'],'html','UTF-8' ));
}?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['color'])) && (isset($_smarty_tpl->tpl_vars['tr']->value['color']))) {?>
					</span>
				<?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_danger'])) && $_smarty_tpl->tpl_vars['params']->value['badge_danger'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_danger'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_danger'] == $_smarty_tpl->tpl_vars['params']->value['badge_danger']) {?></span><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_warning'])) && $_smarty_tpl->tpl_vars['params']->value['badge_warning'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_warning'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_warning'] == $_smarty_tpl->tpl_vars['params']->value['badge_warning']) {?></span><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['params']->value['badge_success'])) && $_smarty_tpl->tpl_vars['params']->value['badge_success'] && (isset($_smarty_tpl->tpl_vars['tr']->value['badge_success'])) && $_smarty_tpl->tpl_vars['tr']->value['badge_success'] == $_smarty_tpl->tpl_vars['params']->value['badge_success']) {?></span><?php }?>
			<?php
}
}
/* {/block "td_content"} */
/* {block "close_td"} */
class Block_156116637865a7002c8368b4_97076393 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'close_td' => 
  array (
    0 => 'Block_156116637865a7002c8368b4_97076393',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

				</td>
			<?php
}
}
/* {/block "close_td"} */
}
