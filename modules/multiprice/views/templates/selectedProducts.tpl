{*
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 *}

<div id="ajax_choose_product" class="clearfix">
    <div class="input-group col-md-8">
        <span class="input-group-addon">{l s='search for product' mod='multiprice'}</span>
        <input type="text" id="product_autocomplete_input" name="product_autocomplete_input" autocomplete="off" class="ac_input">
        <span class="input-group-addon"><i class="icon-search"></i></span>
    </div>
</div>
<br/>
{if $selectedProducts == false}
    <div class="alert alert-warning no-products col-md-8">
        {l s='No products selected' mod='multiprice'}
    </div>
    <div id="addProducts" class="clearfix">
    </div>
{else}
    <div id="addProducts" class="clearfix">
        {foreach $selectedProducts as $key=>$product}
            <div id="selected_product_{$product}" class="form-control-static margin-form"><input type="hidden" name="mp_products[]" value="{$product}" class="mp_products"><button type="button" class="btn btn-default remove-product" name="{$product}" onclick="deleteProduct({$product})"><i class="icon-remove text-danger"></i></button>
                {multiprice::returnProductName($product)}
            </div>
        {/foreach}
    </div>
{/if}

<div class="alert alert-info">
    {l s='You can also display module for products associated with selected categories. Just select them below' mod='multiprice'}
</div>
<div class="panel">
    <h3>{l s='And / or select categories' mod='multiprice'}</h3>
    {$categoryTree nofilter}
</div>

<script>
    {literal}
    var img = '';
    var tokenProducts = "{/literal}{Tools::getAdminTokenLite('AdminProducts')}{literal}";
    $(document).ready(function () {
        $("#product_autocomplete_input")
            .autocomplete('ajax_products_list.php?token='+tokenProducts+'&exclude_packs=0&excludeVirtuals=0&excludeIds=99999999999', {
                minChars: 1,
                autoFill: true,
                max: 20,
                matchContains: true,
                mustMatch: false,
                scroll: false,
                cacheLength: 0,
                formatItem: function (item) {
                    return item[0] + ' - ' + item[1];
                }
            }).result(function (e, p) {
            var $divAccessories = $('#addProducts');
            var exclude = [];
            var selected = $('.mp_products');
            for (var i = 0; i < selected.length; i++)
                exclude.push(selected[i].value);
            var ps_div = '';

            if ($.inArray(p[1], exclude) == -1) {
                $('.no-products').hide();
                ps_div = '<div id="selected_product_' + p[1] + '" class="form-control-static margin-form"><input type="hidden" name="mp_products[]" value="' + p[1] + '" class="mp_products"/><button type="button" class="btn btn-default remove-product" name="' + p[1] + '" onclick="deleteProduct(' + p[1] + ')">' + img + '<i class="icon-remove text-danger"></i></button>&nbsp;' + p[0] + '</div>';
                $divAccessories.show().html($divAccessories.html() + ps_div);
            }
            $(this).val('');
        });
    });

    function deleteProduct(id) {
        $("#selected_product_" + id).remove();
    }
    {/literal}
</script>