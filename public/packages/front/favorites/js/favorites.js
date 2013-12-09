(function($){
	$.addToFavorites = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("addToFavorites", base);
		
		base.init = function(){
			
			
			base.options = $.extend({},$.addToFavorites.defaultOptions, options);
			

			base._bind();

			if(base._inArray(base._getFavoritesCookie(),base.$el.data('id'))){
				base.$el.addClass(base.options.inFavoritesClass);
			}

			base._updateFavoritesStatusCount();
		};



		base._inArray = function(array,id){
			var r = false;
			$.each(array,function(k,v){
				v = parseInt(v);
				id = parseInt(id);
				if(v == id){
					r = true;
				}
			});

			return r;
		};

		base._getFavoritesCookie = function(){

			var c = $.cookie(base.options.cookieListKey);

			if(typeof c != 'undefined'){
				c = c.split(',');
				return c;
			}

			return false;
		};

		base._bind = function(){
			base.$el.on('click',base._toggleAddToFavorites);
		};

		base._toggleAddToFavorites = function(){

			var id = base.$el.data('id');

			if(base.$el.hasClass(base.options.inFavoritesClass)){
				base.removeFromFavorites(id);
			} else {
				base._addToFavorites(id);
			}

			base._updateFavoritesStatusCount();
			base._animate();

			base.$el.blur();
			// console.log($.cookie(base.options.cookieListKey));
		};
		
		base._updateFavoritesStatusCount = function(){

			var c = base._getFavoritesCookie();


			// $(base.options.statusIconSelector).addClass(base.options.statusAnimationClass);

			if(c){
				$(base.options.statusCountSelector).html(c.length);
				$(base.options.myFavoritesContainerSelector).removeClass('hide');
			} else {
				$(base.options.statusCountSelector).html('0');
				$(base.options.myFavoritesContainerSelector).addClass('hide');
			}
		};

		base._animate = function(){

			var time = 170;

			var fontSizeMax = 30;

			$(base.options.statusIconSelector).animate({fontSize:fontSizeMax},time,function(){
				$(base.options.statusIconSelector).animate({fontSize:20},time-50,function(){
					$(base.options.statusIconSelector).animate({fontSize:fontSizeMax},time,function(){
						$(base.options.statusIconSelector).animate({fontSize:20},time-50);
					});					
				});
			});



			$(base.options.statusIconSelector).addClass(base.options.statusAnimationClass);

			setTimeout(function(){
				$(base.options.statusIconSelector).removeClass(base.options.statusAnimationClass);
			},base.options.animationTime);

		};

		base._addToFavorites = function(id){


			var cookieArray = base._getFavoritesCookie() ? base._getFavoritesCookie() : [] ;
			
				cookieArray.push(id);
				cookieArray.join();			
				$.cookie(base.options.cookieListKey,cookieArray);

				base.$el.addClass(base.options.inFavoritesClass);
			
		};

		base.removeFromFavorites = function(id){


			var cookieArray = base._getFavoritesCookie();
			id = id.toString();
				var p = cookieArray.indexOf(id);
						cookieArray.splice(p,1);
						cookieArray.join();
						// console.log(cookieArray.length);
						if(cookieArray.length < 1){
							$.removeCookie(base.options.cookieListKey);
						} else {
							$.cookie(base.options.cookieListKey,cookieArray);
						}

			base.$el.removeClass(base.options.inFavoritesClass);
		};

		base.init();
	};
	
	$.addToFavorites.defaultOptions = {
	};
	
	$.fn.addToFavorites = function(options){
		return this.each(function(){
			(new $.addToFavorites(this, options));});};
	
})(jQuery);

$(function(){
	$('.addToFavorites').addToFavorites({
		cookieListKey: 'favoritesList',
		inFavoritesClass: 'selected',
		statusAnimationClass: 'pulsed',
		statusIconSelector: '#myFav i.icon-heart',
		statusCountSelector: '#myFav span',
		myFavoritesContainerSelector:'#myFav',
		animationTime:500
	});
});