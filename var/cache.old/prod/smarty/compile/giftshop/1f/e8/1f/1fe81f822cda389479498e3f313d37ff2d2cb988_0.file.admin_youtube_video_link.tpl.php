<?php
/* Smarty version 3.1.43, created on 2024-01-11 12:09:28
  from '/home2/inveriti/public_html/modules/kbmobileapp/views/templates/admin/admin_youtube_video_link.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a012b8157e05_43347306',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1fe81f822cda389479498e3f313d37ff2d2cb988' => 
    array (
      0 => '/home2/inveriti/public_html/modules/kbmobileapp/views/templates/admin/admin_youtube_video_link.tpl',
      1 => 1697325105,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a012b8157e05_43347306 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="product-tab-content-kbmobileapp-youtube" class="product-tab-content" style="display: block;">
    <div id="kbmobileapp-product-youtube" class="panel product-tab">
	<h3><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product YouTube Video URL','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
</h3>
	<div class="form-group">
            <label class="control-label col-lg-3" for="product_youtube_url">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter the Product Video URL from YouTube here and save the product','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'YouTube URL','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>

                </span>
            </label>
            <div class="col-lg-9">
                <fieldset style="border:none;">
                    <input type="text" class="form-control" id="product_youtube_url" name="product_youtube_url" value="<?php if ((isset($_smarty_tpl->tpl_vars['velsof_yt_data']->value['youtube_url'])) && $_smarty_tpl->tpl_vars['velsof_yt_data']->value['youtube_url'] != '') {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['velsof_yt_data']->value['youtube_url'],'htmlall','UTF-8' ));
}?>"/>
                </fieldset>
            </div>
	</div>
        <div class="panel-footer">
            <a href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['velsof_product_back_url']->value,'htmlall','UTF-8' ));?>
" class="btn btn-default"><i class="process-icon-cancel"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancel','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
</a>
            <button type="submit" name="submitAddproduct" class="btn btn-default pull-right kbmobileapp-product-youtube-submit"><i class="process-icon-save"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
</button>
            <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right kbmobileapp-product-youtube-submit"><i class="process-icon-save"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save and stay','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
</button>
        </div>
    </div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
//error messages for velovalidation.js
velovalidation.setErrorLanguage({
    empty_fname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter First name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_fname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'First name cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_fname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'First name cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_mname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter middle name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_mname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Middle name cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_mname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Middle name cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    only_alphabet: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Only alphabets are allowed.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_lname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter Last name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_lname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Last name cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_lname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Last name cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    alphanumeric: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Field should be alphanumeric.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter Password.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password should contain atleast 1 special character.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    alphabets_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password should contain alphabets.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    capital_alphabets_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password should contain atleast 1 capital letter.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    small_alphabets_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password should contain atleast 1 small letter.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    digit_pass: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Password should contain atleast 1 digit.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_field: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Field cannot be empty.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    number_field: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You can enter only numbers.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    positive_number: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Number should be greater than 0.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_field: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Fields cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_field: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Fields cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_email: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter Email.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    validate_email: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter a valid Email.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_country: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter country name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_country: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Country cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_country: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Country cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_city: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter city name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_city: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'City cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_city: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'City cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_state: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter state name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_state: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'State cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_state: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'State cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_proname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter product name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_proname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_proname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_catname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter category name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_catname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Category cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_catname: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Category cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_zip: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter zip code.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_zip: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Zip cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_zip: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Zip cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_username: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter zip code.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_username: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Zip cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_username: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Zip cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_date: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invalid date format.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_sku: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'SKU cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_sku: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'SKU cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_sku: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invalid SKU format.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_sku: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter SKU.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    validate_range: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Number is not in the valid range.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_address: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter address.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_address: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_address: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_company: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter company name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_company: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Company name cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_company: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Company name cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_phone: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Phone number is invalid.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_phone: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter phone number.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_phone: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Phone number cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_phone: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Phone number cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_brand: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter brand name.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_brand: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Brand name cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_brand: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Brand name cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_shipment: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter Shimpment.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_shipment: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipment cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    minchar_shipment: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipment cannot be less than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_ip: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invalid IP format.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_url: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invalid URL format.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_url: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please enter URL.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    empty_amount: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount cannot be empty.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    valid_amount: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount should be numeric.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    max_email: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Email cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_zip: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Zip should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_sku: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'SKU should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    max_url: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'URL cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    valid_percentage: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Percentage should be in number.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    between_percentage: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Percentage should be between 0 and 100.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_size: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Size cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_size: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Size should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_upc: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'UPC should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_upc: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'UPC cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_ean: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'EAN should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_ean: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'EAN cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar_bar: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Barcode should not have special characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_bar: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Barcode cannot be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    positive_amount: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Amount should be positive.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    maxchar_color: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Color could not be greater than {%d} characters.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_color: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Color is not valid.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    specialchar: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Special characters are not allowed.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    script: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Script tags are not allowed.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    style: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Style tags are not allowed.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    iframe: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Iframe tags are not allowed.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    not_image: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Uploaded file is not an image','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    image_size: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Uploaded file size must be less than {%d}.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    html_tags: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Field should not contain HTML tags.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    number_pos: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You can enter only positive numbers.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
",
    invalid_separator: "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invalid comma ({%d}) separated values.','mod'=>'kbmobileapp'),$_smarty_tpl ) );?>
"
});
<?php echo '</script'; ?>
>
            
<?php }
}
