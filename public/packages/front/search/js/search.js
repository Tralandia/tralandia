
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
			    minimumInputLength: 3,
			    ajax: { 
			        url: url,
			        dataType: 'json',
			        data: function (term, page) {
			            return {
			                string: term, 
			            };
			        },
			        results: function (data, page) { 
			        	
			        	console.log(data);
			        	
			        	var r = [];

			        	$.each(data,function(k,v){

			        		var sep = {
			        			name :k,
			        			type : 'separator'
			        		};

			        		r.push(sep);

			        		$.each(v,function(key,val){
			        			val.type = k;
			        			if(typeof val.id != 'number'){
			        				val.id = k;
			        			}
			        			r.push(val);
			        		});

			        	});

			        	//console.log(r);

			            return {results:r};
			        }
			    },
			    formatResult: function(r){

			    	switch(r.type){
			    		case 'counties':
			    			return '<img class="flag" src="'+r.icon+'"> '+r.name;
			    			break;
			    		case 'other':
			    			return r.name;
			    			break;			    		
			    		case 'rentals':
			    			return r.name;
			    			break;
			    		case 'separator':
			    			return '<h4>'+r.name+'</h4>';
			    			break;			    			

			    	}
			    	
			    }, 

			    formatSelection: function(r){

			    	//console.log(r);
			    	if(typeof r.icon == 'string'){
			    		return '<img class="flag" src="'+r.icon+'"> '+r.name;	
			    	} else {
			    		return r.name;
			    	}
			    },
			   
			    
			    escapeMarkup: function (m) { return m; }

			});



		});
	};
	
})(jQuery);

$(function(){
	$('.searchForm').searchFormSuggest();
	$('.searchForm .select2:not(#frm-searchBar-searchForm-location)').select2({
		dropdownCssClass: 'searchSelect',
		allowClear: true,
		minimumResultsForSearch: 'X',
	});	
	$('.searchForm #frm-searchBar-searchForm-location').select2({
		dropdownCssClass: 'searchSelect',
	});

	$('.searchForm .select2').on('change',function(e){

		var url = $('#sidebar').attr('data-searchcount')+'&'+$('.searchForm').serialize();
		var select2Id = '#s2id_'+$(this).attr('id');
			$select2 = $(select2Id);

		if($(this).val()){
			$select2.find('a span').css({
				color: '#333'
			})
		} else {
			$select2.find('a span').css({
				color: '#999'
			})
		}

		jQuery.getJSON(url,function(d){
			var label = $('#getSearchCount').attr('data-label');
			$('#getSearchCount').html(d.count+' '+label);
		});
	});








});













