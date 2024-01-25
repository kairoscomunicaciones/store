<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:23:08
  from 'module:pscontactinfopscontactinf' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a70fdccc6313_12473198',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '628089b29e2c5fb69de1c5694b088f988b830ba7' => 
    array (
      0 => 'module:pscontactinfopscontactinf',
      1 => 1697325107,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a70fdccc6313_12473198 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="contact-rich">
  <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_ADDRESS'])) && $_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_ADDRESS']) {?>
            <div class="block">
                <div class="icon"><i class="material-icons">place</i></div>
                <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br />
                <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_ADDRESS'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</div>
            </div>
      <?php } elseif (((isset($_smarty_tpl->tpl_vars['contact_infos']->value['address']['address1'])) && $_smarty_tpl->tpl_vars['contact_infos']->value['address']['address1']) || ((isset($_smarty_tpl->tpl_vars['contact_infos']->value['address']['address2'])) && $_smarty_tpl->tpl_vars['contact_infos']->value['address']['address2'])) {?>
          <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['address']['address1']) {?>
              <div class="block">
                <div class="icon"><i class="material-icons">place</i></div>
                <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br />
                    <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['address']['address1'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                </div>
              </div>
          <?php }?>
          <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['address']['address2']) {?>
              <div class="block">
                <div class="icon"><i class="material-icons">place</i></div>
                <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br />
                    <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['address']['address2'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                </div>
              </div>
          <?php }?>
      <?php } else { ?>
        <?php if ((isset($_smarty_tpl->tpl_vars['contact_infos']->value['address']['formatted'])) && $_smarty_tpl->tpl_vars['contact_infos']->value['address']['formatted']) {?>
            <div class="block">
                <div class="icon"><i class="material-icons">place</i></div>
                <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br />
                    <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['address']['formatted'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                </div>
              </div>
        <?php }?>
      <?php }?>
  
  
  
  <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_PHONE_LABEL'])) && $_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_PHONE_LABEL']) {?>
    <div class="block">
      <div class="icon"><i class="material-icons">local_phone</i></div>
      <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Call us:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br/>
        <a href="tel:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_PHONE_CALL'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['tc_config']->value['BLOCKCONTACTINFOS_PHONE_LABEL'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a>
       </div>
    </div>
  <?php }?>
  
  
  
  
  <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['fax']) {?>
    <div class="block">
      <div class="icon"><i class="material-icons">present_to_all</i></div>
      <div class="data"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Fax:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br/>
        <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['fax'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

      </div>
    </div>
  <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['email']) {?>
    <div class="block">
      <div class="icon"><i class="material-icons">mail_outline</i></div>
      <div class="data email"><span class="data_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email us:','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span><br/>
        <a href="mailto:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['email'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['contact_infos']->value['email'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</a>
       </div>
    </div>
  <?php }?>
</div>
<?php }
}
