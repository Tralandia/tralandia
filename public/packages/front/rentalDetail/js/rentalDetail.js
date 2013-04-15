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


  			if(typeof $('body').attr('data-googleMapRender') == 'undefined' ){

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

					// 	$('#placesImg').html(html);
					// });	


			        $('body').attr('data-googleMapRender',true);


}




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
				initAllSocialPlugins();
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


$(document).ready(function(){


	$('.pinterestShare').click(function(){

		if(!$(this).hasClass('opened')){
			$(this).find('.content img').attr('src','//assets.pinterest.com/images/pidgets/pin_it_button.png');

			var attr = $('body').attr('data-pinterestInitJs');

removejscssfile('http://assets.pinterest.com/js/pinit.js','js');
addScript('http://assets.pinterest.com/js/pinit.js');


			var html = $(this).find('.content').html();
				$(this).html(html);
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

	$( ".datepicker" ).datepicker({ minDate: 0, maxDate: "+12M +10D" , dateFormat: "yy-mm-dd" });	


	$( ".datepickerto" ).datepicker({ 
		minDate: new Date(2013, 1, 28), 
		maxDate: "+12M +10D" ,
		dateFormat: "yy-mm-dd" ,
		beforeShow: function(){

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
	if(typeof $('body').attr('data-googleMapinit') == 'undefined' ){
		var script = document.createElement("script"); 
		script.src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=global.mapInit"; 
		document.body.appendChild(script); 
		$('body').attr('data-googleMapinit',true);  
	}  
} 

// lazy loading map
$(function() {
	$('#objectDetailMap').appear();
	$(document.body).on('appear', '#objectDetailMap', function(e, $affected) {           
		lazyLoadMap();
	});

	$('#placesImg').appear();
	$(document.body).on('appear','#placesImg' , function(e,$affected){

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

					// 	$('#placesImg').html(html);
					// });		
	})

});



