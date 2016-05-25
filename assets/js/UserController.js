'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('UserController', ['$scope', '$cookies', '$uibModal','UserService', '$location', 
    function($scope, $cookies, $uibModal, UserService, $location) {
	var user = $cookies.getObject('globals');

	var login = user.currentUser.login;
	

	function refreshUser() {
		UserService.getByLogin(login).then(
			function(response) {
				$scope.user = response;
			},
			function(response) {
				alert("error");
	        	console.log(response);
			}
		);
	}
	refreshUser();
	
	
  $scope.update = function() {
  	
    var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateUser.html',
            controller: 'UserModalController',
            scope: $scope,
            resolve: {
                users: function() {
                    return {
                        user: user
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshUser();
            },
            function() {
                alert("error update");
            }
        )
  }

  $scope.updatePass = function() {
  	
    var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdatePass.html',
            controller: 'UserModalController',
            scope: $scope,
            resolve: {
                users: function() {
                    return {
                        login: login
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                $location.path('/login');
            },
            function() {
                alert("error update");
            }
        )
    }

    $scope.delete = function() {
		
		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteUser.html',
            controller: 'UserModalController',
            scope: $scope,
            resolve: {
                users: function() {
                    return {
                        login: login
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                $location.path('/login');
            },
            function() {
                alert("error delete");
            }
        )
	
	}

	

	
}]); 
