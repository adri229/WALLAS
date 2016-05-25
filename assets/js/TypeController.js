'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeController', ['$scope', '$cookies', '$uibModal', 'TypeService',
	function($scope, $cookies, $uibmodal, TypeService) {

  	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

	
	function refreshTypes() {
		TypeService.getByOwner(login).then(
	  		function(response) {
				$scope.types = response;
			},
			function(response) {
				alert("error");
	        	console.log(response);
			}
		)
	}
	refreshTypes();

  	$scope.addNewType = function() {
  		var uibmodalInstance = $uibmodal.open({
  			templateUrl: 'assets/html/modalNewType.html',
  			controller: 'TypeModalController',
  			scope: $scope,
            resolve: {
                types: function() {

                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
  				refreshTypes();
  				console.log("RESPONSE" + response);
  			},
  			function() {
  				alert("error");
  			}
  		)
  	}


    $scope.update = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalUpdateType.html',
            controller: 'TypeModalController',
            scope: $scope,
            resolve: {
                types: function() {
                    return {
                        type: type
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshTypes();
            },
            function() {
                alert("error update");
            }
        )
        
    }


    $scope.delete = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalDeleteType.html',
            controller: 'TypeModalController',
            scope: $scope,
            resolve: {
                types: function() {
                    return {
                           idType:type.idType
                    }
                     
                }
            }
        });

        uibmodalInstance.result.then(
            function(response) {
                refreshTypes();
            },
            function() {
                alert("error delete");
            }
        )
    }



}]);