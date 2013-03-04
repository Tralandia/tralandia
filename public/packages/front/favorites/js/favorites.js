


// showFavoriteSlider
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
			
			var self = this;

			this.list = $(this).find('ul');

			$('.jscrollPane').jScrollPane({showArrows: true});

			$rigthArrow = $('#favorites-right-button');
			$leftArrow = $('#favorites-left-button');
			
			var pane = $('.jscrollPane');
			global.jscrollPaneApi = pane.data('jsp');

			$leftArrow .bind('click',function(){				
			
					global.jscrollPaneApi.scrollBy(-40,0);
					return false;

			});

			$rigthArrow.bind('click',function(){				
					
					global.jscrollPaneApi.scrollBy(40,0);
					return false;

			});	

		});
	};
	
})(jQuery);




/* set selected css class to Rental list objects and Rental detail object */

(function($){
	$.favoriteActiveLinks = function( el, appObject, options ){

		var base = this;
				
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("favoriteActiveLinks", base);
		
		base.init = function(){
			base.options = $.extend({},$.favoriteActiveLinks.defaultOptions, options);      
		};
		
		base.init();
	};
	
	$.favoriteActiveLinks.defaultOptions = {
		
	};
	
	$.fn.favoriteActiveLinks = function( appObject , options ){
		return this.each(function(){

			(new $.favoriteActiveLinks(this, options));
			
			var list = $.cookie('favoritesList');

			if(typeof list != 'undefined'){
				list = list.split(',');
			} else {
				list = false;
			}			


			if(list){
				var currentId = parseInt($(this).attr('rel'));

				if(appObject.in_array(list,currentId)){
					
					$(this).addClass('selected');
				} 
			} 

		});
	};
	
})(jQuery);



// neviem ci sa pouziva treba testnut
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

$(function(){

	$('li.favoriteSocialIcons').click(function(){  
		$('body').attr({
			socialshareOpen: true
		});		
	});

	$('#favoritesShareList li a').click(function(){
		
		initAllSocialPlugins();
		
		if($(this).hasClass('open')){
			
			$('#favoriteShareContent').hide();
		} else {
			
			$('#favoriteShareContent').show();			
		}

		$(this).toggleClass('open');

		return false;
		//var forconteiner = $(this).attr('rel');

	});




	$('body').click(function(){

		var attr = $('body').attr('socialshareOpen');	

		if (typeof attr == 'undefined' || attr == false) {
			if($('#favoritesShareList li a').hasClass('open')){
				$('#favoriteShareContent').hide();
				$('#favoritesShareList li a').removeClass('open');
			}		  

		}

		$('body').removeAttr('socialshareOpen');  

	});




});






