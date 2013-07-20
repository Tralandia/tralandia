var favorites;

function initNavBarShare(data)
{
	var html = '<ul class="social-buttons">'+
		(data.twitterShare ? '<li>'+data.twitterShare+'</li>' : null)+
		(data.googlePlusShare ? '<li>'+data.googlePlusShare+'</li>' : null)+
		(data.facebookShare ? '<li>'+data.facebookShare+'</li>' : null)+
		(data.pinterestShare ? '<li>'+data.pinterestShare+'</li>' : null)+
	'</ul>';

	$shareContainer = $('#dynamicShareContainer').html(html);
	Socialite.load($shareContainer);

	if (data.linkToShare) {
		$('#clipboardLinkShare').val(data.linkToShare);
	}
	$('body').removeAttr('data-socialPluginsInit');

}

function removejscssfile(filename, filetype)
{
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

	$('#clipboardLinkShareButton').click(function(){
		$('#clipboardLinkShare').select();
		return false;
	});

	$('body').click(function(){
		var attr = $('body').attr('socialshareOpen');	
		if (typeof attr == 'undefined' || attr == false) {
			if($('#favoritesShareList li a').hasClass('open')){
				$('#shareContent').hide();
				$('#favoritesShareList li a').removeClass('open');
			}
		}

		$('body').removeAttr('socialshareOpen');  
	});
});





/*
* Favorites
*/
var Favorites = {
	cookieName : 'favoritesList',
	visitedCookieName: 'visitedList',
	favoritesPluginDiv: '#favoritesStaticContainer',
	favoritesPlacehoderDiv: '#favoritesStaticContainerPlaceholder',
	favorietsShowSpeed: 300,
	autoupdateTime: 3000,
	jscrollPaneClassName: '.jscrollPane',
	addToFavoritesButtonClass: '.addToFavorites'

};

Favorites.init = function()
{
	if($(this.jscrollPaneClassName).length != 0){

		this.cleanTrash();

		// this.initJscrollpaneUi();
		this.eachSelectedRentalButtons();

	}
};

// vycisti data ktore nepatria do local storage
Favorites.cleanTrash = function()
{
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

Favorites.eachSelectedRentalButtons = function()
{
	
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

Favorites._getFavoritesLength = function()
{
	var c = this._cookieArray();
	if(c){
		return c.length;
	} else {
		return 0;
	}
	
}

Favorites.toggleAdd = function(e, obj)
{

	$obj = $(obj);
	var id = parseInt($obj.attr('rel'));
	Favorites.cleanTrash();

	// open favorites tab
	$('li[for=#favoritesNavFavorites]').trigger('click');

	var data = {
		id: 		parseInt($obj.attr('rel')),
		name: 		$obj.attr('data-name'),
		link: 		$obj.attr('data-link'),
		thumb: 		$obj.attr('data-thumb'),
		location: 	$obj.attr('data-location'),
		price: 		$obj.attr('data-price'),
		capacity: 	$obj.attr('data-capacity')
	}

	if($obj.hasClass('selected')){			
		Favorites.removeFromFavorites(data);
		$obj.removeClass('selected');
	} else {
		Favorites.addToFavorites(data);
		$obj.addClass('selected');
	}

	Favorites.updateList();	
};

Favorites.removeLink = function(e, obj)
{
	$(obj).parents('li').popover('hide');
	var id = parseInt($(obj).parents('li').attr('rel'));
	Favorites.cleanTrash();
	Favorites.removeFromLocalStorage(id);
	Favorites.removeFromCookie(id);
	Favorites.updateList();
	return false;
}

Favorites.addToFavorites = function(data)
{	
	this.addToCookie(data.id);
	this.addToLocalStorage(data);
};

Favorites.removeFromFavorites = function(data)
{			
	this.removeFromCookie(data.id);
	this.removeFromLocalStorage(data.id);
};


/*
*	local storage control favorites
*/
Favorites.addToLocalStorage = function(data)
{
	var storageArray = $.jStorage.get(this.cookieName);
	if(storageArray == null){
		storageArray  = [];
	}

	if(!this.getObjectById(storageArray,data.id)){
		storageArray.push(data);
		$.jStorage.set(this.cookieName,storageArray);				
	}
		
};

Favorites.removeFromLocalStorage = function(id)
{
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
Favorites._idInArray = function(array,id)
{
	var r = false;
	$.each(array,function(k,v){
		if(v == id){
			r = true;
		}
	});

	return r;
}

Favorites._cookieArray = function()
{
	var c = this.getCookie();		
	if(c){
		c = c.split(',');					
	}
	return c;
};

Favorites._visitedRentalArray = function()
{
	var c = this.getVisitedCookie();		
	if(c){
		c = c.split(',');					
	}
	return c;		
}

Favorites.addToCookie = function(id)
{
	var cookieArray = this._cookieArray() ? this._cookieArray() : [] ;
	
	if(!this._idInArray(cookieArray,id)){
		cookieArray.push(id);
		cookieArray.join();
		$.cookie(this.cookieName,cookieArray);
	}		
	
};

Favorites.removeFromCookie = function(id)
{
	var cookieArray = this._cookieArray();
	id = id.toString();
	if(this._idInArray(cookieArray,id)){
		var p = cookieArray.indexOf(id);
				cookieArray.splice(p,1);
				cookieArray.join();

		$.cookie(this.cookieName,cookieArray);
	}				

};

// return favorites list in cookie

Favorites.getCookie = function()
{
	var r = $.cookie(this.cookieName);

		if(typeof r == 'undefined') {
			return false;
		} else {
			return r ;
		}		
};	

Favorites.getVisitedCookie = function()
{
	var r = $.cookie(this.visitedCookieName);

		if(typeof r == 'undefined') {
			return false;
		} else {
			return r ;
		}		
};	

Favorites.getObjectById = function(arrayObjects,id)
{
	if (!arrayObjects) return [];

	var r = false;
	id = parseInt(id);

	$.each(arrayObjects,function(k,v){
		if(v.id == id){
			r = v;
		}
	});
	return r;
};

Favorites.updateList = function()
{
	this.cookieArray = this._cookieArray();
	//_cookieArray
	var self = this;

	this.getLocalStorageArray = self.getLocalStorage();
	var allForView = [];

	var favoriteSlider = $('#navigationBarFavorites');

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

			patternText = patternText.replace("~id~", data.id)							
							.replace("~name~", data.name)
							.replace("~link~", data.link)
							.replace("~thumb~", data.thumb)
							.replace("~location~", data.location)
							.replace("~price~", data.price)
							.replace("~capacity~", data.capacity)
							.replace("template", "");
			
			var newLi = $(patternText);
			var visited = self._visitedRentalArray();

			if(visited){
				if(!self.in_array(visited,data.id)) {
					newLi.find('.checked').remove();
				}					
			}

			html += newLi[0].outerHTML;
	});

	$('#scrollInnerContent').html(html);

	//self.initJscrollpaneUi();
	// self.jscrollPaneApi.reinitialise();
	self.eachSelectedRentalButtons();
};

Favorites.getLocalStorage = function()
{
	return $.jStorage.get(this.cookieName);
};


Favorites.in_array = function(array,value)
{
	var r = false ;

	$.each(array , function(k,v){
		if(v == value) r = true;
	});

	return r; 		
};


Favorites.checkChanges = function()
{
	this.list = $('#scrollInnerContent');
	var self = this;
	self.rentalIdsArray = [];
	self.rentalIdsString = false;

	self.list.find('li:not(.template)').each(function(k){			
		self.rentalIdsArray.push($(this).attr('rel'));
	});

	self.rentalIdsString = self.rentalIdsArray.join();

	if(self.getCookie() == self.rentalIdsString){
		return true;
	} else {
		return false;
	}
};

