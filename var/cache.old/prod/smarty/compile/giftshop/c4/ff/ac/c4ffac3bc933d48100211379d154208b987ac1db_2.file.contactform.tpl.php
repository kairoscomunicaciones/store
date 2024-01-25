<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:23:08
  from '/home2/inveriti/public_html/themes/giftshop/modules/contactform/views/templates/widget/contactform.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a70fdccebee6_57650771',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c4ffac3bc933d48100211379d154208b987ac1db' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/contactform/views/templates/widget/contactform.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a70fdccebee6_57650771 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="contact-form">
  <form action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['pages']['contact'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" method="post" <?php if ($_smarty_tpl->tpl_vars['contact']->value['allow_file_upload']) {?>enctype="multipart/form-data"<?php }?>>

    <?php if ($_smarty_tpl->tpl_vars['notifications']->value) {?>
      <div class="col-xs-12 alert <?php if ($_smarty_tpl->tpl_vars['notifications']->value['nw_error']) {?>alert-danger<?php } else { ?>alert-success<?php }?>">
        <ul>
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notifications']->value['messages'], 'notif');
$_smarty_tpl->tpl_vars['notif']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['notif']->value) {
$_smarty_tpl->tpl_vars['notif']->do_else = false;
?>
            <li><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['notif']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</li>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </ul>
      </div>
    <?php }?>

    <div class="form-fields">
      <div class="form-group row">
        <label class="col-md-3 form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Subject','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
</label>
        <div class="col-md-6">
          <select name="id_contact" class="form-control form-control-select">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['contact']->value['contacts'], 'contact_elt');
$_smarty_tpl->tpl_vars['contact_elt']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['contact_elt']->value) {
$_smarty_tpl->tpl_vars['contact_elt']->do_else = false;
?>
              <option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_elt']->value['id_contact'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_elt']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email address','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
</label>
        <div class="col-md-6">
          <input
            class="form-control"
            name="from"
            type="email"
            value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact']->value['email'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
            placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'your@email.com','d'=>'Shop.Forms.Help'),$_smarty_tpl ) );?>
"
          >
        </div>
      </div>

      <?php if ($_smarty_tpl->tpl_vars['contact']->value['orders']) {?>
        <div class="form-group row">
          <label class="col-md-3 form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order reference','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
</label>
          <div class="col-md-6">
            <select name="id_order" class="form-control form-control-select">
              <option value=""><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select reference','d'=>'Shop.Forms.Help'),$_smarty_tpl ) );?>
</option>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['contact']->value['orders'], 'order');
$_smarty_tpl->tpl_vars['order']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['order']->value) {
$_smarty_tpl->tpl_vars['order']->do_else = false;
?>
                <option value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value['id_order'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['order']->value['reference'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</option>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
          </div>
          <span class="col-md-3 form-control-comment">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'optional','d'=>'Shop.Forms.Help'),$_smarty_tpl ) );?>

          </span>
        </div>
      <?php }?>

      <?php if ($_smarty_tpl->tpl_vars['contact']->value['allow_file_upload']) {?>
        <div class="form-group row">
          <label class="col-md-3 form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Attachment','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
</label>
          <div class="col-md-6">
            <input type="file" name="fileUpload" class="filestyle">
          </div>
          <span class="col-md-3 form-control-comment">
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'optional','d'=>'Shop.Forms.Help'),$_smarty_tpl ) );?>

          </span>
        </div>
      <?php }?>

      <div class="form-group row">
        <label class="col-md-3 form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Message','d'=>'Shop.Forms.Labels'),$_smarty_tpl ) );?>
</label>
        <div class="col-md-9">
          <textarea
            class="form-control"
            name="message"
            placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'How can we help?','d'=>'Shop.Forms.Help'),$_smarty_tpl ) );?>
"
            rows="3"
          ><?php if ($_smarty_tpl->tpl_vars['contact']->value['message']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact']->value['message'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?></textarea>
        </div>
      </div>
    </div>

    <footer class="form-footer">
        <div class="form-group row">
            <label class="col-md-3 form-control-label"></label>
            <div class="col-md-9">
                <input class="btn btn-primary" type="submit" name="submitMessage" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Send','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
">
            </div>
        </div>
    </footer>

  </form>
</div>
<?php }
}
