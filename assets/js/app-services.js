angular.module('app').

factory('GetAll', ['$http', '$q', function($http, $q) {
	return function(url, param) {
		var defer = $q.defer();
		$http({
			url: 'ci/' + url,
			method: 'GET',
			params: param
		}).success(function(data) {
			defer.resolve(data);
		}).error(function(msg) {
			console.log(msg);
		});
		return defer.promise;
	};
}]);