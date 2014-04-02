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
		.when('/membership', {
			RouteData: {
				activePage: 'Passenger'
			},
			templateUrl: 'partials/membership.html'
		})
		.when('/booking', {
			RouteData: {
				activePage: 'Passenger'
			},
			templateUrl: 'partials/booking.html',
			controller: 'BookingController'
		})

		.when('/itinerary/:bk_id_from/:bk_id_to', {
			RouteData: {
				activePage: 'Passenger',
			},
			templateUrl: 'partials/itinerary.html',
			controller: 'ItineraryController'
		})

		.when('/airline-companies', {
			RouteData: {
				activePage: 'Passenger'
			},
			templateUrl: 'partials/airline-companies.html'
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