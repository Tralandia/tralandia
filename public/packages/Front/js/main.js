

function uiToogleClick(){
	
	var span  = $(this).find('span');

	var forClass = '#'+$(this).attr('for');

	if($(this).hasClass('active')){
		$(forClass).slideUp();
		$(this).removeClass('active');
	} else {
		$(forClass).slideDown();
		$(this).addClass('active');
	}

	return false;
}


function buttonCompareClick(){
	var child = $(this).find('span');
	
	if($(child).hasClass('entypo-star')){
		$(child).removeClass('entypo-star');
		$(child).addClass('entypo-ok');
	} else {
		$(child).removeClass('entypo-ok');
		$(child).addClass('entypo-star');
	}
	
}

/* register listeners */
$(document).ready(function(){

	$('.toogle').click(uiToogleClick);
	$('.btn-compare').click(buttonCompareClick);
	
	$( ".tabs" ).tabs();
	$( ".datepicker" ).datepicker();


});