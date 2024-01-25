<?php
/* Smarty version 3.1.43, created on 2024-01-16 19:32:41
  from '/home2/inveriti/public_html/themes/giftshop/modules/ets_multilayerslider/views/templates/hook/multilayerslider.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a71219efb0d4_40415440',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '36e96702f70ff37659708545857b2ff87d9b33d5' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/modules/ets_multilayerslider/views/templates/hook/multilayerslider.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a71219efb0d4_40415440 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    var width_slider = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['mls_width']->value), ENT_QUOTES, 'UTF-8');?>
;
    var height_slider = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['mls_height']->value), ENT_QUOTES, 'UTF-8');?>
;
<?php echo '</script'; ?>
>
<?php if ((isset($_smarty_tpl->tpl_vars['mls_slides']->value)) && $_smarty_tpl->tpl_vars['mls_slides']->value) {?>
<div class="ets_multilayerslider<?php if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_ENABLE_CATE_BLOCK'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_ENABLE_CATE_BLOCK'] == 0) {?> multislider_full<?php }
if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_TYPE'] == 'full') {?>hidden<?php }?>">
    <div class="mls_slides">
    <div data-max-slide-time="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['mls_max_slide_time']->value), ENT_QUOTES, 'UTF-8');?>
" class="mls_slider <?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_LOADING_ICON']) {?>loading<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_TYPE']))) {?>mls_slider_type_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( strtolower($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_TYPE']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> mls_layout_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_layout']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="width: <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['mls_width']->value), ENT_QUOTES, 'UTF-8');?>
px; height: <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['mls_height']->value), ENT_QUOTES, 'UTF-8');?>
px; <?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BACKGROUND']) {?>background-color: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BACKGROUND'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>" <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayMLSConfigs'),$_smarty_tpl ) );?>
>
        <div class="mls_slider_running" style="<?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR']) {?>background-color: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php } else { ?>#000000;<?php }?>"></div>                    
        <ul class="mls_slides_front">
            <?php $_smarty_tpl->_assignInScope('ik', 0);?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mls_slides']->value, 'slide');
$_smarty_tpl->tpl_vars['slide']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['slide']->value) {
$_smarty_tpl->tpl_vars['slide']->do_else = false;
?>
                <?php $_smarty_tpl->_assignInScope('ik', $_smarty_tpl->tpl_vars['ik']->value+1);?>
                <li data-slide-background-image="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['link_img'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" data-slide-order="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ik']->value), ENT_QUOTES, 'UTF-8');?>
" data-max-layer-in="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['slide']->value['max_layer_in']), ENT_QUOTES, 'UTF-8');?>
" data-max-layer-out="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['slide']->value['max_layer_out']), ENT_QUOTES, 'UTF-8');?>
" data-animation-in="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['animation_in'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" data-animation-out="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['animation_out'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" data-id-slide="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['slide']->value['id_slide']), ENT_QUOTES, 'UTF-8');?>
" class="mls_slide_front <?php if ((isset($_smarty_tpl->tpl_vars['slide']->value['custom_class'])) && $_smarty_tpl->tpl_vars['slide']->value['custom_class']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['custom_class'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> item_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ik']->value), ENT_QUOTES, 'UTF-8');?>
 mls_slide_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['slide']->value['id_slide']), ENT_QUOTES, 'UTF-8');?>
" style="<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['link_img'],'html','UTF-8' ))) {?> <?php if (!$_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_LOADING_ICON'] || $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_LOADING_ICON'] && $_smarty_tpl->tpl_vars['ik']->value > 1) {?>background-image: url('<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['link_img'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
');<?php }?>background-repeat: <?php if ($_smarty_tpl->tpl_vars['slide']->value['repeat_x'] && $_smarty_tpl->tpl_vars['slide']->value['repeat_y']) {?>repeat<?php } elseif ($_smarty_tpl->tpl_vars['slide']->value['repeat_x']) {?>repeat-x<?php } elseif ($_smarty_tpl->tpl_vars['slide']->value['repeat_y']) {?>repeat-y<?php } else { ?>no-repeat<?php }?>;<?php }
if ($_smarty_tpl->tpl_vars['slide']->value['backgroud_color']) {?> background-color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['backgroud_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
; <?php }?> position: relative;">
                    <?php if ((isset($_smarty_tpl->tpl_vars['slide']->value['layers'])) && $_smarty_tpl->tpl_vars['slide']->value['layers']) {?>                        
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['slide']->value['layers'], 'layer');
$_smarty_tpl->tpl_vars['layer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['layer']->value) {
$_smarty_tpl->tpl_vars['layer']->do_else = false;
?>
                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'image' && $_smarty_tpl->tpl_vars['layer']->value['image'] || $_smarty_tpl->tpl_vars['layer']->value['layer_type'] != 'image' && $_smarty_tpl->tpl_vars['layer']->value['content_layer']) {?>
                                <div class="msl_layer_front <?php if ((isset($_smarty_tpl->tpl_vars['layer']->value['custom_class'])) && $_smarty_tpl->tpl_vars['layer']->value['custom_class']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['custom_class'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?> msl_layer_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['layer']->value['id_layer']), ENT_QUOTES, 'UTF-8');?>
 layer_layout_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_layout']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 mls_layer_type_front_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['layer_type'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="position: absolute; <?php if ($_smarty_tpl->tpl_vars['mls_layout']->value == 'rtl' && $_smarty_tpl->tpl_vars['mls_multilayout']->value) {?>right:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['right'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px; top:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['top_right'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px; left: auto;<?php } else { ?>left:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['left'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px; top:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['top'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px; right: auto;<?php }?> float:left; bottom: auto; z-index: <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['layer']->value['sort_order']), ENT_QUOTES, 'UTF-8');?>
;" data-top="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['top'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" data-id-layer="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['layer']->value['id_layer']), ENT_QUOTES, 'UTF-8');?>
" 
                                    data-animation-in="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['animation_in'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" 
                                    data-animation-out="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['animation_out'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" 
                                    data-move-in="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['move_in'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" 
                                    data-move-out="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['move_out'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" 
                                    data-delay-in="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['start_delay'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                    data-delay-out="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['stand_duration'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                    <?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'image') {?>
                                        <?php if ($_smarty_tpl->tpl_vars['layer']->value['link']) {?><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php }?><img class="spot" src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['link_image'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="<?php if ($_smarty_tpl->tpl_vars['layer']->value['width']) {?>width: <?php echo htmlspecialchars(floatval($_smarty_tpl->tpl_vars['layer']->value['width']), ENT_QUOTES, 'UTF-8');?>
px;<?php }
if ($_smarty_tpl->tpl_vars['layer']->value['height']) {?>height: <?php echo htmlspecialchars(floatval($_smarty_tpl->tpl_vars['layer']->value['height']), ENT_QUOTES, 'UTF-8');?>
px;<?php }?>" alt="" /><?php if ($_smarty_tpl->tpl_vars['layer']->value['link']) {?></a><?php }?>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'text' || $_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'link') {?>
                                        <?php if ($_smarty_tpl->tpl_vars['layer']->value['link'] && $_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'link') {?><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php }?><span style="
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_size']) {?>font-size:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_size'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px<?php }?>;
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_color']) {?>color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_family']) {?>font-family:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_family'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_weight']) {?>font-weight:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_weight'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_decoration']) {?>text-decoration:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_decoration'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_transform']) {?>text-transform:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_transform'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                        "><?php echo $_smarty_tpl->tpl_vars['layer']->value['content_layer'];?>
</span><?php if ($_smarty_tpl->tpl_vars['layer']->value['link'] && $_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'link') {?></a><?php }?>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'text_background') {?>
                                        <span style="
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_size']) {?>font-size:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_size'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px<?php }?>;
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_color']) {?>color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_family']) {?>font-family:'<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_family'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
';<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_weight']) {?>font-weight:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_weight'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['background_color']) {?>background-color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['background_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_decoration']) {?>text-decoration:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_decoration'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_transform']) {?>text-transform:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_transform'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['padding']) {?>padding:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['padding'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                        "><?php echo $_smarty_tpl->tpl_vars['layer']->value['content_layer'];?>
</span>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['layer']->value['layer_type'] == 'button') {?>
                                        <?php if ($_smarty_tpl->tpl_vars['layer']->value['link']) {?><a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php }?><span style="
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_size']) {?>font-size:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_size'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
px<?php }?>;
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_color']) {?>color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_family']) {?>font-family:'<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_family'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
';<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['font_weight']) {?>font-weight:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['font_weight'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['background_color']) {?>background-color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['background_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_transform']) {?>text-transform:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_transform'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['padding']) {?>padding:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['padding'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['text_decoration']) {?>text-decoration:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['text_decoration'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['layer']->value['box_radius']) {?>border-radius:<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['layer']->value['box_radius']), ENT_QUOTES, 'UTF-8');?>
px;<?php }?>
                                        "><?php echo $_smarty_tpl->tpl_vars['layer']->value['content_layer'];?>
</span><?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['layer']->value['link'],'html','UTF-8' ))) {?></a><?php }?>
                                    <?php }?>
                                </div>
                            <?php }?>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>                        
                    <?php }?>
                </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </ul>
        <?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_LOADING_ICON']) {?>
            <div class="mls_loading_icon" style="<?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BACKGROUND']) {?>background-color: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BACKGROUND'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php } else { ?>#000000;<?php }?>"><img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_img_base_dir']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
ajax-loader.gif" alt=""/></div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_NEXT_PREV']) {?>
            <div class="mls_nav">
                <div class="mls_prev" style="<?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR']) {?>background-color: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Prev','mod'=>'ets_multilayerslider'),$_smarty_tpl ) );?>
</div>
                <div class="mls_next" style="<?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR']) {?>background-color: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Next','mod'=>'ets_multilayerslider'),$_smarty_tpl ) );?>
</div>
            </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_ENABLE_PAGINATION']) {?>
            <div class="mls_pagination">
                <?php $_smarty_tpl->_assignInScope('ik', 0);?>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mls_slides']->value, 'slide');
$_smarty_tpl->tpl_vars['slide']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['slide']->value) {
$_smarty_tpl->tpl_vars['slide']->do_else = false;
?>
                    <?php $_smarty_tpl->_assignInScope('ik', $_smarty_tpl->tpl_vars['ik']->value+1);?>
                    <div class="mls_pag_button mls_pag_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ik']->value), ENT_QUOTES, 'UTF-8');?>
" style="background-color: <?php if ((isset($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'])) && $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?> <?php if ((isset($_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'])) && $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR']) {?>border-color:<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['mls_configs']->value['ETS_MLS_SLIDER_BUTTON_COLOR'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;<?php }?>" data-slide-order="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ik']->value), ENT_QUOTES, 'UTF-8');?>
" data-id-slide="<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['slide']->value['id_slide']), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['ik']->value), ENT_QUOTES, 'UTF-8');?>
</div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
        <?php }?>
    </div>
    </div>
    </div>
<?php }
if (!$_smarty_tpl->tpl_vars['mls_slides']->value && $_smarty_tpl->tpl_vars['mls_backend_load']->value) {?>
    <div class="alert alert-warning"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No active slides available','mod'=>'ets_multilayerslider'),$_smarty_tpl ) );?>
</div>
<?php }
if ((isset($_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LAYOUT'])) && $_smarty_tpl->tpl_vars['tc_config']->value['YBC_TC_LAYOUT'] == 'LayoutHome2') {?>
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayTopColumn'),$_smarty_tpl ) );?>

<?php }
}
}
