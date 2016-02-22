'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('AuthenticationService',
	['$http','$cookies', '$rootScope','$timeout',
	function($http,$cookies, $rootScope,$timeOut){
		alert("hola");
		var serviceLogin =  {};

		serviceLogin.login = function(login, password, callback) {
			$http.post('/api/authenticate', {login: login, password: password}).
				success(function(response) {
					callback(response);
				});
		};

		serviceLogin.setCredentials = function (login, password) {
			var authdata = btoa(login + ":" + password);

			//Todos los controllers heredan estos datos
			$rootScope.globals = {
				currentUser: {
					login: login,
					authdata: authdata
				}
			};
			$http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
			$cookies.put('globals',$rootScope.globals);
		};

		serviceLogin.clearCredentials = function() {
			$rootScope.globals = {};
			$cookies.remove('globals');
			$http.defaults.headers.common['Authorization'] = 'Basic';
		};

		return serviceLogin;


}]);
