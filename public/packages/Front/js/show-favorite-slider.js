


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
				this.visitList = appObject.storageGet('visitObjectList');

				

				if(typeof this.favoritesData == 'undefined' || this.favoritesData == null){
					$(this).parent().parent().parent().hide();
				} else {
					var self = this;

					this.currentId = $('#content').find('h1.addToViewList');
					this.currentId = $(this.currentId).attr('id');
					

					$.each(this.favoritesData , function(k,v){			

						var newLink = $('<a></a>').attr('href',v.link);

							// if is visited object
							if(appObject.in_array(self.visitList,v.id)){
								var visited = $('<div></div>').addClass('checked entypo-ok');
									visited.appendTo(newLink);
							}

						var newLi = $('<li></li>').attr('rel',v.id);

							newLi.addClass('rel-'+v.id);

							newLink.appendTo(newLi);

							if(v.id == self.currentId){
								newLi.addClass('current');
							}
							newLi.appendTo(self.list);

					});


					// full real width list
					var sumPx = 0;
					var listCount = 0;
					$(this).find('ul li').each(function(index) {					    
					    sumPx += parseInt($(this).css('width'));
					    ++listCount;
					});

					sumPx = sumPx + (listCount*3);

					$(this).find('ul').css('width',sumPx+'px');

					// set nav links

				}

		});
	};
	
})(jQuery);
