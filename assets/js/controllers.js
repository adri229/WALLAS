'use strict';

var wallasControllers = angular.module('wallasController', []);

wallasControllers.controller('LoginController', 
['$scope', '$rootScope', '$location', 'AuthenticationService',
 function ($scope, $rootScope, $location, $AuthenticationService) {
        AuthenticationService.clearCredentials();
		
		$scope.login = function() {
			AuthenticationService.login($scope.credentials.login, $scope.credentials.password function(response){
				if (response.success) {
					AuthenticationService.setCredentials($scope.credentials.login,$scope.credentials.password);
					location.path('/');
				} else {
					$scope.error = response.message;
				} 
			});
		}
]);


wallasControllers.controller("RegisterController", ["$scope", function ($scope) {
        $scope.mensaje = "Texto cargado desde el controlador Pagina2Controller";
}]);
