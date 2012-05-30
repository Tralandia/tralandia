(function($) {

    $.fn.phraseControl = function(options) {
        
        $.fn.phraseControl.options = {
            classSimple: 'plain-text-simple',
            classComplex: 'plain-text-complex',
            classHtmlText: 'html-text'
        };

        return this.each(function() {
            
            var base = $(this);
            
            if (base.hasClass($.fn.phraseControl.options.classSimple)) {
                // Plain Text Simple

                base.find('.dropdown-menu li a').bind('click', function(e) {

                    lang = $(this).attr('lang');
                    options = base.find('input[lang*=]');

                    options
                        .addClass('hide');
                    base
                        .find('input[lang="'+ lang +'"]')
                        .removeClass('hide');

                    e.preventDefault();
                });

                base.find('input[lang*=]').bind('blur', function() {

                    lang = $(this).attr('lang');
                    value = $(this).val();
                    base
                        .find('.dropdown-menu li a[lang="'+ lang +'"] span')
                        .html(value);

                });

                return true;
                    
            } else if (base.hasClass($.fn.phraseControl.options.classComplex)) {
                // Plain Text Complex
                return true;

            } else if (base.hasClass($.fn.phraseControl.options.classHtmlText)) {
                // HTML text
                return true;

            }

        });

    }

})(jQuery);
