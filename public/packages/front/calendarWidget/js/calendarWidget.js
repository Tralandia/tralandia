(function($){
	$.calendarWidgetControl = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("calendarWidgetControl", base);
		
		base.init = function(){
			if( typeof( radius ) === "undefined" || radius === null ) radius = "20px";
			
			base.radius = radius;
			
			base.options = $.extend({},$.calendarWidgetControl.defaultOptions, options);

		};
		
		base.init();
	};
	
	$.calendarWidgetControl.defaultOptions = {
		radius: "20px"
	};
	
	$.fn.calendarWidgetControl = function(radius, options){
		return this.each(function(){
			(new $.calendarWidgetControl(this, radius, options));

			var self = this;
			var $self = $(this);
			var $viewer = $('#calendarIframeViewer');
			var $outputUser = $('#htmlOutputFromViewer');

			var linkTemplate = $self.attr('data-link-template');
			var rightIframeMargin = 10;
			var marginTopBody = 10;

			$self.find('select').on('change',function(){

				var calendarWidth = 136;
				var calendarHeight = 150;

				$form = $(this).parents('form');

				var data = {
					rows: $self.find('[name=rows]').val(),
					columns: $self.find('[name=columns]').val(),
					language: $self.find('[name=iso]').val(),
					id: $form.attr('data-id')
				};

				// console.log(data);

				if(data.columns > 0 & data.rows > 0 & data.iso != 0){

					url = linkTemplate
						.replace('__rental__', data.id)
						.replace('__language__', data.language)
						.replace('__columns__', data.columns)
						.replace('__rows__', data.rows);

					$.get(url, function(response) {
						console.log(response);
						$viewer.html(response.code);
						$outputUser.val(response.code);
					});

				}
					
			});
			
			$self.find('select[name=iso]').trigger('change');

		});
	};
	
})(jQuery);


$(document).ready(function(){
	$('#calendarWidgetControl').calendarWidgetControl();

	$("#rentalSelect").on('change', function() {
		redirect = $(this).find(":selected").attr('redirect');
		console.log(redirect);
		if (redirect) {
			window.location.href = redirect;
		}
	});
});