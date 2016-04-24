'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('AuthenticationService',
	['$http','$cookies','$rootScope','$timeout', 
	function($http,$cookies, $rootScope,$timeout){
		
		var serviceLogin =  {};

		serviceLogin.login = function(credentials) {
			return $http.post('rest/users/login/'.concat(credentials.login), {login: credentials.login, password: credentials.password});
				
		};

		serviceLogin.setCredentials = function (credentials) {
			var authdata = btoa(credentials.login + ":" + credentials.password);

			//Todos los controllers heredan estos datos
			$rootScope.globals = {
				currentUser: {
					login: credentials.login,
					authdata: authdata
				}
			};

			$http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
			$cookies.putObject('globals',$rootScope.globals);
			
			var user = $cookies.getObject('globals');
			var username = user.currentUser.login;
			var password = user.currentUser.authdata;
			console.log(username);
			console.log(password);

		};

		serviceLogin.clearCredentials = function() {
			$rootScope.globals = {};
			$cookies.remove('globals');
			$http.defaults.headers.common['Authorization'] = 'Basic';
		};

		return serviceLogin;


}]);










