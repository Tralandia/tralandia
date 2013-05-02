$(function(){
	
// $("#geocomplete").geocomplete();    

// navigator.geolocation.getCurrentPosition(GetLocation);
// function GetLocation(location) {
//     alert(location.coords.latitude);
//     alert(location.coords.longitude);
//     alert(location.coords.accuracy);
// }

	$("#geocomplete")
	  .geocomplete()
	  .bind("geocode:result", function(event, result){
		console.log(result);
	});


	$('.addToFavorites').on('click',function(){
	   $(this).toggleClass('active'); 
	});
	
	
	
	$('.changeCountry').on('click',function(){
		$('#country').focus();
		return false;
	});


	$('.reservationform').on('submit',function(){
		$(this).find('button').addClass('active');
		// return false;
	});
	
	$('.back').click(function(){
		window.history.back();
		return false;
	});

	$('#getMyLocation').myPositionButton();
	$('.select2').selectPlaceholder();
});




(function($){
		$.selectPlaceholder = function(el, options){

				var base = this;
				
				base.$el = $(el);
				base.el = el;

				base.$el.data("selectPlaceholder", base);
				
	

				base.init = function()
				{
					base.$el.find('option:first').html(base.$el.attr('data-placeholder'));					
				};
				  

				base.init();
		};

		$.fn.selectPlaceholder = function(options){
				return this.each(function(){
						(new $.selectPlaceholder(this, options));});
		};
		
})(jQuery);




(function($){
	$.myPositionButton = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;


		base.$el.data("myPositionButton", base);
		
		base.init = function(){
			base.bind();
		};
		
		base.bind = function(){
			base.$el.click(base.getLocation);
		}

		base.getLocation = function(){
			navigator.geolocation.getCurrentPosition(base.GetLocation);			
		}

		base.GetLocation = function(location) {
			// console.log(location.coords.latitude);
			// console.log(location.coords.longitude);
			// console.log(location.coords.accuracy);
			base.setCookie(location.coords.latitude,location.coords.longitude);
		}

		base.setCookie = function(latitude,longitude){
			$.cookie('latitude', latitude);
			$.cookie('longitude', longitude);
		}

		base.init();
	};

	
	$.fn.myPositionButton = function(options){
		return this.each(function(){
			(new $.myPositionButton(this, options));});
	};
	
})(jQuery);

