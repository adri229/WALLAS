'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingController', ['$scope', '$cookies', '$uibModal', 'SpendingService', 'TypeService',
    function($scope, $cookies, $uibModal, SpendingService, TypeService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.hide = false;
    $scope.searchSpending = '';

    $scope.changeSort = function(sort) {
        switch (sort) {
            case 1:
                if ($scope.sortSpending == 'name') {
                    $scope.sortSpending = '-name';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortSpending = 'name';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            case 2:
                if ($scope.sortSpending == 'quantity') {
                    $scope.sortSpending = '-quantity';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortSpending = 'quantity';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            case 3:
                if ($scope.sortSpending == 'date') {
                    $scope.sortSpending = '-date';
                    $scope.classSort = 'fa fa-caret-up';
                } else {
                    $scope.sortSpending = 'date';
                    $scope.classSort = 'fa fa-caret-down';
                }
                break;
            default:
                break;
        }


    }

    $scope.intervalDate = function(option) {

            var defaultDateStart = new Date();
            var defaultDateEnd = new Date();
            switch (option) {
                case 1:
                    defaultDateStart.setDate(1);
                    defaultDateEnd.setMonth(defaultDateEnd.getMonth() + 1);
                    defaultDateEnd.setDate(1);
                    break;
                case 2:
                    defaultDateStart.setDate(1);
                    defaultDateStart.setMonth(defaultDateStart.getMonth() - 2);

                    defaultDateEnd.setMonth(defaultDateEnd.getMonth() + 1);
                    defaultDateEnd.setDate(1);
                    break;
                case 3:

                    defaultDateStart.setDate(1);
                    defaultDateStart.setFullYear(defaultDateStart.getFullYear() - 1);

                    defaultDateEnd.setMonth(defaultDateEnd.getMonth() + 1);
                    defaultDateEnd.setDate(1);

                    break;
                default:
                    break;
            }
            refreshSpendings(defaultDateStart, defaultDateEnd);
        }

    function notification(msg, type) {
        $scope.message = msg;
        $scope.type = type;
        $scope.hide = true;
        $scope.show = true;
    }

    function refreshSpendings(startDate, endDate) {
        $scope.sortSpending = 'date';
        $scope.classSort = 'fa fa-caret-down';

        SpendingService.getByOwner(login, startDate, endDate).then(
            function(response) {
                $scope.spendings = response;
            },
            function(response) {
                notification("You don't have any spendings in this range of dates", "info");
                $scope.spendings = null;
            }
        );
    };

    function defaultIntervalDate() {
        $scope.intervalDate(1);
    }
    defaultIntervalDate();


    $scope.changeIntervalSpendings = function() {
        if ($scope.startDate != null && $scope.endDate != null) {
            refreshSpendings($scope.startDate, $scope.endDate);    
        }
    }

    function getTypes() {
        TypeService.getByOwner(login).then(
            function(response) {
                $scope.types = response.data;

            },
            function(response) {

            }
        )
    }

    $scope.create = function() {
        getTypes();
        var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalNewSpending.html',
            controller: 'SpendingModalController',
            animation : true,
            backdrop: false,
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
                    defaultIntervalDate();
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);
                }
            },
            function(response) {
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
            animation : true,
            backdrop: false,
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
                    defaultIntervalDate();
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);
                }
            },
            function(response) {
                if (response.localeCompare("cancel") != 0 ) {
                    notification("An error ocurred!", "danger");
                } else {
                    if ($scope.startDate == null || $scope.endDate == null) {
                       refreshSpendings(defaultStartDate, defaultEndDate);
                    } else {
                        refreshSpendings($scope.startDate, $scope.endDate);
                    }
                }
            }
        )
    }


    $scope.delete = function(spending) {
    	var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteSpending.html',
            controller: 'SpendingModalController',
            animation : true,
            backdrop: false,
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
                    defaultIntervalDate();
                } else {
                    refreshSpendings($scope.startDate, $scope.endDate);
                }
            },
            function(response) {
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
