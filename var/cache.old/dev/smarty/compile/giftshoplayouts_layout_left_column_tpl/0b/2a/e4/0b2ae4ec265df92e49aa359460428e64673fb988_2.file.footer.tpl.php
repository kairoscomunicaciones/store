<?php
/* Smarty version 3.1.43, created on 2023-12-13 09:36:53
  from '/home2/inveriti/public_html/themes/giftshop/templates/_partials/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579b375503e27_47624393',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b2ae4ec265df92e49aa359460428e64673fb988' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/_partials/footer.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579b375503e27_47624393 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="footer-container">
  <div class="footer_top_extra">
    <div class="container">
        <div class="row">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterBefore'),$_smarty_tpl ) );?>

        </div>
    </div>
  </div>
  <div class="container">
        <div class="footer_top">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooter'),$_smarty_tpl ) );?>

        </div> 
  </div>
  <div class="footer_after">
      <div class="container">
       <div class="row">
          <div class="ybc_coppyright col-xs-12 col-md-6">
             <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_COPYRIGHT_TEXT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_COPYRIGHT_TEXT']) {?>
                <?php echo $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_COPYRIGHT_TEXT'];?>

             <?php }?>
          </div>
          <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PAYMENT_LOGO'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PAYMENT_LOGO']) {?>
             <div class="payment_footer col-xs-12 col-md-6">
                <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tc_module_path']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
images/config/<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_PAYMENT_LOGO'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment methods'),$_smarty_tpl ) );?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment methods'),$_smarty_tpl ) );?>
" />
             </div>
          <?php }?>
        </div>
      </div>
  </div>
  <div class="footer_before">
      <div class="container">
          <div class="row">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterAfter'),$_smarty_tpl ) );?>

                      </div>
      </div>
  </div>
  <div class="scroll_top"><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TOP','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span></div>
</div>
<?php }
}
