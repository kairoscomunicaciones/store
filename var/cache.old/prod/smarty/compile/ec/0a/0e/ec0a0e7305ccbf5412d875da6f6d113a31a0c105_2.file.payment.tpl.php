<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:58:20
  from '/home2/inveriti/public_html/themes/giftshop/templates/checkout/_partials/steps/payment.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6998c1e2ad3_96945945',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec0a0e7305ccbf5412d875da6f6d113a31a0c105' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/checkout/_partials/steps/payment.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:checkout/_partials/order-final-summary.tpl' => 1,
  ),
),false)) {
function content_65a6998c1e2ad3_96945945 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_35203529465a6998c1cbac6_37210182', 'step_content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'checkout/_partials/steps/checkout-step.tpl');
}
/* {block 'step_content'} */
class Block_35203529465a6998c1cbac6_37210182 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'step_content' => 
  array (
    0 => 'Block_35203529465a6998c1cbac6_37210182',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>




  <div class="payment-options">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['payment_options']->value, 'module_options');
$_smarty_tpl->tpl_vars['module_options']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['module_options']->value) {
$_smarty_tpl->tpl_vars['module_options']->do_else = false;
?>
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['module_options']->value, 'option');
$_smarty_tpl->tpl_vars['option']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->do_else = false;
?>
        <div>
          <div id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
-container" class="payment-option clearfix">
                        <span class="custom-radio pull-xs-left">
              <input
                class="ps-shown-by-js <?php if ($_smarty_tpl->tpl_vars['option']->value['binary']) {?> binary <?php }?>"
                id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                data-module-name="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['module_name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                name="payment-option"
                type="radio"
                required
                <?php if ($_smarty_tpl->tpl_vars['selected_payment_option']->value == $_smarty_tpl->tpl_vars['option']->value['id']) {?> checked <?php }?>
              >
              <span></span>
            </span>
                        <form method="GET" class="ps-hidden-by-js">
              <?php if ($_smarty_tpl->tpl_vars['option']->value['id'] === $_smarty_tpl->tpl_vars['selected_payment_option']->value) {?>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Selected','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>

              <?php } else { ?>
                <button class="ps-hidden-by-js" type="submit" name="select_payment_option" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Choose','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

                </button>
              <?php }?>
            </form>

            <label for="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
              <span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['call_to_action_text'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
              <?php if ($_smarty_tpl->tpl_vars['option']->value['logo']) {?>
                <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['logo'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
              <?php }?>
            </label>

          </div>
        </div>
        

        <?php if ($_smarty_tpl->tpl_vars['option']->value['additionalInformation']) {?>
          <div
            id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
-additional-information"
            class="js-additional-information definition-list additional-information<?php if ($_smarty_tpl->tpl_vars['option']->value['id'] != $_smarty_tpl->tpl_vars['selected_payment_option']->value) {?> ps-hidden <?php }?>"
          >
            <?php echo $_smarty_tpl->tpl_vars['option']->value['additionalInformation'];?>

          </div>
        <?php }?>

        <div
          id="pay-with-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
-form"
          class="js-payment-option-form <?php if ($_smarty_tpl->tpl_vars['option']->value['id'] != $_smarty_tpl->tpl_vars['selected_payment_option']->value) {?> ps-hidden <?php }?>"
        >
          <?php if ($_smarty_tpl->tpl_vars['option']->value['form']) {?>
            <?php echo $_smarty_tpl->tpl_vars['option']->value['form'];?>

          <?php } else { ?>
            <form id="payment-form" method="POST" action="<?php echo $_smarty_tpl->tpl_vars['option']->value['action'];?>
">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['option']->value['inputs'], 'input');
$_smarty_tpl->tpl_vars['input']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['input']->value) {
$_smarty_tpl->tpl_vars['input']->do_else = false;
?>
                <input type="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" name="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['value'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              <button style="display:none" id="pay-with-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['option']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" type="submit"></button>
            </form>
          <?php }?>
        </div>
      <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php
}
if ($_smarty_tpl->tpl_vars['module_options']->do_else) {
?>
      <p class="alert alert-danger"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unfortunately, there are no payment method available.','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</p>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
  </div>
  
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayPaymentTop'),$_smarty_tpl ) );?>


  <?php if (count($_smarty_tpl->tpl_vars['conditions_to_approve']->value)) {?>
    <p class="ps-hidden-by-js">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'By confirming the order, you certify that you have read and agree with all of the conditions below:','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>

    </p>

    <form id="conditions-to-approve" method="GET">
      <ul>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['conditions_to_approve']->value, 'condition', false, 'condition_name');
$_smarty_tpl->tpl_vars['condition']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['condition_name']->value => $_smarty_tpl->tpl_vars['condition']->value) {
$_smarty_tpl->tpl_vars['condition']->do_else = false;
?>
          <li>
            <div class="pull-xs-left">
              <span class="custom-checkbox">
                <input  id    = "conditions_to_approve[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['condition_name']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                        name  = "conditions_to_approve[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['condition_name']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]"
                        required
                        type  = "checkbox"
                        value = "1"
                        class = "ps-shown-by-js"
                >
                <span><i class="material-icons checkbox-checked">&#xE5CA;</i></span>
              </span>
            </div>
            <div class="condition-label">
              <label class="js-terms" for="conditions_to_approve[<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['condition_name']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
]">
                <?php echo $_smarty_tpl->tpl_vars['condition']->value;?>

              </label>
            </div>
          </li>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </ul>
    </form>
  <?php }?>

  <?php if ($_smarty_tpl->tpl_vars['show_final_summary']->value) {?>
    <?php $_smarty_tpl->_subTemplateRender('file:checkout/_partials/order-final-summary.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php }?>

  <div id="payment-confirmation" class="etssss">
    <div class="ps-shown-by-js">
      <button type="submit" class="btn btn-primary center-block<?php if (!$_smarty_tpl->tpl_vars['selected_payment_option']->value) {?> disabled<?php }?>">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order with an obligation to pay','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>

      </button>
      <?php if ($_smarty_tpl->tpl_vars['show_final_summary']->value) {?>
        <article class="alert alert-danger m-t-2 js-alert-payment-conditions" role="alert" data-alert="danger">
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please make sure you\'ve chosen a [1]payment method[/1] and accepted the [2]terms and conditions[/2].','sprintf'=>array('[1]'=>'<a href="#checkout-payment-step">','[/1]'=>'</a>','[2]'=>'<a href="#conditions-to-approve">','[/2]'=>'</a>'),'d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>

        </article>
      <?php }?>
    </div>
    <div class="ps-hidden-by-js">
      <?php if ($_smarty_tpl->tpl_vars['selected_payment_option']->value && $_smarty_tpl->tpl_vars['all_conditions_approved']->value) {?>
        <label for="pay-with-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['selected_payment_option']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order with an obligation to pay','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</label>
      <?php }?>
    </div>
  </div>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayPaymentByBinaries'),$_smarty_tpl ) );?>


  <div class="modal fade" id="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      </div>
    </div>
  </div>
<?php
}
}
/* {/block 'step_content'} */
}
