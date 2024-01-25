<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:24
  from 'module:psbookingviewstemplatesho' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04eb494d812_02161708',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8f558e5d3e3f8eadbef324a7f795f329242241fb' => 
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
function content_65a04eb494d812_02161708 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_51963752365a04eb4945497_07690385', 'cart_summary_product_line');
?>


  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_79616308965a04eb494ce76_66734096', 'order_items_table_head');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['checkout_template_file']->value);
}
/* {block 'cart_summary_product_line'} */
class Block_51963752365a04eb4945497_07690385 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'cart_summary_product_line' => 
  array (
    0 => 'Block_51963752365a04eb4945497_07690385',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="media-left">
    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
">
      <?php if ($_smarty_tpl->tpl_vars['product']->value['default_image']) {?>
        <img class="media-object" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['default_image']['small']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy">
      <?php } else { ?>
        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['no_picture_image']['bySize']['small_default']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
      <?php }?>
    </a>
  </div>
  <div class="media-body">
    <span class="product-name">
      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>

      <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['isBookingProduct'])) && $_smarty_tpl->tpl_vars['product']->value['isBookingProduct']) {?>
        </br>
        <span class="booking_product_label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Booking product','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</span>
      <?php }?>
    </span>
    <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['isBookingProduct'])) && $_smarty_tpl->tpl_vars['product']->value['isBookingProduct']) {?>
      <span class="product-price float-xs-right"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['total_price_tax_excl_formatted'], ENT_QUOTES, 'UTF-8');?>
</span>
    <?php } else { ?>
      <span class="product-quantity">x<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['quantity'], ENT_QUOTES, 'UTF-8');?>
</span>
      <span class="product-price float-xs-right"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</span>
    <?php }?>
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"unit_price"),$_smarty_tpl ) );?>

  </div>
  <style>
    .booking_product_label {
      background-color: #24b9d7;
      color: #fff;
      font-size: 11px;
      padding: 0px 4px;
      border-radius: 4px;`
    }
  </style>
<?php
}
}
/* {/block 'cart_summary_product_line'} */
/* {block 'order_items_table_head'} */
class Block_79616308965a04eb494ce76_66734096 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'order_items_table_head' => 
  array (
    0 => 'Block_79616308965a04eb494ce76_66734096',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <h3 class="card-title h3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order items','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</h3>
  <?php
}
}
/* {/block 'order_items_table_head'} */
}
