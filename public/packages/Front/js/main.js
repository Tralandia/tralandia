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
* App controller 
*/

var App = $class({
	
	constructor: function (){

	}

});

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

/****************************************************************************************************
*	OBJECT DETAIL
****************************************************************************************************/

/**
*	initialize map in object detail
*/

App.prototype.initMapsObjectDetail = function(){
	$('#objectDetailListMap').trigger('click');
	
	$.scrollTo('#objectDetailListMap',{duration:3000} );
}


/****************************************************************************************************
*	RUNN APPS
****************************************************************************************************/


$(document).ready(function(){

	var A = new App();

	/* register listeners */

	$('.toogle').click(A.uiToogleClick);
	$('.btn-compare').click(A.buttonCompareClick);
	$('.mapsImg').click(A.initMapsObjectDetail);

	$( ".tabs" ).tabs();
	$( ".datepicker" ).datepicker();	
	$('.accordion').accordion({ autoHeight: false , active: false , navigation: true, collapsible: true });


});