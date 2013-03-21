$(function(){

	$('select.selectRedirect').each(function(){
		resetSelectRedirect(this);
	});

	$('select.selectRedirect').on('change',function(){
		
		var id = $(this).attr('id');
		var locationRedirect = $('#'+id+' option[value="'+$(this).val()+'"]').attr('data-redirect');
		window.location = locationRedirect;

	});

	rentalTypeOnLoad();

	$('.rentalType select').on('change',function(){
		_rentalTypeClasification(this);
	});

});

function _rentalTypeClasification(elem){
	var clasification = $('.rentalType select option[value="'+$(elem).val()+'"]').attr('data-classification');
	if(typeof clasification != 'undefined'){
		$('.classification').show();
	} else {
		$('.classification').hide();
	}		
}

function rentalTypeOnLoad(){
	$('.rentalType select').each(function(){
		_rentalTypeClasification(this);
	});
}



function resetSelectRedirect(elem){
	var id = '#'+$(elem).attr('id');
	var val = $('select'+id+' option[selected]').val();
	$('select'+id).select2('val',val);	
}


