/**

* This is main js file. Don't edit the file if you want to update module in future.

* 

* @author    Globo Software Solution JSC <contact@globosoftware.net>

* @copyright 2021 Globo., Jsc

* @link	     http://www.globosoftware.net

* @license   please read license in file license.txt

*/



$(document).ready(function(){

    reloadAllDatetimepicker();

    function loadOrderExtraData(idorder,quickview = false){

        $('.order_extra_box').removeClass('active').removeClass('quickviewactive');

        $('#order_extra_box_'+idorder).addClass('active');

        if(quickview) $('#order_extra_box_'+idorder).addClass('quickviewactive');

        if($('#order_extra_box_'+idorder+' .order_extra_block > div').length == 0){

            datapost = 'loadOrderExtraData=1&idorder='+idorder;

            $.ajax({

              type : 'POST',                  

              data: datapost,

            })

            .done(function(data) {

                $('#order_extra_box_'+idorder+' .order_extra_block').html(data);

                reloadAllDatetimepicker();

            });

        }

    }

    

    $('.orderproductimage_link').fancybox();

    $('.ifancybox').fancybox({

		'type': 'iframe',

		'width': '90%',

		'height': '90%',

	});

    $(document).on('click', '.edit_discount_link', function() {

        rel = $(this).attr('rel');

        $('.discount_edit_field').stop(true,true).css('display','none');

        $('.edit_discount_link_'+rel).stop(true,true).css('display','none');

        $('.delete_edit_discount_link_'+rel).stop(true,true).css('display','none');

        $('.discount_edit_field_'+rel).stop(true,true).css('display','block');

        return false;

    });

    $(document).on('click', '.cancel_edit_discount_link', function() {

        rel = $(this).attr('rel');

        $('.edit_discount_link_'+rel).stop(true,true).css('display','block');

        $('.delete_edit_discount_link_'+rel).stop(true,true).css('display','block');

        $('.discount_edit_field_'+rel).stop(true,true).css('display','none');

        return false;

    });

    $(document).on('click', '.edit_payment_information', function() {

        rel = $(this).data('id');

        $('.edit_payment_information_'+rel).stop(true,true).css('display','none');

        $('#edit_payment_information_'+rel).stop(true,true).css('display','table-row');

        $(this).parents('.payment_table').find('.current-edit').stop(true,true).css('display','none');

        return false;

    });

    $(document).on('click', '.cancel_edit_payment_information', function() {

        rel = $(this).attr('rel');

        $('.edit_payment_information_'+rel).stop(true,true).css('display','inline-block');

        $('#edit_payment_information_'+rel).stop(true,true).css('display','none');

        $(this).parents('.payment_table').find('.current-edit').stop(true,true).css('display','table-row');

        return false;

    });

    $(document).on('change', 'input[name="orderBox[]"]', function() {

        if($(this).is(':checked')){

            $('.tr_order_'+$(this).val()).addClass('tdselected');

        }else

            $('.tr_order_'+$(this).val()).removeClass('tdselected');

    });

    

    function updateAmounts_mod(order)

    {

        

    	$('#total_products td.amount').fadeOut('slow', function() {

    		$(this).html(formatCurrency(parseFloat(order.total_products_wt), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	$('#total_discounts td.amount').fadeOut('slow', function() {

    		$(this).html('-'+formatCurrency(parseFloat(order.total_discounts_tax_incl), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	if (order.total_discounts_tax_incl > 0)

    		$('#total_discounts').slideDown('slow');

    	$('#total_wrapping td.amount').fadeOut('slow', function() {

    		$(this).html(formatCurrency(parseFloat(order.total_wrapping_tax_incl), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	if (order.total_wrapping_tax_incl > 0)

    		$('#total_wrapping').slideDown('slow');

    	$('#total_shipping td.amount').fadeOut('slow', function() {

    		$(this).html(formatCurrency(parseFloat(order.total_shipping_tax_incl), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	$('#total_order td.amount').fadeOut('slow', function() {

    		$(this).html(formatCurrency(parseFloat(order.total_paid_tax_incl), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	$('.total_paid').fadeOut('slow', function() {

    		$(this).html(formatCurrency(parseFloat(order.total_paid_tax_incl), currency_format, currency_sign, currency_blank));

    		$(this).fadeIn('slow');

    	});

    	$('.alert').slideDown('slow');

    	$('#product_number').fadeOut('slow', function() {

    		var old_quantity = parseInt($(this).html());

    		$(this).html(old_quantity + 1);

    		$(this).fadeIn('slow');

    	});

    	$('#shipping_table .weight').fadeOut('slow', function() {

    		$(this).html(order.weight);

    		$(this).fadeIn('slow');

    	});

    }

    function updateAllBlook(data)

    {

        if(!data.haserror)

        {

			go = false;

			updateAmounts_mod(data.order);

			updateInvoice(data.invoices);

			updateDocuments(data.documents_html);

			updateDiscountForm(data.discount_form_html);

            $('#panel-vouchers-wp').html(data.discount_val_html);

			// Initialize all events

			init();



			$('.standard_refund_fields').hide();

			$('.partial_refund_fields').hide();

			$('.order_action').show();

            showSuccessMessage('#'+id_order+' : '+data.warning);      

        }else{

            showErrorMessage('#'+id_order+' : '+data.warning);

        } 

    }

    

    $(document).on('click', '.OrderSubmitEditPayment', function() {

        rel = $(this).attr('rel');

        idorder = $(this).data('idorder');

        datapost = 'OrderSubmitEditPayment=true&idorder='+idorder+'&paymentid='+rel+'&';

        datapost += $('#edit_payment_information_'+rel).find('input:visible, select:visible').serialize();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: datapost,

        })

        .done(function(data) {

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                if($('.tr_order_'+idorder).length > 0)

                    $('.tr_order_'+idorder).next('.tr_custom_data').find('.payment_table').html(data.payment);

                if($('.order_view_page').length > 0)

                    $('#formAddPaymentPanel .payment_table').html(data.payment);

                reloadAllDatetimepicker();

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    });

    

    

    $(document).on('click', '#submitMessage2', function() {

        datapost = 'addMessage=1&'+$('#messages form').serialize();

        $.ajax({

          type : 'POST', 

          url : currentIndex+'&token='+token,

          dataType : "json",                     

          data: datapost,

        })

        .done(function(data) {

            if(!data.haserror)

            {

                showSuccessMessage(data.warning);

            }else{

                showErrorMessage(data.warning);

            } 

        });

        return false;

    });

    

    

    

    $(document).on('click', 'button[name="submitNewVoucher"]', function() {

        datapost = 'OrderNewVoucher=true&idorder='+id_order+'&';

        datapost += $('#voucher_form').find('input:visible, select:visible').serialize();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: datapost,

        })

        .done(function(data) {

            updateAllBlook(data);

        });

        return false;

    });

    

    

    

    

    $(document).on('click', '.delete_edit_discount_link', function() {

        rel = $(this).attr('rel');

        if(confirm(remove_confirm))

        {

            $.ajax({

              type : 'POST', 

              dataType : "json",                     

              data: 'OrderDeleteDiscount=true&idorder='+id_order+'&id_order_cart_rule='+rel,

            })

            .done(function(data) {

                updateAllBlook(data);

            });

        }

        return false;

    });

    

    

    $(document).on('click', '.save_edit_discount_link', function() {

        rel = $(this).attr('rel');

        discount_name = $('#edit_discount_name_'+rel).val();

        discount_price = $('#edit_discount_price_'+rel).val();

        if(discount_name == ''){

            $('#edit_discount_name_'+rel).focus();

            return false;

        }

        if(discount_price == ''){

            $('#edit_discount_price_'+rel).focus();

            return false;

        }

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderEditDiscount=true&idorder='+id_order+'&id_order_cart_rule='+rel+'&discount_name='+discount_name+'&discount_price='+discount_price,

        })

        .done(function(data) {

            updateAllBlook(data);

        });

        return false;

    });

    var gloading_html = '<span class="lds-dual-ring-w"><span class="lds-dual-ring"></span></span>';

    $('.ggenerate_ex_password').click(function(){

        var randomstring = Math.random().toString(36).slice(-4)+Math.random().toString(36).slice(-4);

        $('.duplicate_reference').val(randomstring.toUpperCase());

        return false;

    });

    $(document).on('click', '.generate_ex_reference', function() {

        var randomstring = Math.random().toString(36).slice(-4)+Math.random().toString(36).slice(-5);

        $(this).closest('.edit_order_reference').find('.order_reference').val(randomstring.toUpperCase());

        return false;

    });





    





    $(document).on('click', '.box_duplicate_save', function() {

        $('.gpopup_duplicate_content_loading').html(gloading_html);

		$.ajax({

			type:"POST",

            dataType:'json',

			url: currentIndex+'&token='+token,

			data : 'OrderDuplicateAndGetCart=true&idorder='+idorder+'&id_order='+idorder,

			success : function(res)

			{

				if(res.error == 0)

                {

                    window.open(res.addcartlink, '_blank');

                    $('.g_ordermanager_popup_wp').removeClass('active');

                }else{

                    showErrorMessage('#'+idorder+' : '+res.warning);

                }

			}

		});

        return false;

    });

    $(document).on('click', '.change_order_customer', function() {

        $('.gpopup_duplicate_content_loading').html(gloading_html);

		$.ajax({

			type:"POST",

            dataType:'json',

			url: currentIndex+'&token='+token,

			data : 'OrderDuplicateAndChangeCustomer=true&idorder='+idorder+'&id_order='+idorder+'&id_customer='+$(this).attr('data-customer'),

			success : function(res)

			{

				if(res.error == 0)

                {

                    window.open(res.addcartlink, '_blank');

                    $('.g_ordermanager_popup_wp').removeClass('active');

                }else{

                    showErrorMessage('#'+idorder+' : '+res.warning);

                }

			}

		});

        return false;

    });

    $(document).on('click', '.duplicateorder', function() {

        idorder = $(this).attr('rel');

        order_field_reference = '';

        if($('.tr_order_'+idorder+' .order_field_reference').length > 0){

            order_field_reference = $('.tr_order_'+idorder+' .order_field_reference').text();

            order_field_reference = order_field_reference.trim();

        }

        $('.box_grid_duplicate_order_wp').addClass('active');

        if(order_field_reference !='') $('.duplicate_order_ref').html(order_field_reference);

        else $('.duplicate_order_ref').html(idorder);

        return false;

	});

    

    

    $(document).on('click', '#desc-order-invoice,#desc-order-delivery,#desc-order-csv,.desc-order-invoice,.desc-order-delivery,.desc-order-csv,.desc-order-print,.print_order_link,#desc-order-print,#desc-order-csv-csv1,#desc-order-csv-csv2', function() {    

        submitaction = $(this).attr('href');

        $(this).attr('disable','disable');

        

        idorders = '';

        if($(this).hasClass('print_order_link'))

        {

            idorders = $(this).attr('rel')+'-';

        }else

        $('input[name="orderBox[]"]').each(function () {

            if(this.checked)

                idorders = idorders+$(this).val()+'-';

        });

        if(idorders !='')

        {

            url = currentIndex+'&token='+token_admin_ordermanager+'&'+submitaction+'=true&idorders='+idorders.slice(0,-1);

            window.open(url, '_blank');

        }else{

            alert(requied_choose);

        }

        $(this).removeAttr('disable');

        return false;

    })

    

    $(document).on('change', '.shipping_cost_incl', function() {

        idorder = $(this).attr('rel');

        cost_incl = $(this).val();

        taxrate = $('#tax_rate_'+idorder).val();

        shipping_cost = cost_incl/(1+taxrate/100);

        $('#shipping_cost_excl_'+idorder).val(shipping_cost.toFixed(2));

    })

    $(document).on('change', '.shipping_cost_excl', function() {

        idorder = $(this).attr('rel');

        cost_excl = $(this).val();

        taxrate = $('#tax_rate_'+idorder).val();

        shipping_cost = cost_excl*(1+taxrate/100);

        $('#shipping_cost_incl_'+idorder).val(shipping_cost.toFixed(2));

    })

    $(document).on('change', '.order_tax_rate', function() {

        idorder = $(this).attr('rel');

        cost_excl = $('#shipping_cost_excl_'+idorder).val();

        taxrate = $('#tax_rate_'+idorder).val();

        shipping_cost = cost_excl*(1+taxrate/100);

        $('#shipping_cost_incl_'+idorder).val(shipping_cost.toFixed(2));

    })

    /*

    $(document).on('click', 'td.order_field_reference', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_reference').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_reference').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    });

    $(document).on('click', 'td.order_field_osname', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_status').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_status').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    })

    

    $(document).on('click', 'td.order_field_cname', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_cname').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_cname').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    });

    $(document).on('click', 'td.order_field_customer', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_customer').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_customer').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    })

    $(document).on('click', 'td.orderreference', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        if($('.change_reference').css('display') !='none')

        {

            $('.change_reference').stop(true,true).slideUp(500);

        }else{

            $('.change_reference').stop(true,true).slideDown(500);

        }

        return false;

    });

    

    

    

    $(document).on('click', 'td.order_field_payment', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_payment').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_payment').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    })

    $(document).on('click', 'td.order_field_date_add', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_date').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_date').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    })

    */

    $(document).on('click', '.order_field_viewproduct', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'),true);

        /*

            tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

            if(tr_custom_data.find('.viewproduct').css('display') !='none')

            {

                $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            }else{

                $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

                tr_custom_data.find('.viewproduct').addClass('oditempopup').stop(true,true).slideDown(500);

            }

        */

        return false;

    })

    $(document).on('click', 'span.orderreference', function() {

        if($('.change_reference').css('display') !='none')

        {

            $('.change_reference').stop(true,true).slideUp(500);

        }else{

            $('.change_reference').stop(true,true).slideDown(500);

        }

        return false;

    });

    $(document).on('click', '.order_field_caname', function() {

        if($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').length > 0)

            loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        tr_custom_data = $(this).parents('tr').next('.tr_custom_data');

        if(tr_custom_data.find('.change_carrier').css('display') !='none')

        {

            $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

        }else{

            $('.tr_custom_data  > td > .panel').removeClass('oditempopup').stop(true,true).slideUp(500);

            tr_custom_data.find('.change_carrier').addClass('oditempopup').stop(true,true).slideDown(500);

        }

        return false;

    })

    

    $(document).on('click', '.edit_shipping_link', function() {

        rel = $(this).attr('rel');

        if($('#'+rel).css('display') !='none')

        {

            $('#'+rel).stop(true,true).slideUp(500);

        }else{

            $('.editshippinglink').stop(true,true).slideUp(500);

            $('#'+rel).slideDown(500);

        }

        return false;

    })

    

    $(document).on('click', '.open_payment_information2', function() {

        rel = $(this).data('id');

        if($('#payment_information_'+rel).css('display') != 'none')

        {

            $('#payment_information_'+rel).stop(true,true).slideUp(500);

        }else{

            $('.payment_information').stop(true,true).slideUp(500);

            $('#payment_information_'+rel).stop(true,true).slideDown(500);

        }

        return false;

    })

    

    

    $(document).on('change', '.change_product_attribute', function() {

    

        id = $(this).attr('rel');

        productname = $('#productname_'+id).val();

        combinationname = $("#add_product_product_attribute_id_"+id+" option:selected").text();

        $('#productname'+id).val(productname+' - '+combinationname);

    });

    

    $(document).on('click', '.gclose_popup', function() {

        $('.tr_custom_data > td > .panel').removeClass('oditempopup').stop(true,true).css('display','none');

        return false;

    });

    

    $(document).on('click', '.delete_status_history', function() {

        if(confirm(remove_confirm))

        {

            idorder = $(this).data('idorder');

            idhistory = $(this).data('history');

            $.ajax({

              type : 'POST', 

              dataType : "json",                     

              data: 'OrderRemoveHistory=true&idorder='+idorder+'&history='+idhistory,

            })

            .done(function(data) {

                warning = '';

                if(!data.haserror)

                {

                    showSuccessMessage('#'+idorder+' : '+data.warning);

                    $('.order_history_'+idorder+'_'+idhistory).remove();

                    if($('.tr_order_'+idorder+' .order_field_osname').length > 0)

                        $('.tr_order_'+idorder+' .order_field_osname').html(data.state);     

                    if($('.order_history_'+idorder+'_'+data.current_history).length > 0)   

                        $('.order_history_'+idorder+'_'+data.current_history).children('td').css('background-color',data.backgroud_current_history).css('color',data.color_current_history);           

                }else{

                    showErrorMessage('#'+idorder+' : '+data.warning);

                } 

            });

        }

        return false;

    })

    

    

    $(document).on('click', '.OrderSubmitAddPayment', function() {

        idorder = $(this).data('idorder');

        payment_method = $(this).parents('tr.current-edit').find('input[name="payment_method"]').val();

        payment_date = $(this).parents('tr.current-edit').find('input[name="payment_date"]').val();

        payment_transaction_id = $(this).parents('tr.current-edit').find('input[name="payment_transaction_id"]').val();

        payment_amount = $(this).parents('tr.current-edit').find('input[name="payment_amount"]').val();

        payment_currency = $(this).parents('tr.current-edit').find('select[name="payment_currency"]').val();

        payment_invoice = $(this).parents('tr.current-edit').find('select[name="payment_invoice"]').val();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderAddPayment=true&idorder='+idorder+'&payment_method='+payment_method+'&payment_date='+payment_date+'&payment_transaction_id='+payment_transaction_id+'&payment_amount='+payment_amount+'&payment_currency='+payment_currency+'&payment_invoice='+payment_invoice,

        })

        .done(function(data) {

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                

                if($('.tr_order_'+idorder).length > 0)

                    $('.tr_order_'+idorder).next('.tr_custom_data').find('.payment_table').html(data.payment);

                if($('.order_view_page').length > 0)

                    $('#formAddPaymentPanel .payment_table').html(data.payment);

                reloadAllDatetimepicker();

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    

    

    $(document).on('click', '.delete_payment_information', function() {

        if(confirm(remove_confirm))

        {

            idorder = $(this).data('idorder');

            id_payment = $(this).data('id');

            $.ajax({

              type : 'POST', 

              dataType : "json",                     

              data: 'OrderDeletePayment=true&idorder='+idorder+'&idpayment='+id_payment,

            })

            .done(function(data) {

                warning = '';

                if(!data.haserror)

                {

                    showSuccessMessage('#'+idorder+' : '+data.warning);

                    $('.tr_order_'+idorder).next('.tr_custom_data').find('.payment_table').html(data.payment);

                    if($('.order_view_page').length > 0)

                        $('#formAddPaymentPanel .payment_table').html(data.payment);

                    reloadAllDatetimepicker();

                }else{

                    showErrorMessage('#'+idorder+' : '+data.warning);

                } 

            });

        }

        return false;

    })

    $(document).on('click', 'button[name="submitUpdateOrderStatus"]', function() {

        orders = '';

        $('input[name="orderBox[]"]').each(function () {

            if(this.checked)

                orders = orders+$(this).val()+'-';

                

        });

        state = $('#id_order_state').val();

        if(orders !=''){

            $.ajax({

              type : 'POST', 

              dataType : "json",                     

              data: 'OrderChangeStates=true&orders='+orders+'&state='+state,

            })

            .done(function(data) {

               $.each(data.haserror, function( index, value ) {

                    showErrorMessage(data.warning[index]);

               });

                $.each(data.payment_history, function( index, value ) {

                    showSuccessMessage(data.warning[index]);

                    $('.tr_order_'+index+' .order_field_osname').html(data.state[index]);

                    $('.tr_order_'+index+' .order_field_id_pdf').html(data._print_pdf_icon[index]);

                    $('.tr_order_'+index).next('.tr_custom_data').find('.order_history_table').html(data.payment_history[index]);

               });

            });

        }else{

            alert(requied_choose);

        }

      return false;

    })

    

    $(document).on('click', '.OrderChangeCarrier', function() {

        idorder = $(this).attr('rel');

        idcarrier = $('#carrier_'+idorder).val();

        shipping_cost_incl = $('#shipping_cost_incl_'+idorder).val();

        shipping_cost_excl = $('#shipping_cost_excl_'+idorder).val();

        tax_rate = $('#tax_rate_'+idorder).val();

        shipping_weight = $('#shipping_weight_'+idorder).val();

        tracking_number = $('#tracking_number_'+idorder).val();

        orderlist='1';

        if($(this).hasClass('orderpage')) orderlist = '0';

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderChangeCarrier=true&orderlist='+orderlist+'&idorder='+idorder+'&idcarrier='+idcarrier+'&shipping_cost_incl='+shipping_cost_incl+'&shipping_cost_excl='+shipping_cost_excl+'&tax_rate='+tax_rate+'&shipping_weight='+shipping_weight+'&tracking_number='+tracking_number,

        })

        .done(function(data) {

            warning = '';

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                if(orderlist == '0')

                {

                    $('.panel-total').html(data.order_total);

                    $('#orderchangeshipping_wp').html(data.orderchangeshipping);

                    $('#formAddPaymentPanel').html(data.payment);

                }else{

                    $('.tr_order_'+idorder+' .order_field_caname').html(data.carriername);

                    $('.tr_order_'+idorder+' .order_field_total_paid_tax_incl').html(data.total_paid);

                    $('.tr_order_'+idorder).next('.tr_custom_data').find('.panel-total').html(data.order_total);

                }

                

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    $(document).on('click', '.resend_email', function() {

        idorder = $(this).data('idorder');

        state = $(this).data('state');

        _history = $(this).data('history');

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderResendEmail=true&idorder='+idorder+'&state='+state+'&history='+_history,

        })

        .done(function(data) {

            warning = '';

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    $(document).on('click', '.submitChangeState', function() {

        idorder = $(this).attr('rel');

        state = $('#id_order_state_'+idorder).val();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderChangeState=true&idorder='+idorder+'&state='+state,

        })

        .done(function(data) {

            warning = '';

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                if($('.tr_order_'+idorder).length > 0)

                {

                    $('.tr_order_'+idorder+' .order_field_osname').html(data.state);

                    $('.tr_order_'+idorder+' .order_field_id_pdf').html(data._print_pdf_icon);

                    $('.tr_order_'+idorder).next('.tr_custom_data').find('.order_history_table').html(data.payment_history);

                }

                if($('.order_view_page').length > 0)

                    $('.order_history_table').html(data.payment_history);

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    $(document).on('click', '.OrderChangeDateAdd', function() {

        idorder = $(this).attr('rel');

        dateadd = $('#dateadd_'+idorder).val();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderChangeDateAdd=true&idorder='+idorder+'&dateadd='+dateadd,

        })

        .done(function(data) {

            warning = '';

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                $('.tr_order_'+idorder+' .order_field_date_add').html(data.dateadd);

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    

    

    

    $(document).on('click', '.submitSentCustomerNote', function() {

        idorder = $(this).data('notecontent');

        customerId = $(this).data('customer');

    	var noteContent = $('#noteContent_'+idorder).val();

        if(noteContent !=''){

        	var data = 'token=' + token_admin_customers + '&tab=AdminCustomers&ajax=1&action=updateCustomerNote&id_customer=' + customerId + '&note=' + encodeURIComponent(noteContent);

        	$.ajax({

        		type: "POST",

        		url: "index.php",

        		data: data,

        		async : true,

        		success: function(r) {

        			showSuccessMessage(update_success_msg);

        		}

        	});

        }

        return false;

    });

    $(document).on('click', '.submitAddressShipping', function() {

        idorder = $(this).data('id_order');

        type = $(this).data('type');

        id_address = $('#'+type+'_id_address_'+idorder).val();

        $.ajax({

          type : 'POST', 

          dataType : "json",                     

          data: 'OrderChangeAddress=true&idorder='+idorder+'&type='+type+'&id_address='+id_address,

        })

        .done(function(data) {

            warning = '';

            if(!data.haserror)

            {

                showSuccessMessage('#'+idorder+' : '+data.warning);

                $('#'+type+'_address_text_'+idorder).html(data.address);

                if (typeof invoice_marker != 'undefined')

                {

                    invoice_marker.setMap(null);

                    geocoder.geocode({

        				address: data.address_map

        				}, function(results, status) {

        				if (status === google.maps.GeocoderStatus.OK)

        				{

        				    maptype= 'invoice';

        				    if(type == 'shipping') maptype= 'delivery';

                            map = new google.maps.Map(document.getElementById('map-'+maptype+'-canvas-'+idorder), {

        						zoom: 10,

        						mapTypeId: google.maps.MapTypeId.ROADMAP,

        						center: results[0].geometry.location

        					});

                            invoice_marker.setMap(map);

                            var invoice_marker2 = new google.maps.Marker({

        						map: map,

        						position: results[0].geometry.location,

        						url: ''

        					});

                            x = map.getZoom();

                			c = map.getCenter();

                			google.maps.event.trigger(map, 'resize');

                			map.setZoom(x);

                			map.setCenter(c);

                            

        				}

        			});

                

                }

                if(type == 'shipping') 

                    //$('.tr_order_'+idorder+' .order_field_cname').html('<div class="max_height_box">'+data.address+'</div>');

                    $('.tr_order_'+idorder+' .order_field_cname').html(data.country);

            }else{

                showErrorMessage('#'+idorder+' : '+data.warning);

            } 

        });

        return false;

    })

    $(document).on('click', '.OrderChangeReference', function() {

        idorder = $(this).attr('rel');

        reference = $('#reference_'+idorder).val();

        button_click = $(this);

        $('.alert.alert-danger').remove();

        $('.alert.alert-success').remove();

        if(reference !='' && reference.length <=9 ){

            $.ajax({

              type : 'POST', 

              dataType : "json",                     

              data: 'OrderChangeReference=true&idorder='+idorder+'&reference='+reference,

            })

            .done(function(data) {

                warning = '';

                if(!data.haserror)

                {

                    if($('.tr_order_'+idorder+' .order_field_reference').length > 0) $('.tr_order_'+idorder+' .order_field_reference').html(reference);

                    if($('.orderreference').length > 0) $('.orderreference').html(reference);

                    showSuccessMessage('#'+idorder+' : '+data.warning);

                }else{

                    showErrorMessage('#'+idorder+' : '+data.warning);

                    $('#reference_'+idorder).focus();

                } 

            });

        }else{

            showErrorMessage('#'+idorder+' : '+content_invalid);

            $('#reference_'+idorder).focus();

        }

        return false;

    });

    $('button.submitProductChange2').unbind('click').live('click',function(e) {

    /* $(document).on('click', 'button.submitProductChange2', function() { */





		if ($(this).closest('tr.product-line-row').find('td .edit_product_quantity').val() <= 0)

		{

			jAlert(txt_add_product_no_product_quantity);

			return false;

		}

		if ($(this).closest('tr.product-line-row').find('td .edit_product_price').val() <= 0)

		{

			jAlert(txt_add_product_no_product_price);

			return false;

		}

		if (confirm(txt_confirm))

		{

		    id = $(this).attr('rel');

            var form_data = '';

            if($('.customization_file_'+id).length > 0)

            {

    		    var form_data = new FormData();

                

                $('.customization_file_'+id).each(function(){

                    rel = $(this).attr('rel');

                    $.each($(this)[0].files, function(i, file) {

                        form_data.append('file['+rel+']', file);

                    });

                });

            }

            

			var element = $(this);

			var element_list = $('.customized-' + $(this).parent().parent().find('.edit_product_id_order_detail').val());

            

            

			

            query = '&ajax=1&action=editProductOnOrder&id_order='+id_order+'&';

			

            if (element_list.length)

            {

                temp = 0;

                element_list.each(function(){

                    temp++;

                    if(temp != 1) query += '&';

                    query += $(this).find('input:visible, select:visible, .edit_product_id_order_detail').serialize();

                })

            }

			//	query += element_list.parent().parent().find('input:visible, select:visible, .edit_product_id_order_detail').serialize();

			else

				query += element.parent().parent().find('input:visible, select:visible, .edit_product_id_order_detail').serialize();

            if($('.customization_text_'+id).length > 0)

                query +='&'+$('.customization_text_'+id).serialize();

                

                

			$.ajax({

				url: admin_ordermanager_tab_link+ query,

				dataType: 'json',

                type: 'POST',

                data: form_data,

                mimeType: "multipart/form-data",

                contentType: false,

                cache: false,

                processData: false,

				success : function(data)

				{

					if (data.result)

					{

						refreshProductLineView(element, data.view);

						updateAmounts(data.order);

						updateInvoice(data.invoices);

						updateDocuments(data.documents_html);

						updateDiscountForm(data.discount_form_html);



						// Initialize all events

						init();



						$('.standard_refund_fields').hide();

						$('.partial_refund_fields').hide();

						$('.add_product_fields').hide();

						$('.row-editing-warning').hide();

						$('td.product_action').attr('colspan', 3);

                        $('.order_action').show();

					}

					else

						jAlert(data.error);

				}

			});

		}



		return false;

	});

    $(document).on('click', '.edit_product_change_link2', function() {

		$('.add_product_fields, .standard_refund_fields, .order_action').hide();

		$('.edit_product_fields').show();

		$('.row-editing-warning').hide();

		$('.cancel_product_change_link:visible').trigger('click');

		closeAddProduct();

		var element = $(this);

		$.ajax({

			type: 'POST',

			url: admin_ordermanager_tab_link,

			cache: false,

			dataType: 'json',

			data : {

				ajax: 1,

				token: token,

				action: 'loadProductInformation',

				id_order_detail: element.closest('tr.product-line-row').find('input.edit_product_id_order_detail').val(),

				id_address: id_address,

				id_order: id_order

			},

			success : function(data)

			{

				if (data.result)

				{

					current_product = data;



					var element_list = $('.customized-' + element.parents('.product-line-row').find('.edit_product_id_order_detail').val());

					if (!element_list.length)

					{

						element_list = element.parents('.product-line-row');

						element_list.find('td .product_quantity_show').hide();

						element_list.find('td .product_quantity_edit').show();

					}

					else

					{

						element_list.find('td .product_quantity_show').hide();

						element_list.find('td .product_quantity_edit').show();

					}

					element_list.find('td .product_price_show').hide();

					element_list.find('td .product_price_edit').show();

					element_list.find('td.cancelCheck').hide();

					element_list.find('td.cancelQuantity').hide();

					element_list.find('td.product_invoice').show();

					$('td.product_action').attr('colspan', 3);

					$('th.edit_product_fields').show();

					$('th.edit_product_fields').attr('colspan',  2);

					element_list.find('td.product_action').attr('colspan', 1);

					element.parent().children('.edit_product_change_link2').parent().hide();

					element.parent().parent().find('button.submitProductChange').show();

					element.parent().parent().find('.cancel_product_change_link').show();



					if (+data.reduction_percent != +0)

						element_list.find('.row-editing-warning').show();



					$('.standard_refund_fields').hide();

					$('.partial_refund_fields').hide();

				}

				else

					jAlert(data.error);

			}

		});

	});

    $(document).on('click', '.delete_product_line2', function() {

		if (!confirm(txt_confirm))

			return false;

		var tr_product = $(this).closest('.product-line-row');

		var id_order_detail = $(this).closest('.product-line-row').find('td .edit_product_id_order_detail').val();

		var query = 'ajax=1&action=deleteProductLine&ajaxProcessDeleteProductLine=1&id_order_detail='+id_order_detail+'&id_order='+id_order;



		$.ajax({

			type: 'POST',

			url: admin_ordermanager_tab_link,

			cache: false,

			dataType: 'json',

			data : query,

			success : function(data)

			{

				if (data.result)

				{

					tr_product.fadeOut('slow', function() {

					   if($(this).hasClass('customized')){

					       $('.customized-'+id_order_detail).remove();

					   }

						$(this).remove();

					});

					updateAmounts(data.order);

					updateInvoice(data.invoices);

					updateDocuments(data.documents_html);

					updateDiscountForm(data.discount_form_html);

				}

				else

					jAlert(data.error);

			}

		});

	});

    if($("#add_product_product_name2").length > 0)

    $("#add_product_product_name2").autocomplete(admin_ordermanager_tab_link,

		{

			minChars: 3,

			max: 10,

			width: 500,

			selectFirst: false,

			scroll: false,

			dataType: "json",

			highlightItem: true,

			formatItem: function(data, i, max, value, term) {

				return value;

			},

			parse: function(data) {

				var products = new Array();

				if (typeof(data.products) != 'undefined')

					for (var i = 0; i < data.products.length; i++)

						products[i] = { data: data.products[i], value: data.products[i].name };

				return products;

			},

			extraParams: {

				ajax: true,

				token: token,

				action: 'searchProducts',

				id_lang: id_lang,

				id_currency: id_currency,

				id_address: id_address,

				id_customer: id_customer,

				product_search: function() { return $('#add_product_product_name2').val(); }

			}

		}

	)

	.result(function(event, data, formatted) {

		if (!data)

		{

			$('tr#new_product input, tr#new_product select').each(function() {

				if ($(this).attr('id') != 'add_product_product_name')

					$('tr#new_product input, tr#new_product select, tr#new_product button').attr('disabled', true);

			});

		}

		else

		{

			$('tr#new_product input, tr#new_product select, tr#new_product button').removeAttr('disabled');

			// Keep product variable

			current_product = data;

			$('#add_product_product_id').val(data.id_product);

			$('#add_product_product_name2').val(data.name);

			$('#add_product_product_price_tax_incl').val(data.price_tax_incl);

			$('#add_product_product_price_tax_excl').val(data.price_tax_excl);

			addProductRefreshTotal();

			if (stock_management)

				$('#add_product_product_stock').html(data.stock[0]);



			if (current_product.combinations.length !== 0)

			{

				// Reset combinations list

				$('select#add_product_product_attribute_id').html('');

				var defaultAttribute = 0;

				$.each(current_product.combinations, function() {

					$('select#add_product_product_attribute_id').append('<option value="'+this.id_product_attribute+'"'+(this.default_on == 1 ? ' selected="selected"' : '')+'>'+this.attributes+'</option>');

					if (this.default_on == 1)

					{

						if (stock_management)

							$('#add_product_product_stock').html(this.qty_in_stock);

						defaultAttribute = this.id_product_attribute;

					}

				});

				// Show select list

				$('#add_product_product_attribute_area').show();



				populateWarehouseList(current_product.warehouse_list[defaultAttribute]);

			}

			else

			{

				// Reset combinations list

				$('select#add_product_product_attribute_id').html('');

				// Hide select list

				$('#add_product_product_attribute_area').hide();



				populateWarehouseList(current_product.warehouse_list[0]);

			}

		}

	});

     $('#submitAddProduct2').unbind('click').click(function(e) { 

        

    /* $(document).on('click', '#submitAddProduct2', function() { */

		stopAjaxQuery();

		var go = true;



		if ($('input#add_product_product_id').val() == 0)

		{

			jAlert(txt_add_product_no_product);

			go = false;

		}



		if ($('input#add_product_product_quantity').val() == 0)

		{

			jAlert(txt_add_product_no_product_quantity);

			go = false;

		}



		if ($('input#add_product_product_price_excl').val() == 0)

		{

			jAlert(txt_add_product_no_product_price);

			go = false;

		}



		if (go)

		{

			if (parseInt($('input#add_product_product_quantity').val()) > parseInt($('#add_product_product_stock').html()))

				go = confirm(txt_add_product_stock_issue);



			if (go && $('select#add_product_product_invoice').val() == 0)

				go = confirm(txt_add_product_new_invoice);



			if (go)

			{

				var query = 'ajax=1&token='+token+'&action=addProductOnOrder&id_order='+id_order+'&';



				query += $('#add_product_warehouse').serialize()+'&';

				query += $('tr#new_product select, tr#new_product input').serialize();

				if ($('select#add_product_product_invoice').val() == 0)

					query += '&'+$('tr#new_invoice select, tr#new_invoice input').serialize();



				var ajax_query = $.ajax({

					type: 'POST',

					url: admin_ordermanager_tab_link,

					cache: false,

					dataType: 'json',

					data : query,

					success : function(data) {

						if (data.result)

						{

							if (data.refresh)

							{

								location.reload();

								return;

							}

							go = false; 

							addViewOrderDetailRow(data.view);

							updateAmounts(data.order);

							updateInvoice(data.invoices);

							updateDocuments(data.documents_html);

							updateShipping(data.shipping_html);

							updateDiscountForm(data.discount_form_html);



							// Initialize all events

							init();



							$('.standard_refund_fields').hide();

							$('.partial_refund_fields').hide();

							$('.order_action').show();

						}

						else

							jAlert(data.error);

					},

					error : function(XMLHttpRequest, textStatus, errorThrown) {

						jAlert("Impossible to add the product to the cart.\n\ntextStatus: '" + textStatus + "'\nerrorThrown: '" + errorThrown + "'\nresponseText:\n" + XMLHttpRequest.responseText);

					}

				});

				ajaxQueries.push(ajax_query);

			}

		}

	});

    $(document).on('click', '#submitOrderNote', function() {

        noteval = $('#order_note').val();

        id_gordernotes = $('#id_gordernotes').val();

        showinpdf =  $('input[name="ordernotepdf"]:checked').val();

        if(noteval !=''){

            var query = 'ajax=1&addOrderNote=1&token='+token+'&action=addOrderNote&id_order='+id_order+'&noteval='+noteval+'&showinpdf='+showinpdf+'&id_gordernotes='+id_gordernotes;

            $('#submitOrderNote').slideUp(0);

            $.ajax({

				type: 'POST',

				url: admin_ordermanager_tab_link,

				cache: false,

				dataType: 'json',

				data : query,

				success : function(data) {

				    $('#submitOrderNote').slideDown(500);

					if (!data.error)

					{

					   html_notes = '';

					   $.each(data.datas,function(key,val){

					       html_notes += '<tr id="note_'+val.id_gordernotes+'"><td class="noteval">'+val.notes+'</td>';

                           html_notes += '<td><input type="checkbox" name="showinpdf['+val.id_gordernotes+']" '+(val.showinpdf == '1' ? ' checked="checked" ' : '' )+' value="1" /></td>';

                           html_notes += '<td>'+val.date_add+'</td>';

                           html_notes += '<td><button class="editordernote btn btn-default" rel="'+val.id_gordernotes+'"><i class="icon-pencil"></i></button><button class="deleteordernote btn btn-default" rel="'+val.id_gordernotes+'"><i class="icon-trash"></i></button></td></tr>';

					   });

					  $('.order-note-history .table tbody').html(html_notes);

                      showSuccessMessage('#'+id_order+' : '+data.warning);

					}else{

					   showErrorMessage('#'+id_order+' : '+data.warning);

					}

                } 

            });

        }

        return false;

        

    });

    $(document).on('click', '.deleteordernote', function() {

        id_gordernotes = $(this).attr('rel');

        if(id_gordernotes > 0){

            var query = 'ajax=1&deleteOrdernote=1&token='+token+'&action=deleteOrdernote&id_order='+id_order+'&id_gordernotes='+id_gordernotes;

            $.ajax({

				type: 'POST',

				url: admin_ordermanager_tab_link,

				cache: false,

				dataType: 'json',

				data : query,

				success : function(data) {

					if (!data.error)

					{

					   html_notes = '';

					   $.each(data.datas,function(key,val){

					       html_notes += '<tr id="note_'+val.id_gordernotes+'"><td class="noteval">'+val.notes+'</td>';

                           html_notes += '<td><input type="checkbox" name="showinpdf['+val.id_gordernotes+']" '+(val.showinpdf == '1' ? ' checked="checked" ' : '' )+' value="1" /></td>';

                           html_notes += '<td>'+val.date_add+'</td>';

                           html_notes += '<td><button class="editordernote btn btn-default" rel="'+val.id_gordernotes+'"><i class="icon-pencil"></i></button><button class="deleteordernote btn btn-default" rel="'+val.id_gordernotes+'"><i class="icon-trash"></i></button></td></tr>';

					   });

					  $('.order-note-history .table tbody').html(html_notes);

                      showSuccessMessage('#'+id_order+' : '+data.warning);

					}else{

					   showErrorMessage('#'+id_order+' : '+data.warning);

					}

                }

            });

        }

        return false;

        

    });

    $(document).on('click', '.editordernote', function() {

        rel = $(this).attr('rel');

        if($('#note_'+rel).length > 0){

            noteval = $('#note_'+rel).find('.noteval').html();

            showinpdf = $('input[name="showinpdf['+rel+']"]:checked').val();

            $('#order_note').val(noteval);

            $('input[name="ordernotepdf"]').removeAttr('checked');

            if(showinpdf)

                $('#ordernotepdf_on').prop("checked", true);

            else $('#ordernotepdf_off').prop("checked", true);

            $('#id_gordernotes').val(rel);

            $('.order-note-form').addClass('editnote');

        }

        return false;

    });

    $(document).on('click', '#cancel_editnote', function() {

        $('#order_note').val('');

        $('#id_gordernotes').val('');

        $('.order-note-form').removeClass('editnote');

        return false;

    });

    $(document).on('click', '.box_extra_showinclose', function() {

        $(this).closest('.order_extra_box').toggleClass('active');

		return false;

    });

    $(document).on('click', '.ordermanager_list_action .gquickview_btn .edit', function() {

        loadOrderExtraData($(this).parents('tr').next('.tr_custom_data').find('.order_extra_box').data('idorder'));

        return false;

    });

    $('#desc-order-changegrid').click(function(){

        if($('.box_setting_showin_box').length > 0) $('.box_setting_showin_box').addClass('active');

        return false;

    });

    $(document).on('click', '.box_setting_showin_save', function() {

        var datas = {};

        datas['ajax'] = 1;

        datas['action'] = 'updateGridConfig';

        datas['updateGridConfig'] = '1';

        datas['token'] = token;



        var order_fields = {};

        $('.order_field_config:checked').each(function(){

            order_fields[$(this).attr('rel')] = 1;

        });

        datas['order_fields'] = order_fields;

        $.ajax({

            type: 'POST',

            cache: false,

            dataType: 'json',

            data : datas,

            success : function(data) {

                console.log(data);

                if (!data.error)

                {

                    window.location.href = window.location.href;

                }else{

                   showErrorMessage(data.warning);

                }

            }

        });

        return false;

    });



    function searchDuplicateCustomer(keyword){

        $.ajax({

			type:"POST",

			async: true,

			dataType: "json",

			data : {

				ajax: "1",

				'searchCustomers': 1,

				customer_search: keyword},

        success : function(res)

        {

            if(res.found)

            {

                var html = '';

                $.each(res.customers, function() {

                    html += '<div class="customerCard col-lg-4">';

                    html += '<div class="panel">';

                    html += '<div class="panel-heading">'+this.firstname+' '+this.lastname;

                    html += '<span class="pull-right">#'+this.id_customer+'</span></div>';

                    html += '<span>'+this.email+'</span><br/>';

                    html += '<span class="text-muted">'+((this.birthday != '0000-00-00') ? this.birthday : '')+'</span><br/>';

                    html += '<div class="panel-footer">';

                    html += '<a target="_blank" href="'+this.customer_link+'" class="btn btn-default"><i class="icon-search"></i> Details</a>';

                    html += '<button type="button" data-customer="'+this.id_customer+'" class="change_order_customer btn btn-default pull-right"><i class="icon-arrow-right"></i> Choose</button>';

                    html += '</div>';

                    html += '</div>';

                    html += '</div>';

                });

            }

            else

                html = '<div class="alert alert-warning"></div>';

            $('#customers').html(html);

        }

        });

    }

    var duplicate_searchtimeout = null;

    $('.duplicate_search_customer').keyup(function() {

      clearTimeout(duplicate_searchtimeout);

      duplicate_searchtimeout = setTimeout(() => {

        var keyword = $(this).val().trim();

        if(keyword == ''){

            clearTimeout(duplicate_searchtimeout);

        } 

        else{

            searchDuplicateCustomer(keyword);

        }

      }, 500);

    });

})