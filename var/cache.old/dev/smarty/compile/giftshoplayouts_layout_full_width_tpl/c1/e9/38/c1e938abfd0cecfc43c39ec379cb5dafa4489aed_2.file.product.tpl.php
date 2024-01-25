<?php
/* Smarty version 3.1.43, created on 2023-12-13 09:40:11
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579b43b79ab52_60323949',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c1e938abfd0cecfc43c39ec379cb5dafa4489aed' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/catalog/product.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/product-cover-thumbnails.tpl' => 1,
    'file:catalog/_partials/product-prices.tpl' => 1,
    'file:catalog/_partials/product-customization.tpl' => 1,
    'file:catalog/_partials/product-variants.tpl' => 1,
    'file:catalog/_partials/miniatures/pack-product.tpl' => 1,
    'file:catalog/_partials/product-discounts.tpl' => 1,
    'file:catalog/_partials/product-add-to-cart.tpl' => 1,
    'file:catalog/_partials/product-details.tpl' => 1,
    'file:catalog/_partials/miniatures/product.tpl' => 1,
    'file:catalog/_partials/product-images-modal.tpl' => 1,
  ),
),false)) {
function content_6579b43b79ab52_60323949 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13636271026579b43b76d545_42719313', 'head_seo');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7371411916579b43b76e867_37877250', 'head');
?>




<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8887284966579b43b7755e9_66357124', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'head_seo'} */
class Block_13636271026579b43b76d545_42719313 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head_seo' => 
  array (
    0 => 'Block_13636271026579b43b76d545_42719313',
  ),
);
public $prepend = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <link rel="canonical" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['canonical_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
<?php
}
}
/* {/block 'head_seo'} */
/* {block 'head'} */
class Block_7371411916579b43b76e867_37877250 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_7371411916579b43b76e867_37877250',
  ),
);
public $append = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <meta property="og:type" content="product">
  <meta property="og:url" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['current_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:title" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['meta']['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:site_name" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['shop']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:description" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['meta']['description'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:image" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['large']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:pretax_price:amount" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['price_tax_exc'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:pretax_price:currency" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value['iso_code'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:price:amount" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['price_amount'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:price:currency" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['currency']->value['iso_code'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['weight'])) && ($_smarty_tpl->tpl_vars['product']->value['weight'] != 0)) {?>
  <meta property="product:weight:value" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['weight'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:weight:units" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['weight_unit'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
  <?php }
}
}
/* {/block 'head'} */
/* {block 'product_cover_thumbnails'} */
class Block_20037502326579b43b776f13_39481147 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-cover-thumbnails.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
              <?php
}
}
/* {/block 'product_cover_thumbnails'} */
/* {block 'page_content'} */
class Block_3317503356579b43b776c48_48709859 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20037502326579b43b776f13_39481147', 'product_cover_thumbnails', $this->tplIndex);
?>

            <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_19282505676579b43b776941_58349232 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <div class="page-content" id="content">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3317503356579b43b776c48_48709859', 'page_content', $this->tplIndex);
?>

          </div>
        <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_title'} */
class Block_20762476226579b43b778d55_08612183 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}
}
/* {/block 'page_title'} */
/* {block 'page_header'} */
class Block_20367148986579b43b778a94_94039791 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <h1 class="h1 page-heading-product" itemprop="name"><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20762476226579b43b778d55_08612183', 'page_title', $this->tplIndex);
?>
</h1>
            <?php
}
}
/* {/block 'page_header'} */
/* {block 'page_header_container'} */
class Block_9329842586579b43b778788_32957768 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20367148986579b43b778a94_94039791', 'page_header', $this->tplIndex);
?>

          <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'product_prices'} */
class Block_934334756579b43b779df1_26747048 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-prices.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
          <?php
}
}
/* {/block 'product_prices'} */
/* {block 'product_description_short'} */
class Block_18221216936579b43b77a615_89790158 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <div id="product-description-short-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" itemprop="description"><?php echo $_smarty_tpl->tpl_vars['product']->value['description_short'];?>
</div>
            <?php
}
}
/* {/block 'product_description_short'} */
/* {block 'product_customization'} */
class Block_2438174206579b43b77c314_12950062 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php $_smarty_tpl->_subTemplateRender("file:catalog/_partials/product-customization.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('customizations'=>$_smarty_tpl->tpl_vars['product']->value['customizations']), 0, false);
?>
              <?php
}
}
/* {/block 'product_customization'} */
/* {block 'product_variants'} */
class Block_15984281326579b43b7801b4_35042277 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-variants.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                  <?php
}
}
/* {/block 'product_variants'} */
/* {block 'product_miniature'} */
class Block_13194373736579b43b782557_56216399 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/miniatures/pack-product.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product_pack']->value), 0, true);
?>
                          <?php
}
}
/* {/block 'product_miniature'} */
/* {block 'product_pack'} */
class Block_31674846579b43b780a08_33446851 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php if ($_smarty_tpl->tpl_vars['packItems']->value) {?>
                      <section class="product-pack">
                        <h3 class="h4"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This pack contains','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</h3>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['packItems']->value, 'product_pack');
$_smarty_tpl->tpl_vars['product_pack']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product_pack']->value) {
$_smarty_tpl->tpl_vars['product_pack']->do_else = false;
?>
                          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13194373736579b43b782557_56216399', 'product_miniature', $this->tplIndex);
?>

                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </section>
                    <?php }?>
                  <?php
}
}
/* {/block 'product_pack'} */
/* {block 'product_discounts'} */
class Block_9462447586579b43b7835b7_25114379 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-discounts.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                  <?php
}
}
/* {/block 'product_discounts'} */
/* {block 'product_add_to_cart'} */
class Block_21177867506579b43b783de4_11519175 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-add-to-cart.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                  <?php
}
}
/* {/block 'product_add_to_cart'} */
/* {block 'product_refresh'} */
class Block_19913204166579b43b784da1_60600917 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Refresh','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
">
                  <?php
}
}
/* {/block 'product_refresh'} */
/* {block 'product_buy'} */
class Block_12713935656579b43b77dfe5_59974105 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <form action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['cart'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['static_token']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                  <input type="hidden" name="id_product" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_customization'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" id="product_customization_id">

                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15984281326579b43b7801b4_35042277', 'product_variants', $this->tplIndex);
?>


                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_31674846579b43b780a08_33446851', 'product_pack', $this->tplIndex);
?>


                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9462447586579b43b7835b7_25114379', 'product_discounts', $this->tplIndex);
?>

                    <div class="line clearfix"></div>
                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21177867506579b43b783de4_11519175', 'product_add_to_cart', $this->tplIndex);
?>

                   
                   <div class="product-additional-info">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductButtons','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                    
                  </div> 
                  
                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19913204166579b43b784da1_60600917', 'product_refresh', $this->tplIndex);
?>

                </form>
              <?php
}
}
/* {/block 'product_buy'} */
/* {block 'product_description'} */
class Block_8124541376579b43b78b827_74846963 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

               <div class="product-description"><?php echo $_smarty_tpl->tpl_vars['product']->value['description'];?>
</div>
             <?php
}
}
/* {/block 'product_description'} */
/* {block 'product_details'} */
class Block_9835139606579b43b78c225_90898591 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

             <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-details.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
           <?php
}
}
/* {/block 'product_details'} */
/* {block 'product_attachments'} */
class Block_14199643186579b43b78ca63_91334506 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

             <?php if ($_smarty_tpl->tpl_vars['product']->value['attachments']) {?>
              <div class="tab-pane fade in" id="attachments">
                 <section class="product-attachments">
                   <h3 class="h5 text-uppercase"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</h3>
                   <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['attachments'], 'attachment');
$_smarty_tpl->tpl_vars['attachment']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['attachment']->value) {
$_smarty_tpl->tpl_vars['attachment']->do_else = false;
?>
                     <div class="attachment">
                       <h4><a href="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['url'][0], array( array('entity'=>'attachment','params'=>array('id_attachment'=>$_smarty_tpl->tpl_vars['attachment']->value['id_attachment'])),$_smarty_tpl ) );?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['attachment']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a></h4>
                       <p><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['attachment']->value['description'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</p
                       <a href="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['url'][0], array( array('entity'=>'attachment','params'=>array('id_attachment'=>$_smarty_tpl->tpl_vars['attachment']->value['id_attachment'])),$_smarty_tpl ) );?>
">
                         <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
 (<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['attachment']->value['file_size_formatted'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
)
                       </a>
                     </div>
                   <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                 </section>
               </div>
             <?php }?>
           <?php
}
}
/* {/block 'product_attachments'} */
/* {block 'product_miniature'} */
class Block_8893074276579b43b795b56_95190166 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/miniatures/product.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product_accessory']->value), 0, true);
?>
              <?php
}
}
/* {/block 'product_miniature'} */
/* {block 'product_accessories'} */
class Block_21288150766579b43b7948f4_25656578 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php if ($_smarty_tpl->tpl_vars['accessories']->value) {?>
        <section class="product-accessories col-md-12 col-xs-12">
          <h3 class="h1 products-section-title text-uppercase">
            <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You might also like','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</span></h3>
          <div class="products categoryproducts_content">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['accessories']->value, 'product_accessory');
$_smarty_tpl->tpl_vars['product_accessory']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product_accessory']->value) {
$_smarty_tpl->tpl_vars['product_accessory']->do_else = false;
?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8893074276579b43b795b56_95190166', 'product_miniature', $this->tplIndex);
?>

            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </div>
        </section>
      <?php }?>
    <?php
}
}
/* {/block 'product_accessories'} */
/* {block 'product_footer'} */
class Block_17893851636579b43b796aa9_40311768 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterProduct','product'=>$_smarty_tpl->tpl_vars['product']->value,'category'=>$_smarty_tpl->tpl_vars['category']->value),$_smarty_tpl ) );?>

    <?php
}
}
/* {/block 'product_footer'} */
/* {block 'product_images_modal'} */
class Block_6018298796579b43b797594_66094719 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-images-modal.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'product_images_modal'} */
/* {block 'page_footer'} */
class Block_14539223816579b43b798087_23493420 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_14626204106579b43b797da6_32221385 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14539223816579b43b798087_23493420', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_8887284966579b43b7755e9_66357124 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_8887284966579b43b7755e9_66357124',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_19282505676579b43b776941_58349232',
  ),
  'page_content' => 
  array (
    0 => 'Block_3317503356579b43b776c48_48709859',
  ),
  'product_cover_thumbnails' => 
  array (
    0 => 'Block_20037502326579b43b776f13_39481147',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_9329842586579b43b778788_32957768',
  ),
  'page_header' => 
  array (
    0 => 'Block_20367148986579b43b778a94_94039791',
  ),
  'page_title' => 
  array (
    0 => 'Block_20762476226579b43b778d55_08612183',
  ),
  'product_prices' => 
  array (
    0 => 'Block_934334756579b43b779df1_26747048',
  ),
  'product_description_short' => 
  array (
    0 => 'Block_18221216936579b43b77a615_89790158',
  ),
  'product_customization' => 
  array (
    0 => 'Block_2438174206579b43b77c314_12950062',
  ),
  'product_buy' => 
  array (
    0 => 'Block_12713935656579b43b77dfe5_59974105',
  ),
  'product_variants' => 
  array (
    0 => 'Block_15984281326579b43b7801b4_35042277',
  ),
  'product_pack' => 
  array (
    0 => 'Block_31674846579b43b780a08_33446851',
  ),
  'product_miniature' => 
  array (
    0 => 'Block_13194373736579b43b782557_56216399',
    1 => 'Block_8893074276579b43b795b56_95190166',
  ),
  'product_discounts' => 
  array (
    0 => 'Block_9462447586579b43b7835b7_25114379',
  ),
  'product_add_to_cart' => 
  array (
    0 => 'Block_21177867506579b43b783de4_11519175',
  ),
  'product_refresh' => 
  array (
    0 => 'Block_19913204166579b43b784da1_60600917',
  ),
  'product_description' => 
  array (
    0 => 'Block_8124541376579b43b78b827_74846963',
  ),
  'product_details' => 
  array (
    0 => 'Block_9835139606579b43b78c225_90898591',
  ),
  'product_attachments' => 
  array (
    0 => 'Block_14199643186579b43b78ca63_91334506',
  ),
  'product_accessories' => 
  array (
    0 => 'Block_21288150766579b43b7948f4_25656578',
  ),
  'product_footer' => 
  array (
    0 => 'Block_17893851636579b43b796aa9_40311768',
  ),
  'product_images_modal' => 
  array (
    0 => 'Block_6018298796579b43b797594_66094719',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_14626204106579b43b797da6_32221385',
  ),
  'page_footer' => 
  array (
    0 => 'Block_14539223816579b43b798087_23493420',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<?php if ($_smarty_tpl->tpl_vars['category']->value->name == 'salud') {?>
    <style>
        .menu_header {
            background-color: rgb(3, 92, 90) !important;
        }
    </style>
<?php }?>


  <div id="main" itemscope itemtype="https://schema.org/Product">
    <meta itemprop="url" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
      <div class="col-md-6 col-xs-12">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19282505676579b43b776941_58349232', 'page_content_container', $this->tplIndex);
?>

        </div>
        <div class="right_pb_product col-md-6 col-xs-12">
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9329842586579b43b778788_32957768', 'page_header_container', $this->tplIndex);
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_934334756579b43b779df1_26747048', 'product_prices', $this->tplIndex);
?>


          <div class="product-information">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18221216936579b43b77a615_89790158', 'product_description_short', $this->tplIndex);
?>


            <?php if ($_smarty_tpl->tpl_vars['product']->value['is_customizable'] && count($_smarty_tpl->tpl_vars['product']->value['customizations']['fields'])) {?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2438174206579b43b77c314_12950062', 'product_customization', $this->tplIndex);
?>

            <?php }?>
            <div class="line clearfix"></div>
            <div class="product-actions">
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12713935656579b43b77dfe5_59974105', 'product_buy', $this->tplIndex);
?>


            </div>

            
        </div>
      </div>
      <div class="tabs col-md-12 col-xs-12">
          <ul class="nav nav-tabs">
            <?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?>
            <li class="nav-item">
              <a class="nav-link<?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" data-toggle="tab" href="#description">
                
                <?php if ($_smarty_tpl->tpl_vars['product']->value['category'] == 'salud') {?>
             
                
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Biografía','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

                  
                <?php } else { ?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Description','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

                <?php }?>
              


                
                
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
              <a class="nav-link<?php if (!$_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" data-toggle="tab" href="#product-details">
                <?php if ($_smarty_tpl->tpl_vars['product']->value['category'] == 'salud') {?>
             
                
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Detalle del médico','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

                  
                <?php } else { ?>
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Detalle del producto','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

                <?php }?>
                
                
                </a>
            </li>
            <?php if ($_smarty_tpl->tpl_vars['product']->value['attachments']) {?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#attachments"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Attachments','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
            </li>
            <?php }?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['extraContent'], 'extra', false, 'extraKey');
$_smarty_tpl->tpl_vars['extra']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['extraKey']->value => $_smarty_tpl->tpl_vars['extra']->value) {
$_smarty_tpl->tpl_vars['extra']->do_else = false;
?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#extra-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['extraKey']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['extra']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a>
            </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'productTab','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

          </ul>

          <div class="tab-content" id="tab-content">
           <div class="tab-pane fade in<?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" id="description">
             <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8124541376579b43b78b827_74846963', 'product_description', $this->tplIndex);
?>

           </div>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9835139606579b43b78c225_90898591', 'product_details', $this->tplIndex);
?>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14199643186579b43b78ca63_91334506', 'product_attachments', $this->tplIndex);
?>

           <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['extraContent'], 'extra', false, 'extraKey');
$_smarty_tpl->tpl_vars['extra']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['extraKey']->value => $_smarty_tpl->tpl_vars['extra']->value) {
$_smarty_tpl->tpl_vars['extra']->do_else = false;
?>
           <div class="tab-pane fade in <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['extra']->value['attr']['class'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" id="extra-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['extraKey']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['extra']->value['attr'], 'val', false, 'key');
$_smarty_tpl->tpl_vars['val']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->do_else = false;
?> <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['val']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>>
               <?php echo $_smarty_tpl->tpl_vars['extra']->value['content'];?>

           </div>
           <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
           <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'productTabContent','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

        </div>
      </div>
    <div class="clearfix"></div>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21288150766579b43b7948f4_25656578', 'product_accessories', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17893851636579b43b796aa9_40311768', 'product_footer', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6018298796579b43b797594_66094719', 'product_images_modal', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14626204106579b43b797da6_32221385', 'page_footer_container', $this->tplIndex);
?>

  </div>






  <?php if ($_smarty_tpl->tpl_vars['product']->value['category'] == 'salud') {?>
  
 
  
    <?php echo '<script'; ?>
>
    
        //cargar el script una vez que se haya cargado por completo la pagina
                    console.log("cargando script");
                    //que cargue despues de carque todos los estilos css        
                    
                    
     
        //document.body.style.backgroundColor = "red";
        document.querySelector(".menu_header").style.backgroundColor = "#035C5A";
        
        var logos = document.getElementsByClassName("logo");
for (var i = 0; i < logos.length; i++) {
    logos[i].src = "https://inversionesrpz.com/img/logos.png";
}
       
        
     
        //document.querySelector(".logo").style.background-image = "url('./imagenes/Web-2-logos.png');";
        /*
        document.querySelector(".ets_mm_megamenu.scroll_heading").style.backgroundColor = "#035C5A";
        document.querySelector(".footer_top_extra").style.backgroundColor = "#035C5A";
   
                  
         */           
                   
                    setTimeout(function(){
                        document.querySelector(".logo").src = "https://inversionesrpz.com/img/logos.png";
                        //console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 500);
                    
                    setTimeout(function(){
                       // console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 1000);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 2000);
                    
                    setTimeout(function(){
                        //console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 3000);
                    
                    setTimeout(function(){
                        //console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 4000);
                    
                    setTimeout(function(){
                        //console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 5000);
                    
                    setTimeout(function(){
                        //console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 6000);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 7000);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 8000);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 9000);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 10000);
                        setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 1500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 2500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 3500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 4500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 5500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 6500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 7500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 8500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 9500);
                    
                    setTimeout(function(){
                        console.log("cargando script");
                        //cambiar el color del menu
                        $(".menu_header").css("background-color", "#035C5A !important");
                        $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                        $(".footer_top_extra").css("background-color", "#035C5A !important");
                    }, 10500);
                    
    
        
      <?php echo '</script'; ?>
>
    <?php }?>

<?php
}
}
/* {/block 'content'} */
}
