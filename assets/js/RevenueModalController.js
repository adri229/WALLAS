'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueModalController', ['$scope', '$uibModalInstance', 'RevenueService', 'items',
	function($scope, $uibModalInstance, RevenueService, items) {

		$scope.create = function(revenue) {
			console.log(revenue);
			RevenueService.create(revenue).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error create");
				}
			)
		}


		$scope.update = function(revenue) {
			RevenueService.update(revenue, items.idRevenue).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}

			)
		}


		$scope.delete = function(revenue) {
			RevenueService.delete(items.idRevenue).then(
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


		$scope.dateOptions = {
			formatYear: 'yy',
			maxDate: new Date(2020, 5, 22),
			minDate: new Date(1982, 7, 21),
			startingDay: 1
		};

		$scope.datepopupOpened = false;
		$scope.opendate = function() {
		    $scope.datepopupOpened = true;
		};

	}]);