'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('StockModalController', ['$scope', '$uibModalInstance', 'StockService', 'stocks', '$timeout',
	function($scope, $uibModalInstance, StockService, stocks, $timeout) {

		$scope.create = function(stock) {
			StockService.create(stock).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error create");
				}
			)
		}


		$scope.update = function(stock) {
			StockService.update(stock, stocks.idStock).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error update");
				}
			)
		}



		$scope.delete = function() {
			StockService.delete(stocks.idStock).then(
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
