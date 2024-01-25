<?php
/* Smarty version 3.1.43, created on 2024-01-11 12:47:24
  from '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/helpers/uploader/simple.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a01b9c0ea084_85700009',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '265761b430dfcb4a51c97941bc1bfc687ebb9b2a' => 
    array (
      0 => '/home2/inveriti/public_html/modules/gwadvancedinvoice/views/templates/admin/helpers/uploader/simple.tpl',
      1 => 1697325100,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a01b9c0ea084_85700009 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['max_files']->value)) && count($_smarty_tpl->tpl_vars['files']->value) >= $_smarty_tpl->tpl_vars['max_files']->value) {?>
<div class="row">
	<div class="alert alert-warning"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You have reached the limit (%s) of files to upload, please remove files to continue uploading','sprintf'=>$_smarty_tpl->tpl_vars['max_files']->value,'mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
</div>
</div>
<?php } else { ?>
<div class="form-group">
	<div class="col-sm-6">
		<input id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
" type="file" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['name']->value,'htmlall','UTF-8' ));?>
"<?php if ((isset($_smarty_tpl->tpl_vars['multiple']->value)) && $_smarty_tpl->tpl_vars['multiple']->value) {?> multiple="multiple"<?php }?> class="hide" />
		<div class="dummyfile input-group">
			<span class="input-group-addon"><i class="icon-file"></i></span>
			<input id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name" type="text" name="filename" readonly />
			<span class="input-group-btn">
				<button id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
					<i class="icon-folder-open"></i> <?php if ((isset($_smarty_tpl->tpl_vars['multiple']->value)) && $_smarty_tpl->tpl_vars['multiple']->value) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add files','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add file','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
				</button>
				<?php if ((!(isset($_smarty_tpl->tpl_vars['multiple']->value)) || !$_smarty_tpl->tpl_vars['multiple']->value) && (isset($_smarty_tpl->tpl_vars['files']->value)) && count($_smarty_tpl->tpl_vars['files']->value) == 1 && (isset($_smarty_tpl->tpl_vars['files']->value[0]['download_url']))) {?>
				<a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['files']->value[0]['download_url'],'htmlall','UTF-8' ));?>
">
					<button type="button" class="btn btn-default">
						<i class="icon-cloud-download"></i>
						<?php if ((isset($_smarty_tpl->tpl_vars['size']->value))) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download current file (%skb)','sprintf'=>$_smarty_tpl->tpl_vars['size']->value,'mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download current file','mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );
}?>
					</button>
				</a>
				<?php }?>
			</span>
		</div>
	</div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
<?php if ((isset($_smarty_tpl->tpl_vars['multiple']->value)) && (isset($_smarty_tpl->tpl_vars['max_files']->value))) {?>
	var <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
_max_files = <?php echo $_smarty_tpl->tpl_vars['max_files']->value-count($_smarty_tpl->tpl_vars['files']->value);?>
;
<?php }?>
	$(document).ready(function(){
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-selectbutton').click(function(e) {
			$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
').trigger('click');
		});
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').click(function(e) {
			$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
').trigger('click');
		});
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').on('dragenter', function(e) {
			e.stopPropagation();
			e.preventDefault();
		});
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').on('dragover', function(e) {
			e.stopPropagation();
			e.preventDefault();
		});
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').on('drop', function(e) {
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
')[0].files = files;
			$(this).val(files[0].name);
		});
		$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
').change(function(e) {
			if ($(this)[0].files !== undefined)
			{
				var files = $(this)[0].files;
				var name  = '';
				$.each(files, function(index, value) {
					name += value.name+', ';
				});
				$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').val(name.slice(0, -2));
			}
			else // Internet Explorer 9 Compatibility
			{
				var name = $(this).val().split(/[\\/]/);
				$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
-name').val(name[name.length-1]);
			}
		});
		if (typeof <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
_max_files !== 'undefined')
		{
			$('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
').closest('form').on('submit', function(e) {
				if ($('#<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
')[0].files.length > <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['id']->value,'htmlall','UTF-8' ));?>
_max_files) {
					e.preventDefault();
					alert('<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>sprintf('You can upload a maximum of %s files',$_smarty_tpl->tpl_vars['max_files']->value),'mod'=>'gwadvancedinvoice'),$_smarty_tpl ) );?>
');
				}
			});
		}
	});
<?php echo '</script'; ?>
>
<?php }
}
}
