<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:09:55
  from '/home2/inveriti/public_html/modules/ets_onepagecheckout/views/templates/hook/social.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a6feb3aea0c5_36897601',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c361686a3bf9f01ca43eaa29394cd29ba62f4c40' => 
    array (
      0 => '/home2/inveriti/public_html/modules/ets_onepagecheckout/views/templates/hook/social.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a6feb3aea0c5_36897601 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['list_socials']->value) {?>
    <div class="opc_social_form col-xs-12 col-sm-12">
        <div class="opc_solo_or"><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'OR log in with','mod'=>'ets_onepagecheckout'),$_smarty_tpl ) );?>
</span></div>
        <ul class="opc_social">
            <?php if ($_smarty_tpl->tpl_vars['list_socials']->value) {?>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_socials']->value, 'social');
$_smarty_tpl->tpl_vars['social']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['social']->value) {
$_smarty_tpl->tpl_vars['social']->do_else = false;
?>
                    <li class="opc_social_item <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::strtolower($_smarty_tpl->tpl_vars['social']->value),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 active" data-auth="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['social']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php if (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'paypal') {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sign in with Paypal','mod'=>'ets_onepagecheckout'),$_smarty_tpl ) );
} elseif (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'facebook') {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sign in with Facebook','mod'=>'ets_onepagecheckout'),$_smarty_tpl ) );
} elseif (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'google') {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sign in with Google','mod'=>'ets_onepagecheckout'),$_smarty_tpl ) );
}?>">
                        <span class="opc_social_btn medium rounded custom">
                            
                            <?php if (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'paypal') {?>
                                <i class="ets_svg_icon">
                                    <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1647 646q18 84-4 204-87 444-565 444h-44q-25 0-44 16.5t-24 42.5l-4 19-55 346-2 15q-5 26-24.5 42.5t-44.5 16.5h-251q-21 0-33-15t-9-36q9-56 26.5-168t26.5-168 27-167.5 27-167.5q5-37 43-37h131q133 2 236-21 175-39 287-144 102-95 155-246 24-70 35-133 1-6 2.5-7.5t3.5-1 6 3.5q79 59 98 162zm-172-282q0 107-46 236-80 233-302 315-113 40-252 42 0 1-90 1l-90-1q-100 0-118 96-2 8-85 530-1 10-12 10h-295q-22 0-36.5-16.5t-11.5-38.5l232-1471q5-29 27.5-48t51.5-19h598q34 0 97.5 13t111.5 32q107 41 163.5 123t56.5 196z"/></svg>
                                </i>
                            <?php } elseif (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'facebook') {?>
                                <i class="ets_svg_icon">
                                    <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1343 12v264h-157q-86 0-116 36t-30 108v189h293l-39 296h-254v759h-306v-759h-255v-296h255v-218q0-186 104-288.5t277-102.5q147 0 228 12z"/></svg>
                                </i>
                            <?php } elseif (Tools::strtolower($_smarty_tpl->tpl_vars['social']->value) == 'google') {?>
                                <i class="ets_svg_icon">
                                    <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z"/></svg>
                                </i>
                            <?php } else { ?>
                                <i class="icon icon-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::strtolower($_smarty_tpl->tpl_vars['social']->value),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 fa fa-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( Tools::strtolower($_smarty_tpl->tpl_vars['social']->value),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"></i>
                            <?php }?> <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['social']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                        </span>
                    </li>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
        </ul>
    </div>
<?php }
}
}
