<?php
/* Smarty version 3.1.43, created on 2024-01-20 07:37:29
  from '/home/ecomunik2/htdocs/ecomunik2.store/admin730/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65abb0796197c7_47472247',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '43cfff9a601c2c0a4ae214b561398c158c677b11' => 
    array (
      0 => '/home/ecomunik2/htdocs/ecomunik2.store/admin730/themes/default/template/content.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65abb0796197c7_47472247 (Smarty_Internal_Template $_smarty_tpl) {
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
