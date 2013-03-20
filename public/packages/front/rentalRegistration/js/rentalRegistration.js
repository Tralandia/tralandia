$(function(){

	$('select.selectRedirect').each(function(){
		resetSelectRedirect(this);
	});

	$('select.selectRedirect').on('change',function(){
		
		var id = $(this).attr('id');
		var locationRedirect = $('#'+id+' option[value="'+$(this).val()+'"]').attr('data-redirect');
		window.location = locationRedirect;

	});

	$('.rentalType select').on('change',function(){
		var clasification = $('.rentalType select option[value="'+$(this).val()+'"]').attr('data-classification');
		if(typeof clasification != 'undefined'){
			$('.classification').show();
		} else {
			$('.classification').hide();
		}
	});

});

function resetSelectRedirect(elem){
	var id = '#'+$(elem).attr('id');
	var val = $('select'+id+' option[selected]').val();
	$('select'+id).select2('val',val);	
}


