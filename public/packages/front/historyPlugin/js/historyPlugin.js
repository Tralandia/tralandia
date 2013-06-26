// static page history plugin

(function($){
	$.historyPlugin = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("historyPlugin", base);
		
		base.pageData = {};

		base.rentalDetailVariables = {};

		base.init = function(){
			base.options = $.extend({},$.historyPlugin.defaultOptions, options);

			// console.log(base._getPageType());

			switch(base._getPageType()){
				case 'rootHome':
						base._setRootHomeHistory();
					break;
				case 'home':
						base._setHomeHistory();
					break;
				case 'list':
						base._setListHistory();
					break;
				case 'detail':
						base._initDetail();
					break;															
			}

			// console.log(base._getHistory());
		};



		/********************************************************************************
		*	PLUGIN METHOD
		*/

		base._setListHistory = function(){
			base._setHistory(base._getListData());
		};

		base._setHomeHistory = function(){			
			base._setHistory(base._getHomeData());
		};

		base._setRootHomeHistory = function(){

		};

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

		/********************************************************************************
		*	LOCAL STORAGE METHOD
		*/

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
		*	RENTAL DETAIL METHOD
		*/

		base._initDetail = function(){

			base._setRentalDetailVariables();

			if(base._isRentalInHistory()){
				base._renderNavigationBar();
			} else {
				base._deleteHistory();
				console.log('nepatri do mojej historie');
				// @todo ajax function
			}

		};

		base._setRentalDetailVariables = function(){

			base.$prevlink = $(base.options.selectorNavBarPrevLink);
			base.$nextLink = $(base.options.selectorNavBarNextLink);
			base.$rentalListPosition = $(base.options.selectorNavBarObjectPosition);
			base.$listFullCount = $(base.options.selectorNavBarFullCount);
			base.$listName = $(base.options.selectorNavBarListName);


			base.rentalDetailVariables = $(base.options.selectorRentalinfo).data('info');
		};

		base._isRentalInHistory = function(){

			var r = false;

			$.each(base._getHistory().listData,function(k,v){				

				if(parseInt(base.rentalDetailVariables.id) == parseInt(v.id)){
					r = true;
				}
				
			});

			return r;
		};


		base._renderNavigationBar = function(){

			var data = base._getInformationFromHistory();
				dataHistory = base._getHistory();
				console.log(data);

				base.$prevlink.attr('href',data.prevLink.url)
							  .attr('title',data.prevLink.name);

				base.$nextLink.attr('href',data.nextLink.url)
							  .attr('title',data.nextLink.name);

				base.$listName.html(dataHistory.listTitle).attr('href',dataHistory.listUrl);							  						  
				base.$listFullCount.html(dataHistory.rentalCount);							  						  
				base.$rentalListPosition.html(data.currentObjectPosition);							  						  

		};		

		base._getInformationFromHistory = function(){

			var data = base._getHistory().listData;

			var r = {
				lengthHistory: data.length
			};

			$.each(data,function(k,v){				

				if(parseInt(base.rentalDetailVariables.id) == parseInt(v.id)){
					r.currentObjectPosition = k+1;
				}
				
			});

			if(r.currentObjectPosition > 1){
				r.prevLink = data[r.currentObjectPosition-2];
			} else {
				r.prevLink = false;
			}

			if(data.length > (r.currentObjectPosition-1)){
				r.nextLink = data[r.currentObjectPosition];
			} else {
				r.nextLink = false;
			} 

			return r;

		};


		/********************************************************************************
		*	HOME METHOD
		*/

		base._getHomeRentals = function(){

			var r = [];

			$(base.options.selectorHomeRentals).each(function(k,v){
				r.push($(this).find('variables').data('info'));
			});

			return r;
		}

		base._getHomeInfo = function(){
			return $(base.options.selectorHomeInfo).data('info');
		};

		base._getHomeData = function(){
			return {
				rentalCount: base._getHomeInfo().rentalCount,
				listTitle: base._getHomeInfo().listTitle,					
				listUrl: document.URL,
				listData: base._getHomeRentals(),
				paginator: false,		
			};
		}

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
		base._getListData = function(){

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
	
	$.fn.historyPlugin = function(options){return this.each(function() {if (undefined == $(this).data('historyPlugin')) {var plugin = new $.historyPlugin(this, options);}});};
	
})(jQuery);

$(function(){
	var inithistory = $('body').historyPlugin({
		// rental list selectors
		selectorRentalPostRow: '.rentalList',
		selectorPaginator: '.pagination',
		selectorListInfo: 'variables[name=listInfo]',
		localSotrageKeyName: 'historyPlugin',
		// rental detail selectors
		selectorRentalinfo: 'variables[name=rentalDetailInfo]',
		selectorNavBarPrevLink: '#staticNavBar a.prev',
		selectorNavBarNextLink: '#staticNavBar a.next',

		selectorNavBarObjectPosition: '#staticNavBar .objectPosition',
		selectorNavBarFullCount: '#staticNavBar .listFullCount',
		selectorNavBarListName: '#staticNavBar .listName',

		selectorHomeRentals: 'ul.hp-list li',
		selectorHomeInfo: 'variables[name=homeInfo]',
	});

});