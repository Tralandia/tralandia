
// autosuggest form search
(function($){
	$.searchFormSuggest = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("searchFormSuggest", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.searchFormSuggest.defaultOptions, options);            

		};
		
		base.init();
	};
	
	$.searchFormSuggest.defaultOptions = {
		
	};
	
	$.fn.searchFormSuggest = function(options){
		return this.each(function(){
			(new $.searchFormSuggest(this, options));
			var self = this;
			var $self = $(this);

			var url = $self.attr('data-url');

			var placeholder = $self.attr('data-placeholder');

			var conditionsText = $self.attr('data-conditions-text');

			$('#serachSidebar').select2({
			    placeholder: placeholder,
			    minimumInputLength: 1,
			    ajax: { 
			        url: url,
			        dataType: 'json',
			        data: function (term, page) {
			            return {
			                string: term, 
			            };
			        },
			        results: function (data, page) { 
			            return {results: data.counties};
			        }
			    },
			    formatResult: function(r){
			    	return '<img class="flag" src="'+r.flag+'"> '+r.name;
			    }, 

			    formatSelection: function(r){
			    	return r.name;
			    },
			   
			    
			    escapeMarkup: function (m) { return m; } ,
			    formatInputTooShort: function (input, min) { 
			    	var n = min - input.length; 
			    	//conditionsText = conditionsText.split('%');
			    	return '';
			    	//return conditionsText[0] + n + conditionsText[1] + (n == 1? "" : "s"); 
			    }

			});



		});
	};
	
})(jQuery);

$(function(){
	$('.searchForm').searchFormSuggest();
});