var global = {};
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
var navBarShare = false;

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
		this._traMapInit = false;

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

App.prototype.uitoggleOldClick = function(){
	var span  = $(this).find('span');
	var $b  = $(this).find('b');
	var forClass = '#'+$(this).attr('for');

	var openText = $(this).attr('data-opentext');
	var closeText = $(this).attr('data-closetext');


	if($(this).hasClass('active')){
		$(forClass).slideUp('fast');
		$(this).removeClass('active').html($(this).attr('close'));
		$(this).parent().parent().find('i').addClass('entypo-open');
		$(this).parent().parent().find('i').removeClass('entypo-close');
		$b.html(openText);
	} else {
		$(forClass).slideDown('fast');
		$(this).addClass('active').html($(this).attr('opened'));
		$(this).parent().parent().find('i').addClass('entypo-close');
		$(this).parent().parent().find('i').removeClass('entypo-open');
		$b.html(closeText);
	}

	return false;
}

App.prototype.uitoggleClick = function(){

	var forClass = '.'+$(this).attr('for');
	console.log(forClass);

	var openText = $(this).attr('data-opentext');
	var closeText = $(this).attr('data-closetext');



	if($(this).hasClass('active')){
		$(forClass).slideUp('fast');
		$(this).removeClass('active').html($(this).attr('close'));

		$(this).html(openText);
	} else {
		$(forClass).slideDown('fast');
		$(this).addClass('active').html($(this).attr('opened'));

		$(this).html(closeText);		
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

	if($(this).parent().find('input').hasClass('focus')){
		$( ".datepicker" ).datepicker( "hide" );
		$(this).parent().find('input').removeClass('focus');
	} else {
		$(this).parent().find('input').focus();
		$(this).parent().find('input').addClass('focus');
	}

}

/**
*	initialize map in object detail
*/

App.prototype.initMapsObjectDetail = function(){
	$('#objectDetailListMap').trigger('click');

	$.scrollTo('#objectDetailMap',800);

	setTimeout(function(){
		//maplodader();
	},800);

}

/****************************************************************************************************
*	CONTACT PAGE FORM CREATOR
****************************************************************************************************/


App.prototype.loadContactForm = function(){
	
	// $('#contactFormCover').removeClass('hide');
	// $('#contactFormCover').removeClass('hide');
	// $.scrollTo('#contactFormCover',800);

	// return false;
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
*	AUTO SELECT TEXTAREA
****************************************************************************************************/

App.prototype.autoselect = function(){
	$(this).select();
}



/****************************************************************************************************
*	RUNN APPS
****************************************************************************************************/


$(document).ready(function(){

	jsVariablesReplace();


	// Prevent "empty" links to "click"
	$("a[href='#']").click(function() {
		return false;
	});

	$('.toggleHide').toggle(function(){
		$(this).addClass('active');
		$(this).find('span').html($(this).data('closeText'));
		$($(this).data('toggleHideFor')+'.hide').removeClass('hide');
		return false;
	} , function(){
		$(this).removeClass('active');
		$(this).find('span').html($(this).data('openText'));
		$($(this).data('toggleHideFor')).addClass('hide');
		return false;
	});


	$('form:not(.searchForm) .select2.notFulltext').select2({
		dropdownCssClass: "notFulltext",
		allowClear: true,
		minimumResultsForSearch: 'X',
	});

	$("form:not(.searchForm) select.select2:not(.select2.notFulltext)").select2({dropdownCssClass: "mainForm"});

	$('form:not(.searchForm) .select2.sidebarCountry').select2({
		dropdownCssClass: 'searchSelect'
	});

	var A = new App();

	$('.autoselect').click(A.autoselect);

	// $('.socialIconsDetail').socialIconsDetail();

	$('li[tooltip="true"]').hover(function(){
		var $self = $(this);

		var attr = $(this).attr('tooltip-location');

		var position = 'top';

		if (typeof attr !== 'undefined' && attr !== false) {
			position = attr;
		}

		$(this).tooltip({
			animation: false,
			placement: position,
			trigger: 'manual'
		});

		$(this).tooltip('show');
	} , function(){
		$(this).tooltip('hide');
	})



	// click map tooltip
	$('.point').tooltip();

	// alerts
	$(".alert").alert();
	$('.alert:not(.alert-error)').customAlert();


	$('.calendarEdit').calendarEdit();

	//$('.mapControl').mapControl();

	$('.phraseForm').phraseForm();

	$('.control-photo').galleryControl();

	/* register listeners */
	/* UI toggle function */
	$('.toggle').click(A.uitoggleClick);

	/* object detail init large map after small map click */
	$('.mapsImage').click(A.initMapsObjectDetail);


	$('.loadContactForm').click(A.loadContactForm);

	/* UI calendar */
	$(".datepickerIcon").click(A.datepickerIcon);
	$(".datepickerIcon").live('click',A.datepickerIcon);
	$('.accordion').accordion({ autoHeight: false , active: false , navigation: true, collapsible: true });

	/* add attachment file  */
	$('.attachment').click(A.attachment);

	/* http://www.sk.tra.com/ticket/ */
	$('#ticketMesageCannedSelect').change(A.ticketMesageCannedSelect);



	/* rental open modal contact dialog */
	$('.openContactForm').click(A.openContactForm);
	$('.cancelContactForm').click(A.cancelContactForm);

	/* after show Rental object detail append this object to View list in local storage */
	$('.addToViewList').objectVisitList(A);

	/* @todo */
	//$('.favoriteSlider').favoriteSlider(A);


	// closeForgottenPasswordForm
	$('#forgottenPasswordOpen').click(A.forgottenPasswordOpen);
	$('#closeForgottenPasswordForm').click(A.closeForgottenPasswordForm);





	// nahrada pre zobrazenie lang menu
	var langmenuOpen = false;
	$('#langMenuOptionsOpen').click(function(){
		if(!langmenuOpen){
			$('#langMenuOptions').show();
			$(this).find('i.entypo-chevron-down').removeClass('entypo-chevron-down').addClass('entypo-chevron-up');
			langmenuOpen = true;
		} else {
			$('#langMenuOptions').hide();
			$(this).find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
			langmenuOpen = false;
		}

		return false;
	});

	// nahrada pre zobrazenie lang menu
	var socialIconsMenu = false;


	$('#socialIcons').click(function(){

		var $arrow = $(this).find('span');
		var socialShitCover = '#socialIconsMenu';

		if(!socialIconsMenu){

			$arrow.html('&#59231;');
			$(socialShitCover).show();

			var loadingWidgetCount = 0;

			Socialite.load(socialShitCover);
			socialIconsMenu = true;
		} else {

			$arrow.html('&#59228;');
			$(socialShitCover).hide();
			socialIconsMenu = false;
		}

		return false;
	});


$('body').click(function(event){

	if(langmenuOpen){
		$('#langMenuOptions').hide();
		$('#langMenuOptionsOpen').find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
		langmenuOpen = false;
	}
	if(socialIconsMenu){
		$('#socialIcons').find('span').html('&#59228;');
		$('#socialIconsMenu').hide();
		socialIconsMenu = false;
	}

	});




$('a').live('click',function(){
	if($(this).attr('href') == '#'){
		return false;
	}
});


$('.pricePhrase').pricePhrase();

_selectSetSelectedValue();

});

// replace js variables

function jsVariablesReplace() {

	var dataPrefix = 'data-js-';
	var rmultiDash = /([a-z])([A-Z])/g;

	$('variables').each(function(i){
		var selector = $(this).attr('for');

		$.each($(this).data() , function(k,v){

			k = k.replace( rmultiDash, "$1-$2" ).toLowerCase();

			var currentElement = $(selector+' ['+dataPrefix+k+']');
			var dataAttrName = 'data-'+currentElement.attr(dataPrefix+k);
			currentElement.attr(dataAttrName,v);
		})

	});
}



function _selectSetSelectedValue(){

	$('variables').each(function(i){
		var selector = $(this).attr('for');

		$(selector+' select,'+selector+' input').each(function(k,v){

			var dataSelector = '';

			switch($(this).prop('tagName')){
				case 'SELECT':					
				dataSelector = 'data-selected';
				break;				
				case 'INPUT':
				dataSelector = 'data-'+$(this).attr('name')+'-name';
				break;

			}

			

			var attr = $(this).attr(dataSelector);

			if (typeof attr !== 'undefined' && attr !== false) {



				$(this).val(attr);

				// console.log(attr);
			}


		})

	});

}
















