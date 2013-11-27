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
						// base._setRootHomeHistory();
					break;
				case 'home':
						// base._setHomeHistory();
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

			// console.log(base._getHistory());

			if(base._getHistory() != null){

				base._setRentalDetailVariables();

				if(base._isRentalInHistory()){
					if(base._getHistory().rentalCount > 1){
						base._renderNavigationBar();
						$(base.options.selectorPaginatorContainer).addClass('show');
					}
				} else {
					base._deleteHistory();
					// @todo ajax function
				}

			}
		};

		base._setRentalDetailVariables = function(){

			base.$prevlink = $(base.options.selectorNavBarPrevLink);
			base.$nextLink = $(base.options.selectorNavBarNextLink);
			base.$rentalListPosition = $(base.options.selectorNavBarObjectPosition);
			base.$listFullCount = $(base.options.selectorNavBarFullCount);
			base.$listName = $(base.options.selectorNavBarListName);
			base.$objectText = $(base.options.selectorObjectText);

			base.rentalDetailVariables = {
				id: $(base.options.selectorRentalinfo).data('infoId'),
				name: $(base.options.selectorRentalinfo).data('infoName'),
			};
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

		base._renderPaginator = function(data,dataHistory){

				// console.log(data);

				if(data.prevLink) {
					base.$prevlink.attr('href',data.prevLink.url).removeClass('disabled');
				} else {
					base.$prevlink.attr('href','#').addClass('disabled');
				}

				if(data.nextLink) {
					base.$nextLink.attr('href',data.nextLink.url)
								  .attr('title',data.nextLink.name)
								  .removeClass('disabled');
				} else {
					base.$nextLink.attr('href','#')
								  .attr('title','')
								  .addClass('disabled');					
				}

				base.$listName.attr('href',dataHistory.listUrl);							  						  
				base.$listFullCount.html(dataHistory.rentalCount);	
				base.$objectText.html(base.rentalDetailVariables.rentalText);
				base.$rentalListPosition.html(data.currentObjectPosition);

		};


		base._createUrlForAjax = function(dataHistory){
			var u = dataHistory.listUrl.split('?');

				if(u.length > 1){
					return u[0]+'?getDataForBreadcrumb=1';
				} else {
					return dataHistory.listUrl+'?getDataForBreadcrumb=1';
				}
		}

		base._renderNavigationBar = function(){

			var data = base._getInformationFromHistory(false);

			dataHistory = base._getHistory();

				if(typeof data.nextLink == 'undefined'){

						jQuery.getJSON( base._createUrlForAjax(dataHistory) , function(d){

							dataHistory.listData = d.listData;

							base._setHistory(dataHistory);
							data = base._getInformationFromHistory(true);

							base._renderPaginator(data,dataHistory);

						});

				} else {
					base._renderPaginator(data,dataHistory);
				}

				$(base.options.selectorListBreadcrumb).html(dataHistory.listBreadcrumb);							  						  

		};

		base._getInformationFromHistory = function(fulllistHistory){

			var dataHistory = base._getHistory();

			var data = dataHistory.listData;
			
			var r = {
				lengthHistory: data.length
			};

			var arrayCurrentPosition = 0;

			$.each(data,function(k,v){				

				if(parseInt(base.rentalDetailVariables.id) == parseInt(v.id)){
					r.currentObjectPosition = k+1;
					arrayCurrentPosition = k+1;
				}
				
			});

			if(!fulllistHistory){
				if((dataHistory.pageCountPosition > 1) && (arrayCurrentPosition < dataHistory.rentalCount)){
					r.currentObjectPosition = arrayCurrentPosition + ( (dataHistory.pageCountPosition - 1)*50 );
				}				
			} else {
				r.currentObjectPosition = arrayCurrentPosition;
			}

			if(arrayCurrentPosition > 1){
				r.prevLink = data[arrayCurrentPosition-2];
			} else {
				r.prevLink = false;
			}

			if(arrayCurrentPosition < dataHistory.rentalCount){
				r.nextLink = data[arrayCurrentPosition];
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

			var rentals = base._getHomeRentals();

			return {
				rentalCount: rentals.length,
				listTitle: base._getHomeInfo().listTitle,					
				listUrl: document.URL,
				listData: rentals,
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
				var rpush = {
					name: $(this).find('variables').data('infoName'),
					id: $(this).find('variables').data('infoId'),
					url: $(this).find('variables').data('infoUrl'),
				};
				r.push(rpush);
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
			// return $(base.options.selectorListInfo).data('info');

			return {
				name: $(base.options.selectorListInfo).data('infoName'),
				id: $(base.options.selectorListInfo).data('infoId'),
				url: $(base.options.selectorListInfo).data('infoUrl'),
				rentalCount: $(base.options.selectorListInfo).data('infoRentalCount'),
				listTitle: $(base.options.selectorListInfo).data('infoListTitle'),
				paginatorPage: $(base.options.selectorListInfo).data('infoPaginatorPage'),
				pagging: $(base.options.selectorListInfo).data('infoPagging'),
			};
		}

		// get all rental list data
		base._getListData = function(){

			return {
				rentalCount: base._getListInfo().rentalCount,
				listTitle: base._getListInfo().listTitle,				
				pageCountPosition: base._getListInfo().paginatorPage,				
				pagging: base._getListInfo().pagging,				
				listUrl: document.URL,
				listBreadcrumb: $(base.options.selectorListBreadcrumb).html(),
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
		selectorListBreadcrumb: '.breadcrumb',
		// rental detail selectors
		selectorPaginatorContainer: '#staticNavBar .info',
		selectorRentalinfo: 'variables[name=rentalDetailInfo]',
		selectorNavBarPrevLink: '#staticNavBar a.prev',
		selectorNavBarNextLink: '#staticNavBar a.next',
		selectorObjectText: '#staticNavBar .objectText',

		selectorNavBarObjectPosition: '#staticNavBar .objectPosition',
		selectorNavBarFullCount: '#staticNavBar .listFullCount',
		selectorNavBarListName: '#staticNavBar .listName',

		selectorHomeRentals: 'ul.hp-list li',
		selectorHomeInfo: 'variables[name=homeInfo]',
	});

});