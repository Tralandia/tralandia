// confirm link
(function($){

	$.confirmLink = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("confirmLink", base);

		base.confirmText = base.$el.data('confirmText');

		base.init = function(){
			base.bind();
		};

		base.bind = function(){
			base.$el.live('click',base.confirmDialog);
		}

		base.confirmDialog = function(){

			if(typeof base.confirmText != 'undefined'){
				return confirm(base.confirmText);
			} else {
				return true;
			}

		}

		base.init();
	};

	$.fn.confirmLink = function(radius, options){
		return this.each(function(){
			(new $.confirmLink(this, radius, options));});
	};

})(jQuery);




$(function() {
	$('#frm-userEditForm a[rel=changePassword]').click(function() {
		$this = $(this);
		$form = $this.parents('form');

		var clearPasswordChange = false;
		$form.find('.password').toggleClass(function() {
			$this = $(this);
			if ($this.hasClass('changePassword')) {
				if ($this.hasClass('hide')) {
					clearPasswordChange = true;
				} else {
					clearPasswordChange = false;
				}
			}

			if (clearPasswordChange) {
				$this.find('input').val('');
			}

			return 'hide';
		});
		return false;
	});
});

$(function(){

	$('input.adminSearch').keypress(function(e) {
		if(e.which == 13) {

			var patterns = {
					patternQ: '__query__',
					patternIso: '__languageId__',
					patternSearchInUserContent: '__searchInUserContent__'
				};

			var url = '-- ';

			if($(this).hasClass('adminSearchDictionary')){
				url = $(this).attr('data-redirect')
					.replace(patterns.patternQ,encodeURIComponent($(this).val()))
					.replace(patterns.patternIso,encodeURIComponent($('select.dictionaryLanguage').val()))
					.replace(patterns.patternSearchInUserContent,Number($('input[name=searchInUserContent]').is(":checked")))
			} else {
				url = $(this).attr('data-redirect').replace(patterns.patternQ,encodeURIComponent($(this).val()));
			}

			window.location = url;

		}
	});

});

//  remove phrase link
(function($){
	$.phraseDelete = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("phraseDelete", base);

		base.init = function(){
			base.bind();
		};

		base.bind = function(){
			base.$el.on('click',base.deleteCurrentPhrase);
		}

		base.deleteCurrentPhrase = function(){

				$parent = base.$el.parents('.phraseForm');
				$parent.parents('.phraseEditForm').find('hr:first').remove();
				$parent.remove();

			$.getJSON(base.$el.attr('href'), function(data) {



				if(data.success == true){
					// base.$el.parents('.phraseForm').remove();
				} else {
					alert('delete error');
				}
			});

			return false;
		}

		base.init();
	};

	$.fn.phraseDelete = function(radius, options){
		return this.each(function(){
			(new $.phraseDelete(this, radius, options));});
	};

})(jQuery);




(function($){
	$.textareaPreview = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("textareaPreview", base);

		// vars
		base.previewUrl = base.$el.data('previewLink');
		base.previewTitle = base.$el.data('previewTitle');
		base.modalBox = $('#myModal');
		base.modalBoxTitle = base.modalBox.find('.modal-header h3');
		base.modalBoxContent = base.modalBox.find('.modal-body');
		base.texyText = '';
		base.ajaxType = 'POST';
		// selectors
		base.controlCover = '.phrasecontrol';

		base.init = function(){

			base.options = $.extend({},$.textareaPreview.defaultOptions, options);
			base.bind();

		};

		base.bind = function(){
			base.$el.on('click',base.click);
		}

		base.click = function(){

			base.texyText = base.$el.parents(base.controlCover).find('textarea').val();

			$.ajax({
				url: base.previewUrl,
				context: document.body,
				type: base.ajaxType,
				data: {
					texy: base.texyText
				}
			}).done(function(data) {

				base.modalBoxTitle.html(base.previewTitle);
				base.modalBoxContent.html(data);

				base.modalBox.modal();

				base.modalBox.show();
			});

		}

		base.init();
	};

	$.fn.textareaPreview = function(options){
		return this.each(function(){(new $.textareaPreview(this, options));});
	};

})(jQuery);

$(function(){
	$('.phraseFormHeaderCover a.ajaxDelete').phraseDelete();
	$('.phraseFormHeaderCover a.preview').textareaPreview();

	$('#sidebar.adminSidebar a').confirmLink();
});


$(function() {
	$('#selectAll').change(function() {
		if($(this).is(':checked')) {
			$('.phraseEditForm :input[type=checkbox]').attr('checked', 'checked');
		} else {
			$('.phraseEditForm :input[type=checkbox]').removeAttr('checked');
		}
	});
});

$(function(){
	var $textareas = $('.phraseEditForm').find('textarea');

	$textareas.each(function(){
		var scH = $(this)[0].scrollHeight;

			scH = parseInt(scH);

		if(scH < 10){
			$(this).height(60);
		} else {
			$(this).height(scH);	
		}
		
	});
});

function setUnits(obj) {
	var $select = $(obj);
	var rentalId = $select.val();

	$('tr.units').find('.units-container').addClass('hide');
	$('tr.units').find('#unit'+rentalId).removeClass('hide');
}
