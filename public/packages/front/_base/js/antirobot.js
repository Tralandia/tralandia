
	var category = 'trafficQuality';
	var dimension = 'botDetection';
	var human_events = ['onkeydown','onmousemove'];


	console.log(navigator.appName);
	console.log(document.referrer);


	if ( 1) {
		for(var i = 0; i < human_events.length; i++){
			$(document).bind(human_events[i], ourEventPushOnce);
		}
	}else{
		// _gaq.push( [ '_trackEvent', category, dimension, 'botExcluded', 1, true ] );
	}

	function ourEventPushOnce(ev) {

		console.log('ourEventPushOnce');

		// _gaq.push( [ '_trackEvent', category, dimension, 'on' + ev.type, 1, true ] );

		for(var i = 0; i < human_events.length; i++){
			$(document).bind(human_events[i], ourEventPushOnce);
		}

	} 