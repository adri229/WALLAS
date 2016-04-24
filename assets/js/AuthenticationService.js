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
			address: user.address,
			country: user.country
		}); 
	}

	userService.getByLogin = function(login) {
		return $http.get('rest/users/'.concat(login));
	}

	userService.delete = function(login) {
		return $http.delete('rest/users/'.concat(login));
	}


	userService.update = function(user) {
		return $http.put('rest/users/'.concat(login), {
			verifyPass: user.verifyPass,
			email: user.email,
			phone: user.phone,
			address: user.address,
			country: user.country
		});
	}

	return userService;

}]);


wallas.factory('RevenueService', ['$http', function($http){

	var revenueService = {};

	revenueService.create = function(revenue) {
		return $http.post('rest/revenues', {
			quantity: revenue.quantity,
			name: revenue.name
		}); 
	}

	revenueService.update = function(revenue) {
		console.log(revenue);
		return $http.put('rest/revenues/'.concat(revenue.idRevenue), {
			quantity: revenue.quantity,
			name: revenue.name
		});
	}

	revenueService.getByOwner = function(login) {
		return $http.get('rest/revenues/'.concat(login) + '?startDate=0000-00-00&endDate=2016-05-13');
	}


	revenueService.delete = function(id) {
		return $http.delete('rest/revenues/' + id);
	}

	return revenueService;

}]);



wallas.factory('TypeService', ['$http', function($http) {

	var typeService = {};

	typeService.create = function(type) {
		return $http.post('rest/types', {
			name: type.name
		});
	}

	typeService.update = function(type) {
		return $http.put('rest/types/'.concat(type.idType), {name: type.name});
	}


	typeService.getByOwner = function(login) {
		return $http.get('rest/types/'.concat(login));
	};

	typeService.delete = function(id) {
		return $http.delete('rest/types/' + id);
	};

	return typeService;

}]);

wallas.factory('StockService', ['$http', function($http) {

	var stockService = {};

	stockService.create = function(stock) {
		return $http.post('rest/stocks', {
			total: stock.total
		});
	}

	stockService.getByOwner = function(login) {
		return $http.get('rest/stocks/'.concat(login) + '?startDate=0000-00-00&endDate=2016-05-13');
	};

	stockService.update = function(stock) {
		return $http.put('rest/stocks/'.concat(stock.idStock), {total: stock.total});
	}

	stockService.delete = function(id) {
		return $http.delete('rest/stocks/' + id);
	};

	return stockService;

}]);


wallas.factory('SpendingService', ['$http', function($http) {

	var spendingService = {};

	spendingService.create = function(spending) {
		return $http.post('rest/spendings', {
			quantity: spending.quantity,
			name: spending.name
		});
	}


	spendingService.getByOwner = function(login) {
		return $http.get('rest/spendings/'.concat(login) + '?startDate=0000-00-00&endDate=2016-05-13');
	}


	spendingService.delete = function(idSpending) {
		return $http.delete('rest/spendings/'.concat(idSpending));
	}

	spendingService.update = function(spending) {
		return $http.put('rest/spendings/'.concat(spending.idSpending) + '/', {
			quantity: spending.quantity,
			name: spending.name,
			types: spending.types
		});
	}

	return spendingService;
}]);