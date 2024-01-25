<?php
/* Smarty version 3.1.43, created on 2024-01-20 08:56:08
  from '/home/ecomunik2/htdocs/ecomunik2/admin730/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65abc2e87ca224_44371234',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '02f44fb5cfd9a5103758519cc634bab74f2e04c5' => 
    array (
      0 => '/home/ecomunik2/htdocs/ecomunik2/admin730/themes/default/template/content.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65abc2e87ca224_44371234 (Smarty_Internal_Template $_smarty_tpl) {
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
