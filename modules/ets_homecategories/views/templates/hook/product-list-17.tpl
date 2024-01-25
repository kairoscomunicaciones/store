{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($products) && $products}	
	<!-- Products list -->
	{if $wrapper}<div class="homecat_product_list_wrapper {if isset($class) && $class}hc-wrapper-{$class|escape:'html':'UTF-8'}{/if}"><div{if isset($id) && $id} id="{$id|escape:'html':'UTF-8'}"{/if} class="product_list products grid row{if isset($class) && $class} {$class|escape:'html':'UTF-8'}{/if}">{/if}
	{foreach from=$products item="product"}
          <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product|intval}" data-id-product-attribute="{$product.id_product_attribute|intval}" itemscope itemtype="http://schema.org/Product">
              <div class="thumbnail-container">
                {block name='product_thumbnail'}
                  <a href="{$product.url|escape:'html':'UTF-8'}" class="thumbnail product-thumbnail">
                    <img
                      src = "{$product.cover.bySize.home_default.url|escape:'html':'UTF-8'}"
                      alt = "{$product.cover.legend|escape:'html':'UTF-8'}"
                      data-full-size-image-url = "{$product.cover.large.url|escape:'html':'UTF-8'}"
                    >
                  </a>
                {/block}
            
                <div class="product-description">
                  {block name='product_name'}
                    <h1 class="h3 product-title" itemprop="name"><a href="{$product.url|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'|truncate:30:'...'}</a></h1>
                  {/block}
            
                  {block name='product_price_and_shipping'}
                    {if $product.show_price}
                      <div class="product-price-and-shipping">
                        {if $product.has_discount}
                          {hook h='displayProductPriceBlock' product=$product type="old_price"}
            
                          <span class="regular-price">{$product.regular_price|escape:'html':'UTF-8'}</span>
                          {*if $product.discount_type === 'percentage'}
                            <span class="discount-percentage">{$product.discount_percentage|escape:'html':'UTF-8'}</span>
                          {/if*}
                        {/if}
            
                        {hook h='displayProductPriceBlock' product=$product type="before_price"}
            
                        <span itemprop="price" class="price">{$product.price|escape:'html':'UTF-8'}</span>
            
                        {hook h='displayProductPriceBlock' product=$product type='unit_price'}
            
                        {hook h='displayProductPriceBlock' product=$product type='weight'}
                      </div>
                    {/if}
                  {/block}
                </div>
                {block name='product_flags'}
                  <ul class="product-flags">
                    {foreach from=$product.flags item=flag}
                      <li class="{$flag.type|escape:'html':'UTF-8'}">{$flag.label|escape:'html':'UTF-8'}</li>
                    {/foreach}
                    {if $product.show_price}
                        {if $product.has_discount}
                          {if $product.discount_type === 'percentage'}
                            <li class="discount_custom">
                                <span class="discount-percentage-custom">{$product.discount_percentage|escape:'html':'UTF-8'}</span>
                            </li>
                          {/if}
                        {/if}
                    {/if}
                  </ul>
                {/block}
                <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
                  <a
                    href="#"
                    class="quick-view"
                    data-link-action="quickview"
                  >
                    <i class="material-icons search">&#xE8B6;</i> {l mod='ets_homecategories' s='Quick view' d='Shop.Theme.Actions'}
                  </a>
            
                  {block name='product_variants'}
                    {if $product.main_variants}
                      {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                    {/if}
                  {/block}
                </div>
            
              </div>
            </article>
    {/foreach}
	{if $wrapper}</div>{if $ETS_HOMECAT_ENBLE_LOAD_MORE && count($products) >= (int)$ETS_HOMECAT_PER_PAGE}<div data-product-count="{count($products)|intval}" data-id-category="{$id_category|escape:'html':'UTF-8'}" class="homecat-load-more homecat-load-more-{$id_category|escape:'html':'UTF-8'}">{l s='Load more products' mod='ets_homecategories'}<i class="material-icons">&#xE315;</i></div>{/if}</div>{/if}
{else $wrapper}
    <div class="homecat_product_list_wrapper {if isset($class) && $class}hc-wrapper-{$class|escape:'html':'UTF-8'}{/if}">
        <div class="col-sm-12 col-xs-12"><div class="clearfix"></div><span class="alert alert-warning">{l s='No products available' mod='ets_homecategories'}</span></div>
    </div>     
{/if}