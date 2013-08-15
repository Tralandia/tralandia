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

			console.log(base.map);

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
						iconName  = base.options.iconSet.small.home;
						break;
				}
			}

			return base.options.iconPath + iconName;    		
		};
	
		//  @todo ajax callback
		base._renderOtherRentals = function(lat,lng){

			var html = '';

			$.each(base._returnRentalsTmp(lat,lng),function(k,v){

				html += '<li data-meta=\''+JSON.stringify(v)+'\'><img src="'+v.thumbnail+'"></li>';

				base._makeMarker(v.lat,v.lng,v.name);

			});

			$(base.options.otherRentaliListSelector).html(html);

			$(base.options.otherRentaliListSelector+' li').live('click',function(){
				console.log($(this).data('meta'));
			});

		};

		base._makeMarker = function(lat,lng,title){

			var markerOptions = {
					map: base.googleMap, 
					position: new google.maps.LatLng(lat, lng),
					title: title,
				};
			
			var nm = new google.maps.Marker(markerOptions);
			base.markers.push(nm);

			console.log(lat);
			console.log(lng);

		};

		base._returnRentalsTmp = function(lat,lng){

			var data = [];

			var delta = 10000;
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

			for(var i = 0 ; i < 9 ; ++i){

				dimensions.helpLat[1] = dimensions.helpLat[1] - Math.floor(Math.random() * (delta) + 1);
				dimensions.helpLng[1] = dimensions.helpLng[1] - Math.floor(Math.random() * (delta) + 1);

				var forPush = {
					name: 'tralala '+i,
					id: i,
					info1: 'Max '+Math.floor(Math.random() * (50 - 1 + 1) + 1)+' osob | '+Math.floor(Math.random() * (100 - 17 + 1) + 17)+' Eur osoba/noc',
					info2: 'Studňa, zváračka, cukrová repa, zelovoc, cédéčka',
					url: 'http://www.sk.tra.com/utulny-privat-kosar-v-tichom-prostredi-r21501',
					thumbnail: thubnails[i],
					lat: dimensions.helpLat[0]+'.'+dimensions.helpLat[1],
					lng: dimensions.helpLng[0]+'.'+dimensions.helpLng[1],

				};
				
				data.push(forPush);
			}

			// console.log(data);

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
				home: 'other-rental-small.png',
			}
		},
		otherRentaliListSelector: 'ul.relatedRentals',
	};
	
	$.fn.traxDetailMap = function(options){
		return this.each(function(){(new $.traxDetailMap(this, options));});};
	
})(jQuery);



// (function($) {
	
// 	$.fn.traMap = function() {

// 		// default map zoom level
// 		var zoomVal = 4;

// 		var rentalId = parseInt($(this).data('rentalId'));

// 		if(typeof $(this).attr('zoom') != 'undefined')
// 		{
// 			zoomVal = parseInt($(this).attr('zoom'));
// 		}

// 		if(typeof $(this).attr('value') == 'undefined')
// 		{
// 			$(this).html('error');
// 		} else {


// 			if(typeof $('body').attr('data-google-map-render') == 'undefined' ){

// 				var coordinates = $(this).attr('value').split(',');

// 				var lat = parseFloat(coordinates[0]);
// 				var lng = parseFloat(coordinates[1]);

// 				var iconBase = '../../../../images/markers/';

// 				var myLatlng = new google.maps.LatLng(lat,lng);
// 				var mapOptions = {
// 					zoom: zoomVal,
// 					scrollwheel: false,
// 					center: myLatlng,
// 					mapTypeId: google.maps.MapTypeId.HYBRID
// 				}
// 				var map = new google.maps.Map(document.getElementById($(this).attr('id')), mapOptions);

// 				var isFavorites = false;
// 				var myFavorites = $.cookie('favoritesList');

// 				if(tndefinypeof myFavorites != 'ued' && myFavorites != null)
// 				{
// 					myFavorites = myFavorites.split(',');

// 					$.each(myFavorites,function(k,v){
// 						if(rentalId == v){
// 							isFavorites = true;
// 						}
// 					});					
// 				}


// 				if(isFavorites){
// 					var iconName  = 'map-pointer-heart.png';
// 				} else {
// 					var iconName  = 'map-pointer-home.png';
// 				}


// 				var marker = new google.maps.Marker({
// 					position: myLatlng,
// 					map: map,
// 					icon: iconBase + iconName
// 				});




// 			$('body').attr('data-google-map-render',true);


// }




// }

// };
// })(jQuery);








// (function($){
//     $.traMap = function(el, options){

//         var base = this;
		
//         base.$el = $(el);
//         base.el = el;
		
//         base.$el.data("traMap", base);
		
//         base.init = function(){
			
			
//             base.options = $.extend({},$.traMap.defaultOptions, options);
			
//         };
		

//         base.init();
//     };
	
//     $.traMap.defaultOptions = {
//     	zoom: 4
//     };
	
//     $.fn.traMap = function(options){return this.each(function(){(new $.traMap(this, options));});};
// })(jQuery);




















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

	//console.log(lang);

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