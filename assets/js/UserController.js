'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('UserController', ['$scope', '$cookies', 'UserService', 
    function($scope, $cookies,UserService) {
	var user = $cookies.getObject('globals');

	var login = user.currentUser.login;
	


	UserService.getByLogin(login).then(
		function(response) {
			$scope.user = response;
		},
		function(response) {
			alert("error");
        	console.log(response);
		}
	);

	$scope.delete = function() {
		
		UserService.delete(login).then(
		function(response) {
			alert("DELETE USER");
			$location.path('/register');
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}

	);
	}

  $scope.update = function(user) {
  	
    UserService.update(login,user).then(
        function(response) {
            alert("UPDATE ");
        },
        function(response) {
            alert("error update");
            console.log(response);
        }        
    );
  }
	

	
}]); 
