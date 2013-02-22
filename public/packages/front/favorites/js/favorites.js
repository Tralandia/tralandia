
// showFavoriteSlider
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




/* set selected css class to Rental list objects and Rental detail object */

(function($){
	$.favoriteActiveLinks = function( el, appObject, options ){

		var base = this;
				
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("favoriteActiveLinks", base);
		
		base.init = function(){
			base.options = $.extend({},$.favoriteActiveLinks.defaultOptions, options);      
		};
		
		base.init();
	};
	
	$.favoriteActiveLinks.defaultOptions = {
		
	};
	
	$.fn.favoriteActiveLinks = function( appObject , options ){
		return this.each(function(){

			(new $.favoriteActiveLinks(this, options));
			
			var list = $.cookie('favoritesList');

			if(typeof list != 'undefined'){
				list = list.split(',');
			} else {
				list = false;
			}			


			if(list){
				var currentId = parseInt($(this).attr('rel'));

				if(appObject.in_array(list,currentId)){
					
					$(this).addClass('selected');
				} 
			} 

		});
	};
	
})(jQuery);



// neviem ci sa pouziva treba testnut
(function($){
    $.favoriteSlider = function( el, appObject, options ){

        var base = this;
                
        base.$el = $(el);
        base.el = el;
        
        base.$el.data("favoriteSlider", base);
        
        base.init = function(){
            base.options = $.extend({},$.favoriteSlider.defaultOptions, options);      
        };
        
        base.init();
    };
    
    $.favoriteSlider.defaultOptions = {
        
    };
    
    $.fn.favoriteSlider = function( appObject , options ){
        return this.each(function(){

            (new $.favoriteSlider(this, options));
            
            console.log('s tebou me bavi svet ');
            $(this).css('backround','red');


            var $leftArrow = $(this).find('div.header');
                $leftArrow.css('backround','red');
        });
    };
    
})(jQuery);