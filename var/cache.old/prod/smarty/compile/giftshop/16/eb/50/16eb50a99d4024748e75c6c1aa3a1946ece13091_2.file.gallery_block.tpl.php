<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:32:41
  from '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/gallery_block.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71219b53da8_79252897',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '16eb50a99d4024748e75c6c1aa3a1946ece13091' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/gallery_block.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71219b53da8_79252897 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="block <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_RTL_CLASS'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 ybc_block_gallery <?php if ((isset($_smarty_tpl->tpl_vars['page']->value)) && $_smarty_tpl->tpl_vars['page']->value) {?>page_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
_gallery<?php } else { ?>page_blog_gallery<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_BLOCK_HOME_SLIDER_ENABLED'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_BLOCK_HOME_SLIDER_ENABLED'] && $_smarty_tpl->tpl_vars['page']->value == 'home' || (isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_BLOCK_SIDEBAR_SLIDER_ENABLED'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_BLOCK_SIDEBAR_SLIDER_ENABLED'] && $_smarty_tpl->tpl_vars['page']->value != 'home') {?>ybc_block_slider<?php } else { ?>ybc_block_default<?php }?>">
    <h4 class="h1 products-section-title text-uppercase">
        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery_link']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Blog gallery','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>

        </a>
    </h4> 
    <?php if ($_smarty_tpl->tpl_vars['galleries']->value) {?>
        <ul class="block_content">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['galleries']->value, 'gallery');
$_smarty_tpl->tpl_vars['gallery']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['gallery']->value) {
$_smarty_tpl->tpl_vars['gallery']->do_else = false;
?>  
                <li>
                    <?php if ((isset($_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_SLIDESHOW_ENABLED'])) && $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_GALLERY_SLIDESHOW_ENABLED']) {?>
                    <a <?php if ($_smarty_tpl->tpl_vars['gallery']->value['description']) {?>title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( strip_tags($_smarty_tpl->tpl_vars['gallery']->value['description']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?>  rel="prettyPhotoBlock[galleryblock]" class="gallery_block_slider gallery_item" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                        <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['thumb'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  />
                    </a>
                    <?php } else { ?>
                        <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['thumb'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  />
                    <?php }?>   
                    <h3 class="ybc_title_block"><?php if (strlen($_smarty_tpl->tpl_vars['gallery']->value['title']) > 50) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( substr($_smarty_tpl->tpl_vars['gallery']->value['title'],0,49),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
...<?php } else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?></h3>                                           
                </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>            
        </ul>        
    <?php } else { ?>
        <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No featured images','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</p>
    <?php }?>
    <a class="view_all" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gallery_link']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View gallery','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</a>
</div><?php }
}
