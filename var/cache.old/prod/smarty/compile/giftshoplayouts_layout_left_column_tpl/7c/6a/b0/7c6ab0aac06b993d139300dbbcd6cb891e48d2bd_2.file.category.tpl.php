<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:50:05
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/listing/category.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7081d7d9d34_90172024',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7c6ab0aac06b993d139300dbbcd6cb891e48d2bd' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/catalog/listing/category.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7081d7d9d34_90172024 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_173700537865a7081d7d4802_11441055', 'product_list_header');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'catalog/listing/product-list.tpl');
}
/* {block 'product_list_header'} */
class Block_173700537865a7081d7d4802_11441055 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_list_header' => 
  array (
    0 => 'Block_173700537865a7081d7d4802_11441055',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    
    <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_NAME_CAT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_NAME_CAT'] == 1) {?>
        <h1 class="h1 title_category"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['category']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</h1>
    <?php }?>
    <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_IMAGE_BLOCK'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_IMAGE_BLOCK'] == 1 || (isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_DESCRIPTION'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_DESCRIPTION'] == 1) {?>    
        <div class="block-category card card-block">
            <?php if ($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LISTING_IMAGE_BLOCK'] == 1) {?>
                <div class="category-cover">
                  <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['category']->value['image']['large']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['category']->value['image']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                </div>
            <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['category']->value['description']) {?>
            <div id="category-description" class="text-muted"><?php echo $_smarty_tpl->tpl_vars['category']->value['description'];?>
</div>
          <?php }?>
        </div>
    <?php }
}
}
/* {/block 'product_list_header'} */
}
