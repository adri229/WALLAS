'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpendingController', ['$scope', '$cookies', 'SpendingService',
    function($scope, $cookies, SpendingService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
        SpendingService.create($scope.spending).then(
            function(response) {
            	refreshSpendings();
                
            },
            function(response) {
                alert("error create");
                console.log(response);
            }
        );
    };

    
	function refreshSpendings() {
		SpendingService.getByOwner(login).then(
	        function(response) {
	            $scope.spendings = response;
	            
	            console.log($scope.spendings.data[0].types[0]);

	        },
	        function(response) {
	            alert("error");
	            console.log(response);
	        }
    	);	
	};
    
    refreshSpendings();


    $scope.delete = function(idSpending) {
    	SpendingService.delete(idSpending).then(
    		function(response) {
    			refreshSpendings();
                
            },
            function(response) {
                alert("error delete");
                console.log(response);
            }

    	)
    };

    $scope.update = function(spending) {
        SpendingService.update(spending).then(
            function(response) {
                refreshSpendings();
                
            },
            function(response) {
                alert("error update");
                console.log(response);
            }            
        )
    }


  }]);