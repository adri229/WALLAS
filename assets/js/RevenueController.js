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

    $scope.intervalDate = function(option) {
            var defaultDate = new Date();
            var defaultStartDateUTC = 0;
            var defaultEndDateUTC = 0;
            switch (option) {
                case 1:
                    defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + defaultDate.getUTCMonth()+ '-01'; 
                    defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01'; 
                    break;
                case 2:
                    defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()-2)+ '-01'; 
                    defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01'; 
                    break;
                case 3:
                    defaultStartDateUTC = (defaultDate.getUTCFullYear()-1) + '-' + defaultDate.getUTCMonth()+ '-01'; 
                    defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01'; 
                    break;
                default:
                    break;
            }
            refreshRevenues(new Date(defaultStartDateUTC), new Date(defaultEndDateUTC));
        }

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

    function refreshRevenues(startDate, endDate){
        console.log(startDate);
        $scope.sortRevenue = 'name';
        $scope.classSort = 'fa fa-caret-down';

    	RevenueService.getByOwner(login, startDate, endDate).then(
            function(response) {
                $scope.revenues = response;
            },
            function(response) {
                notification("You don't have any revenues in this range of dates", "info");
                $scope.revenues = null;
            }
        );
    }

 	function defaultIntervalDate() {
        $scope.intervalDate(1);
    }
    defaultIntervalDate();

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
                    defaultIntervalDate();
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
                    	defaultIntervalDate();
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
                    defaultIntervalDate();
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
