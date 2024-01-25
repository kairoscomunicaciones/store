<?php
/* Smarty version 3.1.43, created on 2023-12-13 10:34:03
  from 'module:psemailsubscriptionviewst' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579c0db3dd556_98920935',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '307dc6bd4724d29d1572cc301dd7148e962604ef' => 
    array (
      0 => 'module:psemailsubscriptionviewst',
      1 => 1697325107,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579c0db3dd556_98920935 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- begin /home2/inveriti/public_html/themes/giftshop/modules/ps_emailsubscription/views/templates/hook/ps_emailsubscription.tpl --><div id="footer_nlt" class="block_newsletter links col-lg-3 col-md-3 col-sm-12">
    <h4 class="text-uppercase title-footer-block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sign up to our newsletter: ','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</h4>
      <form action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['index'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
#footer" method="post">
            <?php if ($_smarty_tpl->tpl_vars['conditions']->value) {?>
                <p><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['conditions']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</p>
              <?php }?>
            <div class="block_newsletter_form">
                <div class="newsletter_submit">
                <input
                  class="btn btn-primary pull-xs-right"
                  name="submitNewsletter" type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Subscribe','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
" >
                <input
                  class="btn btn-primary pull-xs-right hidden-sm-up hidden-xs-down"
                  name="submitNewsletter"
                  type="submit"
                  value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'OK','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
"
                >
                </div>
                <div class="input-wrapper">
                  <input
                    name="email"
                    type="text"
                    value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                    placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enter your email...','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
"
                  >
                </div>
                <input type="hidden" name="action" value="0">
                <div class="clearfix"></div>
            </div>
          <div class="col-xs-12">
            <div class="row">
              <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
                <p class="alert <?php if ($_smarty_tpl->tpl_vars['nw_error']->value) {?>alert-danger<?php } else { ?>alert-success<?php }?>">
                  <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['msg']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                </p>
              <?php }?>
              </div>
          </div>
      </form>
</div>
<!-- end /home2/inveriti/public_html/themes/giftshop/modules/ps_emailsubscription/views/templates/hook/ps_emailsubscription.tpl --><?php }
}
