
(function($) {

	$.fn.contactsControl = function(options) {
		
		$.fn.contactsControl.options = {
			brickHtml: '<label class="label">' +
					'<div class="btn-group pull-right">' +
						'<a class="btn btn-danger delete btn-mini"><i class="icon-white icon-remove"></i></a>' +
					'</div>' +
					'<span></span>' +
				'</label>',
			dataSeparator: '~',
			conditions: {
				email: [/\b[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b/],
				phone: [null, /^[0-9\-+ ]+$/],
				address: [/.{2,}/, null, null, null, null],
				url: [/.{5,}/],
				name: [/.{2,}/, null, null],
			}
		};

		return this.each(function() {

			var $base = $(this);
			var $types = $base.find('ul.contacts-types li a[contacts-type*=]');
			var $inputs = $base.find('div.contacts-types');

			$.fn.contactsControl.initTextareaData($base);

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


			$inputs.find('input, select').bind('keypress', function(e) {
				if (e.keyCode == 13) {
					e.preventDefault();

					type = $base.find('button.types').attr('selected-type');
					$.fn.contactsControl.createBrick($base, type);
				}
			});

			$base.find('button.submit').bind('click', function(e) {
				e.preventDefault();

				type = $base.find('button.types').attr('selected-type');
				$.fn.contactsControl.createBrick($base, type);

			});

		});

	}

	$.fn.contactsControl.setTextareaData = function($base) {

		var r = [];
		var i = 0;
		$base.find('.input-bricks label.label').each(function() {
			r[i] = $(this).attr('value');
			i++;
		});

		$base.find('.input-bricks textarea').val(r.join("\n"));

	}

	$.fn.contactsControl.initTextareaData = function($base) {

		$textarea = $base.find('.input-bricks textarea');
		lines = $textarea.val().split("\n");

		bricks = this.linesToArray(lines);
		for(i in bricks) {
			if (typeof bricks[i] !== 'object') continue;
			this.addBrick($base, bricks[i]);
		}

	}

	$.fn.contactsControl.createBrick = function($base, type) {

		$inputs = $base.find('div.contacts-types .type-' + type);

		var value = new Array(type);

		var i=1;
		var isValid = true;
		$inputs.find('select, input').each(function() {
			v = $(this).val();
			$(this).removeClass('invalid');
			if (!$.fn.contactsControl.isValid(type, v, (i-1))) {
				$(this).addClass('invalid').focus();
				isValid = false;
			}
			value[i] = v;
			i++;
		});

		// If NOT valid return false
		if (!isValid) {
			return false;
		} else {
			$inputs.find('select, input').each(function() {
				$(this).val('').focus();
			});
		}

		this.addBrick($base, value);

	}

	$.fn.contactsControl.addBrick = function($base, value) {

		$brick = $(this.options.brickHtml);
		$span = $brick.find('span');

		type = value[0];

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

		$brick.attr('value', this.arrayToLines(value));

		$textarea = $base.find('.input-bricks textarea');
		$textarea
			.after($brick);

		// DELETE button trigger
		$brick.find('a.delete').bind('click', function(e) {
			e.preventDefault();
			$(this).parents('label.label').detach();
			$.fn.contactsControl.setTextareaData($base);
		});

		this.setTextareaData($base);

	}


	// hepl methods
	$.fn.contactsControl.arrayToLines = function(value) {

		return value.join(this.options.dataSeparator);

	}

	$.fn.contactsControl.linesToArray = function(lines) {

		r = [];
		for(i in lines) {
			line = lines[i];
			if (typeof line == 'string') r[i] = line.split(this.options.dataSeparator);
		}

		return r;

	}

	$.fn.contactsControl.isValid = function(type, value, i) {

		isValid = true;

		if (this.options.conditions[type]) {
			if (this.options.conditions[type][i] !== null) {
				if (!value) {
					isValid = false;
				} else {
					isValid = this.options.conditions[type][i].test(value);
				}
			}
		}

		return isValid;

	}

})(jQuery);

$(function() {
	$('.contacts-control').contactsControl();
});
