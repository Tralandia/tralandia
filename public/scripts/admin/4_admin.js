$(function() {

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
	$.localise('ui-multiselect', { path: baseUrl+'/scripts/multiselect/locale/', language: 'en' });
	$(".multiselect").multiselect();
	$('.btn').tooltip();
	$('#myModal').modal({
		show: false
	});

	$('input:checkbox').checkbox({
		cls:'jquery-safari-checkbox',
		empty: baseUrl+'/images/spacer.gif'
	});

})

function debug(val) { console.log(val); }