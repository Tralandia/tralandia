function importShareLink(fromUrl, callback){
	jQuery.getJSON( fromUrl , callback );

}


function removejscssfile(filename, filetype){
 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
 var allsuspects=document.getElementsByTagName(targetelement)
 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
   allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
 }
}


$(function(){

	$('li.favoriteSocialIcons').click(function(){  
		$('body').attr({
			socialshareOpen: true
		});		
	});


// var clip = new ZeroClipboard($('#clipboardLinkShareButton'));
	$('#clipboardLinkShareButton').click(function(){
		$('#clipboardLinkShare').select();
		return false;
	});

	$('#favoritesShareList li a').click(function(){
		
				removejscssfile('http://platform.twitter.com/widgets.js','js');
				removejscssfile('https://apis.google.com/js/plusone.js','js');
		
		var shareUrl = $('#favoritesStaticContainer').attr('data-favoritesLink');
			importShareLink(shareUrl , function(d){
				var linkToShare = d.link;
				var html = '<span class="facebook facebookLikeButtonContainer" data-facebook-src="'+linkToShare+'"></span><g:plusone size="medium" href="'+linkToShare+'" style="margin-top:2px;"></g:plusone><a href="https://twitter.com/share" class="twitter-share-button" data-url="'+linkToShare+'" data-via="Tralandia" data-text="{_o100036}" data-hashtags="{_o100034}" data-lang="{$currentLanguage->iso}">Tweet</a><a href="http://pinterest.com/pin/create/button/?url='+linkToShare+'" class="pin-it-button" count-layout="horizontal"></a>'

				$('#dynamicShareContainer').html(html);
				$('#clipboardLinkShare').val(d.link);
				$('body').removeAttr('data-socialPluginsInit');
				initAllSocialPlugins();

			});

				if($(this).hasClass('open')){
					
					$('#favoriteShareContent').hide();
				} else {
					
					$('#favoriteShareContent').show();			
				}

				$(this).toggleClass('open');

		return false;

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



		// tabs nav
		$('#favoritesTabs.nav-tabs li:not(.pull-right)').click(function () {
			var currentId = $(this).attr('for');
			
			$(this).parent().find('li.active').removeClass('active');
			$(this).addClass('active');

			$('#favoritesTabContent').find('.tab-pane.active').removeClass('active');
			$('#favoritesTabContent').find(currentId).addClass('active');
			$('#favoritesTabContent').find(currentId).addClass('active').css({
				width:'881px'
			});

			// console.log(currentId);

			var cookieName = 'navbarTab';
			$.cookie(cookieName,currentId);
		});



	// remove object from favorites list     

	$('a.removeLink').live('click',Favorites.removeLink);
	$('a.removeLink').on('click',Favorites.removeLink);

	$('.addToFavorites').click(Favorites.toggleAdd);
	//$('.addToFavorites').favoriteActiveLinks(A);


	var y = Favorites;
		y.init();
		//y.checkChanges();

});


var Favorites = {
	cookieName : 'favoritesList',
	visitedCookieName: 'favoritesVisitedList',
	favoritesPluginDiv: '#favoritesStaticContainer',
	favoritesPlacehoderDiv: '#favoritesStaticContainerPlaceholder',
	favorietsShowSpeed: 300,
	autoupdateTime: 3000,
	jscrollPaneClassName: '.jscrollPane',
	addToFavoritesButtonClass: '.addToFavorites'

};

	Favorites.init = function(){
		//console.log($.jStorage.deleteKey(this.cookieName));
		//$.removeCookie(this.cookieName);
		// console.log(this._cookieArray());
		// console.log($.jStorage.get(this.cookieName));
		if($(this.jscrollPaneClassName).length != 0){

			this.cleanTrash();

			this.initJscrollpaneUi();
			this.eachSelectedRentalButtons();
			this.autoUpdate();

		}

	};

	// vycisti data ktore nepatria do local storage
	Favorites.cleanTrash = function(){
		var self = this ;
		var array = this._cookieArray();

		var localStorageArray  = this.getLocalStorage();
		var newLocalStorageArray = [];

		if(localStorageArray != null){
			$.each(localStorageArray, function(k,v){
				if(self._idInArray(array,v.id)){
					newLocalStorageArray.push(v);					
				}
			});	

			if(newLocalStorageArray.length > 0){
				$.jStorage.set(this.cookieName,newLocalStorageArray);
			} else {
				$.jStorage.deleteKey(this.cookieName);
			}				
		} else {
			$.jStorage.deleteKey(this.cookieName);
		}
		
	}

	Favorites.eachSelectedRentalButtons = function(){
		
		var self = this ;
		var array = this._cookieArray();


		$(self.addToFavoritesButtonClass).each(function(index){
			var id = $(this).attr('rel');
			if(self._idInArray(array,id)){
				$(this).addClass('selected');
			} else {
				$(this).removeClass('selected');
			}
		});
	}

	Favorites.initJscrollpaneUi = function(){			

			var self = this;

			var settings = {
				showArrows: true
			};

			self.pane = $('.jscrollPane');

			self.pane.jScrollPane(settings);

			self.contentPane = self.pane.data('jsp').getContentPane();

			$rigthArrow = $('#favorites-right-button');
			$leftArrow = $('#favorites-left-button');
			
			//var pane = $('.jscrollPane');
			self.jscrollPaneApi = self.pane.data('jsp');

			$leftArrow .bind('click',function(){							
					self.jscrollPaneApi.scrollBy(-40,0);
					return false;
			});

			$rigthArrow.bind('click',function(){									
					self.jscrollPaneApi.scrollBy(40,0);
					return false;

			});	
	};

	Favorites.pluginShow = function(){		
		$(this.favoritesPluginDiv).slideDown(this.favorietsShowSpeed,function(){
			$(this.favoritesPluginDiv).removeClass('hide');
		});
	};

	Favorites.pluginHide = function(){
		$(this.favoritesPluginDiv).slideUp(this.favorietsShowSpeed,function(){
			$(this.favoritesPluginDiv).addClass('hide');
		});
		
	};

	Favorites.isShow = function(){
		return !$(this.favoritesPluginDiv).hasClass('hide');
	}

	Favorites.sanitizePluginView = function(){
		
			if(this._getFavoritesLength() == 0){
				this.pluginHide();
			}
		
			if(this._getFavoritesLength() > 0){
				this.pluginShow();
			}			

			if(this._getFavoritesLength() < 7){
				var $container = $(this.favoritesPluginDiv).find('.jspContainer .jspPane');
				$container.css({
					left: 0
				});
			}
		
	}

	Favorites._getFavoritesLength = function(){
		var c = this._cookieArray();
		if(c){
			return c.length;
		} else {
			return 0;
		}
		
	}

	Favorites.toggleAdd = function(e){
		
		var id = parseInt($(this).attr('rel'));
		Favorites.cleanTrash();



		var data = {
			id: parseInt($(this).attr('rel')),
			link: $(this).attr('link'),
			thumb: $(this).attr('thumb'),
			title: $(this).attr('data-title')
		}

		if($(this).hasClass('selected')){			
			Favorites.removeFromFavorites(data);
			$(this).removeClass('selected');
		} else {
			Favorites.addToFavorites(data);
			$(this).addClass('selected');
		}

		Favorites.updateList();
		Favorites.sanitizePluginView();		
	};

	Favorites.removeLink = function(e){
		var id = parseInt($(this).parents('li').attr('rel'));
		Favorites.cleanTrash();
		Favorites.removeFromLocalStorage(id);
		Favorites.removeFromCookie(id);
		Favorites.updateList();
		Favorites.sanitizePluginView();
		return false;
	}

	Favorites.addToFavorites = function(data){	
			this.addToCookie(data.id);
			this.addToLocalStorage(data);
	};

	Favorites.removeFromFavorites = function(data){			
			this.removeFromCookie(data.id);
			this.removeFromLocalStorage(data.id);
	};

	/*
	*	local storage control favorites
	*/

	Favorites.addToLocalStorage = function(data){
		var storageArray = $.jStorage.get(this.cookieName);
			if(storageArray == null){
				storageArray  = [];
			}

			if(!this.getObjectById(storageArray,data.id)){
				storageArray.push(data);
				$.jStorage.set(this.cookieName,storageArray);				
			}
			
	};

	Favorites.removeFromLocalStorage = function(id){
		var storageArray = $.jStorage.get(this.cookieName);
		var newArray = [];

		if(typeof storageArray != 'undefined' && storageArray != null){
			$.each(storageArray,function(k,v){
				if(v.id != id){
					newArray.push(v);
				}
			});				
		}

		if(newArray.length > 0){
			$.jStorage.set(this.cookieName,newArray);
		} else {
			$.jStorage.deleteKey(this.cookieName);
		}
			
	};

	/*
	*	cookie control favorites
	*/

	Favorites._idInArray = function(array,id){
		
		var r = false;
		$.each(array,function(k,v){
			if(v == id){
				r = true;
			}
		});

		return r;
	}

	Favorites._cookieArray = function(){
		var c = this.getCookie();		
		if(c){
			c = c.split(',');					
		}
		return c;
	};

	Favorites._visitedRentalArray = function(){
		var c = this.getVisitedCookie();		
		if(c){
			c = c.split(',');					
		}
		return c;		
	}

	Favorites.addToCookie = function(id){

		var cookieArray = this._cookieArray() ? this._cookieArray() : [] ;
		
		if(!this._idInArray(cookieArray,id)){
			cookieArray.push(id);
			cookieArray.join();
			$.cookie(this.cookieName,cookieArray);
		}		
		
	};

	Favorites.removeFromCookie = function(id){
		var cookieArray = this._cookieArray();
		id = id.toString();
		if(this._idInArray(cookieArray,id)){
			var p = cookieArray.indexOf(id);
					cookieArray.splice(p,1);
					cookieArray.join();

			$.cookie(this.cookieName,cookieArray);
		}				

	};

	Favorites.autoUpdate = function(){

		if(!Favorites.checkChanges()){
			Favorites.sanitizePluginView();
			Favorites.updateList();
			
		}		
		setTimeout(Favorites.autoUpdate,Favorites.autoupdateTime);
	};

	// return favorites list in cookie

	Favorites.getCookie = function(){

		var r = $.cookie(this.cookieName);

			if(typeof r == 'undefined') {
				return false;
			} else {
				return r ;
			}		
	};	

	Favorites.getVisitedCookie = function(){

		var r = $.cookie(this.visitedCookieName);

			if(typeof r == 'undefined') {
				return false;
			} else {
				return r ;
			}		
	};	

	Favorites.getObjectById = function(arrayObjects,id){

		var r = false;
		id = parseInt(id);

		$.each(arrayObjects,function(k,v){
			
			if(v.id == id){
				r = v;				
			}
		});
		return r;
	};

	Favorites.updateList = function(){

		this.cookieArray = this._cookieArray();
			//_cookieArray
			//console.log(this.cookieArray);
			var self = this;

			this.getLocalStorageArray = self.getLocalStorage();
			//console.log(this.getLocalStorageArray);
			var allForView = [];

			var favoriteSlider = $('#compareList');

			var sliderList = favoriteSlider.find('ul');

				$pattern = sliderList.find('li.template');

				sliderList.html('');

				$pattern.appendTo(sliderList);

				var html = $pattern[0].outerHTML;


			$.each(this.cookieArray,function(k,v){				
				var data = self.getObjectById(self.getLocalStorageArray,v);					
				allForView.push(data);

					sliderList.find('li.template').css('background-image','url('+data.thumb+')');
					$pattern = sliderList.find('li.template');
					var patternText = $pattern[0].outerHTML;					

					patternText = patternText.replace("~id~",data.id)							
									.replace("~title~",data.title)
									.replace("~url~",data.link)
									.replace("template","");
					
					var newLi = $(patternText);
					var visited = self._visitedRentalArray();

						if(visited){
							if(!self.in_array(visited,data.id)){									
								newLi.find('.checked').remove();
							}					
						}

					//html+=patternText;
					html += newLi[0].outerHTML;
				
			});

			$('#scrollInnerContent').html(html);

			//self.initJscrollpaneUi();
			self.jscrollPaneApi.reinitialise();
			self.eachSelectedRentalButtons();
	};

	Favorites.getLocalStorage = function(){
		return $.jStorage.get(this.cookieName);
	};


	Favorites.in_array = function(array,value){
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
/*		console.log(self.getCookie());
		console.log(self.rentalIdsString);*/

		if(self.getCookie() == self.rentalIdsString){
			return true;
		} else {
			return false;
		}
	};







