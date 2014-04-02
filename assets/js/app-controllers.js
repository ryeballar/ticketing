angular.module('app').controller('PageHeaderController', ['$scope', function($scope) {
	$scope.nav = [
		{icon: 'icon-plane', label: 'Home', url: '#'},
		{icon: 'icon-user', label: 'Passenger', 
			dropdown: [
				{label: 'Booking', url: 'booking'},
				{label: 'Airline Companies', url: 'airline-companies'}
			]
		},
		{icon: 'icon-user', label: 'Login', url: 'login'},
		{icon: 'icon-user', label: 'Sign Up', url: 'signup'},
	];

	$scope.isCollapse = false;

	$scope.toggle = function() {
		$scope.isCollapse = !$scope.isCollapse;
	};
	$scope.collapse = function() {
		$scope.isCollapse = false;
	};
}]).

controller('LandingController', ['$scope', '$rootScope', function($scope, $rootScope) {
	$scope.interval = 5000;

	$scope.slides = [
		{template: 'partials/landing-carousel/item1.html'},
		{template: 'partials/landing-carousel/item2.html'},
		{template: 'partials/landing-carousel/item3.html'},
	];

	$scope.charts = [
		{icon: 'icon-desktop', title: 'Easy Booking', label: 'Enjoy our easy way of airline booking.'},
		{icon: 'icon-plane', title: 'Safe', label: 'Security? Fly safe with us.'},
		{icon: 'icon-globe', title: 'Around The World', label: 'Tour around the world.'},
		{icon: 'icon-credit-card', title: 'Online Payment', label: 'Pay it Safe! with our newly security card encryptor!'}
	];

	setTimeout(landingAnimate);
}]).

controller('SignupController', ['$scope', '$http', '$location', function($scope, $http, $location) {
	$scope.data = {
		member: {
			member_name: '',
			member_email: '',
			password: ''
		},
		confirm_password: ''
	};
}]).

controller('LoginController', ['$scope', function($scope) {
	$scope.data = {
		member: {
			member_name: '',
			member_email: '',
			password: ''
		},
		confirm_password: ''
	};
}]).

controller('BookingController', 
	['$scope', '$http', '$filter', 
	'$timeout', '$cookieStore', '$location', 
	'$route', 'GetAll', 
	function($scope, $http, $filter, 
			$timeout, $cookieStore, $location, 
			$route, GetAll) {
	
	loadData();
	
	$scope.continue = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$cookieStore.put('booking.steps_active', ++$scope.steps_active);
		$cookieStore.put('booking.data', $scope.data);
		$cookieStore.put('booking.latest_step', $scope.steps_active);
		$scope.latest_step = $scope.steps_active;
		populateScheduleStep();
	};

	$scope.cancel = function() {
		removeData();
		$route.reload();
	};

	function populateScheduleStep() {
		GetAll('flight_schedule', {
			date: $scope.data.dept_date,
			ac_id: $scope.data.ac
		}).then(function(data) {
			$scope.flight_schedules = data;
			$scope.models = [];
			for(var e in $scope.flight_schedules)
				$scope.models[e] = $scope.flight_schedules[e].accomodations[0];
		});
	}

	$scope.selectAgency = function() {
		var record = $scope.data.agency;
		if(record) {
			GetAll('airline_company/search_by_agency', {
				agency_id: record.agency_id
			}).then(function(data) {
				$scope.airline_companies = data;
			});
		}
	};

	$scope.changeGuests = function() {
		var limit = $scope.data.guest_limit;

		while($scope.data.guests.length > limit)
			$scope.data.guests.pop();

		while($scope.data.guests.length < limit) {
			$scope.data.guests.push({
				ps_name: '',
				ps_address: '',
				p_stel: '',
				ps_fax: '',
				ps_email: '',
				ps_bdate: $filter("date")(Date.now(), 'yyyy-MM-dd'),
				ps_contact_person: '',
				ps_contact_tel: ''
			});
		}

	};

	$scope.goTo = function(index) {
		$cookieStore.put('booking.steps_active', index);
		loadData();
	};

	$scope.select = function(sched, accom) {
		$scope.sched = sched;
		$scope.accom = accom;
		$cookieStore.put('booking.steps_active', ++$scope.steps_active);
		$cookieStore.put('booking.sched', sched);
		$cookieStore.put('booking.accom', accom);
		$cookieStore.put('booking.latest_step', $scope.steps_active);
		$scope.latest_step = $scope.steps_active;
	};

	$scope.payLater = function() {
		$http({
			url: 'ci/booking/save',
			method: 'GET',
			params: {
				data: $scope.data,
				accom: $scope.accom,
				sched: $scope.sched
			}
		}).success(function(data) {
			console.log(data);
			$location.path('/itinerary/' + data['from'] + '/' + data['to']);
		});
	};

	$timeout(function() {
		$scope.data.guest_limit = 1;
		$scope.changeGuests();
	}, 500);

	$scope.steps = [
		{label: 'Booking', templateUrl: 'templates/booking/step1.html'},
		{label: 'Schedules', templateUrl: 'templates/booking/step2.html'},
		{label: 'Ticket Summary', templateUrl: 'templates/booking/step3.html'},
		{label: 'Payments', templateUrl: 'templates/booking/step4.html'}
	];

	function loadData() {
		$scope.data = $cookieStore.get('booking.data') || {
			agency: '',
			ac: '',
			dept_date: $filter("date")(Date.now(), 'yyyy-MM-dd'),
			guest_limit: '',
			guests: []
		};

		$scope.sched = $cookieStore.get('booking.sched') || null;
		$scope.accom = $cookieStore.get('booking.accom') || null;
		$scope.steps_active = $cookieStore.get('booking.steps_active') || 0;
		$scope.latest_step = $cookieStore.get('booking.latest_step') || 0;

		switch($scope.steps_active) {
			case 0:
				GetAll('agency').then(function(data) {
					$scope.agencies = data;
				});

				GetAll('airline_company').then(function(data) {
					$scope.airline_companies = data;
				});

			break;
			case 1: populateScheduleStep(); break;
		}
	}	

	function removeData() {
		$cookieStore.remove('booking.sched');
		$cookieStore.remove('booking.accom');
		$cookieStore.remove('booking.steps_active');
		$cookieStore.remove('booking.latest_step');
		$cookieStore.remove('booking.data');
	}

}]).

controller('ItineraryController', ['$scope', '$http', '$routeParams', 'GetAll', function($scope, $http, $routeParams, GetAll) {
	$http.post('ci/booking/search_range', {
		from: $routeParams.bk_id_from,
		to: $routeParams.bk_id_to,
	}).success(function(data) {
		$scope.booking = data;
		console.log(data);
	});

}]);