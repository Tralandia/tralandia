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

		if($(this).hasClass('onClickThisHide')){
			$(this).hide();
		}

		return false;
	} , function(){
		$(this).removeClass('active');
		$(this).find('span').html($(this).data('openText'));
		$($(this).data('toggleHideFor')).addClass('hide');
		return false;
	});


	$('form:not(.searchForm) .select2.notFulltext').select2({
		dropdownCssClass: "notFulltext disableFulltext",
		allowClear: true,
		minimumResultsForSearch: -1,
		// placeholder:true,
	});

	$("form:not(.searchForm) select.select2:not(.select2.notFulltext)").select2({dropdownCssClass: "mainForm"});

	$('form:not(.searchForm) .select2.sidebarCountry').select2({
		dropdownCssClass: 'searchSelect'
	});

	$('.selectLanguage').select2({
		dropdownCssClass: 'orangeSelect',
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
	$('.tooltipElem').tooltip();


	// alerts
	$(".alert").alert();
	$('.alert:not(.alert-error)').customAlert();

	$('.scrollTo').click(elemScrollTo);
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

		if(countryMenuOpen){
			$('#countryMenuOptions').hide();
			$('#countryMenuOptionsOpen').find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
			countryMenuOpen = false;
		}

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
	var countryMenuOpen = false;
	$('#countryMenuOptionsOpen').click(function(){

		if(langmenuOpen){
			$('#langMenuOptions').hide();
			$('#langMenuOptionsOpen').find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
			langmenuOpen = false;
		}

		if(!countryMenuOpen){
			$('#countryMenuOptions').show();
			$(this).find('i.entypo-chevron-down').removeClass('entypo-chevron-down').addClass('entypo-chevron-up');
			countryMenuOpen = true;
		} else {
			$('#countryMenuOptions').hide();
			$(this).find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
			countryMenuOpen = false;
		}

		return false;
	});
	

	$('#socialIcons').socialShareMenu();



// selec2 onBlur close
var clickSelect2 = false;

$('div.select2 , div.sidebarLocation , div.selectLanguage').live('click',function(){
	clickSelect2 = true;
});

$('div.sidebarLocation , div.selectLanguage').live('click',function(){
	var opened = $('body').attr('data-open-select');
	if(!opened){
		$('body').attr('data-open-select',true);
	} else {
		$('body').attr('data-open-select',false);
	}

});


$('body').live('click',function(){
	setTimeout(function(){

		var opened = $('body').attr('data-open-select');


		if(opened){

			if(clickSelect2){
				clickSelect2 = false;
			} else {
				$('.select2').select2('close');
				$('.sidebarLocation').select2('close');
				$('.selectLanguage').select2('close');
				clickSelect2 = false;
			}
			
		} 
		
	},10);	
});



$('body').click(function(event){

	// $('div:not(.select2-choice)').select2('close');

	if(langmenuOpen){
		$('#langMenuOptions').hide();
		$('#langMenuOptionsOpen').find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
		langmenuOpen = false;
	}

	if(countryMenuOpen){
		$('#countryMenuOptions').hide();
		$('#countryMenuOptionsOpen').find('i.entypo-chevron-up').removeClass('entypo-chevron-up').addClass('entypo-chevron-down');
		countryMenuOpen = false;
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
// docasne riesenie @todo zapracovat 
function jsVariablesReplace() {

	var dataPrefix = 'data-js-';
	var rmultiDash = /([a-z])([A-Z])/g;

	$('variables').each(function(i){
		var selector = $(this).attr('for');

		$.each($(this).data() , function(k,v){

			k = k.replace( rmultiDash, "$1-$2" ).toLowerCase();

			var currentElement = $(selector+' ['+dataPrefix+k+']');

			if(typeof currentElement.attr(dataPrefix+k) != 'undefined'){

				var dataAttrName = 'data-'+currentElement.attr(dataPrefix+k);
				currentElement.attr(dataAttrName,v);

			} else {
				
				currentElement = $(selector+' [name='+k+']');
				
				setTimeout(function(){
					currentElement.select2('val',v);
				},10); 
			}

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



(function($){
	$.socialShareMenu = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("socialShareMenu", base);
		
		base.initMenu = false;
		base.openMenu = false;
		base.socialShitCover = '#socialIconsMenu';

		base.init = function(){
						
			base.options = $.extend({},$.socialShareMenu.defaultOptions, options);
			base.bind();
			base.urlInit();
		};

		base.bind = function(){
			base.$el.click(base._toggleOpenMenu);
			
			$('body').click(function(){

				base._closeMenu();
				base.openMenu = false;				

			});

		};

		base.urlInit = function(){

			if(window.location.hash == '#share'){

				setTimeout(function(){
					
					base._toggleOpenMenu();

				},10);
			}
		}

		base._toggleOpenMenu = function(){

			if(!base.initMenu){
				base._firstOpen();
				base.initMenu = true;
				base.openMenu = true;
			} else if (!base.openMenu){
				base._openMenu();
				base.openMenu = true;
			} else {
				base._closeMenu();
				base.openMenu = false;
			}

			return false;

		};

		base._firstOpen = function(){

			$(base.socialShitCover).find('a.twitter-share').attr('data-url',document.URL);
			$(base.socialShitCover).find('a.facebook-like').attr('data-href',document.URL);
			$(base.socialShitCover).find('a.googleplus-one').attr('href','https://plus.google.com/share?url='+document.URL).attr('data-href',document.URL);

			base._openMenu();

			Socialite.load(base.socialShitCover);
		};
		
		base._openMenu = function(){

			var $arrow = $(this).find('span');
			
			$arrow.html('&#59231;');
			$(base.socialShitCover).show();			
						
		};

		base._closeMenu = function(){
			$(base.socialShitCover).hide();
		}

		base.init();
	};
	
	$.socialShareMenu.defaultOptions = {
	};
	
	$.fn.socialShareMenu = function(options){return this.each(function(){(new $.socialShareMenu(this, options));});};
	
})(jQuery);




function elemScrollTo(){
	// console.log($(this).data('scrollTo'));

	$.scrollTo($(this).data('scrollTo'),800 , {offset: { top: -10} });
}

function ulShowMore(obj) {
	$(obj).hide();
	$(obj).parents('ul').find('li.hide').removeClass('hide');
	return false;
} 


$(function(){
	$('.linkToSocialSite').click(function(){
		window.open($(this).attr('href'),'_blank');
	});	
});







