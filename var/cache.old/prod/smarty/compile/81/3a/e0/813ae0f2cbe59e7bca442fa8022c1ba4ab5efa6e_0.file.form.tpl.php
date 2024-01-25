<?php
/* Smarty version 3.1.43, created on 2024-01-11 12:47:24
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/gwadvancedinvoiceconfig/helpers/form/form.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a01b9c11c665_81831852',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '813ae0f2cbe59e7bca442fa8022c1ba4ab5efa6e' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/gwadvancedinvoiceconfig/helpers/form/form.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a01b9c11c665_81831852 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_32757242765a01b9c0f1811_00289515', "field");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "helpers/form/form.tpl");
}
/* {block "field"} */
class Block_32757242765a01b9c0f1811_00289515 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'field' => 
  array (
    0 => 'Block_32757242765a01b9c0f1811_00289515',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['input']->value['type'] == 'customergroupselect') {?>
        <div class="col-lg-9 customergroupselect">
            <select name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'html','UTF-8' ));?>
_TYPE" class="fixed-width-xl customergroupselect_type">
                <option value="customer_group" <?php if (!(isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) || $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] == 'customer_group') {?> selected="selected" <?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Customer group','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</option>
                <option value="payment_method" <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) && $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] == 'payment_method') {?> selected="selected" <?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment method','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</option>
                <option value="order_status" <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) && $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] == 'order_status') {?> selected="selected" <?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order status','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</option>
            </select>
            <br />
            
            <div class="order_status_box customergroupselect_box" <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) && $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] != 'order_status') {?> style="display:none;" <?php }?>>
                <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value['allstatus'])) && $_smarty_tpl->tpl_vars['fields_value']->value['allstatus']) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fields_value']->value['allstatus'], 'group');
$_smarty_tpl->tpl_vars['group']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->do_else = false;
?>
                        <?php if (($_smarty_tpl->tpl_vars['input']->value['name'] == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE' && $_smarty_tpl->tpl_vars['group']->value['invoice']) || ($_smarty_tpl->tpl_vars['input']->value['name'] == 'GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE' && $_smarty_tpl->tpl_vars['group']->value['delivery'])) {?>
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['name'],'html','UTF-8' ));?>

                            </label>
                            <div class="col-lg-3">
                                <?php if ($_smarty_tpl->tpl_vars['input']->value['name'] == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE') {?>
                                    <select name="GWADVANCEDINVOICE_STATUS_TEMPLATE_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_order_state'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_STATUS_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_order_state'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_STATUS_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_order_state'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php } else { ?>
                                    <select name="GWADVANCEDDELIVERY_STATUS_TEMPLATE_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_order_state'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_STATUS_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_order_state'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_STATUS_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_order_state'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php }?>
                            </div>
                        </div>
                        <?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
            </div>
            
            <div class="customer_group_box customergroupselect_box" <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) && $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] != 'customer_group') {?> style="display:none;" <?php }?>>
                <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value['groups'])) && $_smarty_tpl->tpl_vars['fields_value']->value['groups']) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fields_value']->value['groups'], 'group');
$_smarty_tpl->tpl_vars['group']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->do_else = false;
?>
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['name'],'html','UTF-8' ));?>

                            </label>
                            <div class="col-lg-3">
                                <?php if ($_smarty_tpl->tpl_vars['input']->value['name'] == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE') {?>
                                    <select name="GWADVANCEDINVOICE_GROUP_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_group'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_GROUP_".((string)$_smarty_tpl->tpl_vars['group']->value['id_group'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_GROUP_".((string)$_smarty_tpl->tpl_vars['group']->value['id_group'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php } else { ?>
                                    <select name="GWADVANCEDDELIVERY_GROUP_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_group'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_GROUP_".((string)$_smarty_tpl->tpl_vars['group']->value['id_group'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_GROUP_".((string)$_smarty_tpl->tpl_vars['group']->value['id_group'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php }?>
                            </div>
                        </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
            </div>
            <div class="payment_method_box customergroupselect_box" <?php if (!(isset($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"])) || $_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_TYPE"] != 'payment_method') {?> style="display:none;" <?php }?>>
                <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value['payment_modules'])) && $_smarty_tpl->tpl_vars['fields_value']->value['payment_modules']) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fields_value']->value['payment_modules'], 'group');
$_smarty_tpl->tpl_vars['group']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->do_else = false;
?>
                        <div class="form-group customer_group">
                            <label class="col-lg-3">
                                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['name'],'html','UTF-8' ));?>

                            </label>
                            <div class="col-lg-3">
                                <?php if ($_smarty_tpl->tpl_vars['input']->value['name'] == 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE') {?>
                                    <select name="GWADVANCEDINVOICE_PAYMENT_TEMPLATE_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_module'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_PAYMENT_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_module'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDINVOICE_PAYMENT_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_module'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php } else { ?>
                                    <select name="GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['group']->value['id_module'],'html','UTF-8' ));?>
" class="fixed-width-xl">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['input']->value['options'])) && $_smarty_tpl->tpl_vars['input']->value['options']) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['input']->value['options'], 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
                                                <option <?php if ((isset($_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_module'])])) && $_smarty_tpl->tpl_vars['fields_value']->value["GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_".((string)$_smarty_tpl->tpl_vars['group']->value['id_module'])] == $_smarty_tpl->tpl_vars['option']->value['value']) {?> selected="selected" <?php }?>  value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['value'],'html','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['name'],'html','UTF-8' ));?>
</option>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php }?>
                                    </select>
                                <?php }?>
                            </div>
                        </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
            </div>
            <?php 
$_smarty_tpl->inheritance->callParent($_smarty_tpl, $this, '{$smarty.block.parent}');
?>

        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['input']->value['type'] == 'opentab') {?>
        </div>
        <div class="<?php if ((isset($_smarty_tpl->tpl_vars['input']->value['name'])) && $_smarty_tpl->tpl_vars['input']->value['name']) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'htmlall','UTF-8' ));
}?> <?php if ($_smarty_tpl->tpl_vars['input']->value['name'] == 'config_content') {?>activetab<?php }?>">
        <div>
    <?php } elseif ($_smarty_tpl->tpl_vars['input']->value['type'] == 'closetab') {?>
        </div>
    <?php } else { ?>
        <?php 
$_smarty_tpl->inheritance->callParent($_smarty_tpl, $this, '{$smarty.block.parent}');
?>

    <?php }
}
}
/* {/block "field"} */
}
