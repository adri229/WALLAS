'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('AuthenticationController',
['$scope', '$rootScope', '$location', 'AuthenticationService',
 function ($scope, $rootScope, $location, AuthenticationService) {
   
        AuthenticationService.clearCredentials();

        $scope.login = function() {
        	AuthenticationService.setCredentials($scope.credentials);
        	
			AuthenticationService.login($scope.credentials).then(
				function(response) {
					//AuthenticationService.setCredentials($scope.credentials);
					$location.path('/user');
				}, 
				function(response) {
					AuthenticationService.clearCredentials();
              		$scope.error = response.message;
              		alert("error");
				}
			);
        };
    
}]);


















