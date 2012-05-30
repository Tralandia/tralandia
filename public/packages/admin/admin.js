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
		$('.tooltip').tooltip({
			placement: 'left'
		});
	});

	// Modals for Add New buttons
	$('a.add-new').on('click', function(event) {
		link = $(this).attr('data-link');
		modal = $($(this).attr('href'));
		modalBody = $('.modal-body', modal);
		$(modal).modal();
		$.ajax({
			url: link,
			error: function(e) { modalBody.html('Error ' + e.status + ': ' + e.statusText); },
			beforeSend: function() { modalBody.html('loading...'); },
			success: function(data) { modalBody.html(data); }
		});
		event.preventDefault();
	});

	$("textarea.neon")
		.tabby()
		.neon({ajaxTimeout: 500});

});

function toggleEdit(obj) {
	$($(obj).parent()).next().children('form').toggleClass('edit-mode');
}

function debug(val) { console.log(val); }