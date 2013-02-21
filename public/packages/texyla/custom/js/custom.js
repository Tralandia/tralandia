
$(document).ready(function(){

	
	$.texyla.setDefaults({
		texyCfg: "admin",
		baseDir: '/packages/texyla/texyla',
		previewPath: "preview.php",
		filesPath: null,
		filesThumbPath: null,
		filesUploadPath: null
	});

    $("textarea.texyla").texyla({
				toolbar: [
					'h1', 'h2', 'h3', 'h4',
					null,
					'bold', 'italic',
					null,
					'center', 'left', 'right', 'justify',
					null,
					'ul', 'ol',
			
					
				],
				texyCfg: "admin",
				bottomLeftToolbar: ['edit', 'preview', 'htmlPreview'],
				buttonType: "button",
				tabs: true
			});
});