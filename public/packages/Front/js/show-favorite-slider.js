
(function($){
	$.showFavoriteSlider = function( el, appObject, options ){

		var base = this;
				
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("showFavoriteSlider", base);
		
		base.init = function(){
			base.options = $.extend({},$.showFavoriteSlider.defaultOptions, options);      
		};
		

		base.init();
	};
	
	$.showFavoriteSlider.defaultOptions = {
		
	};
	

	$.fn.showFavoriteSlider = function( appObject , options ){
		return this.each(function(){

			(new $.showFavoriteSlider(this, options));
			
			this.list = $(this).find('ul');

					$rigthArrow = $('#favorites-right-button');
					$lefthArrow = $('#favorites-left-button');

					var $innerrList = this.list;

					var speed = 500;


					var ulWidth = $innerrList.width();


					$rigthArrow.click(function(){
						
						var leftOffset = parseInt($innerrList.css('left').replace('px','').replace('auto','0'));


						var delta = (ulWidth - 692 + leftOffset);

						console.log(leftOffset);
						console.log(delta);

						if(delta >0){
							$innerrList.animate({left:  '-=180'}, 800);
						}

						

  										    

					});

					$lefthArrow.click(function(){
						
						$innerrList.animate({left: '+=180'}, 800);

					}); 

		});
	};
	
})(jQuery);
