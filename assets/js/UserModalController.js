'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('UserModalController', ['$scope', '$uibModalInstance', 'UserService', 'users',
	function($scope, $uibModalInstance, UserService, users) {


		$scope.update = function(user) {
			UserService.update(users.user.currentUser.login, user).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertUser = true;
				}
			)
		}

		$scope.updatePass = function(user) {
			UserService.updatePass(users.login, user).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertUser = true;
				}
			)
		}



		$scope.delete = function() {
			UserService.delete(users.login).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertUser = true;
				}
			)
		}

		$scope.cancel = function () {
	        $uibModalInstance.dismiss('cancel');
	    };

	    $scope.closeAlert = function(index) {
        	$scope.alertUser = false;
    	};	   
}]);