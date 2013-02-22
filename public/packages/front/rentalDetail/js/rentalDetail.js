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


  		}

  };
})(jQuery);


/* afret load Rental detail add object id to visit list array */
(function($) {
	
  $.fn.objectVisitList = function(appObject) {  	

  	if(this.length > 0){

	  	var visitList = new Array();
	  	
	  	var objectList = $.cookie('favoritesVisitedList');
	  		if(typeof objectList != 'undefined'){
	  			objectList = objectList.split(',');
	  		} else {
	  			objectList = false;
	  		}

	  	var currentId = parseInt($(this).attr('id'));  	

	  		if(!objectList){
	  				$.cookie('favoritesVisitedList' , currentId);
	  		} else {

	  			// chech if index not exist 

	  				if(appObject.in_array(objectList,currentId) == false){
			  			visitList = objectList;
			  			visitList.push(currentId); 
			  			$.cookie('favoritesVisitedList' , visitList);					
	  				}

	  		}

  	}
	
  };
})(jQuery);

/* tweet button */
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

// social shit 
(function($){
	$.socialIconsDetail = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("socialIconsDetail", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.socialIconsDetail.defaultOptions, options);            

		};
		
		base.init();
	};
	
	$.socialIconsDetail.defaultOptions = {
		
	};
	
	$.fn.socialIconsDetail = function(options){
		return this.each(function(){

			var self = this;
			var $self = $(this);

			var $close = $(this).find('a.close');

			

			$self.click(function(){
				$self.find('.socialBtnContent').removeClass('hide');
				$self.find('.socialBtnHeader').addClass('hide');				
			});


			$close.click(function(){				
				$self.find('.socialBtnContent').addClass('hide');
				$self.find('.socialBtnHeader').removeClass('hide');	
				return false;			
			});

		});
	};
	
})(jQuery);

