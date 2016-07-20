'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('RevenueService', ['$http', function($http){

	var revenueService = {};

	revenueService.create = function(revenue) {
		return $http.post('rest/revenues', {
			date: revenue.date,
			quantity: revenue.quantity,
			name: revenue.name,
			types: revenue.types
		});
	}

	revenueService.update = function(revenue, idRevenue) {
		return $http.put('rest/revenues/'.concat(idRevenue), {
			date: revenue.date,
			quantity: revenue.quantity,
			name: revenue.name,
			types: revenue.types
		});
	}

	revenueService.getByOwner = function(login,startDate,endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-' + startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
        var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-' + endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';

		return $http.get('rest/revenues/'.concat(login) + '/crud' + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
	}

	revenueService.getDataChartSpenRev = function(login,startDate,endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth())+ '-'
		+ startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
				var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth())+ '-'
				+ endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';
		return $http.get('rest/revenues/'.concat(login) + '/chart' + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
	}

	revenueService.delete = function(idRevenue) {
		return $http.delete('rest/revenues/' + idRevenue);
	}

	return revenueService;

}]);
