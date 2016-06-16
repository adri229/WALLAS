'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeController', ['$scope', '$cookies', '$uibModal', 'TypeService',
	function($scope, $cookies, $uibmodal, TypeService) {

  	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;


    $scope.hide = false;
    $scope.searchType = '';

    $scope.changeSort = function() {
        if ($scope.sortType == 'name') {
            $scope.sortType = '-name';
            $scope.classSort = 'fa fa-caret-up';
        } else {
        	$scope.sortType = 'name';
            $scope.classSort = 'fa fa-caret-down';
        }

    }

    function notification(msg, type) {
        $scope.message = msg;
        $scope.typeAlert = type;
        $scope.hide = true;
        $scope.show = true;
    }


	function refreshTypes() {
        $scope.sortType = 'name';
        $scope.classSort = 'fa fa-caret-down';
        
		TypeService.getByOwner(login).then(
	  		function(response) {
				$scope.types = response;
			},
			function(response) {
				notification("You don't have any revenues", "info");
                $scope.types = null;
			}
		)
	}
	refreshTypes();

  	$scope.create = function() {
  		var uibmodalInstance = $uibmodal.open({
  			templateUrl: 'assets/html/modalNewType.html',
  			controller: 'TypeModalController',
            animation : true,
            backdrop: false,
  			scope: $scope,
            resolve: {
                types: function() {

                }    
            }
  		});

  		uibmodalInstance.result.then(
  			function(response) {
  				notification("New type was added successfully", "success");
  				refreshTypes();
  			},
  			function(response) {
  				console.log(response);
  				if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                }
  			}
  		)
  	}


    $scope.update = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalUpdateType.html',
            controller: 'TypeModalController',
            animation : true,
            backdrop: false,
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
                notification("Type updated successfully", "success");
                refreshTypes();
            },
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                } else {
                    refreshTypes();
                }
            }
        )
        
    }


    $scope.delete = function(type) {
        var uibmodalInstance = $uibmodal.open({
            templateUrl: 'assets/html/modalDeleteType.html',
            controller: 'TypeModalController',
            animation : true,
            backdrop: false,
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
                notification("Type deleted successfully", "success");
                refreshTypes();
            },
            function(response) {
                if (response.localeCompare("cancel") != 0) {
                    notification("An error ocurred!", "danger");
                }
            }
        )
    }

    $scope.show = true;
  
    $scope.closeAlert = function(index) {
        $scope.show = false;
    };


}]);