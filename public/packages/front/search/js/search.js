
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

			var url = $(this).attr('data-autocomplete-url');

			var placeholder = $(this).attr('data-placeholder');

			var noResults = $(this).data('noResultsText');

			// var conditionsText = $('.sidebarLocation').attr('data-conditions-text');

			var minimumInputLengthtext = $(this).attr('data-format-input-too-short');

			$(this).select2({

				dropdownCssClass: 'searchSelect',
				formatInputTooShort: function(w,v){
					return minimumInputLengthtext;
				},
				initSelection: function(element, callback) {					
					// this.self = element;        		
					callback({id:0,name:$(element).attr('data-placeholder')});
				},


				placeholder: placeholder,
				minimumInputLength: 3,
				// data:[{id:0,type:'other',name:'enhancement'},{id:1,type:'other',name:'bug'},{id:2,type:'other',name:'duplicate'},{id:3,type:'other',name:'invalid'},{id:4,type:'other',name:'wontfix'}],

				ajax: { 
					url: url,
					dataType: 'json',
					data: function (term, page) {
						return {
							string: term, 
						};
					},
					results: function (data, page) {

						var r =[];					

						if(typeof data.localitiesAndRegions != 'undefined'){
							
							$.each(data.localitiesAndRegions,function(k,v){

								r.push({
									id: v.slug,
									name: v.name,
									nameSource: v.nameSource
								});

							});

							return {results:r};    		
						} else {
							
							return {results:''} ;
						}


					}
				},

				formatNoMatches: function(search){
					return noResults;
				},

				formatResult: function(r){

					if(r == false){
						
					} else {
						if(typeof r.nameSource != 'undefined'){
							return r.name+' <span class="nameSource">('+r.nameSource+')</span>';
						} else {
							return r.name;
						}						
					}

				}, 

				formatSelection: function(r){



					if(r == false){
						
					} else {
						if(typeof r.nameSource != 'undefined'){
							return r.name+' <span class="nameSource">('+r.nameSource+')</span>';
						} else {
							return r.name;
						}						
					}

				},
			   
				
				escapeMarkup: function (m) { return m; }
			});


			var attr = $(this).attr('data-location-name');

			if (typeof attr !== 'undefined' && attr !== false) {

				$(this).select2('data',{
					name:attr,
					id: $(this).attr('data-location-slug')
				});

				// $(this).parent().addClass('selected');

				searchCriteriumSetActive(this);


			}



		});
	};

})(jQuery);






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

function generateRedirectUrl(count){

	var path = [];

	$.each($('.searchForm select.path:not(.selectRedirect),input.path,input.nospam').serializeArray(),function(k,v){
		path.push(v.value);
	});

	// remove empty eements from array
	path = $.grep(path,function(n){
		return(n);
	});

	path = path.join('/');

	var p = $('.searchForm').find("select[value][value!='']:not(.path), input[value][value!=''][name!='location'][name!='do']:not(.path,.nospam)").serialize();

	var allParameetrs = p+path;
		allParameetrs = allParameetrs.length;

		if(allParameetrs == 0){

			if(window.location.pathname.length > 1){
				url = document.domain;
			} else {
				return false;
			}

		}

	var url = path+(p != '' ? '?'+p : '');

	if (count) {
		if (p.length == 0) {
			url+='?do=searchBar-getSearchCount';
		} else {
			url+='&do=searchBar-getSearchCount';
		}
	}

	return url;
}


function updateCriteriaCount(){

	$('#advancedSearchCriteriaButton:not(.active)').trigger('click');

	var url = generateRedirectUrl(true);

	if(url){
		url = '/'+url;
	} else {
		$('#getSearchCount').html('');
		$('#searchControlLink').attr('href','#');
		return false;		
	}

	if($('.searchForm').find("select[value][value!='']:not(.sidebarCountry),input[type=hidden]").serialize().length > 0){
		$.ajax({
		  url: url,
		  beforeSend: function(){
		  $('#getSearchCount').html('');	  	
			searchLoader('start');
		  },
		}).done(function(d) {
			searchLoader('stop');
		  $('#getSearchCount').html(d.label);
			  if(d.count == 0){
				$('#searchControlLink').attr('href','#');
			  }
		});			
	} else {
		$('#getSearchCount').html('');
		$('#searchControlLink').attr('href','#');
	}


}

function searchLoader(status){
	switch(status){
		case 'start':
			$('form.searchForm #searchLoaderStatus').css({
				'visibility':'visible'
			});
		break;
		case 'stop':
			$('form.searchForm #searchLoaderStatus').css({
				'visibility':'hidden'
			});
		break;		
	}
}

function updateSerachLinkUrl(){

	var $filterLinkButton = $('#searchControlLink');

	var url = generateRedirectUrl(false);

	if(url !== false) {
		url = '/'+url;
	} else {
		url = '#';
	}

	if(window.location.protocol+'//'+document.domain+'/'+generateRedirectUrl(false) == location.href) {
		url = '#';
	} 	

	$filterLinkButton.attr('href',url);
	var link = $filterLinkButton[0].outerHTML;
	var parent = $filterLinkButton.parent();
		$filterLinkButton.remove();
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
			$(select).parent().append('<a href="#" class="btnSearchClose"><i class="icon-remove"></i></a>');
		}
	}		
}

function searchCriteriumSetInactive(select){
	$(select).parent().find('.btnSearchClose').remove();
	$(select).parent().removeClass('selected');		
}

function _updatePriceTo(){	

	var $priceTo = $('select.sidebarPriceTo');
	var $priceFrom = $('select.sidebarPriceFrom');

	var priceFromValue = $priceFrom.val();

	var priceToValue = $priceTo.val();

	if(priceToValue > 0 ){
		if(priceFromValue > priceToValue){
			$priceTo.parents('.inputFilterSearch').removeClass('selected').find('.btnSearchClose').remove();
			$priceTo.select2('val','');
		}
	}

	$priceTo.find('option').each(function(k,v){

		$(this).attr('disabled',false);

		if(parseInt($(this).val()) <= parseInt(priceFromValue)){
			$(this).attr('disabled',true);
			
			// console.log('pricefrom'+priceFromValue);
		}
	});
	
}

$(function(){

	_searchSelect2();
	_updatePriceTo();
// $('#frm-searchBar-searchForm-rentalType').select2();

	$('[data-autocomplete-url]').searchFormSuggest();

	$('.searchForm .select2.disabledFulltext').select2({
		dropdownCssClass: 'searchSelect disableFulltext',
		allowClear: true,
		minimumResultsForSearch: 99,
		placeholder:true,
	});


	$(".select2 , .sidebarLocation").on("select2-opening",function(e){

		var cssClass = $(this).data('cssClass');
			if(typeof cssClass != 'undefined'){
				setTimeout(function(){
					$('body').find('#select2-drop').addClass(cssClass);
				},1);
			}

	});

	$('.searchForm .select2:not(.disabledFulltext)').select2({
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
			$('body').attr('data-open-select',true);
		} else {
			$('body').removeAttr('data-open-select');
		}
	});



	$('.searchForm .select2 , input[data-autocomplete-url] , input[name="address"] , input[name="viewport"]').on('change',function(e){

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


	$('.searchForm .select2.sidebarPriceFrom').on('change',function(e){
		_updatePriceTo();
	});	

	

	$('.btnSearchClose').on('click',closeCriteriaSelectButton)
						.live('click',closeCriteriaSelectButton);

	var $geocomplete = $('.searchForm input#geocomplete');
	var country = $geocomplete.data('country');

	$geocomplete.geocomplete({
			country: country,
			types: ['geocode','establishment']
		})
		.bind("geocode:result", geocodeResult)
		.bind("geocode:error", geocodeError)
		.bind("geocode:multiple", geocodeMultiple);

});

function geocodeResult(event, result) {
	var $geocomplete = $(this).parent('div');

	if (result.geometry) {
		if (result.geometry.location) {
			var latitude = result.geometry.location.lat();
				latitude = Math.round(latitude * 10000000) / 10000000;
			var longitude = result.geometry.location.lng();
				longitude = Math.round(longitude * 10000000) / 10000000;
			$geocomplete.find('input[name="latitude"]').val(latitude);
			$geocomplete.find('input[name="longitude"]').val(longitude);
		}
	}

	if (result.address_components) {
		for(var n in result.address_components) {
			var component = result.address_components[n];
			if (component.types && component.types[0] == 'country' && component.short_name) {
				$geocomplete.find('input[name="country"]').val(component.short_name.toLowerCase());
				break;
			}
		}
	}

	$geocomplete.addClass('selected');

	updateSerachLinkUrl();
	updateCriteriaCount();

}

function geocodeError(event, error) {
	console.log(error);
}

function geocodeMultiple(event, results) {
	console.log(results);
}

function closeCriteriaSelectButton(){
		
		$(this).parent().removeClass('selected');
		$(this).parent().find('.select2 , input[data-autocomplete-url]').select2('val','');
		$(this).remove();
		updateCriteriaCount();
		updateSerachLinkUrl();	
		_updatePriceTo();	
		$('#select2-drop').hide();

		return false;
}












