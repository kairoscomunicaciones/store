/**
* Don't edit the file if you want to update module in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/

$(document).ready(function(){
	if($('.sortable').length > 0)
    $('.sortable').sortable(
	{
		forcePlaceholderSize: true,
		handle: '.order_field_move',
		opacity: 0.5,
		cursor: 'move',
	});

	
	$(document).on('click', '.box_setting_showinclose', function() {
        $(this).closest('.box_setting_showin_box').toggleClass('active');
		if($('.g_ordermanager_popup_wp').length > 0) $('.g_ordermanager_popup_wp').removeClass('active');
		return false;
    });
	$(document).on('click', '.grid_config_pos', function() {
        $('.box_gridsetting_showin_box').toggleClass('active');
		return false;
    });
	$(document).on('click', '.csv_config_pos', function() {
        $('.box_csvsetting_showin_box').toggleClass('active');
		return false;
    });
	$('.order_field_config').change(function(){
		var rel = $(this).attr('rel');
		if($(this).is(':checked')){
			$('.order_field_'+rel).removeClass('field_not_show');
		}else {
			$('.order_field_'+rel).addClass('field_not_show');
		}
	});
	$('.order_csv_field_config').change(function(){
		var rel = $(this).attr('rel');
		if($(this).is(':checked')){
			$('.order_csv_field_'+rel).removeClass('field_not_show');
		}else {
			$('.order_csv_field_'+rel).addClass('field_not_show');
		}
	});
	$('#box_setting_showinreset').click(function(){
		$('.box_gridsetting_showin_box input:checkbox').prop('checked', false).change();   
		var defaultfield = ['id_order','reference','new','cname','customer','total_paid_tax_incl','payment','osname','date_add']; 
		setTimeout(function(){
			$.each(defaultfield,function(key,field){
				$('.box_gridsetting_showin_box #order_fields_'+field).prop('checked', true).change();   
			});
		},200);
		
		return false;
	});
	$('#box_csvsetting_showinreset').click(function(){
		$('.box_csvsetting_showin_box input:checkbox').prop('checked', false).change();   
		var defaultfield = ['id_order','reference','new','cname','customer','total_paid_tax_incl','payment','osname','date_add']; 
		setTimeout(function(){
			$.each(defaultfield,function(key,field){
				$('.box_csvsetting_showin_box #order_fields_'+field).prop('checked', true).change();   
			});
		},200);
		
		return false;
	});
})