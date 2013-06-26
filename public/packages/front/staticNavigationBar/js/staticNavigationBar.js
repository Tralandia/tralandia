// static navigation bar


(function($){
	$.staticNavigationBar = function(el, radius, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("staticNavigationBar", base);
		
		base.init = function(){

			
			base.options = $.extend({},$.staticNavigationBar.defaultOptions, options);

		};		

		base.init();
	};
	
	$.staticNavigationBar.defaultOptions = {
		radius: "20px"
	};
	
	$.fn.staticNavigationBar = function(radius, options){
		return this.each(function(){(new $.staticNavigationBar(this, options));});
	};
	
})(jQuery);