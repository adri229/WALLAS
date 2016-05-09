'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('SpendingService', ['$http', function($http) {

	var spendingService = {};

	spendingService.create = function(spending) {
		return $http.post('rest/spendings', {
			date: spending.date,
			quantity: spending.quantity,
			name: spending.name,
			types: spending.types
		});
	}


	spendingService.getByOwner = function(login) {
		return $http.get('rest/spendings/'.concat(login) + '?startDate=0000-00-00&endDate=2016-05-13');
	}


	spendingService.delete = function(idSpending) {
		return $http.delete('rest/spendings/'.concat(idSpending));
	}

	spendingService.update = function(spending,idSpending) {
		return $http.put('rest/spendings/'.concat(idSpending) + '/', {
			date: spending.date,
			quantity: spending.quantity,
			name: spending.name,
			types: spending.types
		});
	}

	return spendingService;
}]);