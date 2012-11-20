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
	location.replace(this._getLocationUrl()+anchorName);
}

/****************************************************************************************************
*	UNIVERSAL UI FUNCTIONS
****************************************************************************************************/

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

	if(currentIndex == 4) {
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
		$(this).removeClass('active');
	} else {
		$(forClass).slideDown();
		$(this).addClass('active');
	}

	return false;
}


App.prototype.buttonCompareClick = function(){
	var child = $(this).find('span');
	
	if($(child).hasClass('entypo-star')){
		$(child).removeClass('entypo-star');
		$(child).addClass('entypo-ok');
	} else {
		$(child).removeClass('entypo-ok');
		$(child).addClass('entypo-star');
	}
	
}


App.prototype.uiTabsClickChangeHashAdress = function(){

	var self = new App;
	
	$.scrollTo(this,{duration:500 , offset: -50} );
	var currentTabId = $(this).attr('href');
	self._setLocationUrlAnchor($(this).attr('href'));
	$(this).blur();

	if($(this).attr('href') == '#tabs-2'){
		/* large map plugin */
		$('#map_canvas').traMap();	
	}
}


/****************************************************************************************************
*	OBJECT DETAIL
****************************************************************************************************/

/**
*	initialize map in object detail
*/

App.prototype.initMapsObjectDetail = function(){
	$('#objectDetailListMap').trigger('click');
	/* large map plugin */
	$('#map_canvas').traMap();	
}

App.prototype.addToViewList = function(){
	var params = {
		name: $(this).attr('name'),
		thumb: $(this).attr('thumb'),
		url: $(this).attr('url'),
		id: $(this).attr('id')
	}

	console.log(params);
}

/****************************************************************************************************
*	RUNN APPS
****************************************************************************************************/


$(document).ready(function(){

	var A = new App();

	/* register listeners */
	/* UI toogle function */
	$('.toogle').click(A.uiToogleClick);
	/* compare button click */
	$('.btn-compare').click(A.buttonCompareClick);
	/* object detail init large map after small map click */
	$('.mapsImg').click(A.initMapsObjectDetail);
	/* UI tabs */
	$( ".tabs" ).tabs(A.uiSelectedTabs());
	$( ".tabs ul li a" ).click(A.uiTabsClickChangeHashAdress);
	/* UI calendar */
	$( ".datepicker" ).datepicker();	
	$('.accordion').accordion({ autoHeight: false , active: false , navigation: true, collapsible: true });
	

	$('.addToViewList').ready(A.addToViewList);


});