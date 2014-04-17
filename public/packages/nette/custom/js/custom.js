var Nette = Nette || {};

Nette.addError = function(elem, message) {

	if (elem.focus) {
		$(elem)
			.parents('.control-group')
			.addClass('error');
		elem.focus();
	}
	if (message) {
		var msgId = '#'+$(elem).attr('data-validation-message');
		$(msgId).html(message);
		
	}
};

Nette.removeError = function(elem) {
	$(elem)
		.parents('.control-group')
		.removeClass('error')
		.find('.help-inline.error')
		.detach();

		var msgId = '#'+$(elem).attr('data-validation-message');
		$(msgId).html('');
};


Nette.validateControl = function(elem, rules, onlyCheck) {


	// @todo docasne vypnuta validacia
	return true;

	rules = rules || eval('[' + (elem.getAttribute('data-nette-rules') || '') + ']');
	for (var id = 0, len = rules.length; id < len; id++) {
		var rule = rules[id], op = rule.op.match(/(~)?([^?]+)/);
		rule.neg = op[1];
		rule.op = op[2];
		rule.condition = !!rule.rules;
		var el = rule.control ? elem.form.elements[rule.control] : elem;

		var success = Nette.validateRule(el, rule.op, rule.arg);
		if (success === null) { continue; }
		if (rule.neg) { success = !success; }

		Nette.removeError(el);
		if (rule.condition && success) {
			if (!Nette.validateControl(elem, rule.rules, onlyCheck)) {
				return false;
			}
		} else if (!rule.condition && !success) {
			if (el.disabled) { continue; }
			if (!onlyCheck) {
				Nette.addError(el, rule.msg.replace('%value', Nette.getValue(el)));
			}
			return false;
		}
	}
	return true;
};


$(document).ready(function(){

$('a.a.btn-warning').click(function (e) {

	e.preventDefault();

	var $self = $(this);

    $.nette.ajax({
        validate: false,
        success: function(data){

        	if(data.success == true){
        		$self.removeClass('btn-warning').addClass('btn-success');
        	}

        }
    }, this, e);

    return false;
});

$.nette.init(function (netteAjaxHandler) {
    $('a.ajax').click(netteAjaxHandler);
    $('form.ajax').submit(netteAjaxHandler);
    $('form.ajax').live('submit',netteAjaxHandler);
});

var c = $.nette.ext('snippets');
	c.updateSnippet = function($el, html, back){
		if (typeof $el == 'string') {
			$el = this.getElement($el);
		}
		// Fix for setting document title in IE
		if ($el.is('title')) {
			document.title = html;
		} else {
			this.applySnippet($el, html, back);
		}

		$('form[method="post"]').submitButtonPlugin({
			buttonActiveClass: 'active',
			cacheFormSelector: 'cache',
			select2Selector: 'select2',
			cacheIndexName: 'formDataCache',
			buttonSelector: 'button[type=submit]'
		});
		
		rentalDetailDatepickerInit();
		// $("select.select2").select2();

	}	

	$.ajaxSetup({
		success: function(data){

			if(data.success == true){

				var form = $(this)[0];
				var $form = null;


				$('form.ajax').each(function(){

					var current = $(this);
					if(current.attr('action') == form.url){
						$form = current;
					}
				});

				$form.find('button.active').removeClass('active');

				var inactiveText = $form.find('button').data('inactiveText');

				if(typeof inactiveText != 'undefined'){
					$form.find('button small').html(inactiveText);
				}

			}
		}
	});

	
});





