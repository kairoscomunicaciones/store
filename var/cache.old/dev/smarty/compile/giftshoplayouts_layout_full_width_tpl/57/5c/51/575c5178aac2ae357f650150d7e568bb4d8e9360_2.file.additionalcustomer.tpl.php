<?php
/* Smarty version 3.1.43, created on 2023-12-13 09:56:01
  from '/home2/inveriti/public_html/themes/giftshop/templates/additionalcustomer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579b7f1955cf0_32743920',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '575c5178aac2ae357f650150d7e568bb4d8e9360' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/additionalcustomer.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579b7f1955cf0_32743920 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13332473576579b7f1952406_88843424', 'page_header_container');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17223638526579b7f1952fb9_77055996', 'page_content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_header_container'} */
class Block_13332473576579b7f1952406_88843424 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_header_container' => 
  array (
    0 => 'Block_13332473576579b7f1952406_88843424',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content'} */
class Block_17223638526579b7f1952fb9_77055996 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_17223638526579b7f1952fb9_77055996',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
 
<h1><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Código de Validación no se encuentra. El registro no pudo ser completado, intente nuevamente con un código vàlido.','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
 </h1>
<?php
}
}
/* {/block 'page_content'} */
}
