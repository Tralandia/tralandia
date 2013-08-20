(function($){
    $.reportBugPlugin = function(el, options){

        var base = this;
        
        base.$el = $(el);
        base.el = el;
        
        base.$el.data("reportBugPlugin", base);
        
        base.init = function(){
            
            console.log('wrgerg');
            
            base.options = $.extend({},$.reportBugPlugin.defaultOptions, options);
            base._bind();
        };

        base._bind = function(){
            base.$el.click(base.listener);
            $(base.options.buttonId).click(base._submitForm);
        };

        base._submitForm = function(){
            $(base.options.modalId).modal('hide');

            console.log(navigator);

            // @todo ajax dorobit

            var message = base.$el.data('alertSuccess');

            return false;
        };

        base.listener = function(){        

            $(base.options.modalId).modal('show');

            return false;
        }
        
        base.init();
    };
    
    $.reportBugPlugin.defaultOptions = {
    };
    
    $.fn.reportBugPlugin = function(options){
        return this.each(function(){
            (new $.reportBugPlugin(this, options));

        });
    };
    
})(jQuery);

$(function(){
    $('.reportBugButton').reportBugPlugin({
        modalId: '#myModal',
        buttonId: '#reportBugButton',
    });
});