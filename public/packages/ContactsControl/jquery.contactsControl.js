
(function($) {

	$.fn.contactsControl = function(options) {
		
		$.fn.contactsControl.options = {
			brickHtml: '<label class="label">' +
					'<div class="btn-group pull-right">' +
						'<a class="btn btn-danger delete btn-mini"><i class="icon-white icon-remove"></i></a>' +
					'</div>' +
					'<span></span>' +
					'<input type="text" class="span12 text hide" />' +
				'</label>',
			dataSeparator: '~',
			conditions: {
				email: [/\b[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b/],
				phone: [null, /^[0-9\-+ ]+$/],
				address: [/.{2,}/, null, null, null, null],
				url: [/.{5,}/],
				name: [null, null, null],
			}
		};

		return this.each(function() {

			var $base = $(this);
			var $types = $base.find('ul.contacts-types li a[contacts-type*=]');
			var $inputs = $base.find('div.contacts-types');


			$types.bind('click',function (e) {
				e.preventDefault();

				$this = $(this);
				var type = $this.attr('contacts-type');

				$base
					.find('button.types')
					.html($this.html() + ' <span class="caret"></span>')
					.attr('selected-type', type);

				$inputs
					.find('[class^="type-"]')
					.addClass('hide');
				$inputs
					.find('.type-' + type)
					.removeClass('hide');

			});


			$base.find('button.submit').bind('click', function(e) {
				e.preventDefault();

				type = $base.find('button.types').attr('selected-type');
				$.fn.contactsControl.addBrick($base, type);

			});

		});

	}

	$.fn.contactsControl.isValid = function(type, value, i) {

		isValid = true;

		if ($.fn.contactsControl.options.conditions[type]) {
			if ($.fn.contactsControl.options.conditions[type][i-1] !== null) {
				if (!value) {
					isValid = false;
				} else {
					isValid = $.fn.contactsControl.options.conditions[type][i-1].test(value);
				}
			}
		}

		return isValid;

	}

	$.fn.contactsControl.addBrick = function($base, type) {

		$brick = $($.fn.contactsControl.options.brickHtml);
		$inputs = $base.find('div.contacts-types .type-' + type);

		var value = new Array(type);

		var i=1;
		var isInvalid = false;
		$inputs.find('select, input').each(function() {
			v = $(this).val();
			$(this).removeClass('invalid');
			if (!$.fn.contactsControl.isValid(type, v, i)) {
				$(this).addClass('invalid').focus();
				alert('Invalid ' + $(this).attr('placeholder'))
				isInvalid = true;
			}
			value[i] = v;
			i++;
		});

		// If NOT valid return false
		if (isInvalid) {
			return false;
		} else {
			$inputs.find('select, input').each(function() {
				$(this).val('').focus();
			});
		}


		$span = $brick.find('span');

		icon = '<i class="' + $base.find('.contacts-types a[contacts-type="'+ type +'"] i').attr('class') + '"></i> ';

		if (type == 'email') {
			html = icon + value[1];
			$span.html(html);

		} else if (type == 'phone') {
			html = icon + '+'+ value[1] +' '+ value[2];
			$span.html(html);

		} else if (type == 'address') {
			html = icon + value[1] +' '+ value[2] +', '+ value[3] +', '+ value[4] +', '+ value[5];
			$span.html(html);

		} else if (type == 'url') {
			html = icon + value[1];
			$span.html(html);

		} else if (type == 'name') {
			html = icon + value[1] +' '+ value[2] +' '+ value[3];
			$span.html(html);
		}

		$brick
			.find('input')
			.attr('name', 'contacts[]')
			.attr('value', value.join($.fn.contactsControl.options.dataSeparator));

		$base.before($brick);

		// DELETE button trigger
		$brick.find('a.delete').bind('click', function(e) {
			e.preventDefault();
			$(this).parents('label.label').detach();
		});

	}

})(jQuery);

$(function() {
	$('.contacts-control').contactsControl();
});
