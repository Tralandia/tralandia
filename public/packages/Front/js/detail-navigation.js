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
							type: $(this).attr('data-type'),
							location: $(this).attr('data-location')
						};

						var c = '<img src="'+info.thumb+'" /><b>'+info.type+' </b><br/><b>'+info.location+'</b>';	
										
					return c;
				},
				delay: 500,
				placement: 'bottom'
			});



});