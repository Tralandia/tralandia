$(function(){
	
	$('body').popover({
		selector: '#navbarTabContent li:not(.placeholder)',
		trigger: 'hover',
		title: function(){
			return $(this).attr('data-name').replace(/~star~/g, '<i class="icon-star"></i>');
		},
		content: function(){

			var info = {
				title: $(this).attr('data-title'),
				link: $(this).attr('data-link'),
				thumb: $(this).attr('data-thumb'),
				location: $(this).attr('data-location'),
				price: $(this).attr('data-price'),
				capacity: $(this).attr('data-capacity')
			};

			return '<img src="'+info.thumb+'" /><p><span class="location">'+info.location+'</span></br>'+info.price+'&nbsp;|&nbsp;'+info.capacity+'</p>';	

		},
		delay: {show: 500, hide: 0},
		animation: false,
		placement: 'bottom'
	});

});