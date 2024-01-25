/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

function select_undelected()
{
    if ($('select[name="MP_PP"] option:selected').val() != 'custom') {
        $('input[name="MP_PPC"]').parent().parent().hide();
    } else {
        $('input[name="MP_PPC"]').parent().parent().show();
    }
    if ($('select[name="MP_PL"] option:selected').val() != 'custom') {
        $('input[name="MP_PLC"]').parent().parent().hide();
    } else {
        $('input[name="MP_PLC"]').parent().parent().show();
    }
}

$('document').ready(function(){
    select_undelected();
    $('select[name="MP_PP"], select[name="MP_PL"]').change(function(){
        select_undelected();
    });
    $('a.multipricefancybox').fancybox();
});