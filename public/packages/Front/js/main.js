var $class = function(definition) {
	var constructor = definition.constructor;        
	var parent = definition.Extends;
	if (parent) {
		var F = function() { };
		constructor._superClass = F.prototype = parent.prototype;
		constructor.prototype = new F();
	}
	for (var key in definition) {
		constructor.prototype[key] = definition[key];
	}
	constructor.prototype.constructor = constructor;
	return constructor;
};

function executeFunctionByName(functionName, context /*, args */) {
  var args = Array.prototype.slice.call(arguments).splice(2);
  var namespaces = functionName.split(".");
  var func = namespaces.pop();
  for(var i = 0; i < namespaces.length; i++) {
	context = context[namespaces[i]];
  }
  return context[func].apply(this, args);
}

/*
* App class 
*/

var App = $class({
	
	constructor: function (){

	}

});

/****************************************************************************************************
*	UNIVERSAL FUNCTIONS
****************************************************************************************************/


/**
* return current location url string
*/

App.prototype._getLocationUrl = function(withAnchor){

	if(typeof withAnchor == 'undefined' || withAnchor == false){
		var hash = window.location.hash;
		var url = document.location.toString();
			url = url.replace(hash,''); 
		
		return url;		
	}

	if(withAnchor == true) {
		return document.location.toString();
	}

}

/**
* return location url anchor string
*/

App.prototype._getLocationUrlAnchor = function(){
	return window.location.hash;
}

/**
* set url anchor 
*/

App.prototype._setLocationUrlAnchor = function(anchorName){
	//location.replace(this._getLocationUrl()+anchorName);

	var scrollmem = $('body').scrollTop();

	document.location.hash = anchorName;
	$('html,body').scrollTop(scrollmem);
	//window.location.href = this._getLocationUrl()+anchorName;
	return false;
}

/****************************************************************************************************
*	UNIVERSAL UI FUNCTIONS
****************************************************************************************************/

App.prototype.uiTabsClickChangeHashAdress = function(){

	var self = new App;
	
	var currentTabId = $(this).attr('href');
	self._setLocationUrlAnchor($(this).attr('href'));
	$(this).blur();

	if($(this).attr('href') == '#tabs-2'){
		/* large map plugin */
		$('#map_canvas').traMap();	
	}

}

App.prototype.attachment = function(){
	var forInput = $(this).attr('for');
	$(forInput).show();
	$(this).html('<i class="entypo-attachment"></i>');
	$(forInput).click();
	return false;
}

App.prototype.ticketMesageCannedSelect = function(){	
	$(this).parent().find('textarea').remove();
	$(this).parent().parent().find('a').remove();
	return false;
}

App.prototype.uiSelectedTabs = function(){

	var currentAnchor = this._getLocationUrlAnchor();

		currentIndex = 0;

		$('.tabs ul.ui-tabs-nav li a').each(function(index){

			if($(this).attr('href') == currentAnchor){			
				currentIndex = index;
			}

		});

	var setting = {
		active: currentIndex
	};

	if(currentIndex == 3) {
		/* large map plugin */
		$('#map_canvas').traMap();
	}

	return setting;
}

App.prototype.uiToogleClick = function(){
	var span  = $(this).find('span');

	var forClass = '#'+$(this).attr('for');

	if($(this).hasClass('active')){
		$(forClass).slideUp();
		$(this).removeClass('active').html($(this).attr('close'));
		$(this).parent().parent().find('i').addClass('entypo-open');
		$(this).parent().parent().find('i').removeClass('entypo-close');
	} else {
		$(forClass).slideDown();
		$(this).addClass('active').html($(this).attr('opened'));
		$(this).parent().parent().find('i').addClass('entypo-close');
		$(this).parent().parent().find('i').removeClass('entypo-open');
	}

	return false;
}







App.prototype.in_array = function(array, value) {

	var r = false ;

	$.each(array , function(k,v){
		if(v == value) r = true;
	});

	return r; 
}

/****************************************************************************************************
*	LOCAL STORAGE FUNCTION
****************************************************************************************************/

App.prototype.storageSet = function(key,value){
	$.jStorage.set(key, value);
}

App.prototype.storageGet = function(key){
	return $.jStorage.get(key);
}

App.prototype.storageDelete = function(key){
	$.jStorage.deleteKey(key);
}


/****************************************************************************************************
*	RENTAL DETAIL + RENTALI LIST
****************************************************************************************************/

/*
* add to favorites function
*/ 

App.prototype.addToFavorites = function(){

	var self = new App;

	var list = self.storageGet('favoritesList');

	var data = {
		id: parseInt($(this).attr('rel')),
		link: $(this).attr('link'),
		thumb: $(this).attr('thumb'),
	}

	var favoriteSlider = $('#compareList');

	if($(this).hasClass('selected')){
		// remove from list

		newList = new Array();

		$.each(list,function(k,v){
			if(v.id != data.id){
				newList.push(v);
			}			
		});

		// save new list to local storage 

		if(newList.length == 0){
			self.storageDelete('favoritesList');
		} else {
			self.storageSet('favoritesList' , newList);
		}

		$(this).removeClass('selected');

		// remove from favorites slider 		
		// if page is Rental:detai 

		if(favoriteSlider.length > 0){
			favoriteSlider.find('ul li.rel-'+data.id).remove();
			
			//@todo vsade aplikovat

		}

		if(newList.length == 0){
			$('#compareList').parent().parent().parent().hide();
		}


	} else {

		if(typeof list == 'undefined' || list == null){
			// if favorites dont exist 
			var list = new Array();
			list[0] = data;

			self.storageSet('favoritesList',list);
			favoriteSlider.parent().parent().parent().show();

			var newLi = $('<li></li>');
				newLi.addClass('current');
				newLi.addClass('rel-'+data.id);

			var sliderList = favoriteSlider.find('ul');
				newLi.appendTo(sliderList);

		} else {

			if(!self._checkIdInObject(list,data.id)){
				// write unique data
				list.push(data);
				self.storageSet('favoritesList',list);
				$(this).addClass('selected');

				// append to favorites slider (if exist)

				if(favoriteSlider.length > 0){



					var newLi = $('<li></li>').css('background-image','url('+data.thumb+')');
						newLi.addClass('current');
						newLi.addClass('rel-'+data.id);

						var sliderList = favoriteSlider.find('ul');
							newLi.appendTo(sliderList);

					//favoriteSlider.find('ul li.rel-'+data.id).remove();
				}

			}

		}		
	}

}

App.prototype._checkIdInObject = function( object , id ){
	var r = false;
		$.each(object , function(k,v){
			
			if(v.id == id){
				r=true;
			}
		});	

	return r;
}

/****************************************************************************************************
*	RENTAL LIST
****************************************************************************************************/

App.prototype.openContactForm = function(){
	$('#ModalBox').modal();
	return false;
}

/****************************************************************************************************
*	RENTAL DETAIL
****************************************************************************************************/

App.prototype.datepickerIcon = function(){
	$(this).parent().find('input').focus();
}

/**
*	initialize map in object detail
*/

App.prototype.initMapsObjectDetail = function(){
	$('#objectDetailListMap').trigger('click');
	/* large map plugin */
	$('#map_canvas').traMap();
	$.scrollTo('#objectDetailListMap',800);	
}

/****************************************************************************************************
*	CONTACT PAGE FORM CREATOR
****************************************************************************************************/


App.prototype.loadContactForm = function(){
	
	$('#contentForForm').hide();
	$('#contactFormCover').show();

	return false;
}

App.prototype.cancelContactForm = function(){
	
	$('#contentForForm').show();
	$('#contactFormCover').hide();

	return false;
}

App.prototype.forgottenPasswordOpen = function(){
  $('#forgottenPassword').slideDown('fast', function() {
    // Animation complete.
  });
  return false;	
}

App.prototype.closeForgottenPasswordForm = function(){
	$('#forgottenPassword').slideUp();
	return false;		
}

/****************************************************************************************************
*	RUNN APPS
****************************************************************************************************/


$(document).ready(function(){

	var A = new App();

	/* register listeners */
	/* UI toogle function */
	$('.toogle').click(A.uiToogleClick);

	/* object detail init large map after small map click */
	$('.mapsImg').click(A.initMapsObjectDetail);
	/* UI tabs */
	$( ".tabs" ).tabs(A.uiSelectedTabs());
	$( ".tabs ul li a" ).click(A.uiTabsClickChangeHashAdress);
	$(window).bind('hashchange', function() {
	  $( ".tabs" ).tabs(A.uiSelectedTabs());
	});


	$('.loadContactForm').click(A.loadContactForm);
	
	/* UI calendar */
	$(".datepickerIcon").click(A.datepickerIcon);
	$('.accordion').accordion({ autoHeight: false , active: false , navigation: true, collapsible: true });
	
	/* add attachment file  */
	$('.attachment').click(A.attachment);

	/* http://www.sk.tra.com/ticket/ */
	$('#ticketMesageCannedSelect').change(A.ticketMesageCannedSelect);

	/* rental favorites list*/
	$('.addToFavorites').click(A.addToFavorites);
	$('.addToFavorites').favoriteActiveLinks(A);

	/* rental open modal contact dialog */
	$('.openContactForm').click(A.openContactForm);
	$('.cancelContactForm').click(A.cancelContactForm);

	/* after show Rental object detail append this object to View list in local storage */
	$('.addToViewList').objectVisitList(A);

	/* @todo */
	//$('.favoriteSlider').favoriteSlider(A);

	/* */
	$('#compareList').showFavoriteSlider(A);

	// closeForgottenPasswordForm
	$('#forgottenPasswordOpen').click(A.forgottenPasswordOpen);
	$('#closeForgottenPasswordForm').click(A.closeForgottenPasswordForm);

	// sidebar show hide more options	
	$('ul li.more a').click(function(e) {
		e.preventDefault();
		$ul = $(this).parents('ul');
		$ul.find('li.hidden').toggleClass('hide');
		
		var $icon = $(this).find('i');
		var currentClass = $icon.attr('class');

		if(currentClass == 'entypo-down'){
			$(this).html($(this).attr('data-open')+' <i class="entypo-up"></i>');
		} else {
			$(this).html($(this).attr('data-close')+' <i class="entypo-down"></i>');
		}

	});




});
