$(function(){
	$.nette.init();
		
	$.nette.ext('mobile', {
			before: function () {
				$('.reservationform').find('button').addClass('active');
			},
			complete: function(){
				$('.datepicker,.datepickerto').mobileDatepicker();
			}	});  

	$('.datepicker,.datepickerto').mobileDatepicker();


// Modernizr.load({
//   test: Modernizr.inputtypes.date,
//   nope: ['http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js', 'css/vendor/jqueryUi/jquery-ui-1.10.2.custom.min.css'],
//   complete: function () {
//       console.log('zedp');
//         $('input[type=date]').datepicker({
//             dateFormat:'yy-mm-dd',        
//             beforeShow: function(input, inst)
//             {
//                 $('#ui-datepicker-div').css({
//                     marginTop: '-260px',
//                     'z-index': '3000'
//                 });
//             }
//         });       
//   }
// });

});


(function($){
		$.mobileDatepicker = function(el, options){

				var base = this;
				
				base.$el = $(el);
				base.el = el;

				base.$el.data("mobileDatepicker", base);
				
				base.datepickerUiContainer = '#ui-datepicker-div';

				base.init = function()
				{

					if((!base.checkInput('date'))){
						base._datepickerUi();
					} else {
						base._replaceTextInput();
					}
					
				};
				
				base._datepickerUi = function()
				{
					base.$el.datepicker({
							dateFormat:'yy-mm-dd',        
							beforeShow: function(input, inst)
							{									
									$(base.datepickerUiContainer).css({
											marginTop: '-260px',
									});
							}
					});
				};

				base._replaceTextInput = function()
				{
					var pattern = base.$el.clone();
							pattern.removeAttr('type')
										 .attr('type','date');

							pattern.insertBefore(base.el);
							base.$el.remove();
				};

				base.checkInput = function(type)
				{
					var input = document.createElement("input");
					input.setAttribute("type", type);
					return input.type == type;
				};    

				base.init();
		};

		$.fn.mobileDatepicker = function(options){
				return this.each(function(){
						(new $.mobileDatepicker(this, options));});
		};
		
})(jQuery);





