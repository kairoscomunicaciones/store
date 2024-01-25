<html>
<head>
    
<link rel="stylesheet" href={$mod_dir nofilter}views/css/front/kb_front.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_dir nofilter}views/css/front/slick-theme.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_dir nofilter}views/css/font-awesome.min.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_dir nofilter}views/css/front/slick.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<script type="text/javascript" src="{$mod_dir nofilter}views/js/front/datetimepicker/bootstrap-datetimepicker.js"></script>{*Variable contains URL, escape not required*}
<script type="text/javascript" src={$kbmobileapp_dir nofilter}views/js/kb_front.js" ></script>{*Variable contains URL, escape not required*}
<script type="text/javascript" src={$mod_dir nofilter}views/js/front/slick.js" ></script>{*Variable contains URL, escape not required*}
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="overflow-y: hidden; height:200px;">

{if isset($hotel_rooms) && !empty($hotel_rooms)} 
        
            {if isset($hotel_rooms[0]['room_facilities']) && !empty($hotel_rooms[0]['room_facilities'])}
                <div class="kb-room-facilities-block">
                    
                    <div class="kb_room_slider kb-center slider-nav" style="display: flex;    margin: 2%;">
                        {foreach $hotel_rooms[0]['room_facilities'] as $facilities}
                            <div class="kb-slick-block col-lg-3" style="width:200px !important;">
                                {if $facilities['image_type'] == 'font'}
                                    <i class="fa fa-3x {$facilities['font_awesome_icon']}"></i>
                                {else}
                                    <img src="{$facilities['upload_image']}" height="62" width="62">
                                {/if}
                                <div>
                                    <label>
                                        <span>{$facilities['name']}</span>
                                    </label>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                    <script>
                        var kb_slider_item_count = '{$hotel_rooms[0]['room_facilities']|count|escape:'quotes':'UTF-8'}';
                    </script>
                </div>
            {/if}
            
        
{/if}

<script>
    var kb_no_room_available = "{l s='No Rooms available for the selected category' mod='kbmobileapp' }";
    var room_available = "{l s='Available Rooms' mod='kbmobileapp' }";
</script>

</body>
<style>
    .kb-slick-block col-lg-3{
        width:200px !important;
    }
    
    .images-container .slick-track {
{*        width:100% !important;*}
    }
    
</style>
</html>
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
* @copyright 2019 Knowband
* @license   see file: LICENSE.txt
*
*}