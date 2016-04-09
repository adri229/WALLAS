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
					$location.path('/register');
				}, 
				function(response) {
					AuthenticationService.clearCredentials();
              		$scope.error = response.message;
              		alert("error");
				}
			);
        };
/*
        $scope.login = function() {
          AuthenticationService.setCredentials($scope.credentials.login,$scope.credentials.password);
          AuthenticationService.login($scope.credentials.login, $scope.credentials.password, function(response){
            if (response.success) {
              //AuthenticationService.setCredentials($scope.credentials.login,$scope.credentials.password);
              location.path('/');
            } else {
              AuthenticationService.clearCredentials();
              $scope.error = response.message;
            }
          });

          */
    
}]);



wallas.controller('RegisterController', ['$scope','UserService', function ($scope, UserService) {
        $scope.register = function() {
        	UserService.create($scope.user).then(
        		function(response) {
        			alert("success");
        		},
        		function(response) {
        			alert("error");
        			console.log(response);
        		}

        	);
        };
}]);


wallas.controller('UserController', ['$scope','UserService', function($scope, UserService) {

}]); 



wallas.controller('DropdownCtrl', ['$scope', function($scope) {

    $scope.items = [
    'The first choice!',
    'And another choice for you.',
    'but wait! A third!'
  ];

  $scope.status = {
    isopen: false
  };

  $scope.toggleDropdown = function($event) {
    $event.preventDefault();
    $event.stopPropagation();
    $scope.status.isopen = !$scope.status.isopen;
  };

  $scope.appendToEl = angular.element(document.querySelector('#dropdown-long-content'));
}]);
