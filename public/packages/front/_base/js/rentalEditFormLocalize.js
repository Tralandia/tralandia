

$(function(){
    $('.selectLanguage').on('change',function(){
        var iso = $(this).val();
        $(this).parents('form').find('tr.interview:not(.'+iso+'),tr.name:not(.'+iso+'),tr.teaser:not(.'+iso+')').addClass('hide');
        $(this).parents('form').find('tr.interview.'+iso+',tr.name.'+iso+',tr.teaser.'+iso+'').removeClass('hide');
    });
});

