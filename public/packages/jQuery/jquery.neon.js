
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

			$.fn.neon.getOutput(this);

			// Onchange events
			$(this).bind('keyup',function (e) {

				clearTimeout($.fn.neon.neonOutputTimeout);
				$.fn.neon.getOutput(this);
				
			});

		});

	}

	$.fn.neon.getOutput = function(obj) {

		var input = $(obj).val();
		var ajaxTimeout = $.fn.neon.options.ajaxTimeout;
		var neonOutput = $(obj).next('.neonOutput');

		$.fn.neon.neonOutputTimeout = setTimeout(function() {

			var decoderUrl = $.fn.neon.options.decoderUrl;

			$.ajax({
				url: decoderUrl,
				type: 'POST',
				data: {input: input},
				success: function(data) {
					$(neonOutput).html(data.output);
				}
			});

		}, ajaxTimeout);

	}

})(jQuery);
