


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

				//appObject.storageDelete('favoritesList');

				//this.visitList = appObject.storageDelete('favoritesList');

					var liWidth = 122;

					
					var listCount = 0;
					$(this).find('ul li').each(function(index) {						
						++listCount;
					});

					var sumPx = (listCount*liWidth)+(listCount*6);

					this.list.css('width',sumPx+'px');

					// set nav links action

					var animationOfset = 100;
					var animationSpeed = 200;

					$rigthArrow = $('#favorites-right-button');
					$lefthArrow = $('#favorites-left-button');

					var self = this;

					var leftOfset = parseInt(self.list.css('left'));
					
					$rigthArrow.click(function(){

						listCount = 0;
						$(this).parent().find('ul li').each(function(index) {						
							++listCount;
						});

						sumPx = (listCount*liWidth)+(listCount*6);
						

						if( (leftOfset+760) < sumPx){
							leftOfset += animationOfset;
						  self.list.animate({
							
							left: '-='+animationOfset,
							
						  }, animationSpeed, function() {
							// Animation complete.
							
						  });

						}


					});

					$lefthArrow.click(function(){

						if(leftOfset > 0){
							leftOfset -= animationOfset;
							  self.list.animate({
								
								left: '+='+animationOfset,
								
							  }, animationSpeed, function() {
								// Animation complete.
								
							  });
						}


					}); // ---------


				this.favoritesData = appObject.storageGet('favoritesList');
				
				this.visitList = appObject.storageGet('visitObjectList');

				if(typeof this.favoritesData == 'undefined' || this.favoritesData == null){
					//$(this).parent().parent().parent().hide();
					//$('#favoritesStatisContainerPlaceholder').addClass('inactive');
				} else {
					$(this).parent().parent().parent().show();
					var self = this;

					this.currentId = $('#content').find('h1.addToViewList');
					this.currentId = $(this.currentId).attr('id');
					

					$.each(this.favoritesData , function(k,v){			

						var removeLink = $('<a></a>').attr({
							href: '#',
							rel: v.id
						}).addClass('removeLink');

						var newLink = $('<a></a>').attr({
							href: v.link,
							title: v.title
						}).addClass('link');

							// if is visited object
							if(appObject.in_array(self.visitList,v.id)){
								var visited = $('<div></div>').addClass('checked entypo-eye');
									visited.appendTo(newLink);
							}

							

						var newLi = $('<li></li>').attr('rel',v.id).css('background-image', 'url('+v.thumb+')');

							newLi.addClass('rel-'+v.id);

							removeLink.appendTo(newLi);
							newLink.appendTo(newLi);

							if(v.id == self.currentId){
								newLi.addClass('current');
							}
							newLi.appendTo(self.list);

					});


					// full real width list






					

				}

		});
	};
	
})(jQuery);
