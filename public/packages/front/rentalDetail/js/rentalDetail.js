(function($){
	$.traxDetailMap = function(el, options){

		var base = this;
		
		base.$el = $(el);
		base.el = el;
		base.markers = [];
		base.$el.data("traxDetailMap", base);
		
		base.init = function(){
						
			base.options = $.extend({},$.traxDetailMap.defaultOptions, options);

			base.options.mapZoom = base.$el.data('zoom');

			base._loadMap();

		};
		
		base._loadMap = function(){
			base.rentalId = parseInt(base.$el.data('rentalId'));
			base.mapCoordinates = base.$el.attr('value');
			base.mapCoordinates = base.mapCoordinates.split(',');

			base.map = {
				lat: parseFloat(base.mapCoordinates[0]),
				lng: parseFloat(base.mapCoordinates[1])
			};


			base.myLatlng = new google.maps.LatLng(base.map.lat,base.map.lng);

			var mapOptions = {
				zoom: base.options.mapZoom,
				scrollwheel: false,
				center: base.myLatlng,
				mapTypeId: google.maps.MapTypeId.ROUTE
				// mapTypeId: google.maps.MapTypeId.HYBRID
			}

			base.googleMap = new google.maps.Map(document.getElementById(base.$el.attr('id')), mapOptions);

			var marker = new google.maps.Marker({
				position: base.myLatlng,
				map: base.googleMap,
				icon: base._getMarkerIcon()
			});


			$('body').attr('data-google-map-render',true);

			base._bindMapListener();

			base._renderOtherRentals(base.mapCoordinates[0],base.mapCoordinates[1]);

		};

		base._bindMapListener = function(){

			google.maps.event.addListener(base.googleMap, 'dragend', base._afterMapListener);
			google.maps.event.addListener(base.googleMap, 'zoom_changed', base._afterMapListener);

		};

		base._afterMapListener = function(){
			var zoomLevel = base.googleMap.getZoom();
			var bounds = base.googleMap.getBounds();
			// @tmp
			var center = base.googleMap.getCenter();
		   
			// console.log(base.googleMap.getCenter());

			// console.log(zoomLevel);
			// console.log(bounds.getNorthEast());
			// console.log(bounds.getSouthWest());

			// base._renderOtherRentals(center.mb,center.nb);
		};

		base._getMarkerIcon = function(type){

			var isFavorites = false;
			var myFavorites = $.cookie(base.options.favoritesCookieKey);

			if(typeof myFavorites != 'undefined' && myFavorites != null)
			{
				myFavorites = myFavorites.split(',');

				$.each(myFavorites,function(k,v){
					if(base.rentalId == v){
						isFavorites = true;
					}
				});					
			}

			var iconName = '';

			if(isFavorites){
				iconName  = base.options.iconSet.large.heart;
			} else {
				iconName  = base.options.iconSet.large.home;
			} 

			if(typeof type != 'undefined'){
				switch(type){
					case 'other-small':
						iconName  = base.options.iconSet.small.inactive;
						break;
					case 'other-mini':
						iconName  = base.options.iconSet.mini.inactive;
						break;
					case 'other-small-active':
						iconName  = base.options.iconSet.small.active;
					console.log(iconName);

						break;
					case 'other-mini-active':
						iconName  = base.options.iconSet.mini.active;
						break;												
				}
			}


			return base.options.iconPath + iconName;    		
		};
	
		//  @todo ajax callback
		base._renderOtherRentals = function(lat,lng){

			var html = '';

			$.each(base._returnRentalsTmp(lat,lng),function(k,v){


				var markerSize = 'other-mini';

					if(v.isImportant){
						markerSize = 'other-small';
					}

				base._makeMarker(v.lat,v.lng,v.name,markerSize,k);

				if(k < 9){
					html += '<li data-id="'+v.id+'" data-meta=\''+JSON.stringify(v)+'\'><img src="'+v.thumbnail+'"></li>';
				}

			});

			$(base.options.otherRentaliListSelector).html(html);

			$(base.options.otherRentaliListSelector+' li').live('click',base._listOtherClickListener);

		};

		base._listOtherClickListener = function(){
			
			base._showInfoBox($(this).data('meta'));

		};

		base._showInfoBox = function(data){

			if(typeof base.$infoBox == 'undefined'){
				base.$infoBox = $('.rentalMapMetaBox');
				base.$infoBox.removeClass('hide');
			}

			base.$infoBox.slideDown({
				duration: 200
			});

			if(typeof base.$metaInfoBox == 'undefined'){
				base.$metaInfoBox = {
					thumb: base.$infoBox.find('.thumb'),
					title: base.$infoBox.find('.content h3 a'),
					info1: base.$infoBox.find('.content .teaser'),
					info2: base.$infoBox.find('.amenities'),
					info3: base.$infoBox.find('.food span'),
					close: base.$infoBox.find('.remove'),
					capacity: base.$infoBox.find('.count span'),
					capacityText: base.$infoBox.find('.capacity small'),

					price: base.$infoBox.find('.price'),
					priceText: base.$infoBox.find('.price small'),				
				};
			}

			// close box function 
			base.$metaInfoBox.close.click(function(){
				base.$infoBox.slideUp({
					duration: 200
				});
			});			

			// update box information 
			base.$metaInfoBox.thumb.html('<img src="'+data.thumbnail+'">');
			base.$metaInfoBox.title.html(data.name).attr('href',data.url);
			base.$metaInfoBox.info1.html(data.info1);
			base.$metaInfoBox.info2.html(data.info2);
			base.$metaInfoBox.info3.html(data.info3);
			base.$metaInfoBox.capacity.html(data.box.capacity);
			base.$metaInfoBox.capacityText.html(data.box.capacityText);
			base.$metaInfoBox.price.html(data.box.price);
			base.$metaInfoBox.priceText.html(data.box.priceText);

			// set current list 
			base._setCurrentRentalInList(data.id);

		};

		base._setCurrentRentalInList = function(currentId){
			// set surrent class 
			$(base.options.otherRentaliListSelector+' li').each(function(){
				if($(this).data('id') != currentId){
					$(this).removeClass('current');
				} else {
					$(this).addClass('current');
				}
			});

			$.each(base.markers,function(k,v){
				// console.log(v);
				if(typeof v != 'undefined'){
					if(k!=currentId){
						var IconType = (base.responSedata[k].isImportant) ? "other-small" : "other-mini";
						v.setIcon(base._getMarkerIcon(IconType));
					}					
				}

			});
		};

		base._makeMarker = function(lat,lng,title,size,markerId){

			var markerOptions = {
					map: base.googleMap, 
					position: new google.maps.LatLng(lat, lng),
					title: title,
					icon: base._getMarkerIcon(size),
				};
			
			var nm = new google.maps.Marker(markerOptions);
				nm.set('id',markerId);
				base.markers[markerId]=nm;

			google.maps.event.addListener(nm, 'click', function() { 
				if(base.googleMap.getZoom() > 11){
					var IconType = (base.responSedata[nm.id].isImportant) ? "other-small-active" : "other-mini-active";
					base._setCurrentRentalInList(nm.id);
					base._showInfoBox(base.responSedata[nm.id]);
					nm.setIcon(base._getMarkerIcon(IconType));
				}
			});

		};

		base._returnRentalsTmp = function(lat,lng){

			var data = [];

			var delta = 900000000;
			var dimensions = {
				helpLat : lat.toString().split('.'),
				helpLng : lng.toString().split('.'),
			}

			var thubnails = [];
				thubnails[0] = 'http://tralandiastatic.com/rental_images/2013_06/03/yn/1e/medium.jpeg';
				thubnails[1] = 'http://tralandiastatic.com/rental_images/2013_06/05/f0/ds/medium.jpeg';
				thubnails[2] = 'http://tralandiastatic.com/rental_images/2013_06/04/s4/85/medium.jpeg';
				thubnails[3] = 'http://tralandiastatic.com/rental_images/2013_06/03/5p/18/medium.jpeg';
				thubnails[4] = 'http://tralandiastatic.com/rental_images/2013_06/05/40/cq/medium.jpeg';
				thubnails[5] = 'http://tralandiastatic.com/rental_images/2013_06/05/k6/19/medium.jpeg';
				thubnails[6] = 'http://tralandiastatic.com/rental_images/2013_06/05/xa/u6/medium.jpeg';
				thubnails[7] = 'http://tralandiastatic.com/rental_images/2013_06/03/of/6j/medium.jpeg';
				thubnails[8] = 'http://tralandiastatic.com/rental_images/2013_06/05/gv/23/medium.jpeg';
				thubnails[9] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[10] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[11] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[12] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[13] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[14] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[15] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';
				thubnails[16] = 'http://tralandiastatic.com/rental_images/2013_06/05/2m/cj/medium.jpeg';

			for(var i = 0 ; i < 15 ; ++i){

				dimensions.helpLat[1] = dimensions.helpLat[1] - Math.floor(Math.random() * (delta) + 1);
				dimensions.helpLng[1] = dimensions.helpLng[1] - Math.floor(Math.random() * (delta) + 1);

				var forPush = {

					name: 'tralala '+i,
					id: i+Math.floor(Math.random() * (30000 - 0 + 1) + 0),
					info1: 'teaser slogan neskutocny',
					box: {
						capacity: Math.floor(Math.random() * (50 - 1 + 1) + 1)+' osob',
						capacityText: '',
						price: Math.floor(Math.random() * (100 - 17 + 1) + 17)+' EUR',
						priceText: 'osoba/noc',
					},
					info2: 'Studňa, zváračka, cukrová repa, zelovoc, cédéčka, Studňa, zváračka, cukrová repa, zelovoc, cédéčka',
					info3: 'strava bude',
					url: 'http://www.sk.tra.com/utulny-privat-kosar-v-tichom-prostredi-r21501',
					thumbnail: thubnails[i],
					lat: dimensions.helpLat[0]+'.'+dimensions.helpLat[1],
					lng: dimensions.helpLng[0]+'.'+dimensions.helpLng[1],
					isImportant: Math.floor(Math.random() * (1 - 0 + 1) + 0)

				};
				
				data.push(forPush);
			}

			base.responSedata = data;


			return data;
			
		}
		
		base.init();
	};
	
	$.traxDetailMap.defaultOptions = {
		mapZoom: 4,
		iconPath: '../../../../images/markers/',
		favoritesCookieKey: 'favoritesList',
		iconSet: {
			large: {
				heart: 'map-pointer-heart.png',
				home: 'map-pointer-home.png'
			},
			small: {
				inactive: 'other-small-inactive.png',
				active: 'other-small-active.png',
			},
			mini: {
				inactive: 'other-mini-inactive.png',
				active: 'other-mini-active.png',
			}			
		},
		otherRentaliListSelector: 'ul.relatedRentals',
	};
	
	$.fn.traxDetailMap = function(options){
		return this.each(function(){(new $.traxDetailMap(this, options));});};
	
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





var socialShitInitDetail = false;
var socialIconsMenuDetailSelector = '#socialIconsMenuDetail';
var socialShitFirstInitDetail = false;

$(document).ready(function(){

	// tmp
	$('.objectDetailServicesIconList').click(function(){
		$(this).find('.amenitiesStart li').toggleClass('open');

		// $(this).find('.amenitiesStart li').animate({
		// 	whiteSpace: 'normal',
		// 	height: 'auto'
		// })
	});

	var socialIconsDetailHeader = '.socialBtnHeader';



	// load social icons 
	$(socialIconsDetailHeader).on('click',function(){

		if(socialShitInitDetail){
			$(socialIconsMenuDetailSelector).hide();
			socialShitInitDetail = false;
		} else {
			Socialite.load($(socialIconsMenuDetailSelector));
			$(socialIconsMenuDetailSelector).show();

			// loader
			if(!socialShitFirstInitDetail){
				$socialLinksList = $('.socialIconTable').find('li.socialIconCover');
				$socialLoaderList = $('.socialIconTable').find('li.loader');

				setTimeout(function(){
					$socialLinksList.removeClass('hide');
					$socialLoaderList.addClass('hide');
				},2000);					
			}

			socialShitFirstInitDetail = true;
			setTimeout(function(){
				socialShitInitDetail = true;
			},100);					
		}
	});

	$('.pinterestShare').click(function(){

		Socialite.load($(this)[0]);

		$(this).find('i').remove();

		if(!$(this).hasClass('opened')){
			$(this).addClass('opened');
			
			return false;			
		}

	});

	rentalDetailDatepickerInit();

});



function onChangeCalendar(self){

	var reservations = $(self).data('reservations');
		reservations = reservations.split(',');
		

	setTimeout(function(){

		var my = $('.ui-datepicker-calendar td:not(.ui-state-disabled):first');

		var m = my.data('month')+1; 
		var y = my.data('year');

		var statusClass = {
			start: 'stat0',
			middle: 'stat1',
			stop: 'stat2'
		};

			my = '-'+m+'-'+y;

			var status = 0;

			$('.ui-datepicker-calendar td:not(.ui-datepicker-other-month)').each(function(index){
				var realDate = (index+1)+my;
				var nextDate = (index+2)+my;

					// console.log(realDate);

					// console.log(jQuery.inArray(realDate, reservations));

					if(jQuery.inArray(realDate, reservations) != -1){
						

						// console.log(status);

						switch(status){
							case 0 :
								datepickerSetClass(this,statusClass.start);
								status = 1;
								break;
							case 1 :
								
								if(jQuery.inArray(nextDate, reservations) != -1){
									datepickerSetClass(this,statusClass.middle);
									status = 1;
								} else {
									datepickerSetClass(this,statusClass.stop);
									status = 0;
								}
									
						}
					}
				
			});

	},100);

}


function datepickerSetClass(elem,cssClass){
	var $el = $(elem);

	var point = $el.find('a');

		if(point.length > 0){
			point.addClass(cssClass);
		} else {
			$el.find('span').addClass(cssClass);
		}

}



function rentalDetailDatepickerInit(){

	var fromDateOrigin = {
		year: 0,
		month: 0,
		day:0,
		date: new Date(),
		dayPlus:0,
	}

	$('.datepicker').click(function(){
		$(this).addClass('focus');
	});

	var lang = $( "html" ).attr('lang');

	$.datepicker.setDefaults(  $.datepicker.regional[ lang ] );


	$( ".datepicker" ).datepicker({ 
		minDate: 0, 
		maxDate: "+12M +10D" , 
		firstDay: 1,

		dateFormat: "yy-mm-dd",
		beforeShow: function(textbox, instance){
			instance.dpDiv.css({
					marginLeft: '0px'
			});	

			onChangeCalendar(this);
				
		},
		onChangeMonthYear: function(){
			onChangeCalendar(this);
		}	
	});	


	$( ".datepickerto" ).datepicker({ 
		minDate: new Date(2013, 1, 28), 
		maxDate: "+12M +10D" ,
		dateFormat: "yy-mm-dd" ,
		firstDay: 1,
		beforeShow: function(textbox, instance){


			instance.dpDiv.css({
					
					marginLeft: (-textbox.offsetWidth-10) + 'px'
			});			

			var fromValues = $('.datepicker').val().split('-');

			if ( fromValues.length > 1 ) {

				fromDateOrigin.year = parseInt(fromValues[0]);
				fromDateOrigin.month = parseInt(fromValues[1])-1;
				fromDateOrigin.day = parseInt(fromValues[2]);
				
				fromDateOrigin.date.setYear(fromDateOrigin.year);
				fromDateOrigin.date.setMonth(fromDateOrigin.month);
				fromDateOrigin.date.setDate(fromDateOrigin.day);

				fromDateOrigin.dayPlus = new Date(fromDateOrigin.date.getFullYear(), fromDateOrigin.date.getMonth(), fromDateOrigin.date.getDate()+1);

				$( this ).datepicker("option",{ minDate: fromDateOrigin.dayPlus, maxDate: "+12M +10D" });	

			} else {
				$( this ).datepicker("option",{ minDate: 1 , maxDate: "+12M +10D" });
			}

			onChangeCalendar(this);

		},
		onChangeMonthYear: function(){
			onChangeCalendar(this);
		}		
	});		
}

var global = {};

global.mapInit = function(){
	// $('#objectDetailMap').traMap();
	$('#objectDetailMap').traxDetailMap();
}

function lazyLoadMap() { 
	if(typeof $('body').attr('data-google-map-init') == 'undefined' ){
		var script = document.createElement("script"); 
		script.src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=global.mapInit"; 
		document.body.appendChild(script); 
		$('body').attr('data-google-map-init',true);  
	}  
} 

// lazy loading map
$(function() {
	$('#objectDetailMap').appear();
	$(document.body).on('appear', '#objectDetailMap', function(e, $affected) {           
		lazyLoadMap();
	});

	$('#placesImage').appear();
	$(document.body).on('appear','#placesImage' , function(e,$affected){

				var coordinates = $('#objectDetailMap').attr('value').split(',');

				var lat = parseFloat(coordinates[0]);
				var lng = parseFloat(coordinates[1]);

					var delta = 0.05;

					var MinLat = lat - delta;
					var MaxLat = lat + delta;

					var MinLng = lng - delta;
					var MaxLng = lng + delta;


					var findPanoramio = {
						set: 'public',
						from: 0,
						to: 16,
						minx: MinLng,
						miny: MinLat,
						maxy: MaxLat,
						maxx: MaxLng,
						size: 'medium',
						mapfilter: true
					};

				
		   //      $.ajax({
					//   dataType: "jsonp",
					//   crossDomain: true,
					//   data: findPanoramio,
					//   url: 'http://www.panoramio.com/map/get_panoramas.php',					  
					//   success: function(data){

					//   }
					// }).done(function(d){
						
					// 	var html = '';
					// 	$.each(d.photos,function(k,v){
					// 		console.log(v);
							
					// 		var myLatlng = new google.maps.LatLng(v.latitude,v.longitude);

		
							
					// 		html+= '<li style="background-image:url('+v.photo_file_url+');"></li>';

					// 	});

					// 	$('#placesImage').html(html);
					// });		
	})

});



$(function(){
	$('.rentalDetailMenu ul li a').tooltip({
		placement:'left'
	});
});




					//console.log(lat+' '+lng);

			  //       var delta = 0.05;

			  //       var MinLat = lat - delta;
			  //       var MaxLat = lat + delta;

			  //       var MinLng = lng - delta;
			  //       var MaxLng = lng + delta;


			  //       var findPanoramio = {
			  //       	set: 'public',
			  //       	from: 0,
			  //       	to: 12,
			  //       	minx: MinLng,
			  //       	miny: MinLat,
			  //       	maxy: MaxLat,
			  //       	maxx: MaxLng,
			  //       	size: 'medium',
			  //       	mapfilter: true
			  //       };
				
   //      $.ajax({
			//   dataType: "jsonp",
			//   crossDomain: true,
			//   data: findPanoramio,
			//   url: 'http://www.panoramio.com/map/get_panoramas.php',					  
			//   success: function(data){

			//   }
			// }).done(function(d){
				
			// 	var html = '';
			// 	$.each(d.photos,function(k,v){
			// 		console.log(v);
					
			// 		var myLatlng = new google.maps.LatLng(v.latitude,v.longitude);

			//         var marker = new google.maps.Marker({
			//             position: myLatlng,
			//             map: map
			//         });
					
			// 		html+= '<li style="background-image:url('+v.photo_file_url+');"></li>';

			// 	});

			// 	$('#placesImage').html(html);
			// });	