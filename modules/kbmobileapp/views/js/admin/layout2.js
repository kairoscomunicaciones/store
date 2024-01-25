/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 * @author    knowband.com <support@knowband.com>
 * @copyright 2015 Knowband
 * @license   see file: LICENSE.txt
 */
var num_of_component = 0;

$(document).ready(function () {
    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
    /*
     * To change header color in the preview device on changing the theme color.
     */
    $("input[name='KBMOBILEAPP_APP_THEME_COLOR'").change(function()  {
        var color = $(this).val();
        $('.topHeader').css('background', color);
        $('.chatBoxIcon').css('background', color);
    });
    /*
     * 
     * To prepend the preview button in the top header of the page
     */
    var button_html = "<li><a class='toolbar_btn' title="+ hide_preview +" onclick='showPreview();'><i class='process-icon-preview'></i><div id='rightText'>"+ hide_preview +"</div></a></li>";
			
    if ($('.btn-toolbar').children().find('#rightText').length==0){
        $('.btn-toolbar').children().prepend(button_html);
    } 

    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
    $('#top_category').click(function () {
        addTopCategory(0);
    });
    /*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
    $('#banner_custom').click(function () {
        /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
        addCustomBanner(0, 'Banner-custom');
        /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
    });
    /*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
    $('#banner_square').click(function () {
        addBannerSquare(0);
    });
    $('#banner_HS').click(function () {
        addBannerHorizontalslide(0);
    });
    $('#banner_grid').click(function () {
        addBannergrid(0);
    });
    $('#banner_countdown').click(function () {
        addBannerCountdown(0);
    });
    $('#product_square').click(function () {
        addProductSquare(0);
    });
    $('#product_HS').click(function () {
        addProductHorizontalslide(0);
    });
    $('#product_grid').click(function () {
        addProductGrid(0);
    });
    $('#product_LA').click(function () {
        addLastAccessed(0);
    });
});

/*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
/*
 *  Js to hide mobile preview from the bottom of the general settings screen to show save button clearly to the users.
 */
$(document).scroll(function(){
    if($(this).scrollTop() >= 1100){
        $('#general_settings_mobile_preview').addClass('itsBottom');
    }
    else{
        $('#general_settings_mobile_preview').removeClass('itsBottom');
    }
});
/*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 

function addTopCategory(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var top_category_html = $('.top_category').html();
        var id_layout = $('#id_layout').val();
        top_category_html = top_category_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        top_category_html = top_category_html.replace(/top_category_edit_component/g, 'edit_' + id);
        top_category_html = top_category_html.replace(/top_category_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To assign the unique ID to top 4 categories div for different component in different layouts
         */
        var id_component = id;
        for (var index=1; index<=4; index++) {
           var to_replace1 =  'top_category_'+ index;
           var to_replace2 =  'top_category_text_'+ index;
           var with_replace1 = 'top_category_'+ index +'_' + id_layout + '_' + id;
           var with_replace2 = 'top_category_text_'+ index +'_' + id_layout + '_' + id;
           top_category_html = top_category_html.replace(to_replace1, with_replace1);
           top_category_html = top_category_html.replace(to_replace2, with_replace2);
        }
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(top_category_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To append preview in the device for already added categories when any layout is edited.
         */
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getCategoryForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
           
        },
        success: function (data)
        {
            var c = JSON.parse(data);
            if (1) {

                $('input[id="category_heading1_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_1_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading1_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading2_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_2_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading2_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading3_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_3_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading3_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading4_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_4_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading4_'+default_language_id+'"]').val());
                });
                uploadtopCategoryfile(id_component, id_layout);
                var index = 1;
                if(c.Added_Categories != null) {
                $.each(c.Added_Categories, function( key, value ) {
                        
                    $('img[id="top_category_'+index+'_'+ id_layout + '_' + id_component + '"]').attr('src', value.image_src);
                    if (value.heading != '') {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').text(value.heading);
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'block');
    } else {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'none');
                    }
                    index++;
                });
                }
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getTopcategoryImageUrl=true",
                    data: 'id_layout=' + id_layout + '&id_component=' + id_component,
                    type: "post",
                    success: function (data)
                    {
                        var b = JSON.parse(data);
                        
                        if (b.length > 0) {
                            for (i = 0; i < b.length; i++) {
                                if (b[i]['name'] == 'sliderimage_1') {
                                    $("#sliderimage_1").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_2') {
                                    $("#sliderimage_2").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_3') {
                                    $("#sliderimage_3").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_4') {
                                    $("#sliderimage_4").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_5') {
                                    $("#sliderimage_5").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_6') {
                                    $("#sliderimage_6").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_7') {
                                    $("#sliderimage_7").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_8') {
                                    $("#sliderimage_8").attr('src', b[i]['value']);
                                }
                            }
                        }
                        deleteCategoryImage();
                        uploadtopCategoryfile();
                    }
                });
            }
            uploadtopCategoryfile();
        }
    });
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        var a = "top_category";
        var id_layout = $('#id_layout').val();
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var top_category_html = $('.top_category').html();
                        var id_layout = $('#id_layout').val();
                        top_category_html = top_category_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        top_category_html = top_category_html.replace(/top_category_edit_component/g, 'edit_' + id);
                        top_category_html = top_category_html.replace(/top_category_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        /*
                        * To assign the unique ID to top 4 categories div for different component in different layouts for a new category component
                        */
                        for (var index=1; index<=4; index++) {
                           var to_replace1 =  'top_category_'+ index;
                           var to_replace2 =  'top_category_text_'+ index;
                           var with_replace1 = 'top_category_'+ index +'_' + id_layout + '_' + id;
                           var with_replace2 = 'top_category_text_'+ index +'_' + id_layout + '_' + id;
                           top_category_html = top_category_html.replace(to_replace1, with_replace1);
                           top_category_html = top_category_html.replace(to_replace2, with_replace2);
                        }
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(top_category_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}

function showHideImageType(a) {
    //$("#image_type").on('change', function () {
    if ($(a).val() == 'url') {
        $('#image_url').parent().parent().show();
        $('#slideruploadedfile').parent().parent().parent().parent().show();
        $('#slideruploadedfile').parent().hide();
    } else if ($(a).val() == 'image') {
        $('#image_url').parent().parent().hide();
        $('#slideruploadedfile').parent().parent().parent().parent().show();
        $('#slideruploadedfile').parent().show();
    } else {
        $('#image_url').parent().parent().hide();
        $('#slideruploadedfile').parent().parent().parent().parent().hide();
    }
    //});

}
function getCategoryproducts(a) {
    var id_category = $('#category_id').val();
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getCategoryProducts=true",
        data: 'id_category=' + id_category,
        type: "post",
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#category_products').html('');
                $('#category_products').append(b.category_product_options);
            }
        }
    });

}
function showHideProductType(a) {
    if ($('#product_type').val() == 'category_products') {
        $('#product_list').closest('.form-group').hide();
        $('#category_id').closest('.form-group').show();
        $('#category_products').closest('.form-group').show();
        $('#layout_type_id').closest('.form-group').hide();
        /* changes made by vibhaas */
    } else if ($('#product_type').val() == 'custom_products') {
        $('#product_list').closest('.form-group').show();
        $('#category_products').closest('.form-group').hide();
        $('#category_id').closest('.form-group').hide();
        $('#layout_type_id').closest('.form-group').hide();
    } else {
        $('#product_list').closest('.form-group').hide();
        $('#category_products').closest('.form-group').hide();
        $('#category_id').closest('.form-group').hide();
        $('#layout_type_id').closest('.form-group').hide();
    }

}
function showUrlImage() {
//    $('.kb_error_message').remove();
//    $('input[name="image_url"]').removeClass('kb_error_field');
//    if ($('#image_url').val() != '') {
//        var image_url_err = velovalidation.checkUrl($('input[name="image_url"]'));
//        $('#sliderimage').attr('src', $('#image_url').val());
//    }
    $("#image_url").on('blur', function () {
        $('.kb_error_message').remove();
        $('input[name="image_url"]').removeClass('kb_error_field');
        if ($('#image_url').val() != '') {
            var image_url_err = velovalidation.checkUrl($('input[name="image_url"]'));
            $('#sliderimage').attr('src', $('#image_url').val());
            $('#sliderimage').show();
        }
    });

}
function showuploadedimage() {
    if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
    {
        var files = $(this)[0].files[0];
        var file_data = e.target.files;
        var file_mimetypes = [
            'image/gif',
            'image/jpeg',
            'image/png',
            'application/x-shockwave-flash',
            'image/psd',
            'image/bmp',
            'image/tiff',
            'application/octet-stream',
            'image/jp2',
            'image/iff',
            'image/vnd.wap.wbmp',
            'image/xbm',
            'image/vnd.microsoft.icon',
            'image/webp'
        ];

        var file_format = false;
        for (i = 0; i < file_mimetypes.length; i++) {
            if (files.type == file_mimetypes[i]) {
                file_format = true;
            }
        }

        if (!file_format)
        {
            $('input[name="slideruploadedfile"]').parent().append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
            slider_banner_file_error = true;

        } else if (files.size > default_file_size) {
            $('input[name="slideruploadedfile"]').parent().append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
            slider_banner_file_error = true;
        } else {
            slider_banner_file_error = false;
            if (typeof (FileReader) != "undefined") {

                var image_holder = $("#sliderimage");

                image_holder.empty();

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#sliderimage').attr('src', e.target.result);
                    $('#sliderimage').show();
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            }
            $('input[name="slideruploadedfile"]').parent().find('.kb_error_message').remove();
        }

    }
    else // Internet Explorer 9 Compatibility
    {
        $('#notification_error').html(invalid_file_txt);
        file_error = true;
    }
}
function showHideRedirectType(a) {
    if ($(a).val() == 'category') {
        $('#category_id').parent().parent().show();
        $('#layout_type_id').parent().parent().hide();
        $('#redirect_banner_product_name').parent().parent().hide();
        /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
        $('#redirect_banner_cms_name').parent().parent().hide();
        /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    } else if ($(a).val() == 'product') {
        $('#category_id').parent().parent().hide();
        $('#layout_type_id').parent().parent().hide();
        $('#redirect_banner_product_name').parent().parent().show();
        /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
        $('#redirect_banner_cms_name').parent().parent().hide();
        /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    /*changes made by vibhaas to add layout */
    } else if ($(a).val() == 'layout') {
        $('#category_id').parent().parent().hide();
        $('#redirect_banner_product_name').parent().parent().hide();
        $('#layout_type_id').parent().parent().show();
        /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
        $('#redirect_banner_cms_name').parent().parent().hide();
        /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    }
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    else if ($(a).val() == 'CMS') {
        $('#category_id').parent().parent().hide();
        $('#layout_type_id').parent().parent().hide();
        $('#redirect_banner_product_name').parent().parent().hide();
        $('#redirect_banner_cms_name').parent().parent().show();
    }
    /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    else {
        $('#category_id').parent().parent().hide();
        $('#layout_type_id').parent().parent().hide();
        $('#redirect_banner_product_name').parent().parent().hide();
        /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
        $('#redirect_banner_cms_name').parent().parent().hide();
        /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
}
}

function showHideTopCategoryRedirectType1(a)
{
    if($(a).val() == 1)
    {
        
        $('#category_id_1').parent().parent().hide();
        $('#layout_type_id_1').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_1').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_1').parent().parent().show();
        
    }
    
    else
    {
        $('#layout_type_id_1').parent().parent().hide();
       // $('#category_heading1_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_1').parent().parent().hide();
    }
}
function showHideTopCategoryRedirectType2(a)
{
    if($(a).val() == 1)
    {
        //$('#category_heading2_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_2').parent().parent().hide();
        $('#layout_type_id_2').parent().parent().show();
    }
    
    else if($(a).val() == 2)
    {
        $('#layout_type_id_2').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_2').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_2').parent().parent().hide();
        //$('#category_heading2_1').parent().parent().parent().parent().parent().show();
        $('#category_id_2').parent().parent().hide();
        
    }
}
function showHideTopCategoryRedirectType3(a)
{
    if($(a).val() == 1)
    {
       // $('#category_heading3_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_3').parent().parent().hide();
        $('#layout_type_id_3').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_3').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_3').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_3').parent().parent().hide();
       // $('#category_heading3_1').parent().parent().parent().parent().parent().show();
        $('#category_id_3').parent().parent().hide();
        
    }
}

function showHideTopCategoryRedirectType4(a)
{
    if($(a).val() == 1)
    {
       // $('#category_heading4_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_4').parent().parent().hide();
        $('#layout_type_id_4').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_4').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_4').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_4').parent().parent().hide();
      //  $('#category_heading4_1').parent().parent().parent().parent().parent().show();
        $('#category_id_4').parent().parent().hide();
        
    }
}

function showHideTopCategoryRedirectType5(a)
{
    if($(a).val() == 1)
    {
      //  $('#category_heading5_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_5').parent().parent().hide();
        $('#layout_type_id_5').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_5').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_5').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_5').parent().parent().hide();
    //    $('#category_heading5_1').parent().parent().parent().parent().parent().show();
        $('#category_id_5').parent().parent().hide();
        
    }
}

function showHideTopCategoryRedirectType6(a)
{
    if($(a).val() == 1)
    {
    //   $('#category_heading6_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_6').parent().parent().hide();
        $('#layout_type_id_6').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_6').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_6').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_6').parent().parent().hide();
    //    $('#category_heading6_1').parent().parent().parent().parent().parent().show();
        $('#category_id_6').parent().parent().hide();
        
    }
}

function showHideTopCategoryRedirectType7(a)
{
    if($(a).val() == 1)
    {
    //    $('#category_heading7_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_7').parent().parent().hide();
        $('#layout_type_id_7').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_7').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_7').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_7').parent().parent().hide();
    //    $('#category_heading7_1').parent().parent().parent().parent().parent().show();
        $('#category_id_7').parent().parent().hide();
        
    }
}

function showHideTopCategoryRedirectType8(a)
{
    if($(a).val() == 1)
    {
    //   $('#category_heading8_1').parent().parent().parent().parent().parent().hide();
        $('#category_id_8').parent().parent().hide();
        $('#layout_type_id_8').parent().parent().show();
    }
    else if($(a).val() == 2)
    {
        $('#layout_type_id_8').parent().parent().hide();
        //$('#category_heading1_1').parent().parent().parent().parent().parent().show();
        $('#category_id_8').parent().parent().show();
        
    }
    else
    {
        $('#layout_type_id_8').parent().parent().hide();
    //    $('#category_heading8_1').parent().parent().parent().parent().parent().show();
        $('#category_id_8').parent().parent().hide();
        
    }
}
/*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
function addCustomBanner(id, alias_name) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var banner_custom_html = $('.banner-custom').html();
        var id_layout = $('#id_layout').val();
        banner_custom_html = banner_custom_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        banner_custom_html = banner_custom_html.replace(/banner_custom_edit_component/g, 'edit_' + id);
        banner_custom_html = banner_custom_html.replace(/banner_custom_delete_component/g, 'delete_' + id);
        /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
        banner_custom_html = banner_custom_html.replace(/Banner-custom/g, alias_name);
        var id_component = id;
        banner_custom_html = banner_custom_html.replace(/bannerSquareBanner_elem_heading/g, 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id);
        banner_custom_html = banner_custom_html.replace(/bannerSquare_comp_heading/g, 'bannerSquare_comp_heading_' + id_layout + '_' + id);
        banner_custom_html = banner_custom_html.replace(/bannerSquareBannerimg/g, 'bannerSquareBannerimg_' + id_layout + '_' + id);
        banner_custom_html = banner_custom_html.replace(/bannerSList/g, 'bannerSList_' + id_layout + '_' + id);
        /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
        //$('.slides').append(banner_custom_html);
        //console.log(banner_custom_html);
        $('.slides').append(banner_custom_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
            url: ajaxaction + "&configure=kbmobileapp&get_custom_banner=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
            type: "post",
        beforeSend: function () {

        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                
                $('#layout_type_id').parent().parent().hide();
                

                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'banner_custom');
                $('input[id="heading_of_banner_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="bannerSquareBanner_elem_heading_'+id_layout+ '_'+id_component+'"]').html($('input[id="heading_of_banner_'+default_language_id+'"]').val());
                });
                $('input[id="banner_heading_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="bannerSquare_comp_heading_'+id_layout+ '_'+id_component+'"]').html($('input[id="banner_heading_'+default_language_id+'"]').val());
                });
                var element_type = 'bannerSquareBannerimg_';
                uploadfile(id_layout, id_component, element_type);
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var element_type = 'bannerSquareBannerimg_';
                $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').parent().remove();
                $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                $.each( b.Added_Banners, function( key, value ) {
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                    $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                    if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                            $('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<span class="BSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading" id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            //$('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<p id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'">');
               }
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                    } else {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image).css("width", "100%");
        });
                if (b.component_heading!=null) {
                    $('p[id="bannerSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                showUrlImage();
        
                autoCompleteProduct();
                setDate();
                setColor();
            }
            }
        });
//        /* Changes done By Ashish on 16th April 2020 for Custom Banner View */
//        $.ajax({
//            url: ajaxaction + "&configure=kbmobileapp&get_custom_banner=true",
//            data: 'id_component=' + id + "&id_layout=" + $('#id_layout').val(),
//            type: "post",
//            async: false,
//            success: function(response){
//                $('.slides').append(response);
//                preview_content();
//                scrollToBottom();
//               }
//        });
//        /* End Changes done By Ashish on 16th April 2020 for Custom Banner View */
        
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();
        var a = "banner_custom";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var banner_custom_html = $('.banner-custom').html();
                        var id_layout = $('#id_layout').val();
                        banner_custom_html = banner_custom_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        banner_custom_html = banner_custom_html.replace(/banner_custom_edit_component/g, 'edit_' + id);
                        banner_custom_html = banner_custom_html.replace(/banner_custom_delete_component/g, 'delete_' + id);
                        /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
                        banner_custom_html = banner_custom_html.replace(/Banner-custom/g, alias_name);
                        /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
                        banner_custom_html = banner_custom_html.replace(/bannerSquareBanner_elem_heading/g, 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id);
                        banner_custom_html = banner_custom_html.replace(/bannerSquare_comp_heading/g, 'bannerSquare_comp_heading_' + id_layout + '_' + id);
                        banner_custom_html = banner_custom_html.replace(/bannerSquareBannerimg/g, 'bannerSquareBannerimg_' + id_layout + '_' + id);
                        banner_custom_html = banner_custom_html.replace(/bannerSList/g, 'bannerSList_' + id_layout + '_' + id);
                        $('.slides').append(banner_custom_html);
                        preview_content();
                        scrollToBottom();

                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
/*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
function addBannerSquare(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var banner_square_html = $('.banner-slide').html();
        var id_layout = $('#id_layout').val();
        banner_square_html = banner_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        banner_square_html = banner_square_html.replace(/banner_square_edit_component/g, 'edit_' + id);
        banner_square_html = banner_square_html.replace(/banner_square_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To assign the unique ID to banner square slides for different component in different layouts
         */
        var id_component = id;
        banner_square_html = banner_square_html.replace(/bannerSquareBanner_elem_heading/g, 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id);
        banner_square_html = banner_square_html.replace(/bannerSquare_comp_heading/g, 'bannerSquare_comp_heading_' + id_layout + '_' + id);
        banner_square_html = banner_square_html.replace(/bannerSquareBannerimg/g, 'bannerSquareBannerimg_' + id_layout + '_' + id);
        banner_square_html = banner_square_html.replace(/bannerSList/g, 'bannerSList_' + id_layout + '_' + id);
        
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(banner_square_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {

        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                
                $('#layout_type_id').parent().parent().hide();
                

                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'banner_square');
                $('input[id="heading_of_banner_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="bannerSquareBanner_elem_heading_'+id_layout+ '_'+id_component+'"]').html($('input[id="heading_of_banner_'+default_language_id+'"]').val());
                });
                $('input[id="banner_heading_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="bannerSquare_comp_heading_'+id_layout+ '_'+id_component+'"]').html($('input[id="banner_heading_'+default_language_id+'"]').val());
                });
                var element_type = 'bannerSquareBannerimg_';
                uploadfile(id_layout, id_component, element_type);
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var element_type = 'bannerSquareBannerimg_';
                $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').parent().remove();
                $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                $.each( b.Added_Banners, function( key, value ) {
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                    $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                    if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                            $('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<span class="BSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading" id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            //$('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<p id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'">');
                    } 
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
    } else {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image).css("width", "100%");
                });
                if (b.component_heading!=null) {
                    $('p[id="bannerSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                showUrlImage();
                
                autoCompleteProduct();
                setDate();
                setColor();
            }
            }
        });
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();
        var a = "banner_square";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var banner_square_html = $('.banner-slide').html();
                        var id_layout = $('#id_layout').val();
                        banner_square_html = banner_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        banner_square_html = banner_square_html.replace(/banner_square_edit_component/g, 'edit_' + id);
                        banner_square_html = banner_square_html.replace(/banner_square_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
//                        banner_square_html = banner_square_html.replace(/bannerSquareBanner/g, 'bannerSquareBanner_' + id_layout + '_' + id);
//                        banner_square_html = banner_square_html.replace(/bannerSquareBanner_elem_heading/g, 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id);
//                        banner_square_html = banner_square_html.replace(/bannerSquare_comp_heading/g, 'bannerSquare_comp_heading_' + id_layout + '_' + id);
                        banner_square_html = banner_square_html.replace(/bannerSquareBanner_elem_heading/g, 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id);
                        banner_square_html = banner_square_html.replace(/bannerSquare_comp_heading/g, 'bannerSquare_comp_heading_' + id_layout + '_' + id);
                       // banner_square_html = banner_square_html.replace(/img id="bannerSquareBannerimg"/g, 'img id="bannerSquareBannerimg_' + id_layout + '_' + id);
                        banner_square_html = banner_square_html.replace(/bannerSquareBannerimg/g, 'bannerSquareBannerimg_' + id_layout + '_' + id);
                        //banner_square_html = banner_square_html.replace(/banner_preview layout_div/g, 'banner_preview layout_div_' + id_layout + '_' + id);
                        banner_square_html = banner_square_html.replace(/bannerSList/g, 'bannerSList_' + id_layout + '_' + id);
                        
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(banner_square_html);
                        preview_content();
                        scrollToBottom();

                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addBannerHorizontalslide(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var Hbanner_square_html = $('.Hbanner-slide').html();
        var id_layout = $('#id_layout').val();
        Hbanner_square_html = Hbanner_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/banner_horizontal_edit_component/g, 'edit_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/banner_horizontal_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To assign the unique ID to banner horizontal slides for different component in different layouts
         */
        var id_component = id;
        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_comp_heading/g, 'bannerHorizontalSlide_comp_heading_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlideimg1/g, 'bannerHorizontalSlideimg1_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlideimg2/g, 'bannerHorizontalSlideimg2_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_elem_heading1/g, 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_elem_heading2/g, 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id);
        Hbanner_square_html = Hbanner_square_html.replace(/slidingBList/g, 'slidingBList_' + id_layout + '_' + id);
        
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(Hbanner_square_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var element_type = 'bannerHorizontalSlideimg_';
                var index = 1;
                $.each( b.Added_Banners, function( key, value ) {
                    $('p[id="bannerHorizontalSlide_elem_heading_'+ id_layout + '_'+ id_component +'"]').remove();
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                    var element_type1 = 'bannerHorizontalSlideimg1_';

                    var element_type2 = 'bannerHorizontalSlideimg2_';

                    $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').parent().remove();
                    $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').parent().remove();    
                    $('p[id="bannerHorizontalSlide_elem_heading1_'+ id_layout + '_'+ id_component +'"]').remove();
                    $('p[id="bannerHorizontalSlide_elem_heading2_'+ id_layout + '_'+ id_component +'"]').remove();
                    if (index == 1) {
                        element_type = 'bannerHorizontalSlideimg1_';
                        if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                
                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList1').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            }
                        if (value.heading != '') {
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                        } else {
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                        }
                        $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                   } else if (index == 2) {
                        element_type = 'bannerHorizontalSlideimg2_';
                        if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                
                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList2').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            }  
                        if (value.heading != '') {
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                        } else {
                            $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                        }
                        $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                    } 
                    index++;
                });
                if (b.component_heading!=null) {
                    $('h4[id="bannerHorizontalSlide_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                showUrlImage();
                
                autoCompleteProduct();
                setDate();
                setColor();
            }
        }
    });
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "banner_horizontal_slider";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var Hbanner_square_html = $('.Hbanner-slide').html();
                        var id_layout = $('#id_layout').val();
                        Hbanner_square_html = Hbanner_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/banner_horizontal_edit_component/g, 'edit_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/banner_horizontal_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_comp_heading/g, 'bannerHorizontalSlide_comp_heading_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlideimg1/g, 'bannerHorizontalSlideimg1_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlideimg2/g, 'bannerHorizontalSlideimg2_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_elem_heading1/g, 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/bannerHorizontalSlide_elem_heading2/g, 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id);
                        Hbanner_square_html = Hbanner_square_html.replace(/slidingBList/g, 'slidingBList_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(Hbanner_square_html);
                        preview_content();
                        scrollToBottom();

                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addBannergrid(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var banner_Grid_html = $('.banner-grid').html();
        var id_layout = $('#id_layout').val();
        banner_Grid_html = banner_Grid_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        banner_Grid_html = banner_Grid_html.replace(/banner_grid_edit_component/g, 'edit_' + id);
        banner_Grid_html = banner_Grid_html.replace(/banner_grid_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To assign the unique ID to banner grid slides for different component in different layouts
         */
        var id_component = id;
        banner_Grid_html = banner_Grid_html.replace(/bannerGrid_comp_heading/g, 'bannerGrid_comp_heading_' + id_layout + '_' + id);
        banner_Grid_html = banner_Grid_html.replace(/bannerGRow1/g, 'bannerGRow1_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(banner_Grid_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
           
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'banner_grid');
                var element_type = 'bannerGridimg1_';
                var template_html = $('#bannerGRow').html();
                $.each( b.Added_Banners, function(key, value) {
                    $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').find('#bannerGridimg').parent().remove();    
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/bannerGridimg1/g, 'bannerGridimg1_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);
                    productinfo_html = productinfo_html.replace(/bannerGrid_elem_heading/g, 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);
                    
                    
                    if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').length == "0") {
                        $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                    }
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').attr('src', value.Image);
                    
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').text(value.heading);
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'block');
    } else {
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'none');
                    }
                });
                $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"] .bannerGridList').each(function () {    
                        if ($(this).children().length == 0){
                            $(this).remove();
                        }
                });
                /* Changes done by Tanisha on 28-Nov-2022 */
                 if (b.product_name!='') {
                     $('redirect_banner_product_name').html(b.product_name);
                 }else{
                     $('redirect_banner_product_name').html('');
                 }
                 
                if (b.component_heading!=null) {
                    $('h4[id="bannerGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                showUrlImage();
                
                autoCompleteProduct();
                setDate();
                setColor();
            }
        }
    });
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "banners_grid";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var banner_Grid_html = $('.banner-grid').html();
                        var id_layout = $('#id_layout').val();
                        banner_Grid_html = banner_Grid_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        banner_Grid_html = banner_Grid_html.replace(/banner_grid_edit_component/g, 'edit_' + id);
                        banner_Grid_html = banner_Grid_html.replace(/banner_grid_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        banner_Grid_html = banner_Grid_html.replace(/bannerGrid_comp_heading/g, 'bannerGrid_comp_heading_' + id_layout + '_' + id);
                        //banner_Grid_html = banner_Grid_html.replace(/bannerGridimg/g, 'bannerGridimg_' + id_layout + '_' + id);
                        banner_Grid_html = banner_Grid_html.replace(/bannerGRow1/g, 'bannerGRow1_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(banner_Grid_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);

                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }

}
function addBannerCountdown(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var banner_countdown_html = $('.banner-countdown').html();
        var id_layout = $('#id_layout').val();
        banner_countdown_html = banner_countdown_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        banner_countdown_html = banner_countdown_html.replace(/banner_countdown_edit_component/g, 'edit_' + id);
        banner_countdown_html = banner_countdown_html.replace(/banner_countdown_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        /*
         * To assign the unique ID to banner countdown slides for different component in different layouts
         */
        var id_component = id;
        banner_countdown_html = banner_countdown_html.replace(/bannerCountdown_comp_heading/g, 'bannerCountdown_comp_heading_' + id_layout + '_' + id);
        banner_countdown_html = banner_countdown_html.replace(/bannerCountdownList/g, 'bannerCountdownList_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(banner_countdown_html);
        preview_content();
        scrollToBottom();
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            
        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'banner_countdown');
                var element_type = 'bannerCountdownimg1_';
                $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                $.each( b.Added_Banners, function( key, value ) {
                    $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').find('#bannerCountdownimg').parent().remove();  
                    var template_html = $('.countdownlistContentContainer1').html();
                    template_html = template_html.replace(/bannerCountdownimg1/g, 'bannerCountdownimg1_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                    template_html = template_html.replace(/bannerCountdown_elem_heading/g, 'bannerCountdown_elem_heading_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                    template_html = template_html.replace(/countdown_banner_img_url/g, value.Image);
                    if (value.is_enabled_background_color == "1") {
                        template_html = template_html.replace(/background_color_of_timer_text/g, value.background_color);
    } else {
                        template_html = template_html.replace(/background_color_of_timer_text/g, 'transparent');
                    }    
                    if ($('div[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                        $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').append(template_html);
                    }
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                    
                    } else {
                        $('div[id="'+ 'bannerCountdownimg1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('bottom', '5px');
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
                    
                });
                if (b.component_heading!=null) {
                    $('h4[id="bannerCountdown_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
        }
    });
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "banners_countdown";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var banner_countdown_html = $('.banner-countdown').html();
                        var id_layout = $('#id_layout').val();
                        banner_countdown_html = banner_countdown_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        banner_countdown_html = banner_countdown_html.replace(/banner_countdown_edit_component/g, 'edit_' + id);
                        banner_countdown_html = banner_countdown_html.replace(/banner_countdown_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        banner_countdown_html = banner_countdown_html.replace(/bannerCountdown_comp_heading/g, 'bannerCountdown_comp_heading_' + id_layout + '_' + id);
                       // banner_countdown_html = banner_countdown_html.replace(/bannerCountdownimg/g, 'bannerCountdownimg_' + id_layout + '_' + id);
                        banner_countdown_html = banner_countdown_html.replace(/bannerCountdownList/g, 'bannerCountdownList_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(banner_countdown_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addProductSquare(id) {

    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var product_square_html = $('.product-square').html();
        var id_layout = $('#id_layout').val();
        var id_component = id;
        product_square_html = product_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        product_square_html = product_square_html.replace(/product_square_edit_component/g, 'edit_' + id);
        product_square_html = product_square_html.replace(/product_square_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/   
        /*
         * To assign the unique ID to product square slides for different component in different layouts
         */
        product_square_html = product_square_html.replace(/productSquare_comp_heading/g, 'productSquare_comp_heading_' + id_layout + '_' + id);
        product_square_html = product_square_html.replace(/productSList/g, 'productSList_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
        $('.slides').append(product_square_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
            $.ajax({
            url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
            data: 'id_layout=' + id_layout + '&id_component=' + id_component,
            type: "post",
            beforeSend: function () {
                
            },
            success: function (data)
            {
                var b = JSON.parse(data);
                if (1) {
                    
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('.panel-footer').attr('element_type', 'productSquareimg');
                    var element_type = 'productSquareimg1_';
                    var template_html = $('.productSquareimg1').parent().html();
                    $.each( b.added_Products.products_for_preview, function(key, value) {
                        var element_type = 'productSquareimg1_';
                        var template_html = $('.productSquareimg1').parent().html();
                        var img_id = element_type + id_layout + '_' + id_component;
                        var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                        var productinfo_html = template_html;
                        productinfo_html = productinfo_html.replace(/productSquareimg1/g, 'productSquareimg1_' + value.id);
                        productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                        productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                        if ($('img[id="'+ element_type + value.id +'"]').length == "0") {
                            $('div[id="productSList_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                        }
                        $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                        $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                        $('img[id="'+ element_type + value.id +'"]').attr('src', value.src);
                        $('img[id="'+ element_type + value.id +'"]').css('width','282px');
                        $('img[id="'+ element_type + value.id +'"]').css('height','261px');
                    });
                    if (b.added_Products.products_for_preview.length > 0) {
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').next().remove();
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').remove();
                    }
                    if (b.added_Products.component_heading_preview!=null) {
                        $('h4[id="productSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/

                }
            }
        });
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "products_square";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var product_square_html = $('.product-square').html();
                        var id_layout = $('#id_layout').val();
                        product_square_html = product_square_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        product_square_html = product_square_html.replace(/product_square_edit_component/g, 'edit_' + id);
                        product_square_html = product_square_html.replace(/product_square_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/   
                        product_square_html = product_square_html.replace(/productSquare_comp_heading/g, 'productSquare_comp_heading_' + id_layout + '_' + id);
                        product_square_html = product_square_html.replace(/productSList/g, 'productSList_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/ 
                        $('.slides').append(product_square_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addProductHorizontalslide(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var Hproduct_slide_html = $('.Hproduct-slide').html();
        var id_layout = $('#id_layout').val();
        Hproduct_slide_html = Hproduct_slide_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/product_horizontal_edit_component/g, 'edit_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/product_horizontal_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        var id_component = id;
        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingPRow1/g, 'slidingPRow1_' + id_layout + '_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProducts_comp_heading/g, 'slidingProducts_comp_heading_' + id_layout + '_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg2/g, 'slidingProductsimg2_' + id_layout + '_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg1/g, 'slidingProductsimg1_' + id_layout + '_' + id);
        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg3/g, 'slidingProductsimg3_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        
        $('.slides').append(Hproduct_slide_html);
        preview_content();
        scrollToBottom();
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            
        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'slidingProducts');
                    var element_type = 'slidingProductsimg';
                    var template_html = $('#slidingPRow').html();
                    var index = 1;
                    $.each( b.added_Products.products_for_preview, function( key, value ) {
                       // $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'slidingProductsimg1_';
                        var element_type2 = 'slidingProductsimg2_';
                        var element_type3 = 'slidingProductsimg3_';    
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').remove();
                        if (index == 1) {
                            element_type = 'slidingProductsimg1_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 2) {
                            element_type = 'slidingProductsimg2_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 3) {
                            element_type = 'slidingProductsimg3_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } 
                        index++;
                    });
                    if (b.added_Products.component_heading_preview!=null) {
                        $('h4[id="slidingProducts_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
               
            }
        }
    });
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "products_horizontal";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var Hproduct_slide_html = $('.Hproduct-slide').html();
                        var id_layout = $('#id_layout').val();
                        Hproduct_slide_html = Hproduct_slide_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/product_horizontal_edit_component/g, 'edit_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/product_horizontal_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingPRow1/g, 'slidingPRow1_' + id_layout + '_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProducts_comp_heading/g, 'slidingProducts_comp_heading_' + id_layout + '_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg2/g, 'slidingProductsimg2_' + id_layout + '_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg1/g, 'slidingProductsimg1_' + id_layout + '_' + id);
                        Hproduct_slide_html = Hproduct_slide_html.replace(/slidingProductsimg3/g, 'slidingProductsimg3_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(Hproduct_slide_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addProductGrid(id) {

    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var product_Grid_html = $('.product-grid').html();
        var id_layout = $('#id_layout').val();
        product_Grid_html = product_Grid_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        product_Grid_html = product_Grid_html.replace(/product_grid_edit_component/g, 'edit_' + id);
        product_Grid_html = product_Grid_html.replace(/product_grid_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        var id_component = id;
        product_Grid_html = product_Grid_html.replace(/productGrid_comp_heading/g, 'productGrid_comp_heading_' + id_layout + '_' + id);
      //  product_Grid_html = product_Grid_html.replace(/productGridimg/g, 'productGridimg_' + id_layout + '_' + id);
//        product_Grid_html = product_Grid_html.replace(/productGridRowContainer/g, 'productGridRowContainer_' + id_layout + '_' + id);
//        product_Grid_html = product_Grid_html.replace(/productGridRowContainer/g, 'productGridRowContainer_' + id_layout + '_' + id);
        product_Grid_html = product_Grid_html.replace(/productGRow1/g, 'productGRow1_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(product_Grid_html);
        preview_content();
        scrollToBottom();
//        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            
        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'productGridimg');
                var element_type = 'productGridimg_';
                var template_html = $('#productGRow').html();
                var id_ProductArray = [];
////                $(".productGridRow").remove();
                $.each( b.added_Products.products_for_preview, function(key, value) {
//                    $(".productGridRow1").remove();
                    $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"]').find('#productGridimg1').parent().remove();  
                    id_ProductArray.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                    var img_id = element_type + id_layout + '_' + id_component;
                    var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                    productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                    productinfo_html = productinfo_html.replace(/productGridimg/g, 'productGridimg_' +id_layout + '_' + id_component+ '_' +value.id);
                    if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.id +'"]').length == "0") {
                            $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                    }
                    $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                    $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.id +'"]').attr('src', value.src);
                });
                
                if (b.added_Products.component_heading_preview!=null) {
                    $('p[id="productGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
        }
    });
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "products_grid";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var product_Grid_html = $('.product-grid').html();
                        var id_layout = $('#id_layout').val();
                        product_Grid_html = product_Grid_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        product_Grid_html = product_Grid_html.replace(/product_grid_edit_component/g, 'edit_' + id);
                        product_Grid_html = product_Grid_html.replace(/product_grid_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        product_Grid_html = product_Grid_html.replace(/productGrid_comp_heading/g, 'productGrid_comp_heading_' + id_layout + '_' + id);
                        //product_Grid_html = product_Grid_html.replace(/productGridimg/g, 'productGridimg_' + id_layout + '_' + id);
                       // product_Grid_html = product_Grid_html.replace(/productGridRowContainer/g, 'productGridRowContainer_' + id_layout + '_' + id);
                        product_Grid_html = product_Grid_html.replace(/productGRow1/g, 'productGRow1_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(product_Grid_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }
}
function addLastAccessed(id) {
    if (id) {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        $('#number_of_component').val(num_of_component);
        var last_accessed_html = $('.product-lastAccess').html();
        var id_layout = $('#id_layout').val();
        last_accessed_html = last_accessed_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
        last_accessed_html = last_accessed_html.replace(/last_access_delete_component/g, 'delete_' + id);
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        var id_component = id;
        last_accessed_html = last_accessed_html.replace(/slidingRecentPRow1/g, 'slidingRecentPRow1_' + id_layout + '_' + id);
        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg2/g, 'slidingRecentProductsimg2_' + id_layout + '_' + id);
        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg1/g, 'slidingRecentProductsimg1_' + id_layout + '_' + id);
        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg3/g, 'slidingRecentProductsimg3_' + id_layout + '_' + id);
        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $('.slides').append(last_accessed_html);
        preview_content();
        scrollToBottom();
        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
        $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getRecentProducts=true",
        type: "post",
        beforeSend: function () {
            
        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var element_type = 'slidingRecentProductsimg';
                var template_html = $('#slidingRecentPRow').html();
                    var index = 1;
                    $.each( b.added_Products.products_for_preview, function( key, value ) {
                       // $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'slidingRecentProductsimg1_';
                        var element_type2 = 'slidingRecentProductsimg2_';
                        var element_type3 = 'slidingRecentProductsimg3_';    
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').remove();
                        if (index == 1) {
                            element_type = 'slidingRecentProductsimg1_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList1').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList1').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 2) {
                            element_type = 'slidingRecentProductsimg2_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList2').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 3) {
                            element_type = 'slidingRecentProductsimg3_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList3').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList3').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } 
                        index++;
                    });
                    
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
               
            }
        }
    });
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    } else {
        num_of_component = parseInt($('#number_of_component').val());
        num_of_component = num_of_component + 1;
        var id_layout = $('#id_layout').val();

        var a = "products_recent";
        if (num_of_component <= 20) {
            $('#number_of_component').val(num_of_component);
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&assign_component_id=true",
                data: 'component_type=' + a + '&id_layout=' + id_layout,
                type: "post",
                success: function (data)
                {
                    var id = data;
                    if (id) {
                        var last_accessed_html = $('.product-lastAccess').html();
                        var id_layout = $('#id_layout').val();
                        last_accessed_html = last_accessed_html.replace(/component_position/g, 'layout_component_' + id_layout + '_' + id);
                        last_accessed_html = last_accessed_html.replace(/last_access_delete_component/g, 'delete_' + id);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        var id_component = id;
                        last_accessed_html = last_accessed_html.replace(/slidingRecentPRow1/g, 'slidingRecentPRow1_' + id_layout + '_' + id);
                        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg2/g, 'slidingRecentProductsimg2_' + id_layout + '_' + id);
                        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg1/g, 'slidingRecentProductsimg1_' + id_layout + '_' + id);
                        last_accessed_html = last_accessed_html.replace(/slidingRecentProductsimg3/g, 'slidingRecentProductsimg3_' + id_layout + '_' + id);
                        /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $('.slides').append(last_accessed_html);
                        preview_content();
                        scrollToBottom();
                        showSuccessMessage(component_add);
                        /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                        $.ajax({
                        url: ajaxaction + "&configure=kbmobileapp&getRecentProducts=true",
                        type: "post",
                        beforeSend: function () {

                        },
                        success: function (data)
                        {
                            var b = JSON.parse(data);
                            if (1) {

                                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                                var element_type = 'slidingRecentProductsimg';
                                var template_html = $('#slidingRecentPRow').html();
                                    var index = 1;
                                    $.each( b.added_Products.products_for_preview, function( key, value ) {
                                       // $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                                        var element_type1 = 'slidingRecentProductsimg1_';
                                        var element_type2 = 'slidingRecentProductsimg2_';
                                        var element_type3 = 'slidingRecentProductsimg3_';    
                                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').next().remove();
                                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').remove();
                                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').next().remove();
                                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').remove();
                                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').next().remove();
                                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').remove();
                                        if (index == 1) {
                                            element_type = 'slidingRecentProductsimg1_';
                                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList1').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList1').append(productinfo_html);
                    }
                                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                                        } else if (index == 2) {
                                            element_type = 'slidingRecentProductsimg2_';
                                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList2').append(productinfo_html);
                }
                                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                                        } else if (index == 3) {
                                            element_type = 'slidingRecentProductsimg3_';
                                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList3').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                                $('div[id="slidingRecentPRow1_'+ id_layout + '_' + id_component + '"] > #productRecentSlideList3').append(productinfo_html);
                                            }
                                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                                       } 
                                        index++;
            });

                                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/

                            }
                        }
                    });
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    }
                }
            });

        } else {
            showErrorMessage(limit_reached);
        }
    }

}
function settingFunction(setting) {
    //$(setting).next('.file-uploader').slideToggle();
}
function trashFunction(trash) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    $(trash).parents('.slide').remove();
    preview_content();
}
function preview_content() {
    $('.iframe_html').html('');
    var Display_content = $('.slides').html();
    $('.iframe_html').append(Display_content);
    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    if( $.trim($('.iframe_html').html()) == '') {
        $('.iframe_html').append('<img class="pic"src="' + home_pic_url + '"  style="width:100%; height:484px">');
    };
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/   
}


function scrollToBottom() {

    var content = jQuery(".layout_gallery"), autoScrollTimer = 200, autoScrollTimerAdjust, autoScroll;

    content.mCustomScrollbar({
        scrollButtons: {
            enable: true
        },
        theme: "dark",
        callbacks: {
            whileScrolling: function () {
                autoScrollTimerAdjust = autoScrollTimer * this.mcs.topPct / 100;
                privateTop = this.mcs.topPct;
                if (privateTop >= 90) {
                    jQuery('.goToLastMessage').hide();
                    count = 0;
                }

            },
            onScroll: function () {
                if (jQuery(this).data("mCS").trigger === "internal") {
                    AutoScrollOff();

                }
            }
        }
    });

    content.addClass("auto-scrolling-on auto-scrolling-to-bottom");
    AutoScrollOn("bottom");

    function AutoScrollOn(to, timer) {

        if (!timer) {
            timer = autoScrollTimer;
        }
        content.addClass("auto-scrolling-on").mCustomScrollbar("scrollTo", to, {
            scrollInertia: timer,
            scrollEasing: "easeInOutSmooth"
        });

    }
    function AutoScrollOff() {
        clearTimeout(autoScroll);
        content.removeClass("auto-scrolling-on").mCustomScrollbar("stop");
    }

}
function addLayoutComponents(b) {
    for (i = 0; i < b.length; i++) {
        if (b[i]['type'] == 'banner_square') {
            addBannerSquare(b[i]['id']);
        } else if (b[i]['type'] == 'top_category') {
            addTopCategory(b[i]['id']);
        } else if (b[i]['type'] == 'banners_countdown') {
            addBannerCountdown(b[i]['id']);
        } else if (b[i]['type'] == 'products_square') {
            addProductSquare(b[i]['id']);
        } else if (b[i]['type'] == 'products_grid') {
            addProductGrid(b[i]['id']);
        } else if (b[i]['type'] == 'products_recent') {
            addLastAccessed(b[i]['id']);
        } else if (b[i]['type'] == 'banners_grid') {
            addBannergrid(b[i]['id']);
        } else if (b[i]['type'] == 'banner_horizontal_slider') {
            addBannerHorizontalslide(b[i]['id']);
        } else if (b[i]['type'] == 'products_horizontal') {
            addProductHorizontalslide(b[i]['id']);
        /*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
        } else if (b[i]['type'] == 'banner_custom') {
            /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
            addCustomBanner(b[i]['id'], b[i]['alias_name']);
            /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
        }
        /*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
    }
}
/*start: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function uploadtabiconfile()
{
    $('#tabiconuploadfile').on('change', function (e) {
        $('input[name="tabiconuploadfile"]').parent().removeClass('kb_error_field');
        $('input[name="tabiconuploadfile"]').parent().parent().parent().find('.kb_error_message').remove();
        $('#savetabbarformbutton').removeAttr("disabled", true);
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                $('input[name="tabiconuploadfile"]').parent().append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                $('#savetabbarformbutton').prop("disabled", true);
                //slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                $('input[name="tabiconuploadfile"]').parent().append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                $('#savetabbarformbutton').prop("disabled", true);
                //slider_banner_file_error = true;
            } else {
                //slider_banner_file_error = false;
                $('#savetabbarformbutton').removeAttr("disabled", true);
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#tabiconimage");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#tabiconimage').attr('src', e.target.result);
                    }
                    //$('#tabiconimage').show();
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="tabiconuploadfile"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            //file_error = true;
        }
    });
}
function addNewTabIcon(a) {
    //uploadtabiconfile();
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getTabIconCount=true",
        type: "post",
        async: false,
        beforesend: function() {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('.modal-title').text(manage_tab_text);
            $('#layoutNameModel').modal({
                show: 'true',
            });
            $('.modal-layout-body').html('<img id="loader_module_list" style="text-align: center;width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');

        },
        success: function (data1)
        {
            if (data1 == 5) {
                showErrorMessage(tab_bar_limit_msg);
            } else {
                $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&getTabIconForm=true",
                type: "post",
                beforeSend: function () {
                    $('#kbGDPRDialogueModel .modal-body').html('');
                    $('.modal-title').text(manage_tab_text);
                    $('#layoutNameModel').modal({
                        show: 'true',
                    });
                    $('.modal-layout-body').html('<img id="loader_module_list" style="text-align: center;width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');

                },
                success: function (data)
                {
                    var b = JSON.parse(data);
                    $('.modal-layout-body').html('');
                    $('.modal-layout-body').append(b.html);
                    $('.panel-footer').attr("code",''); 
                    uploadtabiconfile();
                }

            });
            }
        }
    });
    
    
}
/*end: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
/*start: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function edittabbarform(a)
{
    var code = $(a).closest('tr').find('.td-vss-code').html();
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&edittabbarform=true",
        type: "post",
        data: 'code=' + code,
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
//            $('#kbGDPRDialogueModel').modal({
//                show: 'true',
//            });
            $('.modal-title').text(edit_tab_text);
            $('#layoutNameModel').modal({
                        show: 'true',
                    });
            $('.modal-layout-body').html('<img id="loader_module_list" style="text-align: center;width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');        
        },
        success: function (data)
        {
            var c = JSON.parse(data);
            if (1) {
                $('.modal-layout-body').html('');
                $('.modal-title').text(edit_tab_text);
                $('.modal-layout-body').append(c.html);
                code = $.trim(code);
                if (parseInt(code) <= parseInt(total_shops)) {
                    $("#tab_icon_redirect_type").prop('disabled','disabled');
                }
                if ($('#tab_icon_redirect_type').val() == 'My Account') {
                    $('#tab_icon_text_' + default_language_id).parent().append('<span class="kb_error_message">' + text_login_msg + '</span>');
                } else {
                    $('#tab_icon_text_' + default_language_id).parent().find('.kb_error_message').remove();
                }
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getTabIconImageUrl=true",
                    data: 'code=' + code,
                    type: "post",
                    success: function (data)
                    {
                        var b = JSON.parse(data);
                            $("#tabiconimage").attr('src', b);
                            $("#tabiconimage").show();
                            $('.panel-footer').attr("code",code);
                            //uploadtabIconfile();
                    }
                });
            }
            uploadtabiconfile();
        }
    });
    uploadtabiconfile();

}
function deletetabbarform(a)
{
    if (confirm(confirmation_tab_txt + '?'))
    {
        $('.alert-success').parent().remove();
        /////$(a).closest('tr').remove();
        var code = $(a).closest('tr').find('.td-vss-code').html();
        code = $.trim(code);
        if (parseInt(code) <= parseInt(total_shops)) {
            showErrorMessage(can_not_delete);
        } 
        else {
          $.ajax({
            url: ajaxaction + "&configure=kbmobileapp&deletetabbarform=true" + "&code=" + code,
            type: "post",
            dataType: "text",
            success: function (data) {
                var b = JSON.parse(data);
                showSuccessMessage(delete_tab_msg);
                $('#form-kb_tab_bar_list').html('');
                $('#form-kb_tab_bar_list').append(b.html);
                $("#form-kb_tab_bar_list .panel .panel-heading").append(b.button);
                $('#vss-add-new-tab-icon-button').show();
                $('#form-kb_tab_bar_list').addClass('col-lg-10 col-md-9');
                $("#form-kb_tab_bar_list").css("float", "right");
                $('#layoutNameModel').modal('hide');
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getTabIconCount=true",
                    type: "post",
                    async: false,
                    
                    success: function (data1)
                    {
                        if (data1 < 2) {
                            $('#tab_bar_status_off').prop('checked', true);
                        }
                    }
                });
                
            }
        });  
        }
        
    }
    else
    {
        return false;
    }

}
/*end: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function editLayout(a)
{
    $('.slides').empty();
    var id_layout = jQuery.trim($(a).closest('tr').find('.td-vss-code').html());
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getlayoutComponent=true",
        data: 'id_layout=' + id_layout,
        type: "post",
        /*chnages made by vibhaas */
         beforeSend: function () { $('#number_of_component').val(0)},
         /*changes over */
        success: function (data)
        {
            var b = JSON.parse(data);
            $('#id_layout').val(id_layout);
            if (b.length > 0) {
                $('.slides').html('');
                $('.iframe_html').html('');
                addLayoutComponents(b);
            } else {
                $('.slides').html('');
                $('.iframe_html').html('');
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.iframe_html').append('<img class="pic" src=" ' + home_pic_url + '"  style="width:100%; height:484px">');
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
            $('#kbmobileapp_configuration_form').hide();
            $('.layout_add_edit_form').slideDown("fast", function () {
                $('#id_layout').val(id_layout);
                $('#add_new').html(add_new_entry);
                $('#add_new').attr('onclick', 'closelayoutForm()');
                $('#form-configuration .panel h3').remove("#vss-button");
                $('#vss-button').appendTo($("#configuration_form .panel-heading"));
            });
            $(".slides").sortable();
        }
        
    });
    showUrlImage();
    //getproductdata();
}

function autoCompleteProduct() {
    $('#redirect_banner_product_name').autocomplete(ajaxaction + '&configure=kbmobileapp&ajaxproductaction=true', {
        delay: 100,
        minChars: 1,
        autoFill: true,
        max: 20,
        matchContains: false,
        mustMatch: false,
        scroll: false,
        cacheLength: 0,
        // param multipleSeparator:'||' ajoutÃ© Ã  cause de bug dans lib autocomplete
        multipleSeparator: '||',
        formatItem: function (item) {
            return item[1] + ' - ' + item[0];
        },
        extraParams: {
            excludeIds: '',
            excludeVirtuals: '',
            exclude_packs: ''
        }
    }).result(function (event, item) {
        $('#redirect_banner_product_id').val(item[1]);
        $('#redirect_banner_product_name').val(item[0]);
    });
}

function countdownbannerDatepicker() {
    $('.datetimepicker').click(function () {
        $('.ui-datepicker').css('z-index', '99999999');
    });
}
/*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
function editBannerCustomComponentFunction(a) {
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getCustomBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        async: false,
        beforeSend: function () {
            
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
            //$(".modal-dialog").css('width','77%');
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                
                $('#layout_type_id').parent().parent().hide();
                /* 25 April 2019*/
//                $('#slider2_form').css('width','60%');
//                $('#slider2_form').css('float','left');
                $('.front_custom_banner_preview').parent().css('width', '40%');
                $('.front_custom_banner_preview').parent().css('padding-left', '5px');
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                var lang = active_languages;
                for (i = 0; i < lang.length; i++) {
                    $('#banner_heading_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#banner_heading_' + lang[i]).parent().addClass('col-lg-5');
                    $('#heading_of_banner_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#heading_of_banner_' + lang[i]).parent().addClass('col-lg-5');
                }
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
                $('#width_of_banner').on('click change', function(e) {
                    
                    if ($(this).val() && $('input[name="slideruploadedfile"]').val()) {
                        var img = new Image();
                        img.src = $('#sliderimage').attr('src');
                        var width = img.width;
                        var height = img.height;
                        
                        $('#height_of_banner').val(Math.ceil((height*$('#width_of_banner').val())/width));
                    }
                });
                /*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
            }
        }
    });
}
/*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
/*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
function editBannerHorizontalComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /* 25 April 2019*/
                /* changes made by vibhaas to hide layout option */
                $('#layout_type_id').parent().parent().hide();
                $('#is_enabled_background_color_on').parent().closest('.form-group').hide();
                $('#countdown_validity').closest('.form-group').hide();
                $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'horizontal_banner');
                $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    var element_type = 'bannerHorizontalSlideimg_';
                    var index = 1;
                    $.each( b.Added_Banners, function( key, value ) {
                        $('p[id="bannerHorizontalSlide_elem_heading_'+ id_layout + '_'+ id_component +'"]').remove();
                        $('img[class="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'bannerHorizontalSlideimg1_';
                        var element_type2 = 'bannerHorizontalSlideimg2_';
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').parent().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').parent().remove();    
                        $('p[id="bannerHorizontalSlide_elem_heading1_'+ id_layout + '_'+ id_component +'"]').remove();
                        $('p[id="bannerHorizontalSlide_elem_heading2_'+ id_layout + '_'+ id_component +'"]').remove();
                        if (index == 1) {
                            element_type = 'bannerHorizontalSlideimg1_';
                            var class_name = '.bannerHorizontalSlide_elem_heading1_' + id_layout + '_'+ id_component;
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                
                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList1').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
            }
                            if (value.heading != '') {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                            } else {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
        }
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                        } else if (index == 2) {
                            element_type = 'bannerHorizontalSlideimg2_';
                            var class_name = 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_'+ id_component;
                              if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList2').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            }  
                            if (value.heading != '') {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                            } else {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                            }
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                         } 
                        index++;
    });
                    if (b.component_heading!=null) {
                        $('h4[id="bannerHorizontalSlide_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }    
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/

            }
        }
    });

}
function editBannerGridComponentFunction(a) 
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /* 25 April 2019*/
                /* changes made by vibhaas to hide layout option */
                $('#layout_type_id').parent().parent().hide();
                $('#is_enabled_background_color_on').parent().closest('.form-group').hide();
                $('#countdown_validity').closest('.form-group').hide();
                $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*
                 * To append the preview of already added banner grid
                 * 
                 */
                $('.panel-footer').attr('element_type', 'banner_grid');
                var element_type = 'bannerGridimg1_';
                var template_html = $('#bannerGRow').html();
                $.each( b.Added_Banners, function(key, value) {
                    $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').find('#bannerGridimg').parent().remove();    
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/bannerGridimg1/g, 'bannerGridimg1_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);
                    productinfo_html = productinfo_html.replace(/bannerGrid_elem_heading/g, 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);
                    
                    
                    if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').length == "0") {
                        $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                    }
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').attr('src', value.Image);
                    
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').text(value.heading);
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'block');
                    } else {
                        $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'none');
                    }
                });
                $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"] .bannerGridList').each(function () {    
                        if ($(this).children().length == 0){
                            $(this).remove();
                        }
                });
                if (b.component_heading!=null) {
                    $('h4[id="bannerGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                
            }
        }
    });
}
/*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
function editBannerSquareComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
        },
        success: function (data)
        {
            var b = JSON.parse(data);

            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /* 25 April 2019*/
                /* changes made by vibhaas to hide layout option */
                $('#layout_type_id').parent().parent().hide();
                $('#is_enabled_background_color_on').parent().closest('.form-group').hide();
                $('#countdown_validity').closest('.form-group').hide();
                $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
		/*
                 * To append the preview of already added banner square
                 */
		$('.panel-footer').attr('element_type','banner_square');

                var element_type = 'bannerSquareBannerimg_';
                $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                $.each( b.Added_Banners, function( key, value ) {
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').parent().remove();
                    $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                    $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                    if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                            $('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<span class="BSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading" id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            //$('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<p id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'">');
                        }
                    
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                    } else {
                        $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image).css("width", "100%");
                });
                if (b.component_heading!=null) {
                    $('p[id="bannerSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                
            }
        }
    });


}
function showHidebackgroundColor() {
    if ($('input[name="is_enabled_background_color"]:checked').val() == "1") {
        $('input[name="timer_background_color"]').parent().parent().show();
    } else {
        $('input[name="timer_background_color"]').parent().parent().hide();
    }
}
function editBannerCountdownComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getBannerForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /* 25 April 2019*/
                /* changes made by vibhaas to hide layout option */
                $('#layout_type_id').parent().parent().hide();
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                showUrlImage();
                uploadfile();
                countdownbannerDatepicker();
                //showHidebackgroundColor();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*
                 * To append the preview of already added banner countdown
                 */
                $('.panel-footer').attr('element_type', 'banner_countdown');
                var element_type = 'bannerCountdownimg1_';
                $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                $.each( b.Added_Banners, function( key, value ) {
                    $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').find('#bannerCountdownimg').parent().remove();  
                    var template_html = $('.countdownlistContentContainer1').html();
                    template_html = template_html.replace(/bannerCountdownimg1/g, 'bannerCountdownimg1_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                    template_html = template_html.replace(/bannerCountdown_elem_heading/g, 'bannerCountdown_elem_heading_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                    template_html = template_html.replace(/countdown_banner_img_url/g, value.Image);
                    if (value.is_enabled_background_color == "1") {
                        template_html = template_html.replace(/background_color_of_timer_text/g, value.background_color);
                    } else {
                        template_html = template_html.replace(/background_color_of_timer_text/g, 'transparent');
            }
                    if ($('div[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                        $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').append(template_html);
        }
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                    } else {
                        $('div[id="'+ 'bannerCountdownimg1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('bottom', '5px');
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
    });
                if (b.component_heading!=null) {
                    $('h4[id="bannerCountdown_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
        }
    });
    //showHidebackgroundColor();


}
function editLayoutName(a) {
    var id_layout = jQuery.trim($(a).closest('tr').find('.td-vss-code').html());
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getlayoutNameForm=true",
        data: 'id_layout=' + id_layout,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#layoutNameModel').modal({
                show: 'true',
            });
            $('.modal-layout-body').html('<img id="loader_module_list" style="text-align: center;width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            $('#id_layout').val(id_layout);
            $('.modal-layout-body').html('');
            $('.modal-layout-body').append(b.html);

        },
        complete: function () {
            //$('#kbsw_show_loader').hide();
        }
    });
}

function addNewLayout(a) {
    var id_layout = 0;
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getlayoutNameForm=true",
        data: 'id_layout=' + id_layout,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#layoutNameModel').modal({
                show: 'true',
            });
            $('.modal-layout-body').html('<img id="loader_module_list" style="text-align: center;width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            $('.modal-layout-body').html('');
            $('.modal-layout-body').append(b.html);
//            $('#layoutNameModel').modal({
//                show: 'true',
//            });
        }
    });
}
function saveLayoutData(a) {
    /* changes made by vibhaas to show error if layout name is empty */
    $error = true;
    
    var id_layout = $('#layout_id').val();
    var fd = new FormData();
    fd.append('id_layout', id_layout);
    var lang = active_languages;
    
    $('.kb_error_message').remove();
    for (i = 0; i < lang.length; i++) {
    $('input[name="layout_title_'+ lang[i] + '"]').removeClass('kb_error_field');
    }
    
    for (i = 0; i < lang.length; i++) {
      fd.append('layout_name_' + lang[i], $('#layout_title_' + lang[i]).val());
    }
    
    for (i = 0; i < lang.length; i++) {
       if($('#layout_title_' + lang[i]).val()=='')
       {
           $error = false;
       }
    }
    if($error == false)
    {
        error_message = layout_select;
        for (i = 0; i < lang.length; i++) {
        $('input[name="layout_title_'+ lang[i] + '"]').addClass('kb_error_field');
        $('input[name="layout_title_'+ lang[i] + '"]').after('<span class="kb_error_message">' + error_message + '</span>');
    }
        return false;
    }
    
    
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&savelayoutNameForm=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                if (id_layout == 0) {
                    showSuccessMessage(layout_add_message);
                } else {
                    showSuccessMessage(layout_name_update_message);
                }
                $('#layout_list').html('');
                $('#layout_list').append(b.html);
                $("#layout_list .panel .panel-heading").append(b.button);
                $('#KBMOBILEAPP_HOME_PAGE_LAYOUT').html('');
                $('#KBMOBILEAPP_HOME_PAGE_LAYOUT').append(b.layout_select_options);
                /* changes by rishabh jain
                 * for layput tab
                 */
                $('#form-kb_layouts_list').addClass('col-lg-10 col-md-9');
                $("#form-kb_layouts_list").css("float", "right");
                $('#layoutNameModel').modal('hide');
                /* changes over */
            }
            return false;
        }
    });
    return false;
}
/*start: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function saveTabIconData(a) {
    var fd = new FormData();
    var tab_icon_redirect_type = $('#tab_icon_redirect_type').val();
    fd.append('tab_icon_redirect_type', tab_icon_redirect_type);
    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('tab_icon_text_' + lang[i], $('#tab_icon_text_' + lang[i]).val());
    }
    if ($('#tabiconuploadfile').get(0).files.length > 0) {
        fd.append('tabiconimage', $('#tabiconuploadfile')[0].files[0]);
    }
    // fd.append('tabiconimage', $('#tabiconuploadfile').attr('src'));
    var code = $(a).closest('tr').find('.td-vss-code').html();
    var code = $('.panel-footer').attr('code');
    if (code) {
        fd.append('code', code);
        fd.append('tabiconimage', $('#tabiconimage').attr('src'));
        
    } else {
        fd.append('code','');
    }
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveTabIconData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#savetabbarformbutton').prop("disabled", true);

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                
                showSuccessMessage(success_message);
                
                $('#form-kb_tab_bar_list').html('');
                $('#form-kb_tab_bar_list').append(b.html);
                $("#form-kb_tab_bar_list .panel .panel-heading").append(b.button);
                $('#vss-add-new-tab-icon-button').show();
                $('#form-kb_tab_bar_list').addClass('col-lg-10 col-md-9');
                $("#form-kb_tab_bar_list").css("float", "right");
                $('#layoutNameModel').modal('hide');
                /* changes over */
            }
        }
    });
    //return false;
}
/*end: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function veloValidateProductForm(a) {
    $('.kb_error_message').remove();
    $('input[name="number_of_products"]').removeClass('kb_error_field');
    $('select[name="category_id"]').removeClass('kb_error_field');
    $('select[name="product_list"]').removeClass('kb_error_field');
    $('select[name="category_products"]').removeClass('kb_error_field');
    var product_form_error = false;


    var number_of_product = $.trim($('#number_of_products').val());
    var category_id = $.trim($('#category_id').val());
    var product_list = $.trim($('#product_list').val());
    var category_products = $.trim($('#category_products').val());
    var product_type = $('#product_type').val();
    var error_message = '';

    if (product_type == 'category_products') {
        if (category_id == 0) {
            product_form_error = true;
            error_message = select_category_txt;
            $('#category_id').addClass('kb_error_field');
            $('#category_id').after('<span class="kb_error_message">' + error_message + '</span>');
        }
    } else if (product_type == 'custom_products') {
        if (product_list == '') {
            product_form_error = true;
            error_message = select_image_txt;
            $('#product_list').addClass('kb_error_field');
            $('#product_list').after('<span class="kb_error_message">' + error_message + '</span>');
        }
    }

    var key_numeric_err = velovalidation.isNumeric($('#number_of_products'), true);
    if (key_numeric_err != true)
    {
        product_form_error = true;
        error_message = select_image_txt;
        $('#number_of_products').addClass('kb_error_field');
        $('#number_of_products').after('<span class="kb_error_message">' + error_message + '</span>');
    }


    if (product_form_error) {
        return false;
    }
    submitProductform(event);

    return false;
}
/*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/  
function editProductSquareComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                showHideProductType(a);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*
                 * To append the preview of already added product square
                 */
                $('.panel-footer').attr('element_type', 'productSquareimg');
                var element_type = 'productSquareimg1_';
                var template_html = $('.productSquareimg1').parent().html();
                
                $.each( b.added_Products.products_for_preview, function(key, value) {
                    var element_type = 'productSquareimg1_';
                    var template_html = $('.productSquareimg1').parent().html();
                    var img_id = element_type + id_layout + '_' + id_component;
                    var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/productSquareimg1/g, 'productSquareimg1_' + value.id);
                    productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                    productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                    if ($('img[id="'+ element_type + value.id +'"]').length == "0") {
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                    }
                    $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                    $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                    $('img[id="'+ element_type + value.id +'"]').attr('src', value.src);
                    $('img[id="'+ element_type + value.id +'"]').css('width','282px');
                    $('img[id="'+ element_type + value.id +'"]').css('height','261px');
//                    
                });
                if (b.added_Products.products_for_preview.length > 0) {
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').next().remove();
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').remove();
                    }
                if (b.added_Products.component_heading_preview!=null) {
                    $('h4[id="productSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                
            }
        }
    });


}
function editProductGridComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                showHideProductType(a);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').attr('element_type', 'productGridimg');
                var element_type = 'productGridimg_';
                var template_html = $('#productGRow').html();
                var id_ProductArray = [];
                $.each( b.added_Products.products_for_preview, function(key, value) {
                    id_ProductArray.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                    var img_id = element_type + id_layout + '_' + id_component;
                    var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                    productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                    productinfo_html = productinfo_html.replace(/productGridimg/g, 'productGridimg_' +id_layout + '_' + id_component+ '_' +value.id);
                    if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' +  value.id +'"]').length == "0") {
                            $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                    }
                    $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                    $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.id +'"]').attr('src', value.src);
                    
                    
////                    
                });
                
                if (b.added_Products.component_heading_preview!=null) {
                    $('p[id="productGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
        }
    });
}
/*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/   
function editProductHorizontalComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getProductForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                showHideProductType(a);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*
                 * To append the preview of already added horizontal products
                 */
                $('.panel-footer').attr('element_type', 'slidingProducts');
                    var element_type = 'slidingProductsimg';
                    var template_html = $('#slidingPRow').html();
                    var index = 1;
                    $.each( b.added_Products.products_for_preview, function( key, value ) {
                       // $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'slidingProductsimg1_';
                        var element_type2 = 'slidingProductsimg2_';
                        var element_type3 = 'slidingProductsimg3_';    
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').remove();
                        if (index == 1) {
                            element_type = 'slidingProductsimg1_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 2) {
                            element_type = 'slidingProductsimg2_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } else if (index == 3) {
                            element_type = 'slidingProductsimg3_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                        } 
                        index++;
                    });
                    if (b.added_Products.component_heading_preview!=null) {
                        $('h4[id="slidingProducts_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                //$('#redirect_banner_product_name').parent().parent().hide();
//                $('#countdown_validity').closest('.form-group').hide();
//                $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
//                $('#image_url').parent().parent().hide();
//                $('#slideruploadedfile').parent().parent().parent().parent().hide();
//                showUrlImage();
//                uploadfile();
//                autoCompleteProduct();
//                setDate();
//                setColor();
            }
        }
    });



}

function setColor() {
    $(document).on('change', '.kbsw_wheel_color', function () {
        var color = $(this).val();
        changeColor(color);
    });
}
function rgb2hsb(r, g, b)
{
    r /= 255;
    g /= 255;
    b /= 255; // Scale to unity.
    var minVal = Math.min(r, g, b),
        maxVal = Math.max(r, g, b),
        delta = maxVal - minVal,
        HSB = {hue: 0, sat: 0, bri: maxVal},
    del_R, del_G, del_B;

    if (delta !== 0)
    {
        HSB.sat = delta / maxVal;
        del_R = (((maxVal - r) / 6) + (delta / 2)) / delta;
        del_G = (((maxVal - g) / 6) + (delta / 2)) / delta;
        del_B = (((maxVal - b) / 6) + (delta / 2)) / delta;

        if (r === maxVal) {
            HSB.hue = del_B - del_G;
        } else if (g === maxVal) {
            HSB.hue = (1 / 3) + del_R - del_B;
        } else if (b === maxVal) {
            HSB.hue = (2 / 3) + del_G - del_R;
        }

        if (HSB.hue < 0) {
            HSB.hue += 1;
        }
        if (HSB.hue > 1) {
            HSB.hue -= 1;
        }
    }

    HSB.hue *= 360;
    HSB.sat *= 100;
    HSB.bri *= 100;
    return HSB;
}
function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function changeColor(wheel_color)
{
    velsofWheelHexCode = wheel_color;
    var colorRGB = hexToRgb(velsofWheelHexCode);
    var hslColorCode = rgb2hsb(colorRGB.r, colorRGB.g, colorRGB.b);
    //   document.getElementById("kbsw_preview_img").style.filter = 'hue-rotate(' + hslColorCode.hue + 'deg) saturate(' + hslColorCode.sat + '%) contrast(1.1)';

}

function setDate() {
    $('.datetimepicker').click(function () {
        $('.ui-datepicker').css('z-index', '99999999');
    });
}
function setCategoryId(a) {
    if ($(a).val != 0) {
        for (i = 1; i < 8; i++) {
            var cat_id = 'category_id_' + i;
            if ($(a).attr('id') != cat_id) {
                if ($('#category_id_' + i).val() == $(a).val()) {
                    $('#category_id_' + i).val(0);
                }
            }
        }
    }
}
/*start: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function veloValidateTabIconForm(a) {
    var is_error = false;
    $('.kb_error_message').remove();
    $('input[name="tab_icon_text_' + default_language_id + '"]').removeClass('kb_error_field');
    $('input[id="tabiconuploadfile"]').parent().removeClass('kb_error_field');
    $('input[id="tabiconuploadfile"]').parent().parent().parent().find('.kb_error_message').remove();
    $('input[id="tab_icon_text_' + default_language_code + '"]').removeClass('kb_error_field');
    var tab_icon_text = velovalidation.checkMandatory($('#tab_icon_text_' + default_language_id));
    if (tab_icon_text!= true) {
            error_msg = 'error' + " " + default_language_code;
            $('input[name="tab_icon_text_' + default_language_id + '"]').addClass('kb_error_field');
            $('input[name="tab_icon_text_' + default_language_id + '"]').after('<span class="kb_error_message">' + tab_icon_text + '</span>');
            is_error = true;
            
    }
    if ($('#tabiconimage').attr('src')=='') {
        is_error = true;
        $('input[id="tabiconuploadfile"]').parent().addClass('kb_error_field');
        $('input[id="tabiconuploadfile"]').parent().parent().after('<span class="kb_error_message">' + error_check_message_in_tab_form + '</span>');
    }
    if(is_error) {
        return false;
    } else {
        saveTabIconData();
        return true;
    }
}
/*end: changes made by Aayushi Agarwal on 15 April 2019  to add tab bar functionality*/
function veloValidateTopcategoryForm(a) {
    var selected_cat = 0;
    var unselected_image = 0;
    var selected_layout = 0;
    var selected_redirect = 0;
    var total_selected = 0;
    var selected_image = 0;
    var category_image_error = false;
    
    var error_check =false;
    
    for (i = 1; i <= 8; i++) {
      //  console.log($('#redirect_activity_' + i).val());
        if ($('#redirect_activity_' + i).val() !=0 && ($('#layout_type_id_' + i).val() != 0 || $('#category_id_' + i).val() != 0 ) ) {


            total_selected = total_selected  + 1;
        }

        if ($('#redirect_activity_' + i).val() == 1  || $('#redirect_activity_' + i).val() == 2)
        {
            selected_redirect = selected_redirect + 1;
        }
        if ($('#sliderimage_' + i).attr('src') != "") {
             
                selected_image = selected_image + 1;
        }
        
        if($('#redirect_activity_' + i).val() == 1 && $('#layout_type_id_' + i).val() == 0)
        {
            var error_check = true;
        
        }
        if($('#redirect_activity_' + i).val() == 2 && $('#category_id_' + i).val() == 0)
        {
            var error_check = true;
        
        }


    }

    if ( selected_redirect  <  4) {
       var error = true;
        showErrorMessage(min_category_layout_message);
    }
    if ( total_selected < 4)
    {
         var error = true;
        showErrorMessage(fill_details_message);
    }
    
     if((selected_redirect != total_selected) || (selected_redirect != selected_image) || (error_check == true) )
    {
         var error = true;
        showErrorMessage(fill_details_message);
    }
   
    
    else {
        for (j=1; j<=unselected_image; j++) {
            $('#slideruploadedfile_' + j).parent().find('.kb_error_message').remove();
            if ($('#redirect_activity_' + j).val() == 1 || $('#redirect_activity_' + j).val() == 2) {
           if (($('#slideruploadedfile_' + j).val() == "") && ($('#sliderimage_'+j).attr('src') == "")) {
                error = true;
                category_image_error = true;
                $('#slideruploadedfile_' + j).parent().append('<span class="kb_error_message">' + select_image_txt + '</span>');
            }
        }
    }
}
    if (error) {
        if (category_image_error) {
            return false;
        }
        showErrorMessage(error_check_message);
        return false;
    } else {
        submitTopCategoryForm();
    }
    return false;
}
function submitTopCategoryForm() {
    var id_layout = $('#id_layout').val();
    var id_component = $('#id_component_selected').val();
    var id_category_1 = $('#category_id_1').val();
    var id_category_2 = $('#category_id_2').val();
    var id_category_3 = $('#category_id_3').val();
    var id_category_4 = $('#category_id_4').val();
    var id_category_5 = $('#category_id_5').val();
    var id_category_6 = $('#category_id_6').val();
    var id_category_7 = $('#category_id_7').val();
    var id_category_8 = $('#category_id_8').val();
    var layout_type_id_1 = $('#layout_type_id_1').val();
    var layout_type_id_2 = $('#layout_type_id_2').val();
    var layout_type_id_3 = $('#layout_type_id_3').val();
    var layout_type_id_4 = $('#layout_type_id_4').val();
    var layout_type_id_5 = $('#layout_type_id_5').val();
    var layout_type_id_6 = $('#layout_type_id_6').val();
    var layout_type_id_7 = $('#layout_type_id_7').val();
    var layout_type_id_8 = $('#layout_type_id_8').val();
  
  
  
    var redirect_activity_1 = $('#redirect_activity_1').val();
    var redirect_activity_2 = $('#redirect_activity_2').val();
    var redirect_activity_3 = $('#redirect_activity_3').val();
    var redirect_activity_4 = $('#redirect_activity_4').val();
    var redirect_activity_5 = $('#redirect_activity_5').val();
    var redirect_activity_6 = $('#redirect_activity_6').val();
    var redirect_activity_7 = $('#redirect_activity_7').val();
    var redirect_activity_8 = $('#redirect_activity_8').val();
    
    // changes
    var image_content_mode = $('#image_content_mode').val();
    // changes started
    var fd = new FormData();
    if ($('#slideruploadedfile_1').get(0).files.length > 0 && (id_category_1 != 0 || layout_type_id_1 !=0)) {
        fd.append('image_1', $('#slideruploadedfile_1')[0].files[0]);
    }
    if ($('#slideruploadedfile_2').get(0).files.length > 0 && (id_category_2 != 0 || layout_type_id_2 !=0)) {
        fd.append('image_2', $('#slideruploadedfile_2')[0].files[0]);
    }
    fd.append('image_content_mode', image_content_mode);
    if ($('#slideruploadedfile_3').get(0).files.length > 0 && (id_category_3 != 0 || layout_type_id_3 !=0)) {
        fd.append('image_3', $('#slideruploadedfile_3')[0].files[0]);
    }
    if ($('#slideruploadedfile_4').get(0).files.length > 0 && (id_category_4 != 0 || layout_type_id_4 !=0)) {
        fd.append('image_4', $('#slideruploadedfile_4')[0].files[0]);
    }
    if ($('#slideruploadedfile_5').get(0).files.length > 0 && (id_category_5 != 0 || layout_type_id_5 !=0)) {
        fd.append('image_5', $('#slideruploadedfile_5')[0].files[0]);
    }
    if ($('#slideruploadedfile_6').get(0).files.length > 0 && (id_category_6 != 0 || layout_type_id_6 !=0)) {
        fd.append('image_6', $('#slideruploadedfile_6')[0].files[0]);
    }
    if ($('#slideruploadedfile_7').get(0).files.length > 0 && (id_category_7 != 0 || layout_type_id_7 !=0)) {
        fd.append('image_7', $('#slideruploadedfile_7')[0].files[0]);
    }
    if ($('#slideruploadedfile_8').get(0).files.length > 0 && (id_category_8 != 0 || layout_type_id_8 !=0)) {
        fd.append('image_8', $('#slideruploadedfile_8')[0].files[0]);
    }

    fd.append('id_layout', id_layout);
    fd.append('id_component', id_component);
    fd.append('id_category_1', id_category_1);
    fd.append('id_category_2', id_category_2);
    fd.append('id_category_3', id_category_3);
    fd.append('id_category_4', id_category_4);
    fd.append('id_category_5', id_category_5);
    fd.append('id_category_6', id_category_6);
    fd.append('id_category_7', id_category_7);
    fd.append('id_category_8', id_category_8);
    
    
    fd.append('layout_type_id_1', layout_type_id_1);
    fd.append('layout_type_id_2', layout_type_id_2);
    fd.append('layout_type_id_3', layout_type_id_3);
    fd.append('layout_type_id_4', layout_type_id_4);
    fd.append('layout_type_id_5', layout_type_id_5);
    fd.append('layout_type_id_6', layout_type_id_6);
    fd.append('layout_type_id_7', layout_type_id_7);
    fd.append('layout_type_id_8', layout_type_id_8);
    
    fd.append('redirect_activity_1', redirect_activity_1);
    fd.append('redirect_activity_2', redirect_activity_2);
    fd.append('redirect_activity_3', redirect_activity_3);
    fd.append('redirect_activity_4', redirect_activity_4);
    fd.append('redirect_activity_5', redirect_activity_5);
    fd.append('redirect_activity_6', redirect_activity_6);
    fd.append('redirect_activity_7', redirect_activity_7);
    fd.append('redirect_activity_8', redirect_activity_8);
    /*start: changes made by aayushi on 25 april 2019 to add category heading*/
    //var category_heading1 = $('#category_heading1_1').val();
    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('category_heading1_' + lang[i], $('#category_heading1_' + lang[i]).val());
        fd.append('category_heading2_' + lang[i], $('#category_heading2_' + lang[i]).val());
        fd.append('category_heading3_' + lang[i], $('#category_heading3_' + lang[i]).val());
        fd.append('category_heading4_' + lang[i], $('#category_heading4_' + lang[i]).val());
        fd.append('category_heading5_' + lang[i], $('#category_heading5_' + lang[i]).val());
        fd.append('category_heading6_' + lang[i], $('#category_heading6_' + lang[i]).val());
        fd.append('category_heading7_' + lang[i], $('#category_heading7_' + lang[i]).val());
        fd.append('category_heading8_' + lang[i], $('#category_heading8_' + lang[i]).val());
    }
    /*end: changes made by aayushi on 25 april 2019 to add category heading*/
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveTopcategoryFormData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#submitOptionsslider2').prop("disabled", true);

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#banner_form_popup').show();
                $('#component_edit_popup').empty();
                $('#component_edit_popup').append(b.html);
                $('#confirmation_block_modal').show();
                showSuccessMessage(success_message);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var index = 1;
                $.each(b.Added_Categories, function( key, value ) {
                        
                    $('img[id="top_category_'+index+'_'+ id_layout + '_' + id_component + '"]').attr('src', value.image_src);
                    if (value.heading != '') {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').text(value.heading);
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'block');
                    } else {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'none');
                    }
                    index++;
                });
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                //$('#kbGDPRDialogueModel').modal('hide');
            }
        }
    });
    return false;
}
function deleteCategoryImage(e) {
    $('#kbGDPRDialogueModel').find('.icon-trash').parent().each(function () {
        var str = $(this).parent().parent().find('.category_image_class').attr('id');
        var src = $("#" + str).attr('src');
        if (src == '') {
            $(this).parent().css("display", "none");
        }
        $(this).bind('click', function () {
            var str = $(this).parent().parent().find('.category_image_class').attr('id');
            var array = str.split("_");
            var id_category_component = array[1];
            var id_component = $('#id_component_selected').val();
            $(this).parent().css("display", "none");
            $.ajax({
                url: ajaxaction + "&configure=kbmobileapp&deleteTopCategoryImage=true",
                data: 'id_category_component=' + id_category_component + '&id_component=' + id_component,
                type: "post",
                success: function (data)
                {
                    if (1) {
                        $("#" + str).attr('src', '');
                        $(this).parent().css("display", "none");
                        //$(this).css("display", "none");
                        showSuccessMessage(category_image_delete_message);
                    }
                }
            });
            return false;
        })
    })

}

function editTopCategoryComponentFunction(a)
{
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);

    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&getCategoryForm=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        beforeSend: function () {
            $('#kbGDPRDialogueModel .modal-body').html('');
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });

        },
        success: function (data)
        {
            var c = JSON.parse(data);
            if (1) {

                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(c.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });

                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('input[id="category_heading1_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_1_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading1_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading2_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_2_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading2_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading3_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_3_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading3_'+default_language_id+'"]').val());
                });
                $('input[id="category_heading4_'+default_language_id+'"]').keyup(function(event) {
                    $('p[id="top_category_text_4_'+id_layout+'_'+id_component+'"]').html($('input[id="category_heading4_'+default_language_id+'"]').val());
                });
                uploadtopCategoryfile(id_component, id_layout);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                var index = 1;
                if(c.Added_Categories != null) {
                $.each(c.Added_Categories, function( key, value ) {
                        
                    $('img[id="top_category_'+index+'_'+ id_layout + '_' + id_component + '"]').attr('src', value.image_src);
                    
                    if (value.heading != '') {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').text(value.heading);
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'block');
                    } else {
                        $('p[id="top_category_text_'+index+'_'+ id_layout + '_' + id_component + '"]').css('display', 'none');
                    }
                    index++;
                });
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                
                /* changes made by vibhaas */
                
               
               for (i = 1; i <= 8; i++)
                {
                    if(c.silder_field_value['redirect_activity_'+i] == 1)
                    {
                       
                        $('#category_id_'+i+'').parent().parent().hide();
                        $('#layout_type_id_'+i+'').parent().parent().show();
                    }
                    else if (c.silder_field_value['redirect_activity_'+i] == 2)
                    {
                       
                        $('#category_id_'+i+'').parent().parent().show();
                        $('#layout_type_id_'+i+'').parent().parent().hide();
                        
                    }
                    else 
                    {
                        
                        $('#category_id_'+i+'').parent().parent().hide();
                        $('#layout_type_id_'+i+'').parent().parent().hide();
                        
                    }
                }
                
                
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getTopcategoryImageUrl=true",
                    data: 'id_layout=' + id_layout + '&id_component=' + id_component,
                    type: "post",
                    success: function (data)
                    {
                        var b = JSON.parse(data);
                        
                        if (b.length > 0) {
                            for (i = 0; i < b.length; i++) {
                                if (b[i]['name'] == 'sliderimage_1') {
                                    $("#sliderimage_1").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_2') {
                                    $("#sliderimage_2").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_3') {
                                    $("#sliderimage_3").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_4') {
                                    $("#sliderimage_4").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_5') {
                                    $("#sliderimage_5").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_6') {
                                    $("#sliderimage_6").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_7') {
                                    $("#sliderimage_7").attr('src', b[i]['value']);
                                } else if (b[i]['name'] == 'sliderimage_8') {
                                    $("#sliderimage_8").attr('src', b[i]['value']);
                                }
                            }
                        }
                        deleteCategoryImage();
                        uploadtopCategoryfile();
                    }
                });
            }
            uploadtopCategoryfile();
        }
    });
}
/*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
function submitcustombannerform(a)
{
    var id_layout = $('#id_layout').val();
    var id_component = $('#id_component_selected').val();
    var image_type = $('#image_type').val();
    var image_url = $('#image_url').val();
    var redirect_activity = $('#redirect_activity').val();
    var category_id = $('#category_id').val();
    var redirect_product_id = $('#redirect_banner_product_id').val();
    var image_content_mode = $('#image_content_mode').val();
    var redirect_product_name = $('#redirect_banner_product_name').val();
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var redirect_cms_id = $('#redirect_banner_cms_name').val();
    /*END:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var Sliding_of_banner = $('input[name="Sliding_of_banner"]:checked').val()
    var width_of_banner = $('#width_of_banner').val();
    var height_of_banner = $('#height_of_banner').val();
    var top_inset_of_banner = $('#top_inset_of_banner').val();
    var right_inset_of_banner = $('#right_inset_of_banner').val();
    var bottom_inset_of_banner = $('#bottom_inset_of_banner').val();
    var left_inset_of_banner = $('#left_inset_of_banner').val();
    var custom_banner_background_color = $('input[name="custom_banner_background_color"]').val();
    /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
    var custom_banner_alias_name = $('#custom_banner_alias_name').val();
    /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
    var layout_type_id = $('#layout_type_id').val();
    
    var fd = new FormData();
    fd.append('image', $('#slideruploadedfile')[0].files[0]);
    fd.append('id_layout', id_layout);
    fd.append('id_component', id_component);
    fd.append('category_id', category_id);
    fd.append('redirect_activity', redirect_activity);
    fd.append('image_url', image_url);
    fd.append('layout_type_id', layout_type_id);

    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('banner_heading_' + lang[i], $('#banner_heading_' + lang[i]).val());
    }
    /*start:changes made by knowband on 25 April 2019 to add banner heading*/
    for (i = 0; i < lang.length; i++) {
        fd.append('heading_of_banner_' + lang[i], $('#heading_of_banner_' + lang[i]).val());
    }
    /*end:changes made by knowband on 25 April 2019 to add banner heading*/

    fd.append('image_content_mode', image_content_mode);
    fd.append('image_type', image_type);
    fd.append('redirect_product_id', redirect_product_id);
    fd.append('redirect_product_name', redirect_product_name);
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    fd.append('redirect_cms_id', redirect_cms_id);
    /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    fd.append('Sliding_of_banner', Sliding_of_banner);
    fd.append('width_of_banner', width_of_banner);
    fd.append('height_of_banner', height_of_banner);
    fd.append('top_inset_of_banner', top_inset_of_banner);
    fd.append('left_inset_of_banner', left_inset_of_banner);
    fd.append('right_inset_of_banner', right_inset_of_banner);
    fd.append('bottom_inset_of_banner', bottom_inset_of_banner);
    fd.append('custom_banner_background_color', custom_banner_background_color);
    /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
    fd.append('custom_banner_alias_name', custom_banner_alias_name);
    /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
    /*start: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
    if ($('.panel-footer').attr('id_custom_banner')) {
        fd.append('id_custom_banner', $('.panel-footer').attr('id_custom_banner'));
    } else {
        fd.append('id_custom_banner', '');
    }
    /*end: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveCustomBannerFormData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
            $('#submitOptionsslider2').prop("disabled", true);
            //$(".modal-dialog").css('width','77%');

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').empty();
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#sliderimage').hide();
                //$('#category_id').parent().parent().hide();
                
                /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#image_url').parent().parent().hide();
                $('#layout_type_id').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                $('#confirmation_block_modal').show();
                /*start: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
                $('.panel-footer'). removeAttr("id_custom_banner");
                /*end: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
                showSuccessMessage(success_message);
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
                var lang = active_languages;
                for (i = 0; i < lang.length; i++) {
                    $('#banner_heading_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#banner_heading_' + lang[i]).parent().addClass('col-lg-5');
                    $('#heading_of_banner_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#heading_of_banner_' + lang[i]).parent().addClass('col-lg-5');
                }
                $('.front_custom_banner_preview').parent().css('width', '40%');
                $('.front_custom_banner_preview').parent().css('padding-left', '5px');
                $('#width_of_banner').on('click change', function(e) {
                    if ($(this).val() && $('input[name="slideruploadedfile"]').val()) {
                        var img = new Image();
                        img.src = $('#sliderimage').attr('src');
                        var width = img.width;
                        var height = img.height;
                        
                        $('#height_of_banner').val(Math.ceil((height*$('#width_of_banner').val())/width));
                    }
                });
                /*Start:changes made by aayushi on 15th January 2020 to change the component alias name*/
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getlayoutComponent=true",
                    data: 'id_layout=' + id_layout,
                    type: "post",
                    success: function (data)
                    {
                        var b = JSON.parse(data);
                        $('#id_layout').val(id_layout);
                        if (b.length > 0) {
                            $('.slides').html('');
                            $('.iframe_html').html('');
                            addLayoutComponents(b);
                        } else {
                            $('.slides').html('');
                            $('.iframe_html').html('');
                            /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                            $('.iframe_html').append('<img class="pic" src="' + home_pic_url + '"  style="width:100%; height:484px">');
                            /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
            }
                        $('#kbmobileapp_configuration_form').hide();
                        $('.layout_add_edit_form').slideDown("fast", function () {
                            $('#id_layout').val(id_layout);
                            $('#add_new').html(add_new_entry);
                            $('#add_new').attr('onclick', 'closelayoutForm()');
                            $('#form-configuration .panel h3').remove("#vss-button");
                            $('#vss-button').appendTo($("#configuration_form .panel-heading"));
                        });
                        $(".slides").sortable();
        }

    });
                /*end:changes made by aayushi on 15th January 2020 to change the component alias name*/
            }
        }
    });
    return false;
}
/*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
/*start: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
function edit_custom_banner(e) {
    $('#loader_module_list').remove();
    var a = jQuery.trim($(e).closest('tr').find('.td-vss-code').html());
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&editCustomBanner=true",
        data: 'id_banner=' + a,
        type: "post",
        beforeSend: function () {
            $(e).closest('tr').find('.btn-group-action').parent().after('<img id="loader_module_list" style="text-align: center;width:25px;height:25px;align:center" src=" ' + loader_url + '" alt="" border="0" />');
        },
        success: function(data)
        {
            var b = JSON.parse(data);
            $('#loader_module_list').remove();
            $('#kb_banner_id').val(b.kb_banner_id);
            $('#image_content_mode').val(b.image_contentMode);
            $('#width_of_banner').val(b.width);
            $('#height_of_banner').val(b.height);
            $('#top_inset_of_banner').val(b.top_inset);
            $('#left_inset_of_banner').val(b.left_inset);
            $('#right_inset_of_banner').val(b.right_inset);
            $('#bottom_inset_of_banner').val(b.bottom_inset);
            $('.kbsw_wheel_color').val(b.background_color_of_banner);
            if ($.trim(b.image_type) != '') {
                $('#image_type').val(b.image_type);
                if ($.trim(b.image_type) == 'url'){
                    $('#sliderimage').attr('src', b.image_url);
                    $('#image_url').val(b.image_url);
                    $('#image_url').parent().parent().show();
                    $('#slideruploadedfile').parent().parent().parent().parent().show();
                    $('#slideruploadedfile').parent().hide();
                }
                if ($.trim(b.image_type) == 'image'){
                    $('#sliderimage').attr('src', b.image_url);
                    $('#image_url').val(b.image_url);
                    $('#image_url').parent().parent().hide();
                    $('#slideruploadedfile').parent().parent().parent().parent().show();
                    $('#slideruploadedfile').parent().show();
                    var image_holder = $("#sliderimage");
                    image_holder.show();
                }
            }
            

            if ($.trim(b.redirect_activity) == 'category') {
                $('#layout_type_id').parent().parent().hide();
                $('#redirect_activity').val(b.redirect_activity);
                $('#category_id').val(b.category_id);
                $('#category_id').parent().parent().show();
                $('#redirect_banner_product_name').parent().parent().hide();
            } else if ($.trim(b.redirect_activity) == 'product') {
                $('#layout_type_id').parent().parent().hide();
                $('#redirect_activity').val(b.redirect_activity);
                $('#redirect_product_id').val(b.product_id);
                $('#redirect_product_name').val(b.product_name);
                $('#redirect_banner_product_name').parent().parent().show();
                $('#category_id').parent().parent().hide();
            } else if ($.trim(b.redirect_activity) == 'CMS') {
                $('#layout_type_id').parent().parent().hide();
                $('#redirect_activity').val(b.redirect_activity);
                $('#redirect_banner_cms_name').parent().parent().show()
                $('#category_id').parent().parent().hide();
                $('#redirect_product_id').val(b.category_id);
                $('#redirect_banner_product_name').parent().parent().hide();
                $('#category_id').parent().parent().hide();
            }
            
            else if ($.trim(b.redirect_activity) == 'layout') {
                $('#layout_type_id').parent().parent().show();
                $('#redirect_activity').val(b.redirect_activity);
                $('#layout_type_id').val(b.layout_type_id);
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
            }
            else {
                $('#redirect_activity').val(b.redirect_activity);
                $('#redirect_banner_product_name').parent().parent().hide();
                $('#category_id').parent().parent().hide();
                $('#layout_type_id').parent().parent().hide();
            }
            var lang = active_languages;
            for (i = 0; i < lang.length; i++) {
                $('#heading_of_banner_' + lang[i]).val(b.banner_heading_data[lang[i]]);
            }
            $('#custom_banner_alias_name').focus();
            $('.panel-footer').attr("id_custom_banner", a); 

        }
    });  
}
function edit_banner(d)
{
    $('#loader_module_list').remove();
    var a = jQuery.trim($(d).closest('tr').find('.td-vss-code').html());
    var option_name = '';
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&editBanner=true",
        data: 'id_banner=' + a,
        type: "post",
        beforeSend: function () {
            $(d).closest('tr').find('.btn-group-action').parent().after('<img id="loader_module_list" style="text-align: center;width:25px;height:25px;align:center" src=" ' + loader_url + '" alt="" border="0" />');
        },
        success: function(data)
        {
            $('#loader_module_list').remove();
            var b = JSON.parse(data);
            if (1) {
                
                $('#kb_banner_id').val(b.kb_banner_id);
                 $('#countdown_validity').val(b.countdown);
                $('#image_content_mode').val(b.image_contentMode);
                if ($.trim(b.image_type) != '') {
                    $('#image_type').val(b.image_type);
                    if ($.trim(b.image_type) == 'url'){
                        $('#sliderimage').attr('src', b.image_url);
                        $('#image_url').val(b.image_url);
                        $('#image_url').parent().parent().show();
                        $('#slideruploadedfile').parent().parent().parent().parent().show();
                        $('#slideruploadedfile').parent().hide();
                    }
                    if ($.trim(b.image_type) == 'image'){
                        $('#sliderimage').attr('src', b.image_url);
                        $('#image_url').val(b.image_url);
                        $('#image_url').parent().parent().hide();
                        $('#slideruploadedfile').parent().parent().parent().parent().show();
                        $('#slideruploadedfile').parent().show();
                        var image_holder = $("#sliderimage");
                        image_holder.show();
                    }
                }

                if ($.trim(b.redirect_activity) == 'category') {
                    $('#redirect_activity').val(b.redirect_activity);
                    $('#category_id').val(b.category_id);
                    $('#category_id').parent().parent().show();
                    $('#redirect_product_name').parent().parent().hide();
                    $('#layout_type_id').parent().parent().hide();
                } else if ($.trim(b.redirect_activity) == 'product') {
                    $('#redirect_activity').val(b.redirect_activity);
                    $('#redirect_product_id').val(b.product_id);
                    $('#redirect_product_name').val(b.product_name);
                    $('#redirect_product_name').parent().parent().show();
                    $('#category_id').parent().parent().hide();
                    $('#layout_type_id').parent().parent().hide();
                } else if ($.trim(b.redirect_activity) == 'layout') {
                    $('#redirect_activity').val(b.redirect_activity);
                    $('#layout_type_id').parent().parent().show();
                    $('#layout_type_id').val(b.layout_type_id);
                    $('#category_id').parent().parent().hide();
                    $('#redirect_product_name').parent().parent().hide();
                } else if ($.trim(b.redirect_activity) == 'CMS') {
                    $('#redirect_activity').val(b.redirect_activity);
                    $('#redirect_banner_cms_name').parent().parent().show()
                    $('#category_id').parent().parent().hide();
                    $('#redirect_product_id').val(b.category_id);
                    $('#redirect_product_name').parent().parent().hide();
                    $('#category_id').parent().parent().hide();
                    $('#layout_type_id').parent().parent().hide();
                } 
                else {
                    $('#redirect_activity').val(b.redirect_activity);
                    $('#redirect_product_name').parent().parent().hide();
                    $('#category_id').parent().parent().hide();
                    $('#layout_type_id').parent().parent().hide();
                }
                var lang = active_languages;
                for (i = 0; i < lang.length; i++) {
                    $('#heading_of_banner_' + lang[i]).val(b.banner_heading_data[lang[i]]);
                    $('#banner_heading_' + lang[i]).focus();
                }
                $('.panel-footer').attr("id_banner", a); 
                
                $('.vss-add-slider').slideDown("fast", function() {
//                    $('#add_new').html(close_new_entry);
//                    $('#add_new').attr('onclick', 'closePaymentMethod()');
//                    $('#form-configuration .panel h3').remove("#vss-button");
//                    $('#vss-button').appendTo($("#configuration_form .panel-heading"));
                });
            }
        }
    });
}
/*end: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
function submitbannersliderform(a)
{
    var id_layout = $('#id_layout').val();
    var id_component = $('#id_component_selected').val();
    var image_type = $('#image_type').val();
    var image_url = $('#image_url').val();
    var redirect_activity = $('#redirect_activity').val();
    var category_id = $('#category_id').val();
    var redirect_product_id = $('#redirect_banner_product_id').val();
    var image_content_mode = $('#image_content_mode').val();
    var redirect_product_name = $('#redirect_banner_product_name').val();
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var redirect_cms_id = $('#redirect_banner_cms_name').val();
    /*END:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var layout_type_id = $('#layout_type_id').val();
    var fd = new FormData();
    fd.append('image', $('#slideruploadedfile')[0].files[0]);
    fd.append('id_layout', id_layout);
    fd.append('id_component', id_component);
    fd.append('category_id', category_id);
    fd.append('redirect_activity', redirect_activity);
    fd.append('image_url', image_url);
    /* changes made by vibhaas */        
    fd.append('layout_type_id', layout_type_id);
    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('banner_heading_' + lang[i], $('#banner_heading_' + lang[i]).val());
    }
    /*start:changes made by knowband on 25 April 2019 to add banner heading*/
    for (i = 0; i < lang.length; i++) {
        fd.append('heading_of_banner_' + lang[i], $('#heading_of_banner_' + lang[i]).val());
    }
    /*end:changes made by knowband on 25 April 2019 to add banner heading*/
    /*start: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
    if ($('.panel-footer').attr('id_banner')) {
        fd.append('id_banner', $('.panel-footer').attr('id_banner'));
    } else {
        fd.append('id_banner', '');
    }
    /*end: changes made by aayushi on 31st jan 2020 to make changes for edit functionality*/
    if ($('#countdown_validity').is(":visible")) {
        fd.append('countdown_validity', $('#countdown_validity').val());
        fd.append('is_enabled_background_color', $('input[name="is_enabled_background_color"]:checked').val());
        fd.append('timer_background_color', $('input[name=timer_background_color]').val());
        fd.append('timer_text_color', $('input[name=timer_text_color]').val());
    }
    fd.append('image_content_mode', image_content_mode);
    fd.append('image_type', image_type);
    fd.append('redirect_product_id', redirect_product_id);
    fd.append('redirect_product_name', redirect_product_name);
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    fd.append('redirect_cms_id', redirect_cms_id);
    /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var banner_elem = $('.panel-footer').attr('element_type');
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveBannerSliderFormData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#kbGDPRDialogueModel').modal({
                show: 'true',
            });
            $('#submitOptionsslider2').prop("disabled", true);

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').empty();
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#sliderimage').hide();
                //$('#category_id').parent().parent().hide();
                $('#is_enabled_background_color_on').parent().closest('.form-group').hide();
                $('#countdown_validity').closest('.form-group').hide();
                $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* changes made by vibhaas to hide layout and category dropdown after saving banner */
                $('#category_id').parent().parent().hide();
                $('#layout_type_id').parent().parent().hide();
                /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                $('#confirmation_block_modal').show();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
		
                if (banner_elem == 'banner_square') {
	            $('.panel-footer').attr('element_type','banner_square');		
                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    var element_type = 'bannerSquareBannerimg_';
                    $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').parent().remove();
                    $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                    $.each( b.Added_Banners, function( key, value ) {
                        $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        $('p[id="bannerSquareBanner_elem_heading_'+ id_layout + '_'+ id_component +'"]').parent().remove();
                        if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                            $('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<span class="BSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading" id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            //$('div[id="bannerSList_'+ id_layout + '_' + id_component + '"]').append('<p id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'">');
                        }
                        
                        if (value.heading != '') {
                            $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                            $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                        } else {
                            $('p[id="'+ 'bannerSquareBanner_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                        }
                        $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image).css("width", "100%");
                    });
                    if (b.component_heading!=null) {
                        $('p[id="bannerSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }
                } else if (banner_elem == 'horizontal_banner') {
		    $('.panel-footer').attr('element_type','horizontal_banner');
                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    var element_type = 'bannerHorizontalSlideimg_';
                    var index = 1;
                    $.each( b.Added_Banners, function( key, value ) {
                        $('p[id="bannerHorizontalSlide_elem_heading_'+ id_layout + '_'+ id_component +'"]').remove();
                        $('img[class="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'bannerHorizontalSlideimg1_';
                        var element_type2 = 'bannerHorizontalSlideimg2_';
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').parent().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').parent().remove();    
                        $('p[id="bannerHorizontalSlide_elem_heading1_'+ id_layout + '_'+ id_component +'"]').remove();
                        $('p[id="bannerHorizontalSlide_elem_heading2_'+ id_layout + '_'+ id_component +'"]').remove();
                        if (index == 1) {
                            element_type = 'bannerHorizontalSlideimg1_';
                            var class_name = '.bannerHorizontalSlide_elem_heading1_' + id_layout + '_'+ id_component;
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                
                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList1').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                            }
                            if (value.heading != '') {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                            } else {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                            }
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                        } else if (index == 2) {
                            element_type = 'bannerHorizontalSlideimg2_';
                            var class_name = 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_'+ id_component;
//                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
//                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img>');
//                                $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList2').append('<p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p>');
//                            }
                              if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                                
                                    $('div[id="slidingBList_'+ id_layout + '_' + id_component + '"] > #bannerHorizontalSlideList2').append('<span class="BHSSection"><img id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'" src=""></img><p class="elem_heading"  id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"></p></span>');
                                }
                            if (value.heading != '') {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                            } else {
                                $('p[id="'+ 'bannerHorizontalSlide_elem_heading2_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                            }
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').attr('src', value.Image);
                        } 
                        index++;
                    });
                    if (b.component_heading!=null) {
                        $('h4[id="bannerHorizontalSlide_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }
                } else if (banner_elem == 'banner_grid') {
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('.panel-footer').attr('element_type', 'banner_grid');
                    var element_type = 'bannerGridimg1_';
                    var template_html = $('#bannerGRow').html();
                    $.each( b.Added_Banners, function(key, value) {
                        $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').find('#bannerGridimg').parent().remove();    
                        var productinfo_html = template_html;
                        productinfo_html = productinfo_html.replace(/bannerGridimg1/g, 'bannerGridimg1_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);
                        productinfo_html = productinfo_html.replace(/bannerGrid_elem_heading/g, 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id);


                        if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').length == "0") {
                            $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                        }
                        $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.kb_banner_id +'"]').attr('src', value.Image);
                        
                        if (value.heading != '') {
                            $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').text(value.heading);
                            $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'block');
                        } else {
                            $('p[id="'+ 'bannerGrid_elem_heading_' + id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').css('display', 'none');
                        }
                    });
                    $('div[id="bannerGRow1_'+ id_layout + '_' + id_component + '"] .bannerGridList').each(function () {    
                        if ($(this).children().length == 0){
                            $(this).remove();
                        }
                    });
                    if (b.component_heading!=null) {
                        $('h4[id="bannerGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                } else if (banner_elem == 'banner_countdown') {
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('.panel-footer').attr('element_type', 'banner_countdown');
                    var element_type = 'bannerCountdownimg1_';
                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    $.each( b.Added_Banners, function( key, value ) {
                        $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').find('#bannerCountdownimg').parent().remove();  
                        var template_html = $('.countdownlistContentContainer1').html();
                        template_html = template_html.replace(/bannerCountdownimg1/g, 'bannerCountdownimg1_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                        template_html = template_html.replace(/bannerCountdown_elem_heading/g, 'bannerCountdown_elem_heading_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                        template_html = template_html.replace(/countdown_banner_img_url/g, value.Image);
                        if (value.is_enabled_background_color == "1") {
                            template_html = template_html.replace(/background_color_of_timer_text/g, value.background_color);
                        } else {
                            template_html = template_html.replace(/background_color_of_timer_text/g, 'transparent');
                        }    
                        if ($('div[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                            $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').append(template_html);
                        }
                        
                        if (value.heading != '') {
                            $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                            $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                        } else {
                            $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                        }
                    });
                    if (b.component_heading!=null) {
                        $('h4[id="bannerCountdown_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $('.panel-footer').removeAttr('id_banner');
                showSuccessMessage(success_message);
                showUrlImage();
                uploadfile();
                autoCompleteProduct();
            }
        }
    });
    return false;
}
function submitProductform(a)
{
    var id_layout = $('#id_layout').val();
    var id_component = $('#id_component_selected').val();
    var category_id = $('#category_id').val();
    var number_of_product = $.trim($('#number_of_products').val());
    var category_id = $.trim($('#category_id').val());
    var layout_type_id = $.trim($('#layout_type_id').val());
    var product_list = $.trim($('#product_list').val());
    var category_products = $.trim($('#category_products').val());
    var product_type = $('#product_type').val();
    var image_content_mode = $('#image_content_mode').val();
    var fd = new FormData();
    fd.append('number_of_product', number_of_product);
    fd.append('id_component', id_component);
    fd.append('category_id', category_id);
    fd.append('id_layout', id_layout);
    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('component_heading_' + lang[i], $('#component_heading_' + lang[i]).val());
    }
    fd.append('product_type', product_type);
    fd.append('product_list', product_list);
    fd.append('category_products', category_products);
    
    fd.append('image_content_mode', image_content_mode);
    var pro_elem = $('.panel-footer').attr('element_type');     
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveProductFormData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#submitOptionsslider2').prop("disabled", true);
        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').empty();
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#confirmation_block_modal').show();
                //$('#category_id').parent().parent().hide();
                showHideProductType(a);
                showSuccessMessage(success_message);
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                if (pro_elem == 'productGridimg') {
                    $('.panel-footer').attr('element_type', 'productGridimg');
                var element_type = 'productGridimg_';
                var template_html = $('#productGRow').html();
                var id_ProductArray = [];
////                $(".productGridRow").remove();
                $.each( b.added_Products.products_for_preview, function(key, value) {
//                    $(".productGridRow1").remove();
                    $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"]').find('#productGridimg1').parent().remove();  
                    id_ProductArray.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                    var img_id = element_type + id_layout + '_' + id_component;
                    var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                    var productinfo_html = template_html;
                    productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                    productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                    productinfo_html = productinfo_html.replace(/productGridimg/g, 'productGridimg_' +id_layout + '_' + id_component+ '_' +value.id);
                    if ($('img[id="'+ element_type + id_layout + '_' + id_component + '_' +  value.id +'"]').length == "0") {
                            $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
            }
                    $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                    $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                    $('img[id="'+ element_type + id_layout + '_' + id_component + '_' + value.id +'"]').attr('src', value.src);
                });
                    $('div[id="productGRow1_'+ id_layout + '_' + id_component + '"] img').each(function () {    
                        if($.inArray($(this).attr('id'), id_ProductArray) == -1){
                            $(this).parent().remove();
        }
    });
                    if (b.added_Products.component_heading_preview!=null) {
                        $('p[id="productGrid_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                } else if (pro_elem == 'productSquareimg') {
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    var element_type = 'productSquareimg1_';
                    var template_html = $('.productSquareimg1').parent().html();
                    //$(".iframe_html #productSList_" + id_layout + '_' + id_component).empty();
                    var id_array = [];
                    $.each( b.added_Products.products_for_preview, function(key, value) {
                        
                        var element_type = 'productSquareimg1_';
                        id_array.push(element_type + value.id);
                        var template_html = $('.productSquareimg1').parent().html();
                        var img_id = element_type + id_layout + '_' + id_component;
                        var img_id_replaced = img_id.replace(img_id, img_id+'_'+value.id);
                        var productinfo_html = template_html;
                        productinfo_html = productinfo_html.replace(/productSquareimg1/g, 'productSquareimg1_' + value.id);
                        productinfo_html = productinfo_html.replace(/productName/g, 'productName_' + value.id);
                        productinfo_html = productinfo_html.replace(/productPrice/g, 'productPrice_' + value.id);
                        if ($('img[id="'+ element_type + value.id +'"]').length == "0") {
                            $('div[id="productSList_'+ id_layout + '_' + id_component + '"]').append(productinfo_html);
                        }
                        $('h5[id="productName_' + value.id +'"]').html(value.name.substring(0, 8));
                        $('h6[id="productPrice_' + value.id +'"]').html(value.price);
                        $('img[id="'+ element_type + value.id +'"]').attr('src', value.src);
                        $('img[id="'+ element_type + value.id +'"]').css('width','282px');
                        $('img[id="'+ element_type + value.id +'"]').css('height','261px');
    //                    
                    });
                    $('div[id="productSList_'+ id_layout + '_' + id_component + '"] img').each(function () {    
                        if($.inArray($(this).attr('id'), id_array) == -1){
                            $(this).next().remove();
                            $(this).remove();
                        }
                    });
                    if (b.added_Products.products_for_preview.length > 0) {
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').next().remove();
                        $('div[id="productSList_'+ id_layout + '_' + id_component + '"] .productSquareimg').remove();
                    }
                    if (b.added_Products.component_heading_preview!=null) {
                        $('h4[id="productSquare_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                } else if (pro_elem == 'slidingProducts') {
                    $('.panel-footer').attr('element_type', 'slidingProducts');
                    var element_type = 'slidingProductsimg';
                    var template_html = $('#slidingPRow').html();
                    var index = 1;
                    var id_ProductHorizontal_array1 = [];
                    var id_ProductHorizontal_array2 = [];
                    var id_ProductHorizontal_array3 = [];
                    $.each( b.added_Products.products_for_preview, function( key, value ) {
                       // $('img[id="'+element_type+ id_layout + '_' + id_component + '"]').remove();
                        var element_type1 = 'slidingProductsimg1_';
                        var element_type2 = 'slidingProductsimg2_';
                        var element_type3 = 'slidingProductsimg3_';    
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type1+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type2+ id_layout + '_' + id_component + '"]').remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').next().remove();
                        $('img[id="'+element_type3+ id_layout + '_' + id_component + '"]').remove();
                        if (index == 1) {
                            
                            element_type = 'slidingProductsimg1_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                            id_ProductHorizontal_array1.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                        } else if (index == 2) {
                            
                            element_type = 'slidingProductsimg2_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                            id_ProductHorizontal_array2.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                        } else if (index == 3) {
                            
                            element_type = 'slidingProductsimg3_';
                            var productinfo_html = $('.productSlideList').find('div[class="productContent"]').html();
                            productinfo_html = productinfo_html.replace(/ProductName/g, 'ProductName_' + value.id);
                            productinfo_html = productinfo_html.replace(/ProductPrice/g, 'ProductPrice_' + value.id);
                            if ($('img[id="'+element_type+ id_layout + '_' + id_component + '_' + value.id + '"]').length == "0") {
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append('<img id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'" src=""></img>');
                                $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3').append(productinfo_html);
                            }
                            $('h5[id="ProductName_' + value.id +'"]').html(value.name.substring(0, 8));
                            $('h6[id="ProductPrice_' + value.id +'"]').html(value.price);
                            $('img[id="'+ element_type + id_layout + '_' + id_component + '_' +value.id +'"]').attr('src', value.src);
                            id_ProductHorizontal_array3.push(element_type + id_layout + '_' + id_component + '_' +value.id);
                        } 
                        index++;
                    });
                    console.log(id_ProductHorizontal_array1);
                   // console.log(id_ProductHorizontal_array2);
                    //console.log(id_ProductHorizontal_array3);
                    $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList1 img').each(function () {    
                        
                        if($.inArray($(this).attr('id'), id_ProductHorizontal_array1) == -1){
                            $(this).next().remove();
                            $(this).remove();
                        }
                    });
                    $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList2 img').each(function () {    
                        if($.inArray($(this).attr('id'), id_ProductHorizontal_array2) == -1){
                            $(this).next().remove();
                            $(this).remove();
                        }
                    });
                    $('div[id="slidingPRow1_'+ id_layout + '_' + id_component + '"] > #productSlideList3 img').each(function () {    
                        if($.inArray($(this).attr('id'), id_ProductHorizontal_array3) == -1){
                            $(this).next().remove();
                            $(this).remove();
                        }
                    });
                    if (b.added_Products.component_heading_preview!=null) {
                        $('h4[id="slidingProducts_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.added_Products.component_heading_preview);
                    }
                    
                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                
            }
        }
    });
    return false;
}
function submitCountdownbannersliderform(a)
{
    var id_layout = $('#id_layout').val();
    var id_component = $('#id_component_selected').val();
    var image_type = $('#image_type').val();
    var image_url = $('#image_url').val();
    var redirect_activity = $('#redirect_activity').val();
    var category_id = $('#category_id').val();
    var redirect_product_id = $('#redirect_banner_product_id').val();
    var image_content_mode = $('#image_content_mode').val();
    var redirect_product_name = $('#redirect_banner_product_name').val();
    var layout_type_id = $('#layout_type_id').val();
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var redirect_cms_id = $('#redirect_banner_cms_name').val();
    /*END:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    var fd = new FormData();
    fd.append('image', $('#slideruploadedfile')[0].files[0]);
    fd.append('id_layout', id_layout);
    fd.append('id_component', id_component);
    fd.append('category_id', category_id);
    fd.append('redirect_activity', redirect_activity);
    fd.append('image_url', image_url);
    fd.append('layout_type_id', layout_type_id);
    var lang = active_languages;
    for (i = 0; i < lang.length; i++) {
        fd.append('banner_heading_' + lang[i], $('#banner_heading_' + lang[i]).val());
    }
    /*start:changes made by knowband on 25 April 2019 to add banner heading*/
    for (i = 0; i < lang.length; i++) {
        fd.append('heading_of_banner_' + lang[i], $('#heading_of_banner_' + lang[i]).val());
    }
    /*end:changes made by knowband on 25 April 2019 to add banner heading*/
    if ($('#countdown_validity').is(":visible")) {
        /*changes made by vibhaas */
         fd.append('countdown_validity_start', $('#countdown_validity_start').val());
         /* changes over */
        fd.append('countdown_validity', $('#countdown_validity').val());
        fd.append('is_enabled_background_color', $('input[name="is_enabled_background_color"]:checked').val());
        fd.append('timer_background_color', $('input[name=timer_background_color]').val());
        fd.append('timer_text_color', $('input[name=timer_text_color]').val());
    }
    fd.append('image_content_mode', image_content_mode);
    fd.append('image_type', image_type);
    fd.append('redirect_product_id', redirect_product_id);
    fd.append('redirect_product_name', redirect_product_name);
    /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    fd.append('redirect_cms_id', redirect_cms_id);
    /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
    
    if ($('.panel-footer').attr('id_banner')) {
        fd.append('id_banner', $('.panel-footer').attr('id_banner'));
    } else {
        fd.append('id_banner', '');
    }
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&saveBannerSliderFormData=true",
        data: fd,
        type: "post",
        processData: false,
        contentType: false,
        beforeSend: function () {
//            $('#kbGDPRDialogueModel').modal({
//                show: 'true',
//            });
//            $('body').addClass("kb_loading");
            //$('modal-body').addClass("kb_loading");
            // $('#kbGDPRDialogueModel .modal-body').append('<img id="loader_module_list" style="width:50px;height:50px;align:center" src=" ' + loader_url + '" alt="" border="0" />');

        },
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').empty();
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#redirect_banner_product_name').parent().parent().hide();
                /*start:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                /*end:changes made by knowband on 25 April 2019 to add CMS page redirect activity*/
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                $('#confirmation_block_modal').show();
                //$('#kbGDPRDialogueModel').modal('hide');
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('.panel-footer').attr('element_type', 'banner_countdown');
                    var element_type = 'bannerCountdownimg1_';
                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").show();
                    $.each( b.Added_Banners, function( key, value ) {
                        $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').find('#bannerCountdownimg').parent().remove();  
                        var template_html = $('.countdownlistContentContainer1').html();
                        template_html = template_html.replace(/bannerCountdownimg1/g, 'bannerCountdownimg1_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                        template_html = template_html.replace(/bannerCountdown_elem_heading/g, 'bannerCountdown_elem_heading_' +id_layout+'_'+id_component+'_'+ value.kb_banner_id);
                        template_html = template_html.replace(/countdown_banner_img_url/g, value.Image);
                        if (value.is_enabled_background_color == "1") {
                            template_html = template_html.replace(/background_color_of_timer_text/g, value.background_color);
                        } else {
                            template_html = template_html.replace(/background_color_of_timer_text/g, 'transparent');
                        }    
                        if ($('div[id="'+element_type+ id_layout + '_' + id_component + '_' + value.kb_banner_id + '"]').length == "0") {
                        $('div[id="bannerCountdownList_'+ id_layout + '_' + id_component + '"]').append(template_html);
                    }
                    if (value.heading != '') {
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').html(value.heading);
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'block');
                    } else {
                        $('div[id="'+ 'bannerCountdownimg1_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('bottom', '5px');
                        $('p[id="'+ 'bannerCountdown_elem_heading_' + id_layout + '_' + id_component + '_' +value.kb_banner_id +'"]').css('display', 'none');
                    }
                    });
                    if (b.component_heading!=null) {
                        $('h4[id="bannerCountdown_comp_heading_'+ id_layout + '_'+ id_component +'"]').html(b.component_heading);
                    }
                     $('.panel-footer').removeAttr('id_banner');
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                showSuccessMessage(success_message);
                showUrlImage();
                uploadfile();
                setDate();
                setColor();
                autoCompleteProduct();
            }
        }
    });
    return false;
}
/*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
function delete_custom_banner(a) {
    var a = jQuery.trim($(a).closest('tr').find('.td-vss-code').html());
    var id_component = $('#id_component_selected').val();
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteCustomBanner=true",
        data: 'id_custom_banner=' + a + '&id_component=' + id_component,
        type: "post",
        
        
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
               $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#kbGDPRDialogueModel').modal({
                    show: 'true',
                });
                $('#sliderimage').hide();
                $('#category_id').parent().parent().hide();
                $('#redirect_banner_product_name').parent().parent().hide();
                /* 25 April 2019*/
                $('#redirect_banner_cms_name').parent().parent().hide();
                //showHidebackgroundColor();
                showSuccessMessage(custom_banner_delete_message);
                autoCompleteProduct();
                showUrlImage();
                uploadfile();
                setDate();
                setColor();
                var lang = active_languages;
                for (i = 0; i < lang.length; i++) {
                    $('#banner_heading_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#banner_heading_' + lang[i]).parent().addClass('col-lg-5');
                    $('#heading_of_banner_' + lang[i]).parent().removeClass('col-lg-9');
                    $('#heading_of_banner_' + lang[i]).parent().addClass('col-lg-5');
                }
                $('#width_of_banner').on('click change', function(e) {
                    if ($(this).val() && $('input[name="slideruploadedfile"]').val()) {
                        var img = new Image();
                        img.src = $('#sliderimage').attr('src');
                        var width = img.width;
                        var height = img.height;
                        
                        $('#height_of_banner').val(Math.ceil((height*$('#width_of_banner').val())/width));
                    }
                });
                $('.front_custom_banner_preview').parent().css('width', '40%');
                $('.front_custom_banner_preview').parent().css('padding-left', '5px');
//                $.ajax({
//                    url: ajaxaction + "&configure=kbmobileapp&getComponentType=true",
//                    data: '&id_component=' + id_component,
//                    type: "post",
//                    success: function (data)
//                    {
//                        
//                    }
//                });

            }
        }
    });
    //showUrlImage();
}
/*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
function delete_banner_slider(a) {
    var a = jQuery.trim($(a).closest('tr').find('.td-vss-code').html());
    var id_component = $('#id_component_selected').val();
    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    var id_layout = $('#id_layout').val();
    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteSliderBanner=true",
        data: 'id_banner=' + a + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            var b = JSON.parse(data);
            if (1) {
                $('#kbGDPRDialogueModel .modal-body').html('');
                $('#kbGDPRDialogueModel .modal-body').empty();
                $('#kbGDPRDialogueModel .modal-body').append(b.html);
                $('#component_edit_popup').append(b.html);
                $('#category_id').parent().parent().hide();
                $('#redirect_product_name').parent().parent().hide();
                $('#image_url').parent().parent().hide();
                $('#slideruploadedfile').parent().parent().parent().parent().hide();
                showUrlImage();
                uploadfile();
                showSuccessMessage(banner_delete_message);
                countdownbannerDatepicker();
                //showHidebackgroundColor();
                autoCompleteProduct();
                setDate();
                setColor();
                /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $("img[id$='"+id_layout+'_'+id_component+'_'+a+"']").next().remove();
                $("img[id$='"+id_layout+'_'+id_component+'_'+a+"']").remove();
                $("div[id$='"+id_layout+'_'+id_component+'_'+a+"']").parent().remove();
                if ($("p[id$='"+id_layout+'_'+id_component+'_'+a+"']").remove()) {
                    var p_length = "1";
                } else {
                    var p_length = "0";
                }
                if ($(".iframe_html div[id$='"+id_layout+'_'+id_component+"']").find('p').length == p_length) {
                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").hide();
                }
                
//                if ($(".iframe_html div[id$='"+id_layout+'_'+id_component+"']").find('h5').length =="0") {
//                    $(".iframe_html li[id='layout_component_"+id_layout+'_'+id_component+"']").hide();
//                }
                /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                $.ajax({
                    url: ajaxaction + "&configure=kbmobileapp&getComponentType=true",
                    data: '&id_component=' + id_component,
                    type: "post",
                    success: function (data)
                    {
                        if (data != 'banners_countdown') {
                            $('#is_enabled_background_color_on').parent().closest('.form-group').hide();
                            $('#countdown_validity').closest('.form-group').hide();
                            $('.kbsw_wheel_color').closest('.form-group').parents('.form-group').hide();
                        }
                    }
                });

            }
        }
    });
    showUrlImage();
}

function uploadfile() {
    $('#slideruploadedfile').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                $('input[name="slideruploadedfile"]').parent().append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                $('input[name="slideruploadedfile"]').parent().append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="slideruploadedfile"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
}
/*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
function uploadtopCategoryfile(id_component, id_layout) {
/*ends:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
    $('#slideruploadedfile_1').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_1"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_1"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_1"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_1"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_1");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_1').attr('src', e.target.result);
                    }
                    
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('img[id="top_category_1_'+ id_layout + '_' + id_component + '"]').attr('src', $('#sliderimage_1').attr('src'));
                    var image_cat_holder = $('img[id="top_category_1_'+ id_layout + '_' + id_component + '"]');

                    image_cat_holder.empty();

                    var reader1 = new FileReader();
                    reader1.onload = function (f) {
                        $('img[id="top_category_1_'+ id_layout + '_' + id_component + '"]').attr('src', f.target.result);
                }
                    image_cat_holder.show();
                    reader1.readAsDataURL($(this)[0].files[0]);
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    
                }
                $('input[name="slideruploadedfile_1"]').parent().find('.kb_error_message').remove();
            }

        }
        else
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_2').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_2"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_2"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_2"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_2"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_2");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_2').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('img[id="top_category_2_'+ id_layout + '_' + id_component + '"]').attr('src', $('#sliderimage_2').attr('src'));
                    var image_cat_holder = $('img[id="top_category_1_'+ id_layout + '_' + id_component + '"]');

                    image_cat_holder.empty();

                    var reader1 = new FileReader();
                    reader1.onload = function (f) {
                        $('img[id="top_category_2_'+ id_layout + '_' + id_component + '"]').attr('src', f.target.result);
                }
                    image_cat_holder.show();
                    reader1.readAsDataURL($(this)[0].files[0]);
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    
                }
                $('input[name="slideruploadedfile_2"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_4').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_4"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_4"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_4"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_4"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_2");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_4').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('img[id="top_category_4_'+ id_layout + '_' + id_component + '"]').attr('src', $('#sliderimage_4').attr('src'));
                    var image_cat_holder = $('img[id="top_category_4_'+ id_layout + '_' + id_component + '"]');

                    image_cat_holder.empty();

                    var reader1 = new FileReader();
                    reader1.onload = function (f) {
                        $('img[id="top_category_4_'+ id_layout + '_' + id_component + '"]').attr('src', f.target.result);
                }
                    image_cat_holder.show();
                    reader1.readAsDataURL($(this)[0].files[0]);
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                }
                $('input[name="slideruploadedfile_4"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_3').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
               
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_3"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_3"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_3"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_3"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_3");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_3').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                    /*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                    $('img[id="top_category_3_'+ id_layout + '_' + id_component + '"]').attr('src', $('#sliderimage_3').attr('src'));
                    var image_cat_holder = $('img[id="top_category_3_'+ id_layout + '_' + id_component + '"]');

                    image_cat_holder.empty();

                    var reader1 = new FileReader();
                    reader1.onload = function (f) {
                        $('img[id="top_category_3_'+ id_layout + '_' + id_component + '"]').attr('src', f.target.result);
                }
                    image_cat_holder.show();
                    reader1.readAsDataURL($(this)[0].files[0]);
                    /*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*/
                }
                $('input[name="slideruploadedfile_3"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_5').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_5"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_5"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_5"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_5"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_5");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_5').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="slideruploadedfile_5"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_6').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_6"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_6"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_6"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_6"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_6");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_6').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="slideruploadedfile_6"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_7').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_7"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_7"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_7"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_7"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_7");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_7').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="slideruploadedfile_7"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });
    $('#slideruploadedfile_8').on('change', function (e) {
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0)
        {
            var files = $(this)[0].files[0];
            var file_data = e.target.files;
            var file_mimetypes = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'application/x-shockwave-flash',
                'image/psd',
                'image/bmp',
                'image/tiff',
                'application/octet-stream',
                'image/jp2',
                'image/iff',
                'image/vnd.wap.wbmp',
                'image/xbm',
                'image/vnd.microsoft.icon',
                'image/webp'
            ];

            var file_format = false;
            for (i = 0; i < file_mimetypes.length; i++) {
                if (files.type == file_mimetypes[i]) {
                    file_format = true;
                }
            }

            if (!file_format)
            {
                /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_8"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_8"]').parent(':first').append('<span class="kb_error_message">' + invalid_file_format_txt + '</span>');
                }
                slider_banner_file_error = true;

            } else if (files.size > default_file_size) {
                 /**
                 * Added fixes to error message was appended multiple times 
                 * TGmay2023 File-error-message
                 * @date 08-05-2023
                 * @modifier Tanisha Gupta
                 */
                if ($('input[name="slideruploadedfile_8"]').parent().find('.kb_error_message').length == 0) {
                    // Append error message
                    $('input[name="slideruploadedfile_8"]').parent(':first').append('<span class="kb_error_message">' + file_size_error_txt + '</span>');
                }
                slider_banner_file_error = true;
            } else {
                slider_banner_file_error = false;
                if (typeof (FileReader) != "undefined") {

                    var image_holder = $("#sliderimage_8");

                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#sliderimage_8').attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }
                $('input[name="slideruploadedfile_8"]').parent().find('.kb_error_message').remove();
            }

        }
        else // Internet Explorer 9 Compatibility
        {
            $('#notification_error').html(invalid_file_txt);
            file_error = true;
        }
    });


}
function trashBannerSquareComponentFunction(a) {

    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteBannerSquarecomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);

            }
        }
    });

}
/*start:changes made by aayushi on 3rd January 2020 to add custom banners*/
function trashBannerCustomComponentFunction (a) {

    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteBannerCustomcomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
/*end:changes made by aayushi on 3rd January 2020 to add custom banners*/
function trashBannerCountdownComponentFunction(a) {

    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteBannerCountdowncomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashBannerGridComponentFunction(a) {

    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteBannerGridcomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);

            }
        }
    });

}
function trashBannerHorizontalComponentFunction(a) {

    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteBannerHorizontalcomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashLastAccessComponentFunction(a) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteLastAccesscomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashProductSquareComponentFunction(a) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteProductSquarecomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashProductGridComponentFunction(a) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteProductGridcomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashProductHorizontalComponentFunction(a) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)
    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteProductHorizonatlcomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function trashTopcategoryComponentFunction(a) {
    num_of_component = parseInt($('#number_of_component').val());
    num_of_component = num_of_component - 1;
    $('#number_of_component').val(num_of_component)

    var str = $(a).attr('id');
    var array = str.split("_");
    var id_component = array[1];
    var id_layout = $('#id_layout').val();
    $('#id_component_selected').val(id_component);
    $.ajax({
        url: ajaxaction + "&configure=kbmobileapp&deleteTopcategorycomponent=true",
        data: 'id_layout=' + id_layout + '&id_component=' + id_component,
        type: "post",
        success: function (data)
        {
            if (1) {
                $(a).parents('.slide').remove();
                preview_content();
                showSuccessMessage(component_delete);
            }
        }
    });

}
function closeLayoutForm()
{
    $('.layout_add_edit_form').slideUp("fast", function () {

    });
}
