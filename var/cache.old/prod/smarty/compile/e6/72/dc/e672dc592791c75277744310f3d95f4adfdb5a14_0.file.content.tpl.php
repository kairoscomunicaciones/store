<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:16:12
  from '/home2/inveriti/public_html/admin730/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7002c8b8ef4_26050625',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e672dc592791c75277744310f3d95f4adfdb5a14' => 
    array (
      0 => '/home2/inveriti/public_html/admin730/themes/default/template/content.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7002c8b8ef4_26050625 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
