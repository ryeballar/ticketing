angular.module('app').

directive('navItem', function() {
	return {
		restrict: 'A',
		templateUrl: 'templates/navbar-item.html'
	};
}).

directive('formValidate', ['$compile', function($compile) {
	return {
		restrict: 'E',
		replace: true,
		transclude: true,
		template: '<form ng-submit="submit()" ng-transclude></form>',
		controller: ['$scope', '$http', '$location', function($scope, $http, $location) {
			$scope.focused = false;

			$scope.isFocused = function() {
				return $scope.focused;
			};

			$scope.setFocus = function(isFocused) {
				$scope.focused = isFocused;
			};

			$scope.errors = angular.copy($scope.data)

			$scope.submit = function() {
				console.log('submit');
				$http.post($scope.url, $scope.data).success(function(data) {
					if(data.success) {
						if($scope.redirect)
							$location.path($scope.redirect);
						else if($scope.force)
							window.location.replace($scope.force);
					} else {
						var errors = data.errors;
						for(var index in errors) {
							var var_eval = ('$scope.errors.' + index).replace(/\[+/g, '.').replace(/\]/g, '');
							var val_val = "'" + errors[index] + "'";
							eval(var_eval + '=' + val_val);
						}
					}
					$scope.setFocus(false);
				}).error(function(msg) {
					console.log(msg);
				});
			};
		}],

		link: function(scope, elem, attr) {
			scope.url = elem.attr('url');
			scope.redirect = elem.attr('redirect');
			scope.force = elem.attr('force');
		}
	}
}]).

directive('formGroup', function($compile) {
	return {
		restrict: 'E',
		transclude: true,
		replace: true,
		template: '<div class="form-group" ng-transclude></div>',
		controller: ['$scope', function($scope) {
			var control;

			this.getControlModel = function() {
				return 'data.' + control;
			};

			this.setControlModel = function(ctrl) {
				control = ctrl;
			};
		}],
		link: function(scope, elem, attr, ctrl) {
			var control = elem.attr('control');
			ctrl.setControlModel(control);
			var tpl = angular.element('<small class="error" ng-bind="errors.' + control + '"></small>');
			var append_elem = $compile(tpl)(scope);
			elem.append(append_elem);
		}
	}
}).

directive('formControl', ['$compile', function($compile) {
	return {
		restrict: 'A',
		link: function(scope, elem, attr) {
			var var_eval = ('scope.' + elem.attr('ng-model')).replace(/\[+/g, '.').replace(/\]/g, '').replace('data', 'errors');
			elem.bind('input', function() {
				scope.$apply(function() {
					eval(var_eval + '= "";');
				});
			});

			scope.$watch(function() {
				if(typeof(eval(var_eval)) !== 'undefined' && !scope.isFocused()) {
					elem.focus();
					scope.setFocus(true);
				}	
			});
		}
	};
}]);