$(function() {

	$('[type=checkbox], [type=radio]').change(function() {
		switch($(this).attr('type')) {
			case 'checkbox':
				$($(this).parent()).toggleClass('checked');
				break;
			case 'radio':
				radios = $('[name='+$(this).attr('name')+']')
				$(radios).each(function(k,v) {
					$(v).parent().removeClass('checked');
				});
				$($(this).parent()).addClass('checked');
				break;
		}
	});

	// $('.content').livequery(function() {
		$('[rel="tooltip"]').tooltip({
			placement: 'top'
		});
	// });

	// Ajax Modals
	$('a[data-toggle="ajax-modal"]').on('click', function(event) {

		event.preventDefault();

		link = $(this).attr('href');
		$modal = $('#modal');
		$modalBody = $('.modal-body', $modal);
		loading = '<div class="loading-frame"></div>';

		$modal.modal();

		$.ajax({
			url: link,
			dataType: 'html',
			error: function(e) {
				$modalBody.html('Error ' + e.status + ': ' + e.statusText);
			},
			beforeSend: function() {
				$modalBody.html(loading);
			},
			success: function(html) {
				$modalBody.html(html);
			}
		});

	});

	$("textarea.neon")
		.tabby()
		.neon({ajaxTimeout: 500});


	var typeaheadAjaxDelay = null;
	$('.typeahead').typeahead({
		source: function (typeahead, query) {
			clearTimeout(typeaheadAjaxDelay);

			var $input = this.$element;

			url = '/admin/attraction-attraction/suggestion';
			var getData = {
				serviceList: $input.attr('data-servicelist'),
				property: $input.attr('data-property'),
				language: $input.attr('data-language'),
				search: query
			}

			typeaheadAjaxDelay = setTimeout(function() {
				$.ajax({
					type: 'GET',
					url: url,
					data: getData,
					beforeSend: function() {
						$input.addClass('loading');
					},
					success: function(data) {
						$input.removeClass('loading');
						typeaheadData = [];
						i = 0;
						for(id in data['suggestion']) {
							typeaheadData[i] = {
								id: id,
								value: data['suggestion'][id]
							};
							i++;
						}
						return typeahead.process(typeaheadData);
					}

				});
			}, 300);

		},
		onselect: function(obj) {
			myJSONtext = $(this.$menu).find('li.active').attr('data-value');
			myObject = eval('(' + myJSONtext + ')');
			$(this.$element).next('input').val(myObject.id);

	$('input[data-dateinput-type]').dateinput({
		datetime: {
			dateFormat: 'd.m.yy',
			timeFormat: 'h:mm'
		},
		'datetime-local': {
			dateFormat: 'd.m.yy',
			timeFormat: 'h:mm'
		},
		date: {
			dateFormat: 'd.m.yy'
		},
		month: {
			dateFormat: 'MM yy'
		},
		week: {
			dateFormat: "w. 'týden' yy"
		},
		time: {
			timeFormat: 'h:mm'
		}
	});

});

function toggleEdit(obj) {
	$($(obj).parent()).next().children('form').toggleClass('edit-mode');
}

function debug(val) { console.log(val); }