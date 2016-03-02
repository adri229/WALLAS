'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('AuthenticationService',
	['$http','$cookies', '$rootScope','$timeout',
	function($http,$cookies, $rootScope,$timeOut){
		
		var serviceLogin =  {};

		serviceLogin.login = function(login, password, callback) {
			alert(password);
			$http.post('wallas/rest/user', {login: login, password: password}).
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



/*
wallas.factory('AuthenticationService',
	['$http', '$rootScope','Session','AUTH_EVENTS',
	function($http, $rootScope, Session, AUTH_EVENTS) {
		
		var authService =  {};

		authService.login = function(login, password, callback) {
			alert(password);
			$http.post('wallas/rest/users/login'.login, {login: login, password: password}).
				success(function(response) {
					callback(response);
				});
		};

		authService.isAuthenticated() {
			return Session.user == null;
		}

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

		*/