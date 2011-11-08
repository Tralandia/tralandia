$(document).ready(function() {
	if ($.browser.webkit) {
		$("body").addClass("webkit");
	} else if ($.browser.mozilla) {
		$("body").addClass("mozilla");
	} else if ($.browser.opera) {
		$("body").addClass("opera");
	}

	$("a.ajax, a.datagrid-ajax").live("click", function (event) {
	    event.preventDefault();
	    $.get(this.href);
	});

	$("form.ajax, form.datagrid").live("submit", function(e) {
		try {tinyMCE.triggerSave();} catch (e) {};
		$(this).ajaxSubmit();
		return false;
    });

	$("form.ajax :submit, form.datagrid :submit").live("click", function(e) {
		try {tinyMCE.triggerSave();} catch (e) {};
		$(this).ajaxSubmit();
		return false;
    });

	$("form.ajax.onchange").live("change", function() {
		try {tinyMCE.triggerSave();} catch (e) {};
		$(this).ajaxSubmit();
		return false;
	});

	$("form.datagrid select").live("change", function() {
		$(this).parents("form.datagrid").find("input:submit[name=itemsSubmit]").ajaxSubmit();
		return false;
	});

	$("input.datepicker").livequery(function() {
		$(this).datepicker({duration: 'fast'});
	});
	$("input.datetimepicker").livequery(function() {
		$(this).datetimepicker();
	});
});


Function.prototype.bind = function(obj) {
	var method = this,
	temp = function() {
		return method.apply(obj, arguments);
	};
	return temp;
}
function goLocation(url) {
	document.location = url;
}
function debug(field) {
	if (window.console && window.console.log) {
		window.console.log(field);
	}
}