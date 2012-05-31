(function($) {

    $.fn.liveWysi = function(options) {

        $.fn.liveWysi.options = {
            template: 
                '<div class="span6 live-wysi">' +
                    '<div class="output">' +
                        '<div></div>' +
                        '<span class="loading-frame hide"></span>' +
                    '</div>' +
                    '<button class="btn btn-mini">Preview</button>' +
                '</div>',
            outputUrl: '/admin/ap/live-wysi/'
        };

        return this.each(function() {

            var $base = $(this);
            var $template = $($.fn.liveWysi.options.template);

            $template.find('.output')
                .height($base.height())
                .width(($base.width() - 20));
            $base.after($template);

            // bind Refresh button
            $template.find('button').bind('click', function(e) {

                $.fn.liveWysi.getOutput($base, $template);

                e.preventDefault();

            });

        });

    }

    $.fn.liveWysi.getOutput = function($base, $template) {

        var editor = tinymce.get($base.attr('id'));

        $.ajax({
            type: 'POST',
            url: $.fn.liveWysi.options.outputUrl,
            data: {content: editor.getContent()},
            dataType: 'json',
            beforeSend: function() {
                $template
                    .find('.output span')
                    .removeClass('hide');
            },
            success: function(data) {
                $template
                    .find('.output div')
                    .html(data.content);
                $template
                    .find('.output span')
                    .addClass('hide');
            }
        });

    }

})(jQuery);