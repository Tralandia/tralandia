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
  			$(this).html('error');
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

		        //console.log(lat+' '+lng);

		        var delta = 0.4;

		        var MinLat = lat - delta;
		        var MaxLat = lat + delta;

		        var MinLng = lng - delta;
		        var MaxLng = lng + delta;


		        var findPanoramio = {
		        	set: 'public',
		        	from: 0,
		        	to: 12,
		        	minx: MinLng,
		        	miny: MinLat,
		        	maxy: MaxLat,
		        	maxx: MaxLng,
		        	size: 'medium',
		        	mapfilter: true
		        };


		        /*
		        $.ajax({
					  dataType: "jsonp",
					  crossDomain: true,
					  data: findPanoramio,
					  url: 'http://www.panoramio.com/map/get_panoramas.php',					  
					  success: function(data){

					  }
					}).done(function(d){
						

						var html = '';
						$.each(d.photos,function(k,v){
							console.log(v);

							
							var myLatlng = new google.maps.LatLng(v.latitude,v.longitude);

					        var marker = new google.maps.Marker({
					            position: myLatlng,
					            map: map
					        });
							

							html+= '<li style="background-image:url('+v.photo_file_url+');"></li>';

						});

						$('#placesImg').html(html);
					});

			*/

  		}

  };
})(jQuery);

