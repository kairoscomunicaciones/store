<?php
/* Smarty version 3.1.43, created on 2024-01-16 20:08:25
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71a797d9134_57791143',
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
function content_65a71a797d9134_57791143 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_110283624065a71a797ab9e8_20147609', 'head_seo');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_112161678665a71a797ad1c2_89222897', 'head');
?>




<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_33030953965a71a797b3d56_91681540', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'head_seo'} */
class Block_110283624065a71a797ab9e8_20147609 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head_seo' => 
  array (
    0 => 'Block_110283624065a71a797ab9e8_20147609',
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
class Block_112161678665a71a797ad1c2_89222897 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_112161678665a71a797ad1c2_89222897',
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
class Block_191820145965a71a797b5704_92128753 extends Smarty_Internal_Block
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
class Block_103669786665a71a797b5458_05855571 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_191820145965a71a797b5704_92128753', 'product_cover_thumbnails', $this->tplIndex);
?>

            <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_163439673465a71a797b5141_68527006 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <div class="page-content" id="content">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_103669786665a71a797b5458_05855571', 'page_content', $this->tplIndex);
?>

          </div>
        <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_title'} */
class Block_104708327365a71a797b73d7_49671120 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}
}
/* {/block 'page_title'} */
/* {block 'page_header'} */
class Block_60998825365a71a797b70f9_55720796 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <h1 class="h1 page-heading-product" itemprop="name"><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_104708327365a71a797b73d7_49671120', 'page_title', $this->tplIndex);
?>
</h1>
            <?php
}
}
/* {/block 'page_header'} */
/* {block 'page_header_container'} */
class Block_89763120365a71a797b6df9_11725574 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_60998825365a71a797b70f9_55720796', 'page_header', $this->tplIndex);
?>

          <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'product_prices'} */
class Block_172972446565a71a797b84d3_53224622 extends Smarty_Internal_Block
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
class Block_150978536365a71a797b8d59_87802414 extends Smarty_Internal_Block
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
class Block_145969184665a71a797ba935_03253960 extends Smarty_Internal_Block
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
class Block_79894442165a71a797be9d8_18168652 extends Smarty_Internal_Block
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
class Block_75859429765a71a797c0e41_32160045 extends Smarty_Internal_Block
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
class Block_165318096565a71a797bf269_01358144 extends Smarty_Internal_Block
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_75859429765a71a797c0e41_32160045', 'product_miniature', $this->tplIndex);
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
class Block_88788307865a71a797c1e75_10966667 extends Smarty_Internal_Block
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
class Block_139899085965a71a797c26b6_98723542 extends Smarty_Internal_Block
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
class Block_197735098365a71a797c3596_58565569 extends Smarty_Internal_Block
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
class Block_165318931565a71a797bc7a9_52886327 extends Smarty_Internal_Block
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_79894442165a71a797be9d8_18168652', 'product_variants', $this->tplIndex);
?>


                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_165318096565a71a797bf269_01358144', 'product_pack', $this->tplIndex);
?>


                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_88788307865a71a797c1e75_10966667', 'product_discounts', $this->tplIndex);
?>

                    <div class="line clearfix"></div>
                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_139899085965a71a797c26b6_98723542', 'product_add_to_cart', $this->tplIndex);
?>

                   
                   <div class="product-additional-info">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductButtons','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                    
                  </div> 
                  
                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_197735098365a71a797c3596_58565569', 'product_refresh', $this->tplIndex);
?>

                </form>
              <?php
}
}
/* {/block 'product_buy'} */
/* {block 'product_description'} */
class Block_142570646665a71a797c9fa9_44329954 extends Smarty_Internal_Block
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
class Block_73571797165a71a797ca931_33419112 extends Smarty_Internal_Block
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
class Block_109176152265a71a797cb147_68024218 extends Smarty_Internal_Block
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
class Block_14270574165a71a797d4393_71317793 extends Smarty_Internal_Block
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
class Block_104938986365a71a797d3120_15169742 extends Smarty_Internal_Block
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14270574165a71a797d4393_71317793', 'product_miniature', $this->tplIndex);
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
class Block_10249808465a71a797d52f3_72683811 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterProduct','product'=>$_smarty_tpl->tpl_vars['product']->value,'category'=>$_smarty_tpl->tpl_vars['category']->value),$_smarty_tpl ) );?>

    <?php
}
}
/* {/block 'product_footer'} */
/* {block 'product_images_modal'} */
class Block_99456568865a71a797d5df2_86671278 extends Smarty_Internal_Block
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
class Block_143195487965a71a797d68d5_25685916 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_32244499365a71a797d65f7_74996581 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_143195487965a71a797d68d5_25685916', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_33030953965a71a797b3d56_91681540 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_33030953965a71a797b3d56_91681540',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_163439673465a71a797b5141_68527006',
  ),
  'page_content' => 
  array (
    0 => 'Block_103669786665a71a797b5458_05855571',
  ),
  'product_cover_thumbnails' => 
  array (
    0 => 'Block_191820145965a71a797b5704_92128753',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_89763120365a71a797b6df9_11725574',
  ),
  'page_header' => 
  array (
    0 => 'Block_60998825365a71a797b70f9_55720796',
  ),
  'page_title' => 
  array (
    0 => 'Block_104708327365a71a797b73d7_49671120',
  ),
  'product_prices' => 
  array (
    0 => 'Block_172972446565a71a797b84d3_53224622',
  ),
  'product_description_short' => 
  array (
    0 => 'Block_150978536365a71a797b8d59_87802414',
  ),
  'product_customization' => 
  array (
    0 => 'Block_145969184665a71a797ba935_03253960',
  ),
  'product_buy' => 
  array (
    0 => 'Block_165318931565a71a797bc7a9_52886327',
  ),
  'product_variants' => 
  array (
    0 => 'Block_79894442165a71a797be9d8_18168652',
  ),
  'product_pack' => 
  array (
    0 => 'Block_165318096565a71a797bf269_01358144',
  ),
  'product_miniature' => 
  array (
    0 => 'Block_75859429765a71a797c0e41_32160045',
    1 => 'Block_14270574165a71a797d4393_71317793',
  ),
  'product_discounts' => 
  array (
    0 => 'Block_88788307865a71a797c1e75_10966667',
  ),
  'product_add_to_cart' => 
  array (
    0 => 'Block_139899085965a71a797c26b6_98723542',
  ),
  'product_refresh' => 
  array (
    0 => 'Block_197735098365a71a797c3596_58565569',
  ),
  'product_description' => 
  array (
    0 => 'Block_142570646665a71a797c9fa9_44329954',
  ),
  'product_details' => 
  array (
    0 => 'Block_73571797165a71a797ca931_33419112',
  ),
  'product_attachments' => 
  array (
    0 => 'Block_109176152265a71a797cb147_68024218',
  ),
  'product_accessories' => 
  array (
    0 => 'Block_104938986365a71a797d3120_15169742',
  ),
  'product_footer' => 
  array (
    0 => 'Block_10249808465a71a797d52f3_72683811',
  ),
  'product_images_modal' => 
  array (
    0 => 'Block_99456568865a71a797d5df2_86671278',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_32244499365a71a797d65f7_74996581',
  ),
  'page_footer' => 
  array (
    0 => 'Block_143195487965a71a797d68d5_25685916',
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_163439673465a71a797b5141_68527006', 'page_content_container', $this->tplIndex);
?>

        </div>
        <div class="right_pb_product col-md-6 col-xs-12">
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_89763120365a71a797b6df9_11725574', 'page_header_container', $this->tplIndex);
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_172972446565a71a797b84d3_53224622', 'product_prices', $this->tplIndex);
?>


          <div class="product-information">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_150978536365a71a797b8d59_87802414', 'product_description_short', $this->tplIndex);
?>


            <?php if ($_smarty_tpl->tpl_vars['product']->value['is_customizable'] && count($_smarty_tpl->tpl_vars['product']->value['customizations']['fields'])) {?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_145969184665a71a797ba935_03253960', 'product_customization', $this->tplIndex);
?>

            <?php }?>
            <div class="line clearfix"></div>
            <div class="product-actions">
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_165318931565a71a797bc7a9_52886327', 'product_buy', $this->tplIndex);
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_142570646665a71a797c9fa9_44329954', 'product_description', $this->tplIndex);
?>

           </div>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_73571797165a71a797ca931_33419112', 'product_details', $this->tplIndex);
?>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_109176152265a71a797cb147_68024218', 'product_attachments', $this->tplIndex);
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_104938986365a71a797d3120_15169742', 'product_accessories', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10249808465a71a797d52f3_72683811', 'product_footer', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_99456568865a71a797d5df2_86671278', 'product_images_modal', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_32244499365a71a797d65f7_74996581', 'page_footer_container', $this->tplIndex);
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
