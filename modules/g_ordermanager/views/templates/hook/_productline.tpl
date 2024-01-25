{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}


<div class="add_product_product_attribute_area">
    <div id="add_product_product_attribute_area_{$id_order_detail|escape:'html':'UTF-8'}" class="form-group" style="">
		<label>{l s='Product name' mod='g_ordermanager'}</label>
        <input type="hidden" id="productname_{$id_order_detail|escape:'html':'UTF-8'}" value="{$productname|escape:'html':'UTF-8'}" />
        <input type="text" name="productname" id="productname{$id_order_detail|escape:'html':'UTF-8'}" value="{$product.product_name|escape:'html':'UTF-8'}" />
        {if $combinations}
        <label>{l s='Combinations' mod='g_ordermanager'}</label>
		<select name="product_attribute_id" rel="{$id_order_detail|escape:'html':'UTF-8'}" class="change_product_attribute" id="add_product_product_attribute_id_{$id_order_detail|escape:'html':'UTF-8'}">
            {foreach $combinations as $key=>$combination}
                <option value="{$key|escape:'html':'UTF-8'}" {if $product_attribute_id == $key} selected="selected" {/if}>{$combination|escape:'html':'UTF-8'}</option>
            {/foreach}
        </select>
        {/if}
	</div>
</div>
