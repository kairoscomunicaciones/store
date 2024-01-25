<?php
/* Smarty version 3.1.43, created on 2023-12-13 10:34:03
  from '/home2/inveriti/public_html/themes/giftshop/modules/ets_megamenu/views/templates/hook/megamenu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579c0db3a1073_33121977',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d9b4aba42e83ff78fb603d1365a13599413472f' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/ets_megamenu/views/templates/hook/megamenu.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579c0db3a1073_33121977 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['menusHTML']->value) {?>
    <div class="ets_mm_megamenu ets_animated 
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_LAYOUT'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_LAYOUT']) {?>layout_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_LAYOUT'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>  
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_SKIN'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_SKIN']) {?>skin_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_SKIN'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>  
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_TRANSITION_EFFECT'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_TRANSITION_EFFECT']) {?>transition_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_TRANSITION_EFFECT'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>   
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MOBILE_MM_TYPE'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MOBILE_MM_TYPE']) {?>transition_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MOBILE_MM_TYPE'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> 
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_CUSTOM_CLASS'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_CUSTOM_CLASS']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_CUSTOM_CLASS'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> 
         
        <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_FLOAT_HEADER'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_FLOAT_HEADER']) {?>sticky_enabled<?php } else { ?>sticky_disabled<?php }?>
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_ACTIVE_ENABLED'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_ACTIVE_ENABLED']) {?>enable_active_menu<?php }?> 
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_layout_direction']->value)) && $_smarty_tpl->tpl_vars['mm_layout_direction']->value) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mm_layout_direction']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else { ?>ets-dir-ltr<?php }?>
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_HOOK_TO'])) && $_smarty_tpl->tpl_vars['mm_config']->value['ETS_MM_HOOK_TO'] == 'customhook') {?>hook-custom<?php } else { ?>hook-default<?php }?>
        <?php if ((isset($_smarty_tpl->tpl_vars['mm_multiLayout']->value)) && $_smarty_tpl->tpl_vars['mm_multiLayout']->value) {?>multi_layout<?php } else { ?>single_layout<?php }?>">
        <div class="ets_mm_megamenu_content">
                        <?php echo $_smarty_tpl->tpl_vars['menusHTML']->value;?>

        </div>
    </div>
<?php }
}
}
