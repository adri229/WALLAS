'use strict';

var wallas = angular.module('wallasApp');


wallas.factory('PositionService', ['$http', function($http) {

	var positionService = {};

	positionService.getPositions = function(login, startDate, endDate) {
		var startDateUTC = startDate.getUTCFullYear() + '-' + (startDate.getUTCMonth() + 1)+ '-'
		+ startDate.getUTCDate()+'T'+startDate.getUTCHours()+':'+startDate.getUTCMinutes()+'Z';
				var endDateUTC  = endDate.getUTCFullYear() + '-' + (endDate.getUTCMonth() + 1)+ '-'
				+ endDate.getUTCDate()+'T'+endDate.getUTCHours()+':'+endDate.getUTCMinutes()+'Z';
		return $http.get('rest/positions/'.concat(login) + '?startDate=' + startDateUTC + '&endDate=' + endDateUTC);
	};



	return positionService;

}]);
