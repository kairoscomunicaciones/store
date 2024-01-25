<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:23:08
  from '/home2/inveriti/public_html/themes/giftshop/templates/contact.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a70fdcaa9db2_84595131',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6425b730ed9b350b82224698ec3deb3bba9f9954' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/contact.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a70fdcaa9db2_84595131 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_43118813865a70fdcaa0724_28931122', 'page_header_container');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_123272253865a70fdcaa0f03_43268965', 'page_content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_header_container'} */
class Block_43118813865a70fdcaa0724_28931122 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_header_container' => 
  array (
    0 => 'Block_43118813865a70fdcaa0724_28931122',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content'} */
class Block_123272253865a70fdcaa0f03_43268965 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_123272253865a70fdcaa0f03_43268965',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'] == 'contact_layout1') {?>
      <div class="page_contact_layout1 col-xs-12 col-sm-12">
          <?php if ($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] != '') {?>
            <div class="embe_map_contact">
                <?php echo $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'];?>

            </div>
          <?php }?>
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"ps_contactinfo",'hook'=>'displayLeftColumn'),$_smarty_tpl ) );?>

          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"contactform"),$_smarty_tpl ) );?>

      </div>
    <?php }?>
    
    
        <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'] == 'contact_layout2') {?>
        <div class="page_contact_layout2 col-xs-12 col-sm-12">
            <div class="row">
              <?php if ($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] != '') {?>
                
                    <div class="embe_map_contact col-xs-12 col-sm-6">
                        <?php echo $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'];?>

                    </div>
                    <div class="embe_map_contact col-xs-12 col-sm-6">
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"contactform"),$_smarty_tpl ) );?>

                    </div>
                
              <?php } else { ?>
                <div class="embe_map_contact col-xs-12 col-sm-12">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"contactform"),$_smarty_tpl ) );?>

                </div>
              <?php }?>
              <div class="col-xs-12 col-sm-12">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"ps_contactinfo",'hook'=>'displayLeftColumn'),$_smarty_tpl ) );?>

              </div>
            </div>
        </div> 
    <?php }?>
    
        <?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_CONTACT_FORM_LAYOUT'] == 'contact_layout3') {?>
        <div class="page_contact_layout3">  
          <?php if ($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'] != '') {?>
                <div class="col-sm-6 col-md-4">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"ps_contactinfo",'hook'=>'displayLeftColumn'),$_smarty_tpl ) );?>

                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="embe_map_contact">
                        <?php echo $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_GOOGLE_MAP_EMBED_CODE'];?>

                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"contactform"),$_smarty_tpl ) );?>

                </div>
          <?php } else { ?>
                <div class="col-sm-6 col-md-6">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"ps_contactinfo",'hook'=>'displayLeftColumn'),$_smarty_tpl ) );?>

                </div>
                <div class="col-sm-6 col-md-6">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"contactform"),$_smarty_tpl ) );?>

                </div>
          <?php }?>
        </div>
    <?php }
}
}
/* {/block 'page_content'} */
}
