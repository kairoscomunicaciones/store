{*
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
* @author    FMM Modules
* @copyright 2022 FMM Modules All right reserved
* @license   FMM Modules
*}
<table id="customfields_product_list" class="std table">
	<thead>
     <tr>
     	<th class="center">{l s='ID' mod='customfields'}</th>
     	<th class="left">{l s='Product' mod='customfields'}</th>
     	<th class="col-sm-1">{l s='Actions' mod='customfields'}</th>
	 </tr>
	</thead>
	<tbody id="customProducts">
	{if isset($products) AND $products}
		{foreach from=$products item=id_product}
			<tr id="selected_product_{$id_product|escape:'htmlall':'UTF-8'}" class="row_product">
				<input type="hidden" name="products[]" value="{$id_product|escape:'htmlall':'UTF-8'}" class="products"/>
				<td class="center">{$id_product|escape:'htmlall':'UTF-8'}</td>
				<td class="left">{Product::getProductName($id_product|escape:'htmlall':'UTF-8', null, $id_lang)}</td>
				<td class="center">
					<a class="btn btn-danger button" href="javascript:void(0);" title="{l s='Delete' mod='customfields'}" onclick="deleteProduct({$id_product|escape:'htmlall':'UTF-8'})">
						<i class="icon-trash"></i>
						{if $version < 1.6}
							<img src="{$smarty.const._PS_ADMIN_IMG_}delete.gif" alt="{l s='Delete' mod='customfields'}" />
						{/if}
					</a>
				</td> 
			</tr>
		{/foreach}
	{/if}
	</tbody>	
</table>

<script type="text/javascript">
	var link = "{$link->getAdminLink('AdminFields')}";
	var lang = '{$id_lang}';
	var version = "{$version}";
	var img = "";
	var delete_label = "{l s='Delete' mod='customfields' js=1}";
	if (parseFloat(version) < 1.6) {
		img = '<img src="../img/admin/delete.gif" />';
	}

	$(document).ready(function() {
		$("#customProducts").parent().removeClass("hide");
		var options =  {
			minChars: 3,
			max: 10,
			width: 500,
			selectFirst: false,
			scroll: false,
			dataType: "json",
			formatItem: function(data, i, max, value, term) {
				return value;
			},
			parse: function(data) {
				var mytab = new Array();
				for (var i = 0; i < data.length; i++) {
					mytab[mytab.length] = {
						data: data[i],
						value: data[i].id_product + ' - ' + data[i].pname
					};
				}
				return mytab;
			},
			extraParams: {
				action : 'searchProduct',
				ajaxSearch: true,
				id_lang: lang
			}
		};

		/* Autocomplete */
		$("#CUSTOMFIELDS_PRODUCT")
		.autocomplete(link, options)
		.result(function(event, data, formatted) {
			var $customProducts = $("#customProducts");
			if (data.id_product.length > 0 && data.pname.length > 0) {
				var exclude = [];
				var selected = $(".products");
				for (var i = 0; i < selected.length; i++) {
					exclude.push(selected[i].value);
				}
				var ps_div = "";
				if ($.inArray(data.id_product, exclude) == -1) {
					ps_div = '<tr id="selected_product_' + data.id_product + '">'
						+'<input type="hidden" name="products[]" value="' + data.id_product + '" class="products"/>'
						+'<td class="center">' + data.id_product + '</td>'
						+'<td class="left">'+ data.pname +'</td>'
						+'<td class="center">'
						+'<a class="btn btn-danger button" href="javascript:void(0);" title="' + delete_label + '" onclick="deleteProduct('+ data.id_product +')">'+ img +'<i class="icon-trash"></i></a>'
						'</td>'
					+'</tr>';
					$customProducts.show().html($customProducts.html() + ps_div);
				}

			}
		});
	});

	function deleteProduct(id) {
		$("#selected_product_"+id).remove();
	}
</script>