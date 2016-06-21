'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('NavbarController', ['$scope', '$cookies', '$location', 'AuthenticationService', 
	function($scope, $cookies, $location, AuthenticationService) {

	$scope.isActive = function (viewLocation) { 
        return viewLocation === $location.path();
    };


    $scope.logout = function() {
    	$location.path('/login');
    	AuthenticationService.clearCredentials();
    }
	

}]);