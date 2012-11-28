


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
