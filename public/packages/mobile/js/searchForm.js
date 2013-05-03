(function($){
		$.searchForm = function(el, options){

				var base = this;
				
				base.$el = $(el);
				base.el = el;

				base.$el.data("searchForm", base);
				
				// ui elements
				base.$button = base.$el.find('a#searchControlLink');
				base.$inputs = base.$el.find('select,input');
				base.$pathInputs = base.$el.find('select.path,input.path');				
				base.$autocomplete = base.$el.find("#searchFormGeocomplete");
				base.$searchCounter = $('#getSearchCount');

				base.init = function()
				{
					base.bind();
				}
				  
				base.bind = function(){

					base.$autocomplete.geocomplete()
					  .bind("geocode:result", function(event, result){
						base.$el.find('input[name="gps"]').val(result.geometry.location.jb+','+result.geometry.location.kb);
						base.change();
					});

					base.$inputs.on('change' , base.change);

				}

				base.change = function(){
					// console.log(base.createLink(false));
					// console.log(base.createLink(true));
					base.updatePriceRangeInput();
					base.getCount();
					base.$button.attr('href',base.createLink(false));
				}

				base.updatePriceRangeInput = function(){
					var range = base.$el.find('select[name="priceRange"]').val().split('-');
						base.$el.find('select.priceRange.from').val(range[0]);
						base.$el.find('select.priceRange.to').val(range[1]);
				}

				base.createLink = function(count){

					var getVars = base.$el.find("select[value!='']:not(.path,.none),input[value!='']:not(.path)").serialize();

					var pathVars = base.$el.find('select.path').val();

					var url = '';

						if(pathVars.length > 0){
							url = '/'+pathVars+'/?'+getVars;
						} else {
							url = '/?'+getVars;
						}

						if(count){
							if(pathVars.length == 0){			
								url+='?do=searchBar-getSearchCount';
							} else {
								url+='&do=searchBar-getSearchCount';
							}
						}

					// console.log(url);

					return url;				

				}

				base.getCount = function(){
					
					$.getJSON( base.createLink(true), function( data ) {
						base.renderCount(data);
					});					

				}

				base.renderCount = function(data){
					base.$searchCounter.html(data.label);
					if(data.count == 0){
						base.$button.attr('href','#').addClass('disabled');
					} else {
						base.$button.removeClass('disabled');
					}
				}

				base.init();
		};

		$.fn.searchForm = function(options){
				return this.each(function(){
						(new $.searchForm(this, options));});
		};
		
})(jQuery);

$(function(){
	$('.searchForm').searchForm();
})
