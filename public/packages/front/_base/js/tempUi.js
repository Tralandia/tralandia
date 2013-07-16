$(function(){
	console.log('temp mode');

	$(document).scroll(function(){
		console.log();

		if($('body').scrollTop() > 99){
			$('#socialIcons').css({
				position: 'fixed',
				top:'0px',
				'margin-left': '811px',
				'border-radius': '0 0 5px 5px'
			});
		} else {
			$('#socialIcons').css({
				position: 'relative',
				top: '-20px',
				'margin-left':0,
				'border-radius': '5px'
			});
		}
	});
});

