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

	}]);