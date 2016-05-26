'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingController', ['$scope', '$cookies', '$uibModal', 'SpendingService', 'TypeService',
    function($scope, $cookies, $uibModal, SpendingService, TypeService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.hide = false;

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

    function refreshSpendings(startDate, endDate) {
        SpendingService.getByOwner(login, startDate, endDate).then(
            function(response) {
                $scope.spendings = response;
            },
            function(response) {
                notification("You don't have any spendings", "info");
                $scope.spendings = null;
            }
        );
    };

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
    

    refreshSpendings(defaultStartDate, defaultEndDate);

    $scope.changeIntervalSpendings = function() {
        refreshSpendings($scope.startDate, $scope.endDate);  
    }

    function getTypes() {
        TypeService.getByOwner(login).then(
            function(response) {
                $scope.types = response.data;
                console.log(response);
            },
            function(response) {
                alert("error create");
            }
        )
    }

    $scope.create = function() {
        getTypes();
        var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalNewSpending.html',
            controller: 'SpendingModalController',
            scope: $scope,
            resolve: {
                spendings: function() {

                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("New revenue was added successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshSpendings(defaultStartDate, defaultEndDate);
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
    };

    $scope.update = function(spending) {
        getTypes();
        var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateSpending.html',
            controller: 'SpendingModalController',
            scope: $scope,
            resolve: {
                spendings: function() {
                    return {
                        spending: spending
                    }

                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Spending updated successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshSpendings(defaultStartDate, defaultEndDate);
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);  
                }
            },
            function() {
                if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
    }


    $scope.delete = function(spending) {
    	var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteSpending.html',
            controller: 'SpendingModalController',
            scope: $scope,
            resolve: {
                spendings: function() {
                    return {
                        idSpending: spending.idSpending
                    }

                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                notification("Spending deleted successfully", "success");
                if ($scope.startDate == null || $scope.endDate == null) {
                    refreshSpendings(defaultStartDate, defaultEndDate);
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);  
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
