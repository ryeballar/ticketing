angular.module('RouteData', []).

provider('RouteData', function () {
  var settings = {};
  var hookToRootScope = false;
  var preCallbacks = [];
  
  this.applyConfig = function(newSettings) {
    settings = newSettings;
  };

  this.addCallback = function(fn) {
    if(angular.isFunction(fn))
      preCallbacks.push(fn);
  };
  
  this.hookToRootScope = function(enableRootScopeHook) {
    hookToRootScope = enableRootScopeHook;
  };
   
  function RouteData() {
    var _this = this;

    this.firePreCallbacks = function() {
      for(var e in preCallbacks)
        preCallbacks[e](_this);
    };

    this.set = function(index, value) {
      settings[index] = value;
    };
    
    this.get = function(index) {
      if(settings.hasOwnProperty(index)) {
        return settings[index];
      } else {
        console.log('RouteData: Attempt to access a propery that has not been set');
      }
    };
    
    this.isHookedToRootSope = function() {
      return hookToRootScope;
    };
  }
  
  this.$get = function() {
      return new RouteData();
  };
}).

run(['$location', '$rootScope', 'RouteData', function($location, $rootScope, RouteData) {
  if(RouteData.isHookedToRootSope())
    $rootScope.RouteData = RouteData;

  $rootScope.$on('$routeChangeStart', function(event, current, previous) {
    var route = current.$$route;

    RouteData.firePreCallbacks();

    if(typeof(route) !== 'undefined') {
      if(typeof(route['RouteCallback']) !== 'undefined') {
        var fn = route['RouteCallback'];
        if(angular.isFunction(fn))
          fn();
      }

      if(typeof(route['RouteData']) !== 'undefined') {
        var data = route['RouteData'];
        for(var index in data)
          RouteData.set(index, data[index]);  
      }

    } 
  });
}]);