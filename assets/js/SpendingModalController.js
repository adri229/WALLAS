'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingModalController', ['$scope', '$uibModalInstance', 'SpendingService', 'spendings',
	function($scope, $uibModalInstance, SpendingService, spendings) {


		

		$scope.create = function(spending) {
			spending.types = $scope.selected;
			SpendingService.create(spending).then(
				function(response) {
					//$scope.selected = null;
					$uibModalInstance.close('closed');
				},
				function(response) {
					alert("error create");
				}
			)
		}



		$scope.update = function(spending) {
			spending.types = $scope.selected;
			SpendingService.update(spending, spendings.spending.idSpending).then(
				function(response) {
					//$scope.selected = null;
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


		$scope.selected = [];
      	$scope.toggle = function (item, list) {
	        var idx = list.indexOf(item);
	        if (idx > -1) {
	          list.splice(idx, 1);
	        }
	        else {
	          list.push(item);
	        }
      	};
      	$scope.exists = function (item, list) {
        	return list.indexOf(item) > -1;
      	};
}]);