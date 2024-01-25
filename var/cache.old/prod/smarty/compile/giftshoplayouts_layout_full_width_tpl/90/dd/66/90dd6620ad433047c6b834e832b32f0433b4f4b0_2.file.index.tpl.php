<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:32:41
  from '/home2/inveriti/public_html/themes/giftshop/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71219bc3bd7_22160015',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90dd6620ad433047c6b834e832b32f0433b4f4b0' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/index.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71219bc3bd7_22160015 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_144393789665a71219bc0eb3_93906840', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_130206267265a71219bc2930_78553317 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_80984970565a71219bc2fa0_08588296 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_144393789665a71219bc0eb3_93906840 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_144393789665a71219bc0eb3_93906840',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_130206267265a71219bc2930_78553317',
  ),
  'page_content' => 
  array (
    0 => 'Block_80984970565a71219bc2fa0_08588296',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displaytopcolumn'),$_smarty_tpl ) );?>

    <div class="tabhome">
        <div class="tabhome_title">
            <h4 class="title_tab"><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Featured [1]Products[/1]','d'=>'Shop.Theme.Actions','sprintf'=>array('[1]'=>'<span class="main-color">','[/1]'=>'</span>')),$_smarty_tpl ) );?>
</span></h4>
            <ul class="tabhome_title_list"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'tabHome'),$_smarty_tpl ) );?>
</ul>
        </div>
        <div class="tabhome_content">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'tabHomeContent'),$_smarty_tpl ) );?>

        </div>
    </div>
      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_130206267265a71219bc2930_78553317', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_80984970565a71219bc2fa0_08588296', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
