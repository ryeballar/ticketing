angular.module('app').controller('PageHeaderController', ['$scope', function($scope) {
	$scope.nav = [
		{icon: 'icon-home', label: 'Home', url: '#'},
		{icon: 'icon-plane', label: 'Flight Schedules', url: 'flights'},
		{icon: 'icon-user', label: 'Login', url: 'http://localhost/report/login.php'},
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

controller('FlightController', 
['$scope', '$filter', '$http', '$routeParams', '$location', '$cookieStore',
function($scope, $filter, $http, $routeParams, $location, $cookieStore) {
	$scope.accomodation = {};

	$http.get('ci/airline_company').success(function(data) {
		$scope.airlineCompanies = data;
	});

	$http.get('ci/agency').success(function(data) {
		$scope.agencies = data;
	});

	$scope.ac_id = $routeParams.ac_id;
	$scope.agency_id = $routeParams.agency_id;

	$scope.start_date = $routeParams.start_date || $filter('date')(new Date, 'yyyy-MM-dd');
	$scope.end_date = $routeParams.end_date || $scope.start_date;

	$http.get('ci/accomodation/get_min_max').success(function(data) {
		$scope.price_min = parseFloat(data.min.fare);
		$scope.price_max = parseFloat(data.max.fare);

		$scope.price_start = getPrice($routeParams.start_price || $scope.price_min);
		$scope.price_end = getPrice($routeParams.end_price || $scope.price_max);
	});

	if($routeParams.seats > 0)
		$scope.available_seats = parseFloat($routeParams.seats);
	else
		$scope.available_seats = parseFloat(1);

	$scope.destination_from = $routeParams.from;
	$scope.destination_to = $routeParams.to;

	$http.get('ci/airline/get_max_seats').success(function(data) {
		$scope.max_seats = data.al_max;
	});

	$http.get('ci/place').success(function(data) {
		$scope.places = data;
	});

	$scope.getPrice = getPrice;

	$scope.validate = function(event, fs_id, flight) {
		if(angular.isDefined($scope.accomodation[fs_id])) {
			$cookieStore.put('accomodation', $scope.accomodation[fs_id]);
			$cookieStore.put('flight', flight);
			console.log('here: ', $cookieStore.get('flight'));
			$location.path('booking/entry/' + fs_id);
		} else {
			event.preventDefault();
			event.stopPropagation();
			alert('Select an accomodation first');
		}
	};

	$scope.changeAvailableSeat = function() {
		if($scope.available_seats < 1) 
			$scope.available_seats = 1;
		if($scope.available_seats > $scope.max_seats)
			$scope.available_seats = $scope.max_seats;
	};

	$scope.change = function() {
		console.log($scope);
	};

	$scope.search = function() {
		var params = {
			ac_id: $scope.ac_id || 0,
			agency_id: $scope.agency_id || 0,
			start_date: $scope.start_date,
			end_date: $scope.end_date,
			price_start: $scope.price_start,
			price_end: $scope.price_send,
			available_seats: $scope.available_seats,
			destination_from: $scope.destination_from || 0,
			destination_to: $scope.destination_to || 0
		};

		console.log(params);

		$http({
			method: 'GET',
			params: params,
			url: 'ci/flight'
		}).success(function(data) {
			$scope.flightGroupItem = $filter('group')(data, 3);
			console.log(data);
		}).error(function(msg) {
			console.log(msg);
		});
	};

	$scope.search();

	function getPrice(price) {
		if(price < $scope.price_min) return $scope.price_min;
		if(price > $scope.price_max) return $scope.price_max;
		return price;
	}

}]).

controller('BookingEntryController', 
['$scope', '$http', '$cookieStore', '$routeParams', '$location',
function($scope, $http, $cookieStore, $routeParams, $location) {

	$scope.data = {};

	$http({
		method: 'GET',
		url: 'ci/flight',
		params: {fs_id: $routeParams.sched_id}
	}).success(function(data) {
		$scope.flight = data[0];
		console.log($scope.flight);
	});

	$scope.saveToCookies = function(event) {
		event.preventDefault();
		event.stopPropagation();

		$cookieStore.put('passenger', $scope.data);
		$cookieStore.put('sched_id', $routeParams.sched_id);

		$location.path('booking/summary');
	}
}]).

controller('BookingSummaryController',
['$scope', '$http', '$cookieStore', '$location',
function($scope, $http, $cookieStore, $location) {
	var passenger = $cookieStore.get('passenger');
	var fs_id = $cookieStore.get('sched_id');
	var accomodation = $cookieStore.get('accomodation');

	$scope.passenger = passenger;
	$scope.fs_id = fs_id;
	$scope.accomodation = accomodation;

	$http({
		method: 'GET',
		url: 'ci/flight',
		params: {fs_id: fs_id}
	}).success(function(data) {
		$scope.flight = data[0];
	});

	$scope.payLater = function() {
		$http.post('ci/booking/save',{
			passenger: passenger,
			fs_id: fs_id,
			accom_id: accomodation.accom_id
		}).success(function(data) {
			$location.path('booking/number/' + data.bk_id);
		}).error(function(msg) {
			console.log(msg);
		});
	};

	$scope.cancel = function() {
		$cookieStore.remove('passenger');
		$cookieStore.remove('sched_id');
		$cookieStore.remove('accomodation');
		$location.path('flights');
	};
}]).

controller('BookingNumberController', 
['$scope', '$routeParams',
function($scope, $routeParams) {
	$scope.bk_id = $routeParams.bk_id;
}]).

controller('BookingPaypalLoginController', 
['$scope', '$http', '$location',
function($scope, $http, $location) {
	$scope.submit = function(event) {
		event.preventDefault();
		event.stopPropagation();
		$location.path('booking/paypal-confirmation')
	};
}]).

controller('BookingPaypalConfirmationController',
['$scope', '$http', '$cookieStore', '$location',
function($scope, $http, $cookieStore, $location) {
	var flight = $cookieStore.get('flight');
	var accom = $cookieStore.get('accomodation');
	var passenger = $cookieStore.get('passenger');
	var fs_id = $cookieStore.get('sched_id');
	var accomodation = $cookieStore.get('accomodation');

	$scope.payTo = flight.ac_name;
	$scope.accom = accom;

	$scope.payNow = function() {
		$http.post('ci/booking/create_ticket',{
			passenger: passenger,
			fs_id: fs_id,
			accom_id: accomodation.accom_id
		}).success(function(data) {
			console.log(data);
			$location.path('booking/ticket/' + data.at_id);
		}).error(function(msg) {
			console.log(msg);
		});

	};
}]).

controller('BookingTicketController',
['$scope', '$http', '$cookieStore', '$routeParams',
function($scope, $http, $cookieStore, $routeParams) {
	$scope.at_id = $routeParams.at_id;
}]).

filter('group', function() {
    return function(items, groupItems) {
        if (items) {
            var newArray = [];

            for (var i = 0; i < items.length; i+=groupItems) {
                if (i + groupItems > items.length) {
                    newArray.push(items.slice(i));
                } else {
                    newArray.push(items.slice(i, i + groupItems));
                }
            }

            return newArray;
        }
    };
});;