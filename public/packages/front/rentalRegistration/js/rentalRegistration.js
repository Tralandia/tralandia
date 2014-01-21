
// rental edit and refistration
$(function(){


	$('select.rentalType').on('change',function(){
		_rentalTypeClassification(this);
	});

	$('select.selectRedirect').each(function(){
		resetSelectRedirect(this);
	});

	$('select.selectRedirect').on('change',function(){

		var id = $(this).attr('id');
		var locationRedirect = $('#'+id+' option[value="'+$(this).val()+'"]').attr('data-redirect');

		if(window.location.href.toString() != locationRedirect){
			window.location = locationRedirect;
		}

	});

	rentalTypeOnLoad();


});

function _rentalTypeClassification(elem){

	var classification = $('select.rentalType  option[value="'+$(elem).val()+'"]').attr('data-classification');
	// var classification = $('select.rentalType  option[value="'+$(elem).val()+'"]').html();

	// console.log($(elem).val());


//	if(typeof classification != 'undefined'){
//		$('.classification').show();
//	} else {
//		$('.classification').hide();
//	}
}

function rentalTypeOnLoad(){
	$('select.rentalType').each(function(){
		_rentalTypeClassification(this);
	});
}



function resetSelectRedirect(elem){
	var id = '#'+$(elem).attr('id');
	var val = $('select'+id+' option[selected]').val();
	$('select'+id).select2('val',val);
}




