$(function(){
	$('.socialPluginsInit').live('click',function(){

	});
});


function initAllSocialPlugins(){

	if(!isInitSocialPlugins()){
		initSocialPlugins();		
		initAllFacebookLikeButtons();		
	}
	
}

function initSocialPlugins(){
	addScript('https://apis.google.com/js/plusone.js');
	addScript('http://platform.twitter.com/widgets.js');
	addScript('http://assets.pinterest.com/js/pinit.js');

	$('body').attr('data-socialPluginsInit',true);
}

function initAllFacebookLikeButtons(){
	$('.FacebookLikeButtonContainer').each(function(){
		initFacebookLikePlugin(this);
	});
}

function initFacebookLikePlugin(elementId){
	var iframe = $('<iframe></iframe>');
	var $elem = $(elementId);
	var params = {
		href:$elem.attr('data-facebook-src'),
		locate: $elem.attr('data-facebook-locate'),
		layout: 'button_count',
		show_faces: false,
		width: 130,
		action: 'like',
		colorscheme: 'light',
		height: 80
	};

	iframe.attr({
		scrolling: 'no',
		frameborder: 0,
		src: 'http://www.facebook.com/plugins/like.php?'+jQuery.param(params)
	}).css({
		border: 'none',
		overflow: 'hidden',
		height: '20px',
		width: '130px',
		allowTransparency: 'true'
	});	

	$(elementId).html(iframe);	

}

function isInitSocialPlugins(){
	

	if(typeof $('body').attr('data-socialPluginsInit') != 'undefined' ){
		return true;
	} else {
		return false;
	}
	//console.log(typeof $('body').attr('data-socialPluginsInit'));
	//return $('body').hasAttr('data-socialPluginsInit');
}

function addScript(src){
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = src;
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);	
}