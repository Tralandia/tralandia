

// phrase form
(function($){
	$.phraseForm = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("phraseForm", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.phraseForm.defaultOptions, options);            

		};
		

		base.init();
	};
	
	$.phraseForm.defaultOptions = {
		
	};
	
	$.fn.phraseForm = function(options){
		return this.each(function(){

			var self = this;
			var $self = $(this);

			var current = $self.find('#phraseLanguage').val();
				current = '#'+current+'_phrase';
				$(current).removeClass('hide');
			//console.log(current);

			$self.find('#phraseLanguage').on('change',function(){

				var visibleID = '#'+$(this).val()+'_phrase';
					//console.log(visibleID);

					$('#phraseTranslateCurrent').html($('#phraseLanguage option:selected').text());
					$self.find('.phrasecontrol').addClass('hide');
					$(visibleID).removeClass('hide');
			});

		});
	};
	
})(jQuery);






// small price phrase form
(function($){
	$.pricePhrase = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("pricePhrase", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.pricePhrase.defaultOptions, options);            

		};
		

		base.init();
	};
	
	$.pricePhrase.defaultOptions = {

	};
	
	$.fn.pricePhrase = function(options){
		return this.each(function(){

			var self = this;

			$(self).find('select.select2').each(function( index ) {
				$(this).on('change',function(){                                        

					var currentVal = $(this).val();
					var forId = '#'+$(this).attr('rel');
						
					$(this).find('option').each(function(i){
						
						if(currentVal == $(this).val()){
							$(forId).val($(this).attr('data-sufix'));
						}
					});
					
				}); 
			}); 


		});
	};
	
})(jQuery);




// gps map control

(function($){
	$.traMapcontrol = function(el, options){
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;
		
		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;
		
		// Add a reverse reference to the DOM object
		base.$el.data("traMapcontrol", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.traMapcontrol.defaultOptions, options);            

		};


		

		base.init();
	};
	
	$.traMapcontrol.defaultOptions = {

	};

	$.traMapcontrol.ajax = function(url,data,callback){

		$.ajax({
			  type: "POST",
			  url: url,
			  data: data,
			  dataType: 'json',
			}).done(callback);


		console.log(data);
		console.log(url);


		callback(data);

	}

	$.traMapcontrol.removeError = function(elem){
		
		var errorMessageDiv = $('#'+$(elem).attr('data-validation-message-div-id'));
		var controlGroup = $('#'+$(elem).attr('data-validation-control-div-id'));

		errorMessageDiv.html('');

		if(controlGroup.hasClass('error')){
			controlGroup.removeClass('error');
		}		 

	}

	// add error message 
	$.traMapcontrol.addError = function(elem,message){
		
		var errorMessageDiv = $('#'+elem.attr('data-validation-message-div-id'));
		var controlGroup = $('#'+elem.attr('data-validation-control-div-id'));

		errorMessageDiv.html(message);

		if(!controlGroup.hasClass('error')){
			controlGroup.addClass('error');
		}		
	}

	// before ajax request validation
	$.traMapcontrol.responseValidation = function(data){
		$.each(data.elements,function(k,v){
			if(!v.status){
				
				$input = $($("[name='"+k+"']"));
				$input.val(v.value);
	
				if(v.message){
					$.traMapcontrol.addError($input,v.message);
				}
				

			}
		});
	}

	// js validation client site with Nette validator
	// using after send ajax request
	$.traMapcontrol.validateInputs = function(p){
	
		var o = {
			valid : true,
			data: {}
		};

		p.find('input[type=text] , select , input[type=hidden]').each(function(){
			// nette validation
			var inputName = $(this).attr('data-name');

			if(typeof inputName != 'undefined'){

				if(!Nette.validateControl(this)){
					o.valid = false;
				} else {
					$.traMapcontrol.removeError(this);
				}

				o.data[inputName] = $(this).val();		

			}

		}); 

		return o;

	}


	
	$.fn.traMapcontrol = function(options){
		return this.each(function(){
			(new $.traMapcontrol(this, options));



			var self = this;
			var $self = $(this);

			var requestUrl = $self.attr('data-link');

			// render map and add map listener
			var $mapDiv = $self.find('div.mapRender');

			var $inputLat = $self.find('input.latitude');
			var $inputLng = $self.find('input.longitude');

            var zoom = parseInt($mapDiv.attr('data-zoom')) || 12;



				/*
				var lat = parseFloat(coordinates[0]);
				var lng = parseFloat(coordinates[1]);
				*/
				//console.log('init maops plugin'+lat+' '+lng+' '+zoom);

				var lat = parseFloat($inputLat.val());
				var lng = parseFloat($inputLng.val());

				//console.log('init maops plugin'+lat+' '+lng+' '+zoom);

				var myLatlng = new google.maps.LatLng(lat,lng);
				var mapOptions = {
				  zoom: zoom,
				  scrollwheel: false,
				  center: myLatlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map
				});

			  google.maps.event.addListener(map, 'click', function(event) {
				
				$.traMapcontrol.call();

				  marker.setPosition(event.latLng);

				  var lat = event.latLng.gb;
				  var lng = event.latLng.hb;
				  $inputLat.val(lat);
				  $inputLng.val(lng);
				  

				  $('#gps_position').html(lat+' '+lng);
				  //map.setCenter(event.latLng);


					v = $.traMapcontrol.validateInputs($self);
					
					
					// send data
						
					$.traMapcontrol.ajax(requestUrl,v.data , function(data){
					
						if(!data.status){
							$.traMapcontrol.responseValidation(data);
							
							//var newPosition = new google.maps.LatLng(data.gps.lat,data.gps.lng);
							//	marker.setPosition(newPosition);
						}
					});
					

			  });  



				var inputs = {};

				$self.find('button').click(function(){

					v = $.traMapcontrol.validateInputs($self);
					
					if(v.valid){
						// send data
						
						$.traMapcontrol.ajax(requestUrl,v.data , function(data){
							//console.log(data);
							if(!data.status){
								$.traMapcontrol.responseValidation(data);
								
								var newPosition = new google.maps.LatLng(data.gps.lat,data.gps.lng);
									marker.setPosition(newPosition);
							}
						});
					}
			
					return false;
				});
		  


		});
	};
	
})(jQuery);




// calendar edit function 
(function($){
	$.calendarEdit = function(el, options){
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;
		
		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;
		
		// Add a reverse reference to the DOM object
		base.$el.data("calendarEdit", base);
		
		base.init = function(){

			base.options = $.extend({},$.calendarEdit.defaultOptions, options);            

		};
		

		base.init();
	};
	
	$.calendarEdit.defaultOptions = {
		
	};
	

	$.calendarEdit.conteiner = [];

	$.calendarEdit.addToInput = function(a,$elem){		

		$elem.val(a.toString());

	}

	$.calendarEdit.addDate = function(d,$elem){

		$.calendarEdit.conteiner.push(d);

		$.calendarEdit.addToInput($.calendarEdit.conteiner,$elem);
	};	

	$.calendarEdit.removeDate = function(d,$elem){
	
		var p = $.calendarEdit.conteiner.indexOf(d);

		$.calendarEdit.conteiner.splice(p,1);
		$.calendarEdit.addToInput($.calendarEdit.conteiner,$elem);
	};		

	$.fn.calendarEdit = function(options){
		return this.each(function(){

			var calendarForm = this;
			var $calendarForm = $(this);

			var $input = $calendarForm.find('input[type=hidden]');
			var defaultValue = $input.val();

			if(defaultValue.length > 0){
				$.calendarEdit.conteiner = defaultValue.split(',');
			}

			

			$calendarForm.find('.calendar').each(function(i){

				var calendar = this;
				var $calendar = $(this);
								
					$calendar.find('.day.active').click(function(){
						
						var currentTime = $(this).attr('data-date');
						
						if(!$(this).hasClass('selected')){

							$(this).addClass('selected');
													
							$.calendarEdit.addDate(currentTime,$input);

						} else {
							$(this).removeClass('selected');
							
							$.calendarEdit.removeDate(currentTime,$input);
							
						}

						var statusClass = {
							first: 'status01',
							last: 'status10',
							middle: 'status11'
						}

						var stats = 0 ;

						$calendar.find('.day.active').each(function(ii){

							$(this).removeClass(statusClass.last);
							$(this).removeClass(statusClass.middle);
							$(this).removeClass(statusClass.first);                            

							if($(this).hasClass('selected') && stats == 0 ){
								$(this).addClass(statusClass.first);

								stats = 1;
							}

							else if($(this).hasClass('selected') && (stats == 1 || stats == 2) ){
								$(this).addClass(statusClass.middle);
						  
								stats = 2;
							}

							else if(!$(this).hasClass('selected') && (stats == 2 || stats == 1) ){
								$(this).addClass(statusClass.last);
							   
								stats = 0;
							}

						});

						return false;
						
					});			   

			});


		});
	};
	
})(jQuery);







// photo control
(function($){
	$.galleryControl = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("galleryControl", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.galleryControl.defaultOptions, options);            

		};
		
		base.init();
	};
	
	$.galleryControl.defaultOptions = {
		
	};
	

	// return sort array 
	$.galleryControl.sortableArray = function($elem){

		var o = [];

		$elem.find('li').each(function(i){

			o.push($(this).attr('data-id'));
		});

		return o.join(',') ;
	}

	// save sortable values
	$.galleryControl.saveSortableValues = function($input,$elem){
		$input.val($.galleryControl.sortableArray($elem));
	}


	$.fn.galleryControl = function(options){

		return this.each(function(){

			var self = this;
			var $self = $(this);

			var $uploadButton = $self.find('button');
			var $uploadButtonReal = $self.find('input[type=file]');

			var $sortInput = $self.find('#frm-registrationForm-rental-photos-sort');

			var $listGallery = $self.find('#sortable');

			var sortableUrl = $listGallery.attr('data-url');

			var removeUrl = $listGallery.attr('data-remove-url');

			var $removeLinkElement = $listGallery.find('li a');
			
			// default sort photos 
			$.galleryControl.saveSortableValues($sortInput,$listGallery);

			// upload button ui
			$uploadButton.live('click',function(){
				//alert('click');
				//$uploadButtonReal.trigger('click');
				return false;
			});

			// remove image function 
			$removeLinkElement.live('click',function(){						
				
				var $el = $(this);

				$el.parent().css({
					opacity: '0.5'
				});

				var data = {
					id: $el.parent().attr('data-id')
				}

			  	$el.parent().fadeOut(
			  		150,
			  		function(){
			  			$(this).remove();
			  			$.galleryControl.saveSortableValues($sortInput,$listGallery);
			  		}
			  	);					
				
				return false;
			});


			// sort images function
			$listGallery.sortable({
			  stop: function( event, ui ) {
			  	$.galleryControl.saveSortableValues($sortInput,$listGallery);
			  }
			});
			$listGallery.disableSelection();


			// upload images function
			var firstStart = false;

			$self.find('input[type="file"]').fileupload({
				dataType: 'json',
				add: function (e, data) {

					if(!firstStart){

						var html = '';
						$.each(data.originalFiles,function(k,v){							
							html+= '<li class="loading" id="+divId+"><i class="icon-spinner icon-spin"></i></li>';

						});						



						console.log(data.originalFiles);

						$listGallery.append(html);
						//return false;
						firstStart = true;
					}

					data.submit();
				},
				done: function (e, data) {

					$listGallery.find('li.loading').each(function(index){
						
						if(index == 0){
							$(this).attr({
									class: 'ui-state-default',
									'data-id': data.result[0].id
								}).html('<img src="'+data.result[0].path+'" /><a href="#" class="remove"></a>');
						} else {
							return false;
						}
					});

					firstStart = false;
				}
			}).bind('fileuploadstop',function(d){
				$.galleryControl.saveSortableValues($sortInput,$listGallery);
			});



		});
	};
	
})(jQuery);







(function($){
    $.formMapControl = function(el, options){

        var base = this;

        base.$el = $(el);
        base.el = el;

        base.$el.data("formMapControl", base);
        
        base.init = function(){
            
            base.options = $.extend({},$.formMapControl.defaultOptions, options);

        };
        
        base.init();
    };
    
    $.formMapControl.defaultOptions = {        

    };
    
    $.formMapControl.lazyLoadScript = function(url, callback) {

        var script = document.createElement("script")
        script.type = "text/javascript";

        if (script.readyState) { //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else { //Others
            script.onload = function () {
                callback();
            };
        }

        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
    }


    $.fn.formMapControl = function( options){
        return this.each(function(){
            (new $.formMapControl(this,options));


        var lat = -33.8688,
        	lng = 151.2195,
        	latlng = new google.maps.LatLng(lat, lng),
        	image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png'; 

        var mapOptions = {
          center: latlng,
          zoom: 13,
          scrollwheel: false,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        var map = new google.maps.Map(document.getElementById('map_canvas'),
          mapOptions);

        var input = document.getElementById('frm-registrationForm-rental-address-address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map
        });

		// google.maps.event.addListener(map, 'click', function(event) {
			
		// 	//set marker 
		// 	if(!marker){
		// 		marker = new google.maps.Marker({
		// 				      position: event.latLng,
		// 				      map: map
		// 				  });
		// 	} else {

		// 		marker.setPosition(event.latLng);
		// 	}		 

		// 	// set geocoder
  //       	var geocoder = new google.maps.Geocoder();
  //       	geocoder.geocode({ 'latLng': event.latLng} , function(r, status){
  //       		if(status == 'OK'){

  //       			$('#frm-registrationForm-rental-address-address').val(r[0].formatted_address);

  //       		} else {
  //       			alert('address error');
  //       		}
  //       	});

		// });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          input.className = '';
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // Inform the user that the place was not found and return.
            input.className = 'notfound';
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }

          marker.setPosition(place.geometry.location);
          marker.setVisible(true);
          
        });

        return false;
        });
    };
    
})(jQuery);


var maps = {};

maps.mapInit = function(){
	$("#frm-registrationForm-rental-address-address").formMapControl();
}

// lazy loading map
$(function() {

	$('#frm-registrationForm-rental-address-address').appear();
	$(document.body).on('appear', '#frm-registrationForm-rental-address-address', function(e, $affected) {           
		if(typeof $('body').attr('data-googleMapinit') == 'undefined' ){

			var script = document.createElement("script"); 
			script.src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=maps.mapInit"; 
			document.body.appendChild(script); 
			$('body').attr('data-googleMapinit',true);  
		}
	});
		

});
      
