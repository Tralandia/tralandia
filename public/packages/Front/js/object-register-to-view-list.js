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