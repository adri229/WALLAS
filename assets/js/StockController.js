'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('StockController', ['$scope', '$cookies', '$uibModal', 'StockService',
	function($scope, $cookies, $uibModal, StockService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    function refreshStocks() {
    	StockService.getByOwner(login).then(
	  		function(response) {
				$scope.stocks = response;
				console.log($scope.stocks.data);
			},
			function(response) {
				alert("error");
	        	console.log(response);
			}
		);	
    }
    refreshStocks();

    $scope.create = function() {
    	var uibmodalInstance = $uibModal.open({
  			templateUrl: 'assets/html/modalNewStock.html',
  			controller: 'StockModalController',
  			scope: $scope,
            resolve: {
                stocks: function() {

                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
  				refreshStocks();
  				console.log("RESPONSE" + response);
  			},
  			function() {
  				alert("error");
  			}
  		)
    };

    
	
	$scope.update = function(stock) {
		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalUpdateStock.html',
            controller: 'StockModalController',
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
                refreshStocks();
            },
            function() {
                alert("error update");
            }
        )
	}

  	$scope.delete = function(stock) {
  		var uibmodalInstance = $uibModal.open({
            templateUrl: 'assets/html/modalDeleteStock.html',
            controller: 'StockModalController',
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
                refreshStocks();
            },
            function() {
                alert("error delete");
            }
        )
  	};

}]);