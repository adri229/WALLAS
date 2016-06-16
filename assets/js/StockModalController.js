'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('StockModalController', ['$scope', '$uibModalInstance', 'StockService', 'stocks',
	function($scope, $uibModalInstance, StockService, stocks) {

		$scope.create = function(stock) {
			StockService.create(stock).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertStock = true;
				}
			)
		}

		
		if (stocks != null) {
			$scope.stock = stocks.stock;	
		}
		

		$scope.update = function(stock) {
			
			StockService.update(stock, stocks.stock.idStock).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertStock = true;
				}
			)
		}



		$scope.delete = function() {
			StockService.delete(stocks.idStock).then(
				function(response) {
					$uibModalInstance.close('closed');
				},
				function(response) {
					$scope.msg = 'An error ocurred';
	    			$scope.alertStock = true;
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
        	$scope.alertStock = false;
    	};

	}]);
