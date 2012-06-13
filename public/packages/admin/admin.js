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

	$('.content').livequery(function() {
		$('[rel="tooltip"]').tooltip({
			placement: 'top'
		});
	});

	// Modals for Add New buttons
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

});

function toggleEdit(obj) {
	$($(obj).parent()).next().children('form').toggleClass('edit-mode');
}

function debug(val) { console.log(val); }