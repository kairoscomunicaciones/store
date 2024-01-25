{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{if count($products)}
    <div class="orderproductimage_wp {if count($products) > 6} hasscrolbox {/if}">
        {foreach $products as $product}
            {if isset($product.image) && $product.image->id}
                <a class="orderproductimage_link" href="{$link->getImageLink('ordermanager', $product.image->id, 'large_default')|escape:'html':'UTF-8'}">
                    {$product.image_tag nofilter}{* <img /> tag content, no need to escape *}
                </a>
            {/if}
        {/foreach}
    </div>
{/if}