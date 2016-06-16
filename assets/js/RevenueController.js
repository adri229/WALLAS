'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueController', ['$scope', '$cookies', '$uibModal','RevenueService', 
  function($scope, $cookies, $uibModal, RevenueService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.hide = false;
    $scope.searchRevenue = '';

    $scope.changeSort = function(sort) {
        switch (sort) {
            case 1: 
                if ($scope.sortRevenue == 'name') {
                    $scope.sortRevenue = '-name';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortRevenue = 'name';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            case 2:
                if ($scope.sortRevenue == 'quantity') {
                    $scope.sortRevenue = '-quantity';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortRevenue = 'quantity';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            case 3:
                if ($scope.sortRevenue == 'date') {
                    $scope.sortRevenue = '-date';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortRevenue = 'date';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            default:
                break;
        }
        

    }

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

    function refreshRevenues(startDate, endDate){
        $scope.sortRevenue = 'name';
        $scope.classSort = 'fa fa-caret-down';

    	RevenueService.getByOwner(login, startDate, endDate).then(
            function(response) {
                $scope.revenues = response;
            },
            function(response) {
                notification("You don't have any revenues", "info");
                $scope.revenues = null;
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
    

    refreshRevenues(defaultStartDate, defaultEndDate);

    $scope.changeIntervalRevenues = function() {
        refreshRevenues($scope.startDate, $scope.endDate);  
    }

    


    $scope.create = function() {
    	var uibmodalInstance = $uibModal.open({
  			templateUrl: 'assets/html/modalNewRevenue.html',
  			controller: 'RevenueModalController',
            animation : true,
            backdrop: false,
  			scope: $scope,
            resolve: {
                revenues: function() {
                    
                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
                notification("New revenue was added successfully", "success");
  				if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
  			},
  			function(response) {
  				if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
  			}
  		)
    };

    $scope.update = function(revenue) {
        var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateRevenue.html',
            controller: 'RevenueModalController',
            animation : true,
            backdrop: false,
            scope: $scope,
            resolve: {
                revenues: function() {
                    return {
                        revenue: revenue
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Revenue updated successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
            },
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                } else {
                	if ($scope.startDate == null || $scope.endDate == null) {
                    	refreshRevenues(defaultStartDate, defaultEndDate);
                	} else {
                    	refreshRevenues($scope.startDate, $scope.endDate);  
                	}
                }
            }
        )
    };

  	$scope.delete = function(revenue) {
  		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteRevenue.html',
            controller: 'RevenueModalController',
            animation : true,
            backdrop: false,
            scope: $scope,
            resolve: {
                revenues: function() {
                    return {
                        idRevenue: revenue.idRevenue
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Revenue deleted successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
            },
            function(response) {
                if (response.localeCompare("cancel") != 0) {
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
