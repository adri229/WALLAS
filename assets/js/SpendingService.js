'use strict';

var wallas = angular.module('wallasApp');

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