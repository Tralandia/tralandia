
$(function(){

    $('a.tabPane').click(function(){
        languageSelect(this,$(this).data('iso'));
    });

    $('.selectLanguageInterview').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('.toggleLanguage.interview:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('.toggleLanguage.interview.'+iso).removeClass('hide');
    }); 

});

function languageSelect(el,iso){

    if(iso != 0){

        $(el).parents('form').find('.toggleLanguage:not(.'+iso+')').addClass('hide');
        $(el).parents('form').find('.toggleLanguage.'+iso).removeClass('hide');

        $(el).parents('form').find('a.tabPane').removeClass('current');
        $(el).parents('form').find('a.tabPane.'+iso).removeClass('hide').addClass('current');

        $(el).select2('val',0);
    }
   
}
