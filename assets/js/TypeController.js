'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeController', ['$scope', '$cookies', 'TypeService',
	function($scope, $cookies, TypeService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	TypeService.create($scope.type).then(
    		function(response) {
    			refreshTypes();
    			
    		},
    		function(response) {
    			alert("error create");
            	console.log(response);
    		}

    	)
    };

	
	function refreshTypes() {
		TypeService.getByOwner(login).then(
	  		function(response) {
				$scope.types = response;
				console.log($scope.types.data);
			},
			function(response) {
				alert("error");
	        	console.log(response);
			}
		)
	}
	refreshTypes();

	$scope.update = function(type) {
		TypeService.update(type).then(
			function(response) {
				refreshTypes();
				
			},
			function(response) {
				alert("error");
			}
		)
	}
	


  	$scope.delete = function(idType) {
  		TypeService.delete(idType).then(
		function(response) {
			refreshTypes();
			
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}
		)
  	};

}]);