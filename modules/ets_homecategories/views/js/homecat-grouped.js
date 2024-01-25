/*
* 2007-2017 PrestaShop
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
*/
$(document).ready(function(){    
    if($('.homecat_tabs .homecat_product_list_wrapper .product_list').length > 0)
    {
        $('.homecat_tabs .homecat_product_list_wrapper:first-child').addClass('active').children('.product_list').addClass('active');
    }
    if($('.ets_homecat_category_list > li').length > 0)
       $('.ets_homecat_category_list > li:first-child .homecat_tab_name').addClass('active').parents('li').addClass('active'); 
    $('.homecat_ajax_tab').click(function(){        
        $('.homecat_ajax_tab').removeClass('active').parent('li').removeClass('active');          
        $(this).addClass('active').parents('li').addClass('active');
        if($('.hc-wrapper-homecat-tab-'+$(this).data('id-category')).length > 0)
        {
            $('.homecat_tabs .homecat_product_list_wrapper').removeClass('active').children('.product_list').removeClass('active');
            $('.hc-wrapper-homecat-tab-'+$(this).data('id-category')).addClass('active').children('.product_list').addClass('active');
        }
        else
        if($(this).hasClass('loading'))
        {
            $('.homecat_tabs').addClass('loading');
        }                  
        if($('.hc-wrapper-homecat-tab-'+$(this).data('id-category')).length <=0)
        {
            if($(this).hasClass('loading') && !$('.homecat_tabs').hasClass('loading'))
                $('.homecat_tabs').addClass('loading');
            if(!$(this).hasClass('loading'))
            {
                $(this).addClass('loading');
                $('.homecat_tabs').addClass('loading');
                $.ajax({
                    url: homecat_ajax_link,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id_category: $(this).data('id-category')!='all' ? parseInt($(this).data('id-category')) : 0, 
                        homecateajax: true, 
                        ets_homecat_order_seed: ets_homecat_order_seed,                      
                    },
                    success: function(json)
                    {
                        $('.homecat_tabs').append(json.html);
                        $('.homcat_tab_'+json.id_category).removeClass('loading');                        
                        if($('.homcat_tab_'+json.id_category).hasClass('active'))
                        {
                            $('.homecat_tabs').removeClass('loading');
                            $('.homecat_tabs .homecat_product_list_wrapper').removeClass('active').children('.product_list').removeClass('active');                            
                            if($('.hc-wrapper-homecat-tab-'+json.id_category).length > 0)
                                $('.hc-wrapper-homecat-tab-'+json.id_category).addClass('active').children('.product_list').addClass('active');     
                        }
                        if(ETS_HOMECAT_ENBLE_CAROUSEL && $('.homecat-tab-'+json.id_category).length > 0)
                            $('.homecat-tab-'+json.id_category).owlCarousel({
                                items : 4,
                                responsive : {
                                    // breakpoint from 0 up
                                    0 : {
                                        items : 1
                                    },
                                    // breakpoint from 480 up
                                    545 : {
                                        items : 2
                                    },
                                    // breakpoint from 768 up
                                    801 : {
                                        items : 3
                                    },
                                    1200 : {
                                        items : 4
                                    }
                                },
                                navText: ["<i class='material-icons'>&#xE314;</i>","<i class='material-icons'>&#xE315;</i>"]
                            });                                                   
                    },
                    error: function()
                    {
                        $('.homecat_ajax_tab').removeClass('loading');
                        $('.homecat_tabs').removeClass('loading');
                    }
                });            
            }
        }
        else
        {
            $('.homecat_tabs').removeClass('loading');
        }
        return false;
    });
    $('#homecat_sort_by').change(function(){
        if(!(typeof $(this).attr('disabled') !== typeof undefined && $(this).attr('disabled') !== false && $(this).attr('disabled')=='disabled'))
        {
            $(this).attr('disabled','disabled');
            $('.homecat_tabs').addClass('loading');
            $.ajax({
                url: homecat_ajax_link,
                type: 'post',
                dataType: 'json',
                data: {
                    id_category: $('.homecat_tab_name.active').length > 0 && $('.homecat_tab_name.active').data('id-category')!='all' ? parseInt($('.homecat_tab_name.active').data('id-category')) : 0, 
                    homecateajaxsort: true,  
                    sort_by: $(this).val(),  
                    ets_homecat_order_seed: ets_homecat_order_seed,                   
                },
                success: function(json)
                {
                    if(!json.error)
                    {
                        $('.homecat_tabs .homecat_product_list_wrapper').remove();
                        $('.homecat_tabs').prepend(json.html);     
                        if($('.homecat-tab-'+json.id_category).length > 0)
                            $('.homecat-tab-'+json.id_category).addClass('active').parent('.homecat_product_list_wrapper').addClass('active');
                        if(ETS_HOMECAT_ENBLE_CAROUSEL && $('.homecat-tab-'+json.id_category).length > 0)
                            $('.homecat-tab-'+json.id_category).owlCarousel({
                                items : 4,
                                responsive : {
                                    // breakpoint from 0 up
                                    0 : {
                                        items : 1
                                    },
                                    // breakpoint from 480 up
                                    545 : {
                                        items : 2
                                    },
                                    // breakpoint from 768 up
                                    801 : {
                                        items : 3
                                    },
                                    1200 : {
                                        items : 4
                                    }
                                },
                                navText: ["<i class='material-icons'>&#xE314;</i>","<i class='material-icons'>&#xE315;</i>"]
                            });    
                    }
                    $('.homecat_tabs').removeClass('loading');                       
                    $('#homecat_sort_by').prop("disabled", false);                                               
                },
                error: function()
                {                    
                    $('.homecat_tabs').removeClass('loading');
                    $('#homecat_sort_by').prop("disabled", false);
                }
            }); 
        }             
    });
    $(document).on('click','.homecat-load-more',function(){
        if(!$(this).hasClass('active'))
        {
            $(this).addClass('active');            
            $.ajax({
                url: homecat_ajax_link,
                type: 'post',
                dataType: 'json',
                data: {
                    id_category: $(this).data('id-category')!='all' ? parseInt($(this).data('id-category')) : 0, 
                    homecateajaxloadmore: true,  
                    product_count: parseInt($(this).attr('data-product-count')),  
                    ets_homecat_order_seed: ets_homecat_order_seed, 
                },
                success: function(json)
                {
                    if(json.html && json.hasProducts)
                    {
                        $('.homecat-tab-'+json.id_category).append(json.html);
                    }
                    $('.homecat-load-more-'+json.id_category).attr('data-product-count',json.productCount); 
                    if(json.nomore)
                        $('.homecat-load-more-'+json.id_category).addClass('hidden');
                    $('.homecat-load-more-'+json.id_category).removeClass('active');                                             
                },
                error: function()
                {                    
                    $('.homecat-load-more').removeClass('active');     
                }
            }); 
        }  
    });   
    if($('.homecat_product_list_wrapper > .product_list').length && ETS_HOMECAT_ENBLE_CAROUSEL > 0)
         $('.homecat_product_list_wrapper > .product_list').owlCarousel({
            items : 4,
            responsive : {
                // breakpoint from 0 up
                0 : {
                    items : 1
                },
                // breakpoint from 480 up
                545 : {
                    items : 2
                },
                // breakpoint from 768 up
                801 : {
                    items : 3
                },
                1200 : {
                    items : 4
                }
            },
            navText: ["<i class='material-icons'>&#xE314;</i>","<i class='material-icons'>&#xE315;</i>"]
         });
});