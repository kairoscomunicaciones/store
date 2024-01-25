<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:58:58
  from 'module:universalpayviewstemplate' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a699b2bea217_58430227',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7e712b23b151a81aa6de4394aa429f4957143898' => 
    array (
      0 => 'module:universalpayviewstemplate',
      1 => 1697325104,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a699b2bea217_58430227 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_152108949965a699b2be0db4_38515141', "page_content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block "page_content"} */
class Block_152108949965a699b2be0db4_38515141 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_152108949965a699b2be0db4_38515141',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'path', null, null);
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['paysistem']->value->name,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>

<h1 class="page-heading">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order summary','mod'=>'universalpay'),$_smarty_tpl ) );?>

</h1>

<?php if ($_smarty_tpl->tpl_vars['nbProducts']->value <= 0) {?>
    <p class="alert alert-warning">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your shopping cart is empty.','mod'=>'universalpay'),$_smarty_tpl ) );?>

    </p>
<?php } else { ?>
    <form action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getModuleLink('universalpay','validation',array(),true),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" method="post">
        <div class="box cheque-box">
            <h3 class="page-subheading">
                <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['paysistem']->value->name,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

            </h3>
            <p class="cheque-indent">
                <?php echo $_smarty_tpl->tpl_vars['paysistem']->value->description;?>

            </p>
            <p>
                <b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please confirm your order by clicking "I confirm my order"','mod'=>'universalpay'),$_smarty_tpl ) );?>
.</b>
            </p>
        </div>
        <p class="cart_navigation clearfix" id="cart_navigation">
            <a class="button-exclusive btn btn-default"
               href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,NULL,"step=3"),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                <i class="icon-chevron-left"></i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Other payment methods','mod'=>'universalpay'),$_smarty_tpl ) );?>

            </a>
            <input type="hidden" name="id_universalpay_system" value="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['paysistem']->value->id), ENT_QUOTES, 'UTF-8');?>
"/>
            <button class="button btn btn-default button-medium" type="submit">
                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'I confirm my order','mod'=>'universalpay'),$_smarty_tpl ) );?>
<i class="icon-chevron-right right"></i></span>
            </button>
        </p>
    </form>
<?php }
}
}
/* {/block "page_content"} */
}
