$(document).ready(function(){


    $(document).scroll(function(){



    	var offset = parseInt($(this).scrollTop());
    	if(offset > 155){
    		$('#favoritesStaticContainer').css({
    			position: 'fixed',
    			top:'0px'
    		});

            favoritesPlaceholder('show');

    	} else {
    		$('#favoritesStaticContainer').css({
    			position: 'relative'
    		});    	
            
            favoritesPlaceholder('hide');
    	}   	
    });
    
});

function favoritesPlaceholder(action){

    if($('#favoritesStaticContainerPlaceholder').hasClass('inactive')){

    } else {
        switch(action){
            case 'show':
                $('#favoritesStaticContainerPlaceholder').removeClass('hide');
            break;
            case 'hide':
                $('#favoritesStaticContainerPlaceholder').addClass('hide'); 
            break;            
        }
    }

}