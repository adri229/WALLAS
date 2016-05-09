'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('UserService', ['$http', function($http){

	var userService = {};

	userService.create = function(user) {
		return $http.post('rest/users', {
			login: user.login,
			passwd: user.passwd,
			verifyPass: user.verifyPass,
			fullname: user.fullname,
			email: user.email,
			phone: user.phone,
			country: user.country
		}); 
	}

	userService.getByLogin = function(login) {
		return $http.get('rest/users/'.concat(login));
	}

	userService.delete = function(login) {
		return $http.delete('rest/users/'.concat(login));
	}


	userService.update = function(login, user) {
		return $http.put('rest/users/'.concat(login).concat('/account'), {
			fullname: user.fullname,
			email: user.email,
			phone: user.phone,
			country: user.country
		});
	}

	userService.updatePass = function(login, user) {		
		return $http.put('rest/users/'.concat(login).concat('/password'), {
			passwd: user.passwd,
			verifyPass: user.verifyPass
		});
	}

	return userService;

}]);