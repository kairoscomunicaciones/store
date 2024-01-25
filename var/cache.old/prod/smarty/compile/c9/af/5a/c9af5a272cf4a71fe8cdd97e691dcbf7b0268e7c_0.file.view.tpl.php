<?php
/* Smarty version 3.1.43, created on 2024-01-11 11:23:17
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/view/view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a007e5480114_27644613',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c9af5a272cf4a71fe8cdd97e691dcbf7b0268e7c' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/ordermanager/helpers/view/view.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../../_customized_data.tpl' => 1,
    'file:../../_product_line.tpl' => 1,
    'file:../../_new_product.tpl' => 1,
    'file:../../_discount_form.tpl' => 1,
    'file:../../_discount_val.tpl' => 1,
  ),
),false)) {
function content_65a007e5480114_27644613 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>



<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_108347352365a007e52e6e75_88685735', "override_tpl");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "helpers/view/view.tpl");
}
/* {block "override_tpl"} */
class Block_108347352365a007e52e6e75_88685735 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'override_tpl' => 
  array (
    0 => 'Block_108347352365a007e52e6e75_88685735',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),1=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/modifier.regex_replace.php','function'=>'smarty_modifier_regex_replace',),));
?>

    <?php $_smarty_tpl->_assignInScope('adminaddresseslink', ((string)$_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminAddresses',true))."&submitFormAjax=1");?>
	<?php echo '<script'; ?>
 type="text/javascript">
    /* token = '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getAdminToken'][0], array( array('tab'=>'AdminOrders'),$_smarty_tpl ) );?>
'; */
    var admin_ordermanager_tab_link = decodeURIComponent("<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['admin_ordermanager_tab_link']->value,'htmlall','UTF-8' ));?>
");
	var admin_order_tab_link = decodeURIComponent("<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['admin_order_tab_link']->value,'htmlall','UTF-8' ));?>
");
    var admin_address_link   = decodeURIComponent("<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['admin_address_link']->value,'htmlall','UTF-8' ));?>
");
    var admin_address_link_back = decodeURIComponent("<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['adminaddresseslink']->value,'htmlall','UTF-8' ));?>
");
	var id_order = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
;
	var id_lang = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_id_lang']->value,'html','UTF-8' ));?>
;
	var id_currency = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id_currency,'html','UTF-8' ));?>
;
	var id_customer = <?php echo intval($_smarty_tpl->tpl_vars['order']->value->id_customer);?>
;
	<?php $_smarty_tpl->_assignInScope('PS_TAX_ADDRESS_TYPE', Configuration::get('PS_TAX_ADDRESS_TYPE'));?>
	var id_address = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->{$_smarty_tpl->tpl_vars['PS_TAX_ADDRESS_TYPE']->value},'html','UTF-8' ));?>
;
	var currency_sign = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->sign,'html','UTF-8' ));?>
";
	var currency_format = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->format,'html','UTF-8' ));?>
";
	var currency_blank = "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->blank,'html','UTF-8' ));?>
";
	var priceDisplayPrecision = <?php echo intval((defined('_PS_PRICE_DISPLAY_PRECISION_') ? constant('_PS_PRICE_DISPLAY_PRECISION_') : null));?>
;
	var use_taxes = <?php if ($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_INC') ? constant('PS_TAX_INC') : null)) {?>true<?php } else { ?>false<?php }?>;
	var stock_management = <?php echo intval($_smarty_tpl->tpl_vars['stock_management']->value);?>
;
	var txt_add_product_stock_issue = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Are you sure you want to add this quantity?','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var txt_add_product_new_invoice = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Are you sure you want to create a new invoice?','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var txt_add_product_no_product = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Error: No product has been selected','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var txt_add_product_no_product_quantity = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Error: Quantity of products must be set','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var txt_add_product_no_product_price = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Error: Product price must be set','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var txt_confirm = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Are you sure?','js'=>1,'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	var statesShipped = new Array();
	var has_voucher = <?php if (count($_smarty_tpl->tpl_vars['discounts']->value)) {?>1<?php } else { ?>0<?php }?>;
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['states']->value, 'state');
$_smarty_tpl->tpl_vars['state']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['state']->value) {
$_smarty_tpl->tpl_vars['state']->do_else = false;
?>
		<?php if (((isset($_smarty_tpl->tpl_vars['currentState']->value->shipped)) && !$_smarty_tpl->tpl_vars['currentState']->value->shipped && $_smarty_tpl->tpl_vars['state']->value['shipped'])) {?>
			statesShipped.push(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['state']->value['id_order_state'],'html','UTF-8' ));?>
);
		<?php }?>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	var order_discount_price = <?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
									<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_excl,'html','UTF-8' ));?>

								<?php } else { ?>
									<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl,'html','UTF-8' ));?>

								<?php }?>;

	var errorRefund = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Error. You cannot refund a negative amount.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
";
	<?php echo '</script'; ?>
>

	<?php ob_start();
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayInvoice",'id_order'=>$_smarty_tpl->tpl_vars['order']->value->id),$_smarty_tpl ) );
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('hook_invoice', $_prefixVariable1);?>
	<?php if (($_smarty_tpl->tpl_vars['hook_invoice']->value)) {?>
	<div><?php echo $_smarty_tpl->tpl_vars['hook_invoice']->value;?>
</div>	<?php }?>
	<div class="panel kpi-container order_view_page">
		<div class="row">
			<div class="col-xs-6 col-sm-3 box-stats color3" >
				<div class="kpi-content">
					<i class="icon-calendar-empty"></i>
					<span class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
					<span class="value"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['order']->value->date_add,'full'=>false),$_smarty_tpl ) );?>
</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color4" >
				<div class="kpi-content">
					<i class="icon-money"></i>
					<span class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
					<span class="value"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color2" >
				<div class="kpi-content">
					<i class="icon-comments"></i>
					<span class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Messages','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
					<span class="value"><a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminCustomerThreads'),'html','UTF-8' ));?>
&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sizeof($_smarty_tpl->tpl_vars['customer_thread_message']->value),'html','UTF-8' ));?>
</a></span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 box-stats color1" >
				<a href="#start_products">
					<div class="kpi-content">
						<i class="icon-book"></i>
						<span class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
						<span class="value"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sizeof($_smarty_tpl->tpl_vars['products']->value),'html','UTF-8' ));?>
</span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-7">
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-credit-card"></i>
					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

					
                    <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Click to change order reference','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
 				        <span class="badge orderreference">
                            <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->reference,'html','UTF-8' ));?>

            			</span>
                    </span>
					<span class="badge"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'#','mod'=>'g_ordermanager'),$_smarty_tpl ) );
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
</span>
					<div class="panel-heading-action">
						<div class="btn-group">
							<a class="btn btn-default<?php if (!$_smarty_tpl->tpl_vars['previousOrder']->value) {?> disabled<?php }?>" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminOrdermanager'),'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['previousOrder']->value);?>
">
								<i class="icon-backward"></i>
							</a>
							<a class="btn btn-default<?php if (!$_smarty_tpl->tpl_vars['nextOrder']->value) {?> disabled<?php }?>" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminOrdermanager'),'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['nextOrder']->value);?>
">
								<i class="icon-forward"></i>
							</a>
						</div>
					</div>
				</div>
                <div class="panel change_reference">
                    <div class="panel-heading">#<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Change Reference','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</div>
                    <div class="col-lg-6">
                        <div class="row">
                			<div class="col-lg-9">
                                <input type="text" id="reference_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->reference,'html','UTF-8' ));?>
" />
                                <small class="text-muted"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Maximum 9 charachter.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</small>
                            </div>
                            <div class="col-lg-3">
                                <button rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" type="submit" class="btn btn-default pull-left OrderChangeReference"><i class="icon-save"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
				<!-- Orders Actions -->
				<div class="well hidden-print">
					<a class="btn btn-default print_order_link" href="submitPrintOrder"  rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
">
						<i class="icon-print"></i>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Print order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

					</a>
					&nbsp;
					<?php if (Configuration::get('PS_INVOICE') && count($_smarty_tpl->tpl_vars['invoices_collection']->value) && $_smarty_tpl->tpl_vars['order']->value->invoice_number) {?>
						<a data-selenium-id="view_invoice" class="btn btn-default _blank" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminPdf'),'html','UTF-8' ));?>
&amp;submitAction=generateInvoicePDF&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
							<i class="icon-file"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</a>
					<?php } else { ?>
						<span class="span label label-inactive">
							<i class="icon-remove"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</span>
					<?php }?>
					&nbsp;
					<?php if ($_smarty_tpl->tpl_vars['order']->value->delivery_number) {?>
						<a class="btn btn-default _blank"  href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminPdf'),'html','UTF-8' ));?>
&amp;submitAction=generateDeliverySlipPDF&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
							<i class="icon-truck"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View delivery slip','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</a>
					<?php } else { ?>
						<span class="span label label-inactive">
							<i class="icon-remove"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No delivery slip','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</span>
					<?php }?>
					&nbsp;
					<?php if (Configuration::get('PS_ORDER_RETURN')) {?>
						<a id="desc-order-standard_refund" class="btn btn-default" href="#refundForm">
							<i class="icon-exchange"></i>
							<?php if ($_smarty_tpl->tpl_vars['order']->value->hasBeenShipped()) {?>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Return products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							<?php } elseif ($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid()) {?>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Standard refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							<?php } else { ?>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							<?php }?>
						</a>
						&nbsp;
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['order']->value->hasInvoice()) {?>
						<a id="desc-order-partial_refund" class="btn btn-default" href="#refundForm">
							<i class="icon-exchange"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Partial refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</a>
					<?php }?>
                    <a id="desc-order-copy" class="btn btn-default duplicateorder" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" href="#copyForm">
						<i class="icon-copy"></i>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Duplicate order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

					</a>
				</div>
				<!-- Tab nav -->
				<ul class="nav nav-tabs" id="tabOrder">
                    <?php if ((isset($_smarty_tpl->tpl_vars['HOOK_TAB_ORDER']->value))) {?>
					   <?php echo $_smarty_tpl->tpl_vars['HOOK_TAB_ORDER']->value;?>
                    <?php }?>
					<li class="active">
						<a href="#status">
							<i class="icon-time"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['history']->value),'html','UTF-8' ));?>
</span>
						</a>
					</li>
					<li>
						<a href="#documents">
							<i class="icon-file-text"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Documents','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getDocuments()),'html','UTF-8' ));?>
</span>
						</a>
					</li>
				</ul>
				<!-- Tab content -->
				<div class="tab-content panel">
                    <?php if ((isset($_smarty_tpl->tpl_vars['HOOK_CONTENT_ORDER']->value))) {?>
					   <?php echo $_smarty_tpl->tpl_vars['HOOK_CONTENT_ORDER']->value;?>
                    <?php }?>
					<!-- Tab status -->
					<div class="tab-pane active" id="status">
						<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge">(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['history']->value),'html','UTF-8' ));?>
)</span></h4>
						<!-- History of status -->
						<div class="table-responsive order_history_table">
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
 <?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_lastname']);
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
							</table>
						</div>
						<!-- Change status form -->
                        <div class="row">
                            <div class="col-lg-9">
                    			<select id="id_order_state_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" class="chosen form-control" name="id_order_state">
                    			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['states']->value, 'state');
$_smarty_tpl->tpl_vars['state']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['state']->value) {
$_smarty_tpl->tpl_vars['state']->do_else = false;
?>
                    				<option value="<?php echo intval($_smarty_tpl->tpl_vars['state']->value['id_order_state']);?>
"<?php if ((isset($_smarty_tpl->tpl_vars['currentState']->value)) && $_smarty_tpl->tpl_vars['state']->value['id_order_state'] == $_smarty_tpl->tpl_vars['currentState']->value->id) {?> selected="selected" disabled="disabled"<?php }?>><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['state']->value['name'] ));?>
</option>
                    			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    			</select>
                    		</div>
                    		<div class="col-lg-3">
                    			<button rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
"  type="submit" name="submitState" class="btn btn-primary submitChangeState">
                    				<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                    			</button>
                    		</div>
                        </div>
					</div>
					<!-- Tab documents -->
					<div class="tab-pane" id="documents">
						<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Documents','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge">(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getDocuments()),'html','UTF-8' ));?>
)</span></h4>
						                        <div class="table-responsive">
                        	<table class="table" id="documents_table">
                        		<thead>
                        			<tr>
                        				<th>
                        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
                        				</th>
                        				<th>
                        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Document','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
                        				</th>
                        				<th>
                        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Number','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
                        				</th>
                        				<th>
                        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
                        				</th>
                        				<th></th>
                        			</tr>
                        		</thead>
                        		<tbody>
                        			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getDocuments(), 'document');
$_smarty_tpl->tpl_vars['document']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['document']->value) {
$_smarty_tpl->tpl_vars['document']->do_else = false;
?>
                        
                        				<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        					<?php if ((isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        					<tr id="delivery_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        					<?php } else { ?>
                        					<tr id="invoice_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        					<?php }?>
                        				<?php } elseif (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderSlip') {?>
                        					<tr id="orderslip_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        				<?php }?>
                        
                        						<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['document']->value->date_add),$_smarty_tpl ) );?>
</td>
                        						<td>
                        							<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        								<?php if ((isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery slip','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        								<?php } else { ?>
                        									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        								<?php }?>
                        							<?php } elseif (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderSlip') {?>
                        								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Credit Slip','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        							<?php }?>
                        						</td>
                        						<td>
                        							<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        								<?php if ((isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        									<a class="_blank" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See the document','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminPdf'),'html','UTF-8' ));?>
&amp;submitAction=generateDeliverySlipPDF&amp;id_order_invoice=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        								<?php } else { ?>
                        									<a class="_blank" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See the document','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminPdf'),'html','UTF-8' ));?>
&amp;submitAction=generateInvoicePDF&amp;id_order_invoice=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        							   <?php }?>
                        							<?php } elseif (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderSlip') {?>
                        								<a class="_blank" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See the document','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminPdf'),'html','UTF-8' ));?>
&amp;submitAction=generateOrderSlipPDF&amp;id_order_slip=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
">
                        							<?php }?>
                        							<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        								<?php if ((isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        									<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Configuration::get('PS_DELIVERY_PREFIX',$_smarty_tpl->tpl_vars['current_id_lang']->value,null,$_smarty_tpl->tpl_vars['order']->value->id_shop),'html','UTF-8' ));
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf('%06d',$_smarty_tpl->tpl_vars['document']->value->delivery_number),'html','UTF-8' ));?>

                        								<?php } else { ?>
                        									<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->getInvoiceNumberFormatted($_smarty_tpl->tpl_vars['current_id_lang']->value,$_smarty_tpl->tpl_vars['order']->value->id_shop),'html','UTF-8' ));?>

                        								<?php }?>
                        							<?php } elseif (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderSlip') {?>
                        								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Configuration::get('PS_CREDIT_SLIP_PREFIX',$_smarty_tpl->tpl_vars['current_id_lang']->value),'html','UTF-8' ));
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf('%06d',$_smarty_tpl->tpl_vars['document']->value->id),'html','UTF-8' ));?>

                        							<?php }?>
                        							</a>
                        						</td>
                        						<td>
                        						<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        							<?php if ((isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        								--
                        							<?php } else { ?>
                        								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['document']->value->total_paid_tax_incl,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
&nbsp;
                        								<?php if ($_smarty_tpl->tpl_vars['document']->value->getTotalPaid()) {?>
                        									<span>
                        									<?php if ($_smarty_tpl->tpl_vars['document']->value->getRestPaid() > 0) {?>
                        										(<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['document']->value->getRestPaid(),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'not paid','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
)
                        									<?php } elseif ($_smarty_tpl->tpl_vars['document']->value->getRestPaid() < 0) {?>
                        										(<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>-$_smarty_tpl->tpl_vars['document']->value->getRestPaid(),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'overpaid','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
)
                        									<?php }?>
                        									</span>
                        								<?php }?>
                        							<?php }?>
                        						<?php } elseif (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderSlip') {?>
                        							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['document']->value->total_products_tax_incl+$_smarty_tpl->tpl_vars['document']->value->total_shipping_tax_incl,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

                        						<?php }?>
                        						</td>
                        						<td class="text-right document_action">
                        						<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        							<?php if (!(isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        
                        								<?php if ($_smarty_tpl->tpl_vars['document']->value->getRestPaid()) {?>
                        									<a href="#formAddPaymentPanel" class="js-set-payment btn btn-default anchor" data-amount="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->getRestPaid(),'html','UTF-8' ));?>
" data-id-invoice="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Set payment form','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
">
                        										<i class="icon-money"></i>
                        										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enter payment','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        									</a>
                        								<?php }?>
                        
                        								<a href="#" class="btn btn-default" onclick="$('#invoiceNote<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
').show(); return false;" title="<?php if ($_smarty_tpl->tpl_vars['document']->value->note == '') {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add note','mod'=>'g_ordermanager'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit note','mod'=>'g_ordermanager'),$_smarty_tpl ) );
}?>">
                        									<?php if ($_smarty_tpl->tpl_vars['document']->value->note == '') {?>
                        										<i class="icon-plus-sign-alt"></i>
                        										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add note','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        									<?php } else { ?>
                        										<i class="icon-pencil"></i>
                        										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit note','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        									<?php }?>
                        								</a>
                        
                        							<?php }?>
                        						<?php }?>
                        						</td>
                        					</tr>
                        				<?php if (get_class($_smarty_tpl->tpl_vars['document']->value) == 'OrderInvoice') {?>
                        					<?php if (!(isset($_smarty_tpl->tpl_vars['document']->value->is_delivery))) {?>
                        					<tr id="invoiceNote<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
" style="display:none">
                        						<td colspan="5">
                        							<form action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;viewOrder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));
if ((isset($_GET['token']))) {?>&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));
}?>" method="post">
                        								<p>
                        									<label for="editNote<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
" class="t"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Note','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
                        									<input type="hidden" class="id_order_invoice-note" name="id_order_invoice" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
" />
                        									<textarea name="note" id="editNote<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
" class="edit-note edit-note-value textarea-autosize"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->note,'html','UTF-8' ));?>
</textarea>
                        								</p>
                        								<p>
                        									<button type="button" name="submitEditNote" id="submitEditNote" class="btn btn-default">
                        										<i class="icon-save"></i>
                        										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        									</button>
                        									<a class="btn btn-default" href="#" id="cancelNote" onclick="$('#invoiceNote<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['document']->value->id,'html','UTF-8' ));?>
').hide();return false;">
                        										<i class="icon-remove"></i>
                        										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        									</a>
                        								</p>
                        							</form>
                        						</td>
                        					</tr>
                        					<?php }?>
                        				<?php }?>
                        			<?php
}
if ($_smarty_tpl->tpl_vars['document']->do_else) {
?>
                        				<tr>
                        					<td colspan="5" class="list-empty">
                        						<div class="list-empty-msg">
                        							<i class="icon-warning-sign list-empty-icon"></i>
                        							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'There is no available document','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        						</div>
                        						<?php if ((isset($_smarty_tpl->tpl_vars['invoice_management_active']->value)) && $_smarty_tpl->tpl_vars['invoice_management_active']->value) {?>
                        							<a class="btn btn-default" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;viewOrder&amp;submitGenerateInvoice&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));
if ((isset($_GET['token']))) {?>&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));
}?>">
                        								<i class="icon-repeat"></i>
                        								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                        							</a>
                        						<?php }?>
                        					</td>
                        				</tr>
                        			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        		</tbody>
                        	</table>
                        </div>
					</div>
				</div>
				<?php echo '<script'; ?>
>
					$('#tabOrder a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				<?php echo '</script'; ?>
>
				<hr />
				<!-- Tab nav -->
				<ul class="nav nav-tabs" id="myTab">
                    <?php if ((isset($_smarty_tpl->tpl_vars['HOOK_TAB_SHIP']->value))) {?>
					   <?php echo $_smarty_tpl->tpl_vars['HOOK_TAB_SHIP']->value;?>
                    <?php }?>
					<li class="active">
						<a href="#shipping">
							<i class="icon-truck "></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getShipping()),'html','UTF-8' ));?>
</span>
						</a>
					</li>
					<li>
						<a href="#returns">
							<i class="icon-undo"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Merchandise Returns','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getReturn()),'html','UTF-8' ));?>
</span>
						</a>
					</li>
				</ul>
				<!-- Tab content -->
				<div class="tab-content panel">
                <?php if ((isset($_smarty_tpl->tpl_vars['HOOK_CONTENT_SHIP']->value))) {?>
				   <?php echo $_smarty_tpl->tpl_vars['HOOK_CONTENT_SHIP']->value;?>
                <?php }?>
					<!-- Tab shipping -->
					<div class="tab-pane active" id="shipping">
						<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge">(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getShipping()),'html','UTF-8' ));?>
)</span></h4>
						<!-- Shipping block -->
						<?php if (!$_smarty_tpl->tpl_vars['order']->value->isVirtual()) {?>
						<div class="form-horizontal">
							<?php if ($_smarty_tpl->tpl_vars['order']->value->gift_message) {?>
							<div class="form-group">
								<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Message','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
								<div class="col-lg-9">
									<p class="form-control-static"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( nl2br($_smarty_tpl->tpl_vars['order']->value->gift_message),'html','UTF-8' ));?>
</p>
								</div>
							</div>
							<?php }?>
                            <div id="orderchangeshipping_wp">
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'OrderChangeShipping','order'=>$_smarty_tpl->tpl_vars['order']->value),$_smarty_tpl ) );?>

                            </div>
							<?php if ($_smarty_tpl->tpl_vars['carrierModuleCall']->value) {?>
								<?php echo $_smarty_tpl->tpl_vars['carrierModuleCall']->value;?>
							<?php }?>
							<hr />
							<?php if ($_smarty_tpl->tpl_vars['order']->value->recyclable) {?>
								<span class="label label-success"><i class="icon-check"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Recycled packaging','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
							<?php } else { ?>
								<span class="label label-inactive"><i class="icon-remove"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Recycled packaging','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['order']->value->gift) {?>
								<span class="label label-success"><i class="icon-check"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gift wrapping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
							<?php } else { ?>
								<span class="label label-inactive"><i class="icon-remove"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gift wrapping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
							<?php }?>
						</div>
						<?php }?>
					</div>
					<!-- Tab returns -->
					<div class="tab-pane" id="returns">
						<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Merchandise Returns','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge">(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['order']->value->getReturn()),'html','UTF-8' ));?>
)</span></h4>
						<?php if (!$_smarty_tpl->tpl_vars['order']->value->isVirtual()) {?>
						<!-- Return block -->
							<?php if (count($_smarty_tpl->tpl_vars['order']->value->getReturn()) > 0) {?>
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
											<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Type','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
											<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Carrier','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
											<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tracking number','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
										</tr>
									</thead>
									<tbody>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getReturn(), 'line');
$_smarty_tpl->tpl_vars['line']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['line']->value) {
$_smarty_tpl->tpl_vars['line']->do_else = false;
?>
										<tr>
											<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['date_add'],'html','UTF-8' ));?>
</td>
											<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['type'],'html','UTF-8' ));?>
</td>
											<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['state_name'],'html','UTF-8' ));?>
</td>
											<td class="actions">
												<span class="shipping_number_show"><?php if ((isset($_smarty_tpl->tpl_vars['line']->value['url'])) && (isset($_smarty_tpl->tpl_vars['line']->value['tracking_number']))) {?><a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( smarty_modifier_replace($_smarty_tpl->tpl_vars['line']->value['url'],'@',$_smarty_tpl->tpl_vars['line']->value['tracking_number']),'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['tracking_number'],'html','UTF-8' ));?>
</a><?php } elseif ((isset($_smarty_tpl->tpl_vars['line']->value['tracking_number']))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['tracking_number'],'html','UTF-8' ));
}?></span>
												<?php if ($_smarty_tpl->tpl_vars['line']->value['can_edit']) {?>
												<form method="post" action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminOrders'),'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
&amp;id_order_invoice=<?php if ($_smarty_tpl->tpl_vars['line']->value['id_order_invoice']) {
echo intval($_smarty_tpl->tpl_vars['line']->value['id_order_invoice']);
} else { ?>0<?php }?>&amp;id_carrier=<?php if ($_smarty_tpl->tpl_vars['line']->value['id_carrier']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['id_carrier'],'html','UTF-8' ));
} else { ?>0<?php }?>">
													<span class="shipping_number_edit" style="display:none;">
														<button type="button" name="tracking_number">
															<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( htmlentities($_smarty_tpl->tpl_vars['line']->value['tracking_number']),'html','UTF-8' ));?>

														</button>
														<button type="submit" class="btn btn-default" name="submitShippingNumber">
															<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Update','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

														</button>
													</span>
													<button href="#" class="edit_shipping_number_link">
														<i class="icon-pencil"></i>
														<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

													</button>
													<button href="#" class="cancel_shipping_number_link" style="display: none;">
														<i class="icon-remove"></i>
														<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

													</button>
												</form>
												<?php }?>
											</td>
										</tr>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</tbody>
								</table>
							</div>
							<?php } else { ?>
							<div class="list-empty hidden-print">
								<div class="list-empty-msg">
									<i class="icon-warning-sign list-empty-icon"></i>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No merchandise returned yet','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</div>
							</div>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['carrierModuleCall']->value) {?>
								<?php echo $_smarty_tpl->tpl_vars['carrierModuleCall']->value;?>
							<?php }?>
						<?php }?>
					</div>
				</div>
				<?php echo '<script'; ?>
>
					$('#myTab a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				<?php echo '</script'; ?>
>
			</div>
			<!-- Payments block -->
            <div id="formAddPaymentPanel"  class="panel change_payment">
            	<div class="panel-heading">
            		<i class="icon-money"></i>
            		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

            	</div>
                <div class="payment_table">
                	<?php if (count($_smarty_tpl->tpl_vars['order']->value->getOrderPayments()) > 0) {?>
                		<p class="alert alert-danger"<?php if (round($_smarty_tpl->tpl_vars['orders_total_paid_tax_incl']->value,2) == round($_smarty_tpl->tpl_vars['total_paid']->value,2) || ((isset($_smarty_tpl->tpl_vars['currentState']->value)) && $_smarty_tpl->tpl_vars['currentState']->value->id == 6)) {?> style="display: none;"<?php }?>>
                			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Warning','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                			<strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['total_paid']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</strong>
                			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'paid instead of','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                			<strong class="total_paid"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['orders_total_paid_tax_incl']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</strong>
                			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getBrother(), 'brother_order');
$_smarty_tpl->tpl_vars['brother_order']->index = -1;
$_smarty_tpl->tpl_vars['brother_order']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['brother_order']->value) {
$_smarty_tpl->tpl_vars['brother_order']->do_else = false;
$_smarty_tpl->tpl_vars['brother_order']->index++;
$_smarty_tpl->tpl_vars['brother_order']->first = !$_smarty_tpl->tpl_vars['brother_order']->index;
$__foreach_brother_order_8_saved = $_smarty_tpl->tpl_vars['brother_order'];
?>
                				<?php if ($_smarty_tpl->tpl_vars['brother_order']->first) {?>
                					<?php if (count($_smarty_tpl->tpl_vars['order']->value->getBrother()) == 1) {?>
                						<br /><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This warning also concerns order ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                					<?php } else { ?>
                						<br /><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This warning also concerns the next orders:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                					<?php }?>
                				<?php }?>
                				<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brother_order']->value->id,'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
">
                					#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf('%06d',$_smarty_tpl->tpl_vars['brother_order']->value->id),'html','UTF-8' ));?>

                				</a>
                			<?php
$_smarty_tpl->tpl_vars['brother_order'] = $__foreach_brother_order_8_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                		</p>
                	<?php }?>
                	<div class="table-responsive">
                		<table class="table">
                			<thead>
                				<tr>
                					<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
                					<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment method','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
                					<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Transaction ID','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
                					<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
                					<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
                					<th></th>
                				</tr>
                			</thead>
                			<tbody>
                				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getOrderPaymentCollection(), 'payment');
$_smarty_tpl->tpl_vars['payment']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['payment']->value) {
$_smarty_tpl->tpl_vars['payment']->do_else = false;
?>
                				<tr>
                					<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['payment']->value->date_add,'full'=>true),$_smarty_tpl ) );?>
</td>
                					<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->payment_method,'html','UTF-8' ));?>
</td>
                					<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->transaction_id,'html','UTF-8' ));?>
</td>
                					<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['payment']->value->amount,'currency'=>$_smarty_tpl->tpl_vars['payment']->value->id_currency),$_smarty_tpl ) );?>
</td>
                					<td>
                					<?php $_prefixVariable2 = $_smarty_tpl->tpl_vars['payment']->value->getOrderInvoice($_smarty_tpl->tpl_vars['order']->value->id);
$_smarty_tpl->_assignInScope('invoice', $_prefixVariable2);
if ($_prefixVariable2) {?>
                						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->getInvoiceNumberFormatted($_smarty_tpl->tpl_vars['current_id_lang']->value,$_smarty_tpl->tpl_vars['order']->value->id_shop),'html','UTF-8' ));?>

                					<?php } else { ?>
                					<?php }?>
                					</td>
                					<td class="actions">
                						<button class="btn btn-default open_payment_information2"  data-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
">
                							<i class="icon-search"></i>
                							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Details','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                						</button>
                                        <button class="btn btn-default edit_payment_information edit_payment_information_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
"  data-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
">
                							<i class="icon-edit"></i>
                						</button>
                                        <button class="btn btn-default delete_payment_information" data-idorder="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" data-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
">
                							<i class="icon-trash"></i>
                						</button>
                					</td>
                				</tr>
                				<tr class="payment_information" id="payment_information_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
" style="display: none;">
                					<td colspan="6">
                						<p>
                							<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Card Number','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b>&nbsp;
                							<?php if ($_smarty_tpl->tpl_vars['payment']->value->card_number) {?>
                								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->card_number,'html','UTF-8' ));?>

                							<?php } else { ?>
                								<i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Not defined','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</i>
                							<?php }?>
                						</p>
                						<p>
                							<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Card Brand','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b>&nbsp;
                							<?php if ($_smarty_tpl->tpl_vars['payment']->value->card_brand) {?>
                								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->card_brand,'html','UTF-8' ));?>

                							<?php } else { ?>
                								<i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Not defined','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</i>
                							<?php }?>
                						</p>
                						<p>
                							<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Card Expiration','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b>&nbsp;
                							<?php if ($_smarty_tpl->tpl_vars['payment']->value->card_expiration) {?>
                								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->card_expiration,'html','UTF-8' ));?>

                							<?php } else { ?>
                								<i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Not defined','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</i>
                							<?php }?>
                						</p>
                						<p>
                							<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Card Holder','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b>&nbsp;
                							<?php if ($_smarty_tpl->tpl_vars['payment']->value->card_holder) {?>
                								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->card_holder,'html','UTF-8' ));?>

                							<?php } else { ?>
                								<i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Not defined','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</i>
                							<?php }?>
                						</p>
                					</td>
                				</tr>
                                <tr class="edit_payment_information" id="edit_payment_information_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
" style="display: none;">
                                    <td>
                						<div class="input-group fixed-width-l">
                							<input type="text" name="payment_date" class="datetimepicker" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['payment']->value->date_add,'full'=>true),$_smarty_tpl ) );?>
" />
                							<div class="input-group-addon">
                								<i class="icon-calendar-o"></i>
                							</div>
                						</div>
                					</td>
                					<td>
                						<input name="payment_method" list="payment_method" class="payment_method" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->payment_method,'html','UTF-8' ));?>
" />
                						<datalist id="payment_method">
                						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['payment_methods']->value, 'payment_method');
$_smarty_tpl->tpl_vars['payment_method']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['payment_method']->value) {
$_smarty_tpl->tpl_vars['payment_method']->do_else = false;
?>
                							<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment_method']->value,'html','UTF-8' ));?>
">
                						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                						</datalist>
                					</td>
                					<td>
                						<input type="text" name="payment_transaction_id" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->transaction_id,'html','UTF-8' ));?>
" class="form-control fixed-width-sm"/>
                					</td>
                					<td>
                						<input type="text" name="payment_amount" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::ps_round(Tools::convertPrice($_smarty_tpl->tpl_vars['payment']->value->amount,$_smarty_tpl->tpl_vars['currency']->value),2),'html','UTF-8' ));?>
" class="form-control fixed-width-sm pull-left" />
                						<select name="payment_currency" class="payment_currency form-control fixed-width-xs pull-left">
                							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['currencies']->value, 'current_currency');
$_smarty_tpl->tpl_vars['current_currency']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['current_currency']->value) {
$_smarty_tpl->tpl_vars['current_currency']->do_else = false;
?>
                								<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_currency']->value['id_currency'],'html','UTF-8' ));?>
"<?php if ($_smarty_tpl->tpl_vars['current_currency']->value['id_currency'] == $_smarty_tpl->tpl_vars['currency']->value->id) {?> selected="selected"<?php }?>><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_currency']->value['sign'],'html','UTF-8' ));?>
</option>
                							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                						</select>
                					</td>
                					<td>
                						<?php if (count($_smarty_tpl->tpl_vars['invoices_collection']->value) > 0) {?>
                							<select name="payment_invoice" id="payment_invoice">
                							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['invoices_collection']->value, 'invoice');
$_smarty_tpl->tpl_vars['invoice']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['invoice']->value) {
$_smarty_tpl->tpl_vars['invoice']->do_else = false;
?>
                								<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->id,'html','UTF-8' ));?>
" selected="selected"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->getInvoiceNumberFormatted($_smarty_tpl->tpl_vars['current_id_lang']->value,$_smarty_tpl->tpl_vars['order']->value->id_shop),'html','UTF-8' ));?>
</option>
                							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                							</select>
                						<?php }?>
                					</td>
                					<td class="actions">
                						<button class="btn btn-primary OrderSubmitEditPayment" type="submit" data-idorder="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
">
                							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                						</button>
                                        <button class="btn btn-default cancel_edit_payment_information" type="submit" rel="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->id,'html','UTF-8' ));?>
">
                							<i class="icon-remove text-danger"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                						</button>
                					</td>
                                </tr>
                				<?php
}
if ($_smarty_tpl->tpl_vars['payment']->do_else) {
?>
                				<tr>
                					<td class="list-empty hidden-print" colspan="6">
                						<div class="list-empty-msg">
                							<i class="icon-warning-sign list-empty-icon"></i>
                							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No payment methods are available','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                						</div>
                					</td>
                				</tr>
                				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                				<tr class="current-edit hidden-print">
                					<td>
                						<div class="input-group fixed-width-l">
                							<input type="text" name="payment_date" class="datetimepicker" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( date('Y-m-d'),'html','UTF-8' ));?>
" />
                							<div class="input-group-addon">
                								<i class="icon-calendar-o"></i>
                							</div>
                						</div>
                					</td>
                					<td>
                						<input name="payment_method" list="payment_method" class="payment_method" />
                						<datalist id="payment_method">
                						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['payment_methods']->value, 'payment_method');
$_smarty_tpl->tpl_vars['payment_method']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['payment_method']->value) {
$_smarty_tpl->tpl_vars['payment_method']->do_else = false;
?>
                							<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment_method']->value,'html','UTF-8' ));?>
">
                						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                						</datalist>
                					</td>
                					<td>
                						<input type="text" name="payment_transaction_id" value="" class="form-control fixed-width-sm"/>
                					</td>
                					<td>
                						<input type="text" name="payment_amount" value="" class="form-control fixed-width-sm pull-left" />
                						<select name="payment_currency" class="payment_currency form-control fixed-width-xs pull-left">
                							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['currencies']->value, 'current_currency');
$_smarty_tpl->tpl_vars['current_currency']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['current_currency']->value) {
$_smarty_tpl->tpl_vars['current_currency']->do_else = false;
?>
                								<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_currency']->value['id_currency'],'html','UTF-8' ));?>
"<?php if ($_smarty_tpl->tpl_vars['current_currency']->value['id_currency'] == $_smarty_tpl->tpl_vars['currency']->value->id) {?> selected="selected"<?php }?>><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_currency']->value['sign'],'html','UTF-8' ));?>
</option>
                							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                						</select>
                					</td>
                					<td>
                						<?php if (count($_smarty_tpl->tpl_vars['invoices_collection']->value) > 0) {?>
                							<select name="payment_invoice" id="payment_invoice">
                							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['invoices_collection']->value, 'invoice');
$_smarty_tpl->tpl_vars['invoice']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['invoice']->value) {
$_smarty_tpl->tpl_vars['invoice']->do_else = false;
?>
                								<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->id,'html','UTF-8' ));?>
" selected="selected"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['invoice']->value->getInvoiceNumberFormatted($_smarty_tpl->tpl_vars['current_id_lang']->value,$_smarty_tpl->tpl_vars['order']->value->id_shop),'html','UTF-8' ));?>
</option>
                							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                							</select>
                						<?php }?>
                					</td>
                					<td class="actions">
                						<button class="btn btn-primary OrderSubmitAddPayment" type="submit" data-idorder="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
">
                							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

                						</button>
                					</td>
                				</tr>
                			</tbody>
                		</table>
                	</div>
                </div>
            	<?php if ((!$_smarty_tpl->tpl_vars['order']->value->valid && sizeof($_smarty_tpl->tpl_vars['currencies']->value) > 1)) {?>
            		<form class="form-horizontal well" method="post" action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currentIndex']->value,'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
">
            			<div class="row">
            				<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Change currency','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
            				<div class="col-lg-6">
            					<select name="new_currency">
            					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['currencies']->value, 'currency_change');
$_smarty_tpl->tpl_vars['currency_change']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['currency_change']->value) {
$_smarty_tpl->tpl_vars['currency_change']->do_else = false;
?>
            						<?php if ($_smarty_tpl->tpl_vars['currency_change']->value['id_currency'] != $_smarty_tpl->tpl_vars['order']->value->id_currency) {?>
            						<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency_change']->value['id_currency'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency_change']->value['name'],'html','UTF-8' ));?>
 - <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency_change']->value['sign'],'html','UTF-8' ));?>
</option>
            						<?php }?>
            					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            					</select>
            					<p class="help-block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Do not forget to update your exchange rate before making this change.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
            				</div>
            				<div class="col-lg-3">
            					<button type="submit" class="btn btn-default" name="submitChangeCurrency"><i class="icon-refresh"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Change','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
            				</div>
            			</div>
            		</form>
            	<?php }?>
            </div>
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayAdminOrderLeft",'id_order'=>$_smarty_tpl->tpl_vars['order']->value->id),$_smarty_tpl ) );?>

		</div>
		<div class="col-lg-5">
			<!-- Customer informations -->
			<div class="panel">
				<?php if ($_smarty_tpl->tpl_vars['customer']->value->id) {?>
					<div class="panel-heading">
						<i class="icon-user"></i>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						<span class="badge">
							<a href="?tab=AdminCustomers&amp;id_customer=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->id,'html','UTF-8' ));?>
&amp;viewcustomer&amp;token=<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getAdminToken'][0], array( array('tab'=>'AdminCustomers'),$_smarty_tpl ) );?>
">
								<?php if (Configuration::get('PS_B2B_ENABLE')) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->company,'html','UTF-8' ));?>
 - <?php }?>
								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gender']->value->name,'html','UTF-8' ));?>

								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->firstname,'html','UTF-8' ));?>

								<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->lastname,'html','UTF-8' ));?>

							</a>
						</span>
						<span class="badge">
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'#','mod'=>'g_ordermanager'),$_smarty_tpl ) );
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->id,'html','UTF-8' ));?>

						</span>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<?php if (($_smarty_tpl->tpl_vars['customer']->value->isGuest())) {?>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This order has been placed by a guest.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								<?php if ((!Customer::customerExists($_smarty_tpl->tpl_vars['customer']->value->email))) {?>
									<form method="post" action="index.php?tab=AdminCustomers&amp;id_customer=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->id,'html','UTF-8' ));?>
&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
&amp;token=<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getAdminToken'][0], array( array('tab'=>'AdminCustomers'),$_smarty_tpl ) );?>
">
										<input type="hidden" name="id_lang" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id_lang,'html','UTF-8' ));?>
" />
										<input class="btn btn-default" type="submit" name="submitGuestToCustomer" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Transform a guest into a customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" />
										<p class="help-block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This feature will generate a random password and send an email to the customer.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
									</form>
								<?php } else { ?>
									<div class="alert alert-warning">
										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'A registered customer account has already claimed this email address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

									</div>
								<?php }?>
							<?php } else { ?>
								<dl class="well list-detail">
									<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
										<dd><a href="mailto:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->email,'html','UTF-8' ));?>
"><i class="icon-envelope-o"></i> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->email,'html','UTF-8' ));?>
</a></dd>
									<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Account registered','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
										<dd class="text-muted"><i class="icon-calendar-o"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['customer']->value->date_add,'full'=>true),$_smarty_tpl ) );?>
</dd>
									<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Valid orders placed','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
										<dd><span class="badge"><?php echo intval($_smarty_tpl->tpl_vars['customerStats']->value['nb_orders']);?>
</span></dd>
									<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total spent since registration','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
										<dd><span class="badge badge-success"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round(Tools::convertPrice($_smarty_tpl->tpl_vars['customerStats']->value['total_orders'],$_smarty_tpl->tpl_vars['currency']->value),2),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</span></dd>
									<?php if (Configuration::get('PS_B2B_ENABLE')) {?>
										<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Siret','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
											<dd><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->siret,'html','UTF-8' ));?>
</dd>
										<dt><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'APE','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</dt>
											<dd><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->ape,'html','UTF-8' ));?>
</dd>
									<?php }?>
								</dl>
							<?php }?>
						</div>

						<div class="col-xs-6">
							<div class="form-group hidden-print">
								<a href="?tab=AdminCustomers&amp;id_customer=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->id,'html','UTF-8' ));?>
&amp;viewcustomer&amp;token=<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getAdminToken'][0], array( array('tab'=>'AdminCustomers'),$_smarty_tpl ) );?>
" class="btn btn-default btn-block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View full details...','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</a>
							</div>
							<div class="panel panel-sm">
								<div class="panel-heading">
									<i class="icon-eye-slash"></i>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Private note','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</div>
								<form id="customer_note" class="form-horizontal" action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;viewOrder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));
if ((isset($_GET['token']))) {?>&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));
}?>" method="post" onsubmit="saveCustomerNote(<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->id,'html','UTF-8' ));?>
);return false;" >
									<div class="form-group">
										<div class="col-lg-12">
											<textarea name="note" id="noteContent" class="textarea-autosize" onkeyup="$(this).val().length > 0 ? $('#submitCustomerNote').removeAttr('disabled') : $('#submitCustomerNote').attr('disabled', 'disabled')"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->note,'html','UTF-8' ));?>
</textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<button type="button" id="submitCustomerNote" class="btn btn-default pull-right" disabled="disabled">
												<i class="icon-save"></i>
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

											</button>
										</div>
									</div>
									<span id="note_feedback"></span>
								</form>
							</div>
						</div>
					</div>
				<?php }?>
				<!-- Tab nav -->
				<div class="row">
					<ul class="nav nav-tabs" id="tabAddresses">
						<li class="active">
							<a href="#addressShipping">
								<i class="icon-truck"></i>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</a>
						</li>
						<li>
							<a href="#addressInvoice">
								<i class="icon-file-text"></i>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</a>
						</li>
					</ul>
					<!-- Tab content -->
					<div class="tab-content panel">
						<!-- Tab status -->
						<div class="tab-pane  in active" id="addressShipping">
							<!-- Addresses -->
							<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
							<?php if (!$_smarty_tpl->tpl_vars['order']->value->isVirtual()) {?>
							<!-- Shipping address -->
								<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
                                <div class="form-horizontal hidden-print">
                                        <div class="form-group">
											<div class="col-lg-9">
                                                <select name="id_address" id="shipping_id_address_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customer_addresses']->value, 'address');
$_smarty_tpl->tpl_vars['address']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['address']->value) {
$_smarty_tpl->tpl_vars['address']->do_else = false;
?>
													<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['id_address'],'html','UTF-8' ));?>
"
														<?php if ($_smarty_tpl->tpl_vars['address']->value['id_address'] == $_smarty_tpl->tpl_vars['order']->value->id_address_delivery) {?>
															selected="selected"
														<?php }?>>
														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['alias'],'html','UTF-8' ));?>
 -
														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['address1'],'html','UTF-8' ));?>

														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['postcode'],'html','UTF-8' ));?>

														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['city'],'html','UTF-8' ));?>

														<?php if (!empty($_smarty_tpl->tpl_vars['address']->value['state'])) {?>
															<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['state'],'html','UTF-8' ));?>

														<?php }?>,
														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['country'],'html','UTF-8' ));?>

													</option>
													<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</select>
											</div>
											<div class="col-lg-3">
                                                <button class="btn btn-default submitAddressShipping" data-type="shipping" data-id_order="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
"><i class="icon-refresh"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Change','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
                                            </div>
										</div>
                                    </div>
								<?php }?>
								<div class="well">
									<div class="row">
										<div class="col-sm-6">
											<a  id="edit_delivery_address_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
"  class="btn btn-default pull-right ifancybox" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminAddresses'),'html','UTF-8' ));?>
&id_address=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['delivery']->id,'html','UTF-8' ));?>
&id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
&address_type=1&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back=<?php echo urlencode($_smarty_tpl->tpl_vars['adminaddresseslink']->value);?>
#">
												<i class="icon-pencil"></i>
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

											</a>
                                            <div id="shipping_address_text_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
    											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayAddressDetail'][0], array( array('address'=>$_smarty_tpl->tpl_vars['addresses']->value['delivery'],'newLine'=>'<br />'),$_smarty_tpl ) );?>

    											<?php if ($_smarty_tpl->tpl_vars['addresses']->value['delivery']->other) {?>
    												<hr /><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['delivery']->other,'html','UTF-8' ));?>
<br />
    											<?php }?>
                                            </div>
										</div>
										<div class="col-sm-6 hidden-print">
											<div id="map-delivery-canvas-<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" style="height: 190px"></div>
										</div>
									</div>
								</div>
							<?php }?>
						</div>
						<div class="tab-pane " id="addressInvoice">
							<!-- Invoice address -->
							<h4 class="visible-print"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
							<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
                                <div class="form-horizontal hidden-print">
                                	<div class="form-group">
										<div class="col-lg-9">
                                            <select name="id_address" id="invoice_id_address_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customer_addresses']->value, 'address');
$_smarty_tpl->tpl_vars['address']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['address']->value) {
$_smarty_tpl->tpl_vars['address']->do_else = false;
?>
												<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['id_address'],'html','UTF-8' ));?>
"
													<?php if ($_smarty_tpl->tpl_vars['address']->value['id_address'] == $_smarty_tpl->tpl_vars['order']->value->id_address_invoice) {?>
													selected="selected"
													<?php }?>>
													<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['alias'],'html','UTF-8' ));?>
 -
													<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['address1'],'html','UTF-8' ));?>

													<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['postcode'],'html','UTF-8' ));?>

													<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['city'],'html','UTF-8' ));?>

													<?php if (!empty($_smarty_tpl->tpl_vars['address']->value['state'])) {?>
														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['state'],'html','UTF-8' ));?>

													<?php }?>,
													<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['address']->value['country'],'html','UTF-8' ));?>

												</option>
												<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</select>
										</div>
										<div class="col-lg-3">
										    <button class="btn btn-default submitAddressShipping" data-type="invoice"  data-id_order="<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
"  ><i class="icon-refresh"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Change','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
                                        </div>
									</div>
                                </div>
							<?php }?>
							<div class="well">
								<div class="row">
									<div class="col-sm-6">
										<a id="edit_invoice_address_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" class="btn btn-default pull-right ifancybox" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminAddresses'),'html','UTF-8' ));?>
&id_address=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['invoice']->id,'html','UTF-8' ));?>
&id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
&address_type=2&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back=<?php echo urlencode($_smarty_tpl->tpl_vars['adminaddresseslink']->value);?>
#">
											<i class="icon-pencil"></i>
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										</a>
                                        <div id="invoice_address_text_<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
    										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayAddressDetail'][0], array( array('address'=>$_smarty_tpl->tpl_vars['addresses']->value['invoice'],'newLine'=>'<br />'),$_smarty_tpl ) );?>

    										<?php if ($_smarty_tpl->tpl_vars['addresses']->value['invoice']->other) {?>
    											<hr /><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['invoice']->other,'html','UTF-8' ));?>
<br />
    										<?php }?>
                                        </div>
									</div>
									<div class="col-sm-6 hidden-print">
										<div id="map-invoice-canvas-<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" style="height: 190px"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo '<script'; ?>
>
					$('#tabAddresses a').click(function (e) {
						e.preventDefault()
						$(this).tab('show')
					})
				<?php echo '</script'; ?>
>
			</div>
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-envelope"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Messages','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sizeof($_smarty_tpl->tpl_vars['customer_thread_message']->value),'html','UTF-8' ));?>
</span>
				</div>
				<?php if ((sizeof($_smarty_tpl->tpl_vars['messages']->value))) {?>
					<div class="panel panel-highlighted">
						<div class="message-item">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['messages']->value, 'message');
$_smarty_tpl->tpl_vars['message']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['message']->value) {
$_smarty_tpl->tpl_vars['message']->do_else = false;
?>
								<div class="message-avatar">
									<div class="avatar-md">
										<i class="icon-user icon-2x"></i>
									</div>
								</div>
								<div class="message-body">

									<span class="message-date">&nbsp;<i class="icon-calendar"></i>
										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['message']->value['date_add']),$_smarty_tpl ) );?>
 -
									</span>
									<h4 class="message-item-heading">
										<?php if ((call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['elastname'],'html','UTF-8' )))) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['efirstname'],'html','UTF-8' ));?>

											<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['elastname'],'html','UTF-8' ));
} else {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['cfirstname'],'html','UTF-8' ));?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['clastname'],'html','UTF-8' ));?>

										<?php }?>
										<?php if (($_smarty_tpl->tpl_vars['message']->value['private'] == 1)) {?>
											<span class="badge badge-info"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Private','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
										<?php }?>
									</h4>
									<p class="message-item-text">
										<?php echo nl2br(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['message']->value['message'],'html','UTF-8' )));?>

									</p>
								</div>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					</div>
				<?php }?>
				<div id="messages" class="well hidden-print">
					<form action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_SERVER['REQUEST_URI'],'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
" method="post" onsubmit="if (getE('visibility').checked == true) return confirm('<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Do you want to send this message to the customer?','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
');">
						<div id="message" class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Choose a standard message','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
								<div class="col-lg-9">
									<select class="chosen form-control" name="order_message" id="order_message" onchange="orderOverwriteMessage(this, '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Do you want to overwrite your existing message?','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
')">
										<option value="0" selected="selected">-</option>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orderMessages']->value, 'orderMessage');
$_smarty_tpl->tpl_vars['orderMessage']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['orderMessage']->value) {
$_smarty_tpl->tpl_vars['orderMessage']->do_else = false;
?>
										<option value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderMessage']->value['message'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['orderMessage']->value['name'],'html','UTF-8' ));?>
</option>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
									<p class="help-block">
										<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminOrderMessage'),'html','UTF-8' ));?>
">
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configure predefined messages','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

											<i class="icon-external-link"></i>
										</a>
									</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Display to customer?','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
								<div class="col-lg-9">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="visibility" id="visibility_on" value="0" />
										<label for="visibility_on">
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Yes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										</label>
										<input type="radio" name="visibility" id="visibility_off" value="1" checked="checked" />
										<label for="visibility_off">
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Message','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
								<div class="col-lg-9">
									<textarea id="txt_msg" class="textarea-autosize" name="message"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::getValue('message'),'html','UTF-8' ));?>
</textarea>
									<p id="nbchars"></p>
								</div>
							</div>


							<input type="hidden" name="id_order" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" />
							<input type="hidden" name="id_customer" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id_customer,'html','UTF-8' ));?>
" />
							<button type="submit" id="submitMessage2" class="btn btn-primary pull-right" name="submitMessage">
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Send message','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</button>
							<a class="btn btn-default" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminCustomerThreads'),'html','UTF-8' ));?>
&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
">
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Show all messages','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								<i class="icon-external-link"></i>
							</a>
						</div>
					</form>
				</div>
			</div>
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayAdminOrderRight",'id_order'=>$_smarty_tpl->tpl_vars['order']->value->id),$_smarty_tpl ) );?>

		</div>
	</div>
	<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayAdminOrder",'id_order'=>$_smarty_tpl->tpl_vars['order']->value->id),$_smarty_tpl ) );?>

	<div class="row" id="start_products">
		<div class="col-lg-12">
			<form class="container-command-top-spacing" action="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;vieworder&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
&amp;id_order=<?php echo intval($_smarty_tpl->tpl_vars['order']->value->id);?>
" method="post" onsubmit="return orderDeleteProduct('<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This product cannot be returned.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
', '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quantity to cancel is greater than quantity available.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
');">
				<input type="hidden" name="id_order" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
" />
				<div style="display: none">
					<input type="hidden" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( implode($_smarty_tpl->tpl_vars['order']->value->getWarehouseList()),'html','UTF-8' ));?>
" id="warehouse_list" />
				</div>

				<div class="panel">
					<div class="panel-heading">
						<i class="icon-shopping-cart"></i>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['products']->value),'html','UTF-8' ));?>
</span>
					</div>
					<div id="refundForm">
					<!--
						<a href="#" class="standard_refund"><img src="../img/admin/add.gif" alt="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Process a standard refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" /> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Process a standard refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</a>
						<a href="#" class="partial_refund"><img src="../img/admin/add.gif" alt="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Process a partial refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
" /> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Process a partial refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</a>
					-->
					</div>

					<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, "TaxMethod", null, null);?>
						<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax excluded.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						<?php } else { ?>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'tax included.','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						<?php }?>
					<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
					<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
						<input type="hidden" name="TaxMethod" value="0">
					<?php } else { ?>
						<input type="hidden" name="TaxMethod" value="1">
					<?php }?>
					<div class="table-responsive">
						<table class="table" id="orderProducts">
							<thead>
								<tr>
									<th></th>
									<th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
									<th>
										<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unit Price','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
										<small class="text-muted"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod'),'html','UTF-8' ));?>
</small>
									</th>
									<th class="text-center"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qty','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
									<?php if ($_smarty_tpl->tpl_vars['display_warehouse']->value) {?><th><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Warehouse','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th><?php }?>
									<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid())) {?><th class="text-center"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Refunded','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th><?php }?>
									<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || $_smarty_tpl->tpl_vars['order']->value->hasProductReturned())) {?>
										<th class="text-center"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Returned','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
									<?php }?>
									<?php if ((isset($_smarty_tpl->tpl_vars['stock_management']->value)) && $_smarty_tpl->tpl_vars['stock_management']->value) {?><th class="text-center"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Available quantity','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th><?php }?>
									<th>
										<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
										<small class="text-muted"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod'),'html','UTF-8' ));?>
</small>
									</th>
									<th style="display: none;" class="add_product_fields"></th>
									<th style="display: none;" class="edit_product_fields"></th>
									<th style="display: none;" class="standard_refund_fields">
										<i class="icon-minus-sign"></i>
										<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || $_smarty_tpl->tpl_vars['order']->value->hasBeenShipped())) {?>
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Return','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										<?php } elseif (($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid())) {?>
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										<?php } else { ?>
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

										<?php }?>
									</th>
									<th style="display:none" class="partial_refund_fields">
										<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Partial refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
									</th>
									<?php if (!$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered()) {?>
									<th></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', false, 'k');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
																<?php $_smarty_tpl->_subTemplateRender('file:../../_customized_data.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
																<?php $_smarty_tpl->_subTemplateRender('file:../../_product_line.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
								<?php $_smarty_tpl->_subTemplateRender('file:../../_new_product.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
							<?php }?>
							</tbody>
						</table>
					</div>

					<?php if ($_smarty_tpl->tpl_vars['can_edit']->value) {?>
					<div class="row-margin-bottom row-margin-top order_action">
						<button type="button" id="add_product" class="btn btn-default">
							<i class="icon-plus-sign"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add a product','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</button>
						<button id="add_voucher" class="btn btn-default" type="button" >
							<i class="icon-ticket"></i>
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add a new discount','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</button>
					</div>
					<?php }?>
					<div class="clear">&nbsp;</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="alert alert-warning">
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'For this customer group, prices are displayed as: [1]%s[/1]','sprintf'=>array($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod')),'tags'=>array('<strong>'),'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								<?php if (!Configuration::get('PS_ORDER_RETURN')) {?>
									<br/><strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Merchandise returns are disabled','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</strong>
								<?php }?>
							</div>
                            <div class="current-edit" id="voucher_form" style="display:none;">
                                <?php $_smarty_tpl->_subTemplateRender('file:../../_discount_form.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
							</div>
                            <div id="order-note" class="panel">
                                <h1 class="panel-heading"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order Notes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h1>
                                <div class="order-note-history">
                                    <div class="panel panel-highlighted">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Notes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                                                    <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Show in pdf','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                                                    <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date add','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if ((isset($_smarty_tpl->tpl_vars['order_notes']->value)) && $_smarty_tpl->tpl_vars['order_notes']->value) {?>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order_notes']->value, 'order_note');
$_smarty_tpl->tpl_vars['order_note']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['order_note']->value) {
$_smarty_tpl->tpl_vars['order_note']->do_else = false;
?>
                                                    <tr id="note_<?php echo intval($_smarty_tpl->tpl_vars['order_note']->value['id_gordernotes']);?>
">
                                                        <td  class="noteval"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order_note']->value['notes'],'html','UTF-8' ));?>
</td>
                                                        <td><input type="checkbox" name="showinpdf[<?php echo intval($_smarty_tpl->tpl_vars['order_note']->value['id_gordernotes']);?>
]" <?php if ($_smarty_tpl->tpl_vars['order_note']->value['showinpdf']) {?> checked="checked" <?php }?> value="1" /></td>
                                                        <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order_note']->value['date_add'],'html','UTF-8' ));?>
</td>
                                                        <td>
                                                            <button class="editordernote btn btn-default" rel="<?php echo intval($_smarty_tpl->tpl_vars['order_note']->value['id_gordernotes']);?>
"><i class="icon-pencil"></i></button>
                                                            <button class="deleteordernote btn btn-default" rel="<?php echo intval($_smarty_tpl->tpl_vars['order_note']->value['id_gordernotes']);?>
"><i class="icon-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                <div class="order-note-form  well hidden-print">
                                    <p class="form-group">
                                        <label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Note','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
                                        <input type="hidden" value="" id="id_gordernotes" value="" autocomplete="off" />
                                        <div class="col-lg-9">
                                            <textarea name="order_note" class="textarea-autosize" id="order_note"></textarea>
                                        </div>
                                    </p>
                                    <div class="form-group">
        								<label class="control-label col-lg-3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Show in pdf?','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</label>
        								<div class="col-lg-9">
        									<span class="switch prestashop-switch fixed-width-lg">
        										<input type="radio" name="ordernotepdf" id="ordernotepdf_on" value="1" />
        										<label for="ordernotepdf_on">
        											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Yes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

        										</label>
        										<input type="radio" name="ordernotepdf" id="ordernotepdf_off" value="0" checked="checked" />
        										<label for="ordernotepdf_off">
        											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

        										</label>
        										<a class="slide-button btn"></a>
        									</span>
        								</div>
        							</div>
                                    <button type="button" id="submitOrderNote" class="btn btn-primary pull-right" name="submitOrderNote">
        								<span class="add_label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
                                        <span class="edit_label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        							</button>
                                    <button type="button" class="btn btn-default" id="cancel_editnote"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</button>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
						</div>
						<div class="col-xs-6">
                            <div id="panel-vouchers-wp">
                                <?php $_smarty_tpl->_subTemplateRender('file:../../_discount_val.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                            </div>
							<div class="panel panel-total">
								<div class="table-responsive">
									<table class="table">
																				<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
											<?php $_smarty_tpl->_assignInScope('order_product_price', ($_smarty_tpl->tpl_vars['order']->value->total_products));?>
											<?php $_smarty_tpl->_assignInScope('order_discount_price', $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_excl);?>
											<?php $_smarty_tpl->_assignInScope('order_wrapping_price', $_smarty_tpl->tpl_vars['order']->value->total_wrapping_tax_excl);?>
											<?php $_smarty_tpl->_assignInScope('order_shipping_price', $_smarty_tpl->tpl_vars['order']->value->total_shipping_tax_excl);?>
										<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('order_product_price', $_smarty_tpl->tpl_vars['order']->value->total_products_wt);?>
											<?php $_smarty_tpl->_assignInScope('order_discount_price', $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl);?>
											<?php $_smarty_tpl->_assignInScope('order_wrapping_price', $_smarty_tpl->tpl_vars['order']->value->total_wrapping_tax_incl);?>
											<?php $_smarty_tpl->_assignInScope('order_shipping_price', $_smarty_tpl->tpl_vars['order']->value->total_shipping_tax_incl);?>
										<?php }?>
										<tr id="total_products">
											<td class="text-right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
											<td class="amount text-right nowrap">
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_product_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

											</td>
											<td class="partial_refund_fields current-edit" style="display:none;"></td>
										</tr>
										<tr id="total_discounts" <?php if ($_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl == 0) {?>style="display: none;"<?php }?>>
											<td class="text-right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discounts','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
											<td class="amount text-right nowrap">
												-<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_discount_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

											</td>
											<td class="partial_refund_fields current-edit" style="display:none;"></td>
										</tr>
										<tr id="total_wrapping" <?php if ($_smarty_tpl->tpl_vars['order']->value->total_wrapping_tax_incl == 0) {?>style="display: none;"<?php }?>>
											<td class="text-right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wrapping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
											<td class="amount text-right nowrap">
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_wrapping_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

											</td>
											<td class="partial_refund_fields current-edit" style="display:none;"></td>
										</tr>
										<tr id="total_shipping">
											<td class="text-right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
											<td class="amount text-right nowrap" >
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_shipping_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

											</td>
											<td class="partial_refund_fields current-edit" style="display:none;">
												<div class="input-group">
													<div class="input-group-addon">
														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->prefix,'html','UTF-8' ));?>

														<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->suffix,'html','UTF-8' ));?>

													</div>
													<input type="text" name="partialRefundShippingCost" value="0" />
												</div>
												<p class="help-block"><i class="icon-warning-sign"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'(%s)','sprintf'=>$_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'TaxMethod'),'mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
											</td>
										</tr>
										<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
			 							<tr id="total_taxes">
			 								<td class="text-right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Taxes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
			 								<td class="amount text-right nowrap" ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>($_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl-$_smarty_tpl->tpl_vars['order']->value->total_paid_tax_excl),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</td>
			 								<td class="partial_refund_fields current-edit" style="display:none;"></td>
			 							</tr>
			 							<?php }?>
										<?php $_smarty_tpl->_assignInScope('order_total_price', $_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl);?>
										<tr id="total_order">
											<td class="text-right"><strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</strong></td>
											<td class="amount text-right nowrap">
												<strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_total_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</strong>
											</td>
											<td class="partial_refund_fields current-edit" style="display:none;"></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div style="display: none;" class="standard_refund_fields form-horizontal panel">
						<div class="form-group">
							<?php if (($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN'))) {?>
							<p class="checkbox">
								<label for="reinjectQuantities">
									<input type="checkbox" id="reinjectQuantities" name="reinjectQuantities" />
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Re-stock products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<?php }?>
							<?php if (((!$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() && $_smarty_tpl->tpl_vars['order']->value->hasBeenPaid()) || ($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))) {?>
							<p class="checkbox">
								<label for="generateCreditSlip">
									<input type="checkbox" id="generateCreditSlip" name="generateCreditSlip" onclick="toggleShippingCost()" />
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate a credit slip','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<p class="checkbox">
								<label for="generateDiscount">
									<input type="checkbox" id="generateDiscount" name="generateDiscount" onclick="toggleShippingCost()" />
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate a voucher','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<p class="checkbox" id="spanShippingBack" style="display:none;">
								<label for="shippingBack">
									<input type="checkbox" id="shippingBack" name="shippingBack" />
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Repay shipping costs','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<?php if ($_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_excl > 0 || $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl > 0) {?>
							<br/><p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This order has been partially paid by voucher. Choose the amount you want to refund:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
							<p class="radio">
								<label id="lab_refund_total_1" for="refund_total_1">
									<input type="radio" value="0" name="refund_total_voucher_off" id="refund_total_1" checked="checked" />
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Include amount of initial voucher: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<p class="radio">
								<label id="lab_refund_total_2" for="refund_total_2">
									<input type="radio" value="1" name="refund_total_voucher_off" id="refund_total_2"/>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Exclude amount of initial voucher: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</label>
							</p>
							<div class="nowrap radio-inline">
								<label id="lab_refund_total_3" class="pull-left" for="refund_total_3">
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount of your choice: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

									<input type="radio" value="2" name="refund_total_voucher_off" id="refund_total_3"/>
								</label>
								<div class="input-group col-lg-1 pull-left">
									<div class="input-group-addon">
										<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->prefix,'html','UTF-8' ));?>

										<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->suffix,'html','UTF-8' ));?>

									</div>
									<input type="text" class="input fixed-width-md" name="refund_total_voucher_choose" value="0"/>
								</div>
							</div>
							<?php }?>
						<?php }?>
						</div>
						<?php if ((!$_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() || ($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))) {?>
						<div class="row">
							<input type="submit" name="cancelProduct" value="<?php if ($_smarty_tpl->tpl_vars['order']->value->hasBeenDelivered()) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Return products','mod'=>'g_ordermanager'),$_smarty_tpl ) );
} elseif ($_smarty_tpl->tpl_vars['order']->value->hasBeenPaid()) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Refund products','mod'=>'g_ordermanager'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel products','mod'=>'g_ordermanager'),$_smarty_tpl ) );
}?>" class="btn btn-default" />
						</div>
						<?php }?>
					</div>
					<div style="display:none;" class="partial_refund_fields">
						<p class="checkbox">
							<label for="reinjectQuantitiesRefund">
								<input type="checkbox" id="reinjectQuantitiesRefund" name="reinjectQuantities" />
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Re-stock products','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</label>
						</p>
						<p class="checkbox">
							<label for="generateDiscountRefund">
								<input type="checkbox" id="generateDiscountRefund" name="generateDiscountRefund" onclick="toggleShippingCost()" />
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Generate a voucher','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</label>
						</p>
						<?php if ($_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_excl > 0 || $_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl > 0) {?>
						<p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This order has been partially paid by voucher. Choose the amount you want to refund:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</p>
						<p class="radio">
							<label id="lab_refund_1" for="refund_1">
								<input type="radio" value="0" name="refund_voucher_off" id="refund_1" checked="checked" />
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product(s) price: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</label>
						</p>
						<p class="radio">
							<label id="lab_refund_2" for="refund_2">
								<input type="radio" value="1" name="refund_voucher_off" id="refund_2"/>
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product(s) price, excluding amount of initial voucher: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

							</label>
						</p>
						<div class="nowrap radio-inline">
								<label id="lab_refund_3" class="pull-left" for="refund_3">
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount of your choice: ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

									<input type="radio" value="2" name="refund_voucher_off" id="refund_3"/>
								</label>
								<div class="input-group col-lg-1 pull-left">
									<div class="input-group-addon">
										<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->prefix,'html','UTF-8' ));?>

										<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value->suffix,'html','UTF-8' ));?>

									</div>
									<input type="text" class="input fixed-width-md" name="refund_voucher_choose" value="0"/>
								</div>
							</div>
						<?php }?>
						<br/>
						<button type="submit" name="partialRefund" class="btn btn-default">
							<i class="icon-check"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Partial refund','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<!-- Sources block -->
			<?php if ((sizeof($_smarty_tpl->tpl_vars['sources']->value))) {?>
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-globe"></i>
					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sources','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <span class="badge"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( count($_smarty_tpl->tpl_vars['sources']->value),'html','UTF-8' ));?>
</span>
				</div>
				<ul <?php if (sizeof($_smarty_tpl->tpl_vars['sources']->value) > 3) {?>style="height: 200px; overflow-y: scroll;"<?php }?>>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sources']->value, 'source');
$_smarty_tpl->tpl_vars['source']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['source']->value) {
$_smarty_tpl->tpl_vars['source']->do_else = false;
?>
					<li>
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['source']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
<br />
						<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'From','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b><?php if ($_smarty_tpl->tpl_vars['source']->value['http_referer'] != '') {?><a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['source']->value['http_referer'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( smarty_modifier_regex_replace(parse_url($_smarty_tpl->tpl_vars['source']->value['http_referer'],(defined('PHP_URL_HOST') ? constant('PHP_URL_HOST') : null)),'/^www./',''),'html','UTF-8' ));?>
</a><?php } else { ?>-<?php }?><br />
						<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'To','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b> <a href="http://<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['source']->value['request_uri'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['source']->value['request_uri'],100,'...' )),'html','UTF-8' ));?>
</a><br />
						<?php if ($_smarty_tpl->tpl_vars['source']->value['keywords']) {?><b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Keywords','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</b> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['source']->value['keywords'],'html','UTF-8' ));?>
<br /><?php }?><br />
					</li>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</ul>
			</div>
			<?php }?>

			<!-- linked orders block -->
			<?php if (count($_smarty_tpl->tpl_vars['order']->value->getBrother()) > 0) {?>
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-cart"></i>
					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Linked orders','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

				</div>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order no. ','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</th>
								<th>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</th>
								<th>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getBrother(), 'brother_order');
$_smarty_tpl->tpl_vars['brother_order']->index = -1;
$_smarty_tpl->tpl_vars['brother_order']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['brother_order']->value) {
$_smarty_tpl->tpl_vars['brother_order']->do_else = false;
$_smarty_tpl->tpl_vars['brother_order']->index++;
$_smarty_tpl->tpl_vars['brother_order']->first = !$_smarty_tpl->tpl_vars['brother_order']->index;
$__foreach_brother_order_24_saved = $_smarty_tpl->tpl_vars['brother_order'];
?>
							<tr>
								<td>
									<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brother_order']->value->id,'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
">#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brother_order']->value->id,'html','UTF-8' ));?>
</a>
								</td>
								<td>
									<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brother_order']->value->getCurrentOrderState()->name[$_smarty_tpl->tpl_vars['current_id_lang']->value],'html','UTF-8' ));?>

								</td>
								<td>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['brother_order']->value->total_paid_tax_incl,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

								</td>
								<td>
									<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_index']->value,'html','UTF-8' ));?>
&amp;vieworder&amp;id_order=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brother_order']->value->id,'html','UTF-8' ));?>
&amp;token=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_GET['token'],'html','UTF-8' ));?>
">
										<i class="icon-eye-open"></i>
										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See the order','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

									</a>
								</td>
							</tr>
							<?php
$_smarty_tpl->tpl_vars['brother_order'] = $__foreach_brother_order_24_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tbody>
					</table>
				</div>
			</div>
			<?php }?>
		</div>
	</div>

	<?php echo '<script'; ?>
 type="text/javascript">
		var geocoder = new google.maps.Geocoder();
		var delivery_map, invoice_map;

		$(document).ready(function()
		{
            //tung update 14/12/2021 note invoice
            $('#submitEditNote').click(function(){
                $.ajax({
                    type:"POST",
                    url: $(this).closest('form').attr('action'),
                    dataType: "json",
                    data : {
                        note:$(this).closest('form').find('.edit-note-value').val(),
                        id_order_invoice: $(this).closest('form').find('.id_order_invoice-note').val(),
                        submitEditNote: '',
                        },
                    success : function(res)
                    {
                        if(res==true) showSuccessMessage('saved');
                        else showSuccessMessage('error');
                    }
                });
            });
            //
            //tung update 14/12/2021 customer note
            
            $('#submitCustomerNote').click(function(){
                $.ajax({
                    type:"POST",
                    url: $(this).closest('form').attr('action'),
                    dataType: "json",
                    data : {
                        noteContent:$(this).closest('form').find('#noteContent').val(),
                        submitCustomerNote: '',
                        },
                    success : function(res)
                    {
                        if(res==true) showSuccessMessage('saved');
                        else showSuccessMessage('error');
                    }
                });
            });

			$(".textarea-autosize").autosize();

			geocoder.geocode({
				address: '<?php echo addcslashes($_smarty_tpl->tpl_vars['addresses']->value['delivery']->address1,'\'');?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['delivery']->postcode,'\''),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['delivery']->city,'\''),'html','UTF-8' ));
if ((isset($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name)) && $_smarty_tpl->tpl_vars['addresses']->value['delivery']->id_state) {?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name,'\''),'html','UTF-8' ));
}?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['delivery']->country,'\''),'html','UTF-8' ));?>
'
				}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK)
				{
					delivery_map = new google.maps.Map(document.getElementById('map-delivery-canvas-<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
'), {
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						center: results[0].geometry.location
					});
					var delivery_marker = new google.maps.Marker({
						map: delivery_map,
						position: results[0].geometry.location,
						url: 'http://maps.google.com?q=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['delivery']->address1),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['delivery']->postcode),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['delivery']->city),'html','UTF-8' ));
if ((isset($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name)) && $_smarty_tpl->tpl_vars['addresses']->value['delivery']->id_state) {?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name),'html','UTF-8' ));
}?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['delivery']->country),'html','UTF-8' ));?>
'
					});
					google.maps.event.addListener(delivery_marker, 'click', function() {
						window.open(delivery_marker.url);
					});
				}
			});

			geocoder.geocode({
				address: '<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['invoice']->address1,'\''),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['invoice']->postcode,'\''),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['invoice']->city,'\''),'html','UTF-8' ));
if ((isset($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name)) && $_smarty_tpl->tpl_vars['addresses']->value['invoice']->id_state) {?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name,'\''),'html','UTF-8' ));
}?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( addcslashes($_smarty_tpl->tpl_vars['addresses']->value['invoice']->country,'\''),'html','UTF-8' ));?>
'
				}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK)
				{
					invoice_map = new google.maps.Map(document.getElementById('map-invoice-canvas-<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
'), {
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						center: results[0].geometry.location
					});
					invoice_marker = new google.maps.Marker({
						map: invoice_map,
						position: results[0].geometry.location,
						url: 'http://maps.google.com?q=<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['invoice']->address1),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['invoice']->postcode),'html','UTF-8' ));?>
,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['invoice']->city),'html','UTF-8' ));
if ((isset($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name)) && $_smarty_tpl->tpl_vars['addresses']->value['invoice']->id_state) {?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['deliveryState']->name),'html','UTF-8' ));
}?>,<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( urlencode($_smarty_tpl->tpl_vars['addresses']->value['invoice']->country),'html','UTF-8' ));?>
'
					});
					google.maps.event.addListener(invoice_marker, 'click', function() {
						window.open(invoice_marker.url);
					});
				}
			});
            
            /*
			$('.datetimepicker').datetimepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',
				// Define a custom regional settings in order to use PrestaShop translation tools
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
            */
		});

		// Fix wrong maps center when map is hidden
		$('#tabAddresses').click(function(){
		    if (typeof delivery_map != 'undefined' && typeof invoice_map != 'undefined')
            {
    			x = delivery_map.getZoom();
    			c = delivery_map.getCenter();
    			google.maps.event.trigger(delivery_map, 'resize');
    			delivery_map.setZoom(x);
    			delivery_map.setCenter(c);
    
    			x = invoice_map.getZoom();
    			c = invoice_map.getCenter();
    			google.maps.event.trigger(invoice_map, 'resize');
    			invoice_map.setZoom(x);
    			invoice_map.setCenter(c);
            }
		});
        function getSummary()
    	{
    		$.ajax({
    			type:"POST",
    			url: admin_ordermanager_tab_link,
    			dataType: "json",
    			data : {
    				ajaxUpdateAddresses: true,
    				id_customer: id_customer,
    				id_order: id_order
    				},
    			success : function(res)
    			{
                        var addresses_delivery_options = '';
                        var addresses_invoice_options = '';
                        var address_invoice_detail = '';
                        var address_delivery_detail = '';
                        var delivery_address_edit_link = '';
                        var invoice_address_edit_link = '';
    			     $.each(res.addresses, function() {
            			if (this.id_address == res.id_address_invoice)
            			{
            				address_invoice_detail = this.formated_address;
            				invoice_address_edit_link = admin_address_link+"&id_address="+this.id_address+"&id_order="+id_order+"&address_type=2&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back="+admin_address_link_back+"#";
                        }
            
            			if(this.id_address == res.id_address_delivery)
            			{
            				address_delivery_detail = this.formated_address;
            				delivery_address_edit_link = admin_address_link+"&id_address="+this.id_address+"&id_order="+id_order+"&address_type=1&addaddress&realedit=1&liteDisplaying=1&submitFormAjax=1&back="+admin_address_link_back+"#";
                        }
            
            			addresses_delivery_options += '<option value="'+this.id_address+'" '+(this.id_address == res.id_address_delivery ? 'selected="selected"' : '')+'>'+this.alias+'</option>';
            			addresses_invoice_options += '<option value="'+this.id_address+'" '+(this.id_address == res.id_address_invoice ? 'selected="selected"' : '')+'>'+this.alias+'</option>';
            		});
                    $('#shipping_id_address_'+id_order).html(addresses_delivery_options);
		            $('#invoice_id_address_'+id_order).html(addresses_invoice_options);
                    $('#shipping_address_text_'+id_order).html(address_delivery_detail);
		            $('#invoice_address_text_'+id_order).html(address_invoice_detail);
                    $('#edit_delivery_address_'+id_order).attr('href', delivery_address_edit_link);
		            $('#edit_invoice_address_'+id_order).attr('href', invoice_address_edit_link);
                    showSuccessMessage('#'+id_order+' : '+data.warning);
    			}
    		});
    	}
	<?php echo '</script'; ?>
>

<?php
}
}
/* {/block "override_tpl"} */
}
