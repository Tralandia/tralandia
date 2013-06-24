$(function(){

	if(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		registerGa();
	} else {
		$(document).mouseover(registerGa)
				   .scroll(registerGa);
	}			

});

var gaInit = false;

function registerGa(){
	if(!gaInit){

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-1541490-17']);
		_gaq.push(['_setDomainName', 'none']);
		_gaq.push(['_setAllowLinker', true]);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();


		gaInit = true;
	}	
}