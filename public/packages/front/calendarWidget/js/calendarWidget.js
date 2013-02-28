(function($){
    $.calendarWidgetControl = function(el, options){

        var base = this;

        base.$el = $(el);
        base.el = el;

        base.$el.data("calendarWidgetControl", base);
        
        base.init = function(){
            if( typeof( radius ) === "undefined" || radius === null ) radius = "20px";
            
            base.radius = radius;
            
            base.options = $.extend({},$.calendarWidgetControl.defaultOptions, options);

        };
        
        base.init();
    };
    
    $.calendarWidgetControl.defaultOptions = {
        radius: "20px"
    };
    
    $.fn.calendarWidgetControl = function(radius, options){
        return this.each(function(){
            (new $.calendarWidgetControl(this, radius, options));

            var self = this;
            var $self = $(this);
            
            var $viewer = $('#calendarIframeViewer');

            

            var rightIframeMargin = 10;
            var marginTopBody = 10;

            var $outputUser = $('#htmlOutputFromViewer');



            $self.find('select').on('change',function(){

                var calendarWidth = 136;
                var calendarHeight = 150;

                var data = {
                    rows: $self.find('[name=rows]').val(),
                    cells: $self.find('[name=cells]').val(),
                    count: 0,
                    iso: $self.find('[name=iso]').val()
                };

                

                //console.log(data);

                if(data.cells > 0 & data.rows > 0 & data.iso != 0){
                    data.count = data.cells * data.rows;
                    
                    calendarWidth = (calendarWidth*data.cells)+rightIframeMargin;
                    calendarHeight = (calendarHeight*(data.count/data.cells))+marginTopBody;

                    $viewer.css({
                        width: calendarWidth,
                        height:calendarHeight
                    }).attr({
                        src: rawUrl
                    });

                    var rawUrl = 'http://www.'+data.iso+'.tra.com/front/calendar-iframe?rentalId=1&monthsCount='+data.count;

                    var iframeForIser = '<iframe scrolling="no" style="width:'+calendarWidth+'px ;height:'+calendarHeight+'px; border:none; margin:0 auto;" src="'+rawUrl+'"></iframe>';
                        $outputUser.val(iframeForIser);                    
                }


                    
            });

        });
    };
    
})(jQuery);


$(document).ready(function(){
     $('#calendarWidgetControl').calendarWidgetControl();

});