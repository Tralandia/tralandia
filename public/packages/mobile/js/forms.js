
Modernizr.load({
  test: Modernizr.inputtypes.date,
  nope: ['http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js', 'css/vendor/jqueryUi/jquery-ui-1.10.2.custom.min.css'],
  complete: function () {
      console.log('zedp');
        $('input[type=date]').datepicker({
            dateFormat:'yy-mm-dd',        
            beforeShow: function(input, inst)
            {
                $('#ui-datepicker-div').css({
                    marginTop: '-260px',
                    'z-index': '3000'
                });
            }
        });       
  }
});


