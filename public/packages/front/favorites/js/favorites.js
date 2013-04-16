var favorites;

function initNavBarShare(linkToShare)
{
	var html = '<ul class="social-buttons">'+
		'<li><div class="socialite twitter-share socialite-instance socialite-loaded" data-url="'+linkToShare+'" data-text="Ubytovanie v Bojniciach" data-count="none" data-default-href="http://twitter.com/share" data-socialite="4"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.1363148939.html#_=1366115029146&amp;count=none&amp;id=twitter-widget-1&amp;lang=en&amp;original_referer=http%3A%2F%2Fwww.sk.tra.com%2Futulna-chalupa-u-peta-r49&amp;size=m&amp;text=Ubytovanie%20v%20Bojniciach&amp;url=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1" class="twitter-share-button twitter-count-none" style="width: 56px; height: 20px;" title="Twitter Tweet Button" data-twttr-rendered="true"></iframe></div></li>'+
		'<li><div class="socialite googleplus-one socialite-instance socialite-loaded" data-href="'+linkToShare+'" data-size="medium" data-default-href="https://plus.google.com/share?url=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1" data-socialite="5"><div class="g-plusone" data-href="'+linkToShare+'" data-size="medium" data-default-href="https://plus.google.com/share?url=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1" data-socialite="5" id="___plusone_1" style="text-indent: 0px; margin: 0px; padding: 0px; background-color: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 90px; height: 20px; background-position: initial initial; background-repeat: initial initial;"><iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 90px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 20px;" tabindex="0" vspace="0" width="100%" id="I1_1366115029440" name="I1_1366115029440" src="https://plusone.google.com/_/+1/fastbutton?bsv&amp;size=medium&amp;default-href=https%3A%2F%2Fplus.google.com%2Fshare%3Furl%3Dhttp%253A%252F%252Fwww.sk.tra.com%252Fbojnice1&amp;socialite=5&amp;hl=en-GB&amp;origin=http%3A%2F%2Fwww.sk.tra.com&amp;url=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.sk.kYNa6JEzwdk.O%2Fm%3D__features__%2Fam%3DQQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAItRSTMAP1gp_nMeKVKI4yTJlDDEOz5arA#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Constartinteraction%2Conendinteraction&amp;id=I1_1366115029440&amp;parent=http%3A%2F%2Fwww.sk.tra.com&amp;rpctoken=31166877" allowtransparency="true" data-gapiattached="true" title="+1"></iframe></div></div></li>'+
		'<li><div class="socialite facebook-like socialite-instance socialite-loaded" data-href="'+linkToShare+'" data-layout="button_count" data-send="false" data-width="60" data-show-faces="false" data-default-href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1&amp;t=Ubytovanie+v+Bojniciach" data-socialite="6"><div class="fb-like fb_edge_widget_with_comment fb_iframe_widget" data-href="'+linkToShare+'" data-layout="button_count" data-send="false" data-width="60" data-show-faces="false" data-default-href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1&amp;t=Ubytovanie+v+Bojniciach" data-socialite="6" fb-xfbml-state="rendered"><span style="height: 20px; width: 75px;"><iframe id="f2f1a9c2c4" name="f2d0252568" scrolling="no" style="border: none; overflow: hidden; height: 20px; width: 75px;" title="Like this content on Facebook." class="fb_ltr" src="http://www.facebook.com/plugins/like.php?api_key=&amp;locale=en_GB&amp;sdk=joey&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D21%23cb%3Df2dd5a8b4%26origin%3Dhttp%253A%252F%252Fwww.sk.tra.com%252Ff3c14eb29%26domain%3Dwww.sk.tra.com%26relation%3Dparent.parent&amp;href=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1&amp;node_type=link&amp;width=90&amp;layout=button_count&amp;colorscheme=light&amp;show_faces=false&amp;send=false&amp;extended_social_context=false"></iframe></span></div></div></li>'+
		// '<li><div class="socialite pinterest-pinit socialite-instance socialite-loaded" data-count-layout="horizontal" data-default-href="http://pinterest.com/pin/create/button/?url='+linkToShare+'&amp;media=http%3A%2F%2Fwww.sk.tra.com%2Ffakeimages%2F13310616760678.jpg&amp;description=Ubytovanie+v+Bojniciach" data-socialite="7"><a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.sk.tra.com%2Fbojnice1&amp;media=http%3A%2F%2Fwww.sk.tra.com%2Ffakeimages%2F13310616760678.jpg&amp;description=Ubytovanie+v+Bojniciach" class="PIN_1366115029658_pin_it_button PIN_1366115029658_pin_it_button_inline PIN_1366115029658_pin_it_beside" target="_blank" data-pin-log="button_pinit" data-pin-config="beside"><span class="PIN_1366115029658_hidden" id="PIN_1366115029658_pin_count_1"><i></i></span></a></div></li>'+
	'</ul>';

	$shareContainer = $('#dynamicShareContainer').html(html);
	Socialite.load($shareContainer);

	$('#clipboardLinkShare').val(linkToShare);
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

	var favoriteSlider = $('#navBarFavorites');

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

