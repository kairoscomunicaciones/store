<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:29:31
  from 'module:psbookingviewstemplatesho' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7034b8f27b2_71823460',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c4ec5bd7517e7762a52fe0ab7c638b3496cd0f66' => 
    array (
      0 => 'module:psbookingviewstemplatesho',
      1 => 1697325102,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a7034b8f27b2_71823460 (Smarty_Internal_Template $_smarty_tpl) {
?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8');?>
psbooking/views/css/customerBookingInterface.css">
<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8');?>
psbooking/views/css/datepickerCustom.css">
<?php if ($_smarty_tpl->tpl_vars['bookingProductInformation']->value['booking_type'] == 1) {?>
  <p id="booking_product_available_qty">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Available quantity','mod'=>'psbooking'),$_smarty_tpl ) );?>
 &nbsp;&nbsp;<span class="product_max_avail_qty_display"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['maxAvailableQuantity']->value, ENT_QUOTES, 'UTF-8');?>
</span>
  </p>
<?php }?>
<div class="product-customization">
  <div class="card card-block wk-booking-container">
    <p class="h4 card-title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Book your slot','mod'=>'psbooking'),$_smarty_tpl ) );?>
</p>
    <div class="wk-booking-block wk-booking-content col-sm-12 wk_padding_zero">
      <?php if ($_smarty_tpl->tpl_vars['bookingProductInformation']->value['booking_type'] == 1) {?>
        <div class="date_range_form">
          <div class="form-group row">
            <div class="col-lg-6 col-md-12">
              <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'From','mod'=>'psbooking'),$_smarty_tpl ) );?>
</span>
              <div class="input-group">
                <input id="booking_date_from" autocomplete="off" class="booking_date_from form-control" type="text" readonly="true" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Book From','mod'=>'psbooking'),$_smarty_tpl ) );?>
" value="<?php if ((isset($_smarty_tpl->tpl_vars['date_from']->value))) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['date_from']->value, ENT_QUOTES, 'UTF-8');
}?>">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'To','mod'=>'psbooking'),$_smarty_tpl ) );?>

              <div class="input-group">
                <input id="booking_date_to" autocomplete="off" class="booking_date_to form-control" type="text" readonly="true" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Book To','mod'=>'psbooking'),$_smarty_tpl ) );?>
" value="<?php if ((isset($_smarty_tpl->tpl_vars['date_to']->value))) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['date_to']->value, ENT_QUOTES, 'UTF-8');
}?>">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-4 col-md-7">
              <span class="control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quantity','mod'=>'psbooking'),$_smarty_tpl ) );?>
</span>
              <input
                  type="text"
                  id="booking_product_quantity_wanted"
                  value="1"
                  class="input-group form-control"
                  min="1"
                  aria-label="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quantity','mod'=>'psbooking'),$_smarty_tpl ) );?>
"
              >
            </div>
          </div>
        </div>
      <?php } else { ?>
        <div class="date_range_form">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <input id="booking_time_slot_date" autocomplete="off" class="booking_time_slot_date form-control" type="text" readonly="true" placeholder="Book From" value="<?php if ((isset($_smarty_tpl->tpl_vars['date_from']->value))) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['date_from']->value, ENT_QUOTES, 'UTF-8');
}?>">
                <span class="input-group-addon">
                  <i class="material-icons">date_range</i>
                </span>
              </div>
            </div>
          </div>
          <div id="booking_product_time_slots">
            <?php if ((isset($_smarty_tpl->tpl_vars['bookingTimeSlots']->value)) && $_smarty_tpl->tpl_vars['bookingTimeSlots']->value) {?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bookingTimeSlots']->value, 'time_slot');
$_smarty_tpl->tpl_vars['time_slot']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['time_slot']->value) {
$_smarty_tpl->tpl_vars['time_slot']->do_else = false;
?>
                <div class="time_slot_checkbox row">
                  <label class="col-sm-9 form-control-static">
                    <input <?php if (!$_smarty_tpl->tpl_vars['time_slot']->value['available_qty']) {?>disabled="disabled"<?php }?> <?php if ($_smarty_tpl->tpl_vars['time_slot']->value['checked']) {?>checked="checked"<?php }?> type="checkbox" data-slot_price="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['price'], ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['id'], ENT_QUOTES, 'UTF-8');?>
" class="product_blooking_time_slot">&nbsp;&nbsp;&nbsp;<span class="time_slot_price"><?php if ((isset($_smarty_tpl->tpl_vars['time_slot']->value['formated_slot_price_regular'])) && $_smarty_tpl->tpl_vars['show_regular_price_after_discount']->value) {?><strike><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['formated_slot_price_regular'], ENT_QUOTES, 'UTF-8');?>
</strike> <?php }
echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['formated_slot_price'], ENT_QUOTES, 'UTF-8');?>
</span>&nbsp;&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'for','mod'=>'psbooking'),$_smarty_tpl ) );?>
&nbsp;&nbsp;<span class="time_slot_range"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['time_slot_from'], ENT_QUOTES, 'UTF-8');?>
 &nbsp;-&nbsp;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['time_slot']->value['time_slot_to'], ENT_QUOTES, 'UTF-8');?>
</span>
                      <span id="booking_product_available_qty"><span class="product_max_avail_qty_display"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Max','mod'=>'psbooking'),$_smarty_tpl ) );?>
 - <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['time_slot']->value['available_qty'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                      </span>
                  </label>
                  <?php if ($_smarty_tpl->tpl_vars['time_slot']->value['available_qty']) {?>
                    <label class="col-sm-3" id="slot_quantity_container_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['time_slot']->value['id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                      <input type="hidden" id="slot_max_avail_qty_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['time_slot']->value['id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="slot_max_avail_qty" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['time_slot']->value['available_qty'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                      <input type="text" class="booking_time_slots_quantity_wanted  form-control" value="1" min="1">
                  <?php } else { ?>
                    <label class="col-sm-3 form-control-static" id="slot_quantity_container_<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['time_slot']->value['id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                    <span class="booked_slot_text"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Slot Booked','mod'=>'psbooking'),$_smarty_tpl ) );?>
!</span>
                  <?php }?>
                  </label>
                </div>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php } else { ?>
              <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No slots available','mod'=>'psbooking'),$_smarty_tpl ) );?>

            <?php }?>
          </div>
        </div>
      <?php }?>
      <hr>
      <p class="col-sm-12 alert-danger booking_product_errors">
      </p>
      <div class="row">
        <div id="bookings_in_select_range" class="col-sm-12 table-responsive">
        </div>
        <div class="col-sm-6">
          <input type="hidden" id="max_available_qty" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['maxAvailableQuantity']->value, ENT_QUOTES, 'UTF-8');?>
" class="input-group form-control">
          <p class="wk_total_booking_price_container">
            <span class="booking_total_price_text"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Total','mod'=>'psbooking'),$_smarty_tpl ) );?>
</span>&nbsp;&nbsp;<span class="booking_total_price"><?php if ((isset($_smarty_tpl->tpl_vars['show_regular_price_after_discount']->value)) && $_smarty_tpl->tpl_vars['show_regular_price_after_discount']->value && (isset($_smarty_tpl->tpl_vars['productFeaturePriceRegular']->value))) {?><strike><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productFeaturePriceRegular']->value, ENT_QUOTES, 'UTF-8');?>
</strike> <?php }
echo htmlspecialchars($_smarty_tpl->tpl_vars['productFeaturePrice']->value, ENT_QUOTES, 'UTF-8');?>
</span>
          </p>
        </div>
        <div class="col-sm-6">
          <div class="col-sm-12">
            <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8');?>
psbooking/views/img/ajax-loader.gif" class="booking_loading_img" alt=<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Not Found','mod'=>'psbooking'),$_smarty_tpl ) );?>
/>
            <button button class="btn btn-primary pull-sm-right" id="booking_button"  booking_type="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bookingProductInformation']->value['booking_type'], ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['selectedDatesDisabled']->value || !$_smarty_tpl->tpl_vars['maxAvailableQuantity']->value || ((isset($_smarty_tpl->tpl_vars['totalSlotsQty']->value)) && $_smarty_tpl->tpl_vars['totalSlotsQty']->value == 0) || ((isset($_smarty_tpl->tpl_vars['bookingTimeSlots']->value)) && !$_smarty_tpl->tpl_vars['bookingTimeSlots']->value)) {?>disabled<?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Book Now','mod'=>'psbooking'),$_smarty_tpl ) );?>
</button>
          </div>
          <p class="col-sm-12 unavailable_slot_err" style="<?php if (($_smarty_tpl->tpl_vars['selectedDatesDisabled']->value || !$_smarty_tpl->tpl_vars['maxAvailableQuantity']->value || ((isset($_smarty_tpl->tpl_vars['totalSlotsQty']->value)) && $_smarty_tpl->tpl_vars['totalSlotsQty']->value == 0) || ((isset($_smarty_tpl->tpl_vars['bookingTimeSlots']->value)) && !$_smarty_tpl->tpl_vars['bookingTimeSlots']->value))) {?>display:block;<?php } elseif ((!$_smarty_tpl->tpl_vars['maxAvailableQuantity']->value || ((isset($_smarty_tpl->tpl_vars['totalSlotsQty']->value)) && $_smarty_tpl->tpl_vars['totalSlotsQty']->value == 0) || ((isset($_smarty_tpl->tpl_vars['bookingTimeSlots']->value)) && !$_smarty_tpl->tpl_vars['bookingTimeSlots']->value))) {?>display:block;<?php } else { ?>display:none;<?php }?>">
            <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No booking available','mod'=>'psbooking'),$_smarty_tpl ) );?>
 !</span>
          </p>
        </div>
      </div>
      <?php if ((isset($_smarty_tpl->tpl_vars['bookingPricePlans']->value)) && $_smarty_tpl->tpl_vars['bookingPricePlans']->value && $_smarty_tpl->tpl_vars['show_feature_price_rules']->value) {?>
        <div class="feature_plans_info col-sm-12 wk_padding_zero">
          <hr>
          <strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Note','mod'=>'psbooking'),$_smarty_tpl ) );?>
</strong> : <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Following booking price rules are applying for this product','mod'=>'psbooking'),$_smarty_tpl ) );?>
 -
          <ol class="product_booking_feature_plans" type="1">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bookingPricePlans']->value, 'pricePlan', false, 'key');
$_smarty_tpl->tpl_vars['pricePlan']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['pricePlan']->value) {
$_smarty_tpl->tpl_vars['pricePlan']->do_else = false;
?>
              <li>
                <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pricePlan']->value['feature_price_name'], ENT_QUOTES, 'UTF-8');?>
 :
                <?php if ($_smarty_tpl->tpl_vars['pricePlan']->value['impact_way'] == 1) {?>
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Discount of','mod'=>'psbooking'),$_smarty_tpl ) );?>

                <?php } else { ?>
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Extra charges of','mod'=>'psbooking'),$_smarty_tpl ) );?>

                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['pricePlan']->value['impact_type'] == 1) {?>
                  <?php echo htmlspecialchars(round($_smarty_tpl->tpl_vars['pricePlan']->value['impact_value'],2), ENT_QUOTES, 'UTF-8');?>
%
                <?php } else { ?>
                  <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pricePlan']->value['impact_value_formated'], ENT_QUOTES, 'UTF-8');?>
 (tax excl.)
                <?php }?>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'on unit price','mod'=>'psbooking'),$_smarty_tpl ) );?>

                <?php if ($_smarty_tpl->tpl_vars['pricePlan']->value['date_selection_type'] == 1) {?>
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'from','mod'=>'psbooking'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['pricePlan']->value['date_from']), ENT_QUOTES, 'UTF-8');?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'to','mod'=>'psbooking'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['pricePlan']->value['date_to']), ENT_QUOTES, 'UTF-8');?>

                  <?php if ($_smarty_tpl->tpl_vars['pricePlan']->value['is_special_days_exists'] == 1) {?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'for special days','mod'=>'psbooking'),$_smarty_tpl ) );?>

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pricePlan']->value['special_days'], 'day');
$_smarty_tpl->tpl_vars['day']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->do_else = false;
?>
                      <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['day']->value, ENT_QUOTES, 'UTF-8');?>

                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                  <?php }?>
                <?php } else { ?>
                  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'for','mod'=>'psbooking'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars(Tools::displayDate($_smarty_tpl->tpl_vars['pricePlan']->value['date_from']), ENT_QUOTES, 'UTF-8');?>

                <?php }?>
                .
              </li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </ol>
        </div>
        <div class="feature_plans_priority col-sm-12 alert alert-info ">
          <strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Important','mod'=>'psbooking'),$_smarty_tpl ) );?>
</strong> : <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'If mutiple plans apply on a date then plans priority will be :','mod'=>'psbooking'),$_smarty_tpl ) );?>
</br>
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featurePricePriority']->value, 'priority', false, 'key');
$_smarty_tpl->tpl_vars['priority']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['priority']->value) {
$_smarty_tpl->tpl_vars['priority']->do_else = false;
?>
            <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['priority']->value, ENT_QUOTES, 'UTF-8');?>
 <?php if ($_smarty_tpl->tpl_vars['key']->value < 2) {?>><?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
      <?php }?>
      <?php if (!empty($_smarty_tpl->tpl_vars['wk_google_map_key']->value) && $_smarty_tpl->tpl_vars['wk_show_map']->value) {?>
        <div class="row bk_map_div wk_padding_zero">
          <div id="map"></div>
        </div>
      <?php }?>
    </div>
  </div>
</div>
<?php echo '<script'; ?>
>
  if (typeof addTouchSpin !== 'undefined' && typeof addTouchSpin === 'function') {
    addTouchSpin();
  }
<?php echo '</script'; ?>
>
<?php if (!empty($_smarty_tpl->tpl_vars['wk_google_map_key']->value) && $_smarty_tpl->tpl_vars['wk_show_map']->value) {?>
    <?php echo '<script'; ?>
>
    var loadJS = function(url, implementationCode, location){
        //url is URL of external file, implementationCode is the code
        //to be called from the file, location is the location to
        //insert the <?php echo '<script'; ?>
> element

        var scriptTag = document.createElement('script');
        scriptTag.src = url;

        scriptTag.onload = implementationCode;
        scriptTag.onreadystatechange = implementationCode;

        location.appendChild(scriptTag);
    };
    var key = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['wk_google_map_key']->value, ENT_QUOTES, 'UTF-8');?>
";
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
  <?php echo '</script'; ?>
>
<?php }?>

<?php }
}
