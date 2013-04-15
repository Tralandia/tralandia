(function($){
    $.rentalEditFormLocalize = function(el, options){

        var base = this;
        
        base.$el = $(el);
        base.el = el;
        
        base.$allInputs = base.$el.parents('form').find('.interview,.name,.slogan');
        
        base.init = function(){      
            base.bind();              
        };

        base.bind = function(){
            $el.on('change' , base.setLanguage);
        }

        base.setLanguage = function(){
            base.$allInputs.addClass('hide');
            base.$el.parents('form').find('[locate='+base.$el.val()+']').removeClass('hide');
        }
        
        base.init();
    };
    
    
    $.fn.rentalEditFormLocalize = function(options){
        return this.each(function(){
            (new $.rentalEditFormLocalize(this, options));
        });
    };
    
})(jQuery);