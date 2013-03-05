
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

	$('#serachSidebar').on('change',function(e){
		var val = $(this).val();
			if(val != ''){
				$('.searchForm').find('a.btnSearchClose').show();
				$('.searchForm').find('div.select2-container a div b').css({
					left: '-30px',
					position: 'relative'
				});
			}		
	});

	$('.btnSearchClose').click(function(){
		$(this).hide();
		
		$("#serachSidebar").select2("data", {id: "", title: ""}); 

		$('.searchForm').find('div.select2-container a div b').css({
			left: '0px',
			position: 'relative'
		});		

/*		$('.searchForm').find('.select2-container').css({
			'border-radius': '4px'
		});	*/	
	});
});













