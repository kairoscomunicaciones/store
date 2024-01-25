<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:59:03
  from 'module:psbookingviewstemplatesho' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a699b78eae78_59738134',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3ae6f7b975b4a18e89fed56945ed468605e6a96f' => 
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
function content_65a699b78eae78_59738134 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_26678727565a699b78cb835_05379513', 'order_confirmation_table');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['order_confirmation_template_file']->value);
}
/* {block 'order-items-table-head'} */
class Block_210597859265a699b78cbc87_42200308 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div id="order-items" class="col-md-8">
    <h3 class="card-title h3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order items','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</h3>
  <?php
}
}
/* {/block 'order-items-table-head'} */
/* {block 'order_confirmation_table'} */
class Block_26678727565a699b78cb835_05379513 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'order_confirmation_table' => 
  array (
    0 => 'Block_26678727565a699b78cb835_05379513',
  ),
  'order-items-table-head' => 
  array (
    0 => 'Block_210597859265a699b78cbc87_42200308',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_210597859265a699b78cbc87_42200308', 'order-items-table-head', $this->tplIndex);
?>

    <div class="order-confirmation-table" style="text-align: center;">
      <table class="table">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orderProducts']->value, 'product');
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
?>
          <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['isBookingProduct'])) && $_smarty_tpl->tpl_vars['product']->value['isBookingProduct']) {?>
            <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['booking_product_data'])) && $_smarty_tpl->tpl_vars['product']->value['booking_product_data']) {?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['booking_product_data'], 'bookingProduct');
$_smarty_tpl->tpl_vars['bookingProduct']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['bookingProduct']->value) {
$_smarty_tpl->tpl_vars['bookingProduct']->do_else = false;
?>
                <div class="order-line row">
                  <div class="col-sm-2 col-xs-3">
                    <span class="image">
                      <?php if (!empty($_smarty_tpl->tpl_vars['product']->value['default_image'])) {?>
                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['default_image']['medium']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                      <?php } else { ?>
                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['no_picture_image']['bySize']['medium_default']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                      <?php }?>
                    </span>
                  </div>
                  <div class="col-sm-4 col-xs-9 details">
                    <?php if ($_smarty_tpl->tpl_vars['add_product_link']->value) {?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
" target="_blank"><?php }?>
                      <span><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</span>
                    <?php if ($_smarty_tpl->tpl_vars['add_product_link']->value) {?></a><?php }?>
                    <?php if (count($_smarty_tpl->tpl_vars['product']->value['customizations'])) {?>
                      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['customizations'], 'customization');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
                        <div class="customizations">
                          <a href="#" data-toggle="modal" data-target="#product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
                        </div>
                        <div class="modal fade customization-modal" id="product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</h4>
                              </div>
                              <div class="modal-body">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customization']->value['fields'], 'field');
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
                                  <div class="product-customization-line row">
                                    <div class="col-sm-3 col-xs-4 label">
                                      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['label'], ENT_QUOTES, 'UTF-8');?>

                                    </div>
                                    <div class="col-sm-9 col-xs-8 value">
                                      <?php if ($_smarty_tpl->tpl_vars['field']->value['type'] == 'text') {?>
                                        <?php if ((int)$_smarty_tpl->tpl_vars['field']->value['id_module']) {?>
                                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['text'], ENT_QUOTES, 'UTF-8');?>

                                        <?php } else { ?>
                                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['text'], ENT_QUOTES, 'UTF-8');?>

                                        <?php }?>
                                      <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['type'] == 'image') {?>
                                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['image']['small']['url'], ENT_QUOTES, 'UTF-8');?>
">
                                      <?php }?>
                                    </div>
                                  </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <?php }?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"unit_price"),$_smarty_tpl ) );?>

                  </div>
                  <div class="col-sm-6 col-xs-12 qty">
                    <div class="row">
                      <div class="col-xs-4 text-sm-right text-xs-left booking_date_range" style="font-size: 14px; text-align: center!important;">
                        <?php if ($_smarty_tpl->tpl_vars['bookingProduct']->value['booking_type'] == 1) {?>
                          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
</br>
                          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'to','mod'=>'psbooking'),$_smarty_tpl ) );?>
</br>
                          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_to']), ENT_QUOTES, 'UTF-8');?>

                        <?php } else { ?>
                          <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
</br>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['time_from'], ENT_QUOTES, 'UTF-8');?>
 - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['time_to'], ENT_QUOTES, 'UTF-8');?>

                        <?php }?>
                      </div>
                      <div class="col-xs-1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['quantity'], ENT_QUOTES, 'UTF-8');?>
</div>
                      <div class="col-xs-3">
                        <?php if ((!$_smarty_tpl->tpl_vars['priceDisplay']->value || $_smarty_tpl->tpl_vars['priceDisplay']->value == 2)) {?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['unit_feature_price_tax_incl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php } else { ?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['unit_feature_price_tax_excl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php }?>
                      </div>
                      <div class="col-xs-4 text-xs-right bold">
                        <?php if ((!$_smarty_tpl->tpl_vars['priceDisplay']->value || $_smarty_tpl->tpl_vars['priceDisplay']->value == 2)) {?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['total_range_feature_price_tax_incl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php } else { ?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['total_range_feature_price_tax_excl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php }?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
          <?php } else { ?>
            <div class="order-line row">
              <div class="col-sm-2 col-xs-3">
                <span class="image">
                  <?php if (!empty($_smarty_tpl->tpl_vars['product']->value['default_image'])) {?>
                    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['default_image']['medium']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                  <?php } else { ?>
                    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['no_picture_image']['bySize']['medium_default']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                  <?php }?>
                </span>
              </div>
              <div class="col-sm-4 col-xs-9 details">
                <?php if ($_smarty_tpl->tpl_vars['add_product_link']->value) {?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
" target="_blank"><?php }?>
                  <span><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</span>
                <?php if ($_smarty_tpl->tpl_vars['add_product_link']->value) {?></a><?php }?>
                <?php if (count($_smarty_tpl->tpl_vars['product']->value['customizations'])) {?>
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['customizations'], 'customization');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
                    <div class="customizations">
                      <a href="#" data-toggle="modal" data-target="#product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
                    </div>
                    <div class="modal fade customization-modal" id="product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</h4>
                          </div>
                          <div class="modal-body">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['customization']->value['fields'], 'field');
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
                              <div class="product-customization-line row">
                                <div class="col-sm-3 col-xs-4 label">
                                  <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['label'], ENT_QUOTES, 'UTF-8');?>

                                </div>
                                <div class="col-sm-9 col-xs-8 value">
                                  <?php if ($_smarty_tpl->tpl_vars['field']->value['type'] == 'text') {?>
                                    <?php if ((int)$_smarty_tpl->tpl_vars['field']->value['id_module']) {?>
                                      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['text'], ENT_QUOTES, 'UTF-8');?>

                                    <?php } else { ?>
                                      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['text'], ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                  <?php } elseif ($_smarty_tpl->tpl_vars['field']->value['type'] == 'image') {?>
                                    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value['image']['small']['url'], ENT_QUOTES, 'UTF-8');?>
">
                                  <?php }?>
                                </div>
                              </div>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"unit_price"),$_smarty_tpl ) );?>

              </div>
              <div class="col-sm-6 col-xs-12 qty">
                <div class="row">
                  <div class="col-xs-5"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['quantity'], ENT_QUOTES, 'UTF-8');?>
</div>
                  <div class="col-xs-3 text-sm-right text-xs-left"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</div>
                  <div class="col-xs-4 text-xs-right bold"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['total'], ENT_QUOTES, 'UTF-8');?>
</div>
                </div>
              </div>
            </div>
          <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      <hr />
      <table>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['subtotals']->value, 'subtotal');
$_smarty_tpl->tpl_vars['subtotal']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['subtotal']->value) {
$_smarty_tpl->tpl_vars['subtotal']->do_else = false;
?>
          <?php if ($_smarty_tpl->tpl_vars['subtotal']->value['type'] !== 'tax') {?>
            <tr>
              <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subtotal']->value['label'], ENT_QUOTES, 'UTF-8');?>
</td>
              <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subtotal']->value['value'], ENT_QUOTES, 'UTF-8');?>
</td>
            </tr>
          <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php if ($_smarty_tpl->tpl_vars['subtotals']->value['tax']['label'] !== null) {?>
          <tr class="sub">
            <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subtotals']->value['tax']['label'], ENT_QUOTES, 'UTF-8');?>
</td>
            <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subtotals']->value['tax']['value'], ENT_QUOTES, 'UTF-8');?>
</td>
          </tr>
        <?php }?>
        <tr class="font-weight-bold">
          <td><span class="text-uppercase"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totals']->value['total']['label'], ENT_QUOTES, 'UTF-8');?>
</span> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['labels']->value['tax_short'], ENT_QUOTES, 'UTF-8');?>
</td>
          <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totals']->value['total']['value'], ENT_QUOTES, 'UTF-8');?>
</td>
        </tr>
      </table>
    </div>
  </div>
<?php
}
}
/* {/block 'order_confirmation_table'} */
}
