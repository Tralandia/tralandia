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
	
//    $(document).on('scroll',function(){
//        
//        if($(this).scrollTop() > 56){			
//                $('#subnav').addClass('fix');
//                $('#subnavPlaceholder').removeClass('hide'); 
//        } else {
//                $('#subnav').removeClass('fix');
//                $('#subnavPlaceholder').addClass('hide'); 
//        }
//       
//    });

	$('#getMyLocation').myPositionButton();
	
});


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
			console.log(location.coords.latitude);
			console.log(location.coords.longitude);
			console.log(location.coords.accuracy);
		}

		base.init();
	};

	
	$.fn.myPositionButton = function(options){
		return this.each(function(){
			(new $.myPositionButton(this, options));});
	};
	
})(jQuery);

