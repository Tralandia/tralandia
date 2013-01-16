


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

				this.favoritesData = appObject.storageGet('favoritesList');
				
				this.visitList = appObject.storageGet('visitObjectList');

				console.log(this.favoritesData);

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

							

						var newLi = $('<li></li>').attr('rel',v.id).css('background-image', 'url('+v.thumb+')');

							newLi.addClass('rel-'+v.id);

							newLink.appendTo(newLi);

							if(v.id == self.currentId){
								newLi.addClass('current');
							}
							newLi.appendTo(self.list);

					});


					// full real width list

					var liWidth = 100;

					
					var listCount = 0;
					$(this).find('ul li').each(function(index) {						
						++listCount;
					});

					var sumPx = (listCount*liWidth)+(listCount*6);

					this.list.css('width',sumPx+'px');

					// set nav links action

					var animationOfset = 200;
					var animationSpeed = 800;

					$rigthArrow = $(this).find('.rightArrow');
					$lefthArrow = $(this).find('.leftArrow');

					var self = this;

					var leftOfset = parseInt(self.list.css('left'));
					

					$rigthArrow.click(function(){

						

						console.log(leftOfset);

						listCount = 0;
						$(this).parent().find('ul li').each(function(index) {						
							++listCount;
						});

						sumPx = (listCount*liWidth)+(listCount*6);
						
						console.log(leftOfset);
						console.log(sumPx);

						if( (leftOfset+920) < sumPx){
							leftOfset += animationOfset;
						  self.list.animate({
							
							left: '-='+animationOfset,
							
						  }, animationSpeed, function() {
							// Animation complete.
							
						  });

						}


					});


					

					

					$lefthArrow.click(function(){

						

						console.log(leftOfset);

						if(leftOfset > 0){
							leftOfset -= animationOfset;
							  self.list.animate({
								
								left: '+='+animationOfset,
								
							  }, animationSpeed, function() {
								// Animation complete.
								
							  });
						}


					});


					

				}

		});
	};
	
})(jQuery);
