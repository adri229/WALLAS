'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('StockController', ['$scope', '$cookies', 'StockService',
	function($scope, $cookies, StockService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	StockService.create($scope.stock).then(
    		function(response) {
    			refreshStocks();
    			
    		},
    		function(response) {
    			alert("error create");
            	console.log(response);
    		}

    	)
    };

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
	
	$scope.update = function(stock) {
		StockService.update(stock).then(
			function(response) {
				refreshStocks();
				
			},
			function(response) {
				alert("error");
			}
		)

	}

  	$scope.delete = function(idStock) {
  		StockService.delete(idStock).then(
			function(response) {
				refreshStocks();
				
			},
			function(response) {
				alert("error delete");
	        	console.log(response);
			}
		)
  	};

}]);