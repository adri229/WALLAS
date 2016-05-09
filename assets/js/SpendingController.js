'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingController', ['$scope', '$cookies', '$uibModal', 'SpendingService', 'TypeService',
    function($scope, $cookies, $uibModal, SpendingService, TypeService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;


    function refreshSpendings() {
        SpendingService.getByOwner(login).then(
            function(response) {

                $scope.spendings = response;
            //    alert(JSON.stringify(response.data));
                console.log(response);

                $scope.variable = "hola";


                console.log($scope.spendings.data[0].types[0]);


            },
            function(response) {
                alert("error");
                console.log(response);
            }
        );
    };
    refreshSpendings();

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
                refreshSpendings();
                console.log("RESPONSE" + response);
            },
            function() {
                alert("error");
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
                        idSpending: spending.idSpending
                    }

                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshSpendings();
            },
            function() {
                alert("error update");
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
                refreshSpendings();
            },
            function() {
                alert("error delete");
            }
        )
    };




  }]);
