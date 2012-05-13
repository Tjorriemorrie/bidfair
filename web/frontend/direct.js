$(function() {
	console.log('direct.js rdy');
	var auctionId = $('#auctionId').text();
	var haveMadeTheBid = true;
	console.log('id = ' + auctionId);
	
	var vm = {
		auction		: ko.observableArray(),
		lastBid		: ko.observableArray(),
		product		: ko.observableArray(),
		makeBids	: ko.observable(false),
		bidsCount	: ko.observable(0),
		bidTime		: ko.observable(''),
		bidfair		: ko.observable('loading...'),
		timeout		: ko.observable(500),
		lag			: ko.observableArray([]),
	};
	
	vm.toggleBidding = function() {
		console.log('Bidding toggled');
		vm.makeBids(!vm.makeBids());
	}
	
	// update auction
	vm.update = function() {
		var timeStart = new Date().getTime();
		$.ajax({
			url			: '/direct/fetch',
			type		: 'get',
			dataType	: 'json',
			timeout		: vm.timeout(),
			beforeSend	: function() {
				
			},
			error		: function() {
				vm.timeout(vm.timeout() + 10);
			},
			success		: function(data) {
				//console.log(data);
				vm.auction(ko.mapping.fromJS(data.Auction));
				vm.lastBid(ko.mapping.fromJS(data.LastBid));
				vm.product(ko.mapping.fromJS(data.Product));
				vm.timeout(vm.timeout() - 1);

				if (vm.auction().closed() == 1) {
					vm.makeBids(false);
					vm.timeout(vm.timeout() + 100);
				}
				
			},
			complete	: function() {
				var timeEnd = new Date().getTime();
				var duration = timeEnd - timeStart;
				vm.lag.push(duration);
				
				if (vm.makeBids() && vm.auction().time_left() < 3 && haveMadeTheBid) {
					haveMadeTheBid = false;
					vm.makeBid();
				} else {
					setTimeout(function() {
						vm.update();
					}, 50);
				}
			}
		});
	}
	
	// making bid
	vm.makeBid = function() {
		var timeStart = new Date().getTime();
		$.ajax({
			url			: '/direct/bid',
			type		: 'get',
			dataType	: 'json',
			beforeSend	: function() {
				$('#status').text('making a bid');
			},
			error		: function() {
				$('#status').text('error occurred with bidding');
			},
			success		: function(data) {
				vm.bidsCount(vm.bidsCount() + 1);
				$('#status').text(data);
				setTimeout(function() {
					$('#status').fadeOut(3000);
					haveMadeTheBid = true;
				}, 2000);
				vm.update();
			},
			complete	: function() {
				var now = new Date();
				var timeEnd = now.getTime();
				var duration = timeEnd - timeStart;
				vm.lag.push(duration);
				vm.bidTime(now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds());
			}
		});
	}
	
	// update sparkline
	vm.lag.subscribe(function(newValue) {
		if (vm.lag().length > 100) {
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
	vm.status();
	vm.update();
});