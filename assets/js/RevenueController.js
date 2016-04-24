'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RevenueController', ['$scope', '$cookies', 'RevenueService', 
  function($scope, $cookies, RevenueService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	RevenueService.create($scope.revenue).then(
    		function(response) {
    			refreshRevenues();
                console.log($scope.revenue);
    		},
    		function(response) {
    			alert("error create");
            	console.log(response);
    		}

    		);
    };

    $scope.update = function(revenue) {
        
        RevenueService.update(revenue).then(
            function(response) {
                
            },
            function(response) {
                alert("error update")
            }
        )  
    };
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
  	

  	$scope.delete = function(idRevenue) {
  		RevenueService.delete(idRevenue).then(
		function(response) {
			
            refreshRevenues();
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}
		)
  	};


  }]);
