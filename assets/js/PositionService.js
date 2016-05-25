'use strict';

var wallas = angular.module('wallasApp');


wallas.factory('PositionService', ['$http', function($http) {

	var positionService = {};

	positionService.getPositions = function(login, startDate, endDate) {
		return $http.get('rest/positions/'.concat(login) + '?startDate=' + startDate + '&endDate=' + endDate);
	};

	

	return positionService;

}]);