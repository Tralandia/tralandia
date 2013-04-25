
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

	$('input.adminSerach').keypress(function(e) {
		if(e.which == 13) {
			
			var patternQ = '__%q%__';
			var patternIso = '__%iso%__';

			var url = '-- ';

			if($(this).hasClass('adminSearchDictionary')){
				url = $(this).attr('data-redirect').replace(patternQ,encodeURIComponent($(this).val())).replace(patternIso,encodeURIComponent($('select.DictionaryLanguage').val()));
			} else {
				url = $(this).attr('data-redirect').replace(patternQ,encodeURIComponent($(this).val()));
			}

			
			console.log(url);

		}
	});

});


$(function(){
	$('.select2').select2();
})


