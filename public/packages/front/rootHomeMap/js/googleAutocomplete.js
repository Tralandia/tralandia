function onJSONPLoad(data){
	console.log(data);
}

(function($){
	$.googleAutocompleteSuggestion = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		base.$el.data("googleAutocompleteSuggestion", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.googleAutocompleteSuggestion.defaultOptions, options);

			base.sendUrl = base.$el.data('ajaxUrl');

			base._bind();

		};

		base._bind = function(){

			var searchbox = new google.maps.places.SearchBox(base.el);

			google.maps.event.addListener(searchbox, 'places_changed', function(){
				var places = searchbox.getPlaces();
					if(places.length>0){
						console.log(places[0]);
						// base._sendData(places[0]);
					}
			});

		};

		base._sendData = function(data){
			$.ajax({
				url: base.sendUrl,
				data:data
			}).done(function() {
				// @todo
			});
		};

		base.init();
	};
	
	$.googleAutocompleteSuggestion.defaultOptions = {
	};
	
	$.fn.googleAutocompleteSuggestion = function(options){
		return this.each(function(){
			(new $.googleAutocompleteSuggestion(this, options));
		});
	};
	
})(jQuery);

$(function(){
	$('#mapSearchInput').googleAutocompleteSuggestion({});
});