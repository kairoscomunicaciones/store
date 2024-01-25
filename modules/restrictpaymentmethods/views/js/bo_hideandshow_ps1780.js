/**
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FME Modules
*  @copyright 2020 FME Modules
*  @license   Comerical Licence
*  @package   rsm
*/
$(document).ready(function() {
    $('input#checkme').parents('div.col-lg-6:first').parent().addClass('rpm_has_groupBox');
    $('.prestashop-switch label').each(function() {
       rsmhascategories(this);
    });
});

$(document).on('click', '.prestashop-switch input', rsmhascategoriesclk);

function rsmhascategories(_el) {
    var _ele_val = $(this).attr('for');
    if (!_ele_val) {
        _ele_val = $(_el).attr('for');
    }
    var _name_of_input_elem = _ele_val.replace('_on', '');
    _name_of_input_elem = _name_of_input_elem.replace('_off', '');
    if(_ele_val.indexOf('rpm') > -1) {
        var __radio_value_return = $('input[name="'+_name_of_input_elem+'"]:checked').val();
        if (__radio_value_return > 0) {
            $('.'+_name_of_input_elem).parent().parent().show();
        }
        else if (__radio_value_return <= 0 || !__radio_value_return) {
            $('.'+_name_of_input_elem).parent().parent().hide();
        }
    }
}

function rsmhascategoriesclk() {
    var _ele_val = $(this).next('label').attr('for');
    
    var _name_of_input_elem = _ele_val.replace('_on', '');
    
    _name_of_input_elem = _name_of_input_elem.replace('_off', '');
    console.log(_name_of_input_elem+' '+_name_of_input_elem);
    if(_ele_val.indexOf('rpm') > -1) {
        if(_ele_val.indexOf('_on') > -1) {
            var __radio_value_return = 1;
        }
        else {
            var __radio_value_return = 0;
        }
        if (__radio_value_return > 0) {
            $('.'+_name_of_input_elem).parent().parent().show();
        }
        else if (__radio_value_return <= 0 || !__radio_value_return) {
            $('.'+_name_of_input_elem).parent().parent().hide();
        }
    }
}