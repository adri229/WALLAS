'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('UserController', ['$scope', '$cookies', '$uibModal','UserService', '$location', 
    function($scope, $cookies, $uibModal, UserService, $location) {
	var user = $cookies.getObject('globals');

	var login = user.currentUser.login;
	
    $scope.hide = false;

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

	function refreshUser() {
		UserService.getByLogin(login).then(
			function(response) {
				$scope.user = response;
			},
			function(response) {
				notification("An error ocurred!", "danger");
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
                notification("Account updated successfully", "success");
                alert($scope.message);
            },
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                }
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
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                }
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
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
	
	}

	
    $scope.show = true;
  
    $scope.closeAlert = function(index) {
        $scope.show = false;
    };

	
}]); 
