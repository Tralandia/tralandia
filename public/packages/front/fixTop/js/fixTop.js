(function($){
	$.fixTop = function(el, radius, options){
		var base = this;
		
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("fixTop", base);
		
		base.init = function(){
			base.elTopOffset = base.$el.offset().top;
			base.options = $.extend({},$.fixTop.defaultOptions, options);
			base._bind();
		};

		base._bind = function(){
			$(document).scroll(function(){

				if(parseInt($(this).scrollTop()) > base.elTopOffset) {
					console.log($(this).scrollTop());
					base.$el.addClass('fix');
				} else {
					// base.$el.css({
					// 	position: 'relative'
					base.$el.removeClass('fix');
				}
				
			});
		};
		
		base.init();
	};
	
	$.fixTop.defaultOptions = {
	};
	
	$.fn.fixTop = function(options){
		return this.each(function(){
			(new $.fixTop(this, options));

		});
	};
	
})(jQuery);

$(function(){
	console.log('tusome');
	$('.fixTop').fixTop();
});