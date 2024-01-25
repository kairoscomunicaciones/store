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

var steps = [];
var slist = [];
var links = [];
function orderStep(step) {
  steps.push(step);
}

function addCarrier(fieldsHtml) {
  var chDiv = $('<div id="checkoutfields" ></div>');
  chDiv.append(fieldsHtml);
  if ($("#extra_carrier").find("#shipping_form").length) {
    $("#shipping_form")
      .parent()
      .remove();
    $("#extra_carrier")
      .append(chDiv)
      .show();
  }
}

function addAddress(fieldsHtml) {
  var chDiv = $('<div id="customfields" ></div>');
  chDiv.append(fieldsHtml);
  $(".add-address").last().prepend(chDiv);
  $("div.js-address-form form").after(chDiv);
  $("div.js-address-form #customfields div.fmm_cf_shipping_block").css(
    "width",
    "100%"
  );
  $("div.js-address-form #customfields").css("padding-top", "10px");
  $("#carrier_area").before(chDiv);
}

function setDatePicker() {
  $(".fields_datapicker ").datepicker({
    dateFormat: "yy-mm-dd",
    prevText: "",
    nextText: ""
  });
}

function sendForm(step) {
  var redirect = true;
  var formData = new FormData($("#" + step + "_form")[0]);
  var ajaxData = {
    url: $("#" + step + "_form").attr("action"),
    data: formData,
    method: "post",
    dataType: "json",
    contentType: false,
    processData: false,
    success: function(response) {
      var content = $("#customfieldsblock_" + step);
      content.find(".error").remove();
      if (!response.result) {
        redirect = false;
        if (response.errors) {
          content.prepend(
            '<div class="error alert alert-danger" id="' +
              response.type +
              '_error"></div>'
          );
          var ul = $("<ol>");
          for (var i = 0; i < response.errors.length; i++) {
            ul.append($("<li>" + response.errors[i] + "</li>"));
          }
          $("#" + step + "_error").append(ul);
          appendErrorConsoleItems(response.errors);
        }
      }
      if (slist.length) {
        sendForm(slist.pop());
      } else if (redirect) {
        trigger(links);
      }
    }
  };
  var xhr = $.ajax(ajaxData);
}

function sendFormAddresses(step, ev) {
  if ($("#checkout-addresses-step").find("#" + step + "_form").length) {
    var redirect = true;
    var formData = new FormData($("#" + step + "_form")[0]);
    var ajaxData = {
      url: $("#" + step + "_form").attr("action"),
      data: formData,
      method: "post",
      dataType: "json",
      async: false,
      contentType: false,
      processData: false,
      success: function(response) {
        var content = $("#customfieldsblock_" + step);
        content.find(".error").remove();
        if (response.result === false) {
          redirect = false;
          if (response.errors) {
            content.prepend(
              '<div class="error alert alert-danger" id="' +
                response.type +
                '_error"></div>'
            );
            var ul = $("<ol>");
            for (var i = 0; i < response.errors.length; i++) {
              ul.append($("<li> " + response.errors[i] + "</li>"));
            }
            $("#" + step + "_error").append(ul);
            appendErrorConsoleItems(response.errors);
          }
        }
      }
    };
    var xhr = $.ajax(ajaxData);
    return xhr.responseJSON.result;
  }
}

function sendFormShipment(step, ev) {
  var redirect = true;
  var formData = new FormData($("#" + step + "_form")[0]);
  var ajaxData = {
    url: $("#" + step + "_form").attr("action"),
    data: formData,
    method: "post",
    dataType: "json",
    contentType: false,
    processData: false,
    success: function(response) {
      var content = $("#customfieldsblock_" + step);
      content.find(".error").remove();
      if (!response.result) {
        redirect = false;
        if (response.errors) {
          content.prepend(
            '<div class="error alert alert-danger" id="' +
              response.type +
              '_error"></div>'
          );
          var ul = $("<ol>");
          for (var i = 0; i < response.errors.length; i++) {
            ul.append($("<li>" + response.errors[i] + "</li>"));
          }
          $("#" + step + "_error").append(ul);
          appendErrorConsoleItems(response.errors);
        }
      }
      if (redirect) {
        $(ev).after(
          '<button type="submit" id="fmm_cf_trigger_shippment" style="visibility:hidden;width:0px" name="confirmDeliveryOption"></button>'
        );
        $("#fmm_cf_trigger_shippment").click();
      }
    }
  };
  var xhr = $.ajax(ajaxData);
}

function sendFormPayment(step, ev) {
  var redirect = true;
  var formData = new FormData($("#" + step + "_form")[0]);
  var ajaxData = {
    url: $("#" + step + "_form").attr("action"),
    data: formData,
    method: "post",
    dataType: "json",
    contentType: false,
    processData: false,
    success: function(response) {
      var content = $("#customfieldsblock_" + step);
      content.find(".error").remove();
      if (!response.result) {
        redirect = false;
        if (response.errors) {
          content.prepend(
            '<div class="error alert alert-danger" id="' +
              response.type +
              '_error"></div>'
          );
          var ul = $("<ol>");
          for (var i = 0; i < response.errors.length; i++) {
            ul.append($("<li>" + response.errors[i] + "</li>"));
          }
          $("#" + step + "_error").append(ul);
          appendErrorConsoleItems(response.errors);
        }
      }
      if (redirect) {
        //if there is no error trigger click on default payment button
        $("#payment-confirmation button").trigger('click');
      }
    }
  };
  var xhr = $.ajax(ajaxData);
}

function trigger(links) {
  if (this.links.is("a")) {
    if (this.links.children().is("form")) {
      this.links.children().submit();
    } else {
      location.href = this.links.attr("href");
    }
  } else if (this.links.is("img")) {
    location.href = this.links.parent().attr("href");
  } else if (this.links.parent().is("form")) {
    this.links.parent().submit();
  } else if (this.links.children(":first").is("a")) {
    location.href = this.links.children(":first").attr("href");
  } else if (this.links.parent().is("a")) {
    location.href = this.links.parent().attr("href");
  } else if (this.links.parent().parent().is("a")) {
    location.href = this.links.parent().parent().attr("href");
  } else if (
    this.links.parent().parent().parent().is("a")) {
    location.href = this.links.parent().parent().parent().attr("href");
  } else {
    this.links.unbind("click");
    this.links.trigger("click");
  }
}

$(document).find(".fields_datapicker ").datepicker({
  dateFormat: "yy-mm-dd",
  prevText: "",
  nextText: ""
});

//function saveFieldData() {
  // Summary step
  $(document).on('click', "div.cart-detailed-actions a.btn", function(event) {
    if($('.checkout_form').length) {
      event.preventDefault();
      var is_checked = checkRequiredBoxes();
      if (is_checked.length && $.inArray(false, is_checked) >= 0) {
        return false;
      } else {
        links = $(this);
        if (!steps.length) {
          trigger();
        }
        slist = [].concat(steps);
        sendForm(slist.pop());
        return false;
      }
    }
  });

  // For Addresses
  $(document).on("click", "#checkout-addresses-step .continue", function(event) {
    if ($("#checkout-addresses-step").find(".checkout_form").length) {
        event.preventDefault();
        var results = [];
        var is_checked = checkRequiredBoxes();
        if (is_checked.length && $.inArray(false, is_checked) >= 0) {
          return false;
        } else {
          $("#checkout-addresses-step").find(".checkout_form").each(function(e) {
            if ($("#" + $(this).data("step") + "_form").length) {
              var result = sendFormAddresses($(this).data("step"), this);
              results.push(result);
            }
          });
          if ($.inArray(false, results) === -1) {
            $(this).after(
              '<button type="submit" id="fmm_cf_trigger_addresses" style="visibility:hidden;width:0px" name="confirm-addresses"></button>'
            );
            $("#fmm_cf_trigger_addresses").click();
          }
          return false;
        }
      }
  });

  //For Shipping Step
  $(document).on('click', "#checkout-delivery-step form .continue", function(event) {
      if ($("#shipping_form").length) {
        var is_checked = checkRequiredBoxes();
        if (is_checked.length && $.inArray(false, is_checked) >= 0) {
          return false;
        } else {
          sendFormShipment("shipping", this);
          return false;
        }
      }
  });

  //For payment step - override payment button
  if ($("#payment_form").length) {
    var _paymentBtn = $("#payment-confirmation button[type=submit]");
    if (_paymentBtn) {
      var _overridePaymentBtn = $(getPaymentBtn($.trim(_paymentBtn.html())));
      _paymentBtn.hide().after(_overridePaymentBtn);_paymentBtn.css('visibility', 'hidden');
      _overridePaymentBtn.after(_paymentBtn).show();
    }

    $(document).on('click', "#fmm-payment-btn", function(event) {
      if (false == $('#fmm-payment-btn').hasClass('cf_disabled_btn')) {
        var is_checked = checkRequiredBoxes();
        if (is_checked.length && $.inArray(false, is_checked) >= 0) {
          return false;
        } else {
          sendFormPayment("payment", this);
          return false;
        }
      }
    });
  }
//}

$(document).on('click', '#checkout-payment-step *', function() {
  var _selected_payment = $('#checkout-payment-step').find('input[name="payment-option"]:checked').length;
//   var _terms_n_cond = $('#conditions-to-approve').find('input[type="checkbox"]:checked').length;
  if ( (typeof _selected_payment !== 'undefined' && _selected_payment >= 1) ) {
    $('#fmm-payment-btn').removeClass('cf_disabled_btn');
  } else {
    if (false == $('#fmm-payment-btn').hasClass('cf_disabled_btn')) {
      $('#fmm-payment-btn').addClass('cf_disabled_btn');
    }
  }
})
function runScript() {
  if ($(".checkout_form").length) {
    setDatePicker();
    //saveFieldData();
  }
}

$(document).on("click", "button[name=saveCustomFields]", function(event) {
  var result = [];
  var is_checked = checkRequiredBoxes();
  if (is_checked.length && $.inArray(false, is_checked) >= 0) {
    event.preventDefault();
    return false;
  } else {
    $(".sending_loader").show();
    $(".checkout_form").each(function(e) {
      var step = $(this).data("step");
      result.push(sendFormOrderDetail(step));
    });
    $(".sending_loader").fadeOut(600, function() {
      if ($.inArray(false, result) === -1) {
        location.reload(false);
      }
    });
  }
});

//order-detail page
function sendFormOrderDetail(step) {
  if ($("#checkoutblock-wrapper").find("#" + step + "_form").length) {
    var formData = new FormData($("#" + step + "_form")[0]);
    var ajaxData = {
      url: $("#" + step + "_form").attr("action"),
      data: formData,
      method: "post",
      dataType: "json",
      async: false,
      contentType: false,
      processData: false,
      success: function(response) {
        var content = $("#customfieldsblock_" + step);
        content.find(".error").remove();
        if (response.result === false) {
          if (response.errors) {
            content.prepend(
              '<div class="error alert alert-danger" id="' +
                response.type +
                '_error"></div>'
            );
            var ul = $("<ol>");
            for (var i = 0; i < response.errors.length; i++) {
              ul.append($("<li> " + response.errors[i] + "</li>"));
            }
            $("#" + step + "_error").append(ul);
            appendErrorConsoleItems(response.errors);
          }
        }
      },
      error: function() {
        $(".sending_loader").hide();
      }
    };
    var xhr = $.ajax(ajaxData);
    return xhr.responseJSON.result;
  }
}
var required_fields = ".input_wrapper input:checkbox, .input_wrapper input:radio, .input_wrapper select";
var to_find = 'input[type="checkbox"]:checked, input[type="radio"]:checked, select option:selected';
$(document).ready(function() {
  reCrawlOpts();
  appendErrorConsole();
  prestashop.on("updateCart", function(event) {
    location.reload();
  });
});

$(document).on("change", ".input_wrapper select", function(e) {
  checkDependency($(this));
});

$(document).on("click", ".input_wrapper input:checkbox, .input_wrapper input:radio", function(e) {
  checkDependency($(this));
});

function checkRequiredBoxes() {
  var is_checked = [];
  $(".error_wrapper").remove();
  $(".checkboxes").each(function(e) {
    if ($(this).attr("data-required") == 1 && $(this).val() <= 0) {
      var parentDiv = $(this).closest(".input_wrapper");
      var field_label = parentDiv.find(".input_label").text();
      parentDiv.prepend(
        '<div class="error alert alert-danger error_wrapper">' +
          field_label +
          is_required_label +
          "</div>"
      );
      is_checked.push(false);
    }
  });
  return is_checked;
}

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


function getPaymentBtn(text) {
  return '<span class="btn btn-primary center-block cf_disabled_btn" id="fmm-payment-btn">'+text+'</span>'
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