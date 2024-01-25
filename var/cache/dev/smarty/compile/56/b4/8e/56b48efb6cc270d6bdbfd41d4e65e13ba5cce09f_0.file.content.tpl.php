<?php
/* Smarty version 3.1.43, created on 2024-01-18 18:31:36
  from '/home/ecomunik2/htdocs/www.ecomunik2.store/admin730/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a9a6c8c26fd7_85012570',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56b48efb6cc270d6bdbfd41d4e65e13ba5cce09f' => 
    array (
      0 => '/home/ecomunik2/htdocs/www.ecomunik2.store/admin730/themes/default/template/content.tpl',
      1 => 1697325111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a9a6c8c26fd7_85012570 (Smarty_Internal_Template $_smarty_tpl) {
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
