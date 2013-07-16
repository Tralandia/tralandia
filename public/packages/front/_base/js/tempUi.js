$(function(){
	// console.log('temp mode');

	$(document).scroll(function(){
		console.log();

		if($('body').scrollTop() > 99){
			$('#headerShareMenu').addClass('fixed');
		} else {
			$('#headerShareMenu').removeClass('fixed');
		}
	});
});



(function($){
    $.yourPluginName = function(el, options){

        var base = this;

        base.$el = $(el);
        base.el = el;

        base.$el.data("yourPluginName", base);
        
        base.init = function(){
            
            base.options = $.extend({},$.yourPluginName.defaultOptions, options);

        };

        base.init();
    };
    
    $.yourPluginName.defaultOptions = {
    };
    
    $.fn.yourPluginName = function(options){
        return this.each(function(){
            (new $.yourPluginName(this, options));
        });
    };
    
})(jQuery);
