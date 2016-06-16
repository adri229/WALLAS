'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('PercentSpendingService', ['$http', function($http) {

	var percentSpendingService = {};

	percentSpendingService.getPercents = function(login, startDate, endDate) {
		return $http.get('rest/percents/'.concat(login) + '?startDate=' + startDate + '&endDate=' + endDate);
	};


	return percentSpendingService;

}]);