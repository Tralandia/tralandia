(function($) {
	
  $.fn.traMap = function() {

  		// default map zoom level
  		var zoomVal = 4;

  		if(typeof $(this).attr('zoom') != 'undefined')
  		{
  			zoomVal = parseInt($(this).attr('zoom'));
  		}

  		if(typeof $(this).attr('value') == 'undefined')
  		{
  			$(this).html('nejde to bo nemas suradnice vole');
  		} else {

  			var coordinates = $(this).attr('value').split(',');

			var lat = parseFloat(coordinates[0]);
			var lng = parseFloat(coordinates[1]);

	        var myLatlng = new google.maps.LatLng(lat,lng);
	        var mapOptions = {
	          zoom: zoomVal,
	          scrollwheel: false,
	          center: myLatlng,
	          mapTypeId: google.maps.MapTypeId.ROADMAP
	        }
	        var map = new google.maps.Map(document.getElementById($(this).attr('id')), mapOptions);

	        var marker = new google.maps.Marker({
	            position: myLatlng,
	            map: map
	        });

  		}

  };
})(jQuery);