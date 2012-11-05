

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




/* register listeners */
$(document).ready(function(){

	$('.toogle').click(uiToogleClick);

});