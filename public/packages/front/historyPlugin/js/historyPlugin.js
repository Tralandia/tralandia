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
			// base.returnHistory = $.extend({},$.historyPlugin.returnHistory);
			// console.log(base._getPageData());
			// base.historySet();
			// console.log( base._getHistory());

			console.log(base._getPageType());
		};

		



		/********************************************************************************
		*	PLUGIN METHOD
		*/

		base._getPageType = function(){
			if($("div.rentalDetailPage").length > 0){
				return 'detail';
			} else if ($("form.searchForm").length > 0 && $(".rentalList").length > 0) {
				return 'list';
			} else if ($("form.searchForm").length > 0) {
				return 'home';
			} else {
				return 'rootHome';
			}
		}

		base.historySet = function(){
			base._setHistory(base._getPageData()); // set new history to local storage
		}

		// history method

		base._getHistory = function(){
			return $.jStorage.get(base.options.localSotrageKeyName);
		};

		base._setHistory = function(v){
			return $.jStorage.set(base.options.localSotrageKeyName,v);
		};

		base._deleteHistory = function(){
			return $.jStorage.set(base.options.localSotrageKeyName,null);
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
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('historyPlugin')) {

                // create a new instance of the plugin
                // pass the DOM element and the user-provided options as arguments
                var plugin = new $.historyPlugin(this, options);

                // in the jQuery version of the element
                // store a reference to the plugin object
                // you can later access the plugin and its methods and properties like
                // element.data('pluginName').publicMethod(arg1, arg2, ... argn) or
                // element.data('pluginName').settings.propertyName
                $(this).data('historyPlugin', plugin);

            }

        });
	};
	
})(jQuery);

$(function(){
	var inithistory = $('body').historyPlugin({

		selectorRentalPostRow: '.rentalList',
		selectorPaginator: '.pagination',
		selectorListInfo: 'variables[name=listInfo]',
		localSotrageKeyName: 'historyPlugin'
	});

});