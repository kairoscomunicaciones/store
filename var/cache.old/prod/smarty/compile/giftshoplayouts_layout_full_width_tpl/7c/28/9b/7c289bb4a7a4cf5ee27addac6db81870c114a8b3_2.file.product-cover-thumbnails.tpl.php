<?php
/* Smarty version 3.1.43, created on 2024-01-16 20:08:25
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/_partials/product-cover-thumbnails.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71a79a26e07_42263750',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7c289bb4a7a4cf5ee27addac6db81870c114a8b3' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/catalog/_partials/product-cover-thumbnails.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71a79a26e07_42263750 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
if (((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'] == 'layout2')) {?>
<div class="images-container images-container-vertical-right">
  
  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192027943965a71a799fb037_04247597', 'product_cover');
?>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_96326070565a71a79a02015_75619767', 'product_images');
?>

  
  
</div>

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[
setTimeout(function(){
	$('.product-images').slick({
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  vertical: true,
	  infinite: false,
	  arrows: true,
      centerPadding: '20px',
	  responsive: [
		{
		  breakpoint: 1024,
		  settings: {
			slidesToShow: 4,
		  }
		},
		{
		  breakpoint: 992,
		  settings: {
			slidesToShow: 3,
		  }
		},
		{
		  breakpoint: 768,
		  settings: {
			slidesToShow: 3,
		  }
		},
		{
		  breakpoint: 480,
		  settings: {
			slidesToShow: 2,
		  }
		}]
	});
	},500);	
	
// ]]>
<?php echo '</script'; ?>
>



<?php } elseif (((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'] == 'layout1')) {?>
    <div class="images-container images-container-vertical-left">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_73155763865a71a79a07bf2_30705220', 'product_cover');
?>

    
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_155153321465a71a79a0df90_46406692', 'product_images');
?>

      
    </div>
    
    <?php echo '<script'; ?>
 type="text/javascript">
    // <![CDATA[
    setTimeout(function(){
    	$('.product-images').slick({
    	  slidesToShow: 4,
    	  slidesToScroll: 1,
    	  vertical: true,
    	  infinite: false,
    	  arrows: true,
    	  responsive: [
    		{
    		  breakpoint: 1024,
    		  settings: {
    			slidesToShow: 4,
    		  }
    		},
    		{
    		  breakpoint: 992,
    		  settings: {
    			slidesToShow: 3,
    		  }
    		},
    		{
    		  breakpoint: 768,
    		  settings: {
    			slidesToShow: 3,
    		  }
    		},
    		{
    		  breakpoint: 480,
    		  settings: {
    			slidesToShow: 2,
    		  }
    		}]
    	});
    	},500);	
    	
    // ]]>
    <?php echo '</script'; ?>
>
    
<?php } elseif (((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PRODUCT_LAYOUT'] == 'layout4')) {?>
    <div class="images-container images-container-img-sync">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_105561596965a71a79a12e31_93973756', 'product_images');
?>

    </div>
    
    <?php echo '<script'; ?>
 type="text/javascript">
    // <![CDATA[
    setTimeout(function(){
    	$('.product-images-big').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          fade: true,
          infinite: false,
          asNavFor: '.product-images'
        });
        $('.product-images').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: false,
          asNavFor: '.product-images-big',
          focusOnSelect: true,
          arrows: false,
        });
   	},500);	
    	
    // ]]>
    <?php echo '</script'; ?>
>
    
<?php } else { ?>
<div class="images-container type_horizonal">
  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_43663872665a71a79a1bf22_27784192', 'product_cover');
?>


  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_126337888465a71a79a224d3_38569855', 'product_images');
?>

  
</div>

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[
setTimeout(function(){
	$('.product-images').slick({
	  slidesToShow: 5,
	  slidesToScroll: 1,
	  vertical: false,
	  infinite: false,
	  arrows: true,
	  responsive: [
		{
		  breakpoint: 1024,
		  settings: {
			slidesToShow: 5,
		  }
		},
		{
		  breakpoint: 992,
		  settings: {
			slidesToShow: 4,
		  }
		},
		{
		  breakpoint: 768,
		  settings: {
			slidesToShow: 4,
		  }
		},
		{
		  breakpoint: 480,
		  settings: {
			slidesToShow: 3,
		  }
		}]
	});
	},500);	
    
// ]]>
<?php echo '</script'; ?>
>

<?php }
}
/* {block 'product_flags'} */
class Block_202585541865a71a799fe077_15534742 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <ul class="product-flags">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['flags'], 'flag');
$_smarty_tpl->tpl_vars['flag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['flag']->value) {
$_smarty_tpl->tpl_vars['flag']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'new') {?>
              <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                <span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['label'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
              </li>
            <?php } elseif ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'on-sale') {?>
              <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sale','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
              </li>
            <?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </ul>
      <?php
}
}
/* {/block 'product_flags'} */
/* {block 'product_cover'} */
class Block_192027943965a71a799fb037_04247597 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_cover' => 
  array (
    0 => 'Block_192027943965a71a799fb037_04247597',
  ),
  'product_flags' => 
  array (
    0 => 'Block_202585541865a71a799fe077_15534742',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="product-cover<?php if (((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQZOOM'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQZOOM'] == 1)) {?> product-cover-zoom<?php }?>">
      <img class="js-qv-product-cover" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="width:100%;" itemprop="image">
      <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
        <i class="material-icons zoom-in">zoom_in</i>
      </div>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_202585541865a71a799fe077_15534742', 'product_flags', $this->tplIndex);
?>

    </div>
  <?php
}
}
/* {/block 'product_cover'} */
/* {block 'product_images'} */
class Block_96326070565a71a79a02015_75619767 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_images' => 
  array (
    0 => 'Block_96326070565a71a79a02015_75619767',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="js-qv-mask mask">
      <ul class="product-images js-qv-product-images">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['images'], 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
          <li class="thumb-container">
            <img
              class="thumb js-thumb <?php if ($_smarty_tpl->tpl_vars['image']->value['id_image'] == $_smarty_tpl->tpl_vars['product']->value['cover']['id_image']) {?> selected <?php }?>"
              data-image-medium-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['medium_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              data-image-large-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['home_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              width="100"
              itemprop="image"
            >
          </li>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </ul>
    </div>
  <?php
}
}
/* {/block 'product_images'} */
/* {block 'product_flags'} */
class Block_124346093365a71a79a0a708_70006493 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <ul class="product-flags">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['flags'], 'flag');
$_smarty_tpl->tpl_vars['flag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['flag']->value) {
$_smarty_tpl->tpl_vars['flag']->do_else = false;
?>
                <?php if ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'new') {?>
                  <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                    <span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['label'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                  </li>
                <?php } elseif ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'on-sale') {?>
                  <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                    <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sale','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                  </li>
                <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
          <?php
}
}
/* {/block 'product_flags'} */
/* {block 'product_cover'} */
class Block_73155763865a71a79a07bf2_30705220 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_cover' => 
  array (
    0 => 'Block_73155763865a71a79a07bf2_30705220',
  ),
  'product_flags' => 
  array (
    0 => 'Block_124346093365a71a79a0a708_70006493',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="product-cover<?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQUERYZOOM'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQUERYZOOM'] == 1) {?> product-cover-zoom<?php }?>">
          <img class="js-qv-product-cover" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="width:100%;" itemprop="image">
          <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
            <i class="material-icons zoom-in">zoom_in</i>
          </div>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_124346093365a71a79a0a708_70006493', 'product_flags', $this->tplIndex);
?>

        </div>
      <?php
}
}
/* {/block 'product_cover'} */
/* {block 'product_images'} */
class Block_155153321465a71a79a0df90_46406692 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_images' => 
  array (
    0 => 'Block_155153321465a71a79a0df90_46406692',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="js-qv-mask mask">
          <ul class="product-images js-qv-product-images">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['images'], 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
              <li class="thumb-container">
                <img
                  class="thumb js-thumb <?php if ($_smarty_tpl->tpl_vars['image']->value['id_image'] == $_smarty_tpl->tpl_vars['product']->value['cover']['id_image']) {?> selected <?php }?>"
                  data-image-medium-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['medium_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  data-image-large-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['home_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  width="100"
                  itemprop="image"
                >
              </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </ul>
        </div>
      <?php
}
}
/* {/block 'product_images'} */
/* {block 'product_images'} */
class Block_105561596965a71a79a12e31_93973756 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_images' => 
  array (
    0 => 'Block_105561596965a71a79a12e31_93973756',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="js-qv-mask mask">
          <ul class="product-images-big js-qv-product-images">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['images'], 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
              <li class="thumb-container<?php if (((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQZOOM'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQZOOM'] == 1)) {?> product-cover-zoom<?php }?>">
                <img
                  class="thumb js-thumb <?php if ($_smarty_tpl->tpl_vars['image']->value['id_image'] == $_smarty_tpl->tpl_vars['product']->value['cover']['id_image']) {?> selected <?php }?>"
                  data-image-medium-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['medium_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  data-image-large-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  itemprop="image"
                >
              </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </ul>
          <ul class="product-images js-qv-product-images">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['images'], 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
              <li class="thumb-container">
                <img
                  class="thumb js-thumb <?php if ($_smarty_tpl->tpl_vars['image']->value['id_image'] == $_smarty_tpl->tpl_vars['product']->value['cover']['id_image']) {?> selected <?php }?>"
                  data-image-medium-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['medium_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  data-image-large-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['home_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                  width="100"
                  itemprop="image"
                >
              </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </ul>
        </div>
      <?php
}
}
/* {/block 'product_images'} */
/* {block 'product_flags'} */
class Block_60772708765a71a79a1ea87_19882949 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <ul class="product-flags">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['flags'], 'flag');
$_smarty_tpl->tpl_vars['flag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['flag']->value) {
$_smarty_tpl->tpl_vars['flag']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'new') {?>
              <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                <span><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['label'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
              </li>
            <?php } elseif ($_smarty_tpl->tpl_vars['flag']->value['type'] == 'on-sale') {?>
              <li class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['flag']->value['type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sale','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
              </li>
            <?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </ul>
      <?php
}
}
/* {/block 'product_flags'} */
/* {block 'product_cover'} */
class Block_43663872665a71a79a1bf22_27784192 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_cover' => 
  array (
    0 => 'Block_43663872665a71a79a1bf22_27784192',
  ),
  'product_flags' => 
  array (
    0 => 'Block_60772708765a71a79a1ea87_19882949',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="product-cover<?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQUERYZOOM'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_JQUERYZOOM'] == 1) {?> product-cover-zoom<?php }?>">
      <img class="js-qv-product-cover" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['cover']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="width:100%;" itemprop="image">
      <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
        <i class="material-icons zoom-in">zoom_in</i>
      </div>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_60772708765a71a79a1ea87_19882949', 'product_flags', $this->tplIndex);
?>

    </div>
  <?php
}
}
/* {/block 'product_cover'} */
/* {block 'product_images'} */
class Block_126337888465a71a79a224d3_38569855 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_images' => 
  array (
    0 => 'Block_126337888465a71a79a224d3_38569855',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="js-qv-mask mask">
      <ul class="product-images js-qv-product-images">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['images'], 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
          <li class="thumb-container">
            <img
              class="thumb js-thumb <?php if ($_smarty_tpl->tpl_vars['image']->value['id_image'] == $_smarty_tpl->tpl_vars['product']->value['cover']['id_image']) {?> selected <?php }?>"
              data-image-medium-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['medium_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              data-image-large-src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['large_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['bySize']['home_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['image']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
              width="100"
              itemprop="image"
            >
          </li>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </ul>
    </div>
  <?php
}
}
/* {/block 'product_images'} */
}
