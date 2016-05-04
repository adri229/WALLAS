'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RegisterController', ['$scope','UserService', '$location', function ($scope, UserService, $location) {
    $scope.showHints = true;
    
    
    $scope.register = function() {
      	UserService.create($scope.user).then(
       		function(response) {
       			alert("success");
            $location.path('/login');
       		},
       		function(response) {
       			alert("error");
       			console.log(response);
       		}
       	);
    };
}]);
