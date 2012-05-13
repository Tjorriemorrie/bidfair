$(function() {
	console.log('auctions.js rdy');
	
	var vm = {
		auctions	: ko.observableArray([]),
		lag			: ko.observableArray([]),
		bidfair		: ko.observable('loading...'),
		delay		: ko.observable(5000),
	};
	
	// update auctions (for now reload everything)
	vm.update = function() {
		var timeStart = new Date().getTime();
		$.ajax({
			url			: '/run',
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
				vm.auctions(data);
				if (data.length < 3) {
					vm.delay(100);
				} else if (vm.delay() != 5000) {
					vm.delay(5000);
				}
				setTimeout(function() {
					vm.update();
				}, vm.delay());
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
	
	// clean up database of stuck open auctions
	vm.cleanup = function() {
		$.getJSON('/cleanup', function(data) {
			vm.bidfair(data);
			vm.update();
			setTimeout(function() {
				vm.status();
			}, 10000);
		});
	}
	
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
	vm.cleanup();
});