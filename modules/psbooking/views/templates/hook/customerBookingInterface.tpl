{*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.txt
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
*}

<link rel="stylesheet" href="{$module_dir}psbooking/views/css/customerBookingInterface.css">
<link rel="stylesheet" href="{$module_dir}psbooking/views/css/datepickerCustom.css">
{if $bookingProductInformation['booking_type'] == 1}
  <p id="booking_product_available_qty">
    {l s='Available quantity' mod='psbooking'} &nbsp;&nbsp;<span class="product_max_avail_qty_display">{$maxAvailableQuantity}</span>
  </p>
{/if}
<div class="product-customization">
  <div class="card card-block wk-booking-container">
    <p class="h4 card-title">{l s='Book your slot' mod='psbooking'}</p>
    <div class="wk-booking-block wk-booking-content col-sm-12 wk_padding_zero">
      {if $bookingProductInformation['booking_type'] == 1}
        <div class="date_range_form">
          <div class="form-group row">
            <div class="col-lg-6 col-md-12">
              <span>{l s='From' mod='psbooking'}</span>
              <div class="input-group">
                <input id="booking_date_from" autocomplete="off" class="booking_date_from form-control" type="text" readonly="true" placeholder="{l s='Book From' mod='psbooking'}" value="{if isset($date_from)}{$date_from}{/if}">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              {l s='To' mod='psbooking'}
              <div class="input-group">
                <input id="booking_date_to" autocomplete="off" class="booking_date_to form-control" type="text" readonly="true" placeholder="{l s='Book To' mod='psbooking'}" value="{if isset($date_to)}{$date_to}{/if}">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-4 col-md-7">
              <span class="control-label">{l s='Quantity' mod='psbooking'}</span>
              <input
                  type="text"
                  id="booking_product_quantity_wanted"
                  value="1"
                  class="input-group form-control"
                  min="1"
                  aria-label="{l s='Quantity' mod='psbooking'}"
              >
            </div>
          </div>
        </div>
      {else}
        <div class="date_range_form">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <input id="booking_time_slot_date" autocomplete="off" class="booking_time_slot_date form-control" type="text" readonly="true" placeholder="Book From" value="{if isset($date_from)}{$date_from}{/if}">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
          </div>
          <div id="booking_product_time_slots">
            {if isset($bookingTimeSlots) && $bookingTimeSlots}
              {foreach $bookingTimeSlots as $time_slot}
                <div class="time_slot_checkbox row">
                  <label class="col-sm-9 form-control-static">
                    <input {if !$time_slot['available_qty']}disabled="disabled"{/if} {if $time_slot['checked']}checked="checked"{/if} type="checkbox" data-slot_price="{$time_slot['price']}" value="{$time_slot['id']}" class="product_blooking_time_slot">&nbsp;&nbsp;&nbsp;<span class="time_slot_price">{if isset($time_slot['formated_slot_price_regular']) && $show_regular_price_after_discount}<strike>{$time_slot['formated_slot_price_regular']}</strike> {/if}{$time_slot['formated_slot_price']}</span>&nbsp;&nbsp;{l s='for' mod='psbooking'}&nbsp;&nbsp;<span class="time_slot_range">{$time_slot['time_slot_from']} &nbsp;-&nbsp;{$time_slot['time_slot_to']}</span>
                      <span id="booking_product_available_qty"><span class="product_max_avail_qty_display">{l s='Max' mod='psbooking'} - {$time_slot['available_qty']|escape:'htmlall':'UTF-8'}</span>
                      </span>
                  </label>
                  {if $time_slot['available_qty']}
                    <label class="col-sm-3" id="slot_quantity_container_{$time_slot['id']|escape:'htmlall':'UTF-8'}">
                      <input type="hidden" id="slot_max_avail_qty_{$time_slot['id']|escape:'htmlall':'UTF-8'}" class="slot_max_avail_qty" value="{$time_slot['available_qty']|escape:'htmlall':'UTF-8'}">
                      <input type="text" class="booking_time_slots_quantity_wanted  form-control" value="1" min="1">
                  {else}
                    <label class="col-sm-3 form-control-static" id="slot_quantity_container_{$time_slot['id']|escape:'htmlall':'UTF-8'}">
                    <span class="booked_slot_text">{l s='Slot Booked' mod='psbooking'}!</span>
                  {/if}
                  </label>
                </div>
              {/foreach}
            {else}
              {l s='No slots available' mod='psbooking'}
            {/if}
          </div>
        </div>
      {/if}
      <hr>
      <p class="col-sm-12 alert-danger booking_product_errors">
      </p>
      <div class="row">
        <div id="bookings_in_select_range" class="col-sm-12 table-responsive">
        </div>
        <div class="col-sm-6">
          <input type="hidden" id="max_available_qty" value="{$maxAvailableQuantity}" class="input-group form-control">
          <p class="wk_total_booking_price_container">
            <span class="booking_total_price_text">{l s='Total' mod='psbooking'}</span>&nbsp;&nbsp;<span class="booking_total_price">{if isset($show_regular_price_after_discount) && $show_regular_price_after_discount && isset($productFeaturePriceRegular)}<strike>{$productFeaturePriceRegular}</strike> {/if}{$productFeaturePrice}</span>
          </p>
        </div>
        <div class="col-sm-6">
          <div class="col-sm-12">
            <img src="{$module_dir}psbooking/views/img/ajax-loader.gif" class="booking_loading_img" alt={l s='Not Found' mod='psbooking'}/>
            <button button class="btn btn-primary pull-sm-right" id="booking_button"  booking_type="{$bookingProductInformation['booking_type']}" {if $selectedDatesDisabled || !$maxAvailableQuantity || (isset($totalSlotsQty) && $totalSlotsQty == 0) || (isset($bookingTimeSlots) && !$bookingTimeSlots)}disabled{/if}>{l s='Book Now' mod='psbooking'}</button>
          </div>
          <p class="col-sm-12 unavailable_slot_err" style="{if ($selectedDatesDisabled || !$maxAvailableQuantity || (isset($totalSlotsQty) && $totalSlotsQty == 0) || (isset($bookingTimeSlots) && !$bookingTimeSlots))}display:block;{elseif (!$maxAvailableQuantity || (isset($totalSlotsQty) && $totalSlotsQty == 0) || (isset($bookingTimeSlots) && !$bookingTimeSlots))}display:block;{else}display:none;{/if}">
            <span>{l s='No booking available' mod='psbooking'} !</span>
          </p>
        </div>
      </div>
      {if isset($bookingPricePlans) && $bookingPricePlans && $show_feature_price_rules}
        <div class="feature_plans_info col-sm-12 wk_padding_zero">
          <hr>
          <strong>{l s='Note' mod='psbooking'}</strong> : {l s='Following booking price rules are applying for this product' mod='psbooking'} -
          <ol class="product_booking_feature_plans" type="1">
            {foreach $bookingPricePlans as $key=>$pricePlan}
              <li>
                {$pricePlan['feature_price_name']} :
                {if $pricePlan['impact_way'] == 1}
                  {l s='Discount of' mod='psbooking'}
                {else}
                  {l s='Extra charges of' mod='psbooking'}
                {/if}
                {if $pricePlan['impact_type'] == 1}
                  {$pricePlan['impact_value']|round:2}%
                {else}
                  {$pricePlan['impact_value_formated']} (tax excl.)
                {/if}
                {l s='on unit price' mod='psbooking'}
                {if $pricePlan['date_selection_type'] == 1}
                  {l s='from' mod='psbooking'} {Tools::displayDate($pricePlan['date_from'])} {l s='to' mod='psbooking'} {Tools::displayDate($pricePlan['date_to'])}
                  {if $pricePlan['is_special_days_exists'] == 1}
                    {l s='for special days' mod='psbooking'}
                    {foreach $pricePlan['special_days'] as $day}
                      {$day}
                    {/foreach}
                  {/if}
                {else}
                  {l s='for' mod='psbooking'} {Tools::displayDate($pricePlan['date_from'])}
                {/if}
                .
              </li>
            {/foreach}
          </ol>
        </div>
        <div class="feature_plans_priority col-sm-12 alert alert-info ">
          <strong>{l s='Important' mod='psbooking'}</strong> : {l s='If mutiple plans apply on a date then plans priority will be :' mod='psbooking'}</br>
          {foreach $featurePricePriority as $key => $priority}
            {$priority} {if $key < 2 }>{/if}
          {/foreach}
        </div>
      {/if}
      {if !empty($wk_google_map_key) && $wk_show_map}
        <div class="row bk_map_div wk_padding_zero">
          <div id="map"></div>
        </div>
      {/if}
    </div>
  </div>
</div>
<script>
  if (typeof addTouchSpin !== 'undefined' && typeof addTouchSpin === 'function') {
    addTouchSpin();
  }
</script>
{if !empty($wk_google_map_key) && $wk_show_map}
  {* this js here because this tpl is calling by ajax on add to cart *}
  <script>
    var loadJS = function(url, implementationCode, location){
        //url is URL of external file, implementationCode is the code
        //to be called from the file, location is the location to
        //insert the <script> element

        var scriptTag = document.createElement('script');
        scriptTag.src = url;

        scriptTag.onload = implementationCode;
        scriptTag.onreadystatechange = implementationCode;

        location.appendChild(scriptTag);
    };
    var key = "{$wk_google_map_key}";
    //if loaded google map then call only map function otherwise load google map js
    if (typeof google === 'object' && typeof google.maps === 'object') {
      initMap();
    } else {
      loadJS("https://maps.googleapis.com/maps/api/js?key="+key+"&libraries=&v=weekly", initMap, document.body);
    }

    function initMap() {
      if (typeof(wk_booking_show_map) != 'undefined' && wk_booking_show_map != "0") {
          const mapOptions = {
              zoom: 8,
              center: { lat: parseFloat(wk_booking_latitude), lng: parseFloat(wk_booking_longitude) },
          };
          map = new google.maps.Map(document.getElementById("map"), mapOptions);
          const marker = new google.maps.Marker({
              position: { lat: parseFloat(wk_booking_latitude), lng: parseFloat(wk_booking_longitude) },
              map: map,
          });
          const infowindow = new google.maps.InfoWindow({
              content: "<p>" + wk_booking_address + "</p>",
          });
          google.maps.event.addListener(marker, "click", () => {
              infowindow.open(map, marker);
          });
      }
    }
  </script>
{/if}

