<li class="slide" id="layout_component_{$id_layout}_{$id_component}">
    <span class="slideTitle">{l s='Banner Custom' mod='kbmobileapp'}</span>
    <span class="edit_component" id="edit_{$id_component}" onclick="editBannerCustomComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
    <span class="trash" id="delete_{$id_component}" onclick="trashBannerCustomComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
    <div class="banner_preview layout_div" >
        <div class="bannerSquare">
            {if $component_heading}
            <p class="comp_heading">{$component_heading}</p>
            {/if}
            <div class="bannerSquareList">
                <span class="BSSection">
                {foreach $custom_banners as $custom_banner}
                    <div style="float: left; width:{$custom_banner.width}%;">
                        <img class="bannerSquareBannerimg" src="{$custom_banner.image_url}" style="height: {$custom_banner.height}%; width: 100%; {if $custom_banner.top_inset} margin-top: {$custom_banner.top_inset}px; {/if} {if $custom_banner.bottom_inset} margin-bottom: {$custom_banner.bottom_inset}px; {/if} {if $custom_banner.left_inset} margin-left: {$custom_banner.left_inset}px; {/if} {if $custom_banner.right_inset} margin-right: {$custom_banner.right_inset}px; {/if}">
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</li> 
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2016 Knowband
* @license   see file: LICENSE.txt
*
*}
