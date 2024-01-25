/*
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    FMM Modules
 *  @copyright 2020 FMM Modules
 *  @license   FMM Modules
 *  @version   1.0.0
*/
function getRelProducts(e) {
	var search_q_val = $(e).val();
	//controller_url = controller_url+'&q='+search_q_val;
	if (typeof search_q_val !== 'undefined' && search_q_val) {
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: mod_url + '&q=' + search_q_val,
			success: function(data)
			{
				var quicklink_list ='<li class="rel_breaker" onclick="relClearData();"><i class="material-icons">&#xE14C;</i></li>';
				$.each(data, function(index,value){
					if (typeof data[index]['id'] !== 'undefined')
						quicklink_list += '<li onclick="relSelectThis('+data[index]['id']+','+data[index]['id_product_attribute']+',\''+data[index]['name']+'\',\''+data[index]['image']+'\');"><img src="' + data[index]['image'] + '" width="60"> ' + data[index]['name'] + '</li>';
				});
				if (data.length == 0) {
					quicklink_list = '';
				}
				$('#rel_holder').html('<ul>'+quicklink_list+'</ul>');
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});
	}
	else {
		$('#rel_holder').html('');
	}
}
function relSelectThis(id, ipa, name, img) {
	if ($('#row_' + id + '_' + ipa).length > 0) {
		showErrorMessage(error_msg);
	} else {
	  var draw_html = '<li id="row_' + id + '" class="media"><div class="media-left"><img src="'+img+'" class="media-object image"></div><div class="media-body media-middle"><span class="label">'+name+'&nbsp;(ID:'+id+')</span><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+id+'" name="related_products[]"></li>'
	  $('#rel_holder_temp ul').append(draw_html);
	}
}
function relClearData() {
    $('#rel_holder').html('');
}
function relDropThis(e) {
    $(e).parent().parent().remove();
}


function getBrands(ele) {
	var _search_q_val = $(ele).val();
 if (typeof _search_q_val !== 'undefined' && _search_q_val) {
   $.ajax({
			type: 'GET',
			dataType: 'json',
			url: _mod_url + '&q=' + _search_q_val,
			success: function(data)
			{
      var _quicklink_list = '';
      if (data.length > 0) {
        $.each(data, function(index,value){
          if (typeof data[index]['id'] !== 'undefined') {
             _quicklink_list += '<li onclick="SelectThisBrand('+data[index]['id']+',\''+data[index]['name']+'\');">(ID.'+data[index]['id']+') ' + data[index]['name'] + '</li>';
          }
         });
     $('#brands_collector').show();
      }
      else {
       _quicklink_list = '';
       $('#brands_collector').hide();
     }
     $('#brands_collector ul').html(_quicklink_list);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});
 }
}
function SelectThisBrand(id, name) {
 if ($('#brands_collection ul li#row_' + id).length > 0) {
		//do nothing
	} else {
	  var _draw_html = '<li id="row_' + id + '"><div class="left">'+id+'</div><div class="middle">'+name+'</div><div class="right"><i onclick="BrandDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+id+'" name="brands_filter[]"></li>'
 }
	  $('#brands_collection ul').append(_draw_html);
	}
 $(document).mouseup(function (e){
    var __container = $(".fmm_relative");
   
    if (!__container.is(e.target) && __container.has(e.target).length === 0){
        $('#brands_collector').hide();
    }
				
				var __container_supp = $(".fmm_relative_supp");
   
    if (!__container_supp.is(e.target) && __container_supp.has(e.target).length === 0){
        $('#supps_collector').hide();
    }
});
 
 function BrandDropThis(e) {
    $(e).parent().parent().remove();
}


function getSupps(___ele) {
	var _search_q_val = $(___ele).val();
 if (typeof _search_q_val !== 'undefined' && _search_q_val) {
   $.ajax({
			type: 'GET',
			dataType: 'json',
			url: __mod_url + '&q=' + _search_q_val,
			success: function(data)
			{
      var _quicklink_list = '';
      if (data.length > 0) {
        $.each(data, function(index,value){
          if (typeof data[index]['id'] !== 'undefined') {
             _quicklink_list += '<li onclick="SelectThisSupp('+data[index]['id']+',\''+data[index]['name']+'\');">(ID.'+data[index]['id']+') ' + data[index]['name'] + '</li>';
          }
         });
     $('#supps_collector').show();
      }
      else {
       _quicklink_list = '';
       $('#supps_collector').hide();
     }
     $('#supps_collector ul').html(_quicklink_list);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});
 }
}

function SelectThisSupp(id, name) {
 if ($('#supps_collection ul li#row_supp_' + id).length > 0) {
		//do nothing
	} else {
	  var _draw_html = '<li id="row_supp_' + id + '"><div class="left">'+id+'</div><div class="middle">'+name+'</div><div class="right"><i onclick="BrandDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+id+'" name="suppliers_filter[]"></li>'
 }
	  $('#supps_collection ul').append(_draw_html);
	}
	
	function SelectThisCity(evv) {
		var _vl = $('#fmm_city_plc').val();
		if (_vl) {
						var _draw_html = '<li id="row_city_' + _vl + '"><div class="middle">'+_vl+'</div><div class="right"><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+_vl+'" name="city_filter[]"></li>'
						
						$('#city_collection ul').append(_draw_html);
		}
		
		$('#fmm_city_plc').val('');
	}
	
	function SelectThisZip(evv) {
		var _vl = $('#fmm_zips_plc').val();
		if (_vl) {
						var _draw_html = '<li id="row_zips_' + _vl + '"><div class="middle">'+_vl+'</div><div class="right"><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+_vl+'" name="zip_filter[]"></li>'
						
						$('#zips_collection ul').append(_draw_html);
		}
		
		$('#fmm_zips_plc').val('');
	}