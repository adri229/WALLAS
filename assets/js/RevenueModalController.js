'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueModalController', ['$scope', '$uibModalInstance', 'RevenueService', 'revenues',
	function($scope, $uibModalInstance, RevenueService, revenues) {

		$scope.create = function(revenue) {
			console.log(revenue);
			RevenueService.create(revenue).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertRevenue = true;
				}
			)
		}

		if (revenues != null) {
			$scope.revenue = revenues.revenue;	
		}
		

		$scope.update = function(revenue) {
			RevenueService.update(revenue, revenues.revenue.idRevenue).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertRevenue = true;
				}

			)
		}


		$scope.delete = function(revenue) {
			RevenueService.delete(revenues.idRevenue).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertRevenue = true;
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

		$scope.closeAlert = function(index) {
        	$scope.alertRevenue = false;
    	};

	}]);