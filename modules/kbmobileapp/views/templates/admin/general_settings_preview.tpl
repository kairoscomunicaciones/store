<div id="general_settings_mobile_preview" class="general_settings_mobile_preview" style="display:block">
    <div>
        {*<button type="button" class="rightText" onclick="showPreview()">{l s='Hide Preview' mod='kbmobileapp'}</button>*}
            <div class="front_preview" >
                    <div class="layout_gallery">
                            {*Start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                            <div class="topHeader" style="background:{$top_bar_color};">{*variable contains HTML content, Can not escape this*}
                                    <div class="leftmenu">
                                            <span class="toggleMenu"><i class="icon-bars"></i></span>
                                    </div>
                                    <div class="logo">
                                        {if $display_logo_on_title_bar eq 1}{*variable contains HTML content, Can not escape this*}
                                            <img src="{$title_bar_logo_url}"/>{*variable contains HTML content, Can not escape this*}
                                        {else}
                                            <p>{l s='Home' mod='kbmobileapp'}</p> 
                                        {/if}    
                                    </div>
                                    <div class="cartSection">
                                            <span class="cartIcon"><i class="icon-shopping-cart"></i></span>
                                    </div>
                                    <div class="searchBar">
                                            <span class="searchicon"><i class="icon-search"></i></span>
                                    </div>	

                            </div>
                            {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                            <img src="{$live_app_gif}">{*variable contains HTML content, Can not escape this*}
                            <div class="chatBoxIcon" style="display:none;background:{$top_bar_color};">{*variable contains HTML content, Can not escape this*}
                                <img src="{$chat_icon}">{*variable contains HTML content, Can not escape this*}
                            </div>
                    </div>
            </div>			
    </div>
</div>
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