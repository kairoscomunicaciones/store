/**
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
*  @author    FME Modules
*  @copyright 2022 fmemodules.com All right reserved
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @category  FMM Modules
*  @package   Customfields
*/
var required_fields = ".input_wrapper input:checkbox, .input_wrapper input:radio, .input_wrapper select, div.selector";
var to_find = 'input[type="checkbox"]:checked, input[type="radio"]:checked, select option:selected';
$(document).ready(function() {
    reCrawlOpts();
});

function checkDependency(object) {
  var nbr_checkedboxes = object.closest(".input_wrapper").find(to_find).length;
  object.closest(".input_wrapper").find(".checkboxes").val(nbr_checkedboxes);
  dependanceLookup(object, true);
}

function reCrawlOpts() {
  $(required_fields).each(function(e) {
      dependanceLookup($(this));
    });
  return false;
}
//----------------
var steps = [];
var slist = [];
var links = [];
var ajax_result = [];
var watchElem = $("#onepagecheckoutps_contenedor").find('.loading_small');
var required_fields = ".type_multiboxes, .input_wrapper select";
var to_find = 'input[type="checkbox"]:checked, input[type="radio"]:checked, select option:selected';
/* For Zelgra one step checkout */
var submitButton = null;
var buttonFunction = null;
$(document).on("change", ".input_wrapper select", function(e) {
    checkDependency($(this));
});

$(document).on("click", ".input_wrapper input:checkbox, .input_wrapper input:radio", function(e) {
    checkDependency($(this));
});
function orderStep(step) {
    steps.push(step);
}

function addCarrier(fieldsHtml, is_module, mod) {
    if (typeof is_module !== 'undefined' && is_module) {
        if (typeof mod !== 'undefined' && mod) {
            if (mod == 'zelgra') {
                var chDiv = $('<div id="fmm_checkoutfields" style="display:inline-block"></div>');
                chDiv.append(fieldsHtml);
                $('#extra_carrier').closest('form').wrapAll('<div id="customfields_carrier_opc"/>');
                $('#customfields_carrier_opc').append(chDiv);
            } else if (mod == 'pts')  {
                var chDiv = $('<div id="fmm_checkoutfields"></div>');
                chDiv.append(fieldsHtml);
                setTimeout(function() {
                    $('#carrier_area').parent().after('<div id="customfields_carrier_opc"/>');
                    $('#customfields_carrier_opc').append(chDiv).show();
                }, 500)
            }
        }
    } else {
        var chDiv = $('<div id="checkoutfields"></div>');
        chDiv.append(fieldsHtml);
        $('#extra_carrier').append(chDiv).show();
    }
}

function addAddress(fieldsHtml, is_module, mod) {
    var chDiv = $('<div id="customfields"></div>');
    chDiv.append(fieldsHtml);
    if (typeof is_module !== 'undefined' && is_module) {
        chDiv.find('div').css({'float': 'none', 'width' : 'auto;'});
        if (typeof mod !== 'undefined' && mod) {
            if (mod == 'zelgra') {
                $('#dlv_addresses_div').closest('form').after(chDiv);
            } else if (mod == 'pts')  {
                $('#panel_addresses_customer').after(chDiv);
            }
        }
    } else {    
        $('.address_add').prepend(chDiv);
        $('#carrier_area').before(chDiv);
    }
}

function addPayment(fieldsHtml, is_module, mod) {
    if (typeof is_module !== 'undefined' && is_module) {
        if (mod == 'zelgra') {

            var chDiv = $('<div id="checkoutfields" style="display:inline-block"></div>');
            chDiv.append(fieldsHtml);
            $('#HOOK_TOP_PAYMENT').closest('form').wrapAll('<div id="customfields_payment_opc"/>');
            $('#customfields_payment_opc').append(chDiv);
        } else if (mod == 'pts')  {

            var chDiv = $('<div id="fmm_checkoutfields"></div>');
            chDiv.append(fieldsHtml);
            setTimeout(function() {
                $('#payment_method_container').parent().after('<div id="customfields_carrier_opc_payment"/>');
                $('#customfields_carrier_opc_payment').append(chDiv).show();
            }, 1000)
        }
    }
}
function setDatePicker() {
    $(".fields_datapicker").datepicker({dateFormat: 'yy-mm-dd', prevText: '',nextText: ''});
}

function sendForm(step) {
    var redirect = true;
    var formData = new FormData($('#' + step + '_form')[0]);
    var form_url = $('#' + step + '_form').attr('action');
    if (typeof form_url == 'undefined' || form_url == '') {
        form_url = ajax_controller;
    }
    var ajaxData = {
        url: form_url,
        data: formData,
        method: 'post',
        dataType: 'json',
        async: false,
        contentType: false,
        processData: false,
        success: function(response) {
            var content = $('#customfieldsblock_' + step);
            content.find('.error').remove();
            
            ajax_result.push(response.result);
            if (response.result === false) {
                redirect = false;
                if (response.errors) {
                    content.prepend('<div class="error alert alert-danger" id="' + response.type + '_error"></div>');
                    var ul = $('<ol>');
                    for(var i=0; i < response.errors.length; i++) {
                        ul.append($('<li>' + response.errors[i] + '</li>'));
                    }
                    $('#' + step + '_error').append(ul);
                    appendErrorConsoleItems(response.errors);
                }
            }
            if (slist.length) {
                sendForm(slist.pop());
            }
        }
    };

    var xhr = $.ajax(ajaxData);
}

function trigger(links) {
    if ( this.links.is('a'))
    {
        if(this.links.children().is('form'))
        {
            this.links.children().submit();
        }
        else
        {
            location.href = this.links.attr('href');
        }
    }
    else if( this.links.is('img'))
    {
        location.href = this.links.parent().attr('href');
    }
    else if(this.links.parent().is('form'))
    {
        this.links.parent().submit();
    }
    else if(this.links.children(":first").is('a'))
    {
        location.href = this.links.children(":first").attr('href');
    }
    else if(this.links.parent().is('a'))
    {
        location.href = this.links.parent().attr('href');
    }
    else if(this.links.parent().parent().is('a'))
    {
        location.href = this.links.parent().parent().attr('href');
    }
    else if(this.links.parent().parent().parent().is('a'))
    {
        location.href = this.links.parent().parent().parent().attr('href');
    }
    else
    {
        this.links.unbind('click');
        this.links.trigger('click');
    }
}

function saveFieldData() {
    $('.standard-checkout, .cart_navigation > button[type="submit"], .cart_navigation > input[type="submit"]').click(function(event) {
        var is_checked = checkRequiredBoxes();
        if (is_checked.length && $.inArray(false, is_checked) >= 0) {
            event.preventDefault();
            return false;
        } else {
            links = $(this);
            if (!steps.length) {
                trigger();
            }

            slist = [].concat(steps);
            sendForm(slist.pop());
            if ($.inArray(false, ajax_result) >= 0) {
                ajax_result = [];
                event.preventDefault();
                return false;
            }
            return true;
        }
    });

    $('#HOOK_PAYMENT').delegate('*', 'click', function(event) {
        links = $(this);
        var is_checked = checkRequiredBoxes();
        if (is_checked.length && $.inArray(false, is_checked) >= 0) {
            event.preventDefault();
            return false;
        } else {
            if (!steps.length) {
                trigger();
            }
            slist = [].concat(steps);
            sendForm(slist.pop());
            if ($.inArray(false, ajax_result) >= 0) {
                ajax_result = [];
                event.preventDefault();
                return false;
            }
            return true;
        }
    });
}

function dependanceLookup(_el, ev = false) {
  if (_el.is("input:radio") || _el.is("input:checkbox") || _el.is("select")) {
    var __cf_id = _el.closest('.input_wrapper').attr("data-id");
    var __f_id = _el.attr("data-field");
    var __f_type = _el.attr("data-type");
    var _req_input = $(".rf_only_f_" + __f_id).find("input.is_required");
    var __v_id = _el.val();
    var _checkedBoxes = [];

    if (__f_type == 'boolean') {
      __v_id = getBooleanVal(_el.val());
    } else if (__f_type == 'select') {
      __v_id = $(_el).find(":selected").val();
    } else if ($.inArray(__f_type, ['radio', 'checkbox']) >= 0) {
      __v_id = $("input[name='" + _el.attr('name') + "']:checked").val();
      if (__f_type == 'checkbox') {
        $.each($("input[name='" + _el.attr('name') + "']:checked"), function(){
          _checkedBoxes.push($(this).val());
        });
      }
    };

    var __elem_target = $(".rf_no_display_" + __f_id + "_" + __v_id);

    if (_el.is(":checked") || (_el.has("option:selected") && _el.is("select"))) {
      if (_el.is("select") && __elem_target.length > 0) {
        $(".rf_only_f_" + __f_id).hide();
        //_req_input.attr("checked", false);
        var __rf_children_target = parseInt(_req_input.attr("data-field"));
        if (__rf_children_target > 0) {
          $(".rf_only_f_" + __rf_children_target).hide();
        }
      } else if (_el.is("input:radio") && __elem_target.length > 0) {
        $(".rf_only_f_" + __f_id).hide();
        //_req_input.attr("checked", false);
        var __rf_children_target = parseInt(_req_input.attr("data-field"));
        if (__rf_children_target > 0) {
          $(".rf_only_f_" + __rf_children_target).hide();
        }
      }
      __elem_target.show();
      __elem_target.find("input.rf_checkboxes").val(0);
      if (_el.is("input:radio") && __elem_target.length < 1) {
        $(".rf_only_f_" + __f_id).hide();
        //_req_input.attr("checked", false);
        var __rf_children_target = parseInt(_req_input.attr("data-field"));
        if (__rf_children_target > 0) {
          $(".rf_only_f_" + __rf_children_target).hide();
        }
      } else if (_el.is("select") && __elem_target.length < 1) {
        $(".rf_only_f_" + __f_id).hide();
        $(".rf_only_f_" + __f_id).find("select").val(0);
      }

      // handle dependent checkboxes
      if (__f_type == 'checkbox') {
        var _dependent_checkbox = $('[data-f="' + __cf_id + '"]');
        var _dependent_val = _dependent_checkbox.closest('.input_wrapper').attr('data-v');
        if (typeof __v_id == 'undefined' || (_checkedBoxes.length && $.inArray(_dependent_val, _checkedBoxes) === -1)) {
          _dependent_checkbox.hide();
        } else {
          _dependent_checkbox.show();
        }
      }
    } else {
      __elem_target.hide();

      //_req_input.attr("checked", false);
      __elem_target.find("input.rf_checkboxes").val(1);
      var ___rf_children_target = parseInt(_req_input.attr("data-field"));
      if (___rf_children_target > 0) {
        $(".rf_only_f_" + ___rf_children_target).hide();
      }

      // handle dependent checkboxes
      if (__f_type == 'checkbox') {
        var _dependent_checkbox = $('[data-f="' + __cf_id + '"]');
        var _dependent_val = _dependent_checkbox.closest('.input_wrapper').attr('data-v');
        if (typeof __v_id == 'undefined' || (_checkedBoxes.length && $.inArray(_dependent_val, _checkedBoxes) === -1)) {
          _dependent_checkbox.hide();
        } else {
          _dependent_checkbox.show();
        }
      }

    }
  }
}

function getBooleanVal(val) {
    return ((val == 'Yes')? 1 : 0);
}

function runScript() {
    setDatePicker();
    saveFieldData();
}

$(document).on('click', 'button[name=saveCustomFields]', function(event) {
    var result = [];
    var is_checked = checkRequiredBoxes();
    if (is_checked.length && $.inArray(false, is_checked) >= 0) {
        event.preventDefault();
        return false;
    } else {
        $('.sending_loader').show();
        $('.checkout_form').each(function(e) {
            var step = $(this).data('step');
            result.push(sendFormOrderDetail(step));
        });
        $('.sending_loader').fadeOut(600, function(){
            if ($.inArray(false, result) === -1) {
                location.reload(false); 
            }
        });
    }
});

//order-detail page
function sendFormOrderDetail(step) {
    if ($('#checkoutblock-wrapper').find('#' + step + '_form').length) {
        var formData = new FormData($('#' + step + '_form')[0]);
        var ajaxData = {
            url: $('#' + step + '_form').attr('action'),
            data: formData,
            method:'post',
            dataType:'json',
            async: false,
            contentType: false,
            processData: false,
            success: function(response) {
                var content = $('#customfieldsblock_' + step);
                content.find('.error').remove();
                if (response.result === false) {
                    if (response.errors) {
                        content.prepend('<div class="error alert alert-danger" id="' + response.type + '_error"></div>');
                        var ul = $('<ol>');
                        for(var i = 0; i < response.errors.length; i++) {
                            ul.append($('<li> '+ response.errors[i] + '</li>'));
                        }
                        $('#' + step + '_error').append(ul);
                        appendErrorConsoleItems(response.errors);
                    }

                }
            },
            error: function() {
                $('.sending_loader').hide();
            }
        };
        var xhr = $.ajax(ajaxData);
        return xhr.responseJSON.result;
    }
}

$(document).on('click', '.checkout_form .uploader .action , .checkout_form .uploader .filename', function(e) {
    $(this).closest('.uploader').find('input[type=file]').trigger('click');
})

$(document).ready(function() {
    /* For Zelgra one step checkout */
    submitButton = $('#opc_checkout').find('.confirm_button');
    if (typeof submitButton !== 'undefined' && submitButton) {
        buttonFunction = submitButton.attr('onclick');
        if (typeof buttonFunction !== 'undefined' && buttonFunction) {
            buttonFunction = buttonFunction.replace(/\(\)\;/g, '');
            submitButton.removeAttr('onclick');
            submitButton.on('click', function(event) {
                    event.preventDefault();
                    var is_checked = checkRequiredBoxes();
                    if (is_checked.length && $.inArray(false, is_checked) >= 0) {
                        return false;
                    } else {
                        $('#opc_checkout .checkout_form').each(function(e) {
                            var step = $(this).attr('data-step');
                            sendForm(step);
                        });

                        if ($.inArray(false, ajax_result) >= 0) {
                            ajax_result = [];
                        } else {
                            ajax_result = [];
                            if (buttonFunction in window) {
                                window[buttonFunction].call();
                            }
                    }
                }
            });
        }
    }

    /* For PTS one step module */
    if ($('#onepagecheckoutps_contenedor').length) {
        addFakeButton();
        $(document).on('click', '#btn_place_order_dummy', function(event) {
            var is_checked = checkRequiredBoxes();
            if (is_checked.length && $.inArray(false, is_checked) >= 0) {
                return false;
            } else {
                $('#onepagecheckoutps_contenedor .checkout_form').each(function(e) {
                    var step = $(this).attr('data-step');
                    console.log(step)
                    sendForm(step);
                });

                if ($.inArray(false, ajax_result) >= 0) {
                    ajax_result = [];
                } else {
                    ajax_result = [];
                    $('#btn_place_order').trigger('click');
                }
            }
        });
    }
    appendErrorConsole();
});

$(document).on('click', required_fields, function() {
    var nbr_checkedboxes = $(this).closest('.input_wrapper').find(to_find).length;
    $(this).closest('.input_wrapper').find('.checkboxes').val(nbr_checkedboxes);
})

function checkRequiredBoxes() {
    var is_checked = [];
    $('.error_wrapper').remove();
    $('.checkboxes').each(function(e) {
        if ($(this).attr('data-required') == 1 && $(this).val() <= 0) {
            var parentDiv = $(this).closest('.input_wrapper');
            var field_label = parentDiv.find('.input_label').text();
            parentDiv.prepend('<div class="error alert alert-danger error_wrapper">'
                + field_label + is_required_label
                + '</div>');
            is_checked.push(false);
        }
    });
    return is_checked;
}

// Watch css changes
watchElem.watch({
    // specify CSS styles or attribute names to monitor
    properties: "display",
    // callback function when a change is detected
    callback: addFakeButton,
});

function addFakeButton() {
    var fake_btn = '<button class="btn btn-primary btn-lg pull-right" id="btn_place_order_dummy" type="button">'
        + '<i class="fa-pts fa-pts-shopping-cart fa-pts-1x"></i>'
        + checkout_label
        + '</button>';
    setTimeout(function(){
        if ($('#btn_place_order_dummy').length == 0) {
            $('#btn_place_order').hide().after(fake_btn);
        }
    },1500);
}

function appendErrorConsole() {
  $('body').append('<div id="fmm_error_console"></div>');
}

function appendErrorConsoleItems(_errors) {
  var __error_parent_exists = $('#fmm_error_console').length;
  if (__error_parent_exists > 0 && _errors.length > 0) {
    for (var i = 0; i < _errors.length; i++) {
      $('#fmm_error_console').append('<div class="fmm_error_console_item">'+_errors[i]+'</div>');
    }
  }
  errorConsoleDropEverything();
}

function errorConsoleDropEverything() {
  setTimeout(function() {$('#fmm_error_console *').fadeOut(800)}, 5000);
}