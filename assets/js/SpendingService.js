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


	spendingService.getByOwner = function(login,startDate,endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-'
		+ startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
        var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-'
        + endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';

		return $http.get('rest/spendings/'.concat(login) + '/crud' + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
	}

	spendingService.getDataChartSpenRev = function(login, startDate, endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-'
		+ startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
				var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-'
				+ endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';

		return $http.get('rest/spendings/'.concat(login) + '/chart' + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
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
