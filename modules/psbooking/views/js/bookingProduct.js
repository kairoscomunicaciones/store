/**
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
*/

$(document).ready(function() {
    $('a[data-toggle="tab"]').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        var id = $(e.target).attr("href");
        localStorage.setItem('selectedTab', id)
    });

    var selectedTab = localStorage.getItem('selectedTab');

    if (selectedTab != null) {
        $('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');
    }

    $('input[type=radio][name=show_map]').change(function() {

        if (this.value == 0) {
            $('.show_map_div').hide();
        }
        else if (this.value == 1) {
            $('.show_map_div').show();
        }
    });

    //Tab active code
    if ($('#active_tab').val() != '') {
        var active_tab = $('#active_tab').val();
        changeTabStatus(active_tab);
    }

    $(document).on("click", '.submitBookingProduct, #availability-search-submit', function(e) {
        //put active tab in input hidden type
        var active_tab_id = $('.wk-tabs-panel .nav-tabs li.active a').attr('href');
        if (typeof active_tab_id !== 'undefined') {
            var active_tab_name = active_tab_id.substring(1, active_tab_id.length);
            $('#active_tab').val(active_tab_name);
        }
    });

    $(document).on("click", '.submitBookingProduct', function(e) {
        //to stop adding multiple times when click on submit button
        var button = $(this);
        setTimeout(function () {
            button.attr('disabled', true);
        }, 100)
        //get all checked category value in a input hidden type name 'product_category'
        var rawCheckedID = [];
        $('.jstree-clicked').each(function() {
            var rawIsChecked = $(this).parent('.jstree-node').attr('id');
            rawCheckedID.push(rawIsChecked);
        });

        $('#product_category').val(rawCheckedID.join(","));

        var checkbox_length = $('#product_category').val();
        if (checkbox_length == 0) {
            // return false;
        }
    });

    // select which type of booking is
    $('#booking_type').on('change', function() {
        if ($(this).val() == 1) {
            $('.booking_price_period').text(day_text);
        } else if ($(this).val() == 2) {
            $('.booking_price_period').text(slot_text);
        }
    });

    //date range row append
    $(document).on('click', '#add_more_date_ranges', function() {
        var date_ranges_length = $('.booking_date_ranges').length;
        var changeKey = $('.single_date_range_slots_container').length + 1;

        html = '<div class="single_date_range_slots_container" date_range_slot_num="' + date_ranges_length + '">';
        html += '<div  class="form-group table-responsive-row col-sm-5 booking_date_ranges">';
        html += '<table class="table">';
        html += '<thead>';
        html += '<tr>';
        html += '<th class="center">';
        html += '<span>' + date_from_txt + '</span>';
        html += '</th>';
        html += '<th class="center">';
        html += '<span>' + date_to_txt + '</span>';
        html += '</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        html += '<tr>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control sloting_date_from" type="text" name="sloting_date_from[' + date_ranges_length +']" value="" change_date = "sloting_date_from_' + changeKey +'" change_key = "'+ changeKey +'" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-calendar"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';

        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control sloting_date_to" type="text" name="sloting_date_to[' + date_ranges_length +']" value="" change_date = "sloting_date_to_' + changeKey +'" change_key = "'+ changeKey +'" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-calendar"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
        html += '<div  class="form-group table-responsive-row col-sm-7 time_slots_prices_table_div">  ';
        html += '<table class="table time_slots_prices_table">';
        html += '<thead>';
        html += '<tr>';
        html += '<th class="center">';
        html += '<span>' + slot_time_from_txt + '</span>';
        html += '</th>';
        html += '<th class="center">';
        html += '<span>' + slot_time_to_txt + '</span>';
        html += '</th>';
        html += '<th class="center">';
        html += '<span>' + price_txt + '</span>';
        html += '</th>';
        html += '<th class="center">';
        html += '<span>' + 'Qty' + '</span>';
        html += '</th>';
        html += '<th class="center">';
        html += '<span>' + status_txt + '</span>';
        html += '</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        html += '<tr>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input id="booking_time_from" autocomplete="off" class="booking_time_from" type="text" name="booking_time_from' + date_ranges_length + '[]" value="" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control booking_time_to" type="text" name="booking_time_to' + date_ranges_length + '[]" value="" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="day_range_price" name="slot_range_price' + date_ranges_length + '[]" value="' + parseFloat($('#product_price').val()).toFixed(2) + '">';
        html += '<span class="input-group-addon">' + defaultCurrencySign + '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="day_range_qty" name="slot_range_qty' + date_ranges_length + '[]" value="' + $('#product_quantity').val() + '">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="slot_status_div">';
        html += '<input type="hidden" value="1" name="slot_active' + date_ranges_length + '[]" class="time_slot_status day_time_slot_status">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
        html += '</td>';
        html += '</tr>';
        html += '</tbody>';
        html += '</table>';
        html += '<div class="form-group">';
        html += '<div class="col-lg-12 text-right">';
        html += '<button class="add_more_time_slot_price" class="btn btn-default" type="button" data-size="s" data-style="expand-right">';
        html += '<i class="icon-calendar-empty"></i>' + '&nbsp;'+add_more_slots_txt;
        html += '</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $('.time_slots_prices_content').append(html);
    });

    //time slots row append
    $(document).on('click', '.add_more_time_slot_price_day', function() {
        var date_ranges_length = $(this).closest('.single_date_range_slots_container').attr('date_range_slot_num');
        html = '<tr>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control booking_time_from day_time_from" type="text" name="booking_time_day_from' + date_ranges_length + '[]" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control booking_time_to day_time_to" type="text" name="booking_time_day_to' + date_ranges_length + '[]" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="form-control day_range_price" name="slot_range_day_price' + date_ranges_length + '[]" value="' + parseFloat($('#product_price').val()).toFixed(2) + '">';
        html += '<span class="input-group-addon">' + defaultCurrencySign + '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="form-control day_range_qty" name="slot_range_day_qty' + date_ranges_length + '[]" value="' + $('#product_quantity').val() + '">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="slot_status_div">';
        html += '<input type="hidden" value="1" name="slot_active' + date_ranges_length + '[]" class="time_slot_status day_time_slot_status">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
        html += '</td>';
        html += '</tr>';

        $(this).closest('.time_slots_prices_table_div').find('.time_slots_prices_table').append(html);
    });

    $(document).on('click', '.add_more_time_slot_price', function() {
        var date_ranges_length = $(this).closest('.single_date_range_slots_container').attr('date_range_slot_num');
        html = '<tr>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control booking_time_from day_time_from" type="text" name="booking_time_from' + date_ranges_length + '[]" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input autocomplete="off" class="form-control booking_time_to day_time_to" type="text" name="booking_time_to' + date_ranges_length + '[]" readonly>';
        html += '<span class="input-group-addon">';
        html += '<i class="icon-clock-o"></i>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="form-control day_range_price" name="slot_range_price' + date_ranges_length + '[]" value="' + parseFloat($('#product_price').val()).toFixed(2) + '">';
        html += '<span class="input-group-addon">' + defaultCurrencySign + '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="input-group">';
        html += '<input type="text" class="form-control day_range_qty" name="slot_range_qty' + date_ranges_length + '[]" value="' + $('#product_quantity').val() + '">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<div class="slot_status_div">';
        html += '<input type="hidden" value="1" name="slot_active' + date_ranges_length + '[]" class="time_slot_status day_time_slot_status">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
        html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
        html += '</div>';
        html += '</td>';
        html += '<td class="center">';
        html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
        html += '</td>';
        html += '</tr>';

        $(this).closest('.time_slots_prices_table_div').find('.time_slots_prices_table').append(html);
    });

    //To remove a row created with add new time slots buttons
    $(document).on('click', '.remove_time_slot', function(e) {
        e.preventDefault();
        if ($(this).closest('.time_slots_prices_table').find('.remove_time_slot').length == 1) {
            if ($('#time_slot_type').val() == time_slot_type_date) {
                if ($(this).closest('.single_date_range_slots_container').attr('date_range_slot_num') != '0') {
                    $(this).closest('.single_date_range_slots_container').remove();
                } else {
                    $(this).closest('tr').remove();
                }
            } else {
                $(this).closest('tr').remove();
            }
        } else {
            $(this).closest('tr').remove();
        }
    });
    //date picker for date ranges
    $(document).on("focus", ".sloting_date_from, .sloting_date_to", function() {
        $(".sloting_date_from").datepicker({
            showOtherMonths: true,
            dateFormat: 'dd-mm-yy',
            minDate: 0,
            onSelect: function(selected) {
                var changeDate = $(this).attr('change_key');
                $("[change_date=sloting_date_to_"+changeDate+"]").datepicker("option","minDate", selected)
            }
        });
        $(".sloting_date_to").datepicker({
            showOtherMonths: true,
            dateFormat: 'dd-mm-yy',
            minDate: 0,
        });
    });

    //time picker for time slots
    $(document).on("focus", ".booking_time_from, .booking_time_to", function() {
        $(".booking_time_from, .booking_time_to").timepicker({
            pickDate: false,
            datepicker: false,
            format: 'H:i',
        });
    });

    // Stats calendar tab js starts from here
    $('#stats-calendar').datepicker({
        defaultDate: (typeof calendarDate != 'undefined') ? calendarDate : new Date(),
        dayNamesMin: [wk_sun, wk_mon, wk_tue, wk_wed, wk_thu, wk_fri, wk_sat],
        numberOfMonths: 2,
        dateFormat: 'dd-mm-yy',
        minDate: 0,
        beforeShowDay: function(date) {
            var currentMonth = date.getMonth() + 1;
            var currentDate = date.getDate();
            if (currentMonth < 10) {
                currentMonth = '0' + currentMonth;
            }
            if (currentDate < 10) {
                currentDate = '0' + currentDate;
            }
            dateToWork = date.getFullYear() + "-" + currentMonth + "-" + currentDate;
            var calendarCssClass = '';
            var flag = 0;

            if (typeof disabledDays != 'undefined' && disabledDays) {
                var currentDay = date.getDay();
                if ($.inArray(String(currentDay), disabledDays) != -1) {
                    calendarCssClass += 'calender-disabled-dates ';
                }
            }
            if (typeof disabledDates != 'undefined' && disabledDates) {
                if ($.inArray(dateToWork, disabledDates) !== -1) {
                    calendarCssClass += 'calender-disabled-dates ';
                }
            }

            $.each(bookingCalendarData, function(key, value) {
                if (key === dateToWork) {
                    if (typeof value.calendarCssClass != 'undefined') {
                        calendarCssClass += ' ' + value.calendarCssClass + ' ' + key + ' ' + 'ui-datepicker-unselectable';
                        flag = 1;
                    }
                    return 1;
                }
            });
            if (flag) {
                return [true, calendarCssClass];
            } else {
                return [true, 'ui-datepicker-unselectable'];
            }
        },
    });

    // add popover information and rates on the dates <td>
    if (typeof bookingCalendarData != 'undefined') {
        $.each(bookingCalendarData, function(key, dateInfo) {
            var dateBookingInfo = dateInfo.booking_info;
            if ((typeof dateInfo.booking_type != 'undefined') && dateInfo.booking_type == 1) {
                if (typeof dateBookingInfo.price.total_price_tax_incl_formatted != 'undefined') {
                    $('body td.' + key).append('</br><span class="ui-datepicker-day-price">' + dateBookingInfo.price.total_price_tax_incl_formatted + '</span>');
                    $('body td.' + key).addClass('date_ranges_info_td');
                    $('body td.' + key + ' .ui-state-default').attr('data-toggle', 'popover');
                    $('body td.' + key + ' .ui-state-default').attr('data-placement', 'auto');
                    $('body td.' + key + ' .ui-state-default').attr('data-html', true);
                    if (typeof dateBookingInfo.price != 'undefined') {
                        toolTipMsg = wk_total_available_qty + ': ' + dateBookingInfo.available_qty + '</br>' + wk_total_booked_qty + ': ' +dateBookingInfo.booked_qty + '</br>' + wk_price + ': ' + dateBookingInfo.price.total_price_tax_incl_formatted;
                    } else {
                        toolTipMsg = no_info_found_txt;
                    }
                    $('body td.' + key + ' .ui-state-default').attr('data-content', toolTipMsg);
                }
            } else if ((typeof dateInfo.booking_type != 'undefined') && dateInfo.booking_type == 2) {
                $('body td.' + key).addClass('time_slots_info_td');
                $('body td.' + key + ' .ui-state-default').attr('data-toggle', 'popover');
                $('body td.' + key + ' .ui-state-default').attr('data-placement', 'auto');
                $('body td.' + key + ' .ui-state-default').attr('data-html', true);
                var slotHtml = '';
                slotHtml += '<div class="table-responsive">';
                slotHtml += '<table class="table">';
                slotHtml += '<thead>';
                slotHtml += '<th>' + slot_text + '</th>';
                slotHtml += '<th>' + avl_qty_txt + '</th>';
                slotHtml += '<th>' + price_txt + '</th>';
                slotHtml += '<th>' + booked_qty_txt + '</th>';
                slotHtml += '<th>' + status_txt + '</th>';
                slotHtml += '</thead>';
                if (typeof dateBookingInfo != 'undefined') {
                    if (dateBookingInfo.length) {
                        slotHtml += '<tbody>';
                        $.each(dateBookingInfo, function(keySlot, slotInfo) {
                            slotHtml += '<tr>';
                            slotHtml += '<td>' + slotInfo.time_slot_from + ' ' + to_txt + ' ' + slotInfo.time_slot_to + '</td>';
                            slotHtml += '<td>' + slotInfo.available_qty + '</td>';
                            slotHtml += '<td>' + slotInfo.price_formatted + '</td>';
                            slotHtml += '<td>' + slotInfo.booked_qty + '</td>';
                            slotHtml += '<td>';
                            if (slotInfo.active == 1) {
                                slotHtml += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png">';
                            } else {
                                slotHtml += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png">';
                            }
                            slotHtml += '</td>';
                            slotHtml += '</tr>';
                        });
                        slotHtml += '</tbody>';
                    } else {
                        slotHtml += '<tr>';
                        slotHtml += '<td colspan="4">' + no_slots_avail_txt + '</td>';
                        slotHtml += '</tr>';
                    }
                }
                slotHtml += '</table>';
                slotHtml += '</div>';
                $('body td.' + key + ' .ui-state-default').attr('data-content', slotHtml);
            }
        });
    }
    // When page will be loaded changes on calendar td cell content
    $('#stats-calendar .booking_available .ui-state-default').append('&nbsp;<i class="icon-circle"></i>');
    $('#stats-calendar .booking_unavailable .ui-state-default').append('&nbsp;<i class="icon-circle"></i>');

    //If change the month in the booking information calendar
    $(document).on('click', '.calendar_change_month_link', function() {
        $('#stats-calendar .booking_available').append('&nbsp;<i class="icon-circle"></i>');
        $('#stats-calendar .booking_unavailable').append('&nbsp;<i class="icon-circle"></i>');
        if (typeof bookingCalendarData != 'undefined') {
            $.each(bookingCalendarData, function(key, dateInfo) {
                var dateBookingInfo = dateInfo.booking_info;
                if (typeof dateInfo.booking_type != 'undefined' && dateInfo.booking_type == 1) {
                    if (typeof dateBookingInfo.price.total_price_tax_incl_formatted != 'undefined') {
                        $('body td.' + key).append('</br><span class="ui-datepicker-day-price">' + dateBookingInfo.price.total_price_tax_incl_formatted + '</span>');
                        $('body td.' + key + ' .ui-state-default').attr('data-toggle', 'popover');
                        $('body td.' + key + ' .ui-state-default').attr('data-placement', 'auto');
                        $('body td.' + key + ' .ui-state-default').attr('data-html', true);
                        if (typeof dateBookingInfo.price != 'undefined') {
                            toolTipMsg = wk_total_available_qty + ': ' + dateBookingInfo.available_qty + '</br>' + wk_total_booked_qty + ': ' + dateBookingInfo.booked_qty + '</br>'+ wk_price + ': ' + dateBookingInfo.price.total_price_tax_incl_formatted;
                        } else {
                            toolTipMsg = no_info_found_txt;
                        }
                        $('body td.' + key + ' .ui-state-default').attr('data-content', toolTipMsg);
                    }
                } else if ((typeof dateInfo.booking_type != 'undefined') && dateInfo.booking_type == 2) {
                    $('body td.' + key).addClass('time_slots_info_td');
                    $('body td.' + key + ' .ui-state-default').attr('data-toggle', 'popover');
                    $('body td.' + key + ' .ui-state-default').attr('data-placement', 'auto');
                    $('body td.' + key + ' .ui-state-default').attr('data-html', true);
                    if (typeof dateBookingInfo != 'undefined') {
                        if (dateBookingInfo.length) {
                            var slotHtml = '';
                            slotHtml += '<div class="table-responsive">';
                            slotHtml += '<table class="table">';
                            slotHtml += '<thead>';
                            slotHtml += '<th>' + slot_text + '</th>';
                            slotHtml += '<th>' + avl_qty_txt + '</th>';
                            slotHtml += '<th>' + price_txt + '</th>';
                            slotHtml += '<th>' + booked_qty_txt + '</th>';
                            slotHtml += '<th>' + status_txt + '</th>';
                            slotHtml += '</thead>';
                            slotHtml += '<tbody>';
                            $.each(dateBookingInfo, function(keySlot, slotInfo) {
                                slotHtml += '<tr>';
                                slotHtml += '<td>' + slotInfo.time_slot_from + ' ' + to_txt + ' ' + slotInfo.time_slot_to + '</td>';
                                slotHtml += '<td>' + slotInfo.available_qty + '</td>';
                                slotHtml += '<td>' + slotInfo.price_formatted + '</td>';
                                slotHtml += '<td>' + slotInfo.booked_qty + '</td>';
                                slotHtml += '<td>';
                                if (slotInfo.active == 1) {
                                    slotHtml += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png">';
                                } else {
                                    slotHtml += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png">';
                                }
                                slotHtml += '</td>';
                                slotHtml += '</tr>';
                            });
                            slotHtml += '</tbody>';
                            slotHtml += '</table>';
                            slotHtml += '</div>';
                        } else {
                            slotHtml = no_slots_avail_txt;
                        }
                        $('body td.' + key + ' .ui-state-default').attr('data-content', slotHtml);
                    }
                }
            });
        }
        // To add our class on changing links of months to seperate from other datepickers
        $('[data-toggle="popover"]').popover();
        $('#stats-calendar-info .ui-datepicker-next, #stats-calendar-info .ui-datepicker-prev').addClass('calendar_change_month_link');
        $('body, .ui-state-default').on('click', function(e) {
            $('.popover').remove();
            $(this).popover('show');
        });
    });

    // To remove the other popovers before opening the new popover
    $('body, .ui-state-default').on('click', function() {
        $('.popover').remove();
    });

    // datepicker on search date from
    $("#search_date_from").datepicker({
        showOtherMonths: true,
        dateFormat: 'dd-mm-yy',
        minDate: 0,
        beforeShowDay: function(date) {
            return highlightDateBorder($("#search_date_from").val(), date);
        },
        onSelect: function(selectedDate) {
            var date_format = selectedDate.split("-");
            var selectedDate = new Date($.datepicker.formatDate('yy-mm-dd', new Date(date_format[2], date_format[1] - 1, date_format[0])));
            selectedDate.setDate(selectedDate.getDate());
            $("#search_date_to").datepicker("option", "minDate", selectedDate);
        },
    });

    // datepicker on search date to
    $("#search_date_to").datepicker({
        showOtherMonths: true,
        dateFormat: 'dd-mm-yy',
        minDate: 0,
        beforeShowDay: function(date) {
            return highlightDateBorder($("#search_date_to").val(), date);
        }
    });

    $('#availability-search-submit').on('click', function(e) {
        var dateFrom = $("#search_date_from").val();
        var dateTo = $("#search_date_to").val();
        var dateFromSplit = dateFrom.split("-");
        var dateFromFormatted = new Date($.datepicker.formatDate('yy-mm-dd', new Date(dateFromSplit[2], dateFromSplit[1] - 1, dateFromSplit[0])));
        var dateToSplit = dateTo.split("-");
        var checkOutFormatted = new Date($.datepicker.formatDate('yy-mm-dd', new Date(dateToSplit[2], dateToSplit[1] - 1, dateToSplit[0])));
        var error = false;
        $("#search_date_from").removeClass("error_border");
        $("#search_date_to").removeClass("error_border");
        $('#date_erros').text('');
        if (dateFrom == '') {
            $("#search_date_from").addClass("error_border");
            error = true;
        } else if (dateFromFormatted < $.datepicker.formatDate('yy-mm-dd', new Date())) {
            $("#search_date_from").addClass("error_border");
            $('#date_erros').text(date_from_less_current_date_err);
            error = true;
        }
        if (dateTo == '') {
            $("#search_date_to").addClass("error_border");
            error = true;
        } else if (checkOutFormatted < dateFromFormatted) {
            $("#search_date_to").addClass("error_border");
            $('#date_erros').text(date_to_more_date_from_err);
            error = true;
        }
        if (error) {
            return false;
        } else {
            return true;
        }
    });

    // Disable dates tab js starts from here

    $('.is_disabled_week_days_exists').on('change', function() {
        if ($(this).val() == 1) {
            $('.disabled_week_days').show();
        } else if ($(this).val() == 0) {
            $('.disabled_week_days').hide();
        }
    });

    $('.is_disabled_specific_dates_exists').on('change', function() {
        if ($(this).val() == 1) {
            $('.disabled_specific_dates').show();
        } else if ($(this).val() == 0) {
            $('.disabled_specific_dates').hide();
        }
    });

    // add the disabled date ranges in the disable dates json
    $("button[name='submitDateRange']").on('click', function(e) {
        e.preventDefault();
        var id_product = $('#id_ps_product').val();
        var $dateFrom = $('#date-start').val();
        var $dateTo = $('#date-end').val();
        var disabledDates = $('#disabled_specific_dates_json').val();
        error = false;
        if ($dateFrom == '') {
            showErrorMessage(date_from_req);
            error = true;
        }
        if ($dateTo == '') {
            showErrorMessage(date_to_req);
            error = true;
        }

        if (error) {
            return false;
        }
        if (disabledDates == '') {
            disabledDates = {};
        } else {
            disabledDates = JSON.parse(disabledDates);
        }
        // If booking id slot tyle bookings
        if (booking_type == 2) {
            if ($dateFrom.trim() && $dateTo.trim()) {
                if (disabledDates[$dateFrom + '_' + $dateTo] === undefined) {
                    $.ajax({
                        url: admin_booking_product_url,
                        data: {
                            id_product: id_product,
                            date_from: $dateFrom,
                            date_to: $dateTo,
                            action: 'getDateRangeAvailableBookingSlots',
                            ajax: true,
                        },
                        method: 'POST',
                        dataType: 'JSON',
                        success: function(result) {
                            $('.booking-disable-slots-content').empty();
                            if (result.status == 'failed') {
                                $.each(result.errors, function(key, error) {
                                    showErrorMessage(error);
                                });
                            } else if (result.status == 'success') {
                                if (result.slots == 'no_slot') {
                                    $('.disableSlotsModalSubmit').hide();
                                    $('.booking-disable-slots-content').append('<div class="alert alert-danger">' + no_slots_avail_txt + '</div>');
                                } else if (result.slots == 'all') {
                                    $('.booking-disable-slots').hide();
                                    $('.booking-disable-slots-content').attr('date_from', $dateFrom);
                                    $('.booking-disable-slots-content').attr('date_to', $dateTo);
                                    $('.booking-disable-slots-content').attr('all_slots', 1);
                                    $('.booking-disable-slots-content').append('<div class="alert alert-warning">' + all_slots_disable_warning + '</div>');
                                } else {
                                    $('.booking-disable-slots-content').attr('date_from', $dateFrom);
                                    $('.booking-disable-slots-content').attr('date_to', $dateTo);
                                    $('.booking-disable-slots-content').attr('all_slots', 0);
                                    var html = '<div class="from-group table-responsive-row clearfix">';
                                    html += '<table class="table booking-disable-slots">';
                                    html += '<tbody>';
                                    $.each(result.slots, function(key, slot) {
                                        html += '<tr>';
                                        html += '<td>' + slot.time_slot_from + ' &nbsp;' + to_txt + ' &nbsp;' + slot.time_slot_to + '</td>';
                                        html += '<td><input time_from="' + slot.time_slot_from + '" time_to="' + slot.time_slot_to + '" id_slot="' + slot.id + '" type="checkbox" class="selected_disable_slots"></td>';
                                        html += '</tr>';
                                    });
                                    html += '</tbody>';
                                    html += '</table>';
                                    html += '</div>';
                                    $('.booking-disable-slots-content').append(html);
                                }
                                $('#disableTimeSlotsModal').modal('show');
                            }
                        }
                    });
                } else {
                    showErrorMessage(date_range_already_added);
                }
            }
        } else { // If booking id date range type bookings
            if ($dateFrom.trim() && $dateTo.trim()) {
                if (disabledDates[$dateFrom + '_' + $dateTo] === undefined) {
                    var dateRangeObj = {
                        'date_from': $dateFrom,
                        'date_to': $dateTo,
                    };
                    disabledDates[$dateFrom + '_' + $dateTo] = dateRangeObj;
                    if (disabledDates) {
                        $('#disabled_specific_dates_json').val(JSON.stringify(disabledDates));
                        var html = '<div class="col-sm-3">';
                        html += '<div class="disabled_date_container">';
                        html += '<span>' + $dateFrom + '&nbsp; To &nbsp;' + $dateTo + '</span><span class="remove_disable_date" remove-date-index="' + ($dateFrom + '_' + $dateTo) + '"><i class="icon-times-circle"></i></span>';
                        html += '</div>';
                        html += '</div>';
                        $('.selected_disabled_dates').append(html);
                    }
                } else {
                    showErrorMessage(date_range_already_added);
                }
            }
            return false;
        }
    });

    $(document).on('click', '.edit_disable_date_slots', function(e) {
        e.preventDefault();
        var id_product = $('#id_ps_product').val();
        var $dateFrom = $(this).attr('date_start');
        var $dateTo = $(this).attr('date_end');
        error = false;
        if ($dateFrom == '') {
            showErrorMessage(date_from_req);
            error = true;
        }
        if ($dateTo == '') {
            showErrorMessage(date_to_req);
            error = true;
        }

        if (error) {
            return false;
        } else {
            if ($dateFrom.trim() && $dateTo.trim()) {
                $.ajax({
                    url: admin_booking_product_url,
                    data: {
                        id_product: id_product,
                        date_from: $dateFrom,
                        date_to: $dateTo,
                        action: 'getDateRangeAvailableBookingSlots',
                        ajax: true,
                    },
                    method: 'POST',
                    dataType: 'JSON',
                    success: function(result) {
                        $('.booking-disable-slots-content').empty();
                        if (result.status == 'failed') {
                            $.each(result.errors, function(key, error) {
                                showErrorMessage(error);
                            });
                        } else if (result.status == 'success') {
                            if (result.slots == 'all') {
                                $('.booking-disable-slots').hide();
                                $('.booking-disable-slots-content').attr('date_from', $dateFrom);
                                $('.booking-disable-slots-content').attr('date_to', $dateTo);
                                $('.booking-disable-slots-content').attr('all_slots', 1);
                                $('.booking-disable-slots-content').append('<div class="alert alert-warning">' + all_slots_disable_warning + '</div>');
                                $('#disableTimeSlotsModal').modal('show');
                            } else {
                                var disabledDates = $('#disabled_specific_dates_json').val();
                                if (disabledDates == '') {
                                    disabledDates = {};
                                } else {
                                    disabledDates = JSON.parse(disabledDates);
                                }
                                $('.booking-disable-slots-content').attr('date_from', $dateFrom);
                                $('.booking-disable-slots-content').attr('date_to', $dateTo);
                                $('.booking-disable-slots-content').attr('all_slots', 0);
                                var html = '<div class="from-group table-responsive-row clearfix">';
                                html += '<table class="table booking-disable-slots">';
                                html += '<tbody>';
                                $.each(result.slots, function(key_ajax_slots, ajax_slot) {
                                    html += '<tr>';
                                    html += '<td>' + ajax_slot.time_slot_from + ' &nbsp;' + to_txt + ' &nbsp;' + ajax_slot.time_slot_to + '</td>';
                                    html += '<td><input time_from="' + ajax_slot.time_slot_from + '" time_to="' + ajax_slot.time_slot_to + '" id_slot="' + ajax_slot.id + '" type="checkbox" class="selected_disable_slots"';
                                    $.each(disabledDates, function(key_disables_dates, disableRange) {
                                        if (key_disables_dates == $dateFrom + '_' + $dateTo) {
                                            $.each(disableRange.slots_info, function(key_slot_info, slot_info) {
                                                if (slot_info.time_from == ajax_slot.time_slot_from && slot_info.time_to == ajax_slot.time_slot_to) {
                                                    html += ' checked="checked"';
                                                }
                                            });
                                        }
                                    });
                                    html += '></td>';
                                    html += '</tr>';
                                });
                                html += '</tbody>';
                                html += '</table>';
                                html += '</div>';
                                $('.booking-disable-slots-content').append(html);
                                $('#disableTimeSlotsModal').modal('show');
                            }
                        }
                    }
                });
            }
        }
    });

    // Disable dates data save when model open
    $(document).on('click', '.disableSlotsModalSubmit', function() {
        var dateFrom = $('.booking-disable-slots-content').attr('date_from');
        var dateTo = $('.booking-disable-slots-content').attr('date_to');
        var allSlots = $('.booking-disable-slots-content').attr('all_slots');
        error = false;
        if (typeof dateFrom == 'undefined' || dateFrom == '') {
            showErrorMessage(date_from_req);
            error = true;
        }
        if (typeof dateTo == 'undefined' || dateTo == '') {
            showErrorMessage(date_to_req);
            error = true;
        }
        if (allSlots == 0) {
            if ($('.selected_disable_slots:checked').length == 0) {
                showErrorMessage(no_slot_selected_err);
                error = true;
            }
        }
        if (error) {
            return false;
        }
        var slotInfo = new Array();
        $('.selected_disable_slots:checked').each(function(key, slot) {
            slotInfo.push({
                'time_from': $(this).attr('time_from'),
                'time_to': $(this).attr('time_to')
            });
        });

        var disabledDates = $('#disabled_specific_dates_json').val();
        if (disabledDates == '') {
            disabledDates = {};
        } else {
            disabledDates = JSON.parse(disabledDates);
        }
        if (dateFrom.trim() && dateTo.trim()) {
            var dateRangeSlotsObj = {
                'date_from': dateFrom,
                'date_to': dateTo,
                'slots_info': slotInfo,
            };
            if (disabledDates[dateFrom + '_' + dateTo] === undefined) {
                disabledDates[dateFrom + '_' + dateTo] = dateRangeSlotsObj;
                if (disabledDates) {
                    $('#disabled_specific_dates_json').val(JSON.stringify(disabledDates));
                    var html = '<div class="col-sm-3">';
                    html += '<div class="disabled_date_container">';
                    html += '<span>' + dateFrom + '&nbsp; To &nbsp;' + dateTo + '</span><span class="remove_disable_date" remove-date-index="' + (dateFrom + '_' + dateTo) + '"><i class="icon-times-circle"></i></span><span date_end="' + dateTo + '" date_start="' + dateFrom + '" class="edit_disable_date_slots"><i class="icon-pencil"></i></span>';
                    html += '</div>';
                    html += '</div>';
                    $('.selected_disabled_dates').append(html);
                }
            } else {
                disabledDates[dateFrom + '_' + dateTo] = dateRangeSlotsObj;
                $('#disabled_specific_dates_json').val(JSON.stringify(disabledDates));
            }
        }
        $('#disableTimeSlotsModal').modal('hide');
    });

    //delete the dateranges from the disables date ranges json
    $(document).on('click', '.remove_disable_date', function() {
        var indexToRemove = $(this).attr('remove-date-index');
        var disableDatesArray = JSON.parse($('#disabled_specific_dates_json').val());
        delete disableDatesArray[indexToRemove];
        $('#disabled_specific_dates_json').val(JSON.stringify(disableDatesArray));
        $(this).closest('.disabled_date_container').parent('div').remove();
    });

    // To make calendar dates disabled
    $('#date-start').attr('readonly', true);
    $('#date-end').attr('readonly', true);

    $('#time_slot_type').change(function() {
        if ($(this).val() == time_slot_type_date)
        {
            $('#time_slot_type_date').show();
            $('#time_slot_type_day').hide();
        } else {
            $('#time_slot_type_date').hide();
            $('#time_slot_type_day').show();
        }
        // $(this).val() will work here
    });

    $(document).on('click','.add_day_wise_slot', function() {
        var days = [];
        let add_check = 1;
        $.each($("input[name='days']:checked"), function(){
            days.push($(this).val());
        });
        $('input[type="text"].day_time_from').each(function () {
            if ($(this).val() == '') {
                $.growl.error({title: '', message: please_select_time_from_txt});
                add_check = 0;
            }
        });
        $('input[type="text"].day_time_to').each(function () {
            if ($(this).val() == '') {
                $.growl.error({title: '', message: please_select_time_to_txt});
                add_check = 0;
            }
        });
        $('input[type="text"].day_range_price').each(function () {
            if ($(this).val() == '') {
                $.growl.error({title: '', message: please_select_time_to_txt});
                add_check = 0;
            }
        });
        if (days.length == 0) {
            $.growl.error({title: '', message: please_select_days_txt});
            add_check = 0;
        }
        if (add_check) {
            var daysWithTime = [];
            days.forEach(element => {
                if (typeof daysWithTime[element] == 'undefined'){
                    daysWithTime[element] = [];
                }
                if (typeof daysWithTime[element]['from'] == 'undefined'){
                    daysWithTime[element]['from'] = [];
                }
                if (typeof daysWithTime[element]['to'] == 'undefined'){
                    daysWithTime[element]['to'] = [];
                }
                if (typeof daysWithTime[element]['price'] == 'undefined'){
                    daysWithTime[element]['price'] = [];
                }
                if (typeof daysWithTime[element]['qty'] == 'undefined'){
                    daysWithTime[element]['qty'] = [];
                }
                if (typeof daysWithTime[element]['status'] == 'undefined'){
                    daysWithTime[element]['status'] = [];
                }

                $('input[type="text"].day_time_from').each(function () {

                    daysWithTime[element]['from'].push($(this).val());
                });
                $('input[type="text"].day_time_to').each(function () {

                    daysWithTime[element]['to'].push($(this).val());
                });
                $('input[type="text"].day_range_price').each(function () {

                    daysWithTime[element]['price'].push($(this).val());
                });
                $('input[type="text"].day_range_qty').each(function () {

                    daysWithTime[element]['qty'].push($(this).val());
                });
                $('input[type="hidden"].day_time_slot_status').each(function () {
                    daysWithTime[element]['status'].push($(this).val());
                });
            });
            $.each(daysWithTime, function( index, value ) {
                switch(index) {
                    case 1:
                        if (daysWithTime[1] != undefined) {
                            addMondaySlot(daysWithTime[1]);
                        }
                        break;
                    case 2:
                        if (daysWithTime[2] != undefined) {
                            addTuesdaySlot(daysWithTime[2]);
                        }
                        break;
                    case 3:
                        if (daysWithTime[3] != undefined) {
                            addWednesdaySlot(daysWithTime[3]);
                        }
                        break;
                    case 4:
                        if (daysWithTime[4] != undefined) {
                            addThursdaySlot(daysWithTime[4]);
                        }
                        break;
                    case 5:
                        if (daysWithTime[5] != undefined) {
                            addFridaySlot(daysWithTime[5]);
                        }
                        break;
                    case 6:
                        if (daysWithTime[6] != undefined) {
                            addSaturdaySlot(daysWithTime[6]);
                        }
                        break;
                    case 7:
                        if (daysWithTime[7] != undefined) {
                            addSundaySlot(daysWithTime[7]);
                        }
                        break;
                    default:
                    // code block
                }
            });

        }
    });

    $(document).on("click", '.slot_active_img', function(e) {
        $(this).hide();
        $(this).closest('.slot_status_div').find('.slot_deactive_img').show();
        $(this).closest('.slot_status_div').find('.time_slot_status').val(0);
    });

    $(document).on("click", '.slot_deactive_img', function(e) {
        $(this).hide();
        $(this).closest('.slot_status_div').find('.slot_active_img').show();
        $(this).closest('.slot_status_div').find('.time_slot_status').val(1);
    });

    if (typeof apiKey != 'undefined' && document.getElementById('map-canvas') != null) {
        //load google map
        initialize();
    }

});

// map initialization on load booking creation page
function initialize() {
    if ($('#latitude').val() != '' && $('#latitude').val() != '0.00000000') {
        lat = $('#latitude').val();
        lng = $('#longitude').val();
        map_address = $('#address').val();
    }
    if ($('#latitude').val() != '' && $('#latitude').val() != '0.00000000') { //if edit booking
        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 17
        };
    } else {
        var mapOptions = {
            center: new google.maps.LatLng(-33.8688, 151.2195),
            zoom: 13
        };
    }

    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var input = (document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();


    if ($('#latitude').val() != '' && $('#latitude').val() != '0.00000000') { //if edit booking
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            position: mapOptions.center
        });
        infowindow.setContent(map_address);
        infowindow.open(map, marker);
    } else {
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });
    }

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();

        if (!place.geometry) {
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
        }
        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        //get lat, lng and address from map
        $("#latitude").val(place.geometry.location.lat());
        $("#longitude").val(place.geometry.location.lng());
        $("#address").val($("#pac-input").val());

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
    });
}


function addMondaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from1[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to1[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day1[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day1[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day1[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';

        $('.monday_tbody').append(html);
    }
}

function addTuesdaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from2[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to2[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day2[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day2[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day2[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.tuesday_tbody').append(html);
    }
}

function addWednesdaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from3[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to3[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day3[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day3[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day3[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.wednesday_tbody').append(html);
    }
}

function addThursdaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from4[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to4[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day4[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day4[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day4[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.thursday_tbody').append(html);
    }
}

function addFridaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from5[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to5[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day5[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day5[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day5[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.friday_tbody').append(html);
    }
}


function addSaturdaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from6[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to6[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day6[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day6[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day6[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.saturday_tbody').append(html);
    }
}

function addSundaySlot(row) {
    for (i=0; i<row.from.length; i++) {
        var html = '<tr>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_from7[]" value="'+ row.from[i] +'">';
                    html += '<span>'+ row.from[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="booking_time_day_to7[]" value="'+ row.to[i] +'">';
                    html += '<span>'+ row.to[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_price_day7[]" value="'+ row.price[i] +'">';
                    html += '<span>'+ Number(row.price[i]) +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<input type="hidden" name="slot_range_qty_day7[]" value="'+ row.qty[i] +'">';
                    html += '<span>'+ row.qty[i] +'</span>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<div class="slot_status_div">';
                        html += '<input type="hidden" value="'+ row.status[i] +'" name="slot_active_day7[]" class="time_slot_status">';
                        if (row.status[i] == 1) {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" style="display:none;" class="slot_deactive_img">';
                        } else {
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-check.png" style="display:none;" class="slot_active_img">';
                            html += '<img src="' + module_dir + 'psbooking/views/img/icon/icon-close.png" class="slot_deactive_img">';
                        }
                    html += '</div>';
                html += '</td>';
                html += '<td class="center">';
                    html += '<a href="#" class="remove_time_slot btn btn-default"><i class="icon-trash"></i></a>';
                html += '</td>';
            html += '</tr>';
        $('.sunday_tbody').append(html);
    }
}

function changeTabStatus(active_tab) {
    //Remove all tabs from active (make normal)
    $('.wk-tabs-panel .nav-tabs li').removeClass('active');
    $('.wk-tabs-panel .tab-content .tab-pane').removeClass('active');
    //Add active class in selected tab
    $('[href*="#' + active_tab + '"]').parent('li').addClass('active');
    $('#' + active_tab).addClass('active in');
}

// To hignlight the dates
function highlightDateBorder(elementVal, date) {
    if (elementVal) {
        var currentDate = date.getDate();
        var currentMonth = date.getMonth() + 1;
        if (currentMonth < 10) {
            currentMonth = '0' + currentMonth;
        }
        if (currentDate < 10) {
            currentDate = '0' + currentDate;
        }
        dmy = date.getFullYear() + "-" + currentMonth + "-" + currentDate;
        var date_format = elementVal.split("-");
        var check_in_time = (date_format[2]) + '-' + (date_format[1]) + '-' + (date_format[0]);
        if (dmy == check_in_time) {
            return [true, "selectedCheckedDate", "Check-In date"];
        } else {
            return [true, ""];
        }
    } else {
        return [true, ""];
    }
}

function showProdLangField(select_lang_name, id_lang)
{
    $('.wk_text_field_all').hide();
    $('.wk_text_field_' + id_lang).show();


    $('.all_lang_icon').attr('src', img_dir_l+id_lang+'.jpg');
    $('#choosedLangId').val(id_lang);
    $('#seller_lang_btn').val(id_lang);
    $('#seller_lang_btn_text').text(select_lang_name);
    $('#id_selected_lang').val(id_lang)
}

// To initialize the popover
$(function() {
    $('[data-toggle="popover"]').popover();
    // To add our class on changing links of months to seperate from other datepickers
    $('#stats-calendar-info .ui-datepicker-next, #stats-calendar-info .ui-datepicker-prev').addClass('calendar_change_month_link');
});