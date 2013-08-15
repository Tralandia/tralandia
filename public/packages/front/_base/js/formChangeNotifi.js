(function($){
	$.formChangeNotifi = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		
		base.changed_flag = 0;

		base.$el.data("formChangeNotifi", base);
		
		base.confirmMessage = base.$el.data('leaveConfirmMessage');

		base.init = function(){
			
			base.options = $.extend({},$.formChangeNotifi.defaultOptions, options);
			base._bind();

			window.onbeforeunload = function() {
			  if ( base.changed_flag ) {
				return base.confirmMessage
			  }
			};            
		};
		
		base._bind = function(){
			base.$el.find('input,input[type=hidden],textarea,select:not(.selectLanguage)').change(base.changeListener);			
		};

		base.changeListener = function(){
			base.changed_flag = 1;
			console.log('change form');
		};

		base.init();
	};
	
	$.formChangeNotifi.defaultOptions = {
	};
	
	$.fn.formChangeNotifi = function(options){
		return this.each(function(){
			(new $.formChangeNotifi(this, options));
		});
	};
	
})(jQuery);


$(function(){
	$('form.leaveChangeControl').formChangeNotifi();
});

