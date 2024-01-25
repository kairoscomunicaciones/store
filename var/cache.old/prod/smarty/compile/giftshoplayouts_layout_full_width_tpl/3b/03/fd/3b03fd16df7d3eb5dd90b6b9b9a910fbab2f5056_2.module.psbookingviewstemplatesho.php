<?php
/* Smarty version 3.1.43, created on 2024-01-11 16:25:09
  from 'module:psbookingviewstemplatesho' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a04ea5a47e36_45170202',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3b03fd16df7d3eb5dd90b6b9b9a910fbab2f5056' => 
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
function content_65a04ea5a47e36_45170202 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_120053094665a04ea5a20f90_81098227', 'cart_overview');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['cart_template_file']->value);
}
/* {block 'cart_overview'} */
class Block_120053094665a04ea5a20f90_81098227 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'cart_overview' => 
  array (
    0 => 'Block_120053094665a04ea5a20f90_81098227',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="cart-overview js-cart" data-refresh-url="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['url'][0], array( array('entity'=>'cart','params'=>array('ajax'=>true,'action'=>'refresh')),$_smarty_tpl ) );?>
">
    <?php if ($_smarty_tpl->tpl_vars['presentedCart']->value['products']) {?>
    <ul class="cart-items">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['presentedCart']->value['products'], 'product');
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
              <li class="cart-item">
                <div class="product-line-grid">
                  <!--  product left content: image-->
                  <div class="product-line-grid-left col-md-3 col-xs-4">
                    <span class="product-image media-middle">
                      <?php if ($_smarty_tpl->tpl_vars['product']->value['default_image']) {?>
                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['cart_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" loading="lazy">
                      <?php } else { ?>
                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['no_picture_image']['bySize']['cart_default']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                      <?php }?>
                    </span>
                  </div>

                  <!--  product left body: description -->
                  <div class="product-line-grid-body col-md-3 col-xs-8">
                    <div class="product-line-info">
                      <a class="label" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a>
                    </div>

                    <div class="product-line-info">
                      <span class="value">
                        <?php if ((!$_smarty_tpl->tpl_vars['priceDisplay']->value || $_smarty_tpl->tpl_vars['priceDisplay']->value == 2)) {?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['unit_feature_price_tax_incl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php } else { ?>
                          <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['unit_feature_price_tax_excl_formated'], ENT_QUOTES, 'UTF-8');?>

                        <?php }?>
                        </span>
                      <?php if ($_smarty_tpl->tpl_vars['product']->value['unit_price_full']) {?>
                        <div class="unit-price-cart"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['unit_price_full'], ENT_QUOTES, 'UTF-8');?>
</div>
                      <?php }?>
                    </div>

                    <br/>

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['attributes'], 'value', false, 'attribute');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['attribute']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
                      <div class="product-line-info">
                        <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attribute']->value, ENT_QUOTES, 'UTF-8');?>
:</span>
                        <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8');?>
</span>
                      </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                    <?php if (count($_smarty_tpl->tpl_vars['product']->value['customizations'])) {?>
                      <br/>
                      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['customizations'], 'customization');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
                        <a href="#" data-toggle="modal" data-target="#product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
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
                  </div>

                  <!--  product left body: description -->
                  <div class="product-line-grid-right product-line-actions col-md-6 col-xs-12">
                    <div class="row">
                      <div class="col-xs-4 hidden-md-up"></div>
                      <div class="col-md-10 col-xs-6">
                        <div class="row">
                          <div class="col-md-5 col-xs-6 qty booking_product_date" style="font-size: 14px; text-align: center;">
                            <?php if ($_smarty_tpl->tpl_vars['bookingProduct']->value['booking_type'] == 1) {?>
                              <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
</br> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'to','mod'=>'psbooking'),$_smarty_tpl ) );?>
</br> <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_to']), ENT_QUOTES, 'UTF-8');?>

                            <?php } else { ?>
                              <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['bookingProduct']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
</br>
                              <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['time_from'], ENT_QUOTES, 'UTF-8');?>
 - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['time_to'], ENT_QUOTES, 'UTF-8');?>

                            <?php }?>
                          </div>
                          <div class="col-md-2" style="line-height: 36px;">
                            <input class="booking-product-range-qty" type="hidden" value="{}">
                            <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['quantity'], ENT_QUOTES, 'UTF-8');?>

                          </div>
                          <div class="col-md-5 col-xs-2 price">
                            <span class="product-price">
                              <strong>
                                <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['is_gift'])) && $_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                                  <span class="gift"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gift','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
                                <?php } else { ?>
                                  <?php if ((!$_smarty_tpl->tpl_vars['priceDisplay']->value || $_smarty_tpl->tpl_vars['priceDisplay']->value == 2)) {?>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['totalPriceTI'], ENT_QUOTES, 'UTF-8');?>

                                  <?php } else { ?>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['totalPriceTE'], ENT_QUOTES, 'UTF-8');?>

                                  <?php }?>
                                <?php }?>
                              </strong>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 col-xs-2 text-xs-right">
                        <div class="cart-line-product-actions ">
                          <a
                            style                  = "color: #414141;display: inline-block;margin-top: .3125rem;"
                            href                   = "#"
                            id-cart-booking        = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProduct']->value['id'], ENT_QUOTES, 'UTF-8');?>
"
                            class                  = "remove-booking-product"
                            id-product             = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
"
                            id-product-attribute   = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], ENT_QUOTES, 'UTF-8');?>
"
                          >
                            <?php if (!(isset($_smarty_tpl->tpl_vars['product']->value['is_gift'])) || !$_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                            <i class="material-icons pull-xs-left">delete</i>
                            <?php }?>
                          </a>
                          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayCartExtraProductActions','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                </div>
              </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          <?php }?>
        <?php } else { ?>
          <li class="cart-item">
            <div class="product-line-grid">
              <!--  product left content: image-->
              <div class="product-line-grid-left col-md-3 col-xs-4">
                <span class="product-image media-middle">
                  <?php if ($_smarty_tpl->tpl_vars['product']->value['default_image']) {?>
                    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['cart_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" loading="lazy">
                  <?php } else { ?>
                    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['no_picture_image']['bySize']['cart_default']['url'], ENT_QUOTES, 'UTF-8');?>
" loading="lazy" />
                  <?php }?>
                </span>
              </div>

              <!--  product left body: description -->
              <div class="product-line-grid-body col-md-3 col-xs-8">
                <div class="product-line-info">
                  <a class="label" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a>
                </div>

                <div class="product-line-info">
                  <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</span>
                  <?php if ($_smarty_tpl->tpl_vars['product']->value['unit_price_full']) {?>
                    <div class="unit-price-cart"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['unit_price_full'], ENT_QUOTES, 'UTF-8');?>
</div>
                  <?php }?>
                </div>

                <br/>

                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['attributes'], 'value', false, 'attribute');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['attribute']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
                  <div class="product-line-info">
                    <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attribute']->value, ENT_QUOTES, 'UTF-8');?>
:</span>
                    <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8');?>
</span>
                  </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                <?php if (count($_smarty_tpl->tpl_vars['product']->value['customizations'])) {?>
                  <br/>
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['customizations'], 'customization');
$_smarty_tpl->tpl_vars['customization']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['customization']->value) {
$_smarty_tpl->tpl_vars['customization']->do_else = false;
?>
                    <a href="#" data-toggle="modal" data-target="#product-customizations-modal-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['customization']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product customization','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
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
              </div>

              <!--  product left body: description -->
              <div class="product-line-grid-right product-line-actions col-md-6 col-xs-12">
                <div class="row">
                  <div class="col-xs-4 hidden-md-up"></div>
                  <div class="col-md-10 col-xs-6">
                    <div class="row">
                      <div class="col-md-7 col-xs-6 qty">
                        <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['is_gift'])) && $_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                          <span class="gift-quantity"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['quantity'], ENT_QUOTES, 'UTF-8');?>
</span>
                        <?php } else { ?>
                          <input
                            class="js-cart-line-product-quantity"
                            data-down-url="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['down_quantity_url'], ENT_QUOTES, 'UTF-8');?>
"
                            data-up-url="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['up_quantity_url'], ENT_QUOTES, 'UTF-8');?>
"
                            data-update-url="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['update_quantity_url'], ENT_QUOTES, 'UTF-8');?>
"
                            data-product-id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
"
                            type="text"
                            value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['quantity'], ENT_QUOTES, 'UTF-8');?>
"
                            name="product-quantity-spin"
                            min="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['minimal_quantity'], ENT_QUOTES, 'UTF-8');?>
"
                          />
                        <?php }?>
                      </div>
                      <div class="col-md-5 col-xs-2 price">
                        <span class="product-price">
                          <strong>
                            <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['is_gift'])) && $_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                              <span class="gift"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gift','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
                            <?php } else { ?>
                              <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['total'], ENT_QUOTES, 'UTF-8');?>

                            <?php }?>
                          </strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-xs-2 text-xs-right">
                    <div class="cart-line-product-actions ">
                      <a
                          class                       = "remove-from-cart"
                          rel                         = "nofollow"
                          href                        = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['remove_from_cart_url'], ENT_QUOTES, 'UTF-8');?>
"
                          data-link-action            = "delete-from-cart"
                          data-id-product             = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
"
                          data-id-product-attribute   = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], ENT_QUOTES, 'UTF-8');?>
"
                          data-id-customization       = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
"
                      >
                        <?php if (!(isset($_smarty_tpl->tpl_vars['product']->value['is_gift'])) || !$_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                        <i class="material-icons pull-xs-left">delete</i>
                        <?php }?>
                      </a>
                      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayCartExtraProductActions','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                    </div>
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>
            </div>
          </li>
        <?php }?>
        <?php if (count($_smarty_tpl->tpl_vars['product']->value['customizations']) > 1) {?>
        <hr>
        <?php }?>
      <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </ul>
    <?php } else { ?>
      <span class="no-items"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'There are no more items in your cart','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
    <?php }?>
  </div>
<?php
}
}
/* {/block 'cart_overview'} */
}
