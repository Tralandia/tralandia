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

	$('textarea.tinymce').tinymce({
		script_url : baseUrl+'/scripts/tinymce/tiny_mce.js',
		theme : "advanced",
		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,separator,forecolor,formatselect,separator,bullist,numlist,separator,undo,redo,separator,charmap,image',
		theme_advanced_buttons2 : '',
		theme_advanced_buttons3 : '',
		theme_advanced_text_colors : "A0B325,630,000000",
		theme_advanced_toolbar_location: "top",
		theme_advanced_blockformats : "h3,h4,h5,h6"
	});
	$(".multiselect").multiselect();

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

	$('input:checkbox').checkbox({
		cls:'jquery-safari-checkbox',
		empty: baseUrl+'/images/spacer.gif'
	});

	$("textarea.neon").tabby().neon({ajaxTimeout: 500});

	$(".phrase-control").phraseControl();

});

function toggleEdit(obj) {
	$($(obj).parent()).next().children('form').toggleClass('edit-mode');
}

function debug(val) { console.log(val); }