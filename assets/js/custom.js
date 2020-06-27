$(document).ready(function() {

    var siteurl = $('.siteurl').val();

    $('.regnr').editable();
    $('.kontonr').editable();

    if($('.disableChained').length == 0){
    	$("#gbs").chained("#devices");
    }



    $('.datepicker').datepicker()

    $('.selectpicker').select2();

    $('.bulkCheck').click(function(){
	   if($(this).is(":checked")){
		   $(this).closest('tr').css('background','#C4504D');
	   }else{
		   $(this).closest('tr').css('background','#e5443f');
	   }
    });

    $('#devices').change(function(){

	   if($("#devices option:selected").attr('controlprice') == 1 && $('input[name=bought_from_company]').is(':checked') == false){
		   //$('input[name=price]').attr('type','hidden');
		   //$('.pricearea').show();
	   }else{
		   //$('input[name=price]').attr('type','text');
		   //$('input[name=price]').val('');
		   //$('.pricearea').html('Prisen vil fremgå her når model, GB og tilstand er valgt');
		   //$('.pricearea').hide();
	   }

    });

    $('input[name=bought_from_company]').click(function(){
	    if($('input[name=bought_from_company]').is(':checked') == true){
		   $('input[name=price]').attr('type','text');
		   $('input[name=price]').val('');
		   $('.pricearea').html('Prisen vil fremgå her når model, GB og tilstand er valgt');
		   $('.pricearea').hide();
	    }else{
		   $('input[name=price]').attr('type','hidden');
		   $('.pricearea').show();
	    }
    });



    $('input[name=splitPayment]').click(function(){
	    if($('input[name=splitPayment]').is(':checked') == true){
		   $('input[name=split_cash]').attr('required',true);
		   $('input[name=split_cash]').val('');

		   $('input[name=split_card]').attr('required',true);
		   $('input[name=split_card]').val('');

		   $('.splitPaymentArea').show();
	    }else{
		   $('input[name=split_cash]').attr('required',false);
		   $('input[name=split_cash]').val('');

		   $('input[name=split_card]').attr('required',false);
		   $('input[name=split_card]').val('');

		   $('.splitPaymentArea').hide();
	    }
    });


    $('input[name=panserBox]').click(function(){
	    if($('input[name=panserBox]').is(':checked') == true){
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price+200;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }else{
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price-200;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }
    });

    $('input[name=headsetBox]').click(function(){
	    if($('input[name=headsetBox]').is(':checked') == true){
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price+149;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }else{
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price-149;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }
    });

    $('input[name=beskyttelseBox]').click(function(){
	    if($('input[name=beskyttelseBox]').is(':checked') == true){
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price+199;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }else{
		   var price = parseInt($('input[name=price]').val());
		   var newprice = price-199;
		   $('input[name=price]').val(newprice);
		   $('.pricearea').html(newprice+' kr');
	    }
    });

    $('input[name=insuranceBox]').click(function(){
	    if($('input[name=insuranceBox]').is(':checked') == true){
			$('.insuranceDataBox').show();
			$('input[name=insurancePrice]').attr('required',true);
			$('select[name=insurance_years]').attr('required',true);
	    }else{
		    $('.insuranceDataBox').hide();
		    $('input[name=insurancePrice]').attr('required',false);
			$('select[name=insurance_years]').attr('required',false);
	    }
    });

    /*$('.calculateBoughtPriceBasedOnValues').change(function(){

	   var phone = $("#devices option:selected").val();
	   var gb    = $("#gbs option:selected").val();
	   var condition = $("select[name=condition] option:selected").val();

	   if(phone > 0 && gb > 0 && condition > 0){
		   $.ajax({
			   type: "POST",
			   url: siteurl+"bought/calculatePrice",
			   data: { phone: phone, gb: gb, condition: condition }
			}).done(function( msg ) {
				$('.pricearea').html(msg+' kr');
				$('input[name=price]').val(msg);
			});

	   }

    });*/

    $('input[name=discount_amount]').keyup(function(){

	   var price = $('input[name=price]').val();

	   var discount = $(this).val()/100;

	   var new_price = price*discount;

    });

    $('.calculateSoldPriceBasedOnValues').change(function(){

       var order_id = $(".order_device_info_hidden").val();
	   var condition = $("select[name=condition] option:selected").val();

	   if(condition != '' && order_id > 0){
		   $.ajax({
			   type: "POST",
			   url: siteurl+"sold/calculatePrice",
			   data: { order_id: order_id, condition: condition }
			}).done(function( msg ) {
				if(msg == 0){
					$('input[name=price]').attr('type','text');
				    $('input[name=price]').val('');
				    $('.pricearea').html('Prisen vil fremgå her når model, GB og tilstand er valgt');
				    $('.pricearea').hide();
				    $('.addPanser').hide();
				    $('.addInsurance').hide();
				}else{

					$('input[name=price]').attr('type','text');
					$('.pricearea').show();

					$('.pricearea').html(msg+' kr');
					$('input[name=price]').val(msg);
					$('.addPanser').show();
					$('.addInsurance').show();
				}
			});

	   }

    });


    $('input[name=only_create]').click(function(){

		if($(this).is(':checked')){
			$('select[name=payment_type]').attr('required',false);
			$('input[name=price]').attr('required',false);
		}else{
			$('select[name=payment_type]').attr('required',true);
			$('input[name=price]').attr('required',true);
		}

	});

	$('.working-btn').click(function(){

		if($(this).hasClass('start')){
			var type = 'start';
			$('.working-btn.start').hide();
			$('.working-btn.stop').show();
		}else{
			var type = 'stop';
			$('.working-btn.stop').hide();
			$('.working-btn.start').show();
		}

		$.ajax({
			type: "POST",
			url: siteurl+"timer/action",
			data: { type: type }
		}).done(function( msg ) {

		});


	});


	$('#edit_part').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var id = button.data('id') // Extract info from data-* attributes
	  var device = button.data('device');

	  $.ajax({
		  type: "POST",
		  url: siteurl+"products/edit_inventory/"+device+"",
		  data: { id: id}
		}).done(function( msg ) {
		  $('#edit_part .modal-body').html(msg);
		});


	});


	$('#transfer_part').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var id = button.data('id') // Extract info from data-* attributes
	  var device = button.data('device');

	  $.ajax({
		  type: "POST",
		  url: siteurl+"products/transfer_part/"+device+"",
		  data: { id: id}
		}).done(function( msg ) {
		  $('#transfer_part .modal-body').html(msg);
		  getAmountTransfer();
		});


	});

	$('.showDetailsTimer').click(function(){

		var id = $(this).attr('data-id');

		if($('.show_more_'+id).is(':hidden')){
			$(this).html('Skjul detaljeret');
		}else{
			$(this).html('Vis detaljeret');
		}

		$('.show_more_'+id).slideToggle('fast');

		return false;

	});


    $('.checkIfNewAccess').change(function(){
	    if($(this).val() == 'new_access'){
		    $('.createNewAccessWrapper').slideDown('fast');
		    $('.newAccessName').attr('required',true);
		    $('.newAccessPrice').attr('required',true);
	    }else{
		    $('.createNewAccessWrapper').slideUp('fast');
		    $('.newAccessName').attr('required',false);
		    $('.newAccessPrice').attr('required',false);
	    }
    })

    $('.alreadyTestedAdmin').click(function(){

	   $('input[name=alreadyTested]').val(1);

	   $('#getDeviceInfo').submit();

    });

	$('#getDeviceInfo').submit(function(){

	   $('input[name=exchangeId]').val('');
	   $('input[name=exchangePrice]').val('');
	   $('input[name=exchangeBoughtPrice]').val('');

	   var order_id = $('.orderid').val();
	   var alreadyTested = $('input[name=alreadyTested]').val();

	   $('.loader_order').show();
	   $('.already_sold').hide();
	   $('.no_order_found').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"sold/getInfo",
	      data: { order_id: order_id, alreadyTested: alreadyTested }
	   }).done(function( msg ) {

	   	   $('.order_device_info_hidden').val(order_id);
	   	   $('input[name=price]').attr('min',Math.round(msg.price));

	   	   $('.loader_order img').hide();
	   	   $('.already_tested_admin').hide();
	   	   $('.no_test_found').hide();

	   	   if(!msg){
		   	   $('.sell_unit').hide();
		   	   $('.no_order_found').show();
		   	   $('.no_test_found').hide();
	   	   }else if(msg.tested == false){
	   	   	   $('.sell_unit').hide();
	   	   	   $('.no_order_found').hide();
		   	   $('.no_test_found').show();

		   	   if(msg.admin == true){
			   	   $('.already_tested_admin').show();
		   	   }else{
			   	   $('.already_tested_admin').hide();
		   	   }

	   	   }else{

		       if(msg.sold == 1){
			       $('.sell_unit').hide();
			       $('.no_test_found').hide();
				   $('.no_order_found').hide();
				   $('.already_sold').show();
		       }else{

			       $('select[name=model]').val(msg.product_id);
			       $('select[name=gb]').val(msg.gb_id);
			       $('input[name=imei]').val(msg.imei);
			       $('input[name=color]').val(msg.color);
			       $('input[name=bought_order_id]').val(msg.id);

			       $('.sell_unit').fadeIn('fast');

		       }

	       }

	   });


	   return false;

    });


    $('.exchangePhone').click(function(){

	   var exhangeId = $('input[name=exchange_id]').val();

	   $.ajax({
		   type: "POST",
		   url: siteurl+"sold/getExchangeInfo",
		   data: { id: exhangeId }
		}).done(function( msg ) {
			if(msg){
				var sold_price = $('input[name=price]').val();
				$('.exchangeTable').show();
				$('.exchangeSoldPrice').html(sold_price+' kr');
				$('.exchangePrice').html('-'+msg.price+' kr');

				var totalPrice = parseFloat(sold_price)-parseFloat(msg.price);

				$('.totalExchangePrice').html(totalPrice+' kr');

				$('input[name=exchangeId]').val(msg.id);
				$('input[name=exchangePrice]').val(totalPrice);
				$('input[name=exchangeBoughtPrice]').val(msg.price);

			}else{
				$('.exchangeTable').hide();
				$('input[name=exchangeId]').val('');
				$('input[name=exchangePrice]').val('');
				$('input[name=exchangeBoughtPrice]').val('');
				alert('Ingen telefoner fundet');
			}

			return false;
		});



    });

    $('.exchangePhoneCheckbox').click(function(){

	   if($(this).is(':checked')){
		   $('.exchangePhoneArea').show();
	   }else{
		   $('.exchangePhoneArea').hide();
	   }

    });

    $('#edit_user').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"users/edit",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContent').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });


    $('#edit_device_phone').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"products/edit",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContent').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });



    $('#tested').on('shown.bs.modal', function (event) {

	   $('#tested .modal-body').html('<center><img src="/assets/images/loader.gif" /></center>');

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"bought/tested",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('#tested .modal-body').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });


    $('#edit_boutique').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"boutiques/edit",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContent').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });


    $('#edit_access').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"access/edit",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContent').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });


    $('#edit_permission').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContent').html('');
	   $('.editContent').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"users/editPermission",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContent').html(msg);

	       $('.editContent').fadeIn('fast');

	   });


	   return false;

    });



    $('#edit_sold_device').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.loader').show();
	   $('.editContentSold').html('');
	   $('.editContentSold').hide();

	   $.ajax({
	      type: "POST",
	      url: siteurl+"sold/editDevice",
	      data: { id: id }
	   }).done(function( msg ) {

	   	   $('.loader img').hide();

	   	   $('.editContentSold').html(msg);

	       $('.editContentSold').fadeIn('fast');

	       payment_type();

	   });


	   return false;

    });


	$('#reason_for_creditnote').on('shown.bs.modal', function (event) {

	   var button = $(event.relatedTarget) // Button that triggered the modal
   	   var id = button.data('id')

	   $('.cancelCreditLineID').val(id);

    });


    $('.confirm').click(function(){

	   if(!confirm('Er du sikker?')){
		   return false;
	   }

    });

    payment_type();


    $('.change_redirect').change(function(){

	   var val = $(this).val();

	   top.location.href = val;

	   return false;

    });


    $('.change_click').click(function(){

	   var val = $(this).attr('url');

	   top.location.href = val;

	   return false;

    });


    $('#edit_device').on('shown.bs.modal', function (event) {

   		var button = $(event.relatedTarget) // Button that triggered the modal
   		var id = button.data('id')

   		$.ajax({
	      type: "POST",
	      url: siteurl+"bought/edit",
	      data: { id: id }
	    }).done(function( msg ) {
	    	$('#edit_device .modal-body').html(msg);
	    	$("#gbs_edit").chained("#devices_edit");
	    	exchange();
	    });

	});



	$('#transfer').on('shown.bs.modal', function (event) {

   		var button = $(event.relatedTarget) // Button that triggered the modal
   		var id = button.data('id')

   		$.ajax({
	      type: "POST",
	      url: siteurl+"bought/transfer",
	      data: { id: id }
	    }).done(function( msg ) {
	    	$('#transfer .modal-body').html(msg);
	    });

	});



	$('#parts_used').on('shown.bs.modal', function (event) {

   		var button = $(event.relatedTarget) // Button that triggered the modal
   		var id = button.data('id')

   		$.ajax({
	      type: "POST",
	      url: siteurl+"bought/parts_used",
	      data: { id: id }
	    }).done(function( msg ) {
	    	$('#parts_used .modal-body').html(msg);
	    	parts_used();
	    	remove_part_from_list();
	    });

	});

	$('.end_day_box').click(function(){

		$('.logout_end_day').fadeIn('fast');

	});


	exchange();


	$('.choose_all').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });


    $( ".calculate_diff" ).keyup(function() {

		var kasseKort   		= parseFloat($('.kasseKort').val());

		var dankort     		= parseFloat($('.manuelDankort').val().replace(",", '.'));
		var manuelDanskeECMCVI 	= parseFloat($('.manuelDanskeECMCVI').val().replace(",", '.'));
		var manuelECMCVIJBC 	= parseFloat($('.manuelECMCVIJBC').val().replace(",", '.'));
		var manuelGebyr 		= parseFloat($('.manuelGebyr').val().replace(",", '.'));

		var formel              = (dankort+manuelDanskeECMCVI+manuelECMCVIJBC-manuelGebyr)-kasseKort;

		if(dankort > 0){
			$('.manuelDankort').closest('.form-group').addClass('has-success');
			$('.manuelDankort').closest('.form-group').find('.form-control-feedback').show();
		}else{
			$('.manuelDankort').closest('.form-group').removeClass('has-success');
			$('.manuelDankort').closest('.form-group').find('.form-control-feedback').hide();
		}


		if(manuelDanskeECMCVI > 0){
			$('.manuelDanskeECMCVI').closest('.form-group').addClass('has-success');
			$('.manuelDanskeECMCVI').closest('.form-group').find('.form-control-feedback').show();
		}else{
			$('.manuelDanskeECMCVI').closest('.form-group').removeClass('has-success');
			$('.manuelDanskeECMCVI').closest('.form-group').find('.form-control-feedback').hide();
		}


		if(manuelECMCVIJBC > 0){
			$('.manuelECMCVIJBC').closest('.form-group').addClass('has-success');
			$('.manuelECMCVIJBC').closest('.form-group').find('.form-control-feedback').show();
		}else{
			$('.manuelECMCVIJBC').closest('.form-group').removeClass('has-success');
			$('.manuelECMCVIJBC').closest('.form-group').find('.form-control-feedback').hide();
		}


		if(manuelGebyr > 0){
			$('.manuelGebyr').closest('.form-group').addClass('has-success');
			$('.manuelGebyr').closest('.form-group').find('.form-control-feedback').show();
		}else{
			$('.manuelGebyr').closest('.form-group').removeClass('has-success');
			$('.manuelGebyr').closest('.form-group').find('.form-control-feedback').hide();
		}

		if(formel < 0){
			$('.cardResultManuel').addClass('negativeResult');
			$('.cardResultManuel').removeClass('positiveResult');
		}else{
			$('.cardResultManuel').addClass('positiveResult');
			$('.cardResultManuel').removeClass('negativeResult');
		}

		$('.cardResultManuel').html(formel);

	});


	$( ".calculate_diff_cash" ).keyup(function() {

		var kasseKontant   	= parseInt($('.kasseKontant').val());

		var halvore     		= parseInt($('.manuelCash50ore').val());

		if(halvore == 0){
			var halvore = 0;
		}else if(halvore < 2){
			var halvore       = 0.5;
		}else{
			var halvore       = halvore/2;
		}


		var enkr 				= parseInt($('.manuelCash1kr').val());
		var tokr 				= parseInt($('.manuelCash2kr').val())*2;
		var femkr 				= parseInt($('.manuelCash5kr').val())*5;

		var tikr 				= parseInt($('.manuelCash10kr').val())*10;
		var tyvekr 				= parseInt($('.manuelCash20kr').val())*20;
		var halvkr 				= parseInt($('.manuelCash50kr').val())*50;

		var hundkr 				= parseInt($('.manuelCash100kr').val())*100;
		var tohundkr 			= parseInt($('.manuelCash200kr').val())*200;
		var femhundkr 			= parseInt($('.manuelCash500kr').val())*500;

		var tusindkr 			= parseInt($('.manuelCash1000kr').val())*1000;

		var formel          = (halvore+enkr+tokr+femkr+tikr+tyvekr+halvkr+hundkr+tohundkr+femhundkr+tusindkr)-kasseKontant;

		if(formel < 0){
			$('.cashResultManuel').addClass('negativeResult');
			$('.cashResultManuel').removeClass('positiveResult');
		}else{
			$('.cashResultManuel').addClass('positiveResult');
			$('.cashResultManuel').removeClass('negativeResult');
		}

		$('.cashResultManuel').html(formel);

	});

});


function exchange(){
	$('.exchangePrice').click(function(){

		if($(this).is(':checked')){
			$('.reg_account_area').slideUp('fast');
			$('input[name=reg_nr]').attr('required',false);
			$('input[name=account_nr]').attr('required',false);
		}else{
			$('.reg_account_area').slideDown('fast');
			$('input[name=reg_nr]').attr('required',true);
			$('input[name=account_nr]').attr('required',true);
		}

	});
}

function payment_type(){
	$(document).on('change','select[name=payment_type],select.payment_type',function(){
	   $('.order_id_webshop').hide();
	   $('.name_number').hide();

	   var value = $(this).val();

	   $('.invoice_informations input[name=company_name]').attr('required',false);
   	   $('.invoice_informations input[name=buyer_name]').attr('required',false);
   	   $('.invoice_informations input[name=cvr]').attr('required',false);

	   if(value == 'cash'){
		   // show checkboks
		   $(this).parents('.payment_method').find('.hidden_checkboks').show();
       $(this).parents('.payment_method').find('.hidden_checkboks input').val($(this).parents('.payment_method').index());
	   }else{
		   $(this).parents('.payment_method').find('.hidden_checkboks').hide();
		   $(this).parents('.payment_method').find('.hidden_checkboks input').prop('checked',false);
	   }

	   if(value == 'cash' || value == 'card' || value == 'mobilepay' || value == 'loan'){
		   $('.name_number').show();
		   //$('.name_number input[name=buyer_name]').attr('required',true);
		   //$('.name_number input[name=number]').attr('required',true);

		   $('.invoice_informations').hide();
		   $('.order_id_webshop input[name=order_id]').attr('required',false);
	   }else if(value == 'invoice'){
	   	   $('.invoice_informations').fadeIn('fast');

	   	   $('.invoice_informations input[name=company_name]').attr('required',true);
	   	   $('.invoice_informations input[name=buyer_name]').attr('required',true);
	   	   $('.invoice_informations input[name=cvr]').attr('required',true);

	   }else if(value == 'webshop' || value == 'nettalk'){
		   $('.order_id_webshop').show();
		   $('.invoice_informations').hide();
		   $('.order_id_webshop input[name=order_id]').attr('required',true);

		   $('.name_number input[name=buyer_name]').attr('required',false);
		   $('.name_number input[name=number]').attr('required',false);
	   }

    });
}

function parts_used(){
	$('#choosed_part').change(function(){

		var id = $(this).val();
		var value = $('#choosed_part').find(":selected").attr('raw-text');
		var price = $('#choosed_part').find(":selected").attr('price');

		$('.parts_used_area').append('<div class="added_part_wrapper"><div class="remove_added_part" style="width: 200px; float: left;">'+value+'</div>\
				<div style="width: 150px; float: left">'+price+' kr</div>\
				<div style="width: 50px; float: left"><a href="#" class="remove_part_from_list">Slet</a></div>\
			<input type="hidden" name="used_parts[]" value="'+id+'" /></div>');

		remove_part_from_list();

		return false;

	});
}

function remove_part_from_list(){
	$('.remove_part_from_list').click(function(){
		$(this).closest('.added_part_wrapper').remove();

		return false;

	});
}


function getAmountTransfer(){

	var siteurl = $('.siteurl').val();

	$('#getAmount').submit(function(){

		$('.pleasewait_wrap').show();

		$.ajax({
			type: "POST",
			url: siteurl+"products/transfer_part_amount/"+$('.hiddenProductId').val()+"",
			data: { from: $('.fromTransfer').val(), to: $('.toTransfer').val(), id: $('.hiddenUniqueString').val() }
		}).done(function( msg ) {
			$('.transferAmountContent').html(msg);
			$('.pleasewait_wrap').hide();
		});


		return false;

	});

}


$(document).ready(function() {
		$("#accordian a").click(function(e) {

				var link = $(this);
				var closest_ul = link.closest("ul");
				var parallel_active_links = closest_ul.find(".active")
				var closest_li = link.closest("li");
				var link_status = closest_li.hasClass("active");
				var count = 0;
        /*
				closest_ul.find("ul").slideUp(function() {
						if (++count == closest_ul.find("ul").length)
								parallel_active_links.removeClass("active");
				});
        */
        if($(this).parent('li').children('ul').length){
          e.preventDefault();
        }
				if (!link_status) {

						closest_li.children("ul").slideDown();
						closest_li.addClass("active");
				}
		});

    $("#accordian li ul li").each(function(index){
      if($(this).hasClass('active')){
        $(this).parents('li').addClass('active');
      }
    });


})
