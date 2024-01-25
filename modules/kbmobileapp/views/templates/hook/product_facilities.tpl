<html>
<head>
    
<link rel="stylesheet" href={$mod_path nofilter}views/css/front/kb_front.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_path nofilter}views/css/front/slick-theme.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_path nofilter}views/css/font-awesome.min.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<link rel="stylesheet" href={$mod_path nofilter}views/css/front/slick.css" type="text/css" media="all">{*Variable contains URL, escape not required*}
<script type="text/javascript" src={$mod_path nofilter}views/js/front/datetimepicker/bootstrap-datetimepicker.js"></script>{*Variable contains URL, escape not required*}
<script type="text/javascript" src={$kbmobileapp_dir nofilter}views/js/kb_front.js" ></script>{*Variable contains URL, escape not required*}
<script type="text/javascript" src={$mod_path nofilter}views/js/front/slick.js" ></script>{*Variable contains URL, escape not required*}
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="height:200px; overflow-y: hidden;">
{if !empty($booking_facilities)}
    {*<div class="kb-product-facilities-block page-product-box">*}
        
        <div class="kb_slider kb-center slider-nav" style="display: flex;    margin: 2%;">
            {foreach $booking_facilities as $facilities}
                <div class="kb-slick-block col-lg-4" style="width:200px !important;">
                    {if $facilities['image_type'] == 'font'}
                        <i class="fa fa-4x {$facilities['font_awesome_icon']|escape:'htmlall':'UTF-8'}"></i>
                    {else}
                        <img src="{$facilities['upload_image']|escape:'quotes':'UTF-8'}" height="62" width="62">
                    {/if}
                    <div>
                        <label>
                            <span>{$facilities['name']|escape:'htmlall':'UTF-8'}</span>
                        </label>
                    </div>
                </div>
            {/foreach}
        </div>
        <script>
            var kb_slider_item_count = '{$booking_facilities|count|escape:'htmlall':'UTF-8'}';

        </script>
{*    </div>*}
    
{/if}
</body>
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
