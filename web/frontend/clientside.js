function checkTime() {
	var delay = 500;

	var time = $('.timer').text().split(':');  
	
	if (time[0] > 0) {
		console.log('hours remaining');
		
	} else if (time[1] > 0) {
		console.log('minutes remaning');
		
	} else {
		console.log(time[2] + ' seconds remaining');
	
		if (time[2] > 0 && time[2] < 3) {
			console.log('making a bid');
			delay = 900;
			
//		    var a = $('.bid-button > a')[0];
//		    var e = document.createEvent('MouseEvents');
//		    e.initEvent( 'click', true, true );
//		    a.dispatchEvent(e);
			$('.bid-button > a').trigger('click');
		    
		}
	}

	setTimeout(function() {
		checkTime();
	}, delay);
}


checkTime();