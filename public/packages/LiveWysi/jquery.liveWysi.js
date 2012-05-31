(function($) {

    $.fn.liveWysi = function(options) {
        
        $.fn.liveWysi.options = {

        };

        return this.each(function() {

            var base = $(this);

            var template = $('<div class="span6 live-wysi">' +
                    '<div class="output">' +
                        '<div></div>' +
                        '<span class="hide"></span>' +
                    '</div>' +
                    '<button class="btn btn-mini">Preview</button>' +
                '</div>');

            template
                .find('.output')
                .height(base.height())
                .width((base.width() - 20))

            base.after(template);

            // Refresh button
            template.find('button').bind('click', function(e) {

                var editor = tinymce.get(base.attr('id'));

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/admin/ap/live-wysi/',
                    data: {content: editor.getContent()},
                    dataType: 'json',
                    beforeSend: function() {
                        template
                            .find('.output span')
                            .removeClass('hide');
                    },
                    success: function(data) {
                        template
                            .find('.output div')
                            .html(data.content);
                        template
                            .find('.output span')
                            .addClass('hide')
                    }
                });

                e.preventDefault();

            });


        });

    }

})(jQuery);