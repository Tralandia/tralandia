
(function($) {

	$.fn.neon = function(options) {
		
		$.fn.neon.options = {
			spanSize: 'span6',
			ajaxTimeout: 2000,
			initOnLoad: true,
			decoderUrl: '/admin/ap/decode-neon'
		};

		return this.each(function() {

			var $base = $(this);

			$base
				.addClass($.fn.neon.options.spanSize)
				.next('.neonOutput')
				.addClass($.fn.neon.options.spanSize);

			if ($.fn.neon.options.initOnLoad) $.fn.neon.getOutput($base);

			// Onchange events
			$base.bind('keyup',function (e) {

				clearTimeout($.fn.neon.neonOutputTimeout);
				$.fn.neon.getOutput($base);
				
			});

		});

	}

	$.fn.neon.getOutput = function($base) {

		var input = $base.val();
		var ajaxTimeout = $.fn.neon.options.ajaxTimeout;
		var $neonOutput = $base.next('.neonOutput');

		$.fn.neon.neonOutputTimeout = setTimeout(function() {

			var decoderUrl = $.fn.neon.options.decoderUrl;

			$.ajax({
				url: decoderUrl,
				type: 'POST',
				data: {input: input},
				beforeSend: function() {
					$neonOutput
						.height($base.height())
						.html('<div class="loading-frame"></div>');
				},
				success: function(data) {
					el = $(data.output);
					el.height($base.height());
					$neonOutput.html(el);
				}
			});

		}, ajaxTimeout);

	}

})(jQuery);
