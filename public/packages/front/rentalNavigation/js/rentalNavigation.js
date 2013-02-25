$(function(){
	

			$('#objectDetailNavBar li a').popover({
				title: function(){
					return 'strinf';
				},
				trigger: 'hover',
				content: function(){

						var info = {
							title: $(this).attr('data-title'),
							thumb: $(this).attr('data-thumb'),
							location: $(this).attr('data-location'),
							price: $(this).attr('data-price'),
							capacity: $(this).attr('data-capacity')
						};

						var c = '<img src="'+info.thumb+'" /><p>'+info.location+'</br>'+info.price+' '+info.capacity+'</p>';	
										
					return c;
				},
				delay: {show: 250, hide: 0},
				animation: false,
				placement: 'bottom'
			});



});