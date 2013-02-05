

(function($){
    $.pricePhrase = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;
        
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;
        
        // Add a reverse reference to the DOM object
        base.$el.data("pricePhrase", base);
        
        base.init = function(){
            
            base.options = $.extend({},$.pricePhrase.defaultOptions, options);            

        };
        

        base.init();
    };
    
    $.pricePhrase.defaultOptions = {
        radius: "20px"
    };
    
    $.fn.pricePhrase = function(options){
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







// calendar edit function 


(function($){
    $.calendarEdit = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;
        
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;
        
        // Add a reverse reference to the DOM object
        base.$el.data("calendarEdit", base);
        
        base.init = function(){

            base.options = $.extend({},$.calendarEdit.defaultOptions, options);            

        };
        

        base.init();
    };
    
    $.calendarEdit.defaultOptions = {
        
    };
    
    $.fn.calendarEdit = function(options){
        return this.each(function(){

            var calendarForm = this;
            var $calendarForm = $(this);

            $calendarForm.find('.calendar').each(function(i){

                var calendar = this;
                var $calendar = $(this);

                var currentDate = $calendar.attr('data-date');

                    $calendar.find('.day.active').click(function(){

                        var currentDay = $(this).attr('data-day');
                        var currentTime = currentDate+'-'+currentDay;
                        
                        if(!$(this).hasClass('selected')){

                            $(this).addClass('selected');

                            var newInput = $('<input>').attr({
                                value: currentTime,
                                type: 'hidden',
                                name: 'calendar[]'
                            });

                            $(this).append(newInput);

                        } else {
                            $(this).removeClass('selected');

                            $(this).find('input').remove();
                        }


                        var statusClass = {
                            first: 'status01',
                            last: 'status10',
                            middle: 'status11'
                        }

                        var stats = 0 ;

                        $calendar.find('.day.active').each(function(ii){

                            $(this).removeClass(statusClass.last);
                            $(this).removeClass(statusClass.middle);
                            $(this).removeClass(statusClass.first);                            

                            if($(this).hasClass('selected') && stats == 0 ){
                                $(this).addClass(statusClass.first);

                                stats = 1;
                            }

                            else if($(this).hasClass('selected') && (stats == 1 || stats == 2) ){
                                $(this).addClass(statusClass.middle);
                          
                                stats = 2;
                            }

                            else if(!$(this).hasClass('selected') && (stats == 2 || stats == 1) ){
                                $(this).addClass(statusClass.last);
                               
                                stats = 0;
                            }

                        });

                        return false;
                        
                    });

               

            });


        });
    };
    
})(jQuery);