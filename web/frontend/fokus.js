$(function() {
	console.log('fokus.js rdy');
	
	var vm = {
		active		: ko.observable('?'),
		endAt		: ko.observable('?'),
		price		: ko.observable('?'),
		username	: ko.observable('?'),
		bidfair		: ko.observable('loading...'),
		lag			: ko.observableArray([]),
	};
	
	// update auction
	vm.update = function() {
		var timeStart = new Date().getTime();
		$.ajax({
			url			: '/fokus/run',
			type		: 'get',
			dataType	: 'json',
			beforeSend	: function() {
				//$('#status').text('scraping...');
			},
			error		: function() {
				$('#status').text('error occurred with run');
			},
			success		: function(data) {
				//console.log(data);
				vm.active(data.active);
				vm.endAt(data.endAt);
				vm.price(data.price);
				vm.username(data.username);
				vm.update();
			},
			complete	: function() {
				var timeEnd = new Date().getTime();
				var duration = timeEnd - timeStart;
				vm.lag.push(duration);
			}
		});
	}
	
	// update sparkline
	vm.lag.subscribe(function(newValue) {
		if (vm.lag().length > 30) {
			vm.lag.shift();
		}
		$('#sparkline').sparkline(vm.lag(), {type: 'bar', barColor: 'green', colorMap: range_map, height: '2em'});
	});
	
	// get updated bidfair status
	// update every 5min
	vm.status = function() {
		$.getJSON('/bidfair', function(data) {
			vm.bidfair('Logged in for ' + data);
			setTimeout(function() {
				vm.status();
			}, (1000 * 60 * 5));
		});
	}
	
	// configs
	var range_map = $.range_map({
	    ':499'		: 'green',
	    '500:999'	: 'yellow',
	    '999:'		: 'red'
	});

	// start
	ko.applyBindings(vm);
	vm.update();
});