	//Evil Robot Detection

	var category = 'trafficQuality';
	var dimension = 'botDetection';
	var human_events = ['onkeydown','onmousemove'];

	if ( navigator.appName == 'Microsoft Internet Explorer' && !document.referrer) {
		for(var i = 0; i < human_events.length; i++){
			document.attachEvent(human_events[i], ourEventPushOnce);
		}
	}else{
		_gaq.push( [ '_trackEvent', category, dimension, 'botExcluded', 1, true ] );
	}

	function ourEventPushOnce(ev) {

		_gaq.push( [ '_trackEvent', category, dimension, 'on' + ev.type, 1, true ] );

		for(var i = 0; i < human_events.length; i++){
			document.detachEvent(human_events[i], ourEventPushOnce);
		}

	} // end ourEventPushOnce()

	//End Evil Robot Detection