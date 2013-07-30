(function($) {
	
	$.fn.traMap = function() {

		// default map zoom level
		var zoomVal = 4;

		var rentalId = parseInt($(this).data('rentalId'));

		if(typeof $(this).attr('zoom') != 'undefined')
		{
			zoomVal = parseInt($(this).attr('zoom'));
		}

		if(typeof $(this).attr('value') == 'undefined')
		{
			$(this).html('error');
		} else {


			if(typeof $('body').attr('data-google-map-render') == 'undefined' ){

				var coordinates = $(this).attr('value').split(',');

				var lat = parseFloat(coordinates[0]);
				var lng = parseFloat(coordinates[1]);

				var iconBase = '../../../../images/markers/';

				var myLatlng = new google.maps.LatLng(lat,lng);
				var mapOptions = {
					zoom: zoomVal,
					scrollwheel: false,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.HYBRID
				}
				var map = new google.maps.Map(document.getElementById($(this).attr('id')), mapOptions);

				var isFavorites = false;
				var myFavorites = $.cookie('favoritesList');

				


				if(typeof myFavorites != 'undefined' && myFavorites != null)
				{
					myFavorites = myFavorites.split(',');

					$.each(myFavorites,function(k,v){
						if(rentalId == v){
							isFavorites = true;
						}
					});					
				}


				if(isFavorites){
					var iconName  = 'map-pointer-heart.png';
				} else {
					var iconName  = 'map-pointer-home.png';
				}


				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					icon: iconBase + iconName
				});




			$('body').attr('data-google-map-render',true);


}




}

};
})(jQuery);








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
		dateFormat: "yy-mm-dd",
		beforeShow: function(textbox, instance){
            instance.dpDiv.css({
                    marginLeft: '0px'
            });			
		}
	});	


	$( ".datepickerto" ).datepicker({ 
		minDate: new Date(2013, 1, 28), 
		maxDate: "+12M +10D" ,
		dateFormat: "yy-mm-dd" ,
		beforeShow: function(textbox, instance){

			console.log(textbox.offsetHeight);

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



		}
	});		
}

var global = {};

global.mapInit = function(){
	$('#objectDetailMap').traMap();
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