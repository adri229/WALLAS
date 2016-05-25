'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueController', ['$scope', '$cookies', '$uibModal','RevenueService', 
  function($scope, $cookies, $uibModal, RevenueService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    function refreshRevenues(startDate, endDate){
    	RevenueService.getByOwner(login, startDate, endDate).then(
            function(response) {
                $scope.revenues = response;
                console.log($scope.revenues.data);
            },
            function(response) {
                alert("error");
                console.log(response);
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
  			scope: $scope,
            resolve: {
                revenues: function() {
                    
                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
  				if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
  			},
  			function() {
  				alert("error");
  			}
  		)
    };

    $scope.update = function(revenue) {
        var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateRevenue.html',
            controller: 'RevenueModalController',
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
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                alert("error update");
            }
        )
    };

  	$scope.delete = function(revenue) {
  		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteRevenue.html',
            controller: 'RevenueModalController',
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
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshRevenues(defaultStartDate, defaultEndDate);
                } else {
                    refreshRevenues($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                alert("error delete");
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


  }]);
