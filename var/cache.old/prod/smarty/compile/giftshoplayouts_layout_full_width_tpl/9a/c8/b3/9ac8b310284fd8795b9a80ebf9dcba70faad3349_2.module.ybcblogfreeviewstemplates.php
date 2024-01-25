<?php
/* Smarty version 3.1.43, created on 2024-01-10 12:22:31
  from 'module:ybcblogfreeviewstemplates' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_659ec4478900c8_37411140',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9ac8b310284fd8795b9a80ebf9dcba70faad3349' => 
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
function content_659ec4478900c8_37411140 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['language']->value['iso_code'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">

  <head>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1241418347659ec44787cee8_82461653', 'head');
?>

  </head>

  <body id="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['page_name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'classnames' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['body_classes'] )), ENT_QUOTES, 'UTF-8');?>
">

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayAfterBodyOpeningTag'),$_smarty_tpl ) );?>


    <main>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2123628705659ec44787fb14_15865109', 'product_activation');
?>

      <header id="header">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_450271028659ec4478804f8_96041453', 'header');
?>

      </header>
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_986703859659ec447880e70_66530932', 'notifications');
?>

      <section id="wrapper">
        <div class="container">
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2022103964659ec4478817b1_92595551', 'breadcrumb');
?>

          <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'left') {?>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_584659063659ec4478839c4_81181244', "left_column");
?>

          <?php }?>  
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_718171201659ec447884768_02615385', "content_wrapper");
?>

          <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'right') {?>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_791265915659ec44788db75_19452861', "right_column");
?>

          <?php }?>
        </div>
      </section>

      <footer id="footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1244479982659ec44788e710_76779293', "footer");
?>

      </footer>

    </main>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_816246501659ec44788f078_03083872', 'javascript_bottom');
?>


    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayBeforeBodyClosingTag'),$_smarty_tpl ) );?>


  </body>

</html><?php }
/* {block 'head'} */
class Block_1241418347659ec44787cee8_82461653 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_1241418347659ec44787cee8_82461653',
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
class Block_2123628705659ec44787fb14_15865109 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_activation' => 
  array (
    0 => 'Block_2123628705659ec44787fb14_15865109',
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
class Block_450271028659ec4478804f8_96041453 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_450271028659ec4478804f8_96041453',
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
class Block_986703859659ec447880e70_66530932 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'notifications' => 
  array (
    0 => 'Block_986703859659ec447880e70_66530932',
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
class Block_2022103964659ec4478817b1_92595551 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_2022103964659ec4478817b1_92595551',
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
class Block_584659063659ec4478839c4_81181244 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'left_column' => 
  array (
    0 => 'Block_584659063659ec4478839c4_81181244',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
              <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"blogSidebar"),$_smarty_tpl ) );?>

            </div>
          <?php
}
}
/* {/block "left_column"} */
/* {block "content"} */
class Block_208837208659ec4478861c1_23038125 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <div class="ybc_blog_free_layout_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_layout']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 ybc-blog-wrapper ybc-blog-wrapper-gallery">
                    <h1 class="page-heading"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Blog gallery','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</h1>
                    <?php if ((isset($_smarty_tpl->tpl_vars['blog_galleries']->value))) {?>                   
                        <ul class="ybc-gallery">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['blog_galleries']->value, 'gallery');
$_smarty_tpl->tpl_vars['gallery']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['gallery']->value) {
$_smarty_tpl->tpl_vars['gallery']->do_else = false;
?>            
                                <li>
                                    <a class="gallery_item"  <?php if ($_smarty_tpl->tpl_vars['gallery']->value['description']) {?> title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( strip_tags($_smarty_tpl->tpl_vars['gallery']->value['description']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?> rel="prettyPhotoGalleryPage[gallery]" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['thumb'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" /></a>                    
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
                        <p class="alert alert-warning"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No item found','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</p>
                    <?php }?>
                </div>                
              <?php
}
}
/* {/block "content"} */
/* {block "content_wrapper"} */
class Block_718171201659ec447884768_02615385 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content_wrapper' => 
  array (
    0 => 'Block_718171201659ec447884768_02615385',
  ),
  'content' => 
  array (
    0 => 'Block_208837208659ec4478861c1_23038125',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <div id="content-wrapper" class="<?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'left') {?>left-column col-xs-12 col-sm-8 col-md-9<?php } elseif ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_SIDEBAR_POSITION'] == 'right') {?>right-column col-xs-12 col-sm-8 col-md-9<?php }?>">
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_208837208659ec4478861c1_23038125', "content", $this->tplIndex);
?>

            </div>
          <?php
}
}
/* {/block "content_wrapper"} */
/* {block "right_column"} */
class Block_791265915659ec44788db75_19452861 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'right_column' => 
  array (
    0 => 'Block_791265915659ec44788db75_19452861',
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
class Block_1244479982659ec44788e710_76779293 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_1244479982659ec44788e710_76779293',
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
class Block_816246501659ec44788f078_03083872 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'javascript_bottom' => 
  array (
    0 => 'Block_816246501659ec44788f078_03083872',
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
