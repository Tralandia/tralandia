
(function($) {

	$.fn.neon = function(options) {
		
		$.fn.neon.options = {
			spanSize: 'span6',
			ajaxTimeout: 2000,
			decoderUrl: '/admin/ap/decode-neon'
		};

		return this.each(function() {
			$(this)
				.addClass($.fn.neon.options.spanSize)
				.next('neonOutput')
				.addClass($.fn.neon.options.spanSize);

			$(this).bind('keyup',function (e) {
				clearTimeout($.fn.neon.neonOutputTimeout);

				$.fn.neon.input = $(this).val();
				var ajaxTimeout = $.fn.neon.options.ajaxTimeout;
				neonOutput = $(this).next('.neonOutput');

				$.fn.neon.neonOutputTimeout = setTimeout(function() {

					var decoderUrl = $.fn.neon.options.decoderUrl;

					$.ajax({
						url: decoderUrl,
						type: 'POST',
						data: {input: $.fn.neon.input},
						success: function(data) {
							$(neonOutput).html(data.output);
						}
					});

				}, ajaxTimeout);
				
			});

		});

	}

})(jQuery);
