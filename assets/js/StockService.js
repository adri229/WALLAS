'use strict';

var wallas = angular.module('wallasApp');


wallas.factory('StockService', ['$http', function($http) {

	var stockService = {};

	stockService.create = function(stock) {
         
		return $http.post('rest/stocks', {
			date: stock.date,
			total: stock.total
		});
	}

	stockService.getByOwner = function(login, startDate, endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-' + startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
        var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-' + endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';
                
		return $http.get('rest/stocks/'.concat(login) + '?startDate=' + 
			startDateUTC + '&endDate=' + endDateUTC);
	};

	stockService.update = function(stock, idStock) {
		return $http.put('rest/stocks/'.concat(idStock), {
			date: stock.date,
			total: stock.total
		});
	}

	stockService.delete = function(id) {
		return $http.delete('rest/stocks/' + id);
	};

	return stockService;

}]);