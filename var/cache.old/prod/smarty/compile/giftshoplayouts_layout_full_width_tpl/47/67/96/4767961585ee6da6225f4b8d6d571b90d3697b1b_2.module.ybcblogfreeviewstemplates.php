<?php
/* Smarty version 3.1.43, created on 2024-01-16 16:04:00
  from 'module:ybcblogfreeviewstemplates' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6e13020e6a3_53102914',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4767961585ee6da6225f4b8d6d571b90d3697b1b' => 
    array (
      0 => 'module:ybcblogfreeviewstemplates',
      1 => 1697325107,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
    'file:_partials/head.tpl' => 1,
    'file:catalog/_partials/product-activation.tpl' => 1,
    'file:_partials/header.tpl' => 1,
    'file:_partials/notifications.tpl' => 1,
    'file:_partials/breadcrumb.tpl' => 1,
    'file:_partials/footer.tpl' => 1,
    'file:_partials/javascript.tpl' => 1,
  ),
),false)) {
function content_65a6e13020e6a3_53102914 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['language']->value['iso_code'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">

  <head>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192248459565a6e1301c32f0_42625423', 'head');
?>

  </head>

  <body id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['page_name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'classnames' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['body_classes'] )), ENT_QUOTES, 'UTF-8');?>
 <?php if ((isset($_smarty_tpl->tpl_vars['YBC_TC_CLASSES']->value))) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['YBC_TC_CLASSES']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}
if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_REVIEW'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_REVIEW'] != 1) {?> hidden_review<?php }?>">

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayAfterBodyOpeningTag'),$_smarty_tpl ) );?>


    <main>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_136228254365a6e1301ce860_17596403', 'product_activation');
?>

      <header id="header">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_96354948165a6e1301cf325_34690283', 'header');
?>

      </header>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_199110993465a6e1301cfc14_22955895', 'notifications');
?>

      <section id="wrapper">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_92304543165a6e1301d0458_16115031', 'breadcrumb');
?>

        <div class="container">
          <div class="row">
              <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'left') {?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_38976707765a6e1301d3ec4_19956050', "left_column");
?>

              <?php }?>  
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_152238290665a6e1301d4d35_60439097', "content_wrapper");
?>

              <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'right') {?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_111117882565a6e13020bd91_65401192', "right_column");
?>

              <?php }?>
          </div>
        </div>
      </section>

      <footer id="footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_45786327965a6e13020ca83_01272996', "footer");
?>

      </footer>

    </main>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_88929978265a6e13020d574_86997715', 'javascript_bottom');
?>


    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayBeforeBodyClosingTag'),$_smarty_tpl ) );?>


  </body>

</html><?php }
/* {block 'head'} */
class Block_192248459565a6e1301c32f0_42625423 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_192248459565a6e1301c32f0_42625423',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php $_smarty_tpl->_subTemplateRender('file:_partials/head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'head'} */
/* {block 'product_activation'} */
class Block_136228254365a6e1301ce860_17596403 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_activation' => 
  array (
    0 => 'Block_136228254365a6e1301ce860_17596403',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-activation.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
      <?php
}
}
/* {/block 'product_activation'} */
/* {block 'header'} */
class Block_96354948165a6e1301cf325_34690283 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_96354948165a6e1301cf325_34690283',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->_subTemplateRender('file:_partials/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block 'header'} */
/* {block 'notifications'} */
class Block_199110993465a6e1301cfc14_22955895 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'notifications' => 
  array (
    0 => 'Block_199110993465a6e1301cfc14_22955895',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:_partials/notifications.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
      <?php
}
}
/* {/block 'notifications'} */
/* {block 'breadcrumb'} */
class Block_92304543165a6e1301d0458_16115031 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_92304543165a6e1301d0458_16115031',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:_partials/breadcrumb.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
          <?php
}
}
/* {/block 'breadcrumb'} */
/* {block "left_column"} */
class Block_38976707765a6e1301d3ec4_19956050 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'left_column' => 
  array (
    0 => 'Block_38976707765a6e1301d3ec4_19956050',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <div id="left-column" class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"blogSidebar"),$_smarty_tpl ) );?>

                </div>
              <?php
}
}
/* {/block "left_column"} */
/* {block "content"} */
class Block_48689132365a6e1301d6864_30093679 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <div class="ybc_blog_free_layout_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_layout']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 ybc-blog-wrapper ybc-blog-wrapper-blog-list <?php if ($_smarty_tpl->tpl_vars['blog_latest']->value) {?>ybc-page-latest<?php } elseif ($_smarty_tpl->tpl_vars['blog_category']->value) {?>ybc-page-category<?php } elseif ($_smarty_tpl->tpl_vars['blog_tag']->value) {?>ybc-page-tag<?php } elseif ($_smarty_tpl->tpl_vars['blog_search']->value) {?>ybc-page-search<?php } elseif ($_smarty_tpl->tpl_vars['author']->value) {?>ybc-page-author<?php } else { ?>ybc-page-home<?php }?>">
                        <?php if ($_smarty_tpl->tpl_vars['is_main_page']->value) {?>
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'blogSlidersBlock'),$_smarty_tpl ) );?>

                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['blog_category']->value) {?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['blog_category']->value['enabled'])) && $_smarty_tpl->tpl_vars['blog_category']->value['enabled']) {?>
                                <div class="blog-category <?php if ($_smarty_tpl->tpl_vars['blog_category']->value['image']) {?>has-blog-image<?php }?>">
                                    <?php if ($_smarty_tpl->tpl_vars['blog_category']->value['image']) {?>
                                        <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_dir']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
views/img/category/<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_category']->value['image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_category']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_category']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                                    <?php }?>
                                    <h1 class="page-heading product-listing"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_category']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</h1>            
                                    <?php if ($_smarty_tpl->tpl_vars['blog_category']->value['description']) {?>
                                        <div class="blog-category-desc">
                                            <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_category']->value['description'] ));?>

                                        </div>
                                    <?php }?>
                                </div>
                            <?php } else { ?>
                                <p class="alert alert-warning"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This category is not available','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</p>
                            <?php }?>
                        <?php } elseif ($_smarty_tpl->tpl_vars['blog_latest']->value) {?>
                           <h1 class="page-heading product-listing"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Latest posts','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</h1>
                        <?php } elseif ($_smarty_tpl->tpl_vars['blog_tag']->value) {?>
                            <h1 class="page-heading product-listing"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tag: ','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
"<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( ucfirst($_smarty_tpl->tpl_vars['blog_tag']->value),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"</h1>
                        <?php } elseif ($_smarty_tpl->tpl_vars['blog_search']->value) {?>
                            <h1 class="page-heading product-listing"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search: ','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
"<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( ucfirst($_smarty_tpl->tpl_vars['blog_search']->value),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"</h1>
                        <?php } elseif ($_smarty_tpl->tpl_vars['author']->value) {?>
                            <h1 class="page-heading product-listing"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Author: ','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
"<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['author']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"</h1>
                        <?php }?>
                        
                        <?php if (!($_smarty_tpl->tpl_vars['blog_category']->value && (!(isset($_smarty_tpl->tpl_vars['blog_category']->value['enabled'])) || (isset($_smarty_tpl->tpl_vars['blog_category']->value['enabled'])) && !$_smarty_tpl->tpl_vars['blog_category']->value['enabled'])) && ($_smarty_tpl->tpl_vars['blog_category']->value || $_smarty_tpl->tpl_vars['blog_tag']->value || $_smarty_tpl->tpl_vars['blog_search']->value || $_smarty_tpl->tpl_vars['author']->value || $_smarty_tpl->tpl_vars['is_main_page']->value || $_smarty_tpl->tpl_vars['blog_latest']->value)) {?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['blog_posts']->value)) && $_smarty_tpl->tpl_vars['blog_posts']->value) {?>
                                <ul class="ybc-blog-list row <?php if ($_smarty_tpl->tpl_vars['is_main_page']->value) {?>blog-main-page<?php }?>">
                                    <?php $_smarty_tpl->_assignInScope('first_post', true);?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['blog_posts']->value, 'post');
$_smarty_tpl->tpl_vars['post']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['post']->value) {
$_smarty_tpl->tpl_vars['post']->do_else = false;
?>            
                                        <li>                         
                                            <div class="post-wrapper">
                                                <?php if ($_smarty_tpl->tpl_vars['is_main_page']->value && $_smarty_tpl->tpl_vars['first_post']->value && ($_smarty_tpl->tpl_vars['blog_layout']->value == 'large_list' || $_smarty_tpl->tpl_vars['blog_layout']->value == 'large_grid')) {?>
                                                    <?php if ($_smarty_tpl->tpl_vars['post']->value['image']) {?>
                                                        <a class="ybc_item_img" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                            <img title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                                                        </a>                              
                                                    <?php } elseif ($_smarty_tpl->tpl_vars['post']->value['thumb']) {?>
                                                        <a class="ybc_item_img" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                            <img title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['thumb'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                                                        </a>
                                                    <?php }?>
                                                    <?php $_smarty_tpl->_assignInScope('first_post', false);?>
                                                <?php } elseif ($_smarty_tpl->tpl_vars['post']->value['thumb']) {?>
                                                    <a class="ybc_item_img" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                                        <img title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['thumb'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                                                    </a>
                                                <?php }?>
                                                <div class="ybc-blog-wrapper-content">
                                                <div class="ybc-blog-wrapper-content-main">
                                                    <a class="ybc_title_block" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a>
                                                    <?php if ($_smarty_tpl->tpl_vars['show_date']->value || $_smarty_tpl->tpl_vars['show_categories']->value && $_smarty_tpl->tpl_vars['post']->value['categories']) {?>
                                                        <div class="ybc-blog-sidear-post-meta"> 
                                                            <?php if (!$_smarty_tpl->tpl_vars['date_format']->value) {
$_smarty_tpl->_assignInScope('date_format', 'F jS Y');
}?>
                                                            <?php if ($_smarty_tpl->tpl_vars['show_categories']->value && $_smarty_tpl->tpl_vars['post']->value['categories']) {?>
                                                                <div class="ybc-blog-categories">
                                                                    <?php $_smarty_tpl->_assignInScope('ik', 0);?>
                                                                    <?php $_smarty_tpl->_assignInScope('totalCat', count($_smarty_tpl->tpl_vars['post']->value['categories']));?>
                                                                    <span class="be-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Posted in','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
: </span>
                                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['post']->value['categories'], 'cat');
$_smarty_tpl->tpl_vars['cat']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->do_else = false;
?>
                                                                        <?php $_smarty_tpl->_assignInScope('ik', $_smarty_tpl->tpl_vars['ik']->value+1);?>                                        
                                                                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['cat']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( ucfirst($_smarty_tpl->tpl_vars['cat']->value['title']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a><?php if ($_smarty_tpl->tpl_vars['ik']->value < $_smarty_tpl->tpl_vars['totalCat']->value) {?>, <?php }?>
                                                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                                </div>
                                                            <?php }?>
                                                            <?php if ($_smarty_tpl->tpl_vars['show_date']->value) {?>                                
                                                                <span class="post-date"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Posted on','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
 <span><?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_DATE_FORMAT'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_DATE_FORMAT']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( date($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_DATE_FORMAT'],strtotime($_smarty_tpl->tpl_vars['post']->value['datetime_added'])),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( date('F jS Y',strtotime($_smarty_tpl->tpl_vars['post']->value['datetime_added'])),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?></span></span>                              
                                                            <?php }?> 
                                                        </div> 
                                                    <?php }?>
                                                    <div class="ybc-blog-latest-toolbar">	
                    									<?php if ($_smarty_tpl->tpl_vars['show_views']->value) {?>                    
                                                                <span class="ybc-blog-latest-toolbar-views" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Page views','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
">
                                                                    <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['post']->value['click_number']), ENT_QUOTES, 'UTF-8');?>

                                                                    <?php if ($_smarty_tpl->tpl_vars['post']->value['click_number'] != 1) {?><span>
                                                                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Views','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</span>
                                                                    <?php } else { ?>
                                                                        <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</span>
                                                                    <?php }?>
                                                                </span>
                                                        <?php }?> 
                                                        <?php if ($_smarty_tpl->tpl_vars['allow_rating']->value) {?>
                                                            <?php if ($_smarty_tpl->tpl_vars['post']->value['total_review']) {?>
                                                                <span title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Comments','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
" class="blog__rating_reviews">
                                                                     <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['post']->value['total_review']), ENT_QUOTES, 'UTF-8');?>

                                                                </span>
                                                            <?php }?>
                                                        <?php }?>
                                                        <?php if ($_smarty_tpl->tpl_vars['allow_like']->value) {?>
                                                            <span title="<?php if ($_smarty_tpl->tpl_vars['post']->value['liked']) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Liked','mod'=>'ybc_blog_free'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Like this post','mod'=>'ybc_blog_free'),$_smarty_tpl ) );
}?>" class="item ybc-blog-like-span ybc-blog-like-span-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['id_post'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 <?php if ($_smarty_tpl->tpl_vars['post']->value['liked']) {?>active<?php }?>"  data-id-post="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['id_post'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">                        
                                                                <span class="blog-post-total-like ben_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['id_post'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['likes'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                                                <span class="blog-post-like-text blog-post-like-text-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['id_post'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Liked','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</span></span>
                                                            </span> 
                                                        <?php }?>                     
                                                        <?php if ($_smarty_tpl->tpl_vars['allow_rating']->value && (isset($_smarty_tpl->tpl_vars['post']->value['everage_rating'])) && $_smarty_tpl->tpl_vars['post']->value['everage_rating']) {?>
                                                            <?php $_smarty_tpl->_assignInScope('everage_rating', $_smarty_tpl->tpl_vars['post']->value['everage_rating']);?>
                                                            <div class="blog-extra-item be-rating-block item">
                                                                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Rating: ','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</span>
                                                                <div class="blog_rating_wrapper">
                                                                    <div class="ybc_blog_free_review" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Everage rating','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
">
                                                                        <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['everage_rating']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['everage_rating']->value)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                                                            <div class="star star_on"></div>
                                                                        <?php }
}
?>
                                                                        <?php if ($_smarty_tpl->tpl_vars['everage_rating']->value < 5) {?>
                                                                            <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 5+1 - ($_smarty_tpl->tpl_vars['everage_rating']->value+1) : $_smarty_tpl->tpl_vars['everage_rating']->value+1-(5)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['everage_rating']->value+1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                                                                <div class="star"></div>
                                                                            <?php }
}
?>
                                                                        <?php }?>
                                                                        <span  class="ybc-blog-rating-value"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( number_format((float)$_smarty_tpl->tpl_vars['everage_rating']->value,1,'.',''),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }?>   
                                                    </div>
                                                    <div class="blog_description">
                                                        <?php if ($_smarty_tpl->tpl_vars['post']->value['short_description']) {?>
                                                            <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( strip_tags($_smarty_tpl->tpl_vars['post']->value['short_description']),500,'...' )),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                                        <?php } elseif ($_smarty_tpl->tpl_vars['post']->value['description']) {?>
                                                            <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( strip_tags($_smarty_tpl->tpl_vars['post']->value['description']),500,'...' )),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                                        <?php }?>                                
                                                    </div>
                                                    <a class="read_more" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['post']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Read More','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</a>
                                                  </div>
                                                </div>
                                            </div>
                                            
                                        </li>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </ul>
                                <?php if ($_smarty_tpl->tpl_vars['blog_paggination']->value) {?>
                                    <div class="blog-paggination">
                                        <?php echo $_smarty_tpl->tpl_vars['blog_paggination']->value;?>

                                    </div>
                                <?php }?>
                            <?php } else { ?>
                                <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No posts found','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</p>
                            <?php }?>
                        <?php }?>
                    </div>                
                  <?php
}
}
/* {/block "content"} */
/* {block "content_wrapper"} */
class Block_152238290665a6e1301d4d35_60439097 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content_wrapper' => 
  array (
    0 => 'Block_152238290665a6e1301d4d35_60439097',
  ),
  'content' => 
  array (
    0 => 'Block_48689132365a6e1301d6864_30093679',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <div id="content-wrapper" class="<?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'left') {?>left-column col-xs-12 col-sm-8 col-md-8 col-lg-9<?php } elseif ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'right') {?>right-column col-xs-12 col-sm-8 col-md-8 col-lg-9<?php }?>">
                  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_48689132365a6e1301d6864_30093679', "content", $this->tplIndex);
?>

                </div>
              <?php
}
}
/* {/block "content_wrapper"} */
/* {block "right_column"} */
class Block_111117882565a6e13020bd91_65401192 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'right_column' => 
  array (
    0 => 'Block_111117882565a6e13020bd91_65401192',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <div id="right-column" class="col-xs-12 col-sm-4 col-md-3">
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"blogSidebar"),$_smarty_tpl ) );?>

                </div>
              <?php
}
}
/* {/block "right_column"} */
/* {block "footer"} */
class Block_45786327965a6e13020ca83_01272996 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_45786327965a6e13020ca83_01272996',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->_subTemplateRender("file:_partials/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block "footer"} */
/* {block 'javascript_bottom'} */
class Block_88929978265a6e13020d574_86997715 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'javascript_bottom' => 
  array (
    0 => 'Block_88929978265a6e13020d574_86997715',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php $_smarty_tpl->_subTemplateRender("file:_partials/javascript.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('javascript'=>$_smarty_tpl->tpl_vars['javascript']->value['bottom']), 0, false);
?>
    <?php
}
}
/* {/block 'javascript_bottom'} */
}
