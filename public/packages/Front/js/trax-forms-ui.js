(function($){
    $.pricePhrase = function(el, radius, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;
        
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;
        
        // Add a reverse reference to the DOM object
        base.$el.data("pricePhrase", base);
        
        base.init = function(){
            if( typeof( radius ) === "undefined" || radius === null ) radius = "20px";
            
            base.radius = radius;
            
            base.options = $.extend({},$.pricePhrase.defaultOptions, options);            

        };
        

        base.init();
    };
    
    $.pricePhrase.defaultOptions = {
        radius: "20px"
    };
    
    $.fn.pricePhrase = function(radius, options){
        return this.each(function(){

            var self = this;

            $(self).find('select.select2').each(function( index ) {
                $(this).on('change',function(){                                        

                    var currentVal = $(this).val();
                    var forId = '#'+$(this).attr('rel');
                        
                    $(this).find('option').each(function(i){
                        
                        if(currentVal == $(this).val()){
                            $(forId).val($(this).attr('data-sufix'));
                        }
                    });
                    
                }); 
            }); 


        });
    };
    
})(jQuery);