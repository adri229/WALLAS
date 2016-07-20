'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueModalController', ['$scope', '$uibModalInstance', 'RevenueService', 'revenues', '$cookies', 'TypeService',
	function($scope, $uibModalInstance, RevenueService, revenues, $cookies, TypeService) {

		var user = $cookies.getObject('globals');
    	var login = user.currentUser.login;

		function getTypes() {
        	TypeService.getByOwner(login).then(
	            function(response) {
	                $scope.types = response.data;
	            },
	            function(response) {

	            }
        	)
    	}

		if (revenues != null) {
			$scope.revenue = revenues.revenue;	
		}
		

		$scope.create = function(revenue) {
			revenue.types = $scope.selected;
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

		$scope.update = function(revenue) {
			revenue.types = $scope.selected;
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

      	$scope.isCollapsed = false;


      	$scope.addNewType = function(type) {
      		TypeService.create(type).then(
				function(response) {
					$scope.newType = null;
					getTypes();
				},
				function(response) {
					if (response.data == 'This type already exits') {
	    				$scope.msg = 'This type already exits';
	    			} else {
	    				$scope.msg = 'An error ocurred';
	    			}
	    			$scope.alertSpending = true;
				}
			)
      	}

	}]);