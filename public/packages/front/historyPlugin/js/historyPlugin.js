// static page history plugin


(function($){
	$.historyPlugin = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("historyPlugin", base);
		
		base.pageData = {};

		base.init = function(){
			base.options = $.extend({},$.historyPlugin.defaultOptions, options);
			console.log(base._getPageData());
		};

		// fetch rentals to array 
		base._getListRentals = function(){
			var r = [];
			$(base.options.selectorRentalPostRow).each(function(k,v){
				r.push({
					id: parseInt($(v).find('button').attr('rel')),
					name: $(v).find(base.options.selectorRentalLink).html(),
					url: $(v).find(base.options.selectorRentalLink).attr('href'),
				});
			});
			return r ;			
		};

		// check/set paginator 
		base._getPaginator = function(){
			
			var paginator = base.$el.find(base.options.selectorPaginator);

			if(paginator.length > 0){

				paginatorCurrentPage = paginator.find('li.current a span').html();

				paginator = {
					current: parseInt(paginatorCurrentPage),
				};

			} else {
				paginator = false;
			}

			return paginator;
		};

		// get rental list name
		base._getRentalListName = function(){
			var name = $(base.options.selectorListTitle).clone();
				name.find('span').remove();
				return name.html();
		};

		// get rental list count
		base._getRentalListFullCount = function(){
			return parseInt($(base.options.selectorRentalCount).html().match(/[0-9]+/g)[0]);
		};

		// get all rental list data
		base._getPageData = function(){

			return {
				rentalCount: base._getRentalListFullCount(),
				listTitle: base._getRentalListName(),					
				listUrl: document.URL,
				listData: base._getListRentals(),
				paginator: base._getPaginator(),		
			};
		}

		base.init();
	};
	
    $.historyPlugin.defaultOptions = {
        selectorListTitle: "",
        selectorRentalCount: "",
        selectorRentalPostRow: "",
        selectorRentalLink: "",
        selectorPaginator: "",
    };	
	
	$.fn.historyPlugin = function(options){
		return this.each(function(){(new $.historyPlugin(this, options));});
	};
	
})(jQuery);

$(function(){
	$('body').historyPlugin({
		selectorListTitle: '#content h1',
		selectorRentalCount: 'h1 span',
		selectorRentalPostRow: '.rentalList',

		selectorRentalLink: 'h2 a',

		selectorPaginator: '.pagination',
	});
});