

$(function(){
    $('.selectLanguage').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('tr.toggleLanguage:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('tr.toggleLanguage.'+iso).removeClass('hide');
    });
});

