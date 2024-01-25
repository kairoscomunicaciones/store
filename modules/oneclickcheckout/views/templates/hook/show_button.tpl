{if (!isset($catalog_mode))}
    <div id="vss_occ_buy_now_block">
        <button type="button" name="vss_buy_now" class="btn btn-primary" id="vss_buy_now" style="background:{$background};color:{$text};border: 1px solid {$border};" onclick="buyNowButton()">
            <i class="material-icons">&#xe854;</i>
            {l s='Buy Now' mod='oneclickcheckout'}
        </button>
    </div>
{/if}

<script type="text/javascript">
var button_background = "{$background}";
var front_action_url = "{$link nofilter}"; {*Variable contains a URL, escape not required*}
</script>
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
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin tpl file
*}