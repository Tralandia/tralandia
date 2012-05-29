/*
 * jQuery File Upload Plugin JS Example 6.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function () {
	'use strict';

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		url: '/json.html',
		filesContainer: $('.files-container'),
		prependFiles: false,
		previewMaxWidth: 165,
		previewMaxHeight: 120,
		uploadTemplateId: null,
		downloadTemplateId: null,
		autoUpload: true,
		sequentialUploads: true,
		add: function (e, data) {
			var that = $(this).data('fileupload'),
				options = that.options,
				files = data.files;
			$(this).fileupload('process', data).done(function () {
				that._adjustMaxNumberOfFiles(-files.length);
				data.isAdjusted = true;
				data.files.valid = data.isValidated = that._validate(files);
				data.context = that._renderUpload(files).data('data', data);
				options.filesContainer[
					options.prependFiles ? 'prepend' : 'append'
				](data.context);
				that._renderPreviews(files, data.context);
				that._forceReflow(data.context);
				that._transition(data.context).done(
					function () {
						if ((that._trigger('added', e, data) !== false) &&
								(options.autoUpload || data.autoUpload) &&
								data.autoUpload !== false && data.isValidated) {
							data.submit();
						}
					}
				);
			});
		},
		send: function (e, data) {
			var that = $(this).data('fileupload');
			$('.overlay', this).removeClass('waiting');
			$('.progress', this).removeClass('hide');
			if (!data.isValidated) {
				if (!data.isAdjusted) {
					that._adjustMaxNumberOfFiles(-data.files.length);
				}
				if (!that._validate(data.files)) {
					return false;
				}
			}
			if (data.context && data.dataType &&
					data.dataType.substr(0, 6) === 'iframe') {
				// Iframe Transport does not support progress events.
				// In lack of an indeterminate progress bar, we set
				// the progress to 100%, showing the full animated bar:
				data.context
					.find('.progress').addClass(
						!$.support.transition && 'progress-animated'
					)
					.attr('aria-valuenow', 100)
					.find('.bar').css(
						'width',
						'100%'
					);
			}
			return that._trigger('sent', e, data);
		},
		uploadTemplate: function (o) {
			var rows = $();
			$.each(o.files, function (index, file) {
				var row = $('<div class="thumbnail-wrapper fade">' +
						'<div class="thumbnail">' + 
							'<div class="overlay waiting"></div>' + 
							'<div class="preview"><span class="fade"></span></div>' +
							(file.error ? '<div class="error" colspan="2"></div>' :	'<div class="progress progress-striped active hide" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%"></div></div>') + 
							'<a href="#" class="btn btn-mini btn-danger delete" title="Delete image"><i class="icon-remove icon-white"></i></a>' + 
							'<div class="caption"><input class="input-medium" placeholder="" /></div>' + 
						'</div>' + 
					'</div>');
				row.find('.caption input').attr('placeholder', file.name);
				if (file.error) {
					row.find('.error').text(
						locale.fileupload.errors[file.error] || file.error
					);
				}
				rows = rows.add(row);
			});
			return rows;
		},
		downloadTemplate: function (o) {
			var rows = $();
			$.each(o.files, function (index, file) {
				var row = $('<div class="thumbnail-wrapper fade">' +
						'<div class="thumbnail">' + 
							'<div class="preview"></div>' +
							(file.error ? '<div class="error" colspan="2"></div>' :	'') + 
							'<a href="#" class="btn btn-mini btn-danger delete" title="Delete image"><i class="icon-remove icon-white"></i></a>' + 
							'<div class="caption"><input class="input-medium" placeholder="" /></div>' + 
						'</div>' + 
					'</div>');
				row.find('.size').text(o.formatFileSize(file.size));
				if (file.error) {
					row.find('.name').text(file.name);
					row.find('.error').text(
						locale.fileupload.errors[file.error] || file.error
					);
				} else {
					row.find('.name a').text(file.name);
					if (file.thumbnail_url) {
						row.find('.preview').append('<a><img></a>')
							.find('img').prop('src', file.thumbnail_url);
						row.find('a').prop('rel', 'gallery');
					}
					row.find('a').prop('href', file.url);
					row.find('.delete button')
						.attr('data-type', file.delete_type)
						.attr('data-url', file.delete_url);
				}
				rows = rows.add(row);
			});
			return rows;
		}
	});

});
