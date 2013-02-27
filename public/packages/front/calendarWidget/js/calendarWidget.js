(function($){
    $.calendarWidgetControl = function(el, options){

        var base = this;

        base.$el = $(el);
        base.el = el;

        base.$el.data("calendarWidgetControl", base);
        
        base.init = function(){
            if( typeof( radius ) === "undefined" || radius === null ) radius = "20px";
            
            base.radius = radius;
            
            base.options = $.extend({},$.calendarWidgetControl.defaultOptions, options);

        };
        
        base.init();
    };
    
    $.calendarWidgetControl.defaultOptions = {
        radius: "20px"
    };
    
    $.fn.calendarWidgetControl = function(radius, options){
        return this.each(function(){
            (new $.calendarWidgetControl(this, radius, options));

            $(this).css('background','red');

            $(this).find('select').on('change',function(){
                console.log('init');
            });

        });
    };
    
})(jQuery);


$(document).ready(function(){
     $('#calendarWidgetControl').calendarWidgetControl();
});