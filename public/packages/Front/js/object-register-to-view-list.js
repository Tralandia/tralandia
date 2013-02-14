/* afret load Rental detail add object id to visit list array */
(function($) {
	
  $.fn.objectVisitList = function(appObject) {  	

  	if(this.length > 0){

	  	var visitList = new Array();
	  	
	  	var objectList = $.cookie('visitObjectList');
	  		if(typeof objectList != 'undefined'){
	  			objectList = objectList.split(',');
	  		} else {
	  			objectList = false;
	  		}

	  	var currentId = parseInt($(this).attr('id'));  	

	  		if(!objectList){
	  				$.cookie('visitObjectList' , currentId);
	  		} else {

	  			// chech if index not exist 

	  				if(appObject.in_array(objectList,currentId) == false){
			  			visitList = objectList;
			  			visitList.push(currentId); 
			  			$.cookie('visitObjectList' , visitList);					
	  				}

	  		}

  	}
	
  };
})(jQuery);