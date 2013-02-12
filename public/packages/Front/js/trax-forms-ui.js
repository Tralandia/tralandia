
/* tweet button */
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

/**
 * custom Nette addError function
 * Display error message.
 */
 /*
Nette.addError = function(elem, message) {
	if (elem.focus) {
		elem.focus();
	}
	if (message) {		
		
			var errorMessageDiv = $('#'+elem.getAttribute('data-validation-message-div-id'));
			var controllGroup = $('#'+elem.getAttribute('data-validation-controll-div-id'));

			errorMessageDiv.html(message);

			if(!controllGroup.hasClass('error')){
				controllGroup.addClass('error');
			}		
		
	}
};
*/



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

			$self.click(function(){
				$self.find('.socialBtnContent').removeClass('hide');
				$self.find('.socialBtnHeader').addClass('hide');
			});


		});
	};
	
})(jQuery);





// autosuggest form search
(function($){
	$.searchFormSuggest = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("searchFormSuggest", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.searchFormSuggest.defaultOptions, options);            

		};
		
		base.init();
	};
	
	$.searchFormSuggest.defaultOptions = {
		
	};
	
	$.fn.searchFormSuggest = function(options){
		return this.each(function(){

			var self = this;
			var $self = $(this);

			var url = $self.attr('data-url');

			var placeholder = $self.attr('data-placeholder');

			var conditionsText = $self.attr('data-conditions-text');

			$('#serachSidebar').select2({
			    placeholder: placeholder,
			    minimumInputLength: 1,
			    ajax: { 
			        url: url,
			        dataType: 'json',
			        data: function (term, page) {
			            return {
			                string: term, 
			            };
			        },
			        results: function (data, page) { 
			            return {results: data.counties};
			        }
			    },
			    formatResult: function(r){
			    	return '<img class="flag" src="'+r.flag+'"> '+r.name;
			    }, 

			    formatSelection: function(r){
			    	return r.name;
			    },
			   
			    
			    escapeMarkup: function (m) { return m; } ,
			    formatInputTooShort: function (input, min) { 
			    	var n = min - input.length; 
			    	//conditionsText = conditionsText.split('%');
			    	return '';
			    	//return conditionsText[0] + n + conditionsText[1] + (n == 1? "" : "s"); 
			    }

			});



		});
	};
	
})(jQuery);




// universal form ajax
/*
(function($){
	$.ajaxForm = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("ajaxForm", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.ajaxForm.defaultOptions, options);            

		};
		
		base.init();
	};
	
	$.ajaxForm.defaultOptions = {
		
	};
	
	$.fn.ajaxForm = function(options){
		return this.each(function(){

			var self = this;
			var $self = $(this);

			var url  = $self.attr('action') || '/';
			var requestType = $self.attr('method') || 'post';
			var $errorMsgDiv = $self.find('.errorMsg');

			$self.live('submit',function(){

				var data = $self.serialize() || {};

				$.ajax({
				  url: url,
				  data: data,
				  type: requestType,
				  dataType: 'JSON',
				}).done(function(d) {
				  if(!d.status && d.msg){
						$errorMsgDiv.show();
						$errorMsgDiv.html(d.msg);
				  }
				});


				return false;

			});


		});
	};
	
})(jQuery);
*/




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
					$self.find('.phraseControll').addClass('hide');
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




// gps map controll

(function($){
	$.traMapControll = function(el, options){
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;
		
		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;
		
		// Add a reverse reference to the DOM object
		base.$el.data("traMapControll", base);
		
		base.init = function(){
			
			base.options = $.extend({},$.traMapControll.defaultOptions, options);            

		};


		

		base.init();
	};
	
	$.traMapControll.defaultOptions = {

	};

	$.traMapControll.ajax = function(url,data,callback){

		$.ajax({
			  type: "POST",
			  url: url,
			  data: data,
			  dataType: 'json',
			}).done(callback);


		console.log(data);


		callback(data);

	}

	$.traMapControll.removeError = function(elem){
		
		var errorMessageDiv = $('#'+$(elem).attr('data-validation-message-div-id'));
		var controllGroup = $('#'+$(elem).attr('data-validation-controll-div-id'));

		errorMessageDiv.html('');

		if(controllGroup.hasClass('error')){
			controllGroup.removeClass('error');
		}		 

	}

	// add error message 
	$.traMapControll.addError = function(elem,message){
		
		var errorMessageDiv = $('#'+elem.attr('data-validation-message-div-id'));
		var controllGroup = $('#'+elem.attr('data-validation-controll-div-id'));

		errorMessageDiv.html(message);

		if(!controllGroup.hasClass('error')){
			controllGroup.addClass('error');
		}		
	}

	// before ajax request validation
	$.traMapControll.responseValidation = function(data){
		$.each(data.elements,function(k,v){
			if(!v.status){
				
				$input = $($("[name='"+k+"']"));
				$input.val(v.value);
	
				if(v.message){
					$.traMapControll.addError($input,v.message);
				}
				

			}
		});
	}

	// js validation client site with Nette validator
	// using after send ajax request
	$.traMapControll.validateInputs = function(p){
	
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
					$.traMapControll.removeError(this);
				}

				o.data[inputName] = $(this).val();		

			}

		}); 

		return o;

	}
	
	$.fn.traMapControll = function(options){
		return this.each(function(){
			(new $.traMapControll(this, options));

			var self = this;
			var $self = $(this);

			var requestUrl = $self.attr('data-link');

			// render map and add map listener
			var $mapDiv = $self.find('div.mapRender');

			var $inputLat = $self.find('input.latitude');
			var $inputLng = $self.find('input.longitude');

            var zoom = $(this).attr('data-zoom');
				var coordinates = $mapDiv.attr('data-value').split(',');
				var lat = parseFloat(coordinates[0]);
				var lng = parseFloat(coordinates[1]);

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
				
				$.traMapControll.call();

				  marker.setPosition(event.latLng);

				  var lat = event.latLng.gb;
				  var lng = event.latLng.hb;
				  $inputLat.val(lat);
				  $inputLng.val(lng);
				  

				  $('#gps_position').html(lat+' '+lng);
				  //map.setCenter(event.latLng);


					v = $.traMapControll.validateInputs($self);
					
					
					// send data
						
					$.traMapControll.ajax(requestUrl,v.data , function(data){
					
						if(!data.status){
							$.traMapControll.responseValidation(data);
							
							//var newPosition = new google.maps.LatLng(data.gps.lat,data.gps.lng);
							//	marker.setPosition(newPosition);
						}
					});
					

			  });  



				var inputs = {};

				$self.find('button').click(function(){

					v = $.traMapControll.validateInputs($self);
					
					if(v.valid){
						// send data
						
						$.traMapControll.ajax(requestUrl,v.data , function(data){
							//console.log(data);
							if(!data.status){
								$.traMapControll.responseValidation(data);
								
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







// photo controll
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

			var $sortInput = $self.find('#frm-baseForm-photos-sort');

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

				setTimeout(function(){
				  	$el.parent().fadeOut({
				  		complete: function(){
				  			$(this).remove();
				  			$.galleryControl.saveSortableValues($sortInput,$listGallery);
				  		}
				  	});					
				},1000);

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
							html+= '<li class="loading" id="+divId+"></li>';
						});

						$listGallery.append(html);

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
								}).html('<img src="'+data.result[0].path+'" /><a href="#" class="remove">#delete</a>');
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








