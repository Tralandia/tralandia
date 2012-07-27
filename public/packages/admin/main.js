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

	$("form.datagrid .footer select").live("change", function() {
		$(this).parents("form.datagrid").find("input:submit[name=itemsSubmit]").ajaxSubmit();
		return false;
	});

	$("input.datepicker").livequery(function() {
		$(this).datepicker({
			duration: 'fast',
			dateFormat: 'dd.mm.yy',
			changeMonth: true,
			changeYear: true,
			firstDay: 1
		});
	});
	$("input.datetimepicker").livequery(function() {
		$(this).datetimepicker();
	});
});


function str_replace (search, replace, subject, count) {
	var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
			f = [].concat(search),
			r = [].concat(replace),
			s = subject,
			ra = r instanceof Array, sa = s instanceof Array;
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}

	for (i=0, sl=s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j=0, fl=f.length; j < fl; j++) {
			temp = s[i]+'';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length-s[i].length)/f[j].length;}
		}
	}
	return sa ? s : s[0];
}

Array.prototype.search = function(needle, argStrict) {
	var strict = !!argStrict;
	var key = '';

	for (key in this) {
		if ((strict && this[key] === needle) || (!strict && this[key] == needle)) {
			return key;
		}
	}

	return false;
}


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

function loadMap(el, zoom){

	var map;
	var zoom = zoom;
	var marker = null;
	var mapContainer = $(el + ' .map-placeholder')[0];
	var $el = $(el);

	var $latitude = $el.find('.map-data input[name$="[latitude][latitude]"]');
	var latitude = $latitude.val();

	var $longitude = $el.find('.map-data input[name$="[latitude][longitude]"]');
	var longitude = $longitude.val();

	$latitude.bind('change', function() {
		loadMap.moveMarker(new google.maps.LatLng($(this).val(), longitude));
	});
	
	$longitude.bind('change', function() {
		loadMap.moveMarker(new google.maps.LatLng(latitude, $(this).val()));
	});

	var s = document.createElement("script");
	s.type = "text/javascript";
	s.src  = "http://maps.google.com/maps/api/js?v=3&sensor=true&callback=loadMap.gmap_draw";
	$("head").append(s);

	$el.find('.map-data').addClass('active').find('.btn').detach();

	loadMap.gmap_draw = function() {

		$el.find('.map-placeholder').css({height: 200});

		var options = {
			zoom: zoom,
			center: new google.maps.LatLng(latitude, longitude),
			mapTypeId: "terrain"
		};

		map = new google.maps.Map(mapContainer, options);

		loadMap.moveMarker(new google.maps.LatLng(latitude, longitude));

		google.maps.event.addListener(map, 'click', function(event) {
			loadMap.moveMarker(event.latLng);
		});
	}

	loadMap.setInputsLatLng = function(latLng) {

		$latitude.val(latLng.lat());
		$longitude.val(latLng.lng());

	}

	loadMap.moveMarker = function(latLng) {

		if (marker) marker.setMap(null);
		marker = new google.maps.Marker({
			position: latLng,
			map: map
		});
		loadMap.setInputsLatLng(latLng);

	}

};