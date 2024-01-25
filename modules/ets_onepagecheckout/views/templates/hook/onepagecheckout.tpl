{*
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
*}
<style>
{$css_extra nofilter}
</style>
<script type="text/javascript">
var confirm_product = '{l s='Do you want to delete this product?' mod='ets_onepagecheckout' js=1}';
var confirm_discount = '{l s='Do you want to delete this discount?' mod='ets_onepagecheckout' js=1}';
var iso_code_state='';
var ets_opc_date_format_lite ='{$date_format_lite|escape:'html':'UTF-8'}';
var countries = {literal}{}{/literal};
var ETS_OPC_USE_NAME_ACCOUNT ={Configuration::get('ETS_OPC_USE_NAME_ACCOUNT')|intval};
var countriesNeedZipCode = {literal}{}{/literal};
var login_before_text = '{l s='Please login before completing your order' mod='ets_onepagecheckout' js=1}';
{if $list_countries}
    {foreach from=$list_countries item='country'}
        countries[{$country.id_country|intval}] ={literal}{}{/literal};
        countries[{$country.id_country|intval}]['iso_code'] = '{$country.iso_code|escape:'html':'UTF-8'}';
        countriesNeedZipCode[{$country.id_country|intval}] = '{$country.zip_code_format|escape:'html':'UTF-8'}';
    {/foreach}
{/if}
</script>
{if $ETS_OPC_ADDRESS_GOOGLE_AUTOFILL_ENABLED}
    <script>
    {literal}
    var shipping_autocomplete,invoice_autocomplete;
    
    var componentForm = {
      locality: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'
    };
    
    function initAutocomplete() {
      shipping_autocomplete = new google.maps.places.Autocomplete(
          document.getElementById('shipping_address_address1'), {types: ['geocode']});
      shipping_autocomplete.setFields(['address_component']);
      shipping_autocomplete.addListener('place_changed', fillInShippingAddress);
      invoice_autocomplete = new google.maps.places.Autocomplete(
          document.getElementById('invoice_address_address1'), {types: ['geocode']});
    
      invoice_autocomplete.setFields(['address_component']);
      invoice_autocomplete.addListener('place_changed', fillInInvoiceAddress);
    }
    
    function fillInShippingAddress() {
      var place = shipping_autocomplete.getPlace();
      if(place)
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i]['short_name'];
          if(addressType=='country')
          {
                if($('#shipping_address_id_country option').length)
                {
                    var $ok = false;
                    $('#shipping_address_id_country option').each(function(){
                        if($(this).attr('data-iso-code')==val)
                        {
                            $ok = true;
                            if($('#shipping_address_id_country').val()!=$(this).attr('value') && $(this).attr('selected')!='selected')
                            {
                                $('#shipping_address_id_country option').removeAttr('selected');
                                $(this).attr('selected','selected');
                                $('#shipping_address_id_country').val($(this).attr('value'));
                                $('#shipping_address_id_country').change(); 
                            }
                           
                        }
                   });
                   if(!$ok)
                   {
                        $('#shipping_address_id_country option').removeAttr('selected');
                        $('#shipping_address_id_country').val('');
                        $('#shipping_address_id_country').change(); 
                   }
                }
                
          }
          if(addressType=='locality')
          {
                if($('#shipping_address_city').length)
                {
                    $('#shipping_address_city').val(val);
                    $('#shipping_address_city').change();
                }
          }
          if(addressType=='postal_code')
          {
                if($('#shipping_address_postal_code').length)
                {
                    $('#shipping_address_postal_code').val(val);
                    $('#shipping_address_postal_code').change();
                }
          }
        }
        else
        {
            if(addressType=='administrative_area_level_1')
            {
                iso_code_state = place.address_components[i]['short_name'];
                if($('#shipping_address_id_state').length)
                {
                    $('#shipping_address_id_state option').each(function(){
                        if($(this).attr('data-iso-code')==iso_code_state && $('#shipping_address_id_state').val()!=$(this).attr('value') && $(this).attr('selected')!='selected')
                        {
                           $('#shipping_address_id_state option').removeAttr('selected');
                           $(this).attr('selected','selected');
                           $('#shipping_address_id_state').val($(this).attr('value'));
                           $('#shipping_address_id_state').change(); 
                        }
                   });
                }
            }
        }
      }
    }
    function fillInInvoiceAddress() {
      var place = invoice_autocomplete.getPlace();
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          if(addressType=='country')
          {
                if($('#invoice_address_id_country option').length)
                {
                    var $ok = false;
                    $('#invoice_address_id_country option').each(function(){
                        if($(this).attr('data-iso-code')==val)
                        {
                            $ok = true;
                            if($('#invoice_address_id_country').val()!=$(this).attr('value') && $(this).attr('selected')!='selected')
                            {
                                $('#invoice_address_id_country option').removeAttr('selected');
                                $(this).attr('selected','selected');
                                $('#invoice_address_id_country').val($(this).attr('value'));
                                $('#invoice_address_id_country').change(); 
                            }
                           
                        }
                   }); 
                   if(!$ok)
                   {
                        $('#invoice_address_id_country option').removeAttr('selected');
                        $('#invoice_address_id_country').val('');
                        $('#invoice_address_id_country').change(); 
                   }
                }
          }
          if(addressType=='locality')
          {
                if($('#invoice_address_city').length)
                {
                    $('#invoice_address_city').val(val);
                    $('#invoice_address_city').change();
                }
          }
          if(addressType=='postal_code')
          {
                if($('#invoice_address_postal_code').length)
                {
                    $('#invoice_address_postal_code').val(val);
                    $('#invoice_address_postal_code').change();
                }
          }
        }
        else
        {
            if(addressType=='administrative_area_level_1')
            {
                iso_code_state = place.address_components[i][componentForm[addressType]];
                if($('#invoice_address_id_state').length)
                {
                    $('#invoice_address_id_state option').each(function(){
                        if($(this).attr('data-iso-code')==iso_code_state && $('#invoice_address_id_state').val()!=$(this).attr('value') && $(this).attr('selected')!='selected')
                        {
                           $('#invoice_address_id_state option').removeAttr('selected');
                           $(this).attr('selected','selected');
                           $('#invoice_address_id_state').val($(this).attr('value'));
                           $('#invoice_address_id_state').change(); 
                        }
                   });
                }
            }
        }
      }
    }
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          var circle = new google.maps.Circle(
              {center: geolocation, radius: position.coords.accuracy});
          autocomplete.setBounds(circle.getBounds());
        });
      }
    }
    {/literal}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={$ETS_OPC_GOOGLE_KEY_API|escape:'html':'UTF-8'}&libraries=places&callback=initAutocomplete"
        async defer></script>
{/if}
<div id="onepagecheckout-information-errros" class="" style="">
    {if $isAvailable}
        {$isAvailable nofilter}
    {/if}
</div>
{if $opc_layout=='layout_1'}
    {include file='module:ets_onepagecheckout/views/templates/hook/layout_1.tpl'}
{elseif $opc_layout=='layout_2'}
    {include file='module:ets_onepagecheckout/views/templates/hook/layout_2.tpl'}
{elseif $opc_layout=='layout_3'}
    {include file='module:ets_onepagecheckout/views/templates/hook/layout_3.tpl'}
{elseif $opc_layout=='layout_4'}
    {include file='module:ets_onepagecheckout/views/templates/hook/layout_4.tpl'}
{/if}

<div class="modal fade" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="js-modal-content">
                {$terms_page->content nofilter}
            </div>
        </div>
    </div>
</div>