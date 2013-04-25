'use strict';

/* Controllers */


function TraxMobileCtrl($scope) {
  $scope.templates =
    [ { name: 'partial1.html', url: 'partials/partial1.html'}
    , { name: 'partial2.html', url: 'partials/partial2.html'} ];
  $scope.template = $scope.templates[1];    
}
TraxMobileCtrl.$inject = ['$scope'];


