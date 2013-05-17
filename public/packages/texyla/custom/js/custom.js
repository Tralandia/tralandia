
$(document).ready(function(){

	$.texyla.setDefaults({
		texyCfg: "admin",
		baseDir: '/packages/texyla/lib/texyla',		
		filesPath: null,
		filesThumbPath: null,
		previewPath:'',
		filesUploadPath: null,
		toolbar: [
			'h1', 'h2', 'h3', 'h4',
			null,
			'bold', 'italic',
			null,
			'ul', 'ol',		
		],
		texyCfg: "admin",
		bottomLeftToolbar: ['edit', 'preview', 'htmlPreview'],
		bottomRightEditToolbar: [],
		buttonType: "button",
		tabs: true
	});

	$("textarea.texyla").each(function(){
		var self = $(this);
		$(this).texyla({
			previewPath:self.data('previewPath')
		});
	});

});