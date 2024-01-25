{*
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FMM Modules
*  @copyright 2021 FME Modules
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{extends file="helpers/form/form.tpl"}
{block name="input"}
{if $input.name == 'categories'}
<div class="col-lg-10 rcg_max_height rpm_has_categories">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> </th>
                <th>
                    <span class="title_box">
                        {l s='ID' mod='restrictpaymentmethods'}
                    </span>
                </th>
                <th>
                    <span class="title_box">
                        {l s='Name' mod='restrictpaymentmethods'}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            {if !isset($categories) || empty($categories)}
            <tr>
                <td>{l s='No categories found.' mod='restrictpaymentmethods'}</td>
            </tr>
            {else}
            {foreach from=$categories item=category}
                <tr>
                <td>
                    <input type="checkbox" name="category[]" value="{$category.id_category}"{if isset($category.checked) && $category.checked > 0} checked="checked"{/if} />
                </td>
                <td>
                    {$category.id_category}
                </td>
                <td>
                    {$category.name}
                </td>
                </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>
</div>
{elseif $input.name == 'payments'}
<div class="col-lg-10 rcg_max_height">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> </th>
                <th>
                    <span class="title_box">
                        {l s='Module' mod='restrictpaymentmethods'}
                    </span>
                </th>
                <th>
                    <span class="title_box">
                        {l s='Name' mod='restrictpaymentmethods'}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            {if !isset($payment_methods) || empty($payment_methods)}
            <tr>
                <td>{l s='No payment methods found.' mod='restrictpaymentmethods'}</td>
            </tr>
            {else}
            {foreach from=$payment_methods item=payment}
                <tr>
                <td>
                    <input type="checkbox" name="payments[]" value="{$payment.name}"{if isset($payment.checked) && $payment.checked > 0} checked="checked"{/if} />
                </td>
                <td>
                    {$payment.name}
                </td>
                <td>
                    {$payment.title}
                </td>
                </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>
    <p class="help-block"><b>*</b> {l s='Please note that these payment methods will be disabled for below selected categories/products/cart totals.' mod='restrictpaymentmethods'}</p>
</div>
{elseif $input.name == 'products'}
<div class="rpm_has_products col-lg-10{if $ps_17 <= 0} ps_16_specific{/if}">
    <div class="col-lg-8 placeholder_holder">
        <input type="text" placeholder="Example: Blue XL shirt" onkeyup="getRelProducts(this);" />
        <div id="rel_holder"></div>
        <div id="rel_holder_temp">
            <ul>
                {if (!empty($products))}
                {foreach from=$products item=product}
                <li id="row_{$product->id|escape:'htmlall':'UTF-8'}" class="media"><div class="media-left"><img src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'home_default')|escape:'htmlall':'UTF-8'}" class="media-object image"></div><div class="media-body media-middle"><span class="label">{$product->name|escape:'htmlall':'UTF-8'}&nbsp;(ID:{$product->id|escape:'htmlall':'UTF-8'})</span><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$product->id|escape:'htmlall':'UTF-8'}" name="related_products[]"></li>
                {/foreach}
                {/if}
            </ul>
        </div>
    </div>
</div>
{elseif $input.name == 'brands'}
<div class="rpm_has_brands col-lg-10{if $ps_17 <= 0} ps_16_specific{/if}">
  <div class="col-lg-8 fmm_relative">
   <input type="text" placeholder="Example: ACME Corp" onkeyup="getBrands(this);" />
   <p class="help-block">{l s='Type brand name and select from result.' mod='restrictpaymentmethods'}</p>
   <div id="brands_collector">
     <ul>
     </ul>
   </div>
   <div id="brands_collection">
    <ul>
     {if (!empty($brands))}
     {foreach from=$brands item=brand}
       <li id="row_{$brand.id_brand|escape:'htmlall':'UTF-8'}"><div class="left">{$brand.id_brand|escape:'htmlall':'UTF-8'}</div><div class="middle">{$brand.name|escape:'htmlall':'UTF-8'}</div><div class="right"><i onclick="BrandDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$brand.id_brand|escape:'htmlall':'UTF-8'}" name="brands_filter[]"></li>
     {/foreach}
     {/if}
    </ul>
   </div>
  </div>
</div>
{elseif $input.name == 'range'}
<div class="rpm_has_range col-lg-10 rpm_bottom_padded">
 <div class="row">
    <div class="col-lg-4">
     <label class="control-label col-lg-3">{l s='From' mod='restrictpaymentmethods'}</label>
     <div class="col-lg-9">
        <div class="input-group fixed-width-lg">
            <span class="input-group-addon">{$currency_code|escape:'htmlall':'UTF-8'}</span>
            <input name="range_min" type="text" placeholder="50" value="{$obj->range_min}">
        </div>
    </div>
    </div>
    <div class="col-lg-4">
     <label class="control-label col-lg-3">{l s='To' mod='restrictpaymentmethods'}</label>
       <div class="col-lg-9">
        <div class="input-group fixed-width-lg">
            <span class="input-group-addon">{$currency_code|escape:'htmlall':'UTF-8'}</span>
            <input name="range_max" type="text" placeholder="320" value="{$obj->range_max}">
        </div>
       </div>
    </div>
    <div class="col-lg-12">
      <p class="help-block">{l s='Please leave it empty OR zero to disable range option. Please select below appropriate rules as well.' mod='restrictpaymentmethods'}</p>
    </div>
 </div>
</div>
{elseif $input.name == 'suppliers'}
<div class="rpm_has_suppliers col-lg-10{if $ps_17 <= 0} ps_16_specific{/if}">
  <div class="col-lg-8 fmm_relative_supp">
   <input type="text" placeholder="Example: ACME Corp" onkeyup="getSupps(this);" />
   <p class="help-block">{l s='Type supplier name and select from result.' mod='restrictpaymentmethods'}</p>
   <div id="supps_collector">
     <ul>
     </ul>
   </div>
   <div id="supps_collection">
    <ul>
     {if (!empty($suppliers))}
     {foreach from=$suppliers item=supplier}
       <li id="row_supp_{$supplier.id_supplier|escape:'htmlall':'UTF-8'}"><div class="left">{$supplier.id_supplier|escape:'htmlall':'UTF-8'}</div><div class="middle">{$supplier.name|escape:'htmlall':'UTF-8'}</div><div class="right"><i onclick="BrandDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$supplier.id_supplier|escape:'htmlall':'UTF-8'}" name="suppliers_filter[]"></li>
     {/foreach}
     {/if}
    </ul>
   </div>
  </div>
</div>
{elseif $input.name == 'cities'}
<div class="rpm_has_cities col-lg-10{if $ps_17 <= 0} ps_16_specific{/if}">
  <div class="col-lg-9 fmm_relative_cities">
   <div class="col-lg-9"><input type="text" id="fmm_city_plc" placeholder="Example: Miami" /></div>
   <div class="col-lg-3 fmmcities_adding"><i onclick="SelectThisCity(this);" class="material-icons add">add</i></div>
   <p class="help-block col-lg-12">{l s='Type city name and hit plus button on right of input field.' mod='restrictpaymentmethods'}</p>
   <div id="city_collector">
     <ul>
     </ul>
   </div>
   <div id="city_collection">
    <ul>
     {if (!empty($cities))}
     {foreach from=$cities item=city}
       <li id="row_city_{$city.name|escape:'htmlall':'UTF-8'}"><div class="middle">{$city.name|escape:'htmlall':'UTF-8'}</div><div class="right"><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$city.name|escape:'htmlall':'UTF-8'}" name="city_filter[]"></li>
     {/foreach}
     {/if}
    </ul>
   </div>
  </div>
</div>
{elseif $input.name == 'zips'}
<div class="rpm_has_zips col-lg-10{if $ps_17 <= 0} ps_16_specific{/if}">
  <div class="col-lg-9 fmm_relative_zips">
   <div class="col-lg-9"><input type="text" id="fmm_zips_plc" placeholder="Example: 44900" /></div>
   <div class="col-lg-3 fmmcities_adding"><i onclick="SelectThisZip(this);" class="material-icons add">add</i></div>
   <p class="help-block col-lg-12">{l s='Type zipcode and hit plus button on right of input field.' mod='restrictpaymentmethods'}</p>
   <div id="zips_collector">
     <ul>
     </ul>
   </div>
   <div id="zips_collection">
    <ul>
     {if (!empty($zips))}
     {foreach from=$zips item=zip}
       <li id="row_zips_{$zip.code|escape:'htmlall':'UTF-8'}"><div class="middle">{$zip.code|escape:'htmlall':'UTF-8'}</div><div class="right"><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$zip.code|escape:'htmlall':'UTF-8'}" name="zip_filter[]"></li>
     {/foreach}
     {/if}
    </ul>
   </div>
  </div>
</div>
{elseif $input.name == 'days'}
<div class="rpm_has_days col-lg-10">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> </th>
                <th>
                    <span class="title_box">
                        {l s='ID' mod='restrictpaymentmethods'}
                    </span>
                </th>
                <th>
                    <span class="title_box">
                        {l s='Day' mod='restrictpaymentmethods'}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            {if !isset($days) || empty($days)}
            <tr>
                <td>{l s='No days found.' mod='restrictpaymentmethods'}</td>
            </tr>
            {else}
            {foreach from=$days item=day}
                <tr>
                <td>
                    <input type="checkbox" name="days_filter[]" value="{$day.id_day}"{if isset($day.checked) && $day.checked > 0} checked="checked"{/if} />
                </td>
                <td>
                    {$day.id_day}
                </td>
                <td>
                    {$day.day}
                </td>
                </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>
    <p class="help-block"><b>*</b> {l s='Select none to avoid this condition.' mod='restrictpaymentmethods'}</p>
</div>
{else}
{$smarty.block.parent}
{/if}
<script>
var mod_url = "{$action_url}";
var _mod_url = "{$action_url_brands}";
var __mod_url = "{$action_url_supps}";
</script>
{/block}