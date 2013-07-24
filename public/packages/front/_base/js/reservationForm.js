function showButtonLoader(){
	
	var $button = $(this).find('button[type=submit]');

	$button.addClass('active').attr('disabled',1);

	var attr = $button.attr('data-loading-text');

	if (typeof attr !== 'undefined' && attr !== false) {
		$button.find('small').html($button.data('loadingText'));
	}


}


$(function(){

		// $('form[method="post"]').submit(showButtonLoader)
		// 					.live('submit',showButtonLoader);

		$('form[method="post"]').submitButtonPlugin({
			buttonActiveClass: 'active',
			cacheFormSelector: 'cache',
			select2Selector: 'select2',
			cacheIndexName: 'formDataCache',
			buttonSelector: 'button[type=submit]'
		});
});


(function($){
	$.submitButtonPlugin = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		
		base.$el.data("submitButtonPlugin", base);
		
		base.init = function(){

			base.options = $.extend({},$.submitButtonPlugin.defaultOptions, options);
			base.$button = base.$el.find(base.options.buttonSelector);

			base.buttonLoadingText = base.$button.attr('data-loading-text');

			base._bind();

			base._setForm();

		};

		base._bind = function(){
			base.$el.live('submit',base._submit);
		};

		base._submit = function(){
			base.$button.addClass(base.options.buttonActiveClass).attr('disabled',1);

			if(base.$el.hasClass(base.options.cacheFormSelector)){
				base._cacheForm();
			}
		};

		base._cacheForm = function(){

			var data = [];

			base.$el.find('input,select,textarea').each(function(){
				var elem = {
					id: $(this).attr('id')
				};

				elem.value = $(this).attr('value');

				data.push(elem);
			});


			$.jStorage.set(base.$el.attr('id'), data);

		};

		base._setForm = function(){
			
			var exist = base._existCache();
			if(exist){
				setTimeout(function(){
					$.each(exist,function(k,v){
						var $input = base.$el.find('#'+v.id);

							$input.val(v.value);

							if($input.hasClass(base.options.select2Selector)){
								$input.select2('val',v.value);
							}
					});					
				},100);
			}

		};

		base._existCache = function(){
			var formId = base.$el.attr('id');

			var e = $.jStorage.get(base.$el.attr('id'));

				if(typeof e == 'undefined' || e == false){
					return false;
				} else {
					return e;
				}

		};

		base.init();
	};
	
	$.submitButtonPlugin.defaultOptions = {
	};
	
	$.fn.submitButtonPlugin = function( options){
		return this.each(function(){
			(new $.submitButtonPlugin(this, options));


		});
	};
	
})(jQuery);