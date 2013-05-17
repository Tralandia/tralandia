
$(document).ready(function(){

	
	$.texyla.setDefaults({
		texyCfg: "admin",
		baseDir: '/packages/texyla/lib/texyla',
		previewPath: "http://www.com.tra.com/admin/phrase-list/texyla-preview",
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
					'ul', 'ol',
			
					
				],
				texyCfg: "admin",
				bottomLeftToolbar: ['edit', 'preview', 'htmlPreview'],
				bottomRightEditToolbar: [],
				buttonType: "button",
				tabs: true
			});
});