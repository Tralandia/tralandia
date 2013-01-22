$(document).ready(function(){

    $(document).scroll(function(){

    	var offset = parseInt($(this).scrollTop());
    	if(offset > 185){
    		$('#favoritesStatisContainer').css({
    			position: 'fixed',
    			top:'-10px'
    		});
    	} else {
    		$('#favoritesStatisContainer').css({
    			position: 'static'
    		});    		
    	}   	
    });
    
});