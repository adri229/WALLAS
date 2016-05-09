'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('UserModalController', ['$scope', '$uibModalInstance', 'UserService', 'users',
	function($scope, $uibModalInstance, UserService, users) {


		$scope.update = function(user) {
			UserService.update(users.login, user).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}
			)
		}

		$scope.updatePass = function(user) {
			UserService.updatePass(users.login, user).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}
			)
		}



		$scope.delete = function() {
			UserService.delete(users.login).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error delete");
				}
			)
		}

		$scope.cancel = function () {
	        $uibModalInstance.dismiss('cancel');
	    };

	   
}]);