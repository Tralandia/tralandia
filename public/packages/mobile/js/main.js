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

	
	$('.addToFavorites').addToFavorites();
	
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

	$.addToFavorites = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;

		base.$el.data("addToFavorites", base);
		base.favCookieName = 'favoritesList';
		base.id = base.$el.attr('rel');

		base.init = function()
		{
			base.bind();
		};

		base.bind = function(){

			base.$el.on('click',function(){
				
				if(base.$el.hasClass('active')){
					base.$el.removeClass('active');
					base.removeFromFavorites();
				} else {
					base.addToFavorites();
					base.$el.addClass('active');
				}

				// console.log(base.getCookieArray());

				return false;

			});
		}

		base.removeFromFavorites = function(){

			var newCookies = [];

			$.each(base.getCookieArray(), function(index, value) {
				if(value != base.id){
					newCookies.push(value);
				}
			});

			$.cookie(base.favCookieName,newCookies.join());

		}

		base.addToFavorites = function(){
			var exist = base.getCookieArray();
				exist.push(base.id);
				$.cookie(base.favCookieName,exist.join());

		}

		base.getCookieArray = function(){
			return $.cookie(base.favCookieName).split(',');
		}
		  

		base.init();
	};

	$.fn.addToFavorites = function(options){return this.each(function(){(new $.addToFavorites(this, options));});};
		
})(jQuery);


// nastavi placeholdery nakolko nemame select2 v mob. verzii
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

