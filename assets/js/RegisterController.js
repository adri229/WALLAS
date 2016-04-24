'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('RegisterController', ['$scope','UserService', function ($scope, UserService) {
    $scope.register = function() {
      	UserService.create($scope.user).then(
       		function(response) {
       			alert("success");
       		},
       		function(response) {
       			alert("error");
       			console.log(response);
       		}
       	);
    };
}]);
