<?php
/* Smarty version 3.1.43, created on 2024-01-16 16:04:00
  from '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/search_block.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6e130422076_02392743',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '379b9d11e78107fe396d1647ced1e9c0281e9895' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/ybc_blog_free/views/templates/hook/search_block.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6e130422076_02392743 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="block ybc_block_search <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blog_config']->value['YBC_BLOG_FREE_RTL_CLASS'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
    <h4 class="title_blog title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Blog Search','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
</h4>
    <div class="content_block block_content">
        <form action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['action']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" method="post">
            <input class="form-control" type="text" name="blog_search" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Type in key words...','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['search']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
            <input class="button" type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search','mod'=>'ybc_blog_free'),$_smarty_tpl ) );?>
" />
            <span class="icon_search"></span>
        </form>
    </div>
</div>
<?php }
}
