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
				console.log($scope.types.data);
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
                items: function() {

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


    $scope.editType = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalEditType.html',
            controller: 'TypeModalController',
            scope: $scope,
            resolve: {
                items: function() {
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
                alert("error update");
            }
        )
        
    }


    $scope.deleteType = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalDeleteType.html',
            controller: 'TypeModalController',
            scope: $scope,
            resolve: {
                items: function() {
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