/* afret load Rental detail add object id to visit list array ( using local strorage ) */
(function($) {
	
  $.fn.objectVisitList = function(appObject) {  	

  	if(this.length > 0){

	  	var visitList = new Array();
	  	var objectList = appObject.storageGet('visitObjectList');

	  	var currentId = parseInt($(this).attr('id'));  	

	  		if(typeof objectList == 'undefined' || objectList == null){
	  			
	  			// create new object list
	  			  
	  				visitList[0] = currentId;

	  				appObject.storageSet('visitObjectList' , visitList);

	  		} else {

	  			// chech if index not exist 

	  				if(appObject.in_array(objectList,currentId) == false){
			  			visitList = objectList;
			  			visitList.push(currentId); 
			  			appObject.storageSet('visitObjectList' , visitList);					
	  				}

	  		}

	  		// potreba doplnit each na elementy v top slider

	  		//console.log(objectList);

  	}
	
  };
})(jQuery);