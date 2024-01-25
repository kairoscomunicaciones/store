<?php
/* Smarty version 3.1.43, created on 2024-01-15 12:46:42
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/_partials/miniatures/brand.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a56172687ae5_98935113',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a9c332615e4354149f58c8761d0c5a937461bbc8' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/catalog/_partials/miniatures/brand.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a56172687ae5_98935113 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_36298120265a56172682d53_58878104', 'brand');
?>

<?php }
/* {block 'brand'} */
class Block_36298120265a56172682d53_58878104 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'brand' => 
  array (
    0 => 'Block_36298120265a56172682d53_58878104',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <li class="brand col-md-4 col-sm-6 col-xs-12">
    <div class="brand_content_item">
        <div class="brand-img"><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"></a></div>
        <div class="brand-infos">
          <h3><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a></h3>
          <?php echo $_smarty_tpl->tpl_vars['brand']->value['text'];?>

        </div>
        <div class="brand-products">
          <a class="brand-count-products" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['nb_products'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a>
          <a class="brand-view-products" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['brand']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View products','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</a>
        </div>
    </div>
  </li>
<?php
}
}
/* {/block 'brand'} */
}
