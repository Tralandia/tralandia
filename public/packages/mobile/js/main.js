$(function(){
    
    
    $('.addToFavorites').on('click',function(){
       $(this).toggleClass('active'); 
    });
    
    
    
    $('.changeCountry').on('click',function(){
        $('#country').focus();
        return false;
    });
    
//    $(document).on('scroll',function(){
//        
//        if($(this).scrollTop() > 56){			
//                $('#subnav').addClass('fix');
//                $('#subnavPlaceholder').removeClass('hide'); 
//        } else {
//                $('#subnav').removeClass('fix');
//                $('#subnavPlaceholder').addClass('hide'); 
//        }
//       
//    });
    
});



//function initialize() {
//
//        
//        var input = document.getElementById('searchTextField');
//        //var options = {
//        //  types: ['(cities)'],
//        //  componentRestrictions: {country: 'fr'}
//        //};
//
//        autocomplete = new google.maps.places.Autocomplete(input);
//
//        google.maps.event.addListener(autocomplete, 'place_changed', function() {
//            var place = autocomplete.getPlace();
//            console.log(place);
//        });
//        
//        
//        
//
//      }
//      
//google.maps.event.addDomListener(window, 'load', initialize);
          
      

        
        
        
        
        
        
        
        
function loadImages(lat,lng , map){

//
//var delta = 0.05;
//
//var MinLat = lat - delta;
//var MaxLat = lat + delta;
//
//var MinLng = lng - delta;
//var MaxLng = lng + delta;
//
//
//var findPanoramio = {
//        set: 'public',
//        from: 0,
//        to: 12,
//        minx: MinLng,
//        miny: MinLat,
//        maxy: MaxLat,
//        maxx: MaxLng,
//        size: 'medium',
//        mapfilter: true
//};        
//        
//        
//$.ajax({
//    dataType: "jsonp",
//    crossDomain: true,
//    data: findPanoramio,
//    url: 'http://www.panoramio.com/map/get_panoramas.php',					  
//    success: function(data){
//
//    }
//  }).done(function(d){
//
//          var html = '';
//          $.each(d.photos,function(k,v){
//         
//
//          var myLatlng = new google.maps.LatLng(v.latitude,v.longitude);
//
//          var marker = new google.maps.Marker({
//              position: myLatlng,
//              map: map
//          });
//
//                  html+= '<li style="background-image:url('+v.photo_file_url+');"></li>';
//
//          });
//
//          $('#placesImg').html(html);
//  });    
}        
