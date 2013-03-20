$(function(){

	// $('#frm-registrationForm-country').on('change',function(){
	// 	var locationRedirect = $('#frm-registrationForm-country option[value="'+$(this).val()+'"]').attr('data-redirect');
	// 	window.location = locationRedirect;
	// });

	// $('#frm-registrationForm-language').on('change',function(){
	// 	var locationRedirect = $('#frm-registrationForm-language option[value="'+$(this).val()+'"]').attr('data-redirect');
	// 	window.location = locationRedirect;
	// });

	$('.rentalType select').on('change',function(){
		var clasification = $('.rentalType select option[value="'+$(this).val()+'"]').attr('data-classification');
		if(typeof clasification != 'undefined'){
			console.log(clasification);
			$('.classification').show();
		} else {
			$('.classification').hide();
		}
	});

});


