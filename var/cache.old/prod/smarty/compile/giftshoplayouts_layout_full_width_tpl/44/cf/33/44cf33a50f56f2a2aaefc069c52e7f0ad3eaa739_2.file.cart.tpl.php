<?php
/* Smarty version 3.1.43, created on 2024-01-16 10:55:18
  from '/home2/inveriti/public_html/themes/giftshop/templates/checkout/cart.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a698d6ab56a5_81727807',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44cf33a50f56f2a2aaefc069c52e7f0ad3eaa739' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/checkout/cart.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:checkout/_partials/cart-detailed.tpl' => 1,
    'file:checkout/_partials/cart-detailed-totals.tpl' => 1,
    'file:checkout/_partials/cart-detailed-actions.tpl' => 1,
  ),
),false)) {
function content_65a698d6ab56a5_81727807 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>



<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_194300421465a698d6aaefd3_75452771', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'cart_overview'} */
class Block_34618738265a698d6aafef5_69628901 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:checkout/_partials/cart-detailed.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cart'=>$_smarty_tpl->tpl_vars['cart']->value), 0, false);
?>
          <?php
}
}
/* {/block 'cart_overview'} */
/* {block 'continue_shopping'} */
class Block_85333822665a698d6ab1276_33823230 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <a class="label" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['index'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
            <i class="material-icons">chevron_left</i><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Continue shopping','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

          </a>
        <?php
}
}
/* {/block 'continue_shopping'} */
/* {block 'cart_totals'} */
class Block_2969467965a698d6ab35b9_49069858 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                  <?php $_smarty_tpl->_subTemplateRender('file:checkout/_partials/cart-detailed-totals.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cart'=>$_smarty_tpl->tpl_vars['cart']->value), 0, false);
?>
                <?php
}
}
/* {/block 'cart_totals'} */
/* {block 'cart_actions'} */
class Block_24033887765a698d6ab4097_78387986 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                  <?php $_smarty_tpl->_subTemplateRender('file:checkout/_partials/cart-detailed-actions.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('cart'=>$_smarty_tpl->tpl_vars['cart']->value), 0, false);
?>
                <?php
}
}
/* {/block 'cart_actions'} */
/* {block 'cart_summary'} */
class Block_148764045365a698d6ab2fc4_20202932 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <div class="card cart-summary">
    
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayShoppingCart'),$_smarty_tpl ) );?>

    
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2969467965a698d6ab35b9_49069858', 'cart_totals', $this->tplIndex);
?>

    
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_24033887765a698d6ab4097_78387986', 'cart_actions', $this->tplIndex);
?>

    
              </div>
            <?php
}
}
/* {/block 'cart_summary'} */
/* {block 'display_reassurance'} */
class Block_140568371965a698d6ab4c45_77743547 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayReassurance'),$_smarty_tpl ) );?>

            <?php
}
}
/* {/block 'display_reassurance'} */
/* {block 'content'} */
class Block_194300421465a698d6aaefd3_75452771 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_194300421465a698d6aaefd3_75452771',
  ),
  'cart_overview' => 
  array (
    0 => 'Block_34618738265a698d6aafef5_69628901',
  ),
  'continue_shopping' => 
  array (
    0 => 'Block_85333822665a698d6ab1276_33823230',
  ),
  'cart_summary' => 
  array (
    0 => 'Block_148764045365a698d6ab2fc4_20202932',
  ),
  'cart_totals' => 
  array (
    0 => 'Block_2969467965a698d6ab35b9_49069858',
  ),
  'cart_actions' => 
  array (
    0 => 'Block_24033887765a698d6ab4097_78387986',
  ),
  'display_reassurance' => 
  array (
    0 => 'Block_140568371965a698d6ab4c45_77743547',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <div id="main">
    <div class="cart-grid">

      <!-- Left Block: cart product informations & shpping -->
      <div class="cart-grid-body col-xs-12 col-lg-8">

        <!-- cart products detailed -->
        <div class="card cart-container">
          <div class="card-block">
            <h1 class="h1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shopping Cart','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</h1>
          </div>
          
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_34618738265a698d6aafef5_69628901', 'cart_overview', $this->tplIndex);
?>

        </div>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_85333822665a698d6ab1276_33823230', 'continue_shopping', $this->tplIndex);
?>


        <!-- shipping informations -->
        <div>
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayShoppingCartFooter'),$_smarty_tpl ) );?>

        </div>
      </div>

      <!-- Right Block: cart subtotal & cart total -->
      <div class="cart-grid-right col-xs-12 col-lg-4">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_148764045365a698d6ab2fc4_20202932', 'cart_summary', $this->tplIndex);
?>

    
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_140568371965a698d6ab4c45_77743547', 'display_reassurance', $this->tplIndex);
?>

      </div>

    </div>
  </div>
<?php
}
}
/* {/block 'content'} */
}
