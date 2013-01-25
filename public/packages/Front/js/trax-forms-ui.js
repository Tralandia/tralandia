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
            
            // Put your initialization code here
        };
        
        // Sample Function, Uncomment to use
        // base.functionName = function(paramaters){
        // 
        // };
        
        // Run initializer
        base.init();
    };
    
    $.pricePhrase.defaultOptions = {
        radius: "20px"
    };
    
    $.fn.pricePhrase = function(radius, options){
        return this.each(function(){

            var html = '';
            var self = this;

            $(this).find('select option').each(function( i ) {              
              html += '<li><a href="#" data-value="'+$(this).val()+'" data-text="'+$(this).text()+'"><strong>'+$(this).val()+'</strong> ' +$(this).text()+'</a></li>';              
            });

            $(this).find('.dropdown-menu').html(html);

            $(this).find('.dropdown-menu li a').click(function(){
               
                var newValue = $(this).attr('data-text');
                var newPrefix = $(this).attr('data-value');

                // replace input values

                $(self).find('.input-prepend').each(function(i){
                    
                });

                console.log($(this).parent());
                $(this).parent().find('input').each(function( i ) {              
                  $(this).val(newValue);             
                });

                // replace prefix values
                $(self).find('span').each(function( i ) {              
                  $(this).html(newPrefix);             
                });

                // replace prefix values
                $(self).find('button').each(function( i ) {              
                  $(this).html(newPrefix);             
                });

                return false;
            });
		   // END DOING STUFF

        });
    };
    
})(jQuery);