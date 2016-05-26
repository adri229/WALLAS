'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('StockController', ['$scope', '$cookies', '$uibModal', 'StockService',
	function($scope, $cookies, $uibModal, StockService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.hide = false;

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

    function refreshStocks(startDate, endDate) {
    	StockService.getByOwner(login, startDate, endDate).then(
	  		function(response) {
				$scope.stocks = response;
			},
			function(response) {
				notification("You don't have any stocks", "info");
	        	$scope.stocks = null;
			}
		);	
    }
    var defaultStartDate = new Date();
    defaultStartDate.setHours(0);
    defaultStartDate.setMinutes(0);
    defaultStartDate.setSeconds(0);
    var defaultStartDateUTC = defaultStartDate.getUTCFullYear() + '-' + (defaultStartDate.getUTCMonth() + 1)+ '-' 
        + defaultStartDate.getUTCDate()+'T'+defaultStartDate.getUTCHours()+':'+defaultStartDate.getUTCMinutes()+'Z';
    
    var defaultEndDate = new Date();
    defaultEndDate.setHours(0);
    defaultEndDate.setMinutes(0);
    defaultEndDate.setSeconds(0);
    var defaultStartDateUTC = defaultEndDate.getUTCFullYear() + '-' + (defaultEndDate.getUTCMonth() + 1)+ '-' 
        + defaultEndDate.getUTCDate()+'T'+defaultEndDate.getUTCHours()+':'+defaultEndDate.getUTCMinutes()+'Z';
    

    refreshStocks(defaultStartDate, defaultEndDate);

    $scope.changeIntervalStocks = function() {
        refreshStocks($scope.startDate, $scope.endDate);  
    }

    $scope.create = function() {
    	var uibmodalInstance = $uibModal.open({
  			templateUrl: 'assets/html/modalNewStock.html',
  			controller: 'StockModalController',
            animation : true,
            backdrop: false,
  			scope: $scope,
            resolve: {
                stocks: function() {

                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
                notification("New stock was added successfully", "success");
  				if ($scope.startDate == null || $scope.endDate == null) {
                    refreshStocks(defaultStartDate, defaultEndDate);
                } else {
                    refreshStocks($scope.startDate, $scope.endDate);  
                }
  			},
  			function() {
  				if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
  			}
  		)
    };

    
	
	$scope.update = function(stock) {
		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateStock.html',
            controller: 'StockModalController',
            animation : true,
            backdrop: false,
            scope: $scope,
            resolve: {
                stocks: function() {
                    return {
                        stock: stock
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Stock updated successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshStocks(defaultStartDate, defaultEndDate);
                } else {
                    refreshStocks($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
	}

  	$scope.delete = function(stock) {
  		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteStock.html',
            controller: 'StockModalController',
            animation : true,
            backdrop: false,
            scope: $scope,
            resolve: {
                stocks: function() {
                    return {
                        idStock: stock.idStock
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Stock deleted successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshStocks(defaultStartDate, defaultEndDate);
                } else {
                    refreshStocks($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
  	};

    $scope.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(1982, 7, 21),
            startingDay: 1
        };

        
        $scope.openInitDate = function() {
            $scope.datepopupInitOpened = true;
        };
        $scope.openEndDate = function() {
            $scope.datepopupEndOpened = true;
        };

    $scope.show = true;
  
    $scope.closeAlert = function(index) {
        $scope.show = false;
    };

}]);