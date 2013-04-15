
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