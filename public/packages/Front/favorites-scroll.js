$(document).ready(function(){

    $(document).scroll(function(){

    	var offset = parseInt($(this).scrollTop());
    	if(offset > 155){
    		$('#favoritesStatisContainer').css({
    			position: 'fixed',
    			top:'-10px'
    		});

            favoritesPlaceholder('show');

    	} else {
    		$('#favoritesStatisContainer').css({
    			position: 'static'
    		});    	
            
            favoritesPlaceholder('hide');
    	}   	
    });
    
});

function favoritesPlaceholder(action){

    if($('#favoritesStatisContainerPlaceholder').hasClass('inactive')){

    } else {
        switch(action){
            case 'show':
                $('#favoritesStatisContainerPlaceholder').removeClass('hide');
            break;
            case 'hide':
                $('#favoritesStatisContainerPlaceholder').addClass('hide'); 
            break;            
        }
    }

}