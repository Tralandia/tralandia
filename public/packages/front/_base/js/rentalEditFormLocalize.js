

$(function(){

    $('.selectLanguageBasicInformation').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('tr.toggleLanguage.selectLanguageBasicInformation:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('tr.toggleLanguage.selectLanguageBasicInformation.'+iso).removeClass('hide');
    });

    $('.selectLanguageInterview').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('tr.toggleLanguage.interview:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('tr.toggleLanguage.interview.'+iso).removeClass('hide');
    });   

});

