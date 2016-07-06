'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RegisterController', ['$scope','UserService', '$location', function ($scope, UserService, $location) {
    
    $scope.register = function() {
      	UserService.create($scope.user).then(
       		function(response) {
    	        $location.path('/login');
       		},
       		function(response) {
       		}
       	);
    };
}]);
