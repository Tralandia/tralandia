


(function($){
    $.showFavoriteSlider = function( el, appObject, options ){

        var base = this;
                
        base.$el = $(el);
        base.el = el;
        
        base.$el.data("showFavoriteSlider", base);
        
        base.init = function(){
            base.options = $.extend({},$.showFavoriteSlider.defaultOptions, options);      
        };
        
        base.init();
    };
    
    $.showFavoriteSlider.defaultOptions = {
        
    };
    
    $.fn.showFavoriteSlider = function( appObject , options ){
        return this.each(function(){

            (new $.showFavoriteSlider(this, options));
            
            this.list = $(this).find('ul');

                this.favoritesData = appObject.storageGet('favoritesList');

                var self = this;

                this.currentId = $('#content').find('h1.addToViewList');
                this.currentId = $(this.currentId).attr('id');
                console.log(this.currentId);

                $.each(this.favoritesData , function(k,v){
            
                    var newLi = $('<li></li>').attr('rel',v.id);

                        newLi.addClass('rel-'+v.id);

                        if(v.id == self.currentId){
                            newLi.addClass('current');
                        }
                        newLi.appendTo(self.list);

                });
            

        });
    };
    
})(jQuery);
