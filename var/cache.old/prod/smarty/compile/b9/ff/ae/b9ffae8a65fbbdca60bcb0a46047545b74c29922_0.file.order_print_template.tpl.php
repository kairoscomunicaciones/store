<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:35:31
  from '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/order_print_template.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a05113770918_38349680',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b9ffae8a65fbbdca60bcb0a46047545b74c29922' => 
    array (
      0 => '/home2/inveriti/public_html/modules/g_ordermanager/views/templates/admin/order_print_template.tpl',
      1 => 1697325096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a05113770918_38349680 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/function.cycle.php','function'=>'smarty_function_cycle',),1=>array('file'=>'/home2/inveriti/public_html/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<style>
    table
    {
        width:100%;
    }
    table.table_border{
        border:1pt solid #ccc;
    }
    th,.total_title{
        background-color:#ccc;
        font-weight:both;
    }
    .even
    {
        background-color:#fdfdfd;
    }
    .odd
    {
        background-color:#f0f0f0;
    }
    .text-right{
        text-align:right;
    }
</style>
<table  style="width: 100%; font-size:9pt;"  cellpadding="4" cellspacing="0">
    <tr>
        <td style=" width: 30%;"><img src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['logo']->value,'html','UTF-8' ));?>
" alt="" /></td>
        <td style=" width: 20%;"></td>
        <td style=" width: 20%;"></td>
        <td style="float: right; width: 30%;background-color:#ccc;">
            <table style="float:right;text-align:right;">
                <tr>
                    <td style="float: right;font-size:15pt;">#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value->id,'html','UTF-8' ));?>
</td>
                </tr>
                <tr>
                    <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['order']->value->date_add,'full'=>false),$_smarty_tpl ) );?>
</td>
                </tr>
                <tr>
                    <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</td>
                </tr>
            </table>
            
        </td>
    </tr>
    <tr>
        <td colspan="2"><h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
                    <tr>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Status','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Employee','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                    </tr>
                </thead>
                <tbody>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['history']->value, 'row', false, 'key');
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
						<?php if (($_smarty_tpl->tpl_vars['key']->value == 0)) {?>
							<tr>
                                <?php $_smarty_tpl->_assignInScope('text_color', 'white');?>
                                <?php if ((isset($_smarty_tpl->tpl_vars['row']->value['text-color']))) {?>
								    <?php $_smarty_tpl->_assignInScope('text_color', $_smarty_tpl->tpl_vars['row']->value['text-color']);?>
                                <?php }?>
                                <td style="text-align:left;background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['ostate_name']);?>
</td>
								<td style="text-align:center;background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php if ($_smarty_tpl->tpl_vars['row']->value['employee_lastname']) {
echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_firstname']);?>
 <?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_lastname']);
}?></td>
								<td style="text-align:right;background-color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['color'],'html','UTF-8' ));?>
;color:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['text_color']->value,'html','UTF-8' ));?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['row']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
</td>
							</tr>
						<?php } else { ?>
							<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
								<td style="text-align:left;"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( stripslashes($_smarty_tpl->tpl_vars['row']->value['ostate_name']),'html','UTF-8' ));?>
</td>
								<td style="text-align:center;"><?php if ($_smarty_tpl->tpl_vars['row']->value['employee_lastname']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_firstname']),'html','UTF-8' ));?>
 <?php echo stripslashes($_smarty_tpl->tpl_vars['row']->value['employee_lastname']);
} else { ?>&nbsp;<?php }?></td>
								<td style="text-align:right;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['row']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
</td>
							</tr>
						<?php }?>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
			</table>
        
        </td>
        <td colspan="2"><h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
    			<thead>
    				<tr>
    					<th class="" ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
    					<th ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment method','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
    					<th ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Transaction ID','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
    					<th ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getOrderPaymentCollection(), 'payment');
$_smarty_tpl->tpl_vars['payment']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['payment']->value) {
$_smarty_tpl->tpl_vars['payment']->do_else = false;
?>
    				<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
    					<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['payment']->value->date_add,'full'=>true),$_smarty_tpl ) );?>
</td>
    					<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->payment_method,'html','UTF-8' ));?>
</td>
    					<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['payment']->value->transaction_id,'html','UTF-8' ));?>
</td>
    					<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['payment']->value->amount,'currency'=>$_smarty_tpl->tpl_vars['payment']->value->id_currency),$_smarty_tpl ) );?>
</td>
    				</tr>
    				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    			</tbody>
    		</table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
            <table class="table_border" cellpadding="4" cellspacing="0">
        		<thead>
        			<tr>
        				<th>
        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Date','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        				</th>
        				<th>
        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Carrier','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        				</th>
        				<th>
        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Weight','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        				</th>
        				<th>
        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping cost','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        				</th>
        				<th>
        					<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tracking number','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
        				</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['order']->value->getShipping(), 'line');
$_smarty_tpl->tpl_vars['line']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['line']->value) {
$_smarty_tpl->tpl_vars['line']->do_else = false;
?>
        			<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
        				<td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['line']->value['date_add'],'full'=>true),$_smarty_tpl ) );?>
</td>
        				<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['carrier_name'],'html','UTF-8' ));?>
</td>
        				<td class="weight"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf("%.3f",$_smarty_tpl->tpl_vars['line']->value['weight']),'html','UTF-8' ));?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Configuration::get('PS_WEIGHT_UNIT'),'html','UTF-8' ));?>
</td>
        				<td class="center">
        					<?php if ($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_INC') ? constant('PS_TAX_INC') : null)) {?>
        						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['line']->value['shipping_cost_tax_incl'],'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

        					<?php } else { ?>
        						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['line']->value['shipping_cost_tax_excl'],'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

        					<?php }?>
        				</td>
        				<td>
        					<span class="shipping_number_show"><?php if ($_smarty_tpl->tpl_vars['line']->value['url'] && $_smarty_tpl->tpl_vars['line']->value['tracking_number']) {?><a class="_blank" href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( smarty_modifier_replace($_smarty_tpl->tpl_vars['line']->value['url'],'@',$_smarty_tpl->tpl_vars['line']->value['tracking_number']),'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['tracking_number'],'html','UTF-8' ));?>
</a><?php } else {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['line']->value['tracking_number'],'html','UTF-8' ));
}?></span>
        				</td>
        			</tr>
        			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        		</tbody>
        	</table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Customer','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
                    <tr>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Name','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Account registered','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Orders','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="even">
                        <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->firstname,'html','UTF-8' ));?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->lastname,'html','UTF-8' ));?>
</td>
                        <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customer']->value->email,'html','UTF-8' ));?>
</td>
                        <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0], array( array('date'=>$_smarty_tpl->tpl_vars['customer']->value->date_add,'full'=>true),$_smarty_tpl ) );?>
</td>
                        <td><?php echo intval($_smarty_tpl->tpl_vars['customerStats']->value['nb_orders']);?>
</td>
                        <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round(Tools::convertPrice($_smarty_tpl->tpl_vars['customerStats']->value['total_orders'],$_smarty_tpl->tpl_vars['currency']->value),2),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                        <th><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice address','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayAddressDetail'][0], array( array('address'=>$_smarty_tpl->tpl_vars['addresses']->value['delivery'],'newLine'=>'<br />'),$_smarty_tpl ) );?>

            				<?php if ($_smarty_tpl->tpl_vars['addresses']->value['delivery']->other) {?>
            					<hr /><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['delivery']->other,'html','UTF-8' ));?>
<br />
            				<?php }?>
                        </td>
                        <td><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayAddressDetail'][0], array( array('address'=>$_smarty_tpl->tpl_vars['addresses']->value['invoice'],'newLine'=>'<br />'),$_smarty_tpl ) );?>

            				<?php if ($_smarty_tpl->tpl_vars['addresses']->value['invoice']->other) {?>
            					<hr /><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['addresses']->value['invoice']->other,'html','UTF-8' ));?>
<br />
            				<?php }?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4"><h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</h4>
            <table style="font-size:8pt;max-width:100%;" class="table_border" cellpadding="4" cellspacing="0">
				<thead>
					<tr>
                        <th style="width:7%;"></th>
						<th style="width:40%;"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
						<th style="width:10%;">
							<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unit Price','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
						</th>
						<th class="text-center"  style="width:10%;"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qty','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th>
						<?php if ((isset($_smarty_tpl->tpl_vars['stock_management']->value)) && $_smarty_tpl->tpl_vars['stock_management']->value) {?><th class="text-center"><span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Available quantity','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span></th><?php }?>
                        <th>
							<span class="title_box "><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</span>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', false, 'k');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
					<?php if ($_smarty_tpl->tpl_vars['product']->value['customizedDatas']) {?>
                        <?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
                        	<?php $_smarty_tpl->_assignInScope('product_price', ($_smarty_tpl->tpl_vars['product']->value['unit_price_tax_excl']+$_smarty_tpl->tpl_vars['product']->value['ecotax']));?>
                        <?php } else { ?>
                        	<?php $_smarty_tpl->_assignInScope('product_price', $_smarty_tpl->tpl_vars['product']->value['unit_price_tax_incl']);?>
                        <?php }?>
                        	<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
                                <td style="width:7%;"><?php if ((isset($_smarty_tpl->tpl_vars['product']->value['image'])) && $_smarty_tpl->tpl_vars['product']->value['image']->id) {
echo $_smarty_tpl->tpl_vars['product']->value['image_tag'];
}?></td>                         		<td><span class="productName"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_name'],'html','UTF-8' ));?>
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
                        		</td>
                        		<td><span class="product_price_show"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['product_price']->value,'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>
</span></td>
                        		<td class="productQuantity text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],'html','UTF-8' ));?>
</td>
                                <?php if ((isset($_smarty_tpl->tpl_vars['stock_management']->value)) && $_smarty_tpl->tpl_vars['stock_management']->value) {?><td class="text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['current_stock'],'html','UTF-8' ));?>
</td><?php }?>
                        		
                                <td class="total_product">
                        		<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
                        			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price']*$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

                        		<?php } else { ?>
                        			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price_wt']*$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

                        		<?php }?>
                        		</td>
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
                        			<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
                        				<td colspan="2">
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
$__foreach_data_7_saved = $_smarty_tpl->tpl_vars['data'];
if ($_smarty_tpl->tpl_vars['data']->value['name']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['name'],'html','UTF-8' ));
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Picture #','mod'=>'g_ordermanager'),$_smarty_tpl ) );
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->iteration,'html','UTF-8' ));
}
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_7_saved;
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
$__foreach_data_8_saved = $_smarty_tpl->tpl_vars['data'];
if ($_smarty_tpl->tpl_vars['data']->value['name']) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%s','sprintf'=>$_smarty_tpl->tpl_vars['data']->value['name'],'mod'=>'g_ordermanager'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Text #%s','sprintf'=>$_smarty_tpl->tpl_vars['data']->iteration,'mod'=>'g_ordermanager'),$_smarty_tpl ) );
}?>:<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['data']->value['value'],'html','UTF-8' ));
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_8_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        							<?php }?>
                                                    <br/>
                        						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        					</div>
                        				</td>
                        				<td>-</td>
                        				<td class="productQuantity text-center">
                        					<span class="product_quantity_show<?php if ((int)$_smarty_tpl->tpl_vars['customization']->value['quantity'] > 1) {?> red bold<?php }?>"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['customization']->value['quantity'],'html','UTF-8' ));?>
</span>
                        				</td>
                                        <td class="text-center">-</td>
                                        
                        				<td class="total_product">
                        					<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
                        						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price']*$_smarty_tpl->tpl_vars['customization']->value['quantity'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

                        					<?php } else { ?>
                        						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>Tools::ps_round($_smarty_tpl->tpl_vars['product']->value['product_price_wt']*$_smarty_tpl->tpl_vars['customization']->value['quantity'],2),'currency'=>intval($_smarty_tpl->tpl_vars['currency']->value->id)),$_smarty_tpl ) );?>

                        					<?php }?>
                        				</td>
                        			</tr>
                        		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <?php }?>
                        <?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
                        	<?php $_smarty_tpl->_assignInScope('product_price', ($_smarty_tpl->tpl_vars['product']->value['unit_price_tax_excl']+$_smarty_tpl->tpl_vars['product']->value['ecotax']));?>
                        <?php } else { ?>
                        	<?php $_smarty_tpl->_assignInScope('product_price', $_smarty_tpl->tpl_vars['product']->value['unit_price_tax_incl']);?>
                        <?php }?>
                        
                        <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( ($_smarty_tpl->tpl_vars['product']->value['product_quantity'] > $_smarty_tpl->tpl_vars['product']->value['customized_product_quantity']),'html','UTF-8' ))) {?>
                        <tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
                            <td  style="width:7%;"><?php if ((isset($_smarty_tpl->tpl_vars['product']->value['image'])) && $_smarty_tpl->tpl_vars['product']->value['image']->id) {
echo $_smarty_tpl->tpl_vars['product']->value['image_tag'];
}?></td>                         	<td><span class="productName"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_name'],'html','UTF-8' ));?>
</span><br />
                    			<?php if ($_smarty_tpl->tpl_vars['product']->value['product_reference']) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Reference number:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_reference'],'html','UTF-8' ));?>
<br /><?php }?>
                    			<?php if ($_smarty_tpl->tpl_vars['product']->value['product_supplier_reference']) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Supplier reference:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['product_supplier_reference'],'html','UTF-8' ));
}?>
                        	</td>
                        	<td>
                        		<span class="product_price_show"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['product_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</span>
                        	</td>
                        	<td class="productQuantity text-center">
                        		<span class="product_quantity_show<?php if ((int)$_smarty_tpl->tpl_vars['product']->value['product_quantity']-(int)$_smarty_tpl->tpl_vars['product']->value['customized_product_quantity'] > 1) {?> badge<?php }?>"><?php echo (int)$_smarty_tpl->tpl_vars['product']->value['product_quantity']-(int)call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['customized_product_quantity'],'html','UTF-8' ));?>
</span>
                        	</td>
                        	<?php if ((isset($_smarty_tpl->tpl_vars['stock_management']->value)) && $_smarty_tpl->tpl_vars['stock_management']->value) {?><td class="productQuantity product_stock text-center"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['current_stock'],'html','UTF-8' ));?>
</td><?php }?>
                        	
                            <td class="total_product">
                        		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>(Tools::ps_round($_smarty_tpl->tpl_vars['product_price']->value,2)*($_smarty_tpl->tpl_vars['product']->value['product_quantity']-$_smarty_tpl->tpl_vars['product']->value['customizationQuantityTotal'])),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

                        	</td>
                        </tr>
                        <?php }?>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="2"  style="width:50%;"><?php if ($_smarty_tpl->tpl_vars['discounts']->value) {?><br />
            <table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
            	<thead>
            		<tr>
            			<th>
            				<span class="title_box ">
            					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discount name','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

            				</span>
            			</th>
            			<th>
            				<span class="title_box ">
            					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Value','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

            				</span>
            			</th>
            		</tr>
            	</thead>
            	<tbody>
            		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['discounts']->value, 'discount');
$_smarty_tpl->tpl_vars['discount']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['discount']->value) {
$_smarty_tpl->tpl_vars['discount']->do_else = false;
?>
            		<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
            			<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['discount']->value['name'],'html','UTF-8' ));?>
</td>
            			<td>
            			<?php if ($_smarty_tpl->tpl_vars['discount']->value['value'] != 0.00) {?>
            				-
            			<?php }?>
            			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value'],'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

            			</td>
            		</tr>
            		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            	</tbody>
            </table>
            <?php }
if ((isset($_smarty_tpl->tpl_vars['notes']->value)) && $_smarty_tpl->tpl_vars['notes']->value) {?><table style="font-size:8pt;" class="table_border" cellpadding="4" cellspacing="0">
            	<thead>
            		<tr>
            			<th>
            				<span class="title_box ">
            					<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Notes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>

            				</span>
            			</th>
            		</tr>
            	</thead>
            	<tbody>
            		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notes']->value, 'note');
$_smarty_tpl->tpl_vars['note']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['note']->value) {
$_smarty_tpl->tpl_vars['note']->do_else = false;
?>
            		<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
            			<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['note']->value['notes'],'html','UTF-8' ));?>
</td>
            		</tr>
            		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            	</tbody>
            </table><?php }?>
        </td>
        <td colspan="2" style="width:50%;"><br />
            <table class="table_border" cellpadding="4" cellspacing="0">
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
				<tr class="odd">
					<td class="text-right total_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products:','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
					<td class="amount text-right nowrap">
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_product_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

					</td>
				</tr><?php if ($_smarty_tpl->tpl_vars['order']->value->total_discounts_tax_incl != 0) {?>
				<tr  class="odd">
					<td class="text-right total_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discounts','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
					<td class="amount text-right nowrap">
						-<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_discount_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

					</td>
				</tr>
                <?php }
if ($_smarty_tpl->tpl_vars['order']->value->total_wrapping_tax_incl != 0) {?>
				<tr class="odd">
					<td class="text-right total_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wrapping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
					<td class="amount text-right nowrap">
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_wrapping_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

					</td>
				</tr>
                <?php }?>
				<tr class="odd">
					<td class="text-right total_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
					<td class="amount text-right nowrap" >
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_shipping_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>

					</td>
				</tr>
				<?php if (($_smarty_tpl->tpl_vars['order']->value->getTaxCalculationMethod() == (defined('PS_TAX_EXC') ? constant('PS_TAX_EXC') : null))) {?>
				<tr class="odd">
					<td class="text-right total_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Taxes','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</td>
					<td class="amount text-right nowrap" ><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>($_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl-$_smarty_tpl->tpl_vars['order']->value->total_paid_tax_excl),'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</td>
				</tr>
				<?php }?>
				<?php $_smarty_tpl->_assignInScope('order_total_price', $_smarty_tpl->tpl_vars['order']->value->total_paid_tax_incl);?>
				<tr class="odd">
					<td class="text-right total_title"><strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'g_ordermanager'),$_smarty_tpl ) );?>
</strong></td>
					<td class="amount text-right nowrap ">
						<strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0], array( array('price'=>$_smarty_tpl->tpl_vars['order_total_price']->value,'currency'=>$_smarty_tpl->tpl_vars['currency']->value->id),$_smarty_tpl ) );?>
</strong>
					</td>
				</tr>
			</table>
        </td>
    </tr>
</table><?php }
}
