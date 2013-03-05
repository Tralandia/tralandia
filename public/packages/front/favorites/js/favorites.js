


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

//$.jStorage.flush();
	var y = Favorites;

		y.autoUpdate();

});


var Favorites = {

};
	Favorites.autoUpdate = function(){
		console.log(Favorites.checkChanges());
		console.log(Favorites.getLocalStorage());
		if(!Favorites.checkChanges()){
			Favorites.updateList();
		}
		setTimeout(Favorites.autoUpdate,3000);
	};

	// return favorites list in cookie
	Favorites.getCookie = function(){

		var r = $.cookie('favoritesList');

			if(typeof r == 'undefined') {
				return false;
			} else {
				return r ;
			}
		//return $.cookie('favoritesList');
	};	

	Favorites.updateList = function(){

		this.cookieArray = this.getCookie().split();
			console.log(this.cookieArray);
			var self = this;

			$.each(this.cookieArray,function(k,v){
				self.list.find('li:not(.template)').each(function(k){			
					//self.rentalIdsArray.push($(this).attr('rel'));
				});	
			});

/*		this.list.find('li.template').css('background-image','url('+data.thumb+')');

		$pattern = this.list.find('li.template');

		var patternText = $pattern[0].outerHTML;

		patternText = patternText.replace("~id~",data.id)							
						.replace("~title~",data.title)
						.replace("~url~",data.link)
						.replace("template","");*/

		//this.list.html('ymena');
	};

	Favorites.getLocalStorage = function(){
		return $.jStorage.get('favoritesList');
	};


	Favorites.in_array = function(){
		var r = false ;

		$.each(array , function(k,v){
			if(v == value) r = true;
		});

		return r; 		
	};


	Favorites.checkChanges = function(){
		this.list = $('#scrollInnerContent');
		var self = this;
		self.rentalIdsArray = [];
		self.rentalIdsString = false;

		self.list.find('li:not(.template)').each(function(k){			
			self.rentalIdsArray.push($(this).attr('rel'));
		});

		self.rentalIdsString = self.rentalIdsArray.join();
		console.log(self.getCookie());
		console.log(self.rentalIdsString);

		if(self.getCookie() == self.rentalIdsString){
			return true;
		} else {
			return false;
		}
	};







