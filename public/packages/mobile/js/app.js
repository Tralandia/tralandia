'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.services', 'myApp.directives']).
  config(['$routeProvider', function($routeProvider) {
      
    $routeProvider.when('/', {templateUrl: 'partials/partial1.html', controller: TraxMobileCtrl});
//    $routeProvider.otherwise({redirectTo: '/'});
  }]).run( function($rootScope, $location) {

    // register listener to watch route changes
    $rootScope.$on( "$routeChangeStart", function(event, next, current) {
      
      console.log($rootScope);
      console.log($location);
              
    });
 });
