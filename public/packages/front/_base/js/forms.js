$(function(){

	$('.showOptionsCheckbox').on('change',function(){
		$('.phraseFormHeader').toggleClass('hide');
	});
	$('.priceListRowsContainer input[type=file]').fileInputUi();


    $('input[name=nospam]').each(function(e){
        $('input[name=nospam]').val('no'+'spam');
    });


});



// phrase form
(function($){
	$.fileInputUi = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$button = base.$el.parents('.btn').find('span');

		base.$el.data("fileInputUi", base);

		base.btnSelector = '.btn';
		base.btnInactiveClass = 'btn-green';
		base.btnActiveClass = 'btn-orange';

		base.init = function()
		{
			base.bind();
		};

		base.bind = function()
		{
			base.$el.on('change',base.change);
		}

		base.change = function()
		{
			if(base.$el.val().length > 0){
				base.setSelectedButton(base.parseName(base.$el.val()),base.btnInactiveClass,base.btnActiveClass);
			} else {
				if(base.$el.parents(base.btnSelector).hasClass(base.btnActiveClass)){
					base.setSelectedButton(base.$el.parents(base.btnSelector).data('placeholder'),base.btnActiveClass,base.btnInactiveClass);
				}
			}
		}

		base.setSelectedButton = function(buttonText,removeClass,buttonClass)
		{
			base.$button.html(buttonText).parents(base.btnSelector).removeClass(removeClass).addClass(buttonClass);
		}

		base.parseName = function(fileName)
		{
			var returnName = fileName.split('\\');
				return returnName[returnName.length-1];
		}

		base.init();
	};



	$.fn.fileInputUi = function(options){
		return this.each(function(){
			(new $.fileInputUi(this, options));
		});
	};

})(jQuery);


// phrase form
(function($){
	$.phraseForm = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;
		base.select = $('.toLanguages');

		base.$el.data("phraseForm", base);

		base.init = function(){
			base.defaultRender();
			base.bind();
		};

		base.defaultRender = function(){
			base.showCurrentLanguage();
		}

		base.bind = function(){
			base.select.on('change',base.showCurrentLanguage);
		}


		base.showCurrentLanguage = function(){
			var visibleID = base.select.val();
			visibleID = '.'+visibleID+'_phrase';
			$('.phrasecontrol').addClass('hide');
			$(visibleID).removeClass('hide');

		}

		base.init();
	};



	$.fn.phraseForm = function(options){
		return this.each(function(){
			(new $.phraseForm(this, options));
		});
	};

})(jQuery);


// form invalid scroll
(function($){
	$.invalidScroll = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("invalidScroll", base);

		base.init = function(){
			base.findInvalidInput();
		};

		base.findInvalidInput = function(){

				var invalid = base.$el.find('.invalid:first');

				if(invalid.length > 0){

					var invalidInput = invalid.find('input,textarea,select,checkbox');

					var invalidGenerateId = 'formInfalidScroll-'+invalidInput.attr('id');

						invalid.attr('id',invalidGenerateId);

					$.scrollTo('#'+invalidGenerateId,1200);
					return false;

				}

		}

		base.init();
	};



	$.fn.invalidScroll = function(options){
		return this.each(function(){
			(new $.invalidScroll(this, options));
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
	$.mapControl = function(el, options){
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;

		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;

		// Add a reverse reference to the DOM object
		base.$el.data("mapControl", base);

		base.init = function(){

			base.options = $.extend({},$.mapControl.defaultOptions, options);

		};




		base.init();
	};

	$.mapControl.defaultOptions = {

	};

	$.mapControl.ajax = function(url,data,callback){

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

	$.mapControl.removeError = function(elem){

		var errorMessageDiv = $('#'+$(elem).attr('data-validation-message-div-id'));
		var controlGroup = $('#'+$(elem).attr('data-validation-control-div-id'));

		errorMessageDiv.html('');

		if(controlGroup.hasClass('error')){
			controlGroup.removeClass('error');
		}

	}

	// add error message
	$.mapControl.addError = function(elem,message){

		var errorMessageDiv = $('#'+elem.attr('data-validation-message-div-id'));
		var controlGroup = $('#'+elem.attr('data-validation-control-div-id'));

		errorMessageDiv.html(message);

		if(!controlGroup.hasClass('error')){
			controlGroup.addClass('error');
		}
	}

	// before ajax request validation
	$.mapControl.responseValidation = function(data){
		$.each(data.elements,function(k,v){
			if(!v.status){

				$input = $($("[name='"+k+"']"));
				$input.val(v.value);

				if(v.message){
					$.mapControl.addError($input,v.message);
				}


			}
		});
	}

	// js validation client site with Nette validator
	// using after send ajax request
	$.mapControl.validateInputs = function(p){

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
					$.mapControl.removeError(this);
				}

				o.data[inputName] = $(this).val();

			}

		});

		return o;

	}



	$.fn.mapControl = function(options){
		return this.each(function(){
			(new $.mapControl(this, options));



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

				// var lat = parseFloat($(this).attr('data-latitude'));
				// var lng = parseFloat($(this).attr('data-longitude'));

				console.log('init maops plugin'+lat+' '+lng+' '+zoom);

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

					$.mapControl.call();

					marker.setPosition(event.latLng);

					var lat = event.latLng.gb;
					var lng = event.latLng.hb;
					$inputLat.val(lat);
					$inputLng.val(lng);


					$('#gps_position').html(lat+' '+lng);
				  //map.setCenter(event.latLng);


				  v = $.mapControl.validateInputs($self);

					// send data

					$.mapControl.ajax(requestUrl,v.data , function(data){

						if(!data.status){
							$.mapControl.responseValidation(data);

							//var newPosition = new google.maps.LatLng(data.gps.lat,data.gps.lng);
							//	marker.setPosition(newPosition);
						}
					});


				});



				var inputs = {};

				$self.find('button').click(function(){

					v = $.mapControl.validateInputs($self);

					if(v.valid){
						// send data

						$.mapControl.ajax(requestUrl,v.data , function(data){
							//console.log(data);
							if(!data.status){
								$.mapControl.responseValidation(data);

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

		var base = this;

		base.$el = $(el);
		base.el = el;

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

			var $buttonSave = $('.calendarSumitButton');

			var inputValues = $input.val();
			console.log(inputValues);

			if(defaultValue.length > 0){
				$.calendarEdit.conteiner = defaultValue.split(',');
			}

			$input.on('change',function(){
				console.log($(this).val());
			})

			$calendarForm.find('.calendar').each(function(i){

				var calendar = this;
				var $calendar = $(this);

				$calendar.find('.day.active').click(function(){

					var currentTime = $(this).attr('data-day');
					// console.log(currentTime);

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

					var newInputValues = $input.val();

					if(newInputValues != inputValues){
						$buttonSave.attr('disabled',false);
						$buttonSave.find('small').html($buttonSave.data('activeText'));
					} else {
						$buttonSave.attr('disabled',true);
						$buttonSave.find('small').html($buttonSave.data('inactiveText'));
					}


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
		$input.val($.galleryControl.sortableArray($elem)).trigger('change');
	}


	$.fn.galleryControl = function(options){

		return this.each(function(){

			var self = this;
			var $self = $(this);

			var $controlParams = $self.find('.photoControlParams');
			var $ajaxErrors = $self.find('.ajaxErrors');

			var errors = {
				wrongFile: $controlParams.data('wrongFileMessage'),
				uploadFail: $controlParams.data('uploadFailMessage'),
				smallImage: $controlParams.data('smallImageMessage')
			};

			var $uploadButton = $self.find('button');
			var $sortInput = $self.find('input[type=hidden]');
			var $listGallery = $self.find('#sortable');
            var $removeLinkElement = $listGallery.find('li a');
//			var $uploadButtonReal = $self.find('input[type=file]');
//			var sortableUrl = $listGallery.attr('data-url');
//			var removeUrl = $listGallery.attr('data-remove-url');

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

					$self.find('span.fileinput-button').addClass('disabled').find('input[type=file]').attr('disabled',true);

					$ajaxErrors.html('');

					if(!firstStart){

						var html = '';
						$.each(data.originalFiles,function(k,v){
							html+= '<li data-name="'+data.originalFiles[k].name+'" class="loading" id="+divId+"><i class="icon-spinner icon-spin"></i></li>';
						});

						$listGallery.append(html);
						$listGallery.find('.addPhotoButton').appendTo('#sortable');
						//return false;
						firstStart = true;

					}

					data.submit();
				},
				done: function (e, data) {

					$self.find('span.fileinput-button').removeClass('disabled').find('input[type=file]').attr('disabled',false);

					$listGallery.find('li.loading').each(function(index){

						if(index == 0){

							if(data.result[0].error){

								$ajaxErrors.append('<li>'+$(this).data('name')+' <strong>'+errors[data.result[0].error]+'</strong></li>')

								$(this).remove();

							} else {

								$(this).attr({
									'class': 'ui-state-default',
									'data-id': data.result[0].id
								}).html('<img src="'+data.result[0].path+'" /><a href="#" class="remove"></a>');

							}
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

	$.fn.updateFormGeo = function(lat,lng){
		$('.rentalAddressLatitude').val(lat);
		$('.rentalAddressLongitude').val(lng);
	};

	$.fn.formMapControl = function( options){
		return this.each(function(){
			(new $.formMapControl(this,options));


			var lat = $('#map_canvas').attr('data-latitude'),
			lng = $('#map_canvas').attr('data-longitude'),
			latlng = new google.maps.LatLng(lat, lng),
			image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png',
			zoom = $('#map_canvas').attr('data-zoom') || 12;


			var mapOptions = {
				center: latlng,
				zoom: parseInt(zoom),
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var map = new google.maps.Map(document.getElementById('map_canvas'),
				mapOptions);

			var input = $('.rentalAutocompleteAddress').get(0);
				currentId = '#'+$('.rentalAutocompleteAddress').attr('id');
				// console.log(currentId);


			var autocomplete = new google.maps.places.Autocomplete(input);

			autocomplete.bindTo('bounds', map);

			var infowindow = new google.maps.InfoWindow();

			var marker = false;

			if(parseInt($('#map_canvas').attr('data-show-marker')) == 1){

				marker = new google.maps.Marker({
					map: map,
					position:latlng
				});

			}

			google.maps.event.addListener(map, 'click', function(event) {

				if(marker){
					marker.setPosition(event.latLng);
				} else {
					marker = new google.maps.Marker({
						map: map,
						position:event.latLng
					});
				}

			// set geocoder
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({ 'latLng': event.latLng} , function(r, status){
				if(status == 'OK'){
					$.fn.updateFormGeo(event.latLng.jb,event.latLng.kb);
					$(currentId).val(r[0].formatted_address);
				} else {
					alert('address error');
				}
			});

		});

			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				infowindow.close();
		  // marker.setVisible(false);
		  input.className = '';
		  var place = autocomplete.getPlace();
		  if (!place.geometry) {
			input.className = 'notfound';
			return;
		  }

		  if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		  } else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);
		  }

		  $.fn.updateFormGeo(place.geometry.location.jb,place.geometry.location.kb);
		  // console.log(place.geometry.location.jb);

		  if(marker){
			marker.setPosition(place.geometry.location);
		  } else {
			marker = new google.maps.Marker({
				map: map,
				position:place.geometry.location
			});
		  }

	  });

			return false;
		});
};

})(jQuery);


var maps = {};

maps.mapInit = function(){
	$(".mapControl").formMapControl();
}

// lazy loading map
$(function() {

	$('form:not(.allowEnterSubmit) input').keypress(function(e) {
		if(e.which == 13) {
			return false;
		}
	});

	$('.mapControl').appear();
	$(document.body).on('appear', '.mapControl', function(e, $affected) {
		if(typeof $('body').attr('data-google-map-init') == 'undefined' ){
			var lang = $('html').attr('lang');
			var script = document.createElement("script");

			script.src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=maps.mapInit&language="+lang;
			document.body.appendChild(script);
			$('body').attr('data-google-map-init',true);
		}
	});







$('button[type=submit]').click(function(){
	$(this).addClass('active');
});
$('button[type=submit]').live('click',function(){
	$(this).addClass('active');
});

	$('.priceList .remove').on('click',removePriceLine);
	$('.priceList .remove').live('click',removePriceLine);

	$('.pricelistControlButton a.createNewLine').on('click',createNewLineInPriceList);

});


//

function removePriceLine(){
		if($('.priceList').length > 1){
			$(this).parents('.priceList').remove();
		} else {
			$(this).parents('.priceList').find('input,select').val('');
		}
}

function createNewLineInPriceList(){

	 var pattern = $('.priceList:first').clone();

		pattern.find('select,input').removeAttr('id');
		pattern.find('select').removeClass('select2').removeClass('select2-offscreen');
		pattern.find('.select2-container').remove();

		var lastExistNameIterator = $('.pricelistControl').find('input[type=text].price').length;

		pattern.find('select,input').each(function(){
			var originName = $(this).attr('name');
				$(this).attr('name',originName.replace("[0]","["+(lastExistNameIterator)+"]"));
		})



		$('.pricelistControl').append(pattern);

	return false;
}

// rental price upload plugin

(function($){
	$.rentalPriceUpload = function(el, options){

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data("rentalPriceUpload", base);

		base.init = function(){
			base.initElementSelectors();
			base.initElements();
			base.bindElements();
		};

		base.initElementSelectors = function(){
			base.removeButtonSelector = 'span.remove';
			base.addButtonSelector = 'a.addLine';
			base.firstRowSelector = '.rentalPriceRow:first';
			base.listContainer = '.priceListRowsContainer';
			base.select2Container = '.select2-container';
			base.rowSelector = '.rentalPriceRow';
			base.inputElements = 'select,input';
		}

		base.initElements = function(){
			base.$addButton = base.$el.find(base.addButtonSelector);
			base.$removeButton = base.$el.find(base.removeButtonSelector);
		}

		base.bindElements = function(){
			base.$addButton.click(base.addRow);
			base.$removeButton.click(base.removeRow);
			base.$removeButton.live('click',base.removeRow);
		}

		base.removeRow = function(){
			var count = $(base.listContainer).find(base.rowSelector).length;
			if(count > 1){
				$(this).parents(base.rowSelector).remove();
			} else {

				$(this).parents(base.rowSelector).find(base.inputElements).val('');

				var $uploadButton = $(this).parents(base.rowSelector).find('.btn');

				if($uploadButton.hasClass('btn-orange')){
					$uploadButton.removeClass('btn-orange').addClass('btn-green').find('span').html($uploadButton.data('placeholder'));
				}
			}

		}

		base._resetValue = function($input){
			switch ($input[0].tagName){
				case 'INPUT' :
					$input.val('');

					if($input[0].type == 'file'){
						// console.log($input.parents('.btn'));
						// console.log('tututututuu');
						$input.parents('.btn').removeClass('btn-orange').addClass('btn-green').find('span').html($input.parents('.btn').data('placeholder'));
						$input.fileInputUi();
					}

					break;
				case 'SELECT' :
					// $input.find('option').attr('selected',false);
					break;
			}
		}

		base._createNewRow = function(){
			// create pattern from first row
			var pattern = base.$el.find(base.firstRowSelector).clone();
				pattern.find(base.inputElements)
						.removeAttr('id');


			// create input names
			var count = (base.$el.find(base.rowSelector).length);

			pattern.find(base.inputElements).each(function(){

				// console.log($(this)[0].tagName);

				base._resetValue($(this));

				var originName = $(this).attr('name');
					$(this).attr('name',originName.replace("[0]","["+(count)+"]"));
			})



			base.$el.find(base.listContainer).append(pattern);

		}

		base.addRow = function(){
			base._createNewRow();
			return false;
		}


		base.init();
	};



	$.fn.rentalPriceUpload = function(options){
		return this.each(function(){
			(new $.rentalPriceUpload(this, options));
		});
	};

})(jQuery);



$(function(){

	$('.rentalPriceUpload').rentalPriceUpload();
	$('form').invalidScroll();

	$('.rentalRemoveLink').click(function(){
		var alertText = {
			first: $(this).data('alertFirst'),
			last: $(this).data('alertLast'),
		};

		if(confirm(alertText.first)){
			if(confirm(alertText.last)) {
				return true;
			}
		}

		return false;
	});
});
