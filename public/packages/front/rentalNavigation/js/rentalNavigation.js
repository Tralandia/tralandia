$(function(){
	
	$('body').popover({
		selector: '#navbarTabContent li:not(.placeholder)',
		title: function(){
			return 'strinf';
		},
		trigger: 'hover',
		content: function(){

				var info = {
					title: $(this).attr('data-title'),
					link: $(this).attr('data-link'),
					thumb: $(this).attr('data-thumb'),
					location: $(this).attr('data-location'),
					price: $(this).attr('data-price'),
					capacity: $(this).attr('data-capacity')
				};

				var c = '<img src="'+info.thumb+'" /><p><span class="location">'+info.location+'</span></br>'+info.price+'&nbsp;|&nbsp;'+info.capacity+'</p>';	
								
			return c;
		},
		delay: {show: 500, hide: 0},
		animation: false,
		placement: 'bottom'
	});

});