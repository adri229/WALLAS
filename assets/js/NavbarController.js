'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('NavbarController', ['$scope', '$cookies', '$location', function($scope, $cookies, $location) {

	$scope.isActive = function (viewLocation) { 
        return viewLocation === $location.path();
    };


    $scope.logout = function() {
    	$location.path('/login');
    	$cookies.remove('globals');
    }
	

}]);