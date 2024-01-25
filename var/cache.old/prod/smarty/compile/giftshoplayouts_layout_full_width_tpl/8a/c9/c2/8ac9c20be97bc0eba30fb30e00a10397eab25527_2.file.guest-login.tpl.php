<?php
/* Smarty version 3.1.43, created on 2024-01-11 19:33:00
  from '/home2/inveriti/public_html/themes/giftshop/templates/customer/guest-login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a07aaca2bd69_57149385',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8ac9c20be97bc0eba30fb30e00a10397eab25527' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/customer/guest-login.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a07aaca2bd69_57149385 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_119213206165a07aaca25751_34167332', 'page_title');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_84061847865a07aaca26983_29197782', 'page_content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_title'} */
class Block_119213206165a07aaca25751_34167332 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_title' => 
  array (
    0 => 'Block_119213206165a07aaca25751_34167332',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Guest Order Tracking','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

<?php
}
}
/* {/block 'page_title'} */
/* {block 'page_content'} */
class Block_84061847865a07aaca26983_29197782 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_84061847865a07aaca26983_29197782',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <form id="guestOrderTrackingForm" action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['guest_tracking'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" method="get">
    <header>
      <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'To track your order, please enter the following information:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</p>
    </header>

    <section class="form-fields">

      <div class="form-group row">
        <label class="col-md-3 form-control-label required">
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order Reference:','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>

        </label>
        <div class="col-md-6">
          <input
            class="form-control"
            name="order_reference"
            type="text"
            size="8"
            value="<?php if ((isset($_REQUEST['order_reference']))) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_REQUEST['order_reference'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>"
          >
          <div class="form-control-comment">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'For example: QIIXJXNUI or QIIXJXNUI#1','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 form-control-label required">
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email:','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>

        </label>
        <div class="col-md-6">
          <input
            class="form-control"
            name="email"
            type="email"
            value="<?php if ((isset($_REQUEST['email']))) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_REQUEST['email'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>"
          >
        </div>
      </div>

    </section>

    <footer class="form-footer text-xs-center clearfix">
      <button class="btn btn-primary" type="submit">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Send','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

      </button>
    </footer>
  </form>
<?php
}
}
/* {/block 'page_content'} */
}
