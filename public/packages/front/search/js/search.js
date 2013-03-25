
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




function updateCriteriaCount(){

	var url = generateRedirectUrl();

		$.ajax({
		  url: url+'&do=searchBar-getSearchCount',
		}).done(function(d) {
		  $('#getSearchCount').html(d.count);
		});	
}

// remove empty attributes from object
function removeEmpty(o){
	var r = {};
	$.each(o,function(k,v){
		if (v != ''){
			r[k] = v;			
		}
	});
	return r;
}

function generateRedirectUrl(){

	var path = [
		$('#frm-searchBar-searchForm-location').val(),
		$('#frm-searchBar-searchForm-rentalType').val()
	];

	// remove empty eements from array
	path = $.grep(path,function(n){
	    return(n);
	});

	path = path.join('/');


	var p = {
		priceFrom: $('#frm-searchBar-searchForm-priceFrom').val(),
		priceTo: $('#frm-searchBar-searchForm-priceTo').val(),
		capacity: $('#frm-searchBar-searchForm-capacity').val(),
		spokenLanguage: $('#frm-searchBar-searchForm-spokenLanguage').val()
	};

	p = removeEmpty(p);

	p = decodeURIComponent($.param(p));

	var url = path+(p != '' ? '?'+p : '');

	return url;
	// console.log(url);
}

function updateSerachLinkUrl(){

		var url = '/'+generateRedirectUrl();
		if(url == '/' ){
			url = '#';
		}

		$('#searchControlLink').attr('href',url);
		var link = $('#searchControlLink')[0].outerHTML;
		var parent = $('#searchControlLink').parent();
			$('#searchControlLink').remove();
			parent.append(link);
			
}


function _searchSelect2() {
	$('form.searchForm select').each(function(){
		var attr = $(this).attr('data-selected');
			if (typeof attr !== 'undefined' && attr !== false) {
				searchCriteriumSetActive(this);
			}			
	});
}

function searchCriteriumSetActive(select){
	$(select).parent().addClass('selected');

	if(!$(select).hasClass('selectRedirect')){
		if ($(select).parent().find('.btnSearchClose').length == 0){
			$(select).parent().append('<a href="/" class="btnSearchClose"><i class="entypo-no"></i></a>');
		}
	}		
}

function searchCriteriumSetInactive(select){
	$(select).parent().find('.btnSearchClose').remove();
	$(select).parent().removeClass('selected');		
}

$(function(){

	_searchSelect2();

// $('#frm-searchBar-searchForm-rentalType').select2();

	$('.searchForm').searchFormSuggest({
		dropdownCssClass: 'searchSelectOrder'
	});

	$('.searchForm .select2:not(#frm-searchBar-searchForm-location , #frm-searchBar-searchForm-country)').select2({
		dropdownCssClass: 'searchSelect',
		allowClear: true,
		minimumResultsForSearch: 'X',
	});	
	



	$('.searchForm #frm-searchBar-searchForm-location,.searchForm #frm-searchBar-searchForm-country').select2({
		dropdownCssClass: 'searchSelect'
	   // matcher: function(term, text, opt) {

	   // 	// console.log(this.attr('id'));
	   // 	console.log(text);
	   // 	console.log(opt);

	   //     return text.toUpperCase().indexOf(term.toUpperCase())>=0
	   //         || opt.attr("alt").toUpperCase().indexOf(term.toUpperCase())>=0;
	   // }		
	});


	$('div.select2 a , form.searchForm .select2 a').click(function(){
		var id = $(this).parent().attr('id');
		$('div.select2:not(#'+id+')').select2('close');
		if($('#select2-drop').length == 1){
			$('body').attr('data-openSelect',true);
		} else {
			$('body').removeAttr('data-openSelect');
		}
	});




	$('.searchForm .select2').on('change',function(e){

		updateSerachLinkUrl();
		updateCriteriaCount();

		if($(this).attr('id') != 'frm-searchBar-searchForm-country'){
			if($(this).val()){
				searchCriteriumSetActive(this);
			} else {
				searchCriteriumSetInactive(this);
			}			
		}

	});

	$('.btnSearchClose').on('click',function(){
		
		$(this).parent().removeClass('selected');
		$(this).parent().find('.select2').select2('val','');
		$(this).remove();
		$('#select2-drop').remove();
		updateCriteriaCount();
		updateSerachLinkUrl();
		return false;
	});

	$('.btnSearchClose').live('click',function(){
		
		$(this).parent().removeClass('selected');
		$(this).parent().find('.select2').select2('val','');
		$(this).remove();
		$('#select2-drop').remove();
		updateCriteriaCount();
		updateSerachLinkUrl();
		return false;
	});



});














