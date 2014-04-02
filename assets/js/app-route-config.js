angular.module('app').

config(['$locationProvider', '$routeProvider', 'RouteDataProvider', function($locationProvider, $routeProvider, RouteDataProvider) {
	RouteDataProvider.applyConfig({
		activePage: 'Home'
	});

	RouteDataProvider.hookToRootScope(true);
	$locationProvider.html5Mode(true);

	$routeProvider
		.when('/', {
			RouteData: {
				activePage: 'Home'
			},
			templateUrl: 'partials/landing.html',
			controller: 'LandingController'
		})
		// :ac_id/:al_id/:start_date/:end_date/:start_price/:end_price/:seats/:from/:to
		.when('/flights', {
			RouteData: {
				activePage: 'Flight Schedules'
			},
			templateUrl: 'partials/flights.html',
			controller: 'FlightController'
		})

		.when('/flights/:ac_id/:al_id/:start_date/:end_date/:start_price/:end_price/:seats/:from/:to', {
			RouteData: {
				activePage: 'Flight Schedules'
			},
			templateUrl: 'partials/flights.html',
			controller: 'FlightController'
		})

		.when('/booking/entry/:sched_id', {
			RouteData: {
				activePage: 'Flight Schedules'
			},
			templateUrl: 'partials/booking-entry.html',
			controller: 'BookingEntryController'
		})

		.when('/booking/summary', {
			RouteData: {
				activePage: 'Flight Schedules'
			},
			templateUrl: 'partials/booking-summary.html',
			controller: 'BookingSummaryController'
		})
		
		.when('/booking/number/:bk_id', {
			RouteData: {
				activePage: 'Flight Schedules'
			},
			templateUrl: 'partials/booking-number.html',
			controller: 'BookingNumberController'
		})

		.when('/login', {
			RouteData: {
				activePage: 'Login'
			},
			templateUrl: 'partials/login.html',
			controller: 'LoginController'
		})
		.when('/signup', {
			RouteData: {
				activePage: 'Sign Up',
			},
			templateUrl: 'partials/signup.html',
			controller: 'SignupController'
		})
		.otherwise({
			redirectTo: '/'
		});
}]);