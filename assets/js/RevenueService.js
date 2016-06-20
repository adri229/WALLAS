'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('RevenueService', ['$http', function($http){

	var revenueService = {};

	revenueService.create = function(revenue) {
		return $http.post('rest/revenues', {
			date: revenue.date,
			quantity: revenue.quantity,
			name: revenue.name
		}); 
	}

	revenueService.update = function(revenue, idRevenue) {
		return $http.put('rest/revenues/'.concat(idRevenue), {
			date: revenue.date,
			quantity: revenue.quantity,
			name: revenue.name
		});
	}

	revenueService.getByOwner = function(login,startDate,endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-' + startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
        var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-' + endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';
		
		return $http.get('rest/revenues/'.concat(login) + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
	}

	revenueService.getDataChartSpenRev = function(login,startDate,endDate) {
		return $http.get('rest/revenues/'.concat(login) + '?startDate=' + startDate + '&endDate=' + endDate);
	}

	revenueService.delete = function(id) {
		return $http.delete('rest/revenues/' + id);
	}

	return revenueService;

}]);

