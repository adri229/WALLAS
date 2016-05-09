'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueController', ['$scope', '$cookies', '$uibModal','RevenueService', 
  function($scope, $cookies, $uibModal, RevenueService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    function refreshRevenues(){
    	RevenueService.getByOwner(login).then(
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
 	refreshRevenues();

    $scope.create = function() {
    	var uibmodalInstance = $uibModal.open({
  			templateUrl: 'assets/html/modalNewRevenue.html',
  			controller: 'RevenueModalController',
  			scope: $scope,
            resolve: {
                items: function() {

                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
  				refreshRevenues();
  				console.log("RESPONSE" + response);
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
                items: function() {
                    return {
                        idRevenue: revenue.idRevenue
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshRevenues();
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
                items: function() {
                    return {
                        idRevenue: revenue.idRevenue
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshRevenues();
            },
            function() {
                alert("error delete");
            }
        )
    };
  	


  }]);
