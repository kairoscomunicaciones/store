/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @author    knowband.com <support@knowband.com>
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
* @category  PrestaShop Module
*/

$(document).ready(function(){
    $('.product-add-to-cart').bind("DOMSubtreeModified", function() {
        if ($('.product-add-to-cart .add-to-cart').is(":disabled")) {
            $('#vss_buy_now').prop('disabled', true);
        } else {
            $('#vss_buy_now').prop('disabled', false);
        }
        $('.product-additional-info #vss_occ_buy_now_block').remove();
    });
    $("#vss_buy_now").mouseover(function() {
        $(this).css("background", ColorLuminance(button_background, -0.2));
    });

    $("#vss_buy_now").mouseout(function() {
        $(this).css("background", button_background);
    });
    disableBuyNowButton();
});

function disableBuyNowButton()
{
    if ($('.product-add-to-cart .add-to-cart').is(":disabled")) {
        $('#vss_buy_now').prop('disabled', true);
    } else {
        $('#vss_buy_now').prop('disabled', false);
    }
    $('.product-add-to-cart').after($('#vss_occ_buy_now_block'));
}

function getAttributes()
{
    var kb_attributes = {};
    $(".product-variants :input").each(function(){
        if($(this).is("select")){
            kb_attributes[$(this).attr('data-product-attribute')] = $(this).val();
        } else if($(this).is("input")) {
            if($(this).is(":radio")){
                if($(this).is(":checked") == true){
                    kb_attributes[$(this).attr('data-product-attribute')] = $(this).val();
                }
            } else {
                kb_attributes[$(this).attr('data-product-attribute')] = $(this).val();
            }
        }
    });
    return kb_attributes;
}

function buyNowButton() {
    if ($('.product-add-to-cart .add-to-cart').is(":disabled")) {
        return false;
    }
    var product_attribute = getAttributes();
    var id_product = document.getElementById('product_page_product_id').value;
    var quantity = document.getElementById('quantity_wanted').value;
    $.post(
        front_action_url,
        {
            id: id_product,
            group: product_attribute,
            qty: quantity
        },
        function (data) {
            window.location.replace(data);
        }
    );
}

function ColorLuminance(hex, lum) {
    // validate hex string
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    lum = lum || 0;

    // convert to decimal and change luminosity
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i * 2, 2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00" + c).substr(c.length);
    }
    return rgb;
}