<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_new_product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e55ffb85_10350403',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e4b0918bf396f5ddbf765858689720491be3230a' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/_new_product.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a007e55ffb85_10350403 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('currencySymbolBeforeAmount', $_smarty_tpl->tpl_vars['currency']->value->format[0] === '¤');?>
<tr id="new_product" style="display:none">
	<td style="display:none;" colspan="2">
		<input type="hidden" id="add_product_product_id" name="add_product[product_id]" value="0" />

		<div class="form-group">
			<label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
			<div class="input-group">
				<input type="text" id="add_product_product_name2" value=""/>
				<div class="input-group-addon">
					<i class="icon-search"></i>
				</div>
			</div>
		</div>

		<div id="add_product_product_attribute_area" class="form-group" style="display: none;">
			<label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Combinations','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
			<select name="add_product[product_attribute_id]" id="add_product_product_attribute_id"></select>
		</div>

		<div id="add_product_product_warehouse_area" class="form-group" style="display: none;">
			<label><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Warehouse','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
			<select  id="add_product_warehouse" name="add_product_warehouse"></select>
		</div>
	</td>
	<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() != (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
	<td></td>
	<?php }?>
	<td style="display:none;">
		<div class="row">
			<div class="input-group fixed-width-xl">
				<?php if ($_smarty_tpl->tpl_vars['currencySymbolBeforeAmount']->value) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
				<input type="text" name="add_product[product_price_tax_excl]" id="add_product_product_price_tax_excl" value="" disabled="disabled" />
				<?php if (!($_smarty_tpl->tpl_vars['currencySymbolBeforeAmount']->value)) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="input-group fixed-width-xl">
				<?php if ($_smarty_tpl->tpl_vars['currencySymbolBeforeAmount']->value) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax incl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
				<input type="text" name="add_product[product_price_tax_incl]" id="add_product_product_price_tax_incl" value="" disabled="disabled" />
				<?php if (!($_smarty_tpl->tpl_vars['currencySymbolBeforeAmount']->value)) {?><div class="input-group-addon"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax incl.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div><?php }?>
			</div>
		</div>
	</td>

	<td style="display:none;" class="productQuantity">
		<input type="number" class="form-control fixed-width-sm" name="add_product[product_quantity]" id="add_product_product_quantity" value="1" disabled="disabled" />
	</td>
	<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid())) {?><td style="display:none;" class="productQuantity"></td><?php }?>
	<?php if ($_smarty_tpl->tpl_vars['display_warehouse']->value) {?><td></td><?php }?>
	<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered())) {?><td style="display:none;" class="productQuantity"></td><?php }?>
	<td style="display:none;" class="productQuantity" id="add_product_product_stock">0</td>
	<td style="display:none;" id="add_product_product_total"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>0,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</td>
	<td style="display:none;" colspan="2">
		<?php if (sizeof($_smarty_tpl->tpl_vars['invoices_collection']->value)) {?>
		<select class="form-control" name="add_product[invoice]" id="add_product_product_invoice" disabled="disabled">
			<optgroup class="existing" label="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Existing','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['invoices_collection']->value, 'invoice');
$_smarty_tpl->tpl_vars['invoice']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['invoice']->value) {
$_smarty_tpl->tpl_vars['invoice']->do_else = false;
?>
				<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->id,'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->getInvoiceNumberFormatted($_smarty_tpl->tpl_vars['current_id_lang']->value),'html','UTF-8' ));?>
</option>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</optgroup>
			<optgroup label="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'New','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
				<option value="0"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Create a new invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</option>
			</optgroup>
		</select>
		<?php }?>
	</td>
	<td style="display:none;">
		<button type="button" class="btn btn-default" id="cancelAddProduct">
			<i class="icon-remove text-danger"></i>
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

		</button>
		<button type="button" class="btn btn-default" id="submitAddProduct2" disabled="disabled">
			<i class="icon-ok text-success"></i>
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

		</button>
	</td>
</tr>

<tr id="new_invoice" style="display:none">
	<td colspan="10">
		<h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'New invoice information','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
		<div class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Carrier','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
				<div class="col-lg-9">
					<p class="form-control-static"><strong><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['carrier']->value->name,'html','UTF-8' ));?>
</strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping Cost','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
				<div class="col-lg-9">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="add_invoice[free_shipping]" value="1" />
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Free shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</label>
						<p class="help-block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'If you don\'t select "Free shipping," the normal shipping cost will be applied.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
					</div>
				</div>
			</div>
		</div>
	</td>
</tr>
<?php }
}
