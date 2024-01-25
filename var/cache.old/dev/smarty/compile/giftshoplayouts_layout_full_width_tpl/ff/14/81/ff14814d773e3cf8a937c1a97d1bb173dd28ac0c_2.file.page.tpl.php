<?php
/* Smarty version 3.1.43, created on 2023-12-13 10:34:03
  from '/home2/inveriti/public_html/themes/giftshop/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579c0db1be620_20703729',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ff14814d773e3cf8a937c1a97d1bb173dd28ac0c' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/page.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579c0db1be620_20703729 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21075398496579c0db1ba389_12199817', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_18186648816579c0db1bab23_16152527 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header class="page-header">
          <h2><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h2>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_3940985106579c0db1ba715_13756550 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18186648816579c0db1bab23_16152527', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_16910655306579c0db1bcd88_02825944 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_18369051766579c0db1bd235_79704248 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_8808386046579c0db1bca44_82467891 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <div id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16910655306579c0db1bcd88_02825944', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18369051766579c0db1bd235_79704248', 'page_content', $this->tplIndex);
?>

      </div>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_3332213156579c0db1bdcd0_43349282 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_15429908406579c0db1bd955_67566785 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3332213156579c0db1bdcd0_43349282', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_21075398496579c0db1ba389_12199817 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_21075398496579c0db1ba389_12199817',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_3940985106579c0db1ba715_13756550',
  ),
  'page_title' => 
  array (
    0 => 'Block_18186648816579c0db1bab23_16152527',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_8808386046579c0db1bca44_82467891',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_16910655306579c0db1bcd88_02825944',
  ),
  'page_content' => 
  array (
    0 => 'Block_18369051766579c0db1bd235_79704248',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_15429908406579c0db1bd955_67566785',
  ),
  'page_footer' => 
  array (
    0 => 'Block_3332213156579c0db1bdcd0_43349282',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <div id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3940985106579c0db1ba715_13756550', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8808386046579c0db1bca44_82467891', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15429908406579c0db1bd955_67566785', 'page_footer_container', $this->tplIndex);
?>


  </div>

<?php
}
}
/* {/block 'content'} */
}
