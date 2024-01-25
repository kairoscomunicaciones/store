{*
* This file to add javascript to footer of Admin Backoffice 
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net/
* @license   please read license in file license.txt
*/
*}

<script type="text/javascript">
    var requied_choose = "{l s='Please select order' mod='g_ordermanager'}";
    var remove_confirm = "{l s='Delete selected item(s)' mod='g_ordermanager'}";
    var content_invalid = "{l s='Content invalid, Please check again.' mod='g_ordermanager'}";
    var admincartlink = "{$link->getAdminLink('AdminCarts')|addslashes|escape:'html':'UTF-8'}";
    var admincartstoken = "{$admincartstoken|escape:'html':'UTF-8'}";
    var token_admin_ordermanager = "{$adminordermanagerstoken|escape:'html':'UTF-8'}";
    function reloadAllDatetimepicker()
    {
        if($('.datetimepicker').length > 0)
            $('.datetimepicker').each(function(){
                if(!$(this).hasClass('hasDatepicker'))
                    $(this).datetimepicker({
            			prevText: '',
            			nextText: '',
            			dateFormat: 'yy-mm-dd',
            			currentText: '{l s='Now' js=1 mod='g_ordermanager'}',
            			closeText: '{l s='Done' js=1 mod='g_ordermanager'}',
            			ampm: false,
            			amNames: ['AM', 'A'],
            			pmNames: ['PM', 'P'],
            			timeFormat: 'hh:mm:ss tt',
            			timeSuffix: '',
            			timeOnlyTitle: '{l s='Choose Time' js=1 mod='g_ordermanager'}',
            			timeText: '{l s='Time' js=1 mod='g_ordermanager'}',
            			hourText: '{l s='Hour' js=1 mod='g_ordermanager'}',
            			minuteText: '{l s='Minute' js=1 mod='g_ordermanager'}'
            		});
                });
    }
</script>
<script type="text/javascript" src="{$base_uri|escape:'html':'UTF-8'}modules/g_ordermanager/views/js/admin/g_ordermanager.js"></script>
<a id="linkDynamic" target="_blank" href="#"></a>