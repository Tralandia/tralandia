

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
