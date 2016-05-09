'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingModalController', ['$scope', '$uibModalInstance', 'SpendingService', 'spendings',
	function($scope, $uibModalInstance, SpendingService, spendings) {


		var types = [];

		$scope.toggle = function(type) {
			types.push(type);
		}

		
		function clearArray() {
			types.splice(0,types.length);
		}

		

		$scope.create = function(spending) {
			spending.types = types;
			SpendingService.create(spending).then(
				function(response) {
					clearArray();
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error create");
				}
			)
		}


		$scope.update = function(spending) {
			spending.types = types;

			SpendingService.update(spending, spendings.idSpending).then(
				function(response) {
					clearArray();
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}	
			)
		}


		$scope.delete = function() {

			SpendingService.delete(spendings.idSpending).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}	
			)

		}


		$scope.cancel = function () {
	        $uibModalInstance.dismiss('cancel');
	    };


}]);