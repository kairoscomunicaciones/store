/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @author    knowband.com <support@knowband.com>
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
* @category  PrestaShop Module
*/

$(document).ready(function() {
    $('.kbocc_config_submit_button').click(function(){
        var is_error = false;
        $('.kb_error_message').remove();
        $('input[name="oneclickcheckout[background]"]').parent().removeClass('kb_error_field');
        $('input[name="oneclickcheckout[text]"]').parent().removeClass('kb_error_field');
        $('input[name="oneclickcheckout[border]"]').parent().removeClass('kb_error_field');
    
        /*Knowband validation start*/
        var bg_mandatory_err = velovalidation.checkMandatory($('input[name="oneclickcheckout[background]"]'));
        if (bg_mandatory_err != true)
        {
            is_error = true;            
            $('input[name="oneclickcheckout[background]"]').parent().addClass('kb_error_field');
            $('input[name="oneclickcheckout[background]"]').parent().parent().after('<span class="kb_error_message">' + bg_mandatory_err + '</span>');
        }
        else
        {
            var bg_msg_err = velovalidation.isColor($('input[name="oneclickcheckout[background]"]'));
            if (bg_msg_err != true)
            {
                is_error = true;            
                $('input[name="oneclickcheckout[background]"]').parent().addClass('kb_error_field');
                $('input[name="oneclickcheckout[background]"]').parent().parent().after('<span class="kb_error_message">' + bg_msg_err + '</span>');
            }
        }
        /*Knowband validation end*/

        /*Knowband validation start*/
        var txt_mandatory_err = velovalidation.checkMandatory($('input[name="oneclickcheckout[text]"]'));
        if (txt_mandatory_err != true)
        {
            is_error = true;            
            $('input[name="oneclickcheckout[text]"]').parent().addClass('kb_error_field');
            $('input[name="oneclickcheckout[text]"]').parent().parent().after('<span class="kb_error_message">' + txt_mandatory_err + '</span>');
        }
        else
        {
            var txt_msg_err = velovalidation.isColor($('input[name="oneclickcheckout[text]"]'));
            if (txt_msg_err != true)
            {
                is_error = true;            
                $('input[name="oneclickcheckout[text]"]').parent().addClass('kb_error_field');
                $('input[name="oneclickcheckout[text]"]').parent().parent().after('<span class="kb_error_message">' + txt_msg_err + '</span>');
            }
        }
        /*Knowband validation end*/
        
        /*Knowband validation start*/
        var border_mandatory_err = velovalidation.checkMandatory($('input[name="oneclickcheckout[border]"]'));
        if (border_mandatory_err != true)
        {
            is_error = true;            
            $('input[name="oneclickcheckout[border]"]').parent().addClass('kb_error_field');
            $('input[name="oneclickcheckout[border]"]').parent().parent().after('<span class="kb_error_message">' + border_mandatory_err + '</span>');
        }
        else
        {
            var border_msg_err = velovalidation.isColor($('input[name="oneclickcheckout[border]"]'));
            if (border_msg_err != true)
            {
                is_error = true;            
                $('input[name="oneclickcheckout[border]"]').parent().addClass('kb_error_field');
                $('input[name="oneclickcheckout[border]"]').parent().parent().after('<span class="kb_error_message">' + border_msg_err + '</span>');
            }
        }
        /*Knowband validation end*/

        if(is_error){
            return false;
        }
        /*Knowband button validation start*/
        $('.kbocc_config_submit_button').attr('disabled','disabled');
        /*Knowband button validation end*/
        $('#configuration_form').submit();
    });
});
