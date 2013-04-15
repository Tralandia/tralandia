(function($){
    $.customAlert = function(el, options){

        var base = this;
        
        base.$el = $(el);
        base.el = el;
        
        base.timeout = 5000;
        
        base.init = function(){    
            base.hideAlert();  
        };

        base.hideAlert = function(){
            setTimeout(function(){
                base.$el.fadeOut();
            },base.timeout);
        }

        
        base.init();
    };
    
    
    $.fn.customAlert = function(options){
        return this.each(function(){
            (new $.customAlert(this, options));
        });
    };
    
})(jQuery);