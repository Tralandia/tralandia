(function($){
	$.rootHomeMap = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("rootHomeMap", base);
		
		base.$area = base.$el.find('area');

		base.init = function(){
			
			base.options = $.extend({},$.rootHomeMap.defaultOptions, options);
			
			base.dataTree = base.$el.find(base.options.clickMapSelector).data('tree');

			base.$locality = base.$el.find(base.options.mapLocalitySelector);

			base._bind();

		};
		
		base._bind = function(){
			base.$area.hover(base._regionHoverListener,function(){
				$(base.options.clickMapRegionSelector).addClass(base.options.regionHiddenClassName);
			});

			base.$area.on('click',base._regionClickListener);

			base.$locality.hover(base._localityHoverListener,base._localityLeaveHoverListener)
						  .click(base._localityClick);
		};

		base._localityLeaveHoverListener = function(){
				base.$el.find(base.options.mapLocalitySelector).removeClass('current').tooltip('hide');
		};

		base._localityClick = function(){
			console.log($(this));
		};

		base._localityHoverListener = function(){

			var current = $(this).data('for');

			base.$el.find(base.options.mapLocalitySelector).removeClass('current');

			base.$el.find(base.options.mapLocalitySelector+'.locId'+current).addClass('current').tooltip('show');
		};

		base._regionHoverListener = function(){
			$(base.options.clickMapRegionSelector).addClass(base.options.regionHiddenClassName);
			$(base.options.clickMapRegionSelector+'.rid'+$(this).attr('rid')).removeClass(base.options.regionHiddenClassName);
		};

		base._regionClickListener = function(){
			console.log($(this).attr('rid'));
			return false;
		};

		base.init();
	};
	
	$.rootHomeMap.defaultOptions = {
	};
	
	$.fn.rootHomeMap = function(options){
		return this.each(function(){
			(new $.rootHomeMap(this, options));
		});
	};
	
})(jQuery);

$(function(){
	$('#rootHomeMapContainer').rootHomeMap({
		clickMapRegionSelector: '.clickMapRegion',
		regionHiddenClassName: 'tempShow',
		mapLocalitySelector: '.mapPoint',
		clickMapSelector: '.click'
	});
});