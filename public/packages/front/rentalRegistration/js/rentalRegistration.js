$(function(){
	$('#frm-registrationForm-country').on('change',function(){
		var locationRedirect = $('#frm-registrationForm-country option[value="'+$(this).val()+'"]').attr('data-redirect');
		window.location = locationRedirect;
	});	
	$('#frm-registrationForm-language').on('change',function(){
		var locationRedirect = $('#frm-registrationForm-language option[value="'+$(this).val()+'"]').attr('data-redirect');
		window.location = locationRedirect;
	});
});
