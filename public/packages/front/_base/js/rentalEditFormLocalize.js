

$(function(){

    $('.selectLanguageBasicInformation').on('change',function(){
        languageSelect(this,$(this).val());
    });

    $('a.tabPane').click(function(){
        languageSelect(this,$(this).data('iso'));
    });

    $('.selectLanguageInterview').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('tr.toggleLanguage.interview:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('tr.toggleLanguage.interview.'+iso).removeClass('hide');
    }); 


    $('.simpleInput.toggleLanguage').each(function(){
        var input = $(this).find('input[type=text]');

        if(input.val().length > 0){
            var id = input.attr('id');
            var iso = id.split('-').pop();
            $('.langTabs .header a.tabPane.'+iso).removeClass('hide');
        }
    });

});


function languageSelect(el,iso){

    if(iso != 0){

        $(el).parents('form').find('tr.toggleLanguage.selectLanguageBasicInformation:not(.'+iso+')').addClass('hide');
        $(el).parents('form').find('tr.toggleLanguage.selectLanguageBasicInformation.'+iso).removeClass('hide');


        $(el).parents('form').find('a.tabPane').removeClass('current');
        $(el).parents('form').find('a.tabPane.'+iso).removeClass('hide').addClass('current');

        $(el).select2('val',0);
    }
   
}
