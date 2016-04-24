'use strict';

var wallas = angular.module('wallasApp');


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