<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:14:36
  from '/home2/inveriti/public_html/themes/giftshop/templates/errors/not-found.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6ffcc3d5654_72467301',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30bfe1b5bf1cf4ffeb9afcdf2378d02abb4d3146' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/errors/not-found.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6ffcc3d5654_72467301 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<section id="content" class="page-content page-not-found">
  <h4><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sorry for the inconvenience.','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</h4>
  <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search again what you are looking for','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</p>

  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_97954798165a6ffcc3d4a49_17291784', 'search');
?>


  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayNotFound'),$_smarty_tpl ) );?>

</section>
<?php }
/* {block 'search'} */
class Block_97954798165a6ffcc3d4a49_17291784 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'search' => 
  array (
    0 => 'Block_97954798165a6ffcc3d4a49_17291784',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displaySearch'),$_smarty_tpl ) );?>

  <?php
}
}
/* {/block 'search'} */
}