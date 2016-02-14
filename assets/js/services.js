'use strict';

var wallasServices = angular.module('wallasServices',['ngResource']);

wallasServices.factory('AuthenticationService',
	['Base64','$http','$cookies','$rootScope','$timeOut']}
	function(Base64,$http,$cookies,$rootScope,$timeOut){
		var serviceLogin =  {};
		
		serviceLogin.login = function(login, password, callback) {
			$http.post('/api/authenticate', {login: login, password: password}).
				success(function(response) {
					callback(response);
				});
		};
		
		serviceLogin.setCredentials = function (login, password) {
			var authdata = Base64.encode(login + ":" + password);
			
			//Todos los controllers heredan estos datos
			$rootScope.globals = {
				currentUser: {
					login: login,
					authdata: authdata
				}
			};
			$http.defaults.header.common['Authorization'] = 'Basic ' + authdata;
			$cookies.put('globals',$rootScope.globals);
		};
		
		serviceLogin.clearCredentials = function() {
			$rootScope.globals = {};
			$cookies.remove('globals');
			$http.defaults.header.common['Authorization'] = 'Basic';
		};

		return service;	
				
); 