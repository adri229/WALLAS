'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('AuthenticationController',
['$scope', '$location', 'AuthenticationService',
 function ($scope, $location, AuthenticationService) {

 		$scope.hide = false;

 		function notification() {
	        $scope.hide = true;
	        $scope.show = true;
    	}

    	$scope.show = true;
  
	    $scope.closeAlert = function(index) {
	        $scope.show = false;
	    };
   
        AuthenticationService.clearCredentials();

        $scope.login = function() {
        	AuthenticationService.setCredentials($scope.credentials);
        	
			AuthenticationService.login($scope.credentials).then(
				function(response) {
					$location.path('/dashboard');
				}, 
				function(response) {
					AuthenticationService.clearCredentials();
              		notification();
				}
			);
        };
    
}]);