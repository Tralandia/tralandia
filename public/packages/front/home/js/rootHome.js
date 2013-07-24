$(function(){
	$('.rootCountriesSidebar span.parent').click(function(){		
		$('ul.children.'+$(this).data('for')).toggleClass('hide');
	});
});