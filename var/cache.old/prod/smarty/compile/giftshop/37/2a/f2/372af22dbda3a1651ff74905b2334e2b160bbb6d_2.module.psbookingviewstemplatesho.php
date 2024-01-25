<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:24:45
  from 'module:psbookingviewstemplatesho' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04e8dc40032_73509920',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '372af22dbda3a1651ff74905b2334e2b160bbb6d' => 
    array (
      0 => 'module:psbookingviewstemplatesho',
      1 => 1697325102,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a04e8dc40032_73509920 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['bookingProductCartInfo']->value))) {?>
  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bookingProductCartInfo']->value, 'productBooking', false, 'key');
$_smarty_tpl->tpl_vars['productBooking']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['productBooking']->value) {
$_smarty_tpl->tpl_vars['productBooking']->do_else = false;
?>
  <div class="cart_pop_up_data range-period">
      <div class="booking-dates">
        <?php if ($_smarty_tpl->tpl_vars['productBooking']->value['booking_type'] == 1) {?>
          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['productBooking']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
&nbsp;
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'to','mod'=>'psbooking'),$_smarty_tpl ) );?>
&nbsp;
          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['productBooking']->value['date_to']), ENT_QUOTES, 'UTF-8');?>

        <?php } else { ?>
          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['productBooking']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
&nbsp;
          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productBooking']->value['time_from'], ENT_QUOTES, 'UTF-8');?>
 - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productBooking']->value['time_to'], ENT_QUOTES, 'UTF-8');?>

        <?php }?>
      </div>
      <div class="booking-quantity">
        <span style="font-weight: bold;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total quantity','mod'=>'psbooking'),$_smarty_tpl ) );?>
</span>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productBooking']->value['quantity'], ENT_QUOTES, 'UTF-8');?>

      </div>
      <div class="booking-price">
        <span style="font-weight: bold;"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total price','mod'=>'psbooking'),$_smarty_tpl ) );?>
</span>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productBooking']->value['totalPriceTE'], ENT_QUOTES, 'UTF-8');?>
 (tax excl.)
      </div>
  </div>
  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>
<style type="text/css">
  .product-name ~ p {
    display: none;
  }
  .booking-dates {
    font-weight:bold;
  }
  .cart_pop_up_data {
    font-size: 12px;
    color: #333;
    border-bottom:1px solid #333;
  }
</style>
<?php }
}
