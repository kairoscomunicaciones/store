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


<div class="col-md-12">
  <hr class="">
  {if $bookingProductInformation['booking_type'] == 1}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-lg-4">
                    {l s='Product name' mod='psbooking'}
                </label>
                <label class="control-label col-lg-8 wk-text-left">
                    {$product_name}
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <p id="booking_product_available_qty">
                    <label class="control-label col-lg-4">
                        {l s='Available quantity' mod='psbooking'}
                    </label>
                    <label class="control-label col-lg-8 wk-text-left">
                        <span class="product_max_avail_qty_display"> {$maxAvailableQuantity} </span>
                    </label>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label col-lg-4 required">{l s='From' mod='psbooking'}</label>
          <div class="col-lg-8">
            <div class="row">
              <div class="input-group">
                  <input type="hidden" id="product_page_product_id" value="{$idProduct}">
                  <input id="booking_date_from" autocomplete="off" readonly="true" placeholder="{l s='Book From' mod='psbooking'}" type="text" class="text booking_date_from" value="{if isset($date_from)}{$date_from}{/if}">
                  <span class="input-group-addon">
                      <i class="icon-calendar"></i>
                  </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label col-lg-4 required">{l s='To' mod='psbooking'}</span>
          </label>
          <div class="col-lg-8">
            <div class="row">
              <div class="input-group">
                  <input id="booking_date_to" autocomplete="off" readonly="true" placeholder="{l s='Book To' mod='psbooking'}" type="text" class="text booking_date_to" value="{if isset($date_to)}{$date_to}{/if}">
                  <span class="input-group-addon">
                      <i class="icon-calendar"></i>
                  </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label col-lg-4 required">{l s='Quantity' mod='psbooking'}</label>
          <div class="col-lg-8">
            <div class="row">
              <input type="text" id="booking_product_quantity_wanted" class="text" value="1" min="1">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="max_available_qty" value="{$maxAvailableQuantity}" class="input-group form-control">
          <label class="control-label col-lg-4">
            {l s='Total' mod='psbooking'}
            </span>
          </label>
          <div class="col-lg-8">
            <label class="control-label col-lg-8 wk-text-left">
                <span class="booking_total_price"> {$productFeaturePrice} </span>
            </label>
          </div>
        </div>
      </div>
    </div>
  {else}
    <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              <label class="control-label col-lg-4">
                  {l s='Product name' mod='psbooking'}
              </label>
              <label class="control-label col-lg-8 wk-text-left">
                  {$product_name}
              </label>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label col-lg-4 required">
            {l s='On' mod='psbooking'}
          </label>
          <div class="col-lg-8">
            <div class="row">
              <div class="input-group">
                <input type="hidden" id="product_page_product_id" value="{$idProduct}">
                <input id="booking_time_slot_date" autocomplete="off" readonly="true" placeholder="{l s='Book From' mod='psbooking'}" type="text" class="text booking_time_slot_date" value="{if isset($date_from)}{$date_from}{/if}">
                <span class="input-group-addon">
                    <i class="icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="max_available_qty" value="{$maxAvailableQuantity}" class="input-group form-control">
          <label class="control-label col-lg-4">
            {l s='Total' mod='psbooking'}
            </span>
          </label>
          <div class="col-lg-8">
            <label class="control-label col-lg-8 wk-text-left">
                <span class="booking_total_price"> {$productFeaturePrice} </span>
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="booking_product_time_slots">
      {if isset($bookingTimeSlots) && $bookingTimeSlots}
        {foreach $bookingTimeSlots as $time_slot}
          <div class="time_slot_checkbox row">
            <label class="col-sm-4 form-control-static">
              <input {if !$time_slot['available_qty']}disabled="disabled"{/if} {if $time_slot['checked']}checked="checked"{/if} type="checkbox" data-slot_price="{$time_slot['price']}" value="{$time_slot['id']}" class="product_blooking_time_slot">&nbsp;&nbsp;&nbsp;<span class="time_slot_price">{$time_slot['formated_slot_price']}</span>&nbsp;&nbsp;{l s='For' mod='psbooking'}&nbsp;&nbsp;<span class="time_slot_range">{$time_slot['time_slot_from']} &nbsp;-&nbsp;{$time_slot['time_slot_to']}</span>
            </label>
            {if $time_slot['available_qty']}
              <label class="col-sm-4" id="slot_quantity_container_{$time_slot['id']|escape:'htmlall':'UTF-8'}">
              <div class="input-group col-sm-6">
                <input type="hidden" id="slot_max_avail_qty_{$time_slot['id']|escape:'htmlall':'UTF-8'}" class="slot_max_avail_qty" value="{$time_slot['available_qty']|escape:'htmlall':'UTF-8'}">
                <input type="text" class="booking_time_slots_quantity_wanted  form-control" value="1" min="1">
                <div class="input-group-addon" id="qty_avail_{$time_slot['id']|escape:'htmlall':'UTF-8'}">/{$time_slot['available_qty']|escape:'htmlall':'UTF-8'}</div>
              </div>
            {else}
              <label class="col-sm-4 form-control-static" id="slot_quantity_container_{$time_slot['id']|escape:'htmlall':'UTF-8'}">
              <span class="booked_slot_text">{l s='Slot Booked' mod='psbooking'}!</span>
            {/if}
            </label>
          </div>
        {/foreach}
      {else}
        {l s='No slots available' mod='psbooking'}
      {/if}
    </div>
  {/if}
  <div class="row">
      <p class="col-sm-12 alert alert-danger booking_product_errors">
      </p>
      <div id="bookings_in_select_range" class="col-sm-12 table-responsive">
      </div>
  </div>
  <div class="row">
      <div class="col-md-12 wk-text-right">
          <img src="{$module_dir}psbooking/views/img/ajax-loader.gif" class="booking_loading_img" alt={l s='Not Found' mod='psbooking'}/>
          <button button="" class="btn btn-primary" id="booking_button" booking_type="{$bookingProductInformation['booking_type']}" {if $selectedDatesDisabled || !$maxAvailableQuantity || (isset($totalSlotsQty) && $totalSlotsQty == 0) || (isset($bookingTimeSlots) && !$bookingTimeSlots)}disabled{/if}>{l s='Add' mod='psbooking'}</button>
      </div>
      <p class="col-md-12 unavailable_slot_err wk-text-right"style="{if !$maxAvailableQuantity || (isset($totalSlotsQty) && $totalSlotsQty == 0) || (isset($bookingTimeSlots) && !$bookingTimeSlots)}display:block;{else}display:none;{/if}">
          <span>{l s='No booking available' mod='psbooking'} !</span>
      </p>
  </div>
</div>

<script>
  var available_after = '{$available_after}';
  var disabledDays = {$disabledDays|json_encode nofilter};
  var disabledDates = {$disabledDates|json_encode nofilter};
  var timeSlotDays = {$timeSlotDays|json_encode nofilter};
  var timeSlotType = {$timeSlotType|json_encode nofilter};
  var selectedDatesJson = '{$selectedDates nofilter}';

</script>