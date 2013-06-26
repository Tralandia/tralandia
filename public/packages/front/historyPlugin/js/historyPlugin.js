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
			// console.log(base._getPageData());
			base.historySet();
			console.log( base._getHistory());
		};



		/********************************************************************************
		*	PLUGIN METHOD
		*/


		base.historySet = function(){
			base._setHistory(base._getPageData()); // set new history to local storage
		}

		// history method

		base._getHistory = function(){
			return $.jStorage.get(base.options.localsotrageKeyName);
		};

		base._setHistory = function(v){
			return $.jStorage.set(base.options.localsotrageKeyName,v);
		};

		base._deleteHistory = function(){
			return $.jStorage.set(base.options.localsotrageKeyName,null);
		};		

		/********************************************************************************
		*	RENTAL LIST METHOD
		*/

		// fetch rentals to array 
		base._getListRentals = function(){
			var r = [];
			$(base.options.selectorRentalPostRow).each(function(k,v){
				r.push($(this).find('variables').data('info'));
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


		base._getListInfo = function(){
			return $(base.options.selectorListInfo).data('info');
		}

		// get all rental list data
		base._getPageData = function(){

			return {
				rentalCount: base._getListInfo().rentalCount,
				listTitle: base._getListInfo().listTitle,					
				listUrl: document.URL,
				listData: base._getListRentals(),
				paginator: base._getPaginator(),		
			};
		}

		base.init();
	};
	
    $.historyPlugin.defaultOptions = {

        selectorRentalPostRow: "",
        selectorListInfo: "",
        selectorPaginator: "",
    };	
	
	$.fn.historyPlugin = function(options){
		return this.each(function(){(new $.historyPlugin(this, options));});
	};
	
})(jQuery);

$(function(){
	$('body').historyPlugin({

		selectorRentalPostRow: '.rentalList',

		selectorPaginator: '.pagination',

		selectorListInfo: 'variables[name=listInfo]',
		localsotrageKeyName: 'historyPlugin'
	});
});