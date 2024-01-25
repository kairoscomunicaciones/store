<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_customized_data.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e55873e1_93684286',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1eaebf53f254bad45933ab226c7340becbd931a2' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_customized_data.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e55873e1_93684286 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['product']->value['customizedDatas']) {?>
        <?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
    	<?php $_smarty_tpl->_assignInScope('product_price', ($_smarty_tpl->tpl_vars['product']->value['unit_price_tax_excl']+$_smarty_tpl->tpl_vars['product']->value['ecotax']));?>
    <?php } else { ?>
    	<?php $_smarty_tpl->_assignInScope('product_price', $_smarty_tpl->tpl_vars['product']->value['unit_price_tax_incl']);?>
    <?php }?>
    	<tr class="customized customized-<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
 product-line-row">
    		<td>
    			<input type="hidden" class="edit_product_id_order_detail" value="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
" />
    			<?php if ((isset($_smarty_tpl->tpl_vars['product']->value['image'])) && intval($_smarty_tpl->tpl_vars['product']->value['image']->id)) {
echo $_smarty_tpl->tpl_vars['product']->value['image_tag'];
} else { ?>--<?php }?>    		</td>
    		<td>
    			<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts'),'html','UTF-8' ));?>
&amp;id_product=<?php echo intval($_smarty_tpl->tpl_vars['product']->value['product_id']);?>
&amp;updateproduct&amp;token=<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getAdminToken'][0], array( array('tab'=>'AdminProducts'),$_smarty_tpl ) );?>
">
    			<span class="productName"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_name'],'html','UTF-8' ));?>
 - <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Customized','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span><br />
    			<?php if (($_smarty_tpl->tpl_vars['product']->value['product_reference'])) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Reference number:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_reference'],'html','UTF-8' ));?>
<br /><?php }?>
    			<?php if (($_smarty_tpl->tpl_vars['product']->value['product_supplier_reference'])) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Supplier reference:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_supplier_reference'],'html','UTF-8' ));
}?>
    			</a>
                <div class="product_price_edit" style="display:none;">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'orderProuductLideEdit','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                </div>
    		</td>
    		<td>
    			<span class="product_price_show"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['product_price']->value,'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>
</span>
    			<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
    			<div class="product_price_edit" style="display:none;">
    				<input type="hidden" name="product_id_order_detail" class="edit_product_id_order_detail" value="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
" />
    				<div class="form-group">
    					<div class="fixed-width-xl">
    						<div class="input-group">
    							<?php if ($_smarty_tpl->tpl_vars['currency']->value->format%2) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
    							<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['unit_price_tax_excl'],2),'html','UTF-8' ));?>
" size="5" />
    							<?php if (!($_smarty_tpl->tpl_vars['currency']->value->format%2)) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
    						</div>
    					</div>
    					<br/>
    					<div class="fixed-width-xl">
    						<div class="input-group">
    							<?php if ($_smarty_tpl->tpl_vars['currency']->value->format%2) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax incl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
    							<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['unit_price_tax_incl'],2),'html','UTF-8' ));?>
" size="5" />
    							<?php if (!($_smarty_tpl->tpl_vars['currency']->value->format%2)) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax incl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
    						</div>
    					</div>
    				</div>
    			</div>
    			<?php }?>
    		</td>
    		<td class="productQuantity text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],'html','UTF-8' ));?>
</td>
    		<?php if ($_smarty_tpl->tpl_vars['display_warehouse']->value) {?><td>&nbsp;</td><?php }?>
    		<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid())) {?><td class="productQuantity text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['customizationQuantityRefunded'],'html','UTF-8' ));?>
</td><?php }?>
    		<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || $_smarty_tpl->tpl_vars['order']->value->hasProductReturned())) {?><td class="productQuantity text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['customizationQuantityReturned'],'html','UTF-8' ));?>
</td><?php }?>
    		<?php if ((isset($_smarty_tpl->tpl_vars['stock_management']->value)) && $_smarty_tpl->tpl_vars['stock_management']->value) {?><td class="text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['current_stock'],'html','UTF-8' ));?>
</td><?php }?>
    		<td class="total_product">
    		<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
    			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price']*$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

    		<?php } else { ?>
    			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price_wt']*$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

    		<?php }?>
    		</td>
    		<td class="cancelQuantity standard_refund_fields current-edit" style="display:none" colspan="2">
    			&nbsp;
    		</td>
    		<td class="edit_product_fields" colspan="2" style="display:none">&nbsp;</td>
    		<td class="partial_refund_fields current-edit" style="text-align:left;display:none;"></td>           
    		<?php if (($_smarty_tpl->tpl_vars['can_edit']->value && !$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered())) {?>
    			<td class="product_action text-right">
    				    				<div class="btn-group order_action">
    					<button type="button" class="btn btn-default edit_product_change_link2">
    						<i class="icon-pencil"></i>
    						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    					</button>
    					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    						<span class="caret"></span>
    					</button>
    					<ul class="dropdown-menu" role="menu">
    						<li>
    							<a href="#" class="delete_product_line2">
    								<i class="icon-trash"></i>
    								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delete','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    							</a>
    						</li>
    					</ul>
    				</div>
    				    				<button type="button" rel="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
" class="btn btn-default product_price_edit submitProductChange2" style="display:none;">
    					<i class="icon-ok"></i>
    					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</button>
    				<button type="button" class="btn btn-default cancel_product_change_link" style="display: none;">
    					<i class="icon-remove"></i>
    					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    				</button>
    			</td>
    		<?php }?>
    	</tr>
    	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['customizedDatas'], 'customizationPerAddress');
$_smarty_tpl->tpl_vars['customizationPerAddress']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customizationPerAddress']->value) {
$_smarty_tpl->tpl_vars['customizationPerAddress']->do_else = false;
?>
    		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customizationPerAddress']->value, 'customization', false, 'customizationId');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customizationId']->value => $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
    			<tr id="customization_wp_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
"  class="customized customized-<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
">
    				<td colspan="2">
    				<input type="hidden" class="edit_product_id_order_detail" value="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
" />
    					<div class="form-horizontal">
    						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customization']->value['datas'], 'datas', false, 'type');
$_smarty_tpl->tpl_vars['datas']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['type']->value => $_smarty_tpl->tpl_vars['datas']->value) {
$_smarty_tpl->tpl_vars['datas']->do_else = false;
?>
    							<?php if (($_smarty_tpl->tpl_vars['type']->value == Product::CUSTOMIZE_FILE)) {?>
    								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
$_smarty_tpl->tpl_vars['data']->iteration = 0;
$_smarty_tpl->tpl_vars['data']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->do_else = false;
$_smarty_tpl->tpl_vars['data']->iteration++;
$__foreach_data_34_saved = $_smarty_tpl->tpl_vars['data'];
?>
    									<div class="form-group">
    										<span class="col-lg-4 control-label"><strong><?php if ($_smarty_tpl->tpl_vars['data']->value['name']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['name'],'html','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Picture #','mod'=>'g_ordermanager'),$_smarty_tpl ) );
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->iteration,'html','UTF-8' ));
}?></strong></span>
    										<div class="col-lg-8">
                                                <?php if ($_smarty_tpl->tpl_vars['data']->value['value'] != '') {?>
    											<a href="displayImage.php?img=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['value'],'html','UTF-8' ));?>
&amp;name=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
-file<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->iteration,'html','UTF-8' ));?>
" class="_blank">
    												<img class="img-thumbnail" src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( (defined('_THEME_PROD_PIC_DIR_') ? constant('_THEME_PROD_PIC_DIR_') : null),'html','UTF-8' ));
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['value'],'html','UTF-8' ));?>
_small" alt=""/>
    											</a>
                                                <?php } else { ?>--<?php }?>
                                                <div class="product_price_edit" style="display:none;">
                                                    <?php if ($_smarty_tpl->tpl_vars['data']->value['value'] != '') {?>
                                                    <p>
                                                        <input type="checkbox" id="customization_remove_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
" name="customization_remove[<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
][<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
]" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
"/>
                                                        <label for="customization_remove_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delete','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
                                                    </p>
                                                    <?php }?>
                                                    <input rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
" class="customization_file_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_order_detail'],'html','UTF-8' ));?>
" type="file" name="customization[<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
][<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
]" id="customization_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
" />
                                                </div>
    										</div>
    									</div>
    								<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_34_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    							<?php } elseif (($_smarty_tpl->tpl_vars['type']->value == Product::CUSTOMIZE_TEXTFIELD)) {?>
    								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
$_smarty_tpl->tpl_vars['data']->iteration = 0;
$_smarty_tpl->tpl_vars['data']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->do_else = false;
$_smarty_tpl->tpl_vars['data']->iteration++;
$__foreach_data_35_saved = $_smarty_tpl->tpl_vars['data'];
?>
    									<div class="form-group">
    										<span class="col-lg-4 control-label"><strong><?php if ($_smarty_tpl->tpl_vars['data']->value['name']) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s','sprintf'=>$_smarty_tpl->tpl_vars['data']->value['name'],'mod'=>'g_ordermanager'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Text #%s','sprintf'=>$_smarty_tpl->tpl_vars['data']->iteration,'mod'=>'g_ordermanager'),$_smarty_tpl ) );
}?></strong></span>
    										<div class="col-lg-8">
    											<p class="form-control-static"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['value'],'html','UTF-8' ));?>
</p>
                                                <div class="product_price_edit" style="display:none;">
                                                    <textarea  class="customization_text_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_order_detail'],'html','UTF-8' ));?>
"   name="customization[<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
][<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
]" id="customization_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['id_customization'],'html','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['index'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['value'],'html','UTF-8' ));?>
</textarea>
    										      </div>
                                            </div>
    									</div>
    								<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_35_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    							<?php }?>
    						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    					</div>
    				</td>
    				<td>-</td>
    				<td class="productQuantity text-center">
    					<span class="product_quantity_show<?php if ((int)$_smarty_tpl->tpl_vars['customization']->value['quantity'] > 1) {?> red bold<?php }?>"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customization']->value['quantity'],'html','UTF-8' ));?>
</span>
    					<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
    					<span class="product_quantity_edit" style="display:none;">
    						<input type="text" name="product_quantity[<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
]" class="edit_product_quantity" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['customization']->value['quantity']);?>
" size="2" />
    					</span>
    					<?php }?>
    				</td>
    				<?php if ($_smarty_tpl->tpl_vars['display_warehouse']->value) {?><td>&nbsp;</td><?php }?>
    				<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid())) {?>
    				<td class="text-center">
    					<?php if (!empty($_smarty_tpl->tpl_vars['product']->value['amount_refund'])) {?>
    					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s (%s refund)','sprintf'=>array($_smarty_tpl->tpl_vars['customization']->value['quantity_refunded'],$_smarty_tpl->tpl_vars['product']->value['amount_refund']),'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    					<?php }?>
    					<input type="hidden" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['quantity_refundable'],'html','UTF-8' ));?>
" class="partialRefundProductQuantity" />
    					<input type="hidden" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( (Tools::ps_round($_smarty_tpl->tpl_vars['product_price']->value,2)*($_smarty_tpl->tpl_vars['product']->value['product_quantity']-$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'])),'html','UTF-8' ));?>
" class="partialRefundProductAmount" />
    				</td>
    				<?php }?>
    				<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered())) {?><td class="text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customization']->value['quantity_returned'],'html','UTF-8' ));?>
</td><?php }?>
    				<td class="text-center">-</td>
    				<td class="total_product">
    					<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
    						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price']*$_smarty_tpl->tpl_vars['customization']->value['quantity'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

    					<?php } else { ?>
    						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price_wt']*$_smarty_tpl->tpl_vars['customization']->value['quantity'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

    					<?php }?>
    				</td>
    				<td class="cancelCheck standard_refund_fields current-edit" style="display:none">
    					<input type="hidden" name="totalQtyReturn" id="totalQtyReturn" value="<?php echo intval($_smarty_tpl->tpl_vars['customization']->value['quantity_returned']);?>
" />
    					<input type="hidden" name="totalQty" id="totalQty" value="<?php echo intval($_smarty_tpl->tpl_vars['customization']->value['quantity']);?>
" />
    					<input type="hidden" name="productName" id="productName" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_name'],'html','UTF-8' ));?>
" />
    					<?php if (((!$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || Configuration::get('PS_ORDER_RETURN')) && (int)($_smarty_tpl->tpl_vars['customization']->value['quantity_returned']) < (int)($_smarty_tpl->tpl_vars['customization']->value['quantity']))) {?>
    						<input type="checkbox" name="id_customization[<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
]" id="id_customization[<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
]" value="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
" onchange="setCancelQuantity(this, <?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
, <?php echo $_smarty_tpl->tpl_vars['customization']->value['quantity']-$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal']-call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_quantity_reinjected'],'html','UTF-8' ));?>
)" <?php if (($_smarty_tpl->tpl_vars['product']->value['product_quantity_return']+$_smarty_tpl->tpl_vars['product']->value['product_quantity_refunded'] >= $_smarty_tpl->tpl_vars['product']->value['product_quantity'])) {?>disabled="disabled" <?php }?>/>
    					<?php } else { ?>
    					--
    				<?php }?>
    				</td>
    				<td class="cancelQuantity standard_refund_fields current-edit" style="display:none">
    				<?php if (($_smarty_tpl->tpl_vars['customization']->value['quantity_returned']+$_smarty_tpl->tpl_vars['customization']->value['quantity_refunded'] >= $_smarty_tpl->tpl_vars['customization']->value['quantity'])) {?>
    					<input type="hidden" name="cancelCustomizationQuantity[<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
]" value="0" />
    				<?php } elseif ((!$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || Configuration::get('PS_ORDER_RETURN'))) {?>
    					<input type="text" id="cancelQuantity_<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
" name="cancelCustomizationQuantity[<?php echo intval($_smarty_tpl->tpl_vars['customizationId']->value);?>
]" size="2" onclick="selectCheckbox(this);" value="" />0/<?php echo $_smarty_tpl->tpl_vars['customization']->value['quantity']-call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customization']->value['quantity_refunded'],'html','UTF-8' ));?>

    				<?php }?>
    				</td>
    				<td class="partial_refund_fields current-edit" colspan="2" style="display:none; width: 250px;">
    					<?php if ($_smarty_tpl->tpl_vars['product']->value['quantity_refundable'] > 0) {?>
    					<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
    						<?php $_smarty_tpl->_assignInScope('amount_refundable', $_smarty_tpl->tpl_vars['product']->value['amount_refundable']);?>
    					<?php } else { ?>
    						<?php $_smarty_tpl->_assignInScope('amount_refundable', $_smarty_tpl->tpl_vars['product']->value['amount_refundable_tax_incl']);?>
    					<?php }?>
    					<div class="form-group">
    						<div class="<?php if ($_smarty_tpl->tpl_vars['product']->value['amount_refundable'] > 0) {?>col-lg-4<?php } else { ?>col-lg-12<?php }?>">
    							<label class="control-label">
    								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quantity:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

    							</label>
    							<div class="input-group">
    								<input onchange="checkPartialRefundProductQuantity(this)" type="text" name="partialRefundProductQuantity[<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
]" value="<?php if (($_smarty_tpl->tpl_vars['customization']->value['quantity']-$_smarty_tpl->tpl_vars['customization']->value['quantity_refunded']) > 0) {?>1<?php } else { ?>0<?php }?>" />
    								<div class="input-group-addon">/ <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['quantity_refundable'],'html','UTF-8' ));?>
</div>
    							</div>
    						</div>
    						<div class="<?php if ($_smarty_tpl->tpl_vars['product']->value['quantity_refundable'] > 0) {?>col-lg-8<?php } else { ?>col-lg-12<?php }?>">
    							<label class="control-label">
    								<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
    								<small class="text-muted">(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod'),'html','UTF-8' ));?>
)</small>
    							</label>
    							<div class="input-group">
    								<?php if ($_smarty_tpl->tpl_vars['currency']->value->format%2) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
</div><?php }?>
    								<input onchange="checkPartialRefundProductAmount(this)" type="text" name="partialRefundProduct[<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_order_detail']);?>
]" />
    								<?php if (!($_smarty_tpl->tpl_vars['currency']->value->format%2)) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
</div><?php }?>
    							</div>
    							<p class="help-block"><i class="icon-warning-sign"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'(Max %s %s)','sprintf'=>array(Tools::displayPrice(Tools::ps_round($_smarty_tpl->tpl_vars['amount_refundable']->value,2),$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod')),'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
    						</div>
    					</div>
    					<?php }?>
    				</td>
    				<?php if (($_smarty_tpl->tpl_vars['can_edit']->value && !$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered())) {?>
    					<td class="edit_product_fields" colspan="2" style="display:none"></td>
    					<td class="product_action" style="text-align:right"></td>
    				<?php }?>
    			</tr>
    		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }
}
}
